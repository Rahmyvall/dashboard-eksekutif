@extends('layouts.app')

@section('page-title', 'Manajemen Invoice')

@section('content')
     <style>
          .invoice-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 48px;
               background: linear-gradient(145deg, #f8fbff, #f7f4ff 55%, #effcff)
          }

          .invoice-wrap {
               max-width: 1580px;
               margin: auto
          }

          .invoice-hero {
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 26px;
               background: linear-gradient(120deg, #4338ca, #7c3aed 52%, #0891b2);
               box-shadow: 0 22px 52px rgba(67, 56, 202, .2)
          }

          .invoice-hero-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px
          }

          .invoice-heading {
               display: flex;
               align-items: center;
               gap: 16px
          }

          .invoice-icon {
               display: inline-flex;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.65rem;
               border-radius: 19px;
               background: #fff
          }

          .invoice-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 3vw, 2.35rem);
               font-weight: 850;
               letter-spacing: -.04em
          }

          .invoice-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .88)
          }

          .invoice-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 9px
          }

          .invoice-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #4338ca;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               background: #fff
          }

          .invoice-btn-outline {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
               font-weight: 800
          }

          .invoice-card {
               padding: 20px;
               margin-top: 22px;
               border: 1px solid #e2e8f0;
               border-radius: 22px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .07)
          }

          .invoice-filter {
               display: grid;
               grid-template-columns: 2fr 1fr auto auto;
               gap: 12px;
               align-items: end
          }

          .invoice-filter label {
               display: block;
               margin-bottom: 6px;
               color: #64748b;
               font-size: .75rem;
               font-weight: 800
          }

          .invoice-filter input,
          .invoice-filter select {
               width: 100%;
               min-height: 43px;
               padding: 9px 12px;
               border: 1px solid #dbe3ee;
               border-radius: 11px
          }

          .btn-filter {
               min-height: 43px;
               padding: 9px 15px;
               color: #fff;
               border: 0;
               border-radius: 11px;
               background: #4f46e5;
               font-weight: 800
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
               font-weight: 750
          }

          .table-wrap {
               overflow-x: auto;
               margin-top: 18px
          }

          .invoice-table {
               width: 100%;
               min-width: 900px;
               border-collapse: collapse
          }

          .invoice-table th {
               padding: 13px;
               color: #64748b;
               font-size: .7rem;
               letter-spacing: .07em;
               text-align: left;
               text-transform: uppercase;
               background: #f8fafc
          }

          .invoice-table td {
               padding: 14px 13px;
               color: #334155;
               border-bottom: 1px solid #eef2f7;
               vertical-align: middle
          }

          .invoice-table tr:hover td {
               background: #fbfdff
          }

          .invoice-number {
               color: #4f46e5;
               font-weight: 850;
               text-decoration: none
          }

          .invoice-muted {
               color: #94a3b8;
               font-size: .78rem
          }

          .invoice-amount {
               color: #0f172a;
               font-weight: 850;
               white-space: nowrap
          }

          .invoice-badge {
               display: inline-flex;
               padding: 5px 9px;
               border-radius: 999px;
               font-size: .72rem;
               font-weight: 800
          }

          .badge-paid {
               color: #047857;
               background: #d1fae5
          }

          .badge-partial {
               color: #b45309;
               background: #fef3c7
          }

          .badge-unpaid {
               color: #be123c;
               background: #ffe4e6
          }

          .invoice-action {
               display: inline-flex;
               width: 32px;
               height: 32px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               text-decoration: none;
               border-radius: 9px;
               background: #eef2ff
          }

          .empty-invoice {
               padding: 45px 15px;
               color: #94a3b8;
               text-align: center
          }

          @media(max-width:800px) {

               .invoice-hero-row,
               .invoice-filter {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .invoice-btn,
               .invoice-btn-outline {
                    justify-content: center
               }
          }

          @media print {

               .sidebar,
               .topbar,
               .content-header,
               .invoice-hero,
               .invoice-filter,
               .invoice-card:first-of-type,
               .invoice-action,
               .pagination {
                    display: none !important
               }

               .invoice-page,
               .invoice-card {
                    padding: 0;
                    margin: 0;
                    border: 0;
                    box-shadow: none;
                    background: #fff
               }

               .invoice-table {
                    min-width: 0;
                    font-size: 10pt
               }
          }
     </style>
     <div class="invoice-page">
          <div class="invoice-wrap">
               <div class="invoice-hero">
                    <div class="invoice-hero-row">
                         <div class="invoice-heading"><span class="invoice-icon"><i
                                        class="fas fa-file-invoice-dollar"></i></span>
                              <div>
                                   <h1>Invoice</h1>
                                   <p>Kelola tagihan layanan dan pantau status pembayarannya.</p>
                              </div>
                         </div>
                         <div class="invoice-actions"><button class="invoice-btn-outline" type="button"
                                   onclick="window.print()"><i class="fas fa-print"></i> Cetak</button><a class="invoice-btn"
                                   href="{{ route('super-admin.invoices.create') }}"><i class="fas fa-plus"></i> Buat
                                   Invoice</a></div>
                    </div>
               </div>
               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif @if (session('error'))
                         <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="invoice-card">
                         <form method="GET" action="{{ route('super-admin.invoices.index') }}" class="invoice-filter">
                              <div><label for="search">Pencarian</label><input id="search" name="search"
                                        value="{{ request('search') }}" placeholder="Nomor invoice atau service order...">
                              </div>
                              <div><label for="payment_status">Status pembayaran</label><select id="payment_status"
                                        name="payment_status">
                                        <option value="">Semua status</option>
                                        @foreach ($statuses as $status)
                                             <option value="{{ $status }}" @selected(request('payment_status') === $status)>
                                                  {{ ucfirst($status) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="d-flex gap-2"><button class="btn-filter" type="submit"><i
                                             class="fas fa-filter"></i> Filter</button><a class="btn-reset"
                                        href="{{ route('super-admin.invoices.index') }}">Reset</a></div>
                         </form>
                    </div>
                    <div class="invoice-card">
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
                                             <th></th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse($invoices as $invoice)
                                             <tr>
                                                  <td><a class="invoice-number"
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
                                                  <td><span
                                                            class="invoice-badge badge-{{ $invoice->payment_status }}">{{ ucfirst($invoice->payment_status) }}</span>
                                                  </td>
                                                  <td><a class="invoice-action" title="Detail"
                                                            href="{{ route('super-admin.invoices.show', $invoice) }}"><i
                                                                 class="fas fa-eye"></i></a></td>
                                        </tr>@empty<tr>
                                                  <td colspan="8">
                                                       <div class="empty-invoice"><i
                                                                 class="fas fa-file-invoice fa-2x mb-3"></i>
                                                            <div>Belum ada invoice.</div>
                                                       </div>
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                         <div class="mt-3">{{ $invoices->links() }}</div>
                    </div>
          </div>
     </div>
@endsection
