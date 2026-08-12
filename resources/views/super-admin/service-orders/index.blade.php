@extends('layouts.app')

@section('page-title', 'Pesanan Layanan')

@push('styles')
     <style>
          :root {
               --so-primary: #0f766e;
               --so-primary-soft: #ccfbf1;
               --so-accent: #0ea5e9;
               --so-ink: #0f172a;
               --so-muted: #64748b;
               --so-line: #e2e8f0;
               --so-surface: #ffffff;
          }

          .so-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 40px;
               background: radial-gradient(circle at 6% 8%, rgba(20, 184, 166, .12), transparent 22%), radial-gradient(circle at 90% 10%, rgba(14, 165, 233, .1), transparent 25%), #f8fafc;
          }

          .so-wrap {
               max-width: 1500px;
               margin: 0 auto;
          }

          .so-hero {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 16px;
               padding: 24px;
               margin-bottom: 18px;
               border-radius: 20px;
               background: linear-gradient(120deg, #0f766e, #0ea5e9);
               color: #fff;
               box-shadow: 0 16px 32px rgba(2, 132, 199, .22);
          }

          .so-hero h4 {
               margin: 0;
               font-size: 1.4rem;
               font-weight: 800;
               letter-spacing: -.02em;
          }

          .so-hero p {
               margin: 6px 0 0;
               opacity: .92;
          }

          .so-btn {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               border: 0;
               border-radius: 12px;
               padding: 10px 14px;
               font-weight: 700;
               text-decoration: none;
               transition: .2s ease;
          }

          .so-btn:hover {
               transform: translateY(-1px);
               text-decoration: none;
          }

          .so-btn-primary {
               background: #fff;
               color: #0f766e;
          }

          .so-card {
               border: 1px solid var(--so-line);
               border-radius: 18px;
               background: var(--so-surface);
               box-shadow: 0 14px 30px rgba(15, 23, 42, .06);
          }

          .so-filter {
               padding: 18px;
               margin-bottom: 14px;
          }

          .so-filter .form-control {
               border-radius: 10px;
               border-color: #dbe3ee;
               min-height: 42px;
          }

          .so-filter-btn {
               background: var(--so-primary);
               color: #fff;
          }

          .so-filter-reset {
               border: 1px solid var(--so-line);
               color: #334155;
               background: #fff;
          }

          .so-table {
               margin: 0;
          }

          .so-table thead th {
               border: 0;
               background: #f8fafc;
               color: var(--so-muted);
               text-transform: uppercase;
               font-size: .72rem;
               letter-spacing: .06em;
               padding: 13px;
          }

          .so-table tbody td {
               border-color: #eef2f7;
               padding: 13px;
               vertical-align: middle;
          }

          .so-order-number {
               color: #0f766e;
               font-weight: 800;
          }

          .so-badge {
               display: inline-flex;
               padding: 5px 10px;
               border-radius: 999px;
               font-size: .75rem;
               font-weight: 700;
          }

          .so-badge-default {
               background: #e2e8f0;
               color: #334155;
          }

          .so-badge-success {
               background: #dcfce7;
               color: #166534;
          }

          .so-badge-warning {
               background: #fef3c7;
               color: #92400e;
          }

          .so-badge-danger {
               background: #fee2e2;
               color: #991b1b;
          }

          .so-action {
               display: inline-flex;
               width: 34px;
               height: 34px;
               align-items: center;
               justify-content: center;
               border-radius: 10px;
               text-decoration: none;
               margin-right: 4px;
          }

          .so-action-view {
               background: #e0f2fe;
               color: #0369a1;
          }

          .so-action-edit {
               background: #fef3c7;
               color: #b45309;
          }
     </style>
@endpush

@section('content')
     <div class="so-page">
          <div class="so-wrap">
               <div class="so-hero">
                    <div>
                         <h4><i class="bi bi-clipboard2-check me-2"></i>Manajemen Pesanan Layanan</h4>
                         <p>Kelola data order dengan tampilan yang lebih cepat dibaca dan mudah diproses.</p>
                    </div>
                    <a href="{{ route('super-admin.service-orders.create') }}" class="so-btn so-btn-primary">
                         <i class="bi bi-plus-circle"></i>Buat Pesanan
                    </a>
               </div>

               <div class="so-card so-filter">
                    <form method="GET" class="row g-2">
                         <div class="col-md-4">
                              <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                   placeholder="Cari nomor pesanan atau customer">
                         </div>
                         <div class="col-md-3">
                              <select name="customer_id" class="form-control">
                                   <option value="">Semua Customer</option>
                                   @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>
                                             {{ $customer->name }}</option>
                                   @endforeach
                              </select>
                         </div>
                         <div class="col-md-3">
                              <select name="order_status" class="form-control">
                                   <option value="">Semua Status</option>
                                   @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(request('order_status') === $status)>
                                             {{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                   @endforeach
                              </select>
                         </div>
                         <div class="col-md-2 d-grid">
                              <button class="so-btn so-filter-btn justify-content-center" type="submit">
                                   <i class="bi bi-funnel"></i>Filter
                              </button>
                         </div>
                         <div class="col-12">
                              <a href="{{ route('super-admin.service-orders.index') }}" class="so-btn so-filter-reset">
                                   <i class="bi bi-arrow-counterclockwise"></i>Reset Filter
                              </a>
                         </div>
                    </form>
               </div>

               <div class="so-card overflow-hidden">
                    <div class="table-responsive">
                         <table class="table so-table table-hover">
                              <thead>
                                   <tr>
                                        <th>No</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse ($orders as $order)
                                        @php
                                             $statusClass = match ($order->order_status) {
                                                 'completed' => 'so-badge-success',
                                                 'pending', 'processing' => 'so-badge-warning',
                                                 'cancelled' => 'so-badge-danger',
                                                 default => 'so-badge-default',
                                             };
                                        @endphp
                                        <tr>
                                             <td>{{ $loop->iteration + ($orders->firstItem() - 1) }}</td>
                                             <td><span class="so-order-number">{{ $order->order_number }}</span></td>
                                             <td>{{ optional($order->order_date)->format('d-m-Y') ?? $order->order_date }}
                                             </td>
                                             <td>{{ $order->customer?->name ?? '-' }}</td>
                                             <td><span
                                                       class="so-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                                             </td>
                                             <td>Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                                             <td>
                                                  <a href="{{ route('super-admin.service-orders.show', $order) }}"
                                                       class="so-action so-action-view" title="Detail">
                                                       <i class="bi bi-eye"></i>
                                                  </a>
                                                  <a href="{{ route('super-admin.service-orders.edit', $order) }}"
                                                       class="so-action so-action-edit" title="Edit">
                                                       <i class="bi bi-pencil-square"></i>
                                                  </a>
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="7" class="text-center py-4 text-muted">Belum ada data pesanan
                                                  layanan.</td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>
               </div>

               <div class="mt-3">
                    {{ $orders->links() }}
               </div>
          </div>
     </div>
@endsection
