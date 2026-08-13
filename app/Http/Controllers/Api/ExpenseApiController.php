<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL EXPENSES
    | GET /api/v1/expenses
    |--------------------------------------------------------------------------
    |
    | Query params:
    |   search          – kategori / deskripsi / order_number / customer name
    |   category        – string (exact, case-insensitive)
    |   service_order_id– integer
    |   start_date      – YYYY-MM-DD
    |   end_date        – YYYY-MM-DD
    |   per_page        – 1-100  (default 15)
    |   sort_by         – id | expense_date | amount | category | created_at
    |   sort_direction  – asc | desc
    */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'search'           => ['nullable', 'string', 'max:150'],
            'category'         => ['nullable', 'string', 'max:100'],
            'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page'         => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by'          => ['nullable', Rule::in(['id', 'expense_date', 'amount', 'category', 'created_at'])],
            'sort_direction'   => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter filter tidak valid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $f = $validator->validated();

        $expenses = Expense::with([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name',
            'creator:id,name',
        ])
            ->search($f['search'] ?? null)
            ->category($f['category'] ?? null)
            ->serviceOrder(isset($f['service_order_id']) ? (int) $f['service_order_id'] : null)
            ->dateRange($f['start_date'] ?? null, $f['end_date'] ?? null)
            ->orderBy($f['sort_by'] ?? 'created_at', $f['sort_direction'] ?? 'desc')
            ->paginate((int) ($f['per_page'] ?? 15))
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Data pengeluaran berhasil diambil.',
            'data'    => ExpenseResource::collection($expenses->items()),
            'meta'    => [
                'current_page' => $expenses->currentPage(),
                'from'         => $expenses->firstItem(),
                'last_page'    => $expenses->lastPage(),
                'per_page'     => $expenses->perPage(),
                'to'           => $expenses->lastItem(),
                'total'        => $expenses->total(),
            ],
            'links'   => [
                'first' => $expenses->url(1),
                'last'  => $expenses->url($expenses->lastPage()),
                'prev'  => $expenses->previousPageUrl(),
                'next'  => $expenses->nextPageUrl(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE EXPENSE
    | POST /api/v1/expenses   (multipart/form-data jika ada attachment)
    |--------------------------------------------------------------------------
    |
    | Body fields:
    |   service_order_id – integer (optional)
    |   expense_date     – YYYY-MM-DD (required)
    |   category         – string max:100 (required)
    |   description      – string (required)
    |   amount           – numeric > 0 (required)
    |   attachment       – file JPG|PNG|PDF max 5 MB (optional)
    */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
            'expense_date'     => ['required', 'date'],
            'category'         => ['required', 'string', 'max:100'],
            'description'      => ['required', 'string'],
            'amount'           => ['required', 'numeric', 'gt:0'],
            'attachment'       => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ], [
            'service_order_id.exists' => 'Service order tidak ditemukan.',
            'expense_date.required'   => 'Tanggal pengeluaran wajib diisi.',
            'category.required'       => 'Kategori wajib diisi.',
            'amount.gt'               => 'Nominal harus lebih besar dari 0.',
            'attachment.max'          => 'Ukuran lampiran maksimal 5 MB.',
            'attachment.mimes'        => 'Lampiran harus berupa JPG, JPEG, PNG, atau PDF.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['category']    = strtolower(trim((string) $data['category']));
        $data['description'] = trim((string) $data['description']);
        $data['created_by']  = $request->user()?->id;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('expenses/attachments', 'public');
        }

        unset($data['attachment']);

        $expense = DB::transaction(fn () => Expense::create($data));

        $expense->load([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name',
            'creator:id,name',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dibuat.',
            'data'    => new ExpenseResource($expense),
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW EXPENSE DETAIL
    | GET /api/v1/expenses/{id}
    |--------------------------------------------------------------------------
    */
    public function show(Expense $expense): JsonResponse
    {
        $expense->load([
            'serviceOrder:id,order_number,customer_id,order_status,total_amount',
            'serviceOrder.customer:id,name,email,phone',
            'creator:id,name,email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengeluaran berhasil diambil.',
            'data'    => new ExpenseResource($expense),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE EXPENSE
    | PUT /api/v1/expenses/{id}   (multipart/form-data jika ada attachment baru)
    |--------------------------------------------------------------------------
    |
    | Body fields: sama seperti store, ditambah:
    |   remove_attachment – boolean (1/true → hapus lampiran saat ini)
    |
    | Catatan: gunakan POST + _method=PUT untuk multipart/form-data di Postman.
    */
    public function update(Request $request, Expense $expense): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
            'expense_date'     => ['required', 'date'],
            'category'         => ['required', 'string', 'max:100'],
            'description'      => ['required', 'string'],
            'amount'           => ['required', 'numeric', 'gt:0'],
            'attachment'       => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'remove_attachment'=> ['nullable', 'boolean'],
        ], [
            'service_order_id.exists' => 'Service order tidak ditemukan.',
            'expense_date.required'   => 'Tanggal pengeluaran wajib diisi.',
            'category.required'       => 'Kategori wajib diisi.',
            'amount.gt'               => 'Nominal harus lebih besar dari 0.',
            'attachment.max'          => 'Ukuran lampiran maksimal 5 MB.',
            'attachment.mimes'        => 'Lampiran harus berupa JPG, JPEG, PNG, atau PDF.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['category']    = strtolower(trim((string) $data['category']));
        $data['description'] = trim((string) $data['description']);

        DB::transaction(function () use ($request, $expense, &$data): void {
            // Hapus lampiran lama jika diminta atau ada upload baru
            if ($request->boolean('remove_attachment') || $request->hasFile('attachment')) {
                if (filled($expense->attachment_path) && !str_starts_with((string) $expense->attachment_path, 'http')) {
                    Storage::disk('public')->delete((string) $expense->attachment_path);
                }
                $data['attachment_path'] = null;
            }

            if ($request->hasFile('attachment')) {
                $data['attachment_path'] = $request->file('attachment')
                    ->store('expenses/attachments', 'public');
            }

            unset($data['attachment'], $data['remove_attachment']);
            $expense->update($data);
        });

        $expense->refresh()->load([
            'serviceOrder:id,order_number,customer_id',
            'serviceOrder.customer:id,name',
            'creator:id,name',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diperbarui.',
            'data'    => new ExpenseResource($expense),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE EXPENSE
    | DELETE /api/v1/expenses/{id}
    |--------------------------------------------------------------------------
    */
    public function destroy(Expense $expense): JsonResponse
    {
        DB::transaction(function () use ($expense): void {
            if (filled($expense->attachment_path) && !str_starts_with((string) $expense->attachment_path, 'http')) {
                Storage::disk('public')->delete((string) $expense->attachment_path);
            }
            $expense->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dihapus.',
            'data'    => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPENSE SUMMARY (aggregate)
    | GET /api/v1/expenses/summary
    |--------------------------------------------------------------------------
    |
    | Query params:
    |   start_date – YYYY-MM-DD
    |   end_date   – YYYY-MM-DD
    */
    public function summary(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tidak valid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $f = $validator->validated();

        $base = Expense::dateRange($f['start_date'] ?? null, $f['end_date'] ?? null);

        $totalAmount = (clone $base)->sum('amount');
        $totalCount  = (clone $base)->count();

        $byCategory = (clone $base)
            ->selectRaw('category, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'category'  => $row->category,
                'count'     => (int) $row->count,
                'total'     => (float) $row->total,
                'formatted' => 'Rp ' . number_format((float) $row->total, 0, ',', '.'),
            ]);

        $linkedToOrder = (clone $base)->whereNotNull('service_order_id')->count();
        $withAttachment = (clone $base)->whereNotNull('attachment_path')->count();

        return response()->json([
            'success' => true,
            'message' => 'Ringkasan pengeluaran berhasil diambil.',
            'data'    => [
                'period' => [
                    'start_date' => $f['start_date'] ?? null,
                    'end_date'   => $f['end_date'] ?? null,
                ],
                'total_amount'    => (float) $totalAmount,
                'total_count'     => $totalCount,
                'linked_to_order' => $linkedToOrder,
                'with_attachment' => $withAttachment,
                'by_category'     => $byCategory,
                'formatted_total' => 'Rp ' . number_format((float) $totalAmount, 0, ',', '.'),
            ],
        ]);
    }
}
