@extends('layouts.app')

@section('title', 'Sampah Cabang')

@section('content')
     <style>
          .branch-trash-page {
               --bt-primary: #2563eb;
               --bt-primary-dark: #1d4ed8;
               --bt-danger: #dc2626;
               --bt-danger-dark: #b91c1c;
               --bt-success: #16a34a;
               --bt-text: #0f172a;
               --bt-muted: #64748b;
               --bt-border: #e2e8f0;
               min-height: calc(100vh - 70px);
               color: var(--bt-text);
               background:
                    radial-gradient(circle at top right, rgba(220, 38, 38, .08), transparent 26%),
                    radial-gradient(circle at bottom left, rgba(37, 99, 235, .06), transparent 24%),
                    #f8fafc;
          }

          .branch-trash-page .trash-shell {
               max-width: 1480px;
               margin: 0 auto;
          }

          .branch-trash-page .trash-hero {
               position: relative;
               overflow: hidden;
               padding: 30px 32px;
               color: #fff;
               border-radius: 24px;
               background:
                    radial-gradient(circle at 90% 8%, rgba(255, 255, 255, .20), transparent 23%),
                    linear-gradient(135deg, #7f1d1d 0%, #dc2626 52%, #f97316 100%);
               box-shadow: 0 20px 45px rgba(185, 28, 28, .20);
          }

          .branch-trash-page .trash-hero::after {
               content: '';
               position: absolute;
               right: -95px;
               bottom: -130px;
               width: 230px;
               height: 230px;
               border: 34px solid rgba(255, 255, 255, .10);
               border-radius: 50%;
          }

          .branch-trash-page .hero-content,
          .branch-trash-page .hero-actions {
               position: relative;
               z-index: 2;
          }

          .branch-trash-page .hero-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 58px;
               width: 58px;
               height: 58px;
               font-size: 1.45rem;
               border: 1px solid rgba(255, 255, 255, .32);
               border-radius: 18px;
               background: rgba(255, 255, 255, .14);
               backdrop-filter: blur(8px);
          }

          .branch-trash-page .hero-eyebrow {
               display: block;
               margin-bottom: 5px;
               color: rgba(255, 255, 255, .76);
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .12em;
               text-transform: uppercase;
          }

          .branch-trash-page .hero-title {
               margin: 0 0 7px;
               font-size: clamp(1.65rem, 3vw, 2.2rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .branch-trash-page .hero-description {
               max-width: 760px;
               margin: 0;
               color: rgba(255, 255, 255, .82);
               line-height: 1.65;
          }

          .branch-trash-page .hero-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 46px;
               padding: 11px 17px;
               color: #fff;
               font-size: .82rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
               backdrop-filter: blur(8px);
               transition: .2s ease;
          }

          .branch-trash-page .hero-button:hover {
               color: #991b1b;
               background: #fff;
               transform: translateY(-2px);
          }

          .branch-trash-page .alert-modern {
               display: flex;
               align-items: flex-start;
               gap: 12px;
               padding: 16px 18px;
               border-radius: 15px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .05);
          }

          .branch-trash-page .alert-success-modern {
               color: #166534;
               border: 1px solid #bbf7d0;
               background: #f0fdf4;
          }

          .branch-trash-page .warning-card {
               display: flex;
               align-items: flex-start;
               gap: 13px;
               padding: 17px 19px;
               color: #92400e;
               border: 1px solid #fde68a;
               border-radius: 17px;
               background: #fffbeb;
          }

          .branch-trash-page .warning-card i {
               margin-top: 1px;
               font-size: 1.25rem;
          }

          .branch-trash-page .warning-card strong {
               display: block;
               margin-bottom: 3px;
               color: #78350f;
          }

          .branch-trash-page .surface-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .95);
               border-radius: 22px;
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 16px 42px rgba(15, 23, 42, .07);
          }

          .branch-trash-page .list-header {
               padding: 21px 24px;
               border-bottom: 1px solid var(--bt-border);
          }

          .branch-trash-page .section-title {
               margin: 0 0 4px;
               font-size: 1.02rem;
               font-weight: 850;
          }

          .branch-trash-page .section-description {
               margin: 0;
               color: var(--bt-muted);
               font-size: .79rem;
          }

          .branch-trash-page .record-count {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 8px 12px;
               color: #991b1b;
               font-size: .75rem;
               font-weight: 800;
               border: 1px solid #fecaca;
               border-radius: 999px;
               background: #fff1f2;
          }

          .branch-trash-page .trash-table {
               min-width: 1050px;
               margin: 0;
          }

          .branch-trash-page .trash-table thead th {
               padding: 15px 18px;
               color: #475569;
               font-size: .71rem;
               font-weight: 850;
               letter-spacing: .04em;
               text-transform: uppercase;
               border-bottom: 1px solid var(--bt-border);
               background: #f8fafc;
               white-space: nowrap;
          }

          .branch-trash-page .trash-table tbody td {
               padding: 17px 18px;
               font-size: .81rem;
               border-color: #edf2f7;
               vertical-align: middle;
          }

          .branch-trash-page .trash-table tbody tr:hover {
               background: #fffafa;
          }

          .branch-trash-page .row-number {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 34px;
               height: 34px;
               color: #475569;
               font-size: .76rem;
               font-weight: 850;
               border-radius: 10px;
               background: #f1f5f9;
          }

          .branch-trash-page .branch-code {
               display: inline-flex;
               padding: 7px 10px;
               color: #1d4ed8;
               font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
               font-size: .76rem;
               font-weight: 850;
               border: 1px solid #bfdbfe;
               border-radius: 9px;
               background: #eff6ff;
          }

          .branch-trash-page .branch-name {
               margin-bottom: 5px;
               font-size: .86rem;
               font-weight: 850;
          }

          .branch-trash-page .branch-address,
          .branch-trash-page .contact-item,
          .branch-trash-page .deleted-at {
               display: flex;
               align-items: flex-start;
               gap: 7px;
               color: var(--bt-muted);
               font-size: .74rem;
               line-height: 1.45;
          }

          .branch-trash-page .contact-stack {
               display: grid;
               gap: 7px;
          }

          .branch-trash-page .manager-name {
               font-weight: 800;
          }

          .branch-trash-page .manager-empty {
               color: #94a3b8;
               font-style: italic;
          }

          .branch-trash-page .action-group {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
          }

          .branch-trash-page .action-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 38px;
               padding: 8px 11px;
               font-size: .73rem;
               font-weight: 800;
               border-radius: 11px;
               transition: .2s ease;
          }

          .branch-trash-page .action-restore {
               color: #166534;
               border: 1px solid #bbf7d0;
               background: #f0fdf4;
          }

          .branch-trash-page .action-restore:hover {
               color: #fff;
               border-color: var(--bt-success);
               background: var(--bt-success);
          }

          .branch-trash-page .action-force-delete {
               color: #991b1b;
               border: 1px solid #fecaca;
               background: #fff1f2;
          }

          .branch-trash-page .action-force-delete:hover {
               color: #fff;
               border-color: var(--bt-danger);
               background: var(--bt-danger);
          }

          .branch-trash-page .empty-state {
               padding: 65px 24px !important;
          }

          .branch-trash-page .empty-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 72px;
               height: 72px;
               margin-bottom: 16px;
               color: #64748b;
               font-size: 1.8rem;
               border-radius: 22px;
               background: #f1f5f9;
          }

          .branch-trash-page .empty-title {
               margin: 0 0 7px;
               font-size: 1rem;
               font-weight: 850;
          }

          .branch-trash-page .empty-description {
               max-width: 520px;
               margin: 0 auto 18px;
               color: var(--bt-muted);
               font-size: .8rem;
               line-height: 1.6;
          }

          .branch-trash-page .list-footer {
               padding: 17px 24px;
               border-top: 1px solid var(--bt-border);
               background: #f8fafc;
          }

          .branch-trash-page .result-info {
               color: var(--bt-muted);
               font-size: .76rem;
          }

          .branch-trash-page .modal-content {
               overflow: hidden;
               border: 0;
               border-radius: 20px;
               box-shadow: 0 24px 65px rgba(15, 23, 42, .20);
          }

          .branch-trash-page .modal-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 62px;
               height: 62px;
               margin-bottom: 14px;
               font-size: 1.5rem;
               border-radius: 18px;
          }

          .branch-trash-page .modal-icon.restore {
               color: #15803d;
               background: #dcfce7;
          }

          .branch-trash-page .modal-icon.delete {
               color: #b91c1c;
               background: #fee2e2;
          }

          @media (max-width: 767.98px) {
               .branch-trash-page .trash-hero {
                    padding: 24px;
                    border-radius: 20px;
               }

               .branch-trash-page .hero-layout,
               .branch-trash-page .list-header>div,
               .branch-trash-page .list-footer>div {
                    align-items: stretch !important;
                    flex-direction: column;
               }

               .branch-trash-page .hero-button {
                    width: 100%;
               }
          }
     </style>

     <div class="branch-trash-page py-4">
          <div class="container-fluid trash-shell px-3 px-lg-4">
               <header class="trash-hero mb-4">
                    <div class="hero-layout d-flex align-items-center justify-content-between gap-4">
                         <div class="hero-content d-flex align-items-center gap-3">
                              <span class="hero-icon"><i class="bi bi-trash3-fill"></i></span>
                              <div>
                                   <span class="hero-eyebrow">Branch Management</span>
                                   <h1 class="hero-title">Sampah Cabang</h1>
                                   <p class="hero-description">
                                        Pulihkan cabang yang masih dibutuhkan atau hapus permanen data yang sudah tidak
                                        digunakan.
                                   </p>
                              </div>
                         </div>

                         <div class="hero-actions">
                              <a href="{{ route('super-admin.branches.index') }}" class="hero-button">
                                   <i class="bi bi-arrow-left"></i>Kembali ke Daftar
                              </a>
                         </div>
                    </div>
               </header>

               @if (session('success'))
                    <div class="alert-modern alert-success-modern mb-3" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <div>{{ session('success') }}</div>
                    </div>
               @endif

               <div class="warning-card mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                         <strong>Perhatian sebelum menghapus permanen</strong>
                         Data yang sudah dihapus permanen tidak dapat dipulihkan kembali. Gunakan tindakan ini hanya setelah
                         memastikan cabang tidak lagi diperlukan.
                    </div>
               </div>

               <section class="surface-card">
                    <div class="list-header">
                         <div class="d-flex align-items-center justify-content-between gap-3">
                              <div>
                                   <h2 class="section-title"><i class="bi bi-archive-fill text-danger me-2"></i>Data Cabang
                                        Terhapus</h2>
                                   <p class="section-description">Daftar cabang yang dihapus sementara melalui fitur soft
                                        delete.</p>
                              </div>
                              <span class="record-count">
                                   <i class="bi bi-trash3"></i>{{ number_format($branches->total()) }} data
                              </span>
                         </div>
                    </div>

                    <div class="table-responsive">
                         <table class="table trash-table align-middle">
                              <thead>
                                   <tr>
                                        <th class="text-center" style="width: 70px;">No.</th>
                                        <th>Kode</th>
                                        <th>Informasi Cabang</th>
                                        <th>Kepala Cabang</th>
                                        <th>Kontak</th>
                                        <th>Waktu Dihapus</th>
                                        <th class="text-center" style="width: 245px;">Aksi</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse($branches as $branch)
                                        <tr>
                                             <td class="text-center">
                                                  <span
                                                       class="row-number">{{ ($branches->firstItem() ?? 1) + $loop->index }}</span>
                                             </td>
                                             <td><span class="branch-code">{{ $branch->branch_code }}</span></td>
                                             <td>
                                                  <div class="branch-name">{{ $branch->branch_name }}</div>
                                                  <div class="branch-address">
                                                       <i class="bi bi-geo-alt-fill text-danger"></i>
                                                       <span>{{ $branch->address ?: 'Alamat belum tersedia' }}</span>
                                                  </div>
                                             </td>
                                             <td>
                                                  @if ($branch->manager)
                                                       <div class="manager-name">{{ $branch->manager->name }}</div>
                                                       <small class="text-muted">Kepala Cabang</small>
                                                  @else
                                                       <span class="manager-empty">Belum ditentukan</span>
                                                  @endif
                                             </td>
                                             <td>
                                                  <div class="contact-stack">
                                                       <div class="contact-item">
                                                            <i class="bi bi-telephone-fill text-success"></i>
                                                            <span>{{ $branch->phone ?: '-' }}</span>
                                                       </div>
                                                       <div class="contact-item">
                                                            <i class="bi bi-envelope-fill text-primary"></i>
                                                            <span>{{ $branch->email ?: '-' }}</span>
                                                       </div>
                                                  </div>
                                             </td>
                                             <td>
                                                  <div class="deleted-at">
                                                       <i class="bi bi-calendar-x-fill text-danger"></i>
                                                       <span>
                                                            {{ optional($branch->deleted_at)->format('d M Y') }}<br>
                                                            <small>{{ optional($branch->deleted_at)->format('H:i') }}
                                                                 WIB</small>
                                                       </span>
                                                  </div>
                                             </td>
                                             <td class="text-center">
                                                  <div class="action-group">
                                                       <button type="button" class="btn action-button action-restore"
                                                            data-bs-toggle="modal" data-bs-target="#restoreBranchModal"
                                                            data-action-url="{{ route('super-admin.branches.restore', $branch->id) }}"
                                                            data-branch-name="{{ $branch->branch_name }}">
                                                            <i class="bi bi-arrow-counterclockwise"></i>Pulihkan
                                                       </button>

                                                       <button type="button" class="btn action-button action-force-delete"
                                                            data-bs-toggle="modal" data-bs-target="#forceDeleteBranchModal"
                                                            data-action-url="{{ route('super-admin.branches.force-delete', $branch->id) }}"
                                                            data-branch-name="{{ $branch->branch_name }}">
                                                            <i class="bi bi-trash3-fill"></i>Hapus Permanen
                                                       </button>
                                                  </div>
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="7" class="empty-state text-center">
                                                  <div class="empty-icon"><i class="bi bi-trash3"></i></div>
                                                  <h3 class="empty-title">Sampah cabang masih kosong</h3>
                                                  <p class="empty-description">
                                                       Belum ada cabang yang dihapus sementara. Data yang dihapus dari
                                                       halaman daftar akan tampil di sini.
                                                  </p>
                                                  <a href="{{ route('super-admin.branches.index') }}"
                                                       class="btn btn-primary rounded-3 fw-bold">
                                                       <i class="bi bi-list-ul me-1"></i>Lihat Daftar Cabang
                                                  </a>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>

                    @if ($branches->hasPages() || $branches->total() > 0)
                         <div class="list-footer">
                              <div class="d-flex align-items-center justify-content-between gap-3">
                                   <div class="result-info">
                                        @if ($branches->total() > 0)
                                             Menampilkan
                                             <strong>{{ $branches->firstItem() }}</strong>–<strong>{{ $branches->lastItem() }}</strong>
                                             dari <strong>{{ $branches->total() }}</strong> cabang terhapus
                                        @else
                                             Tidak ada data yang ditampilkan
                                        @endif
                                   </div>
                                   <div>{{ $branches->links() }}</div>
                              </div>
                         </div>
                    @endif
               </section>
          </div>

          <div class="modal fade" id="restoreBranchModal" tabindex="-1" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                         <div class="modal-body p-4 p-md-5 text-center">
                              <div class="modal-icon restore"><i class="bi bi-arrow-counterclockwise"></i></div>
                              <h3 class="h5 fw-bold mb-2">Pulihkan cabang?</h3>
                              <p class="text-muted mb-4">
                                   Cabang <strong data-restore-name>-</strong> akan dikembalikan ke daftar cabang.
                              </p>
                              <form method="POST" data-restore-form>
                                   @csrf
                                   <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-light rounded-3 fw-bold"
                                             data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success rounded-3 fw-bold">
                                             <i class="bi bi-arrow-counterclockwise me-1"></i>Pulihkan
                                        </button>
                                   </div>
                              </form>
                         </div>
                    </div>
               </div>
          </div>

          <div class="modal fade" id="forceDeleteBranchModal" tabindex="-1" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                         <div class="modal-body p-4 p-md-5 text-center">
                              <div class="modal-icon delete"><i class="bi bi-trash3-fill"></i></div>
                              <h3 class="h5 fw-bold mb-2">Hapus permanen?</h3>
                              <p class="text-muted mb-2">
                                   Cabang <strong data-delete-name>-</strong> akan dihapus permanen.
                              </p>
                              <p class="small text-danger fw-bold mb-4">Tindakan ini tidak dapat dibatalkan.</p>
                              <form method="POST" data-delete-form>
                                   @csrf
                                   @method('DELETE')
                                   <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-light rounded-3 fw-bold"
                                             data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger rounded-3 fw-bold">
                                             <i class="bi bi-trash3-fill me-1"></i>Hapus Permanen
                                        </button>
                                   </div>
                              </form>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const restoreModal = document.getElementById('restoreBranchModal');
               const deleteModal = document.getElementById('forceDeleteBranchModal');

               if (restoreModal) {
                    restoreModal.addEventListener('show.bs.modal', function(event) {
                         const button = event.relatedTarget;
                         restoreModal.querySelector('[data-restore-form]').action = button.getAttribute(
                              'data-action-url');
                         restoreModal.querySelector('[data-restore-name]').textContent = button
                              .getAttribute('data-branch-name');
                    });
               }

               if (deleteModal) {
                    deleteModal.addEventListener('show.bs.modal', function(event) {
                         const button = event.relatedTarget;
                         deleteModal.querySelector('[data-delete-form]').action = button.getAttribute(
                              'data-action-url');
                         deleteModal.querySelector('[data-delete-name]').textContent = button
                              .getAttribute('data-branch-name');
                    });
               }
          });
     </script>
@endsection
