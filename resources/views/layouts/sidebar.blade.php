@php
     /*
    |--------------------------------------------------------------------------
    | NILAI DEFAULT SIDEBAR
    |--------------------------------------------------------------------------
    |
    | Nilai ini harus dibuat sebelum seluruh pemeriksaan role agar komponen
    | sidebar tetap dapat dirender ketika session role kosong atau route
    | dashboard khusus role belum tersedia.
    |
    */
     $dashboardUrl = \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/');

     $dashboardName = 'Dashboard';
     $activeRole = '';
     $activeRoleLabel = 'Pengguna';

     /*
    |--------------------------------------------------------------------------
    | USER DAN ROLE AKTIF
    |--------------------------------------------------------------------------
    */
     $user = auth()->user();

     /*
      * Ambil role aktif dari session terlebih dahulu. Jika tidak ada, gunakan
      * role pertama dari Spatie Permission.
      */
     $spatieRole = '';

     if ($user && method_exists($user, 'getRoleNames')) {
         $spatieRole = (string) ($user->getRoleNames()->first() ?? '');
     }

     $rawActiveRole =
         session('active_role_name') ??
         (session('active_role') ??
             (data_get($user, 'active_role_name') ??
                 (data_get($user, 'role_name') ?? (data_get($user, 'role') ?? $spatieRole))));

     if (is_array($rawActiveRole) || is_object($rawActiveRole)) {
         $rawActiveRole =
             data_get($rawActiveRole, 'slug') ??
             (data_get($rawActiveRole, 'name') ?? (data_get($rawActiveRole, 'code') ?? ''));
     }

     $normalizedRole = \Illuminate\Support\Str::of((string) $rawActiveRole)
         ->trim()
         ->lower()
         ->replace(['-', ' '], '_')
         ->replaceMatches('/_+/', '_')
         ->toString();

     /*
      * Alias diarahkan ke nama role yang digunakan pada web.php/database.
      */
     $roleAliases = [
         'superadmin' => 'super_admin',
         'super_admin' => 'super_admin',

         'direktur' => 'direktur_utama',
         'direktur_utama' => 'direktur_utama',
         'executive' => 'direktur_utama',

         'hrd' => 'hrd_manager',
         'hr' => 'hrd_manager',
         'human_resource' => 'hrd_manager',
         'human_resources' => 'hrd_manager',
         'hrd_manager' => 'hrd_manager',

         'manager' => 'manager_departemen',
         'manager_department' => 'manager_departemen',
         'manager_departemen' => 'manager_departemen',

         'pegawai' => 'karyawan',
         'employee' => 'karyawan',
         'karyawan' => 'karyawan',

         'pelayanan' => 'admin_pelayanan',
         'admin_service' => 'admin_pelayanan',
         'admin_pelayanan' => 'admin_pelayanan',

         'operasional' => 'admin_operasional',
         'admin_operation' => 'admin_operasional',
         'admin_operasional' => 'admin_operasional',

         'keuangan' => 'finance_staff',
         'financial' => 'finance_staff',
         'finance' => 'finance_staff',
         'finance_staff' => 'finance_staff',

         'audit' => 'auditor_internal',
         'auditor' => 'auditor_internal',
         'auditor_internal' => 'auditor_internal',
     ];

     $activeRole = $roleAliases[$normalizedRole] ?? $normalizedRole;

     /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
     /*
      * Pemeriksaan role dibuat lebih kuat:
      * 1. Memeriksa role aktif yang berasal dari session/user.
      * 2. Memeriksa seluruh role Spatie milik user.
      * 3. Menormalisasi spasi dan tanda hubung agar sesuai dengan slug web.php.
      */
     $hasRole = static function (array $roles) use ($user, $activeRole, $roleAliases): bool {
         if (in_array($activeRole, $roles, true)) {
             return true;
         }

         if (!$user || !method_exists($user, 'getRoleNames')) {
             return false;
         }

         $userRoles = $user
             ->getRoleNames()
             ->map(static function ($roleName) use ($roleAliases): string {
                 $normalized = \Illuminate\Support\Str::of((string) $roleName)
                     ->trim()
                     ->lower()
                     ->replace(['-', ' '], '_')
                     ->replaceMatches('/_+/', '_')
                     ->toString();

                 return $roleAliases[$normalized] ?? $normalized;
             })
             ->all();

         return count(array_intersect($roles, $userRoles)) > 0;
     };

     $routeUrl = static function (string|array $routeNames, array $parameters = []): string {
         foreach ((array) $routeNames as $routeName) {
             if (\Illuminate\Support\Facades\Route::has($routeName)) {
                 return route($routeName, $parameters);
             }
         }

         return '#';
     };

     $routeActive = static fn(string ...$patterns): bool => request()->routeIs(...$patterns);

     /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
    |--------------------------------------------------------------------------
    */
     $isSuperAdmin = $hasRole(['super_admin']);
     $isDirektur = $hasRole(['direktur_utama']);
     $isHrd = $hasRole(['hrd_manager']);
     $isManager = $hasRole(['manager_departemen']);
     $isKaryawan = $hasRole(['karyawan']);
     $isPelayanan = $hasRole(['admin_pelayanan']);
     $isOperasional = $hasRole(['admin_operasional']);
     $isKeuangan = $hasRole(['finance_staff']);
     $isAuditor = $hasRole(['auditor_internal']);

     /*
    |--------------------------------------------------------------------------
    | INFORMASI DASHBOARD
    |--------------------------------------------------------------------------
    */
     $dashboardNames = [
         'super_admin' => 'Dashboard Super Admin',
         'direktur_utama' => 'Dashboard Direktur Utama',
         'hrd_manager' => 'Dashboard HRD Manager',
         'manager_departemen' => 'Dashboard Manager Departemen',
         'karyawan' => 'Dashboard Karyawan',
         'admin_pelayanan' => 'Dashboard Admin Pelayanan',
         'admin_operasional' => 'Dashboard Admin Operasional',
         'finance_staff' => 'Dashboard Finance Staff',
         'auditor_internal' => 'Dashboard Auditor Internal',
     ];

     $dashboardRouteCandidates = [
         'super_admin' => ['super-admin.dashboard', 'dashboard'],
         'direktur_utama' => ['direktur-utama.dashboard', 'dashboard'],
         'hrd_manager' => ['hrd-manager.dashboard', 'dashboard'],
         'manager_departemen' => ['manager-departemen.dashboard', 'dashboard'],
         'karyawan' => ['karyawan.dashboard', 'dashboard'],
         'admin_pelayanan' => ['admin-pelayanan.dashboard', 'dashboard'],
         'admin_operasional' => ['admin-operasional.dashboard', 'dashboard'],
         'finance_staff' => ['finance-staff.dashboard', 'dashboard'],
         'auditor_internal' => ['auditor-internal.dashboard', 'dashboard'],
     ];

     $roleDisplayNames = [
         'super_admin' => 'Super Admin',
         'direktur_utama' => 'Direktur Utama',
         'hrd_manager' => 'HRD Manager',
         'manager_departemen' => 'Manager Departemen',
         'karyawan' => 'Karyawan',
         'admin_pelayanan' => 'Admin Pelayanan',
         'admin_operasional' => 'Admin Operasional',
         'finance_staff' => 'Finance Staff',
         'auditor_internal' => 'Auditor Internal',
     ];

     $dashboardName = $dashboardNames[$activeRole] ?? 'Dashboard';

     $dashboardCandidates = $dashboardRouteCandidates[$activeRole] ?? ['dashboard'];
     $resolvedDashboardUrl = $routeUrl($dashboardCandidates);

     if ($resolvedDashboardUrl !== '#') {
         $dashboardUrl = $resolvedDashboardUrl;
     }

     $activeRoleLabel =
         $roleDisplayNames[$activeRole] ??
         \Illuminate\Support\Str::of($activeRole)->replace('_', ' ')->title()->toString();

     /*
    |--------------------------------------------------------------------------
    | HAK AKSES MENU
    |--------------------------------------------------------------------------
    */
     $canAccessBranches = $hasRole(['super_admin', 'direktur_utama', 'admin_operasional', 'auditor_internal']);

     /*
      * Menu Departemen dapat dilihat oleh lima role.
      * Pembatasan create, edit, delete, trash, restore, dan force delete
      * tetap dilakukan melalui middleware route khusus Super Admin.
      */
     $canAccessDepartments = $hasRole([
         'super_admin',
         'direktur_utama',
         'hrd_manager',
         'manager_departemen',
         'auditor_internal',
     ]);

     /*
      * Menu Jabatan dapat dilihat oleh lima role.
      * Seluruh aksi pengelolaan tetap diamankan oleh middleware route.
      */
     $canAccessPositions = $hasRole([
         'super_admin',
         'direktur_utama',
         'hrd_manager',
         'manager_departemen',
         'auditor_internal',
     ]);

     $canAccessMasterData = $hasRole([
         'super_admin',
         'direktur_utama',
         'hrd_manager',
         'manager_departemen',
         'admin_pelayanan',
         'admin_operasional',
         'finance_staff',
         'auditor_internal',
     ]);

     $canAccessTransactions = $hasRole([
         'super_admin',
         'direktur_utama',
         'admin_pelayanan',
         'admin_operasional',
         'finance_staff',
         'auditor_internal',
     ]);

     $canAccessPerformance = $hasRole([
         'super_admin',
         'direktur_utama',
         'hrd_manager',
         'manager_departemen',
         'karyawan',
         'admin_operasional',
         'auditor_internal',
     ]);

     $canAccessSatisfaction = $hasRole(['super_admin', 'direktur_utama', 'admin_pelayanan', 'auditor_internal']);

     $canAccessComplaints = $hasRole(['super_admin', 'direktur_utama', 'admin_pelayanan', 'auditor_internal']);

     $canAccessReports = $hasRole([
         'super_admin',
         'direktur_utama',
         'hrd_manager',
         'manager_departemen',
         'admin_pelayanan',
         'admin_operasional',
         'finance_staff',
         'auditor_internal',
     ]);

     $canAccessSystem = $hasRole(['super_admin', 'hrd_manager', 'auditor_internal']);

     /*
      * Pastikan route index Departemen benar-benar terdaftar.
      */
     $departmentsRouteExists = \Illuminate\Support\Facades\Route::has('super-admin.departments.index');

     /*
      * Pastikan route index Jabatan benar-benar terdaftar.
      */
     $positionsRouteExists = \Illuminate\Support\Facades\Route::has('super-admin.positions.index');

     /*
    |--------------------------------------------------------------------------
    | STATUS SUBMENU
    |--------------------------------------------------------------------------
    */
     $masterDataOpen = $routeActive(
         'branches.*',
         'super-admin.departments.*',
         'super-admin.positions.*',
         'employees.*',
         'customers.*',
         'service-categories.*',
         'services.*',
     );

     $transactionsOpen = $routeActive('transactions.*', 'transaction-assignments.*');

     $performanceOpen = $routeActive(
         'kpi-categories.*',
         'kpi-indicators.*',
         'employee-targets.*',
         'employee-kpi-results.*',
         'performance-evaluations.*',
         'work-schedules.*',
         'employee-schedules.*',
     );

     $satisfactionOpen = $routeActive('surveys.*', 'survey-questions.*', 'survey-responses.*');
     $complaintsOpen = $routeActive('complaint-categories.*', 'complaints.*');
     $reportsOpen = $routeActive('reports.*');

     $systemOpen = $routeActive(
         'super-admin.users.*',
         'super-admin.roles.*',
         'permissions.*',
         'settings.*',
         'activity-logs.*',
     );

     /*
    |--------------------------------------------------------------------------
    | LABEL MENU CABANG
    |--------------------------------------------------------------------------
    */
     $branchMenuLabel = $isDirektur || $isAuditor ? 'Persetujuan Cabang' : 'Data Cabang';

     /*
    |--------------------------------------------------------------------------
    | URL MENU KARYAWAN
    |--------------------------------------------------------------------------
    */
     $employeeKpiUrl = $routeUrl(
         $isKaryawan
             ? ['employee-kpi-results.mine', 'employee-kpi-results.my', 'employee-kpi-results.index']
             : ['employee-kpi-results.index'],
     );

     $employeeEvaluationUrl = $routeUrl(
         $isKaryawan
             ? ['performance-evaluations.mine', 'performance-evaluations.my', 'performance-evaluations.index']
             : ['performance-evaluations.index'],
     );

     $employeeScheduleUrl = $routeUrl([
         'work-schedules.mine',
         'employee-schedules.mine',
         'work-schedules.index',
         'employee-schedules.index',
     ]);

     /*
      * Variabel final yang digunakan HTML sidebar.
      */
     $safeDashboardUrl = filled($dashboardUrl ?? null) ? (string) $dashboardUrl : url('/');

     $safeDashboardName = filled($dashboardName ?? null) ? (string) $dashboardName : 'Dashboard';

     $safeActiveRoleLabel = filled($activeRoleLabel ?? null) ? (string) $activeRoleLabel : 'Pengguna';
@endphp

<div class="sidebar">
     {{-- ================================================================ --}}
     {{-- SIDEBAR HEADER --}}
     {{-- ================================================================ --}}
     <div class="sidebar-header">
          <div class="sidebar-brand">
               <a href="{{ $safeDashboardUrl }}" class="sidebar-logo" aria-label="Buka dashboard">
                    <img src="{{ asset('backend/assets/img/logo.png') }}" alt="Logo Dashboard Monitoring"
                         class="sidebar-logo-image">
               </a>

               <small class="sidebar-logo-headline">
                    Kinerja Karyawan &amp; Kepuasan Pelanggan
               </small>

               @if ($activeRole !== '')
                    <small class="sidebar-role-name">
                         {{ $safeActiveRoleLabel }}
                    </small>
               @endif
          </div>
     </div>

     {{-- ================================================================ --}}
     {{-- SIDEBAR BODY --}}
     {{-- ================================================================ --}}
     <div id="dpSidebarBody" class="sidebar-body">
          <ul class="nav nav-sidebar">
               {{-- DASHBOARD --}}
               <li class="nav-label">
                    <span class="content-label">Dashboard Utama</span>
               </li>

               <li class="nav-item">
                    <a href="{{ $safeDashboardUrl }}"
                         class="nav-link {{ $routeActive('dashboard', 'dashboard.*', '*.dashboard') ? 'active' : '' }}">
                         <i data-feather="home"></i>
                         <span>{{ $safeDashboardName }}</span>
                    </a>
               </li>

               {{-- ======================================================== --}}
               {{-- MASTER DATA --}}
               {{-- ======================================================== --}}
               @if ($canAccessMasterData)
                    <li class="nav-label">
                         <span class="content-label">Master Data</span>
                    </li>

                    <li class="nav-item {{ $masterDataOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);" class="nav-link with-sub {{ $masterDataOpen ? 'active' : '' }}"
                              aria-expanded="{{ $masterDataOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-master-data">
                              <i data-feather="database"></i>
                              <span>Master Data</span>
                         </a>

                         <nav id="submenu-master-data" class="nav nav-sub" aria-label="Menu Master Data">
                              @if ($canAccessBranches && \Illuminate\Support\Facades\Route::has('branches.index'))
                                   <a href="{{ route('branches.index') }}"
                                        class="nav-sub-link {{ $routeActive('branches.*') ? 'active' : '' }}">
                                        {{ $branchMenuLabel }}
                                   </a>
                              @endif

                              @if (($canAccessDepartments ?? false) && ($departmentsRouteExists ?? false))
                                   <a href="{{ route('super-admin.departments.index') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.departments.*') ? 'active' : '' }}">
                                        Departemen
                                   </a>
                              @endif

                              @if (($canAccessPositions ?? false) && ($positionsRouteExists ?? false))
                                   <a href="{{ route('super-admin.positions.index') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.positions.*') ? 'active' : '' }}">
                                        Jabatan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isOperasional || $isAuditor)
                                   <a href="{{ $routeUrl('employees.index') }}"
                                        class="nav-sub-link {{ $routeActive('employees.*') ? 'active' : '' }}">
                                        Data Karyawan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="{{ $routeUrl('customers.index') }}"
                                        class="nav-sub-link {{ $routeActive('customers.*') ? 'active' : '' }}">
                                        Data Pelanggan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isAuditor)
                                   <a href="{{ $routeUrl('service-categories.index') }}"
                                        class="nav-sub-link {{ $routeActive('service-categories.*') ? 'active' : '' }}">
                                        Kategori Layanan
                                   </a>

                                   <a href="{{ $routeUrl('services.index') }}"
                                        class="nav-sub-link {{ $routeActive('services.*') ? 'active' : '' }}">
                                        Data Layanan
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- TRANSAKSI JASA --}}
               {{-- ======================================================== --}}
               @if ($canAccessTransactions)
                    <li class="nav-label">
                         <span class="content-label">Operasional Jasa</span>
                    </li>

                    <li class="nav-item {{ $transactionsOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $transactionsOpen ? 'active' : '' }}"
                              aria-expanded="{{ $transactionsOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-transaksi">
                              <i data-feather="briefcase"></i>
                              <span>Transaksi Jasa</span>
                         </a>

                         <nav id="submenu-transaksi" class="nav nav-sub" aria-label="Menu Transaksi Jasa">
                              <a href="#"
                                   class="nav-sub-link {{ $routeActive('transactions.index', 'transactions.show', 'transactions.edit') ? 'active' : '' }}">
                                   Daftar Transaksi
                              </a>

                              @if ($isSuperAdmin || $isPelayanan || $isOperasional)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('transactions.create') ? 'active' : '' }}">
                                        Tambah Transaksi
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isOperasional)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('transaction-assignments.*') ? 'active' : '' }}">
                                        Penugasan Karyawan
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- KINERJA KARYAWAN / AKTIVITAS SAYA --}}
               {{-- ======================================================== --}}
               @if ($canAccessPerformance)
                    <li class="nav-label">
                         <span class="content-label">
                              {{ $isKaryawan ? 'Aktivitas Saya' : 'Kinerja Karyawan' }}
                         </span>
                    </li>

                    <li class="nav-item {{ $performanceOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);" class="nav-link with-sub {{ $performanceOpen ? 'active' : '' }}"
                              aria-expanded="{{ $performanceOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-kinerja">
                              <i data-feather="bar-chart-2"></i>
                              <span>{{ $isKaryawan ? 'Kinerja Saya' : 'Kinerja Karyawan' }}</span>
                         </a>

                         <nav id="submenu-kinerja" class="nav nav-sub" aria-label="Menu Kinerja">
                              @if ($isSuperAdmin || $isHrd)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('kpi-categories.*') ? 'active' : '' }}">
                                        Kategori KPI
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('kpi-indicators.*') ? 'active' : '' }}">
                                        Indikator KPI
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('employee-targets.*') ? 'active' : '' }}">
                                        Target Karyawan
                                   </a>
                              @endif

                              <a href="{{ $employeeKpiUrl }}"
                                   class="nav-sub-link {{ $routeActive('employee-kpi-results.*') ? 'active' : '' }}">
                                   {{ $isKaryawan ? 'Hasil KPI Saya' : 'Hasil KPI' }}
                              </a>

                              <a href="{{ $employeeEvaluationUrl }}"
                                   class="nav-sub-link {{ $routeActive('performance-evaluations.*') ? 'active' : '' }}">
                                   {{ $isKaryawan ? 'Evaluasi Saya' : 'Evaluasi Kinerja' }}
                              </a>
                         </nav>
                    </li>

                    @if ($isKaryawan)
                         <li class="nav-item">
                              <a href="{{ $employeeScheduleUrl }}"
                                   class="nav-link {{ $routeActive('work-schedules.*', 'employee-schedules.*') ? 'active' : '' }}">
                                   <i data-feather="calendar"></i>
                                   <span>Jadwal Kerja</span>
                              </a>
                         </li>
                    @endif
               @endif

               {{-- ======================================================== --}}
               {{-- KEPUASAN PELANGGAN --}}
               {{-- ======================================================== --}}
               @if ($canAccessSatisfaction)
                    <li class="nav-label">
                         <span class="content-label">Pelayanan Pelanggan</span>
                    </li>

                    <li class="nav-item {{ $satisfactionOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $satisfactionOpen ? 'active' : '' }}"
                              aria-expanded="{{ $satisfactionOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-kepuasan">
                              <i data-feather="smile"></i>
                              <span>Kepuasan Pelanggan</span>
                         </a>

                         <nav id="submenu-kepuasan" class="nav nav-sub" aria-label="Menu Kepuasan Pelanggan">
                              @if ($isSuperAdmin || $isPelayanan)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('surveys.*') ? 'active' : '' }}">
                                        Data Survei
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('survey-questions.*') ? 'active' : '' }}">
                                        Pertanyaan Survei
                                   </a>
                              @endif

                              <a href="#"
                                   class="nav-sub-link {{ $routeActive('survey-responses.*') ? 'active' : '' }}">
                                   Hasil Survei
                              </a>
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- KELUHAN PELANGGAN --}}
               {{-- ======================================================== --}}
               @if ($canAccessComplaints)
                    <li class="nav-item {{ $complaintsOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $complaintsOpen ? 'active' : '' }}"
                              aria-expanded="{{ $complaintsOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-keluhan">
                              <i data-feather="message-square"></i>
                              <span>Keluhan Pelanggan</span>
                         </a>

                         <nav id="submenu-keluhan" class="nav nav-sub" aria-label="Menu Keluhan Pelanggan">
                              <a href="#"
                                   class="nav-sub-link {{ $routeActive('complaints.index', 'complaints.show', 'complaints.edit') ? 'active' : '' }}">
                                   Daftar Keluhan
                              </a>

                              @if ($isSuperAdmin || $isPelayanan)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('complaints.create') ? 'active' : '' }}">
                                        Tambah Keluhan
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('complaint-categories.*') ? 'active' : '' }}">
                                        Kategori Keluhan
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- LAPORAN --}}
               {{-- ======================================================== --}}
               @if ($canAccessReports)
                    <li class="nav-label">
                         <span class="content-label">Laporan</span>
                    </li>

                    <li class="nav-item {{ $reportsOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);" class="nav-link with-sub {{ $reportsOpen ? 'active' : '' }}"
                              aria-expanded="{{ $reportsOpen ? 'true' : 'false' }}" aria-controls="submenu-laporan">
                              <i data-feather="file-text"></i>
                              <span>Laporan</span>
                         </a>

                         <nav id="submenu-laporan" class="nav nav-sub" aria-label="Menu Laporan">
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('reports.transactions') ? 'active' : '' }}">
                                        Laporan Transaksi
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('reports.performance') ? 'active' : '' }}">
                                        Laporan Kinerja
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('reports.satisfaction') ? 'active' : '' }}">
                                        Laporan Kepuasan
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('reports.complaints') ? 'active' : '' }}">
                                        Laporan Keluhan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isKeuangan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('reports.finance') ? 'active' : '' }}">
                                        Laporan Keuangan
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- PENGATURAN SISTEM --}}
               {{-- ======================================================== --}}
               @if ($canAccessSystem)
                    <li class="nav-label">
                         <span class="content-label">Sistem</span>
                    </li>

                    <li class="nav-item {{ $systemOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);" class="nav-link with-sub {{ $systemOpen ? 'active' : '' }}"
                              aria-expanded="{{ $systemOpen ? 'true' : 'false' }}" aria-controls="submenu-sistem">
                              <i data-feather="settings"></i>
                              <span>Pengaturan Sistem</span>
                         </a>

                         <nav id="submenu-sistem" class="nav nav-sub" aria-label="Menu Pengaturan Sistem">
                              @if ($isSuperAdmin || $isHrd)
                                   <a href="{{ $routeUrl('super-admin.users.index') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.users.*') ? 'active' : '' }}">

                                        Pengguna

                                   </a>
                              @endif

                              @if ($isSuperAdmin)
                                   <a href="{{ $routeUrl('super-admin.roles.index') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.roles.*', 'permissions.*') ? 'active' : '' }}">
                                        Role dan Hak Akses
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('activity-logs.*') ? 'active' : '' }}">
                                        Log Aktivitas
                                   </a>
                              @endif

                              @if ($isSuperAdmin)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('settings.*') ? 'active' : '' }}">
                                        Pengaturan Aplikasi
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- AKUN --}}
               {{-- ======================================================== --}}
               <li class="nav-label">
                    <span class="content-label">Akun</span>
               </li>

               <li class="nav-item">
                    <a href="{{ $routeUrl(['profile.index', 'profile.edit']) }}"
                         class="nav-link {{ $routeActive('profile.*') ? 'active' : '' }}">
                         <i data-feather="user"></i>
                         <span>Profil Saya</span>
                    </a>
               </li>
          </ul>
     </div>
</div>

{{--
    Script cadangan untuk dropdown sidebar.
    Tetap bekerja walaupun JavaScript bawaan template belum memproses .with-sub.
--}}
@once
     <style>
          .sidebar-brand {
               display: flex;
               flex-direction: column;
               align-items: center;
               justify-content: center;
               width: 100%;
               padding: 12px 10px;
               text-align: center;
          }

          .sidebar-logo {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               max-width: 100%;
          }

          .sidebar-logo-image {
               display: block;
               width: auto;
               max-width: 64px;
               height: auto;
               max-height: 64px;
               object-fit: contain;
          }

          .sidebar-logo-headline {
               display: block;
               max-width: 190px;
               margin-top: 7px;
               font-size: 11px;
               line-height: 1.35;
               white-space: normal;
          }

          .sidebar-role-name {
               display: inline-block;
               margin-top: 6px;
               padding: 3px 9px;
               border-radius: 999px;
               font-size: 10px;
               font-weight: 600;
               line-height: 1.3;
          }

          .nav-sidebar>.nav-item>.nav-sub {
               display: none;
          }

          .nav-sidebar>.nav-item.show>.nav-sub {
               display: block;
          }
     </style>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const sidebar = document.querySelector('.nav-sidebar');

               if (!sidebar || sidebar.dataset.submenuInitialized === 'true') {
                    return;
               }

               sidebar.dataset.submenuInitialized = 'true';

               // Capture phase dipakai agar tidak bentrok/dijalankan dua kali
               // dengan handler submenu bawaan template.
               sidebar.addEventListener('click', function(event) {
                    const toggle = event.target.closest('.nav-link.with-sub');

                    if (!toggle || !sidebar.contains(toggle)) {
                         return;
                    }

                    event.preventDefault();
                    event.stopImmediatePropagation();

                    const currentItem = toggle.closest('.nav-item');

                    if (!currentItem) {
                         return;
                    }

                    const willOpen = !currentItem.classList.contains('show');

                    // Accordion: tutup submenu lain sebelum membuka submenu aktif.
                    sidebar.querySelectorAll('.nav-item.show').forEach(function(openedItem) {
                         if (openedItem !== currentItem) {
                              openedItem.classList.remove('show');

                              const openedToggle = openedItem.querySelector(
                                   ':scope > .nav-link.with-sub');
                              openedToggle?.setAttribute('aria-expanded', 'false');
                         }
                    });

                    currentItem.classList.toggle('show', willOpen);
                    toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
               }, true);

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }
          });
     </script>
@endonce
