@extends('layouts.app')

@section('title', 'Performance Indicator Management')

@section('content')
     <style>
          :root {
               --indicator-primary: #6366f1;
               --indicator-primary-dark: #4f46e5;
               --indicator-secondary: #06b6d4;
               --indicator-purple: #8b5cf6;
               --indicator-success: #10b981;
               --indicator-warning: #f59e0b;
               --indicator-danger: #ef4444;
               --indicator-info: #0ea5e9;
               --indicator-text: #24324a;
               --indicator-muted: #718096;
               --indicator-border: #e7eaf3;
               --indicator-white: #ffffff;
          }

          .indicator-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .indicator-container {
               max-width: 1680px;
               margin: 0 auto;
          }

          .indicator-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .7);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 42%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .indicator-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .hero-title-wrap {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               color: var(--indicator-primary-dark);
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .indicator-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.5vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .indicator-hero p {
               max-width: 780px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .97rem;
               line-height: 1.7;
          }

          .btn-hero {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .88rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 14px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
               transition: .22s ease;
          }

          .btn-hero:hover {
               color: #312e81;
               background: #fff;
               transform: translateY(-2px);
          }


          .hero-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               align-items: center;
               justify-content: flex-end;
          }

          .btn-export-excel {
               color: #047857;
               background: #ecfdf5;
               border-color: #a7f3d0;
          }

          .btn-export-excel:hover {
               color: #065f46;
               background: #d1fae5;
          }

          .btn-export-pdf {
               color: #be123c;
               background: #fff1f2;
               border-color: #fecdd3;
          }

          .btn-export-pdf:hover {
               color: #9f1239;
               background: #ffe4e6;
          }

          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: flex-start;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--indicator-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--indicator-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .stats-row {
               margin-bottom: 22px;
          }

          .stat-card {
               position: relative;
               min-height: 138px;
               padding: 22px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .95);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: .23s ease;
          }

          .stat-card:hover {
               transform: translateY(-4px);
          }

          .stat-total {
               color: #4338ca;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .stat-active {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #ccfbf1);
          }

          .stat-inactive {
               color: #be123c;
               background: linear-gradient(135deg, #fff1f2, #ffe4e6);
          }

          .stat-weight {
               color: #b45309;
               background: linear-gradient(135deg, #fff7ed, #fef3c7);
          }

          .stat-card-inner {
               display: flex;
               align-items: center;
               justify-content: space-between;
          }

          .stat-title {
               margin-bottom: 7px;
               font-size: .74rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .stat-value {
               font-size: 2.25rem;
               font-weight: 850;
               line-height: 1;
          }

          .stat-caption {
               margin-top: 8px;
               font-size: .8rem;
               font-weight: 650;
               opacity: .72;
          }

          .stat-icon {
               display: inline-flex;
               width: 54px;
               height: 54px;
               align-items: center;
               justify-content: center;
               font-size: 1.42rem;
               border-radius: 17px;
               background: rgba(255, 255, 255, .72);
          }

          .filter-card {
               padding: 20px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 22px;
               background: rgba(255, 255, 255, .93);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
          }

          .filter-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 15px;
               color: var(--indicator-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .filter-control {
               min-height: 47px;
               color: var(--indicator-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #fff;
          }

          .search-shell {
               position: relative;
          }

          .search-shell i {
               position: absolute;
               top: 50%;
               left: 15px;
               color: #818cf8;
               transform: translateY(-50%);
          }

          .search-shell .form-control {
               padding-left: 42px;
          }

          .filter-actions {
               display: flex;
               gap: 10px;
               align-items: center;
          }

          .btn-filter,
          .btn-reset {
               display: inline-flex;
               min-height: 47px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .btn-filter {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, var(--indicator-primary), var(--indicator-purple), var(--indicator-secondary));
          }

          .btn-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .indicator-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .indicator-card-header {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--indicator-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: var(--indicator-primary-dark);
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--indicator-muted);
               font-size: .81rem;
          }

          .result-badge {
               display: inline-flex;
               padding: 8px 12px;
               gap: 7px;
               align-items: center;
               color: #6d28d9;
               font-size: .76rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .indicator-card-body {
               padding: 10px 18px 20px;
          }

          .indicator-table {
               min-width: 1320px;
               margin-bottom: 0;
          }

          .indicator-table thead th {
               padding: 15px 13px;
               color: #52627a;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               vertical-align: middle;
               border: 0;
               background: linear-gradient(180deg, #fafbff, #f2f5ff);
          }

          .indicator-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
          }

          .number-badge {
               display: inline-flex;
               width: 35px;
               height: 35px;
               align-items: center;
               justify-content: center;
               color: var(--indicator-primary-dark);
               font-size: .77rem;
               font-weight: 820;
               border-radius: 11px;
               background: #eef2ff;
          }

          .indicator-code {
               display: inline-flex;
               padding: 7px 10px;
               color: #5b21b6;
               font-size: .76rem;
               font-weight: 850;
               letter-spacing: .03em;
               border: 1px solid #ddd6fe;
               border-radius: 10px;
               background: #f5f3ff;
          }

          .indicator-name {
               display: block;
               color: #1e293b;
               font-size: .94rem;
               font-weight: 820;
          }

          .indicator-description {
               display: -webkit-box;
               max-width: 360px;
               margin-top: 5px;
               overflow: hidden;
               color: #94a3b8;
               font-size: .74rem;
               line-height: 1.45;
               -webkit-line-clamp: 2;
               -webkit-box-orient: vertical;
          }

          .unit-badge {
               display: inline-flex;
               min-width: 55px;
               padding: 7px 10px;
               align-items: center;
               justify-content: center;
               color: #1d4ed8;
               font-size: .75rem;
               font-weight: 800;
               border: 1px solid #bfdbfe;
               border-radius: 10px;
               background: #eff6ff;
          }

          .weight-value {
               display: inline-flex;
               padding: 7px 11px;
               align-items: center;
               color: #b45309;
               font-size: .78rem;
               font-weight: 850;
               border: 1px solid #fde68a;
               border-radius: 10px;
               background: #fffbeb;
          }

          .custom-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 6px;
               align-items: center;
               font-size: .73rem;
               font-weight: 780;
               white-space: nowrap;
               border: 1px solid transparent;
               border-radius: 999px;
          }

          .badge-increase {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .badge-decrease {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .badge-exact {
               color: #6d28d9;
               border-color: #ddd6fe;
               background: #f5f3ff;
          }

          .badge-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .badge-inactive {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .action-group {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               white-space: nowrap;
          }

          .action-btn {
               display: inline-flex;
               width: 38px;
               height: 38px;
               padding: 0;
               align-items: center;
               justify-content: center;
               border: 0;
               border-radius: 12px;
               transition: .21s ease;
          }

          .btn-view {
               color: #0369a1;
               background: #e0f2fe;
          }

          .btn-edit {
               color: #a16207;
               background: #fef3c7;
          }

          .btn-toggle {
               color: #5b21b6;
               background: #ede9fe;
          }

          .btn-delete {
               color: #be123c;
               background: #ffe4e6;
          }

          .action-btn:hover {
               transform: translateY(-2px);
               filter: brightness(.97);
          }

          .empty-state {
               padding: 65px 20px !important;
               text-align: center;
          }

          .empty-icon {
               display: inline-flex;
               width: 84px;
               height: 84px;
               margin-bottom: 16px;
               align-items: center;
               justify-content: center;
               color: var(--indicator-primary-dark);
               font-size: 2rem;
               border-radius: 25px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe, #fce7f3);
          }

          .pagination-wrapper {
               display: flex;
               padding: 18px 6px 2px;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               border-top: 1px solid #f1f5f9;
          }

          .pagination-info {
               color: #718096;
               font-size: .78rem;
               font-weight: 650;
          }

          @media (max-width: 991.98px) {

               .hero-content,
               .indicator-card-header {
                    align-items: flex-start;
                    flex-direction: column;
               }


               .hero-actions {
                    width: 100%;
                    justify-content: flex-start;
               }

               .filter-actions {
                    width: 100%;
               }

               .btn-filter,
               .btn-reset {
                    flex: 1;
               }
          }

          @media (max-width: 767.98px) {
               .hero-actions .btn-hero {
                    width: 100%;
               }

               .indicator-page {
                    padding: 20px 12px 34px;
               }

               .indicator-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .hero-title-wrap {
                    align-items: flex-start;
               }

               .filter-actions {
                    flex-direction: column;
               }

               .btn-filter,
               .btn-reset {
                    width: 100%;
               }

               .pagination-wrapper {
                    flex-direction: column;
                    justify-content: center;
               }
          }
     </style>

     @php
          $statistics = array_merge(
              [
                  'total' => 0,
                  'active' => 0,
                  'inactive' => 0,
                  'total_active_weight' => 0,
              ],
              $statistics ?? [],
          );

          $currentSearch = request('search', $filters['search'] ?? '');
          $currentStatus = request('status', $filters['status'] ?? '');
          $currentDirection = request('target_direction', $filters['target_direction'] ?? '');
          $currentSortBy = request('sort_by', $filters['sort_by'] ?? 'code');
          $currentSortDirection = request('sort_direction', $filters['sort_direction'] ?? 'asc');
          $currentPerPage = (string) request('per_page', $filters['per_page'] ?? 15);

          $hasActiveFilter =
              $currentSearch !== '' ||
              $currentStatus !== '' ||
              $currentDirection !== '' ||
              $currentSortBy !== 'code' ||
              $currentSortDirection !== 'asc' ||
              $currentPerPage !== '15';

          $directionLabels = $directionOptions ?? [
              'increase' => 'Semakin Besar Semakin Baik',
              'decrease' => 'Semakin Kecil Semakin Baik',
              'exact' => 'Harus Sesuai Target',
          ];

          $directionIcons = [
              'increase' => 'bi-graph-up-arrow',
              'decrease' => 'bi-graph-down-arrow',
              'exact' => 'bi-bullseye',
          ];

          $statusLabels = $statusOptions ?? [
              'active' => 'Aktif',
              'inactive' => 'Tidak Aktif',
          ];
     @endphp

     <div class="indicator-page">
          <div class="indicator-container">
               <div class="indicator-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-speedometer2"></i>
                              </div>

                              <div>
                                   <h1>Performance Indicator Management</h1>
                                   <p>
                                        Kelola kode indikator, nama indikator, satuan, bobot,
                                        arah target, dan status indikator kinerja perusahaan.
                                   </p>
                              </div>
                         </div>

                         <div class="hero-actions">
                              @if (Route::has('super-admin.performance-indicators.export-excel'))
                                   <a href="{{ route(
                                       'super-admin.performance-indicators.export-excel',
                                       request()->only(['search', 'status', 'target_direction', 'sort_by', 'sort_direction']),
                                   ) }}"
                                        class="btn-hero btn-export-excel" title="Unduh Excel sesuai filter">
                                        <i class="bi bi-file-earmark-excel-fill"></i>
                                        Excel
                                   </a>
                              @endif

                              @if (Route::has('super-admin.performance-indicators.export-pdf'))
                                   <a href="{{ route(
                                       'super-admin.performance-indicators.export-pdf',
                                       request()->only(['search', 'status', 'target_direction', 'sort_by', 'sort_direction']),
                                   ) }}"
                                        class="btn-hero btn-export-pdf" title="Unduh PDF sesuai filter">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                        PDF
                                   </a>
                              @endif

                              @if (Route::has('super-admin.performance-indicators.create'))
                                   <a href="{{ route('super-admin.performance-indicators.create') }}" class="btn-hero">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        Tambah Indikator
                                   </a>
                              @endif
                         </div>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               @if ($errors->any())
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-octagon-fill"></i>
                         <div>
                              <strong>Data belum dapat diproses.</strong>
                              <ul class="mb-0 mt-2">
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    </div>
               @endif

               <div class="row g-3 stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-total h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Indikator</div>
                                        <div class="stat-value">{{ number_format((int) $statistics['total'], 0, ',', '.') }}
                                        </div>
                                        <div class="stat-caption">Seluruh data indikator</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-bar-chart-line-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-active h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Indikator Aktif</div>
                                        <div class="stat-value">{{ number_format((int) $statistics['active'], 0, ',', '.') }}
                                        </div>
                                        <div class="stat-caption">Dapat digunakan dalam penilaian</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-inactive h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Tidak Aktif</div>
                                        <div class="stat-value">
                                             {{ number_format((int) $statistics['inactive'], 0, ',', '.') }}</div>
                                        <div class="stat-caption">Tidak digunakan sementara</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-x-circle-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-weight h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Bobot Aktif</div>
                                        <div class="stat-value">
                                             {{ number_format((float) $statistics['total_active_weight'], 2, ',', '.') }}%
                                        </div>
                                        <div class="stat-caption">
                                             {{ (float) $statistics['total_active_weight'] === 100.0
                                                 ? 'Bobot aktif sudah tepat 100%'
                                                 : 'Idealnya total bobot aktif 100%' }}
                                        </div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-percent"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="filter-card">
                    <div class="filter-title">
                         <i class="bi bi-funnel-fill"></i>
                         Pencarian, Filter, dan Pengurutan Indikator
                    </div>

                    <form method="GET" action="{{ route('super-admin.performance-indicators.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-xl-3">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Indikator
                                   </label>

                                   <div class="search-shell">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="search" name="search"
                                             class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Kode, nama, deskripsi, atau satuan..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label for="target_direction" class="form-label fw-semibold small text-secondary">
                                        Arah Target
                                   </label>

                                   <select id="target_direction" name="target_direction" class="form-select filter-control">
                                        <option value="">Semua Arah</option>

                                        @foreach ($directionLabels as $value => $label)
                                             <option value="{{ $value }}" @selected($currentDirection === (string) $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-xl-1">
                                   <label for="status" class="form-label fw-semibold small text-secondary">
                                        Status
                                   </label>

                                   <select id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua</option>

                                        @foreach ($statusLabels as $value => $label)
                                             <option value="{{ $value }}" @selected($currentStatus === (string) $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label for="sort_by" class="form-label fw-semibold small text-secondary">
                                        Urutkan Berdasarkan
                                   </label>

                                   <select id="sort_by" name="sort_by" class="form-select filter-control">
                                        <option value="code" @selected($currentSortBy === 'code')>Kode</option>
                                        <option value="name" @selected($currentSortBy === 'name')>Nama</option>
                                        <option value="unit" @selected($currentSortBy === 'unit')>Satuan</option>
                                        <option value="weight" @selected($currentSortBy === 'weight')>Bobot</option>
                                        <option value="target_direction" @selected($currentSortBy === 'target_direction')>Arah Target</option>
                                        <option value="status" @selected($currentSortBy === 'status')>Status</option>
                                        <option value="created_at" @selected($currentSortBy === 'created_at')>Tanggal Dibuat</option>
                                        <option value="updated_at" @selected($currentSortBy === 'updated_at')>Terakhir Diperbarui</option>
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-xl-1">
                                   <label for="sort_direction" class="form-label fw-semibold small text-secondary">
                                        Arah Urut
                                   </label>

                                   <select id="sort_direction" name="sort_direction" class="form-select filter-control">
                                        <option value="asc" @selected($currentSortDirection === 'asc')>Naik</option>
                                        <option value="desc" @selected($currentSortDirection === 'desc')>Turun</option>
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-xl-1">
                                   <label for="per_page" class="form-label fw-semibold small text-secondary">
                                        Per Halaman
                                   </label>

                                   <select id="per_page" name="per_page" class="form-select filter-control">
                                        @foreach ([10, 15, 25, 50, 100] as $size)
                                             <option value="{{ $size }}" @selected($currentPerPage === (string) $size)>
                                                  {{ $size }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-xl-2">
                                   <div class="filter-actions">
                                        <button type="submit" class="btn-filter">
                                             <i class="bi bi-search"></i>
                                             Terapkan
                                        </button>

                                        <a href="{{ route('super-admin.performance-indicators.index') }}"
                                             class="btn-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               <div class="indicator-card">
                    <div class="indicator-card-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon">
                                        <i class="bi bi-list-check"></i>
                                   </span>
                                   Daftar Performance Indicator
                              </h5>

                              <p class="list-subtitle">
                                   Menampilkan kode, nama, satuan, bobot, arah target, dan status indikator.
                              </p>
                         </div>

                         <span class="result-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ number_format($performanceIndicators->total(), 0, ',', '.') }} data ditemukan
                         </span>
                    </div>

                    <div class="indicator-card-body">
                         <div class="table-responsive">
                              <table class="table indicator-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="5%">No</th>
                                             <th width="10%">Kode</th>
                                             <th width="25%">Nama Indikator</th>
                                             <th width="8%">Satuan</th>
                                             <th width="9%">Bobot</th>
                                             <th width="18%">Arah Target</th>
                                             <th width="10%">Status</th>
                                             <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($performanceIndicators as $performanceIndicator)
                                             @php
                                                  $direction = strtolower(
                                                      (string) $performanceIndicator->target_direction,
                                                  );
                                                  $indicatorStatus = strtolower((string) $performanceIndicator->status);
                                                  $directionClass = in_array(
                                                      $direction,
                                                      ['increase', 'decrease', 'exact'],
                                                      true,
                                                  )
                                                      ? $direction
                                                      : 'exact';
                                                  $statusClass = in_array(
                                                      $indicatorStatus,
                                                      ['active', 'inactive'],
                                                      true,
                                                  )
                                                      ? $indicatorStatus
                                                      : 'inactive';
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $performanceIndicators->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="indicator-code">
                                                            {{ $performanceIndicator->code }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="indicator-name">
                                                            {{ $performanceIndicator->name }}
                                                       </span>

                                                       <span class="indicator-description">
                                                            {{ $performanceIndicator->description ?: 'Tidak ada deskripsi indikator.' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="unit-badge">
                                                            {{ $performanceIndicator->unit }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="weight-value">
                                                            {{ number_format((float) $performanceIndicator->weight, 2, ',', '.') }}%
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="custom-badge badge-{{ $directionClass }}">
                                                            <i
                                                                 class="bi {{ $directionIcons[$direction] ?? 'bi-bullseye' }}"></i>
                                                            {{ $directionLabels[$direction] ?? \Illuminate\Support\Str::of($direction)->replace('_', ' ')->title() }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="custom-badge badge-{{ $statusClass }}">
                                                            <i
                                                                 class="bi {{ $indicatorStatus === 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                                            {{ $statusLabels[$indicatorStatus] ?? \Illuminate\Support\Str::of($indicatorStatus)->replace('_', ' ')->title() }}
                                                       </span>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            @if (Route::has('super-admin.performance-indicators.show'))
                                                                 <a href="{{ route('super-admin.performance-indicators.show', $performanceIndicator) }}"
                                                                      class="btn action-btn btn-view" title="Lihat detail"
                                                                      aria-label="Lihat detail {{ $performanceIndicator->name }}">
                                                                      <i class="bi bi-eye-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.performance-indicators.edit'))
                                                                 <a href="{{ route('super-admin.performance-indicators.edit', $performanceIndicator) }}"
                                                                      class="btn action-btn btn-edit"
                                                                      title="Edit indikator"
                                                                      aria-label="Edit {{ $performanceIndicator->name }}">
                                                                      <i class="bi bi-pencil-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.performance-indicators.toggle-status'))
                                                                 <form action="{{ route('super-admin.performance-indicators.toggle-status', $performanceIndicator) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Yakin ingin mengubah status indikator ini?')">
                                                                      @csrf
                                                                      @method('PATCH')

                                                                      <button type="submit"
                                                                           class="btn action-btn btn-toggle"
                                                                           title="Ubah status"
                                                                           aria-label="Ubah status {{ $performanceIndicator->name }}">
                                                                           <i
                                                                                class="bi {{ $performanceIndicator->isActive() ? 'bi-pause-circle-fill' : 'bi-play-circle-fill' }}"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif

                                                            @if (Route::has('super-admin.performance-indicators.destroy'))
                                                                 <form action="{{ route('super-admin.performance-indicators.destroy', $performanceIndicator) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Data indikator akan dihapus permanen. Lanjutkan?')">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="btn action-btn btn-delete"
                                                                           title="Hapus indikator"
                                                                           aria-label="Hapus {{ $performanceIndicator->name }}">
                                                                           <i class="bi bi-trash3-fill"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="8" class="empty-state">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-clipboard2-x-fill"></i>
                                                       </div>

                                                       <h5>Data Indikator Tidak Ditemukan</h5>

                                                       <p>
                                                            @if ($hasActiveFilter)
                                                                 Tidak ada indikator yang sesuai dengan pencarian atau
                                                                 filter.
                                                            @else
                                                                 Tambahkan indikator pertama untuk mulai mengelola penilaian
                                                                 kinerja.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilter)
                                                            <a href="{{ route('super-admin.performance-indicators.index') }}"
                                                                 class="btn-reset">
                                                                 <i class="bi bi-arrow-counterclockwise"></i>
                                                                 Hapus Filter
                                                            </a>
                                                       @elseif (Route::has('super-admin.performance-indicators.create'))
                                                            <a href="{{ route('super-admin.performance-indicators.create') }}"
                                                                 class="btn-filter">
                                                                 <i class="bi bi-plus-circle-fill"></i>
                                                                 Tambah Indikator
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($performanceIndicators->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan
                                        {{ $performanceIndicators->firstItem() }}–{{ $performanceIndicators->lastItem() }}
                                        dari {{ number_format($performanceIndicators->total(), 0, ',', '.') }} data
                                   </div>

                                   {{ $performanceIndicators->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
