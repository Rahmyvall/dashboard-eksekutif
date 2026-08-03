@extends('layouts.app')

@section('title', 'Performance Period Management')

@section('content')
     <style>
          :root {
               --period-primary: #6366f1;
               --period-primary-dark: #4f46e5;
               --period-secondary: #06b6d4;
               --period-purple: #8b5cf6;
               --period-success: #10b981;
               --period-warning: #f59e0b;
               --period-danger: #ef4444;
               --period-info: #0ea5e9;
               --period-text: #24324a;
               --period-muted: #718096;
               --period-border: #e7eaf3;
               --period-white: #ffffff;
          }

          .period-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .period-container {
               max-width: 1680px;
               margin: 0 auto;
          }

          .period-hero {
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

          .period-hero::before {
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
               color: var(--period-primary-dark);
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .period-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.5vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .period-hero p {
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

          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--period-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--period-danger);
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

          .stat-completed {
               color: #0369a1;
               background: linear-gradient(135deg, #e0f2fe, #cffafe);
          }

          .stat-page {
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
               color: var(--period-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .filter-control {
               min-height: 47px;
               color: var(--period-text);
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
               background: linear-gradient(135deg, var(--period-primary), var(--period-purple), var(--period-secondary));
          }

          .btn-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .period-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .period-card-header {
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
               color: var(--period-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: var(--period-primary-dark);
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--period-muted);
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

          .period-card-body {
               padding: 10px 18px 20px;
          }

          .period-table {
               min-width: 1200px;
               margin-bottom: 0;
          }

          .period-table thead th {
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

          .period-table tbody td {
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
               color: var(--period-primary-dark);
               font-size: .77rem;
               font-weight: 820;
               border-radius: 11px;
               background: #eef2ff;
          }

          .period-name {
               display: block;
               color: #1e293b;
               font-size: .94rem;
               font-weight: 820;
          }

          .period-id {
               display: inline-flex;
               margin-top: 5px;
               padding: 4px 8px;
               color: #6d28d9;
               font-size: .7rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 8px;
               background: #f5f3ff;
          }

          .date-value {
               display: block;
               color: #475569;
               font-size: .82rem;
               font-weight: 760;
          }

          .date-caption {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: .7rem;
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

          .badge-draft {
               color: #a16207;
               border-color: #fde68a;
               background: #fffbeb;
          }

          .badge-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .badge-completed {
               color: #0369a1;
               border-color: #bae6fd;
               background: #f0f9ff;
          }

          .badge-inactive {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .badge-type {
               color: #1d4ed8;
               border-color: #bfdbfe;
               background: #eff6ff;
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

          .btn-delete {
               color: #be123c;
               background: #ffe4e6;
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
               color: var(--period-primary-dark);
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
               .period-card-header {
                    align-items: flex-start;
                    flex-direction: column;
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
               .period-page {
                    padding: 20px 12px 34px;
               }

               .period-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
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
          $periodCollection = $performancePeriods->getCollection();

          $activeOnPage = $periodCollection->where('status', 'active')->count();
          $completedOnPage = $periodCollection->where('status', 'completed')->count();

          $currentSearch = request('search', $search ?? '');
          $currentStatus = request('status', $status ?? '');
          $currentPeriodType = request('period_type', $periodType ?? '');
          $currentDate = request('date', $date ?? '');

          $hasActiveFilter =
              $currentSearch !== '' || $currentStatus !== '' || $currentPeriodType !== '' || $currentDate !== '';

          $typeLabels = [
              'monthly' => 'Bulanan',
              'quarterly' => 'Kuartalan',
              'semester' => 'Semester',
              'annual' => 'Tahunan',
          ];

          $statusLabels = [
              'draft' => 'Draft',
              'active' => 'Aktif',
              'completed' => 'Selesai',
              'inactive' => 'Tidak Aktif',
          ];

          $statusIcons = [
              'draft' => 'bi-pencil-square',
              'active' => 'bi-check-circle-fill',
              'completed' => 'bi-flag-fill',
              'inactive' => 'bi-x-circle-fill',
          ];
     @endphp

     <div class="period-page">
          <div class="period-container">
               <div class="period-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-calendar2-range-fill"></i>
                              </div>

                              <div>
                                   <h1>Performance Period Management</h1>
                                   <p>
                                        Kelola nama periode, rentang tanggal, jenis periode,
                                        dan status pelaksanaan penilaian kinerja karyawan.
                                   </p>
                              </div>
                         </div>

                         @if (Route::has('super-admin.performance-periods.create'))
                              <a href="{{ route('super-admin.performance-periods.create') }}" class="btn-hero">
                                   <i class="bi bi-calendar-plus-fill"></i>
                                   Tambah Periode
                              </a>
                         @endif
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

               <div class="row g-3 stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-total h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Hasil</div>
                                        <div class="stat-value">{{ $performancePeriods->total() }}</div>
                                        <div class="stat-caption">Periode sesuai filter</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-calendar3"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-active h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Aktif</div>
                                        <div class="stat-value">{{ $activeOnPage }}</div>
                                        <div class="stat-caption">Pada halaman ini</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-play-circle-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-completed h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Selesai</div>
                                        <div class="stat-value">{{ $completedOnPage }}</div>
                                        <div class="stat-caption">Pada halaman ini</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-flag-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-page h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Halaman</div>
                                        <div class="stat-value">{{ $performancePeriods->currentPage() }}</div>
                                        <div class="stat-caption">
                                             Dari {{ $performancePeriods->lastPage() }} halaman
                                        </div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-files"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="filter-card">
                    <div class="filter-title">
                         <i class="bi bi-funnel-fill"></i>
                         Pencarian dan Filter Periode
                    </div>

                    <form method="GET" action="{{ route('super-admin.performance-periods.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-lg-4">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Periode
                                   </label>

                                   <div class="search-shell">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="search" name="search"
                                             class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Nama, jenis, atau status periode..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="period_type" class="form-label fw-semibold small text-secondary">
                                        Jenis Periode
                                   </label>

                                   <select id="period_type" name="period_type" class="form-select filter-control">
                                        <option value="">Semua Jenis</option>

                                        @foreach ($periodTypes as $type)
                                             <option value="{{ $type }}" @selected($currentPeriodType === (string) $type)>
                                                  {{ $typeLabels[$type] ?? \Illuminate\Support\Str::of($type)->replace('_', ' ')->title() }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="status" class="form-label fw-semibold small text-secondary">
                                        Status
                                   </label>

                                   <select id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua Status</option>

                                        @foreach ($statuses as $statusOption)
                                             <option value="{{ $statusOption }}" @selected($currentStatus === (string) $statusOption)>
                                                  {{ $statusLabels[$statusOption] ?? \Illuminate\Support\Str::of($statusOption)->replace('_', ' ')->title() }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="date" class="form-label fw-semibold small text-secondary">
                                        Tanggal Cakupan
                                   </label>

                                   <input type="date" id="date" name="date" class="form-control filter-control"
                                        value="{{ $currentDate }}">
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <div class="filter-actions">
                                        <button type="submit" class="btn-filter">
                                             <i class="bi bi-search"></i>
                                             Terapkan
                                        </button>

                                        <a href="{{ route('super-admin.performance-periods.index') }}" class="btn-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               <div class="period-card">
                    <div class="period-card-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon">
                                        <i class="bi bi-list-ul"></i>
                                   </span>
                                   Daftar Periode Penilaian
                              </h5>

                              <p class="list-subtitle">
                                   Menampilkan nama, rentang tanggal, durasi, jenis, dan status periode.
                              </p>
                         </div>

                         <span class="result-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ $performancePeriods->total() }} data ditemukan
                         </span>
                    </div>

                    <div class="period-card-body">
                         <div class="table-responsive">
                              <table class="table period-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="5%">No</th>
                                             <th width="22%">Nama Periode</th>
                                             <th width="13%">Tanggal Mulai</th>
                                             <th width="13%">Tanggal Selesai</th>
                                             <th width="10%">Durasi</th>
                                             <th width="12%">Jenis</th>
                                             <th width="12%">Status</th>
                                             <th width="13%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($performancePeriods as $performancePeriod)
                                             @php
                                                  $type = strtolower((string) $performancePeriod->period_type);
                                                  $periodStatus = strtolower((string) $performancePeriod->status);

                                                  $duration =
                                                      $performancePeriod->start_date && $performancePeriod->end_date
                                                          ? $performancePeriod->start_date->diffInDays(
                                                                  $performancePeriod->end_date,
                                                              ) + 1
                                                          : null;
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $performancePeriods->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="period-name">{{ $performancePeriod->name }}</span>
                                                       <span class="period-id">ID #{{ $performancePeriod->id }}</span>
                                                  </td>

                                                  <td>
                                                       <span class="date-value">
                                                            {{ optional($performancePeriod->start_date)->format('d M Y') ?? '-' }}
                                                       </span>
                                                       <span class="date-caption">Mulai penilaian</span>
                                                  </td>

                                                  <td>
                                                       <span class="date-value">
                                                            {{ optional($performancePeriod->end_date)->format('d M Y') ?? '-' }}
                                                       </span>
                                                       <span class="date-caption">Akhir penilaian</span>
                                                  </td>

                                                  <td>
                                                       <span class="date-value">
                                                            {{ $duration !== null ? $duration . ' hari' : '-' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="custom-badge badge-type">
                                                            <i class="bi bi-calendar-event-fill"></i>
                                                            {{ $typeLabels[$type] ?? \Illuminate\Support\Str::of($type)->replace('_', ' ')->title() }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span
                                                            class="custom-badge badge-{{ in_array($periodStatus, ['draft', 'active', 'completed', 'inactive'], true) ? $periodStatus : 'inactive' }}">
                                                            <i
                                                                 class="bi {{ $statusIcons[$periodStatus] ?? 'bi-circle-fill' }}"></i>
                                                            {{ $statusLabels[$periodStatus] ?? \Illuminate\Support\Str::of($periodStatus)->replace('_', ' ')->title() }}
                                                       </span>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            @if (Route::has('super-admin.performance-periods.show'))
                                                                 <a href="{{ route('super-admin.performance-periods.show', $performancePeriod) }}"
                                                                      class="btn action-btn btn-view" title="Lihat detail"
                                                                      aria-label="Lihat detail {{ $performancePeriod->name }}">
                                                                      <i class="bi bi-eye-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.performance-periods.edit'))
                                                                 <a href="{{ route('super-admin.performance-periods.edit', $performancePeriod) }}"
                                                                      class="btn action-btn btn-edit" title="Edit periode"
                                                                      aria-label="Edit {{ $performancePeriod->name }}">
                                                                      <i class="bi bi-pencil-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.performance-periods.destroy'))
                                                                 <form action="{{ route('super-admin.performance-periods.destroy', $performancePeriod) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Yakin ingin menghapus periode penilaian ini?')">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="btn action-btn btn-delete"
                                                                           title="Hapus periode"
                                                                           aria-label="Hapus {{ $performancePeriod->name }}">
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
                                                            <i class="bi bi-calendar-x-fill"></i>
                                                       </div>

                                                       <h5>Data Periode Tidak Ditemukan</h5>

                                                       <p>
                                                            @if ($hasActiveFilter)
                                                                 Tidak ada periode yang sesuai dengan pencarian atau filter.
                                                            @else
                                                                 Tambahkan periode pertama untuk mulai mengelola penilaian
                                                                 kinerja.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilter)
                                                            <a href="{{ route('super-admin.performance-periods.index') }}"
                                                                 class="btn-reset">
                                                                 <i class="bi bi-arrow-counterclockwise"></i>
                                                                 Hapus Filter
                                                            </a>
                                                       @elseif (Route::has('super-admin.performance-periods.create'))
                                                            <a href="{{ route('super-admin.performance-periods.create') }}"
                                                                 class="btn-filter">
                                                                 <i class="bi bi-calendar-plus-fill"></i>
                                                                 Tambah Periode
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($performancePeriods->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan
                                        {{ $performancePeriods->firstItem() }}–{{ $performancePeriods->lastItem() }}
                                        dari {{ $performancePeriods->total() }} data
                                   </div>

                                   {{ $performancePeriods->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
