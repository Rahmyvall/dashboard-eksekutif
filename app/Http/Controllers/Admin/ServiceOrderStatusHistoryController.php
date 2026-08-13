<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceOrderStatusHistoryRequest;
use App\Http\Requests\UpdateServiceOrderStatusHistoryRequest;
use App\Http\Requests\UpdateServiceOrderStatusRequest;
use App\Http\Resources\ServiceOrderResource;
use App\Http\Resources\ServiceOrderStatusHistoryResource;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatusHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ServiceOrderStatusHistoryController extends Controller
{
	/** @var array<int, string> */
	private const STATUSES = [
		ServiceOrder::ORDER_STATUS_DRAFT,
		ServiceOrder::ORDER_STATUS_PENDING,
		ServiceOrder::ORDER_STATUS_PROCESSING,
		ServiceOrder::ORDER_STATUS_COMPLETED,
		ServiceOrder::ORDER_STATUS_CANCELLED,
	];

	public function index(Request $request): AnonymousResourceCollection
	{
		$validated = $request->validate([
			'search' => ['nullable', 'string', 'max:150'],
			'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
			'new_status' => [
				'nullable',
				Rule::in(self::STATUSES),
			],
			'changed_by' => ['nullable', 'integer', 'exists:users,id'],
			'changed_from' => ['nullable', 'date'],
			'changed_to' => ['nullable', 'date', 'after_or_equal:changed_from'],
			'sort_by' => ['nullable', Rule::in(['id', 'changed_at', 'new_status', 'created_at'])],
			'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
			'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
		]);

		$search = trim((string) ($validated['search'] ?? ''));
		$sortBy = (string) ($validated['sort_by'] ?? 'changed_at');
		$sortDirection = (string) ($validated['sort_direction'] ?? 'desc');
		$perPage = (int) ($validated['per_page'] ?? 15);

		$histories = ServiceOrderStatusHistory::query()
			->with([
				'serviceOrder.customer',
				'changedBy',
			])
			->when(
				$search !== '',
				fn(Builder $builder): Builder => $this->applySearch($builder, $search)
			)
			->when(
				isset($validated['service_order_id']),
				fn(Builder $builder): Builder => $builder->where('service_order_id', (int) $validated['service_order_id'])
			)
			->when(
				isset($validated['new_status']),
				fn(Builder $builder): Builder => $builder->where('new_status', (string) $validated['new_status'])
			)
			->when(
				isset($validated['changed_by']),
				fn(Builder $builder): Builder => $builder->where('changed_by', (int) $validated['changed_by'])
			)
			->when(
				isset($validated['changed_from']),
				fn(Builder $builder): Builder => $builder->whereDate('changed_at', '>=', (string) $validated['changed_from'])
			)
			->when(
				isset($validated['changed_to']),
				fn(Builder $builder): Builder => $builder->whereDate('changed_at', '<=', (string) $validated['changed_to'])
			)
			->orderBy($sortBy, $sortDirection)
			->orderByDesc('id')
			->paginate($perPage)
			->withQueryString();

		return ServiceOrderStatusHistoryResource::collection($histories)
			->additional([
				'success' => true,
				'message' => 'Riwayat status service order berhasil diambil.',
				'filters' => [
					'search' => $search !== '' ? $search : null,
					'service_order_id' => $validated['service_order_id'] ?? null,
					'new_status' => $validated['new_status'] ?? null,
					'changed_by' => $validated['changed_by'] ?? null,
					'changed_from' => $validated['changed_from'] ?? null,
					'changed_to' => $validated['changed_to'] ?? null,
					'sort_by' => $sortBy,
					'sort_direction' => $sortDirection,
					'per_page' => $perPage,
				],
			]);
	}

	public function webIndex(Request $request): View
	{
		$validated = $request->validate([
			'search' => ['nullable', 'string', 'max:150'],
			'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
			'new_status' => ['nullable', Rule::in(self::STATUSES)],
			'changed_by' => ['nullable', 'integer', 'exists:users,id'],
			'changed_from' => ['nullable', 'date'],
			'changed_to' => ['nullable', 'date', 'after_or_equal:changed_from'],
			'per_page' => ['nullable', 'integer', Rule::in([10, 15, 25, 50, 100])],
		]);

		$search = trim((string) ($validated['search'] ?? ''));
		$perPage = (int) ($validated['per_page'] ?? 15);

		$histories = ServiceOrderStatusHistory::query()
			->with([
				'serviceOrder.customer',
				'changedBy',
			])
			->when(
				$search !== '',
				fn(Builder $builder): Builder => $this->applySearch($builder, $search)
			)
			->when(
				isset($validated['service_order_id']),
				fn(Builder $builder): Builder => $builder->where('service_order_id', (int) $validated['service_order_id'])
			)
			->when(
				isset($validated['new_status']),
				fn(Builder $builder): Builder => $builder->where('new_status', (string) $validated['new_status'])
			)
			->when(
				isset($validated['changed_by']),
				fn(Builder $builder): Builder => $builder->where('changed_by', (int) $validated['changed_by'])
			)
			->when(
				isset($validated['changed_from']),
				fn(Builder $builder): Builder => $builder->whereDate('changed_at', '>=', (string) $validated['changed_from'])
			)
			->when(
				isset($validated['changed_to']),
				fn(Builder $builder): Builder => $builder->whereDate('changed_at', '<=', (string) $validated['changed_to'])
			)
			->orderByDesc('changed_at')
			->orderByDesc('id')
			->paginate($perPage)
			->withQueryString();

		return view('super-admin.service_order_status_histories.index', [
			'histories' => $histories,
			'statuses' => self::STATUSES,
		]);
	}

	public function store(StoreServiceOrderStatusHistoryRequest $request): JsonResponse
	{
		try {
			$validated = $request->validated();

			$history = DB::transaction(function () use ($validated): ServiceOrderStatusHistory {
				$serviceOrder = ServiceOrder::query()->findOrFail((int) $validated['service_order_id']);

				$newStatus = (string) $validated['new_status'];
				$previousStatus = $validated['previous_status'] ?? $serviceOrder->order_status;

				$serviceOrder->order_status = $newStatus;
				$this->syncCompletionDate($serviceOrder, $newStatus);
				$serviceOrder->save();

				$history = $serviceOrder->statusHistories()->create([
					'previous_status' => $previousStatus,
					'new_status' => $newStatus,
					'notes' => $validated['notes'] ?? null,
					'changed_by' => $validated['changed_by'] ?? auth()->id(),
					'changed_at' => $validated['changed_at'] ?? now(),
				]);

				return $history;
			});

			$history->loadMissing([
				'serviceOrder.customer',
				'changedBy',
			]);

			return response()->json([
				'success' => true,
				'message' => 'Riwayat status service order berhasil ditambahkan.',
				'data' => (new ServiceOrderStatusHistoryResource($history))->resolve($request),
			], 201);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Riwayat status service order gagal ditambahkan.',
			], 500);
		}
	}

	public function show(ServiceOrderStatusHistory $serviceOrderStatusHistory): ServiceOrderStatusHistoryResource
	{
		$serviceOrderStatusHistory->loadMissing([
			'serviceOrder.customer',
			'changedBy',
		]);

		return (new ServiceOrderStatusHistoryResource($serviceOrderStatusHistory))
			->additional([
				'success' => true,
				'message' => 'Detail riwayat status service order berhasil diambil.',
			]);
	}

	public function webShow(ServiceOrderStatusHistory $serviceOrderStatusHistory): View
	{
		$serviceOrderStatusHistory->loadMissing([
			'serviceOrder.customer',
			'changedBy',
		]);

		return view('super-admin.service_order_status_histories.show', [
			'history' => $serviceOrderStatusHistory,
			'serviceOrderStatusHistory' => $serviceOrderStatusHistory,
		]);
	}

	public function webEdit(ServiceOrderStatusHistory $serviceOrderStatusHistory): View
	{
		$serviceOrderStatusHistory->loadMissing([
			'serviceOrder.customer',
			'changedBy',
		]);

		return view('super-admin.service_order_status_histories.edit', [
			'history' => $serviceOrderStatusHistory,
			'serviceOrderStatusHistory' => $serviceOrderStatusHistory,
		]);
	}

	public function updateStatus(
		UpdateServiceOrderStatusRequest $request,
		ServiceOrder $serviceOrder
	): JsonResponse {
		try {
			$validated = $request->validated();

			$history = DB::transaction(function () use ($serviceOrder, $validated): ServiceOrderStatusHistory {
				$previousStatus = (string) $serviceOrder->order_status;
				$nextStatus = (string) $validated['order_status'];

				$serviceOrder->order_status = $nextStatus;

				if ($nextStatus === ServiceOrder::ORDER_STATUS_COMPLETED) {
					$serviceOrder->completion_date = now()->toDateString();
				} elseif ($previousStatus === ServiceOrder::ORDER_STATUS_COMPLETED) {
					$serviceOrder->completion_date = null;
				}

				$serviceOrder->save();

				return $serviceOrder->statusHistories()->create([
					'previous_status' => $previousStatus,
					'new_status' => $nextStatus,
					'notes' => $validated['notes'] ?? null,
					'changed_by' => auth()->id(),
					'changed_at' => now(),
				]);
			});

			$serviceOrder->refresh()->loadMissing([
				'customer',
				'creator',
				'items',
				'invoice',
				'payments',
				'statusHistories.changedBy',
			]);

			$history->loadMissing([
				'serviceOrder.customer',
				'changedBy',
			]);

			return response()->json([
				'success' => true,
				'message' => 'Status service order berhasil diperbarui.',
				'data' => [
					'service_order' => (new ServiceOrderResource($serviceOrder))->resolve($request),
					'status_history' => (new ServiceOrderStatusHistoryResource($history))->resolve($request),
				],
			]);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Status service order gagal diperbarui.',
			], 500);
		}
	}

	public function update(
		UpdateServiceOrderStatusHistoryRequest $request,
		ServiceOrderStatusHistory $serviceOrderStatusHistory
	): JsonResponse {
		try {
			$validated = $request->validated();

			$updatedHistory = DB::transaction(function () use ($serviceOrderStatusHistory, $validated): ServiceOrderStatusHistory {
				$serviceOrderStatusHistory->update($validated);

				if ($this->isLatestHistory($serviceOrderStatusHistory)) {
					$serviceOrder = $serviceOrderStatusHistory->serviceOrder;
					if ($serviceOrder !== null) {
						$newStatus = (string) $serviceOrderStatusHistory->new_status;
						$serviceOrder->order_status = $newStatus;
						$this->syncCompletionDate($serviceOrder, $newStatus);
						$serviceOrder->save();
					}
				}

				return $serviceOrderStatusHistory;
			});

			$updatedHistory->loadMissing([
				'serviceOrder.customer',
				'changedBy',
			]);

			return response()->json([
				'success' => true,
				'message' => 'Riwayat status service order berhasil diperbarui.',
				'data' => (new ServiceOrderStatusHistoryResource($updatedHistory))->resolve($request),
			]);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Riwayat status service order gagal diperbarui.',
			], 500);
		}
	}

	public function webUpdate(
		UpdateServiceOrderStatusHistoryRequest $request,
		ServiceOrderStatusHistory $serviceOrderStatusHistory
	): RedirectResponse {
		try {
			$validated = $request->validated();

			DB::transaction(function () use ($serviceOrderStatusHistory, $validated): void {
				$serviceOrderStatusHistory->update($validated);

				if ($this->isLatestHistory($serviceOrderStatusHistory)) {
					$serviceOrder = $serviceOrderStatusHistory->serviceOrder;
					if ($serviceOrder !== null) {
						$newStatus = (string) $serviceOrderStatusHistory->new_status;
						$serviceOrder->order_status = $newStatus;
						$this->syncCompletionDate($serviceOrder, $newStatus);
						$serviceOrder->save();
					}
				}
			});

			return redirect()
				->route('super-admin.service-order-status-histories.show', $serviceOrderStatusHistory)
				->with('success', 'Riwayat status service order berhasil diperbarui.');
		} catch (Throwable $throwable) {
			report($throwable);

			return back()
				->withInput()
				->with('error', 'Riwayat status service order gagal diperbarui.');
		}
	}

	public function destroy(ServiceOrderStatusHistory $serviceOrderStatusHistory): JsonResponse
	{
		try {
			DB::transaction(function () use ($serviceOrderStatusHistory): void {
				$serviceOrder = $serviceOrderStatusHistory->serviceOrder;
				$wasLatest = $this->isLatestHistory($serviceOrderStatusHistory);

				$serviceOrderStatusHistory->delete();

				if (! $wasLatest || $serviceOrder === null) {
					return;
				}

				$latestRemaining = ServiceOrderStatusHistory::query()
					->where('service_order_id', $serviceOrder->id)
					->orderByDesc('changed_at')
					->orderByDesc('id')
					->first();

				if ($latestRemaining !== null) {
					$serviceOrder->order_status = (string) $latestRemaining->new_status;
					$this->syncCompletionDate($serviceOrder, $serviceOrder->order_status);
					$serviceOrder->save();
				}
			});

			return response()->json([
				'success' => true,
				'message' => 'Riwayat status service order berhasil dihapus.',
				'data' => null,
			]);
		} catch (Throwable $throwable) {
			report($throwable);

			return response()->json([
				'success' => false,
				'message' => 'Riwayat status service order gagal dihapus.',
			], 500);
		}
	}

	public function webDestroy(ServiceOrderStatusHistory $serviceOrderStatusHistory): RedirectResponse
	{
		try {
			DB::transaction(function () use ($serviceOrderStatusHistory): void {
				$serviceOrder = $serviceOrderStatusHistory->serviceOrder;
				$wasLatest = $this->isLatestHistory($serviceOrderStatusHistory);

				$serviceOrderStatusHistory->delete();

				if (! $wasLatest || $serviceOrder === null) {
					return;
				}

				$latestRemaining = ServiceOrderStatusHistory::query()
					->where('service_order_id', $serviceOrder->id)
					->orderByDesc('changed_at')
					->orderByDesc('id')
					->first();

				if ($latestRemaining !== null) {
					$serviceOrder->order_status = (string) $latestRemaining->new_status;
					$this->syncCompletionDate($serviceOrder, $serviceOrder->order_status);
					$serviceOrder->save();
				}
			});

			return redirect()
				->route('super-admin.service-order-status-histories.index')
				->with('success', 'Riwayat status service order berhasil dihapus.');
		} catch (Throwable $throwable) {
			report($throwable);

			return back()->with('error', 'Riwayat status service order gagal dihapus.');
		}
	}

	private function applySearch(Builder $query, string $search): Builder
	{
		$pattern = '%' . mb_strtolower($search) . '%';

		return $query->where(function (Builder $subQuery) use ($pattern): void {
			$subQuery
				->whereRaw("LOWER(COALESCE(previous_status, '')) LIKE ?", [$pattern])
				->orWhereRaw("LOWER(COALESCE(new_status, '')) LIKE ?", [$pattern])
				->orWhereRaw("LOWER(COALESCE(notes, '')) LIKE ?", [$pattern])
				->orWhereHas('serviceOrder', function (Builder $orderQuery) use ($pattern): void {
					$orderQuery
						->whereRaw("LOWER(COALESCE(order_number, '')) LIKE ?", [$pattern])
						->orWhereHas('customer', function (Builder $customerQuery) use ($pattern): void {
							$customerQuery
								->whereRaw("LOWER(COALESCE(name, '')) LIKE ?", [$pattern])
								->orWhereRaw("LOWER(COALESCE(company_name, '')) LIKE ?", [$pattern]);
						});
				})
				->orWhereHas('changedBy', function (Builder $userQuery) use ($pattern): void {
					$userQuery
						->whereRaw("LOWER(COALESCE(name, '')) LIKE ?", [$pattern])
						->orWhereRaw("LOWER(COALESCE(email, '')) LIKE ?", [$pattern]);
				});
		});
	}

	private function isLatestHistory(ServiceOrderStatusHistory $history): bool
	{
		return ! ServiceOrderStatusHistory::query()
			->where('service_order_id', $history->service_order_id)
			->where(function (Builder $query) use ($history): void {
				$query
					->where('changed_at', '>', $history->changed_at)
					->orWhere(function (Builder $subQuery) use ($history): void {
						$subQuery
							->where('changed_at', '=', $history->changed_at)
							->where('id', '>', $history->id);
					});
			})
			->exists();
	}

	private function syncCompletionDate(ServiceOrder $serviceOrder, string $newStatus): void
	{
		if ($newStatus === ServiceOrder::ORDER_STATUS_COMPLETED) {
			$serviceOrder->completion_date = now()->toDateString();
			return;
		}

		$serviceOrder->completion_date = null;
	}
}
