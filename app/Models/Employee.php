<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel database.
     */
    protected $table = 'employees';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key menggunakan auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe primary key.
     */
    protected $keyType = 'int';

    /**
     * Laravel akan otomatis mengelola:
     * created_at dan updated_at.
     */
    public $timestamps = true;

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'employee_number',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'phone',
        'email',
        'address',
        'hire_date',
        'employment_status',
        'basic_salary',
        'photo_path',
        'status',
    ];

    /**
     * Konversi tipe data ketika data diambil dari database.
     */
    protected $casts = [
        'id'            => 'integer',
        'user_id'       => 'integer',
        'department_id' => 'integer',
        'position_id'   => 'integer',

        'birth_date'    => 'date:Y-m-d',
        'hire_date'     => 'date:Y-m-d',

        'basic_salary'  => 'decimal:2',

        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Atribut tambahan saat model diubah menjadi array atau JSON.
     */
    protected $appends = [
        'photo_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi pegawai dengan akun pengguna.
     *
     * employees.user_id -> users.id
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi pegawai dengan departemen.
     *
     * employees.department_id -> departments.id
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Relasi pegawai dengan jabatan.
     *
     * employees.position_id -> positions.id
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    /**
     * Relasi pegawai dengan data absensi.
     *
     * employees.id -> attendances.employee_id
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }

    /**
     * Relasi pegawai dengan pengajuan cuti.
     *
     * employees.id -> leave_requests.employee_id
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan URL foto pegawai.
     *
     * Contoh nilai photo_path:
     * employees/photos/foto-pegawai.jpg
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (empty($this->photo_path)) {
            return null;
        }

        /*
         * Jika photo_path sudah berupa URL lengkap,
         * langsung kembalikan nilainya.
         */
        if (
            str_starts_with($this->photo_path, 'http://') ||
            str_starts_with($this->photo_path, 'https://')
        ) {
            return $this->photo_path;
        }

        return Storage::disk('public')->url($this->photo_path);
    }

    /**
     * Mendapatkan nama departemen dengan aman.
     */
    public function getDepartmentNameAttribute(): ?string
    {
        return $this->department?->name;
    }

    /**
     * Mendapatkan nama jabatan dengan aman.
     */
    public function getPositionNameAttribute(): ?string
    {
        return $this->position?->name;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil pegawai yang berstatus aktif.
     *
     * Penggunaan:
     * Employee::active()->get();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Filter berdasarkan departemen.
     *
     * Penggunaan:
     * Employee::byDepartment(1)->get();
     */
    public function scopeByDepartment(
        Builder $query,
        int | string | null $departmentId
    ): Builder {
        return $query->when(
            $departmentId,
            fn(Builder $query) => $query->where(
                'department_id',
                $departmentId
            )
        );
    }

    /**
     * Filter berdasarkan jabatan.
     *
     * Penggunaan:
     * Employee::byPosition(2)->get();
     */
    public function scopeByPosition(
        Builder $query,
        int | string | null $positionId
    ): Builder {
        return $query->when(
            $positionId,
            fn(Builder $query) => $query->where(
                'position_id',
                $positionId
            )
        );
    }

    /**
     * Filter berdasarkan status kepegawaian.
     *
     * Penggunaan:
     * Employee::byEmploymentStatus('permanent')->get();
     */
    public function scopeByEmploymentStatus(
        Builder $query,
        ?string $employmentStatus
    ): Builder {
        return $query->when(
            $employmentStatus,
            fn(Builder $query) => $query->where(
                'employment_status',
                $employmentStatus
            )
        );
    }

    /**
     * Pencarian data pegawai.
     *
     * Penggunaan:
     * Employee::search('Budi')->get();
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        return $query->when(
            $keyword,
            function (Builder $query) use ($keyword): void {
                $query->where(function (Builder $subQuery) use ($keyword): void {
                    $subQuery
                        ->where('employee_number', 'like', "%{$keyword}%")
                        ->orWhere('full_name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            }
        );
    }

    /**
     * Filter pegawai berdasarkan jenis kelamin.
     */
    public function scopeByGender(
        Builder $query,
        ?string $gender
    ): Builder {
        return $query->when(
            $gender,
            fn(Builder $query) => $query->where('gender', $gender)
        );
    }

    /**
     * Menampilkan pegawai terbaru.
     */
    public function scopeLatestEmployee(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
