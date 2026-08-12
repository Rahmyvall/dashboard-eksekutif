@extends('layouts.app')

@section('page-title', 'Manajemen Invoice')

@section('content')
     @php
          $invoiceItems = $invoices->getCollection();
          $totalOnPage = (float) $invoiceItems->sum('total_amount');
          $paidCount = $invoiceItems->where('payment_status', 'paid')->count();
          $partialCount = $invoiceItems->where('payment_status', 'partial')->count();
          $unpaidCount = $invoiceItems->where('payment_status', 'unpaid')->count();
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
               border: 1px solid rgba(255, 255, 255, 0.45);
               background: rgba(255, 255, 255, 0.12);
          }

          .invoice-btn:hover,
          .invoice-btn-outline:hover {
               transform: translateY(-1px);
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
               padding: 16px 18px;
               border-bottom: 1px solid #eaf0f8;
               background: linear-gradient(90deg, #f8fbff, #f6f9ff);
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
               padding: 5px 10px;
               border-radius: 999px;
               font-size: 0.7rem;
               font-weight: 800;
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
               .invoice-stats {
                    display: grid;
                    grid-template-columns: 1fr;
               }

               .invoice-btn,
               .invoice-btn-outline,
               .btn-filter,
               .btn-reset {
                    justify-content: center;
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
                                   <h1>Manajemen Invoice</h1>
                                   <p>Kelola data tagihan layanan dengan tampilan ringkas, cepat, dan mudah dipantau.</p>
                              </div>
                         </div>
                         <div class="invoice-actions">
                              <button class="invoice-btn-outline" type="button" onclick="window.print()">
                                   <i class="fas fa-print"></i>
                                   Cetak Ringkasan
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
                         <div class="stat-label">Total Data</div>
                         <div class="stat-value">{{ number_format($invoices->total()) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Sudah Dibayar</div>
                         <div class="stat-value">{{ number_format($paidCount) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Sebagian Dibayar</div>
                         <div class="stat-value">{{ number_format($partialCount) }}</div>
                    </article>
                    <article class="stat-card">
                         <div class="stat-label">Total Halaman Ini</div>
                         <div class="stat-value">Rp {{ number_format($totalOnPage, 0, ',', '.') }}</div>
                    </article>
               </section>

               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
               @endif

               <section class="invoice-card">
                    <div class="invoice-card-head">
                         <h2 class="invoice-card-title">Filter Data Invoice</h2>
                         <p class="invoice-card-subtitle">Cari invoice berdasarkan nomor invoice, service order, dan status
                              pembayaran.</p>
                    </div>
                    <div class="invoice-card-body">
                         <form method="GET" action="{{ route('super-admin.invoices.index') }}" class="invoice-filter">
                              <div>
                                   <label for="search">Pencarian</label>
                                   <input id="search" name="search" value="{{ request('search') }}"
                                        placeholder="Nomor invoice atau service order...">
                              </div>
                              <div>
                                   <label for="payment_status">Status Pembayaran</label>
                                   <select id="payment_status" name="payment_status">
                                        <option value="">Semua status</option>
                                        @foreach ($statuses as $status)
                                             <option value="{{ $status }}" @selected(request('payment_status') === $status)>
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
                    </div>
               </section>

               <section class="invoice-card">
                    <div class="invoice-card-head">
                         <h2 class="invoice-card-title">Daftar Invoice</h2>
                         <p class="invoice-card-subtitle">Menampilkan {{ number_format($invoices->count()) }} data pada
                              halaman saat ini.</p>
                    </div>
                    <div class="invoice-card-body">
                         <div class="table-wrap">
                              <table class="invoice-table">
                                   <thead>
                                        <tr>
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
                                             <tr>
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
                                                  <td>{{ $invoice->due_date?->format('d/m/Y') ?? '-' }}</td>
                                                  <td class="invoice-amount">Rp
                                                       {{ number_format((float) $invoice->total_amount, 2, ',', '.') }}</td>
                                                  <td>
                                                       <span
                                                            class="invoice-badge badge-{{ $invoice->payment_status }}">{{ ucfirst($invoice->payment_status) }}</span>
                                                  </td>
                                                  <td class="text-center">
                                                       <a class="invoice-action" title="Detail"
                                                            href="{{ route('super-admin.invoices.show', $invoice) }}">
                                                            <i class="fas fa-eye"></i>
                                                       </a>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="8">
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
