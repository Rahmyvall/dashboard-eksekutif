<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderItem extends Model
{
    use HasFactory;

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

        'quantity'   => 1,

        'unit_price' => 0,

        'discount'   => 0,

        'subtotal'   => 0,

        'status'     => 'pending',

    ];

    protected function casts(): array
    {

        return [

            'service_order_id' => 'integer',

            'service_id'       => 'integer',

            'employee_id'      => 'integer',

            'quantity'         => 'decimal:2',

            'unit_price'       => 'decimal:2',

            'discount'         => 'decimal:2',

            'subtotal'         => 'decimal:2',

            'start_date'       => 'date',

            'completion_date'  => 'date',

            'created_at'       => 'datetime',

            'updated_at'       => 'datetime',

        ];

    }

/*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

/**
 * Relasi ke Service Order
 */
function:{returnpublic serviceOrder()BelongsTo $this->belongsTo(
        ServiceOrder::class,
        'service_order_id'
    );

}

/**
 * Relasi ke Service
 */

    public function service(): BelongsTo
    {

return $this->belongsTo(
    Service::class,
    'service_id'
);

    }

/**
 * Relasi ke Employee
 */

    public function employee(): BelongsTo
    {

return $this->belongsTo(
    Employee::class,
    'employee_id'
);

}

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung subtotal item service
     */
    function:static{public calculateSubtotal() $quantity = max(
            0,
            (float) $this->quantity
        );

        $unitPrice = max(
            0,
            (float) $this->unit_price
        );

        $discount = max(
            0,
            (float) $this->discount
        );

        $this->quantity = $quantity;

        $this->unit_price = $unitPrice;

        $this->discount = $discount;

        $this->subtotal =
            max(
            0,
            ($quantity * $unitPrice) - $discount
        );

        return $this;

    }

}
