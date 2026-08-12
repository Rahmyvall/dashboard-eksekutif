<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeActivityRequest;
use App\Http\Requests\UpdateEmployeeActivityRequest;
use App\Http\Resources\EmployeeActivityResource;
use App\Models\EmployeeActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class EmployeeActivityController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$validated = $request->validate([
			'search' => ['nullable', 'string', 'max:150'],
			'status' => ['nullable', 'string', Rule::in(EmployeeActivity::availableStatuses())],
			'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
			'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
			'date_start' => ['nullable', 'date'],
			'date_end' => ['nullable', 'date'],
			'verified' => ['nullable', 'boolean'],
			'sort_by' => [
				'nullable',
				'string',
				Rule::in(['id', 'activity_date', 'activity_name', 'duration_minutes', 'status', 'created_at', 'updated_at']),
			],
			'sort_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
			'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
		]);

		$search = trim((string) ($validated['search'] ?? ''));
		$status = $validated['status'] ?? null;
		$employeeId = $validated['employee_id'] ?? null;
		$serviceOrderId = $validated['service_order_id'] ?? null;
		$dateStart = $validated['date_start'] ?? null;
		$dateEnd = $validated['date_end'] ?? null;
		$verified = array_key_exists('verified', $validated)
			? filter_var($validated['verified'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
			: null;
		$sortBy = $validated['sort_by'] ?? 'activity_date';
		$sortDirection = $validated['sort_direction'] ?? 'desc';
		$perPage = (int) ($validated['per_page'] ?? 15);

		$query = EmployeeActivity::query()
			->with([
				'employee.department',
				'serviceOrder.customer',
				'verifier',
			])
			->when(
				$search !== '',
				fn(Builder $builder): Builder => $this->applySearch($builder, $search)
			)
			->when($status !== null, fn(Builder $builder): Builder => $builder->status((string) $status))
			->when($employeeId !== null, fn(Builder $builder): Builder => $builder->employee((int) $employeeId))
			->when($serviceOrderId !== null, fn(Builder $builder): Builder => $builder->serviceOrder((int) $serviceOrderId))
			->when(
				$dateStart !== null && $dateEnd !== null,
				fn(Builder $builder): Builder => $builder->dateRange((string) $dateStart, (string) $dateEnd)
			)
			->when(
				$dateStart !== null && $dateEnd === null,
				fn(Builder $builder): Builder => $builder->whereDate('activity_date', '>=', (string) $dateStart)
			)
			->when(
				$dateStart === null && $dateEnd !== null,
				fn(Builder $builder): Builder => $builder->whereDate('activity_date', '<=', (string) $dateEnd)
			)
			->when(
				$verified === true,
				fn(Builder $builder): Builder => $builder->verified()
			)
			->when(
				$verified === false,
				fn(Builder $builder): Builder => $builder->unverified()
			);

		if ($sortBy === 'activity_date') {
			$query
				->orderBy('activity_date', $sortDirection)
				->orderBy('start_time', $sortDirection)
				->orderBy('id', $sortDirection);
		} else {
			$query->orderBy($sortBy, $sortDirection)->orderByDesc('id');
		}

		$employeeActivities = $query->paginate($perPage)->withQueryString();

		return EmployeeActivityResource::collection($employeeActivities)
			->additional([
				'success' => true,
				'message' => 'Daftar aktivitas pegawai berhasil diambil.',
				'filters' => [
					'search' => $search !== '' ? $search : null,
					'status' => $status,
					'employee_id' => $employeeId,
					'service_order_id' => $serviceOrderId,
					'date_start' => $dateStart,
					'date_end' => $dateEnd,
					'verified' => $verified,
					'sort_by' => $sortBy,
					'sort_direction' => $sortDirection,
					'per_page' => $perPage,
				],
			]);
	}

	public function store(StoreEmployeeActivityRequest $request): JsonResponse
	{
		try {
			$employeeActivity = DB::transaction(function () use ($request): EmployeeActivity {
				return EmployeeActivity::query()->create($request->validated());
			});

			$employeeActivity->loadMissing([
				'employee.department',
				'serviceOrder.customer',
				'verifier',
			]);

			return (new EmployeeActivityResource($employeeActivity))
				->additional([
					'success' => true,
					'message' => 'Aktivitas pegawai berhasil ditambahkan.',
				])
				->response()
				->setStatusCode(201);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Aktivitas pegawai gagal ditambahkan.',
			], 500);
		}
	}

	public function show(EmployeeActivity $employeeActivity): EmployeeActivityResource
	{
		$employeeActivity->loadMissing([
			'employee.department',
			'serviceOrder.customer',
			'verifier',
		]);

		return (new EmployeeActivityResource($employeeActivity))
			->additional([
				'success' => true,
				'message' => 'Detail aktivitas pegawai berhasil diambil.',
			]);
	}

	public function update(
		UpdateEmployeeActivityRequest $request,
		EmployeeActivity $employeeActivity
	): JsonResponse {
		try {
			DB::transaction(function () use ($request, $employeeActivity): void {
				$employeeActivity->update($request->validated());
			});

			$employeeActivity->refresh()->loadMissing([
				'employee.department',
				'serviceOrder.customer',
				'verifier',
			]);

			return (new EmployeeActivityResource($employeeActivity))
				->additional([
					'success' => true,
					'message' => 'Aktivitas pegawai berhasil diperbarui.',
				])
				->response()
				->setStatusCode(200);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Aktivitas pegawai gagal diperbarui.',
			], 500);
		}
	}

	public function destroy(EmployeeActivity $employeeActivity): JsonResponse
	{
		try {
			DB::transaction(function () use ($employeeActivity): void {
				$employeeActivity->delete();
			});

			return response()->json([
				'success' => true,
				'message' => 'Aktivitas pegawai berhasil dihapus.',
				'data' => [
					'id' => $employeeActivity->getKey(),
				],
			]);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Aktivitas pegawai gagal dihapus.',
			], 500);
		}
	}

	public function verify(Request $request, EmployeeActivity $employeeActivity): JsonResponse
	{
		$validated = $request->validate([
			'status' => ['required', 'string', Rule::in([
				'verify',
				'reject',
				'pending',
				EmployeeActivity::STATUS_VERIFIED,
				EmployeeActivity::STATUS_REJECTED,
				EmployeeActivity::STATUS_SUBMITTED,
			])],
			'verified_by' => ['nullable', 'integer', 'exists:users,id'],
			'verified_at' => ['nullable', 'date'],
		]);

		$targetStatus = $this->normalizeVerificationStatus((string) $validated['status']);

		$requiresVerifier = in_array(
			$targetStatus,
			[
				EmployeeActivity::STATUS_VERIFIED,
				EmployeeActivity::STATUS_REJECTED,
			],
			true
		);

		$verifiedBy = $validated['verified_by'] ?? auth()->id();
		if ($requiresVerifier && $verifiedBy === null) {
			return response()->json([
				'success' => false,
				'message' => 'User verifikator wajib diisi.',
				'errors' => [
					'verified_by' => ['User verifikator wajib diisi.'],
				],
			], 422);
		}

		try {
			DB::transaction(function () use ($employeeActivity, $validated, $verifiedBy, $targetStatus, $requiresVerifier): void {
				$employeeActivity->update([
					'status' => $targetStatus,
					'verified_by' => $requiresVerifier ? (int) $verifiedBy : null,
					'verified_at' => $requiresVerifier
						? (isset($validated['verified_at'])
							? Carbon::parse((string) $validated['verified_at'])
							: now())
						: null,
				]);
			});

			$employeeActivity->refresh()->loadMissing([
				'employee.department',
				'serviceOrder.customer',
				'verifier',
			]);

			return (new EmployeeActivityResource($employeeActivity))
				->additional([
					'success' => true,
					'message' => 'Aktivitas pegawai berhasil diverifikasi.',
				])
				->response()
				->setStatusCode(200);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Aktivitas pegawai gagal diverifikasi.',
			], 500);
		}
	}

	public function cancelVerification(EmployeeActivity $employeeActivity): JsonResponse
	{
		try {
			DB::transaction(function () use ($employeeActivity): void {
				$employeeActivity->update([
					'status' => EmployeeActivity::STATUS_SUBMITTED,
					'verified_by' => null,
					'verified_at' => null,
				]);
			});

			$employeeActivity->refresh()->loadMissing([
				'employee.department',
				'serviceOrder.customer',
				'verifier',
			]);

			return (new EmployeeActivityResource($employeeActivity))
				->additional([
					'success' => true,
					'message' => 'Verifikasi aktivitas pegawai berhasil dibatalkan.',
				])
				->response()
				->setStatusCode(200);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Verifikasi aktivitas pegawai gagal dibatalkan.',
			], 500);
		}
	}

	private function normalizeVerificationStatus(string $status): string
	{
		$normalized = strtolower(trim($status));

		return match ($normalized) {
			'verify' => EmployeeActivity::STATUS_VERIFIED,
			'reject' => EmployeeActivity::STATUS_REJECTED,
			'pending' => EmployeeActivity::STATUS_SUBMITTED,
			default => $normalized,
		};
	}

	private function applySearch(Builder $query, string $search): Builder
	{
		$pattern = '%' . mb_strtolower($search) . '%';

		return $query->where(function (Builder $subQuery) use ($pattern): void {
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
						->whereRaw("LOWER(COALESCE(order_number, '')) LIKE ?", [$pattern])
						->orWhereHas('customer', function (Builder $customerQuery) use ($pattern): void {
							$customerQuery
								->whereRaw("LOWER(COALESCE(name, '')) LIKE ?", [$pattern])
								->orWhereRaw("LOWER(COALESCE(company_name, '')) LIKE ?", [$pattern]);
						});
				});
		});
	}
}
