<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori layanan.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'   => [
                'nullable',
                'string',
                'max:150',
            ],
            'status'   => [
                'nullable',
                Rule::in([
                    ServiceCategory::STATUS_ACTIVE,
                    ServiceCategory::STATUS_INACTIVE,
                ]),
            ],
            'sort'     => [
                'nullable',
                Rule::in([
                    'latest',
                    'oldest',
                    'name_asc',
                    'name_desc',
                    'code_asc',
                    'code_desc',
                ]),
            ],
            'per_page' => [
                'nullable',
                'integer',
                Rule::in([10, 25, 50, 100]),
            ],
        ]);

        $search  = trim((string) ($validated['search'] ?? ''));
        $status  = $validated['status'] ?? null;
        $sort    = $validated['sort'] ?? 'latest';
        $perPage = (int) ($validated['per_page'] ?? 10);

        $query = ServiceCategory::query();

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        */

        $query->when(
            $search !== '',
            function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Filter status
        |--------------------------------------------------------------------------
        */

        $query->when(
            filled($status),
            fn($query) => $query->where('status', $status)
        );

        /*
        |--------------------------------------------------------------------------
        | Pengurutan
        |--------------------------------------------------------------------------
        */

        match ($sort) {
            'oldest'    => $query->orderBy('created_at', 'asc'),
            'name_asc'  => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'code_asc'  => $query->orderBy('code', 'asc'),
            'code_desc' => $query->orderBy('code', 'desc'),
            default     => $query->orderBy('created_at', 'desc'),
        };

        $serviceCategories = $query
            ->paginate($perPage)
            ->withQueryString();

        $statuses = ServiceCategory::statuses();

        return view('super-admin.service-categories.index', [
            'serviceCategories' => $serviceCategories,
            'statuses'          => $statuses,
            'monitoringStats'   => $this->getMonitoringStats(),
        ]);
    }

    /**
     * Ringkasan monitoring produktivitas karyawan dan transaksi jasa.
     *
     * @return array<string, int|float>
     */
    private function getMonitoringStats(): array
    {
        $now = now();

        $stats = [
            'employees_total'               => 0,
            'employees_active'              => 0,
            'activities_today'              => 0,
            'activities_pending_verify'     => 0,
            'service_orders_this_month'     => 0,
            'service_orders_processing'     => 0,
            'invoices_unpaid'               => 0,
            'payments_pending'              => 0,
            'payments_confirmed_this_month' => 0.0,
            'service_revenue_this_month'    => 0.0,
        ];

        if (Schema::hasTable('employees')) {
            $stats['employees_total'] = Employee::query()->count();
            $stats['employees_active'] = Employee::query()
                ->where('status', 'active')
                ->count();
        }

        if (Schema::hasTable('employee_activities')) {
            $stats['activities_today'] = EmployeeActivity::query()
                ->whereDate('activity_date', $now->toDateString())
                ->count();

            $stats['activities_pending_verify'] = EmployeeActivity::query()
                ->pendingVerification()
                ->count();
        }

        if (Schema::hasTable('service_orders')) {
            $stats['service_orders_this_month'] = ServiceOrder::query()
                ->whereYear('order_date', $now->year)
                ->whereMonth('order_date', $now->month)
                ->count();

            $stats['service_orders_processing'] = ServiceOrder::query()
                ->where('order_status', ServiceOrder::ORDER_STATUS_PROCESSING)
                ->count();
        }

        if (Schema::hasTable('invoices')) {
            $stats['invoices_unpaid'] = Invoice::query()
                ->where('payment_status', Invoice::PAYMENT_STATUS_UNPAID)
                ->count();

            $stats['service_revenue_this_month'] = (float) Invoice::query()
                ->whereYear('invoice_date', $now->year)
                ->whereMonth('invoice_date', $now->month)
                ->sum('total_amount');
        }

        if (Schema::hasTable('payments')) {
            $stats['payments_pending'] = Payment::query()
                ->where('status', Payment::STATUS_PENDING)
                ->count();

            $stats['payments_confirmed_this_month'] = (float) Payment::query()
                ->where('status', Payment::STATUS_CONFIRMED)
                ->whereYear('payment_date', $now->year)
                ->whereMonth('payment_date', $now->month)
                ->sum('amount');
        }

        return $stats;
    }

    /**
     * Menampilkan form tambah kategori layanan.
     */
    public function create(): View
    {
        return view('super-admin.service-categories.create', [
            'statuses' => ServiceCategory::statuses(),
        ]);
    }

    /**
     * Menyimpan kategori layanan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        DB::transaction(function () use ($validated): void {
            ServiceCategory::create([
                'code'        => $validated['code'],
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'status'      => $validated['status'],
            ]);
        });

        return redirect()
            ->route('super-admin.service-categories.index')
            ->with(
                'success',
                'Kategori layanan berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail kategori layanan.
     */
    public function show(ServiceCategory $serviceCategory): View
    {
        return view('super-admin.service-categories.show', [
            'serviceCategory' => $serviceCategory,
        ]);
    }

    /**
     * Menampilkan form edit kategori layanan.
     */
    public function edit(ServiceCategory $serviceCategory): View
    {
        return view('super-admin.service-categories.edit', [
            'serviceCategory' => $serviceCategory,
            'statuses'        => ServiceCategory::statuses(),
        ]);
    }

    /**
     * Memperbarui kategori layanan.
     */
    public function update(
        Request $request,
        ServiceCategory $serviceCategory
    ): RedirectResponse {
        $this->normalizeInput($request);

        $validated = $request->validate(
            $this->validationRules($serviceCategory),
            $this->validationMessages()
        );

        DB::transaction(
            function () use ($serviceCategory, $validated): void {
                $serviceCategory->update([
                    'code'        => $validated['code'],
                    'name'        => $validated['name'],
                    'description' => $validated['description'] ?? null,
                    'status'      => $validated['status'],
                ]);
            }
        );

        return redirect()
            ->route('super-admin.service-categories.index')
            ->with(
                'success',
                'Kategori layanan berhasil diperbarui.'
            );
    }

    /**
     * Menghapus kategori layanan dengan soft delete.
     */
    public function destroy(
        ServiceCategory $serviceCategory
    ): RedirectResponse {
        DB::transaction(function () use ($serviceCategory): void {
            $serviceCategory->delete();
        });

        return redirect()
            ->route('super-admin.service-categories.index')
            ->with(
                'success',
                'Kategori layanan berhasil dipindahkan ke recycle bin.'
            );
    }

    /**
     * Mengubah status kategori aktif atau tidak aktif.
     */
    public function toggleStatus(
        ServiceCategory $serviceCategory
    ): RedirectResponse {
        $newStatus = $serviceCategory->status
        === ServiceCategory::STATUS_ACTIVE
            ? ServiceCategory::STATUS_INACTIVE
            : ServiceCategory::STATUS_ACTIVE;

        DB::transaction(
            function () use ($serviceCategory, $newStatus): void {
                $serviceCategory->update([
                    'status' => $newStatus,
                ]);
            }
        );

        $statusLabel = ServiceCategory::statuses()[$newStatus] ?? ucfirst($newStatus);

        return back()->with(
            'success',
            "Status kategori berhasil diubah menjadi {$statusLabel}."
        );
    }

    /**
     * Menampilkan kategori yang telah dihapus.
     */
    public function trashed(Request $request): View
    {
        $validated = $request->validate([
            'search'   => [
                'nullable',
                'string',
                'max:150',
            ],
            'per_page' => [
                'nullable',
                'integer',
                Rule::in([10, 25, 50, 100]),
            ],
        ]);

        $search  = trim((string) ($validated['search'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? 10);

        $query = ServiceCategory::onlyTrashed();

        $query->when(
            $search !== '',
            function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }
        );

        $serviceCategories = $query
            ->orderByDesc('deleted_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('super-admin.service-categories.trashed', [
            'serviceCategories' => $serviceCategories,
        ]);
    }

    /**
     * Mengembalikan kategori dari recycle bin.
     */
    public function restore(int $id): RedirectResponse
    {
        $serviceCategory = ServiceCategory::onlyTrashed()
            ->findOrFail($id);

        $codeAlreadyExists = ServiceCategory::query()
            ->where('code', $serviceCategory->code)
            ->exists();

        if ($codeAlreadyExists) {
            return redirect()
                ->route('super-admin.service-categories.trashed')
                ->with(
                    'error',
                    "Kategori tidak dapat dikembalikan karena kode "
                    . "{$serviceCategory->code} sudah digunakan."
                );
        }

        DB::transaction(function () use ($serviceCategory): void {
            $serviceCategory->restore();
        });

        return redirect()
            ->route('super-admin.service-categories.trashed')
            ->with(
                'success',
                'Kategori layanan berhasil dikembalikan.'
            );
    }

    /**
     * Menghapus kategori secara permanen.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $serviceCategory = ServiceCategory::onlyTrashed()
            ->findOrFail($id);

        DB::transaction(function () use ($serviceCategory): void {
            $serviceCategory->forceDelete();
        });

        return redirect()
            ->route('super-admin.service-categories.trashed')
            ->with(
                'success',
                'Kategori layanan berhasil dihapus secara permanen.'
            );
    }

    /**
     * Aturan validasi tambah dan edit.
     */
    private function validationRules(
        ?ServiceCategory $serviceCategory = null
    ): array {
        $uniqueCodeRule = Rule::unique(
            'service_categories',
            'code'
        );

        if ($serviceCategory !== null) {
            $uniqueCodeRule->ignore(
                $serviceCategory->getKey()
            );
        }

        return [
            'code'        => [
                'required',
                'string',
                'max:30',
                $uniqueCodeRule,
            ],
            'name'        => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status'      => [
                'required',
                'string',
                Rule::in([
                    ServiceCategory::STATUS_ACTIVE,
                    ServiceCategory::STATUS_INACTIVE,
                ]),
            ],
        ];
    }

    /**
     * Pesan validasi.
     */
    private function validationMessages(): array
    {
        return [
            'code.required'      => 'Kode kategori wajib diisi.',
            'code.string'        => 'Kode kategori harus berupa teks.',
            'code.max'           => 'Kode kategori maksimal 30 karakter.',
            'code.unique'        => 'Kode kategori sudah digunakan.',

            'name.required'      => 'Nama kategori wajib diisi.',
            'name.string'        => 'Nama kategori harus berupa teks.',
            'name.max'           => 'Nama kategori maksimal 150 karakter.',

            'description.string' => 'Deskripsi harus berupa teks.',

            'status.required'    => 'Status kategori wajib dipilih.',
            'status.string'      => 'Status kategori harus berupa teks.',
            'status.in'          => 'Status kategori yang dipilih tidak valid.',
        ];
    }

    /**
     * Membersihkan input sebelum divalidasi.
     */
    private function normalizeInput(Request $request): void
    {
        $code        = trim((string) $request->input('code'));
        $name        = trim((string) $request->input('name'));
        $description = trim(
            (string) $request->input('description')
        );
        $status = strtolower(
            trim((string) $request->input('status'))
        );

        $request->merge([
            'code'        => strtoupper($code),
            'name'        => $name,
            'description' => $description !== ''
                ? $description
                : null,
            'status'      => $status,
        ]);
    }
}