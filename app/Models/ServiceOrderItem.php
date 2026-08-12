<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderItem extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $table = 'service_order_items';

    protected $fillable = [
        'service_order_id',
        'service_id',
        'employee_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
        'start_date',
        'completion_date',
        'status',
        'notes',
    ];

    protected $attributes = [
        'quantity' => 1,
        'unit_price' => 0,
        'discount' => 0,
        'subtotal' => 0,
        'status' => self::STATUS_PENDING,
    ];

    protected $casts = [
        'service_order_id' => 'integer',
        'service_id' => 'integer',
        'employee_id' => 'integer',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'service_order_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            filled($status),
            fn (Builder $query): Builder => $query->where('status', strtolower(trim((string) $status)))
        );
    }

    public function calculateSubtotal(): self
    {
        $quantity = max(0.0, (float) $this->quantity);
        $unitPrice = max(0.0, (float) $this->unit_price);
        $discount = max(0.0, (float) $this->discount);

        $this->subtotal = max(0.0, ($quantity * $unitPrice) - $discount);

        return $this;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format((float) $this->subtotal, 2, ',', '.');
    }
}