@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
     @php
          $statusClass = match ((string) $leaveRequest->status) {
              \App\Models\LeaveRequest::STATUS_PENDING => 'lrs-pill is-pending',
              \App\Models\LeaveRequest::STATUS_APPROVED => 'lrs-pill is-approved',
              \App\Models\LeaveRequest::STATUS_REJECTED => 'lrs-pill is-rejected',
              default => 'lrs-pill is-default',
          };
     @endphp

     <style>
          @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          :root {
               --lrs-bg: #f4f8ff;
               --lrs-panel: rgba(255, 255, 255, 0.96);
               --lrs-border: rgba(148, 163, 184, 0.24);
               --lrs-text: #15243d;
               --lrs-muted: #64748b;
               --lrs-primary: #0f766e;
               --lrs-secondary: #2563eb;
               --lrs-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
          }

          .lrs-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 44px;
               color: var(--lrs-text);
               font-family: 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 7% 8%, rgba(14, 165, 233, 0.12), transparent 22%),
                    radial-gradient(circle at 94% 10%, rgba(16, 185, 129, 0.12), transparent 24%),
                    linear-gradient(145deg, #f9fcff 0%, #f3f8ff 50%, #f0fdf9 100%);
          }

          .lrs-wrap {
               max-width: 1500px;
               margin: 0 auto;
          }

          .lrs-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               border: 1px solid rgba(255, 255, 255, 0.76);
               border-radius: 28px;
               padding: 28px 30px;
               margin-bottom: 18px;
               color: #fff;
               background:
                    radial-gradient(circle at 88% 18%, rgba(255, 255, 255, 0.25), transparent 20%),
                    radial-gradient(circle at 15% 100%, rgba(16, 185, 129, 0.18), transparent 28%),
                    linear-gradient(128deg, #0f766e 0%, #0369a1 54%, #2563eb 100%);
               box-shadow: 0 26px 60px rgba(3, 105, 161, 0.22);
          }

          .lrs-hero::before {
               position: absolute;
               top: -80px;
               right: 10%;
               width: 240px;
               height: 240px;
               content: '';
               border: 1px solid rgba(255, 255, 255, 0.12);
               border-radius: 50%;
          }

          .lrs-hero h4 {
               margin: 0;
               font-family: 'Sora', 'Manrope', sans-serif;
               font-size: clamp(1.2rem, 2vw, 1.7rem);
               font-weight: 800;
               letter-spacing: -0.04em;
          }

          .lrs-hero p {
               margin: 7px 0 0;
               font-size: 0.9rem;
               color: rgba(255, 255, 255, 0.9);
          }

          .lrs-back {
               position: relative;
               z-index: 1;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 42px;
               height: 42px;
               border-radius: 12px;
               background: rgba(255, 255, 255, 0.96);
               color: #0f172a;
               border: 1px solid rgba(255, 255, 255, 0.3);
               box-shadow: 0 12px 24px rgba(15, 23, 42, 0.14);
          }

          .lrs-card {
               border: 1px solid var(--lrs-border);
               border-radius: 24px;
               background: var(--lrs-panel);
               box-shadow: var(--lrs-shadow);
               padding: 22px;
          }

          .lrs-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .lrs-item {
               border: 1px solid #e5edf6;
               border-radius: 16px;
               background: linear-gradient(180deg, rgba(248, 250, 252, 0.9), rgba(255, 255, 255, 0.96));
               padding: 16px;
          }

          .lrs-item .k {
               margin-bottom: 8px;
               font-size: 0.72rem;
               letter-spacing: 0.09em;
               text-transform: uppercase;
               color: var(--lrs-muted);
               font-weight: 800;
          }

          .lrs-item .v {
               font-size: 0.96rem;
               color: #1f2937;
               font-weight: 700;
               line-height: 1.6;
          }

          .lrs-item.wide {
               grid-column: 1 / -1;
          }

          .lrs-pill {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 110px;
               padding: 6px 12px;
               border-radius: 999px;
               font-size: 0.72rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: 0.05em;
               border: 1px solid transparent;
          }

          .lrs-pill.is-pending {
               color: #92400e;
               background: #fffbeb;
               border-color: #fcd34d;
          }

          .lrs-pill.is-approved {
               color: #065f46;
               background: #ecfdf5;
               border-color: #86efac;
          }

          .lrs-pill.is-rejected {
               color: #9f1239;
               background: #fff1f2;
               border-color: #fda4af;
          }

          .lrs-pill.is-default {
               color: #334155;
               background: #f1f5f9;
               border-color: #cbd5e1;
          }

          .lrs-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               margin-top: 18px;
          }

          .lrs-actions .btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 42px;
               border-radius: 12px;
               font-weight: 700;
          }

          .lrs-actions .btn-outline-primary {
               border-color: rgba(37, 99, 235, 0.2);
               color: #1d4ed8;
               background: rgba(59, 130, 246, 0.04);
          }

          .lrs-actions .btn-outline-danger {
               border-color: rgba(220, 38, 38, 0.2);
               color: #b91c1c;
               background: rgba(239, 68, 68, 0.04);
          }

          @media (max-width: 820px) {
               .lrs-grid {
                    grid-template-columns: 1fr;
               }

               .lrs-hero {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 22px 18px;
               }
          }
     </style>

     <div class="lrs-page">
          <div class="lrs-wrap">
               <div class="lrs-hero">
                    <div>
                         <h4>Detail Pengajuan Cuti</h4>
                         <p>Produktivitas Karyawan dan Transaksi Jasa</p>
                    </div>
                    <a href="{{ route('super-admin.leave-requests.index') }}" class="lrs-back" title="Kembali"
                         aria-label="Kembali">
                         <i class="bi bi-arrow-left"></i>
                    </a>
               </div>

               <div class="lrs-card">
                    <div class="lrs-grid">
                         <article class="lrs-item">
                              <div class="k">Pegawai</div>
                              <div class="v">{{ $leaveRequest->employee?->full_name ?? '-' }}</div>
                         </article>

                         <article class="lrs-item">
                              <div class="k">Jenis Cuti</div>
                              <div class="v">{{ ucfirst((string) $leaveRequest->leave_type) }}</div>
                         </article>

                         <article class="lrs-item">
                              <div class="k">Tanggal</div>
                              <div class="v">{{ optional($leaveRequest->start_date)->format('d M Y') }} -
                                   {{ optional($leaveRequest->end_date)->format('d M Y') }}</div>
                         </article>

                         <article class="lrs-item">
                              <div class="k">Total Hari</div>
                              <div class="v">{{ $leaveRequest->total_days }} hari</div>
                         </article>

                         <article class="lrs-item">
                              <div class="k">Status</div>
                              <div class="v"><span
                                        class="{{ $statusClass }}">{{ ucfirst((string) $leaveRequest->status) }}</span>
                              </div>
                         </article>

                         <article class="lrs-item">
                              <div class="k">Approver</div>
                              <div class="v">{{ $leaveRequest->approver?->name ?? '-' }}</div>
                         </article>

                         <article class="lrs-item wide">
                              <div class="k">Alasan</div>
                              <div class="v">{{ $leaveRequest->reason }}</div>
                         </article>

                         <article class="lrs-item wide">
                              <div class="k">Lampiran</div>
                              <div class="v">
                                   @if ($leaveRequest->attachment_path)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($leaveRequest->attachment_path) }}"
                                             target="_blank" rel="noopener">Lihat Lampiran</a>
                                   @else
                                        -
                                   @endif
                              </div>
                         </article>
                    </div>

                    <div class="lrs-actions">
                         <a href="{{ route('super-admin.leave-requests.edit', $leaveRequest) }}"
                              class="btn btn-outline-primary" title="Ubah" aria-label="Ubah">
                              <i class="bi bi-pencil-square"></i>
                              Ubah
                         </a>
                         <form action="{{ route('super-admin.leave-requests.destroy', $leaveRequest) }}" method="POST"
                              onsubmit="return confirm('Hapus pengajuan ini?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-outline-danger" title="Hapus" aria-label="Hapus">
                                   <i class="bi bi-trash3"></i>
                                   Hapus
                              </button>
                         </form>
                    </div>
               </div>
          </div>
     </div>
@endsection
