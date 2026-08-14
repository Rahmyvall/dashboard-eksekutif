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

          .lrs-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 44px;
               color: #1f2a44;
               font-family: 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 7% 8%, rgba(14, 165, 233, .12), transparent 24%),
                    radial-gradient(circle at 93% 10%, rgba(16, 185, 129, .13), transparent 26%),
                    linear-gradient(155deg, #f7fcff 0%, #eff8ff 52%, #ecfbf7 100%);
          }

          .lrs-wrap {
               max-width: 1360px;
               margin: 0 auto;
          }

          .lrs-hero {
               border-radius: 24px;
               padding: 24px 28px;
               margin-bottom: 16px;
               color: #fff;
               background: linear-gradient(124deg, #0f766e 0%, #0369a1 58%, #2563eb 100%);
               box-shadow: 0 22px 48px rgba(3, 105, 161, .24);
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
          }

          .lrs-hero h4 {
               margin: 0;
               font-family: 'Sora', 'Manrope', sans-serif;
               font-size: clamp(1.05rem, 2vw, 1.45rem);
          }

          .lrs-hero p {
               margin: 6px 0 0;
               font-size: .84rem;
               color: rgba(255, 255, 255, .93);
          }

          .lrs-card {
               border: 1px solid #dbe6f2;
               border-radius: 20px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 30px rgba(15, 23, 42, .08);
               padding: 18px;
          }

          .lrs-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 12px;
          }

          .lrs-item {
               border: 1px solid #e5edf6;
               border-radius: 14px;
               background: #fbfdff;
               padding: 14px;
          }

          .lrs-item .k {
               font-size: .72rem;
               letter-spacing: .08em;
               text-transform: uppercase;
               color: #64748b;
               font-weight: 800;
               margin-bottom: 7px;
          }

          .lrs-item .v {
               font-size: .92rem;
               color: #1f2937;
               font-weight: 700;
          }

          .lrs-item.wide {
               grid-column: 1 / -1;
          }

          .lrs-pill {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 90px;
               padding: 5px 10px;
               border-radius: 999px;
               font-size: .72rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .04em;
               border: 1px solid transparent;
          }

          .lrs-pill.is-pending {
               color: #92400e;
               background: #fffbeb;
               border-color: #fde68a;
          }

          .lrs-pill.is-approved {
               color: #065f46;
               background: #ecfdf5;
               border-color: #a7f3d0;
          }

          .lrs-pill.is-rejected {
               color: #9f1239;
               background: #fff1f2;
               border-color: #fecdd3;
          }

          .lrs-pill.is-default {
               color: #334155;
               background: #f1f5f9;
               border-color: #cbd5e1;
          }

          @media (max-width: 820px) {
               .lrs-grid {
                    grid-template-columns: 1fr;
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
                    <a href="{{ route('super-admin.leave-requests.index') }}" class="btn btn-light btn-sm" title="Kembali"
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

                    <div class="mt-3 d-flex gap-2">
                         <a href="{{ route('super-admin.leave-requests.edit', $leaveRequest) }}"
                              class="btn btn-outline-primary" title="Ubah" aria-label="Ubah">
                              <i class="bi bi-pencil-square"></i>
                         </a>
                         <form action="{{ route('super-admin.leave-requests.destroy', $leaveRequest) }}" method="POST"
                              onsubmit="return confirm('Hapus pengajuan ini?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-outline-danger" title="Hapus" aria-label="Hapus">
                                   <i class="bi bi-trash3"></i>
                              </button>
                         </form>
                    </div>
               </div>
          </div>
     </div>
@endsection
