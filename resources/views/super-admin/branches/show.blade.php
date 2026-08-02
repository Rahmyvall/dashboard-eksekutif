@extends('layouts.app')

@section('title', 'Detail Cabang')

@section('content')
     @php
          $currentUser = auth()->user();
          $isSuperAdmin = $currentUser?->hasRole('super_admin') ?? false;
          $isOperasional = $currentUser?->hasRole('admin_operasional') ?? false;
          $canEdit = ($isSuperAdmin || $isOperasional)
              && (($branch->approval_status ?? 'approved') !== 'pending')
              && \Illuminate\Support\Facades\Route::has('branches.edit');

          $approvalStatus = $branch->approval_status ?? 'approved';
          $pendingApprovalRole = $branch->pending_approval_role ?? null;
          $approvalRoleLabels = [
               'super_admin' => 'Super Admin',
               'direktur_utama' => 'Direktur Utama',
               'admin_operasional' => 'Admin Operasional',
               'auditor_internal' => 'Auditor Internal',
          ];
          $pendingRoleLabel = $approvalRoleLabels[$pendingApprovalRole]
               ?? ($pendingApprovalRole ? str($pendingApprovalRole)->replace('_', ' ')->title() : null);
     @endphp

     <style>
          .branch-show-page {
               --bs-primary: #0f8f83;
               --bs-primary-hover: #0b7d74;
               --bs-heading: #164e63;
               --bs-text: #2b424c;
               --bs-muted: #627983;
               --bs-border: #cfe5e4;
               --bs-soft: #f3fbfa;
               min-height: calc(100vh - 70px);
               padding: 22px 24px 42px;
               color: var(--bs-text);
               background:
                    radial-gradient(circle at 100% 0, rgba(45, 212, 191, .14), transparent 29%),
                    radial-gradient(circle at 0 100%, rgba(125, 211, 252, .11), transparent 27%),
                    linear-gradient(180deg, #f8fffe 0%, #f4fbfb 48%, #f7fafc 100%);
          }

          .branch-show-page * {
               box-sizing: border-box;
          }

          .branch-show-shell {
               width: 100%;
               max-width: 1480px;
               margin: 0 auto;
          }

          .branch-show-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 24px;
               margin-bottom: 20px;
               padding: 30px 34px;
               border: 1px solid #bde7e2;
               border-radius: 22px;
               background:
                    radial-gradient(circle at 90% 10%, rgba(255, 255, 255, .94), transparent 23%),
                    radial-gradient(circle at 72% 112%, rgba(45, 212, 191, .19), transparent 39%),
                    linear-gradient(135deg, #ffffff 0%, #f1fffc 38%, #e5faf6 72%, #d9f5ef 100%);
               box-shadow: 0 18px 42px rgba(15, 118, 110, .10);
          }

          .branch-show-hero::after {
               content: '';
               position: absolute;
               right: -94px;
               bottom: -142px;
               width: 275px;
               height: 275px;
               border: 38px solid rgba(20, 184, 166, .10);
               border-radius: 50%;
               pointer-events: none;
          }

          .branch-show-hero > * {
               position: relative;
               z-index: 1;
          }

          .branch-show-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 7px;
               color: #168178;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .11em;
               text-transform: uppercase;
          }

          .branch-show-page .branch-show-hero h1 {
               margin: 0 0 7px;
               color: var(--bs-heading) !important;
               font-size: clamp(1.75rem, 3vw, 2.45rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .branch-show-page .branch-show-hero p {
               max-width: 850px;
               margin: 0;
               color: #55717a !important;
               font-size: .92rem;
               line-height: 1.65;
          }

          .branch-show-actions {
               display: flex;
               flex-wrap: wrap;
               justify-content: flex-end;
               gap: 9px;
          }

          .branch-show-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 44px;
               padding: 10px 15px;
               font-size: .8rem;
               font-weight: 850;
               text-decoration: none;
               white-space: nowrap;
               border-radius: 12px;
               transition: .2s ease;
          }

          .branch-show-button.light {
               color: #0f766e !important;
               border: 1px solid #8fdad1;
               background: rgba(255, 255, 255, .92);
          }

          .branch-show-button.light:hover {
               color: #0b655f !important;
               border-color: #5eead4;
               background: #f0fdfa;
               transform: translateY(-1px);
          }

          .branch-show-button.primary {
               color: #fff !important;
               border: 1px solid #16a99a;
               background: linear-gradient(135deg, #2bc7b6, #149e91);
               box-shadow: 0 9px 20px rgba(20, 158, 145, .20);
          }

          .branch-show-button.primary:hover {
               color: #fff !important;
               background: linear-gradient(135deg, #24b9aa, #0f8f83);
               transform: translateY(-1px);
          }

          .branch-show-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.35fr) minmax(300px, .65fr);
               gap: 18px;
          }

          .branch-show-card {
               overflow: hidden;
               border: 1px solid var(--bs-border);
               border-radius: 19px;
               background: rgba(255, 255, 255, .99);
               box-shadow: 0 13px 34px rgba(51, 65, 85, .06);
          }

          .branch-show-card + .branch-show-card {
               margin-top: 18px;
          }

          .branch-show-card-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               padding: 17px 20px;
               border-bottom: 1px solid #dcecea;
               background: linear-gradient(90deg, #f3fcfa 0%, #ffffff 100%);
          }

          .branch-show-card-title {
               display: flex;
               align-items: center;
               gap: 9px;
               margin: 0;
               color: #176b68 !important;
               font-size: .98rem;
               font-weight: 850;
          }

          .branch-show-card-title i {
               color: #18a99b;
          }

          .branch-show-card-body {
               padding: 20px;
          }

          .branch-detail-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .branch-detail-item {
               min-width: 0;
               padding: 14px;
               border: 1px solid #e1eeee;
               border-radius: 13px;
               background: #fbfefd;
          }

          .branch-detail-item.full {
               grid-column: 1 / -1;
          }

          .branch-detail-label {
               display: block;
               margin-bottom: 7px;
               color: #7b9098;
               font-size: .66rem;
               font-weight: 850;
               letter-spacing: .075em;
               text-transform: uppercase;
          }

          .branch-detail-value {
               color: #2d4650;
               font-size: .83rem;
               font-weight: 720;
               line-height: 1.55;
               word-break: break-word;
          }

          .branch-detail-value a {
               color: #0f8f83;
               text-decoration: none;
          }

          .branch-detail-value a:hover {
               text-decoration: underline;
          }

          .branch-code-badge,
          .branch-status-badge,
          .branch-approval-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 7px 10px;
               font-size: .72rem;
               font-weight: 850;
               border-radius: 999px;
          }

          .branch-code-badge {
               color: #4f46a5;
               border: 1px solid #d7d7fb;
               background: #f3f3ff;
          }

          .branch-status-badge.active {
               color: #08755e;
               border: 1px solid #a9ead8;
               background: #eafaf4;
          }

          .branch-status-badge.inactive {
               color: #b23b52;
               border: 1px solid #f2c3cc;
               background: #fff4f6;
          }

          .branch-approval-badge.pending {
               color: #9a6700;
               border: 1px solid #f5db91;
               background: #fffaf0;
          }

          .branch-approval-badge.approved {
               color: #08755e;
               border: 1px solid #a9ead8;
               background: #eafaf4;
          }

          .branch-approval-badge.rejected {
               color: #b23b52;
               border: 1px solid #f2c3cc;
               background: #fff4f6;
          }

          .branch-approval-badge.draft {
               color: #586b75;
               border: 1px solid #d5e0e3;
               background: #f7fafb;
          }

          .branch-manager {
               display: flex;
               align-items: center;
               gap: 12px;
          }

          .branch-manager-avatar {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 46px;
               width: 46px;
               height: 46px;
               color: #0f766e;
               font-size: .9rem;
               font-weight: 850;
               border: 1px solid #a7e7df;
               border-radius: 14px;
               background: linear-gradient(135deg, #eafaf7, #d9f6f1);
          }

          .branch-manager-name {
               color: #2d4650;
               font-size: .86rem;
               font-weight: 850;
          }

          .branch-manager-role {
               margin-top: 2px;
               color: #758a93;
               font-size: .7rem;
          }

          .branch-summary-list {
               display: grid;
               gap: 11px;
          }

          .branch-summary-row {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 15px;
               padding-bottom: 11px;
               border-bottom: 1px dashed #dce9e7;
          }

          .branch-summary-row:last-child {
               padding-bottom: 0;
               border-bottom: 0;
          }

          .branch-summary-key {
               color: #748a93;
               font-size: .73rem;
          }

          .branch-summary-value {
               max-width: 58%;
               color: #2d4650;
               font-size: .75rem;
               font-weight: 800;
               text-align: right;
               word-break: break-word;
          }

          .branch-note {
               display: flex;
               align-items: flex-start;
               gap: 9px;
               margin-top: 15px;
               padding: 12px 13px;
               color: #8e5f00;
               font-size: .74rem;
               line-height: 1.55;
               border: 1px solid #f2d992;
               border-radius: 12px;
               background: #fffaf0;
          }

          @media (max-width: 991.98px) {
               .branch-show-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767.98px) {
               .branch-show-page {
                    padding: 12px 10px 28px;
               }

               .branch-show-hero {
                    align-items: stretch;
                    flex-direction: column;
                    padding: 24px 20px;
                    border-radius: 18px;
               }

               .branch-show-actions {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .branch-detail-grid {
                    grid-template-columns: 1fr;
               }

               .branch-detail-item.full {
                    grid-column: auto;
               }
          }

          @media (max-width: 479.98px) {
               .branch-show-actions {
                    grid-template-columns: 1fr;
               }
          }
     </style>

     <div class="branch-show-page">
          <div class="branch-show-shell">
               <header class="branch-show-hero">
                    <div>
                         <div class="branch-show-eyebrow">
                              <i class="bi bi-building-check"></i>
                              Detail Cabang
                         </div>
                         <h1>{{ $branch->branch_name }}</h1>
                         <p>
                              Informasi identitas, penanggung jawab, kontak, status operasional,
                              dan status persetujuan cabang.
                         </p>
                    </div>

                    <div class="branch-show-actions">
                         <a href="{{ route('branches.index') }}" class="branch-show-button light">
                              <i class="bi bi-arrow-left"></i>
                              Kembali
                         </a>

                         @if ($canEdit)
                              <a href="{{ route('branches.edit', $branch->id) }}" class="branch-show-button primary">
                                   <i class="bi bi-pencil-square"></i>
                                   Edit Cabang
                              </a>
                         @endif
                    </div>
               </header>

               <div class="branch-show-grid">
                    <main>
                         <section class="branch-show-card">
                              <div class="branch-show-card-header">
                                   <h2 class="branch-show-card-title">
                                        <i class="bi bi-building"></i>
                                        Identitas Cabang
                                   </h2>
                                   <span class="branch-code-badge">
                                        <i class="bi bi-upc-scan"></i>
                                        {{ $branch->branch_code }}
                                   </span>
                              </div>

                              <div class="branch-show-card-body">
                                   <div class="branch-detail-grid">
                                        <div class="branch-detail-item">
                                             <span class="branch-detail-label">Nama Cabang</span>
                                             <div class="branch-detail-value">{{ $branch->branch_name }}</div>
                                        </div>

                                        <div class="branch-detail-item">
                                             <span class="branch-detail-label">Kode Cabang</span>
                                             <div class="branch-detail-value">{{ $branch->branch_code }}</div>
                                        </div>

                                        <div class="branch-detail-item full">
                                             <span class="branch-detail-label">Alamat Lengkap</span>
                                             <div class="branch-detail-value">
                                                  {{ $branch->address ?: 'Alamat belum tersedia.' }}
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </section>

                         <section class="branch-show-card">
                              <div class="branch-show-card-header">
                                   <h2 class="branch-show-card-title">
                                        <i class="bi bi-person-vcard"></i>
                                        Penanggung Jawab dan Kontak
                                   </h2>
                              </div>

                              <div class="branch-show-card-body">
                                   <div class="branch-detail-grid">
                                        <div class="branch-detail-item full">
                                             <span class="branch-detail-label">Kepala Cabang</span>
                                             @if ($branch->manager)
                                                  <div class="branch-manager">
                                                       <div class="branch-manager-avatar">
                                                            {{ mb_strtoupper(mb_substr($branch->manager->name, 0, 1)) }}
                                                       </div>
                                                       <div>
                                                            <div class="branch-manager-name">{{ $branch->manager->name }}</div>
                                                            <div class="branch-manager-role">
                                                                 Kepala Cabang
                                                                 @if (!empty($branch->manager->email))
                                                                      · {{ $branch->manager->email }}
                                                                 @endif
                                                            </div>
                                                       </div>
                                                  </div>
                                             @else
                                                  <div class="branch-detail-value">Belum ditentukan.</div>
                                             @endif
                                        </div>

                                        <div class="branch-detail-item">
                                             <span class="branch-detail-label">Nomor Telepon</span>
                                             <div class="branch-detail-value">
                                                  @if ($branch->phone)
                                                       <a href="tel:{{ $branch->phone }}">
                                                            <i class="bi bi-telephone-fill me-1"></i>
                                                            {{ $branch->phone }}
                                                       </a>
                                                  @else
                                                       Belum tersedia.
                                                  @endif
                                             </div>
                                        </div>

                                        <div class="branch-detail-item">
                                             <span class="branch-detail-label">Email Cabang</span>
                                             <div class="branch-detail-value">
                                                  @if ($branch->email)
                                                       <a href="mailto:{{ $branch->email }}">
                                                            <i class="bi bi-envelope-fill me-1"></i>
                                                            {{ $branch->email }}
                                                       </a>
                                                  @else
                                                       Belum tersedia.
                                                  @endif
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </section>
                    </main>

                    <aside>
                         <section class="branch-show-card">
                              <div class="branch-show-card-header">
                                   <h2 class="branch-show-card-title">
                                        <i class="bi bi-activity"></i>
                                        Status Cabang
                                   </h2>
                              </div>

                              <div class="branch-show-card-body">
                                   <div class="branch-summary-list">
                                        <div class="branch-summary-row">
                                             <span class="branch-summary-key">Operasional</span>
                                             <span class="branch-summary-value">
                                                  @if ((int) $branch->status === 1)
                                                       <span class="branch-status-badge active">
                                                            <i class="bi bi-check-circle-fill"></i> Aktif
                                                       </span>
                                                  @else
                                                       <span class="branch-status-badge inactive">
                                                            <i class="bi bi-pause-circle-fill"></i> Nonaktif
                                                       </span>
                                                  @endif
                                             </span>
                                        </div>

                                        <div class="branch-summary-row">
                                             <span class="branch-summary-key">Persetujuan</span>
                                             <span class="branch-summary-value">
                                                  @switch($approvalStatus)
                                                       @case('pending')
                                                            <span class="branch-approval-badge pending">
                                                                 <i class="bi bi-hourglass-split"></i> Menunggu
                                                            </span>
                                                       @break

                                                       @case('rejected')
                                                            <span class="branch-approval-badge rejected">
                                                                 <i class="bi bi-x-octagon-fill"></i> Ditolak
                                                            </span>
                                                       @break

                                                       @case('draft')
                                                            <span class="branch-approval-badge draft">
                                                                 <i class="bi bi-file-earmark-text-fill"></i> Draft
                                                            </span>
                                                       @break

                                                       @default
                                                            <span class="branch-approval-badge approved">
                                                                 <i class="bi bi-patch-check-fill"></i> Disetujui
                                                            </span>
                                                  @endswitch
                                             </span>
                                        </div>

                                        @if ($approvalStatus === 'pending')
                                             <div class="branch-summary-row">
                                                  <span class="branch-summary-key">Tahap Berikutnya</span>
                                                  <span class="branch-summary-value">
                                                       {{ $pendingRoleLabel ?? 'Role terkait' }}
                                                  </span>
                                             </div>
                                        @endif

                                        <div class="branch-summary-row">
                                             <span class="branch-summary-key">Dibuat</span>
                                             <span class="branch-summary-value">
                                                  {{ optional($branch->created_at)->format('d M Y, H:i') ?? '-' }}
                                             </span>
                                        </div>

                                        <div class="branch-summary-row">
                                             <span class="branch-summary-key">Diperbarui</span>
                                             <span class="branch-summary-value">
                                                  {{ optional($branch->updated_at)->format('d M Y, H:i') ?? '-' }}
                                             </span>
                                        </div>
                                   </div>

                                   @if ($approvalStatus === 'pending')
                                        <div class="branch-note">
                                             <i class="bi bi-info-circle-fill mt-1"></i>
                                             <span>Data sedang dalam proses persetujuan sehingga pengubahan data dikunci sementara.</span>
                                        </div>
                                   @endif

                                   @if ($approvalStatus === 'rejected' && !empty($branch->rejection_note))
                                        <div class="branch-note">
                                             <i class="bi bi-exclamation-circle-fill mt-1"></i>
                                             <span><strong>Alasan penolakan:</strong> {{ $branch->rejection_note }}</span>
                                        </div>
                                   @endif
                              </div>
                         </section>
                    </aside>
               </div>
          </div>
     </div>
@endsection
