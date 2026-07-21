 <div class="sidebar">
      <div class="sidebar-header">
           <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="sidebar-logo">
                     <img src="{{ asset('backend/assets/img/logo.png') }}" alt="Logo Dashboard Monitoring"
                          class="sidebar-logo-image">
                </a>

                <small class="sidebar-logo-headline">
                     Kinerja Karyawan &amp; Kepuasan Pelanggan
                </small>
           </div>
      </div><!-- sidebar-header -->
      <div id="dpSidebarBody" class="sidebar-body">

           <ul class="nav nav-sidebar">

                {{-- DASHBOARD --}}
                <li class="nav-label">
                     <label class="content-label">Dashboard Utama</label>
                </li>

                <li class="nav-item">
                     <a href="{{ route('dashboard') }}"
                          class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                          <i data-feather="home"></i>
                          <span>Dashboard Eksekutif</span>
                     </a>
                </li>

                {{-- MASTER DATA --}}
                <li class="nav-label">
                     <label class="content-label">Master Data</label>
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
                          <span>Master Data</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#" class="nav-sub-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                               Cabang
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                               Departemen
                          </a>

                          <a href="#" class="nav-sub-link {{ request()->routeIs('positions.*') ? 'active' : '' }}"
                               class="nav-sub-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                               Jabatan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                               Data Karyawan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                               Data Pelanggan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('service-categories.*') ? 'active' : '' }}">
                               Kategori Layanan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                               Data Layanan
                          </a>

                     </nav>
                </li>

                {{-- TRANSAKSI JASA --}}
                <li class="nav-label">
                     <label class="content-label">Operasional Jasa</label>
                </li>

                <li
                     class="nav-item {{ request()->routeIs('transactions.*', 'transaction-assignments.*') ? 'show' : '' }}">

                     <a href="#"
                          class="nav-link with-sub {{ request()->routeIs('transactions.*', 'transaction-assignments.*') ? 'active' : '' }}">

                          <i data-feather="briefcase"></i>
                          <span>Transaksi Jasa</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                               Daftar Transaksi
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('transactions.create') ? 'active' : '' }}">
                               Tambah Transaksi
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('transaction-assignments.*') ? 'active' : '' }}">
                               Penugasan Karyawan
                          </a>

                     </nav>
                </li>

                {{-- KINERJA KARYAWAN --}}
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
                          <span>Kinerja Karyawan</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('kpi-categories.*') ? 'active' : '' }}">
                               Kategori KPI
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('kpi-indicators.*') ? 'active' : '' }}">
                               Indikator KPI
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('employee-targets.*') ? 'active' : '' }}">
                               Target Karyawan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('employee-kpi-results.*') ? 'active' : '' }}">
                               Hasil KPI
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('performance-evaluations.*') ? 'active' : '' }}">
                               Evaluasi Kinerja
                          </a>

                     </nav>
                </li>

                {{-- KEPUASAN PELANGGAN --}}
                <li
                     class="nav-item {{ request()->routeIs('surveys.*', 'survey-questions.*', 'survey-responses.*') ? 'show' : '' }}">

                     <a href="#"
                          class="nav-link with-sub {{ request()->routeIs('surveys.*', 'survey-questions.*', 'survey-responses.*') ? 'active' : '' }}">

                          <i data-feather="smile"></i>
                          <span>Kepuasan Pelanggan</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#" class="nav-sub-link {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
                               Data Survei
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('survey-questions.*') ? 'active' : '' }}">
                               Pertanyaan Survei
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('survey-responses.*') ? 'active' : '' }}">
                               Hasil Survei
                          </a>

                     </nav>
                </li>

                {{-- KELUHAN --}}
                <li class="nav-item {{ request()->routeIs('complaint-categories.*', 'complaints.*') ? 'show' : '' }}">

                     <a href="#"
                          class="nav-link with-sub {{ request()->routeIs('complaint-categories.*', 'complaints.*') ? 'active' : '' }}">

                          <i data-feather="message-square"></i>
                          <span>Keluhan Pelanggan</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('complaints.index') ? 'active' : '' }}">
                               Daftar Keluhan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('complaints.create') ? 'active' : '' }}">
                               Tambah Keluhan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('complaint-categories.*') ? 'active' : '' }}">
                               Kategori Keluhan
                          </a>

                     </nav>
                </li>

                {{-- LAPORAN --}}
                <li class="nav-label">
                     <label class="content-label">Laporan dan Sistem</label>
                </li>

                <li class="nav-item {{ request()->routeIs('reports.*') ? 'show' : '' }}">

                     <a href="#" class="nav-link with-sub {{ request()->routeIs('reports.*') ? 'active' : '' }}">

                          <i data-feather="file-text"></i>
                          <span>Laporan</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                               Laporan Transaksi
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('reports.performance') ? 'active' : '' }}">
                               Laporan Kinerja
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('reports.satisfaction') ? 'active' : '' }}">
                               Laporan Kepuasan
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('reports.complaints') ? 'active' : '' }}">
                               Laporan Keluhan
                          </a>

                     </nav>
                </li>

                {{-- PENGGUNA --}}
                <li
                     class="nav-item {{ request()->routeIs('users.*', 'roles.*', 'settings.*', 'activity-logs.*') ? 'show' : '' }}">

                     <a href="#"
                          class="nav-link with-sub {{ request()->routeIs('users.*', 'roles.*', 'settings.*', 'activity-logs.*') ? 'active' : '' }}">

                          <i data-feather="settings"></i>
                          <span>Pengaturan Sistem</span>
                     </a>

                     <nav class="nav nav-sub">

                          <a href="#" class="nav-sub-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                               Pengguna
                          </a>

                          <a href="#" class="nav-sub-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                               Role dan Hak Akses
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                               Log Aktivitas
                          </a>

                          <a href="#"
                               class="nav-sub-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                               Pengaturan Aplikasi
                          </a>

                     </nav>
                </li>

                {{-- PROFIL DAN LOGOUT --}}
                <li class="nav-item">
                     <a href="#" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                          <i data-feather="user"></i>
                          <span>Profil Saya</span>
                     </a>
                </li>
           </ul>

      </div>
 </div><!-- sidebar -->
