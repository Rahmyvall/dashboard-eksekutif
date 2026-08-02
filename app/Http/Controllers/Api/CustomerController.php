<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

final class CustomerController extends Controller
{
    /**
     * Menampilkan daftar pelanggan aktif/belum dihapus.
     *
     * Query parameter:
     * - search
     * - customer_type
     * - status
     * - sort
     * - direction
     * - per_page
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'search'        => ['nullable', 'string', 'max:150'],
            'customer_type' => [
                'nullable',
                'string',
                Rule::in(Customer::customerTypes()),
            ],
            'status'        => [
                'nullable',
                'string',
                Rule::in(Customer::statuses()),
            ],
            'sort'          => [
                'nullable',
                'string',
                Rule::in([
                    'id',
                    'customer_code',
                    'customer_type',
                    'name',
                    'company_name',
                    'email',
                    'status',
                    'created_at',
                    'updated_at',
                ]),
            ],
            'direction'     => [
                'nullable',
                'string',
                Rule::in(['asc', 'desc']),
            ],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $search       = trim((string) ($validated['search'] ?? ''));
        $customerType = $validated['customer_type'] ?? null;
        $status       = $validated['status'] ?? null;
        $sort         = $validated['sort'] ?? 'created_at';
        $direction    = $validated['direction'] ?? 'desc';
        $perPage      = (int) ($validated['per_page'] ?? 15);

        $customers = Customer::query()
            ->when(
                $search !== '',
                fn(Builder $query): Builder => $this->applySearch(
                    $query,
                    $search
                )
            )
            ->when(
                filled($customerType),
                fn(Builder $query): Builder => $query->where(
                    'customer_type',
                    $customerType
                )
            )
            ->when(
                filled($status),
                fn(Builder $query): Builder => $query->where(
                    'status',
                    $status
                )
            )
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return CustomerResource::collection($customers)
            ->additional([
                'success' => true,
                'message' => 'Daftar pelanggan berhasil diambil.',
                'filters' => [
                    'search'        => $search !== '' ? $search : null,
                    'customer_type' => $customerType,
                    'status'        => $status,
                    'sort'          => $sort,
                    'direction'     => $direction,
                    'per_page'      => $perPage,
                ],
            ]);
    }

    /**
     * Menyimpan pelanggan baru.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $customer = DB::transaction(function () use ($request): Customer {
                $payload = $this->normalizePayload(
                    $request->validated()
                );

                return Customer::query()->create($payload);
            });

            return (new CustomerResource($customer))
                ->additional([
                    'success' => true,
                    'message' => 'Pelanggan berhasil ditambahkan.',
                ])
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $throwable) {
            report($throwable);

            return response()->json([
                'success' => false,
                'message' => 'Pelanggan gagal ditambahkan.',
            ], 500);
        }
    }

    /**
     * Menampilkan detail pelanggan.
     */
    public function show(Customer $customer): CustomerResource
    {
        return (new CustomerResource($customer))
            ->additional([
                'success' => true,
                'message' => 'Detail pelanggan berhasil diambil.',
            ]);
    }

    /**
     * Memperbarui pelanggan.
     */
    public function update(
        UpdateCustomerRequest $request,
        Customer $customer
    ): JsonResponse {
        try {
            DB::transaction(function () use ($request, $customer): void {
                $payload = $this->normalizePayload(
                    $request->validated()
                );

                $customer->update($payload);
            });

            return (new CustomerResource($customer->refresh()))
                ->additional([
                    'success' => true,
                    'message' => 'Pelanggan berhasil diperbarui.',
                ])
                ->response()
                ->setStatusCode(200);
        } catch (Throwable $throwable) {
            report($throwable);

            return response()->json([
                'success' => false,
                'message' => 'Pelanggan gagal diperbarui.',
            ], 500);
        }
    }

    /**
     * Memindahkan pelanggan ke recycle bin.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        try {
            DB::transaction(
                static function () use ($customer): void {
                    $customer->delete();
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil dipindahkan ke sampah.',
                'data'    => [
                    'id'            => $customer->getKey(),
                    'customer_code' => $customer->customer_code,
                ],
            ]);
        } catch (Throwable $throwable) {
            report($throwable);

            return response()->json([
                'success' => false,
                'message' => 'Pelanggan gagal dihapus.',
            ], 500);
        }
    }

    /**
     * Menampilkan pelanggan yang telah dihapus sementara.
     */
    public function trash(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'search'        => ['nullable', 'string', 'max:150'],
            'customer_type' => [
                'nullable',
                'string',
                Rule::in(Customer::customerTypes()),
            ],
            'sort'          => [
                'nullable',
                'string',
                Rule::in([
                    'id',
                    'customer_code',
                    'customer_type',
                    'name',
                    'company_name',
                    'deleted_at',
                ]),
            ],
            'direction'     => [
                'nullable',
                'string',
                Rule::in(['asc', 'desc']),
            ],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $search       = trim((string) ($validated['search'] ?? ''));
        $customerType = $validated['customer_type'] ?? null;
        $sort         = $validated['sort'] ?? 'deleted_at';
        $direction    = $validated['direction'] ?? 'desc';
        $perPage      = (int) ($validated['per_page'] ?? 15);

        $customers = Customer::onlyTrashed()
            ->when(
                $search !== '',
                fn(Builder $query): Builder => $this->applySearch(
                    $query,
                    $search
                )
            )
            ->when(
                filled($customerType),
                fn(Builder $query): Builder => $query->where(
                    'customer_type',
                    $customerType
                )
            )
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return CustomerResource::collection($customers)
            ->additional([
                'success' => true,
                'message' => 'Daftar sampah pelanggan berhasil diambil.',
                'filters' => [
                    'search'        => $search !== '' ? $search : null,
                    'customer_type' => $customerType,
                    'sort'          => $sort,
                    'direction'     => $direction,
                    'per_page'      => $perPage,
                ],
            ]);
    }

    /**
     * Mengembalikan pelanggan dari recycle bin.
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $customer = DB::transaction(function () use ($id): Customer {
                $customer = Customer::onlyTrashed()->findOrFail($id);
                $customer->restore();

                return $customer->fresh();
            });

            return (new CustomerResource($customer))
                ->additional([
                    'success' => true,
                    'message' => 'Pelanggan berhasil dipulihkan.',
                ])
                ->response()
                ->setStatusCode(200);
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan di sampah tidak ditemukan.',
            ], 404);
        } catch (Throwable $throwable) {
            report($throwable);

            return response()->json([
                'success' => false,
                'message' => 'Pelanggan gagal dipulihkan.',
            ], 500);
        }
    }

    /**
     * Menghapus pelanggan secara permanen.
     */
    public function forceDelete(int $id): JsonResponse
    {
        try {
            $deletedCustomer = DB::transaction(
                function () use ($id): array {
                    $customer = Customer::onlyTrashed()->findOrFail($id);

                    $data = [
                        'id'            => $customer->getKey(),
                        'customer_code' => $customer->customer_code,
                    ];

                    $customer->forceDelete();

                    return $data;
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil dihapus permanen.',
                'data'    => $deletedCustomer,
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan di sampah tidak ditemukan.',
            ], 404);
        } catch (Throwable $throwable) {
            report($throwable);

            return response()->json([
                'success' => false,
                'message' => 'Pelanggan gagal dihapus permanen.',
            ], 500);
        }
    }

    /**
     * Pencarian PostgreSQL secara case-insensitive.
     */
    private function applySearch(
        Builder $query,
        string $search
    ): Builder {
        $keyword = '%' . $search . '%';

        return $query->where(
            static function (Builder $subQuery) use ($keyword): void {
                $subQuery
                    ->where('customer_code', 'ILIKE', $keyword)
                    ->orWhere('name', 'ILIKE', $keyword)
                    ->orWhere('company_name', 'ILIKE', $keyword)
                    ->orWhere('phone', 'ILIKE', $keyword)
                    ->orWhere('email', 'ILIKE', $keyword)
                    ->orWhere('address', 'ILIKE', $keyword)
                    ->orWhere('tax_number', 'ILIKE', $keyword);
            }
        );
    }

    /**
     * Normalisasi payload agar API dan web menyimpan format yang sama.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function normalizePayload(array $payload): array
    {
        $isCompany = ($payload['customer_type'] ?? null)
        === Customer::TYPE_COMPANY;

        $payload['company_name'] = $isCompany
            ? ($payload['company_name'] ?? null)
            : null;

        $payload['email'] = filled($payload['email'] ?? null)
            ? mb_strtolower(trim((string) $payload['email']))
            : null;

        foreach ([
            'phone',
            'address',
            'tax_number',
        ] as $nullableField) {
            $payload[$nullableField] = filled(
                $payload[$nullableField] ?? null
            )
                ? trim((string) $payload[$nullableField])
                : null;
        }

        return $payload;
    }
}
