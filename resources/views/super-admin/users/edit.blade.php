@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
     <style>
          :root {
               --primary: #4f46e5;
               --primary-soft: #eef2ff;
               --secondary: #0ea5e9;
               --success: #10b981;
               --warning: #f59e0b;
               --danger: #ef4444;
               --purple: #8b5cf6;
               --pink: #ec4899;
               --text-main: #1e293b;
               --text-muted: #64748b;
               --border: #dbe4f0;
               --surface: #ffffff;
          }

          body {
               background:
                    radial-gradient(circle at 5% 8%, rgba(99, 102, 241, .13), transparent 24%),
                    radial-gradient(circle at 94% 12%, rgba(14, 165, 233, .13), transparent 22%),
                    radial-gradient(circle at 88% 88%, rgba(236, 72, 153, .09), transparent 24%),
                    linear-gradient(145deg, #f8fbff 0%, #f5f3ff 50%, #f0fdfa 100%);
               color: var(--text-main);
               min-height: 100vh;
          }

          .user-edit-page {
               position: relative;
               isolation: isolate;
          }

          .user-edit-page::before,
          .user-edit-page::after {
               content: "";
               position: fixed;
               border-radius: 999px;
               filter: blur(2px);
               pointer-events: none;
               z-index: -1;
          }

          .user-edit-page::before {
               width: 240px;
               height: 240px;
               top: 90px;
               left: -100px;
               background: rgba(14, 165, 233, .10);
          }

          .user-edit-page::after {
               width: 300px;
               height: 300px;
               right: -120px;
               bottom: 10px;
               background: rgba(139, 92, 246, .10);
          }

          /* =========================
                          HERO HEADER
                       ========================= */
          .edit-header {
               position: relative;
               overflow: hidden;
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 24px;
               padding: 38px 42px;
               border: 1px solid rgba(255, 255, 255, .60);
               border-radius: 28px;
               background:
                    linear-gradient(115deg,
                         #6366f1 0%,
                         #8b5cf6 34%,
                         #0ea5e9 68%,
                         #14b8a6 100%);
               color: #ffffff;
               box-shadow: 0 24px 55px rgba(79, 70, 229, .20);
          }

          .edit-header::before,
          .edit-header::after {
               content: "";
               position: absolute;
               border-radius: 50%;
               background: rgba(255, 255, 255, .15);
          }

          .edit-header::before {
               width: 210px;
               height: 210px;
               right: 12%;
               top: -135px;
          }

          .edit-header::after {
               width: 150px;
               height: 150px;
               right: -35px;
               bottom: -80px;
          }

          .header-content,
          .btn-back {
               position: relative;
               z-index: 2;
          }

          .header-kicker {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 7px 13px;
               margin-bottom: 13px;
               border: 1px solid rgba(255, 255, 255, .35);
               border-radius: 999px;
               background: rgba(255, 255, 255, .17);
               backdrop-filter: blur(10px);
               font-size: 12px;
               font-weight: 800;
               letter-spacing: .08em;
               text-transform: uppercase;
          }

          .edit-header h2 {
               margin: 0 0 8px;
               font-size: clamp(28px, 4vw, 39px);
               font-weight: 900;
               letter-spacing: -.035em;
          }

          .edit-header p {
               max-width: 620px;
               margin: 0;
               color: rgba(255, 255, 255, .88);
               font-size: 15px;
          }

          .btn-back {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 132px;
               padding: 13px 20px;
               border: 1px solid rgba(255, 255, 255, .65);
               border-radius: 15px;
               background: rgba(255, 255, 255, .93);
               color: var(--primary);
               font-weight: 800;
               text-decoration: none;
               box-shadow: 0 12px 30px rgba(30, 41, 59, .14);
               transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
          }

          .btn-back:hover {
               transform: translateY(-2px);
               background: #ffffff;
               color: #4338ca;
               box-shadow: 0 16px 32px rgba(30, 41, 59, .18);
          }

          /* =========================
                          ALERT
                       ========================= */
          .validation-alert {
               display: flex;
               gap: 14px;
               padding: 18px 20px;
               border: 1px solid #fecaca;
               border-radius: 18px;
               background: linear-gradient(135deg, #fff1f2, #ffffff);
               color: #991b1b;
               box-shadow: 0 12px 28px rgba(239, 68, 68, .08);
          }

          .validation-alert .alert-icon {
               display: grid;
               flex: 0 0 42px;
               height: 42px;
               place-items: center;
               border-radius: 13px;
               background: #fee2e2;
               color: #dc2626;
               font-size: 20px;
          }

          .validation-alert ul {
               padding-left: 18px;
               margin: 5px 0 0;
          }

          /* =========================
                          MAIN CARD
                       ========================= */
          .edit-card {
               position: relative;
               overflow: hidden;
               padding: 34px;
               border: 1px solid rgba(219, 228, 240, .9);
               border-radius: 28px;
               background: rgba(255, 255, 255, .92);
               box-shadow: 0 24px 60px rgba(71, 85, 105, .10);
               backdrop-filter: blur(10px);
          }

          .edit-card::before {
               content: "";
               position: absolute;
               width: 230px;
               height: 230px;
               top: -160px;
               right: -70px;
               border-radius: 50%;
               background: linear-gradient(135deg, rgba(99, 102, 241, .14), rgba(14, 165, 233, .08));
               pointer-events: none;
          }

          /* =========================
                          PROFILE PANEL
                       ========================= */
          .profile-card {
               position: relative;
               overflow: hidden;
               height: 100%;
               min-height: 500px;
               padding: 34px 28px;
               border: 1px solid #dbeafe;
               border-radius: 25px;
               background:
                    radial-gradient(circle at top right, rgba(14, 165, 233, .18), transparent 30%),
                    linear-gradient(160deg, #eef2ff 0%, #eff6ff 42%, #ecfeff 100%);
               text-align: center;
          }

          .profile-card::after {
               content: "";
               position: absolute;
               width: 120px;
               height: 120px;
               left: -48px;
               bottom: -48px;
               border-radius: 50%;
               background: rgba(139, 92, 246, .11);
          }

          .profile-top-label {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 7px 13px;
               margin-bottom: 25px;
               border: 1px solid #c7d2fe;
               border-radius: 999px;
               background: rgba(255, 255, 255, .75);
               color: #4f46e5;
               font-size: 12px;
               font-weight: 800;
          }

          .avatar-ring {
               position: relative;
               display: inline-flex;
               padding: 7px;
               border-radius: 50%;
               background: linear-gradient(135deg, #6366f1, #0ea5e9, #14b8a6, #f59e0b);
               box-shadow: 0 18px 40px rgba(79, 70, 229, .22);
          }

          .avatar-large {
               display: flex;
               width: 138px;
               height: 138px;
               align-items: center;
               justify-content: center;
               border: 7px solid #ffffff;
               border-radius: 50%;
               background: linear-gradient(135deg, #4f46e5, #7c3aed 48%, #0ea5e9);
               color: #ffffff;
               font-size: 54px;
               font-weight: 900;
               text-transform: uppercase;
          }

          .online-dot {
               position: absolute;
               right: 13px;
               bottom: 13px;
               width: 24px;
               height: 24px;
               border: 5px solid #ffffff;
               border-radius: 50%;
               background: #22c55e;
          }

          .profile-name {
               margin: 22px 0 5px;
               color: #1e293b;
               font-size: 23px;
               font-weight: 900;
               word-break: break-word;
          }

          .profile-email {
               margin: 0;
               color: #64748b;
               font-size: 14px;
               word-break: break-word;
          }

          .account-badge {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-top: 18px;
               padding: 9px 16px;
               border-radius: 999px;
               background: #ffffff;
               color: #4f46e5;
               font-size: 13px;
               font-weight: 800;
               box-shadow: 0 8px 20px rgba(99, 102, 241, .10);
          }

          .profile-meta {
               position: relative;
               z-index: 2;
               display: grid;
               grid-template-columns: 1fr;
               gap: 10px;
               margin-top: 28px;
               text-align: left;
          }

          .profile-meta-item {
               display: flex;
               align-items: center;
               gap: 12px;
               padding: 13px 14px;
               border: 1px solid rgba(199, 210, 254, .78);
               border-radius: 15px;
               background: rgba(255, 255, 255, .68);
          }

          .profile-meta-icon {
               display: grid;
               flex: 0 0 38px;
               height: 38px;
               place-items: center;
               border-radius: 12px;
               background: linear-gradient(135deg, #ede9fe, #dbeafe);
               color: #4f46e5;
               font-size: 17px;
          }

          .profile-meta-label {
               display: block;
               color: #94a3b8;
               font-size: 11px;
               font-weight: 700;
               text-transform: uppercase;
               letter-spacing: .04em;
          }

          .profile-meta-value {
               display: block;
               margin-top: 2px;
               color: #334155;
               font-size: 13px;
               font-weight: 800;
          }

          /* =========================
                          FORM AREA
                       ========================= */
          .form-panel {
               position: relative;
               z-index: 2;
          }

          .section-header {
               display: flex;
               align-items: center;
               gap: 15px;
               margin-bottom: 27px;
          }

          .section-icon {
               display: grid;
               flex: 0 0 52px;
               height: 52px;
               place-items: center;
               border-radius: 16px;
               background: linear-gradient(135deg, #ede9fe, #dbeafe);
               color: #4f46e5;
               font-size: 23px;
          }

          .section-header h4 {
               margin: 0 0 4px;
               color: #1e293b;
               font-size: 21px;
               font-weight: 900;
          }

          .section-header p {
               margin: 0;
               color: var(--text-muted);
               font-size: 14px;
          }

          .field-card {
               height: 100%;
               padding: 17px;
               border: 1px solid #e7edf5;
               border-radius: 19px;
               background: linear-gradient(145deg, #ffffff, #fbfdff);
               transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
          }

          .field-card:focus-within {
               transform: translateY(-2px);
               border-color: #a5b4fc;
               box-shadow: 0 12px 26px rgba(99, 102, 241, .09);
          }

          .form-label {
               display: flex;
               align-items: center;
               justify-content: space-between;
               margin-bottom: 9px;
               color: #334155;
               font-size: 13px;
               font-weight: 800;
          }

          .label-required {
               color: #ef4444;
          }

          .input-wrapper {
               position: relative;
          }

          .input-wrapper>i.input-icon {
               position: absolute;
               top: 50%;
               left: 16px;
               z-index: 2;
               transform: translateY(-50%);
               color: #818cf8;
               font-size: 17px;
               pointer-events: none;
          }

          .form-control,
          .form-select {
               min-height: 54px;
               border: 1px solid var(--border);
               border-radius: 14px;
               background-color: #ffffff;
               color: #1e293b;
               font-size: 14px;
               font-weight: 600;
               transition: border-color .2s ease, box-shadow .2s ease;
          }

          .form-control {
               padding: 12px 46px;
          }

          .form-select {
               padding: 12px 44px 12px 16px;
          }

          .form-control::placeholder {
               color: #a0aec0;
               font-weight: 500;
          }

          .form-control:focus,
          .form-select:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .24rem rgba(99, 102, 241, .13);
          }

          .form-control.is-invalid,
          .form-select.is-invalid {
               border-color: #ef4444;
               background-image: none;
          }

          .invalid-feedback {
               display: block;
               margin-top: 7px;
               color: #dc2626;
               font-size: 12px;
               font-weight: 600;
          }

          .helper-text {
               display: flex;
               align-items: flex-start;
               gap: 7px;
               margin-top: 9px;
               color: #7c8ba1;
               font-size: 12px;
               line-height: 1.5;
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 10px;
               display: grid;
               width: 36px;
               height: 36px;
               place-items: center;
               transform: translateY(-50%);
               border: 0;
               border-radius: 10px;
               background: #f1f5f9;
               color: #64748b;
               transition: background .2s ease, color .2s ease;
          }

          .password-toggle:hover {
               background: #e0e7ff;
               color: #4f46e5;
          }

          .status-options {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 10px;
          }

          .status-option {
               position: relative;
          }

          .status-option input {
               position: absolute;
               opacity: 0;
               pointer-events: none;
          }

          .status-option label {
               display: flex;
               min-height: 64px;
               align-items: center;
               gap: 10px;
               padding: 12px 13px;
               border: 1px solid #e2e8f0;
               border-radius: 14px;
               background: #ffffff;
               cursor: pointer;
               transition: all .2s ease;
          }

          .status-option label:hover {
               transform: translateY(-1px);
               border-color: #c7d2fe;
               box-shadow: 0 8px 18px rgba(99, 102, 241, .08);
          }

          .status-dot {
               flex: 0 0 11px;
               width: 11px;
               height: 11px;
               border-radius: 50%;
          }

          .status-option strong {
               display: block;
               color: #334155;
               font-size: 13px;
          }

          .status-option small {
               display: block;
               margin-top: 2px;
               color: #94a3b8;
               font-size: 10px;
          }

          .status-active .status-dot {
               background: #10b981;
               box-shadow: 0 0 0 5px rgba(16, 185, 129, .12);
          }

          .status-inactive .status-dot {
               background: #f59e0b;
               box-shadow: 0 0 0 5px rgba(245, 158, 11, .12);
          }

          .status-suspended .status-dot {
               background: #ef4444;
               box-shadow: 0 0 0 5px rgba(239, 68, 68, .12);
          }

          .status-option input:checked+label {
               border-width: 2px;
          }

          .status-active input:checked+label {
               border-color: #10b981;
               background: #ecfdf5;
          }

          .status-inactive input:checked+label {
               border-color: #f59e0b;
               background: #fffbeb;
          }

          .status-suspended input:checked+label {
               border-color: #ef4444;
               background: #fff1f2;
          }

          /* =========================
                          SECURITY INFO
                       ========================= */
          .security-box {
               display: flex;
               align-items: center;
               gap: 17px;
               margin-top: 27px;
               padding: 20px;
               border: 1px solid #bfdbfe;
               border-radius: 19px;
               background:
                    linear-gradient(120deg, #eff6ff 0%, #eef2ff 55%, #f0fdfa 100%);
          }

          .security-icon {
               display: grid;
               flex: 0 0 51px;
               height: 51px;
               place-items: center;
               border-radius: 16px;
               background: linear-gradient(135deg, #4f46e5, #0ea5e9);
               color: #ffffff;
               font-size: 23px;
               box-shadow: 0 10px 22px rgba(79, 70, 229, .20);
          }

          .security-box h6 {
               margin: 0 0 4px;
               color: #1e293b;
               font-weight: 900;
          }

          .security-box p {
               margin: 0;
               color: #64748b;
               font-size: 13px;
               line-height: 1.55;
          }

          /* =========================
                          ACTION BUTTONS
                       ========================= */
          .form-actions {
               display: flex;
               justify-content: flex-end;
               gap: 12px;
               margin-top: 32px;
               padding-top: 25px;
               border-top: 1px dashed #d8e1ec;
          }

          .btn-cancel,
          .btn-save {
               display: inline-flex;
               min-height: 52px;
               align-items: center;
               justify-content: center;
               padding: 13px 24px;
               border-radius: 15px;
               font-weight: 800;
               transition: all .2s ease;
          }

          .btn-cancel {
               border: 1px solid #dbe4f0;
               background: #f8fafc;
               color: #475569;
          }

          .btn-cancel:hover {
               transform: translateY(-2px);
               border-color: #cbd5e1;
               background: #ffffff;
               color: #1e293b;
          }

          .btn-save {
               border: none;
               background: linear-gradient(120deg, #4f46e5, #7c3aed 48%, #0ea5e9);
               background-size: 180% 180%;
               color: #ffffff;
               box-shadow: 0 13px 28px rgba(79, 70, 229, .24);
          }

          .btn-save:hover {
               transform: translateY(-2px);
               background-position: 100% 50%;
               color: #ffffff;
               box-shadow: 0 17px 32px rgba(79, 70, 229, .30);
          }

          .btn-save:focus {
               box-shadow: 0 0 0 .25rem rgba(99, 102, 241, .18);
          }

          /* =========================
                          RESPONSIVE
                       ========================= */
          @media (max-width: 1199.98px) {
               .profile-card {
                    min-height: auto;
               }

               .profile-meta {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 767.98px) {
               .edit-header {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 29px 24px;
                    border-radius: 23px;
               }

               .btn-back {
                    width: 100%;
               }

               .edit-card {
                    padding: 18px;
                    border-radius: 23px;
               }

               .profile-card {
                    padding: 28px 20px;
               }

               .avatar-large {
                    width: 118px;
                    height: 118px;
                    font-size: 45px;
               }

               .status-options {
                    grid-template-columns: 1fr;
               }

               .form-actions {
                    flex-direction: column-reverse;
               }

               .btn-cancel,
               .btn-save {
                    width: 100%;
               }
          }

          @media (max-width: 575.98px) {
               .profile-meta {
                    grid-template-columns: 1fr;
               }

               .security-box {
                    align-items: flex-start;
               }
          }
     </style>

     <div class="container-fluid py-4 user-edit-page">
          {{-- Header --}}
          <div class="edit-header mb-4">
               <div class="header-content">
                    <div class="header-kicker">
                         <i class="bi bi-people-fill"></i>
                         Manajemen Pengguna
                    </div>

                    <h2>Edit Pengguna</h2>
                    <p>Perbarui identitas, status akun, dan pengaturan keamanan pengguna dalam satu halaman.</p>
               </div>

               <a href="{{ route('super-admin.users.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali
               </a>
          </div>

          {{-- Validation Error --}}
          @if ($errors->any())
               <div class="validation-alert mb-4" role="alert">
                    <div class="alert-icon">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div>
                         <strong>Data belum dapat disimpan.</strong>
                         <ul>
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               </div>
          @endif

          <div class="edit-card">
               <form method="POST" action="{{ route('super-admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4 g-xl-5">
                         {{-- Profile --}}
                         <div class="col-xl-4">
                              <aside class="profile-card">
                                   <div class="profile-top-label">
                                        <i class="bi bi-person-badge"></i>
                                        Profil Pengguna
                                   </div>

                                   <div class="avatar-ring">
                                        <div class="avatar-large">
                                             {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="online-dot" title="Akun terdaftar"></span>
                                   </div>

                                   <h3 class="profile-name">{{ $user->name }}</h3>
                                   <p class="profile-email">{{ $user->email }}</p>

                                   <span class="account-badge">
                                        <i class="bi bi-patch-check-fill"></i>
                                        User Account
                                   </span>

                                   <div class="profile-meta">
                                        <div class="profile-meta-item">
                                             <span class="profile-meta-icon">
                                                  <i class="bi bi-activity"></i>
                                             </span>
                                             <span>
                                                  <span class="profile-meta-label">Status Saat Ini</span>
                                                  <span class="profile-meta-value text-capitalize">
                                                       {{ $user->status ?? 'active' }}
                                                  </span>
                                             </span>
                                        </div>

                                        <div class="profile-meta-item">
                                             <span class="profile-meta-icon">
                                                  <i class="bi bi-shield-lock-fill"></i>
                                             </span>
                                             <span>
                                                  <span class="profile-meta-label">Keamanan</span>
                                                  <span class="profile-meta-value">Password terenkripsi</span>
                                             </span>
                                        </div>
                                   </div>
                              </aside>
                         </div>

                         {{-- Form --}}
                         <div class="col-xl-8">
                              <div class="form-panel">
                                   <div class="section-header">
                                        <div class="section-icon">
                                             <i class="bi bi-person-lines-fill"></i>
                                        </div>
                                        <div>
                                             <h4>Informasi Akun</h4>
                                             <p>Lengkapi data pengguna pada kolom berikut.</p>
                                        </div>
                                   </div>

                                   <div class="row g-3 g-lg-4">
                                        {{-- Nama Lengkap --}}
                                        <div class="col-md-6">
                                             <div class="field-card">
                                                  <label for="name" class="form-label">
                                                       <span>Nama Lengkap <span class="label-required">*</span></span>
                                                       <i class="bi bi-person-check text-primary"></i>
                                                  </label>

                                                  <div class="input-wrapper">
                                                       <i class="bi bi-person input-icon"></i>
                                                       <input type="text" id="name" name="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            value="{{ old('name', $user->name) }}"
                                                            placeholder="Masukkan nama lengkap" autocomplete="name" required>
                                                  </div>

                                                  @error('name')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-6">
                                             <div class="field-card">
                                                  <label for="email" class="form-label">
                                                       <span>Email <span class="label-required">*</span></span>
                                                       <i class="bi bi-envelope-check text-primary"></i>
                                                  </label>

                                                  <div class="input-wrapper">
                                                       <i class="bi bi-envelope input-icon"></i>
                                                       <input type="email" id="email" name="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            value="{{ old('email', $user->email) }}"
                                                            placeholder="nama@perusahaan.com" autocomplete="email" required>
                                                  </div>

                                                  @error('email')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>

                                        {{-- Status Akun --}}
                                        <div class="col-12">
                                             <div class="field-card">
                                                  <label class="form-label mb-3">
                                                       <span>Status Akun <span class="label-required">*</span></span>
                                                       <i class="bi bi-toggles text-primary"></i>
                                                  </label>

                                                  @php
                                                       $selectedStatus = old('status', $user->status ?? 'active');
                                                  @endphp

                                                  <div class="status-options">
                                                       <div class="status-option status-active">
                                                            <input type="radio" name="status" id="status-active"
                                                                 value="active"
                                                                 {{ $selectedStatus === 'active' ? 'checked' : '' }}>
                                                            <label for="status-active">
                                                                 <span class="status-dot"></span>
                                                                 <span>
                                                                      <strong>Aktif</strong>
                                                                      <small>Dapat mengakses sistem</small>
                                                                 </span>
                                                            </label>
                                                       </div>

                                                       <div class="status-option status-inactive">
                                                            <input type="radio" name="status" id="status-inactive"
                                                                 value="inactive"
                                                                 {{ $selectedStatus === 'inactive' ? 'checked' : '' }}>
                                                            <label for="status-inactive">
                                                                 <span class="status-dot"></span>
                                                                 <span>
                                                                      <strong>Nonaktif</strong>
                                                                      <small>Akses dinonaktifkan sementara</small>
                                                                 </span>
                                                            </label>
                                                       </div>

                                                       <div class="status-option status-suspended">
                                                            <input type="radio" name="status" id="status-suspended"
                                                                 value="suspended"
                                                                 {{ $selectedStatus === 'suspended' ? 'checked' : '' }}>
                                                            <label for="status-suspended">
                                                                 <span class="status-dot"></span>
                                                                 <span>
                                                                      <strong>Ditangguhkan</strong>
                                                                      <small>Akses diblokir oleh admin</small>
                                                                 </span>
                                                            </label>
                                                       </div>
                                                  </div>

                                                  @error('status')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>

                                        {{-- Password Baru --}}
                                        <div class="col-12">
                                             <div class="field-card">
                                                  <label for="password" class="form-label">
                                                       <span>Password Baru</span>
                                                       <i class="bi bi-key-fill text-primary"></i>
                                                  </label>

                                                  <div class="input-wrapper">
                                                       <i class="bi bi-lock input-icon"></i>
                                                       <input type="password" id="password" name="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            placeholder="Kosongkan apabila password tidak diubah"
                                                            autocomplete="new-password">

                                                       <button type="button" class="password-toggle" id="togglePassword"
                                                            aria-label="Tampilkan password">
                                                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                                       </button>
                                                  </div>

                                                  @error('password')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror

                                                  <div class="helper-text">
                                                       <i class="bi bi-info-circle-fill mt-1"></i>
                                                       <span>Password lama tetap digunakan apabila kolom ini tidak
                                                            diisi.</span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="security-box">
                                        <div class="security-icon">
                                             <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div>
                                             <h6>Keamanan Akun Terjaga</h6>
                                             <p>Perubahan data akan diproses setelah tombol simpan ditekan. Password baru
                                                  sebaiknya menggunakan kombinasi huruf, angka, dan simbol.</p>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="form-actions">
                         <a href="{{ route('super-admin.users.index') }}" class="btn btn-cancel">
                              <i class="bi bi-x-circle me-2"></i>
                              Batal
                         </a>

                         <button type="submit" class="btn btn-save">
                              <i class="bi bi-check2-circle me-2"></i>
                              Simpan Perubahan
                         </button>
                    </div>
               </form>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const passwordInput = document.getElementById('password');
               const toggleButton = document.getElementById('togglePassword');
               const toggleIcon = document.getElementById('togglePasswordIcon');

               if (!passwordInput || !toggleButton || !toggleIcon) {
                    return;
               }

               toggleButton.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';

                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
                    toggleButton.setAttribute(
                         'aria-label',
                         isPassword ? 'Sembunyikan password' : 'Tampilkan password'
                    );
               });
          });
     </script>
@endsection
