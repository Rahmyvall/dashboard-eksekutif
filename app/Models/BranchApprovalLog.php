<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchApprovalLog extends Model
{
    use HasFactory;

    protected $table = 'branch_approval_logs';

    protected $fillable = [
        'branch_id',
        'user_id',
        'role_name',
        'action',
        'from_status',
        'to_status',
        'next_approval_role',
        'note',
    ];

    protected $casts = [
        'branch_id' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
