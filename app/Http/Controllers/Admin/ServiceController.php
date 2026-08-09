<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'   => ['nullable', 'string', 'max:150'],
            'status'   => ['nullable', Rule::in(array_keys(Service::statuses()))],
            'category' => ['nullable', 'integer', 'exists:service_categories,id'],
            'sort'     => ['nullable', Rule::in(['latest', 'oldest', 'name_asc', 'name_desc', 'price_asc', 'price_desc'])],
            'per_page' => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $query  = Service::query()->with('category');
        $search = trim((string) ($validated['search'] ?? ''));

        $query->search($search)
            ->status($validated['status'] ?? null)
            ->when(
                isset($validated['category']),
                fn($query) => $query->where('service_category_id', $validated['category'])
            );

        match ($validated['sort'] ?? 'latest') {
            'oldest'     => $query->oldest(),
            'name_asc'   => $query->orderBy('name'),
            'name_desc'  => $query->orderByDesc('name'),
            'price_asc'  => $query->orderBy('base_price'),
            'price_desc' => $query->orderByDesc('base_price'),
            default      => $query->latest(),
        };

        $services = $query
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.services.index', [
            'services'   => $services,
            'statuses'   => Service::statuses(),
            'categories' => ServiceCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('super-admin.services.create', [
            'categories' => $this->activeCategories(),
            'statuses'   => Service::statuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'service_code' => Service::nextServiceCode(),
        ]);

        $this->normalizeInput($request);
        $validated = $request->validate($this->validationRules());

        DB::transaction(fn() => Service::create($validated));

        return redirect()
            ->route('super-admin.services.index')
            ->with('success', 'Service berhasil ditambahkan.');
    }

    public function show(Service $service): View
    {
        $service->load(['category', 'orderItems']);

        return view('super-admin.services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('super-admin.services.edit', [
            'service'    => $service,
            'categories' => $this->activeCategories(),
            'statuses'   => Service::statuses(),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->normalizeInput($request);
        $validated = $request->validate($this->validationRules($service));

        DB::transaction(fn() => $service->update($validated));

        return redirect()
            ->route('super-admin.services.index')
            ->with('success', 'Service berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        DB::transaction(fn() => $service->delete());

        return redirect()
            ->route('super-admin.services.index')
            ->with('success', 'Service berhasil dipindahkan ke recycle bin.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
$currentStatus = strtolower(trim((string) $service->getRawOriginal('status')));
$newStatus     = $currentStatus === Service::STATUS_ACTIVE
    ? Service::STATUS_INACTIVE

:Service::STATUS_ACTIVE;

$service->status = $newStatus;
$service->saveOrFail();


return back()->with(
    'success',
    'Status service berhasil diubah menjadi ' . ($newStatus === Service::STATUS_ACTIVE ? 'aktif.' : 'tidak aktif.')
);

    }

    private function validationRules(?Service $service = null): array
    {
        return [
            'service_category_id'        => ['required', 'integer', 'exists:service_categories,id'],
            'service_code'               => [
                'required',
                'string',
                'max:50',
                Rule::unique('services', 'service_code')->ignore($service?->id),
            ],
            'name'                       => ['required', 'string', 'max:150'],
            'description'                => ['nullable', 'string'],
            'base_price'                 => ['required', 'numeric', 'min:0'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:1'],
            'unit'                       => ['required', 'string', 'max:50'],
            'status'                     => ['required', Rule::in(array_keys(Service::statuses()))],
        ];
    }

    private function normalizeInput(Request $request): void
    {
        $request->merge([
            'service_code' => strtoupper(trim((string) $request->input('service_code'))),
            'name'         => trim((string) $request->input('name')),
            'unit'         => trim((string) $request->input('unit')),
            'status'       => strtolower(trim((string) $request->input('status'))),
        ]);
    }

    private function activeCategories()
    {
        return ServiceCategory::active()->orderBy('name')->get();
    }
}
