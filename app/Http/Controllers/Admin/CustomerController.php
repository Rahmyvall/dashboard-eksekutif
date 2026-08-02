<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar customer.
     */
    public function index(Request $request): View
    {
        $search       = trim((string) $request->query('search', ''));
        $status       = trim((string) $request->query('status', ''));
        $customerType = trim(
            (string) $request->query('customer_type', '')
        );

        $validStatuses = [
            Customer::STATUS_ACTIVE,
            Customer::STATUS_INACTIVE,
        ];

        $validCustomerTypes = [
            Customer::TYPE_INDIVIDUAL,
            Customer::TYPE_COMPANY,
        ];

        $customers = Customer::query()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where(
                                    'customer_code',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'name',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'company_name',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'address',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'tax_number',
                                    'ILIKE',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->when(
                in_array($status, $validStatuses, true),
                fn(Builder $query): Builder => $query->where(
                    'status',
                    $status
                )
            )
            ->when(
                in_array($customerType, $validCustomerTypes, true),
                fn(Builder $query): Builder => $query->where(
                    'customer_type',
                    $customerType
                )
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $customerTypeOptions = Customer::customerTypeOptions();
        $statusOptions       = Customer::statusOptions();

        return view(
            'super-admin.customers.index',
            compact(
                'customers',
                'customerTypeOptions',
                'statusOptions',
                'search',
                'status',
                'customerType'
            )
        );
    }

    /**
     * Menampilkan formulir tambah customer.
     */
    public function create(): View
    {
        $customerTypeOptions = Customer::customerTypeOptions();
        $statusOptions       = Customer::statusOptions();

        return view(
            'super-admin.customers.create',
            compact(
                'customerTypeOptions',
                'statusOptions'
            )
        );
    }

    /**
     * Menyimpan customer baru.
     */
    public function store(
        StoreCustomerRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            DB::transaction(
                function () use ($validated): void {
                    $customerType = (string) $validated['customer_type'];

                    Customer::query()->create([
                        'customer_code' => strtoupper(
                            trim((string) $validated['customer_code'])
                        ),

                        'customer_type' => $customerType,

                        'name'          => trim(
                            (string) $validated['name']
                        ),

                        'company_name'  => $customerType
                        === Customer::TYPE_COMPANY
                        && filled($validated['company_name'] ?? null)
                            ? trim(
                            (string) $validated['company_name']
                        )
                            : null,

                        'phone'         => filled($validated['phone'] ?? null)
                            ? trim((string) $validated['phone'])
                            : null,

                        'email'         => filled($validated['email'] ?? null)
                            ? strtolower(
                            trim((string) $validated['email'])
                        )
                            : null,

                        'address'       => filled($validated['address'] ?? null)
                            ? trim((string) $validated['address'])
                            : null,

                        'tax_number'    => filled(
                            $validated['tax_number'] ?? null
                        )
                            ? trim(
                            (string) $validated['tax_number']
                        )
                            : null,

                        'status'        => (string) $validated['status'],
                    ]);
                }
            );

            return redirect()
                ->route('super-admin.customers.index')
                ->with(
                    'success',
                    'Customer berhasil ditambahkan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Customer gagal ditambahkan. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menampilkan detail customer.
     */
    public function show(Customer $customer): View
    {
        return view(
            'super-admin.customers.show',
            compact('customer')
        );
    }

    /**
     * Menampilkan formulir edit customer.
     */
    public function edit(Customer $customer): View
    {
        $customerTypeOptions = Customer::customerTypeOptions();
        $statusOptions       = Customer::statusOptions();

        return view(
            'super-admin.customers.edit',
            compact(
                'customer',
                'customerTypeOptions',
                'statusOptions'
            )
        );
    }

    /**
     * Memperbarui customer.
     */
    public function update(
        UpdateCustomerRequest $request,
        Customer $customer
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            DB::transaction(
                function () use ($validated, $customer): void {
                    $customerType = (string) $validated['customer_type'];

                    $customer->update([
                        'customer_code' => strtoupper(
                            trim((string) $validated['customer_code'])
                        ),

                        'customer_type' => $customerType,

                        'name'          => trim(
                            (string) $validated['name']
                        ),

                        'company_name'  => $customerType
                        === Customer::TYPE_COMPANY
                        && filled($validated['company_name'] ?? null)
                            ? trim(
                            (string) $validated['company_name']
                        )
                            : null,

                        'phone'         => filled($validated['phone'] ?? null)
                            ? trim((string) $validated['phone'])
                            : null,

                        'email'         => filled($validated['email'] ?? null)
                            ? strtolower(
                            trim((string) $validated['email'])
                        )
                            : null,

                        'address'       => filled($validated['address'] ?? null)
                            ? trim((string) $validated['address'])
                            : null,

                        'tax_number'    => filled(
                            $validated['tax_number'] ?? null
                        )
                            ? trim(
                            (string) $validated['tax_number']
                        )
                            : null,

                        'status'        => (string) $validated['status'],
                    ]);
                }
            );

            return redirect()
                ->route(
                    'super-admin.customers.show',
                    $customer
                )
                ->with(
                    'success',
                    'Customer berhasil diperbarui.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Customer gagal diperbarui. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menghapus customer menggunakan soft delete.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($customer): void {
                    $customer->delete();
                }
            );

            return redirect()
                ->route('super-admin.customers.index')
                ->with(
                    'success',
                    'Customer berhasil dipindahkan ke sampah.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Customer gagal dihapus. Silakan coba kembali.'
            );
        }
    }

    /**
     * Menampilkan customer yang sudah dihapus.
     */
    public function trash(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $customerType = trim(
            (string) $request->query('customer_type', '')
        );

        $validCustomerTypes = [
            Customer::TYPE_INDIVIDUAL,
            Customer::TYPE_COMPANY,
        ];

        $customers = Customer::onlyTrashed()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where(
                                    'customer_code',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'name',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'company_name',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'address',
                                    'ILIKE',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'tax_number',
                                    'ILIKE',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->when(
                in_array($customerType, $validCustomerTypes, true),
                fn(Builder $query): Builder => $query->where(
                    'customer_type',
                    $customerType
                )
            )
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $customerTypeOptions = Customer::customerTypeOptions();

        return view(
            'super-admin.customers.trash',
            compact(
                'customers',
                'customerTypeOptions',
                'search',
                'customerType'
            )
        );
    }

    /**
     * Mengembalikan customer yang sudah dihapus.
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($id): void {
                    $customer = Customer::onlyTrashed()
                        ->findOrFail($id);

                    $customer->restore();
                }
            );

            return redirect()
                ->route('super-admin.customers.index')
                ->with(
                    'success',
                    'Customer berhasil dikembalikan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Customer gagal dikembalikan.'
            );
        }
    }

    /**
     * Menghapus customer secara permanen.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($id): void {
                    $customer = Customer::onlyTrashed()
                        ->findOrFail($id);

                    /*
                     * Jika customer masih digunakan oleh tabel transaksi,
                     * foreign key database dapat menolak force delete.
                     */
                    $customer->forceDelete();
                }
            );

            return redirect()
                ->route('super-admin.customers.trash')
                ->with(
                    'success',
                    'Customer berhasil dihapus secara permanen.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Customer gagal dihapus permanen karena masih digunakan oleh data lain.'
            );
        }
    }
}
