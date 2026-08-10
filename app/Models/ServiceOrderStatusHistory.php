<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'service_order_status_histories';

    protected $fillable = [
        'service_order_id',
        'previous_status',
        'new_status',
        'notes',
        'changed_by',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'service_order_id' => 'integer',
            'changed_by' => 'integer',
            'changed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'service_order_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}