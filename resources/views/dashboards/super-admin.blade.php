@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
     @php
          $currentUser = $user ?? auth()->user();
          $roleLabel = $activeRoleLabel ?? 'Super Admin';

          /*
        |--------------------------------------------------------------------------
        | Data sementara
        |--------------------------------------------------------------------------
        |
        | Nilai di bawah dapat diganti dengan data dari DashboardController.
        | Template tetap berjalan walaupun controller belum mengirim statistik.
        |
        */

          $statistics = $statistics ?? [
              'total_users' => 128,
              'active_users' => 114,
              'total_roles' => 9,
              'total_permissions' => 48,
              'system_uptime' => 99.98,
              'open_alerts' => 3,
          ];

          $systemModules = $systemModules ?? [
              [
                  'name' => 'Autentikasi dan Akses',
                  'description' => 'Login, session, role, dan permission',
                  'status' => 'Normal',
                  'status_type' => 'success',
                  'usage' => 92,
                  'icon' => 'shield',
              ],
              [
                  'name' => 'Manajemen Pengguna',
                  'description' => 'Data pengguna, status, dan aktivitas login',
                  'status' => 'Normal',
                  'status_type' => 'success',
                  'usage' => 78,
                  'icon' => 'users',
              ],
              [
                  'name' => 'Operasional',
                  'description' => 'Layanan, transaksi, dan proses harian',
                  'status' => 'Perhatian',
                  'status_type' => 'warning',
                  'usage' => 68,
                  'icon' => 'activity',
              ],
              [
                  'name' => 'Pelaporan',
                  'description' => 'Rekap, analitik, dan ekspor laporan',
                  'status' => 'Normal',
                  'status_type' => 'success',
                  'usage' => 84,
                  'icon' => 'bar-chart-2',
              ],
          ];

          $recentActivities = $recentActivities ?? [
              [
                  'title' => 'Role pengguna diperbarui',
                  'description' => 'Akses akun Andi Pratama diubah menjadi HRD.',
                  'time' => '8 menit lalu',
                  'icon' => 'user-check',
                  'type' => 'primary',
              ],
              [
                  'title' => 'Pengguna baru ditambahkan',
                  'description' => 'Akun Siti Rahma berhasil dibuat dan diaktifkan.',
                  'time' => '24 menit lalu',
                  'icon' => 'user-plus',
                  'type' => 'success',
              ],
              [
                  'title' => 'Percobaan login gagal',
                  'description' => 'Terdeteksi 4 percobaan login gagal pada satu akun.',
                  'time' => '1 jam lalu',
                  'icon' => 'alert-triangle',
                  'type' => 'danger',
              ],
              [
                  'title' => 'Laporan sistem dibuat',
                  'description' => 'Laporan aktivitas pengguna bulan berjalan tersedia.',
                  'time' => '2 jam lalu',
                  'icon' => 'file-text',
                  'type' => 'purple',
              ],
          ];

          $roleDistribution = $roleDistribution ?? [
              ['label' => 'Karyawan', 'value' => 64, 'percent' => 50],
              ['label' => 'Manager Departemen', 'value' => 18, 'percent' => 14],
              ['label' => 'Admin Operasional', 'value' => 14, 'percent' => 11],
              ['label' => 'Admin Pelayanan', 'value' => 12, 'percent' => 9],
              ['label' => 'Role Lainnya', 'value' => 20, 'percent' => 16],
          ];

          $usersUrl = \Illuminate\Support\Facades\Route::has('admin.users.index') ? route('admin.users.index') : '#';

          $createUserUrl = \Illuminate\Support\Facades\Route::has('admin.users.create')
              ? route('admin.users.create')
              : '#';

          $trashUrl = \Illuminate\Support\Facades\Route::has('admin.users.trash') ? route('admin.users.trash') : '#';
     @endphp

     <style>
          /*
             |--------------------------------------------------------------------------
             | Super Admin Dashboard
             |--------------------------------------------------------------------------
             */

          .super-admin-page {
               --sa-bg: #f4f7fb;
               --sa-surface: #ffffff;
               --sa-surface-soft: #f8fafc;
               --sa-text: #0f172a;
               --sa-muted: #64748b;
               --sa-border: #e2e8f0;
               --sa-primary: #2563eb;
               --sa-primary-soft: #dbeafe;
               --sa-success: #059669;
               --sa-success-soft: #d1fae5;
               --sa-warning: #d97706;
               --sa-warning-soft: #fef3c7;
               --sa-danger: #dc2626;
               --sa-danger-soft: #fee2e2;
               --sa-purple: #7c3aed;
               --sa-purple-soft: #ede9fe;
               --sa-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
               color: var(--sa-text);
          }

          body.black-theme .super-admin-page {
               --sa-bg: #0f1117;
               --sa-surface: #171a22;
               --sa-surface-soft: #20242e;
               --sa-text: #f8fafc;
               --sa-muted: #a8b2c1;
               --sa-border: #2b313d;
               --sa-primary-soft: rgba(37, 99, 235, 0.2);
               --sa-success-soft: rgba(5, 150, 105, 0.18);
               --sa-warning-soft: rgba(217, 119, 6, 0.18);
               --sa-danger-soft: rgba(220, 38, 38, 0.18);
               --sa-purple-soft: rgba(124, 58, 237, 0.2);
               --sa-shadow: 0 18px 45px rgba(0, 0, 0, 0.26);
          }

          .super-admin-page,
          .super-admin-page * {
               box-sizing: border-box;
          }

          .super-admin-page .sa-shell {
               padding-bottom: 30px;
          }

          .super-admin-page .sa-card,
          .super-admin-page .sa-hero,
          .super-admin-page .sa-kpi-card,
          .super-admin-page .sa-action-card {
               transition:
                    transform 0.25s ease,
                    border-color 0.25s ease,
                    background-color 0.25s ease,
                    box-shadow 0.25s ease;
          }

          /*
             |--------------------------------------------------------------------------
             | Hero
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-hero {
               position: relative;
               isolation: isolate;
               overflow: hidden;
               margin-bottom: 22px;
               padding: 30px;
               border-radius: 26px;
               color: #ffffff;
               background:
                    radial-gradient(circle at 82% 20%, rgba(255, 255, 255, 0.20), transparent 28%),
                    radial-gradient(circle at 10% 100%, rgba(34, 211, 238, 0.22), transparent 33%),
                    linear-gradient(135deg, #0f2f62 0%, #2563eb 56%, #7c3aed 100%);
               box-shadow: 0 22px 52px rgba(37, 99, 235, 0.23);
          }

          .super-admin-page .sa-hero::before,
          .super-admin-page .sa-hero::after {
               position: absolute;
               z-index: -1;
               content: "";
               border-radius: 999px;
               border: 1px solid rgba(255, 255, 255, 0.16);
          }

          .super-admin-page .sa-hero::before {
               top: -115px;
               right: -70px;
               width: 310px;
               height: 310px;
          }

          .super-admin-page .sa-hero::after {
               right: 140px;
               bottom: -170px;
               width: 360px;
               height: 360px;
          }

          .super-admin-page .sa-hero-grid {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 24px;
          }

          .super-admin-page .sa-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 13px;
               padding: 7px 12px;
               border: 1px solid rgba(255, 255, 255, 0.22);
               border-radius: 999px;
               font-size: 11px;
               font-weight: 700;
               letter-spacing: 0.04em;
               text-transform: uppercase;
               background: rgba(255, 255, 255, 0.10);
          }

          .super-admin-page .sa-eyebrow-dot {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #67e8f9;
               box-shadow: 0 0 0 5px rgba(103, 232, 249, 0.15);
          }

          .super-admin-page .sa-hero h1 {
               margin: 0 0 9px;
               font-size: clamp(26px, 3vw, 40px);
               line-height: 1.12;
               color: #ffffff !important;
          }

          .super-admin-page .sa-hero p {
               max-width: 720px;
               margin: 0;
               font-size: 14px;
               line-height: 1.75;
               color: rgba(255, 255, 255, 0.78) !important;
          }

          .super-admin-page .sa-hero-actions {
               display: flex;
               flex-wrap: wrap;
               justify-content: flex-end;
               gap: 10px;
               min-width: 280px;
          }

          .super-admin-page .sa-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 42px;
               padding: 10px 16px;
               border: 1px solid transparent;
               border-radius: 12px;
               font-size: 12px;
               font-weight: 700;
               text-decoration: none !important;
               cursor: pointer;
          }

          .super-admin-page .sa-button-light {
               color: #0f172a !important;
               background: #ffffff;
               box-shadow: 0 10px 24px rgba(15, 23, 42, 0.13);
          }

          .super-admin-page .sa-button-glass {
               border-color: rgba(255, 255, 255, 0.25);
               color: #ffffff !important;
               background: rgba(255, 255, 255, 0.10);
          }

          .super-admin-page .sa-button:hover {
               transform: translateY(-1px);
          }

          /*
             |--------------------------------------------------------------------------
             | KPI
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-kpi-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 18px;
               margin-bottom: 22px;
          }

          .super-admin-page .sa-kpi-card {
               position: relative;
               overflow: hidden;
               min-height: 172px;
               padding: 21px;
               border: 1px solid var(--sa-border);
               border-radius: 20px;
               background: var(--sa-surface);
               box-shadow: var(--sa-shadow);
          }

          .super-admin-page .sa-kpi-card:hover {
               transform: translateY(-3px);
               border-color: rgba(37, 99, 235, 0.35);
          }

          .super-admin-page .sa-kpi-top {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 14px;
          }

          .super-admin-page .sa-kpi-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 48px;
               height: 48px;
               border-radius: 15px;
          }

          .super-admin-page .sa-kpi-icon svg {
               width: 21px;
               height: 21px;
          }

          .super-admin-page .sa-kpi-icon.primary {
               color: var(--sa-primary);
               background: var(--sa-primary-soft);
          }

          .super-admin-page .sa-kpi-icon.success {
               color: var(--sa-success);
               background: var(--sa-success-soft);
          }

          .super-admin-page .sa-kpi-icon.purple {
               color: var(--sa-purple);
               background: var(--sa-purple-soft);
          }

          .super-admin-page .sa-kpi-icon.warning {
               color: var(--sa-warning);
               background: var(--sa-warning-soft);
          }

          .super-admin-page .sa-kpi-label {
               margin: 17px 0 6px;
               font-size: 12px;
               font-weight: 700;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-kpi-value {
               margin: 0;
               font-size: 29px;
               font-weight: 800;
               letter-spacing: -0.03em;
               color: var(--sa-text);
          }

          .super-admin-page .sa-trend {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               margin-top: 9px;
               font-size: 11px;
               font-weight: 700;
          }

          .super-admin-page .sa-trend.positive {
               color: var(--sa-success);
          }

          .super-admin-page .sa-trend.warning {
               color: var(--sa-warning);
          }

          .super-admin-page .sa-trend.neutral {
               color: var(--sa-muted);
          }

          /*
             |--------------------------------------------------------------------------
             | Cards and grids
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-main-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.65fr) minmax(330px, 0.85fr);
               gap: 20px;
               margin-bottom: 20px;
          }

          .super-admin-page .sa-bottom-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
               gap: 20px;
          }

          .super-admin-page .sa-card {
               overflow: hidden;
               border: 1px solid var(--sa-border);
               border-radius: 20px;
               background: var(--sa-surface);
               box-shadow: var(--sa-shadow);
          }

          .super-admin-page .sa-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 16px;
               padding: 20px 22px;
               border-bottom: 1px solid var(--sa-border);
          }

          .super-admin-page .sa-card-title {
               margin: 0 0 5px;
               font-size: 15px;
               font-weight: 800;
               color: var(--sa-text);
          }

          .super-admin-page .sa-card-subtitle {
               margin: 0;
               font-size: 11px;
               line-height: 1.65;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-card-body {
               padding: 22px;
          }

          .super-admin-page .sa-card-link {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               white-space: nowrap;
               font-size: 11px;
               font-weight: 700;
               color: var(--sa-primary);
          }

          /*
             |--------------------------------------------------------------------------
             | Chart
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-chart-summary {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 18px;
          }

          .super-admin-page .sa-summary-box {
               padding: 14px;
               border: 1px solid var(--sa-border);
               border-radius: 14px;
               background: var(--sa-surface-soft);
          }

          .super-admin-page .sa-summary-box span {
               display: block;
               margin-bottom: 5px;
               font-size: 10px;
               font-weight: 700;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-summary-box strong {
               display: block;
               font-size: 18px;
               color: var(--sa-text);
          }

          .super-admin-page .sa-chart-wrapper {
               position: relative;
               width: 100%;
               min-height: 335px;
          }

          .super-admin-page #superAdminActivityChart {
               width: 100%;
               height: 335px;
          }

          .super-admin-page .sa-chart-fallback {
               display: none;
               align-items: center;
               justify-content: center;
               min-height: 335px;
               padding: 20px;
               border: 1px dashed var(--sa-border);
               border-radius: 16px;
               text-align: center;
               color: var(--sa-muted);
               background: var(--sa-surface-soft);
          }

          /*
             |--------------------------------------------------------------------------
             | Quick actions
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-action-list {
               display: grid;
               gap: 12px;
          }

          .super-admin-page .sa-action-card {
               display: flex;
               align-items: center;
               gap: 13px;
               padding: 14px;
               border: 1px solid var(--sa-border);
               border-radius: 15px;
               color: var(--sa-text) !important;
               text-decoration: none !important;
               background: var(--sa-surface-soft);
          }

          .super-admin-page .sa-action-card:hover {
               transform: translateX(3px);
               border-color: rgba(37, 99, 235, 0.35);
          }

          .super-admin-page .sa-action-icon {
               display: inline-flex;
               flex: 0 0 42px;
               align-items: center;
               justify-content: center;
               width: 42px;
               height: 42px;
               border-radius: 13px;
               color: var(--sa-primary);
               background: var(--sa-primary-soft);
          }

          .super-admin-page .sa-action-card h6 {
               margin: 0 0 4px;
               font-size: 12px;
               font-weight: 800;
               color: var(--sa-text);
          }

          .super-admin-page .sa-action-card p {
               margin: 0;
               font-size: 10px;
               line-height: 1.5;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-action-arrow {
               margin-left: auto;
               color: var(--sa-muted);
          }

          /*
             |--------------------------------------------------------------------------
             | System health
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-health-box {
               margin-top: 18px;
               padding: 16px;
               border-radius: 16px;
               color: #ffffff;
               background: linear-gradient(135deg, #047857, #10b981);
          }

          .super-admin-page .sa-health-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               margin-bottom: 13px;
          }

          .super-admin-page .sa-health-top span {
               font-size: 11px;
               font-weight: 700;
               color: rgba(255, 255, 255, 0.80);
          }

          .super-admin-page .sa-health-top strong {
               font-size: 22px;
               color: #ffffff;
          }

          .super-admin-page .sa-health-track {
               overflow: hidden;
               height: 8px;
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.20);
          }

          .super-admin-page .sa-health-track span {
               display: block;
               width: 99.98%;
               height: 100%;
               border-radius: inherit;
               background: #ffffff;
          }

          /*
             |--------------------------------------------------------------------------
             | Module table
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-table-wrapper {
               overflow-x: auto;
          }

          .super-admin-page .sa-table {
               width: 100%;
               min-width: 690px;
               border-collapse: collapse;
          }

          .super-admin-page .sa-table th,
          .super-admin-page .sa-table td {
               padding: 14px 12px;
               border-bottom: 1px solid var(--sa-border);
               text-align: left;
               vertical-align: middle;
          }

          .super-admin-page .sa-table th {
               font-size: 10px;
               font-weight: 800;
               letter-spacing: 0.05em;
               text-transform: uppercase;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-module {
               display: flex;
               align-items: center;
               gap: 11px;
          }

          .super-admin-page .sa-module-icon {
               display: inline-flex;
               flex: 0 0 38px;
               align-items: center;
               justify-content: center;
               width: 38px;
               height: 38px;
               border-radius: 12px;
               color: var(--sa-primary);
               background: var(--sa-primary-soft);
          }

          .super-admin-page .sa-module strong {
               display: block;
               margin-bottom: 3px;
               font-size: 11px;
               color: var(--sa-text);
          }

          .super-admin-page .sa-module small {
               display: block;
               font-size: 10px;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-badge {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 9px;
               border-radius: 999px;
               font-size: 10px;
               font-weight: 800;
          }

          .super-admin-page .sa-badge.success {
               color: var(--sa-success);
               background: var(--sa-success-soft);
          }

          .super-admin-page .sa-badge.warning {
               color: var(--sa-warning);
               background: var(--sa-warning-soft);
          }

          .super-admin-page .sa-badge-dot {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: currentColor;
          }

          .super-admin-page .sa-progress {
               overflow: hidden;
               width: 120px;
               height: 7px;
               border-radius: 999px;
               background: var(--sa-border);
          }

          .super-admin-page .sa-progress span {
               display: block;
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, #2563eb, #06b6d4);
          }

          /*
             |--------------------------------------------------------------------------
             | Activities
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-activity-list {
               display: grid;
               gap: 2px;
          }

          .super-admin-page .sa-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 12px 0;
          }

          .super-admin-page .sa-activity-item:not(:last-child)::after {
               position: absolute;
               top: 51px;
               bottom: -5px;
               left: 20px;
               width: 1px;
               content: "";
               background: var(--sa-border);
          }

          .super-admin-page .sa-activity-icon {
               z-index: 1;
               display: inline-flex;
               flex: 0 0 40px;
               align-items: center;
               justify-content: center;
               width: 40px;
               height: 40px;
               border-radius: 13px;
          }

          .super-admin-page .sa-activity-icon.primary {
               color: var(--sa-primary);
               background: var(--sa-primary-soft);
          }

          .super-admin-page .sa-activity-icon.success {
               color: var(--sa-success);
               background: var(--sa-success-soft);
          }

          .super-admin-page .sa-activity-icon.danger {
               color: var(--sa-danger);
               background: var(--sa-danger-soft);
          }

          .super-admin-page .sa-activity-icon.purple {
               color: var(--sa-purple);
               background: var(--sa-purple-soft);
          }

          .super-admin-page .sa-activity-content {
               flex: 1;
               min-width: 0;
          }

          .super-admin-page .sa-activity-content h6 {
               margin: 1px 0 4px;
               font-size: 11px;
               font-weight: 800;
               color: var(--sa-text);
          }

          .super-admin-page .sa-activity-content p {
               margin: 0;
               font-size: 10px;
               line-height: 1.55;
               color: var(--sa-muted);
          }

          .super-admin-page .sa-activity-time {
               margin-top: 5px;
               font-size: 9px;
               font-weight: 700;
               color: var(--sa-muted);
          }

          /*
             |--------------------------------------------------------------------------
             | Role distribution
             |--------------------------------------------------------------------------
             */

          .super-admin-page .sa-role-list {
               display: grid;
               gap: 15px;
          }

          .super-admin-page .sa-role-row {
               display: grid;
               grid-template-columns: minmax(125px, 1fr) 2fr auto;
               align-items: center;
               gap: 12px;
          }

          .super-admin-page .sa-role-label {
               font-size: 10px;
               font-weight: 700;
               color: var(--sa-text);
          }

          .super-admin-page .sa-role-track {
               overflow: hidden;
               height: 8px;
               border-radius: 999px;
               background: var(--sa-border);
          }

          .super-admin-page .sa-role-track span {
               display: block;
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, #7c3aed, #2563eb);
          }

          .super-admin-page .sa-role-value {
               min-width: 38px;
               text-align: right;
               font-size: 10px;
               font-weight: 800;
               color: var(--sa-muted);
          }

          /*
             |--------------------------------------------------------------------------
             | Responsive
             |--------------------------------------------------------------------------
             */

          @media (max-width: 1199px) {
               .super-admin-page .sa-kpi-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .super-admin-page .sa-main-grid,
               .super-admin-page .sa-bottom-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767px) {
               .super-admin-page .sa-hero {
                    padding: 24px;
                    border-radius: 20px;
               }

               .super-admin-page .sa-hero-grid {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .super-admin-page .sa-hero-actions {
                    justify-content: flex-start;
                    min-width: 0;
               }

               .super-admin-page .sa-kpi-grid {
                    grid-template-columns: 1fr;
               }

               .super-admin-page .sa-chart-summary {
                    grid-template-columns: 1fr;
               }

               .super-admin-page .sa-card-header {
                    flex-direction: column;
               }

               .super-admin-page .sa-role-row {
                    grid-template-columns: 1fr auto;
               }

               .super-admin-page .sa-role-track {
                    grid-column: 1 / -1;
                    grid-row: 2;
               }
          }
     </style>

     <div class="super-admin-page">
          <div class="sa-shell">

               {{-- Hero --}}
               <section class="sa-hero">
                    <div class="sa-hero-grid">
                         <div>
                              <div class="sa-eyebrow">
                                   <span class="sa-eyebrow-dot"></span>
                                   {{ $roleLabel }}
                              </div>

                              <h1>
                                   Pusat Kendali Super Admin
                              </h1>

                              <p>
                                   Selamat datang, {{ $currentUser?->name ?? 'Administrator' }}.
                                   Kelola pengguna, role, permission, keamanan, dan kondisi sistem
                                   melalui satu dashboard terintegrasi.
                              </p>
                         </div>

                         <div class="sa-hero-actions">
                              <a href="{{ $createUserUrl }}" class="sa-button sa-button-light">
                                   <i data-feather="user-plus"></i>
                                   Tambah Pengguna
                              </a>

                              <button type="button" class="sa-button sa-button-glass" id="refreshDashboardButton">
                                   <i data-feather="refresh-cw"></i>
                                   Segarkan Data
                              </button>
                         </div>
                    </div>
               </section>

               {{-- KPI --}}
               <section class="sa-kpi-grid">
                    <article class="sa-kpi-card">
                         <div class="sa-kpi-top">
                              <div class="sa-kpi-icon primary">
                                   <i data-feather="users"></i>
                              </div>

                              <span class="sa-trend positive">
                                   <i data-feather="arrow-up-right"></i>
                                   8,2%
                              </span>
                         </div>

                         <p class="sa-kpi-label">Total Pengguna</p>

                         <h2 class="sa-kpi-value">
                              {{ number_format((int) data_get($statistics, 'total_users', 0), 0, ',', '.') }}
                         </h2>

                         <div class="sa-trend neutral">
                              Seluruh akun yang terdaftar
                         </div>
                    </article>

                    <article class="sa-kpi-card">
                         <div class="sa-kpi-top">
                              <div class="sa-kpi-icon success">
                                   <i data-feather="user-check"></i>
                              </div>

                              <span class="sa-trend positive">
                                   <i data-feather="check-circle"></i>
                                   Aktif
                              </span>
                         </div>

                         <p class="sa-kpi-label">Pengguna Aktif</p>

                         <h2 class="sa-kpi-value">
                              {{ number_format((int) data_get($statistics, 'active_users', 0), 0, ',', '.') }}
                         </h2>

                         <div class="sa-trend neutral">
                              Akun dengan status aktif
                         </div>
                    </article>

                    <article class="sa-kpi-card">
                         <div class="sa-kpi-top">
                              <div class="sa-kpi-icon purple">
                                   <i data-feather="shield"></i>
                              </div>

                              <span class="sa-trend neutral">
                                   RBAC
                              </span>
                         </div>

                         <p class="sa-kpi-label">Role dan Permission</p>

                         <h2 class="sa-kpi-value">
                              {{ (int) data_get($statistics, 'total_roles', 0) }}
                              <small style="font-size: 13px; color: var(--sa-muted);">
                                   / {{ (int) data_get($statistics, 'total_permissions', 0) }}
                              </small>
                         </h2>

                         <div class="sa-trend neutral">
                              Role / permission tersedia
                         </div>
                    </article>

                    <article class="sa-kpi-card">
                         <div class="sa-kpi-top">
                              <div class="sa-kpi-icon warning">
                                   <i data-feather="alert-circle"></i>
                              </div>

                              <span class="sa-trend warning">
                                   Perlu ditinjau
                              </span>
                         </div>

                         <p class="sa-kpi-label">Peringatan Sistem</p>

                         <h2 class="sa-kpi-value">
                              {{ (int) data_get($statistics, 'open_alerts', 0) }}
                         </h2>

                         <div class="sa-trend neutral">
                              Peringatan yang masih terbuka
                         </div>
                    </article>
               </section>

               {{-- Main --}}
               <section class="sa-main-grid">
                    <article class="sa-card">
                         <header class="sa-card-header">
                              <div>
                                   <h2 class="sa-card-title">
                                        Aktivitas Sistem
                                   </h2>

                                   <p class="sa-card-subtitle">
                                        Perbandingan login berhasil dan aktivitas administrasi
                                        selama tujuh hari terakhir.
                                   </p>
                              </div>

                              <span class="sa-badge success">
                                   <span class="sa-badge-dot"></span>
                                   Sistem normal
                              </span>
                         </header>

                         <div class="sa-card-body">
                              <div class="sa-chart-summary">
                                   <div class="sa-summary-box">
                                        <span>Login berhasil</span>
                                        <strong>1.284</strong>
                                   </div>

                                   <div class="sa-summary-box">
                                        <span>Perubahan data</span>
                                        <strong>326</strong>
                                   </div>

                                   <div class="sa-summary-box">
                                        <span>Login ditolak</span>
                                        <strong>18</strong>
                                   </div>
                              </div>

                              <div class="sa-chart-wrapper">
                                   <div id="superAdminActivityChart"></div>

                                   <div id="superAdminChartFallback" class="sa-chart-fallback">
                                        Grafik tidak dapat ditampilkan karena library Flot
                                        belum dimuat pada layout.
                                   </div>
                              </div>
                         </div>
                    </article>

                    <aside class="sa-card">
                         <header class="sa-card-header">
                              <div>
                                   <h2 class="sa-card-title">
                                        Aksi Cepat
                                   </h2>

                                   <p class="sa-card-subtitle">
                                        Akses fungsi administrasi utama.
                                   </p>
                              </div>
                         </header>

                         <div class="sa-card-body">
                              <div class="sa-action-list">
                                   <a href="{{ $usersUrl }}" class="sa-action-card">
                                        <span class="sa-action-icon">
                                             <i data-feather="users"></i>
                                        </span>

                                        <span>
                                             <h6>Kelola Pengguna</h6>
                                             <p>Lihat, ubah, dan nonaktifkan akun.</p>
                                        </span>

                                        <i data-feather="chevron-right" class="sa-action-arrow"></i>
                                   </a>

                                   <a href="{{ $createUserUrl }}" class="sa-action-card">
                                        <span class="sa-action-icon">
                                             <i data-feather="user-plus"></i>
                                        </span>

                                        <span>
                                             <h6>Tambah Pengguna</h6>
                                             <p>Buat akun dan tetapkan role pengguna.</p>
                                        </span>

                                        <i data-feather="chevron-right" class="sa-action-arrow"></i>
                                   </a>

                                   <a href="{{ $trashUrl }}" class="sa-action-card">
                                        <span class="sa-action-icon">
                                             <i data-feather="trash-2"></i>
                                        </span>

                                        <span>
                                             <h6>Recycle Bin</h6>
                                             <p>Pulihkan atau hapus akun permanen.</p>
                                        </span>

                                        <i data-feather="chevron-right" class="sa-action-arrow"></i>
                                   </a>

                                   <a href="#" class="sa-action-card">
                                        <span class="sa-action-icon">
                                             <i data-feather="key"></i>
                                        </span>

                                        <span>
                                             <h6>Role dan Permission</h6>
                                             <p>Atur matriks hak akses aplikasi.</p>
                                        </span>

                                        <i data-feather="chevron-right" class="sa-action-arrow"></i>
                                   </a>
                              </div>

                              <div class="sa-health-box">
                                   <div class="sa-health-top">
                                        <span>System uptime</span>

                                        <strong>
                                             {{ number_format((float) data_get($statistics, 'system_uptime', 0), 2, ',', '.') }}%
                                        </strong>
                                   </div>

                                   <div class="sa-health-track">
                                        <span></span>
                                   </div>
                              </div>
                         </div>
                    </aside>
               </section>

               {{-- Module and activity --}}
               <section class="sa-bottom-grid">
                    <article class="sa-card">
                         <header class="sa-card-header">
                              <div>
                                   <h2 class="sa-card-title">
                                        Status Modul Sistem
                                   </h2>

                                   <p class="sa-card-subtitle">
                                        Ringkasan kondisi dan penggunaan modul utama.
                                   </p>
                              </div>

                              <a href="#" class="sa-card-link">
                                   Lihat detail
                                   <i data-feather="arrow-right"></i>
                              </a>
                         </header>

                         <div class="sa-card-body">
                              <div class="sa-table-wrapper">
                                   <table class="sa-table">
                                        <thead>
                                             <tr>
                                                  <th>Modul</th>
                                                  <th>Status</th>
                                                  <th>Penggunaan</th>
                                                  <th>Nilai</th>
                                             </tr>
                                        </thead>

                                        <tbody>
                                             @forelse ($systemModules as $module)
                                                  <tr>
                                                       <td>
                                                            <div class="sa-module">
                                                                 <span class="sa-module-icon">
                                                                      <i
                                                                           data-feather="{{ data_get($module, 'icon', 'box') }}"></i>
                                                                 </span>

                                                                 <span>
                                                                      <strong>
                                                                           {{ data_get($module, 'name', '-') }}
                                                                      </strong>

                                                                      <small>
                                                                           {{ data_get($module, 'description', '-') }}
                                                                      </small>
                                                                 </span>
                                                            </div>
                                                       </td>

                                                       <td>
                                                            <span
                                                                 class="sa-badge {{ data_get($module, 'status_type', 'success') }}">
                                                                 <span class="sa-badge-dot"></span>
                                                                 {{ data_get($module, 'status', '-') }}
                                                            </span>
                                                       </td>

                                                       <td>
                                                            <div class="sa-progress">
                                                                 <span
                                                                      style="width: {{ min(100, max(0, (int) data_get($module, 'usage', 0))) }}%;"></span>
                                                            </div>
                                                       </td>

                                                       <td>
                                                            <strong style="font-size: 11px; color: var(--sa-text);">
                                                                 {{ (int) data_get($module, 'usage', 0) }}%
                                                            </strong>
                                                       </td>
                                                  </tr>
                                             @empty
                                                  <tr>
                                                       <td colspan="4">
                                                            Data modul belum tersedia.
                                                       </td>
                                                  </tr>
                                             @endforelse
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                    </article>

                    <article class="sa-card">
                         <header class="sa-card-header">
                              <div>
                                   <h2 class="sa-card-title">
                                        Aktivitas Terbaru
                                   </h2>

                                   <p class="sa-card-subtitle">
                                        Perubahan penting yang terjadi di sistem.
                                   </p>
                              </div>
                         </header>

                         <div class="sa-card-body">
                              <div class="sa-activity-list">
                                   @forelse ($recentActivities as $activity)
                                        <div class="sa-activity-item">
                                             <span class="sa-activity-icon {{ data_get($activity, 'type', 'primary') }}">
                                                  <i data-feather="{{ data_get($activity, 'icon', 'activity') }}"></i>
                                             </span>

                                             <div class="sa-activity-content">
                                                  <h6>
                                                       {{ data_get($activity, 'title', '-') }}
                                                  </h6>

                                                  <p>
                                                       {{ data_get($activity, 'description', '-') }}
                                                  </p>

                                                  <div class="sa-activity-time">
                                                       {{ data_get($activity, 'time', '-') }}
                                                  </div>
                                             </div>
                                        </div>
                                   @empty
                                        <p class="sa-card-subtitle">
                                             Belum ada aktivitas terbaru.
                                        </p>
                                   @endforelse
                              </div>
                         </div>
                    </article>
               </section>

               {{-- Role distribution --}}
               <section class="sa-card" style="margin-top: 20px;">
                    <header class="sa-card-header">
                         <div>
                              <h2 class="sa-card-title">
                                   Distribusi Pengguna Berdasarkan Role
                              </h2>

                              <p class="sa-card-subtitle">
                                   Komposisi akun berdasarkan role yang terhubung melalui tabel pivot role_user.
                              </p>
                         </div>

                         <a href="{{ $usersUrl }}" class="sa-card-link">
                              Kelola pengguna
                              <i data-feather="arrow-right"></i>
                         </a>
                    </header>

                    <div class="sa-card-body">
                         <div class="sa-role-list">
                              @foreach ($roleDistribution as $role)
                                   <div class="sa-role-row">
                                        <span class="sa-role-label">
                                             {{ data_get($role, 'label', '-') }}
                                        </span>

                                        <div class="sa-role-track">
                                             <span
                                                  style="width: {{ min(100, max(0, (int) data_get($role, 'percent', 0))) }}%;"></span>
                                        </div>

                                        <span class="sa-role-value">
                                             {{ (int) data_get($role, 'value', 0) }}
                                        </span>
                                   </div>
                              @endforeach
                         </div>
                    </div>
               </section>

          </div>
     </div>
@endsection

@push('script')
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const refreshButton = document.getElementById(
                    'refreshDashboardButton'
               );

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }

               if (refreshButton) {
                    refreshButton.addEventListener('click', function() {
                         refreshButton.disabled = true;

                         const originalHtml = refreshButton.innerHTML;

                         refreshButton.innerHTML =
                              '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
                              ' Menyegarkan...';

                         window.setTimeout(function() {
                              window.location.reload();
                         }, 450);

                         window.setTimeout(function() {
                              refreshButton.disabled = false;
                              refreshButton.innerHTML = originalHtml;

                              if (typeof feather !== 'undefined') {
                                   feather.replace();
                              }
                         }, 2500);
                    });
               }
          });
     </script>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const chartElement = document.getElementById(
                    'superAdminActivityChart'
               );

               const fallbackElement = document.getElementById(
                    'superAdminChartFallback'
               );

               if (
                    !chartElement ||
                    typeof window.jQuery === 'undefined' ||
                    typeof window.jQuery.plot !== 'function'
               ) {
                    if (chartElement) {
                         chartElement.style.display = 'none';
                    }

                    if (fallbackElement) {
                         fallbackElement.style.display = 'flex';
                    }

                    return;
               }

               const $ = window.jQuery;
               const $chart = $(chartElement);

               const loginData = [
                    [1, 142],
                    [2, 168],
                    [3, 154],
                    [4, 191],
                    [5, 207],
                    [6, 226],
                    [7, 196]
               ];

               const administrationData = [
                    [1, 31],
                    [2, 42],
                    [3, 38],
                    [4, 56],
                    [5, 61],
                    [6, 53],
                    [7, 45]
               ];

               function isDarkTheme() {
                    return document.body.classList.contains('black-theme');
               }

               function renderChart() {
                    const darkTheme = isDarkTheme();

                    const textColor = darkTheme ?
                         '#a8b2c1' :
                         '#64748b';

                    const gridColor = darkTheme ?
                         '#2b313d' :
                         '#e2e8f0';

                    $.plot(
                         $chart,
                         [{
                                   label: 'Login berhasil',
                                   data: loginData,
                                   color: '#2563eb'
                              },
                              {
                                   label: 'Aktivitas administrasi',
                                   data: administrationData,
                                   color: '#7c3aed'
                              }
                         ], {
                              series: {
                                   lines: {
                                        show: true,
                                        lineWidth: 2.5,
                                        fill: true,
                                        fillColor: {
                                             colors: [{
                                                       opacity: 0.13
                                                  },
                                                  {
                                                       opacity: 0.01
                                                  }
                                             ]
                                        }
                                   },
                                   points: {
                                        show: true,
                                        radius: 3,
                                        lineWidth: 2,
                                        fill: true,
                                        fillColor: darkTheme ?
                                             '#171a22' :
                                             '#ffffff'
                                   },
                                   shadowSize: 0
                              },

                              grid: {
                                   borderWidth: 0,
                                   hoverable: true,
                                   clickable: false,
                                   labelMargin: 12
                              },

                              legend: {
                                   show: true,
                                   position: 'nw',
                                   backgroundOpacity: 0,
                                   labelBoxBorderColor: 'transparent'
                              },

                              xaxis: {
                                   ticks: [
                                        [1, 'Sen'],
                                        [2, 'Sel'],
                                        [3, 'Rab'],
                                        [4, 'Kam'],
                                        [5, 'Jum'],
                                        [6, 'Sab'],
                                        [7, 'Min']
                                   ],
                                   tickColor: 'transparent',
                                   font: {
                                        size: 10,
                                        color: textColor
                                   }
                              },

                              yaxis: {
                                   min: 0,
                                   tickColor: gridColor,
                                   font: {
                                        size: 10,
                                        color: textColor
                                   },
                                   tickFormatter: function(value) {
                                        return Math.round(value);
                                   }
                              }
                         }
                    );
               }

               $('#superAdminChartTooltip').remove();

               const $tooltip = $('<div id="superAdminChartTooltip"></div>')
                    .css({
                         position: 'absolute',
                         display: 'none',
                         padding: '8px 10px',
                         borderRadius: '8px',
                         background: '#0f172a',
                         color: '#ffffff',
                         fontSize: '11px',
                         fontWeight: '700',
                         pointerEvents: 'none',
                         zIndex: 9999,
                         boxShadow: '0 12px 30px rgba(15, 23, 42, 0.25)'
                    })
                    .appendTo('body');

               $chart.off('plothover.superAdmin');

               $chart.on(
                    'plothover.superAdmin',
                    function(event, position, item) {
                         if (!item) {
                              $tooltip.hide();
                              return;
                         }

                         $tooltip
                              .html(
                                   item.series.label +
                                   ': <strong>' +
                                   Math.round(item.datapoint[1]) +
                                   '</strong>'
                              )
                              .css({
                                   left: item.pageX + 12,
                                   top: item.pageY - 42
                              })
                              .show();
                    }
               );

               renderChart();

               let resizeTimer = null;

               window.addEventListener('resize', function() {
                    window.clearTimeout(resizeTimer);

                    resizeTimer = window.setTimeout(function() {
                         renderChart();
                    }, 180);
               });

               const themeObserver = new MutationObserver(function() {
                    renderChart();
               });

               themeObserver.observe(document.body, {
                    attributes: true,
                    attributeFilter: ['class']
               });
          });
     </script>
@endpush
