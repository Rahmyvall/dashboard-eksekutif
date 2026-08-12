<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServiceOrderController extends Controller
{
    private const STATUSES = [
        ServiceOrder::ORDER_STATUS_DRAFT,
        ServiceOrder::ORDER_STATUS_PENDING,
        ServiceOrder::ORDER_STATUS_PROCESSING,
        ServiceOrder::ORDER_STATUS_COMPLETED,
        ServiceOrder::ORDER_STATUS_CANCELLED,
    ];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'order_status' => ['nullable', Rule::in(self::STATUSES)],
            'payment_status' => ['nullable', Rule::in([
                ServiceOrder::PAYMENT_STATUS_UNPAID,
                ServiceOrder::PAYMENT_STATUS_PARTIAL,
                ServiceOrder::PAYMENT_STATUS_PAID,
            ])],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'per_page' => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $orders = ServiceOrder::query()
            ->with(['customer', 'creator'])
            ->search(trim((string) ($validated['search'] ?? '')))
            ->when(isset($validated['order_status']), fn ($query) => $query->status($validated['order_status']))
            ->when(isset($validated['payment_status']), fn ($query) => $query->where('payment_status', $validated['payment_status']))
            ->when(isset($validated['customer_id']), fn ($query) => $query->where('customer_id', $validated['customer_id']))
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.service-orders.index', [
            'orders' => $orders,
            'customers' => Customer::query()->active()->orderBy('name')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function create(): View
    {
        return view('super-admin.service-orders.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $order = DB::transaction(function () use ($validated): ServiceOrder {
            $order = ServiceOrder::create([
                'order_number' => $this->nextOrderNumber(),
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'scheduled_date' => $validated['scheduled_date'] ?? null,
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'order_status' => $validated['order_status'] ?? ServiceOrder::ORDER_STATUS_DRAFT,
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $this->syncItems($order, $validated['items'] ?? []);
            $order->load('items')->calculateTotal()->calculateRemaining()->save();

            return $order;
        });

        return redirect()->route('super-admin.service-orders.show', $order)
            ->with('success', 'Pesanan layanan berhasil dibuat.');
    }

    public function show(ServiceOrder $serviceOrder): View
    {
        $serviceOrder->load([
            'customer', 'creator', 'items.service', 'items.employee',
            'invoice', 'payments', 'statusHistories',
        ]);

        return view('super-admin.service-orders.show', compact('serviceOrder'));
    }

    public function edit(ServiceOrder $serviceOrder): View
    {
        $serviceOrder->load('items');

        return view('super-admin.service-orders.edit', array_merge(
            ['serviceOrder' => $serviceOrder],
            $this->formData()
        ));
    }

    public function update(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $validated = $request->validate($this->rules($serviceOrder));

        DB::transaction(function () use ($validated, $serviceOrder): void {
            $serviceOrder->update([
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'scheduled_date' => $validated['scheduled_date'] ?? null,
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncItems($serviceOrder, $validated['items'] ?? []);
            $serviceOrder->load('items')->calculateTotal()->calculateRemaining()->save();
        });

        return redirect()->route('super-admin.service-orders.show', $serviceOrder)
            ->with('success', 'Pesanan layanan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(self::STATUSES)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['order_status'] === $serviceOrder->order_status) {
            return back()->with('success', 'Status pesanan tidak berubah.');
        }

        DB::transaction(function () use ($validated, $serviceOrder): void {
            $previous = $serviceOrder->order_status;
            $serviceOrder->order_status = $validated['order_status'];
            if ($validated['order_status'] === ServiceOrder::ORDER_STATUS_COMPLETED) {
                $serviceOrder->completion_date = now()->toDateString();
            }
            $serviceOrder->save();

            $serviceOrder->statusHistories()->create([
                'previous_status' => $previous,
                'new_status' => $serviceOrder->order_status,
                'notes' => $validated['notes'] ?? null,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(ServiceOrder $serviceOrder): RedirectResponse
    {
        $serviceOrder->delete();

        return redirect()->route('super-admin.service-orders.index')
            ->with('success', 'Pesanan layanan berhasil dipindahkan ke recycle bin.');
    }

    private function formData(): array
    {
        return [
            'customers' => Customer::query()->active()->orderBy('name')->get(),
            'services' => Service::query()->active()->orderBy('name')->get(),
'employees' => Employee::query()->active()->orderBy('full_name')->get(),

            'statuses' => self::STATUSES,
        ];
    }

    private function rules(?ServiceOrder $order = null): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'scheduled_date' => ['nullable', 'date', 'after_or_equal:order_date'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'order_status' => [$order ? 'sometimes' : 'nullable', Rule::in(self::STATUSES)],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['nullable', 'array'],
            'items.*.service_id' => ['required', 'integer', 'exists:services,id'],
            'items.*.employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.start_date' => ['nullable', 'date'],
            'items.*.completion_date' => ['nullable', 'date', 'after_or_equal:items.*.start_date'],
            'items.*.status' => ['nullable', 'string', 'max:30'],
            'items.*.notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function syncItems(ServiceOrder $order, array $items): void
    {
        $order->items()->delete();

        foreach ($items as $item) {
            $service = Service::findOrFail($item['service_id']);
            $detail = $order->items()->create([
                'service_id' => $service->id,
                'employee_id' => $item['employee_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'] ?? $service->base_price,
                'discount' => $item['discount'] ?? 0,
                'start_date' => $item['start_date'] ?? null,
                'completion_date' => $item['completion_date'] ?? null,
                'status' => $item['status'] ?? 'pending',
                'notes' => $item['notes'] ?? null,
            ]);
            $detail->calculateSubtotal()->save();
        }
    }

    private function nextOrderNumber(): string
    {
        $last = ServiceOrder::withTrashed()->latest('id')->value('order_number');
        preg_match('/(\d+)$/', (string) $last, $matches);

        return 'SO-' . now()->format('Ym') . '-' . str_pad((string) (((int) ($matches[1] ?? 0)) + 1), 4, '0', STR_PAD_LEFT);
    }
}
