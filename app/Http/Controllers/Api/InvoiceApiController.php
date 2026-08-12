<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvoiceApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL INVOICES
    | GET /api/invoices
    |--------------------------------------------------------------------------
    */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'search'         => ['nullable', 'string', 'max:100'],
            'payment_status' => ['nullable', Rule::in(['unpaid', 'partial', 'paid'])],
            'from_date'      => ['nullable', 'date'],
            'to_date'        => ['nullable', 'date', 'after_or_equal:from_date'],
            'per_page'       => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by'        => ['nullable', Rule::in(['id', 'invoice_number', 'invoice_date', 'due_date', 'total_amount', 'payment_status', 'created_at'])],
            'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter filter tidak valid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $filters = $validator->validated();

        $query = Invoice::with([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name,email,phone',
            'payments:id,invoice_id,payment_number,payment_date,amount,payment_method,status',
        ])
            ->when($filters['search'] ?? null, function ($q, $search) {
$q->where(function ($query) use ($search) {
    $query->where('invoice_number', 'LIKE', "%{$search}%")
        ->orWhereHas('serviceOrder', fn($so) =>
            $so->where('order_number', 'LIKE', "%{$search}%"));

});

            })
            ->when($filters['payment_status'] ?? null, fn($q, $status) =>
                $q->where('payment_status', $status))
            ->when($filters['from_date'] ?? null, fn($q, $date) =>
                $q->where('invoice_date', '>=', $date))
            ->when($filters['to_date'] ?? null, fn($q, $date) =>
                $q->where('invoice_date', '<=', $date))
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'desc');

        $invoices = $query->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Data invoice berhasil diambil.',
            'data'    => $invoices->items(),
            'meta'    => [
                'current_page' => $invoices->currentPage(),
                'from'         => $invoices->firstItem(),
                'last_page'    => $invoices->lastPage(),
                'per_page'     => $invoices->perPage(),
                'to'           => $invoices->lastItem(),
                'total'        => $invoices->total(),
            ],
            'links'   => [
                'first' => $invoices->url(1),
                'last'  => $invoices->url($invoices->lastPage()),
                'prev'  => $invoices->previousPageUrl(),
                'next'  => $invoices->nextPageUrl(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE INVOICE
    | POST /api/invoices
    |--------------------------------------------------------------------------
    */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_order_id' => ['required', 'integer', 'exists:service_orders,id', 'unique:invoices,service_order_id'],
            'invoice_number'   => ['required', 'string', 'max:50', 'unique:invoices,invoice_number'],
            'invoice_date'     => ['required', 'date'],
            'due_date'         => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'subtotal'         => ['required', 'numeric', 'min:0'],
            'discount'         => ['nullable', 'numeric', 'min:0'],
            'tax'              => ['nullable', 'numeric', 'min:0'],
            'total_amount'     => ['required', 'numeric', 'min:0'],
            'payment_status'   => ['nullable', Rule::in(['unpaid', 'partial', 'paid'])],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ], [
            'service_order_id.unique' => 'Service order ini sudah memiliki invoice.',
            'invoice_number.unique'   => 'Nomor invoice sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data                   = $validator->validated();
        $data['discount']       = $data['discount'] ?? 0;
        $data['tax']            = $data['tax'] ?? 0;
        $data['payment_status'] = $data['payment_status'] ?? Invoice::PAYMENT_STATUS_UNPAID;

        $invoice = DB::transaction(function () use ($data) {
            return Invoice::create($data);
        });

        $invoice->load([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name,email,phone',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dibuat.',
            'data'    => $invoice,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW INVOICE DETAIL
    | GET /api/invoices/{id}
    |--------------------------------------------------------------------------
    */
    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load([
'serviceOrder:id,order_number,customer_id,order_status,total_amount',

            'serviceOrder.customer:id,name,email,phone,address',
            'payments:id,invoice_id,payment_number,payment_date,amount,payment_method,reference_number,status,notes',
        ]);

        $totalPaid    = $invoice->payments->where('status', 'confirmed')->sum('amount');
        $totalPending = $invoice->payments->where('status', 'pending')->sum('amount');

        return response()->json([
            'success' => true,
            'message' => 'Detail invoice berhasil diambil.',
            'data'    => array_merge($invoice->toArray(), [
                'summary' => [
                    'total_paid'    => round((float) $totalPaid, 2),
                    'total_pending' => round((float) $totalPending, 2),
                    'remaining_due' => round((float) $invoice->total_amount - $totalPaid, 2),
                    'payment_count' => $invoice->payments->count(),
                ],
            ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE INVOICE
    | PUT /api/invoices/{id}
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Invoice $invoice): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => ['sometimes', 'string', 'max:50', Rule::unique('invoices', 'invoice_number')->ignore($invoice->id)],
            'invoice_date'   => ['sometimes', 'date'],
            'due_date'       => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'subtotal'       => ['sometimes', 'numeric', 'min:0'],
            'discount'       => ['nullable', 'numeric', 'min:0'],
            'tax'            => ['nullable', 'numeric', 'min:0'],
            'total_amount'   => ['sometimes', 'numeric', 'min:0'],
            'payment_status' => ['sometimes', Rule::in(['unpaid', 'partial', 'paid'])],
            'notes'          => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $invoice->update($validator->validated());

        $invoice->load([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name,email,phone',
            'payments:id,invoice_id,payment_number,payment_date,amount,payment_method,status',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil diperbarui.',
            'data'    => $invoice,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT STATUS
    | PATCH /api/invoices/{id}/payment-status
    |--------------------------------------------------------------------------
    */
    public function updatePaymentStatus(Request $request, Invoice $invoice): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => ['required', Rule::in(['unpaid', 'partial', 'paid'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $invoice->update(['payment_status' => $request->payment_status]);

        return response()->json([
            'success' => true,
            'message' => "Status pembayaran invoice diubah menjadi [{$request->payment_status}].",
            'data' => [
                'id'             => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'payment_status' => $invoice->payment_status,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE INVOICE
    | DELETE /api/invoices/{id}
    |--------------------------------------------------------------------------
    */
    public function destroy(Invoice $invoice): JsonResponse
    {
        if ($invoice->payments()->where('status', 'confirmed')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak dapat dihapus karena sudah memiliki pembayaran yang dikonfirmasi.',
            ], 409);
        }

        DB::transaction(function () use ($invoice) {
            $invoice->payments()->delete();
            $invoice->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dihapus.',
            'data'    => null,
        ]);
    }
}
