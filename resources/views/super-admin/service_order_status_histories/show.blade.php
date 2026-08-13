@extends('layouts.app')

@section('page-title', 'Detail Riwayat Status Layanan')

@section('breadcrumb')
     <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Riwayat Status</a></li>
     <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@push('styles')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap');

          :root {
               --sh-bg: #eff4fb;
               --sh-ink: #0c1d37;
               --sh-sub: #5f7190;
               --sh-line: #d5e0ee;
               --sh-primary: #0f6fc6;
               --sh-primary-deep: #0a4b88;
               --sh-accent: #ef7d22;
               --sh-surface: #ffffff;
          }

          .sh-page {
               min-height: calc(100vh - 70px);
               background:
                    radial-gradient(circle at 6% 7%, rgba(15, 111, 198, .14), transparent 24%),
                    radial-gradient(circle at 94% 8%, rgba(239, 125, 34, .12), transparent 26%),
                    var(--sh-bg);
               padding: 24px clamp(12px, 2vw, 28px) 40px;
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
          }

          .sh-wrap {
               width: 100%;
               max-width: none;
               margin: 0;
          }

          .sh-topline {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 12px;
               font-size: .78rem;
               color: #607291;
               margin-bottom: 10px;
          }

          .sh-topline .dot {
               display: inline-block;
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #16a34a;
               margin-right: 8px;
               box-shadow: 0 0 0 8px rgba(22, 163, 74, .12);
          }

          .sh-hero {
               position: relative;
               overflow: hidden;
               border-radius: 26px;
               background: linear-gradient(128deg, #0f6fc6 0%, #0a4b88 50%, #ef7d22 100%);
               color: #fff;
               padding: clamp(20px, 2vw, 30px);
               box-shadow: 0 24px 56px rgba(10, 75, 136, .34);
               margin-bottom: 16px;
               display: grid;
               grid-template-columns: 1fr auto;
               gap: 16px;
               align-items: end;
          }

          .sh-hero::after {
               content: '';
               position: absolute;
               width: 340px;
               height: 340px;
               border-radius: 50%;
               right: -140px;
               top: -180px;
               background: rgba(255, 255, 255, .16);
          }

          .sh-hero h4 {
               margin: 0;
               font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.15rem, 1.8vw, 1.7rem);
               font-weight: 800;
               letter-spacing: -.015em;
          }

          .sh-hero small {
               opacity: .95;
          }

          .sh-hero-content,
          .sh-hero-actions {
               position: relative;
               z-index: 1;
          }

          .sh-btn {
               border: 0;
               border-radius: 12px;
               padding: 9px 14px;
               font-weight: 700;
               text-decoration: none;
               display: inline-flex;
               align-items: center;
               gap: 8px;
               transition: .18s ease;
          }

          .sh-btn:hover {
               transform: translateY(-1px);
               text-decoration: none;
          }

          .sh-btn-back {
               background: #fff;
               color: #0a4b88;
          }

          .sh-btn-edit {
               background: rgba(255, 255, 255, .22);
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .35);
          }

          .sh-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 14px;
               margin-bottom: 15px;
          }

          .sh-card {
               border: 1px solid var(--sh-line);
               border-radius: 18px;
               background: var(--sh-surface);
               padding: 16px;
               box-shadow: 0 16px 34px rgba(10, 26, 56, .07);
               position: relative;
               overflow: hidden;
          }

          .sh-card::before {
               content: '';
               position: absolute;
               width: 60px;
               height: 60px;
               border-radius: 50%;
               top: -26px;
               right: -20px;
               background: linear-gradient(160deg, rgba(15, 111, 198, .14), rgba(239, 125, 34, .08));
          }

          .sh-card small {
               color: var(--sh-sub);
               text-transform: uppercase;
               letter-spacing: .08em;
               font-size: .68rem;
               display: block;
               margin-bottom: 6px;
               position: relative;
               z-index: 1;
          }

          .sh-card strong {
               color: var(--sh-ink);
               font-size: 1.03rem;
               position: relative;
               z-index: 1;
          }

          .sh-panel {
               border: 1px solid var(--sh-line);
               border-radius: 20px;
               background: var(--sh-surface);
               box-shadow: 0 16px 34px rgba(10, 26, 56, .07);
               padding: 20px;
          }

          .sh-panel h6 {
               font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
               font-size: 1rem;
               letter-spacing: -.01em;
          }

          .sh-flow {
               display: flex;
               align-items: center;
               justify-content: space-between;
               align-items: center;
               gap: 14px;
               margin-bottom: 0;
               padding: 8px;
               border-radius: 14px;
               background: #f8fbff;
               border: 1px dashed #c8daef;
          }

          .sh-badge {
               display: inline-flex;
               padding: 8px 14px;
               border-radius: 999px;
               font-size: .8rem;
               font-weight: 700;
          }

          .sh-from {
               color: #334155;
               background: #e2e8f0;
          }

          .sh-to {
               color: #0b6cc4;
               background: #dbeafe;
          }

          .sh-arrow {
               color: #7e95b6;
               font-size: 1.05rem;
               display: inline-flex;
               width: 34px;
               height: 34px;
               align-items: center;
               justify-content: center;
               border-radius: 999px;
               background: #eaf2fd;
          }

          .sh-note {
               border-radius: 14px;
               background: linear-gradient(180deg, #f9fcff, #f2f8ff);
               border: 1px dashed #c8d9ef;
               padding: 16px;
               color: #324861;
               line-height: 1.72;
          }

          @media (max-width: 992px) {
               .sh-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .sh-hero {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767px) {
               .sh-grid {
                    grid-template-columns: 1fr;
               }

               .sh-flow {
                    flex-direction: column;
                    gap: 8px;
               }

               .sh-topline {
                    flex-direction: column;
                    align-items: flex-start;
               }
          }
     </style>
@endpush

@section('content')
     @php
          $history = $history ?? ($serviceOrderStatusHistory ?? null);

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
          $editRoute = $resolveRoute('edit');
     @endphp

     <div class="sh-page">
          <div class="sh-wrap">
               <div class="sh-topline">
                    <div><span class="dot"></span>Insight Status Order Aktif</div>
                    <div>{{ now()->format('d M Y, H:i') }} WIB</div>
               </div>

               <section class="sh-hero">
                    <div class="sh-hero-content">
                         <h4><i class="bi bi-activity me-2"></i>Detail Perubahan Status Layanan</h4>
                         <small>ID Riwayat: {{ $history->id ?? '-' }} | Order:
                              {{ $history?->serviceOrder?->order_number ?? '-' }}</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2 sh-hero-actions">
                         @if ($editRoute && $history)
                              <a href="{{ route($editRoute, $history) }}" class="sh-btn sh-btn-edit">
                                   <i class="bi bi-pencil-square"></i> Edit Data
                              </a>
                         @endif
                         <a href="{{ $indexRoute ? route($indexRoute) : url()->previous() }}" class="sh-btn sh-btn-back">
                              <i class="bi bi-arrow-left"></i> Kembali
                         </a>
                    </div>
               </section>

               <section class="sh-grid">
                    <article class="sh-card">
                         <small>Customer</small>
                         <strong>{{ $history?->serviceOrder?->customer?->name ?? '-' }}</strong>
                    </article>
                    <article class="sh-card">
                         <small>Diubah Oleh</small>
                         <strong>{{ $history?->changedBy?->name ?? '-' }}</strong>
                    </article>
                    <article class="sh-card">
                         <small>Waktu Ubah</small>
                         <strong>{{ optional($history?->changed_at)->format('d M Y H:i') ?? '-' }}</strong>
                    </article>
                    <article class="sh-card">
                         <small>Order Status Terkini</small>
                         <strong>{{ ucfirst((string) ($history?->serviceOrder?->order_status ?? '-')) }}</strong>
                    </article>
               </section>

               <section class="sh-panel mb-3">
                    <h6 class="mb-3 fw-bold">Alur Perubahan</h6>
                    <div class="sh-flow">
                         <span
                              class="sh-badge sh-from">{{ ucfirst((string) ($history?->previous_status ?? 'Initial')) }}</span>
                         <span class="sh-arrow"><i class="bi bi-arrow-right"></i></span>
                         <span class="sh-badge sh-to">{{ ucfirst((string) ($history?->new_status ?? '-')) }}</span>
                    </div>
               </section>

               <section class="sh-panel">
                    <h6 class="mb-3 fw-bold">Catatan</h6>
                    <div class="sh-note">
                         {{ $history?->notes ?: 'Tidak ada catatan tambahan untuk perubahan status ini.' }}
                    </div>
               </section>
          </div>
     </div>
@endsection
