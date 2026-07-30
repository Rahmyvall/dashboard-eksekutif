@extends('layouts.app')

@section('title', 'Detail Department')

@section('content')
     <style>
          :root {
               --dept-primary: #6366f1;
               --dept-primary-dark: #4f46e5;
               --dept-secondary: #06b6d4;
               --dept-purple: #8b5cf6;
               --dept-pink: #ec4899;
               --dept-success: #10b981;
               --dept-warning: #f59e0b;
               --dept-danger: #ef4444;
               --dept-text: #24324a;
               --dept-muted: #718096;
               --dept-border: #e7eaf3;
               --dept-white: #ffffff;
          }

          .department-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 4% 4%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 97% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    radial-gradient(circle at 86% 94%, rgba(244, 114, 182, .14), transparent 22%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .department-container {
               max-width: 1500px;
               margin: 0 auto;
          }

          .department-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, .72);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 17%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 43%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .department-hero::before {
               position: absolute;
               top: -76px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .department-hero::after {
               position: absolute;
               right: -36px;
               bottom: -78px;
               width: 180px;
               height: 180px;
               content: '';
               border-radius: 45px;
               background: rgba(255, 255, 255, .12);
               transform: rotate(28deg);
          }

          .hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .hero-title-wrap {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               color: var(--dept-primary-dark);
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .department-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.5vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .department-hero p {
               max-width: 730px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .97rem;
               line-height: 1.7;
          }

          .hero-actions {
               display: flex;
               gap: 10px;
               align-items: center;
          }

          .btn-hero {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .88rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 14px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
               transition: .22s ease;
          }

          .btn-hero:hover {
               color: #312e81;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(76, 29, 149, .22);
          }

          .btn-hero-soft {
               color: #ffffff;
               border-color: rgba(255, 255, 255, .4);
               background: rgba(255, 255, 255, .16);
               backdrop-filter: blur(10px);
          }

          .btn-hero-soft:hover {
               color: #ffffff;
               background: rgba(255, 255, 255, .24);
          }

          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--dept-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--dept-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .detail-card,
          .side-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .detail-card-header {
               display: flex;
               gap: 15px;
               align-items: center;
               padding: 23px 25px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #ffffff 0%, #f7f9ff 48%, #f2fbff 100%);
          }

          .detail-card-icon {
               display: inline-flex;
               flex: 0 0 48px;
               width: 48px;
               height: 48px;
               color: #ffffff;
               font-size: 1.15rem;
               align-items: center;
               justify-content: center;
               border-radius: 15px;
               background: linear-gradient(135deg, var(--dept-primary), var(--dept-secondary));
               box-shadow: 0 10px 22px rgba(99, 102, 241, .23);
          }

          .detail-card-header h4 {
               margin: 0;
               color: var(--dept-text);
               font-size: 1.1rem;
               font-weight: 850;
          }

          .detail-card-header p {
               margin: 4px 0 0;
               color: var(--dept-muted);
               font-size: .83rem;
          }

          .detail-card-body {
               padding: 26px;
          }

          .identity-panel {
               position: relative;
               overflow: hidden;
               display: flex;
               gap: 20px;
               align-items: center;
               padding: 24px;
               margin-bottom: 24px;
               border: 1px solid #e0e7ff;
               border-radius: 20px;
               background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 50%, #ecfeff 100%);
          }

          .identity-panel::after {
               position: absolute;
               right: -36px;
               bottom: -55px;
               width: 135px;
               height: 135px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .62);
          }

          .identity-avatar {
               position: relative;
               z-index: 1;
               display: inline-flex;
               flex: 0 0 76px;
               width: 76px;
               height: 76px;
               color: #ffffff;
               font-size: 1.8rem;
               font-weight: 850;
               align-items: center;
               justify-content: center;
               border: 5px solid rgba(255, 255, 255, .86);
               border-radius: 22px;
               background: linear-gradient(135deg, #6366f1, #8b5cf6 58%, #06b6d4);
               box-shadow: 0 14px 28px rgba(99, 102, 241, .25);
          }

          .identity-content {
               position: relative;
               z-index: 1;
               min-width: 0;
          }

          .identity-code {
               display: inline-flex;
               gap: 6px;
               align-items: center;
               padding: 7px 11px;
               margin-bottom: 8px;
               color: #6d28d9;
               font-size: .75rem;
               font-weight: 850;
               letter-spacing: .05em;
               border: 1px solid #ddd6fe;
               border-radius: 10px;
               background: rgba(255, 255, 255, .78);
          }

          .identity-name {
               margin: 0;
               color: #24324a;
               font-size: clamp(1.25rem, 2vw, 1.7rem);
               font-weight: 850;
               letter-spacing: -.025em;
          }

          .identity-subtitle {
               margin: 6px 0 0;
               color: #64748b;
               font-size: .86rem;
          }

          .detail-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 16px;
          }

          .detail-item {
               padding: 19px;
               border: 1px solid #edf0f7;
               border-radius: 17px;
               background: #ffffff;
               transition: .2s ease;
          }

          .detail-item:hover {
               border-color: #dbe3ff;
               background: #fbfcff;
               transform: translateY(-2px);
               box-shadow: 0 12px 24px rgba(51, 65, 85, .06);
          }

          .detail-item-full {
               grid-column: 1 / -1;
          }

          .detail-label {
               display: flex;
               gap: 8px;
               align-items: center;
               margin-bottom: 9px;
               color: #78869c;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .065em;
               text-transform: uppercase;
          }

          .detail-label i {
               color: var(--dept-primary);
               font-size: .9rem;
          }

          .detail-value {
               margin: 0;
               color: var(--dept-text);
               font-size: .93rem;
               font-weight: 750;
               line-height: 1.65;
               overflow-wrap: anywhere;
          }

          .detail-description {
               color: #536078;
               font-weight: 600;
               white-space: pre-line;
          }

          .status-badge {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               padding: 8px 12px;
               font-size: .78rem;
               font-weight: 800;
               border: 1px solid transparent;
               border-radius: 999px;
          }

          .status-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .status-inactive {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .detail-card-footer {
               display: flex;
               gap: 12px;
               align-items: center;
               justify-content: space-between;
               padding: 21px 25px;
               border-top: 1px solid #eef2f7;
               background: #fbfdff;
          }

          .footer-note {
               display: flex;
               gap: 8px;
               align-items: center;
               color: var(--dept-muted);
               font-size: .78rem;
          }

          .footer-actions {
               display: flex;
               gap: 10px;
               align-items: center;
          }

          .btn-action {
               display: inline-flex;
               min-height: 45px;
               padding: 0 17px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border: 0;
               border-radius: 13px;
               transition: .2s ease;
          }

          .btn-edit-detail {
               color: #92400e;
               border: 1px solid #fde68a;
               background: #fef3c7;
          }

          .btn-edit-detail:hover {
               color: #ffffff;
               background: #f59e0b;
               transform: translateY(-2px);
               box-shadow: 0 10px 20px rgba(245, 158, 11, .25);
          }

          .btn-delete-detail {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .btn-delete-detail:hover {
               color: #ffffff;
               background: #f43f5e;
               transform: translateY(-2px);
               box-shadow: 0 10px 20px rgba(244, 63, 94, .24);
          }

          .sidebar-stack {
               display: grid;
               gap: 20px;
          }

          .side-card {
               padding: 22px;
          }

          .side-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin-bottom: 18px;
          }

          .side-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               color: var(--dept-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .side-title h5 {
               margin: 0;
               color: var(--dept-text);
               font-size: .98rem;
               font-weight: 850;
          }

          .timeline-list {
               display: grid;
               gap: 13px;
          }

          .timeline-item {
               display: flex;
               gap: 12px;
               align-items: flex-start;
               padding: 13px;
               border: 1px solid #edf0f7;
               border-radius: 14px;
               background: #fbfdff;
          }

          .timeline-icon {
               display: inline-flex;
               flex: 0 0 34px;
               width: 34px;
               height: 34px;
               color: #ffffff;
               font-size: .82rem;
               align-items: center;
               justify-content: center;
               border-radius: 10px;
               background: linear-gradient(135deg, var(--dept-purple), var(--dept-secondary));
          }

          .timeline-item strong {
               display: block;
               margin-bottom: 3px;
               color: var(--dept-text);
               font-size: .8rem;
          }

          .timeline-item span {
               color: var(--dept-muted);
               font-size: .77rem;
               line-height: 1.5;
          }

          .database-box {
               padding: 17px;
               color: #075985;
               border: 1px solid #bae6fd;
               border-radius: 16px;
               background: linear-gradient(135deg, #f0f9ff, #ecfeff);
          }

          .database-box strong {
               display: block;
               margin-bottom: 9px;
               font-size: .84rem;
          }

          .database-columns {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .database-columns span {
               padding: 6px 9px;
               color: #0369a1;
               font-size: .7rem;
               font-weight: 800;
               border: 1px solid #bae6fd;
               border-radius: 9px;
               background: rgba(255, 255, 255, .78);
          }

          @media (max-width: 991.98px) {
               .department-page {
                    padding: 20px 12px 34px;
               }

               .department-hero {
                    padding: 27px;
               }

               .hero-content {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hero-actions {
                    width: 100%;
               }

               .btn-hero {
                    flex: 1;
               }
          }

          @media (max-width: 767.98px) {
               .department-hero {
                    padding: 23px;
                    border-radius: 22px;
               }

               .hero-title-wrap {
                    align-items: flex-start;
               }

               .hero-icon {
                    flex-basis: 53px;
                    width: 53px;
                    height: 53px;
                    font-size: 1.35rem;
                    border-radius: 16px;
               }

               .hero-actions,
               .detail-card-footer,
               .footer-actions {
                    flex-direction: column;
                    align-items: stretch;
                    width: 100%;
               }

               .btn-hero,
               .btn-action {
                    width: 100%;
               }

               .detail-card,
               .side-card {
                    border-radius: 20px;
               }

               .detail-card-header,
               .detail-card-body,
               .detail-card-footer {
                    padding: 20px;
               }

               .identity-panel {
                    align-items: flex-start;
               }

               .detail-grid {
                    grid-template-columns: 1fr;
               }

               .detail-item-full {
                    grid-column: auto;
               }

               .footer-note {
                    justify-content: center;
                    text-align: center;
               }
          }

          @media (max-width: 470px) {

               .hero-title-wrap,
               .identity-panel {
                    flex-direction: column;
               }

               .identity-avatar {
                    width: 66px;
                    height: 66px;
                    flex-basis: 66px;
               }
          }
     </style>

     <div class="department-page">
          <div class="department-container">
               <div class="department-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-building-check"></i>
                              </div>

                              <div>
                                   <h1>Detail Department</h1>
                                   <p>
                                        Lihat informasi lengkap department, status penggunaan, serta waktu pencatatan
                                        datanya.
                                   </p>
                              </div>
                         </div>

                         <div class="hero-actions">
                              <a href="{{ route('super-admin.departments.index') }}" class="btn-hero btn-hero-soft">
                                   <i class="bi bi-arrow-left"></i>
                                   Kembali
                              </a>

                              <a href="{{ route('super-admin.departments.edit', $department) }}" class="btn-hero">
                                   <i class="bi bi-pencil-square"></i>
                                   Edit Department
                              </a>
                         </div>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <div class="row g-4">
                    <div class="col-xl-8">
                         <div class="detail-card">
                              <div class="detail-card-header">
                                   <div class="detail-card-icon">
                                        <i class="bi bi-card-heading"></i>
                                   </div>

                                   <div>
                                        <h4>Informasi Department</h4>
                                        <p>Data berikut diambil langsung dari tabel departments.</p>
                                   </div>
                              </div>

                              <div class="detail-card-body">
                                   <div class="identity-panel">
                                        <div class="identity-avatar">
                                             {{ strtoupper(substr($department->name, 0, 1)) }}
                                        </div>

                                        <div class="identity-content">
                                             <span class="identity-code">
                                                  <i class="bi bi-hash"></i>
                                                  {{ $department->code }}
                                             </span>

                                             <h2 class="identity-name">{{ $department->name }}</h2>
                                             <p class="identity-subtitle">Department ID #{{ $department->id }}</p>
                                        </div>
                                   </div>

                                   <div class="detail-grid">
                                        <div class="detail-item">
                                             <div class="detail-label">
                                                  <i class="bi bi-code-square"></i>
                                                  Kode Department
                                             </div>
                                             <p class="detail-value">{{ $department->code }}</p>
                                        </div>

                                        <div class="detail-item">
                                             <div class="detail-label">
                                                  <i class="bi bi-building"></i>
                                                  Nama Department
                                             </div>
                                             <p class="detail-value">{{ $department->name }}</p>
                                        </div>

                                        <div class="detail-item">
                                             <div class="detail-label">
                                                  <i class="bi bi-toggle-on"></i>
                                                  Status
                                             </div>

                                             @if ($department->status === 'active')
                                                  <span class="status-badge status-active">
                                                       <i class="bi bi-check-circle-fill"></i>
                                                       Aktif
                                                  </span>
                                             @else
                                                  <span class="status-badge status-inactive">
                                                       <i class="bi bi-x-circle-fill"></i>
                                                       Tidak Aktif
                                                  </span>
                                             @endif
                                        </div>

                                        <div class="detail-item">
                                             <div class="detail-label">
                                                  <i class="bi bi-key"></i>
                                                  ID Database
                                             </div>
                                             <p class="detail-value">{{ $department->id }}</p>
                                        </div>

                                        <div class="detail-item detail-item-full">
                                             <div class="detail-label">
                                                  <i class="bi bi-card-text"></i>
                                                  Deskripsi
                                             </div>

                                             <p class="detail-value detail-description">
                                                  {{ $department->description ?: 'Belum ada deskripsi untuk department ini.' }}
                                             </p>
                                        </div>
                                   </div>
                              </div>

                              <div class="detail-card-footer">
                                   <div class="footer-note">
                                        <i class="bi bi-shield-check"></i>
                                        <span>Perubahan data akan tercatat pada waktu pembaruan.</span>
                                   </div>

                                   <div class="footer-actions">
                                        <a href="{{ route('super-admin.departments.edit', $department) }}"
                                             class="btn-action btn-edit-detail">
                                             <i class="bi bi-pencil-fill"></i>
                                             Edit Data
                                        </a>

                                        <form action="{{ route('super-admin.departments.destroy', $department) }}"
                                             method="POST"
                                             onsubmit="return confirm('Yakin ingin menghapus department {{ addslashes($department->name) }}? Data akan dipindahkan ke trash.');">
                                             @csrf
                                             @method('DELETE')

                                             <button type="submit" class="btn-action btn-delete-detail">
                                                  <i class="bi bi-trash3-fill"></i>
                                                  Hapus Department
                                             </button>
                                        </form>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-xl-4">
                         <div class="sidebar-stack">
                              <div class="side-card">
                                   <div class="side-title">
                                        <span class="side-title-icon">
                                             <i class="bi bi-clock-history"></i>
                                        </span>
                                        <h5>Riwayat Data</h5>
                                   </div>

                                   <div class="timeline-list">
                                        <div class="timeline-item">
                                             <span class="timeline-icon">
                                                  <i class="bi bi-calendar-plus"></i>
                                             </span>
                                             <div>
                                                  <strong>Dibuat</strong>
                                                  <span>
                                                       {{ $department->created_at?->format('d M Y, H:i') ?? 'Tidak tersedia' }}
                                                  </span>
                                             </div>
                                        </div>

                                        <div class="timeline-item">
                                             <span class="timeline-icon">
                                                  <i class="bi bi-arrow-repeat"></i>
                                             </span>
                                             <div>
                                                  <strong>Terakhir diperbarui</strong>
                                                  <span>
                                                       {{ $department->updated_at?->format('d M Y, H:i') ?? 'Tidak tersedia' }}
                                                  </span>
                                             </div>
                                        </div>

                                        <div class="timeline-item">
                                             <span class="timeline-icon">
                                                  <i class="bi bi-hourglass-split"></i>
                                             </span>
                                             <div>
                                                  <strong>Pembaruan relatif</strong>
                                                  <span>
                                                       {{ $department->updated_at?->diffForHumans() ?? 'Tidak tersedia' }}
                                                  </span>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="side-card">
                                   <div class="side-title">
                                        <span class="side-title-icon">
                                             <i class="bi bi-database-fill-check"></i>
                                        </span>
                                        <h5>Struktur Database</h5>
                                   </div>

                                   <div class="database-box">
                                        <strong>
                                             <i class="bi bi-table me-1"></i>
                                             Tabel departments
                                        </strong>

                                        <div class="database-columns">
                                             <span>id</span>
                                             <span>code</span>
                                             <span>name</span>
                                             <span>description</span>
                                             <span>status</span>
                                             <span>created_at</span>
                                             <span>updated_at</span>
                                             <span>deleted_at</span>
                                        </div>
                                   </div>
                              </div>

                              <div class="side-card">
                                   <div class="side-title">
                                        <span class="side-title-icon">
                                             <i class="bi bi-lightbulb-fill"></i>
                                        </span>
                                        <h5>Informasi</h5>
                                   </div>

                                   <div class="database-box">
                                        Department yang dihapus tidak langsung hilang permanen. Data akan masuk ke halaman
                                        trash dan dapat dikembalikan selama belum dihapus permanen.
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
@endsection
