@extends('layouts.app')

@section('title', 'Detail Performance Indicator')

@section('content')
     @php
          $directionDescriptions = [
              \App\Models\PerformanceIndicator::DIRECTION_INCREASE =>
                  'Nilai realisasi yang lebih besar daripada target dianggap semakin baik.',
              \App\Models\PerformanceIndicator::DIRECTION_DECREASE =>
                  'Nilai realisasi yang lebih kecil daripada target dianggap semakin baik.',
              \App\Models\PerformanceIndicator::DIRECTION_EXACT =>
                  'Nilai realisasi dinilai baik apabila sama atau mendekati target yang ditetapkan.',
          ];

          $directionIcons = [
              \App\Models\PerformanceIndicator::DIRECTION_INCREASE => 'bi-graph-up-arrow',
              \App\Models\PerformanceIndicator::DIRECTION_DECREASE => 'bi-graph-down-arrow',
              \App\Models\PerformanceIndicator::DIRECTION_EXACT => 'bi-bullseye',
          ];

          $weightValue = max(0, min(100, (float) $performanceIndicator->weight));
     @endphp

     <style>
          :root {
               --indicator-primary: #6366f1;
               --indicator-primary-dark: #4f46e5;
               --indicator-secondary: #06b6d4;
               --indicator-purple: #8b5cf6;
               --indicator-success: #10b981;
               --indicator-danger: #ef4444;
               --indicator-text: #24324a;
               --indicator-muted: #718096;
          }

          .indicator-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .indicator-container {
               max-width: 1440px;
               margin: 0 auto;
          }

          .indicator-hero {
               position: relative;
               overflow: hidden;
               padding: 32px 34px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .7);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 42%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .indicator-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .hero-title-wrap {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.75rem;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .indicator-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 2.4vw, 2.3rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .indicator-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .95rem;
               line-height: 1.7;
          }

          .hero-actions {
               display: flex;
               gap: 10px;
               flex-wrap: wrap;
               justify-content: flex-end;
          }

          .btn-hero {
               display: inline-flex;
               min-height: 47px;
               padding: 10px 17px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 14px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
          }

          .btn-hero:hover {
               color: #312e81;
               background: #fff;
          }

          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid #10b981;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid #ef4444;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .detail-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.65fr) minmax(300px, .85fr);
               gap: 22px;
               align-items: start;
          }

          .detail-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .detail-card-header {
               display: flex;
               gap: 14px;
               align-items: center;
               padding: 21px 23px;
               border-bottom: 1px solid #edf2f7;
               background: linear-gradient(90deg, #fff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .detail-header-icon {
               display: inline-flex;
               width: 44px;
               height: 44px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.18rem;
               border-radius: 14px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .detail-card-header h2 {
               margin: 0;
               color: #24324a;
               font-size: 1.05rem;
               font-weight: 850;
          }

          .detail-card-header p {
               margin: 4px 0 0;
               color: #718096;
               font-size: .8rem;
          }

          .detail-card-body {
               padding: 23px;
          }

          .detail-row {
               display: grid;
               grid-template-columns: 190px minmax(0, 1fr);
               gap: 18px;
               padding: 17px 0;
               border-bottom: 1px dashed #e2e8f0;
          }

          .detail-row:first-child {
               padding-top: 0;
          }

          .detail-row:last-child {
               padding-bottom: 0;
               border-bottom: 0;
          }

          .detail-label {
               color: #64748b;
               font-size: .78rem;
               font-weight: 820;
               letter-spacing: .04em;
               text-transform: uppercase;
          }

          .detail-value {
               color: #24324a;
               font-size: .91rem;
               font-weight: 700;
               line-height: 1.7;
               overflow-wrap: anywhere;
          }

          .code-badge,
          .status-badge,
          .direction-badge,
          .unit-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 7px;
               align-items: center;
               font-size: .76rem;
               font-weight: 820;
               border: 1px solid transparent;
               border-radius: 999px;
          }

          .code-badge {
               color: #6d28d9;
               border-color: #ddd6fe;
               background: #f5f3ff;
          }

          .status-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .status-inactive {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .direction-badge {
               color: #1d4ed8;
               border-color: #bfdbfe;
               background: #eff6ff;
          }

          .unit-badge {
               color: #0369a1;
               border-color: #bae6fd;
               background: #f0f9ff;
          }

          .weight-panel {
               padding: 18px;
               border: 1px solid #fde68a;
               border-radius: 17px;
               background: linear-gradient(135deg, #fff7ed, #fef3c7);
          }

          .weight-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               margin-bottom: 11px;
               color: #92400e;
          }

          .weight-top strong {
               font-size: 1.55rem;
          }

          .weight-progress {
               height: 10px;
               overflow: hidden;
               border-radius: 999px;
               background: rgba(255, 255, 255, .72);
          }

          .weight-progress-bar {
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, #f59e0b, #f97316);
          }

          .direction-box {
               padding: 17px;
               color: #1e3a8a;
               border: 1px solid #bfdbfe;
               border-radius: 17px;
               background: linear-gradient(135deg, #eff6ff, #e0f2fe);
          }

          .direction-box strong {
               display: flex;
               gap: 8px;
               align-items: center;
               margin-bottom: 7px;
          }

          .direction-box p {
               margin: 0;
               color: #475569;
               font-size: .8rem;
               line-height: 1.65;
          }

          .side-stack {
               display: grid;
               gap: 22px;
          }

          .metadata-list {
               display: grid;
               gap: 14px;
          }

          .metadata-item {
               padding: 14px 15px;
               border: 1px solid #edf2f7;
               border-radius: 15px;
               background: #fbfdff;
          }

          .metadata-item span {
               display: block;
               margin-bottom: 4px;
               color: #94a3b8;
               font-size: .7rem;
               font-weight: 800;
               letter-spacing: .06em;
               text-transform: uppercase;
          }

          .metadata-item strong {
               color: #334155;
               font-size: .85rem;
          }

          .action-stack {
               display: grid;
               gap: 10px;
          }

          .detail-action-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 0 16px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 820;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .action-edit {
               color: #92400e;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .action-status {
               width: 100%;
               color: #1d4ed8;
               border: 1px solid #bfdbfe;
               background: #eff6ff;
          }

          .action-delete {
               width: 100%;
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          @media (max-width: 991.98px) {
               .detail-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767.98px) {
               .indicator-page {
                    padding: 20px 12px 34px;
               }

               .indicator-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .hero-content {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hero-actions {
                    width: 100%;
                    justify-content: flex-start;
               }

               .detail-row {
                    grid-template-columns: 1fr;
                    gap: 7px;
               }
          }
     </style>

     <div class="indicator-page">
          <div class="indicator-container">
               <section class="indicator-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <span class="hero-icon">
                                   <i class="bi bi-speedometer2"></i>
                              </span>

                              <div>
                                   <h1>Detail Performance Indicator</h1>
                                   <p>
                                        Informasi lengkap indikator
                                        <strong>{{ $performanceIndicator->code }}</strong>
                                        pada tabel <strong>performance_indicators</strong>.
                                   </p>
                              </div>
                         </div>

                         <div class="hero-actions">
                              <a href="{{ route('super-admin.performance-indicators.index') }}" class="btn-hero">
                                   <i class="bi bi-arrow-left-circle-fill"></i>
                                   Kembali
                              </a>

                              <a href="{{ route('super-admin.performance-indicators.edit', $performanceIndicator) }}"
                                   class="btn-hero">
                                   <i class="bi bi-pencil-fill"></i>
                                   Edit Indikator
                              </a>
                         </div>
                    </div>
               </section>

               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <div class="detail-grid">
                    <section class="detail-card">
                         <div class="detail-card-header">
                              <span class="detail-header-icon">
                                   <i class="bi bi-card-list"></i>
                              </span>

                              <div>
                                   <h2>Informasi Utama</h2>
                                   <p>Data inti yang disimpan pada database performance_indicators.</p>
                              </div>
                         </div>

                         <div class="detail-card-body">
                              <div class="detail-row">
                                   <div class="detail-label">ID Database</div>
                                   <div class="detail-value">#{{ $performanceIndicator->id }}</div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Kode Indikator</div>
                                   <div class="detail-value">
                                        <span class="code-badge">
                                             <i class="bi bi-upc-scan"></i>
                                             {{ $performanceIndicator->code }}
                                        </span>
                                   </div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Nama Indikator</div>
                                   <div class="detail-value">{{ $performanceIndicator->name }}</div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Deskripsi</div>
                                   <div class="detail-value">
                                        {{ filled($performanceIndicator->description) ? $performanceIndicator->description : 'Belum ada deskripsi indikator.' }}
                                   </div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Satuan</div>
                                   <div class="detail-value">
                                        <span class="unit-badge">
                                             <i class="bi bi-rulers"></i>
                                             {{ $performanceIndicator->unit }}
                                        </span>
                                   </div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Bobot</div>
                                   <div class="detail-value">
                                        <div class="weight-panel">
                                             <div class="weight-top">
                                                  <span>Bobot indikator</span>
                                                  <strong>{{ number_format((float) $performanceIndicator->weight, 2, ',', '.') }}%</strong>
                                             </div>

                                             <div class="weight-progress" aria-label="Bobot indikator">
                                                  <div class="weight-progress-bar" style="width: {{ $weightValue }}%">
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Arah Target</div>
                                   <div class="detail-value">
                                        <div class="direction-box">
                                             <strong>
                                                  <i
                                                       class="bi {{ $directionIcons[$performanceIndicator->target_direction] ?? 'bi-signpost-split-fill' }}"></i>
                                                  {{ $performanceIndicator->target_direction_label }}
                                             </strong>
                                             <p>
                                                  {{ $directionDescriptions[$performanceIndicator->target_direction] ?? 'Arah target belum dikenali oleh sistem.' }}
                                             </p>
                                        </div>
                                   </div>
                              </div>

                              <div class="detail-row">
                                   <div class="detail-label">Status</div>
                                   <div class="detail-value">
                                        <span
                                             class="status-badge {{ $performanceIndicator->isActive() ? 'status-active' : 'status-inactive' }}">
                                             <i
                                                  class="bi {{ $performanceIndicator->isActive() ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                             {{ $performanceIndicator->status_label }}
                                        </span>
                                   </div>
                              </div>
                         </div>
                    </section>

                    <aside class="side-stack">
                         <section class="detail-card">
                              <div class="detail-card-header">
                                   <span class="detail-header-icon">
                                        <i class="bi bi-clock-history"></i>
                                   </span>

                                   <div>
                                        <h2>Metadata</h2>
                                        <p>Waktu pembuatan dan pembaruan data.</p>
                                   </div>
                              </div>

                              <div class="detail-card-body">
                                   <div class="metadata-list">
                                        <div class="metadata-item">
                                             <span>Dibuat</span>
                                             <strong>
                                                  {{ optional($performanceIndicator->created_at)->format('d M Y, H:i') ?? '-' }}
                                             </strong>
                                        </div>

                                        <div class="metadata-item">
                                             <span>Terakhir diperbarui</span>
                                             <strong>
                                                  {{ optional($performanceIndicator->updated_at)->format('d M Y, H:i') ?? '-' }}
                                             </strong>
                                        </div>

                                        <div class="metadata-item">
                                             <span>Perubahan terakhir</span>
                                             <strong>
                                                  {{ optional($performanceIndicator->updated_at)->diffForHumans() ?? '-' }}
                                             </strong>
                                        </div>
                                   </div>
                              </div>
                         </section>

                         <section class="detail-card">
                              <div class="detail-card-header">
                                   <span class="detail-header-icon">
                                        <i class="bi bi-sliders"></i>
                                   </span>

                                   <div>
                                        <h2>Aksi Data</h2>
                                        <p>Kelola status, perubahan, atau penghapusan indikator.</p>
                                   </div>
                              </div>

                              <div class="detail-card-body">
                                   <div class="action-stack">
                                        <a href="{{ route('super-admin.performance-indicators.edit', $performanceIndicator) }}"
                                             class="detail-action-btn action-edit">
                                             <i class="bi bi-pencil-fill"></i>
                                             Edit Data Indikator
                                        </a>

                                        <form action="{{ route('super-admin.performance-indicators.toggle-status', $performanceIndicator) }}"
                                             method="POST"
                                             onsubmit="return confirm('Yakin ingin mengubah status indikator ini?')">
                                             @csrf
                                             @method('PATCH')

                                             <button type="submit" class="detail-action-btn action-status">
                                                  <i
                                                       class="bi {{ $performanceIndicator->isActive() ? 'bi-toggle-off' : 'bi-toggle-on' }}"></i>
                                                  {{ $performanceIndicator->isActive() ? 'Nonaktifkan Indikator' : 'Aktifkan Indikator' }}
                                             </button>
                                        </form>

                                        <form action="{{ route('super-admin.performance-indicators.destroy', $performanceIndicator) }}"
                                             method="POST"
                                             onsubmit="return confirm('Data indikator akan dihapus permanen. Lanjutkan?')">
                                             @csrf
                                             @method('DELETE')

                                             <button type="submit" class="detail-action-btn action-delete">
                                                  <i class="bi bi-trash3-fill"></i>
                                                  Hapus Indikator
                                             </button>
                                        </form>
                                   </div>
                              </div>
                         </section>
                    </aside>
               </div>
          </div>
     </div>
@endsection
