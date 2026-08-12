<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LookupController extends Controller
{
	public function employeeActivityFilters(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'search' => ['nullable', 'string', 'max:100'],
			'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
			'active_only' => ['nullable', 'boolean'],
		]);

		$search = trim((string) ($validated['search'] ?? ''));
		$limit = (int) ($validated['limit'] ?? 30);
		$activeOnly = array_key_exists('active_only', $validated)
			? filter_var($validated['active_only'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== false
			: true;

		return response()->json([
			'success' => true,
			'message' => 'Lookup aktivitas pegawai berhasil diambil.',
			'data' => [
				'statuses' => collect(EmployeeActivity::availableStatuses())
					->map(fn(string $status): array => [
						'value' => $status,
						'label' => $this->statusLabel($status),
					])
					->values(),
				'employees' => $this->employeeItems($search, $limit, $activeOnly),
				'service_orders' => $this->serviceOrderItems($search, $limit),
			],
		]);
	}

	public function employees(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'search' => ['nullable', 'string', 'max:100'],
			'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
			'active_only' => ['nullable', 'boolean'],
		]);

		$search = trim((string) ($validated['search'] ?? ''));
		$limit = (int) ($validated['limit'] ?? 50);
		$activeOnly = array_key_exists('active_only', $validated)
			? filter_var($validated['active_only'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== false
			: true;

		return response()->json([
			'success' => true,
			'message' => 'Lookup pegawai berhasil diambil.',
			'data' => $this->employeeItems($search, $limit, $activeOnly),
		]);
	}

	public function serviceOrders(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'search' => ['nullable', 'string', 'max:100'],
			'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
		]);

		$search = trim((string) ($validated['search'] ?? ''));
		$limit = (int) ($validated['limit'] ?? 50);

		return response()->json([
			'success' => true,
			'message' => 'Lookup service order berhasil diambil.',
			'data' => $this->serviceOrderItems($search, $limit),
		]);
	}

	public function employeeActivityStatuses(): JsonResponse
	{
		$statuses = collect(EmployeeActivity::availableStatuses())
			->map(fn(string $status): array => [
				'value' => $status,
				'label' => $this->statusLabel($status),
			])
			->values();

		return response()->json([
			'success' => true,
			'message' => 'Lookup status aktivitas pegawai berhasil diambil.',
			'data' => $statuses,
		]);
	}

	private function employeeItems(string $search, int $limit, bool $activeOnly)
	{
		return Employee::query()
			->select(['id', 'full_name', 'employee_number', 'department_id', 'status'])
			->with(['department:id,name'])
			->when($activeOnly, fn(Builder $query): Builder => $query->where('status', 'active'))
			->when(
				$search !== '',
				function (Builder $query) use ($search): void {
					$keyword = '%' . mb_strtolower($search) . '%';
					$query->where(function (Builder $subQuery) use ($keyword): void {
						$subQuery
							->whereRaw("LOWER(COALESCE(full_name, '')) LIKE ?", [$keyword])
							->orWhereRaw("LOWER(COALESCE(employee_number, '')) LIKE ?", [$keyword]);
					});
				}
			)
			->orderBy('full_name')
			->limit($limit)
			->get()
			->map(function (Employee $employee): array {
				return [
					'id' => $employee->id,
					'employee_number' => $employee->employee_number,
					'full_name' => $employee->full_name,
					'department' => $employee->department?->name,
					'status' => $employee->status,
					'label' => trim((string) $employee->full_name . ' (' . ($employee->employee_number ?? '-') . ')'),
				];
			})
			->values();
	}

	private function serviceOrderItems(string $search, int $limit)
	{
		return ServiceOrder::query()
			->select(['id', 'order_number', 'customer_id', 'order_status', 'order_date'])
			->with(['customer:id,name,company_name'])
			->when(
				$search !== '',
				function (Builder $query) use ($search): void {
					$keyword = '%' . mb_strtolower($search) . '%';
					$query->where(function (Builder $subQuery) use ($keyword): void {
						$subQuery
							->whereRaw("LOWER(COALESCE(order_number, '')) LIKE ?", [$keyword])
							->orWhereHas('customer', function (Builder $customerQuery) use ($keyword): void {
								$customerQuery
									->whereRaw("LOWER(COALESCE(name, '')) LIKE ?", [$keyword])
									->orWhereRaw("LOWER(COALESCE(company_name, '')) LIKE ?", [$keyword]);
							});
					});
				}
			)
			->orderByDesc('id')
			->limit($limit)
			->get()
			->map(function (ServiceOrder $serviceOrder): array {
				$customerName = $serviceOrder->customer?->company_name
					?: $serviceOrder->customer?->name;

				return [
					'id' => $serviceOrder->id,
					'order_number' => $serviceOrder->order_number,
					'order_date' => $serviceOrder->order_date?->format('Y-m-d'),
					'order_status' => $serviceOrder->order_status,
					'customer' => $customerName,
					'label' => trim((string) $serviceOrder->order_number . ' - ' . ($customerName ?? '-')),
				];
			})
			->values();
	}

	private function statusLabel(string $status): string
	{
		return match ($status) {
			EmployeeActivity::STATUS_SUBMITTED => 'Submitted',
			EmployeeActivity::STATUS_VERIFIED => 'Verified',
			EmployeeActivity::STATUS_REJECTED => 'Rejected',
			default => ucfirst(str_replace('_', ' ', $status)),
		};
	}
}
