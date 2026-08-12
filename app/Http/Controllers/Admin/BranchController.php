<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BranchController extends Controller
{
    /**
     * Urutan role yang wajib memberikan persetujuan.
     */
    private const APPROVAL_FLOW = [
        'direktur_utama',
        'auditor_internal',
    ];

    /**
     * Role yang boleh mengajukan tambah, edit, dan hapus cabang.
     */
    private const MANAGER_ROLES = [
        'super_admin',
        'admin_operasional',
    ];

    /**
     * Role yang boleh melihat modul cabang.
     */
    private const VIEWER_ROLES = [
        'super_admin',
        'direktur_utama',
        'hrd_manager',
        'manager_departemen',
        'karyawan',
        'admin_pelayanan',
        'admin_operasional',
        'finance_staff',
        'auditor_internal',
    ];

    /**
     * Singkatan lokasi untuk kode cabang.
     *
     * Format akhir:
     * CBG-{KODE_LOKASI}-{NOMOR_URUT}
     *
     * Contoh:
     * Cabang Sragen   -> CBG-SRG-001
     * Cabang Bandung  -> CBG-BDG-001
     * Cabang Jakarta  -> CBG-JKT-001
     */
    private const BRANCH_CODE_ALIASES = [
        'BANDUNG'    => 'BDG',
        'BANYUMAS'   => 'BMS',
        'BATAM'      => 'BTM',
        'BEKASI'     => 'BKS',
        'BOGOR'      => 'BGR',
        'DEPOK'      => 'DPK',
        'JAKARTA'    => 'JKT',
        'MALANG'     => 'MLG',
        'MEDAN'      => 'MDN',
        'PALEMBANG'  => 'PLB',
        'PEKANBARU'  => 'PKU',
        'SEMARANG'   => 'SMG',
        'SOLO'       => 'SLO',
        'SRAGEN'     => 'SRG',
        'SURABAYA'   => 'SBY',
        'SURAKARTA'  => 'SKT',
        'TANGERANG'  => 'TGR',
        'YOGYAKARTA' => 'YGY',
    ];

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $this->ensureCanViewBranches($request);

        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'status' => [
                'nullable',
                'in:0,1',
            ],
        ]);

        $query = Branch::query()->with('manager');

        if (! empty($filters['search'])) {
            $keyword = mb_strtolower(trim($filters['search']));

            $query->where(function (Builder $query) use ($keyword): void {
                $query
                    ->whereRaw('LOWER(branch_code) LIKE ?', ["%{$keyword}%"])
                    ->orWhereRaw('LOWER(branch_name) LIKE ?', ["%{$keyword}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$keyword}%"]);
            });
        }

        if (array_key_exists('status', $filters) && $filters['status'] !== null) {
            $query->where('status', (int) $filters['status']);
        }

        $branches = $query
            ->orderBy('branch_name')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total_branches'    => Branch::count(),
            'active_branches'   => Branch::where('status', 1)->count(),
            'inactive_branches' => Branch::where('status', 0)->count(),
            'manager_count'     => Branch::query()
                ->whereNotNull('manager_id')
                ->distinct('manager_id')
                ->count('manager_id'),
            'deleted_branches'  => Branch::onlyTrashed()->count(),
        ];

        return view(
            'super-admin.branches.index',
            compact('branches', 'stats')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request): View
    {
        $this->ensureCanManageBranches($request);

        $managers = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'super-admin.branches.create',
            compact('managers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE / SUBMIT CREATE APPROVAL
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $user      = $this->ensureCanManageBranches($request);
        $validated = $this->validateBranch($request);

        DB::transaction(function () use ($validated, $user): void {
            /*
             * Record cabang dibuat sebagai record pending agar memiliki ID dan
             * branch_code. Status operasional dibuat 0 sampai approval final.
             */
            $branch = Branch::create([
                'branch_code'           => 'TMP-' . Str::uuid()->toString(),
                'branch_name'           => $validated['branch_name'],
                'address'               => $validated['address'],
                'phone'                 => $validated['phone'],
                'email'                 => $validated['email'],
                'manager_id'            => $validated['manager_id'] ?? null,
                'status'                => 0,
                'approval_status'       => 'pending',
                'approval_action'       => 'create',
                'pending_approval_role' => self::APPROVAL_FLOW[0],
                'pending_payload'       => $validated,
                'submitted_by'          => $user->getKey(),
                'last_approved_by'      => null,
                'approved_at'           => null,
                'rejected_by'           => null,
                'rejected_at'           => null,
                'rejection_note'        => null,
            ]);

            $branch->forceFill([
                'branch_code' => $this->makeBranchCode(
                    branchName: $validated['branch_name'],
                    ignoreBranchId: (int) $branch->getKey(),
                    currentCode: null
                ),
            ])->save();

            $this->writeApprovalLog(
                branch: $branch,
                user: $user,
                action: 'submitted',
                fromStatus: null,
                toStatus: 'pending',
                nextApprovalRole: self::APPROVAL_FLOW[0],
                note: 'Pengajuan penambahan cabang baru.'
            );
        });

        return redirect()
            ->route('branches.index')
            ->with(
                'success',
                'Cabang berhasil diajukan dan menunggu persetujuan Direktur Utama.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, Branch $branch): View
    {
        $this->ensureCanViewBranches($request);

        $branch->load('manager');

        return view(
            'super-admin.branches.show',
            compact('branch')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request, Branch $branch): View
    {
        $this->ensureCanManageBranches($request);

        if ($branch->approval_status === 'pending') {
            throw ValidationException::withMessages([
                'approval' => 'Cabang masih memiliki pengajuan yang menunggu persetujuan.',
            ]);
        }

        $managers = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'super-admin.branches.edit',
            compact('branch', 'managers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE / SUBMIT UPDATE APPROVAL
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $user      = $this->ensureCanManageBranches($request);
        $validated = $this->validateBranch($request, $branch);

        DB::transaction(function () use ($branch, $validated, $user): void {
            $lockedBranch = Branch::query()
                ->lockForUpdate()
                ->findOrFail($branch->getKey());

            if ($lockedBranch->approval_status === 'pending') {
                throw ValidationException::withMessages([
                    'approval' => 'Masih ada pengajuan cabang yang belum selesai.',
                ]);
            }

            /*
             * Jika cabang baru sebelumnya ditolak, pengajuan ulang tetap
             * dikategorikan sebagai create. Selain itu dikategorikan update.
             */
            $approvalAction = $lockedBranch->approval_status === 'rejected'
            && $lockedBranch->approval_action === 'create'
                ? 'create'
                : 'update';

            $fromStatus = $lockedBranch->approval_status;

            $lockedBranch->forceFill([
                'approval_status'       => 'pending',
                'approval_action'       => $approvalAction,
                'pending_approval_role' => self::APPROVAL_FLOW[0],
                'pending_payload'       => $validated,
                'submitted_by'          => $user->getKey(),
                'last_approved_by'      => null,
                'approved_at'           => null,
                'rejected_by'           => null,
                'rejected_at'           => null,
                'rejection_note'        => null,
            ])->save();

            $this->writeApprovalLog(
                branch: $lockedBranch,
                user: $user,
                action: 'submitted',
                fromStatus: $fromStatus,
                toStatus: 'pending',
                nextApprovalRole: self::APPROVAL_FLOW[0],
                note: $approvalAction === 'create'
                    ? 'Pengajuan ulang penambahan cabang setelah ditolak.'
                    : 'Pengajuan perubahan data cabang.'
            );
        });

        return redirect()
            ->route('branches.index')
            ->with(
                'success',
                'Perubahan cabang berhasil diajukan dan menunggu persetujuan Direktur Utama.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE / SUBMIT SOFT DELETE APPROVAL
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, Branch $branch): RedirectResponse
    {
        $user = $this->ensureCanManageBranches($request);

        DB::transaction(function () use ($branch, $user): void {
            $lockedBranch = Branch::query()
                ->lockForUpdate()
                ->findOrFail($branch->getKey());

            if ($lockedBranch->approval_status === 'pending') {
                throw ValidationException::withMessages([
                    'approval' => 'Masih ada pengajuan cabang yang belum selesai.',
                ]);
            }

            $fromStatus = $lockedBranch->approval_status;

            $lockedBranch->forceFill([
                'approval_status'       => 'pending',
                'approval_action'       => 'delete',
                'pending_approval_role' => self::APPROVAL_FLOW[0],
                'pending_payload'       => null,
                'submitted_by'          => $user->getKey(),
                'last_approved_by'      => null,
                'approved_at'           => null,
                'rejected_by'           => null,
                'rejected_at'           => null,
                'rejection_note'        => null,
            ])->save();

            $this->writeApprovalLog(
                branch: $lockedBranch,
                user: $user,
                action: 'submitted',
                fromStatus: $fromStatus,
                toStatus: 'pending',
                nextApprovalRole: self::APPROVAL_FLOW[0],
                note: 'Pengajuan penghapusan cabang.'
            );
        });

        return redirect()
            ->route('branches.index')
            ->with(
                'success',
                'Penghapusan cabang berhasil diajukan dan menunggu persetujuan Direktur Utama.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(Request $request, Branch $branch): RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        DB::transaction(function () use ($branch, $user): void {
            $lockedBranch = Branch::query()
                ->lockForUpdate()
                ->findOrFail($branch->getKey());

            if ($lockedBranch->approval_status !== 'pending') {
                throw ValidationException::withMessages([
                    'approval' => 'Pengajuan ini sudah tidak berstatus menunggu persetujuan.',
                ]);
            }

            $requiredRole = $lockedBranch->pending_approval_role;

            if (! is_string($requiredRole) || $requiredRole === '') {
                throw ValidationException::withMessages([
                    'approval' => 'Role persetujuan berikutnya belum ditentukan.',
                ]);
            }

            $isSuperAdmin = $user->hasRole('super_admin');

            if (! $isSuperAdmin && ! $user->hasRole($requiredRole)) {
                abort(
                    403,
                    'Pengajuan ini harus disetujui oleh role '
                    . $this->formatRoleName($requiredRole)
                    . '.'
                );
            }

            $currentIndex = array_search(
                $requiredRole,
                self::APPROVAL_FLOW,
                true
            );

            if ($currentIndex === false) {
                throw ValidationException::withMessages([
                    'approval' => 'Role persetujuan saat ini tidak terdaftar pada alur approval.',
                ]);
            }

            $nextRole = self::APPROVAL_FLOW[$currentIndex + 1] ?? null;

            $this->writeApprovalLog(
                branch: $lockedBranch,
                user: $user,
                action: 'approved',
                fromStatus: 'pending',
                toStatus: $nextRole === null ? 'approved' : 'pending',
                nextApprovalRole: $nextRole,
                note: $nextRole === null
                    ? 'Persetujuan final diberikan.'
                    : 'Tahap persetujuan diselesaikan.'
            );

            if ($nextRole !== null) {
                $lockedBranch->forceFill([
                    'pending_approval_role' => $nextRole,
                    'last_approved_by'      => $user->getKey(),
                    'rejected_by'           => null,
                    'rejected_at'           => null,
                    'rejection_note'        => null,
                ])->save();

                return;
            }

            $this->applyApprovedAction($lockedBranch, $user);
        });

        return redirect()
            ->route('branches.index')
            ->with('success', 'Tahap persetujuan cabang berhasil diproses.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $user = $this->authenticatedUser($request);

        DB::transaction(function () use ($branch, $user, $validated): void {
            $lockedBranch = Branch::query()
                ->lockForUpdate()
                ->findOrFail($branch->getKey());

            if ($lockedBranch->approval_status !== 'pending') {
                throw ValidationException::withMessages([
                    'approval' => 'Pengajuan ini sudah tidak berstatus menunggu persetujuan.',
                ]);
            }

            $requiredRole = $lockedBranch->pending_approval_role;

            if (! is_string($requiredRole) || $requiredRole === '') {
                throw ValidationException::withMessages([
                    'approval' => 'Role persetujuan berikutnya belum ditentukan.',
                ]);
            }

            $isSuperAdmin = $user->hasRole('super_admin');

            if (! $isSuperAdmin && ! $user->hasRole($requiredRole)) {
                abort(
                    403,
                    'Pengajuan ini harus diproses oleh role '
                    . $this->formatRoleName($requiredRole)
                    . '.'
                );
            }

            $note = trim($validated['rejection_note']);

            $this->writeApprovalLog(
                branch: $lockedBranch,
                user: $user,
                action: 'rejected',
                fromStatus: 'pending',
                toStatus: 'rejected',
                nextApprovalRole: null,
                note: $note
            );

            /*
             * approval_action dan pending_payload tidak dihapus agar pengaju
             * dapat melihat data yang ditolak dan mengajukannya kembali.
             */
            $lockedBranch->forceFill([
                'approval_status'       => 'rejected',
                'pending_approval_role' => null,
                'rejected_by'           => $user->getKey(),
                'rejected_at'           => now(),
                'rejection_note'        => $note,
                'approved_at'           => null,
            ])->save();
        });

        return redirect()
            ->route('branches.index')
            ->with('success', 'Pengajuan cabang telah ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | TRASH
    |--------------------------------------------------------------------------
    */

    public function trash(Request $request): View
    {
        $this->ensureSuperAdmin($request);

        $branches = Branch::onlyTrashed()
            ->with('manager')
            ->orderBy('branch_name')
            ->paginate(10);

        return view(
            'super-admin.branches.trash',
            compact('branches')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function restore(Request $request, int $id): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        $branch = Branch::onlyTrashed()->findOrFail($id);
        $branch->restore();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil dipulihkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */

    public function forceDelete(Request $request, int $id): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        $branch = Branch::onlyTrashed()->findOrFail($id);
        $branch->forceDelete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil dihapus permanen.');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateBranch(Request $request, ?Branch $branch = null): array
    {
        $emailRule = Rule::unique('branches', 'email');

        if ($branch !== null) {
            $emailRule->ignore($branch->getKey());
        }

        return $request->validate([
            'branch_name' => [
                'required',
                'string',
                'max:100',
            ],
            'address'     => [
                'required',
                'string',
            ],
            'phone'       => [
                'required',
                'string',
                'max:20',
            ],
            'email'       => [
                'required',
                'email:rfc',
                'max:100',
                $emailRule,
            ],
            'manager_id'  => [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn($query) => $query->where('status', 'active')
                ),
            ],
            'status'      => [
                'required',
                'in:0,1',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL APPROVAL ACTION
    |--------------------------------------------------------------------------
    */

    private function applyApprovedAction(Branch $branch, User $approver): void
    {
        $approvalAction = $branch->approval_action;
        $pendingPayload = is_array($branch->pending_payload)
            ? $branch->pending_payload
            : [];

        $allowedPayload = Arr::only($pendingPayload, [
            'branch_name',
            'address',
            'manager_id',
            'phone',
            'email',
            'status',
        ]);

        if (in_array($approvalAction, ['create', 'update'], true)) {
            if ($allowedPayload === []) {
                throw ValidationException::withMessages([
                    'approval' => 'Data perubahan yang akan diterapkan tidak tersedia.',
                ]);
            }

            $newBranchName = (string) ($allowedPayload['branch_name'] ?? $branch->branch_name);

            $newBranchCode = $this->makeBranchCode(
                branchName: $newBranchName,
                ignoreBranchId: (int) $branch->getKey(),
                currentCode: (string) $branch->branch_code
            );

            $branch->fill($allowedPayload);
            $branch->forceFill([
                'branch_code' => $newBranchCode,
            ]);
        }

        $isDeleteRequest = $approvalAction === 'delete';

        $branch->forceFill([
            'approval_status'       => 'approved',
            'approval_action'       => null,
            'pending_approval_role' => null,
            'pending_payload'       => null,
            'last_approved_by'      => $approver->getKey(),
            'approved_at'           => now(),
            'rejected_by'           => null,
            'rejected_at'           => null,
            'rejection_note'        => null,
        ])->save();

        if ($isDeleteRequest) {
            $branch->delete();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVAL LOG
    |--------------------------------------------------------------------------
    */

    private function writeApprovalLog(
        Branch $branch,
        User $user,
        string $action,
        ?string $fromStatus,
        ?string $toStatus,
        ?string $nextApprovalRole,
        ?string $note
    ): void {
        DB::table('branch_approval_logs')->insert([
            'branch_id'          => $branch->getKey(),
            'user_id'            => $user->getKey(),
            'role_name'          => $this->resolveActionRole($user, $branch),
            'action'             => $action,
            'from_status'        => $fromStatus,
            'to_status'          => $toStatus,
            'next_approval_role' => $nextApprovalRole,
            'note'               => $note,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    private function resolveActionRole(User $user, Branch $branch): ?string
    {
        $requiredRole = $branch->pending_approval_role;

        if (
            is_string($requiredRole)
            && $requiredRole !== ''
            && $user->hasRole($requiredRole)
        ) {
            return $requiredRole;
        }

        if ($user->hasRole('super_admin')) {
            return 'super_admin';
        }

        return $user->getRoleNames()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401, 'Silakan login terlebih dahulu.');

        return $user;
    }

    private function ensureCanViewBranches(Request $request): User
    {
        $user = $this->authenticatedUser($request);

        abort_unless(
            $this->userHasAnyRole($user, self::VIEWER_ROLES),
            403,
            'Anda tidak memiliki akses untuk melihat data cabang.'
        );

        return $user;
    }

    private function ensureCanManageBranches(Request $request): User
    {
        $user = $this->authenticatedUser($request);

        abort_unless(
            $this->userHasAnyRole($user, self::MANAGER_ROLES),
            403,
            'Anda tidak memiliki akses untuk mengajukan perubahan data cabang.'
        );

        return $user;
    }

    private function ensureSuperAdmin(Request $request): User
    {
        $user = $this->authenticatedUser($request);

        abort_unless(
            $user->hasRole('super_admin'),
            403,
            'Hanya Super Admin yang dapat mengelola sampah cabang.'
        );

        return $user;
    }

    private function userHasAnyRole(User $user, array $roles): bool
    {
        $normalizedUserRoles = $user->getRoleNames()
            ->map(fn(string $role): string => strtolower(str_replace(['-', ' '], '_', trim($role))))
            ->all();

        $aliases = [
            'super_admin' => ['super_admin', 'super administrator', 'superadministrator'],
            'direktur_utama' => ['direktur_utama', 'direktur utama', 'direkturutama', 'executive'],
            'hrd_manager' => ['hrd_manager', 'hrd manager', 'hrdmanager', 'hr'],
            'manager_departemen' => ['manager_departemen', 'manager departemen', 'managerdepartemen'],
            'karyawan' => ['karyawan', 'pegawai', 'employee'],
            'admin_pelayanan' => ['admin_pelayanan', 'admin pelayanan'],
            'admin_operasional' => ['admin_operasional', 'admin operasional'],
            'finance_staff' => ['finance_staff', 'finance staff', 'finance'],
            'auditor_internal' => ['auditor_internal', 'auditor internal', 'auditor'],
        ];

        foreach ($roles as $role) {
            $normalizedRole = strtolower(str_replace(['-', ' '], '_', trim($role)));
            $roleAliases = $aliases[$normalizedRole] ?? [$normalizedRole];
            $normalizedAliases = array_map(
                fn(string $alias): string => strtolower(str_replace(['-', ' '], '_', trim($alias))),
                $roleAliases
            );

            if (array_intersect($normalizedUserRoles, $normalizedAliases) !== []) {
                return true;
            }
        }

        return false;
    }

    private function formatRoleName(string $role): string
    {
        return Str::of($role)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    /*
    |--------------------------------------------------------------------------
    | BRANCH CODE GENERATOR
    |--------------------------------------------------------------------------
    */

    /**
     * Membuat kode cabang berdasarkan nama lokasi.
     *
     * Contoh:
     * Cabang Sragen       -> CBG-SRG-001
     * Cabang Sragen Barat -> CBG-SRG-002
     *
     * Nomor urut dihitung per kode lokasi dan mencakup data soft delete,
     * sehingga kode lama tidak dipakai ulang.
     */
    private function makeBranchCode(
        string $branchName,
        ?int $ignoreBranchId = null,
        ?string $currentCode = null
    ): string {
        $locationCode = $this->makeLocationCode($branchName);
        $prefix       = "CBG-{$locationCode}-";

        /*
         * Jika nama cabang berubah tetapi tetap memiliki kode lokasi yang sama,
         * pertahankan kode lama agar nomor urut tidak berubah tanpa alasan.
         */
        if (
            is_string($currentCode)
            && $currentCode !== ''
            && Str::startsWith($currentCode, $prefix)
        ) {
            return $currentCode;
        }

        $query = Branch::withTrashed()
            ->where('branch_code', 'like', $prefix . '%');

        if ($ignoreBranchId !== null) {
            $query->where('id', '<>', $ignoreBranchId);
        }

        $existingCodes = $query
            ->lockForUpdate()
            ->pluck('branch_code');

        $largestSequence = $existingCodes
            ->map(static function (mixed $code) use ($prefix): int {
                $code = (string) $code;

                if (! Str::startsWith($code, $prefix)) {
                    return 0;
                }

                $sequence = Str::after($code, $prefix);

                return ctype_digit($sequence)
                    ? (int) $sequence
                    : 0;
            })
            ->max() ?? 0;

        $nextSequence = $largestSequence + 1;

        return $prefix . str_pad(
            (string) $nextSequence,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Mengubah nama cabang menjadi singkatan lokasi tiga karakter.
     */
    private function makeLocationCode(string $branchName): string
    {
        $cleanName = Str::of($branchName)
            ->ascii()
            ->upper()
            ->trim()
            ->replaceMatches('/^CABANG[\s\-_]+/', '')
            ->replaceMatches('/[^A-Z0-9\s]/', '')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();

        if ($cleanName === '') {
            return 'XXX';
        }

        $locationName = explode(' ', $cleanName)[0];

        if (isset(self::BRANCH_CODE_ALIASES[$locationName])) {
            return self::BRANCH_CODE_ALIASES[$locationName];
        }

        /*
         * Fallback:
         * gunakan tiga konsonan pertama. Jika kurang dari tiga, gunakan
         * tiga karakter pertama dan tambahkan X bila diperlukan.
         */
        $consonants = preg_replace('/[AEIOU]/', '', $locationName) ?? '';

        if (strlen($consonants) >= 3) {
            return substr($consonants, 0, 3);
        }

        return str_pad(
            substr($locationName, 0, 3),
            3,
            'X'
        );
    }
}
