<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    public const PAYMENT_STATUS_UNPAID  = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';
    public const PAYMENT_STATUS_PAID    = 'paid';

    protected $table = 'invoices';

    protected $fillable = [

        'service_order_id',

        'invoice_number',

        'invoice_date',

        'due_date',

        'subtotal',

        'discount',

        'tax',

        'total_amount',

        'payment_status',

        'notes',

    ];

    protected $casts = [

        'invoice_date' => 'date',

        'due_date'     => 'date',

        'subtotal'     => 'decimal:2',

        'discount'     => 'decimal:2',

        'tax'          => 'decimal:2',

        'total_amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    /**
     * Invoice milik service order
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id'
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPE
    |--------------------------------------------------------------------------
    */

    /**
     * Cari invoice
     */
    public function scopeSearch(
        Builder $query,
        string $keyword
    ) {

        return $query->where(function ($q) use ($keyword) {

            $q->where(
                'invoice_number',
                'LIKE',
                "%{$keyword}%"
            )

                ->orWhereHas(
                    'serviceOrder',
                    function ($order) use ($keyword) {

                        $order->where(
                            'order_number',
                            'LIKE',
                            "%{$keyword}%"
                        );

                    }
                );

        });

    }

    /**
     * Filter status pembayaran
     */
    public function scopePaymentStatus(
        Builder $query,
        string $status
    ) {

        return $query->where(
            'payment_status',
            $status
        );

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

    public function getFormattedSubtotalAttribute()
    {

        return number_format(
            $this->subtotal,
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
     * Menghitung total invoice
     */
    public function calculateTotal()
    {

        $this->total_amount =
        $this->subtotal
         -
        $this->discount
         +
        $this->tax;

        return $this;

    }

    /**
     * Update status pembayaran
     */
    public function updatePaymentStatus(): static
    {
        $paid = (float) $this->payments()
            ->where('status', Payment::STATUS_CONFIRMED)
            ->sum('amount');
        $total = max(0.0, (float) $this->total_amount);

        $this->payment_status = $paid <= 0
            ? self::PAYMENT_STATUS_UNPAID
            : ($paid >= $total ? self::PAYMENT_STATUS_PAID : self::PAYMENT_STATUS_PARTIAL);

        return $this;

    }

}
