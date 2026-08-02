@php
     /*
     |--------------------------------------------------------------------------
     | Partial Form Jadwal Kerja
     |--------------------------------------------------------------------------
     |
     | Partial ini tidak memiliki tag <form>. Gunakan dari create/edit.
     | Variabel $workSchedule boleh null pada halaman create.
     |
     */

     $workSchedule = $workSchedule ?? null;

     $formatScheduleTime = static function ($value, string $default = ''): string {
         if (blank($value)) {
             return $default;
         }

         if ($value instanceof \DateTimeInterface) {
             return $value->format('H:i');
         }

         $time = trim((string) $value);

         if (preg_match('/^(\d{2}):(\d{2})/', $time, $matches) === 1) {
             return $matches[1] . ':' . $matches[2];
         }

         try {
             return \Illuminate\Support\Carbon::parse($time)->format('H:i');
         } catch (\Throwable) {
             return $time;
         }
     };

     $selectedStatus = old('status', $workSchedule?->status ?? 'active');
@endphp

@if ($errors->any())
     <div class="position-error-summary">
          <i data-feather="alert-circle"></i>

          <div>
               <strong>Periksa kembali data yang Anda masukkan.</strong>

               <ul>
                    @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
               </ul>
          </div>
     </div>
@endif

<div class="position-section">
     <div class="position-section-heading">
          <span><i data-feather="info"></i></span>
          <strong>Data Utama</strong>
     </div>

     <div class="row g-3">
          <div class="col-12">
               <label for="name" class="position-label">
                    Nama Jadwal <span class="position-required">*</span>
               </label>

               <div class="position-input-shell">
                    <i data-feather="calendar"></i>

                    <input type="text" id="name" name="name" maxlength="100"
                         value="{{ old('name', $workSchedule?->name ?? '') }}"
                         class="form-control position-control @error('name') is-invalid @enderror"
                         placeholder="Contoh: Jadwal Reguler" required autofocus>
               </div>

               <small class="position-help">
                    Gunakan nama yang mudah dikenali, misalnya Jadwal Reguler atau Shift Pagi.
               </small>

               @error('name')
                    <span class="position-invalid-feedback">{{ $message }}</span>
               @enderror
          </div>

          <div class="col-12 col-md-6">
               <label for="start_time" class="position-label">
                    Jam Masuk <span class="position-required">*</span>
               </label>

               <div class="position-input-shell">
                    <i data-feather="log-in"></i>

                    <input type="time" id="start_time" name="start_time" step="60"
                         value="{{ old('start_time', $formatScheduleTime($workSchedule?->start_time, '08:00')) }}"
                         class="form-control position-control @error('start_time') is-invalid @enderror" required>
               </div>

               @error('start_time')
                    <span class="position-invalid-feedback">{{ $message }}</span>
               @enderror
          </div>

          <div class="col-12 col-md-6">
               <label for="end_time" class="position-label">
                    Jam Pulang <span class="position-required">*</span>
               </label>

               <div class="position-input-shell">
                    <i data-feather="log-out"></i>

                    <input type="time" id="end_time" name="end_time" step="60"
                         value="{{ old('end_time', $formatScheduleTime($workSchedule?->end_time, '16:00')) }}"
                         class="form-control position-control @error('end_time') is-invalid @enderror" required>
               </div>

               @error('end_time')
                    <span class="position-invalid-feedback">{{ $message }}</span>
               @enderror
          </div>
     </div>
</div>

<div class="position-section">
     <div class="position-section-heading">
          <span><i data-feather="sliders"></i></span>
          <strong>Ketentuan Jam Kerja</strong>
     </div>

     <div class="row g-3">
          <div class="col-12">
               <label for="late_tolerance_minutes" class="position-label">
                    Toleransi Keterlambatan
               </label>

               <div class="position-input-shell">
                    <i data-feather="alert-triangle"></i>

                    <input type="number" id="late_tolerance_minutes" name="late_tolerance_minutes" min="0"
                         max="65535" step="1"
                         value="{{ old('late_tolerance_minutes', $workSchedule?->late_tolerance_minutes ?? 0) }}"
                         class="form-control position-control @error('late_tolerance_minutes') is-invalid @enderror"
                         placeholder="0">
               </div>

               <small class="position-help">
                    Masukkan jumlah toleransi dalam menit. Isi 0 jika tidak ada toleransi.
               </small>

               @error('late_tolerance_minutes')
                    <span class="position-invalid-feedback">{{ $message }}</span>
               @enderror
          </div>

          <div class="col-12">
               <label for="working_hours" class="position-label">
                    Total Jam Kerja <span class="position-required">*</span>
               </label>

               <div class="position-input-shell">
                    <i data-feather="clock"></i>

                    <input type="number" id="working_hours" name="working_hours" min="0" max="999.99"
                         step="0.01" value="{{ old('working_hours', $workSchedule?->working_hours ?? '8.00') }}"
                         class="form-control position-control @error('working_hours') is-invalid @enderror"
                         placeholder="8.00" required>
               </div>

               <small class="position-help">
                    Masukkan jumlah jam kerja dalam format desimal, misalnya 7.50 atau 8.00.
               </small>

               @error('working_hours')
                    <span class="position-invalid-feedback">{{ $message }}</span>
               @enderror
          </div>
     </div>
</div>

<div class="position-section">
     <div class="position-section-heading">
          <span><i data-feather="activity"></i></span>
          <strong>Status Jadwal</strong>
     </div>

     <div class="position-status-options">
          <div class="position-status-option">
               <input type="radio" id="status-active" name="status" value="active" @checked($selectedStatus === 'active')
                    required>

               <label for="status-active" class="position-status-label position-status-active">
                    <span class="position-status-icon">
                         <i data-feather="check-circle"></i>
                    </span>

                    <span class="position-status-text">
                         <strong>Aktif</strong>
                         <small>Jadwal dapat digunakan pada pengaturan kerja karyawan.</small>
                    </span>
               </label>
          </div>

          <div class="position-status-option">
               <input type="radio" id="status-inactive" name="status" value="inactive"
                    @checked($selectedStatus === 'inactive') required>

               <label for="status-inactive" class="position-status-label position-status-inactive">
                    <span class="position-status-icon">
                         <i data-feather="x-circle"></i>
                    </span>

                    <span class="position-status-text">
                         <strong>Tidak Aktif</strong>
                         <small>Jadwal disimpan tetapi tidak digunakan sementara.</small>
                    </span>
               </label>
          </div>
     </div>

     @error('status')
          <span class="position-invalid-feedback">{{ $message }}</span>
     @enderror
</div>

@once
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const lateToleranceInput =
                    document.getElementById('late_tolerance_minutes');

               const workingHoursInput =
                    document.getElementById('working_hours');

               const preventNegativeNumber = function(input) {
                    if (!input) {
                         return;
                    }

                    input.addEventListener('input', function() {
                         if (Number(this.value) < 0) {
                              this.value = 0;
                         }
                    });
               };

               preventNegativeNumber(lateToleranceInput);
               preventNegativeNumber(workingHoursInput);

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }
          });
     </script>
@endonce
@extends('layouts.app')

@section('title', 'Detail Jadwal Kerja')

@section('content')
     @php
          $formatScheduleTime = static function ($value): string {
              if (blank($value)) {
                  return '—';
              }

              if ($value instanceof \DateTimeInterface) {
                  return $value->format('H:i');
              }

              $time = trim((string) $value);

              if (preg_match('/^(\d{2}):(\d{2})/', $time, $matches) === 1) {
                  return $matches[1] . ':' . $matches[2];
              }

              try {
                  return \Illuminate\Support\Carbon::parse($time)->format('H:i');
              } catch (\Throwable) {
                  return $time;
              }
          };

          $formatDateTime = static function ($value): string {
              if (blank($value)) {
                  return '—';
              }

              try {
                  return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y, H:i');
              } catch (\Throwable) {
                  return (string) $value;
              }
          };

          $workingHours = is_numeric($workSchedule->working_hours)
              ? rtrim(rtrim(number_format((float) $workSchedule->working_hours, 2, ',', '.'), '0'), ',')
              : $workSchedule->working_hours;

          $isActive = $workSchedule->status === 'active';
          $statusLabel = $isActive ? 'Aktif' : 'Tidak Aktif';
     @endphp

     <style>
          :root {
               --pos-primary: #6366f1;
               --pos-primary-dark: #4f46e5;
               --pos-purple: #8b5cf6;
               --pos-cyan: #06b6d4;
               --pos-success: #10b981;
               --pos-danger: #ef4444;
               --pos-warning: #f59e0b;
               --pos-text: #24324a;
               --pos-muted: #718096;
               --pos-border: #e7eaf3;
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
                    radial-gradient(circle at 96% 6%, rgba(34, 211, 238, .18), transparent 25%),
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
               border: 1px solid rgba(255, 255, 255, .75);
               border-radius: 26px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .32), transparent 24%),
                    linear-gradient(120deg, #6366f1 0%, #8b5cf6 48%, #06b6d4 100%);
               box-shadow: 0 22px 48px rgba(99, 102, 241, .20);
          }

          .position-show-hero::after {
               position: absolute;
               right: -45px;
               bottom: -85px;
               width: 185px;
               height: 185px;
               content: '';
               border-radius: 46px;
               background: rgba(255, 255, 255, .13);
               transform: rotate(28deg);
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
               gap: 16px;
               align-items: center;
          }

          .position-show-hero-icon {
               display: inline-flex;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               color: var(--pos-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 19px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 13px 25px rgba(76, 29, 149, .16);
          }

          .position-show-hero-icon svg {
               width: 27px;
               height: 27px;
          }

          .position-show-hero h1 {
               margin: 0;
               font-size: clamp(1.58rem, 2.4vw, 2.15rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .position-show-hero p {
               max-width: 720px;
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .93);
               font-size: .92rem;
               line-height: 1.65;
          }

          .position-show-hero-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               justify-content: flex-end;
          }

          .position-show-action {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #fff;
               font-size: .82rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .38);
               border-radius: 13px;
               background: rgba(255, 255, 255, .15);
               backdrop-filter: blur(10px);
               transition: .2s ease;
          }

          .position-show-action:hover {
               color: #fff;
               text-decoration: none;
               background: rgba(255, 255, 255, .25);
               transform: translateY(-2px);
          }

          .position-show-action.is-primary {
               color: var(--pos-primary-dark);
               border-color: rgba(255, 255, 255, .9);
               background: rgba(255, 255, 255, .96);
          }

          .position-show-action.is-primary:hover {
               color: var(--pos-primary-dark);
               background: #fff;
          }

          .position-show-action svg {
               width: 17px;
               height: 17px;
          }

          .position-show-layout {
               display: grid;
               grid-template-columns: minmax(0, 2fr) minmax(320px, .85fr);
               gap: 20px;
               align-items: start;
          }

          .position-show-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .95);
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 44px rgba(51, 65, 85, .09);
          }

          .position-show-card-header {
               padding: 22px 25px;
               border-bottom: 1px solid #edf1f7;
               background: linear-gradient(90deg, #fff 0%, #faf8ff 52%, #f1fbff 100%);
          }

          .position-show-card-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--pos-text);
               font-size: 1.06rem;
               font-weight: 830;
          }

          .position-show-card-title>span:first-child {
               display: inline-flex;
               width: 42px;
               height: 42px;
               color: var(--pos-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .position-show-card-title svg {
               width: 20px;
               height: 20px;
          }

          .position-show-card-subtitle {
               margin: 5px 0 0 53px;
               color: var(--pos-muted);
               font-size: .81rem;
          }

          .position-show-card-body {
               padding: 26px;
          }

          .position-show-summary {
               display: flex;
               gap: 18px;
               align-items: center;
               padding: 21px;
               margin-bottom: 22px;
               border: 1px solid #edf1f7;
               border-radius: 19px;
               background: linear-gradient(145deg, #fff, #f8fbff);
          }

          .position-show-summary-icon {
               display: inline-flex;
               flex: 0 0 58px;
               width: 58px;
               height: 58px;
               color: #5b21b6;
               align-items: center;
               justify-content: center;
               border-radius: 17px;
               background: linear-gradient(135deg, #ede9fe, #e0f2fe);
          }

          .position-show-summary-icon svg {
               width: 25px;
               height: 25px;
          }

          .position-show-summary h2 {
               margin: 0;
               color: var(--pos-text);
               font-size: 1.28rem;
               font-weight: 850;
          }

          .position-show-summary p {
               margin: 5px 0 0;
               color: var(--pos-muted);
               font-size: .82rem;
          }

          .position-show-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .position-show-item {
               min-width: 0;
               padding: 18px;
               border: 1px solid #edf1f7;
               border-radius: 17px;
               background: #fff;
          }

          .position-show-item.is-wide {
               grid-column: 1 / -1;
          }

          .position-show-label {
               display: flex;
               gap: 8px;
               align-items: center;
               margin-bottom: 9px;
               color: #718096;
               font-size: .75rem;
               font-weight: 800;
               letter-spacing: .02em;
               text-transform: uppercase;
          }

          .position-show-label svg {
               width: 15px;
               height: 15px;
               color: #818cf8;
          }

          .position-show-value {
               color: #24324a;
               font-size: .98rem;
               font-weight: 820;
               line-height: 1.5;
               overflow-wrap: anywhere;
          }

          .position-show-value.is-time {
               font-size: 1.24rem;
               letter-spacing: .025em;
          }

          .position-show-status {
               display: inline-flex;
               min-height: 34px;
               padding: 7px 12px;
               gap: 7px;
               align-items: center;
               border-radius: 999px;
               font-size: .77rem;
               font-weight: 850;
          }

          .position-show-status::before {
               width: 8px;
               height: 8px;
               content: '';
               border-radius: 50%;
          }

          .position-show-status.is-active {
               color: #047857;
               background: #ecfdf5;
          }

          .position-show-status.is-active::before {
               background: #10b981;
               box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
          }

          .position-show-status.is-inactive {
               color: #be123c;
               background: #fff1f2;
          }

          .position-show-status.is-inactive::before {
               background: #f43f5e;
               box-shadow: 0 0 0 4px rgba(244, 63, 94, .12);
          }

          .position-show-side-stack {
               display: grid;
               gap: 20px;
          }

          .position-show-meta-list {
               display: grid;
               gap: 13px;
          }

          .position-show-meta {
               display: flex;
               gap: 12px;
               align-items: flex-start;
               padding: 15px;
               border: 1px solid #edf1f7;
               border-radius: 15px;
               background: #fff;
          }

          .position-show-meta-icon {
               display: inline-flex;
               flex: 0 0 39px;
               width: 39px;
               height: 39px;
               color: #5b21b6;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: #f5f3ff;
          }

          .position-show-meta-icon svg {
               width: 17px;
               height: 17px;
          }

          .position-show-meta small {
               display: block;
               margin-bottom: 4px;
               color: #94a3b8;
               font-size: .71rem;
               font-weight: 780;
               text-transform: uppercase;
          }

          .position-show-meta strong {
               display: block;
               color: #334155;
               font-size: .82rem;
               line-height: 1.5;
          }

          .position-show-note {
               padding: 17px;
               color: #64748b;
               font-size: .78rem;
               line-height: 1.7;
               border: 1px solid #dbeafe;
               border-radius: 16px;
               background: linear-gradient(135deg, #eff6ff, #f5f3ff);
          }

          .position-show-note strong {
               display: block;
               margin-bottom: 5px;
               color: #4338ca;
          }

          @media (max-width: 1199.98px) {
               .position-show-layout {
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

               .position-show-hero-actions {
                    width: 100%;
                    justify-content: stretch;
               }

               .position-show-action {
                    flex: 1 1 100%;
               }

               .position-show-card-body {
                    padding: 18px;
               }

               .position-show-summary {
                    align-items: flex-start;
               }

               .position-show-grid {
                    grid-template-columns: 1fr;
               }

               .position-show-item.is-wide {
                    grid-column: auto;
               }
          }
     </style>

     <div class="position-show-page">
          <div class="position-show-container">
               <section class="position-show-hero">
                    <div class="position-show-hero-content">
                         <div class="position-show-title-wrap">
                              <span class="position-show-hero-icon">
                                   <i data-feather="clock"></i>
                              </span>

                              <div>
                                   <h1>Detail Jadwal Kerja</h1>

                                   <p>
                                        Informasi lengkap jam masuk, jam pulang, toleransi keterlambatan,
                                        total jam kerja, dan status penggunaan jadwal.
                                   </p>
                              </div>
                         </div>

                         <div class="position-show-hero-actions">
                              <a href="{{ route('super-admin.work-schedules.index') }}" class="position-show-action">
                                   <i data-feather="arrow-left"></i>
                                   <span>Kembali ke Daftar</span>
                              </a>

                              @if (Route::has('super-admin.work-schedules.edit'))
                                   <a href="{{ route('super-admin.work-schedules.edit', $workSchedule) }}"
                                        class="position-show-action is-primary">
                                        <i data-feather="edit-3"></i>
                                        <span>Edit Jadwal</span>
                                   </a>
                              @endif
                         </div>
                    </div>
               </section>

               <div class="position-show-layout">
                    <section class="position-show-card">
                         <header class="position-show-card-header">
                              <h2 class="position-show-card-title">
                                   <span><i data-feather="calendar"></i></span>
                                   <span>Informasi Jadwal Kerja</span>
                              </h2>

                              <p class="position-show-card-subtitle">
                                   Data utama yang tersimpan pada sistem.
                              </p>
                         </header>

                         <div class="position-show-card-body">
                              <div class="position-show-summary">
                                   <span class="position-show-summary-icon">
                                        <i data-feather="calendar"></i>
                                   </span>

                                   <div>
                                        <h2>{{ $workSchedule->name }}</h2>

                                        <p>
                                             Jadwal kerja #{{ $workSchedule->id }}
                                        </p>
                                   </div>
                              </div>

                              <div class="position-show-grid">
                                   <div class="position-show-item">
                                        <div class="position-show-label">
                                             <i data-feather="log-in"></i>
                                             <span>Jam Masuk</span>
                                        </div>

                                        <div class="position-show-value is-time">
                                             {{ $formatScheduleTime($workSchedule->start_time) }}
                                        </div>
                                   </div>

                                   <div class="position-show-item">
                                        <div class="position-show-label">
                                             <i data-feather="log-out"></i>
                                             <span>Jam Pulang</span>
                                        </div>

                                        <div class="position-show-value is-time">
                                             {{ $formatScheduleTime($workSchedule->end_time) }}
                                        </div>
                                   </div>

                                   <div class="position-show-item">
                                        <div class="position-show-label">
                                             <i data-feather="alert-triangle"></i>
                                             <span>Toleransi Keterlambatan</span>
                                        </div>

                                        <div class="position-show-value">
                                             {{ (int) $workSchedule->late_tolerance_minutes }} menit
                                        </div>
                                   </div>

                                   <div class="position-show-item">
                                        <div class="position-show-label">
                                             <i data-feather="clock"></i>
                                             <span>Total Jam Kerja</span>
                                        </div>

                                        <div class="position-show-value">
                                             {{ $workingHours }} jam
                                        </div>
                                   </div>

                                   <div class="position-show-item is-wide">
                                        <div class="position-show-label">
                                             <i data-feather="activity"></i>
                                             <span>Status Jadwal</span>
                                        </div>

                                        <span class="position-show-status {{ $isActive ? 'is-active' : 'is-inactive' }}">
                                             {{ $statusLabel }}
                                        </span>
                                   </div>
                              </div>
                         </div>
                    </section>

                    <aside class="position-show-side-stack">
                         <section class="position-show-card">
                              <header class="position-show-card-header">
                                   <h2 class="position-show-card-title">
                                        <span><i data-feather="database"></i></span>
                                        <span>Metadata</span>
                                   </h2>

                                   <p class="position-show-card-subtitle">
                                        Informasi pencatatan data.
                                   </p>
                              </header>

                              <div class="position-show-card-body">
                                   <div class="position-show-meta-list">
                                        <div class="position-show-meta">
                                             <span class="position-show-meta-icon">
                                                  <i data-feather="hash"></i>
                                             </span>

                                             <div>
                                                  <small>ID Jadwal</small>
                                                  <strong>{{ $workSchedule->id }}</strong>
                                             </div>
                                        </div>

                                        <div class="position-show-meta">
                                             <span class="position-show-meta-icon">
                                                  <i data-feather="plus-circle"></i>
                                             </span>

                                             <div>
                                                  <small>Dibuat</small>
                                                  <strong>{{ $formatDateTime($workSchedule->created_at) }} WIB</strong>
                                             </div>
                                        </div>

                                        <div class="position-show-meta">
                                             <span class="position-show-meta-icon">
                                                  <i data-feather="refresh-cw"></i>
                                             </span>

                                             <div>
                                                  <small>Terakhir Diperbarui</small>
                                                  <strong>{{ $formatDateTime($workSchedule->updated_at) }} WIB</strong>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </section>

                         <div class="position-show-note">
                              <strong>Catatan penggunaan</strong>
                              Jadwal berstatus aktif dapat digunakan pada pengaturan jadwal karyawan.
                              Pastikan jam masuk, jam pulang, dan total jam kerja telah sesuai dengan
                              kebijakan operasional perusahaan.
                         </div>
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
