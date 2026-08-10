@extends('layouts.app')

@section('page-title', 'Manajemen Pembayaran')

@section('content')
     <style>
          .payment-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 48px;
               background: linear-gradient(145deg, #f8fbff, #f7f4ff 55%, #effcff);
          }

          .payment-wrap {
               max-width: 1580px;
               margin: auto;
          }

          .payment-hero {
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 26px;
               background: linear-gradient(120deg, #0f766e, #0891b2 52%, #4f46e5);
               box-shadow: 0 22px 52px rgba(15, 118, 110, .2);
          }

          .payment-hero-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
          }

          .payment-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 3vw, 2.35rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .payment-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .88);
          }

          .payment-icon {
               display: inline-flex;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               font-size: 1.65rem;
               border-radius: 19px;
               background: #fff;
          }

          .payment-heading {
               display: flex;
               align-items: center;
               gap: 16px;
          }

          .payment-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #0f766e;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               background: #fff;
          }

          .payment-btn-outline {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #fff;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
          }

          .payment-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 9px;
          }

          .payment-card {
               padding: 20px;
               margin-top: 22px;
               border: 1px solid #e2e8f0;
               border-radius: 22px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .07);
          }

          .payment-filter {
               display: grid;
               grid-template-columns: 2fr 1fr auto auto;
               gap: 12px;
               align-items: end;
          }

          .payment-filter label {
               display: block;
               margin-bottom: 6px;
               color: #64748b;
               font-size: .75rem;
               font-weight: 800;
          }

          .payment-filter input,
          .payment-filter select {
               width: 100%;
               min-height: 43px;
               padding: 9px 12px;
               border: 1px solid #dbe3ee;
               border-radius: 11px;
          }

          .btn-filter {
               min-height: 43px;
               padding: 9px 15px;
               color: #fff;
               border: 0;
               border-radius: 11px;
               background: #0f766e;
               font-weight: 800;
          }

          .btn-reset {
               display: inline-flex;
               min-height: 43px;
               padding: 9px 15px;
               align-items: center;
               color: #475569;
               text-decoration: none;
               border: 1px solid #dbe3ee;
               border-radius: 11px;
               font-weight: 750;
          }

          .table-wrap {
               overflow-x: auto;
               margin-top: 18px;
          }

          .payment-table {
               width: 100%;
               min-width: 900px;
               border-collapse: collapse;
          }

          .payment-table th {
               padding: 13px;
               color: #64748b;
               font-size: .7rem;
               letter-spacing: .07em;
               text-align: left;
               text-transform: uppercase;
               background: #f8fafc;
          }

          .payment-table td {
               padding: 14px 13px;
               color: #334155;
               border-bottom: 1px solid #eef2f7;
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
               font-size: .78rem;
          }

          .payment-amount {
               color: #0f172a;
               font-weight: 850;
               white-space: nowrap;
          }

          .badge-payment {
               display: inline-flex;
               padding: 5px 9px;
               border-radius: 999px;
               font-size: .72rem;
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
               width: 32px;
               height: 32px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               text-decoration: none;
               border-radius: 9px;
               background: #ecfeff;
          }

          .empty-payment {
               padding: 45px 15px;
               color: #94a3b8;
               text-align: center;
          }

          @media (max-width:800px) {

               .payment-hero-row,
               .payment-filter {
                    grid-template-columns: 1fr;
                    display: grid;
               }

               .payment-heading {
                    align-items: flex-start;
               }

               .payment-btn {
                    justify-content: center;
               }
          }

          @media print {

               .sidebar,
               .topbar,
               .content-header,
               .payment-hero,
               .payment-filter,
               .payment-card:first-of-type,
               .action-link,
               .payment-actions,
               .pagination {
                    display: none !important;
               }

               .payment-page,
               .payment-card {
                    padding: 0;
                    margin: 0;
                    border: 0;
                    box-shadow: none;
                    background: #fff;
               }

               .table-wrap {
                    margin: 0;
               }

               .payment-table {
                    min-width: 0;
                    font-size: 10pt;
               }
          }
     </style>

     <div class="payment-page">
          <div class="payment-wrap">
               <div class="payment-hero">
                    <div class="payment-hero-row">
                         <div class="payment-heading"><span class="payment-icon"><i class="fas fa-money-bill-wave"></i></span>
                              <div>
                                   <h1>Pembayaran</h1>
                                   <p>Kelola penerimaan pembayaran service order dengan rapi dan terkontrol.</p>
                              </div>
                         </div>
                         <div class="payment-actions">
                              <button class="payment-btn-outline" type="button" onclick="window.print()"><i
                                        class="fas fa-print"></i> Cetak</button>
                              <a class="payment-btn" href="{{ route('super-admin.payments.create') }}"><i
                                        class="fas fa-plus"></i> Catat Pembayaran</a>
                         </div>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
               @endif
               @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
               @endif

               <div class="payment-card">
                    <form method="GET" action="{{ route('super-admin.payments.index') }}" class="payment-filter">
                         <div><label for="search">Pencarian</label><input id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Nomor pembayaran, referensi, invoice...">
                         </div>
                         <div><label for="payment_method">Metode</label><select id="payment_method" name="payment_method">
                                   <option value="">Semua metode</option>
                                   @foreach ($methods as $method)
                                        <option value="{{ $method }}" @selected(request('payment_method') === $method)>
                                             {{ ucwords(str_replace('_', ' ', $method)) }}</option>
                                   @endforeach
                              </select>
                         </div>
                         <div><label for="status">Status</label><select id="status" name="status">
                                   <option value="">Semua status</option>
                                   @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(request('status') === $status)>
                                             {{ ucfirst($status) }}</option>
                                   @endforeach
                              </select></div>
                         <div class="d-flex gap-2"><button class="btn-filter" type="submit"><i class="fas fa-filter"></i>
                                   Filter</button><a class="btn-reset"
                                   href="{{ route('super-admin.payments.index') }}">Reset</a></div>
                    </form>
               </div>

               <div class="payment-card">
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
                                        <th></th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse($payments as $payment)
                                        <tr>
                                             <td><a class="payment-number"
                                                       href="{{ route('super-admin.payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                                                  <div class="payment-muted">{{ $payment->receiver?->name ?? 'Sistem' }}
                                                  </div>
                                             </td>
                                             <td>{{ $payment->serviceOrder?->order_number ?? '-' }}<div
                                                       class="payment-muted">
                                                       {{ $payment->serviceOrder?->customer?->display_name ?? ($payment->serviceOrder?->customer?->name ?? '-') }}
                                                  </div>
                                             </td>
                                             <td>{{ $payment->invoice?->invoice_number ?? '-' }}</td>
                                             <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
                                             <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                             <td class="payment-amount">Rp
                                                  {{ number_format((float) $payment->amount, 2, ',', '.') }}</td>
                                             <td><span
                                                       class="badge-payment badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                                             </td>
                                             <td><a class="action-link" title="Detail"
                                                       href="{{ route('super-admin.payments.show', $payment) }}"><i
                                                            class="fas fa-eye"></i></a></td>
                                        </tr>
                                   @empty <tr>
                                             <td colspan="8">
                                                  <div class="empty-payment"><i class="fas fa-receipt fa-2x mb-3"></i>
                                                       <div>Belum ada data pembayaran.</div>
                                                  </div>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>
                    <div class="mt-3">{{ $payments->links() }}</div>
               </div>
          </div>
     </div>
@endsection
