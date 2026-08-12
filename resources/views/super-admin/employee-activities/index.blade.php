@extends('layouts.app')

@section('title', 'Manajemen Aktivitas Pegawai')

@section('content')
     @php
          $search = isset($search) ? trim((string) $search) : trim((string) request('search', ''));
          $status = isset($status) ? (string) $status : (string) request('status', '');
          $employeeId = isset($employeeId) ? $employeeId : request('employee_id');
          $serviceOrderId = isset($serviceOrderId) ? $serviceOrderId : request('service_order_id');
          $dateStart = isset($dateStart) ? (string) $dateStart : (string) request('date_start', '');
          $dateEnd = isset($dateEnd) ? (string) $dateEnd : (string) request('date_end', '');
          $printMode = isset($printMode) ? (bool) $printMode : false;
          $printedAt = $printedAt ?? now();
          $collection = method_exists($employeeActivities, 'getCollection')
              ? $employeeActivities->getCollection()
              : collect($employeeActivities);
          $filteredTotal = method_exists($employeeActivities, 'total')
              ? $employeeActivities->total()
              : $collection->count();
          $pageCount = $collection->count();
          $verifiedCount = $collection->filter(fn($item) => $item->isVerified())->count();
          $totalDuration = (int) $collection->sum('duration_minutes');
          $hasActiveFilters =
              $search !== '' ||
              $status !== '' ||
              !empty($employeeId) ||
              !empty($serviceOrderId) ||
              $dateStart !== '' ||
              $dateEnd !== '';
          $statusLabels = [
              \App\Models\EmployeeActivity::STATUS_SUBMITTED => 'Submitted',
              \App\Models\EmployeeActivity::STATUS_VERIFIED => 'Verified',
              \App\Models\EmployeeActivity::STATUS_REJECTED => 'Rejected',
          ];
          $printPeriodLabel =
              $dateStart !== '' || $dateEnd !== ''
                  ? ($dateStart !== '' ? $dateStart : '...') . ' s/d ' . ($dateEnd !== '' ? $dateEnd : '...')
                  : 'Semua periode';
     @endphp

     <style>
          .ea-index-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 42px;
               background: radial-gradient(circle at 7% 8%, rgba(20, 184, 166, .14), transparent 24%), radial-gradient(circle at 94% 7%, rgba(37, 99, 235, .12), transparent 26%), linear-gradient(145deg, #f9fcff 0%, #f7fbff 48%, #f1f8ff 100%);
          }

          .ea-index-container {
               max-width: 1580px;
               margin: 0 auto;
          }

          .ea-index-hero {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 30px;
               margin-bottom: 20px;
               color: #fff;
               border-radius: 28px;
               background: linear-gradient(125deg, #0f766e 0%, #0284c7 46%, #2563eb 100%);
               box-shadow: 0 22px 50px rgba(14, 116, 144, .22);
          }

          .ea-index-hero-title {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .ea-index-hero-icon {
               display: inline-flex;
               width: 64px;
               height: 64px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               border-radius: 19px;
               background: rgba(255, 255, 255, .95);
          }

          .ea-index-hero-icon svg {
               width: 29px;
               height: 29px;
          }

          .ea-index-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.6vw, 2.35rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .ea-index-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .92);
          }

          .ea-index-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
          }

          .ea-index-btn,
          .ea-index-btn-soft {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               text-decoration: none;
               font-weight: 800;
               border-radius: 13px;
          }

          .ea-index-btn {
               color: #0f766e;
               background: #fff;
          }

          .ea-index-btn-soft {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .35);
               background: rgba(255, 255, 255, .12);
          }

          .ea-index-stats {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 18px;
          }

          .ea-stat {
               padding: 16px;
               border: 1px solid #e5ecf6;
               border-radius: 18px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 30px rgba(51, 65, 85, .06);
          }

          .ea-stat-label {
               color: #6b7a90;
               font-size: .7rem;
               font-weight: 850;
               text-transform: uppercase;
               letter-spacing: .07em;
               margin-bottom: 7px;
          }

          .ea-stat-value {
               color: #24324a;
               font-size: 1.45rem;
               font-weight: 850;
               letter-spacing: -.03em;
          }

          .ea-card {
               overflow: hidden;
               border: 1px solid #e5ecf6;
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 40px rgba(51, 65, 85, .08);
          }

          .ea-card-head {
               padding: 20px 24px;
               border-bottom: 1px solid #edf2f7;
               background: linear-gradient(90deg, #ffffff, #f7fbff);
          }

          .ea-card-title {
               margin: 0;
               color: #24324a;
               font-size: 1rem;
               font-weight: 840;
          }

          .ea-card-subtitle {
               margin: 5px 0 0;
               color: #6b7a90;
               font-size: .81rem;
          }

          .ea-card-body {
               padding: 18px;
          }

          .ea-filter-grid {
               display: grid;
               grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
               gap: 10px;
               align-items: end;
          }

          .ea-label {
               display: block;
               margin-bottom: 6px;
               color: #52627a;
               font-size: .76rem;
               font-weight: 800;
          }

          .ea-control {
               min-height: 45px;
               border: 1px solid #dbe5f1;
               border-radius: 12px;
          }

          .ea-filter-actions {
               display: flex;
               gap: 8px;
          }

          .ea-filter-btn,
          .ea-reset-btn {
               display: inline-flex;
               min-height: 45px;
               padding: 10px 15px;
               align-items: center;
               justify-content: center;
               gap: 7px;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 12px;
          }

          .ea-filter-btn {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #0f766e, #0284c7);
          }

          .ea-reset-btn {
               color: #475569;
               border: 1px solid #dbe5f1;
               background: #fff;
          }

          .ea-chip-row {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               margin-top: 14px;
          }

          .ea-chip {
               display: inline-flex;
               min-height: 30px;
               padding: 5px 10px;
               align-items: center;
               gap: 6px;
               color: #0f766e;
               font-size: .74rem;
               font-weight: 800;
               border: 1px solid #bfdbfe;
               border-radius: 999px;
               background: #f0f9ff;
          }

          .ea-table-shell {
               overflow: hidden;
               border: 1px solid #edf2f7;
               border-radius: 20px;
               background: linear-gradient(180deg, rgba(255, 255, 255, .99), rgba(248, 251, 255, .95));
          }

          .ea-table {
               min-width: 1220px;
               margin: 0;
          }

          .ea-table thead th {
               padding: 14px 12px;
               color: #52627a;
               font-size: .69rem;
               font-weight: 850;
               letter-spacing: .07em;
               text-transform: uppercase;
               border-bottom: 1px solid #e8edf4;
               background: #fbfcff;
          }

          .ea-table tbody td {
               padding: 15px 12px;
               color: #41506a;
               font-size: .84rem;
               vertical-align: middle;
               border-color: #eef2f7;
          }

          .ea-table tbody tr:nth-child(even):not(:hover) {
               background: rgba(248, 250, 252, .58);
          }

          .ea-table tbody tr:hover {
               background: linear-gradient(90deg, #fbfdff, #fafcff);
          }

          .ea-name {
               color: #24324a;
               font-weight: 820;
          }

          .ea-sub {
               margin-top: 4px;
               color: #8a98ab;
               font-size: .73rem;
          }

          .ea-badge {
               display: inline-flex;
               padding: 6px 10px;
               border-radius: 999px;
               font-size: .72rem;
               font-weight: 850;
          }

          .ea-badge.submitted {
               color: #9a3412;
               border: 1px solid #fed7aa;
               background: #fff7ed;
          }

          .ea-badge.verified {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .ea-badge.rejected {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .ea-actions {
               display: flex;
               gap: 7px;
               justify-content: flex-end;
               flex-wrap: nowrap;
          }

          .ea-action-btn {
               display: inline-flex;
               width: 36px;
               height: 36px;
               align-items: center;
               justify-content: center;
               border-radius: 10px;
               text-decoration: none;
          }

          .ea-action-btn svg {
               width: 15px;
               height: 15px;
          }

          .ea-action-show {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .ea-action-edit {
               color: #a16207;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .ea-action-delete {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .ea-action-verify {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .ea-pagination {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               padding: 16px 8px 2px;
               border-top: 1px solid #eef2f7;
          }

          .ea-pagination-info {
               color: #6b7a90;
               font-size: .78rem;
          }

          .ea-empty {
               padding: 60px 18px !important;
               text-align: center;
          }

          .ea-empty-icon {
               display: inline-flex;
               width: 74px;
               height: 74px;
               margin-bottom: 15px;
               align-items: center;
               justify-content: center;
               color: #0284c7;
               border: 1px solid #bfdbfe;
               border-radius: 22px;
               background: linear-gradient(135deg, #eff6ff, #ecfeff);
          }

          .ea-empty-icon svg {
               width: 30px;
               height: 30px;
          }

          .ea-print-only {
               display: none;
          }

          .ea-print-head {
               margin-bottom: 10px;
               border: 1px solid #cbd5e1;
               border-radius: 12px;
               background: #fff;
          }

          .ea-print-brand {
               display: flex;
               justify-content: space-between;
               gap: 14px;
               padding: 10px 12px;
               border-bottom: 2px solid #0f172a;
          }

          .ea-print-brand h1 {
               margin: 0;
               color: #0f172a;
               font-size: 1.1rem;
               font-weight: 850;
               letter-spacing: .02em;
          }

          .ea-print-brand p {
               margin: 2px 0 0;
               color: #334155;
               font-size: .73rem;
          }

          .ea-print-doc {
               text-align: right;
               font-size: .72rem;
               color: #475569;
          }

          .ea-print-meta {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 0;
               border-top: 1px solid #e2e8f0;
          }

          .ea-print-meta-item {
               padding: 8px 10px;
               border-right: 1px solid #e2e8f0;
          }

          .ea-print-meta-item:last-child {
               border-right: 0;
          }

          .ea-print-meta-label {
               display: block;
               color: #64748b;
               font-size: .62rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .06em;
          }

          .ea-print-meta-value {
               display: block;
               margin-top: 2px;
               color: #0f172a;
               font-size: .76rem;
               font-weight: 700;
          }

          .ea-print-head p {
               color: #475569;
               font-size: .72rem;
          }

          @media (max-width: 1200px) {
               .ea-filter-grid {
                    grid-template-columns: 1fr 1fr 1fr;
               }

               .ea-filter-actions {
                    grid-column: 1 / -1;
               }

               .ea-index-stats {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 991px) {
               .ea-index-hero {
                    flex-direction: column;
                    align-items: flex-start;
               }
          }

          @media (max-width: 575px) {
               .ea-index-page {
                    padding: 18px 12px 34px;
               }

               .ea-index-stats,
               .ea-filter-grid {
                    grid-template-columns: 1fr;
               }

               .ea-filter-actions,
               .ea-index-actions {
                    width: 100%;
                    display: grid;
               }

               .ea-index-btn,
               .ea-index-btn-soft,
               .ea-filter-btn,
               .ea-reset-btn {
                    width: 100%;
               }
          }

          @media print {
               @page {
                    size: A4 landscape;
                    margin: 8mm;
               }

               html,
               body {
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #fff !important;
               }

               body * {
                    visibility: hidden !important;
               }

               .ea-print-area,
               .ea-print-area * {
                    visibility: visible !important;
               }

               .ea-print-area {
                    position: absolute !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #fff !important;
                    box-shadow: none !important;
                    border: 0 !important;
               }

               .ea-index-hero,
               .ea-index-stats,
               .ea-filter-card,
               .ea-pagination,
               .ea-no-print,
               .ea-no-print-col,
               .ea-card-head {
                    display: none !important;
               }

               .ea-print-only {
                    display: block !important;
               }

               .ea-card,
               .ea-card-body,
               .ea-table-shell {
                    border: 0 !important;
                    border-radius: 0 !important;
                    box-shadow: none !important;
                    background: #fff !important;
               }

               .table-responsive {
                    overflow: visible !important;
               }

               .ea-table {
                    width: 100% !important;
                    min-width: 0 !important;
               }

               .ea-table thead th,
               .ea-table tbody td {
                    padding: 5px 4px !important;
                    color: #111827 !important;
                    font-size: 8px !important;
                    border: 1px solid #cbd5e1 !important;
                    background: #fff !important;
               }

               .ea-table thead th {
                    text-align: center;
                    background: #f1f5f9 !important;
               }

               .ea-name {
                    font-size: 8.2px !important;
                    font-weight: 700 !important;
                    color: #0f172a !important;
               }

               .ea-sub {
                    margin-top: 2px !important;
                    color: #475569 !important;
                    font-size: 7.5px !important;
               }

               .ea-badge {
                    padding: 3px 6px !important;
                    font-size: 7.3px !important;
                    border-width: 1px !important;
               }

               .ea-print-head {
                    break-inside: avoid;
                    page-break-inside: avoid;
               }

               .ea-table tr {
                    break-inside: avoid;
                    page-break-inside: avoid;
               }

               .ea-print-meta {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }
     </style>

     <div class="ea-index-page {{ $printMode ? 'ea-print-area' : '' }}">
          <div class="ea-index-container">
               <section class="ea-index-hero ea-no-print">
                    <div class="ea-index-hero-title">
                         <span class="ea-index-hero-icon"><i data-feather="clipboard"></i></span>
                         <div>
                              <h1>Manajemen Aktivitas Pegawai</h1>
                              <p>Monitor aktivitas operasional pegawai, keterkaitan service order, dan status verifikasi
                                   dalam satu tampilan kerja.</p>
                         </div>
                    </div>
                    <div class="ea-index-actions">
                         <a href="{{ route('super-admin.employee-activities.print', request()->only(['search', 'status', 'employee_id', 'service_order_id', 'date_start', 'date_end'])) }}"
                              class="ea-index-btn-soft" target="_blank" rel="noopener"><i data-feather="printer"></i> Cetak
                              Data</a>
                         <a href="{{ route('super-admin.employee-activities.create') }}" class="ea-index-btn"><i
                                   data-feather="plus"></i> Tambah Aktivitas</a>
                    </div>
               </section>

               <section class="ea-index-stats ea-no-print">
                    <article class="ea-stat">
                         <div class="ea-stat-label">Total Hasil</div>
                         <div class="ea-stat-value">{{ number_format($filteredTotal) }}</div>
                    </article>
                    <article class="ea-stat">
                         <div class="ea-stat-label">Data Halaman</div>
                         <div class="ea-stat-value">{{ number_format($pageCount) }}</div>
                    </article>
                    <article class="ea-stat">
                         <div class="ea-stat-label">Terverifikasi</div>
                         <div class="ea-stat-value">{{ number_format($verifiedCount) }}</div>
                    </article>
                    <article class="ea-stat">
                         <div class="ea-stat-label">Durasi Total</div>
                         <div class="ea-stat-value">{{ number_format($totalDuration) }} mnt</div>
                    </article>
               </section>

               <section class="ea-card ea-filter-card ea-no-print" style="margin-bottom:18px;">
                    <div class="ea-card-head">
                         <h2 class="ea-card-title">Filter Aktivitas</h2>
                         <p class="ea-card-subtitle">Saring berdasarkan kata kunci, pegawai, service order, status, dan
                              rentang tanggal.</p>
                    </div>
                    <div class="ea-card-body">
                         <form method="GET" action="{{ route('super-admin.employee-activities.index') }}">
                              <div class="ea-filter-grid">
                                   <div>
                                        <label for="ea-search" class="ea-label">Pencarian</label>
                                        <input type="search" id="ea-search" name="search" value="{{ $search }}"
                                             class="form-control ea-control"
                                             placeholder="Aktivitas, pegawai, service order...">
                                   </div>
                                   <div>
                                        <label for="ea-employee" class="ea-label">Pegawai</label>
                                        <select id="ea-employee" name="employee_id" class="form-select ea-control">
                                             <option value="">Semua pegawai</option>
                                             @foreach ($employees as $employee)
                                                  <option value="{{ $employee->id }}" @selected((string) $employeeId === (string) $employee->id)>
                                                       {{ $employee->full_name ?? 'Tanpa nama' }}</option>
                                             @endforeach
                                        </select>
                                   </div>
                                   <div>
                                        <label for="ea-service-order" class="ea-label">Service Order</label>
                                        <select id="ea-service-order" name="service_order_id" class="form-select ea-control">
                                             <option value="">Semua SO</option>
                                             @foreach ($serviceOrders as $serviceOrder)
                                                  <option value="{{ $serviceOrder->id }}" @selected((string) $serviceOrderId === (string) $serviceOrder->id)>
                                                       {{ $serviceOrder->order_number ?? 'SO #' . $serviceOrder->id }}
                                                  </option>
                                             @endforeach
                                        </select>
                                   </div>
                                   <div>
                                        <label for="ea-status" class="ea-label">Status</label>
                                        <select id="ea-status" name="status" class="form-select ea-control">
                                             <option value="">Semua status</option>
                                             @foreach ($statuses as $statusOption)
                                                  <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                                                       {{ $statusLabels[$statusOption] ?? ucfirst($statusOption) }}</option>
                                             @endforeach
                                        </select>
                                   </div>
                                   <div>
                                        <label for="ea-date-start" class="ea-label">Mulai</label>
                                        <input type="date" id="ea-date-start" name="date_start"
                                             value="{{ $dateStart }}" class="form-control ea-control">
                                   </div>
                                   <div>
                                        <label for="ea-date-end" class="ea-label">Selesai</label>
                                        <input type="date" id="ea-date-end" name="date_end" value="{{ $dateEnd }}"
                                             class="form-control ea-control">
                                   </div>
                                   <div class="ea-filter-actions">
                                        <button type="submit" class="ea-filter-btn">Terapkan</button>
                                        <a href="{{ route('super-admin.employee-activities.index') }}"
                                             class="ea-reset-btn">Reset</a>
                                   </div>
                              </div>

                              @if ($hasActiveFilters)
                                   <div class="ea-chip-row">
                                        @if ($search !== '')
                                             <span class="ea-chip">Cari: {{ $search }}</span>
                                        @endif
                                        @if ($status !== '')
                                             <span class="ea-chip">Status:
                                                  {{ $statusLabels[$status] ?? ucfirst($status) }}</span>
                                        @endif
                                        @if (!empty($dateStart) || !empty($dateEnd))
                                             <span class="ea-chip">Periode: {{ $dateStart ?: '...' }} -
                                                  {{ $dateEnd ?: '...' }}</span>
                                        @endif
                                   </div>
                              @endif
                         </form>
                    </div>
               </section>

               <section class="ea-card ea-print-area">
                    <div class="ea-card-body">
                         @if ($printMode)
                              <div class="ea-print-head ea-print-only">
                                   <div class="ea-print-brand">
                                        <div>
                                             <h1>{{ strtoupper(config('app.name', 'Dashboard Eksekutif')) }}</h1>
                                             <p>Laporan Daftar Aktivitas Pegawai</p>
                                        </div>
                                        <div class="ea-print-doc">
                                             <p>Dicetak:
                                                  {{ $printedAt instanceof \DateTimeInterface ? $printedAt->format('d-m-Y H:i') : $printedAt }}
                                                  WIB</p>
                                             <p>Dokumen internal operasional</p>
                                        </div>
                                   </div>
                                   <div class="ea-print-meta">
                                        <div class="ea-print-meta-item">
                                             <span class="ea-print-meta-label">Periode</span>
                                             <span class="ea-print-meta-value">{{ $printPeriodLabel }}</span>
                                        </div>
                                        <div class="ea-print-meta-item">
                                             <span class="ea-print-meta-label">Status</span>
                                             <span
                                                  class="ea-print-meta-value">{{ $status !== '' ? $statusLabels[$status] ?? ucfirst($status) : 'Semua status' }}</span>
                                        </div>
                                        <div class="ea-print-meta-item">
                                             <span class="ea-print-meta-label">Total Data</span>
                                             <span class="ea-print-meta-value">{{ number_format($filteredTotal) }}
                                                  aktivitas</span>
                                        </div>
                                        <div class="ea-print-meta-item">
                                             <span class="ea-print-meta-label">Total Durasi</span>
                                             <span class="ea-print-meta-value">{{ number_format($totalDuration) }}
                                                  menit</span>
                                        </div>
                                   </div>
                              </div>
                         @endif

                         <div class="ea-table-shell">
                              <div class="table-responsive">
                                   <table class="table ea-table">
                                        <thead>
                                             <tr>
                                                  <th style="width:72px;">No.</th>
                                                  <th>Pegawai</th>
                                                  <th>Aktivitas</th>
                                                  <th>Service Order</th>
                                                  <th>Tanggal</th>
                                                  <th>Waktu</th>
                                                  <th>Durasi</th>
                                                  <th>Status</th>
                                                  <th class="ea-no-print-col text-end">Aksi</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             @forelse ($employeeActivities as $employeeActivity)
                                                  @php
                                                       $statusClass = match ($employeeActivity->status) {
                                                           \App\Models\EmployeeActivity::STATUS_VERIFIED => 'verified',
                                                           \App\Models\EmployeeActivity::STATUS_REJECTED => 'rejected',
                                                           default => 'submitted',
                                                       };
                                                  @endphp
                                                  <tr>
                                                       <td>{{ method_exists($employeeActivities, 'firstItem') ? ($employeeActivities->firstItem() ?? 1) + $loop->index : $loop->iteration }}
                                                       </td>
                                                       <td>
                                                            <div class="ea-name">
                                                                 {{ $employeeActivity->employee?->full_name ?? '-' }}</div>
                                                            <div class="ea-sub">
                                                                 {{ $employeeActivity->employee?->employee_number ?? 'Tanpa nomor pegawai' }}
                                                            </div>
                                                       </td>
                                                       <td>
                                                            <div class="ea-name">{{ $employeeActivity->activity_name }}
                                                            </div>
                                                            <div class="ea-sub">
                                                                 {{ \Illuminate\Support\Str::limit($employeeActivity->description ?: 'Tanpa deskripsi', 62) }}
                                                            </div>
                                                       </td>
                                                       <td>
                                                            <div class="ea-name">
                                                                 {{ $employeeActivity->serviceOrder?->order_number ?? '-' }}
                                                            </div>
                                                            <div class="ea-sub">
                                                                 {{ $employeeActivity->serviceOrder?->customer?->company_name ?? ($employeeActivity->serviceOrder?->customer?->name ?? '-') }}
                                                            </div>
                                                       </td>
                                                       <td>{{ optional($employeeActivity->activity_date)->format('d/m/Y') ?? '-' }}
                                                       </td>
                                                       <td>{{ $employeeActivity->getTimeRangeLabel() }}</td>
                                                       <td>{{ number_format((int) $employeeActivity->duration_minutes) }}
                                                            menit</td>
                                                       <td><span
                                                                 class="ea-badge {{ $statusClass }}">{{ $employeeActivity->getStatusLabel() }}</span>
                                                       </td>
                                                       <td class="ea-no-print-col text-end">
                                                            <div class="ea-actions">
                                                                 <a href="{{ route('super-admin.employee-activities.show', $employeeActivity) }}"
                                                                      class="ea-action-btn ea-action-show"
                                                                      title="Detail"><i data-feather="eye"></i></a>
                                                                 <a href="{{ route('super-admin.employee-activities.edit', $employeeActivity) }}"
                                                                      class="ea-action-btn ea-action-edit"
                                                                      title="Edit"><i data-feather="edit-3"></i></a>
                                                                 @if (!$employeeActivity->isVerified())
                                                                      <form method="POST"
                                                                           action="{{ route('super-admin.employee-activities.verify', $employeeActivity) }}"
                                                                           class="d-inline">
                                                                           @csrf
                                                                           @method('PATCH')
                                                                           <button type="submit"
                                                                                class="ea-action-btn ea-action-verify"
                                                                                title="Verifikasi"><i
                                                                                     data-feather="check"></i></button>
                                                                      </form>
                                                                 @endif
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.employee-activities.destroy', $employeeActivity) }}"
                                                                      class="d-inline"
                                                                      onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?');">
                                                                      @csrf
                                                                      @method('DELETE')
                                                                      <button type="submit"
                                                                           class="ea-action-btn ea-action-delete"
                                                                           title="Hapus"><i
                                                                                data-feather="trash-2"></i></button>
                                                                 </form>
                                                            </div>
                                                       </td>
                                                  </tr>
                                             @empty
                                                  <tr>
                                                       <td colspan="9" class="ea-empty">
                                                            <span class="ea-empty-icon"><i
                                                                      data-feather="clipboard"></i></span>
                                                            <h3 class="ea-name" style="margin-bottom:7px;">Belum ada
                                                                 aktivitas pegawai</h3>
                                                            <div class="ea-sub">Tambahkan aktivitas pertama atau ubah filter
                                                                 untuk menampilkan data yang sesuai.</div>
                                                       </td>
                                                  </tr>
                                             @endforelse
                                        </tbody>
                                   </table>
                              </div>
                         </div>

                         @unless ($printMode)
                              @if (method_exists($employeeActivities, 'hasPages') && $employeeActivities->hasPages())
                                   <div class="ea-pagination">
                                        <div class="ea-pagination-info">Menampilkan
                                             {{ number_format($employeeActivities->firstItem() ?? 0) }} sampai
                                             {{ number_format($employeeActivities->lastItem() ?? 0) }} dari
                                             {{ number_format($employeeActivities->total()) }} data</div>
                                        <div>{{ $employeeActivities->onEachSide(1)->links() }}</div>
                                   </div>
                              @elseif ($filteredTotal > 0)
                                   <div class="ea-pagination">
                                        <div class="ea-pagination-info">Menampilkan seluruh {{ number_format($filteredTotal) }}
                                             data pada halaman ini.</div>
                                   </div>
                              @endif
                         @endunless
                    </div>
               </section>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }

                    @if ($printMode)
                         window.setTimeout(function() {
                              window.print();
                         }, 450);

                         window.addEventListener('afterprint', function() {
                              window.close();
                         });
                    @endif
               });
          </script>
     @endonce
@endsection
