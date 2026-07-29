@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
     <style>
          :root {
               --role-primary: #2563eb;
               --role-primary-dark: #1d4ed8;
               --role-primary-soft: #eff6ff;

               --role-success: #16a34a;
               --role-success-soft: #ecfdf5;

               --role-warning: #d97706;
               --role-warning-soft: #fffbeb;

               --role-danger: #dc2626;
               --role-danger-soft: #fef2f2;

               --role-purple: #9333ea;
               --role-purple-soft: #f3e8ff;

               --role-dark: #0f172a;
               --role-muted: #64748b;
               --role-border: #e2e8f0;
               --role-background: #f4f7fb;

               --role-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
          }

          body {
               background-color: var(--role-background);
          }

          .role-page {
               min-height: 100vh;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Hero
                       |--------------------------------------------------------------------------
                       */

          .role-hero {
               position: relative;
               overflow: hidden;
               padding: 36px;
               border-radius: 28px;
               background:
                    radial-gradient(circle at 80% 20%,
                         rgba(180, 162, 162, 0.25),
                         transparent 20%),
                    linear-gradient(135deg,
                         #9da8c0,
                         #2563eb,
                         #0891b2);
               box-shadow: 0 28px 70px rgba(37, 99, 235, 0.25);
          }

          .role-hero::before {
               position: absolute;
               top: -90px;
               right: -70px;
               width: 260px;
               height: 260px;
               content: "";
               border-radius: 50%;
               background-color: rgba(255, 255, 255, 0.08);
          }

          .role-hero::after {
               position: absolute;
               bottom: -120px;
               right: 180px;
               width: 220px;
               height: 220px;
               content: "";
               border-radius: 50%;
               background-color: rgba(255, 255, 255, 0.06);
          }

          .role-hero-content {
               position: relative;
               z-index: 2;
          }

          .role-hero h1 {
               margin-bottom: 14px;
               color: #ffffff;
               font-size: clamp(30px, 4vw, 42px);
               font-weight: 800;
               line-height: 1.15;
          }

          .role-hero p {
               max-width: 680px;
               margin-bottom: 0;
               color: rgba(255, 255, 255, 0.8);
               font-size: 15px;
               line-height: 1.75;
          }

          .hero-badge {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 8px 14px;
               border: 1px solid rgba(255, 255, 255, 0.18);
               border-radius: 999px;
               background-color: rgba(255, 255, 255, 0.15);
               color: #ffffff;
               font-size: 12px;
               font-weight: 700;
               letter-spacing: 0.02em;
               backdrop-filter: blur(8px);
          }

          .btn-add-role {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 10px;
               min-height: 48px;
               padding: 13px 22px;
               border: 1px solid rgba(255, 255, 255, 0.4);
               border-radius: 15px;
               background-color: #ffffff;
               color: var(--role-primary);
               font-weight: 700;
               text-decoration: none;
               box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
               transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease,
                    background-color 0.2s ease;
          }

          .btn-add-role:hover {
               color: var(--role-primary-dark);
               background-color: #f8fafc;
               box-shadow: 0 16px 34px rgba(15, 23, 42, 0.24);
               transform: translateY(-2px);
          }

          /*
                       |--------------------------------------------------------------------------
                       | Statistic cards
                       |--------------------------------------------------------------------------
                       */

          .stat-card {
               height: 100%;
               padding: 24px;
               border: 1px solid var(--role-border);
               border-radius: 22px;
               background-color: #ffffff;
               box-shadow: var(--role-shadow);
               transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease;
          }

          .stat-card:hover {
               box-shadow: 0 22px 55px rgba(15, 23, 42, 0.12);
               transform: translateY(-3px);
          }

          .stat-label {
               margin-bottom: 8px;
               color: var(--role-muted);
               font-size: 12px;
               font-weight: 700;
               letter-spacing: 0.06em;
               text-transform: uppercase;
          }

          .stat-value {
               margin: 0;
               color: var(--role-dark);
               font-size: 32px;
               font-weight: 800;
               line-height: 1;
          }

          .stat-icon {
               display: inline-flex;
               flex: 0 0 auto;
               align-items: center;
               justify-content: center;
               width: 52px;
               height: 52px;
               border-radius: 16px;
               font-size: 22px;
          }

          .icon-blue {
               background-color: var(--role-primary-soft);
               color: var(--role-primary);
          }

          .icon-green {
               background-color: var(--role-success-soft);
               color: var(--role-success);
          }

          .icon-orange {
               background-color: var(--role-warning-soft);
               color: var(--role-warning);
          }

          .icon-purple {
               background-color: var(--role-purple-soft);
               color: var(--role-purple);
          }

          /*
                       |--------------------------------------------------------------------------
                       | Main panels
                       |--------------------------------------------------------------------------
                       */

          .dashboard-panel {
               overflow: hidden;
               border: 1px solid var(--role-border);
               border-radius: 24px;
               background-color: #ffffff;
               box-shadow: var(--role-shadow);
          }

          .panel-header {
               padding: 24px 26px;
               border-bottom: 1px solid #eef2f7;
          }

          .panel-title {
               margin: 0;
               color: var(--role-dark);
               font-size: 20px;
               font-weight: 800;
          }

          .panel-subtitle {
               margin: 5px 0 0;
               color: var(--role-muted);
               font-size: 13px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Filter
                       |--------------------------------------------------------------------------
                       */

          .filter-panel {
               padding: 24px;
          }

          .filter-label {
               margin-bottom: 8px;
               color: #334155;
               font-size: 13px;
               font-weight: 700;
          }

          .filter-panel .form-control,
          .filter-panel .form-select {
               min-height: 46px;
               border-color: var(--role-border);
               border-radius: 12px;
               color: var(--role-dark);
               font-size: 14px;
          }

          .filter-panel .form-control:focus,
          .filter-panel .form-select:focus {
               border-color: var(--role-primary);
               box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
          }

          .btn-filter {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 46px;
               border-radius: 12px;
               font-weight: 700;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Table
                       |--------------------------------------------------------------------------
                       */

          .role-table {
               margin-bottom: 0;
               white-space: nowrap;
          }

          .role-table thead th {
               padding: 16px;
               border-top: 0;
               border-bottom: 1px solid var(--role-border);
               background-color: #f8fafc;
               color: #475569;
               font-size: 11px;
               font-weight: 800;
               letter-spacing: 0.05em;
               text-transform: uppercase;
          }

          .role-table tbody td {
               padding: 16px;
               border-color: #eef2f7;
               color: #334155;
               font-size: 14px;
               vertical-align: middle;
          }

          .role-table tbody tr {
               transition: background-color 0.2s ease;
          }

          .role-table tbody tr:hover {
               background-color: #f8fafc;
          }

          .role-avatar {
               display: inline-flex;
               flex: 0 0 auto;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
               background: linear-gradient(135deg,
                         var(--role-primary-soft),
                         #dbeafe);
               color: var(--role-primary);
               font-size: 17px;
               font-weight: 800;
          }

          .role-name {
               color: var(--role-dark);
               font-weight: 750;
          }

          .role-slug {
               display: block;
               max-width: 250px;
               overflow: hidden;
               color: var(--role-muted);
               font-size: 12px;
               text-overflow: ellipsis;
          }

          .role-description {
               display: block;
               max-width: 280px;
               margin-top: 3px;
               overflow: hidden;
               color: var(--role-muted);
               font-size: 12px;
               text-overflow: ellipsis;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Badges
                       |--------------------------------------------------------------------------
                       */

          .role-badge {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               padding: 6px 10px;
               border-radius: 999px;
               font-size: 11px;
               font-weight: 700;
          }

          .role-badge-active {
               background-color: var(--role-success-soft);
               color: var(--role-success);
          }

          .role-badge-inactive {
               background-color: var(--role-warning-soft);
               color: var(--role-warning);
          }

          .role-badge-system {
               background-color: var(--role-primary-soft);
               color: var(--role-primary);
          }

          .role-badge-custom {
               background-color: #f1f5f9;
               color: #475569;
          }

          .role-badge-guard {
               background-color: #f8fafc;
               color: #475569;
               border: 1px solid var(--role-border);
          }

          .data-count-badge {
               display: inline-flex;
               align-items: center;
               padding: 7px 12px;
               border: 1px solid var(--role-border);
               border-radius: 999px;
               background-color: #f8fafc;
               color: #475569;
               font-size: 12px;
               font-weight: 700;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Actions
                       |--------------------------------------------------------------------------
                       */

          .action-group {
               display: flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
          }

          .action-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               padding: 0;
               border-radius: 10px;
               transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease;
          }

          .action-button:hover {
               box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
               transform: translateY(-2px);
          }

          .action-form {
               display: inline-flex;
               margin: 0;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Empty state
                       |--------------------------------------------------------------------------
                       */

          .empty-state {
               padding: 55px 20px !important;
               text-align: center;
          }

          .empty-state-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 74px;
               height: 74px;
               border-radius: 22px;
               background-color: #f1f5f9;
               color: #94a3b8;
               font-size: 32px;
          }

          .empty-state h5 {
               margin-top: 18px;
               margin-bottom: 6px;
               color: var(--role-dark);
               font-weight: 800;
          }

          .empty-state p {
               margin-bottom: 18px;
               color: var(--role-muted);
          }

          /*
                       |--------------------------------------------------------------------------
                       | Pagination
                       |--------------------------------------------------------------------------
                       */

          .pagination-wrapper {
               display: flex;
               justify-content: flex-end;
               padding: 20px 24px;
               border-top: 1px solid #eef2f7;
          }

          .pagination-wrapper nav {
               margin-bottom: 0;
          }

          /*
                       |--------------------------------------------------------------------------
                       | Responsive
                       |--------------------------------------------------------------------------
                       */

          @media (max-width: 991.98px) {
               .role-hero {
                    padding: 28px;
               }

               .role-hero-action {
                    text-align: left !important;
               }

               .btn-add-role {
                    width: 100%;
               }

               .panel-header-content {
                    align-items: flex-start !important;
                    gap: 15px;
               }
          }

          @media (max-width: 575.98px) {
               .role-page {
                    padding-top: 16px !important;
                    padding-bottom: 24px !important;
               }

               .role-hero {
                    padding: 24px 20px;
                    border-radius: 20px;
               }

               .role-hero h1 {
                    font-size: 28px;
               }

               .stat-card {
                    padding: 20px;
                    border-radius: 18px;
               }

               .dashboard-panel {
                    border-radius: 18px;
               }

               .panel-header,
               .filter-panel {
                    padding: 20px;
               }

               .panel-header-content {
                    flex-direction: column;
               }

               .data-count-badge {
                    align-self: flex-start;
               }

               .pagination-wrapper {
                    justify-content: center;
               }
          }
     </style>

     <div class="container-fluid role-page py-4 py-lg-5">

          {{-- Flash message --}}
          @if (session('success'))
               <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
               </div>
          @endif

          @if (session('error'))
               <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
               </div>
          @endif

          {{-- Hero --}}
          <section class="role-hero mb-4">
               <div class="role-hero-content">
                    <div class="row align-items-center">
                         <div class="col-lg-8">
                              <span class="hero-badge">
                                   <i class="bi bi-shield-lock-fill"></i>
                                   Role Management System
                              </span>

                              <h1 class="mt-3">
                                   Role &amp; Permission Management
                              </h1>

                              <p>
                                   Kelola role sistem, hak akses pengguna, dan pembagian
                                   permission aplikasi melalui dashboard terpusat.
                              </p>
                         </div>

                         <div class="col-lg-4 role-hero-action text-lg-end mt-4 mt-lg-0">
                              <a href="{{ route('super-admin.roles.create') }}" class="btn-add-role">
                                   <i class="bi bi-plus-circle-fill"></i>
                                   Tambah Role
                              </a>
                         </div>
                    </div>
               </div>
          </section>

          {{-- Statistics --}}
          <section class="row g-4 mb-4">
               <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                         <div class="d-flex justify-content-between align-items-center gap-3">
                              <div>
                                   <div class="stat-label">
                                        Total Role
                                   </div>

                                   <h2 class="stat-value">
                                        {{ number_format($stats['total_roles'] ?? 0) }}
                                   </h2>
                              </div>

                              <div class="stat-icon icon-blue">
                                   <i class="bi bi-person-badge-fill"></i>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                         <div class="d-flex justify-content-between align-items-center gap-3">
                              <div>
                                   <div class="stat-label">
                                        Role Aktif
                                   </div>

                                   <h2 class="stat-value">
                                        {{ number_format($stats['active_roles'] ?? 0) }}
                                   </h2>
                              </div>

                              <div class="stat-icon icon-green">
                                   <i class="bi bi-check-circle-fill"></i>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                         <div class="d-flex justify-content-between align-items-center gap-3">
                              <div>
                                   <div class="stat-label">
                                        Role Nonaktif
                                   </div>

                                   <h2 class="stat-value">
                                        {{ number_format($stats['inactive_roles'] ?? 0) }}
                                   </h2>
                              </div>

                              <div class="stat-icon icon-orange">
                                   <i class="bi bi-x-circle-fill"></i>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                         <div class="d-flex justify-content-between align-items-center gap-3">
                              <div>
                                   <div class="stat-label">
                                        System Role
                                   </div>

                                   <h2 class="stat-value">
                                        {{ number_format($stats['system_roles'] ?? 0) }}
                                   </h2>
                              </div>

                              <div class="stat-icon icon-purple">
                                   <i class="bi bi-cpu-fill"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </section>

          {{-- Filter --}}
          <section class="dashboard-panel filter-panel mb-4">
               <form method="GET" action="{{ route('super-admin.roles.index') }}">
                    <div class="row g-3 align-items-end">
                         <div class="col-lg-6">
                              <label for="search" class="form-label filter-label">
                                   Cari Role
                              </label>

                              <input type="search" id="search" name="search" class="form-control"
                                   value="{{ request('search') }}" placeholder="Cari nama, guard, atau deskripsi role..."
                                   autocomplete="off">
                         </div>

                         <div class="col-md-6 col-lg-3">
                              <label for="status" class="form-label filter-label">
                                   Status
                              </label>

                              <select id="status" name="status" class="form-select">
                                   <option value="">
                                        Semua Status
                                   </option>

                                   <option value="active" @selected(request('status') === 'active')>
                                        Aktif
                                   </option>

                                   <option value="inactive" @selected(request('status') === 'inactive')>
                                        Nonaktif
                                   </option>
                              </select>
                         </div>

                         <div class="col-md-3 col-lg-2">
                              <button type="submit" class="btn btn-primary btn-filter w-100">
                                   <i class="bi bi-search"></i>
                                   Cari
                              </button>
                         </div>

                         <div class="col-md-3 col-lg-1">
                              <a href="{{ route('super-admin.roles.index') }}"
                                   class="btn btn-outline-secondary btn-filter w-100" title="Reset filter"
                                   aria-label="Reset filter">
                                   <i class="bi bi-arrow-counterclockwise"></i>
                              </a>
                         </div>
                    </div>
               </form>
          </section>

          {{-- Role table --}}
          <section class="dashboard-panel">
               <header class="panel-header">
                    <div class="panel-header-content d-flex justify-content-between align-items-center">
                         <div>
                              <h2 class="panel-title">
                                   Daftar Role
                              </h2>

                              <p class="panel-subtitle">
                                   Kelola role, pengguna, dan permission aplikasi.
                              </p>
                         </div>

                         <span class="data-count-badge">
                              <i class="bi bi-database me-2"></i>
                              {{ number_format($roles->total()) }} Data
                         </span>
                    </div>
               </header>

               <div class="table-responsive">
                    <table class="table role-table align-middle">
                         <thead>
                              <tr>
                                   <th scope="col">Role</th>
                                   <th scope="col">Guard</th>
                                   <th scope="col">Status</th>
                                   <th scope="col">User</th>
                                   <th scope="col">Permission</th>
                                   <th scope="col">Jenis</th>
                                   <th scope="col" class="text-center">Aksi</th>
                              </tr>
                         </thead>

                         <tbody>
                              @forelse ($roles as $role)
                                   @php
                                        $roleName = $role->name ?? '-';

                                        $displayName =
                                            $role->display_name ?: ucwords(str_replace(['_', '-'], ' ', $roleName));

                                        $roleInitial = mb_strtoupper(mb_substr($displayName, 0, 1));

                                        $roleStatus = $role->status ?? 'active';

                                        $isSystemRole = (bool) ($role->is_system ?? false);
                                   @endphp

                                   <tr>
                                        <td>
                                             <div class="d-flex align-items-center gap-3">
                                                  <div class="role-avatar">
                                                       {{ $roleInitial }}
                                                  </div>

                                                  <div>
                                                       <div class="role-name">
                                                            {{ $displayName }}
                                                       </div>

                                                       <small class="role-slug">
                                                            {{ $roleName }}
                                                       </small>

                                                       @if (!empty($role->description))
                                                            <small class="role-description"
                                                                 title="{{ $role->description }}">
                                                                 {{ $role->description }}
                                                            </small>
                                                       @endif
                                                  </div>
                                             </div>
                                        </td>

                                        <td>
                                             <span class="role-badge role-badge-guard">
                                                  <i class="bi bi-shield-check"></i>
                                                  {{ $role->guard_name ?? 'web' }}
                                             </span>
                                        </td>

                                        <td>
                                             @if ($roleStatus === 'active')
                                                  <span class="role-badge role-badge-active">
                                                       <i class="bi bi-check-circle-fill"></i>
                                                       Aktif
                                                  </span>
                                             @else
                                                  <span class="role-badge role-badge-inactive">
                                                       <i class="bi bi-dash-circle-fill"></i>
                                                       Nonaktif
                                                  </span>
                                             @endif
                                        </td>

                                        <td>
                                             <strong class="text-dark">
                                                  {{ number_format($role->users_count ?? 0) }}
                                             </strong>

                                             <br>

                                             <small class="text-muted">
                                                  Pengguna
                                             </small>
                                        </td>

                                        <td>
                                             <strong class="text-dark">
                                                  {{ number_format($role->permissions_count ?? 0) }}
                                             </strong>

                                             <br>

                                             <small class="text-muted">
                                                  Permission
                                             </small>
                                        </td>

                                        <td>
                                             @if ($isSystemRole)
                                                  <span class="role-badge role-badge-system">
                                                       <i class="bi bi-cpu-fill"></i>
                                                       System
                                                  </span>
                                             @else
                                                  <span class="role-badge role-badge-custom">
                                                       <i class="bi bi-person-gear"></i>
                                                       Custom
                                                  </span>
                                             @endif
                                        </td>

                                        <td>
                                             <div class="action-group">
                                                  <a href="{{ route('super-admin.roles.show', $role->id) }}"
                                                       class="btn btn-sm btn-outline-primary action-button"
                                                       title="Lihat detail role"
                                                       aria-label="Lihat detail role {{ $displayName }}">
                                                       <i class="bi bi-eye-fill"></i>
                                                  </a>

                                                  <a href="{{ route('super-admin.roles.edit', $role->id) }}"
                                                       class="btn btn-sm btn-outline-warning action-button"
                                                       title="Edit role" aria-label="Edit role {{ $displayName }}">
                                                       <i class="bi bi-pencil-fill"></i>
                                                  </a>

                                                  @if (!$isSystemRole)
                                                       <form method="POST"
                                                            action="{{ route('super-admin.roles.destroy', $role->id) }}"
                                                            class="action-form delete-role-form"
                                                            data-role-name="{{ $displayName }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                 class="btn btn-sm btn-outline-danger action-button"
                                                                 title="Hapus role"
                                                                 aria-label="Hapus role {{ $displayName }}">
                                                                 <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                       </form>
                                                  @else
                                                       <button type="button"
                                                            class="btn btn-sm btn-outline-secondary action-button"
                                                            title="System role tidak dapat dihapus"
                                                            aria-label="System role tidak dapat dihapus" disabled>
                                                            <i class="bi bi-lock-fill"></i>
                                                       </button>
                                                  @endif
                                             </div>
                                        </td>
                                   </tr>
                              @empty
                                   <tr>
                                        <td colspan="7" class="empty-state">
                                             <div class="empty-state-icon">
                                                  <i class="bi bi-shield-x"></i>
                                             </div>

                                             <h5>
                                                  Data role belum tersedia
                                             </h5>

                                             <p>
                                                  Tambahkan role baru untuk mulai mengatur
                                                  hak akses pengguna.
                                             </p>

                                             <a href="{{ route('super-admin.roles.create') }}" class="btn btn-primary">
                                                  <i class="bi bi-plus-circle me-1"></i>
                                                  Tambah Role
                                             </a>
                                        </td>
                                   </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>

               {{-- Pagination --}}
               @if ($roles->hasPages())
                    <div class="pagination-wrapper">
                         {{ $roles->withQueryString()->links() }}
                    </div>
               @endif
          </section>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const deleteForms = document.querySelectorAll('.delete-role-form');

               deleteForms.forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                         const roleName = form.dataset.roleName || 'ini';

                         const confirmed = window.confirm(
                              'Apakah Anda yakin ingin menghapus role "' +
                              roleName +
                              '"?\n\nTindakan ini tidak dapat dibatalkan.'
                         );

                         if (!confirmed) {
                              event.preventDefault();
                         }
                    });
               });
          });
     </script>
@endsection
