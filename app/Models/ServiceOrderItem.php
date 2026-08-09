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

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
