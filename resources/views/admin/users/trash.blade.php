@extends('layouts.app')

@section('title', 'Executive User Recycle Bin')

@section('content')
     <style>
          /* =====================================================
                COLORFUL EXECUTIVE RECYCLE BIN
                ===================================================== */
          .recycle-page {
               --primary: #4f46e5;
               --primary-light: #818cf8;
               --secondary: #06b6d4;
               --pink: #ec4899;
               --orange: #f59e0b;
               --green: #10b981;
               --red: #ef4444;
               --ink: #1e293b;
               --muted: #64748b;
               --line: #e2e8f0;
               --surface: rgba(255, 255, 255, 0.92);

               position: relative;
               min-height: calc(100vh - 70px);
               padding: 28px 18px 42px;
               overflow: hidden;
               color: var(--ink);
               background:
                    radial-gradient(circle at 8% 8%, rgba(236, 72, 153, 0.16), transparent 28%),
                    radial-gradient(circle at 92% 10%, rgba(6, 182, 212, 0.18), transparent 30%),
                    radial-gradient(circle at 85% 88%, rgba(245, 158, 11, 0.16), transparent 28%),
                    linear-gradient(145deg, #f8faff 0%, #fdf4ff 48%, #effcff 100%);
          }

          .recycle-page::before,
          .recycle-page::after {
               content: '';
               position: absolute;
               z-index: 0;
               border-radius: 999px;
               filter: blur(2px);
               pointer-events: none;
          }

          .recycle-page::before {
               width: 220px;
               height: 220px;
               top: 90px;
               right: -90px;
               background: linear-gradient(135deg, rgba(129, 140, 248, 0.25), rgba(236, 72, 153, 0.15));
          }

          .recycle-page::after {
               width: 180px;
               height: 180px;
               bottom: 45px;
               left: -75px;
               background: linear-gradient(135deg, rgba(16, 185, 129, 0.18), rgba(6, 182, 212, 0.18));
          }

          .recycle-content {
               position: relative;
               z-index: 1;
               max-width: 1600px;
               margin: 0 auto;
          }

          /* HERO */
          .trash-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 24px;
               min-height: 230px;
               padding: 38px 42px;
               border: 1px solid rgba(255, 255, 255, 0.7);
               border-radius: 30px;
               color: #ffffff;
               background:
                    linear-gradient(120deg, rgba(79, 70, 229, 0.97), rgba(6, 182, 212, 0.93) 56%, rgba(236, 72, 153, 0.91));
               box-shadow: 0 24px 60px rgba(79, 70, 229, 0.22);
          }

          .trash-hero::before {
               content: '';
               position: absolute;
               width: 310px;
               height: 310px;
               top: -170px;
               right: 8%;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.16);
          }

          .trash-hero::after {
               content: '';
               position: absolute;
               width: 170px;
               height: 170px;
               right: -35px;
               bottom: -70px;
               border: 28px solid rgba(255, 255, 255, 0.12);
               border-radius: 50%;
          }

          .hero-copy,
          .hero-actions {
               position: relative;
               z-index: 2;
          }

          .hero-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 14px;
               padding: 7px 12px;
               border: 1px solid rgba(255, 255, 255, 0.28);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.16);
               backdrop-filter: blur(8px);
               font-size: 12px;
               font-weight: 800;
               letter-spacing: 0.08em;
               text-transform: uppercase;
          }

          .trash-hero h1 {
               max-width: 720px;
               margin: 0 0 12px;
               font-size: clamp(30px, 4vw, 46px);
               line-height: 1.12;
               font-weight: 900;
               letter-spacing: -0.035em;
          }

          .trash-hero p {
               max-width: 690px;
               margin: 0;
               color: rgba(255, 255, 255, 0.88);
               font-size: 15px;
               line-height: 1.75;
          }

          .hero-actions {
               display: flex;
               flex-direction: column;
               align-items: flex-end;
               gap: 12px;
               flex-shrink: 0;
          }

          .hero-mini-card {
               min-width: 180px;
               padding: 14px 16px;
               border: 1px solid rgba(255, 255, 255, 0.25);
               border-radius: 18px;
               background: rgba(255, 255, 255, 0.14);
               backdrop-filter: blur(10px);
               text-align: right;
          }

          .hero-mini-card small {
               display: block;
               margin-bottom: 4px;
               color: rgba(255, 255, 255, 0.76);
               font-weight: 700;
          }

          .hero-mini-card strong {
               font-size: 22px;
               font-weight: 900;
          }

          .btn-back {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-width: 180px;
               padding: 13px 20px;
               border: 1px solid rgba(255, 255, 255, 0.7);
               border-radius: 15px;
               color: var(--primary);
               background: #ffffff;
               box-shadow: 0 12px 26px rgba(30, 41, 59, 0.13);
               font-weight: 800;
               text-decoration: none;
               transition: transform 0.25s ease, box-shadow 0.25s ease;
          }

          .btn-back:hover {
               color: var(--primary);
               transform: translateY(-3px);
               box-shadow: 0 16px 32px rgba(30, 41, 59, 0.18);
          }

          /* INFO CARDS */
          .summary-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 18px;
               margin: 22px 0;
          }

          .summary-card {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               gap: 16px;
               min-height: 128px;
               padding: 22px;
               border: 1px solid rgba(255, 255, 255, 0.9);
               border-radius: 22px;
               background: var(--surface);
               box-shadow: 0 14px 36px rgba(71, 85, 105, 0.10);
               backdrop-filter: blur(12px);
               transition: transform 0.25s ease, box-shadow 0.25s ease;
          }

          .summary-card:hover {
               transform: translateY(-4px);
               box-shadow: 0 20px 42px rgba(71, 85, 105, 0.15);
          }

          .summary-card::after {
               content: '';
               position: absolute;
               width: 90px;
               height: 90px;
               right: -34px;
               bottom: -38px;
               border-radius: 50%;
               opacity: 0.24;
          }

          .summary-card.purple::after {
               background: var(--primary-light);
          }

          .summary-card.green::after {
               background: var(--green);
          }

          .summary-card.orange::after {
               background: var(--orange);
          }

          .summary-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 64px;
               height: 64px;
               flex: 0 0 64px;
               border-radius: 19px;
               font-size: 27px;
          }

          .purple .summary-icon {
               color: #4338ca;
               background: linear-gradient(135deg, #e0e7ff, #f5d0fe);
          }

          .green .summary-icon {
               color: #047857;
               background: linear-gradient(135deg, #d1fae5, #cffafe);
          }

          .orange .summary-icon {
               color: #c2410c;
               background: linear-gradient(135deg, #ffedd5, #fef3c7);
          }

          .summary-label {
               margin-bottom: 4px;
               color: var(--muted);
               font-size: 12px;
               font-weight: 800;
               letter-spacing: 0.055em;
               text-transform: uppercase;
          }

          .summary-number {
               margin: 0;
               color: var(--ink);
               font-size: 32px;
               line-height: 1;
               font-weight: 900;
          }

          .summary-description {
               margin: 7px 0 0;
               color: var(--muted);
               font-size: 13px;
               line-height: 1.5;
          }

          /* TABLE PANEL */
          .table-card {
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, 0.95);
               border-radius: 27px;
               background: rgba(255, 255, 255, 0.94);
               box-shadow: 0 20px 48px rgba(71, 85, 105, 0.12);
               backdrop-filter: blur(14px);
          }

          .table-card-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               padding: 24px 26px;
               border-bottom: 1px solid #edf2f7;
               background:
                    linear-gradient(90deg, rgba(224, 231, 255, 0.72), rgba(207, 250, 254, 0.55), rgba(252, 231, 243, 0.55));
          }

          .table-title-wrap {
               display: flex;
               align-items: center;
               gap: 13px;
          }

          .table-title-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
               color: #ffffff;
               background: linear-gradient(135deg, var(--primary), var(--pink));
               box-shadow: 0 10px 20px rgba(79, 70, 229, 0.22);
               font-size: 20px;
          }

          .table-card-header h4 {
               margin: 0;
               color: var(--ink);
               font-size: 19px;
               font-weight: 900;
          }

          .table-card-header p {
               margin: 3px 0 0;
               color: var(--muted);
               font-size: 13px;
          }

          .data-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 9px 14px;
               border-radius: 999px;
               color: #be123c;
               background: #ffe4e6;
               font-size: 13px;
               font-weight: 900;
               white-space: nowrap;
          }

          .table-wrap {
               padding: 8px 18px 0;
          }

          .recycle-table {
               min-width: 850px;
               margin-bottom: 0;
          }

          .recycle-table thead th {
               padding: 15px 14px;
               border: 0;
               color: #64748b;
               background: transparent;
               font-size: 11px;
               font-weight: 900;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               white-space: nowrap;
          }

          .recycle-table tbody td {
               padding: 16px 14px;
               border-top: 1px solid #eef2f7;
               color: #334155;
               vertical-align: middle;
          }

          .recycle-table tbody tr {
               transition: background 0.22s ease, transform 0.22s ease;
          }

          .recycle-table tbody tr:hover {
               background: linear-gradient(90deg, rgba(238, 242, 255, 0.82), rgba(240, 253, 250, 0.62));
          }

          .user-cell {
               display: flex;
               align-items: center;
               gap: 13px;
          }

          .avatar-trash {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 50px;
               height: 50px;
               flex: 0 0 50px;
               border: 3px solid #ffffff;
               border-radius: 16px;
               color: #ffffff;
               background: linear-gradient(135deg, #6366f1, #ec4899);
               box-shadow: 0 8px 18px rgba(99, 102, 241, 0.22);
               font-size: 18px;
               font-weight: 900;
          }

          .user-name {
               display: block;
               color: var(--ink);
               font-weight: 850;
          }

          .user-meta {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: 12px;
          }

          .email-link {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               color: #475569;
               text-decoration: none;
               word-break: break-word;
          }

          .email-link i {
               color: var(--secondary);
          }

          .email-link:hover {
               color: var(--primary);
          }

          .status-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 7px 12px;
               border-radius: 999px;
               font-size: 12px;
               font-weight: 900;
               white-space: nowrap;
          }

          .status-badge::before {
               content: '';
               width: 7px;
               height: 7px;
               border-radius: 50%;
               background: currentColor;
          }

          .status-active {
               color: #047857;
               background: #d1fae5;
          }

          .status-inactive {
               color: #b45309;
               background: #fef3c7;
          }

          .status-suspended {
               color: #be123c;
               background: #ffe4e6;
          }

          .deleted-date {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: #475569;
               white-space: nowrap;
          }

          .deleted-date i {
               color: var(--orange);
          }

          .action-group {
               display: flex;
               align-items: center;
               flex-wrap: wrap;
               gap: 8px;
          }

          .action-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 40px;
               padding: 9px 14px;
               border: 0;
               border-radius: 12px;
               font-size: 12px;
               font-weight: 900;
               transition: transform 0.22s ease, box-shadow 0.22s ease, filter 0.22s ease;
          }

          .action-btn:hover {
               transform: translateY(-2px);
               filter: saturate(1.08);
          }

          .restore {
               color: #ffffff;
               background: linear-gradient(135deg, #10b981, #06b6d4);
               box-shadow: 0 8px 18px rgba(16, 185, 129, 0.2);
          }

          .restore:hover {
               color: #ffffff;
               box-shadow: 0 11px 22px rgba(16, 185, 129, 0.28);
          }

          .delete {
               color: #ffffff;
               background: linear-gradient(135deg, #f43f5e, #ef4444);
               box-shadow: 0 8px 18px rgba(239, 68, 68, 0.2);
          }

          .delete:hover {
               color: #ffffff;
               box-shadow: 0 11px 22px rgba(239, 68, 68, 0.28);
          }

          /* EMPTY STATE */
          .empty-box {
               padding: 68px 20px;
               text-align: center;
          }

          .empty-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 96px;
               height: 96px;
               margin-bottom: 18px;
               border-radius: 30px;
               color: #7c3aed;
               background: linear-gradient(135deg, #ede9fe, #cffafe, #fce7f3);
               font-size: 42px;
               transform: rotate(-4deg);
          }

          .empty-box h5 {
               margin-bottom: 7px;
               color: var(--ink);
               font-weight: 900;
          }

          .empty-box p {
               margin: 0;
               color: var(--muted);
          }

          /* PAGINATION */
          .pagination-area {
               display: flex;
               justify-content: flex-end;
               padding: 20px 24px 24px;
               border-top: 1px solid #f1f5f9;
          }

          .pagination-area .pagination {
               margin-bottom: 0;
               gap: 5px;
          }

          .pagination-area .page-link {
               min-width: 38px;
               border: 0;
               border-radius: 10px !important;
               color: #4f46e5;
               background: #eef2ff;
               font-weight: 800;
               text-align: center;
          }

          .pagination-area .page-item.active .page-link {
               color: #ffffff;
               background: linear-gradient(135deg, var(--primary), var(--pink));
               box-shadow: 0 7px 16px rgba(79, 70, 229, 0.22);
          }

          .pagination-area .page-item.disabled .page-link {
               color: #94a3b8;
               background: #f8fafc;
          }

          /* RESPONSIVE */
          @media (max-width: 991.98px) {
               .trash-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 32px;
               }

               .hero-actions {
                    width: 100%;
                    align-items: stretch;
                    flex-direction: row;
               }

               .hero-mini-card,
               .btn-back {
                    flex: 1;
                    min-width: 0;
               }

               .hero-mini-card {
                    text-align: left;
               }

               .summary-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 575.98px) {
               .recycle-page {
                    padding: 18px 10px 30px;
               }

               .trash-hero {
                    min-height: 0;
                    padding: 26px 22px;
                    border-radius: 24px;
               }

               .trash-hero h1 {
                    font-size: 30px;
               }

               .hero-actions {
                    flex-direction: column;
               }

               .table-card-header {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 20px;
               }

               .table-wrap {
                    padding-inline: 10px;
               }

               .pagination-area {
                    justify-content: center;
                    padding-inline: 14px;
               }
          }
     </style>

     <div class="recycle-page">
          <div class="recycle-content">
               {{-- HERO HEADER --}}
               <section class="trash-hero">
                    <div class="hero-copy">
                         <span class="hero-eyebrow">
                              <i class="bi bi-shield-check"></i>
                              Executive Control Panel
                         </span>

                         <h1>
                              <i class="bi bi-trash3 me-2"></i>
                              Recycle Bin Management
                         </h1>

                         <p>
                              Kelola akun pengguna yang telah dihapus sementara. Pulihkan akun yang masih diperlukan
                              atau hapus permanen data yang sudah tidak digunakan.
                         </p>
                    </div>

                    <div class="hero-actions">
                         <div class="hero-mini-card">
                              <small>Data tersimpan</small>
                              <strong>{{ $users->total() }} pengguna</strong>
                         </div>

                         <a href="{{ route('admin.users.index') }}" class="btn-back">
                              <i class="bi bi-arrow-left"></i>
                              Kembali ke Pengguna
                         </a>
                    </div>
               </section>

               {{-- SUMMARY CARDS --}}
               <section class="summary-grid">
                    <article class="summary-card purple">
                         <div class="summary-icon">
                              <i class="bi bi-trash3-fill"></i>
                         </div>
                         <div>
                              <div class="summary-label">Total Data Terhapus</div>
                              <div class="summary-number">{{ $users->total() }}</div>
                              <p class="summary-description">Jumlah akun yang berada di recycle bin.</p>
                         </div>
                    </article>

                    <article class="summary-card green">
                         <div class="summary-icon">
                              <i class="bi bi-arrow-counterclockwise"></i>
                         </div>
                         <div>
                              <div class="summary-label">Pemulihan Akun</div>
                              <div class="summary-number">Restore</div>
                              <p class="summary-description">Kembalikan akun ke daftar pengguna aktif.</p>
                         </div>
                    </article>

                    <article class="summary-card orange">
                         <div class="summary-icon">
                              <i class="bi bi-exclamation-triangle-fill"></i>
                         </div>
                         <div>
                              <div class="summary-label">Keamanan Data</div>
                              <div class="summary-number">Permanent</div>
                              <p class="summary-description">Penghapusan permanen tidak dapat dibatalkan.</p>
                         </div>
                    </article>
               </section>

               {{-- USER TABLE --}}
               <section class="table-card">
                    <div class="table-card-header">
                         <div class="table-title-wrap">
                              <span class="table-title-icon">
                                   <i class="bi bi-people-fill"></i>
                              </span>
                              <div>
                                   <h4>Daftar Pengguna Terhapus</h4>
                                   <p>Periksa informasi pengguna sebelum melakukan tindakan.</p>
                              </div>
                         </div>

                         <span class="data-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ $users->total() }} Data
                         </span>
                    </div>

                    <div class="table-wrap table-responsive">
                         <table class="table recycle-table align-middle">
                              <thead>
                                   <tr>
                                        <th>Pengguna</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Tanggal Hapus</th>
                                        <th>Aksi</th>
                                   </tr>
                              </thead>

                              <tbody>
                                   @forelse($users as $user)
                                        <tr>
                                             <td>
                                                  <div class="user-cell">
                                                       <div class="avatar-trash">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                       </div>

                                                       <div>
                                                            <span class="user-name">{{ $user->name }}</span>
                                                            <span class="user-meta">User ID: #{{ $user->id }}</span>
                                                       </div>
                                                  </div>
                                             </td>

                                             <td>
                                                  <a href="mailto:{{ $user->email }}" class="email-link">
                                                       <i class="bi bi-envelope-fill"></i>
                                                       {{ $user->email }}
                                                  </a>
                                             </td>

                                             <td>
                                                  @if ($user->status === 'active')
                                                       <span class="status-badge status-active">Active</span>
                                                  @elseif ($user->status === 'inactive')
                                                       <span class="status-badge status-inactive">Inactive</span>
                                                  @else
                                                       <span class="status-badge status-suspended">Suspended</span>
                                                  @endif
                                             </td>

                                             <td>
                                                  <span class="deleted-date">
                                                       <i class="bi bi-calendar2-week-fill"></i>
                                                       {{ $user->deleted_at ? $user->deleted_at->format('d M Y H:i') : '-' }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="action-group">
                                                       <form method="POST"
                                                            action="{{ route('admin.users.restore', $user->id) }}">
                                                            @csrf

                                                            <button type="submit" class="action-btn restore"
                                                                 title="Pulihkan pengguna">
                                                                 <i class="bi bi-arrow-clockwise"></i>
                                                                 Restore
                                                            </button>
                                                       </form>

                                                       <form method="POST"
                                                            action="{{ route('admin.users.forceDelete', $user->id) }}"
                                                            onsubmit="return confirm('Hapus permanen pengguna ini? Data tidak dapat dikembalikan.')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="action-btn delete"
                                                                 title="Hapus permanen pengguna">
                                                                 <i class="bi bi-trash3-fill"></i>
                                                                 Hapus
                                                            </button>
                                                       </form>
                                                  </div>
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="5">
                                                  <div class="empty-box">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-trash3"></i>
                                                       </div>
                                                       <h5>Recycle Bin Kosong</h5>
                                                       <p>Tidak ada pengguna yang sedang berada di recycle bin.</p>
                                                  </div>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>

                    @if ($users->hasPages())
                         <div class="pagination-area">
                              {{ $users->links() }}
                         </div>
                    @endif
               </section>
          </div>
     </div>
@endsection
