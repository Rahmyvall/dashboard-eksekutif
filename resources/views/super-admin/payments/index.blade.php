@extends('layouts.app')

@section('page-title', 'Dashboard Monitoring Produktivitas Karyawan')

@section('content')
     @php
          $paymentItems = $payments->getCollection();
          $totalOnPage = (float) $paymentItems->sum('amount');
          $confirmedCount = $paymentItems->where('status', 'confirmed')->count();
          $pendingCount = $paymentItems->where('status', 'pending')->count();
          $cancelledCount = $paymentItems->where('status', 'cancelled')->count();

          $currentSearch = trim((string) request('search', ''));
          $currentMethod = trim((string) request('payment_method', ''));
          $currentStatus = trim((string) request('status', ''));
          $hasActiveFilter = $currentSearch !== '' || $currentMethod !== '' || $currentStatus !== '';

          $completionRate = $payments->count() > 0 ? round(($confirmedCount / $payments->count()) * 100) : 0;

          $avgPaymentAmount = $payments->count() > 0 ? $totalOnPage / $payments->count() : 0;
     @endphp

     <style>
          .payment-page {
               --pay-text: #1f2937;
               --pay-muted: #64748b;
               --pay-border: #dce5f1;
               --pay-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
               min-height: calc(100vh - 70px);
               padding: 24px 18px 44px;
               background:
                    radial-gradient(circle at 7% 8%, rgba(20, 184, 166, 0.14), transparent 25%),
                    radial-gradient(circle at 92% 10%, rgba(59, 130, 246, 0.12), transparent 28%),
                    linear-gradient(145deg, #f8fcff, #f3f9ff 56%, #eef7ff);
          }

          .payment-wrap {
               max-width: 1600px;
               margin: auto;
          }

          .payment-hero {
               padding: 30px;
               margin-bottom: 18px;
               color: #fff;
               border-radius: 24px;
               background: linear-gradient(125deg, #0f766e 0%, #0891b2 48%, #4f46e5 100%);
               box-shadow: 0 24px 48px rgba(13, 148, 136, 0.28);
          }

          .payment-hero-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
          }

          .payment-heading {
               display: flex;
               align-items: center;
               gap: 14px;
          }

          .payment-icon {
               display: inline-flex;
               width: 60px;
               height: 60px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               font-size: 1.45rem;
               border-radius: 17px;
               background: rgba(255, 255, 255, 0.95);
          }

          .payment-hero h1 {
               margin: 0;
               font-size: clamp(1.6rem, 2.8vw, 2.3rem);
               font-weight: 850;
               letter-spacing: -0.04em;
          }

          .payment-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, 0.9);
          }

          .payment-actions {
               display: flex;
               gap: 10px;
               flex-wrap: wrap;
          }

          .payment-alert {
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

          .payment-alert i {
               margin-top: 2px;
          }

          .payment-alert-success {
               color: #065f46;
               border-left: 4px solid #10b981;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .payment-alert-danger {
               color: #991b1b;
               border-left: 4px solid #ef4444;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .payment-btn,
          .payment-btn-outline {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: 0.82rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 12px;
               transition: transform 0.2s ease;
          }

          .payment-btn {
               color: #0f766e;
               background: #fff;
          }

          .payment-btn-outline {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, 0.45);
               background: rgba(255, 255, 255, 0.12);
          }

          .payment-btn-outline strong {
               display: block;
               font-size: 0.8rem;
               line-height: 1.1;
               letter-spacing: 0.01em;
          }

          .payment-btn-outline small {
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

          .payment-btn:hover,
          .payment-btn-outline:hover {
               transform: translateY(-1px);
          }

          .payment-monitor-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 18px;
          }

          .monitor-card {
               padding: 14px;
               border: 1px solid var(--pay-border);
               border-radius: 16px;
               background: #fff;
               box-shadow: var(--pay-shadow);
          }

          .monitor-label {
               color: var(--pay-muted);
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

          .payment-quick-actions {
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
               border: 1px solid var(--pay-border);
               border-radius: 12px;
               background: #ffffff;
               box-shadow: var(--pay-shadow);
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
               background: linear-gradient(135deg, #0f766e, #0f9f94);
          }

          .payment-stats {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 18px;
          }

          .stat-card {
               padding: 14px;
               border: 1px solid var(--pay-border);
               border-radius: 16px;
               background: #fff;
               box-shadow: var(--pay-shadow);
          }

          .stat-label {
               color: var(--pay-muted);
               font-size: 0.68rem;
               font-weight: 800;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               margin-bottom: 7px;
          }

          .stat-value {
               color: var(--pay-text);
               font-size: 1.45rem;
               font-weight: 850;
               letter-spacing: -0.03em;
               line-height: 1.1;
          }

          .payment-card {
               margin-top: 16px;
               border: 1px solid var(--pay-border);
               border-radius: 18px;
               background: rgba(255, 255, 255, 0.97);
               box-shadow: var(--pay-shadow);
          }

          .payment-card-head {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               padding: 16px 18px;
               border-bottom: 1px solid #eaf0f8;
               background: linear-gradient(90deg, #f6fdff, #f3f9ff);
          }

          .payment-result-chip {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 10px;
               color: #0f766e;
               font-size: 0.72rem;
               font-weight: 800;
               border: 1px solid #99f6e4;
               border-radius: 999px;
               background: #f0fdfa;
          }

          .payment-card-title {
               margin: 0;
               color: var(--pay-text);
               font-size: 0.98rem;
               font-weight: 850;
          }

          .payment-card-subtitle {
               margin: 4px 0 0;
               color: var(--pay-muted);
               font-size: 0.78rem;
          }

          .payment-card-body {
               padding: 16px 18px 18px;
          }

          .payment-filter {
               display: grid;
               grid-template-columns: 2fr 1fr 1fr auto;
               gap: 11px;
               align-items: end;
          }

          .payment-filter label {
               display: block;
               margin-bottom: 6px;
               color: #475569;
               font-size: 0.74rem;
               font-weight: 800;
          }

          .payment-filter input,
          .payment-filter select {
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
               background: linear-gradient(135deg, #0f766e, #0f9f94);
          }

          .btn-reset {
               color: #475569;
               border: 1px solid #dbe3ee;
               background: #fff;
          }

          .table-wrap {
               overflow-x: auto;
          }

          .payment-table {
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
               color: #0f766e;
               font-size: 0.74rem;
               font-weight: 800;
               border-radius: 10px;
               background: #ccfbf1;
          }

          .payment-table th {
               padding: 12px;
               color: #64748b;
               font-size: 0.67rem;
               letter-spacing: 0.07em;
               text-align: left;
               text-transform: uppercase;
               background: #f6fcff;
               border-bottom: 1px solid #e8eef7;
          }

          .payment-table td {
               padding: 13px 12px;
               color: #334155;
               font-size: 0.82rem;
               border-bottom: 1px solid #edf2f8;
               vertical-align: middle;
          }

          .payment-table tr:hover td {
               background: #fbfdff;
          }

          .payment-number {
               color: #0f766e;
               font-weight: 850;
               text-decoration: none;
          }

          .payment-muted {
               color: #94a3b8;
               font-size: 0.74rem;
               margin-top: 4px;
          }

          .payment-amount {
               color: #0f172a;
               font-weight: 850;
               white-space: nowrap;
          }

          .badge-payment {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 5px 10px;
               border-radius: 999px;
               font-size: 0.7rem;
               font-weight: 800;
          }

          .badge-payment::before {
               width: 6px;
               height: 6px;
               content: '';
               border-radius: 999px;
               background: currentColor;
               opacity: 0.8;
          }

          .badge-confirmed,
          .badge-paid {
               color: #047857;
               background: #d1fae5;
          }

          .badge-pending,
          .badge-partial {
               color: #b45309;
               background: #fef3c7;
          }

          .badge-cancelled,
          .badge-refunded,
          .badge-unpaid {
               color: #be123c;
               background: #ffe4e6;
          }

          .action-link {
               display: inline-flex;
               width: 33px;
               height: 33px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               text-decoration: none;
               border-radius: 10px;
               background: #def7f3;
          }

          .action-group {
               display: inline-flex;
               gap: 6px;
               align-items: center;
               justify-content: center;
          }

          .action-link.print {
               color: #0369a1;
               background: #e0f2fe;
          }

          .action-link.edit {
               color: #a16207;
               background: #fef3c7;
          }

          .action-link:hover {
               transform: translateY(-1px);
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
               color: #0f766e;
               font-size: 0.72rem;
               font-weight: 800;
               border: 1px solid #99f6e4;
               border-radius: 999px;
               background: #f0fdfa;
          }

          .empty-payment {
               padding: 52px 15px;
               color: #94a3b8;
               text-align: center;
          }

          .payment-pagination {
               padding-top: 14px;
          }

          @media (max-width: 980px) {

               .payment-hero-row,
               .payment-filter,
               .payment-stats,
               .payment-monitor-grid,
               .payment-quick-actions {
                    display: grid;
                    grid-template-columns: 1fr;
               }

               .payment-heading {
                    align-items: flex-start;
               }

               .filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }

               .payment-card-head {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }

          @media print {

               .sidebar,
               .topbar,
               .content-header,
               .payment-hero,
               .payment-stats,
               .payment-monitor-grid,
               .payment-quick-actions,
               .payment-card:first-of-type,
               .action-link,
               .payment-actions,
               .payment-pagination,
               .payment-card-head {
                    display: none !important;
               }

               .payment-page,
               .payment-wrap,
               .payment-card,
               .payment-card-body {
                    padding: 0 !important;
                    margin: 0 !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    background: #fff !important;
               }

               .table-wrap {
                    margin: 0;
                    overflow: visible;
               }

               .payment-table {
                    min-width: 0;
                    font-size: 10pt;
               }
          }
     </style>

     <div class="payment-page">
          <div class="payment-wrap">
               <header class="payment-hero">
                    <div class="payment-hero-row">
                         <div class="payment-heading">
                              <span class="payment-icon"><i class="fas fa-money-bill-wave"></i></span>
                              <div>
                                   <h1>Dashboard Monitoring Produktivitas Karyawan</h1>
                                   <p>Pantau transaksi jasa, status pembayaran, dan efisiensi proses konfirmasi dalam satu
                                        tampilan operasional modern.</p>
                              </div>
                         </div>
                         <div class="payment-actions">
                              @if (Route::has('super-admin.service-orders.index'))
                                   <a class="payment-btn-outline" href="{{ route('super-admin.service-orders.index') }}">
                                        <i class="fas fa-clipboard-list"></i>
                                        <span>
                                             <strong>Service Order</strong>
                                             <small>Transaksi Jasa</small>
                                        </span>
                                   </a>
                              @endif

                              @if (Route::has('super-admin.invoices.index'))
                                   <a class="payment-btn-outline" href="{{ route('super-admin.invoices.index') }}">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        <span>
                                             <strong>Data Invoice</strong>
                                             <small>Monitoring Tagihan</small>
                                        </span>
                                   </a>
                              @endif

                              <button class="payment-btn-outline" type="button" onclick="window.print()">
                                   <i class="fas fa-print"></i>
                                   <span>
                                        <strong>Cetak Data</strong>
                                        <small>Laporan Monitoring Pembayaran</small>
                                   </span>
                                   <span class="print-indicator" aria-hidden="true"></span>
                              </button>
                              <a class="payment-btn" href="{{ route('super-admin.payments.create') }}">
                                   <i class="fas fa-plus"></i>
                                   Catat Pembayaran
                              </a>
                         </div>
                    </div>
               </header>

               <section class="payment-stats" aria-label="Ringkasan pembayaran">
                    <article class="stat-card">
                         <div class="stat-label">Total Pembayaran</div>
                         <div class="stat-value">{{ number_format($payments->total()) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Terkonfirmasi</div>
                         <div class="stat-value">{{ number_format($confirmedCount) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Menunggu</div>
                         <div class="stat-value">{{ number_format($pendingCount) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Nominal Halaman</div>
                         <div class="stat-value">Rp {{ number_format($totalOnPage, 0, ',', '.') }}</div>
                    </article>
               </section>

               <section class="payment-monitor-grid" aria-label="Monitoring produktivitas dan transaksi">
                    <article class="monitor-card">
                         <div class="monitor-label">Tingkat Konfirmasi</div>
                         <div class="monitor-value">{{ $completionRate }}%</div>
                         <div class="monitor-note">Pembayaran confirmed dari data halaman ini</div>
                    </article>

                    <article class="monitor-card">
                         <div class="monitor-label">Butuh Tindak Lanjut</div>
                         <div class="monitor-value">{{ number_format($pendingCount + $cancelledCount) }} transaksi</div>
                         <div class="monitor-note">Status pending dan cancelled pada halaman ini</div>
                    </article>

                    <article class="monitor-card">
                         <div class="monitor-label">Rata-rata Nominal</div>
                         <div class="monitor-value">Rp {{ number_format($avgPaymentAmount, 0, ',', '.') }}</div>
                         <div class="monitor-note">Rata-rata nilai pembayaran pada halaman aktif</div>
                    </article>
               </section>

               <section class="payment-quick-actions" aria-label="Aksi cepat pembayaran">
                    @if (Route::has('super-admin.payments.create'))
                         <a class="quick-action-btn primary" href="{{ route('super-admin.payments.create') }}">
                              <i class="fas fa-plus-circle"></i>
                              Catat Pembayaran Baru
                         </a>
                    @endif

                    @if (Route::has('super-admin.payments.index'))
                         <a class="quick-action-btn"
                              href="{{ route('super-admin.payments.index', ['status' => 'pending']) }}">
                              <i class="fas fa-hourglass-half"></i>
                              Fokus Pending
                         </a>
                    @endif

                    @if (Route::has('super-admin.payments.index'))
                         <a class="quick-action-btn"
                              href="{{ route('super-admin.payments.index', ['status' => 'confirmed']) }}">
                              <i class="fas fa-check-circle"></i>
                              Fokus Confirmed
                         </a>
                    @endif

                    @if (Route::has('super-admin.invoices.index'))
                         <a class="quick-action-btn"
                              href="{{ route('super-admin.invoices.index', ['payment_status' => 'unpaid']) }}">
                              <i class="fas fa-file-invoice"></i>
                              Invoice Belum Lunas
                         </a>
                    @endif
               </section>

               @if (session('success'))
                    <div class="payment-alert payment-alert-success">
                         <i class="fas fa-check-circle"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif
               @if (session('error'))
                    <div class="payment-alert payment-alert-danger">
                         <i class="fas fa-exclamation-triangle"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <section class="payment-card">
                    <div class="payment-card-head">
                         <div>
                              <h2 class="payment-card-title">Filter Pembayaran</h2>
                              <p class="payment-card-subtitle">Saring berdasarkan kata kunci, metode pembayaran, atau status
                                   transaksi.</p>
                         </div>
                         @if ($hasActiveFilter)
                              <span class="payment-result-chip">
                                   <i class="fas fa-filter"></i>
                                   Filter Aktif
                              </span>
                         @endif
                    </div>
                    <div class="payment-card-body">
                         <form method="GET" action="{{ route('super-admin.payments.index') }}" class="payment-filter">
                              <div>
                                   <label for="search">Pencarian</label>
                                   <input id="search" name="search" value="{{ $currentSearch }}"
                                        placeholder="Nomor pembayaran, referensi, invoice...">
                              </div>
                              <div>
                                   <label for="payment_method">Metode</label>
                                   <select id="payment_method" name="payment_method">
                                        <option value="">Semua metode</option>
                                        @foreach ($methods as $method)
                                             <option value="{{ $method }}" @selected($currentMethod === $method)>
                                                  {{ ucwords(str_replace('_', ' ', $method)) }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>
                              <div>
                                   <label for="status">Status</label>
                                   <select id="status" name="status">
                                        <option value="">Semua status</option>
                                        @foreach ($statuses as $status)
                                             <option value="{{ $status }}" @selected($currentStatus === $status)>
                                                  {{ ucfirst($status) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="filter-actions">
                                   <button class="btn-filter" type="submit">
                                        <i class="fas fa-filter"></i>
                                        Filter
                                   </button>
                                   <a class="btn-reset" href="{{ route('super-admin.payments.index') }}">
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

                                   @if ($currentMethod !== '')
                                        <span class="active-filter-chip">
                                             <i class="fas fa-credit-card"></i>
                                             Metode: {{ ucwords(str_replace('_', ' ', $currentMethod)) }}
                                        </span>
                                   @endif

                                   @if ($currentStatus !== '')
                                        <span class="active-filter-chip">
                                             <i class="fas fa-receipt"></i>
                                             Status: {{ ucfirst($currentStatus) }}
                                        </span>
                                   @endif
                              </div>
                         @endif
                    </div>
               </section>

               <section class="payment-card">
                    <div class="payment-card-head">
                         <div>
                              <h2 class="payment-card-title">Daftar Pembayaran</h2>
                              <p class="payment-card-subtitle">Menampilkan {{ number_format($payments->count()) }} data
                                   pada
                                   halaman ini.</p>
                         </div>
                         <span class="payment-result-chip">
                              <i class="fas fa-bolt"></i>
                              Pending: {{ number_format($pendingCount) }}
                         </span>
                    </div>
                    <div class="payment-card-body">
                         <div class="table-wrap">
                              <table class="payment-table">
                                   <thead>
                                        <tr>
                                             <th>No</th>
                                             <th>Pembayaran</th>
                                             <th>Service Order</th>
                                             <th>Invoice</th>
                                             <th>Tanggal</th>
                                             <th>Metode</th>
                                             <th>Jumlah</th>
                                             <th>Status</th>
                                             <th class="text-center">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse($payments as $payment)
                                             @php
                                                  $paymentStatus = strtolower((string) $payment->status);
                                             @endphp
                                             <tr>
                                                  <td>
                                                       <span class="row-number">
                                                            {{ ($payments->firstItem() ?? 1) + $loop->index }}
                                                       </span>
                                                  </td>
                                                  <td>
                                                       <a class="payment-number"
                                                            href="{{ route('super-admin.payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                                                       <div class="payment-muted">
                                                            {{ $payment->receiver?->name ?? 'Sistem' }}</div>
                                                  </td>
                                                  <td>
                                                       {{ $payment->serviceOrder?->order_number ?? '-' }}
                                                       <div class="payment-muted">
                                                            {{ $payment->serviceOrder?->customer?->display_name ?? ($payment->serviceOrder?->customer?->name ?? '-') }}
                                                       </div>
                                                  </td>
                                                  <td>{{ $payment->invoice?->invoice_number ?? '-' }}</td>
                                                  <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
                                                  <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                                  <td class="payment-amount">Rp
                                                       {{ number_format((float) $payment->amount, 2, ',', '.') }}</td>
                                                  <td>
                                                       <span
                                                            class="badge-payment badge-{{ $paymentStatus }}">{{ ucfirst($paymentStatus) }}</span>
                                                  </td>
                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            <a class="action-link" title="Detail"
                                                                 href="{{ route('super-admin.payments.show', $payment) }}">
                                                                 <i class="fas fa-eye"></i>
                                                            </a>

                                                            @if (Route::has('super-admin.payments.print'))
                                                                 <a class="action-link print" title="Cetak"
                                                                      href="{{ route('super-admin.payments.print', $payment) }}"
                                                                      target="_blank" rel="noopener">
                                                                      <i class="fas fa-print"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.payments.edit'))
                                                                 <a class="action-link edit" title="Edit"
                                                                      href="{{ route('super-admin.payments.edit', $payment) }}">
                                                                      <i class="fas fa-pen"></i>
                                                                 </a>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="9">
                                                       <div class="empty-payment">
                                                            <i class="fas fa-receipt fa-2x mb-3"></i>
                                                            <div>Belum ada data pembayaran.</div>
                                                       </div>
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                         <div class="payment-pagination">{{ $payments->links() }}</div>
                    </div>
               </section>
          </div>
     </div>
@endsection
