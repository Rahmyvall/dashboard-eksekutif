@php
     /*
    |--------------------------------------------------------------------------
    | USER DAN ROLE AKTIF
    |--------------------------------------------------------------------------
    */
     $user = auth()->user();

     $rawActiveRole =
         session('active_role_name') ??
         (session('active_role') ??
             (data_get($user, 'active_role_name') ??
                 (data_get($user, 'role_name') ?? (data_get($user, 'role') ?? ''))));

     // Mendukung active_role yang disimpan sebagai array/object/model.
     if (is_array($rawActiveRole) || is_object($rawActiveRole)) {
         $rawActiveRole =
             data_get($rawActiveRole, 'slug') ??
             (data_get($rawActiveRole, 'name') ?? (data_get($rawActiveRole, 'code') ?? ''));
     }

     // Menyamakan format: "Direktur Utama", "direktur-utama" -> "direktur_utama".
     $normalizedRole = \Illuminate\Support\Str::of((string) $rawActiveRole)
         ->trim()
         ->lower()
         ->replace(['-', ' '], '_')
         ->replaceMatches('/_+/', '_')
         ->toString();

     // Alias role lama/Indonesia diarahkan ke satu nama role canonical.
     $roleAliases = [
         'superadmin' => 'super_admin',
         'super_admin' => 'super_admin',

         'direktur' => 'executive',
         'direktur_utama' => 'executive',
         'executive' => 'executive',

         'hrd' => 'hr',
         'human_resource' => 'hr',
         'human_resources' => 'hr',
         'hr' => 'hr',

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

         'keuangan' => 'finance',
         'financial' => 'finance',
         'finance' => 'finance',

         'audit' => 'auditor',
         'auditor' => 'auditor',
     ];

     $activeRole = $roleAliases[$normalizedRole] ?? $normalizedRole;

     /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
     $hasRole = static fn(array $roles): bool => in_array($activeRole, $roles, true);

     // Mengambil route pertama yang tersedia agar sidebar tidak error
     // saat sebagian modul/route belum dibuat.
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
     $isDirektur = $hasRole(['executive']);
     $isHrd = $hasRole(['hr']);
     $isManager = $hasRole(['manager_departemen']);
     $isKaryawan = $hasRole(['karyawan']);
     $isPelayanan = $hasRole(['admin_pelayanan']);
     $isOperasional = $hasRole(['admin_operasional']);
     $isKeuangan = $hasRole(['finance']);
     $isAuditor = $hasRole(['auditor']);

     /*
    |--------------------------------------------------------------------------
    | INFORMASI DASHBOARD
    |--------------------------------------------------------------------------
    */
     $dashboardNames = [
         'super_admin' => 'Dashboard Super Admin',
         'executive' => 'Dashboard Direktur Utama',
         'hr' => 'Dashboard HRD',
         'manager_departemen' => 'Dashboard Manager Departemen',
         'karyawan' => 'Dashboard Karyawan',
         'admin_pelayanan' => 'Dashboard Admin Pelayanan',
         'admin_operasional' => 'Dashboard Admin Operasional',
         'finance' => 'Dashboard Keuangan',
         'auditor' => 'Dashboard Auditor',
     ];

     $dashboardRouteCandidates = [
         'super_admin' => ['dashboard.super-admin', 'super-admin.dashboard', 'dashboard'],
         'executive' => ['dashboard.direktur-utama', 'direktur-utama.dashboard', 'dashboard'],
         'hr' => ['dashboard.hrd', 'hrd.dashboard', 'dashboard'],
         'manager_departemen' => ['dashboard.manager-departemen', 'manager-departemen.dashboard', 'dashboard'],
         'karyawan' => ['dashboard.karyawan', 'karyawan.dashboard', 'dashboard'],
         'admin_pelayanan' => ['dashboard.admin-pelayanan', 'admin-pelayanan.dashboard', 'dashboard'],
         'admin_operasional' => ['dashboard.admin-operasional', 'admin-operasional.dashboard', 'dashboard'],
         'finance' => ['dashboard.keuangan', 'keuangan.dashboard', 'dashboard'],
         'auditor' => ['dashboard.auditor', 'auditor.dashboard', 'dashboard'],
     ];

     $dashboardName = $dashboardNames[$activeRole] ?? 'Dashboard';
     $dashboardUrl = $routeUrl($dashboardRouteCandidates[$activeRole] ?? ['dashboard']);

     $roleDisplayNames = [
         'super_admin' => 'Super Admin',
         'executive' => 'Direktur Utama',
         'hr' => 'HRD',
         'manager_departemen' => 'Manager Departemen',
         'karyawan' => 'Karyawan',
         'admin_pelayanan' => 'Admin Pelayanan',
         'admin_operasional' => 'Admin Operasional',
         'finance' => 'Keuangan',
         'auditor' => 'Auditor',
     ];

     $activeRoleLabel =
         $roleDisplayNames[$activeRole] ??
         \Illuminate\Support\Str::of($activeRole)->replace('_', ' ')->title()->toString();

     /*
    |--------------------------------------------------------------------------
    | HAK AKSES MENU
    | CATATAN: ini hanya mengatur tampilan sidebar. Route tetap wajib memakai
    | middleware role/permission agar tidak dapat diakses langsung melalui URL.
    |--------------------------------------------------------------------------
    */
     $canAccessMasterData = $hasRole([
         'super_admin',
         'executive',
         'hr',
         'manager_departemen',
         'admin_pelayanan',
         'admin_operasional',
         'finance',
         'auditor',
     ]);

     $canAccessTransactions = $hasRole([
         'super_admin',
         'executive',
         'admin_pelayanan',
         'admin_operasional',
         'finance',
         'auditor',
     ]);

     $canAccessPerformance = $hasRole([
         'super_admin',
         'executive',
         'hr',
         'manager_departemen',
         'karyawan',
         'admin_operasional',
         'auditor',
     ]);

     $canAccessSatisfaction = $hasRole(['super_admin', 'executive', 'admin_pelayanan', 'auditor']);

     $canAccessComplaints = $hasRole(['super_admin', 'executive', 'admin_pelayanan', 'auditor']);

     $canAccessReports = $hasRole([
         'super_admin',
         'executive',
         'hr',
         'manager_departemen',
         'admin_pelayanan',
         'admin_operasional',
         'finance',
         'auditor',
     ]);

     // HRD harus dimasukkan karena memiliki menu Pengguna.
     $canAccessSystem = $hasRole(['super_admin', 'hr', 'auditor']);

     /*
    |--------------------------------------------------------------------------
    | STATUS SUBMENU
    |--------------------------------------------------------------------------
    */
     $masterDataOpen = $routeActive(
         'branches.*',
         'departments.*',
         'positions.*',
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
     $systemOpen = $routeActive('admin.users.*', 'roles.*', 'permissions.*', 'settings.*', 'activity-logs.*');

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
@endphp

<div class="sidebar">
     {{-- ================================================================ --}}
     {{-- SIDEBAR HEADER --}}
     {{-- ================================================================ --}}
     <div class="sidebar-header">
          <div class="sidebar-brand">
               <a href="{{ $dashboardUrl }}" class="sidebar-logo" aria-label="Buka dashboard">
                    <img src="{{ asset('backend/assets/img/logo.png') }}" alt="Logo Dashboard Monitoring"
                         class="sidebar-logo-image">
               </a>

               <small class="sidebar-logo-headline">
                    Kinerja Karyawan &amp; Kepuasan Pelanggan
               </small>

               @if ($activeRole !== '')
                    <small class="sidebar-role-name">
                         {{ $activeRoleLabel }}
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
                    <a href="{{ $dashboardUrl }}"
                         class="nav-link {{ $routeActive('dashboard', 'dashboard.*', '*.dashboard') ? 'active' : '' }}">
                         <i data-feather="home"></i>
                         <span>{{ $dashboardName }}</span>
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
                              @if ($isSuperAdmin || $isDirektur || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('branches.*') ? 'active' : '' }}">
                                        Cabang
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('departments.*') ? 'active' : '' }}">
                                        Departemen
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('positions.*') ? 'active' : '' }}">
                                        Jabatan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('employees.*') ? 'active' : '' }}">
                                        Data Karyawan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('customers.*') ? 'active' : '' }}">
                                        Data Pelanggan
                                   </a>
                              @endif

                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ $routeActive('service-categories.*') ? 'active' : '' }}">
                                        Kategori Layanan
                                   </a>

                                   <a href="#"
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
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('admin.roles.*', 'admin.permissions.*') ? 'active' : '' }}">
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
                    <a href="#" class="nav-link {{ $routeActive('profile.*') ? 'active' : '' }}">
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
