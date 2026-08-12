<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL PAYMENTS
    | GET /api/payments
    |--------------------------------------------------------------------------
    */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'search'           => ['nullable', 'string', 'max:100'],
            'status'           => ['nullable', Rule::in(['pending', 'confirmed', 'cancelled', 'refunded'])],
            'payment_method'   => ['nullable', 'string', 'max:50'],
            'invoice_id'       => ['nullable', 'integer', 'exists:invoices,id'],
            'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
            'from_date'        => ['nullable', 'date'],
            'to_date'          => ['nullable', 'date', 'after_or_equal:from_date'],
            'per_page'         => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by'          => ['nullable', Rule::in(['id', 'payment_number', 'payment_date', 'amount', 'status', 'payment_method', 'created_at'])],
            'sort_direction'   => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter filter tidak valid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $filters = $validator->validated();

        $query = Payment::with([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name,email,phone',
            'invoice:id,invoice_number,total_amount,payment_status',
            'receiver:id,name,email',
        ])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('payment_number', 'LIKE', "%{$search}%")
                        ->orWhere('reference_number', 'LIKE', "%{$search}%")
                        ->orWhereHas('serviceOrder', function ($so) use ($search) {
                            $so->where('order_number', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($filters['status'] ?? null, fn($q, $status) =>
                $q->where('status', $status))
            ->when($filters['payment_method'] ?? null, fn($q, $method) =>
                $q->where('payment_method', $method))
            ->when($filters['invoice_id'] ?? null, fn($q, $id) =>
                $q->where('invoice_id', $id))
            ->when($filters['service_order_id'] ?? null, fn($q, $id) =>
                $q->where('service_order_id', $id))
            ->when($filters['from_date'] ?? null, fn($q, $date) =>
                $q->where('payment_date', '>=', $date))
            ->when($filters['to_date'] ?? null, fn($q, $date) =>
                $q->where('payment_date', '<=', $date))
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'desc');

        $payments = $query->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Data pembayaran berhasil diambil.',
            'data'    => $payments->items(),
            'meta'    => [
                'current_page' => $payments->currentPage(),
                'from'         => $payments->firstItem(),
                'last_page'    => $payments->lastPage(),
                'per_page'     => $payments->perPage(),
                'to'           => $payments->lastItem(),
                'total'        => $payments->total(),
            ],
            'links'   => [
                'first' => $payments->url(1),
                'last'  => $payments->url($payments->lastPage()),
                'prev'  => $payments->previousPageUrl(),
                'next'  => $payments->nextPageUrl(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PAYMENT
    | POST /api/payments
    |--------------------------------------------------------------------------
    */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_order_id' => ['required', 'integer', 'exists:service_orders,id'],
            'invoice_id'       => ['nullable', 'integer', 'exists:invoices,id'],
            'payment_number'   => ['required', 'string', 'max:50', 'unique:payments,payment_number'],
            'payment_date'     => ['required', 'date'],
            'payment_method'   => ['required', 'string', 'max:50', Rule::in(['cash', 'transfer', 'qris', 'debit', 'credit', 'other'])],
            'amount'           => ['required', 'numeric', 'min:0.01'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'status'           => ['nullable', Rule::in(['pending', 'confirmed', 'cancelled', 'refunded'])],
            'received_by'      => ['nullable', 'integer', 'exists:users,id'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ], [
            'payment_number.unique' => 'Nomor pembayaran sudah digunakan.',
            'payment_method.in'     => 'Metode pembayaran tidak valid. Pilih: cash, transfer, qris, debit, credit, other.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data           = $validator->validated();
        $data['status'] = $data['status'] ?? Payment::STATUS_CONFIRMED;

        $payment = DB::transaction(function () use ($data) {
            $payment = Payment::create($data);

            // Auto-update payment_status pada invoice jika terkait
            if ($payment->invoice_id && $payment->status === 'confirmed') {
                $this->syncInvoicePaymentStatus($payment->invoice_id);
            }

            return $payment;
        });

        $payment->load([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name,email,phone',
            'invoice:id,invoice_number,total_amount,payment_status',
            'receiver:id,name,email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dicatat.',
            'data'    => $payment,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PAYMENT DETAIL
    | GET /api/payments/{id}
    |--------------------------------------------------------------------------
    */
    public function show(Payment $payment): JsonResponse
    {
        $payment->load([
'serviceOrder:id,order_number,customer_id,order_status,total_amount',

            'serviceOrder.customer:id,name,email,phone,address',
            'invoice:id,invoice_number,invoice_date,due_date,total_amount,payment_status',
            'receiver:id,name,email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail pembayaran berhasil diambil.',
            'data'    => $payment,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT
    | PUT /api/payments/{id}
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Payment $payment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_number'   => ['sometimes', 'string', 'max:50', Rule::unique('payments', 'payment_number')->ignore($payment->id)],
            'payment_date'     => ['sometimes', 'date'],
            'payment_method'   => ['sometimes', 'string', 'max:50', Rule::in(['cash', 'transfer', 'qris', 'debit', 'credit', 'other'])],
            'amount'           => ['sometimes', 'numeric', 'min:0.01'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'status'           => ['sometimes', Rule::in(['pending', 'confirmed', 'cancelled', 'refunded'])],
            'received_by'      => ['nullable', 'integer', 'exists:users,id'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::transaction(function () use ($payment, $validator) {
            $payment->update($validator->validated());

            if ($payment->invoice_id) {
                $this->syncInvoicePaymentStatus($payment->invoice_id);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diperbarui.',
            'data'    => $payment->fresh()->load([
                'serviceOrder:id,order_number,customer_id',
                'serviceOrder.customer:id,name,email,phone',
                'invoice:id,invoice_number,total_amount,payment_status',
                'receiver:id,name,email',
            ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM PAYMENT
    | PATCH /api/payments/{id}/confirm
    |--------------------------------------------------------------------------
    */
    public function confirm(Request $request, Payment $payment): JsonResponse
    {
        if ($payment->status === Payment::STATUS_CONFIRMED) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah berstatus confirmed.',
            ], 409);
        }

        DB::transaction(function () use ($payment, $request) {
            $payment->update([
                'status'      => Payment::STATUS_CONFIRMED,
                'received_by' => $request->received_by ?? $payment->received_by,
            ]);

            if ($payment->invoice_id) {
                $this->syncInvoicePaymentStatus($payment->invoice_id);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi.',
            'data'    => [
                'id'             => $payment->id,
                'payment_number' => $payment->payment_number,
                'status'         => $payment->fresh()->status,
                'amount'         => $payment->amount,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL PAYMENT
    | PATCH /api/payments/{id}/cancel
    |--------------------------------------------------------------------------
    */
    public function cancel(Request $request, Payment $payment): JsonResponse
    {
        if (in_array($payment->status, [Payment::STATUS_CANCELLED, Payment::STATUS_REFUNDED], true)) {
            return response()->json([
                'success' => false,
                'message' => "Pembayaran sudah berstatus [{$payment->status}] dan tidak dapat dibatalkan.",
            ], 409);
        }

        DB::transaction(function () use ($payment, $request) {
            $payment->update([
                'status' => Payment::STATUS_CANCELLED,
                'notes'  => $request->reason
                    ? "[Dibatalkan] {$request->reason}"
                    : $payment->notes,
            ]);

            if ($payment->invoice_id) {
                $this->syncInvoicePaymentStatus($payment->invoice_id);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibatalkan.',
            'data'    => [
                'id'             => $payment->id,
                'payment_number' => $payment->payment_number,
                'status'         => $payment->fresh()->status,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE PAYMENT
    | DELETE /api/payments/{id}
    |--------------------------------------------------------------------------
    */
    public function destroy(Payment $payment): JsonResponse
    {
        if ($payment->status === Payment::STATUS_CONFIRMED) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran yang sudah dikonfirmasi tidak dapat dihapus. Gunakan cancel terlebih dahulu.',
            ], 409);
        }

        $invoiceId = $payment->invoice_id;

        DB::transaction(function () use ($payment, $invoiceId) {
            $payment->delete();

            if ($invoiceId) {
                $this->syncInvoicePaymentStatus($invoiceId);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus.',
            'data'    => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SUMMARY PER INVOICE
    | GET /api/payments/summary/{invoice_id}
    |--------------------------------------------------------------------------
    */
    public function summary(int $invoiceId): JsonResponse
    {
        $invoice = Invoice::with([
            'payments' => fn($q) => $q->orderBy('payment_date'),
            'serviceOrder:id,order_number',
        ])->findOrFail($invoiceId);

        $confirmed = $invoice->payments->where('status', 'confirmed');
        $pending   = $invoice->payments->where('status', 'pending');

        return response()->json([
            'success' => true,
            'message' => 'Ringkasan pembayaran berhasil diambil.',
            'data'    => [
                'invoice_id'     => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'order_number'   => $invoice->serviceOrder->order_number ?? null,
                'total_amount'   => (float) $invoice->total_amount,
                'payment_status' => $invoice->payment_status,
                'summary'        => [
                    'total_confirmed' => round((float) $confirmed->sum('amount'), 2),
                    'total_pending'   => round((float) $pending->sum('amount'), 2),
                    'remaining_due'   => round((float) $invoice->total_amount - $confirmed->sum('amount'), 2),
                    'payment_count'   => $invoice->payments->count(),
                ],
                'payments'       => $invoice->payments->values(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPER
    |--------------------------------------------------------------------------
    */
    private function syncInvoicePaymentStatus(int $invoiceId): void
    {
        $invoice = Invoice::with('payments')->find($invoiceId);

        if (! $invoice) {
            return;
        }

        $totalPaid = $invoice->payments
            ->where('status', 'confirmed')
            ->sum('amount');

        $status = match (true) {
            $totalPaid <= 0                              => Invoice::PAYMENT_STATUS_UNPAID,
            $totalPaid >= (float) $invoice->total_amount => Invoice::PAYMENT_STATUS_PAID,
            default                                      => Invoice::PAYMENT_STATUS_PARTIAL,
        };

        $invoice->update(['payment_status' => $status]);
    }
}
