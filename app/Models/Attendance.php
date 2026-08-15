<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (self $attendance): void {
            if ($attendance->isDirty('check_in') || $attendance->isDirty('check_out')) {
                $attendance->work_duration_minutes = $attendance->calculateWorkDurationMinutes();
            }
        });
    }

    public const STATUS_PRESENT = 'present';
    public const STATUS_LATE = 'late';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_SICK = 'sick';
    public const STATUS_LEAVE = 'leave';
    public const STATUS_HOLIDAY = 'holiday';

    /**
     * Nama tabel database.
     */
    protected $table = 'attendances';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key menggunakan auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * Mengaktifkan created_at dan updated_at.
     */
    public $timestamps = true;

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'work_duration_minutes',
        'late_minutes',
        'overtime_minutes',
        'status',
        'notes',
    ];

    /**
     * Nilai default saat data dibuat.
     */
    protected $attributes = [
        'work_duration_minutes' => 0,
        'late_minutes' => 0,
        'overtime_minutes' => 0,
        'status' => self::STATUS_PRESENT,
    ];

    /**
     * Konversi tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'employee_id' => 'integer',

            'attendance_date' => 'date:Y-m-d',

            'work_duration_minutes' => 'integer',
            'late_minutes' => 'integer',
            'overtime_minutes' => 'integer',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Pegawai pemilik data absensi.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Filter berdasarkan pegawai.
     */
    public function scopeByEmployee(
        Builder $query,
        int | string $employeeId
    ): Builder {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Filter berdasarkan status absensi.
     */
    public function scopeByStatus(
        Builder $query,
        string $status
    ): Builder {
        return $query->where('status', strtolower(trim($status)));
    }

    /**
     * Filter berdasarkan tanggal absensi.
     */
    public function scopeByDate(
        Builder $query,
        string $date
    ): Builder {
        return $query->whereDate('attendance_date', $date);
    }

    /**
     * Filter berdasarkan rentang tanggal absensi.
     */
    public function scopeDateRange(
        Builder $query,
        string $startDate,
        string $endDate
    ): Builder {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    /**
     * Data absensi hari ini.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('attendance_date', now()->toDateString());
    }

    /**
     * Data absensi dengan status hadir.
     */
    public function scopePresent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /**
     * Data absensi dengan status terlambat.
     */
    public function scopeLate(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LATE);
    }

    /**
     * Data absensi dengan status tidak hadir.
     */
    public function scopeAbsent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Daftar status valid untuk absensi.
     *
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PRESENT,
            self::STATUS_LATE,
            self::STATUS_ABSENT,
            self::STATUS_SICK,
            self::STATUS_LEAVE,
            self::STATUS_HOLIDAY,
        ];
    }

    /**
     * Memeriksa apakah status absensi sama dengan status tertentu.
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === strtolower(trim($status));
    }

    /**
     * Memeriksa apakah sudah check in.
     */
    public function hasCheckedIn(): bool
    {
        return filled($this->check_in);
    }

    /**
     * Memeriksa apakah sudah check out.
     */
    public function hasCheckedOut(): bool
    {
        return filled($this->check_out);
    }

    /**
     * Durasi kerja dalam format jam desimal.
     */
    public function getWorkDurationHoursAttribute(): float
    {
        return round(((int) $this->work_duration_minutes) / 60, 2);
    }

    /**
     * Durasi kerja dalam format HH:MM.
     */
    public function getWorkDurationFormattedAttribute(): string
    {
        $minutes = max(0, (int) $this->work_duration_minutes);
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }

    /**
     * Hitung durasi kerja otomatis dari check in dan check out.
     */
    public function calculateWorkDurationMinutes(): int
    {
        $checkIn = $this->parseTime($this->check_in);
        $checkOut = $this->parseTime($this->check_out);

        if (! $checkIn || ! $checkOut) {
            return 0;
        }

        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            $checkOut->addDay();
        }

        return (int) $checkIn->diffInMinutes($checkOut);
    }

    /**
     * Sinkronkan kolom work_duration_minutes dari jam check in/out.
     */
    public function syncWorkDuration(): static
    {
        $this->work_duration_minutes = $this->calculateWorkDurationMinutes();

        return $this;
    }

    /**
     * Parsing value jam ke objek Carbon.
     */
    private function parseTime(mixed $value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        $stringValue = trim((string) $value);

        foreach (['H:i:s', 'H:i'] as $format) {
            try {
                return Carbon::createFromFormat($format, $stringValue);
            } catch (\Throwable) {
                // continue to next format
            }
        }

        try {
            return Carbon::parse($stringValue);
        } catch (\Throwable) {
            return null;
        }
    }
}