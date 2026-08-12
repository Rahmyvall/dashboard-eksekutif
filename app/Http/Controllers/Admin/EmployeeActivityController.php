<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeActivityRequest;
use App\Http\Requests\UpdateEmployeeActivityRequest;
use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class EmployeeActivityController extends Controller
{
	public function index(Request $request): View
	{
		$search = trim((string) $request->input('search', ''));
		$status = $this->normalizeStatus($request->input('status'));
		$employeeId = $this->normalizeNullableInt($request->input('employee_id'));
		$serviceOrderId = $this->normalizeNullableInt($request->input('service_order_id'));
		$dateStart = $this->normalizeNullableString($request->input('date_start'));
		$dateEnd = $this->normalizeNullableString($request->input('date_end'));

		$employeeActivities = $this->filteredQuery(
			search: $search,
			status: $status,
			employeeId: $employeeId,
			serviceOrderId: $serviceOrderId,
			dateStart: $dateStart,
			dateEnd: $dateEnd,
		)
			->latestActivity()
			->paginate(10)
			->withQueryString();

		return view('super-admin.employee-activities.index', [
			'employeeActivities' => $employeeActivities,
			'employees'          => $this->employeeOptions(),
			'serviceOrders'      => $this->serviceOrderOptions(),
			'statuses'           => EmployeeActivity::availableStatuses(),
			'search'             => $search,
			'status'             => $status,
			'employeeId'         => $employeeId,
			'serviceOrderId'     => $serviceOrderId,
			'dateStart'          => $dateStart,
			'dateEnd'            => $dateEnd,
			'printMode'          => false,
		]);
	}

	public function printAll(Request $request): View
	{
		$search = trim((string) $request->input('search', ''));
		$status = $this->normalizeStatus($request->input('status'));
		$employeeId = $this->normalizeNullableInt($request->input('employee_id'));
		$serviceOrderId = $this->normalizeNullableInt($request->input('service_order_id'));
		$dateStart = $this->normalizeNullableString($request->input('date_start'));
		$dateEnd = $this->normalizeNullableString($request->input('date_end'));

		$employeeActivities = $this->filteredQuery(
			search: $search,
			status: $status,
			employeeId: $employeeId,
			serviceOrderId: $serviceOrderId,
			dateStart: $dateStart,
			dateEnd: $dateEnd,
		)
			->latestActivity()
			->get();

		return view('super-admin.employee-activities.index', [
			'employeeActivities' => $employeeActivities,
			'employees'          => $this->employeeOptions(),
			'serviceOrders'      => $this->serviceOrderOptions(),
			'statuses'           => EmployeeActivity::availableStatuses(),
			'search'             => $search,
			'status'             => $status,
			'employeeId'         => $employeeId,
			'serviceOrderId'     => $serviceOrderId,
			'dateStart'          => $dateStart,
			'dateEnd'            => $dateEnd,
			'printMode'          => true,
			'printedAt'          => now(),
		]);
	}

	public function create(): View
	{
		return view('super-admin.employee-activities.create', [
			'employeeActivity' => new EmployeeActivity(),
			'employees'        => $this->employeeOptions(),
			'serviceOrders'    => $this->serviceOrderOptions(),
			'statuses'         => EmployeeActivity::availableStatuses(),
		]);
	}

	public function store(StoreEmployeeActivityRequest $request): RedirectResponse
	{
		try {
			EmployeeActivity::query()->create($request->validated());

			return redirect()
				->route('super-admin.employee-activities.index')
				->with('success', 'Aktivitas pegawai berhasil ditambahkan.');
		} catch (Throwable $exception) {
			report($exception);

			return back()
				->withInput()
				->with('error', 'Aktivitas pegawai gagal ditambahkan.');
		}
	}

	public function show(EmployeeActivity $employeeActivity): View
	{
		$employeeActivity->loadMissing([
			'employee.user',
			'employee.department',
			'employee.position',
			'serviceOrder.customer',
			'verifier',
		]);

		return view('super-admin.employee-activities.show', [
			'employeeActivity' => $employeeActivity,
		]);
	}

	public function edit(EmployeeActivity $employeeActivity): View
	{
		$employeeActivity->loadMissing([
			'employee.user',
			'serviceOrder.customer',
			'verifier',
		]);

		return view('super-admin.employee-activities.edit', [
			'employeeActivity' => $employeeActivity,
			'employees'        => $this->employeeOptions(),
			'serviceOrders'    => $this->serviceOrderOptions(),
			'statuses'         => EmployeeActivity::availableStatuses(),
		]);
	}

	public function update(
		UpdateEmployeeActivityRequest $request,
		EmployeeActivity $employeeActivity
	): RedirectResponse {
		try {
			$employeeActivity->update($request->validated());

			return redirect()
				->route('super-admin.employee-activities.index')
				->with('success', 'Aktivitas pegawai berhasil diperbarui.');
		} catch (Throwable $exception) {
			report($exception);

			return back()
				->withInput()
				->with('error', 'Aktivitas pegawai gagal diperbarui.');
		}
	}

	public function destroy(EmployeeActivity $employeeActivity): RedirectResponse
	{
		try {
			$employeeActivity->delete();

			return redirect()
				->route('super-admin.employee-activities.index')
				->with('success', 'Aktivitas pegawai berhasil dihapus.');
		} catch (Throwable $exception) {
			report($exception);

			return back()->with(
				'error',
				'Aktivitas pegawai gagal dihapus.'
			);
		}
	}

	public function verify(EmployeeActivity $employeeActivity): RedirectResponse
	{
		try {
			$employeeActivity->verify(
				verifiedBy: (int) auth()->id(),
				status: EmployeeActivity::STATUS_VERIFIED,
			);

			return back()->with('success', 'Aktivitas pegawai berhasil diverifikasi.');
		} catch (Throwable $exception) {
			report($exception);

			return back()->with('error', 'Aktivitas pegawai gagal diverifikasi.');
		}
	}

	public function cancelVerification(EmployeeActivity $employeeActivity): RedirectResponse
	{
		try {
			$employeeActivity->cancelVerification();
			$employeeActivity->update([
				'status' => EmployeeActivity::STATUS_SUBMITTED,
			]);

			return back()->with('success', 'Verifikasi aktivitas pegawai berhasil dibatalkan.');
		} catch (Throwable $exception) {
			report($exception);

			return back()->with('error', 'Verifikasi aktivitas pegawai gagal dibatalkan.');
		}
	}

	private function filteredQuery(
		string $search,
		?string $status,
		?int $employeeId,
		?int $serviceOrderId,
		?string $dateStart,
		?string $dateEnd,
	): Builder {
		return EmployeeActivity::query()
			->with([
				'employee.user',
				'employee.department',
				'serviceOrder.customer',
				'verifier',
			])
			->when(
				$search !== '',
				function (Builder $query) use ($search): void {
					$pattern = '%' . mb_strtolower($search) . '%';

					$query->where(function (Builder $subQuery) use ($pattern): void {
						$subQuery
							->whereRaw('LOWER(activity_name) LIKE ?', [$pattern])
							->orWhereRaw("LOWER(COALESCE(description, '')) LIKE ?", [$pattern])
							->orWhereRaw("LOWER(COALESCE(unit, '')) LIKE ?", [$pattern])
							->orWhereHas('employee', function (Builder $employeeQuery) use ($pattern): void {
								$employeeQuery
									->whereRaw("LOWER(COALESCE(full_name, '')) LIKE ?", [$pattern])
									->orWhereRaw("LOWER(COALESCE(employee_number, '')) LIKE ?", [$pattern]);
							})
							->orWhereHas('serviceOrder', function (Builder $serviceOrderQuery) use ($pattern): void {
								$serviceOrderQuery
									->whereRaw("LOWER(COALESCE(order_number, '')) LIKE ?", [$pattern]);
							});
					});
				}
			)
			->when($status !== null, fn(Builder $query): Builder => $query->status($status))
			->when($employeeId !== null, fn(Builder $query): Builder => $query->employee($employeeId))
			->when($serviceOrderId !== null, fn(Builder $query): Builder => $query->serviceOrder($serviceOrderId))
			->when(
				$dateStart !== null && $dateEnd !== null,
				fn(Builder $query): Builder => $query->dateRange($dateStart, $dateEnd)
			)
			->when(
				$dateStart !== null && $dateEnd === null,
				fn(Builder $query): Builder => $query->whereDate('activity_date', '>=', $dateStart)
			)
			->when(
				$dateStart === null && $dateEnd !== null,
				fn(Builder $query): Builder => $query->whereDate('activity_date', '<=', $dateEnd)
			);
	}

	private function employeeOptions()
	{
		return Employee::query()
			->with(['department:id,name'])
			->orderBy('full_name')
			->get(['id', 'full_name', 'employee_number', 'department_id', 'status']);
	}

	private function serviceOrderOptions()
	{
		return ServiceOrder::query()
			->with(['customer:id,name,company_name'])
			->orderByDesc('id')
			->get(['id', 'order_number', 'customer_id', 'order_date', 'order_status']);
	}

	private function normalizeStatus(mixed $status): ?string
	{
		$normalized = is_string($status)
			? strtolower(trim($status))
			: '';

		return in_array($normalized, EmployeeActivity::availableStatuses(), true)
			? $normalized
			: null;
	}

	private function normalizeNullableInt(mixed $value): ?int
	{
		if ($value === null || $value === '') {
			return null;
		}

		return is_numeric($value)
			? (int) $value
			: null;
	}

	private function normalizeNullableString(mixed $value): ?string
	{
		if (! is_string($value)) {
			return null;
		}

		$normalized = trim($value);

		return $normalized === ''
			? null
			: $normalized;
	}
}
