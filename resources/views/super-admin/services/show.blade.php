@extends('layouts.app')

@section('page-title', 'Detail Service')

@section('content')
     <style>
          .show-service-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 48px;
               background: radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .18), transparent 24%), radial-gradient(circle at 95% 8%, rgba(34, 211, 238, .16), transparent 25%), linear-gradient(145deg, #fbfdff, #f7f5ff 52%, #f0fbff);
          }

          .show-service-container {
               max-width: 1440px;
               margin: 0 auto;
          }

          .show-service-hero {
               position: relative;
               overflow: hidden;
               padding: 30px 34px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background: linear-gradient(120deg, #4f46e5, #7c3aed 50%, #0891b2);
               box-shadow: 0 22px 52px rgba(79, 70, 229, .21);
          }

          .show-kpi-row {
               display: grid;
               grid-template-columns: repeat(3, 1fr);
               gap: 16px;
               margin-bottom: 22px;
          }

          .show-kpi {
               display: flex;
               min-height: 105px;
               padding: 18px 20px;
               gap: 14px;
               align-items: center;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 19px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 12px 30px rgba(51, 65, 85, .06);
          }

          .show-kpi-icon {
               display: inline-flex;
               flex: 0 0 45px;
               width: 45px;
               height: 45px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.15rem;
               border-radius: 14px;
               background: #eef2ff;
          }

          .show-kpi-label {
               color: #94a3b8;
               font-size: .69rem;
               font-weight: 820;
               letter-spacing: .07em;
               text-transform: uppercase;
          }

          .show-kpi-value {
               margin-top: 4px;
               color: #1e293b;
               font-size: 1.18rem;
               font-weight: 850;
          }

          .show-related-card {
               overflow: hidden;
               margin-top: 22px;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 23px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .07);
          }

          .show-related-header {
               padding: 20px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff, #faf8ff 50%, #f0fbff);
          }

          .show-related-header h5 {
               margin: 0;
               color: #24324a;
               font-size: 1rem;
               font-weight: 830;
          }

          .show-related-body {
               padding: 12px 18px 18px;
          }

          .show-related-table {
               min-width: 680px;
          }

          .show-related-table th {
               color: #64748b;
               font-size: .7rem;
               letter-spacing: .07em;
               text-transform: uppercase;
               background: #f8fafc;
          }

          .show-related-table td,
          .show-related-table th {
               padding: 13px;
               border-color: #eef2f7;
          }

          .show-service-hero::after {
               position: absolute;
               right: 7%;
               bottom: -130px;
               width: 260px;
               height: 260px;
               content: '';
               border: 29px solid rgba(255, 255, 255, .1);
               border-radius: 50%;
          }

          .show-hero-inner,
          .show-heading,
          .show-actions {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
          }

          .show-heading {
               justify-content: flex-start;
          }

          .show-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.7rem;
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
          }

          .show-service-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 3vw, 2.35rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .show-service-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .9);
          }

          .show-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #fff;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .35);
               border-radius: 13px;
               background: rgba(255, 255, 255, .13);
          }

          .show-btn:hover {
               color: #fff;
               background: rgba(255, 255, 255, .23);
          }

          .show-btn-light {
               color: #4338ca;
               border: 0;
               background: #fff;
          }

          .show-btn-light:hover {
               color: #312e81;
               background: #fff;
          }

          .show-layout {
               display: grid;
               grid-template-columns: minmax(0, 1fr) 340px;
               gap: 22px;
               align-items: start;
          }

          .show-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 23px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .08);
          }

          .show-card-header {
               padding: 21px 25px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff, #faf8ff 50%, #f0fbff);
          }

          .show-card-header h5 {
               margin: 0;
               color: #24324a;
               font-weight: 830;
          }

          .show-card-body {
               padding: 27px;
          }

          .show-field {
               padding: 17px 0;
               border-bottom: 1px solid #eef2f7;
          }

          .show-field:first-child {
               padding-top: 0;
          }

          .show-field:last-child {
               padding-bottom: 0;
               border-bottom: 0;
          }

          .show-label {
               margin-bottom: 6px;
               color: #94a3b8;
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
          }

          .show-value {
               color: #1e293b;
               font-size: .96rem;
               font-weight: 720;
          }

          .show-description {
               color: #64748b;
               font-size: .87rem;
               line-height: 1.7;
               white-space: pre-line;
          }

          .show-code {
               display: inline-flex;
               padding: 7px 11px;
               color: #4338ca;
               font-size: .78rem;
               font-weight: 850;
               letter-spacing: .05em;
               border: 1px solid #c7d2fe;
               border-radius: 10px;
               background: #eef2ff;
          }

          .show-price {
               color: #4338ca;
               font-size: 1.35rem;
               font-weight: 850;
          }

          .show-pill {
               display: inline-flex;
               padding: 7px 11px;
               color: #475569;
               font-size: .78rem;
               font-weight: 700;
               border: 1px solid #e2e8f0;
               border-radius: 999px;
               background: #f8fafc;
          }

          .show-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 6px;
               align-items: center;
               font-size: .74rem;
               font-weight: 780;
               border-radius: 999px;
          }

          .show-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .show-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .show-side {
               position: sticky;
               top: 20px;
               display: grid;
               gap: 18px;
          }

          .show-summary {
               padding: 23px;
               color: #fff;
               border-radius: 22px;
               background: linear-gradient(135deg, #4f46e5, #7c3aed 55%, #0891b2);
               box-shadow: 0 18px 38px rgba(79, 70, 229, .2);
          }

          .show-summary-label {
               font-size: .7rem;
               font-weight: 800;
               letter-spacing: .1em;
               opacity: .75;
               text-transform: uppercase;
          }

          .show-summary h2 {
               margin: 9px 0 5px;
               font-size: 1.45rem;
               font-weight: 850;
          }

          .show-summary p {
               margin: 0;
               color: rgba(255, 255, 255, .83);
               font-size: .82rem;
          }

          .show-side-card {
               padding: 21px;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 21px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 34px rgba(51, 65, 85, .07);
          }

          .show-side-title {
               margin-bottom: 15px;
               color: #24324a;
               font-size: .93rem;
               font-weight: 830;
          }

          .show-timeline {
               display: grid;
               gap: 15px;
               margin: 0;
               padding: 0;
               list-style: none;
          }

          .show-timeline li {
               display: flex;
               gap: 10px;
               color: #64748b;
               font-size: .8rem;
               line-height: 1.45;
          }

          .show-timeline i {
               color: #6366f1;
          }

          @media (max-width: 991.98px) {
               .show-kpi-row {
                    grid-template-columns: repeat(3, 1fr);
               }

               .show-layout {
                    grid-template-columns: 1fr;
               }

               .show-side {
                    position: static;
               }
          }

          @media (max-width: 767.98px) {
               .show-kpi-row {
                    grid-template-columns: 1fr;
               }

               .show-service-page {
                    padding: 20px 12px 34px;
               }

               .show-service-hero {
                    padding: 24px 20px;
               }

               .show-hero-inner {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .show-actions {
                    width: 100%;
                    align-items: stretch;
                    flex-direction: column;
               }

               .show-btn {
                    width: 100%;
               }

               .show-card-body {
                    padding: 21px 18px;
               }
          }
     </style>

     @php $isActive = $service->status === \App\Models\Service::STATUS_ACTIVE; @endphp
     <div class="show-service-page">
          <div class="show-service-container">
               <div class="show-service-hero">
                    <div class="show-hero-inner">
                         <div class="show-heading"><span class="show-icon"><i class="bi bi-briefcase-fill"></i></span>
                              <div>
                                   <h1>{{ $service->name }}</h1>
                                   <p>Detail lengkap service dan informasi relasi kategori.</p>
                              </div>
                         </div>
                         <div class="show-actions"><a href="{{ route('super-admin.services.index') }}" class="show-btn"><i
                                        class="bi bi-arrow-left"></i> Daftar Service</a><a
                                   href="{{ route('super-admin.services.edit', $service) }}"
                                   class="show-btn show-btn-light"><i class="bi bi-pencil-fill"></i> Edit Service</a></div>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success" role="alert"><i
                              class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
               @endif

               <div class="show-kpi-row">
                    <div class="show-kpi"><span class="show-kpi-icon"><i class="bi bi-cart-check-fill"></i></span>
                         <div>
                              <div class="show-kpi-label">Total order item</div>
                              <div class="show-kpi-value">{{ number_format($service->orderItems->count()) }}</div>
                         </div>
                    </div>
                    <div class="show-kpi"><span class="show-kpi-icon"><i class="bi bi-box-seam-fill"></i></span>
                         <div>
                              <div class="show-kpi-label">Total kuantitas</div>
                              <div class="show-kpi-value">
                                   {{ number_format((float) $service->orderItems->sum('quantity'), 2, ',', '.') }}</div>
                         </div>
                    </div>
                    <div class="show-kpi"><span class="show-kpi-icon"><i class="bi bi-cash-stack"></i></span>
                         <div>
                              <div class="show-kpi-label">Total nilai item</div>
                              <div class="show-kpi-value">Rp
                                   {{ number_format((float) $service->orderItems->sum('subtotal'), 0, ',', '.') }}</div>
                         </div>
                    </div>
               </div>

               <div class="show-layout">
                    <div class="show-card">
                         <div class="show-card-header">
                              <h5><i class="bi bi-info-circle-fill me-2 text-primary"></i>Informasi Service</h5>
                         </div>
                         <div class="show-card-body">
                              <div class="row g-0">
                                   <div class="col-md-6 show-field pe-md-4">
                                        <div class="show-label">Kode Service</div><span
                                             class="show-code">{{ $service->service_code }}</span>
                                   </div>
                                   <div class="col-md-6 show-field ps-md-0">
                                        <div class="show-label">Kategori</div>
                                        <div class="show-value"><span class="show-pill"><i
                                                       class="bi bi-tag-fill me-1"></i>{{ $service->category?->name ?? 'Tanpa kategori' }}</span>
                                        </div>
                                   </div>
                                   <div class="col-md-6 show-field pe-md-4">
                                        <div class="show-label">Harga Dasar</div>
                                        <div class="show-price">{{ $service->formatted_price }}</div>
                                   </div>
                                   <div class="col-md-6 show-field ps-md-0">
                                        <div class="show-label">Unit</div>
                                        <div class="show-value">{{ $service->unit }}</div>
                                   </div>
                                   <div class="col-md-6 show-field pe-md-4">
                                        <div class="show-label">Estimasi Durasi</div>
                                        <div class="show-value">
                                             {{ $service->estimated_duration_minutes ? $service->estimated_duration_minutes . ' menit' : 'Tidak ditentukan' }}
                                        </div>
                                   </div>
                                   <div class="col-md-6 show-field ps-md-0">
                                        <div class="show-label">Status</div><span
                                             class="show-badge {{ $isActive ? 'show-active' : 'show-inactive' }}"><i
                                                  class="bi {{ $isActive ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>{{ $service->status_label }}</span>
                                   </div>
                                   <div class="col-12 show-field">
                                        <div class="show-label">Deskripsi</div>
                                        <div class="show-description">
                                             {{ $service->description ?: 'Tidak ada deskripsi untuk service ini.' }}</div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <aside class="show-side">
                         <div class="show-summary">
                              <div class="show-summary-label">Service ID #{{ $service->id }}</div>
                              <h2>{{ $service->service_code }}</h2>
                              <p>Data service tersimpan pada tabel <strong>services</strong>.</p>
                         </div>
                         <div class="show-side-card">
                              <div class="show-side-title"><i class="bi bi-clock-history text-primary me-1"></i>Riwayat Data
                              </div>
                              <ul class="show-timeline">
                                   <li><i
                                             class="bi bi-plus-circle-fill"></i><span>Dibuat<br><strong>{{ optional($service->created_at)->format('d M Y, H:i') ?? '-' }}</strong></span>
                                   </li>
                                   <li><i
                                             class="bi bi-pencil-fill"></i><span>Diperbarui<br><strong>{{ optional($service->updated_at)->format('d M Y, H:i') ?? '-' }}</strong></span>
                                   </li>
                              </ul>
                         </div>
                         <div class="show-side-card">
                              <div class="show-side-title"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Aksi
                                   Cepat</div>
                              <div class="d-grid gap-2">
                                   <form method="POST" action="{{ route('super-admin.services.toggle-status', $service) }}"
                                        onsubmit="return confirm('Ubah status service ini?')">@csrf @method('PATCH')<button
                                             class="btn btn-outline-{{ $isActive ? 'warning' : 'success' }} w-100"><i
                                                  class="bi bi-toggle-{{ $isActive ? 'on' : 'off' }} me-1"></i>{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                   </form>
                                   <form method="POST" action="{{ route('super-admin.services.destroy', $service) }}"
                                        onsubmit="return confirm('Pindahkan service ini ke recycle bin?')">@csrf
                                        @method('DELETE')<button class="btn btn-outline-danger w-100"><i
                                                  class="bi bi-trash3-fill me-1"></i>Hapus Service</button></form>
                              </div>
                         </div>
                    </aside>
               </div>

               <div class="show-related-card">
                    <div class="show-related-header">
                         <h5><i class="bi bi-receipt-cutoff me-2 text-primary"></i>Service Order Items</h5><small
                              class="text-muted">Riwayat penggunaan service pada transaksi.</small>
                    </div>
                    <div class="show-related-body">
                         <div class="table-responsive">
                              <table class="table show-related-table align-middle mb-0">
                                   <thead>
                                        <tr>
                                             <th>Order ID</th>
                                             <th>Qty</th>
                                             <th>Harga Satuan</th>
                                             <th>Subtotal</th>
                                             <th>Status</th>
                                             <th>Mulai</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse ($service->orderItems->sortByDesc('created_at') as $item)
                                             <tr>
                                                  <td><strong>#{{ $item->service_order_id }}</strong></td>
                                                  <td>{{ number_format((float) $item->quantity, 2, ',', '.') }}</td>
                                                  <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                                  <td class="fw-bold">Rp
                                                       {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                                                  <td><span
                                                            class="badge rounded-pill text-bg-light">{{ ucfirst($item->status) }}</span>
                                                  </td>
                                                  <td>{{ optional($item->start_date)->format('d M Y') ?? '-' }}</td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="6" class="text-center text-muted py-5"><i
                                                            class="bi bi-inbox d-block fs-2 mb-2 text-primary"></i>Belum ada
                                                       order item untuk service ini.</td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
@endsection
