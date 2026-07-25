@php
     /*
    |--------------------------------------------------------------------------
    | Data pengguna dan role
    |--------------------------------------------------------------------------
    */

     $user = auth()->user();

     $roleNames = $user ? $user->roles->pluck('name') : collect();

     /*
    |--------------------------------------------------------------------------
    | Pemeriksaan role
    |--------------------------------------------------------------------------
    */

     $isSuperAdmin = $roleNames->contains('SUPER_ADMIN');
     $isDirektur = $roleNames->contains('DIREKTUR_UTAMA');
     $isHrd = $roleNames->contains('HRD');
     $isManager = $roleNames->contains('MANAGER_DEPARTEMEN');
     $isKaryawan = $roleNames->contains('KARYAWAN');
     $isPelayanan = $roleNames->contains('ADMIN_PELAYANAN');
     $isOperasional = $roleNames->contains('ADMIN_OPERASIONAL');
     $isKeuangan = $roleNames->contains('KEUANGAN');
     $isAuditor = $roleNames->contains('AUDITOR');

     /*
    |--------------------------------------------------------------------------
    | Nama dashboard
    |--------------------------------------------------------------------------
    */

     $dashboardName = match (true) {
         $isSuperAdmin => 'Dashboard Super Admin',
         $isDirektur => 'Dashboard Direktur Utama',
         $isHrd => 'Dashboard HRD',
         $isManager => 'Dashboard Manager Departemen',
         $isKaryawan => 'Dashboard Karyawan',
         $isPelayanan => 'Dashboard Admin Pelayanan',
         $isOperasional => 'Dashboard Admin Operasional',
         $isKeuangan => 'Dashboard Keuangan',
         $isAuditor => 'Dashboard Auditor',
         default => 'Dashboard',
     };

     /*
    |--------------------------------------------------------------------------
    | Akses kelompok menu
    |--------------------------------------------------------------------------
    */

     $canAccessMasterData =
         $isSuperAdmin ||
         $isDirektur ||
         $isHrd ||
         $isManager ||
         $isPelayanan ||
         $isOperasional ||
         $isKeuangan ||
         $isAuditor;

     $canAccessTransactions =
         $isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor;

     $canAccessPerformance =
         $isSuperAdmin || $isDirektur || $isHrd || $isManager || $isKaryawan || $isOperasional || $isAuditor;

     $canAccessSatisfaction = $isSuperAdmin || $isDirektur || $isPelayanan || $isAuditor;

     $canAccessComplaints = $isSuperAdmin || $isDirektur || $isPelayanan || $isAuditor;

     $canAccessReports =
         $isSuperAdmin ||
         $isDirektur ||
         $isHrd ||
         $isManager ||
         $isPelayanan ||
         $isOperasional ||
         $isKeuangan ||
         $isAuditor;

     $canAccessSystem = $isSuperAdmin || $isHrd || $isAuditor;
@endphp

<div class="sidebar">

     {{-- SIDEBAR HEADER --}}
     <div class="sidebar-header">

          <div class="sidebar-brand">

               <a href="#" class="sidebar-logo">

                    <img src="{{ asset('backend/assets/img/logo.png') }}" alt="Logo Dashboard Monitoring"
                         class="sidebar-logo-image">

               </a>

               <small class="sidebar-logo-headline">
                    Kinerja Karyawan &amp; Kepuasan Pelanggan
               </small>

               @if ($roleNames->isNotEmpty())
                    <small class="sidebar-role-name">

                         {{ $roleNames->map(function ($role) {
                                 return ucwords(strtolower(str_replace('_', ' ', $role)));
                             })->implode(', ') }}

                    </small>
               @endif

          </div>

     </div>

     {{-- SIDEBAR BODY --}}
     <div id="dpSidebarBody" class="sidebar-body">

          <ul class="nav nav-sidebar">

               {{-- ====================================================== --}}
               {{-- DASHBOARD --}}
               {{-- ====================================================== --}}

               <li class="nav-label">
                    <label class="content-label">
                         Dashboard Utama
                    </label>
               </li>

               <li class="nav-item">

                    <a href="#" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                         <i data-feather="home"></i>

                         <span>
                              {{ $dashboardName }}
                         </span>

                    </a>

               </li>

               {{-- ====================================================== --}}
               {{-- MASTER DATA --}}
               {{-- ====================================================== --}}

               @if ($canAccessMasterData)

                    <li class="nav-label">
                         <label class="content-label">
                              Master Data
                         </label>
                    </li>

                    <li
                         class="nav-item {{ request()->routeIs(
                             'branches.*',
                             'departments.*',
                             'positions.*',
                             'employees.*',
                             'customers.*',
                             'service-categories.*',
                             'services.*',
                         )
                             ? 'show'
                             : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs(
                                  'branches.*',
                                  'departments.*',
                                  'positions.*',
                                  'employees.*',
                                  'customers.*',
                                  'service-categories.*',
                                  'services.*',
                              )
                                  ? 'active'
                                  : '' }}">

                              <i data-feather="database"></i>

                              <span>
                                   Master Data
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              {{-- Cabang --}}
                              @if ($isSuperAdmin || $isDirektur || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                                        Cabang
                                   </a>
                              @endif

                              {{-- Departemen --}}
                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                                        Departemen
                                   </a>
                              @endif

                              {{-- Jabatan --}}
                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                                        Jabatan
                                   </a>
                              @endif

                              {{-- Data Karyawan --}}
                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                        Data Karyawan
                                   </a>
                              @endif

                              {{-- Data Pelanggan --}}
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                        Data Pelanggan
                                   </a>
                              @endif

                              {{-- Kategori Layanan --}}
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('service-categories.*') ? 'active' : '' }}">
                                        Kategori Layanan
                                   </a>
                              @endif

                              {{-- Data Layanan --}}
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                                        Data Layanan
                                   </a>
                              @endif

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- TRANSAKSI JASA --}}
               {{-- ====================================================== --}}

               @if ($canAccessTransactions)

                    <li class="nav-label">
                         <label class="content-label">
                              Operasional Jasa
                         </label>
                    </li>

                    <li
                         class="nav-item {{ request()->routeIs('transactions.*', 'transaction-assignments.*') ? 'show' : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs('transactions.*', 'transaction-assignments.*') ? 'active' : '' }}">

                              <i data-feather="briefcase"></i>

                              <span>
                                   Transaksi Jasa
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              {{-- Semua role transaksi dapat melihat daftar --}}
                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                                   Daftar Transaksi
                              </a>

                              {{-- Tambah transaksi --}}
                              @if ($isSuperAdmin || $isPelayanan || $isOperasional)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('transactions.create') ? 'active' : '' }}">
                                        Tambah Transaksi
                                   </a>
                              @endif

                              {{-- Penugasan karyawan --}}
                              @if ($isSuperAdmin || $isOperasional)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('transaction-assignments.*') ? 'active' : '' }}">
                                        Penugasan Karyawan
                                   </a>
                              @endif

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- KINERJA KARYAWAN --}}
               {{-- ====================================================== --}}

               @if ($canAccessPerformance)

                    <li
                         class="nav-item {{ request()->routeIs(
                             'kpi-categories.*',
                             'kpi-indicators.*',
                             'employee-targets.*',
                             'employee-kpi-results.*',
                             'performance-evaluations.*',
                         )
                             ? 'show'
                             : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs(
                                  'kpi-categories.*',
                                  'kpi-indicators.*',
                                  'employee-targets.*',
                                  'employee-kpi-results.*',
                                  'performance-evaluations.*',
                              )
                                  ? 'active'
                                  : '' }}">

                              <i data-feather="bar-chart-2"></i>

                              <span>
                                   Kinerja Karyawan
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              {{-- Pengaturan kategori KPI --}}
                              @if ($isSuperAdmin || $isHrd)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('kpi-categories.*') ? 'active' : '' }}">
                                        Kategori KPI
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('kpi-indicators.*') ? 'active' : '' }}">
                                        Indikator KPI
                                   </a>
                              @endif

                              {{-- Target karyawan --}}
                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('employee-targets.*') ? 'active' : '' }}">
                                        Target Karyawan
                                   </a>
                              @endif

                              {{-- Hasil KPI --}}
                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('employee-kpi-results.*') ? 'active' : '' }}">

                                   @if ($isKaryawan)
                                        Hasil KPI Saya
                                   @else
                                        Hasil KPI
                                   @endif

                              </a>

                              {{-- Evaluasi --}}
                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('performance-evaluations.*') ? 'active' : '' }}">

                                   @if ($isKaryawan)
                                        Evaluasi Saya
                                   @else
                                        Evaluasi Kinerja
                                   @endif

                              </a>

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- KEPUASAN PELANGGAN --}}
               {{-- ====================================================== --}}

               @if ($canAccessSatisfaction)

                    <li
                         class="nav-item {{ request()->routeIs('surveys.*', 'survey-questions.*', 'survey-responses.*') ? 'show' : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs('surveys.*', 'survey-questions.*', 'survey-responses.*') ? 'active' : '' }}">

                              <i data-feather="smile"></i>

                              <span>
                                   Kepuasan Pelanggan
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              {{-- Kelola survei --}}
                              @if ($isSuperAdmin || $isPelayanan)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
                                        Data Survei
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('survey-questions.*') ? 'active' : '' }}">
                                        Pertanyaan Survei
                                   </a>
                              @endif

                              {{-- Hasil survei --}}
                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('survey-responses.*') ? 'active' : '' }}">
                                   Hasil Survei
                              </a>

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- KELUHAN PELANGGAN --}}
               {{-- ====================================================== --}}

               @if ($canAccessComplaints)

                    <li
                         class="nav-item {{ request()->routeIs('complaint-categories.*', 'complaints.*') ? 'show' : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs('complaint-categories.*', 'complaints.*') ? 'active' : '' }}">

                              <i data-feather="message-square"></i>

                              <span>
                                   Keluhan Pelanggan
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('complaints.index') ? 'active' : '' }}">
                                   Daftar Keluhan
                              </a>

                              @if ($isSuperAdmin || $isPelayanan)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('complaints.create') ? 'active' : '' }}">
                                        Tambah Keluhan
                                   </a>

                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('complaint-categories.*') ? 'active' : '' }}">
                                        Kategori Keluhan
                                   </a>
                              @endif

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- LAPORAN --}}
               {{-- ====================================================== --}}

               @if ($canAccessReports)

                    <li class="nav-label">
                         <label class="content-label">
                              Laporan dan Sistem
                         </label>
                    </li>

                    <li class="nav-item {{ request()->routeIs('reports.*') ? 'show' : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs('reports.*') ? 'active' : '' }}">

                              <i data-feather="file-text"></i>

                              <span>
                                   Laporan
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              {{-- Laporan transaksi --}}
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isOperasional || $isKeuangan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                                        Laporan Transaksi
                                   </a>
                              @endif

                              {{-- Laporan kinerja --}}
                              @if ($isSuperAdmin || $isDirektur || $isHrd || $isManager || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('reports.performance') ? 'active' : '' }}">
                                        Laporan Kinerja
                                   </a>
                              @endif

                              {{-- Laporan kepuasan --}}
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('reports.satisfaction') ? 'active' : '' }}">
                                        Laporan Kepuasan
                                   </a>
                              @endif

                              {{-- Laporan keluhan --}}
                              @if ($isSuperAdmin || $isDirektur || $isPelayanan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('reports.complaints') ? 'active' : '' }}">
                                        Laporan Keluhan
                                   </a>
                              @endif

                              {{-- Laporan keuangan --}}
                              @if ($isSuperAdmin || $isDirektur || $isKeuangan || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('reports.finance') ? 'active' : '' }}">
                                        Laporan Keuangan
                                   </a>
                              @endif

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- PENGATURAN SISTEM --}}
               {{-- ====================================================== --}}

               @if ($canAccessSystem)

                    <li
                         class="nav-item {{ request()->routeIs('admin.users.*', 'roles.*', 'permissions.*', 'settings.*', 'activity-logs.*')
                             ? 'show'
                             : '' }}">

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs('admin.users.*', 'roles.*', 'permissions.*', 'settings.*', 'activity-logs.*')
                                  ? 'active'
                                  : '' }}">

                              <i data-feather="settings"></i>

                              <span>
                                   Pengaturan Sistem
                              </span>

                         </a>

                         <nav class="nav nav-sub">

                              {{-- Pengguna --}}
                              @if ($isSuperAdmin || $isHrd)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        Pengguna
                                   </a>
                              @endif

                              {{-- Role dan hak akses --}}
                              @if ($isSuperAdmin)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('roles.*', 'permissions.*') ? 'active' : '' }}">
                                        Role dan Hak Akses
                                   </a>
                              @endif

                              {{-- Log aktivitas --}}
                              @if ($isSuperAdmin || $isAuditor)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                                        Log Aktivitas
                                   </a>
                              @endif

                              {{-- Pengaturan aplikasi --}}
                              @if ($isSuperAdmin)
                                   <a href="#"
                                        class="nav-sub-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                        Pengaturan Aplikasi
                                   </a>
                              @endif

                         </nav>

                    </li>

               @endif

               {{-- ====================================================== --}}
               {{-- PROFIL --}}
               {{-- ====================================================== --}}

               <li class="nav-label">
                    <label class="content-label">
                         Akun
                    </label>
               </li>

               <li class="nav-item">

                    <a href="#" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                         <i data-feather="user"></i>

                         <span>
                              Profil Saya
                         </span>

                    </a>

               </li>

          </ul>

     </div>

</div>
