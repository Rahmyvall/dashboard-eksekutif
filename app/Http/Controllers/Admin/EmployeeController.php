<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\View\View;
use Throwable;

class EmployeeController extends Controller
{
    private const EMPLOYEE_NUMBER_PREFIX = 'EMP-';

    private const EMPLOYEE_NUMBER_PADDING = 4;

    /*
     * Angka tetap untuk PostgreSQL advisory transaction lock.
     * Tujuannya agar dua proses penyimpanan tidak membuat nomor yang sama.
     */
    private const EMPLOYEE_NUMBER_LOCK_KEY = 26080301;

    /**
     * Menampilkan daftar employee.
     */
    public function index(Request $request): View
    {
        $search           = trim((string) $request->input('search', ''));
        $departmentId     = $request->integer('department_id') ?: null;
        $positionId       = $request->integer('position_id') ?: null;
        $gender           = $request->input('gender');
        $employmentStatus = $request->input('employment_status');
        $status           = $request->input('status');
        $likeOperator     = $this->likeOperator();

        $employees = Employee::query()
            ->with([
                'user:id,name,email',
                'department:id,code,name',
                'position:id,name',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use (
                    $search,
                    $likeOperator
                ): void {
                    $query->where(
                        function (Builder $subQuery) use (
                            $search,
                            $likeOperator
                        ): void {
                            $subQuery
                                ->where(
                                    'employee_number',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'full_name',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'department',
                                    fn(Builder $departmentQuery) =>
                                    $departmentQuery->where(
                                        'name',
                                        $likeOperator,
                                        "%{$search}%"
                                    )
                                )
                                ->orWhereHas(
                                    'position',
                                    fn(Builder $positionQuery) =>
                                    $positionQuery->where(
                                        'name',
                                        $likeOperator,
                                        "%{$search}%"
                                    )
                                );
                        }
                    );
                }
            )
            ->when(
                $departmentId,
                fn(Builder $query) =>
                $query->where('department_id', $departmentId)
            )
            ->when(
                $positionId,
                fn(Builder $query) =>
                $query->where('position_id', $positionId)
            )
            ->when(
                filled($gender),
                fn(Builder $query) =>
                $query->where('gender', $gender)
            )
            ->when(
                filled($employmentStatus),
                fn(Builder $query) =>
                $query->where(
                    'employment_status',
                    $employmentStatus
                )
            )
            ->when(
                in_array($status, ['active', 'inactive'], true),
                fn(Builder $query) =>
                $query->where('status', $status)
            )
            ->orderBy('full_name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'super-admin.employees.index',
            array_merge(
                [
                    'employees'        => $employees,
                    'search'           => $search,
                    'departmentId'     => $departmentId,
                    'positionId'       => $positionId,
                    'gender'           => $gender,
                    'employmentStatus' => $employmentStatus,
                    'status'           => $status,
                ],
                $this->getFilterOptions()
            )
        );
    }

    /**
     * Menampilkan form tambah employee.
     */
    public function create(): View
    {
        return view(
            'super-admin.employees.create',
            array_merge(
                $this->getFormOptions(),
                [
                    /*
                     * Hanya preview. Nomor final dibuat ulang saat store().
                     */
                    'nextEmployeeNumber' =>
                    $this->calculateNextEmployeeNumber(),
                ]
            )
        );
    }

    /**
     * Menyimpan employee baru.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
         * employee_number tidak diambil dari request.
         * Nomor selalu dibuat oleh server.
         */
        $validated         = $this->validateEmployee($request);
        $uploadedPhotoPath = null;

        try {
            if ($request->hasFile('photo')) {
                $uploadedPhotoPath = $request
                    ->file('photo')
                    ->store('employees/photos', 'public');
            }

            unset(
                $validated['photo'],
                $validated['remove_photo'],
                $validated['employee_number']
            );

            $validated['photo_path'] = $uploadedPhotoPath;

            $employee = DB::transaction(
                function () use ($validated): Employee {
                    $this->acquireEmployeeNumberLock();

                    $data                    = $this->normalizeEmployeeData($validated);
                    $data['employee_number'] =
                    $this->calculateNextEmployeeNumber();

                    /*
                     * forceFill dipakai agar penyimpanan tidak gagal hanya
                     * karena kolom belum dimasukkan ke $fillable model.
                     */
                    $employee = new Employee();
                    $employee->forceFill($data);
                    $employee->save();

                    return $employee;
                },
                3
            );

            return redirect()
                ->route('super-admin.employees.index')
                ->with(
                    'success',
                    "Employee berhasil ditambahkan dengan nomor "
                    . "{$employee->employee_number}."
                );
        } catch (Throwable $exception) {
            report($exception);

            if ($uploadedPhotoPath) {
                Storage::disk('public')->delete($uploadedPhotoPath);
            }

            return back()
                ->withInput(
                    $request->except([
                        'photo',
                        'employee_number',
                    ])
                )
                ->with(
                    'error',
                    $this->saveErrorMessage(
                        $exception,
                        'Employee gagal ditambahkan.'
                    )
                );
        }
    }

    /**
     * Menampilkan detail employee.
     */
    public function show(Employee $employee): View
    {
        $employee->load([
            'user:id,name,email',
            'department:id,code,name',
            'position:id,name',
        ]);

        return view(
            'super-admin.employees.show',
            compact('employee')
        );
    }

    /**
     * Menampilkan form edit employee.
     */
    public function edit(Employee $employee): View
    {
        return view(
            'super-admin.employees.edit',
            array_merge(
                ['employee' => $employee],
                $this->getFormOptions()
            )
        );
    }

    /**
     * Memperbarui employee.
     */
    public function update(
        Request $request,
        Employee $employee
    ): RedirectResponse {
        $validated = $this->validateEmployee(
            $request,
            $employee
        );

        $oldPhotoPath   = $employee->photo_path;
        $newPhotoPath   = null;
        $removeOldPhoto = (bool) (
            $validated['remove_photo'] ?? false
        );

        try {
            if ($request->hasFile('photo')) {
                $newPhotoPath = $request
                    ->file('photo')
                    ->store('employees/photos', 'public');
            }

            unset(
                $validated['photo'],
                $validated['remove_photo']
            );

            /*
             * Nomor tidak boleh hilang jika input edit tidak mengirimkannya.
             */
            $validated['employee_number'] =
            filled($validated['employee_number'] ?? null)
                ? $validated['employee_number']
                : $employee->employee_number;

            if ($newPhotoPath) {
                $validated['photo_path'] = $newPhotoPath;
            } elseif ($removeOldPhoto) {
                $validated['photo_path'] = null;
            }

            DB::transaction(
                function () use (
                    $employee,
                    $validated
                ): void {
                    $employee->forceFill(
                        $this->normalizeEmployeeData($validated)
                    );
                    $employee->save();
                },
                3
            );

            if (
                $oldPhotoPath
                && (
                    $newPhotoPath
                    || $removeOldPhoto
                )
            ) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            return redirect()
                ->route('super-admin.employees.index')
                ->with(
                    'success',
                    'Employee berhasil diperbarui.'
                );
        } catch (Throwable $exception) {
            report($exception);

            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            return back()
                ->withInput($request->except('photo'))
                ->with(
                    'error',
                    $this->saveErrorMessage(
                        $exception,
                        'Employee gagal diperbarui.'
                    )
                );
        }
    }

    /**
     * Menghapus employee menggunakan soft delete.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        try {
            DB::transaction(
                fn() => $employee->delete(),
                3
            );

            return redirect()
                ->route('super-admin.employees.index')
                ->with(
                    'success',
                    'Employee berhasil dihapus.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                $this->saveErrorMessage(
                    $exception,
                    'Employee gagal dihapus.'
                )
            );
        }
    }

    /**
     * Menampilkan employee yang sudah dihapus.
     */
    public function trash(Request $request): View
    {
        $search       = trim((string) $request->input('search', ''));
        $likeOperator = $this->likeOperator();

        $employees = Employee::onlyTrashed()
            ->with([
                'user:id,name,email',
                'department:id,code,name',
                'position:id,name',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use (
                    $search,
                    $likeOperator
                ): void {
                    $query->where(
                        function (Builder $subQuery) use (
                            $search,
                            $likeOperator
                        ): void {
                            $subQuery
                                ->where(
                                    'employee_number',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'full_name',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    $likeOperator,
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    $likeOperator,
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'super-admin.employees.trash',
            compact('employees', 'search')
        );
    }

    /**
     * Mengembalikan employee yang sudah dihapus.
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($id): void {
                    Employee::onlyTrashed()
                        ->findOrFail($id)
                        ->restore();
                },
                3
            );

            return redirect()
                ->route('super-admin.employees.index')
                ->with(
                    'success',
                    'Employee berhasil dikembalikan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                $this->saveErrorMessage(
                    $exception,
                    'Employee gagal dikembalikan.'
                )
            );
        }
    }

    /**
     * Menghapus employee secara permanen.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $photoPath = null;

        try {
            DB::transaction(
                function () use (
                    $id,
                    &$photoPath
                ): void {
                    $employee = Employee::onlyTrashed()
                        ->findOrFail($id);

                    $photoPath = $employee->photo_path;
                    $employee->forceDelete();
                },
                3
            );

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return redirect()
                ->route('super-admin.employees.trash')
                ->with(
                    'success',
                    'Employee berhasil dihapus secara permanen.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                $this->saveErrorMessage(
                    $exception,
                    'Employee gagal dihapus secara permanen.'
                )
            );
        }
    }

    /**
     * Validasi field tabel employees.
     */
    private function validateEmployee(
        Request $request,
        ?Employee $employee = null
    ): array {
        $rules = [
            'user_id'           => [
                'nullable',
                'integer',
                'exists:users,id',
                Rule::unique('employees', 'user_id')
                    ->ignore($employee?->getKey()),
            ],

            'department_id'     => [
                'required',
                'integer',
                'exists:departments,id',
            ],

            'position_id'       => [
                'required',
                'integer',
                'exists:positions,id',
            ],

            'full_name'         => [
                'required',
                'string',
                'max:150',
            ],

            'gender'            => [
                'required',
                Rule::in([
                    'male',
                    'female',
                    'l',
                    'p',
                ]),
            ],

            'birth_place'       => [
                'nullable',
                'string',
                'max:100',
            ],

            'birth_date'        => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'phone'             => [
                'nullable',
                'string',
                'max:30',
            ],

            'email'             => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('employees', 'email')
                    ->ignore($employee?->getKey()),
            ],

            'address'           => [
                'nullable',
                'string',
            ],

            'hire_date'         => [
                'required',
                'date',
            ],

            'employment_status' => [
                'required',
                Rule::in([
                    'permanent',
                    'contract',
                    'probation',
                    'internship',
                    'outsourcing',
                ]),
            ],

            'basic_salary'      => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'photo'             => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'remove_photo'      => [
                'nullable',
                'boolean',
            ],

            'status'            => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],
        ];

        /*
         * Pada create nomor tidak divalidasi dari request karena dibuat server.
         * Pada edit nomor lama tetap boleh dikirim dan harus unik.
         */
        if ($employee) {
            $rules['employee_number'] = [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'employees',
                    'employee_number'
                )->ignore($employee->getKey()),
            ];
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'user_id.exists'             =>
                'Akun pengguna tidak ditemukan.',
                'user_id.unique'             =>
                'Akun pengguna sudah terhubung ke employee lain.',

                'department_id.required'     =>
                'Department wajib dipilih.',
                'department_id.exists'       =>
                'Department yang dipilih tidak ditemukan.',

                'position_id.required'       =>
                'Jabatan wajib dipilih.',
                'position_id.exists'         =>
                'Jabatan yang dipilih tidak ditemukan.',

                'employee_number.required'   =>
                'Nomor employee wajib tersedia.',
                'employee_number.unique'     =>
                'Nomor employee sudah digunakan.',
                'employee_number.max'        =>
                'Nomor employee maksimal 50 karakter.',

                'full_name.required'         =>
                'Nama lengkap wajib diisi.',
                'full_name.max'              =>
                'Nama lengkap maksimal 150 karakter.',

                'gender.required'            =>
                'Jenis kelamin wajib dipilih.',
                'gender.in'                  =>
                'Jenis kelamin tidak valid.',

                'birth_date.before_or_equal' =>
                'Tanggal lahir tidak boleh melebihi hari ini.',

                'email.email'                =>
                'Format email tidak valid.',
                'email.unique'               =>
                'Email employee sudah digunakan.',

                'hire_date.required'         =>
                'Tanggal mulai bekerja wajib diisi.',

                'employment_status.required' =>
                'Status kepegawaian wajib dipilih.',
                'employment_status.in'       =>
                'Status kepegawaian tidak valid.',

                'basic_salary.numeric'       =>
                'Gaji pokok harus berupa angka.',
                'basic_salary.min'           =>
                'Gaji pokok tidak boleh negatif.',

                'photo.image'                =>
                'File foto harus berupa gambar.',
                'photo.mimes'                =>
                'Format foto harus JPG, JPEG, PNG, atau WEBP.',
                'photo.max'                  =>
                'Ukuran foto maksimal 2 MB.',

                'status.required'            =>
                'Status employee wajib dipilih.',
                'status.in'                  =>
                'Status employee tidak valid.',
            ]
        );

        /*
         * Mencegah jabatan dari department lain disimpan.
         */
        $validator->after(
            function (
                ValidationValidator $validator
            ) use ($request): void {
                if (
                    ! $request->filled('department_id')
                    || ! $request->filled('position_id')
                ) {
                    return;
                }

                $positionMatchesDepartment =
                Position::query()
                    ->whereKey(
                        $request->integer('position_id')
                    )
                    ->where(
                        'department_id',
                        $request->integer('department_id')
                    )
                    ->exists();

                if (! $positionMatchesDepartment) {
                    $validator->errors()->add(
                        'position_id',
                        'Jabatan tidak sesuai dengan department yang dipilih.'
                    );
                }
            }
        );

        return $validator->validate();
    }

    /**
     * Merapikan data sebelum disimpan.
     */
    private function normalizeEmployeeData(array $data): array
    {
        foreach (
            [
                'employee_number',
                'full_name',
                'gender',
                'birth_place',
                'phone',
                'email',
                'employment_status',
                'status',
            ] as $field
        ) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            $data[$field] = filled($data[$field])
                ? trim((string) $data[$field])
                : null;
        }

        if (filled($data['employee_number'] ?? null)) {
            $data['employee_number'] = strtoupper(
                (string) $data['employee_number']
            );
        }

        if (filled($data['email'] ?? null)) {
            $data['email'] = strtolower(
                (string) $data['email']
            );
        }

        if (array_key_exists('address', $data)) {
            $data['address'] = filled($data['address'])
                ? trim((string) $data['address'])
                : null;
        }

        return $data;
    }

    /**
     * Mengunci generator nomor employee.
     */
    private function acquireEmployeeNumberLock(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::selectOne(
                'SELECT pg_advisory_xact_lock(?)',
                [self::EMPLOYEE_NUMBER_LOCK_KEY]
            );

            return;
        }

        /*
         * Fallback MySQL/SQLite/driver lain.
         * Lock baris terakhir jika sudah ada data employee.
         */
        DB::table('employees')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();
    }

    /**
     * Menghasilkan EMP-0001, EMP-0002, dan seterusnya.
     *
     * Data soft delete tetap dihitung agar nomor tidak digunakan ulang.
     */
    private function calculateNextEmployeeNumber(): string
    {
        $highestSequence = Employee::withTrashed()
            ->whereNotNull('employee_number')
            ->pluck('employee_number')
            ->reduce(
                static function (
                    int $highest,
                    mixed $employeeNumber
                ): int {
                    $employeeNumber = strtoupper(
                        trim((string) $employeeNumber)
                    );

                    if (
                        preg_match(
                            '/^EMP-(\d+)$/',
                            $employeeNumber,
                            $matches
                        ) !== 1
                    ) {
                        return $highest;
                    }

                    return max(
                        $highest,
                        (int) $matches[1]
                    );
                },
                0
            );

        do {
            $highestSequence++;

            $candidate = self::EMPLOYEE_NUMBER_PREFIX
            . str_pad(
                (string) $highestSequence,
                self::EMPLOYEE_NUMBER_PADDING,
                '0',
                STR_PAD_LEFT
            );
        } while (
            Employee::withTrashed()
            ->where('employee_number', $candidate)
            ->exists()
        );

        return $candidate;
    }

    /**
     * Pilihan filter halaman index.
     */
    private function getFilterOptions(): array
    {
        return [
            'departments' => $this->activeDepartments(),
            'positions'   => $this->activePositions(),
        ];
    }

    /**
     * Pilihan form create dan edit.
     */
    private function getFormOptions(): array
    {
        $usersQuery = User::query();

        /*
         * Tidak mengasumsikan nama kolom status pada tabel users.
         */
        if (Schema::hasColumn('users', 'is_active')) {
            $usersQuery->where('is_active', true);
        } elseif (Schema::hasColumn('users', 'status')) {
            $usersQuery->where('status', 'active');
        }

        $users = $usersQuery
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);

        return [
            'departments' => $this->activeDepartments(),
            'positions'   => $this->activePositions(),
            'users'       => $users,
        ];
    }

    /**
     * Department aktif tanpa mengasumsikan kolom status selalu ada.
     */
    private function activeDepartments()
    {
        $query = Department::query();

        if (Schema::hasColumn('departments', 'status')) {
            $query->where('status', 'active');
        }

        return $query
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
            ]);
    }

    /**
     * Position aktif tanpa mengasumsikan kolom status selalu ada.
     */
    private function activePositions()
    {
        $query = Position::query();

        if (Schema::hasColumn('positions', 'status')) {
            $query->where('status', 'active');
        }

        return $query
            ->orderBy('name')
            ->get([
                'id',
                'department_id',
                'name',
            ]);
    }

    /**
     * Operator pencarian sesuai database.
     */
    private function likeOperator(): string
    {
        return DB::connection()->getDriverName() === 'pgsql'
            ? 'ilike'
            : 'like';
    }

    /**
     * Pesan error aman untuk production dan rinci saat APP_DEBUG=true.
     */
    private function saveErrorMessage(
        Throwable $exception,
        string $defaultMessage
    ): string {
        if (config('app.debug')) {
            return $defaultMessage
            . ' Detail: '
            . $exception->getMessage();
        }

        return $defaultMessage
            . ' Silakan periksa data lalu coba kembali.';
    }
}
