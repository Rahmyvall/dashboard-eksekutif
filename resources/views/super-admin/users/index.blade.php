@extends('layouts.app')

@section('title', 'Executive User Monitoring')

@section('content')
     <style>
          :root {
               --ui-primary: #2563eb;
               --ui-primary-dark: #9ac3f1;
               --ui-primary-soft: #eff6ff;
               --ui-success: #16a34a;
               --ui-success-soft: #ecfdf5;
               --ui-warning: #d97706;
               --ui-warning-soft: #fffbeb;
               --ui-danger: #dc2626;
               --ui-danger-soft: #fef2f2;
               --ui-info: #0891b2;
               --ui-info-soft: #ecfeff;
               --ui-dark: #0f172a;
               --ui-muted: #64748b;
               --ui-border: #e2e8f0;
               --ui-surface: #ffffff;
               --ui-background: #f4f7fb;
               --ui-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
               --ui-shadow-hover: 0 24px 60px rgba(15, 23, 42, 0.13);
          }

          body {
               background: var(--ui-background);
               color: var(--ui-dark);
          }

          .user-monitoring-page {
               position: relative;
               min-height: 100vh;
          }

          /* HERO */
          .executive-hero {
               position: relative;
               overflow: hidden;
               min-height: 235px;
               padding: 36px;
               border: 1px solid rgba(255, 255, 255, 0.18);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 18%, rgba(255, 255, 255, 0.22), transparent 20%),
                    radial-gradient(circle at 72% 85%, rgba(56, 189, 248, 0.30), transparent 28%),
                    linear-gradient(135deg, #0f172a 0%, #c2c9dd 52%, #0891b2 100%);
               box-shadow: 0 28px 70px rgba(30, 64, 175, 0.28);
          }

          .executive-hero::before,
          .executive-hero::after {
               position: absolute;
               content: '';
               border-radius: 999px;
               pointer-events: none;
          }

          .executive-hero::before {
               top: -95px;
               right: -65px;
               width: 260px;
               height: 260px;
               border: 42px solid rgba(255, 255, 255, 0.08);
          }

          .executive-hero::after {
               right: 210px;
               bottom: -95px;
               width: 190px;
               height: 190px;
               background: rgba(255, 255, 255, 0.06);
          }

          .hero-content,
          .hero-action {
               position: relative;
               z-index: 2;
          }

          .hero-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 18px;
               padding: 8px 13px;
               border: 1px solid rgba(255, 255, 255, 0.22);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.10);
               color: rgba(255, 255, 255, 0.92);
               font-size: 12px;
               font-weight: 700;
               letter-spacing: 0.06em;
               text-transform: uppercase;
               backdrop-filter: blur(8px);
          }

          .hero-title {
               max-width: 720px;
               margin: 0;
               color: #ffffff;
               font-size: clamp(29px, 4vw, 42px);
               font-weight: 800;
               line-height: 1.16;
               letter-spacing: -0.035em;
          }

          .hero-description {
               max-width: 660px;
               margin: 14px 0 0;
               color: rgba(255, 255, 255, 0.76);
               font-size: 15px;
               line-height: 1.75;
          }

          .hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 18px;
               margin-top: 24px;
          }

          .hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.78);
               font-size: 13px;
               font-weight: 600;
          }

          .hero-meta-item i {
               color: #7dd3fc;
          }

          .btn-add-user {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 10px;
               min-height: 50px;
               padding: 0 22px;
               border: 1px solid rgba(255, 255, 255, 0.72);
               border-radius: 15px;
               background: #ffffff;
               color: var(--ui-primary-dark);
               font-size: 14px;
               font-weight: 750;
               text-decoration: none;
               box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
               transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
          }

          .btn-add-user:hover {
               color: var(--ui-primary-dark);
               background: #f8fafc;
               transform: translateY(-2px);
               box-shadow: 0 18px 35px rgba(15, 23, 42, 0.24);
          }

          /* KPI */
          .stat-card {
               position: relative;
               height: 100%;
               overflow: hidden;
               padding: 24px;
               border: 1px solid rgba(226, 232, 240, 0.88);
               border-radius: 22px;
               background: var(--ui-surface);
               box-shadow: var(--ui-shadow);
               transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
          }

          .stat-card:hover {
               transform: translateY(-5px);
               border-color: #bfdbfe;
               box-shadow: var(--ui-shadow-hover);
          }

          .stat-card::after {
               position: absolute;
               top: -34px;
               right: -34px;
               width: 110px;
               height: 110px;
               border-radius: 50%;
               background: var(--stat-decoration, #eff6ff);
               content: '';
               opacity: 0.9;
          }

          .stat-header,
          .stat-footer {
               position: relative;
               z-index: 2;
          }

          .stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 52px;
               height: 52px;
               border-radius: 16px;
               font-size: 22px;
          }

          .stat-icon.blue {
               background: var(--ui-primary-soft);
               color: var(--ui-primary);
          }

          .stat-icon.green {
               background: var(--ui-success-soft);
               color: var(--ui-success);
          }

          .stat-icon.orange {
               background: var(--ui-warning-soft);
               color: var(--ui-warning);
          }

          .stat-icon.cyan {
               background: var(--ui-info-soft);
               color: var(--ui-info);
          }

          .stat-label {
               margin-bottom: 5px;
               color: var(--ui-muted);
               font-size: 12px;
               font-weight: 700;
               letter-spacing: 0.035em;
               text-transform: uppercase;
          }

          .stat-value {
               margin: 0;
               color: var(--ui-dark);
               font-size: 32px;
               font-weight: 800;
               line-height: 1;
               letter-spacing: -0.035em;
          }

          .stat-footer {
               display: flex;
               align-items: center;
               gap: 7px;
               margin-top: 22px;
               color: var(--ui-muted);
               font-size: 12px;
               font-weight: 600;
          }

          .stat-dot {
               width: 7px;
               height: 7px;
               border-radius: 50%;
               background: currentColor;
          }

          /* GENERAL PANEL */
          .dashboard-panel {
               border: 1px solid rgba(226, 232, 240, 0.9);
               border-radius: 24px;
               background: var(--ui-surface);
               box-shadow: var(--ui-shadow);
          }

          .filter-panel {
               padding: 22px;
          }

          .panel-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 20px;
               padding: 25px 26px 20px;
               border-bottom: 1px solid #eef2f7;
          }

          .panel-title {
               margin: 0;
               color: var(--ui-dark);
               font-size: 20px;
               font-weight: 800;
               letter-spacing: -0.02em;
          }

          .panel-subtitle {
               margin: 7px 0 0;
               color: var(--ui-muted);
               font-size: 13px;
          }

          .record-count {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 8px 12px;
               border-radius: 999px;
               background: #f1f5f9;
               color: #475569;
               font-size: 12px;
               font-weight: 700;
          }

          /* FILTER */
          .filter-label {
               margin-bottom: 8px;
               color: #334155;
               font-size: 12px;
               font-weight: 700;
          }

          .input-shell {
               position: relative;
          }

          .input-shell>i {
               position: absolute;
               top: 50%;
               left: 16px;
               z-index: 3;
               color: #94a3b8;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .input-shell .form-control {
               padding-left: 44px;
          }

          .form-control,
          .form-select {
               min-height: 48px;
               border: 1px solid var(--ui-border);
               border-radius: 14px;
               background-color: #ffffff;
               color: #1e293b;
               font-size: 14px;
               box-shadow: none;
          }

          .form-control::placeholder {
               color: #94a3b8;
          }

          .form-control:focus,
          .form-select:focus {
               border-color: #60a5fa;
               box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.11);
          }

          .btn-filter,
          .btn-reset,
          .btn-trash {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 48px;
               border-radius: 14px;
               font-size: 13px;
               font-weight: 700;
               text-decoration: none;
               transition: all 0.2s ease;
          }

          .btn-filter {
               width: 100%;
               border: 1px solid var(--ui-primary);
               background: var(--ui-primary);
               color: #ffffff;
               box-shadow: 0 10px 22px rgba(37, 99, 235, 0.20);
          }

          .btn-filter:hover {
               border-color: var(--ui-primary-dark);
               background: var(--ui-primary-dark);
               color: #ffffff;
               transform: translateY(-1px);
          }

          .btn-reset {
               width: 100%;
               border: 1px solid var(--ui-border);
               background: #ffffff;
               color: #475569;
          }

          .btn-reset:hover {
               border-color: #cbd5e1;
               background: #f8fafc;
               color: var(--ui-dark);
          }

          .btn-trash {
               min-height: 42px;
               padding: 0 15px;
               border: 1px solid #fecaca;
               background: #fff7f7;
               color: var(--ui-danger);
          }

          .btn-trash:hover {
               border-color: #fca5a5;
               background: var(--ui-danger-soft);
               color: #b91c1c;
               transform: translateY(-1px);
          }

          /* TABLE */
          .users-table-wrap {
               padding: 4px 18px 18px;
          }

          .users-table {
               min-width: 930px;
               margin-bottom: 0;
               border-collapse: separate;
               border-spacing: 0 8px;
          }

          .users-table thead th {
               padding: 12px 14px;
               border: 0;
               color: #94a3b8;
               font-size: 11px;
               font-weight: 800;
               letter-spacing: 0.06em;
               text-transform: uppercase;
               white-space: nowrap;
          }

          .users-table tbody td {
               padding: 15px 14px;
               border-top: 1px solid #edf2f7;
               border-bottom: 1px solid #edf2f7;
               background: #ffffff;
               color: #334155;
               font-size: 13px;
               vertical-align: middle;
               transition: background 0.18s ease, border-color 0.18s ease;
          }

          .users-table tbody td:first-child {
               border-left: 1px solid #edf2f7;
               border-radius: 14px 0 0 14px;
          }

          .users-table tbody td:last-child {
               border-right: 1px solid #edf2f7;
               border-radius: 0 14px 14px 0;
          }

          .users-table tbody tr:hover td {
               border-color: #dbeafe;
               background: #f8fbff;
          }

          .user-identity {
               display: flex;
               align-items: center;
               gap: 12px;
               min-width: 190px;
          }

          .user-avatar {
               position: relative;
               display: inline-flex;
               flex: 0 0 auto;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border: 3px solid #ffffff;
               border-radius: 15px;
               background: linear-gradient(135deg, #1d4ed8, #38bdf8);
               color: #ffffff;
               font-size: 16px;
               font-weight: 800;
               box-shadow: 0 8px 18px rgba(37, 99, 235, 0.20);
          }

          .avatar-presence {
               position: absolute;
               right: -2px;
               bottom: -2px;
               width: 11px;
               height: 11px;
               border: 2px solid #ffffff;
               border-radius: 50%;
               background: #22c55e;
          }

          .user-name {
               margin-bottom: 3px;
               color: #0f172a;
               font-size: 14px;
               font-weight: 750;
               line-height: 1.3;
          }

          .user-id {
               color: #94a3b8;
               font-size: 11px;
               font-weight: 600;
          }

          .email-text {
               color: #475569;
               font-weight: 550;
          }

          .status-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 7px 11px;
               border-radius: 999px;
               font-size: 11px;
               font-weight: 800;
               white-space: nowrap;
          }

          .status-badge::before {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: currentColor;
               content: '';
          }

          .status-active {
               background: var(--ui-success-soft);
               color: var(--ui-success);
          }

          .status-inactive {
               background: var(--ui-warning-soft);
               color: var(--ui-warning);
          }

          .status-suspended {
               background: var(--ui-danger-soft);
               color: var(--ui-danger);
          }

          .date-main {
               display: block;
               color: #334155;
               font-weight: 650;
               white-space: nowrap;
          }

          .date-caption {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: 11px;
               white-space: nowrap;
          }

          .action-group {
               display: flex;
               align-items: center;
               gap: 7px;
               white-space: nowrap;
          }

          .action-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               padding: 0;
               border: 1px solid transparent;
               border-radius: 11px;
               background: transparent;
               text-decoration: none;
               transition: all 0.18s ease;
          }

          .action-button:hover {
               transform: translateY(-2px);
          }

          .action-view {
               border-color: #bfdbfe;
               background: var(--ui-primary-soft);
               color: var(--ui-primary);
          }

          .action-edit {
               border-color: #fde68a;
               background: var(--ui-warning-soft);
               color: var(--ui-warning);
          }

          .action-delete {
               border-color: #fecaca;
               background: var(--ui-danger-soft);
               color: var(--ui-danger);
          }

          .empty-state {
               padding: 60px 20px !important;
               border: 1px dashed #cbd5e1 !important;
               border-radius: 18px !important;
               background: #f8fafc !important;
               text-align: center;
          }

          .empty-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 62px;
               height: 62px;
               margin-bottom: 15px;
               border-radius: 18px;
               background: #eaf2ff;
               color: var(--ui-primary);
               font-size: 27px;
          }

          .empty-title {
               margin-bottom: 6px;
               color: var(--ui-dark);
               font-size: 16px;
               font-weight: 800;
          }

          .empty-description {
               margin: 0;
               color: var(--ui-muted);
               font-size: 13px;
          }

          /* PAGINATION */
          .pagination-shell {
               display: flex;
               justify-content: flex-end;
               padding: 18px 26px 25px;
               border-top: 1px solid #eef2f7;
          }

          .pagination {
               margin: 0;
               gap: 5px;
          }

          .page-link {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 38px;
               height: 38px;
               border: 1px solid var(--ui-border);
               border-radius: 10px !important;
               color: #475569;
               font-size: 13px;
               font-weight: 700;
               box-shadow: none !important;
          }

          .page-link:hover {
               border-color: #bfdbfe;
               background: var(--ui-primary-soft);
               color: var(--ui-primary);
          }

          .page-item.active .page-link {
               border-color: var(--ui-primary);
               background: var(--ui-primary);
               color: #ffffff;
          }

          .page-item.disabled .page-link {
               background: #f8fafc;
               color: #cbd5e1;
          }

          /* RESPONSIVE */
          @media (max-width: 991.98px) {
               .executive-hero {
                    padding: 30px;
               }

               .hero-action {
                    width: 100%;
                    margin-top: 26px;
               }

               .btn-add-user {
                    width: 100%;
               }

               .panel-heading {
                    align-items: stretch;
                    flex-direction: column;
               }

               .btn-trash {
                    width: 100%;
               }
          }

          @media (max-width: 575.98px) {
               .container-fluid.user-monitoring-page {
                    padding-right: 14px;
                    padding-left: 14px;
               }

               .executive-hero {
                    min-height: auto;
                    padding: 24px 21px;
                    border-radius: 22px;
               }

               .hero-description {
                    font-size: 13px;
               }

               .hero-meta {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 10px;
               }

               .stat-card {
                    padding: 20px;
               }

               .filter-panel {
                    padding: 18px;
               }

               .panel-heading {
                    padding: 21px 18px 17px;
               }

               .users-table-wrap {
                    padding: 0 10px 12px;
               }

               .pagination-shell {
                    justify-content: center;
                    padding: 16px 18px 22px;
               }
          }
     </style>

     <div class="container-fluid user-monitoring-page py-4 py-lg-5">
          {{-- Hero Section --}}
          <section class="executive-hero mb-4">
               <div class="row align-items-center h-100">
                    <div class="col-lg-8 hero-content">
                         <div class="hero-eyebrow">
                              <i class="bi bi-grid-1x2-fill"></i>
                              User Management Dashboard
                         </div>

                         <h1 class="hero-title">Executive User Monitoring</h1>

                         <p class="hero-description">
                              Kelola akun pengguna, pantau status akses, dan tinjau aktivitas login
                              perusahaan dari satu dashboard yang terpusat.
                         </p>

                         <div class="hero-meta">
                              <span class="hero-meta-item">
                                   <i class="bi bi-shield-check"></i>
                                   Pengelolaan akses aman
                              </span>
                              <span class="hero-meta-item">
                                   <i class="bi bi-activity"></i>
                                   Aktivitas sistem terpantau
                              </span>
                         </div>
                    </div>

                    <div class="col-lg-4 hero-action text-lg-end">
                         <a href="{{ route('super-admin.users.create') }}" class="btn-add-user">
                              <i class="bi bi-person-plus-fill"></i>
                              Tambah Pengguna
                         </a>
                    </div>
               </div>
          </section>

          {{-- Statistics --}}
          <section class="row g-3 g-xl-4 mb-4">
               <div class="col-sm-6 col-xl-3">
                    <article class="stat-card" style="--stat-decoration: #dbeafe;">
                         <div class="stat-header d-flex align-items-center justify-content-between gap-3">
                              <div>
                                   <div class="stat-label">Total Pengguna</div>
                                   <h2 class="stat-value">{{ number_format($statistics['total_users'] ?? 0) }}</h2>
                              </div>
                              <div class="stat-icon blue">
                                   <i class="bi bi-people-fill"></i>
                              </div>
                         </div>
                         <div class="stat-footer text-primary">
                              <span class="stat-dot"></span>
                              Seluruh akun terdaftar
                         </div>
                    </article>
               </div>

               <div class="col-sm-6 col-xl-3">
                    <article class="stat-card" style="--stat-decoration: #dcfce7;">
                         <div class="stat-header d-flex align-items-center justify-content-between gap-3">
                              <div>
                                   <div class="stat-label">Pengguna Aktif</div>
                                   <h2 class="stat-value">{{ number_format($statistics['active_users'] ?? 0) }}</h2>
                              </div>
                              <div class="stat-icon green">
                                   <i class="bi bi-person-check-fill"></i>
                              </div>
                         </div>
                         <div class="stat-footer text-success">
                              <span class="stat-dot"></span>
                              Memiliki akses aktif
                         </div>
                    </article>
               </div>

               <div class="col-sm-6 col-xl-3">
                    <article class="stat-card" style="--stat-decoration: #fef3c7;">
                         <div class="stat-header d-flex align-items-center justify-content-between gap-3">
                              <div>
                                   <div class="stat-label">Tidak Aktif</div>
                                   <h2 class="stat-value">{{ number_format($statistics['inactive_users'] ?? 0) }}</h2>
                              </div>
                              <div class="stat-icon orange">
                                   <i class="bi bi-person-dash-fill"></i>
                              </div>
                         </div>
                         <div class="stat-footer text-warning">
                              <span class="stat-dot"></span>
                              Akses sementara nonaktif
                         </div>
                    </article>
               </div>

               <div class="col-sm-6 col-xl-3">
                    <article class="stat-card" style="--stat-decoration: #cffafe;">
                         <div class="stat-header d-flex align-items-center justify-content-between gap-3">
                              <div>
                                   <div class="stat-label">Aktivitas Login</div>
                                   <h2 class="stat-value">{{ number_format($statistics['login_activity'] ?? 0) }}</h2>
                              </div>
                              <div class="stat-icon cyan">
                                   <i class="bi bi-activity"></i>
                              </div>
                         </div>
                         <div class="stat-footer text-info">
                              <span class="stat-dot"></span>
                              Total aktivitas tercatat
                         </div>
                    </article>
               </div>
          </section>

          {{-- Search and Filter --}}
          <section class="dashboard-panel filter-panel mb-4">
               <form method="GET" action="{{ url()->current() }}">
                    <div class="row g-3 align-items-end">
                         <div class="col-lg-6">
                              <label for="search" class="filter-label">Cari Pengguna</label>
                              <div class="input-shell">
                                   <i class="bi bi-search"></i>
                                   <input type="search" id="search" name="search" class="form-control"
                                        value="{{ request('search') }}" placeholder="Masukkan nama atau alamat email..."
                                        autocomplete="off">
                              </div>
                         </div>

                         <div class="col-sm-6 col-lg-3">
                              <label for="status" class="filter-label">Status Akun</label>
                              <select id="status" name="status" class="form-select">
                                   <option value="">Semua Status</option>
                                   <option value="active" @selected(request('status') === 'active')>Active</option>
                                   <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                                   <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                              </select>
                         </div>

                         <div class="col-6 col-sm-3 col-lg-2">
                              <button type="submit" class="btn-filter">
                                   <i class="bi bi-funnel-fill"></i>
                                   Terapkan
                              </button>
                         </div>

                         <div class="col-6 col-sm-3 col-lg-1">
                              <a href="{{ url()->current() }}" class="btn-reset" title="Reset filter"
                                   aria-label="Reset filter">
                                   <i class="bi bi-arrow-counterclockwise"></i>
                                   <span class="d-lg-none">Reset</span>
                              </a>
                         </div>
                    </div>
               </form>
          </section>

          {{-- Users Table --}}
          <section class="dashboard-panel overflow-hidden">
               <header class="panel-heading">
                    <div>
                         <div class="d-flex align-items-center flex-wrap gap-2">
                              <h2 class="panel-title">Daftar Pengguna</h2>
                              <span class="record-count">
                                   <i class="bi bi-database"></i>
                                   {{ number_format($users->total()) }} data
                              </span>
                         </div>
                         <p class="panel-subtitle">
                              Tinjau informasi pengguna dan kelola akses akun melalui menu tindakan.
                         </p>
                    </div>

                    <a href="{{ route('super-admin.users.trash') }}" class="btn-trash">
                         <i class="bi bi-trash3-fill"></i>
                         Recycle Bin
                    </a>
               </header>

               <div class="users-table-wrap table-responsive">
                    <table class="table users-table align-middle">
                         <thead>
                              <tr>
                                   <th scope="col">Pengguna</th>
                                   <th scope="col">Email</th>
                                   <th scope="col">Status</th>
                                   <th scope="col">Login Terakhir</th>
                                   <th scope="col">Tanggal Dibuat</th>
                                   <th scope="col" class="text-center">Aksi</th>
                              </tr>
                         </thead>

                         <tbody>
                              @forelse ($users as $user)
                                   @php
                                        $userStatus = strtolower($user->status ?? 'inactive');
                                        $statusClass = match ($userStatus) {
                                            'active' => 'status-active',
                                            'inactive' => 'status-inactive',
                                            default => 'status-suspended',
                                        };
                                        $statusLabel = match ($userStatus) {
                                            'active' => 'Active',
                                            'inactive' => 'Inactive',
                                            default => 'Suspended',
                                        };
                                   @endphp

                                   <tr>
                                        <td>
                                             <div class="user-identity">
                                                  <div class="user-avatar">
                                                       {{ strtoupper(substr(trim($user->name ?? 'U'), 0, 1)) }}
                                                       @if ($userStatus === 'active')
                                                            <span class="avatar-presence"></span>
                                                       @endif
                                                  </div>

                                                  <div>
                                                       <div class="user-name">{{ $user->name }}</div>
                                                       <div class="user-id">User ID #{{ $user->id }}</div>
                                                  </div>
                                             </div>
                                        </td>

                                        <td>
                                             <span class="email-text">{{ $user->email }}</span>
                                        </td>

                                        <td>
                                             <span class="status-badge {{ $statusClass }}">
                                                  {{ $statusLabel }}
                                             </span>
                                        </td>

                                        <td>
                                             @if ($user->last_login_at)
                                                  <span
                                                       class="date-main">{{ $user->last_login_at->diffForHumans() }}</span>
                                                  <span class="date-caption">
                                                       {{ $user->last_login_at->format('d M Y, H:i') }}
                                                  </span>
                                             @else
                                                  <span class="date-main">Belum Login</span>
                                                  <span class="date-caption">Tidak ada aktivitas</span>
                                             @endif
                                        </td>

                                        <td>
                                             <span class="date-main">{{ $user->created_at->format('d M Y') }}</span>
                                             <span class="date-caption">{{ $user->created_at->diffForHumans() }}</span>
                                        </td>

                                        <td>
                                             <div class="action-group justify-content-center">
                                                  <a href="{{ route('super-admin.users.show', $user->id) }}"
                                                       class="action-button action-view" title="Lihat detail"
                                                       aria-label="Lihat detail {{ $user->name }}">
                                                       <i class="bi bi-eye-fill"></i>
                                                  </a>

                                                  <a href="{{ route('super-admin.users.edit', $user->id) }}"
                                                       class="action-button action-edit" title="Edit pengguna"
                                                       aria-label="Edit pengguna {{ $user->name }}">
                                                       <i class="bi bi-pencil-square"></i>
                                                  </a>

                                                  <form method="POST"
                                                       action="{{ route('super-admin.users.destroy', $user->id) }}"
                                                       class="m-0"
                                                       onsubmit="return confirm('Apakah Anda yakin ingin memindahkan pengguna ini ke Recycle Bin?')">
                                                       @csrf
                                                       @method('DELETE')

                                                       <button type="submit" class="action-button action-delete"
                                                            title="Hapus pengguna"
                                                            aria-label="Hapus pengguna {{ $user->name }}">
                                                            <i class="bi bi-trash3-fill"></i>
                                                       </button>
                                                  </form>
                                             </div>
                                        </td>
                                   </tr>
                              @empty
                                   <tr>
                                        <td colspan="6" class="empty-state">
                                             <div class="empty-icon">
                                                  <i class="bi bi-people"></i>
                                             </div>
                                             <div class="empty-title">Data pengguna belum tersedia</div>
                                             <p class="empty-description">
                                                  Tambahkan pengguna baru atau ubah kata kunci pencarian dan filter status.
                                             </p>
                                        </td>
                                   </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>

               @if ($users->hasPages())
                    <footer class="pagination-shell">
                         {{ $users->withQueryString()->links() }}
                    </footer>
               @endif
          </section>
     </div>
@endsection
