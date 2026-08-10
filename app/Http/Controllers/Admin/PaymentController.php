<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ServiceOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    private const METHODS = ['cash', 'bank_transfer', 'credit_card', 'debit_card', 'qris', 'other'];

    private const STATUSES = [
        Payment::STATUS_PENDING,
        Payment::STATUS_CONFIRMED,
        Payment::STATUS_CANCELLED,
        Payment::STATUS_REFUNDED,
    ];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'         => ['nullable', 'string', 'max:150'],
            'payment_method' => ['nullable', Rule::in(self::METHODS)],
            'status'         => ['nullable', Rule::in(self::STATUSES)],
            'per_page'       => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $payments = Payment::query()
            ->with(['serviceOrder.customer', 'invoice', 'receiver'])
            ->search(trim((string) ($validated['search'] ?? '')))
            ->when(isset($validated['payment_method']), fn($query) => $query->method($validated['payment_method']))
            ->when(isset($validated['status']), fn($query) => $query->status($validated['status']))
            ->latest('payment_date')
            ->latest('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.payments.index', [
            'payments' => $payments,
            'methods'  => self::METHODS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        $selectedOrder = null;
        if ($request->filled('service_order_id')) {
            $selectedOrder = ServiceOrder::with(['customer', 'invoice'])->findOrFail(
                $request->integer('service_order_id')
            );
        }

        return view('super-admin.payments.create', [
            'orders'        => ServiceOrder::with('customer')->latest('order_date')->get(),
            'invoices'      => Invoice::with('serviceOrder.customer')->latest('invoice_date')->get(),
            'selectedOrder' => $selectedOrder,
            'methods'       => self::METHODS,
            'statuses'      => self::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $payment = DB::transaction(function () use ($request, $validated): Payment {
            $order = ServiceOrder::findOrFail($validated['service_order_id']);
            $this->ensureInvoiceBelongsToOrder($validated['invoice_id'] ?? null, $order);
            $status  = $validated['status'] ?? Payment::STATUS_CONFIRMED;
            $invoice = filled($validated['invoice_id'] ?? null)
                ? Invoice::find((int) $validated['invoice_id'])
                : null;
            $this->ensureAmountAvailable($order, (float) $validated['amount'], null, $status, $invoice);

            $payment = Payment::create([
                'service_order_id' => $order->id,
                'invoice_id'       => $validated['invoice_id'] ?? null,
                'payment_number'   => Payment::generatePaymentNumber(),
                'payment_date'     => $validated['payment_date'],
                'payment_method'   => $validated['payment_method'],
                'amount'           => $validated['amount'],
                'reference_number' => Payment::generateReferenceNumber(),
                'status'           => $status,
                'received_by'      => Auth::id(),
                'notes'            => $validated['notes'] ?? null,
            ]);

            if ($request->hasFile('proof_of_payment')) {
                $payment->proof_of_payment_path = $request->file('proof_of_payment')->store('payments', 'public');
                $payment->save();
            }

            $payment->updateServiceOrderPayment();

            return $payment;
        });

        return redirect()->route('super-admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Payment $payment): View
    {
        $payment->load(['serviceOrder.customer', 'invoice', 'receiver']);

        return view('super-admin.payments.show', compact('payment'));
    }

    public function print(Payment $payment): View
    {
        $payment->load(['serviceOrder.customer', 'invoice', 'receiver']);

        return view('super-admin.payments.print', compact('payment'));
    }

    public function captureProof(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'image_data' => ['required', 'string'],
        ]);

        if (! preg_match('/^data:image\/(jpeg|png);base64,(.+)$/', $validated['image_data'], $matches)) {
            throw ValidationException::withMessages([
                'image_data' => 'Format foto kamera tidak valid.',
            ]);
        }

        $image = base64_decode($matches[2], true);
        if ($image === false || strlen($image) > 5 * 1024 * 1024 || @getimagesizefromstring($image) === false) {
            throw ValidationException::withMessages([
                'image_data' => 'Foto kamera tidak valid atau melebihi ukuran 5 MB.',
            ]);
        }

        DB::transaction(function () use ($payment, $image): void {
            if ($payment->proof_of_payment_path) {
                Storage::disk('public')->delete($payment->proof_of_payment_path);
            }

            $path = 'payments/' . $payment->payment_number . '-camera-' . now()->format('YmdHis') . '.jpg';
            Storage::disk('public')->put($path, $image);
            $payment->update(['proof_of_payment_path' => $path]);
        });

        return back()->with('success', 'Foto bukti pembayaran berhasil disimpan.');
    }

    public function edit(Payment $payment): View
    {
        $payment->load(['serviceOrder.customer', 'invoice']);

        return view('super-admin.payments.edit', [
            'payment'  => $payment,
            'invoices' => Invoice::with('serviceOrder.customer')->latest('invoice_date')->get(),
            'methods'  => self::METHODS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate($this->rules($payment));

        DB::transaction(function () use ($request, $validated, $payment): void {
            $order = ServiceOrder::findOrFail($validated['service_order_id']);
            $this->ensureInvoiceBelongsToOrder($validated['invoice_id'] ?? null, $order);
            $invoice = filled($validated['invoice_id'] ?? null)
                ? Invoice::find((int) $validated['invoice_id'])
                : null;
            $this->ensureAmountAvailable($order, (float) $validated['amount'], $payment, $validated['status'] ?? $payment->status, $invoice);

            $payment->update([
                'service_order_id' => $order->id,
                'invoice_id'       => $validated['invoice_id'] ?? null,
                'reference_number' => $payment->reference_number ?: Payment::generateReferenceNumber(),
                'payment_date'     => $validated['payment_date'],
                'payment_method'   => $validated['payment_method'],
                'amount'           => $validated['amount'],
                'status'           => $validated['status'] ?? $payment->status,
                'notes'            => $validated['notes'] ?? null,
            ]);

            if ($request->hasFile('proof_of_payment')) {
                if ($payment->proof_of_payment_path) {
                    Storage::disk('public')->delete($payment->proof_of_payment_path);
                }
                $payment->proof_of_payment_path = $request->file('proof_of_payment')->store('payments', 'public');
                $payment->save();
            }

            $payment->updateServiceOrderPayment();
        });

        return redirect()->route('super-admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'notes'  => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($validated, $payment): void {
            $payment->update([
                'status' => $validated['status'],
                'notes'  => $validated['notes'] ?? $payment->notes,
            ]);
            $payment->updateServiceOrderPayment();
        });

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment): void {
            $order = $payment->serviceOrder;
            $proof = $payment->proof_of_payment_path;
            $payment->delete();
            if ($proof) {
                Storage::disk('public')->delete($proof);
            }
            if ($order) {
                $payment->updateServiceOrderPayment();
            }
        });

        return redirect()->route('super-admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    private function rules(?Payment $payment = null): array
    {
        return [
            'service_order_id' => ['required', 'integer', 'exists:service_orders,id'],
            'invoice_id'       => ['nullable', 'integer', 'exists:invoices,id'],
            'payment_date'     => ['required', 'date'],
            'payment_method'   => ['required', Rule::in(self::METHODS)],
            'amount'           => ['required', 'numeric', 'gt:0'],
            'proof_of_payment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'status'           => ['nullable', Rule::in(self::STATUSES)],
            'notes'            => ['nullable', 'string', 'max:5000'],
        ];
    }

    private function ensureInvoiceBelongsToOrder(int | string | null $invoiceId, ServiceOrder $order): void
    {
        $invoiceId = filled($invoiceId) ? (int) $invoiceId : null;

        if ($invoiceId !== null && ! Invoice::whereKey($invoiceId)->where('service_order_id', $order->id)->exists()) {
            throw ValidationException::withMessages([
                'invoice_id' => 'Invoice yang dipilih tidak sesuai dengan service order.',
            ]);
        }
    }

    private function ensureAmountAvailable(
        ServiceOrder $order,
        float $amount,
        ?Payment $current,
        string $status,
        ?Invoice $invoice = null
    ): void {
        if ($status !== Payment::STATUS_CONFIRMED) {
            return;
        }

        $payments  = $invoice?->payments() ?? $order->payments();
        $confirmed = (float) $payments
            ->where('status', Payment::STATUS_CONFIRMED)
            ->when($current, fn($query) => $query->where('id', '!=', $current->id))
            ->sum('amount');
        $total = $invoice !== null
            ? (float) $invoice->total_amount
            : (float) $order->total_amount;

        $remaining = max(0.0, $total - $confirmed);

        if ($amount > $remaining + 0.01) {
            throw ValidationException::withMessages([
                'amount' => sprintf(
                    'Jumlah pembayaran melebihi sisa tagihan. Maksimal yang dapat dibayar: Rp %s.',
                    number_format($remaining, 2, ',', '.')
                ),
            ]);
        }
    }
}
