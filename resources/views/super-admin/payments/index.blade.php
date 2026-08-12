@extends('layouts.app')

@section('page-title', 'Manajemen Pembayaran')

@section('content')
     @php
          $paymentItems = $payments->getCollection();
          $totalOnPage = (float) $paymentItems->sum('amount');
          $confirmedCount = $paymentItems->where('status', 'confirmed')->count();
          $pendingCount = $paymentItems->where('status', 'pending')->count();
          $cancelledCount = $paymentItems->where('status', 'cancelled')->count();
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

          .payment-btn:hover,
          .payment-btn-outline:hover {
               transform: translateY(-1px);
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
               padding: 16px 18px;
               border-bottom: 1px solid #eaf0f8;
               background: linear-gradient(90deg, #f6fdff, #f3f9ff);
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
               padding: 5px 10px;
               border-radius: 999px;
               font-size: 0.7rem;
               font-weight: 800;
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
               .payment-stats {
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
          }

          @media print {

               .sidebar,
               .topbar,
               .content-header,
               .payment-hero,
               .payment-stats,
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
                                   <h1>Manajemen Pembayaran</h1>
                                   <p>Pantau transaksi pembayaran, metode bayar, dan status konfirmasi secara lebih
                                        terstruktur.</p>
                              </div>
                         </div>
                         <div class="payment-actions">
                              <button class="payment-btn-outline" type="button" onclick="window.print()">
                                   <i class="fas fa-print"></i>
                                   Cetak Data
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
                         <div class="stat-label">Total Data</div>
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

               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
               @endif
               @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
               @endif

               <section class="payment-card">
                    <div class="payment-card-head">
                         <h2 class="payment-card-title">Filter Pembayaran</h2>
                         <p class="payment-card-subtitle">Saring berdasarkan kata kunci, metode pembayaran, atau status
                              transaksi.</p>
                    </div>
                    <div class="payment-card-body">
                         <form method="GET" action="{{ route('super-admin.payments.index') }}" class="payment-filter">
                              <div>
                                   <label for="search">Pencarian</label>
                                   <input id="search" name="search" value="{{ request('search') }}"
                                        placeholder="Nomor pembayaran, referensi, invoice...">
                              </div>
                              <div>
                                   <label for="payment_method">Metode</label>
                                   <select id="payment_method" name="payment_method">
                                        <option value="">Semua metode</option>
                                        @foreach ($methods as $method)
                                             <option value="{{ $method }}" @selected(request('payment_method') === $method)>
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
                                             <option value="{{ $status }}" @selected(request('status') === $status)>
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
                    </div>
               </section>

               <section class="payment-card">
                    <div class="payment-card-head">
                         <h2 class="payment-card-title">Daftar Pembayaran</h2>
                         <p class="payment-card-subtitle">Menampilkan {{ number_format($payments->count()) }} data pada
                              halaman ini.</p>
                    </div>
                    <div class="payment-card-body">
                         <div class="table-wrap">
                              <table class="payment-table">
                                   <thead>
                                        <tr>
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
                                             <tr>
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
                                                            class="badge-payment badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                                                  </td>
                                                  <td class="text-center">
                                                       <a class="action-link" title="Detail"
                                                            href="{{ route('super-admin.payments.show', $payment) }}">
                                                            <i class="fas fa-eye"></i>
                                                       </a>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="8">
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
