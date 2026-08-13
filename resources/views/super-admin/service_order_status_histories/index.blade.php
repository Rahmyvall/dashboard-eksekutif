@extends('layouts.app')

@section('page-title', 'Dashboard Monitoring Status Layanan')

@section('breadcrumb')
     <li class="breadcrumb-item active" aria-current="page">Status History Layanan</li>
@endsection

@push('styles')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap');

          :root {
               --mtr-bg: #eef3fb;
               --mtr-ink: #091b34;
               --mtr-sub: #51627d;
               --mtr-line: #d3deee;
               --mtr-surface: #ffffff;
               --mtr-primary: #0f6fc6;
               --mtr-primary-deep: #0a4b88;
               --mtr-accent: #ef7d22;
               --mtr-success: #0f8c5d;
               --mtr-danger: #be2f2f;
               --mtr-warning: #ab6a16;
               --mtr-shadow: 0 20px 46px rgba(6, 23, 48, .1);
          }

          .mtr-page {
               min-height: calc(100vh - 70px);
               background:
                    radial-gradient(circle at 4% 8%, rgba(15, 111, 198, .16), transparent 23%),
                    radial-gradient(circle at 96% 9%, rgba(239, 125, 34, .16), transparent 26%),
                    var(--mtr-bg);
               padding: 24px clamp(12px, 2vw, 28px) 40px;
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
               color: var(--mtr-ink);
          }

          .mtr-wrap {
               width: 100%;
               max-width: none;
               margin: 0;
          }

          .mtr-topline {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 12px;
               margin-bottom: 10px;
               font-size: .78rem;
               color: #5c6f8d;
          }

          .mtr-topline .dot {
               width: 8px;
               height: 8px;
               border-radius: 999px;
               background: #22c55e;
               box-shadow: 0 0 0 8px rgba(34, 197, 94, .16);
               display: inline-block;
               margin-right: 8px;
               animation: pulseDot 1.8s infinite;
          }

          .mtr-hero {
               position: relative;
               overflow: hidden;
               border-radius: 26px;
               padding: clamp(20px, 2vw, 30px);
               margin-bottom: 16px;
               color: #fff;
               background: linear-gradient(126deg, #0e6cc4 0%, #0a4b88 48%, #ef7d22 100%);
               box-shadow: 0 24px 56px rgba(11, 78, 141, .38);
               display: grid;
               grid-template-columns: 1fr auto;
               align-items: stretch;
               gap: 24px;
          }

          .mtr-hero::after {
               content: '';
               position: absolute;
               width: 340px;
               height: 340px;
               right: -120px;
               top: -170px;
               border-radius: 50%;
               background: rgba(255, 255, 255, .14);
          }

          .mtr-hero h3 {
               margin: 0;
               font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.2rem, 2vw, 2rem);
               font-weight: 800;
               letter-spacing: -.018em;
          }

          .mtr-hero p {
               margin: 10px 0 0;
               opacity: .95;
               max-width: 980px;
               line-height: 1.65;
          }

          .mtr-hero-content {
               position: relative;
               z-index: 1;
          }

          .mtr-hero-stats {
               position: relative;
               z-index: 1;
               min-width: 290px;
               border-radius: 18px;
               padding: 14px;
               background: rgba(255, 255, 255, .16);
               border: 1px solid rgba(255, 255, 255, .26);
               backdrop-filter: blur(8px);
          }

          .mtr-hero-stats h6 {
               margin: 0 0 12px;
               font-size: .82rem;
               letter-spacing: .08em;
               text-transform: uppercase;
               font-weight: 700;
          }

          .mtr-bars {
               display: flex;
               align-items: flex-end;
               gap: 10px;
               height: 84px;
          }

          .mtr-bar {
               flex: 1;
               border-radius: 8px 8px 4px 4px;
               background: linear-gradient(180deg, rgba(255, 255, 255, .95), rgba(255, 255, 255, .4));
               animation: barsRise .6s ease both;
          }

          .mtr-b1 {
               height: 34%;
          }

          .mtr-b2 {
               height: 58%;
          }

          .mtr-b3 {
               height: 81%;
          }

          .mtr-b4 {
               height: 44%;
          }

          .mtr-bars-label {
               margin-top: 8px;
               display: flex;
               justify-content: space-between;
               font-size: .69rem;
               opacity: .9;
          }

          .mtr-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 14px;
               margin-bottom: 16px;
          }

          .mtr-kpi {
               border: 1px solid var(--mtr-line);
               border-radius: 18px;
               background: var(--mtr-surface);
               box-shadow: var(--mtr-shadow);
               padding: 18px;
               position: relative;
               overflow: hidden;
               animation: kpiRise .45s ease both;
          }

          .mtr-kpi::before {
               content: '';
               position: absolute;
               width: 70px;
               height: 70px;
               border-radius: 50%;
               right: -28px;
               top: -30px;
               background: linear-gradient(160deg, rgba(15, 111, 198, .14), rgba(239, 125, 34, .08));
          }

          .mtr-kpi small {
               display: block;
               color: var(--mtr-sub);
               text-transform: uppercase;
               letter-spacing: .08em;
               font-size: .68rem;
               margin-bottom: 6px;
          }

          .mtr-kpi strong {
               font-size: 1.45rem;
               font-weight: 800;
               line-height: 1;
          }

          .mtr-kpi .trend {
               margin-top: 8px;
               font-size: .76rem;
               color: #5d7392;
          }

          .mtr-kpi .trend i {
               margin-right: 4px;
          }

          .mtr-card {
               border: 1px solid var(--mtr-line);
               border-radius: 20px;
               background: var(--mtr-surface);
               box-shadow: var(--mtr-shadow);
          }

          .mtr-filter {
               padding: 18px;
               margin-bottom: 14px;
               backdrop-filter: blur(2px);
          }

          .mtr-label {
               display: block;
               font-size: .72rem;
               text-transform: uppercase;
               letter-spacing: .08em;
               color: var(--mtr-sub);
               margin-bottom: 6px;
               font-weight: 700;
          }

          .mtr-filter .form-control,
          .mtr-filter .form-select {
               border: 1px solid #d5deea;
               border-radius: 12px;
               min-height: 44px;
               font-size: .92rem;
               background: #fcfdff;
          }

          .mtr-filter .form-control:focus,
          .mtr-filter .form-select:focus {
               border-color: #7eb7eb;
               box-shadow: 0 0 0 3px rgba(15, 111, 198, .12);
          }

          .mtr-btn {
               border: 0;
               border-radius: 12px;
               padding: 10px 14px;
               font-weight: 700;
               text-decoration: none;
               display: inline-flex;
               gap: 8px;
               align-items: center;
               justify-content: center;
               transition: .18s ease;
          }

          .mtr-btn:hover {
               transform: translateY(-1px);
               text-decoration: none;
          }

          .mtr-btn-primary {
               background: linear-gradient(120deg, var(--mtr-primary), var(--mtr-primary-deep));
               color: #fff;
          }

          .mtr-btn-reset {
               border: 1px solid var(--mtr-line);
               color: #334155;
               background: #fff;
          }

          .mtr-table {
               margin: 0;
          }

          .mtr-table thead th {
               background: #f1f6fd;
               color: #62718b;
               border: 0;
               padding: 15px;
               font-size: .69rem;
               text-transform: uppercase;
               letter-spacing: .09em;
               position: sticky;
               top: 0;
               z-index: 2;
          }

          .mtr-table tbody td {
               padding: 15px;
               border-color: #edf2f9;
               vertical-align: middle;
          }

          .mtr-table tbody tr {
               transition: background-color .2s ease;
          }

          .mtr-table tbody tr:hover {
               background: #f8fbff;
          }

          .mtr-order {
               font-weight: 800;
               color: #0b5ca8;
               text-decoration: none;
          }

          .mtr-order:hover {
               color: #ef7d22;
          }

          .mtr-badge {
               padding: 6px 10px;
               border-radius: 999px;
               font-size: .74rem;
               font-weight: 700;
               display: inline-flex;
          }

          .mtr-draft {
               color: #475467;
               background: #eaecf0;
          }

          .mtr-pending {
               color: #b45309;
               background: #ffedd5;
          }

          .mtr-processing {
               color: #0b6cc4;
               background: #dbeafe;
          }

          .mtr-completed {
               color: #047857;
               background: #d1fae5;
          }

          .mtr-cancelled {
               color: #b42318;
               background: #fee4e2;
          }

          .mtr-actions {
               display: inline-flex;
               gap: 6px;
          }

          .mtr-action {
               width: 34px;
               height: 34px;
               border-radius: 10px;
               border: 0;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               text-decoration: none;
               color: #fff;
               box-shadow: 0 8px 14px rgba(16, 39, 73, .16);
          }

          .mtr-view {
               background: #0b6cc4;
          }

          .mtr-edit {
               background: #f97316;
          }

          .mtr-empty {
               display: grid;
               place-items: center;
               padding: 38px 12px;
               color: #607391;
               text-align: center;
          }

          .mtr-empty i {
               font-size: 1.8rem;
               margin-bottom: 8px;
               color: #8ba5c8;
          }

          @keyframes kpiRise {
               from {
                    opacity: 0;
                    transform: translateY(6px);
               }

               to {
                    opacity: 1;
                    transform: translateY(0);
               }
          }

          @keyframes pulseDot {

               0%,
               100% {
                    box-shadow: 0 0 0 0 rgba(34, 197, 94, .3);
               }

               50% {
                    box-shadow: 0 0 0 8px rgba(34, 197, 94, .04);
               }
          }

          @keyframes barsRise {
               from {
                    transform: scaleY(.4);
                    transform-origin: bottom;
                    opacity: .4;
               }

               to {
                    transform: scaleY(1);
                    transform-origin: bottom;
                    opacity: 1;
               }
          }

          @media (max-width: 1199px) {
               .mtr-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .mtr-hero {
                    grid-template-columns: 1fr;
               }

               .mtr-hero-stats {
                    width: 100%;
               }
          }

          @media (max-width: 767px) {
               .mtr-page {
                    padding: 18px 10px 30px;
               }

               .mtr-hero {
                    padding: 18px;
                    grid-template-columns: 1fr;
               }

               .mtr-grid {
                    grid-template-columns: 1fr;
               }

               .mtr-topline {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 6px;
               }
          }
     </style>
@endpush

@section('content')
     @php
          $routeBases = ['super-admin.service-order-status-histories', 'super-admin.service_order_status_histories'];

          $resolveRoute = static function (string $action) use ($routeBases): ?string {
              foreach ($routeBases as $base) {
                  $candidate = $base . '.' . $action;
                  if (\Illuminate\Support\Facades\Route::has($candidate)) {
                      return $candidate;
                  }
              }

              return null;
          };

          $indexRoute = $resolveRoute('index');
          $showRoute = $resolveRoute('show');
          $editRoute = $resolveRoute('edit');

          $histories = $histories ?? ($serviceOrderStatusHistories ?? collect());
          $statusOptions = ['draft', 'pending', 'processing', 'completed', 'cancelled'];

          $totalRows = method_exists($histories, 'total')
              ? (int) $histories->total()
              : (is_countable($histories)
                  ? count($histories)
                  : 0);
          $completedCount = collect($histories)->where('new_status', 'completed')->count();
          $processingCount = collect($histories)->where('new_status', 'processing')->count();
          $cancelledCount = collect($histories)->where('new_status', 'cancelled')->count();
     @endphp

     <div class="mtr-page">
          <div class="mtr-wrap">
               <div class="mtr-topline">
                    <div><span class="dot"></span>Live Monitoring Aktif</div>
                    <div>{{ now()->format('d M Y, H:i') }} WIB</div>
               </div>

               <section class="mtr-hero">
                    <div class="mtr-hero-content">
                         <h3><i class="bi bi-speedometer2 me-2"></i>Dashboard Monitoring Produktivitas Karyawan Dan Transaksi
                              Jasa</h3>
                         <p>Pantau perpindahan status order layanan secara real-time untuk membaca ritme kerja tim, memotong
                              bottleneck operasional, dan mempercepat kualitas eksekusi transaksi jasa di setiap cabang.</p>
                    </div>
                    <aside class="mtr-hero-stats">
                         <h6>Performa Mingguan</h6>
                         <div class="mtr-bars">
                              <span class="mtr-bar mtr-b1" title="Senin"></span>
                              <span class="mtr-bar mtr-b2" title="Selasa"></span>
                              <span class="mtr-bar mtr-b3" title="Rabu"></span>
                              <span class="mtr-bar mtr-b4" title="Kamis"></span>
                         </div>
                         <div class="mtr-bars-label">
                              <span>SEN</span>
                              <span>SEL</span>
                              <span>RAB</span>
                              <span>KAM</span>
                         </div>
                    </aside>
               </section>

               <section class="mtr-grid">
                    <article class="mtr-kpi">
                         <small>Total Riwayat</small>
                         <strong>{{ number_format($totalRows, 0, ',', '.') }}</strong>
                         <div class="trend"><i class="bi bi-graph-up-arrow"></i>Data terpantau</div>
                    </article>
                    <article class="mtr-kpi">
                         <small>Selesai</small>
                         <strong>{{ number_format($completedCount, 0, ',', '.') }}</strong>
                         <div class="trend"><i class="bi bi-check2-circle"></i>Penyelesaian terbaik</div>
                    </article>
                    <article class="mtr-kpi">
                         <small>Diproses</small>
                         <strong>{{ number_format($processingCount, 0, ',', '.') }}</strong>
                         <div class="trend"><i class="bi bi-arrow-repeat"></i>Order aktif berjalan</div>
                    </article>
                    <article class="mtr-kpi">
                         <small>Dibatalkan</small>
                         <strong>{{ number_format($cancelledCount, 0, ',', '.') }}</strong>
                         <div class="trend"><i class="bi bi-exclamation-circle"></i>Perlu evaluasi</div>
                    </article>
               </section>

               <section class="mtr-card mtr-filter">
                    <form method="GET" action="{{ $indexRoute ? route($indexRoute) : url()->current() }}" class="row g-3">
                         <div class="col-lg-4">
                              <label class="mtr-label">Cari Data</label>
                              <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                   placeholder="Nomor order, status, catatan, atau user">
                         </div>
                         <div class="col-lg-2 col-md-4">
                              <label class="mtr-label">Status Baru</label>
                              <select class="form-select" name="new_status">
                                   <option value="">Semua Status</option>
                                   @foreach ($statusOptions as $status)
                                        <option value="{{ $status }}" @selected((string) request('new_status') === $status)>
                                             {{ ucfirst($status) }}
                                        </option>
                                   @endforeach
                              </select>
                         </div>
                         <div class="col-lg-2 col-md-4">
                              <label class="mtr-label">Dari Tanggal</label>
                              <input type="date" class="form-control" name="changed_from"
                                   value="{{ request('changed_from') }}">
                         </div>
                         <div class="col-lg-2 col-md-4">
                              <label class="mtr-label">Sampai Tanggal</label>
                              <input type="date" class="form-control" name="changed_to"
                                   value="{{ request('changed_to') }}">
                         </div>
                         <div class="col-lg-2 col-md-4">
                              <label class="mtr-label">Per Halaman</label>
                              <select class="form-select" name="per_page">
                                   @foreach ([10, 15, 25, 50, 100] as $limit)
                                        <option value="{{ $limit }}" @selected((string) request('per_page', '15') === (string) $limit)>
                                             {{ $limit }}</option>
                                   @endforeach
                              </select>
                         </div>
                         <div class="col-12 d-flex flex-wrap gap-2">
                              <button type="submit" class="mtr-btn mtr-btn-primary">
                                   <i class="bi bi-funnel-fill"></i> Terapkan Filter
                              </button>
                              <a href="{{ $indexRoute ? route($indexRoute) : url()->current() }}"
                                   class="mtr-btn mtr-btn-reset">
                                   <i class="bi bi-arrow-clockwise"></i> Reset
                              </a>
                         </div>
                    </form>
               </section>

               <section class="mtr-card overflow-hidden">
                    <div class="table-responsive">
                         <table class="table mtr-table align-middle table-hover">
                              <thead>
                                   <tr>
                                        <th>#</th>
                                        <th>Order</th>
                                        <th>Status Sebelumnya</th>
                                        <th>Status Baru</th>
                                        <th>Diubah Oleh</th>
                                        <th>Waktu Ubah</th>
                                        <th>Catatan</th>
                                        <th class="text-center">Aksi</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse ($histories as $history)
                                        @php
                                             $statusClass = 'mtr-draft';
                                             if ($history->new_status === 'completed') {
                                                 $statusClass = 'mtr-completed';
                                             } elseif ($history->new_status === 'processing') {
                                                 $statusClass = 'mtr-processing';
                                             } elseif ($history->new_status === 'pending') {
                                                 $statusClass = 'mtr-pending';
                                             } elseif ($history->new_status === 'cancelled') {
                                                 $statusClass = 'mtr-cancelled';
                                             }

                                             $rowNumber = method_exists($histories, 'firstItem')
                                                 ? (int) $histories->firstItem() + $loop->index
                                                 : $loop->iteration;
                                        @endphp
                                        <tr>
                                             <td>{{ $rowNumber }}</td>
                                             <td>
                                                  <div class="fw-bold text-dark">
                                                       {{ $history->serviceOrder->order_number ?? '-' }}</div>
                                                  <a class="mtr-order"
                                                       href="{{ $history->serviceOrder ? route('super-admin.service-orders.show', $history->serviceOrder) : '#' }}">
                                                       {{ $history->serviceOrder->customer->name ?? 'Tanpa Customer' }}
                                                  </a>
                                             </td>
                                             <td>
                                                  <span
                                                       class="mtr-badge mtr-draft">{{ ucfirst((string) ($history->previous_status ?? 'initial')) }}</span>
                                             </td>
                                             <td>
                                                  <span
                                                       class="mtr-badge {{ $statusClass }}">{{ ucfirst((string) $history->new_status) }}</span>
                                             </td>
                                             <td>
                                                  <div class="fw-semibold">{{ $history->changedBy->name ?? '-' }}</div>
                                                  <small class="text-muted">{{ $history->changedBy->email ?? '-' }}</small>
                                             </td>
                                             <td>{{ optional($history->changed_at)->format('d M Y H:i') ?? '-' }}</td>
                                             <td>{{ \Illuminate\Support\Str::limit((string) ($history->notes ?? '-'), 70) }}
                                             </td>
                                             <td class="text-center">
                                                  <div class="mtr-actions">
                                                       @if ($showRoute)
                                                            <a class="mtr-action mtr-view" title="Detail"
                                                                 href="{{ route($showRoute, $history) }}">
                                                                 <i class="bi bi-eye"></i>
                                                            </a>
                                                       @endif
                                                       @if ($editRoute)
                                                            <a class="mtr-action mtr-edit" title="Edit"
                                                                 href="{{ route($editRoute, $history) }}">
                                                                 <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                       @endif
                                                  </div>
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="8">
                                                  <div class="mtr-empty">
                                                       <div>
                                                            <i class="bi bi-inbox"></i>
                                                            <div>Belum ada data riwayat status layanan.</div>
                                                       </div>
                                                  </div>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>
               </section>

               @if (method_exists($histories, 'links'))
                    <div class="mt-3">
                         {{ $histories->links() }}
                    </div>
               @endif
          </div>
     </div>
@endsection
