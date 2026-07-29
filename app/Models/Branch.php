<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const APPROVAL_PENDING  = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';

    /**
     * Nama tabel.
     */
    protected $table = 'branches';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Tipe primary key.
     */
    protected $keyType = 'int';

    /**
     * Primary key menggunakan auto increment.
     */
    public $incrementing = true;

    /**
     * Field yang dapat diisi melalui mass assignment.
     *
     * Field approval harus tersedia karena BranchController mengisi field
     * tersebut ketika pengajuan tambah, edit, hapus, approve, dan reject.
     */
    protected $fillable = [
        'branch_code',
        'branch_name',
        'address',
        'phone',
        'email',
        'manager_id',
        'status',

        'approval_status',
        'approval_action',
        'pending_approval_role',
        'pending_payload',
        'submitted_by',
        'last_approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_note',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'status'           => 'integer',
        'manager_id'       => 'integer',
        'pending_payload'  => 'array',
        'submitted_by'     => 'integer',
        'last_approved_by' => 'integer',
        'rejected_by'      => 'integer',
        'approved_at'      => 'datetime',
        'rejected_at'      => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
        'deleted_at'       => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Kepala cabang yang ditugaskan.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    /**
     * Pengguna yang mengajukan perubahan terakhir.
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    /**
     * Pengguna yang memberikan approval terakhir.
     */
    public function lastApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_approved_by', 'id');
    }

    /**
     * Pengguna yang menolak pengajuan terakhir.
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    /**
     * Riwayat proses approval cabang.
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(BranchApprovalLog::class, 'branch_id', 'id')
            ->latest('created_at');
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan cabang aktif saja.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * Menampilkan cabang nonaktif saja.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 0);
    }

    /**
     * Menampilkan cabang yang masih menunggu approval.
     */
    public function scopePendingApproval(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_PENDING);
    }

    /**
     * Menampilkan cabang yang sudah disetujui.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_APPROVED);
    }

    /**
     * Menampilkan cabang dengan pengajuan ditolak.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_REJECTED);
    }

    /**
     * Menampilkan pengajuan yang sedang menunggu role tertentu.
     */
    public function scopeWaitingForRole(Builder $query, string $role): Builder
    {
        return $query
            ->where('approval_status', self::APPROVAL_PENDING)
            ->where('pending_approval_role', $role);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return (int) $this->status === 1;
    }

    public function isInactive(): bool
    {
        return (int) $this->status === 0;
    }

    public function isApprovalPending(): bool
    {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->approval_status === self::APPROVAL_REJECTED;
    }

    public function isCreateRequest(): bool
    {
        return $this->approval_action === self::ACTION_CREATE;
    }

    public function isUpdateRequest(): bool
    {
        return $this->approval_action === self::ACTION_UPDATE;
    }

    public function isDeleteRequest(): bool
    {
        return $this->approval_action === self::ACTION_DELETE;
    }

    /**
     * Data tidak boleh diedit atau diajukan ulang selama masih pending.
     */
    public function canSubmitNewRequest(): bool
    {
        return ! $this->isApprovalPending();
    }

    /**
     * Mengecek apakah approval sedang menunggu role tertentu.
     */
    public function isWaitingForRole(string $role): bool
    {
        return $this->isApprovalPending()
        && $this->pending_approval_role === $role;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return $this->isActive() ? 'Aktif' : 'Nonaktif';
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        return match ($this->approval_status) {
            self::APPROVAL_PENDING  => 'Menunggu Persetujuan',
            self::APPROVAL_APPROVED => 'Disetujui',
            self::APPROVAL_REJECTED => 'Ditolak',
            default                 => 'Belum Diajukan',
        };
    }

    public function getApprovalActionLabelAttribute(): string
    {
        return match ($this->approval_action) {
            self::ACTION_CREATE => 'Penambahan Cabang',
            self::ACTION_UPDATE => 'Perubahan Cabang',
            self::ACTION_DELETE => 'Penghapusan Cabang',
            default             => '-',
        };
    }

    public function getPendingApprovalRoleLabelAttribute(): string
    {
        if (! is_string($this->pending_approval_role) || $this->pending_approval_role === '') {
            return '-';
        }

        return Str::of($this->pending_approval_role)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->branch_code} - {$this->branch_name}";
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        /**
         * Kode ini hanya menjadi fallback apabila controller tidak mengirim
         * branch_code. BranchController akan menggantinya menjadi CBG-0001,
         * CBG-0002, dan seterusnya setelah ID berhasil dibuat.
         */
        static::creating(function (Branch $branch): void {
            if (blank($branch->branch_code)) {
                $branch->branch_code = 'TMP-' . Str::uuid()->toString();
            }

            if (blank($branch->approval_status)) {
                $branch->approval_status = self::APPROVAL_APPROVED;
            }
        });
    }
}
