<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ServiceOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $expenses = Expense::query()
            ->with(['serviceOrder.customer', 'creator'])
            ->search(trim((string) ($validated['search'] ?? '')))
            ->category($validated['category'] ?? null)
            ->serviceOrder(isset($validated['service_order_id']) ? (int) $validated['service_order_id'] : null)
            ->dateRange($validated['start_date'] ?? null, $validated['end_date'] ?? null)
            ->latest('expense_date')
            ->latest('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.expenses.index', [
            'expenses' => $expenses,
            'categories' => Expense::query()->select('category')->distinct()->orderBy('category')->pluck('category'),
            'orders' => ServiceOrder::query()->select('id', 'order_number')->latest('id')->limit(100)->get(),
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

        return view('super-admin.expenses.create', [
            'expense' => new Expense(),
            'orders' => ServiceOrder::query()->with('customer')->latest('order_date')->limit(200)->get(),
            'selectedOrder' => $selectedOrder,
        ]);
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $expense = DB::transaction(function () use ($request, $validated): Expense {
            $payload = [
                'service_order_id' => $validated['service_order_id'] ?? null,
                'expense_date' => $validated['expense_date'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'created_by' => Auth::id(),
            ];

            $expense = Expense::query()->create($payload);

            if ($request->hasFile('attachment')) {
                $expense->attachment_path = $request->file('attachment')->store('expenses', 'public');
                $expense->save();
            }

            return $expense;
        });

        return redirect()->route('super-admin.expenses.show', $expense)
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function show(Expense $expense): View
    {
        $expense->load(['serviceOrder.customer', 'creator']);

        return view('super-admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        $expense->load(['serviceOrder.customer']);

        return view('super-admin.expenses.edit', [
            'expense' => $expense,
            'orders' => ServiceOrder::query()->with('customer')->latest('order_date')->limit(200)->get(),
        ]);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $expense): void {
            $expense->update([
                'service_order_id' => $validated['service_order_id'] ?? null,
                'expense_date' => $validated['expense_date'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
            ]);

            if ($request->boolean('remove_attachment') && $expense->attachment_path) {
                Storage::disk('public')->delete($expense->attachment_path);
                $expense->update(['attachment_path' => null]);
            }

            if ($request->hasFile('attachment')) {
                if ($expense->attachment_path) {
                    Storage::disk('public')->delete($expense->attachment_path);
                }

                $expense->update([
                    'attachment_path' => $request->file('attachment')->store('expenses', 'public'),
                ]);
            }
        });

        return redirect()->route('super-admin.expenses.show', $expense)
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        DB::transaction(function () use ($expense): void {
            if ($expense->attachment_path) {
                Storage::disk('public')->delete($expense->attachment_path);
            }

            $expense->delete();
        });

        return redirect()->route('super-admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
