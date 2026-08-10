@php
     /*
    |--------------------------------------------------------------------------
    | NILAI DEFAULT SIDEBAR
    |--------------------------------------------------------------------------
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

    $roleAliases = [

    // SUPER ADMIN
    'superadmin' => 'super_admin',
    'super_admin' => 'super_admin',
    'super_administrator' => 'super_admin',
    'super administrator' => 'super_admin',
    'administrator' => 'super_admin',

    // DIREKTUR
    'direktur' => 'direktur_utama',
    'direktur_utama' => 'direktur_utama',
    'direktur utama' => 'direktur_utama',
    'executive' => 'direktur_utama',

    // HRD
    'hrd' => 'hrd_manager',
    'hrd_manager' => 'hrd_manager',
    'hrd manager' => 'hrd_manager',

    // MANAGER
    'manager' => 'manager_departemen',
    'manager_departemen' => 'manager_departemen',
    'manager departemen' => 'manager_departemen',

    // KARYAWAN
    'karyawan' => 'karyawan',
    'pegawai' => 'karyawan',
    'employee' => 'karyawan',

    // PELAYANAN
    'admin_pelayanan' => 'admin_pelayanan',
    'admin pelayanan' => 'admin_pelayanan',

    // OPERASIONAL
    'admin_operasional' => 'admin_operasional',
    'admin operasional' => 'admin_operasional',

    // FINANCE
    'finance' => 'finance_staff',
    'finance_staff' => 'finance_staff',
    'finance staff' => 'finance_staff',

    // AUDITOR
    'auditor' => 'auditor_internal',
    'auditor_internal' => 'auditor_internal',
    'auditor internal' => 'auditor_internal',
];

     $activeRole = $roleAliases[$normalizedRole] ?? $normalizedRole;

     /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
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
     $resolvedDashboardUrl = $routeUrl($dashboardRouteCandidates[$activeRole] ?? ['dashboard']);

     if ($resolvedDashboardUrl !== '#') {
         $dashboardUrl = $resolvedDashboardUrl;
     }

     $activeRoleLabel =
         $roleDisplayNames[$activeRole] ??
         \Illuminate\Support\Str::of($activeRole)->replace('_', ' ')->title()->toString();

     /*
    |--------------------------------------------------------------------------
    | CANDIDATE ROUTE SESUAI TABEL DATABASE
    |--------------------------------------------------------------------------
    |
    | Nama pertama menjadi prioritas. Nama berikutnya merupakan fallback agar
    | sidebar tetap kompatibel dengan pola route yang sudah ada di proyek.
    |
    */
     $menuRoutes = [
         // Master data
         'branches' => ['branches.index', 'super-admin.branches.index'],
         'departments' => ['super-admin.departments.index', 'departments.index'],
         'positions' => ['super-admin.positions.index', 'positions.index'],
         'employees' => ['employees.index', 'super-admin.employees.index'],
         'employment' => [
             'super-admin.employment.index',
             'super-admin.employments.index',
             'employment.index',
             'employments.index',
         ],
         'customers' => ['super-admin.customers.index', 'customers.index'],
         'serviceCategories' => ['service-categories.index', 'super-admin.service-categories.index'],
         'services' => ['services.index', 'super-admin.services.index'],

         // Proses layanan
         'serviceOrders' => ['service-orders.index', 'super-admin.service-orders.index'],
         'serviceOrdersCreate' => ['service-orders.create', 'super-admin.service-orders.create'],
         'serviceOrderItems' => ['service-order-items.index', 'super-admin.service-order-items.index'],
         'branchApprovalLogs' => [
             'branch-approval-logs.index',
             'super-admin.branch-approval-logs.index',
             'service-orders.approvals.index',
         ],
         'workSchedules' => ['super-admin.work-schedules.index', 'work-schedules.index'],
         'employeeSchedules' => ['employee-schedules.index', 'super-admin.employee-schedules.index'],
         'employeeSchedulesMine' => [
             'employee-schedules.mine',
             'work-schedules.mine',
             'employee-schedules.index',
             'work-schedules.index',
         ],
         'employeeActivities' => ['employee-activities.index', 'super-admin.employee-activities.index'],
         'employeeActivitiesMine' => [
             'employee-activities.mine',
             'employee-activities.my',
             'employee-activities.index',
         ],
         'serviceOrderHistories' => [
             'service-order-status-histories.index',
             'service-orders.status-histories.index',
             'super-admin.service-order-status-histories.index',
         ],

         // SDM operasional
         'attendances' => ['attendances.index', 'super-admin.attendances.index'],
         'attendancesMine' => ['attendances.mine', 'attendances.my', 'attendances.index'],
         'leaveRequests' => ['leave-requests.index', 'super-admin.leave-requests.index'],
         'leaveRequestsMine' => ['leave-requests.mine', 'leave-requests.my', 'leave-requests.index'],

         // Keuangan
         'expenses' => ['expenses.index', 'super-admin.expenses.index'],
         'invoices' => ['invoices.index', 'super-admin.invoices.index'],
         'payments' => ['payments.index', 'super-admin.payments.index'],

         // Kinerja
         'performanceIndicators' => [
             'performance-indicators.index',
             'super-admin.performance-indicators.index',
             'kpi-indicators.index',
         ],
         'performancePeriods' => ['super-admin.performance-periods.index', 'performance-periods.index'],
         'performanceRoles' => [
             'performance-roles.index',
             'performance-role.index',
             'super-admin.performance-roles.index',
         ],
         'employeeTargets' => ['employee-targets.index', 'super-admin.employee-targets.index'],
         'employeePerformance' => [
             'employee-performance.index',
             'employee-performances.index',
             'employee-kpi-results.index',
         ],
         'employeePerformanceMine' => [
             'employee-performance.mine',
             'employee-performances.mine',
             'employee-kpi-results.mine',
             'employee-kpi-results.my',
             'employee-kpi-results.index',
         ],
         'performanceDetails' => [
             'performance-details.index',
             'super-admin.performance-details.index',
             'performance-evaluations.index',
         ],
         'performanceDetailsMine' => [
             'performance-details.mine',
             'performance-evaluations.mine',
             'performance-evaluations.my',
             'performance-evaluations.index',
         ],

         // Pelayanan pelanggan
         'customerFeedback' => ['customer-feedback.index', 'super-admin.customer-feedback.index'],
         'customerComplaints' => [
             'customer-complaints.index',
             'super-admin.customer-complaints.index',
             'complaints.index',
         ],
         'customerComplaintsCreate' => [
             'customer-complaints.create',
             'super-admin.customer-complaints.create',
             'complaints.create',
         ],

         // Laporan dan snapshot
         'dashboardSnapshots' => ['dashboard-snapshots.index', 'super-admin.dashboard-snapshots.index'],
         'reportServices' => ['reports.services', 'reports.transactions'],
         'reportPerformance' => ['reports.performance'],
         'reportCustomers' => ['reports.customers', 'reports.satisfaction'],
         'reportComplaints' => ['reports.complaints'],
         'reportFinance' => ['reports.finance'],

         // Sistem
         'users' => ['super-admin.users.index', 'users.index'],
         'roles' => ['super-admin.roles.index', 'roles.index'],
         'permissions' => ['permissions.index', 'super-admin.permissions.index'],
         'auditLogs' => ['audit-logs.index', 'super-admin.audit-logs.index'],
         'systemSettings' => ['system-settings.index', 'settings.index'],
         'notifications' => ['notifications.index'],
         'profile' => ['profile.index', 'profile.edit'],
     ];

     $menuUrl = static function (string $key, array $parameters = []) use ($menuRoutes, $routeUrl): string {
         return $routeUrl($menuRoutes[$key] ?? [], $parameters);
     };

     /*
    |--------------------------------------------------------------------------
    | HAK AKSES MENU
    |--------------------------------------------------------------------------
    */
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

      $canAccessServiceProcess = $hasRole([
           'super_admin',
         'manager_departemen',
         'karyawan',
         'admin_pelayanan',
         'admin_operasional',
         'finance_staff',
         'auditor_internal',
     ]);

      $canAccessHrOperations = $hasRole([
           'super_admin',
         'hrd_manager',
         'manager_departemen',
         'karyawan',
         'auditor_internal',
     ]);

      $canAccessFinance = $hasRole([
           'super_admin',
         'admin_operasional',
         'finance_staff',
         'auditor_internal',
     ]);

      $canAccessPerformance = $hasRole([
           'super_admin',
         'hrd_manager',
         'manager_departemen',
         'karyawan',
         'admin_operasional',
         'auditor_internal',
     ]);

      $canAccessCustomerService = $hasRole([
           'super_admin',
         'admin_pelayanan',
         'admin_operasional',
         'auditor_internal',
     ]);

      $canAccessReports = $hasRole([
           'super_admin',
         'hrd_manager',
         'manager_departemen',
         'admin_pelayanan',
         'admin_operasional',
         'finance_staff',
         'auditor_internal',
     ]);

     $canAccessSystem = $hasRole(['super_admin', 'hrd_manager', 'auditor_internal']);

     /*
    |--------------------------------------------------------------------------
    | STATUS SUBMENU BERDASARKAN ROUTE TABEL
    |--------------------------------------------------------------------------
    */
     $masterDataOpen = $routeActive(
         'branches.*',
         'super-admin.branches.*',
         'departments.*',
         'super-admin.departments.*',
         'positions.*',
         'super-admin.positions.*',
         'employees.*',
         'super-admin.employees.*',
         'employment.*',
         'employments.*',
         'super-admin.employment.*',
         'super-admin.employments.*',
         'customers.*',
         'super-admin.customers.*',
         'service-categories.*',
         'super-admin.service-categories.*',
         'services.*',
         'super-admin.services.*',
     );

     $serviceProcessOpen = $routeActive(
         'service-orders.*',
         'super-admin.service-orders.*',
         'service-order-items.*',
         'super-admin.service-order-items.*',
         'branch-approval-logs.*',
         'super-admin.branch-approval-logs.*',
         'work-schedules.*',
         'super-admin.work-schedules.*',
         'employee-schedules.*',
         'super-admin.employee-schedules.*',
         'employee-activities.*',
         'super-admin.employee-activities.*',
         'service-order-status-histories.*',
         'super-admin.service-order-status-histories.*',
     );

     $hrOperationsOpen = $routeActive(
         'attendances.*',
         'super-admin.attendances.*',
         'leave-requests.*',
         'super-admin.leave-requests.*',
     );

     $financeOpen = $routeActive(
         'expenses.*',
         'super-admin.expenses.*',
         'invoices.*',
         'super-admin.invoices.*',
         'payments.*',
         'super-admin.payments.*',
     );

     $performanceOpen = $routeActive(
         'performance-indicators.*',
         'super-admin.performance-indicators.*',
         'performance-periods.*',
         'super-admin.performance-periods.*',
         'performance-roles.*',
         'performance-role.*',
         'employee-targets.*',
         'super-admin.employee-targets.*',
         'employee-performance.*',
         'employee-performances.*',
         'employee-kpi-results.*',
         'performance-details.*',
         'super-admin.performance-details.*',
         'performance-evaluations.*',
     );

     $customerServiceOpen = $routeActive(
         'customer-feedback.*',
         'super-admin.customer-feedback.*',
         'customer-complaints.*',
         'super-admin.customer-complaints.*',
         'complaints.*',
     );

     $reportsOpen = $routeActive('reports.*', 'dashboard-snapshots.*', 'super-admin.dashboard-snapshots.*');

     $systemOpen = $routeActive(
         'super-admin.users.*',
         'users.*',
         'super-admin.roles.*',
         'roles.*',
         'permissions.*',
         'super-admin.permissions.*',
         'audit-logs.*',
         'super-admin.audit-logs.*',
         'system-settings.*',
         'settings.*',
     );

     /*
    |--------------------------------------------------------------------------
    | VARIABEL FINAL
    |--------------------------------------------------------------------------
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
               {{-- Tabel: branches, departments, positions, employees, employment, --}}
               {{-- customers, service_categories, services --}}
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
                              @if ($isSuperAdmin || $isDirektur || $isOperasional || $isAuditor)
                                   <a href="{{ $menuUrl('branches') }}"
                                        class="nav-sub-link {{ $routeActive('branches.*', 'super-admin.branches.*') ? 'active' : '' }}">
                                        Data Cabang
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isAuditor)
                                   <a href="{{ $menuUrl('departments') }}"
                                        class="nav-sub-link {{ $routeActive('departments.*', 'super-admin.departments.*') ? 'active' : '' }}">
                                        Data Departemen
                                   </a>

                                   <a href="{{ $menuUrl('positions') }}"
                                        class="nav-sub-link {{ $routeActive('positions.*', 'super-admin.positions.*') ? 'active' : '' }}">
                                        Data Jabatan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isOperasional || $isAuditor)
                                   <a href="{{ $menuUrl('employees') }}"
                                        class="nav-sub-link {{ $routeActive('employees.*', 'super-admin.employees.*') ? 'active' : '' }}">
                                        Data Karyawan
                                   </a>

                                   <a href="{{ $menuUrl('employment') }}"
                                        class="nav-sub-link {{ $routeActive('employment.*', 'employments.*', 'super-admin.employment.*', 'super-admin.employments.*')
                                            ? 'active'
                                            : '' }}">
                                        Data Employment
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="{{ $menuUrl('customers') }}"
                                        class="nav-sub-link {{ $routeActive('customers.*', 'super-admin.customers.*') ? 'active' : '' }}">
                                        Data Pelanggan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isPelayanan || $isOperasional || $isAuditor)
                                   <a href="{{ $menuUrl('serviceCategories') }}"
                                        class="nav-sub-link {{ $routeActive('service-categories.*', 'super-admin.service-categories.*') ? 'active' : '' }}">
                                        Kategori Layanan
                                   </a>

                                   <a href="{{ $menuUrl('services') }}"
                                        class="nav-sub-link {{ $routeActive('services.*', 'super-admin.services.*') ? 'active' : '' }}">
                                        Data Layanan
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- PROSES LAYANAN --}}
               {{-- Alur tabel: service_orders -> service_order_items -> --}}
               {{-- branch_approval_logs -> work_schedules -> --}}
               {{-- employee_schedules -> employee_activities -> --}}
               {{-- service_order_status_histories --}}
               {{-- ======================================================== --}}
               @if ($canAccessServiceProcess)
                    <li class="nav-label">
                         <span class="content-label">Operasional Layanan</span>
                    </li>

                    <li class="nav-item {{ $serviceProcessOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $serviceProcessOpen ? 'active' : '' }}"
                              aria-expanded="{{ $serviceProcessOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-proses-layanan">
                              <i data-feather="briefcase"></i>
                              <span>Proses Layanan</span>
                         </a>

                         <nav id="submenu-proses-layanan" class="nav nav-sub" aria-label="Menu Proses Layanan">
                              @if (!$isKaryawan)
                                   <a href="{{ $menuUrl('serviceOrders') }}"
                                        class="nav-sub-link {{ $routeActive('service-orders.*', 'super-admin.service-orders.*') ? 'active' : '' }}">
                                        1. Pesanan Layanan
                                   </a>

                                   @if ($isSuperAdmin || $isPelayanan || $isOperasional)
                                        <a href="{{ $menuUrl('serviceOrdersCreate') }}"
                                             class="nav-sub-link {{ $routeActive('service-orders.create', 'super-admin.service-orders.create') ? 'active' : '' }}">
                                             Tambah Pesanan
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isPelayanan || $isOperasional || $isAuditor)
                                        <a href="{{ $menuUrl('serviceOrderItems') }}"
                                             class="nav-sub-link {{ $routeActive('service-order-items.*', 'super-admin.service-order-items.*') ? 'active' : '' }}">
                                             2. Item Pesanan
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isDirektur || $isManager || $isOperasional || $isAuditor)
                                        <a href="{{ $menuUrl('branchApprovalLogs') }}"
                                             class="nav-sub-link {{ $routeActive('branch-approval-logs.*', 'super-admin.branch-approval-logs.*', 'service-orders.approvals.*') ? 'active' : '' }}">
                                             3. Persetujuan Cabang
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isManager || $isOperasional || $isAuditor)
                                        <a href="{{ $menuUrl('workSchedules') }}"
                                             class="nav-sub-link {{ $routeActive('work-schedules.*', 'super-admin.work-schedules.*') ? 'active' : '' }}">
                                             4. Jadwal Kerja
                                        </a>

                                        <a href="{{ $menuUrl('employeeSchedules') }}"
                                             class="nav-sub-link {{ $routeActive('employee-schedules.*', 'super-admin.employee-schedules.*') ? 'active' : '' }}">
                                             5. Penugasan Karyawan
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isManager || $isOperasional || $isAuditor)
                                        <a href="{{ $menuUrl('employeeActivities') }}"
                                             class="nav-sub-link {{ $routeActive('employee-activities.*', 'super-admin.employee-activities.*') ? 'active' : '' }}">
                                             6. Aktivitas Pekerjaan
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isAuditor)
                                        <a href="{{ $menuUrl('serviceOrderHistories') }}"
                                             class="nav-sub-link {{ $routeActive('service-order-status-histories.*', 'super-admin.service-order-status-histories.*', 'service-orders.status-histories.*') ? 'active' : '' }}">
                                             7. Riwayat Status Pesanan
                                        </a>
                                   @endif
                              @endif

                              @if ($isKaryawan)
                                   <a href="{{ $menuUrl('employeeSchedulesMine') }}"
                                        class="nav-sub-link {{ $routeActive('employee-schedules.mine', 'employee-schedules.my', 'work-schedules.mine') ? 'active' : '' }}">
                                        Jadwal Kerja Saya
                                   </a>

                                   <a href="{{ $menuUrl('employeeActivitiesMine') }}"
                                        class="nav-sub-link {{ $routeActive('employee-activities.mine', 'employee-activities.my') ? 'active' : '' }}">
                                        Aktivitas Saya
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- SDM OPERASIONAL --}}
               {{-- Tabel: attendances, leave_requests --}}
               {{-- ======================================================== --}}
               @if ($canAccessHrOperations)
                    <li class="nav-label">
                         <span class="content-label">SDM Operasional</span>
                    </li>

                    <li class="nav-item {{ $hrOperationsOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $hrOperationsOpen ? 'active' : '' }}"
                              aria-expanded="{{ $hrOperationsOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-sdm-operasional">
                              <i data-feather="users"></i>
                              <span>{{ $isKaryawan ? 'Administrasi Saya' : 'SDM Operasional' }}</span>
                         </a>

                         <nav id="submenu-sdm-operasional" class="nav nav-sub" aria-label="Menu SDM Operasional">
                              <a href="{{ $menuUrl($isKaryawan ? 'attendancesMine' : 'attendances') }}"
                                   class="nav-sub-link {{ $routeActive('attendances.*', 'super-admin.attendances.*') ? 'active' : '' }}">
                                   {{ $isKaryawan ? 'Kehadiran Saya' : 'Data Kehadiran' }}
                              </a>

                              <a href="{{ $menuUrl($isKaryawan ? 'leaveRequestsMine' : 'leaveRequests') }}"
                                   class="nav-sub-link {{ $routeActive('leave-requests.*', 'super-admin.leave-requests.*') ? 'active' : '' }}">
                                   {{ $isKaryawan ? 'Pengajuan Cuti Saya' : 'Pengajuan Cuti' }}
                              </a>
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- KEUANGAN LAYANAN --}}
               {{-- Alur tabel: expenses -> invoices -> payments --}}
               {{-- ======================================================== --}}
               @if ($canAccessFinance)
                    <li class="nav-label">
                         <span class="content-label">Keuangan Layanan</span>
                    </li>

                    <li class="nav-item {{ $financeOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);" class="nav-link with-sub {{ $financeOpen ? 'active' : '' }}"
                              aria-expanded="{{ $financeOpen ? 'true' : 'false' }}" aria-controls="submenu-keuangan">
                              <i data-feather="credit-card"></i>
                              <span>Keuangan Layanan</span>
                         </a>

                         <nav id="submenu-keuangan" class="nav nav-sub" aria-label="Menu Keuangan Layanan">
                              @if ($isSuperAdmin || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="{{ $menuUrl('expenses') }}"
                                        class="nav-sub-link {{ $routeActive('expenses.*', 'super-admin.expenses.*') ? 'active' : '' }}">
                                        8. Pengeluaran
                                   </a>
                              @endif

                              <a href="{{ $menuUrl('invoices') }}"
                                   class="nav-sub-link {{ $routeActive('invoices.*', 'super-admin.invoices.*') ? 'active' : '' }}">
                                   9. Invoice
                              </a>

                              <a href="{{ $menuUrl('payments') }}"
                                   class="nav-sub-link {{ $routeActive('payments.*', 'super-admin.payments.*') ? 'active' : '' }}">
                                   10. Pembayaran
                              </a>
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- PELAYANAN PELANGGAN --}}
               {{-- Tabel: customer_feedback, customer_complaints --}}
               {{-- ======================================================== --}}
               @if ($canAccessCustomerService)
                    <li class="nav-label">
                         <span class="content-label">Pelayanan Pelanggan</span>
                    </li>

                    <li class="nav-item {{ $customerServiceOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $customerServiceOpen ? 'active' : '' }}"
                              aria-expanded="{{ $customerServiceOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-pelanggan">
                              <i data-feather="message-circle"></i>
                              <span>Pelayanan Pelanggan</span>
                         </a>

                         <nav id="submenu-pelanggan" class="nav nav-sub" aria-label="Menu Pelayanan Pelanggan">
                              <a href="{{ $menuUrl('customerFeedback') }}"
                                   class="nav-sub-link {{ $routeActive('customer-feedback.*', 'super-admin.customer-feedback.*') ? 'active' : '' }}">
                                   11. Feedback Pelanggan
                              </a>

                              <a href="{{ $menuUrl('customerComplaints') }}"
                                   class="nav-sub-link {{ $routeActive('customer-complaints.*', 'super-admin.customer-complaints.*', 'complaints.*') ? 'active' : '' }}">
                                   12. Keluhan Pelanggan
                              </a>

                              @if ($isSuperAdmin || $isPelayanan)
                                   <a href="{{ $menuUrl('customerComplaintsCreate') }}"
                                        class="nav-sub-link {{ $routeActive('customer-complaints.create', 'super-admin.customer-complaints.create', 'complaints.create') ? 'active' : '' }}">
                                        Tambah Keluhan
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- KINERJA KARYAWAN --}}
               {{-- Tabel: performance_indicators, performance_periods, --}}
               {{-- performance_role, employee_targets, employee_performance, --}}
               {{-- performance_details --}}
               {{-- ======================================================== --}}
               @if ($canAccessPerformance)
                    <li class="nav-label">
                         <span class="content-label">
                              {{ $isKaryawan ? 'Kinerja Saya' : 'Kinerja Karyawan' }}
                         </span>
                    </li>

                    <li class="nav-item {{ $performanceOpen ? 'show' : '' }}">
                         <a href="javascript:void(0);"
                              class="nav-link with-sub {{ $performanceOpen ? 'active' : '' }}"
                              aria-expanded="{{ $performanceOpen ? 'true' : 'false' }}"
                              aria-controls="submenu-kinerja">
                              <i data-feather="bar-chart-2"></i>
                              <span>{{ $isKaryawan ? 'Kinerja Saya' : 'Kinerja Karyawan' }}</span>
                         </a>

                         <nav id="submenu-kinerja" class="nav nav-sub" aria-label="Menu Kinerja Karyawan">
                              @if (!$isKaryawan)
                                   @if ($isSuperAdmin || $isHrd)
                                        <a href="{{ $menuUrl('performanceIndicators') }}"
                                             class="nav-sub-link {{ $routeActive('performance-indicators.*', 'super-admin.performance-indicators.*', 'kpi-indicators.*') ? 'active' : '' }}">
                                             Indikator Kinerja
                                        </a>
                                   @endif

                                   {{-- Performance Period saat ini hanya memiliki route Super Admin. --}}
                                   @if ($isSuperAdmin)
                                        <a href="{{ $menuUrl('performancePeriods') }}"
                                             class="nav-sub-link {{ $routeActive('performance-periods.*', 'super-admin.performance-periods.*') ? 'active' : '' }}">
                                             Periode Penilaian
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isHrd)
                                        <a href="{{ $menuUrl('performanceRoles') }}"
                                             class="nav-sub-link {{ $routeActive('performance-roles.*', 'performance-role.*', 'super-admin.performance-roles.*') ? 'active' : '' }}">
                                             Bobot Kinerja per Role
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager)
                                        <a href="{{ $menuUrl('employeeTargets') }}"
                                             class="nav-sub-link {{ $routeActive('employee-targets.*', 'super-admin.employee-targets.*') ? 'active' : '' }}">
                                             Target Karyawan
                                        </a>
                                   @endif

                                   <a href="{{ $menuUrl('employeePerformance') }}"
                                        class="nav-sub-link {{ $routeActive('employee-performance.*', 'employee-performances.*', 'employee-kpi-results.*') ? 'active' : '' }}">
                                        Hasil Kinerja
                                   </a>

                                   <a href="{{ $menuUrl('performanceDetails') }}"
                                        class="nav-sub-link {{ $routeActive('performance-details.*', 'super-admin.performance-details.*', 'performance-evaluations.*') ? 'active' : '' }}">
                                        Detail Penilaian
                                   </a>
                              @endif

                              @if ($isKaryawan)
                                   <a href="{{ $menuUrl('employeePerformanceMine') }}"
                                        class="nav-sub-link {{ $routeActive('employee-performance.mine', 'employee-performances.mine', 'employee-kpi-results.mine', 'employee-kpi-results.my') ? 'active' : '' }}">
                                        Hasil Kinerja Saya
                                   </a>

                                   <a href="{{ $menuUrl('performanceDetailsMine') }}"
                                        class="nav-sub-link {{ $routeActive('performance-details.mine', 'performance-evaluations.mine', 'performance-evaluations.my') ? 'active' : '' }}">
                                        Detail Penilaian Saya
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- LAPORAN --}}
               {{-- dashboard_snapshots diposisikan sebagai arsip laporan, --}}
               {{-- bukan transaksi operasional. --}}
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
                                   <a href="{{ $menuUrl('reportServices') }}"
                                        class="nav-sub-link {{ $routeActive('reports.services', 'reports.transactions') ? 'active' : '' }}">
                                        Laporan Layanan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isAuditor)
                                   <a href="{{ $menuUrl('reportPerformance') }}"
                                        class="nav-sub-link {{ $routeActive('reports.performance') ? 'active' : '' }}">
                                        Laporan Kinerja
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isAuditor)
                                   <a href="{{ $menuUrl('reportCustomers') }}"
                                        class="nav-sub-link {{ $routeActive('reports.customers', 'reports.satisfaction') ? 'active' : '' }}">
                                        Laporan Feedback
                                   </a>

                                   <a href="{{ $menuUrl('reportComplaints') }}"
                                        class="nav-sub-link {{ $routeActive('reports.complaints') ? 'active' : '' }}">
                                        Laporan Keluhan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isKeuangan || $isAuditor)
                                   <a href="{{ $menuUrl('reportFinance') }}"
                                        class="nav-sub-link {{ $routeActive('reports.finance') ? 'active' : '' }}">
                                        Laporan Keuangan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isAuditor)
                                   <a href="{{ $menuUrl('dashboardSnapshots') }}"
                                        class="nav-sub-link {{ $routeActive('dashboard-snapshots.*', 'super-admin.dashboard-snapshots.*') ? 'active' : '' }}">
                                        Snapshot Dashboard
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- SISTEM --}}
               {{-- Tabel: users, roles, permissions, audit_logs, --}}
               {{-- system_settings. Tabel pivot/framework tidak dijadikan menu. --}}
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
                                   <a href="{{ $menuUrl('users') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.users.*', 'users.*') ? 'active' : '' }}">
                                        Pengguna
                                   </a>
                              @endif

                              @if ($isSuperAdmin)
                                   <a href="{{ $menuUrl('roles') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.roles.*', 'roles.*') ? 'active' : '' }}">
                                        Role
                                   </a>

                                   <a href="{{ $menuUrl('permissions') }}"
                                        class="nav-sub-link {{ $routeActive('permissions.*', 'super-admin.permissions.*') ? 'active' : '' }}">
                                        Hak Akses
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isAuditor)
                                   <a href="{{ $menuUrl('auditLogs') }}"
                                        class="nav-sub-link {{ $routeActive('audit-logs.*', 'super-admin.audit-logs.*') ? 'active' : '' }}">
                                        Log Audit
                                   </a>
                              @endif

                              @if ($isSuperAdmin)
                                   <a href="{{ $menuUrl('systemSettings') }}"
                                        class="nav-sub-link {{ $routeActive('system-settings.*', 'settings.*') ? 'active' : '' }}">
                                        Pengaturan Aplikasi
                                   </a>
                              @endif
                         </nav>
                    </li>
               @endif

               {{-- ======================================================== --}}
               {{-- AKUN --}}
               {{-- Tabel: notifications serta profil pengguna --}}
               {{-- ======================================================== --}}
               <li class="nav-label">
                    <span class="content-label">Akun</span>
               </li>

               <li class="nav-item">
                    <a href="{{ $menuUrl('notifications') }}"
                         class="nav-link {{ $routeActive('notifications.*') ? 'active' : '' }}">
                         <i data-feather="bell"></i>
                         <span>Notifikasi</span>
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
