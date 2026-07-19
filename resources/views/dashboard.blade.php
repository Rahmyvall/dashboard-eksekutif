<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


     <!-- Meta -->
     <meta name="description" content="Responsive Bootstrap 4 Dashboard and Admin Template">
     <meta name="author" content="ThemePixels">


     <!-- Favicon -->
     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/assets/img/logo.png') }}">


     <title>Dashboard Monitoring Kinerja & Kepuasan Pelanggan</title>

     <!-- vendor css -->
     <link href="{{ asset('backend/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

     <link href="{{ asset('backend/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

     <link href="{{ asset('backend/lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/template.css') }}">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">

     <!-- template css -->
     <link rel="stylesheet" href="{{ asset('backend/assets/css/cassie.css') }}">
     <style>
          /* =====================
   BLACK THEME
===================== */

          body.black-theme {

               background: #121212;
               color: #ffffff;

          }


          /* Header */

          body.black-theme .header {

               background: #1e1e1e;

          }


          /* Sidebar */

          body.black-theme .sidebar {

               background: #181818;

          }


          body.black-theme .sidebar-logo span {

               color: white;

          }



          /* Content */

          body.black-theme .content,
          body.black-theme .content-header,
          body.black-theme .content-body {

               background: #121212;
               color: white;

          }


          /* Title */

          body.black-theme .content-title {

               color: white;

          }


          /* Breadcrumb */

          body.black-theme .breadcrumb-item {

               color: #ddd;

          }



          /* Dropdown */

          body.black-theme .dropdown-menu {

               background: #242424;

          }


          body.black-theme .dropdown-item {

               color: white;

          }


          body.black-theme .dropdown-item:hover {

               background: #333;

          }



          /* Input */

          body.black-theme .form-control {

               background: #2b2b2b;
               color: white;
               border-color: #444;

          }



          /* =====================
   WHITE THEME
===================== */


          body.white-theme {

               background: #ffffff;

          }

          /* Container logo */
          .sidebar-brand {
               display: flex;
               flex-direction: column;
               align-items: center;
               justify-content: center;

               width: 100%;
               text-align: center;
          }

          /* Link logo */
          .sidebar-logo {
               display: flex;
               flex-direction: column;
               align-items: center;
               justify-content: center;

               width: 100%;
               padding: 15px 0;
               margin: 0 auto;

               text-align: center;
               text-decoration: none;
               box-sizing: border-box;
          }

          /* Gambar logo */
          .sidebar-logo-image {
               display: block;

               width: auto;
               max-width: 130px;
               height: 80px;

               margin: 0 auto;
               padding: 0;

               object-fit: contain;
               object-position: center;
          }

          /* Teks logo */
          .sidebar-logo-text,
          .sidebar-logo-headline {
               display: block;

               width: 100%;
               margin: 6px auto 0;
               padding: 0 10px;

               color: #0e0d0d;
               font-size: 13px;
               font-weight: 600;
               line-height: 1.3;
               text-align: center;

               box-sizing: border-box;
          }

          /* =========================================
   SIDEBAR TEMA BIRU
========================================= */

          .sidebar {
               background: linear-gradient(180deg, #78a9e0 0%, #78a9e0 55%, #78a9e0 100%) !important;
               color: #ffffff;
          }

          /* Bagian logo */
          .sidebar-header {
               background-color: #78a9e0 !important;
               border-bottom: 1px solid rgba(255, 255, 255, 0.15);
          }

          .sidebar-brand {
               background-color: transparent !important;
          }

          /* Judul di bawah logo */
          .sidebar-logo-headline,
          .sidebar-logo-headline b {
               color: #ffffff !important;
          }

          /* Area menu */
          .sidebar-body {
               background-color: transparent !important;
          }

          /* Judul kelompok menu */
          .sidebar .nav-label,
          .sidebar .content-label {
               color: #bbdefb !important;
          }

          /* Menu utama */
          .sidebar .nav-sidebar .nav-link {
               color: #e3f2fd !important;
               border-radius: 8px;
               margin: 3px 10px;
               padding: 11px 14px;
               transition: all 0.25s ease;
          }

          /* Icon menu */
          .sidebar .nav-sidebar .nav-link i,
          .sidebar .nav-sidebar .nav-link svg {
               color: #e3f2fd !important;
               stroke: #e3f2fd !important;
          }

          /* Hover menu */
          .sidebar .nav-sidebar .nav-link:hover {
               background-color: rgba(255, 255, 255, 0.15) !important;
               color: #ffffff !important;
               transform: translateX(3px);
          }

          /* Menu aktif */
          .sidebar .nav-sidebar .nav-link.active {
               background-color: #ffffff !important;
               color: #0d47a1 !important;
               font-weight: 600;
               box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
          }

          .sidebar .nav-sidebar .nav-link.active i,
          .sidebar .nav-sidebar .nav-link.active svg {
               color: #81a7e0 !important;
               stroke: #81a7e0 !important;
          }

          /* Submenu */
          .sidebar .nav-sub {
               background-color: rgba(0, 0, 0, 0.12);
               margin: 4px 10px 8px;
               padding: 6px 0;
               border-radius: 8px;
          }

          /* Link submenu */
          .sidebar .nav-sub-link {
               color: #d9ecff !important;
               padding: 9px 15px 9px 45px;
               transition: all 0.2s ease;
          }

          /* Hover submenu */
          .sidebar .nav-sub-link:hover {
               color: #ffffff !important;
               background-color: rgba(255, 255, 255, 0.12);
          }

          /* Submenu aktif */
          .sidebar .nav-sub-link.active {
               color: #ffffff !important;
               background-color: rgba(255, 255, 255, 0.2);
               font-weight: 600;
               border-left: 3px solid #ffffff;
          }

          /* Scrollbar sidebar */
          .sidebar-body::-webkit-scrollbar {
               width: 5px;
          }

          .sidebar-body::-webkit-scrollbar-thumb {
               background-color: rgba(255, 255, 255, 0.35);
               border-radius: 10px;
          }

          .sidebar-body::-webkit-scrollbar-track {
               background-color: transparent;
          }

          body.white-theme .header {

               background: #ffffff;

          }
     </style>
</head>

<body>

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

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                                   Cabang
                              </a>

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                                   Departemen
                              </a>

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('positions.*') ? 'active' : '' }}"
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

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
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
                    <li
                         class="nav-item {{ request()->routeIs('complaint-categories.*', 'complaints.*') ? 'show' : '' }}">

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

                         <a href="#"
                              class="nav-link with-sub {{ request()->routeIs('reports.*') ? 'active' : '' }}">

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

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                   Pengguna
                              </a>

                              <a href="#"
                                   class="nav-sub-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
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

     <div class="content">
          <div class="header">
               <div class="header-left">
                    <a href="" class="burger-menu"><i data-feather="menu"></i></a>

                    <div class="header-search">
                         <i data-feather="search"></i>
                         <input type="search" class="form-control" placeholder="What are you looking for?">
                    </div><!-- header-search -->

               </div><!-- header-left -->

               <div class="header-right">

                    <a href="" class="header-help-link"><i data-feather="help-circle"></i></a>
                    <div class="dropdown dropdown-notification">
                         <!-- Theme Toggle -->
                         <a href="#" class="header-help-link" id="themeToggle" title="Change Theme">

                              <i data-feather="moon" id="themeIcon"></i>

                         </a>
                         <a href="" class="dropdown-link new" data-toggle="dropdown"><i
                                   data-feather="bell"></i></a>
                         <div class="dropdown-menu dropdown-menu-right">
                              <div class="dropdown-menu-header">
                                   <h6>Notifications</h6>
                                   <a href=""><i data-feather="more-vertical"></i></a>
                              </div><!-- dropdown-menu-header -->
                              <div class="dropdown-menu-body">
                                   <a href="" class="dropdown-item">
                                        <div class="avatar"><span
                                                  class="avatar-initial rounded-circle text-primary bg-primary-light">s</span>
                                        </div>
                                        <div class="dropdown-item-body">
                                             <p><strong>Socrates Itumay</strong> marked the task as completed.</p>
                                             <span>5 hours ago</span>
                                        </div>
                                   </a>
                                   <a href="" class="dropdown-item">
                                        <div class="avatar"><span
                                                  class="avatar-initial rounded-circle tx-pink bg-pink-light">r</span>
                                        </div>
                                        <div class="dropdown-item-body">
                                             <p><strong>Reynante Labares</strong> marked the task as incomplete.</p>
                                             <span>8 hours ago</span>
                                        </div>
                                   </a>
                                   <a href="" class="dropdown-item">
                                        <div class="avatar"><span
                                                  class="avatar-initial rounded-circle tx-success bg-success-light">d</span>
                                        </div>
                                        <div class="dropdown-item-body">
                                             <p><strong>Dyanne Aceron</strong> responded to your comment on this
                                                  <strong>post</strong>.
                                             </p>
                                             <span>a day ago</span>
                                        </div>
                                   </a>
                                   <a href="" class="dropdown-item">
                                        <div class="avatar"><span
                                                  class="avatar-initial rounded-circle tx-indigo bg-indigo-light">k</span>
                                        </div>
                                        <div class="dropdown-item-body">
                                             <p><strong>Kirby Avendula</strong> marked the task as incomplete.</p>
                                             <span>2 days ago</span>
                                        </div>
                                   </a>
                              </div><!-- dropdown-menu-body -->
                              <div class="dropdown-menu-footer">
                                   <a href="">View All Notifications</a>
                              </div>
                         </div><!-- dropdown-menu -->

                    </div>
                    <div class="dropdown dropdown-loggeduser">
                         <a href="#" class="dropdown-link" data-toggle="dropdown">

                              <div class="avatar avatar-sm">

                                   <img src="{{ asset('backend/assets/img/favicon.png') }}" class="rounded-circle"
                                        alt="User Avatar">

                              </div>

                         </a>
                         <div class="dropdown-menu dropdown-menu-right">
                              <div class="dropdown-menu-header">

                                   <div class="media align-items-center">

                                        <div class="avatar">

                                             <img src="{{ asset('backend/assets/img/favicon.png') }}"
                                                  class="rounded-circle" alt="Avatar">

                                        </div>


                                        <div class="media-body mg-l-10">

                                             <h6>
                                                  Administrator
                                             </h6>

                                             <span>
                                                  Admin Dashboard
                                             </span>

                                        </div>

                                   </div>

                              </div>
                              <div class="dropdown-menu-body">
                                   <a href="" class="dropdown-item"><i data-feather="user"></i> View
                                        Profile</a>
                                   <a href="" class="dropdown-item"><i data-feather="edit-2"></i> Edit
                                        Profile</a>
                                   <a href="" class="dropdown-item"><i data-feather="briefcase"></i> Account
                                        Settings</a>
                                   <a href="" class="dropdown-item"><i data-feather="shield"></i> Privacy
                                        Settings</a>
                                   <a href="" class="dropdown-item"><i data-feather="log-out"></i> Sign
                                        Out</a>
                              </div>
                         </div><!-- dropdown-menu -->

                    </div>
               </div><!-- header-right -->

          </div><!-- header -->

          <div class="content-header">
               <div>
                    <nav aria-label="breadcrumb">
                         <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="#">Home</a></li>
                              <li class="breadcrumb-item"><a href="#">Pages</a></li>
                              <li class="breadcrumb-item active" aria-current="page">Blank Page</li>
                         </ol>
                    </nav>
                    <h4 class="content-title content-title-xs">Blank Page</h4>
               </div>
          </div><!-- content-header -->
          <div class="content-body">

          </div><!-- content-body -->
     </div><!-- content -->

     <!-- Vendor JS -->
     <script src="{{ asset('backend/lib/jquery/jquery.min.js') }}"></script>

     <script src="{{ asset('backend/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

     <script src="{{ asset('backend/lib/feather-icons/feather.min.js') }}"></script>

     <script src="{{ asset('backend/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

     <script src="{{ asset('backend/lib/js-cookie/js.cookie.js') }}"></script>


     <!-- Chart -->
     <script src="{{ asset('backend/lib/chart.js/Chart.bundle.min.js') }}"></script>


     <!-- Flot Chart -->
     <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.js') }}"></script>

     <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.stack.js') }}"></script>

     <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.resize.js') }}"></script>

     <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.threshold.js') }}"></script>


     <!-- Map -->
     <script src="{{ asset('backend/lib/jqvmap/jquery.vmap.min.js') }}"></script>

     <script src="{{ asset('backend/lib/jqvmap/maps/jquery.vmap.world.js') }}"></script>


     <!-- Cassie Template -->
     <script src="{{ asset('backend/assets/js/cassie.js') }}"></script>


     <!-- Dashboard -->
     <script src="{{ asset('backend/assets/js/flot.sampledata.js') }}"></script>

     <script src="{{ asset('backend/assets/js/vmap.sampledata.js') }}"></script>
     <!--
<script src="{{ asset('backend/assets/js/dashboard-one.js') }}"></script>
-->
     <script>
          document.addEventListener("DOMContentLoaded", function() {


               const themeButton = document.getElementById("themeToggle");
               const themeIcon = document.getElementById("themeIcon");


               if (localStorage.getItem("theme") === "black") {

                    document.body.classList.add("black-theme");

                    themeIcon.setAttribute(
                         "data-feather",
                         "sun"
                    );

               }



               themeButton.addEventListener("click", function(e) {

                    e.preventDefault();


                    document.body.classList.toggle("black-theme");


                    if (document.body.classList.contains("black-theme")) {


                         localStorage.setItem(
                              "theme",
                              "black"
                         );


                         themeIcon.setAttribute(
                              "data-feather",
                              "sun"
                         );


                    } else {


                         localStorage.setItem(
                              "theme",
                              "white"
                         );


                         themeIcon.setAttribute(
                              "data-feather",
                              "moon"
                         );


                    }


                    feather.replace();


               });


          });
     </script>
</body>

</html>
