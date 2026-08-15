@extends('layouts.app')

@section('title', 'Tambah Department')

@section('content')
     <style>
          :root {
               --dept-indigo: #6366f1;
               --dept-violet: #8b5cf6;
               --dept-blue: #0ea5e9;
               --dept-cyan: #06b6d4;
               --dept-green: #10b981;
               --dept-yellow: #f59e0b;
               --dept-red: #ef4444;
               --dept-pink: #ec4899;
               --dept-text: #1e293b;
               --dept-muted: #64748b;
               --dept-border: #dbeafe;
               --dept-white: #ffffff;
               --dept-soft-blue: #eff6ff;
               --dept-soft-indigo: #eef2ff;
               --dept-soft-cyan: #ecfeff;
               --dept-soft-green: #ecfdf5;
               --dept-soft-yellow: #fffbeb;
               --dept-soft-red: #fef2f2;
          }

          .department-page {
               position: relative;
               min-height: calc(100vh - 70px);
               padding: 30px;
               overflow: hidden;
               background:
                    radial-gradient(circle at 8% 8%, rgba(99, 102, 241, .20), transparent 24%),
                    radial-gradient(circle at 92% 18%, rgba(6, 182, 212, .17), transparent 25%),
                    radial-gradient(circle at 78% 92%, rgba(236, 72, 153, .12), transparent 24%),
                    linear-gradient(135deg, #f8fbff 0%, #f5f3ff 45%, #ecfeff 100%);
          }

          .department-page::before,
          .department-page::after {
               position: absolute;
               z-index: 0;
               content: "";
               border-radius: 999px;
               pointer-events: none;
          }

          .department-page::before {
               top: 120px;
               right: -110px;
               width: 260px;
               height: 260px;
               background: linear-gradient(135deg, rgba(99, 102, 241, .14), rgba(14, 165, 233, .08));
          }

          .department-page::after {
               bottom: 30px;
               left: -130px;
               width: 280px;
               height: 280px;
               background: linear-gradient(135deg, rgba(16, 185, 129, .10), rgba(236, 72, 153, .10));
          }

          .department-container {
               position: relative;
               z-index: 1;
               max-width: 1480px;
               margin: 0 auto;
          }

          /* Hero */
          .department-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 24px;
               color: var(--dept-text);
               border: 1px solid rgba(255, 255, 255, .9);
               border-radius: 28px;
               background:
                    linear-gradient(115deg, rgba(255, 255, 255, .96), rgba(238, 242, 255, .96) 45%, rgba(236, 254, 255, .96));
               box-shadow: 0 22px 55px rgba(71, 85, 105, .12);
          }

          .department-hero::before {
               position: absolute;
               top: -85px;
               right: -30px;
               width: 250px;
               height: 250px;
               content: "";
               border: 42px solid rgba(99, 102, 241, .08);
               border-radius: 999px;
          }

          .department-hero::after {
               position: absolute;
               right: 170px;
               bottom: -115px;
               width: 210px;
               height: 210px;
               content: "";
               border-radius: 999px;
               background: rgba(6, 182, 212, .08);
          }

          .hero-content,
          .hero-actions {
               position: relative;
               z-index: 2;
          }

          .hero-eyebrow {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               padding: 8px 13px;
               margin-bottom: 14px;
               color: #4338ca;
               font-size: .75rem;
               font-weight: 800;
               letter-spacing: .08em;
               text-transform: uppercase;
               border: 1px solid #c7d2fe;
               border-radius: 999px;
               background: rgba(238, 242, 255, .9);
          }

          .hero-title {
               margin: 0;
               color: #1e293b;
               font-size: clamp(1.75rem, 3vw, 2.55rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .hero-description {
               max-width: 720px;
               margin: 10px 0 0;
               color: var(--dept-muted);
               font-size: .98rem;
               line-height: 1.7;
          }

          .hero-actions {
               display: flex;
               gap: 12px;
               align-items: center;
               justify-content: flex-end;
          }

          .hero-icon-box {
               display: grid;
               width: 70px;
               height: 70px;
               place-items: center;
               color: #ffffff;
               font-size: 1.75rem;
               border-radius: 22px;
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-cyan));
               box-shadow: 0 14px 30px rgba(99, 102, 241, .25);
          }

          .btn-back {
               display: inline-flex;
               gap: 9px;
               align-items: center;
               justify-content: center;
               min-height: 48px;
               padding: 0 20px;
               color: #4338ca;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid #c7d2fe;
               border-radius: 15px;
               background: #ffffff;
               box-shadow: 0 8px 20px rgba(99, 102, 241, .10);
               transition: .2s ease;
          }

          .btn-back:hover {
               color: #ffffff;
               border-color: transparent;
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-violet));
               transform: translateY(-2px);
          }

          /* Validation alert */
          .department-alert {
               display: flex;
               gap: 14px;
               align-items: flex-start;
               padding: 18px 20px;
               margin-bottom: 22px;
               color: #991b1b;
               border: 1px solid #fecaca;
               border-radius: 20px;
               background: rgba(254, 242, 242, .97);
               box-shadow: 0 12px 28px rgba(239, 68, 68, .10);
          }

          .department-alert-icon {
               display: grid;
               flex: 0 0 auto;
               width: 42px;
               height: 42px;
               place-items: center;
               color: #ffffff;
               border-radius: 13px;
               background: linear-gradient(135deg, #f87171, #dc2626);
          }

          .department-alert h6 {
               margin-bottom: 6px;
               font-weight: 850;
          }

          .department-alert ul {
               padding-left: 18px;
               margin: 0;
               font-size: .88rem;
          }

          /* Main card */
          .department-card {
               overflow: hidden;
               border: 1px solid rgba(219, 234, 254, .95);
               border-radius: 26px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 20px 55px rgba(51, 65, 85, .10);
          }

          .department-card-header {
               display: flex;
               gap: 16px;
               align-items: center;
               padding: 24px 26px;
               border-bottom: 1px solid #e0e7ff;
               background: linear-gradient(100deg, #eef2ff 0%, #eff6ff 45%, #ecfeff 100%);
          }

          .card-header-icon {
               display: grid;
               flex: 0 0 auto;
               width: 52px;
               height: 52px;
               place-items: center;
               color: #ffffff;
               font-size: 1.25rem;
               border-radius: 17px;
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-blue), var(--dept-cyan));
               box-shadow: 0 12px 26px rgba(14, 165, 233, .22);
          }

          .department-card-header h4 {
               margin: 0;
               color: var(--dept-text);
               font-size: 1.1rem;
               font-weight: 850;
          }

          .department-card-header p {
               margin: 4px 0 0;
               color: var(--dept-muted);
               font-size: .86rem;
          }

          .department-card-body {
               padding: 30px;
          }

          .form-section {
               padding: 24px;
               margin-bottom: 22px;
               border: 1px solid #e0e7ff;
               border-radius: 20px;
               background: linear-gradient(145deg, #ffffff, #fafbff);
          }

          .form-section:last-child {
               margin-bottom: 0;
          }

          .form-section.identity-section {
               border-top: 4px solid var(--dept-indigo);
          }

          .form-section.status-section {
               border-top: 4px solid var(--dept-green);
          }

          .form-section.description-section {
               border-top: 4px solid var(--dept-pink);
          }

          .section-heading {
               display: flex;
               gap: 12px;
               align-items: center;
               margin-bottom: 20px;
          }

          .section-number {
               display: grid;
               flex: 0 0 auto;
               width: 38px;
               height: 38px;
               place-items: center;
               color: #ffffff;
               font-size: .8rem;
               font-weight: 850;
               border-radius: 12px;
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-violet));
               box-shadow: 0 8px 18px rgba(99, 102, 241, .20);
          }

          .status-section .section-number {
               background: linear-gradient(135deg, var(--dept-green), var(--dept-cyan));
          }

          .description-section .section-number {
               background: linear-gradient(135deg, var(--dept-pink), var(--dept-violet));
          }

          .section-heading h5 {
               margin: 0;
               color: var(--dept-text);
               font-size: .98rem;
               font-weight: 850;
          }

          .section-heading small {
               display: block;
               margin-top: 2px;
               color: var(--dept-muted);
               font-size: .78rem;
          }

          /* Form */
          .department-form .form-label {
               margin-bottom: 8px;
               color: var(--dept-text);
               font-size: .87rem;
               font-weight: 800;
          }

          .required-mark {
               color: var(--dept-red);
          }

          .optional-text {
               margin-left: 5px;
               color: #94a3b8;
               font-size: .73rem;
               font-weight: 650;
          }

          .field-shell {
               position: relative;
          }

          .field-icon {
               position: absolute;
               top: 50%;
               left: 15px;
               z-index: 3;
               color: #64748b;
               font-size: 1rem;
               pointer-events: none;
               transform: translateY(-50%);
               transition: color .2s ease;
          }

          .field-shell-textarea .field-icon {
               top: 17px;
               transform: none;
          }

          .department-form .form-control {
               min-height: 52px;
               padding: 12px 15px 12px 44px;
               color: var(--dept-text);
               font-size: .9rem;
               border: 1px solid #cbd5e1;
               border-radius: 15px;
               background-color: #ffffff;
               box-shadow: 0 2px 5px rgba(15, 23, 42, .03);
               transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
          }

          .department-form textarea.form-control {
               min-height: 145px;
               padding-top: 14px;
               resize: vertical;
          }

          .department-form .form-control::placeholder {
               color: #94a3b8;
          }

          .department-form .form-control:hover {
               border-color: #a5b4fc;
          }

          .department-form .form-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .24rem rgba(99, 102, 241, .13);
          }

          .field-shell:focus-within .field-icon {
               color: var(--dept-indigo);
          }

          .department-form .form-control.is-invalid {
               border-color: #f87171;
               background-image: none;
          }

          .invalid-feedback {
               font-size: .78rem;
               font-weight: 700;
          }

          .form-hint {
               display: flex;
               gap: 6px;
               align-items: flex-start;
               margin-top: 8px;
               color: #64748b;
               font-size: .75rem;
               line-height: 1.5;
          }

          /* Status choice */
          .status-options {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .status-option {
               position: relative;
          }

          .status-option input {
               position: absolute;
               opacity: 0;
               pointer-events: none;
          }

          .status-label {
               display: flex;
               gap: 13px;
               align-items: center;
               min-height: 88px;
               padding: 16px;
               margin: 0;
               cursor: pointer;
               border: 2px solid transparent;
               border-radius: 18px;
               transition: .2s ease;
          }

          .status-label-active {
               color: #065f46;
               background: linear-gradient(135deg, #ecfdf5, #f0fdfa);
               border-color: #a7f3d0;
          }

          .status-label-inactive {
               color: #9f1239;
               background: linear-gradient(135deg, #fff1f2, #fdf2f8);
               border-color: #fecdd3;
          }

          .status-label:hover {
               transform: translateY(-2px);
               box-shadow: 0 10px 22px rgba(71, 85, 105, .10);
          }

          .status-icon {
               display: grid;
               flex: 0 0 auto;
               width: 44px;
               height: 44px;
               place-items: center;
               color: #ffffff;
               font-size: 1.1rem;
               border-radius: 14px;
          }

          .status-label-active .status-icon {
               background: linear-gradient(135deg, var(--dept-green), var(--dept-cyan));
          }

          .status-label-inactive .status-icon {
               background: linear-gradient(135deg, #fb7185, var(--dept-pink));
          }

          .status-label strong {
               display: block;
               margin-bottom: 3px;
               font-size: .88rem;
          }

          .status-label small {
               display: block;
               color: var(--dept-muted);
               font-size: .74rem;
               line-height: 1.45;
          }

          .status-option input:checked+.status-label-active {
               border-color: var(--dept-green);
               box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
          }

          .status-option input:checked+.status-label-inactive {
               border-color: var(--dept-pink);
               box-shadow: 0 0 0 4px rgba(236, 72, 153, .12);
          }

          /* Actions */
          .form-actions {
               display: flex;
               gap: 12px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 28px;
               border-top: 1px solid #e0e7ff;
               background: linear-gradient(100deg, #fbfdff, #f5f3ff, #ecfeff);
          }

          .form-actions-note {
               display: flex;
               gap: 8px;
               align-items: center;
               color: var(--dept-muted);
               font-size: .78rem;
          }

          .form-actions-note i {
               color: var(--dept-green);
          }

          .action-buttons {
               display: flex;
               gap: 12px;
               align-items: center;
          }

          .btn-cancel,
          .btn-save {
               display: inline-flex;
               gap: 9px;
               align-items: center;
               justify-content: center;
               min-height: 49px;
               padding: 0 22px;
               font-size: .88rem;
               font-weight: 850;
               text-decoration: none;
               border-radius: 15px;
               transition: .2s ease;
          }

          .btn-cancel {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #ffffff;
          }

          .btn-cancel:hover {
               color: #4338ca;
               border-color: #a5b4fc;
               background: #eef2ff;
               transform: translateY(-2px);
          }

          .btn-save {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-violet) 45%, var(--dept-cyan));
               box-shadow: 0 13px 28px rgba(99, 102, 241, .25);
          }

          .btn-save:hover {
               color: #ffffff;
               box-shadow: 0 17px 34px rgba(99, 102, 241, .32);
               transform: translateY(-2px);
          }

          /* Sidebar */
          .sidebar-stack {
               display: grid;
               gap: 20px;
          }

          .info-card {
               overflow: hidden;
               border: 1px solid rgba(219, 234, 254, .95);
               border-radius: 23px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 16px 42px rgba(51, 65, 85, .08);
          }

          .info-card-body {
               padding: 22px;
          }

          .info-card-accent {
               height: 6px;
          }

          .accent-purple {
               background: linear-gradient(90deg, var(--dept-indigo), var(--dept-violet), var(--dept-pink));
          }

          .accent-cyan {
               background: linear-gradient(90deg, var(--dept-cyan), var(--dept-blue), var(--dept-indigo));
          }

          .accent-green {
               background: linear-gradient(90deg, var(--dept-green), var(--dept-cyan), var(--dept-yellow));
          }

          .info-card-title {
               display: flex;
               gap: 12px;
               align-items: center;
               margin-bottom: 18px;
          }

          .info-card-title span {
               display: grid;
               width: 42px;
               height: 42px;
               place-items: center;
               color: #ffffff;
               border-radius: 14px;
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-violet));
          }

          .info-card-title h5 {
               margin: 0;
               color: var(--dept-text);
               font-size: .98rem;
               font-weight: 850;
          }

          .guide-list {
               display: grid;
               gap: 15px;
               padding: 0;
               margin: 0;
               list-style: none;
          }

          .guide-item {
               display: flex;
               gap: 12px;
               align-items: flex-start;
          }

          .guide-check {
               display: grid;
               flex: 0 0 auto;
               width: 30px;
               height: 30px;
               place-items: center;
               color: #ffffff;
               font-size: .8rem;
               border-radius: 10px;
               background: linear-gradient(135deg, var(--dept-green), var(--dept-cyan));
          }

          .guide-item:nth-child(2) .guide-check {
               background: linear-gradient(135deg, var(--dept-blue), var(--dept-indigo));
          }

          .guide-item:nth-child(3) .guide-check {
               background: linear-gradient(135deg, var(--dept-pink), var(--dept-violet));
          }

          .guide-item strong {
               display: block;
               margin-bottom: 2px;
               color: var(--dept-text);
               font-size: .83rem;
          }

          .guide-item p {
               margin: 0;
               color: var(--dept-muted);
               font-size: .76rem;
               line-height: 1.55;
          }

          .database-fields {
               display: grid;
               gap: 10px;
          }

          .database-field {
               display: flex;
               gap: 11px;
               align-items: center;
               padding: 12px 13px;
               border: 1px solid #dbeafe;
               border-radius: 14px;
               background: #f8fbff;
          }

          .database-field-icon {
               display: grid;
               flex: 0 0 auto;
               width: 34px;
               height: 34px;
               place-items: center;
               color: #ffffff;
               border-radius: 11px;
               background: linear-gradient(135deg, var(--dept-blue), var(--dept-cyan));
          }

          .database-field:nth-child(2) .database-field-icon {
               background: linear-gradient(135deg, var(--dept-indigo), var(--dept-violet));
          }

          .database-field:nth-child(3) .database-field-icon {
               background: linear-gradient(135deg, var(--dept-pink), var(--dept-violet));
          }

          .database-field:nth-child(4) .database-field-icon {
               background: linear-gradient(135deg, var(--dept-green), var(--dept-cyan));
          }

          .database-field strong {
               display: block;
               color: var(--dept-text);
               font-size: .81rem;
          }

          .database-field small {
               display: block;
               margin-top: 1px;
               color: var(--dept-muted);
               font-size: .72rem;
          }

          .status-info {
               padding: 17px;
               color: #075985;
               border: 1px solid #bae6fd;
               border-radius: 17px;
               background: linear-gradient(135deg, #f0f9ff, #ecfeff);
          }

          .status-info strong {
               display: block;
               margin-bottom: 5px;
               font-size: .84rem;
          }

          .status-info p {
               margin: 0;
               font-size: .76rem;
               line-height: 1.6;
          }

          @media (max-width: 991.98px) {
               .department-page {
                    padding: 22px;
               }

               .department-hero {
                    padding: 28px;
               }

               .hero-actions {
                    justify-content: flex-start;
                    margin-top: 22px;
               }
          }

          @media (max-width: 767.98px) {
               .department-page {
                    padding: 15px;
               }

               .department-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .hero-icon-box {
                    display: none;
               }

               .btn-back {
                    width: 100%;
               }

               .department-card,
               .info-card {
                    border-radius: 19px;
               }

               .department-card-header,
               .department-card-body {
                    padding: 20px;
               }

               .form-section {
                    padding: 19px;
               }

               .status-options {
                    grid-template-columns: 1fr;
               }

               .form-actions {
                    flex-direction: column;
                    align-items: stretch;
                    padding: 20px;
               }

               .form-actions-note {
                    justify-content: center;
                    text-align: center;
               }

               .action-buttons {
                    flex-direction: column-reverse;
                    width: 100%;
               }

               .btn-cancel,
               .btn-save {
                    width: 100%;
               }
          }

          @media (max-width: 420px) {
               .hero-title {
                    font-size: 1.55rem;
               }

               .hero-description {
                    font-size: .88rem;
               }

               .department-card-header {
                    align-items: flex-start;
               }
          }
     </style>

     <div class="department-page">
          <div class="department-container">

               {{-- Header --}}
               <div class="department-hero">
                    <div class="row align-items-center">
                         <div class="col-lg-8">
                              <div class="hero-content">
                                   <span class="hero-eyebrow">
                                        <i class="bi bi-buildings-fill"></i>
                                        Master Data Department
                                   </span>

                                   <h1 class="hero-title">Tambah Department Baru</h1>

                                   <p class="hero-description">
                                        Tambahkan data department menggunakan kode unik, nama department,
                                        deskripsi, dan status sesuai struktur database aplikasi.
                                   </p>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="hero-actions">
                                   <div class="hero-icon-box">
                                        <i class="bi bi-building-add"></i>
                                   </div>

                                   <a href="{{ route('super-admin.departments.index') }}" class="btn-back">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali ke Daftar
                                   </a>
                              </div>
                         </div>
                    </div>
               </div>

               {{-- Global validation error --}}
               @if ($errors->any())
                    <div class="department-alert" role="alert">
                         <div class="department-alert-icon">
                              <i class="bi bi-exclamation-triangle-fill"></i>
                         </div>

                         <div>
                              <h6>Data belum dapat disimpan</h6>
                              <ul>
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    </div>
               @endif

               <div class="row g-4">
                    {{-- Main form --}}
                    <div class="col-xl-8">
                         <form method="POST" action="{{ route('super-admin.departments.store') }}"
                              class="department-card department-form">
                              @csrf

                              <div class="department-card-header">
                                   <div class="card-header-icon">
                                        <i class="bi bi-ui-checks-grid"></i>
                                   </div>

                                   <div>
                                        <h4>Form Informasi Department</h4>
                                        <p>Kolom bertanda bintang wajib diisi sebelum data disimpan.</p>
                                   </div>
                              </div>

                              <div class="department-card-body">
                                   {{-- Section 1: Identity --}}
                                   <div class="form-section identity-section">
                                        <div class="section-heading">
                                             <span class="section-number">01</span>
                                             <div>
                                                  <h5>Identitas Department</h5>
                                                  <small>Masukkan kode unik dan nama department.</small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-md-5">
                                                  <label for="code" class="form-label">
                                                       Kode Department
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-code-square field-icon"></i>
                                                       <input type="text" id="code" name="code" maxlength="30"
                                                            class="form-control @error('code') is-invalid @enderror"
                                                            placeholder="Akan dibuat otomatis dari nama"
                                                            value="{{ old('code', '') }}"
                                                            oninput="this.value = this.value.toUpperCase()" autofocus
                                                            autocomplete="off">
                                                  </div>

                                                  @error('code')
                                                       <div class="invalid-feedback d-block">{{ $message }}</div>
                                                  @enderror

                                                  <div class="form-hint">
                                                       <i class="bi bi-info-circle"></i>
                                                       <span>Kode akan otomatis mengikuti nama department.</span>
                                                  </div>
                                             </div>

                                             <div class="col-md-7">
                                                  <label for="name" class="form-label">
                                                       Nama Department <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-building field-icon"></i>
                                                       <input type="text" id="name" name="name" maxlength="150"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            placeholder="Contoh: Human Resource Department"
                                                            value="{{ old('name') }}" required autocomplete="off">
                                                  </div>

                                                  @error('name')
                                                       <div class="invalid-feedback d-block">{{ $message }}</div>
                                                  @enderror

                                                  <div class="form-hint">
                                                       <i class="bi bi-info-circle"></i>
                                                       <span>Gunakan nama lengkap agar mudah dikenali.</span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   {{-- Section 2: Status --}}
                                   <div class="form-section status-section">
                                        <div class="section-heading">
                                             <span class="section-number">02</span>
                                             <div>
                                                  <h5>Status Department</h5>
                                                  <small>Tentukan apakah department langsung dapat digunakan.</small>
                                             </div>
                                        </div>

                                        <label class="form-label d-block">
                                             Pilih Status <span class="required-mark">*</span>
                                        </label>

                                        <div class="status-options">
                                             <div class="status-option">
                                                  <input type="radio" id="status_active" name="status" value="active"
                                                       @checked(old('status', 'active') === 'active')>

                                                  <label for="status_active" class="status-label status-label-active">
                                                       <span class="status-icon">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                       </span>

                                                       <span>
                                                            <strong>Active</strong>
                                                            <small>Department dapat dipakai pada modul lain.</small>
                                                       </span>
                                                  </label>
                                             </div>

                                             <div class="status-option">
                                                  <input type="radio" id="status_inactive" name="status" value="inactive"
                                                       @checked(old('status') === 'inactive')>

                                                  <label for="status_inactive" class="status-label status-label-inactive">
                                                       <span class="status-icon">
                                                            <i class="bi bi-pause-circle-fill"></i>
                                                       </span>

                                                       <span>
                                                            <strong>Inactive</strong>
                                                            <small>Department disimpan tetapi belum digunakan.</small>
                                                       </span>
                                                  </label>
                                             </div>
                                        </div>

                                        @error('status')
                                             <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                   </div>

                                   {{-- Section 3: Description --}}
                                   <div class="form-section description-section">
                                        <div class="section-heading">
                                             <span class="section-number">03</span>
                                             <div>
                                                  <h5>Deskripsi Department</h5>
                                                  <small>Tambahkan penjelasan singkat mengenai fungsi department.</small>
                                             </div>
                                        </div>

                                        <label for="description" class="form-label">
                                             Deskripsi
                                             <span class="optional-text">Opsional</span>
                                        </label>

                                        <div class="field-shell field-shell-textarea">
                                             <i class="bi bi-card-text field-icon"></i>
                                             <textarea id="description" name="description" rows="5"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  placeholder="Tuliskan tugas, fungsi, atau keterangan department...">{{ old('description') }}</textarea>
                                        </div>

                                        @error('description')
                                             <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <div class="form-hint">
                                             <i class="bi bi-info-circle"></i>
                                             <span>Deskripsi boleh dikosongkan jika belum diperlukan.</span>
                                        </div>
                                   </div>
                              </div>

                              <div class="form-actions">
                                   <div class="form-actions-note">
                                        <i class="bi bi-shield-check"></i>
                                        <span>Pastikan kode department belum pernah digunakan.</span>
                                   </div>

                                   <div class="action-buttons">
                                        <a href="{{ route('super-admin.departments.index') }}" class="btn-cancel">
                                             <i class="bi bi-x-lg"></i>
                                             Batal
                                        </a>

                                        <button type="submit" class="btn-save">
                                             <i class="bi bi-check2-circle"></i>
                                             Simpan Department
                                        </button>
                                   </div>
                              </div>
                         </form>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-xl-4">
                         <div class="sidebar-stack">
                              <div class="info-card">
                                   <div class="info-card-accent accent-purple"></div>
                                   <div class="info-card-body">
                                        <div class="info-card-title">
                                             <span><i class="bi bi-lightbulb-fill"></i></span>
                                             <h5>Panduan Pengisian</h5>
                                        </div>

                                        <ul class="guide-list">
                                             <li class="guide-item">
                                                  <span class="guide-check">
                                                       <i class="bi bi-check-lg"></i>
                                                  </span>
                                                  <div>
                                                       <strong>Kode harus unik</strong>
                                                       <p>Gunakan kode singkat seperti HRD, FIN, IT, atau OPS.</p>
                                                  </div>
                                             </li>

                                             <li class="guide-item">
                                                  <span class="guide-check">
                                                       <i class="bi bi-check-lg"></i>
                                                  </span>
                                                  <div>
                                                       <strong>Nama harus jelas</strong>
                                                       <p>Nama lengkap memudahkan pencarian dan pemilihan data.</p>
                                                  </div>
                                             </li>

                                             <li class="guide-item">
                                                  <span class="guide-check">
                                                       <i class="bi bi-check-lg"></i>
                                                  </span>
                                                  <div>
                                                       <strong>Status dapat diubah</strong>
                                                       <p>Data inactive tetap tersimpan dan dapat diaktifkan kembali.</p>
                                                  </div>
                                             </li>
                                        </ul>
                                   </div>
                              </div>

                              <div class="info-card">
                                   <div class="info-card-accent accent-cyan"></div>
                                   <div class="info-card-body">
                                        <div class="info-card-title">
                                             <span
                                                  style="background: linear-gradient(135deg, var(--dept-blue), var(--dept-cyan));">
                                                  <i class="bi bi-database-fill-check"></i>
                                             </span>
                                             <h5>Kolom Database</h5>
                                        </div>

                                        <div class="database-fields">
                                             <div class="database-field">
                                                  <span class="database-field-icon"><i class="bi bi-code-slash"></i></span>
                                                  <div>
                                                       <strong>code</strong>
                                                       <small>Wajib, unik, maksimal 30 karakter</small>
                                                  </div>
                                             </div>

                                             <div class="database-field">
                                                  <span class="database-field-icon"><i class="bi bi-building"></i></span>
                                                  <div>
                                                       <strong>name</strong>
                                                       <small>Wajib, maksimal 150 karakter</small>
                                                  </div>
                                             </div>

                                             <div class="database-field">
                                                  <span class="database-field-icon"><i class="bi bi-card-text"></i></span>
                                                  <div>
                                                       <strong>description</strong>
                                                       <small>Opsional, tipe data text</small>
                                                  </div>
                                             </div>

                                             <div class="database-field">
                                                  <span class="database-field-icon"><i class="bi bi-toggle-on"></i></span>
                                                  <div>
                                                       <strong>status</strong>
                                                       <small>Active atau inactive</small>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="info-card">
                                   <div class="info-card-accent accent-green"></div>
                                   <div class="info-card-body">
                                        <div class="status-info">
                                             <strong>
                                                  <i class="bi bi-info-circle-fill me-1"></i>
                                                  Informasi Penyimpanan
                                             </strong>
                                             <p>
                                                  Data yang dihapus menggunakan <b>soft delete</b>, sehingga masih dapat
                                                  dikembalikan melalui halaman data terhapus.
                                             </p>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const nameInput = document.getElementById('name');
               const codeInput = document.getElementById('code');

               if (!nameInput || !codeInput) {
                    return;
               }

               const toCodeFromName = (value) => {
                    const cleaned = value
                         .normalize('NFKD')
                         .replace(/[\u0300-\u036f]/g, '')
                         .replace(/[^A-Za-z0-9\s]/g, ' ')
                         .trim();

                    if (!cleaned) {
                         return '';
                    }

                    const parts = cleaned.split(/\s+/).filter(Boolean);
                    const initials = parts
                         .slice(0, 4)
                         .map(part => part.charAt(0).toUpperCase())
                         .join('');

                    const suffix = String(Math.floor(Math.random() * 90) + 10);

                    return initials ? `${initials}-${suffix}` : '';
               };

               const syncCodeFromName = () => {
                    if (codeInput.value.trim() !== '') {
                         return;
                    }

                    codeInput.value = toCodeFromName(nameInput.value);
               };

               nameInput.addEventListener('input', syncCodeFromName);
               syncCodeFromName();
          });
     </script>
@endsection
