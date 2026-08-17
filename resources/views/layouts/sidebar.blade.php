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
         'direktur_manager' => 'direktur_utama',
         'direktur manager' => 'direktur_utama',
         'direkturutama' => 'direktur_utama',
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

     // Fallback ketika role aktif tidak tersimpan konsisten di session.
     if (
         !in_array(
             $activeRole,
             [
                 'super_admin',
                 'direktur_utama',
                 'hrd_manager',
                 'manager_departemen',
                 'karyawan',
                 'admin_pelayanan',
                 'admin_operasional',
                 'finance_staff',
                 'auditor_internal',
             ],
             true,
         )
     ) {
         if (request()->routeIs('direktur-utama.*')) {
             $activeRole = 'direktur_utama';
         }
     }

     /*
    |--------------------------------------------------------------------------
    | HELPER FUNCTIONS
    |--------------------------------------------------------------------------
    */
     $hasRole = static function (array $roles) use ($user, $activeRole, $roleAliases): bool {
         if (in_array($activeRole, $roles, true)) {
             return true;
         }

         if ($user && method_exists($user, 'hasRole')) {
             foreach ($roles as $role) {
                 if ($user->hasRole($role)) {
                     return true;
                 }
             }
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

     /**
      * Helper untuk permission checking
      * Menggunakan system permission yang sudah didefinisikan di RolePermissionSeeder
      */
     $canAccess = static function (string $permission) use ($user, $hasRole): bool {
         if (!$user) {
             return false;
         }

         // Super Administrator selalu memiliki akses menu penuh.
         if ($hasRole(['super_admin'])) {
             return true;
         }

         if ($user->can($permission)) {
             return true;
         }

         // Fallback untuk kasus sinkronisasi permission cache/session belum konsisten.
         if ($hasRole(['direktur_utama'])) {
             $direkturPermissionFallback = [
                 'dashboard.view',
                 'dashboard.executive.view',
                 'branches.view',
                 'departments.view',
                 'positions.view',
                 'employees.view',
                 'customers.view',
                 'services.view',
                 'service_categories.view',
                 'service_orders.view',
                 'service_order_items.view',
                 'service_order_status.view',
                 'work_schedules.view',
                 'employee_schedules.view',
                 'employee_activities.view',
                 'attendances.view',
                 'leave_requests.view',
                 'performance_indicators.view',
                 'performance_periods.view',
                 'performance_roles.view',
                 'employee_targets.view',
                 'employee_performance.view',
                 'performance_details.view',
                 'customer_feedback.view',
                 'customer_complaints.view',
                 'invoices.view',
                 'payments.view',
                 'expenses.view',
                 'reports.view',
                 'reports.export',
                 'reports.services',
                 'reports.performance',
                 'reports.customers',
                 'reports.complaints',
                 'reports.finance',
                 'reports.hr',
             ];

             return in_array($permission, $direkturPermissionFallback, true);
         }

         if ($hasRole(['manager_departemen'])) {
             $managerPermissionFallback = [
                 'dashboard.view',
                 'branches.view',
                 'employees.view',
                 'employee_activities.view',
                 'attendances.view',
                 'leave_requests.view',
                 'leave_requests.approve',
                 'leave_requests.reject',
                 'employee_targets.view',
                 'employee_performance.view',
                 'performance_details.view',
                 'reports.view',
                 'reports.export',
                 'reports.hr',
                 'reports.performance',
             ];

             return in_array($permission, $managerPermissionFallback, true);
         }

         if ($hasRole(['karyawan'])) {
             $karyawanPermissionFallback = [
                 'dashboard.view',
                 'employees.view_own',
                 'employees.edit_own',
                 'attendances.view_own',
                 'attendances.checkin',
                 'attendances.checkout',
                 'leave_requests.view_own',
                 'leave_requests.create',
                 'employee_schedules.view_own',
                 'employee_activities.view_own',
                 'employee_performance.view_own',
                 'performance_details.view_own',
             ];

             return in_array($permission, $karyawanPermissionFallback, true);
         }

         if ($hasRole(['admin_pelayanan'])) {
             $adminPelayananPermissionFallback = [
                 'dashboard.view',
                 'customers.view',
                 'customers.create',
                 'customers.edit',
                 'services.view',
                 'service_categories.view',
                 'service_orders.view',
                 'service_orders.create',
                 'service_orders.edit',
                 'service_order_items.view',
                 'service_order_items.create',
                 'service_order_items.edit',
                 'customer_feedback.view',
                 'customer_feedback.create',
                 'customer_complaints.view',
                 'customer_complaints.create',
                 'invoices.view',
                 'invoices.create',
                 'invoices.edit',
                 'reports.view',
                 'reports.export',
                 'reports.services',
                 'reports.customers',
                 'reports.complaints',
             ];

             return in_array($permission, $adminPelayananPermissionFallback, true);
         }

         if ($hasRole(['admin_operasional'])) {
             $adminOperasionalPermissionFallback = [
                 'dashboard.view',
                 'branches.view',
                 'employees.view',
                 'customers.view',
                 'services.view',
                 'service_categories.view',
                 'service_orders.view',
                 'service_orders.create',
                 'service_orders.edit',
                 'service_order_items.view',
                 'service_order_status.view',
                 'work_schedules.view',
                 'work_schedules.create',
                 'work_schedules.edit',
                 'employee_schedules.view',
                 'employee_schedules.create',
                 'employee_schedules.edit',
                 'employee_activities.view',
                 'employee_activities.create',
                 'employee_activities.edit',
                 'attendances.view',
                 'leave_requests.view',
                 'employee_performance.view',
                 'expenses.view',
                 'invoices.view',
                 'payments.view',
                 'reports.view',
                 'reports.export',
                 'reports.services',
                 'reports.performance',
             ];

             return in_array($permission, $adminOperasionalPermissionFallback, true);
         }

         if ($hasRole(['finance_staff'])) {
             $financePermissionFallback = [
                 'dashboard.view',
                 'service_orders.view',
                 'service_order_items.view',
                 'invoices.view',
                 'invoices.create',
                 'invoices.edit',
                 'invoices.approve',
                 'payments.view',
                 'payments.create',
                 'payments.edit',
                 'payments.approve',
                 'expenses.view',
                 'expenses.create',
                 'expenses.edit',
                 'expenses.approve',
                 'reports.view',
                 'reports.export',
                 'reports.services',
                 'reports.finance',
             ];

             return in_array($permission, $financePermissionFallback, true);
         }

         if ($hasRole(['auditor_internal'])) {
             $auditorPermissionFallback = [
                 'dashboard.view',
                 'branches.view',
                 'departments.view',
                 'positions.view',
                 'employees.view',
                 'customers.view',
                 'services.view',
                 'service_categories.view',
                 'service_orders.view',
                 'service_order_items.view',
                 'service_order_status.view',
                 'work_schedules.view',
                 'employee_schedules.view',
                 'employee_activities.view',
                 'attendances.view',
                 'leave_requests.view',
                 'performance_indicators.view',
                 'employee_targets.view',
                 'employee_performance.view',
                 'performance_details.view',
                 'invoices.view',
                 'payments.view',
                 'expenses.view',
                 'customer_feedback.view',
                 'customer_complaints.view',
                 'audit_logs.view',
                 'audit_logs.export',
                 'reports.view',
                 'reports.export',
                 'reports.services',
                 'reports.performance',
                 'reports.customers',
                 'reports.complaints',
                 'reports.finance',
                 'reports.hr',
             ];

             return in_array($permission, $auditorPermissionFallback, true);
         }

         return false;
     };

     $routeUrl = static function (string|array $routeNames, array $parameters = []): string {
         foreach ((array) $routeNames as $routeName) {
             if (\Illuminate\Support\Facades\Route::has($routeName)) {
                 return route($routeName, $parameters);
             }
         }

         return '#';
     };

     $routeActive = static function (string ...$patterns): bool {
         return request()->routeIs(...$patterns);
     };

     /*
    |--------------------------------------------------------------------------
    | ROLE CHECK (Backward Compatibility)
    |--------------------------------------------------------------------------
    */
     $isSuperAdmin = $hasRole(['super_admin']);
     $isDirektur = $hasRole(['direktur_utama']);
     $isHrd = $hasRole(['hrd_manager']);
     $isManager = $hasRole(['manager_departemen']);
     $isManage = $isManager;
     $isKaryawan = $hasRole(['karyawan']);
     $isPelayanan = $hasRole(['admin_pelayanan']);
     $isOperasional = $hasRole(['admin_operasional']);
     $isKeuangan = $hasRole(['finance_staff']);
     $isAuditor = $hasRole(['auditor_internal']);

     // Guard tambahan agar role direktur tetap terbaca saat session active role tidak sinkron.
     $roleHints = collect([
         (string) $rawActiveRole,
         (string) $spatieRole,
         (string) data_get($user, 'role_name', ''),
         (string) data_get($user, 'role', ''),
     ]);

     if ($user && method_exists($user, 'activeRoleName')) {
         $roleHints->push((string) ($user->activeRoleName() ?? ''));
     }

     if ($user && method_exists($user, 'activeRole')) {
         $roleHints->push((string) data_get($user->activeRole(), 'name', ''));
     }

     if ($user && method_exists($user, 'getRoleNames')) {
         $roleHints = $roleHints->merge($user->getRoleNames()->all());
     }

     $roleHintText = $roleHints
         ->filter(static function ($value): bool {
             return filled($value);
         })
         ->map(static function ($value): string {
             return \Illuminate\Support\Str::of((string) $value)
                 ->lower()
                 ->replace(['-', '_'], ' ')
                 ->toString();
         })
         ->implode(' ');

     $isDirekturByHint = \Illuminate\Support\Str::contains($roleHintText, ['direktur', 'executive']);
     $isDirekturRoute = request()->routeIs('direktur-utama.*') || request()->is('direktur-utama/*');

     $isDirektur = $isDirektur || $isDirekturByHint || $isDirekturRoute;

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
             'branch-approval-log.index',
             'branch-approval-logs.index',
             'super-admin.branch-approval-logs.index',
             'branches.index',
             'super-admin.branches.index',
             'service-orders.approvals.index',
         ],
         'workSchedules' => ['super-admin.work-schedules.index', 'work-schedules.index'],
         'employeeSchedules' => [
             'super-admin.employee-schedules.index',
             'employee-schedules.index',
             'super-admin.work-schedules.index',
             'work-schedules.index',
         ],
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
             'super-admin.service_order_status_histories.index',
         ],

         // SDM operasional
         'attendances' => ['attendances.index', 'super-admin.attendances.index'],
         'attendancesMine' => ['attendances.mine', 'attendances.my', 'attendances.index'],
         'checkIn' => ['attendances.checkin', 'attendances.index'],
         'checkOut' => ['attendances.checkout', 'attendances.index'],
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

     $hasMenuRoute = static function (string $key, array $parameters = []) use ($menuUrl): bool {
         return $menuUrl($key, $parameters) !== '#';
     };

     $canUseMenuKey = static function (string $key) use ($activeRole): bool {
         $blockedByRole = [
             // Sementara route modul ini masih dibatasi role super_admin di web.php.
             'admin_pelayanan' => [
                 'serviceCategories',
                 'services',
                 'serviceOrders',
                 'serviceOrdersCreate',
                 'serviceOrderItems',
                 'serviceOrderHistories',
                 'customerFeedback',
                 'customerComplaints',
                 'customerComplaintsCreate',
                 'reportServices',
                 'reportCustomers',
                 'reportComplaints',
             ],
         ];

         return !in_array($key, $blockedByRole[$activeRole] ?? [], true);
     };

     /*
    |--------------------------------------------------------------------------
    | HAK AKSES MENU - PERMISSION-BASED
    |--------------------------------------------------------------------------
    */
     $canAccessMasterData =
         $canAccess('branches.view') ||
         $canAccess('departments.view') ||
         $canAccess('positions.view') ||
         $canAccess('employees.view') ||
         $canAccess('customers.view') ||
         $canAccess('service_categories.view') ||
         $canAccess('services.view');

     $canAccessServiceProcess =
         ($canAccess('service_orders.view') && $hasMenuRoute('serviceOrders') && $canUseMenuKey('serviceOrders')) ||
         ($canAccess('service_orders.create') &&
             $hasMenuRoute('serviceOrdersCreate') &&
             $canUseMenuKey('serviceOrdersCreate')) ||
         ($canAccess('service_order_items.view') &&
             $hasMenuRoute('serviceOrderItems') &&
             $canUseMenuKey('serviceOrderItems')) ||
         ($canAccess('branch_approvals.view') && $hasMenuRoute('branchApprovalLogs')) ||
         ($canAccess('work_schedules.view') && $hasMenuRoute('workSchedules')) ||
         ($canAccess('employee_schedules.view') && $hasMenuRoute('employeeSchedules')) ||
         ($canAccess('employee_activities.view') && $hasMenuRoute('employeeActivities')) ||
         ($canAccess('service_order_status.view') &&
             $hasMenuRoute('serviceOrderHistories') &&
             $canUseMenuKey('serviceOrderHistories')) ||
         ($canAccess('employee_activities.view_own') && $hasMenuRoute('employeeActivitiesMine')) ||
         ($canAccess('employee_schedules.view_own') && $hasMenuRoute('employeeSchedulesMine'));

     $canAccessHrOperations =
         $canAccess('attendances.view') ||
         $canAccess('attendances.view_own') ||
         $canAccess('leave_requests.view') ||
         $canAccess('leave_requests.view_own');

     $canAccessFinance = $canAccess('expenses.view') || $canAccess('invoices.view') || $canAccess('payments.view');

     $canAccessPerformance =
         ($canAccess('performance_indicators.view') && $hasMenuRoute('performanceIndicators')) ||
         ($canAccess('performance_periods.view') && $hasMenuRoute('performancePeriods')) ||
         ($canAccess('employee_targets.view') && $hasMenuRoute('employeeTargets')) ||
         ($canAccess('employee_performance.view') && $hasMenuRoute('employeePerformance')) ||
         ($canAccess('performance_details.view') && $hasMenuRoute('performanceDetails')) ||
         ($canAccess('employee_performance.view_own') && $hasMenuRoute('employeePerformanceMine')) ||
         ($canAccess('performance_details.view_own') && $hasMenuRoute('performanceDetailsMine'));

     $canAccessCustomerService =
         ($canAccess('customer_feedback.view') &&
             $hasMenuRoute('customerFeedback') &&
             $canUseMenuKey('customerFeedback')) ||
         ($canAccess('customer_complaints.view') &&
             $hasMenuRoute('customerComplaints') &&
             $canUseMenuKey('customerComplaints'));

     $canAccessReports =
         (($canAccess('reports.view') || $canAccess('reports.services')) &&
             $hasMenuRoute('reportServices') &&
             $canUseMenuKey('reportServices')) ||
         (($canAccess('reports.view') || $canAccess('reports.performance')) && $hasMenuRoute('reportPerformance')) ||
         (($canAccess('reports.view') || $canAccess('reports.customers')) &&
             $hasMenuRoute('reportCustomers') &&
             $canUseMenuKey('reportCustomers')) ||
         (($canAccess('reports.view') || $canAccess('reports.complaints')) &&
             $hasMenuRoute('reportComplaints') &&
             $canUseMenuKey('reportComplaints')) ||
         (($canAccess('reports.view') || $canAccess('reports.finance')) && $hasMenuRoute('reportFinance')) ||
         ($canAccess('dashboard_snapshots.view') && $hasMenuRoute('dashboardSnapshots'));

     $canAccessSystem =
         $canAccess('users.view') ||
         $canAccess('roles.view') ||
         $canAccess('permissions.view') ||
         $canAccess('audit_logs.view');

     if ($isDirekturRoute || $isDirekturByHint) {
         $canAccessMasterData = true;
         $canAccessServiceProcess = true;
         $canAccessFinance = true;
         $canAccessPerformance = true;
         $canAccessCustomerService = true;
         $canAccessReports = true;
     }

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
         'super-admin.service_order_status_histories.*',
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
                              @if ($canAccess('branches.view'))
                                   <a href="{{ $menuUrl('branches') }}"
                                        class="nav-sub-link {{ $routeActive('branches.*', 'super-admin.branches.*') ? 'active' : '' }}">
                                        Data Cabang
                                   </a>
                              @endif

                              @if ($canAccess('departments.view'))
                                   <a href="{{ $menuUrl('departments') }}"
                                        class="nav-sub-link {{ $routeActive('departments.*', 'super-admin.departments.*') ? 'active' : '' }}">
                                        Data Departemen
                                   </a>
                              @endif

                              @if ($canAccess('positions.view'))
                                   <a href="{{ $menuUrl('positions') }}"
                                        class="nav-sub-link {{ $routeActive('positions.*', 'super-admin.positions.*') ? 'active' : '' }}">
                                        Data Jabatan
                                   </a>
                              @endif

                              @if ($canAccess('employees.view'))
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

                              @if ($canAccess('customers.view'))
                                   <a href="{{ $menuUrl('customers') }}"
                                        class="nav-sub-link {{ $routeActive('customers.*', 'super-admin.customers.*') ? 'active' : '' }}">
                                        Data Pelanggan
                                   </a>
                              @endif

                              @if ($canAccess('service_categories.view') && $canUseMenuKey('serviceCategories'))
                                   <a href="{{ $menuUrl('serviceCategories') }}"
                                        class="nav-sub-link {{ $routeActive('service-categories.*', 'super-admin.service-categories.*') ? 'active' : '' }}">
                                        Kategori Layanan
                                   </a>
                              @endif

                              @if ($canAccess('services.view') && $canUseMenuKey('services'))
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
                                   @if ($canAccess('service_orders.view') && $hasMenuRoute('serviceOrders') && $canUseMenuKey('serviceOrders'))
                                        <a href="{{ $menuUrl('serviceOrders') }}"
                                             class="nav-sub-link {{ $routeActive('service-orders.*', 'super-admin.service-orders.*') ? 'active' : '' }}">
                                             1. Pesanan Layanan
                                        </a>
                                   @endif

                                   @if (
                                       $canAccess('service_orders.create') &&
                                           $hasMenuRoute('serviceOrdersCreate') &&
                                           $canUseMenuKey('serviceOrdersCreate'))
                                        <a href="{{ $menuUrl('serviceOrdersCreate') }}"
                                             class="nav-sub-link {{ $routeActive('service-orders.create', 'super-admin.service-orders.create') ? 'active' : '' }}">
                                             Tambah Pesanan
                                        </a>
                                   @endif

                                   @if ($canAccess('service_order_items.view') && $hasMenuRoute('serviceOrderItems') && $canUseMenuKey('serviceOrderItems'))
                                        <a href="{{ $menuUrl('serviceOrderItems') }}"
                                             class="nav-sub-link {{ $routeActive('service-order-items.*', 'super-admin.service-order-items.*') ? 'active' : '' }}">
                                             2. Item Pesanan
                                        </a>
                                   @endif

                                   @if ($canAccess('branch_approvals.view') && $hasMenuRoute('branchApprovalLogs'))
                                        <a href="{{ $menuUrl('branchApprovalLogs') }}"
                                             class="nav-sub-link {{ $routeActive('service-orders.approvals.*', 'branches.approve', 'branches.reject') ? 'active' : '' }}">
                                             3. Persetujuan Cabang
                                        </a>
                                   @endif

                                   @if ($canAccess('work_schedules.view') && $hasMenuRoute('workSchedules'))
                                        <a href="{{ $menuUrl('workSchedules') }}"
                                             class="nav-sub-link {{ $routeActive('work-schedules.*', 'super-admin.work-schedules.*') ? 'active' : '' }}">
                                             4. Jadwal Kerja
                                        </a>
                                   @endif

                                   @if ($canAccess('employee_schedules.view') && $hasMenuRoute('employeeSchedules'))
                                        <a href="{{ $menuUrl('employeeSchedules') }}"
                                             class="nav-sub-link {{ $routeActive('employee-schedules.*', 'super-admin.employee-schedules.*') ? 'active' : '' }}">
                                             5. Penugasan Karyawan
                                        </a>
                                   @endif

                                   @if ($canAccess('employee_activities.view') && $hasMenuRoute('employeeActivities'))
                                        <a href="{{ $menuUrl('employeeActivities') }}"
                                             class="nav-sub-link {{ $routeActive('employee-activities.*', 'super-admin.employee-activities.*') ? 'active' : '' }}">
                                             6. Aktivitas Pekerjaan
                                        </a>
                                   @endif

                                   @if (
                                       $canAccess('service_order_status.view') &&
                                           $hasMenuRoute('serviceOrderHistories') &&
                                           $canUseMenuKey('serviceOrderHistories'))
                                        <a href="{{ $menuUrl('serviceOrderHistories') }}"
                                             class="nav-sub-link {{ $routeActive('service-order-status-histories.*', 'super-admin.service-order-status-histories.*', 'super-admin.service_order_status_histories.*', 'service-orders.status-histories.*') ? 'active' : '' }}">
                                             7. Riwayat Status Pesanan
                                        </a>
                                   @endif
                              @endif

                              @if ($isKaryawan)
                                   @if ($canAccess('employee_schedules.view_own') && $hasMenuRoute('employeeSchedulesMine'))
                                        <a href="{{ $menuUrl('employeeSchedulesMine') }}"
                                             class="nav-sub-link {{ $routeActive('employee-schedules.mine', 'employee-schedules.my', 'work-schedules.mine') ? 'active' : '' }}">
                                             Jadwal Kerja Saya
                                        </a>
                                   @endif

                                   @if ($canAccess('employee_activities.view_own') && $hasMenuRoute('employeeActivitiesMine'))
                                        <a href="{{ $menuUrl('employeeActivitiesMine') }}"
                                             class="nav-sub-link {{ $routeActive('employee-activities.mine', 'employee-activities.my') ? 'active' : '' }}">
                                             Aktivitas Saya
                                        </a>
                                   @endif
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
                              @if ($canAccess('attendances.view') || $canAccess('attendances.view_own'))
                                   <a href="{{ $menuUrl($isKaryawan ? 'attendancesMine' : 'attendances') }}"
                                        class="nav-sub-link {{ $routeActive('attendances.*', 'super-admin.attendances.*') ? 'active' : '' }}">
                                        {{ $isKaryawan ? 'Kehadiran Saya' : 'Data Kehadiran' }}
                                   </a>
                              @endif

                              @if ($isKaryawan && $canAccess('attendances.checkin'))
                                   <a href="{{ $menuUrl('checkIn') }}"
                                        class="nav-sub-link {{ $routeActive('attendances.checkin') ? 'active' : '' }}">
                                        Check In
                                   </a>
                              @endif

                              @if ($isKaryawan && $canAccess('attendances.checkout'))
                                   <a href="{{ $menuUrl('checkOut') }}"
                                        class="nav-sub-link {{ $routeActive('attendances.checkout') ? 'active' : '' }}">
                                        Check Out
                                   </a>
                              @endif

                              @if ($canAccess('leave_requests.view') || $canAccess('leave_requests.view_own'))
                                   <a href="{{ $menuUrl($isKaryawan ? 'leaveRequestsMine' : 'leaveRequests') }}"
                                        class="nav-sub-link {{ $routeActive('leave-requests.*', 'super-admin.leave-requests.*') ? 'active' : '' }}">
                                        {{ $isKaryawan ? 'Pengajuan Cuti Saya' : 'Pengajuan Cuti' }}
                                   </a>
                              @endif
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
                              @if ($canAccess('expenses.view'))
                                   <a href="{{ $menuUrl('expenses') }}"
                                        class="nav-sub-link {{ $routeActive('expenses.*', 'super-admin.expenses.*') ? 'active' : '' }}">
                                        8. Pengeluaran
                                   </a>
                              @endif

                              @if ($canAccess('invoices.view'))
                                   <a href="{{ $menuUrl('invoices') }}"
                                        class="nav-sub-link {{ $routeActive('invoices.*', 'super-admin.invoices.*') ? 'active' : '' }}">
                                        9. Invoice
                                   </a>
                              @endif

                              @if ($canAccess('payments.view'))
                                   <a href="{{ $menuUrl('payments') }}"
                                        class="nav-sub-link {{ $routeActive('payments.*', 'super-admin.payments.*') ? 'active' : '' }}">
                                        10. Pembayaran
                                   </a>
                              @endif
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
                              @if ($canAccess('customer_feedback.view') && $hasMenuRoute('customerFeedback') && $canUseMenuKey('customerFeedback'))
                                   <a href="{{ $menuUrl('customerFeedback') }}"
                                        class="nav-sub-link {{ $routeActive('customer-feedback.*', 'super-admin.customer-feedback.*') ? 'active' : '' }}">
                                        11. Feedback Pelanggan
                                   </a>
                              @endif

                              @if (
                                  $canAccess('customer_complaints.view') &&
                                      $hasMenuRoute('customerComplaints') &&
                                      $canUseMenuKey('customerComplaints'))
                                   <a href="{{ $menuUrl('customerComplaints') }}"
                                        class="nav-sub-link {{ $routeActive('customer-complaints.*', 'super-admin.customer-complaints.*', 'complaints.*') ? 'active' : '' }}">
                                        12. Keluhan Pelanggan
                                   </a>

                                   @if (
                                       $canAccess('customer_complaints.create') &&
                                           $hasMenuRoute('customerComplaintsCreate') &&
                                           $canUseMenuKey('customerComplaintsCreate'))
                                        <a href="{{ $menuUrl('customerComplaintsCreate') }}"
                                             class="nav-sub-link {{ $routeActive('customer-complaints.create', 'super-admin.customer-complaints.create', 'complaints.create') ? 'active' : '' }}">
                                             Tambah Keluhan
                                        </a>
                                   @endif
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
                                   @if ($canAccess('performance_indicators.view') && $hasMenuRoute('performanceIndicators'))
                                        <a href="{{ $menuUrl('performanceIndicators') }}"
                                             class="nav-sub-link {{ $routeActive('performance-indicators.*', 'super-admin.performance-indicators.*', 'kpi-indicators.*') ? 'active' : '' }}">
                                             Indikator Kinerja
                                        </a>
                                   @endif

                                   @if ($canAccess('performance_periods.view') && $hasMenuRoute('performancePeriods'))
                                        <a href="{{ $menuUrl('performancePeriods') }}"
                                             class="nav-sub-link {{ $routeActive('performance-periods.*', 'super-admin.performance-periods.*') ? 'active' : '' }}">
                                             Periode Penilaian
                                        </a>
                                   @endif

                                   @if ($canAccess('employee_targets.view') && $hasMenuRoute('employeeTargets'))
                                        <a href="{{ $menuUrl('employeeTargets') }}"
                                             class="nav-sub-link {{ $routeActive('employee-targets.*', 'super-admin.employee-targets.*') ? 'active' : '' }}">
                                             Target Karyawan
                                        </a>
                                   @endif

                                   @if ($canAccess('employee_performance.view') && $hasMenuRoute('employeePerformance'))
                                        <a href="{{ $menuUrl('employeePerformance') }}"
                                             class="nav-sub-link {{ $routeActive('employee-performance.*', 'employee-performances.*', 'employee-kpi-results.*') ? 'active' : '' }}">
                                             Hasil Kinerja
                                        </a>
                                   @endif

                                   @if ($canAccess('performance_details.view') && $hasMenuRoute('performanceDetails'))
                                        <a href="{{ $menuUrl('performanceDetails') }}"
                                             class="nav-sub-link {{ $routeActive('performance-details.*', 'super-admin.performance-details.*', 'performance-evaluations.*') ? 'active' : '' }}">
                                             Detail Penilaian
                                        </a>
                                   @endif
                              @endif

                              @if ($isKaryawan)
                                   @if ($canAccess('employee_performance.view_own') && $hasMenuRoute('employeePerformanceMine'))
                                        <a href="{{ $menuUrl('employeePerformanceMine') }}"
                                             class="nav-sub-link {{ $routeActive('employee-performance.mine', 'employee-performances.mine', 'employee-kpi-results.mine', 'employee-kpi-results.my') ? 'active' : '' }}">
                                             Hasil Kinerja Saya
                                        </a>
                                   @endif

                                   @if ($canAccess('performance_details.view_own') && $hasMenuRoute('performanceDetailsMine'))
                                        <a href="{{ $menuUrl('performanceDetailsMine') }}"
                                             class="nav-sub-link {{ $routeActive('performance-details.mine', 'performance-evaluations.mine', 'performance-evaluations.my') ? 'active' : '' }}">
                                             Detail Penilaian Saya
                                        </a>
                                   @endif
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
                              @if ($canAccess('reports.view') || $canAccess('reports.services'))
                                   @if ($hasMenuRoute('reportServices') && $canUseMenuKey('reportServices'))
                                        <a href="{{ $menuUrl('reportServices') }}"
                                             class="nav-sub-link {{ $routeActive('reports.services', 'reports.transactions') ? 'active' : '' }}">
                                             Laporan Layanan
                                        </a>
                                   @endif
                              @endif

                              @if (($canAccess('reports.view') || $canAccess('reports.performance')) && $hasMenuRoute('reportPerformance'))
                                   <a href="{{ $menuUrl('reportPerformance') }}"
                                        class="nav-sub-link {{ $routeActive('reports.performance') ? 'active' : '' }}">
                                        Laporan Kinerja
                                   </a>
                              @endif

                              @if (
                                  ($canAccess('reports.view') || $canAccess('reports.customers')) &&
                                      $hasMenuRoute('reportCustomers') &&
                                      $canUseMenuKey('reportCustomers'))
                                   <a href="{{ $menuUrl('reportCustomers') }}"
                                        class="nav-sub-link {{ $routeActive('reports.customers', 'reports.satisfaction') ? 'active' : '' }}">
                                        Laporan Feedback
                                   </a>
                              @endif

                              @if (
                                  ($canAccess('reports.view') || $canAccess('reports.complaints')) &&
                                      $hasMenuRoute('reportComplaints') &&
                                      $canUseMenuKey('reportComplaints'))
                                   <a href="{{ $menuUrl('reportComplaints') }}"
                                        class="nav-sub-link {{ $routeActive('reports.complaints') ? 'active' : '' }}">
                                        Laporan Keluhan
                                   </a>
                              @endif

                              @if (($canAccess('reports.view') || $canAccess('reports.finance')) && $hasMenuRoute('reportFinance'))
                                   <a href="{{ $menuUrl('reportFinance') }}"
                                        class="nav-sub-link {{ $routeActive('reports.finance') ? 'active' : '' }}">
                                        Laporan Keuangan
                                   </a>
                              @endif

                              @if ($canAccess('dashboard_snapshots.view') && $hasMenuRoute('dashboardSnapshots'))
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
                              @if ($canAccess('users.view'))
                                   <a href="{{ $menuUrl('users') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.users.*', 'users.*') ? 'active' : '' }}">
                                        Pengguna
                                   </a>
                              @endif

                              @if ($canAccess('roles.view'))
                                   <a href="{{ $menuUrl('roles') }}"
                                        class="nav-sub-link {{ $routeActive('super-admin.roles.*', 'roles.*') ? 'active' : '' }}">
                                        Role
                                   </a>
                              @endif

                              @if ($canAccess('permissions.view'))
                                   <a href="{{ $menuUrl('permissions') }}"
                                        class="nav-sub-link {{ $routeActive('permissions.*', 'super-admin.permissions.*') ? 'active' : '' }}">
                                        Hak Akses
                                   </a>
                              @endif

                              @if ($canAccess('audit_logs.view'))
                                   <a href="{{ $menuUrl('auditLogs') }}"
                                        class="nav-sub-link {{ $routeActive('audit-logs.*', 'super-admin.audit-logs.*') ? 'active' : '' }}">
                                        Log Audit
                                   </a>
                              @endif

                              @if ($canAccess('system_settings.view'))
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
          .sidebar {
               background: linear-gradient(180deg, #243447 0%, #2d4258 55%, #365069 100%) !important;
               box-shadow: 10px 0 30px rgba(20, 33, 49, 0.14);
          }

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
