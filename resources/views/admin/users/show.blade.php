@extends('layouts.app')

@section('title', 'Profil Pengguna Eksekutif')

@section('content')
     <style>
          /* =========================================================
                     EXECUTIVE USER PROFILE — LIGHT & FULL COLOR UI
                  ========================================================== */
          .user-profile-page {
               --primary: #4f46e5;
               --primary-soft: #eef2ff;
               --secondary: #06b6d4;
               --success: #10b981;
               --warning: #f59e0b;
               --danger: #ef4444;
               --pink: #ec4899;
               --purple: #8b5cf6;
               --text-main: #1e293b;
               --text-muted: #64748b;
               --border: rgba(148, 163, 184, 0.22);

               position: relative;
               min-height: calc(100vh - 70px);
               padding: 28px 12px 50px;
               overflow: hidden;
               background:
                    radial-gradient(circle at 5% 8%, rgba(129, 140, 248, 0.22), transparent 25%),
                    radial-gradient(circle at 95% 5%, rgba(34, 211, 238, 0.18), transparent 23%),
                    radial-gradient(circle at 90% 95%, rgba(244, 114, 182, 0.16), transparent 24%),
                    linear-gradient(135deg, #f8fbff 0%, #f5f3ff 48%, #ecfeff 100%);
               color: var(--text-main);
          }

          .user-profile-page::before,
          .user-profile-page::after {
               content: '';
               position: absolute;
               border-radius: 50%;
               pointer-events: none;
               filter: blur(2px);
          }

          .user-profile-page::before {
               width: 210px;
               height: 210px;
               top: 130px;
               left: -95px;
               background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(6, 182, 212, 0.08));
          }

          .user-profile-page::after {
               width: 260px;
               height: 260px;
               right: -120px;
               bottom: 80px;
               background: linear-gradient(135deg, rgba(236, 72, 153, 0.13), rgba(245, 158, 11, 0.08));
          }

          .profile-shell {
               position: relative;
               z-index: 1;
               max-width: 1500px;
               margin: 0 auto;
          }

          /* HERO */
          .profile-hero {
               position: relative;
               isolation: isolate;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 24px;
               padding: 38px 42px;
               border: 1px solid rgba(255, 255, 255, 0.72);
               border-radius: 30px;
               color: #ffffff;
               background:
                    linear-gradient(115deg, rgba(79, 70, 229, 0.98), rgba(14, 165, 233, 0.94) 58%, rgba(6, 182, 212, 0.92));
               box-shadow: 0 24px 55px rgba(79, 70, 229, 0.23);
          }

          .profile-hero::before {
               content: '';
               position: absolute;
               z-index: -1;
               width: 310px;
               height: 310px;
               top: -175px;
               right: 8%;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.13);
          }

          .profile-hero::after {
               content: '';
               position: absolute;
               z-index: -1;
               width: 190px;
               height: 190px;
               right: -30px;
               bottom: -110px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.11);
          }

          .hero-label {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 13px;
               padding: 7px 12px;
               border: 1px solid rgba(255, 255, 255, 0.3);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.15);
               backdrop-filter: blur(8px);
               font-size: 12px;
               font-weight: 800;
               letter-spacing: 0.06em;
               text-transform: uppercase;
          }

          .profile-hero h1 {
               margin: 0 0 8px;
               font-size: clamp(28px, 3vw, 42px);
               font-weight: 900;
               letter-spacing: -0.035em;
          }

          .profile-hero p {
               max-width: 650px;
               margin: 0;
               color: rgba(255, 255, 255, 0.88);
               font-size: 15px;
          }

          .btn-back-profile {
               flex: 0 0 auto;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 48px;
               padding: 0 21px;
               border: 1px solid rgba(255, 255, 255, 0.75);
               border-radius: 15px;
               color: var(--primary);
               background: #ffffff;
               box-shadow: 0 12px 30px rgba(30, 64, 175, 0.16);
               font-weight: 800;
               text-decoration: none;
               transition: transform 0.25s ease, box-shadow 0.25s ease;
          }

          .btn-back-profile:hover {
               color: var(--primary);
               transform: translateY(-2px);
               box-shadow: 0 16px 36px rgba(30, 64, 175, 0.22);
          }

          /* MAIN CARD */
          .profile-panel {
               position: relative;
               margin-top: 24px;
               padding: 30px;
               border: 1px solid rgba(255, 255, 255, 0.9);
               border-radius: 30px;
               background: rgba(255, 255, 255, 0.88);
               box-shadow: 0 22px 50px rgba(51, 65, 85, 0.10);
               backdrop-filter: blur(12px);
          }

          /* PROFILE SUMMARY */
          .profile-summary {
               display: grid;
               grid-template-columns: auto minmax(0, 1fr) auto;
               align-items: center;
               gap: 24px;
               margin-bottom: 28px;
               padding: 26px;
               border: 1px solid rgba(99, 102, 241, 0.11);
               border-radius: 24px;
               background:
                    linear-gradient(135deg, rgba(238, 242, 255, 0.92), rgba(236, 254, 255, 0.9));
          }

          .avatar-wrap {
               position: relative;
               padding: 6px;
               border-radius: 50%;
               background: linear-gradient(135deg, #6366f1, #22d3ee, #f472b6);
               box-shadow: 0 18px 35px rgba(99, 102, 241, 0.24);
          }

          .avatar-profile {
               width: 112px;
               height: 112px;
               display: flex;
               align-items: center;
               justify-content: center;
               border: 5px solid #ffffff;
               border-radius: 50%;
               color: #ffffff;
               background: linear-gradient(135deg, #4f46e5, #06b6d4);
               font-size: 42px;
               font-weight: 900;
               text-transform: uppercase;
          }

          .avatar-online {
               position: absolute;
               right: 8px;
               bottom: 10px;
               width: 20px;
               height: 20px;
               border: 4px solid #ffffff;
               border-radius: 50%;
               background: #22c55e;
          }

          .profile-identity {
               min-width: 0;
          }

          .profile-caption {
               display: inline-block;
               margin-bottom: 6px;
               color: #6366f1;
               font-size: 12px;
               font-weight: 900;
               letter-spacing: 0.08em;
               text-transform: uppercase;
          }

          .profile-name {
               margin: 0 0 6px;
               color: #1e293b;
               font-size: clamp(25px, 2.5vw, 34px);
               font-weight: 900;
               letter-spacing: -0.03em;
               overflow-wrap: anywhere;
          }

          .profile-email {
               display: flex;
               align-items: center;
               gap: 8px;
               margin: 0;
               color: var(--text-muted);
               font-size: 14px;
               overflow-wrap: anywhere;
          }

          .profile-state {
               display: flex;
               flex-direction: column;
               align-items: flex-end;
               gap: 9px;
          }

          .state-label {
               color: var(--text-muted);
               font-size: 11px;
               font-weight: 800;
               letter-spacing: 0.07em;
               text-transform: uppercase;
          }

          .status-badge-profile {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 10px 16px;
               border: 1px solid transparent;
               border-radius: 999px;
               font-size: 13px;
               font-weight: 900;
               white-space: nowrap;
          }

          .status-badge-profile i {
               font-size: 8px;
          }

          .status-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #d1fae5;
          }

          .status-inactive {
               color: #b45309;
               border-color: #fde68a;
               background: #fef3c7;
          }

          .status-suspended {
               color: #b91c1c;
               border-color: #fecaca;
               background: #fee2e2;
          }

          /* SECTION HEADING */
          .section-heading {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin: 4px 0 18px;
          }

          .section-title-wrap {
               display: flex;
               align-items: center;
               gap: 12px;
          }

          .section-icon {
               width: 42px;
               height: 42px;
               display: flex;
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               color: #ffffff;
               background: linear-gradient(135deg, #6366f1, #22c55e);
               box-shadow: 0 10px 22px rgba(99, 102, 241, 0.18);
          }

          .section-heading h5 {
               margin: 0;
               color: #1e293b;
               font-size: 18px;
               font-weight: 900;
          }

          .section-heading p {
               margin: 3px 0 0;
               color: var(--text-muted);
               font-size: 13px;
          }

          /* INFORMATION CARDS */
          .info-card-profile {
               --card-accent: #6366f1;
               --card-soft: #eef2ff;

               position: relative;
               height: 100%;
               min-height: 145px;
               overflow: hidden;
               padding: 22px;
               border: 1px solid var(--border);
               border-radius: 21px;
               background: #ffffff;
               box-shadow: 0 10px 24px rgba(15, 23, 42, 0.055);
               transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
          }

          .info-card-profile::after {
               content: '';
               position: absolute;
               width: 92px;
               height: 92px;
               top: -48px;
               right: -37px;
               border-radius: 50%;
               background: var(--card-soft);
          }

          .info-card-profile:hover {
               transform: translateY(-6px);
               border-color: color-mix(in srgb, var(--card-accent) 28%, white);
               box-shadow: 0 18px 36px rgba(15, 23, 42, 0.10);
          }

          .info-card-profile.indigo {
               --card-accent: #6366f1;
               --card-soft: #e0e7ff;
          }

          .info-card-profile.cyan {
               --card-accent: #0891b2;
               --card-soft: #cffafe;
          }

          .info-card-profile.green {
               --card-accent: #10b981;
               --card-soft: #d1fae5;
          }

          .info-card-profile.orange {
               --card-accent: #f59e0b;
               --card-soft: #fef3c7;
          }

          .info-card-profile.pink {
               --card-accent: #ec4899;
               --card-soft: #fce7f3;
          }

          .info-card-profile.purple {
               --card-accent: #8b5cf6;
               --card-soft: #ede9fe;
          }

          .info-card-head {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 16px;
          }

          .info-icon {
               width: 44px;
               height: 44px;
               display: flex;
               align-items: center;
               justify-content: center;
               border-radius: 14px;
               color: var(--card-accent);
               background: var(--card-soft);
               font-size: 19px;
          }

          .info-number {
               color: color-mix(in srgb, var(--card-accent) 70%, white);
               font-size: 13px;
               font-weight: 900;
          }

          .info-title-profile {
               position: relative;
               z-index: 1;
               margin-bottom: 7px;
               color: var(--text-muted);
               font-size: 11px;
               font-weight: 900;
               letter-spacing: 0.075em;
               text-transform: uppercase;
          }

          .info-value-profile {
               position: relative;
               z-index: 1;
               color: #1e293b;
               font-size: 16px;
               font-weight: 850;
               line-height: 1.45;
               overflow-wrap: anywhere;
          }

          .verification-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 7px 11px;
               border-radius: 999px;
               font-size: 12px;
               font-weight: 900;
          }

          .verification-badge.verified {
               color: #047857;
               background: #d1fae5;
          }

          .verification-badge.unverified {
               color: #b45309;
               background: #fef3c7;
          }

          /* ACTIVITY */
          .activity-section {
               margin-top: 32px;
               padding-top: 28px;
               border-top: 1px dashed #cbd5e1;
          }

          .activity-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 16px;
          }

          .activity-item {
               display: flex;
               align-items: flex-start;
               gap: 15px;
               padding: 19px;
               border: 1px solid rgba(148, 163, 184, 0.20);
               border-radius: 18px;
               background: linear-gradient(135deg, #ffffff, #f8fafc);
               box-shadow: 0 8px 20px rgba(15, 23, 42, 0.045);
          }

          .activity-symbol {
               flex: 0 0 auto;
               width: 42px;
               height: 42px;
               display: flex;
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               color: #ffffff;
               background: linear-gradient(135deg, #6366f1, #8b5cf6);
               box-shadow: 0 9px 20px rgba(99, 102, 241, 0.18);
          }

          .activity-item:nth-child(2) .activity-symbol {
               background: linear-gradient(135deg, #06b6d4, #10b981);
               box-shadow: 0 9px 20px rgba(6, 182, 212, 0.18);
          }

          .activity-item strong {
               display: block;
               margin-bottom: 4px;
               color: #1e293b;
               font-size: 14px;
               font-weight: 900;
          }

          .activity-item p {
               margin: 0;
               color: var(--text-muted);
               font-size: 13px;
          }

          /* ACTION */
          .profile-actions {
               display: flex;
               align-items: center;
               justify-content: flex-end;
               gap: 12px;
               margin-top: 28px;
          }

          .btn-edit-profile {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 50px;
               padding: 0 24px;
               border: 0;
               border-radius: 15px;
               color: #ffffff;
               background: linear-gradient(135deg, #4f46e5, #0ea5e9, #06b6d4);
               background-size: 180% 180%;
               box-shadow: 0 14px 28px rgba(79, 70, 229, 0.25);
               font-weight: 900;
               text-decoration: none;
               transition: transform 0.25s ease, box-shadow 0.25s ease, background-position 0.4s ease;
          }

          .btn-edit-profile:hover {
               color: #ffffff;
               transform: translateY(-3px);
               background-position: 100% 50%;
               box-shadow: 0 18px 34px rgba(79, 70, 229, 0.31);
          }

          /* RESPONSIVE */
          @media (max-width: 991.98px) {
               .profile-hero {
                    padding: 31px;
               }

               .profile-summary {
                    grid-template-columns: auto minmax(0, 1fr);
               }

               .profile-state {
                    grid-column: 1 / -1;
                    align-items: flex-start;
                    padding-top: 3px;
               }
          }

          @media (max-width: 767.98px) {
               .user-profile-page {
                    padding: 18px 4px 34px;
               }

               .profile-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 27px 23px;
                    border-radius: 23px;
               }

               .btn-back-profile {
                    width: 100%;
               }

               .profile-panel {
                    padding: 18px;
                    border-radius: 23px;
               }

               .profile-summary {
                    grid-template-columns: 1fr;
                    justify-items: center;
                    padding: 23px 18px;
                    text-align: center;
               }

               .profile-email {
                    justify-content: center;
               }

               .profile-state {
                    grid-column: auto;
                    align-items: center;
               }

               .activity-grid {
                    grid-template-columns: 1fr;
               }

               .section-heading {
                    align-items: flex-start;
               }

               .section-heading p {
                    display: none;
               }

               .profile-actions,
               .btn-edit-profile {
                    width: 100%;
               }
          }

          @media (max-width: 430px) {
               .profile-hero h1 {
                    font-size: 27px;
               }

               .avatar-profile {
                    width: 98px;
                    height: 98px;
                    font-size: 37px;
               }

               .info-card-profile {
                    min-height: auto;
               }
          }
     </style>

     <div class="user-profile-page">
          <div class="container-fluid profile-shell">
               {{-- HERO HEADER --}}
               <header class="profile-hero">
                    <div>
                         <span class="hero-label">
                              <i class="bi bi-person-badge-fill"></i>
                              User Management
                         </span>

                         <h1>Profil Pengguna Eksekutif</h1>
                         <p>Informasi akun, status, verifikasi, dan riwayat aktivitas pengguna dalam satu tampilan.</p>
                    </div>

                    <a href="{{ route('admin.users.index') }}" class="btn-back-profile">
                         <i class="bi bi-arrow-left"></i>
                         Kembali ke Daftar
                    </a>
               </header>

               <main class="profile-panel">
                    {{-- PROFILE SUMMARY --}}
                    <section class="profile-summary">
                         <div class="avatar-wrap">
                              <div class="avatar-profile">
                                   {{ strtoupper(substr($user->name, 0, 1)) }}
                              </div>
                              @if ($user->status === 'active')
                                   <span class="avatar-online" title="Pengguna aktif"></span>
                              @endif
                         </div>

                         <div class="profile-identity">
                              <span class="profile-caption">Executive Account</span>
                              <h2 class="profile-name">{{ $user->name }}</h2>
                              <p class="profile-email">
                                   <i class="bi bi-envelope-fill"></i>
                                   {{ $user->email }}
                              </p>
                         </div>

                         <div class="profile-state">
                              <span class="state-label">Status Akun</span>

                              @if ($user->status === 'active')
                                   <span class="status-badge-profile status-active">
                                        <i class="bi bi-circle-fill"></i>
                                        Aktif
                                   </span>
                              @elseif ($user->status === 'inactive')
                                   <span class="status-badge-profile status-inactive">
                                        <i class="bi bi-circle-fill"></i>
                                        Tidak Aktif
                                   </span>
                              @else
                                   <span class="status-badge-profile status-suspended">
                                        <i class="bi bi-circle-fill"></i>
                                        Ditangguhkan
                                   </span>
                              @endif
                         </div>
                    </section>

                    {{-- INFORMATION SECTION --}}
                    <div class="section-heading">
                         <div class="section-title-wrap">
                              <div class="section-icon">
                                   <i class="bi bi-grid-fill"></i>
                              </div>
                              <div>
                                   <h5>Informasi Pengguna</h5>
                                   <p>Data utama dan kondisi akun yang tersimpan di dalam sistem.</p>
                              </div>
                         </div>
                    </div>

                    <section class="row g-4">
                         <div class="col-xl-4 col-md-6">
                              <article class="info-card-profile indigo">
                                   <div class="info-card-head">
                                        <div class="info-icon">
                                             <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="info-number">01</span>
                                   </div>
                                   <div class="info-title-profile">Nama Lengkap</div>
                                   <div class="info-value-profile">{{ $user->name }}</div>
                              </article>
                         </div>

                         <div class="col-xl-4 col-md-6">
                              <article class="info-card-profile cyan">
                                   <div class="info-card-head">
                                        <div class="info-icon">
                                             <i class="bi bi-envelope-at-fill"></i>
                                        </div>
                                        <span class="info-number">02</span>
                                   </div>
                                   <div class="info-title-profile">Alamat Email</div>
                                   <div class="info-value-profile">{{ $user->email }}</div>
                              </article>
                         </div>

                         <div class="col-xl-4 col-md-6">
                              <article class="info-card-profile green">
                                   <div class="info-card-head">
                                        <div class="info-icon">
                                             <i class="bi bi-shield-check"></i>
                                        </div>
                                        <span class="info-number">03</span>
                                   </div>
                                   <div class="info-title-profile">Status Akun</div>
                                   <div class="info-value-profile">
                                        @if ($user->status === 'active')
                                             <span class="status-badge-profile status-active">
                                                  <i class="bi bi-circle-fill"></i> Aktif
                                             </span>
                                        @elseif ($user->status === 'inactive')
                                             <span class="status-badge-profile status-inactive">
                                                  <i class="bi bi-circle-fill"></i> Tidak Aktif
                                             </span>
                                        @else
                                             <span class="status-badge-profile status-suspended">
                                                  <i class="bi bi-circle-fill"></i> Ditangguhkan
                                             </span>
                                        @endif
                                   </div>
                              </article>
                         </div>

                         <div class="col-xl-4 col-md-6">
                              <article class="info-card-profile orange">
                                   <div class="info-card-head">
                                        <div class="info-icon">
                                             <i class="bi bi-clock-history"></i>
                                        </div>
                                        <span class="info-number">04</span>
                                   </div>
                                   <div class="info-title-profile">Login Terakhir</div>
                                   <div class="info-value-profile">
                                        {{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : 'Belum pernah login' }}
                                   </div>
                              </article>
                         </div>

                         <div class="col-xl-4 col-md-6">
                              <article class="info-card-profile pink">
                                   <div class="info-card-head">
                                        <div class="info-icon">
                                             <i class="bi bi-patch-check-fill"></i>
                                        </div>
                                        <span class="info-number">05</span>
                                   </div>
                                   <div class="info-title-profile">Verifikasi Email</div>
                                   <div class="info-value-profile">
                                        @if ($user->email_verified_at)
                                             <span class="verification-badge verified">
                                                  <i class="bi bi-check-circle-fill"></i>
                                                  Terverifikasi
                                             </span>
                                        @else
                                             <span class="verification-badge unverified">
                                                  <i class="bi bi-exclamation-circle-fill"></i>
                                                  Belum Terverifikasi
                                             </span>
                                        @endif
                                   </div>
                              </article>
                         </div>

                         <div class="col-xl-4 col-md-6">
                              <article class="info-card-profile purple">
                                   <div class="info-card-head">
                                        <div class="info-icon">
                                             <i class="bi bi-calendar-check-fill"></i>
                                        </div>
                                        <span class="info-number">06</span>
                                   </div>
                                   <div class="info-title-profile">Tanggal Registrasi</div>
                                   <div class="info-value-profile">
                                        {{ $user->created_at->format('d M Y') }}
                                   </div>
                              </article>
                         </div>
                    </section>

                    {{-- ACTIVITY SECTION --}}
                    <section class="activity-section">
                         <div class="section-heading">
                              <div class="section-title-wrap">
                                   <div class="section-icon">
                                        <i class="bi bi-activity"></i>
                                   </div>
                                   <div>
                                        <h5>Aktivitas Pengguna</h5>
                                        <p>Ringkasan aktivitas penting yang tercatat dalam sistem.</p>
                                   </div>
                              </div>
                         </div>

                         <div class="activity-grid">
                              <article class="activity-item">
                                   <div class="activity-symbol">
                                        <i class="bi bi-person-plus-fill"></i>
                                   </div>
                                   <div>
                                        <strong>Akun Dibuat</strong>
                                        <p>{{ $user->created_at->diffForHumans() }}</p>
                                   </div>
                              </article>

                              <article class="activity-item">
                                   <div class="activity-symbol">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                   </div>
                                   <div>
                                        <strong>Login Terakhir</strong>
                                        <p>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum ada aktivitas login' }}
                                        </p>
                                   </div>
                              </article>
                         </div>
                    </section>

                    {{-- ACTION --}}
                    <div class="profile-actions">
                         <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit-profile">
                              <i class="bi bi-pencil-square"></i>
                              Edit Pengguna
                         </a>
                    </div>
               </main>
          </div>
     </div>
@endsection
