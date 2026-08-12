@extends('layouts.app')

@section('page-title', 'Dashboard Monitoring Produktivitas Karyawan')

@section('content')
     @php
          $invoiceItems = $invoices->getCollection();
          $totalOnPage = (float) $invoiceItems->sum('total_amount');
          $paidCount = $invoiceItems->where('payment_status', 'paid')->count();
          $partialCount = $invoiceItems->where('payment_status', 'partial')->count();
          $unpaidCount = $invoiceItems->where('payment_status', 'unpaid')->count();
          $completionRate = $invoices->count() > 0 ? round(($paidCount / $invoices->count()) * 100) : 0;

          $currentSearch = trim((string) request('search', ''));
          $currentPaymentStatus = trim((string) request('payment_status', ''));
          $hasActiveFilter = $currentSearch !== '' || $currentPaymentStatus !== '';

          $today = now()->startOfDay();

          $overdueCount = $invoiceItems
              ->filter(function ($item) use ($today): bool {
                  $dueDate = data_get($item, 'due_date');

                  if (!$dueDate || strtolower((string) data_get($item, 'payment_status')) === 'paid') {
                      return false;
                  }

                  $resolvedDueDate =
                      $dueDate instanceof \Carbon\CarbonInterface
                          ? $dueDate->copy()->startOfDay()
                          : \Illuminate\Support\Carbon::parse((string) $dueDate)->startOfDay();

                  return $resolvedDueDate->lt($today);
              })
              ->count();

          $dueSoonCount = $invoiceItems
              ->filter(function ($item) use ($today): bool {
                  $dueDate = data_get($item, 'due_date');

                  if (!$dueDate || strtolower((string) data_get($item, 'payment_status')) === 'paid') {
                      return false;
                  }

                  $resolvedDueDate =
                      $dueDate instanceof \Carbon\CarbonInterface
                          ? $dueDate->copy()->startOfDay()
                          : \Illuminate\Support\Carbon::parse((string) $dueDate)->startOfDay();

                  $diffDays = $today->diffInDays($resolvedDueDate, false);

                  return $diffDays >= 0 && $diffDays <= 3;
              })
              ->count();
     @endphp

     <style>
          .invoice-page {
               --inv-text: #1e293b;
               --inv-muted: #64748b;
               --inv-border: #dbe3f1;
               --inv-surface: #ffffff;
               --inv-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
               min-height: calc(100vh - 70px);
               padding: 24px 18px 44px;
               background:
                    radial-gradient(circle at 8% 8%, rgba(14, 165, 233, 0.12), transparent 25%),
                    radial-gradient(circle at 92% 12%, rgba(99, 102, 241, 0.14), transparent 28%),
                    linear-gradient(145deg, #f8fbff, #f6f8ff 52%, #f0fbff);
          }

          .invoice-wrap {
               max-width: 1600px;
               margin: auto;
          }

          .invoice-hero {
               padding: 30px;
               margin-bottom: 18px;
               color: #fff;
               border-radius: 24px;
               background: linear-gradient(125deg, #1d4ed8 0%, #4f46e5 42%, #0891b2 100%);
               box-shadow: 0 24px 48px rgba(37, 99, 235, 0.26);
          }

          .invoice-hero-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
          }

          .invoice-heading {
               display: flex;
               gap: 14px;
               align-items: center;
          }

          .invoice-icon {
               display: inline-flex;
               width: 60px;
               height: 60px;
               align-items: center;
               justify-content: center;
               color: #1d4ed8;
               font-size: 1.5rem;
               border-radius: 17px;
               background: rgba(255, 255, 255, 0.95);
          }

          .invoice-hero h1 {
               margin: 0;
               font-size: clamp(1.6rem, 2.8vw, 2.3rem);
               font-weight: 850;
               letter-spacing: -0.04em;
          }

          .invoice-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, 0.88);
          }

          .invoice-actions {
               display: flex;
               gap: 10px;
               flex-wrap: wrap;
          }

          .invoice-alert {
               display: flex;
               align-items: flex-start;
               gap: 10px;
               padding: 12px 14px;
               margin-bottom: 12px;
               border: 0;
               border-radius: 12px;
               font-size: 0.82rem;
               font-weight: 700;
          }

          .invoice-alert i {
               margin-top: 2px;
          }

          .invoice-alert-success {
               color: #065f46;
               border-left: 4px solid #10b981;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .invoice-alert-danger {
               color: #991b1b;
               border-left: 4px solid #ef4444;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .invoice-btn,
          .invoice-btn-outline {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 44px;
               padding: 10px 15px;
               font-size: 0.82rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 12px;
               transition: transform 0.2s ease, background-color 0.2s ease;
          }

          .invoice-btn {
               color: #1d4ed8;
               background: #fff;
          }

          .invoice-btn-outline {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, 0.55);
               background:
                    radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.24), transparent 44%),
                    rgba(255, 255, 255, 0.14);
               box-shadow: 0 14px 24px rgba(30, 64, 175, 0.24);
               backdrop-filter: blur(6px);
          }

          .invoice-btn-outline strong {
               display: block;
               font-size: 0.8rem;
               line-height: 1.1;
               letter-spacing: 0.01em;
          }

          .invoice-btn-outline small {
               display: block;
               margin-top: 2px;
               color: rgba(255, 255, 255, 0.88);
               font-size: 0.66rem;
               font-weight: 700;
               letter-spacing: 0.03em;
               text-transform: uppercase;
          }

          .print-indicator {
               width: 8px;
               height: 8px;
               border-radius: 999px;
               background: #86efac;
               box-shadow: 0 0 0 0 rgba(134, 239, 172, 0.7);
               animation: printPulse 1.8s infinite;
          }

          @keyframes printPulse {
               0% {
                    box-shadow: 0 0 0 0 rgba(134, 239, 172, 0.7);
               }

               70% {
                    box-shadow: 0 0 0 10px rgba(134, 239, 172, 0);
               }

               100% {
                    box-shadow: 0 0 0 0 rgba(134, 239, 172, 0);
               }
          }

          .invoice-btn:hover,
          .invoice-btn-outline:hover {
               transform: translateY(-1px);
          }

          .invoice-monitor-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 18px;
          }

          .monitor-card {
               padding: 14px;
               border: 1px solid #dbe3f1;
               border-radius: 16px;
               background: rgba(255, 255, 255, 0.96);
               box-shadow: var(--inv-shadow);
          }

          .monitor-label {
               color: #64748b;
               font-size: 0.68rem;
               font-weight: 800;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               margin-bottom: 7px;
          }

          .monitor-value {
               color: #0f172a;
               font-size: 1.2rem;
               font-weight: 850;
               line-height: 1.2;
          }

          .monitor-note {
               margin-top: 6px;
               color: #64748b;
               font-size: 0.74rem;
               font-weight: 650;
          }

          .invoice-quick-actions {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 10px;
               margin-bottom: 18px;
          }

          .quick-action-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 44px;
               padding: 10px 12px;
               color: #334155;
               font-size: 0.78rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid #dbe3f1;
               border-radius: 12px;
               background: #ffffff;
               box-shadow: var(--inv-shadow);
               transition: transform 0.2s ease, box-shadow 0.2s ease;
          }

          .quick-action-btn:hover {
               color: #0f172a;
               transform: translateY(-2px);
               box-shadow: 0 18px 30px rgba(15, 23, 42, 0.12);
          }

          .quick-action-btn.primary {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg, #2563eb, #4f46e5);
          }

          .invoice-stats {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 18px;
          }

          .stat-card {
               padding: 14px;
               border: 1px solid var(--inv-border);
               border-radius: 16px;
               background: var(--inv-surface);
               box-shadow: var(--inv-shadow);
          }

          .stat-label {
               color: var(--inv-muted);
               font-size: 0.68rem;
               font-weight: 800;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               margin-bottom: 7px;
          }

          .stat-value {
               color: var(--inv-text);
               font-size: 1.45rem;
               font-weight: 850;
               letter-spacing: -0.03em;
               line-height: 1.1;
          }

          .invoice-card {
               margin-top: 16px;
               border: 1px solid var(--inv-border);
               border-radius: 18px;
               background: rgba(255, 255, 255, 0.97);
               box-shadow: var(--inv-shadow);
          }

          .invoice-card-head {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               padding: 16px 18px;
               border-bottom: 1px solid #eaf0f8;
               background: linear-gradient(90deg, #f8fbff, #f6f9ff);
          }

          .invoice-result-chip {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 10px;
               color: #5b21b6;
               font-size: 0.72rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .invoice-card-title {
               margin: 0;
               color: var(--inv-text);
               font-size: 0.98rem;
               font-weight: 850;
          }

          .invoice-card-subtitle {
               margin: 4px 0 0;
               color: var(--inv-muted);
               font-size: 0.78rem;
          }

          .invoice-card-body {
               padding: 16px 18px 18px;
          }

          .invoice-filter {
               display: grid;
               grid-template-columns: 2fr 1fr auto;
               gap: 11px;
               align-items: end;
          }

          .invoice-filter label {
               display: block;
               margin-bottom: 6px;
               color: #475569;
               font-size: 0.74rem;
               font-weight: 800;
          }

          .invoice-filter input,
          .invoice-filter select {
               width: 100%;
               min-height: 43px;
               padding: 9px 11px;
               border: 1px solid #d8e2f1;
               border-radius: 11px;
               background: #fff;
          }

          .filter-actions {
               display: flex;
               gap: 8px;
               align-items: center;
          }

          .btn-filter,
          .btn-reset {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 6px;
               min-height: 43px;
               padding: 9px 14px;
               border-radius: 11px;
               font-size: 0.78rem;
               font-weight: 800;
               text-decoration: none;
          }

          .btn-filter {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #2563eb, #4f46e5);
          }

          .btn-reset {
               color: #475569;
               border: 1px solid #dbe3ee;
               background: #fff;
          }

          .table-wrap {
               overflow-x: auto;
          }

          .invoice-table {
               width: 100%;
               min-width: 980px;
               border-collapse: collapse;
          }

          .row-number {
               display: inline-flex;
               width: 30px;
               height: 30px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 0.74rem;
               font-weight: 800;
               border-radius: 10px;
               background: #eef2ff;
          }

          .invoice-table th {
               padding: 12px;
               color: #64748b;
               font-size: 0.67rem;
               letter-spacing: 0.07em;
               text-align: left;
               text-transform: uppercase;
               background: #f8fbff;
               border-bottom: 1px solid #e8eef7;
          }

          .invoice-table td {
               padding: 13px 12px;
               color: #334155;
               font-size: 0.82rem;
               border-bottom: 1px solid #edf2f8;
               vertical-align: middle;
          }

          .invoice-table tr:hover td {
               background: #fbfdff;
          }

          .invoice-number {
               color: #1d4ed8;
               font-size: 0.84rem;
               font-weight: 850;
               text-decoration: none;
          }

          .invoice-muted {
               color: #94a3b8;
               font-size: 0.74rem;
               margin-top: 4px;
          }

          .invoice-amount {
               color: #0f172a;
               font-weight: 850;
               white-space: nowrap;
          }

          .invoice-badge {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 5px 10px;
               border-radius: 999px;
               font-size: 0.7rem;
               font-weight: 800;
          }

          .invoice-badge::before {
               width: 6px;
               height: 6px;
               content: '';
               border-radius: 999px;
               background: currentColor;
               opacity: 0.8;
          }

          .badge-paid {
               color: #047857;
               background: #d1fae5;
          }

          .badge-partial {
               color: #b45309;
               background: #fef3c7;
          }

          .badge-unpaid {
               color: #be123c;
               background: #ffe4e6;
          }

          .invoice-action {
               display: inline-flex;
               width: 33px;
               height: 33px;
               align-items: center;
               justify-content: center;
               color: #1d4ed8;
               text-decoration: none;
               border-radius: 10px;
               background: #e8efff;
          }

          .invoice-action-group {
               display: inline-flex;
               gap: 6px;
               align-items: center;
               justify-content: center;
          }

          .invoice-action.print {
               color: #0369a1;
               background: #e0f2fe;
          }

          .invoice-action.edit {
               color: #a16207;
               background: #fef3c7;
          }

          .invoice-action:hover {
               transform: translateY(-1px);
          }

          .due-date {
               display: block;
               font-weight: 700;
          }

          .due-warning {
               display: block;
               margin-top: 3px;
               color: #b45309;
               font-size: 0.7rem;
               font-weight: 700;
          }

          .active-filter-wrap {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               margin-top: 10px;
          }

          .active-filter-chip {
               display: inline-flex;
               gap: 5px;
               align-items: center;
               padding: 5px 9px;
               color: #5b21b6;
               font-size: 0.72rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .empty-invoice {
               padding: 52px 15px;
               text-align: center;
               color: #94a3b8;
          }

          .empty-invoice i {
               margin-bottom: 12px;
          }

          .invoice-pagination {
               padding-top: 14px;
          }

          @media (max-width: 900px) {

               .invoice-hero-row,
               .invoice-filter,
               .invoice-stats,
               .invoice-monitor-grid,
               .invoice-quick-actions {
                    display: grid;
                    grid-template-columns: 1fr;
               }

               .invoice-btn,
               .invoice-btn-outline,
               .btn-filter,
               .btn-reset {
                    justify-content: center;
               }

               .invoice-card-head {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }
          }

          @media print {

               .sidebar,
               .topbar,
               .content-header,
               .invoice-hero,
               .invoice-card:first-of-type,
               .invoice-stats,
               .invoice-monitor-grid,
               .invoice-quick-actions,
               .invoice-action,
               .invoice-pagination,
               .invoice-actions {
                    display: none !important;
               }

               .invoice-page,
               .invoice-card {
                    padding: 0;
                    margin: 0;
                    border: 0;
                    box-shadow: none;
                    background: #fff;
               }
          }
     </style>

     <div class="invoice-page">
          <div class="invoice-wrap">
               <header class="invoice-hero">
                    <div class="invoice-hero-row">
                         <div class="invoice-heading">
                              <span class="invoice-icon">
                                   <i class="fas fa-file-invoice-dollar"></i>
                              </span>
                              <div>
                                   <h1>Dashboard Monitoring Produktivitas Karyawan</h1>
                                   <p>Pantau alur transaksi jasa, status pembayaran invoice, dan progres penyelesaian order
                                        layanan dalam satu layar operasional.</p>
                              </div>
                         </div>
                         <div class="invoice-actions">
                              @if (Route::has('super-admin.service-orders.index'))
                                   <a class="invoice-btn-outline" href="{{ route('super-admin.service-orders.index') }}">
                                        <i class="fas fa-clipboard-list"></i>
                                        <span>
                                             <strong>Service Order</strong>
                                             <small>Transaksi Jasa</small>
                                        </span>
                                   </a>
                              @endif

                              @if (Route::has('super-admin.payments.index'))
                                   <a class="invoice-btn-outline" href="{{ route('super-admin.payments.index') }}">
                                        <i class="fas fa-wallet"></i>
                                        <span>
                                             <strong>Data Payment</strong>
                                             <small>Monitoring Pembayaran</small>
                                        </span>
                                   </a>
                              @endif

                              <button class="invoice-btn-outline" type="button" onclick="window.print()">
                                   <i class="fas fa-print"></i>
                                   <span>
                                        <strong>Cetak Ringkasan</strong>
                                        <small>Laporan Monitoring Transaksi</small>
                                   </span>
                                   <span class="print-indicator" aria-hidden="true"></span>
                              </button>
                              <a class="invoice-btn" href="{{ route('super-admin.invoices.create') }}">
                                   <i class="fas fa-plus"></i>
                                   Buat Invoice
                              </a>
                         </div>
                    </div>
               </header>

               <section class="invoice-stats" aria-label="Ringkasan invoice">
                    <article class="stat-card">
                         <div class="stat-label">Total Invoice</div>
                         <div class="stat-value">{{ number_format($invoices->total()) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Lunas</div>
                         <div class="stat-value">{{ number_format($paidCount) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Sebagian Dibayar</div>
                         <div class="stat-value">{{ number_format($partialCount) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Total Nilai Halaman</div>
                         <div class="stat-value">Rp {{ number_format($totalOnPage, 0, ',', '.') }}</div>
                    </article>
               </section>

               <section class="invoice-monitor-grid" aria-label="Monitoring produktivitas dan transaksi">
                    <article class="monitor-card">
                         <div class="monitor-label">Tingkat Penyelesaian</div>
                         <div class="monitor-value">{{ $completionRate }}%</div>
                         <div class="monitor-note">Invoice lunas dari total data di halaman ini</div>
                    </article>

                    <article class="monitor-card">
                         <div class="monitor-label">Perlu Tindak Lanjut</div>
                         <div class="monitor-value">{{ number_format($partialCount + $unpaidCount) }} invoice</div>
                         <div class="monitor-note">Gabungan status partial dan unpaid, termasuk
                              {{ number_format($overdueCount) }} overdue</div>
                    </article>

                    <article class="monitor-card">
                         <div class="monitor-label">Dashboard Fokus</div>
                         <div class="monitor-value">Transaksi Jasa</div>
                         <div class="monitor-note">Korelasi performa pembayaran dan produktivitas tim</div>
                    </article>
               </section>

               <section class="invoice-quick-actions" aria-label="Aksi cepat dashboard invoice">
                    @if (Route::has('super-admin.invoices.create'))
                         <a class="quick-action-btn primary" href="{{ route('super-admin.invoices.create') }}">
                              <i class="fas fa-plus-circle"></i>
                              Buat Invoice Baru
                         </a>
                    @endif

                    @if (Route::has('super-admin.invoices.index'))
                         <a class="quick-action-btn"
                              href="{{ route('super-admin.invoices.index', ['payment_status' => 'unpaid']) }}">
                              <i class="fas fa-hourglass-half"></i>
                              Fokus Belum Bayar
                         </a>
                    @endif

                    @if (Route::has('super-admin.invoices.index'))
                         <a class="quick-action-btn"
                              href="{{ route('super-admin.invoices.index', ['payment_status' => 'partial']) }}">
                              <i class="fas fa-tasks"></i>
                              Fokus Partial
                         </a>
                    @endif

                    @if (Route::has('super-admin.payments.create'))
                         <a class="quick-action-btn" href="{{ route('super-admin.payments.create') }}">
                              <i class="fas fa-money-check-alt"></i>
                              Input Pembayaran
                         </a>
                    @endif
               </section>

               @if (session('success'))
                    <div class="invoice-alert invoice-alert-success">
                         <i class="fas fa-check-circle"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="invoice-alert invoice-alert-danger">
                         <i class="fas fa-exclamation-triangle"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <section class="invoice-card">
                    <div class="invoice-card-head">
                         <div>
                              <h2 class="invoice-card-title">Filter Data Invoice</h2>
                              <p class="invoice-card-subtitle">Cari invoice berdasarkan nomor invoice, service order, dan
                                   status
                                   pembayaran.</p>
                         </div>
                         @if ($hasActiveFilter)
                              <span class="invoice-result-chip">
                                   <i class="fas fa-filter"></i>
                                   Filter Aktif
                              </span>
                         @endif
                    </div>
                    <div class="invoice-card-body">
                         <form method="GET" action="{{ route('super-admin.invoices.index') }}" class="invoice-filter">
                              <div>
                                   <label for="search">Pencarian</label>
                                   <input id="search" name="search" value="{{ $currentSearch }}"
                                        placeholder="Nomor invoice atau service order...">
                              </div>
                              <div>
                                   <label for="payment_status">Status Pembayaran</label>
                                   <select id="payment_status" name="payment_status">
                                        <option value="">Semua status</option>
                                        @foreach ($statuses as $status)
                                             <option value="{{ $status }}" @selected($currentPaymentStatus === $status)>
                                                  {{ ucfirst($status) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="filter-actions">
                                   <button class="btn-filter" type="submit">
                                        <i class="fas fa-filter"></i>
                                        Filter
                                   </button>
                                   <a class="btn-reset" href="{{ route('super-admin.invoices.index') }}">
                                        <i class="fas fa-undo"></i>
                                        Reset
                                   </a>
                              </div>
                         </form>

                         @if ($hasActiveFilter)
                              <div class="active-filter-wrap">
                                   @if ($currentSearch !== '')
                                        <span class="active-filter-chip">
                                             <i class="fas fa-search"></i>
                                             Kata kunci: {{ $currentSearch }}
                                        </span>
                                   @endif

                                   @if ($currentPaymentStatus !== '')
                                        <span class="active-filter-chip">
                                             <i class="fas fa-receipt"></i>
                                             Status: {{ ucfirst($currentPaymentStatus) }}
                                        </span>
                                   @endif
                              </div>
                         @endif
                    </div>
               </section>

               <section class="invoice-card">
                    <div class="invoice-card-head">
                         <div>
                              <h2 class="invoice-card-title">Daftar Invoice</h2>
                              <p class="invoice-card-subtitle">Menampilkan {{ number_format($invoices->count()) }} data
                                   pada
                                   halaman saat ini.</p>
                         </div>
                         <span class="invoice-result-chip">
                              <i class="fas fa-bolt"></i>
                              Due Soon: {{ number_format($dueSoonCount) }}
                         </span>
                    </div>
                    <div class="invoice-card-body">
                         <div class="table-wrap">
                              <table class="invoice-table">
                                   <thead>
                                        <tr>
                                             <th>No</th>
                                             <th>Invoice</th>
                                             <th>Customer</th>
                                             <th>Service Order</th>
                                             <th>Tanggal</th>
                                             <th>Jatuh Tempo</th>
                                             <th>Total</th>
                                             <th>Status</th>
                                             <th class="text-center">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse($invoices as $invoice)
                                             @php
                                                  $currentInvoiceStatus = strtolower((string) $invoice->payment_status);
                                                  $invoiceDueDate = $invoice->due_date;
                                                  $dueDiffDays = $invoiceDueDate
                                                      ? now()
                                                          ->startOfDay()
                                                          ->diffInDays($invoiceDueDate->copy()->startOfDay(), false)
                                                      : null;
                                             @endphp
                                             <tr>
                                                  <td>
                                                       <span class="row-number">
                                                            {{ ($invoices->firstItem() ?? 1) + $loop->index }}
                                                       </span>
                                                  </td>
                                                  <td>
                                                       <a class="invoice-number"
                                                            href="{{ route('super-admin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                                                       <div class="invoice-muted">{{ $invoice->payments->count() }}
                                                            pembayaran</div>
                                                  </td>
                                                  <td>{{ $invoice->serviceOrder?->customer?->display_name ?? ($invoice->serviceOrder?->customer?->name ?? '-') }}
                                                  </td>
                                                  <td>{{ $invoice->serviceOrder?->order_number ?? '-' }}</td>
                                                  <td>{{ $invoice->invoice_date?->format('d/m/Y') ?? '-' }}</td>
                                                  <td>
                                                       <span
                                                            class="due-date">{{ $invoice->due_date?->format('d/m/Y') ?? '-' }}</span>
                                                       @if (!is_null($dueDiffDays) && $currentInvoiceStatus !== 'paid')
                                                            @if ($dueDiffDays < 0)
                                                                 <span class="due-warning">Overdue {{ abs($dueDiffDays) }}
                                                                      hari</span>
                                                            @elseif ($dueDiffDays <= 3)
                                                                 <span class="due-warning">Jatuh tempo {{ $dueDiffDays }}
                                                                      hari lagi</span>
                                                            @endif
                                                       @endif
                                                  </td>
                                                  <td class="invoice-amount">Rp
                                                       {{ number_format((float) $invoice->total_amount, 2, ',', '.') }}</td>
                                                  <td>
                                                       <span
                                                            class="invoice-badge badge-{{ $currentInvoiceStatus }}">{{ ucfirst($currentInvoiceStatus) }}</span>
                                                  </td>
                                                  <td class="text-center">
                                                       <div class="invoice-action-group">
                                                            <a class="invoice-action" title="Detail"
                                                                 href="{{ route('super-admin.invoices.show', $invoice) }}">
                                                                 <i class="fas fa-eye"></i>
                                                            </a>

                                                            @if (Route::has('super-admin.invoices.print'))
                                                                 <a class="invoice-action print" title="Cetak"
                                                                      href="{{ route('super-admin.invoices.print', $invoice) }}"
                                                                      target="_blank" rel="noopener">
                                                                      <i class="fas fa-print"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.invoices.edit'))
                                                                 <a class="invoice-action edit" title="Edit"
                                                                      href="{{ route('super-admin.invoices.edit', $invoice) }}">
                                                                      <i class="fas fa-pen"></i>
                                                                 </a>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="9">
                                                       <div class="empty-invoice">
                                                            <i class="fas fa-file-invoice fa-2x"></i>
                                                            <div>Belum ada invoice yang dapat ditampilkan.</div>
                                                       </div>
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         <div class="invoice-pagination">
                              {{ $invoices->links() }}
                         </div>
                    </div>
               </section>
          </div>
     </div>
@endsection
