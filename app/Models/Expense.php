<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Expense extends Model
{
	use HasFactory;

	protected $table = 'expenses';

	/**
	 * Kolom yang bisa diisi via mass assignment.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'service_order_id',
		'expense_date',
		'category',
		'description',
		'amount',
		'attachment_path',
		'created_by',
	];

	/**
	 * Tipe data setiap atribut.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'id' => 'integer',
			'service_order_id' => 'integer',
			'expense_date' => 'date',
			'amount' => 'decimal:2',
			'created_by' => 'integer',
			'created_at' => 'datetime',
			'updated_at' => 'datetime',
		];
	}

	public function setCategoryAttribute(string $value): void
	{
		$this->attributes['category'] = strtolower(trim($value));
	}

	public function setDescriptionAttribute(string $value): void
	{
		$this->attributes['description'] = trim($value);
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONSHIPS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Service order asal pengeluaran.
	 */
	public function serviceOrder(): BelongsTo
	{
		return $this->belongsTo(
			ServiceOrder::class,
			'service_order_id',
			'id'
		);
	}

	/**
	 * User yang membuat data pengeluaran.
	 */
	public function creator(): BelongsTo
	{
		return $this->belongsTo(
			User::class,
			'created_by',
			'id'
		);
	}

	/*
	|--------------------------------------------------------------------------
	| QUERY SCOPES
	|--------------------------------------------------------------------------
	*/

	/**
	 * Filter berdasarkan kategori.
	 */
	public function scopeCategory(
		Builder $query,
		?string $category
	): Builder {
		return $query->when(
			filled($category),
			fn (Builder $query): Builder => $query->where('category', strtolower(trim((string) $category)))
		);
	}

	/**
	 * Filter berdasarkan service order.
	 */
	public function scopeServiceOrder(
		Builder $query,
		?int $serviceOrderId
	): Builder {
		return $query->when(
			!is_null($serviceOrderId),
			fn (Builder $query): Builder => $query->where('service_order_id', $serviceOrderId)
		);
	}

	/**
	 * Filter berdasarkan tanggal pengeluaran tunggal.
	 */
	public function scopeExpenseDate(
		Builder $query,
		?string $date
	): Builder {
		return $query->when(
			filled($date),
			fn (Builder $query): Builder => $query->whereDate('expense_date', (string) $date)
		);
	}

	/**
	 * Filter rentang tanggal pengeluaran.
	 */
	public function scopeDateRange(
		Builder $query,
		?string $startDate,
		?string $endDate
	): Builder {
		return $query->when(
			filled($startDate) && filled($endDate),
			fn (Builder $query): Builder => $query->whereBetween('expense_date', [(string) $startDate, (string) $endDate])
		);
	}

	/**
	 * Pencarian berdasarkan kategori, deskripsi, nomor order, atau nama customer.
	 */
	public function scopeSearch(
		Builder $query,
		?string $keyword
	): Builder {
		return $query->when(filled($keyword), function (Builder $query) use ($keyword): void {
			$query->where(function (Builder $query) use ($keyword): void {
				$query->where('category', 'like', "%{$keyword}%")
					->orWhere('description', 'like', "%{$keyword}%")
					->orWhereHas('serviceOrder', function (Builder $orderQuery) use ($keyword): void {
						$orderQuery->where('order_number', 'like', "%{$keyword}%")
							->orWhereHas('customer', function (Builder $customerQuery) use ($keyword): void {
								$customerQuery->where('name', 'like', "%{$keyword}%");
							});
					});
			});
		});
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSORS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Nilai amount yang sudah diformat rupiah.
	 */
	public function getFormattedAmountAttribute(): string
	{
		return 'Rp ' . number_format(
			(float) $this->amount,
			0,
			',',
			'.'
		);
	}

	/**
	 * URL publik untuk file lampiran, jika ada.
	 */
	public function getAttachmentUrlAttribute(): ?string
	{
		if (blank($this->attachment_path)) {
			return null;
		}

		if (str_starts_with((string) $this->attachment_path, 'http')) {
			return (string) $this->attachment_path;
		}

		return Storage::url((string) $this->attachment_path);
	}

	/*
	|--------------------------------------------------------------------------
	| HELPERS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Menandai apakah expense memiliki lampiran.
	 */
	public function hasAttachment(): bool
	{
		return filled($this->attachment_path);
	}
}
