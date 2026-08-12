<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    private const PAYMENT_STATUSES = [
        Invoice::PAYMENT_STATUS_UNPAID,
        Invoice::PAYMENT_STATUS_PARTIAL,
        Invoice::PAYMENT_STATUS_PAID,
    ];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'         => ['nullable', 'string', 'max:150'],
            'payment_status' => ['nullable', Rule::in(self::PAYMENT_STATUSES)],
            'per_page'       => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $invoices = Invoice::query()
            ->with(['serviceOrder.customer', 'payments'])
            ->search(trim((string) ($validated['search'] ?? '')))
            ->when(
                isset($validated['payment_status']),
                fn($query) => $query->paymentStatus($validated['payment_status'])
            )
            ->latest('invoice_date')
            ->latest('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.invoices.index', [
            'invoices' => $invoices,
            'statuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        $selectedOrder = null;
        if ($request->filled('service_order_id')) {
            $selectedOrder = ServiceOrder::query()
                ->with('customer')
                ->findOrFail($request->integer('service_order_id'));
        }

        return view('super-admin.invoices.create', [
            'orders'        => ServiceOrder::query()
                ->with('customer')
                ->whereDoesntHave('invoice')
                ->latest('order_date')
                ->get(),
            'selectedOrder' => $selectedOrder,
            'statuses'      => self::PAYMENT_STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $invoice = DB::transaction(function () use ($validated): Invoice {
            $order = ServiceOrder::query()
                ->with('items')
                ->findOrFail($validated['service_order_id']);

            if ($order->invoice()->exists()) {
                abort(422, 'Service order tersebut sudah memiliki invoice.');
            }

            $invoice = Invoice::create([
                'service_order_id' => $order->id,
                'invoice_number'   => $this->nextInvoiceNumber(),
                'invoice_date'     => $validated['invoice_date'],
                'due_date'         => $validated['due_date'] ?? null,
                'subtotal'         => $order->subtotal,
                'discount'         => $order->discount,
                'tax'              => $order->tax,
                'total_amount'     => 0,
                'payment_status'   => Invoice::PAYMENT_STATUS_UNPAID,
                'notes'            => $validated['notes'] ?? null,
            ]);

            $invoice->calculateTotal()->save();

            return $invoice;
        });

        return redirect()->route('super-admin.invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'serviceOrder.customer',
            'serviceOrder.items.service',
            'payments.receiver',
        ]);

        return view('super-admin.invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice): View
    {
        $invoice->load([
            'serviceOrder.customer',
            'serviceOrder.items.service',
            'payments.receiver',
        ]);

        return view('super-admin.invoices.print', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('serviceOrder.customer');

        return view('super-admin.invoices.edit', [
            'invoice'  => $invoice,
            'statuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate($this->rules($invoice));

        DB::transaction(function () use ($validated, $invoice): void {
            $invoice->update([
                'invoice_date' => $validated['invoice_date'],
                'due_date'     => $validated['due_date'] ?? null,
                'discount'     => $validated['discount'] ?? $invoice->discount,
                'tax'          => $validated['tax'] ?? $invoice->tax,
                'notes'        => $validated['notes'] ?? null,
            ]);

            $invoice->calculateTotal()->updatePaymentStatus()->save();
        });

        return redirect()->route('super-admin.invoices.show', $invoice)
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    public function refreshPaymentStatus(Invoice $invoice): RedirectResponse
    {
        $invoice->updatePaymentStatus()->save();

        return back()->with('success', 'Status pembayaran invoice berhasil disegarkan.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->payments()->exists()) {
            return back()->with('error', 'Invoice yang sudah memiliki pembayaran tidak dapat dihapus.');
        }

        $invoice->delete();

        return redirect()->route('super-admin.invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    private function rules(?Invoice $invoice = null): array
    {
        return [
            'service_order_id' => [
                'required', 'integer', 'exists:service_orders,id',
                Rule::unique('invoices', 'service_order_id')->ignore($invoice?->id),
            ],
            'invoice_date'     => ['required', 'date'],
            'due_date'         => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'discount'         => ['nullable', 'numeric', 'min:0'],
            'tax'              => ['nullable', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:5000'],
        ];
    }

    private function nextInvoiceNumber(): string
    {
        $last = Invoice::query()->latest('id')->value('invoice_number');
        preg_match('/(\d+)$/', (string) $last, $matches);

        return 'INV-' . now()->format('Ym') . '-' . str_pad(
            (string) (((int) ($matches[1] ?? 0)) + 1),
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}
