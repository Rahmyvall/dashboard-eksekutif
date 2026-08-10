<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{

    use HasFactory;

    public const STATUS_PENDING   = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED  = 'refunded';

    protected $table = 'payments';

    protected $fillable = [

        'service_order_id',

        'invoice_id',

        'payment_number',

        'payment_date',

        'payment_method',

        'amount',

        'reference_number',

        'proof_of_payment_path',

        'status',

        'received_by',

        'notes',

    ];

    protected $casts = [

        'payment_date' => 'date',

        'amount'       => 'decimal:2',

    ];

    protected static function booted(): void
    {
        static::creating(function (self $payment): void {
            if (blank($payment->reference_number)) {
                $payment->reference_number = self::generateReferenceNumber();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    /**
     * Pembayaran berdasarkan service order
     */
    public function serviceOrder(): BelongsTo
    {

        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id'
        );

    }

    /**
     * Pembayaran berdasarkan invoice
     */
    public function invoice(): BelongsTo
    {

        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );

    }

    /**
     * User penerima pembayaran
     */
    public function receiver(): BelongsTo
    {

        return $this->belongsTo(
            User::class,
            'received_by'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPE
    |--------------------------------------------------------------------------
    */

    /**
     * Pencarian pembayaran
     */
    public function scopeSearch(
        Builder $query,
        string $keyword
    ) {

        return $query->where(function ($q) use ($keyword) {

            $q->where(
                'payment_number',
                'LIKE',
                "%{$keyword}%"
            )

                ->orWhere(
                    'reference_number',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhereHas(
                    'invoice',
                    function ($invoice) use ($keyword) {

                        $invoice->where(
                            'invoice_number',
                            'LIKE',
                            "%{$keyword}%"
                        );

                    }
                );

        });

    }

    /**
     * Filter metode pembayaran
     */
    public function scopeMethod(
        Builder $query,
        string $method
    ) {

        return $query->where(
            'payment_method',
            $method
        );

    }

    /**
     * Filter status pembayaran
     */
    public function scopeStatus(
        Builder $query,
        string $status
    ) {

        return $query->where(
            'status',
            $status
        );

    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    /**
     * Format jumlah pembayaran
     */
    public function getFormattedAmountAttribute()
    {

        return number_format(
            $this->amount,
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
     * Update pembayaran service order
     */
    public function updateServiceOrderPayment(): static
    {

        if ($this->serviceOrder) {

            $totalPaid = Payment::where(
                'service_order_id',
                $this->service_order_id
            )
                ->where('status', self::STATUS_CONFIRMED)
                ->sum('amount');

            $this->serviceOrder->paid_amount =
                $totalPaid;

            $this->serviceOrder->calculateRemaining();

            $this->serviceOrder->save();

            if ($this->invoice) {
                $this->invoice->updatePaymentStatus()->save();
            }

        }

        return $this;

    }

    /**
     * Generate nomor pembayaran
     */
    public static function generatePaymentNumber()
    {

        $lastPayment = self::latest('id')
            ->first();

        $number = $lastPayment
            ? $lastPayment->id + 1
            : 1;

        return 'PAY-' .
        date('Ymd') .
        '-' .
        str_pad(
            $number,
            5,
            '0',
            STR_PAD_LEFT
        );

    }

    /**
     * Generate a unique internal payment reference number.
     */
    public static function generateReferenceNumber(): string
    {
        $nextNumber = ((int) self::max('id')) + 1;

        do {
            $reference = 'REF-' . date('Ymd') . '-' . str_pad(
                (string) $nextNumber,
                6,
                '0',
                STR_PAD_LEFT
            );
            $nextNumber++;
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }

}
