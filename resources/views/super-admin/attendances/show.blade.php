@extends('layouts.app')

@section('title', 'Detail Kehadiran')

@section('content')
     @php
          $statusClass = match ((string) $attendance->status) {
              \App\Models\Attendance::STATUS_PRESENT => 'attd-pill--present',
              \App\Models\Attendance::STATUS_LATE => 'attd-pill--late',
              \App\Models\Attendance::STATUS_ABSENT => 'attd-pill--absent',
              \App\Models\Attendance::STATUS_SICK => 'attd-pill--sick',
              \App\Models\Attendance::STATUS_LEAVE => 'attd-pill--leave',
              \App\Models\Attendance::STATUS_HOLIDAY => 'attd-pill--holiday',
              default => 'attd-pill--default',
          };
     @endphp

     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          .attd-page {
               min-height: calc(100vh - 70px);
               padding: 26px 18px 44px;
               color: #17324a;
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 8% 7%, rgba(20, 184, 166, .12), transparent 24%),
                    radial-gradient(circle at 94% 9%, rgba(14, 165, 233, .13), transparent 26%),
                    linear-gradient(155deg, #f8fcff 0%, #eff8ff 50%, #edf9f8 100%);
          }

          .attd-wrap {
               max-width: 1450px;
               margin: 0 auto;
          }

          .attd-hero {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               padding: 24px 28px;
               margin-bottom: 16px;
               border-radius: 22px;
               color: #fff;
               background: linear-gradient(124deg, #0f766e 0%, #0369a1 56%, #0ea5e9 100%);
               box-shadow: 0 20px 46px rgba(3, 105, 161, .25);
          }

          .attd-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.08rem, 2vw, 1.55rem);
               font-weight: 700;
          }

          .attd-hero p {
               margin: 7px 0 0;
               font-size: .84rem;
               color: rgba(255, 255, 255, .9);
          }

          .attd-icon-btn {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               border: 1px solid rgba(255, 255, 255, .35);
               color: #fff;
               background: rgba(255, 255, 255, .14);
               transition: .18s ease;
               text-decoration: none;
          }

          .attd-icon-btn:hover {
               color: #fff;
               transform: translateY(-2px);
               background: rgba(255, 255, 255, .24);
          }

          .attd-card {
               border: 1px solid #d6e4f0;
               border-radius: 22px;
               box-shadow: 0 16px 36px rgba(15, 23, 42, .08);
               background: #fff;
          }

          .attd-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 12px;
          }

          .attd-item {
               padding: 14px;
               border: 1px solid #e6eef6;
               border-radius: 14px;
               background: #fbfdff;
          }

          .attd-item-label {
               margin-bottom: 4px;
               color: #5f7389;
               font-size: .72rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .06em;
          }

          .attd-item-value {
               font-size: .9rem;
               font-weight: 700;
          }

          .attd-item-wide {
               grid-column: span 2;
          }

          .attd-pill {
               display: inline-flex;
               padding: 5px 10px;
               border-radius: 999px;
               font-size: .7rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .05em;
          }

          .attd-pill--present {
               color: #065f46;
               background: #d1fae5;
          }

          .attd-pill--late {
               color: #92400e;
               background: #fef3c7;
          }

          .attd-pill--absent {
               color: #991b1b;
               background: #fee2e2;
          }

          .attd-pill--sick {
               color: #1e3a8a;
               background: #dbeafe;
          }

          .attd-pill--leave {
               color: #5b21b6;
               background: #ede9fe;
          }

          .attd-pill--holiday {
               color: #0f766e;
               background: #ccfbf1;
          }

          .attd-pill--default {
               color: #334155;
               background: #e2e8f0;
          }

          @media (max-width: 992px) {
               .attd-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .attd-item-wide {
                    grid-column: span 2;
               }
          }

          @media (max-width: 576px) {
               .attd-grid {
                    grid-template-columns: 1fr;
               }

               .attd-item-wide {
                    grid-column: span 1;
               }
          }
     </style>

     <div class="attd-page">
          <div class="attd-wrap">
               <section class="attd-hero">
                    <div>
                         <h1>Detail Kehadiran Pegawai</h1>
                         <p>Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa</p>
                    </div>

                    <div class="d-flex gap-2">
                         <a href="{{ route('super-admin.attendances.index') }}" class="attd-icon-btn" aria-label="Kembali"
                              title="Kembali">
                              <i data-feather="arrow-left"></i>
                         </a>

                         <a href="{{ route('super-admin.attendances.edit', $attendance) }}" class="attd-icon-btn"
                              aria-label="Edit" title="Edit">
                              <i data-feather="edit-2"></i>
                         </a>

                         <form action="{{ route('super-admin.attendances.destroy', $attendance) }}" method="POST"
                              onsubmit="return confirm('Hapus data kehadiran ini?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="attd-icon-btn" aria-label="Hapus" title="Hapus">
                                   <i data-feather="trash-2"></i>
                              </button>
                         </form>
                    </div>
               </section>

               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
               @endif

               <section class="card attd-card">
                    <div class="card-body p-4">
                         <div class="attd-grid">
                              <article class="attd-item attd-item-wide">
                                   <div class="attd-item-label">Pegawai</div>
                                   <div class="attd-item-value">{{ $attendance->employee?->full_name ?? '-' }}</div>
                              </article>

                              <article class="attd-item attd-item-wide">
                                   <div class="attd-item-label">Nomor Pegawai</div>
                                   <div class="attd-item-value">{{ $attendance->employee?->employee_number ?? '-' }}</div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Tanggal</div>
                                   <div class="attd-item-value">{{ optional($attendance->attendance_date)->format('d-m-Y') }}
                                   </div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Jam Masuk</div>
                                   <div class="attd-item-value">
                                        {{ $attendance->check_in ? substr((string) $attendance->check_in, 0, 5) : '-' }}
                                   </div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Jam Pulang</div>
                                   <div class="attd-item-value">
                                        {{ $attendance->check_out ? substr((string) $attendance->check_out, 0, 5) : '-' }}
                                   </div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Status</div>
                                   <div class="attd-item-value">
                                        <span
                                             class="attd-pill {{ $statusClass }}">{{ ucfirst($attendance->status) }}</span>
                                   </div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Durasi Kerja</div>
                                   <div class="attd-item-value">{{ $attendance->work_duration_formatted }}
                                        ({{ $attendance->work_duration_minutes }}m)</div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Terlambat</div>
                                   <div class="attd-item-value">{{ (int) $attendance->late_minutes }}m</div>
                              </article>

                              <article class="attd-item">
                                   <div class="attd-item-label">Lembur</div>
                                   <div class="attd-item-value">{{ (int) $attendance->overtime_minutes }}m</div>
                              </article>

                              <article class="attd-item attd-item-wide" style="grid-column: 1 / -1;">
                                   <div class="attd-item-label">Catatan</div>
                                   <div class="attd-item-value">{{ $attendance->notes ?: '-' }}</div>
                              </article>
                         </div>
                    </div>
               </section>
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
