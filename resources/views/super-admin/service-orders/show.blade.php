@extends('layouts.app')

@section('page-title', 'Detail Pesanan Layanan')

@push('styles')
     <style>
          .so-show-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 40px;
               background: radial-gradient(circle at 90% 8%, rgba(14, 165, 233, .15), transparent 24%), #f8fafc;
          }

          .so-show-wrap {
               max-width: 1400px;
               margin: 0 auto;
          }

          .so-show-hero {
               border-radius: 18px;
               color: #fff;
               padding: 22px;
               margin-bottom: 16px;
               background: linear-gradient(120deg, #0f172a, #0f766e);
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 14px;
               flex-wrap: wrap;
          }

          .so-show-hero h4 {
               margin: 0;
               font-weight: 800;
          }

          .so-show-actions {
               display: flex;
               gap: 10px;
          }

          .so-btn {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               border-radius: 11px;
               padding: 9px 13px;
               text-decoration: none;
               font-weight: 700;
               border: 0;
          }

          .so-btn-edit {
               color: #fff;
               background: #d97706;
          }

          .so-btn-back {
               color: #0f172a;
               background: #fff;
          }

          .so-show-card {
               border: 1px solid #e2e8f0;
               border-radius: 16px;
               background: #fff;
               box-shadow: 0 14px 30px rgba(15, 23, 42, .06);
          }

          .so-stat {
               border: 1px solid #e2e8f0;
               border-radius: 14px;
               padding: 14px;
               background: #fff;
               height: 100%;
          }

          .so-stat-label {
               color: #64748b;
               font-size: .75rem;
               text-transform: uppercase;
               letter-spacing: .06em;
               margin-bottom: 5px;
          }

          .so-stat-value {
               color: #0f172a;
               font-weight: 800;
          }
     </style>
@endpush

@section('content')
     <div class="so-show-page">
          <div class="so-show-wrap">
               <div class="so-show-hero">
                    <div>
                         <h4><i class="bi bi-receipt-cutoff me-2"></i>Detail Pesanan Layanan</h4>
                         <div class="small opacity-75">Nomor Order: {{ $serviceOrder->order_number }}</div>
                    </div>
                    <div class="so-show-actions">
                         <a href="{{ route('super-admin.service-orders.edit', $serviceOrder) }}" class="so-btn so-btn-edit">
                              <i class="bi bi-pencil-square"></i>Edit
                         </a>
                         <a href="{{ route('super-admin.service-orders.index') }}" class="so-btn so-btn-back">
                              <i class="bi bi-arrow-left"></i>Kembali
                         </a>
                    </div>
               </div>

               <div class="row g-3 mb-3">
                    <div class="col-md-3">
                         <div class="so-stat">
                              <div class="so-stat-label">Tanggal</div>
                              <div class="so-stat-value">
                                   {{ optional($serviceOrder->order_date)->format('d-m-Y') ?? $serviceOrder->order_date }}
                              </div>
                         </div>
                    </div>
                    <div class="col-md-3">
                         <div class="so-stat">
                              <div class="so-stat-label">Customer</div>
                              <div class="so-stat-value">{{ $serviceOrder->customer?->name ?? '-' }}</div>
                         </div>
                    </div>
                    <div class="col-md-3">
                         <div class="so-stat">
                              <div class="so-stat-label">Status</div>
                              <div class="so-stat-value">{{ ucfirst(str_replace('_', ' ', $serviceOrder->order_status)) }}
                              </div>
                         </div>
                    </div>
                    <div class="col-md-3">
                         <div class="so-stat">
                              <div class="so-stat-label">Total</div>
                              <div class="so-stat-value">Rp
                                   {{ number_format((float) $serviceOrder->total_amount, 0, ',', '.') }}</div>
                         </div>
                    </div>
               </div>

               <div class="row g-3 mb-3">
                    <div class="col-md-4">
                         <div class="so-stat">
                              <div class="so-stat-label">Subtotal</div>
                              <div class="so-stat-value">Rp {{ number_format((float) $serviceOrder->subtotal, 0, ',', '.') }}
                              </div>
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="so-stat">
                              <div class="so-stat-label">Diskon</div>
                              <div class="so-stat-value">Rp {{ number_format((float) $serviceOrder->discount, 0, ',', '.') }}
                              </div>
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="so-stat">
                              <div class="so-stat-label">Pajak</div>
                              <div class="so-stat-value">Rp {{ number_format((float) $serviceOrder->tax, 0, ',', '.') }}
                              </div>
                         </div>
                    </div>
               </div>

               <div class="so-show-card p-3">
                    <h5 class="mb-3"><i class="bi bi-list-check me-2"></i>Item Layanan</h5>
                    <div class="table-responsive">
                         <table class="table table-hover align-middle mb-0">
                              <thead class="table-light">
                                   <tr>
                                        <th>Layanan</th>
                                        <th>Karyawan</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Diskon</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse ($serviceOrder->items as $item)
                                        <tr>
                                             <td>{{ $item->service?->name ?? '-' }}</td>
                                             <td>{{ $item->employee?->full_name ?? '-' }}</td>
                                             <td>{{ $item->quantity }}</td>
                                             <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                             <td>Rp {{ number_format((float) $item->discount, 0, ',', '.') }}</td>
                                             <td>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                                             <td>{{ ucfirst((string) $item->status) }}</td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="7" class="text-center text-muted py-4">Tidak ada item.</td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>
               </div>
          </div>
     </div>
@endsection
