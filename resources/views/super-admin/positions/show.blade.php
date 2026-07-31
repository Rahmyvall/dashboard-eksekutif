@extends('layouts.app')

@section('title', 'Detail Jabatan')

@section('content')
     @php
          $authUser = auth()->user();

          $isSuperAdmin = isset($isSuperAdmin)
              ? (bool) $isSuperAdmin
              : $authUser && method_exists($authUser, 'hasRole') && $authUser->hasRole('super_admin');

          $isActive = $position->status === \App\Models\Position::STATUS_ACTIVE;

          $levelLabel = match ((int) $position->level) {
              1 => 'Staff',
              2 => 'Senior Staff',
              3 => 'Supervisor',
              4 => 'Manager',
              5 => 'Direktur',
              default => 'Level ' . $position->level,
          };

          $initials = \Illuminate\Support\Str::of($position->name)
              ->explode(' ')
              ->filter()
              ->take(2)
              ->map(static fn($word) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($word, 0, 1)))
              ->implode('');

          $initials = $initials !== '' ? $initials : 'JB';
     @endphp

     <style>
          :root {
               --show-primary: #6366f1;
               --show-purple: #8b5cf6;
               --show-cyan: #06b6d4;
               --show-success: #10b981;
               --show-danger: #ef4444;
               --show-text: #24324a;
               --show-muted: #718096;
          }

          .position-show-page,
          .position-show-page * {
               box-sizing: border-box;
          }

          .position-show-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 4%, rgba(129, 140, 248, .18), transparent 25%),
                    radial-gradient(circle at 96% 7%, rgba(34, 211, 238, .18), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f8f6ff 48%, #f0fbff 100%);
          }

          .position-show-container {
               width: 100%;
               max-width: 1580px;
               margin: 0 auto;
          }

          .position-show-hero {
               position: relative;
               overflow: hidden;
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 26px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .32), transparent 24%),
                    linear-gradient(120deg, #6366f1 0%, #8b5cf6 48%, #06b6d4 100%);
               box-shadow: 0 22px 48px rgba(99, 102, 241, .20);
          }

          .position-show-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
          }

          .position-show-title-wrap {
               display: flex;
               min-width: 0;
               gap: 16px;
               align-items: center;
          }

          .position-show-avatar {
               display: inline-flex;
               flex: 0 0 72px;
               width: 72px;
               height: 72px;
               color: #4f46e5;
               font-size: 1.25rem;
               font-weight: 900;
               align-items: center;
               justify-content: center;
               border-radius: 22px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .position-show-hero h1 {
               margin: 0;
               font-size: clamp(1.58rem, 2.4vw, 2.15rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .position-show-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .92);
               font-size: .9rem;
          }

          .position-show-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               justify-content: flex-end;
          }

          .position-show-btn {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .82rem;
               font-weight: 810;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .position-show-btn svg {
               width: 17px;
               height: 17px;
          }

          .position-show-btn-soft {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .15);
          }

          .position-show-btn-soft:hover {
               color: #fff;
               text-decoration: none;
               background: rgba(255, 255, 255, .25);
               transform: translateY(-2px);
          }

          .position-show-btn-light {
               color: #4338ca;
               border: 1px solid rgba(255, 255, 255, .75);
               background: rgba(255, 255, 255, .96);
          }

          .position-show-btn-light:hover {
               color: #312e81;
               text-decoration: none;
               background: #fff;
               transform: translateY(-2px);
          }

          .position-show-grid {
               display: grid;
               grid-template-columns: minmax(0, 2.35fr) minmax(320px, .85fr);
               gap: 22px;
               align-items: start;
          }

          .position-show-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .95);
               border-radius: 23px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 42px rgba(51, 65, 85, .085);
          }

          .position-show-card-header {
               padding: 21px 24px;
               border-bottom: 1px solid #edf1f7;
               background: linear-gradient(90deg, #fff, #faf8ff, #f1fbff);
          }

          .position-show-card-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--show-text);
               font-size: 1rem;
               font-weight: 830;
          }

          .position-show-card-title span {
               display: inline-flex;
               width: 40px;
               height: 40px;
               color: #5b21b6;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: #f5f3ff;
          }

          .position-show-card-title svg {
               width: 19px;
               height: 19px;
          }

          .position-show-card-body {
               padding: 24px;
          }

          .position-detail-list {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 16px;
          }

          .position-detail-item {
               min-height: 108px;
               padding: 17px;
               border: 1px solid #edf1f7;
               border-radius: 17px;
               background: linear-gradient(145deg, #fff, #fbfdff);
          }

          .position-detail-label {
               display: flex;
               gap: 7px;
               align-items: center;
               margin-bottom: 9px;
               color: #94a3b8;
               font-size: .72rem;
               font-weight: 820;
               letter-spacing: .055em;
               text-transform: uppercase;
          }

          .position-detail-label svg {
               width: 14px;
               height: 14px;
          }

          .position-detail-value {
               color: #334155;
               font-size: .91rem;
               font-weight: 810;
               line-height: 1.5;
          }

          .position-code-badge,
          .position-level-badge,
          .position-status-badge {
               display: inline-flex;
               padding: 7px 10px;
               gap: 7px;
               align-items: center;
               font-size: .75rem;
               font-weight: 840;
               border-radius: 10px;
          }

          .position-code-badge {
               color: #5b21b6;
               border: 1px solid #ddd6fe;
               background: #f5f3ff;
          }

          .position-level-badge {
               color: #9a3412;
               border: 1px solid #fed7aa;
               background: #fff7ed;
          }

          .position-status-badge.is-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .position-status-badge.is-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .position-status-dot {
               width: 7px;
               height: 7px;
               border-radius: 999px;
               background: currentColor;
          }

          .position-description-box {
               padding: 18px;
               margin-top: 17px;
               color: #64748b;
               font-size: .87rem;
               line-height: 1.75;
               white-space: pre-line;
               border: 1px solid #edf1f7;
               border-radius: 17px;
               background: #fbfcff;
          }

          .position-summary-card {
               padding: 22px;
          }

          .position-summary-icon {
               display: inline-flex;
               width: 54px;
               height: 54px;
               margin-bottom: 14px;
               color: #4f46e5;
               align-items: center;
               justify-content: center;
               border-radius: 17px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .position-summary-icon svg {
               width: 24px;
               height: 24px;
          }

          .position-summary-title {
               margin: 0 0 5px;
               color: var(--show-text);
               font-size: 1rem;
               font-weight: 830;
          }

          .position-summary-text {
               margin: 0 0 18px;
               color: var(--show-muted);
               font-size: .8rem;
               line-height: 1.6;
          }

          .position-summary-row {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 13px 0;
               border-top: 1px solid #edf1f7;
          }

          .position-summary-row-icon {
               display: inline-flex;
               flex: 0 0 37px;
               width: 37px;
               height: 37px;
               color: #6366f1;
               align-items: center;
               justify-content: center;
               border-radius: 11px;
               background: #eef2ff;
          }

          .position-summary-row-icon svg {
               width: 17px;
               height: 17px;
          }

          .position-summary-row small {
               display: block;
               margin-bottom: 2px;
               color: #94a3b8;
               font-size: .69rem;
               font-weight: 750;
          }

          .position-summary-row strong {
               color: #475569;
               font-size: .8rem;
          }

          .position-danger-zone {
               margin-top: 22px;
               border-color: #fecdd3;
          }

          .position-danger-zone .position-show-card-header {
               background: linear-gradient(90deg, #fff, #fff1f2);
          }

          .position-delete-button {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #be123c;
               font-size: .81rem;
               font-weight: 810;
               border: 1px solid #fecdd3;
               border-radius: 12px;
               background: #fff1f2;
               transition: .2s ease;
          }

          .position-delete-button:hover {
               color: #9f1239;
               background: #ffe4e6;
               transform: translateY(-2px);
          }

          .position-delete-button svg {
               width: 17px;
               height: 17px;
          }

          @media (max-width: 1199.98px) {
               .position-detail-list {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 991.98px) {
               .position-show-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767.98px) {
               .position-show-page {
                    padding: 20px 11px 34px;
               }

               .position-show-hero {
                    padding: 23px 20px;
               }

               .position-show-hero-content {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .position-show-title-wrap {
                    align-items: flex-start;
               }

               .position-show-actions {
                    width: 100%;
                    justify-content: stretch;
               }

               .position-show-btn {
                    flex: 1 1 auto;
               }

               .position-detail-list {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 479.98px) {
               .position-show-title-wrap {
                    flex-direction: column;
               }

               .position-show-actions {
                    flex-direction: column;
               }

               .position-show-btn {
                    width: 100%;
               }
          }
     </style>

     <div class="position-show-page">
          <div class="position-show-container">
               <section class="position-show-hero">
                    <div class="position-show-hero-content">
                         <div class="position-show-title-wrap">
                              <span class="position-show-avatar">{{ $initials }}</span>

                              <div>
                                   <h1>{{ $position->name }}</h1>
                                   <p>
                                        {{ $position->code }} ·
                                        {{ $position->department?->name ?? 'Departemen tidak tersedia' }}
                                   </p>
                              </div>
                         </div>

                         <div class="position-show-actions">
                              <a href="{{ route('super-admin.positions.index') }}"
                                   class="position-show-btn position-show-btn-soft">
                                   <i data-feather="arrow-left"></i>
                                   <span>Kembali</span>
                              </a>

                              @if ($isSuperAdmin && \Illuminate\Support\Facades\Route::has('super-admin.positions.edit'))
                                   <a href="{{ route('super-admin.positions.edit', $position) }}"
                                        class="position-show-btn position-show-btn-light">
                                        <i data-feather="edit-3"></i>
                                        <span>Edit Jabatan</span>
                                   </a>
                              @endif
                         </div>
                    </div>
               </section>

               <div class="position-show-grid">
                    <div>
                         <section class="position-show-card">
                              <header class="position-show-card-header">
                                   <h2 class="position-show-card-title">
                                        <span><i data-feather="briefcase"></i></span>
                                        <strong>Informasi Jabatan</strong>
                                   </h2>
                              </header>

                              <div class="position-show-card-body">
                                   <div class="position-detail-list">
                                        <div class="position-detail-item">
                                             <div class="position-detail-label">
                                                  <i data-feather="hash"></i>
                                                  Kode Jabatan
                                             </div>

                                             <div class="position-detail-value">
                                                  <span class="position-code-badge">
                                                       {{ $position->code }}
                                                  </span>
                                             </div>
                                        </div>

                                        <div class="position-detail-item">
                                             <div class="position-detail-label">
                                                  <i data-feather="briefcase"></i>
                                                  Nama Jabatan
                                             </div>

                                             <div class="position-detail-value">
                                                  {{ $position->name }}
                                             </div>
                                        </div>

                                        <div class="position-detail-item">
                                             <div class="position-detail-label">
                                                  <i data-feather="grid"></i>
                                                  Departemen
                                             </div>

                                             <div class="position-detail-value">
                                                  {{ $position->department?->name ?? 'Departemen tidak tersedia' }}

                                                  @if ($position->department?->code)
                                                       <small class="d-block text-muted mt-1">
                                                            {{ $position->department->code }}
                                                       </small>
                                                  @endif
                                             </div>
                                        </div>

                                        <div class="position-detail-item">
                                             <div class="position-detail-label">
                                                  <i data-feather="bar-chart-2"></i>
                                                  Level
                                             </div>

                                             <div class="position-detail-value">
                                                  <span class="position-level-badge">
                                                       Level {{ $position->level }} · {{ $levelLabel }}
                                                  </span>
                                             </div>
                                        </div>

                                        <div class="position-detail-item">
                                             <div class="position-detail-label">
                                                  <i data-feather="activity"></i>
                                                  Status
                                             </div>

                                             <div class="position-detail-value">
                                                  <span
                                                       class="position-status-badge {{ $isActive ? 'is-active' : 'is-inactive' }}">
                                                       <span class="position-status-dot"></span>
                                                       {{ $isActive ? 'Aktif' : 'Tidak Aktif' }}
                                                  </span>
                                             </div>
                                        </div>

                                        <div class="position-detail-item">
                                             <div class="position-detail-label">
                                                  <i data-feather="calendar"></i>
                                                  Dibuat
                                             </div>

                                             <div class="position-detail-value">
                                                  {{ $position->created_at?->format('d M Y, H:i') ?? '—' }} WIB
                                             </div>
                                        </div>
                                   </div>

                                   <div class="position-detail-label mt-4">
                                        <i data-feather="file-text"></i>
                                        Deskripsi Jabatan
                                   </div>

                                   <div class="position-description-box">
                                        {{ filled($position->description) ? $position->description : 'Belum ada deskripsi untuk jabatan ini.' }}
                                   </div>
                              </div>
                         </section>

                         @if ($isSuperAdmin && \Illuminate\Support\Facades\Route::has('super-admin.positions.destroy'))
                              <section class="position-show-card position-danger-zone">
                                   <header class="position-show-card-header">
                                        <h2 class="position-show-card-title">
                                             <span><i data-feather="alert-triangle"></i></span>
                                             <strong>Zona Penghapusan</strong>
                                        </h2>
                                   </header>

                                   <div class="position-show-card-body">
                                        <p class="position-summary-text">
                                             Jabatan yang dihapus akan dipindahkan ke sampah dan masih
                                             dapat dikembalikan oleh Super Admin.
                                        </p>

                                        <form method="POST"
                                             action="{{ route('super-admin.positions.destroy', $position) }}"
                                             onsubmit="return confirm(
                                          'Yakin ingin menghapus jabatan {{ addslashes($position->name) }}?'
                                      );">
                                             @csrf
                                             @method('DELETE')

                                             <button type="submit" class="position-delete-button">
                                                  <i data-feather="trash-2"></i>
                                                  <span>Hapus Jabatan</span>
                                             </button>
                                        </form>
                                   </div>
                              </section>
                         @endif
                    </div>

                    <aside>
                         <section class="position-show-card">
                              <div class="position-summary-card">
                                   <span class="position-summary-icon">
                                        <i data-feather="info"></i>
                                   </span>

                                   <h2 class="position-summary-title">
                                        Ringkasan Data
                                   </h2>

                                   <p class="position-summary-text">
                                        Informasi pencatatan dan pembaruan data jabatan.
                                   </p>

                                   <div class="position-summary-row">
                                        <span class="position-summary-row-icon">
                                             <i data-feather="calendar"></i>
                                        </span>

                                        <div>
                                             <small>Tanggal dibuat</small>
                                             <strong>
                                                  {{ $position->created_at?->format('d M Y') ?? '—' }}
                                             </strong>
                                        </div>
                                   </div>

                                   <div class="position-summary-row">
                                        <span class="position-summary-row-icon">
                                             <i data-feather="clock"></i>
                                        </span>

                                        <div>
                                             <small>Terakhir diperbarui</small>
                                             <strong>
                                                  {{ $position->updated_at?->format('d M Y, H:i') ?? '—' }} WIB
                                             </strong>
                                        </div>
                                   </div>

                                   <div class="position-summary-row">
                                        <span class="position-summary-row-icon">
                                             <i data-feather="database"></i>
                                        </span>

                                        <div>
                                             <small>ID data</small>
                                             <strong>#{{ $position->getKey() }}</strong>
                                        </div>
                                   </div>

                                   <div class="position-summary-row">
                                        <span class="position-summary-row-icon">
                                             <i data-feather="shield"></i>
                                        </span>

                                        <div>
                                             <small>Status penggunaan</small>
                                             <strong>
                                                  {{ $isActive ? 'Dapat digunakan' : 'Dinonaktifkan' }}
                                             </strong>
                                        </div>
                                   </div>
                              </div>
                         </section>
                    </aside>
               </div>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
