@extends('layouts.app')

@section('title', 'Tambah Periode Penilaian')

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
               max-width: 1480px;
               margin: 0 auto;
          }

          .pp-hero,
          .pp-card,
          .pp-info-card {
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

          .pp-back {
               display: inline-flex;
               min-height: 48px;
               padding: 0 20px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid #c7d2fe;
               border-radius: 15px;
               background: #fff;
          }

          .pp-alert {
               display: flex;
               gap: 14px;
               padding: 18px 20px;
               margin-bottom: 22px;
               color: #991b1b;
               border: 1px solid #fecaca;
               border-radius: 18px;
               background: #fef2f2;
          }

          .pp-card,
          .pp-info-card {
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

          .pp-section {
               padding: 22px;
               margin-bottom: 20px;
               border: 1px solid #e0e7ff;
               border-radius: 19px;
               background: linear-gradient(145deg, #fff, #fafbff);
          }

          .pp-section:last-child {
               margin-bottom: 0;
          }

          .pp-section-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin-bottom: 18px;
          }

          .pp-section-number {
               display: grid;
               width: 38px;
               height: 38px;
               place-items: center;
               color: #fff;
               font-size: .8rem;
               font-weight: 850;
               border-radius: 12px;
               background: linear-gradient(135deg, var(--pp-primary), #8b5cf6);
          }

          .pp-section-title h5 {
               margin: 0;
               color: var(--pp-text);
               font-weight: 850;
          }

          .pp-section-title small {
               color: var(--pp-muted);
          }

          .pp-form .form-label {
               margin-bottom: 8px;
               color: var(--pp-text);
               font-size: .87rem;
               font-weight: 800;
          }

          .required {
               color: var(--pp-danger);
          }

          .pp-field {
               position: relative;
          }

          .pp-field>i {
               position: absolute;
               top: 50%;
               left: 15px;
               z-index: 2;
               color: #64748b;
               transform: translateY(-50%);
          }

          .pp-form .form-control,
          .pp-form .form-select {
               min-height: 52px;
               padding-left: 44px;
               color: var(--pp-text);
               border: 1px solid #cbd5e1;
               border-radius: 15px;
          }

          .pp-form .form-control:focus,
          .pp-form .form-select:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .24rem rgba(99, 102, 241, .13);
          }

          .pp-hint {
               display: flex;
               gap: 6px;
               margin-top: 8px;
               color: var(--pp-muted);
               font-size: .75rem;
          }

          .pp-status-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .pp-status-item {
               position: relative;
          }

          .pp-status-item input {
               position: absolute;
               opacity: 0;
          }

          .pp-status-label {
               display: flex;
               min-height: 86px;
               padding: 15px;
               gap: 12px;
               align-items: center;
               margin: 0;
               cursor: pointer;
               border: 2px solid #e2e8f0;
               border-radius: 17px;
               background: #fff;
          }

          .pp-status-label .icon {
               display: grid;
               width: 43px;
               height: 43px;
               place-items: center;
               color: #fff;
               border-radius: 13px;
               background: linear-gradient(135deg, var(--pp-primary), var(--pp-secondary));
          }

          .pp-status-label strong {
               display: block;
               color: var(--pp-text);
          }

          .pp-status-label small {
               display: block;
               margin-top: 2px;
               color: var(--pp-muted);
               line-height: 1.4;
          }

          .pp-status-item input:checked+.pp-status-label {
               border-color: var(--pp-primary);
               box-shadow: 0 0 0 4px rgba(99, 102, 241, .12);
          }

          .pp-actions {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 14px;
               padding: 21px 26px;
               border-top: 1px solid #e0e7ff;
               background: linear-gradient(100deg, #fbfdff, #f5f3ff, #ecfeff);
          }

          .pp-action-buttons {
               display: flex;
               gap: 11px;
          }

          .pp-cancel,
          .pp-save {
               display: inline-flex;
               min-height: 48px;
               padding: 0 21px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-weight: 850;
               text-decoration: none;
               border-radius: 14px;
          }

          .pp-cancel {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #fff;
          }

          .pp-save {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, var(--pp-primary), #8b5cf6, var(--pp-secondary));
          }

          .pp-sidebar {
               display: grid;
               gap: 20px;
          }

          .pp-info-accent {
               height: 6px;
               background: linear-gradient(90deg, var(--pp-primary), #8b5cf6, var(--pp-secondary));
          }

          .pp-info-body {
               padding: 22px;
          }

          .pp-info-body h5 {
               margin-bottom: 16px;
               color: var(--pp-text);
               font-weight: 850;
          }

          .pp-guide {
               display: grid;
               gap: 14px;
               padding: 0;
               margin: 0;
               list-style: none;
          }

          .pp-guide li {
               display: flex;
               gap: 11px;
               align-items: flex-start;
          }

          .pp-guide .check {
               display: grid;
               flex: 0 0 auto;
               width: 29px;
               height: 29px;
               place-items: center;
               color: #fff;
               border-radius: 9px;
               background: linear-gradient(135deg, var(--pp-success), var(--pp-secondary));
          }

          .pp-guide strong {
               display: block;
               color: var(--pp-text);
               font-size: .83rem;
          }

          .pp-guide p {
               margin: 2px 0 0;
               color: var(--pp-muted);
               font-size: .76rem;
               line-height: 1.5;
          }

          .pp-db-list {
               display: grid;
               gap: 9px;
          }

          .pp-db-item {
               padding: 11px 13px;
               border: 1px solid #dbeafe;
               border-radius: 13px;
               background: #f8fbff;
          }

          .pp-db-item strong {
               display: block;
               color: var(--pp-text);
               font-size: .81rem;
          }

          .pp-db-item small {
               color: var(--pp-muted);
               font-size: .72rem;
          }

          @media (max-width: 991.98px) {

               .pp-hero,
               .pp-actions {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }

          @media (max-width: 767.98px) {
               .pp-page {
                    padding: 15px;
               }

               .pp-status-grid {
                    grid-template-columns: 1fr;
               }

               .pp-action-buttons,
               .pp-cancel,
               .pp-save,
               .pp-back {
                    width: 100%;
               }

               .pp-action-buttons {
                    flex-direction: column-reverse;
               }
          }
     </style>

     @php
          $availablePeriodTypes = collect($periodTypes ?? ['monthly', 'quarterly', 'semester', 'annual']);

          $availableStatuses = collect($statuses ?? ['draft', 'active', 'completed', 'inactive']);

          $periodTypeLabels = [
              'monthly' => 'Bulanan',
              'quarterly' => 'Kuartalan',
              'semester' => 'Semester',
              'annual' => 'Tahunan',
          ];

          $statusConfig = [
              'draft' => [
                  'label' => 'Draft',
                  'description' => 'Masih dipersiapkan dan belum digunakan.',
                  'icon' => 'bi-pencil-square',
              ],
              'active' => [
                  'label' => 'Aktif',
                  'description' => 'Dapat digunakan untuk proses penilaian.',
                  'icon' => 'bi-play-circle-fill',
              ],
              'completed' => [
                  'label' => 'Selesai',
                  'description' => 'Proses penilaian pada periode ini telah ditutup.',
                  'icon' => 'bi-flag-fill',
              ],
              'inactive' => [
                  'label' => 'Tidak Aktif',
                  'description' => 'Disimpan tetapi tidak digunakan.',
                  'icon' => 'bi-pause-circle-fill',
              ],
          ];
     @endphp

     <div class="pp-page">
          <div class="pp-container">
               <div class="pp-hero">
                    <div>
                         <span class="pp-eyebrow">
                              <i class="bi bi-calendar2-range-fill"></i>
                              Master Data Performance Period
                         </span>

                         <h1>Tambah Periode Penilaian</h1>

                         <p>
                              Tambahkan nama periode, tanggal mulai, tanggal selesai,
                              jenis periode, dan status sesuai tabel
                              <strong>performance_periods</strong>.
                         </p>
                    </div>

                    <a href="{{ route('super-admin.performance-periods.index') }}" class="pp-back">
                         <i class="bi bi-arrow-left"></i>
                         Kembali ke Daftar
                    </a>
               </div>

               @if ($errors->any())
                    <div class="pp-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill fs-4"></i>

                         <div>
                              <strong>Data belum dapat disimpan.</strong>
                              <ul class="mb-0 mt-2">
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    </div>
               @endif

               <div class="row g-4">
                    <div class="col-xl-8">
                         <form method="POST" action="{{ route('super-admin.performance-periods.store') }}"
                              class="pp-card pp-form" id="performancePeriodForm">
                              @csrf

                              <div class="pp-card-header">
                                   <div class="pp-card-header-icon">
                                        <i class="bi bi-ui-checks-grid"></i>
                                   </div>

                                   <div>
                                        <h4>Form Periode Penilaian</h4>
                                        <p>Semua kolom bertanda bintang wajib diisi.</p>
                                   </div>
                              </div>

                              <div class="pp-card-body">
                                   <div class="pp-section">
                                        <div class="pp-section-title">
                                             <span class="pp-section-number">01</span>
                                             <div>
                                                  <h5>Identitas Periode</h5>
                                                  <small>Masukkan nama periode yang jelas.</small>
                                             </div>
                                        </div>

                                        <label for="name" class="form-label">
                                             Nama Periode <span class="required">*</span>
                                        </label>

                                        <div class="pp-field">
                                             <i class="bi bi-tag-fill"></i>
                                             <input type="text" id="name" name="name" maxlength="255"
                                                  value="{{ old('name') }}"
                                                  class="form-control @error('name') is-invalid @enderror"
                                                  placeholder="Contoh: Penilaian Kinerja Tahunan 2026" required autofocus
                                                  autocomplete="off">
                                        </div>

                                        @error('name')
                                             <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <div class="pp-hint">
                                             <i class="bi bi-info-circle"></i>
                                             Maksimal 255 karakter.
                                        </div>
                                   </div>

                                   <div class="pp-section">
                                        <div class="pp-section-title">
                                             <span class="pp-section-number">02</span>
                                             <div>
                                                  <h5>Rentang Tanggal</h5>
                                                  <small>Tentukan awal dan akhir periode penilaian.</small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-md-6">
                                                  <label for="start_date" class="form-label">
                                                       Tanggal Mulai <span class="required">*</span>
                                                  </label>

                                                  <div class="pp-field">
                                                       <i class="bi bi-calendar-event"></i>
                                                       <input type="date" id="start_date" name="start_date"
                                                            value="{{ old('start_date') }}"
                                                            class="form-control @error('start_date') is-invalid @enderror"
                                                            required>
                                                  </div>

                                                  @error('start_date')
                                                       <div class="invalid-feedback d-block">{{ $message }}</div>
                                                  @enderror
                                             </div>

                                             <div class="col-md-6">
                                                  <label for="end_date" class="form-label">
                                                       Tanggal Selesai <span class="required">*</span>
                                                  </label>

                                                  <div class="pp-field">
                                                       <i class="bi bi-calendar-check"></i>
                                                       <input type="date" id="end_date" name="end_date"
                                                            value="{{ old('end_date') }}"
                                                            class="form-control @error('end_date') is-invalid @enderror"
                                                            required>
                                                  </div>

                                                  @error('end_date')
                                                       <div class="invalid-feedback d-block">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>

                                        <div class="pp-hint">
                                             <i class="bi bi-info-circle"></i>
                                             Tanggal selesai tidak boleh lebih awal dari tanggal mulai.
                                        </div>
                                   </div>

                                   <div class="pp-section">
                                        <div class="pp-section-title">
                                             <span class="pp-section-number">03</span>
                                             <div>
                                                  <h5>Jenis Periode</h5>
                                                  <small>Pilih pola periode yang digunakan.</small>
                                             </div>
                                        </div>

                                        <label for="period_type" class="form-label">
                                             Jenis Periode <span class="required">*</span>
                                        </label>

                                        <div class="pp-field">
                                             <i class="bi bi-calendar3"></i>
                                             <select id="period_type" name="period_type"
                                                  class="form-select @error('period_type') is-invalid @enderror" required>
                                                  <option value="">Pilih jenis periode</option>

                                                  @foreach ($availablePeriodTypes as $type)
                                                       <option value="{{ $type }}" @selected(old('period_type') === (string) $type)>
                                                            {{ $periodTypeLabels[$type] ?? \Illuminate\Support\Str::of($type)->replace('_', ' ')->title() }}
                                                       </option>
                                                  @endforeach
                                             </select>
                                        </div>

                                        @error('period_type')
                                             <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="pp-section">
                                        <div class="pp-section-title">
                                             <span class="pp-section-number">04</span>
                                             <div>
                                                  <h5>Status Periode</h5>
                                                  <small>Tentukan kondisi awal periode.</small>
                                             </div>
                                        </div>

                                        <div class="pp-status-grid">
                                             @foreach ($availableStatuses as $status)
                                                  @php
                                                       $config = $statusConfig[$status] ?? [
                                                           'label' => \Illuminate\Support\Str::of($status)
                                                               ->replace('_', ' ')
                                                               ->title(),
                                                           'description' => 'Status periode penilaian.',
                                                           'icon' => 'bi-circle-fill',
                                                       ];
                                                  @endphp

                                                  <div class="pp-status-item">
                                                       <input type="radio" id="status_{{ $status }}" name="status"
                                                            value="{{ $status }}" @checked(old('status', 'draft') === (string) $status)
                                                            required>

                                                       <label for="status_{{ $status }}" class="pp-status-label">
                                                            <span class="icon">
                                                                 <i class="bi {{ $config['icon'] }}"></i>
                                                            </span>

                                                            <span>
                                                                 <strong>{{ $config['label'] }}</strong>
                                                                 <small>{{ $config['description'] }}</small>
                                                            </span>
                                                       </label>
                                                  </div>
                                             @endforeach
                                        </div>

                                        @error('status')
                                             <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                   </div>
                              </div>

                              <div class="pp-actions">
                                   <small class="text-secondary">
                                        <i class="bi bi-shield-check text-success me-1"></i>
                                        Periksa kembali data sebelum disimpan.
                                   </small>

                                   <div class="pp-action-buttons">
                                        <a href="{{ route('super-admin.performance-periods.index') }}" class="pp-cancel">
                                             <i class="bi bi-x-lg"></i>
                                             Batal
                                        </a>

                                        <button type="submit" class="pp-save" id="submitButton">
                                             <i class="bi bi-check2-circle"></i>
                                             Simpan Periode
                                        </button>
                                   </div>
                              </div>
                         </form>
                    </div>

                    <div class="col-xl-4">
                         <div class="pp-sidebar">
                              <div class="pp-info-card">
                                   <div class="pp-info-accent"></div>

                                   <div class="pp-info-body">
                                        <h5>
                                             <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                                             Panduan Pengisian
                                        </h5>

                                        <ul class="pp-guide">
                                             <li>
                                                  <span class="check"><i class="bi bi-check-lg"></i></span>
                                                  <div>
                                                       <strong>Gunakan nama spesifik</strong>
                                                       <p>Tambahkan tahun atau cakupan periode agar mudah dibedakan.</p>
                                                  </div>
                                             </li>

                                             <li>
                                                  <span class="check"><i class="bi bi-check-lg"></i></span>
                                                  <div>
                                                       <strong>Periksa rentang tanggal</strong>
                                                       <p>Tanggal selesai harus sama dengan atau setelah tanggal mulai.</p>
                                                  </div>
                                             </li>

                                             <li>
                                                  <span class="check"><i class="bi bi-check-lg"></i></span>
                                                  <div>
                                                       <strong>Gunakan Draft lebih dahulu</strong>
                                                       <p>Pilih Draft apabila periode belum siap digunakan.</p>
                                                  </div>
                                             </li>
                                        </ul>
                                   </div>
                              </div>

                              <div class="pp-info-card">
                                   <div class="pp-info-accent"></div>

                                   <div class="pp-info-body">
                                        <h5>
                                             <i class="bi bi-database-fill-check text-primary me-2"></i>
                                             Kolom Database
                                        </h5>

                                        <div class="pp-db-list">
                                             <div class="pp-db-item">
                                                  <strong>name</strong>
                                                  <small>Wajib, maksimal 255 karakter</small>
                                             </div>

                                             <div class="pp-db-item">
                                                  <strong>start_date</strong>
                                                  <small>Wajib, tipe date</small>
                                             </div>

                                             <div class="pp-db-item">
                                                  <strong>end_date</strong>
                                                  <small>Wajib, tipe date</small>
                                             </div>

                                             <div class="pp-db-item">
                                                  <strong>period_type</strong>
                                                  <small>monthly, quarterly, semester, annual</small>
                                             </div>

                                             <div class="pp-db-item">
                                                  <strong>status</strong>
                                                  <small>draft, active, completed, inactive</small>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="pp-info-card">
                                   <div class="pp-info-accent"></div>

                                   <div class="pp-info-body">
                                        <div class="alert alert-info mb-0">
                                             <strong>Informasi penyimpanan</strong>
                                             <p class="mb-0 mt-1 small">
                                                  Data disimpan ke tabel <b>performance_periods</b>.
                                                  Tabel saat ini belum menggunakan soft delete.
                                             </p>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     @push('scripts')
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('performancePeriodForm');
                    const startDate = document.getElementById('start_date');
                    const endDate = document.getElementById('end_date');
                    const submitButton = document.getElementById('submitButton');

                    function syncDateRange() {
                         if (!startDate || !endDate) {
                              return;
                         }

                         endDate.min = startDate.value || '';

                         if (
                              startDate.value !== '' &&
                              endDate.value !== '' &&
                              endDate.value < startDate.value
                         ) {
                              endDate.value = startDate.value;
                         }
                    }

                    startDate?.addEventListener('change', syncDateRange);
                    syncDateRange();

                    form?.addEventListener('submit', function() {
                         if (!submitButton) {
                              return;
                         }

                         submitButton.disabled = true;
                         submitButton.innerHTML =
                              '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
                    });
               });
          </script>
     @endpush
@endsection
