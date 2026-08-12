@extends('layouts.app')

@section('title', 'Detail Aktivitas Pegawai')

@section('content')
     @php
          $statusLabel = $employeeActivity->getStatusLabel();
          $statusClass = match ($employeeActivity->status) {
              \App\Models\EmployeeActivity::STATUS_VERIFIED => 'verified',
              \App\Models\EmployeeActivity::STATUS_REJECTED => 'rejected',
              default => 'submitted',
          };
     @endphp
     <style>
          .ea-show-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 42px;
               background: radial-gradient(circle at 8% 10%, rgba(20, 184, 166, .12), transparent 24%), radial-gradient(circle at 94% 7%, rgba(37, 99, 235, .12), transparent 26%), linear-gradient(145deg, #f9fcff 0%, #f8fbff 45%, #f1f8ff 100%);
          }

          .ea-show-container {
               max-width: 1560px;
               margin: 0 auto;
          }

          .ea-show-hero {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 28px;
               margin-bottom: 20px;
               color: #fff;
               border-radius: 26px;
               background: linear-gradient(125deg, #0f766e 0%, #0284c7 46%, #2563eb 100%);
               box-shadow: 0 20px 48px rgba(14, 116, 144, .22);
          }

          .ea-show-title {
               display: flex;
               gap: 15px;
               align-items: center;
          }

          .ea-show-icon {
               display: inline-flex;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               border-radius: 18px;
               background: rgba(255, 255, 255, .95);
          }

          .ea-show-icon svg {
               width: 28px;
               height: 28px;
          }

          .ea-show-hero h1 {
               margin: 0;
               font-size: clamp(1.6rem, 2.5vw, 2.2rem);
               font-weight: 850;
          }

          .ea-show-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .92);
          }

          .ea-show-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               justify-content: flex-end;
          }

          .ea-show-btn {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #fff;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .36);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
          }

          .ea-show-btn.is-primary {
               color: #0f766e;
               border-color: rgba(255, 255, 255, .9);
               background: rgba(255, 255, 255, .96);
          }

          .ea-show-layout {
               display: grid;
               grid-template-columns: minmax(0, 1.6fr) minmax(320px, .9fr);
               gap: 20px;
          }

          .ea-show-card {
               overflow: hidden;
               border: 1px solid #e5ecf6;
               border-radius: 24px;
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 18px 40px rgba(51, 65, 85, .08);
          }

          .ea-show-card-head {
               padding: 22px 24px;
               border-bottom: 1px solid #edf2f7;
               background: linear-gradient(90deg, #ffffff 0%, #f6fbff 100%);
          }

          .ea-show-card-head h2 {
               margin: 0;
               color: #24324a;
               font-size: 1.02rem;
               font-weight: 840;
          }

          .ea-show-card-body {
               padding: 22px 24px;
          }

          .ea-show-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .ea-show-item {
               padding: 14px;
               border: 1px solid #edf2f7;
               border-radius: 16px;
               background: linear-gradient(180deg, #fff, #fbfdff);
          }

          .ea-show-item-label {
               display: block;
               margin-bottom: 6px;
               color: #6b7a90;
               font-size: .72rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .06em;
          }

          .ea-show-item-value {
               color: #24324a;
               font-size: .92rem;
               font-weight: 780;
               line-height: 1.55;
          }

          .ea-badge {
               display: inline-flex;
               padding: 7px 11px;
               align-items: center;
               justify-content: center;
               border-radius: 999px;
               font-size: .74rem;
               font-weight: 850;
          }

          .ea-badge.submitted {
               color: #9a3412;
               background: #fff7ed;
               border: 1px solid #fed7aa;
          }

          .ea-badge.verified {
               color: #047857;
               background: #ecfdf5;
               border: 1px solid #a7f3d0;
          }

          .ea-badge.rejected {
               color: #be123c;
               background: #fff1f2;
               border: 1px solid #fecdd3;
          }

          .ea-show-note {
               min-height: 140px;
          }

          .ea-show-side-list {
               display: grid;
               gap: 12px;
          }

          .ea-show-side-box {
               padding: 16px;
               border: 1px solid #edf2f7;
               border-radius: 18px;
               background: linear-gradient(180deg, #fff, #fbfdff);
          }

          .ea-show-side-box strong {
               display: block;
               color: #24324a;
               font-size: .9rem;
          }

          .ea-show-side-box span {
               display: block;
               margin-top: 5px;
               color: #6b7a90;
               font-size: .8rem;
               line-height: 1.55;
          }

          @media (max-width: 991px) {

               .ea-show-hero,
               .ea-show-layout {
                    grid-template-columns: 1fr;
                    display: grid;
               }

               .ea-show-actions {
                    justify-content: flex-start;
               }
          }

          @media (max-width: 575px) {
               .ea-show-page {
                    padding: 18px 12px 34px;
               }

               .ea-show-grid {
                    grid-template-columns: 1fr;
               }

               .ea-show-actions {
                    display: grid;
               }

               .ea-show-btn {
                    width: 100%;
               }
          }
     </style>

     <div class="ea-show-page">
          <div class="ea-show-container">
               <section class="ea-show-hero">
                    <div class="ea-show-title">
                         <span class="ea-show-icon"><i data-feather="clipboard"></i></span>
                         <div>
                              <h1>{{ $employeeActivity->activity_name }}</h1>
                              <p>{{ $employeeActivity->employee?->full_name ?? '-' }} |
                                   {{ optional($employeeActivity->activity_date)->translatedFormat('d F Y') ?? '-' }}</p>
                         </div>
                    </div>
                    <div class="ea-show-actions">
                         <a href="{{ route('super-admin.employee-activities.index') }}" class="ea-show-btn"><i
                                   data-feather="arrow-left"></i> Daftar</a>
                         <a href="{{ route('super-admin.employee-activities.edit', $employeeActivity) }}"
                              class="ea-show-btn is-primary"><i data-feather="edit-3"></i> Edit</a>
                    </div>
               </section>

               <div class="ea-show-layout">
                    <section class="ea-show-card">
                         <div class="ea-show-card-head">
                              <h2>Informasi Aktivitas</h2>
                         </div>
                         <div class="ea-show-card-body">
                              <div class="ea-show-grid">
                                   <div class="ea-show-item"><span class="ea-show-item-label">Pegawai</span>
                                        <div class="ea-show-item-value">{{ $employeeActivity->employee?->full_name ?? '-' }}
                                        </div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Status</span>
                                        <div class="ea-show-item-value"><span
                                                  class="ea-badge {{ $statusClass }}">{{ $statusLabel }}</span></div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Tanggal</span>
                                        <div class="ea-show-item-value">
                                             {{ optional($employeeActivity->activity_date)->translatedFormat('d F Y') ?? '-' }}
                                        </div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Service Order</span>
                                        <div class="ea-show-item-value">
                                             {{ $employeeActivity->serviceOrder?->order_number ?? '-' }}</div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Kuantitas</span>
                                        <div class="ea-show-item-value">
                                             {{ number_format((float) $employeeActivity->quantity, 2, ',', '.') }}
                                             {{ $employeeActivity->unit ?: '' }}</div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Rentang Waktu</span>
                                        <div class="ea-show-item-value">{{ $employeeActivity->getTimeRangeLabel() }}</div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Durasi</span>
                                        <div class="ea-show-item-value">
                                             {{ number_format((int) $employeeActivity->duration_minutes) }} menit
                                             ({{ number_format($employeeActivity->getDurationHours(), 2, ',', '.') }} jam)
                                        </div>
                                   </div>
                                   <div class="ea-show-item"><span class="ea-show-item-label">Verifikator</span>
                                        <div class="ea-show-item-value">{{ $employeeActivity->verifier?->name ?? '-' }}
                                        </div>
                                   </div>
                                   <div class="ea-show-item ea-show-note" style="grid-column: 1 / -1;"><span
                                             class="ea-show-item-label">Deskripsi</span>
                                        <div class="ea-show-item-value">
                                             {{ $employeeActivity->description ?: 'Tidak ada deskripsi aktivitas.' }}</div>
                                   </div>
                              </div>
                         </div>
                    </section>

                    <aside class="ea-show-card">
                         <div class="ea-show-card-head">
                              <h2>Tindakan Cepat</h2>
                         </div>
                         <div class="ea-show-card-body">
                              <div class="ea-show-side-list">
                                   @if (!$employeeActivity->isVerified())
                                        <form method="POST"
                                             action="{{ route('super-admin.employee-activities.verify', $employeeActivity) }}">
                                             @csrf
                                             @method('PATCH')
                                             <button type="submit" class="ea-show-btn is-primary"
                                                  style="width:100%; border:0;">Verifikasi Aktivitas</button>
                                        </form>
                                   @else
                                        <form method="POST"
                                             action="{{ route('super-admin.employee-activities.cancel-verification', $employeeActivity) }}">
                                             @csrf
                                             @method('PATCH')
                                             <button type="submit" class="ea-show-btn"
                                                  style="width:100%; border-color:#fecdd3; background:#fff1f2; color:#be123c;">Batalkan
                                                  Verifikasi</button>
                                        </form>
                                   @endif

                                   <form method="POST"
                                        action="{{ route('super-admin.employee-activities.destroy', $employeeActivity) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ea-show-btn"
                                             style="width:100%; border-color:#fecdd3; background:#fff1f2; color:#be123c;">Hapus
                                             Aktivitas</button>
                                   </form>

                                   <div class="ea-show-side-box">
                                        <strong>Referensi Pegawai</strong>
                                        <span>{{ $employeeActivity->employee?->employee_number ?? '-' }}
                                             {{ $employeeActivity->employee?->department?->name ? '| ' . $employeeActivity->employee->department->name : '' }}</span>
                                   </div>

                                   <div class="ea-show-side-box">
                                        <strong>Waktu Sistem</strong>
                                        <span>Dibuat:
                                             {{ optional($employeeActivity->created_at)->translatedFormat('d F Y H:i') ?? '-' }}
                                             WIB</span>
                                        <span>Diperbarui:
                                             {{ optional($employeeActivity->updated_at)->translatedFormat('d F Y H:i') ?? '-' }}
                                             WIB</span>
                                   </div>
                              </div>
                         </div>
                    </aside>
               </div>
          </div>
     </div>
@endsection
