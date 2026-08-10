@extends('layouts.app')

@section('page-title', 'Detail Invoice')

@section('content')
     <style>
          .inv-show {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 55px;
               background: radial-gradient(circle at 5% 5%, rgba(99, 102, 241, .15), transparent 25%), radial-gradient(circle at 95% 8%, rgba(8, 145, 178, .13), transparent 25%), linear-gradient(145deg, #f8fbff, #f7f4ff 55%, #effcff)
          }

          .inv-wrap {
               max-width: 1550px;
               margin: auto
          }

          .inv-hero {
               padding: 31px 34px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background: linear-gradient(120deg, #4338ca, #7c3aed 52%, #0891b2);
               box-shadow: 0 22px 52px rgba(67, 56, 202, .2)
          }

          .inv-hero-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px
          }

          .inv-hero h1 {
               margin: 0;
               font-size: clamp(1.75rem, 3vw, 2.45rem);
               font-weight: 850
          }

          .inv-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .88)
          }

          .inv-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 9px
          }

          .inv-btn {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               gap: 8px;
               align-items: center;
               color: #4338ca;
               text-decoration: none;
               border-radius: 12px;
               background: #fff;
               font-weight: 850
          }

          .inv-btn-print {
               color: #075985;
               background: #e0f2fe
          }

          .inv-btn-refresh {
               color: #047857;
               background: #d1fae5
          }

          .inv-grid {
               display: grid;
               grid-template-columns: repeat(4, 1fr);
               gap: 16px;
               margin-bottom: 22px
          }

          .inv-kpi {
               padding: 19px;
               border: 1px solid #e2e8f0;
               border-radius: 19px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 13px 30px rgba(51, 65, 85, .06)
          }

          .inv-kpi-label {
               color: #94a3b8;
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .07em;
               text-transform: uppercase
          }

          .inv-kpi-value {
               margin-top: 7px;
               color: #1e293b;
               font-size: 1.18rem;
               font-weight: 900
          }

          .inv-kpi-total {
               color: #4338ca
          }

          .inv-status {
               display: inline-flex;
               padding: 6px 10px;
               margin-top: 5px;
               border-radius: 999px;
               font-size: .75rem;
               font-weight: 850
          }

          .status-paid {
               color: #047857;
               background: #d1fae5
          }

          .status-partial {
               color: #b45309;
               background: #fef3c7
          }

          .status-unpaid {
               color: #be123c;
               background: #ffe4e6
          }

          .inv-card {
               padding: 24px;
               margin-top: 20px;
               border: 1px solid #e2e8f0;
               border-radius: 22px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .07)
          }

          .inv-title {
               display: flex;
               align-items: center;
               gap: 10px;
               padding-bottom: 14px;
               margin: 0 0 18px;
               color: #24324a;
               border-bottom: 1px solid #edf2f7;
               font-size: 1rem;
               font-weight: 850
          }

          .inv-title i {
               display: inline-flex;
               width: 33px;
               height: 33px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               border-radius: 10px;
               background: #e0e7ff;
               font-size: .85rem
          }

          .detail-grid {
               display: grid;
               grid-template-columns: repeat(4, 1fr);
               gap: 18px
          }

          .detail small {
               display: block;
               margin-bottom: 5px;
               color: #94a3b8;
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .06em;
               text-transform: uppercase
          }

          .detail strong {
               color: #1e293b
          }

          .detail.full {
               grid-column: 1/-1
          }

          .table-wrap {
               overflow-x: auto
          }

          .inv-table {
               width: 100%;
               min-width: 760px;
               border-collapse: collapse
          }

          .inv-table th {
               padding: 13px;
               color: #64748b;
               font-size: .7rem;
               text-align: left;
               letter-spacing: .06em;
               text-transform: uppercase;
               background: #f8fafc
          }

          .inv-table td {
               padding: 13px;
               color: #334155;
               border-bottom: 1px solid #eef2f7
          }

          .inv-table tr:hover td {
               background: #fbfdff
          }

          .payment-link {
               color: #4f46e5;
               text-decoration: none;
               font-weight: 850
          }

          .inv-note {
               padding: 14px;
               border-radius: 13px;
               color: #475569;
               background: #f8fafc;
               font-size: .85rem
          }

          .inv-refresh {
               display: inline-flex;
               min-height: 42px;
               padding: 9px 14px;
               gap: 8px;
               align-items: center;
               color: #fff;
               border: 0;
               border-radius: 11px;
               background: #4338ca;
               font-weight: 800
          }

          @media(max-width:950px) {
               .inv-grid {
                    grid-template-columns: repeat(2, 1fr)
               }

               .detail-grid {
                    grid-template-columns: repeat(2, 1fr)
               }
          }

          @media(max-width:650px) {
               .inv-hero-row {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .inv-actions {
                    margin-top: 15px
               }

               .inv-grid,
               .detail-grid {
                    grid-template-columns: 1fr
               }

               .inv-card {
                    padding: 18px
               }
          }

          @media print {

               .sidebar,
               .topbar,
               .content-header,
               .inv-hero,
               .inv-refresh {
                    display: none !important
               }

               .inv-show,
               .inv-card,
               .inv-kpi {
                    padding: 0;
                    margin: 0;
                    border: 0;
                    box-shadow: none;
                    background: #fff
               }
          }
     </style>
     <div class="inv-show">
          <div class="inv-wrap">
               <header class="inv-hero">
                    <div class="inv-hero-row">
                         <div>
                              <h1>{{ $invoice->invoice_number }}</h1>
                              <p>Detail tagihan, item layanan, dan riwayat pembayaran.</p>
                         </div>
                         <div class="inv-actions"><button class="inv-btn inv-btn-print" type="button"
                                   onclick="window.print()"><i class="fas fa-print"></i> Cetak</button><a class="inv-btn"
                                   href="{{ route('super-admin.invoices.edit', $invoice) }}"><i class="fas fa-edit"></i>
                                   Edit</a><a class="inv-btn" href="{{ route('super-admin.invoices.index') }}"><i
                                        class="fas fa-list"></i> Daftar</a></div>
                    </div>
               </header>
               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif @if (session('error'))
                         <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="inv-grid">
                         <div class="inv-kpi">
                              <div class="inv-kpi-label">Total Invoice</div>
                              <div class="inv-kpi-value inv-kpi-total">Rp
                                   {{ number_format((float) $invoice->total_amount, 2, ',', '.') }}</div>
                         </div>
                         <div class="inv-kpi">
                              <div class="inv-kpi-label">Subtotal</div>
                              <div class="inv-kpi-value">Rp {{ number_format((float) $invoice->subtotal, 2, ',', '.') }}</div>
                         </div>
                         <div class="inv-kpi">
                              <div class="inv-kpi-label">Pembayaran</div>
                              <div class="inv-kpi-value">{{ $invoice->payments->count() }} transaksi</div>
                         </div>
                         <div class="inv-kpi">
                              <div class="inv-kpi-label">Status</div><span
                                   class="inv-status status-{{ $invoice->payment_status }}">{{ ucfirst($invoice->payment_status) }}</span>
                         </div>
                    </div>
                    <div class="inv-card">
                         <h2 class="inv-title"><i class="fas fa-circle-info"></i> Informasi Invoice</h2>
                         <div class="detail-grid">
                              <div class="detail"><small>Nomor
                                        Invoice</small><strong>{{ $invoice->invoice_number }}</strong></div>
                              <div class="detail"><small>Service
                                        Order</small><strong>{{ $invoice->serviceOrder?->order_number ?? '-' }}</strong>
                              </div>
                              <div class="detail">
                                   <small>Customer</small><strong>{{ $invoice->serviceOrder?->customer?->display_name ?? ($invoice->serviceOrder?->customer?->name ?? '-') }}</strong>
                              </div>
                              <div class="detail"><small>Tanggal
                                        Invoice</small><strong>{{ $invoice->invoice_date?->format('d F Y') ?? '-' }}</strong>
                              </div>
                              <div class="detail"><small>Jatuh
                                        Tempo</small><strong>{{ $invoice->due_date?->format('d F Y') ?? '-' }}</strong>
                              </div>
                              <div class="detail"><small>Diskon</small><strong>Rp
                                        {{ number_format((float) $invoice->discount, 2, ',', '.') }}</strong></div>
                              <div class="detail"><small>Pajak</small><strong>Rp
                                        {{ number_format((float) $invoice->tax, 2, ',', '.') }}</strong></div>
                              <div class="detail full"><small>Catatan</small>
                                   <div class="inv-note">{{ $invoice->notes ?: 'Tidak ada catatan invoice.' }}</div>
                              </div>
                         </div>
                    </div>
                    <div class="inv-card">
                         <h2 class="inv-title"><i class="fas fa-layer-group"></i> Item Layanan</h2>
                         <div class="table-wrap">
                              <table class="inv-table">
                                   <thead>
                                        <tr>
                                             <th>Layanan</th>
                                             <th>Petugas</th>
                                             <th>Qty</th>
                                             <th>Harga</th>
                                             <th>Diskon</th>
                                             <th>Subtotal</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse($invoice->serviceOrder?->items ?? [] as $item)
                                             <tr>
                                                  <td><strong>{{ $item->service?->name ?? '-' }}</strong></td>
                                                  <td>{{ $item->employee?->name ?? '-' }}</td>
                                                  <td>{{ $item->quantity }}</td>
                                                  <td>Rp {{ number_format((float) $item->unit_price, 2, ',', '.') }}</td>
                                                  <td>Rp {{ number_format((float) $item->discount, 2, ',', '.') }}</td>
                                                  <td><strong>Rp
                                                            {{ number_format((float) $item->subtotal, 2, ',', '.') }}</strong>
                                                  </td>
                                        </tr>@empty<tr>
                                                  <td colspan="6" class="text-center text-muted py-4">Tidak ada item
                                                       layanan.</td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                    </div>
                    <div class="inv-card">
                         <h2 class="inv-title"><i class="fas fa-money-bill-wave"></i> Riwayat Pembayaran</h2>
                         <div class="table-wrap">
                              <table class="inv-table">
                                   <thead>
                                        <tr>
                                             <th>Nomor Payment</th>
                                             <th>Tanggal</th>
                                             <th>Metode</th>
                                             <th>Referensi</th>
                                             <th>Jumlah</th>
                                             <th>Status</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse($invoice->payments as $payment)
                                             <tr>
                                                  <td><a class="payment-link"
                                                            href="{{ route('super-admin.payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                                                  </td>
                                                  <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
                                                  <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                                  <td>{{ $payment->reference_number ?: '-' }}</td>
                                                  <td><strong>Rp
                                                            {{ number_format((float) $payment->amount, 2, ',', '.') }}</strong>
                                                  </td>
                                                  <td>{{ ucfirst($payment->status) }}</td>
                                        </tr>@empty<tr>
                                                  <td colspan="6" class="text-center text-muted py-4">Belum ada
                                                       pembayaran.</td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                         <form class="mt-3" method="POST"
                              action="{{ route('super-admin.invoices.refresh-status', $invoice) }}">@csrf
                              @method('PATCH')<button class="inv-refresh" type="submit"><i class="fas fa-rotate"></i>
                                   Segarkan Status Pembayaran</button></form>
                    </div>
          </div>
     </div>
@endsection
