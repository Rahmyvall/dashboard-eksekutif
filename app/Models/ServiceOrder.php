<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use HasFactory, SoftDeletes;

    public const ORDER_STATUS_DRAFT      = 'draft';
    public const ORDER_STATUS_PENDING    = 'pending';
    public const ORDER_STATUS_PROCESSING = 'processing';
    public const ORDER_STATUS_COMPLETED  = 'completed';
    public const ORDER_STATUS_CANCELLED  = 'cancelled';

    public const PAYMENT_STATUS_UNPAID  = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';
    public const PAYMENT_STATUS_PAID    = 'paid';

    protected $table = 'service_orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'order_date',
        'scheduled_date',
        'completion_date',

        'subtotal',
        'discount',
        'tax',
        'total_amount',

        'paid_amount',
        'remaining_amount',

        'payment_status',
        'order_status',

        'notes',

        'created_by',
    ];

    protected $casts = [

        'order_date'       => 'date',

        'scheduled_date'   => 'date',

        'completion_date'  => 'date',

        'subtotal'         => 'decimal:2',

        'discount'         => 'decimal:2',

        'tax'              => 'decimal:2',

        'total_amount'     => 'decimal:2',

        'paid_amount'      => 'decimal:2',

        'remaining_amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    /**
     * Customer pemilik service order
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /**
     * User yang membuat order
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            ServiceOrderItem::class,
            'service_order_id'
        );
    }

    /** Compatibility alias for older callers using details(). */
    public function details(): HasMany
    {
        return $this->items();
    }

    /**
     * Invoice service order
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(
            Invoice::class,
            'service_order_id'
        );
    }

    /**
     * Pembayaran
     */
    public function payments(): HasMany
    {
        return $this->hasMany(
            Payment::class,
            'service_order_id'
        );
    }

    /**
     * History pengerjaan
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(
            ServiceOrderStatusHistory::class,
            'service_order_id'
        );
    }

    /** Compatibility alias for older callers using histories(). */
    public function histories(): HasMany
    {
        return $this->statusHistories();
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPE
    |--------------------------------------------------------------------------
    */

    /**
     * Filter status order
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('order_status', strtolower(trim($status)));
    }

    /**
     * Search order
     */
    public function scopeSearch(
        Builder $query,
        string $keyword
    ) {

        return $query->where(function ($q) use ($keyword) {

            $q->where(
                'order_number',
                'LIKE',
                "%{$keyword}%"
            )

                ->orWhereHas(
                    'customer',
                    function ($customer) use ($keyword) {

                        $customer->where(
                            'name',
                            'LIKE',
                            "%{$keyword}%"
                        );

                    }
                );

        });

    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getFormattedTotalAmountAttribute()
    {
        return number_format(
            $this->total_amount,
            2,
            ',',
            '.'
        );
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return number_format(
            $this->remaining_amount,
            2,
            ',',
            '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Hitung total pembayaran
     */
    public function calculateRemaining(): static
    {
        $total = max(0.0, (float) $this->total_amount);
        $paid  = max(0.0, (float) $this->paid_amount);

        $this->paid_amount      = $paid;
        $this->remaining_amount = max(0.0, $total - $paid);
        $this->payment_status   = $paid <= 0
            ? self::PAYMENT_STATUS_UNPAID
            : ($this->remaining_amount <= 0 ? self::PAYMENT_STATUS_PAID : self::PAYMENT_STATUS_PARTIAL);

        return $this;

    }

    /**
     * Update total harga
     */
    public function calculateTotal(): static
    {
        $itemsSubtotal = (float) $this->items()->sum('subtotal');
        $discount = max(0.0, (float) $this->discount);
        $tax = max(0.0, (float) $this->tax);

        $this->subtotal = max(0.0, $itemsSubtotal);
        $this->total_amount = max(0.0, $this->subtotal - $discount + $tax);

        return $this;
    }

}