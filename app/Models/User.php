<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
use Notifiable;


    protected $guard_name = 'web';
    protected $table = 'users';

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_DIREKTUR_UTAMA = 'direktur_utama';
    public const ROLE_HRD = 'hrd_manager';
    public const ROLE_MANAGER_DEPARTEMEN = 'manager_departemen';
    public const ROLE_KARYAWAN = 'karyawan';
    public const ROLE_ADMIN_PELAYANAN = 'admin_pelayanan';
    public const ROLE_ADMIN_OPERASIONAL = 'admin_operasional';
    public const ROLE_FINANCE_STAFF = 'finance_staff';
    public const ROLE_AUDITOR = 'auditor_internal';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SUSPENDED = 'suspended';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'photo',
        'status',
        'role_id',
        'role',
        'position_id',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function getRoleNames(): \Illuminate\Support\Collection
    {
        return $this->roles()->pluck('roles.name');
    }

    public function hasRole(string|array $roles): bool
    {
        $aliases = [
            'super_admin' => ['super_admin', 'super administrator', 'superadministrator'],
            'direktur_utama' => ['direktur_utama', 'direktur manager', 'direktur_manager', 'direktur utama', 'direkturutama', 'executive'],
            'hrd_manager' => ['hrd_manager', 'hrd manager', 'hrdmanager', 'hr'],
            'manager_departemen' => ['manager_departemen', 'manager departemen', 'managerdepartemen'],
            'karyawan' => ['karyawan', 'pegawai', 'employee'],
            'admin_pelayanan' => ['admin_pelayanan', 'admin pelayanan', 'adminpelayanan'],
            'admin_operasional' => ['admin_operasional', 'admin operasional', 'adminoperasional'],
            'finance_staff' => ['finance_staff', 'finance staff', 'finance'],
            'auditor_internal' => ['auditor_internal', 'auditor internal', 'auditor'],
        ];

        $inputRoles = collect((array) $roles)
            ->map(fn($role): string => (string) $role)
            ->flatMap(fn(string $role): array => $aliases[$this->normalizeRoleName($role)] ?? [$role])
            ->map(fn(string $role): string => $this->normalizeRoleName($role))
            ->unique()
            ->values()
            ->all();

        $dbRoleNames = $this->roles()->pluck('roles.name')->all();

        foreach ($dbRoleNames as $dbRoleName) {
            $normalizedDbRole = $this->normalizeRoleName((string) $dbRoleName);

            if (in_array($normalizedDbRole, $inputRoles, true)) {
                return true;
            }

            foreach ($inputRoles as $inputRole) {
                if ($normalizedDbRole === $inputRole) {
                    return true;
                }
            }
        }

        return false;
    }

    private function normalizeRoleName(string $role): string
    {
        return strtolower(str_replace(['-', ' '], '_', trim($role)));
    }

    public function hasAnyRole(string|array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    public function isDirekturUtama(): bool
    {
        return $this->hasRole(self::ROLE_DIREKTUR_UTAMA);
    }

    public function isHrd(): bool
    {
        return $this->hasRole(self::ROLE_HRD);
    }

    public function isManagerDepartemen(): bool
    {
        return $this->hasRole(self::ROLE_MANAGER_DEPARTEMEN);
    }

    public function isKaryawan(): bool
    {
        return $this->hasRole(self::ROLE_KARYAWAN);
    }

    public function isAdminPelayanan(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN_PELAYANAN);
    }

    public function isAdminOperasional(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN_OPERASIONAL);
    }

    public function isFinanceStaff(): bool
    {
        return $this->hasRole(self::ROLE_FINANCE_STAFF);
    }

    public function isAuditor(): bool
    {
        return $this->hasRole(self::ROLE_AUDITOR);
    }

    public function activeRole(): ?Role
    {
        $roleId = session('active_role_id');

        if (! $roleId) {
            return null;
        }

        return $this->roles()->whereKey($roleId)->first();
    }

    public function activeRoleName(): ?string
    {
        return session('active_role_name');
    }

    public function checkPassword(string $password): bool
    {
        return ! empty($this->password) && Hash::check($password, $this->password);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->is_active !== false;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function resolveEmployee(): ?Employee
    {
        if ($this->employee_id !== null) {
            if ($this->relationLoaded('assignedEmployee')) {
                return $this->getRelation('assignedEmployee');
            }

            $employee = $this->assignedEmployee()->first();

            if ($employee !== null) {
                return $employee;
            }
        }

        if ($this->relationLoaded('employee')) {
            return $this->getRelation('employee');
        }

        return $this->employee()->first();
    }

    public function approvedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by', 'id');
    }

    public function canAccessDashboard(): bool
    {
        return $this->isActive() && $this->roles()->exists();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByRole(Builder $query, string|array $roles): Builder
    {
        return $query->whereHas('roles', function (Builder $query) use ($roles): void {
            $query->whereIn('roles.name', (array) $roles);
        });
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_SUSPENDED,
        ];
    }

    public function markLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public function updateLoginInfo(): void
    {
        $this->markLogin();
    }
}
