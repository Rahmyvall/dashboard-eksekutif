@extends('layouts.app')

@section('title', 'Detail Periode Penilaian')

@section('content')
     <style>
          :root {
               --pp-primary: #6366f1;
               --pp-secondary: #06b6d4;
               --pp-success: #10b981;
               --pp-warning: #f59e0b;
               --pp-danger: #ef4444;
               --pp-text: #1e293b;
               --pp-muted: #64748b;
          }

          .pp-page {
               min-height: calc(100vh - 70px);
               padding: 30px;
               background:
                    radial-gradient(circle at 8% 8%, rgba(99, 102, 241, .18), transparent 24%),
                    radial-gradient(circle at 92% 18%, rgba(6, 182, 212, .15), transparent 25%),
                    linear-gradient(135deg, #f8fbff, #f5f3ff 50%, #ecfeff);
          }

          .pp-container {
               max-width: 1380px;
               margin: 0 auto;
          }

          .pp-hero,
          .pp-card {
               border: 1px solid rgba(219, 234, 254, .95);
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 20px 55px rgba(51, 65, 85, .10);
          }

          .pp-hero {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 24px;
               padding: 32px;
               margin-bottom: 24px;
               border-radius: 28px;
               background: linear-gradient(115deg, #fff, #eef2ff 48%, #ecfeff);
          }

          .pp-eyebrow {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               padding: 8px 13px;
               margin-bottom: 12px;
               color: #4338ca;
               font-size: .75rem;
               font-weight: 800;
               letter-spacing: .08em;
               text-transform: uppercase;
               border: 1px solid #c7d2fe;
               border-radius: 999px;
               background: #eef2ff;
          }

          .pp-hero h1 {
               margin: 0;
               color: var(--pp-text);
               font-size: clamp(1.75rem, 3vw, 2.5rem);
               font-weight: 850;
          }

          .pp-hero p {
               max-width: 760px;
               margin: 10px 0 0;
               color: var(--pp-muted);
               line-height: 1.7;
          }

          .pp-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
          }

          .pp-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-weight: 800;
               text-decoration: none;
               border-radius: 14px;
               transition: .2s ease;
          }

          .pp-btn-back {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #fff;
          }

          .pp-btn-edit {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, var(--pp-primary), #8b5cf6);
          }

          .pp-btn-delete {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #f43f5e, var(--pp-danger));
          }

          .pp-alert {
               margin-bottom: 20px;
               border: 0;
               border-radius: 16px;
          }

          .pp-card {
               overflow: hidden;
               border-radius: 24px;
          }

          .pp-card-header {
               display: flex;
               gap: 14px;
               align-items: center;
               padding: 22px 25px;
               border-bottom: 1px solid #e0e7ff;
               background: linear-gradient(100deg, #eef2ff, #eff6ff, #ecfeff);
          }

          .pp-card-header-icon {
               display: grid;
               width: 50px;
               height: 50px;
               place-items: center;
               color: #fff;
               font-size: 1.25rem;
               border-radius: 16px;
               background: linear-gradient(135deg, var(--pp-primary), var(--pp-secondary));
          }

          .pp-card-header h4 {
               margin: 0;
               color: var(--pp-text);
               font-weight: 850;
          }

          .pp-card-header p {
               margin: 4px 0 0;
               color: var(--pp-muted);
               font-size: .85rem;
          }

          .pp-card-body {
               padding: 28px;
          }

          .pp-summary-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 16px;
               margin-bottom: 24px;
          }

          .pp-summary {
               padding: 18px;
               border: 1px solid #e0e7ff;
               border-radius: 18px;
               background: linear-gradient(145deg, #fff, #f8fafc);
          }

          .pp-summary-icon {
               display: grid;
               width: 42px;
               height: 42px;
               margin-bottom: 12px;
               place-items: center;
               color: #fff;
               border-radius: 13px;
               background: linear-gradient(135deg, var(--pp-primary), var(--pp-secondary));
          }

          .pp-summary small {
               display: block;
               color: var(--pp-muted);
               font-size: .73rem;
               font-weight: 700;
               text-transform: uppercase;
               letter-spacing: .05em;
          }

          .pp-summary strong {
               display: block;
               margin-top: 5px;
               color: var(--pp-text);
               font-size: 1rem;
          }

          .pp-detail-list {
               overflow: hidden;
               border: 1px solid #e2e8f0;
               border-radius: 18px;
          }

          .pp-detail-row {
               display: grid;
               grid-template-columns: 240px 1fr;
               gap: 20px;
               padding: 17px 20px;
               border-bottom: 1px solid #e2e8f0;
          }

          .pp-detail-row:last-child {
               border-bottom: 0;
          }

          .pp-detail-label {
               color: var(--pp-muted);
               font-size: .82rem;
               font-weight: 800;
          }

          .pp-detail-value {
               color: var(--pp-text);
               font-weight: 750;
          }

          .pp-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 6px;
               align-items: center;
               font-size: .76rem;
               font-weight: 800;
               border-radius: 999px;
          }

          .pp-badge-type {
               color: #1d4ed8;
               border: 1px solid #bfdbfe;
               background: #eff6ff;
          }

          .pp-badge-draft {
               color: #92400e;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .pp-badge-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .pp-badge-completed {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .pp-badge-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          @media (max-width: 991.98px) {
               .pp-hero {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .pp-summary-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 767.98px) {
               .pp-page {
                    padding: 15px;
               }

               .pp-summary-grid {
                    grid-template-columns: 1fr;
               }

               .pp-detail-row {
                    grid-template-columns: 1fr;
                    gap: 6px;
               }

               .pp-actions,
               .pp-btn {
                    width: 100%;
               }
          }
     </style>

     @php
          $periodTypeLabels = [
              'monthly' => 'Bulanan',
              'quarterly' => 'Kuartalan',
              'semester' => 'Semester',
              'annual' => 'Tahunan',
          ];

          $statusLabels = [
              'draft' => 'Draft',
              'active' => 'Aktif',
              'completed' => 'Selesai',
              'inactive' => 'Tidak Aktif',
          ];

          $statusIcons = [
              'draft' => 'bi-pencil-square',
              'active' => 'bi-play-circle-fill',
              'completed' => 'bi-flag-fill',
              'inactive' => 'bi-pause-circle-fill',
          ];

          $status = strtolower((string) $performancePeriod->status);
          $type = strtolower((string) $performancePeriod->period_type);

          $duration =
              $performancePeriod->start_date && $performancePeriod->end_date
                  ? $performancePeriod->start_date->diffInDays($performancePeriod->end_date) + 1
                  : null;

          $isCurrent =
              $performancePeriod->start_date &&
              $performancePeriod->end_date &&
              now()
                  ->startOfDay()
                  ->between($performancePeriod->start_date->startOfDay(), $performancePeriod->end_date->endOfDay());
     @endphp

     <div class="pp-page">
          <div class="pp-container">
               @if (session('success'))
                    <div class="alert alert-success pp-alert">
                         <i class="bi bi-check-circle-fill me-2"></i>
                         {{ session('success') }}
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger pp-alert">
                         <i class="bi bi-exclamation-triangle-fill me-2"></i>
                         {{ session('error') }}
                    </div>
               @endif

               <div class="pp-hero">
                    <div>
                         <span class="pp-eyebrow">
                              <i class="bi bi-calendar2-range-fill"></i>
                              Detail Performance Period
                         </span>

                         <h1>{{ $performancePeriod->name }}</h1>

                         <p>
                              Detail lengkap periode penilaian dengan ID
                              <strong>#{{ $performancePeriod->id }}</strong>.
                         </p>
                    </div>

                    <div class="pp-actions">
                         <a href="{{ route('super-admin.performance-periods.index') }}" class="pp-btn pp-btn-back">
                              <i class="bi bi-arrow-left"></i>
                              Kembali
                         </a>

                         <a href="{{ route('super-admin.performance-periods.edit', $performancePeriod) }}"
                              class="pp-btn pp-btn-edit">
                              <i class="bi bi-pencil-fill"></i>
                              Edit
                         </a>

                         <form method="POST"
                              action="{{ route('super-admin.performance-periods.destroy', $performancePeriod) }}"
                              onsubmit="return confirm('Yakin ingin menghapus periode penilaian ini secara permanen?')">
                              @csrf
                              @method('DELETE')

                              <button type="submit" class="pp-btn pp-btn-delete">
                                   <i class="bi bi-trash3-fill"></i>
                                   Hapus
                              </button>
                         </form>
                    </div>
               </div>

               <div class="pp-card">
                    <div class="pp-card-header">
                         <div class="pp-card-header-icon">
                              <i class="bi bi-info-circle-fill"></i>
                         </div>

                         <div>
                              <h4>Informasi Periode Penilaian</h4>
                              <p>Data berasal dari tabel performance_periods.</p>
                         </div>
                    </div>

                    <div class="pp-card-body">
                         <div class="pp-summary-grid">
                              <div class="pp-summary">
                                   <span class="pp-summary-icon">
                                        <i class="bi bi-calendar-event"></i>
                                   </span>
                                   <small>Tanggal Mulai</small>
                                   <strong>{{ optional($performancePeriod->start_date)->format('d M Y') ?? '-' }}</strong>
                              </div>

                              <div class="pp-summary">
                                   <span class="pp-summary-icon">
                                        <i class="bi bi-calendar-check"></i>
                                   </span>
                                   <small>Tanggal Selesai</small>
                                   <strong>{{ optional($performancePeriod->end_date)->format('d M Y') ?? '-' }}</strong>
                              </div>

                              <div class="pp-summary">
                                   <span class="pp-summary-icon">
                                        <i class="bi bi-hourglass-split"></i>
                                   </span>
                                   <small>Durasi</small>
                                   <strong>{{ $duration !== null ? $duration . ' hari' : '-' }}</strong>
                              </div>

                              <div class="pp-summary">
                                   <span class="pp-summary-icon">
                                        <i class="bi bi-activity"></i>
                                   </span>
                                   <small>Kondisi Tanggal</small>
                                   <strong>{{ $isCurrent ? 'Sedang Berjalan' : 'Di Luar Rentang Aktif' }}</strong>
                              </div>
                         </div>

                         <div class="pp-detail-list">
                              <div class="pp-detail-row">
                                   <div class="pp-detail-label">ID Periode</div>
                                   <div class="pp-detail-value">#{{ $performancePeriod->id }}</div>
                              </div>

                              <div class="pp-detail-row">
                                   <div class="pp-detail-label">Nama Periode</div>
                                   <div class="pp-detail-value">{{ $performancePeriod->name }}</div>
                              </div>

                              <div class="pp-detail-row">
                                   <div class="pp-detail-label">Jenis Periode</div>
                                   <div class="pp-detail-value">
                                        <span class="pp-badge pp-badge-type">
                                             <i class="bi bi-calendar3"></i>
                                             {{ $periodTypeLabels[$type] ?? \Illuminate\Support\Str::of($type)->replace('_', ' ')->title() }}
                                        </span>
                                   </div>
                              </div>

                              <div class="pp-detail-row">
                                   <div class="pp-detail-label">Status</div>
                                   <div class="pp-detail-value">
                                        <span
                                             class="pp-badge pp-badge-{{ in_array($status, ['draft', 'active', 'completed', 'inactive'], true) ? $status : 'inactive' }}">
                                             <i class="bi {{ $statusIcons[$status] ?? 'bi-circle-fill' }}"></i>
                                             {{ $statusLabels[$status] ?? \Illuminate\Support\Str::of($status)->replace('_', ' ')->title() }}
                                        </span>
                                   </div>
                              </div>

                              <div class="pp-detail-row">
                                   <div class="pp-detail-label">Tanggal Dibuat</div>
                                   <div class="pp-detail-value">
                                        {{ optional($performancePeriod->created_at)->format('d M Y H:i:s') ?? '-' }}
                                   </div>
                              </div>

                              <div class="pp-detail-row">
                                   <div class="pp-detail-label">Terakhir Diperbarui</div>
                                   <div class="pp-detail-value">
                                        {{ optional($performancePeriod->updated_at)->format('d M Y H:i:s') ?? '-' }}
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
@endsection
