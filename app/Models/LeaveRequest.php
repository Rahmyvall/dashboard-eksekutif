<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     */
    protected $table = 'leave_requests';

    /**
     * Field yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment_path',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * Nilai default saat record dibuat.
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'employee_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_days' => 'integer',
        'approved_by' => 'integer',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Daftar status yang diperbolehkan.
     */
    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    /**
     * Mengembalikan daftar status valid.
     *
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return self::STATUSES;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Pegawai yang mengajukan cuti.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    /**
     * User/admin yang menyetujui atau menolak pengajuan.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Filter pengajuan dengan status pending.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_PENDING
        );
    }

    /**
     * Filter pengajuan yang sudah disetujui.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_APPROVED
        );
    }

    /**
     * Filter pengajuan yang ditolak.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_REJECTED
        );
    }

    /**
     * Filter berdasarkan employee.
     */
    public function scopeByEmployee(
        Builder $query,
        int $employeeId
    ): Builder {
        return $query->where(
            'employee_id',
            $employeeId
        );
    }

    /**
     * Filter berdasarkan jenis cuti.
     */
    public function scopeByLeaveType(
        Builder $query,
        string $leaveType
    ): Builder {
        $normalizedLeaveType = strtolower(trim($leaveType));

        if ($normalizedLeaveType === '') {
            return $query;
        }

        return $query->where('leave_type', $normalizedLeaveType);
    }

    /**
     * Filter berdasarkan status pengajuan.
     */
    public function scopeByStatus(
        Builder $query,
        ?string $status
    ): Builder {
        $normalizedStatus = strtolower(trim((string) $status));

        if ($normalizedStatus === '' || ! in_array($normalizedStatus, self::STATUSES, true)) {
            return $query;
        }

        return $query->where('status', $normalizedStatus);
    }

    /**
     * Filter berdasarkan rentang tanggal pengajuan cuti.
     */
    public function scopeBetweenDates(
        Builder $query,
        string $startDate,
        string $endDate
    ): Builder {
        return $query
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate);
    }

    /**
     * Filter berdasarkan rentang tanggal mulai dan selesai.
     */
    public function scopeDateRange(
        Builder $query,
        ?string $startDate,
        ?string $endDate
    ): Builder {
        return $query
            ->when(
                filled($startDate),
                fn(Builder $builder): Builder => $builder->whereDate('start_date', '>=', $startDate)
            )
            ->when(
                filled($endDate),
                fn(Builder $builder): Builder => $builder->whereDate('end_date', '<=', $endDate)
            );
    }

    /**
     * Pencarian sederhana berdasarkan alasan dan data pegawai.
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($keyword): void {
            $subQuery
                ->where('leave_type', 'like', "%{$keyword}%")
                ->orWhere('reason', 'like', "%{$keyword}%")
                ->orWhereHas('employee', function (Builder $employeeQuery) use ($keyword): void {
                    $employeeQuery
                        ->where('full_name', 'like', "%{$keyword}%")
                        ->orWhere('employee_number', 'like', "%{$keyword}%");
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Apakah pengajuan masih menunggu approval?
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Apakah pengajuan sudah disetujui?
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Apakah pengajuan ditolak?
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approve pengajuan cuti.
     */
    public function approve(int $userId): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject pengajuan cuti.
     */
    public function reject(int $userId): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Mengembalikan pengajuan menjadi pending.
     */
    public function resetToPending(): bool
    {
        return $this->update([
            'status' => self::STATUS_PENDING,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Hitung durasi pengajuan dalam hari secara inklusif.
     */
    public function calculateTotalDays(): int
    {
        $startDate = $this->start_date instanceof Carbon
            ? $this->start_date->copy()->startOfDay()
            : Carbon::parse((string) $this->start_date)->startOfDay();

        $endDate = $this->end_date instanceof Carbon
            ? $this->end_date->copy()->startOfDay()
            : Carbon::parse((string) $this->end_date)->startOfDay();

        return max(1, (int) $startDate->diffInDays($endDate) + 1);
    }

    /**
     * Sinkronkan total hari berdasarkan start_date dan end_date.
     */
    public function syncTotalDays(): static
    {
        $this->total_days = $this->calculateTotalDays();

        return $this;
    }
}