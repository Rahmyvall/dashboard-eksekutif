@extends('layouts.app')

@section('title', 'Tambah Employee')

@section('content')
     <style>
          :root {
               --emp-indigo: #6366f1;
               --emp-violet: #8b5cf6;
               --emp-blue: #0ea5e9;
               --emp-cyan: #06b6d4;
               --emp-green: #10b981;
               --emp-yellow: #f59e0b;
               --emp-red: #ef4444;
               --emp-pink: #ec4899;
               --emp-text: #1e293b;
               --emp-muted: #64748b;
               --emp-border: #dbeafe;
               --emp-white: #ffffff;
               --emp-soft-blue: #eff6ff;
               --emp-soft-indigo: #eef2ff;
               --emp-soft-cyan: #ecfeff;
               --emp-soft-green: #ecfdf5;
               --emp-soft-yellow: #fffbeb;
               --emp-soft-red: #fef2f2;
          }

          .employee-page {
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

          .employee-page::before,
          .employee-page::after {
               position: absolute;
               z-index: 0;
               content: "";
               border-radius: 999px;
               pointer-events: none;
          }

          .employee-page::before {
               top: 120px;
               right: -110px;
               width: 260px;
               height: 260px;
               background: linear-gradient(135deg,
                         rgba(99, 102, 241, .14),
                         rgba(14, 165, 233, .08));
          }

          .employee-page::after {
               bottom: 30px;
               left: -130px;
               width: 280px;
               height: 280px;
               background: linear-gradient(135deg,
                         rgba(16, 185, 129, .10),
                         rgba(236, 72, 153, .10));
          }

          .employee-container {
               position: relative;
               z-index: 1;
               max-width: 1580px;
               margin: 0 auto;
          }

          /* HERO */
          .employee-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 24px;
               color: var(--emp-text);
               border: 1px solid rgba(255, 255, 255, .9);
               border-radius: 28px;
               background:
                    linear-gradient(115deg,
                         rgba(255, 255, 255, .96),
                         rgba(238, 242, 255, .96) 45%,
                         rgba(236, 254, 255, .96));
               box-shadow: 0 22px 55px rgba(71, 85, 105, .12);
          }

          .employee-hero::before {
               position: absolute;
               top: -85px;
               right: -30px;
               width: 250px;
               height: 250px;
               content: "";
               border: 42px solid rgba(99, 102, 241, .08);
               border-radius: 999px;
          }

          .employee-hero::after {
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
               max-width: 760px;
               margin: 10px 0 0;
               color: var(--emp-muted);
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
               background: linear-gradient(135deg,
                         var(--emp-indigo),
                         var(--emp-cyan));
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
               background: linear-gradient(135deg,
                         var(--emp-indigo),
                         var(--emp-violet));
               transform: translateY(-2px);
          }

          /* ALERT */
          .employee-alert {
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

          .employee-alert-icon {
               display: grid;
               flex: 0 0 auto;
               width: 42px;
               height: 42px;
               place-items: center;
               color: #ffffff;
               border-radius: 13px;
               background: linear-gradient(135deg, #f87171, #dc2626);
          }

          .employee-alert h6 {
               margin-bottom: 6px;
               font-weight: 850;
          }

          .employee-alert ul {
               padding-left: 18px;
               margin: 0;
               font-size: .88rem;
          }

          /* CARD */
          .employee-card {
               overflow: hidden;
               border: 1px solid rgba(219, 234, 254, .95);
               border-radius: 26px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 20px 55px rgba(51, 65, 85, .10);
          }

          .employee-card-header {
               display: flex;
               gap: 16px;
               align-items: center;
               padding: 24px 26px;
               border-bottom: 1px solid #e0e7ff;
               background: linear-gradient(100deg,
                         #eef2ff 0%,
                         #eff6ff 45%,
                         #ecfeff 100%);
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
               background: linear-gradient(135deg,
                         var(--emp-indigo),
                         var(--emp-blue),
                         var(--emp-cyan));
               box-shadow: 0 12px 26px rgba(14, 165, 233, .22);
          }

          .employee-card-header h4 {
               margin: 0;
               color: var(--emp-text);
               font-size: 1.1rem;
               font-weight: 850;
          }

          .employee-card-header p {
               margin: 4px 0 0;
               color: var(--emp-muted);
               font-size: .86rem;
          }

          .employee-card-body {
               padding: 30px;
          }

          /* SECTION */
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

          .identity-section {
               border-top: 4px solid var(--emp-indigo);
          }

          .organization-section {
               border-top: 4px solid var(--emp-blue);
          }

          .personal-section {
               border-top: 4px solid var(--emp-pink);
          }

          .contact-section {
               border-top: 4px solid var(--emp-cyan);
          }

          .employment-section {
               border-top: 4px solid var(--emp-yellow);
          }

          .photo-section {
               border-top: 4px solid var(--emp-violet);
          }

          .status-section {
               border-top: 4px solid var(--emp-green);
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
               background: linear-gradient(135deg,
                         var(--emp-indigo),
                         var(--emp-violet));
               box-shadow: 0 8px 18px rgba(99, 102, 241, .20);
          }

          .organization-section .section-number {
               background: linear-gradient(135deg,
                         var(--emp-blue),
                         var(--emp-cyan));
          }

          .personal-section .section-number {
               background: linear-gradient(135deg,
                         var(--emp-pink),
                         var(--emp-violet));
          }

          .contact-section .section-number {
               background: linear-gradient(135deg,
                         var(--emp-cyan),
                         var(--emp-green));
          }

          .employment-section .section-number {
               background: linear-gradient(135deg,
                         var(--emp-yellow),
                         #fb7185);
          }

          .photo-section .section-number {
               background: linear-gradient(135deg,
                         var(--emp-violet),
                         var(--emp-pink));
          }

          .status-section .section-number {
               background: linear-gradient(135deg,
                         var(--emp-green),
                         var(--emp-cyan));
          }

          .section-heading h5 {
               margin: 0;
               color: var(--emp-text);
               font-size: .98rem;
               font-weight: 850;
          }

          .section-heading small {
               display: block;
               margin-top: 2px;
               color: var(--emp-muted);
               font-size: .78rem;
          }

          /* FORM */
          .employee-form .form-label {
               margin-bottom: 8px;
               color: var(--emp-text);
               font-size: .87rem;
               font-weight: 800;
          }

          .required-mark {
               color: var(--emp-red);
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

          .employee-form .form-control,
          .employee-form .form-select {
               min-height: 52px;
               padding: 12px 15px 12px 44px;
               color: var(--emp-text);
               font-size: .9rem;
               border: 1px solid #cbd5e1;
               border-radius: 15px;
               background-color: #ffffff;
               box-shadow: 0 2px 5px rgba(15, 23, 42, .03);
               transition:
                    border-color .2s ease,
                    box-shadow .2s ease,
                    transform .2s ease;
          }

          .employee-form .form-select {
               padding-right: 38px;
          }

          .employee-form textarea.form-control {
               min-height: 135px;
               padding-top: 14px;
               resize: vertical;
          }

          .employee-form .form-control::placeholder {
               color: #94a3b8;
          }

          .employee-form .form-control:hover,
          .employee-form .form-select:hover {
               border-color: #a5b4fc;
          }

          .employee-form .form-control:focus,
          .employee-form .form-select:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .24rem rgba(99, 102, 241, .13);
          }

          .field-shell:focus-within .field-icon {
               color: var(--emp-indigo);
          }

          .employee-form .is-invalid {
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

          /* PHOTO */
          .photo-upload-layout {
               display: grid;
               grid-template-columns: 180px 1fr;
               gap: 22px;
               align-items: center;
          }

          .photo-preview {
               display: grid;
               width: 180px;
               height: 180px;
               overflow: hidden;
               place-items: center;
               color: #6366f1;
               font-size: 3.3rem;
               border: 2px dashed #c7d2fe;
               border-radius: 24px;
               background: linear-gradient(135deg, #eef2ff, #ecfeff);
          }

          .photo-preview img {
               display: none;
               width: 100%;
               height: 100%;
               object-fit: cover;
          }

          .photo-note {
               padding: 16px;
               color: #475569;
               font-size: .8rem;
               line-height: 1.65;
               border: 1px solid #dbeafe;
               border-radius: 16px;
               background: #f8fbff;
          }

          /* STATUS */
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
               background: linear-gradient(135deg,
                         var(--emp-green),
                         var(--emp-cyan));
          }

          .status-label-inactive .status-icon {
               background: linear-gradient(135deg,
                         #fb7185,
                         var(--emp-pink));
          }

          .status-label strong {
               display: block;
               margin-bottom: 3px;
               font-size: .88rem;
          }

          .status-label small {
               display: block;
               color: var(--emp-muted);
               font-size: .74rem;
               line-height: 1.45;
          }

          .status-option input:checked+.status-label-active {
               border-color: var(--emp-green);
               box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
          }

          .status-option input:checked+.status-label-inactive {
               border-color: var(--emp-pink);
               box-shadow: 0 0 0 4px rgba(236, 72, 153, .12);
          }

          /* ACTIONS */
          .form-actions {
               display: flex;
               gap: 12px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 28px;
               border-top: 1px solid #e0e7ff;
               background: linear-gradient(100deg,
                         #fbfdff,
                         #f5f3ff,
                         #ecfeff);
          }

          .form-actions-note {
               display: flex;
               gap: 8px;
               align-items: center;
               color: var(--emp-muted);
               font-size: .78rem;
          }

          .form-actions-note i {
               color: var(--emp-green);
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
               background: linear-gradient(135deg,
                         var(--emp-indigo),
                         var(--emp-violet) 45%,
                         var(--emp-cyan));
               box-shadow: 0 13px 28px rgba(99, 102, 241, .25);
          }

          .btn-save:hover {
               color: #ffffff;
               box-shadow: 0 17px 34px rgba(99, 102, 241, .32);
               transform: translateY(-2px);
          }

          /* SIDEBAR INFO */
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
               background: linear-gradient(90deg,
                         var(--emp-indigo),
                         var(--emp-violet),
                         var(--emp-pink));
          }

          .accent-cyan {
               background: linear-gradient(90deg,
                         var(--emp-cyan),
                         var(--emp-blue),
                         var(--emp-indigo));
          }

          .accent-green {
               background: linear-gradient(90deg,
                         var(--emp-green),
                         var(--emp-cyan),
                         var(--emp-yellow));
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
               background: linear-gradient(135deg,
                         var(--emp-indigo),
                         var(--emp-violet));
          }

          .info-card-title h5 {
               margin: 0;
               color: var(--emp-text);
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
               background: linear-gradient(135deg,
                         var(--emp-green),
                         var(--emp-cyan));
          }

          .guide-item:nth-child(2) .guide-check {
               background: linear-gradient(135deg,
                         var(--emp-blue),
                         var(--emp-indigo));
          }

          .guide-item:nth-child(3) .guide-check {
               background: linear-gradient(135deg,
                         var(--emp-pink),
                         var(--emp-violet));
          }

          .guide-item strong {
               display: block;
               margin-bottom: 2px;
               color: var(--emp-text);
               font-size: .83rem;
          }

          .guide-item p {
               margin: 0;
               color: var(--emp-muted);
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
               background: linear-gradient(135deg,
                         var(--emp-blue),
                         var(--emp-cyan));
          }

          .database-field strong {
               display: block;
               color: var(--emp-text);
               font-size: .81rem;
          }

          .database-field small {
               display: block;
               margin-top: 1px;
               color: var(--emp-muted);
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
               .employee-page {
                    padding: 22px;
               }

               .employee-hero {
                    padding: 28px;
               }

               .hero-actions {
                    justify-content: flex-start;
                    margin-top: 22px;
               }
          }

          @media (max-width: 767.98px) {
               .employee-page {
                    padding: 15px;
               }

               .employee-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .hero-icon-box {
                    display: none;
               }

               .btn-back {
                    width: 100%;
               }

               .employee-card,
               .info-card {
                    border-radius: 19px;
               }

               .employee-card-header,
               .employee-card-body {
                    padding: 20px;
               }

               .form-section {
                    padding: 19px;
               }

               .photo-upload-layout {
                    grid-template-columns: 1fr;
               }

               .photo-preview {
                    width: 150px;
                    height: 150px;
                    margin: 0 auto;
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
     </style>

     @php
          $employmentOptions = [
              'permanent' => 'Karyawan Tetap',
              'contract' => 'Karyawan Kontrak',
              'probation' => 'Masa Percobaan',
              'internship' => 'Magang',
              'outsourcing' => 'Outsourcing',
          ];
     @endphp

     <div class="employee-page">
          <div class="employee-container">
               {{-- HEADER --}}
               <div class="employee-hero">
                    <div class="row align-items-center">
                         <div class="col-lg-8">
                              <div class="hero-content">
                                   <span class="hero-eyebrow">
                                        <i class="bi bi-people-fill"></i>
                                        Master Data Employee
                                   </span>

                                   <h1 class="hero-title">
                                        Tambah Employee Baru
                                   </h1>

                                   <p class="hero-description">
                                        Tambahkan identitas employee, akun pengguna,
                                        departemen, jabatan, informasi pribadi,
                                        status kepegawaian, gaji pokok, dan foto.
                                   </p>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="hero-actions">
                                   <div class="hero-icon-box">
                                        <i class="bi bi-person-plus-fill"></i>
                                   </div>

                                   <a href="{{ route('super-admin.employees.index') }}" class="btn-back">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali ke Daftar
                                   </a>
                              </div>
                         </div>
                    </div>
               </div>

               {{-- GLOBAL VALIDATION ERROR --}}
               @if ($errors->any())
                    <div class="employee-alert" role="alert">
                         <div class="employee-alert-icon">
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
                    {{-- MAIN FORM --}}
                    <div class="col-xl-9">
                         <form method="POST" action="{{ route('super-admin.employees.store') }}"
                              enctype="multipart/form-data" class="employee-card employee-form" novalidate>
                              @csrf

                              <div class="employee-card-header">
                                   <div class="card-header-icon">
                                        <i class="bi bi-ui-checks-grid"></i>
                                   </div>

                                   <div>
                                        <h4>Form Informasi Employee</h4>
                                        <p>
                                             Kolom bertanda bintang wajib diisi sebelum
                                             data disimpan.
                                        </p>
                                   </div>
                              </div>

                              <div class="employee-card-body">
                                   {{-- SECTION 1: IDENTITY --}}
                                   <div class="form-section identity-section">
                                        <div class="section-heading">
                                             <span class="section-number">01</span>

                                             <div>
                                                  <h5>Identitas Utama</h5>
                                                  <small>
                                                       Nomor employee otomatis dan nama lengkap.
                                                  </small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-md-5">
                                                  <label for="employee_number_preview" class="form-label">
                                                       Nomor Employee
                                                       <span class="optional-text">
                                                            Otomatis
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-person-vcard field-icon"></i>

                                                       <input type="text" id="employee_number_preview"
                                                            class="form-control"
                                                            value="{{ $nextEmployeeNumber ?? 'EMP-0001' }}" readonly
                                                            tabindex="-1" aria-describedby="employeeNumberHelp">
                                                  </div>

                                                  <div id="employeeNumberHelp" class="form-hint">
                                                       <i class="bi bi-shield-check"></i>
                                                       <span>
                                                            Nomor final dibuat oleh server
                                                            saat data disimpan agar tetap
                                                            unik dan berurutan.
                                                       </span>
                                                  </div>
                                             </div>

                                             <div class="col-md-7">
                                                  <label for="full_name" class="form-label">
                                                       Nama Lengkap
                                                       <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-person-fill field-icon"></i>

                                                       <input type="text" id="full_name" name="full_name" maxlength="150"
                                                            class="form-control @error('full_name') is-invalid @enderror"
                                                            placeholder="Masukkan nama lengkap employee"
                                                            value="{{ old('full_name') }}" required autofocus
                                                            autocomplete="name">
                                                  </div>

                                                  @error('full_name')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>

                                   {{-- SECTION 2: ORGANIZATION --}}
                                   <div class="form-section organization-section">
                                        <div class="section-heading">
                                             <span class="section-number">02</span>

                                             <div>
                                                  <h5>Akun dan Organisasi</h5>
                                                  <small>
                                                       Hubungkan employee dengan akun,
                                                       departemen, dan jabatan.
                                                  </small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-lg-4">
                                                  <label for="user_id" class="form-label">
                                                       Akun Pengguna
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-person-lock field-icon"></i>

                                                       <select id="user_id" name="user_id"
                                                            class="form-select @error('user_id') is-invalid @enderror">
                                                            <option value="">
                                                                 Tanpa akun pengguna
                                                            </option>

                                                            @foreach ($users as $user)
                                                                 <option value="{{ $user->id }}"
                                                                      @selected((string) old('user_id') === (string) $user->id)>
                                                                      {{ $user->name }}
                                                                      @if ($user->email)
                                                                           — {{ $user->email }}
                                                                      @endif
                                                                 </option>
                                                            @endforeach
                                                       </select>
                                                  </div>

                                                  @error('user_id')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror

                                                  <div class="form-hint">
                                                       <i class="bi bi-info-circle"></i>
                                                       <span>
                                                            Satu akun hanya boleh terhubung
                                                            dengan satu employee.
                                                       </span>
                                                  </div>
                                             </div>

                                             <div class="col-lg-4">
                                                  <label for="department_id" class="form-label">
                                                       Departemen
                                                       <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-buildings-fill field-icon"></i>

                                                       <select id="department_id" name="department_id"
                                                            class="form-select @error('department_id') is-invalid @enderror"
                                                            required>
                                                            <option value="">
                                                                 Pilih departemen
                                                            </option>

                                                            @foreach ($departments as $department)
                                                                 <option value="{{ $department->id }}"
                                                                      @selected((string) old('department_id') === (string) $department->id)>
                                                                      {{ $department->code }}
                                                                      — {{ $department->name }}
                                                                 </option>
                                                            @endforeach
                                                       </select>
                                                  </div>

                                                  @error('department_id')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-lg-4">
                                                  <label for="position_id" class="form-label">
                                                       Jabatan
                                                       <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-briefcase-fill field-icon"></i>

                                                       <select id="position_id" name="position_id"
                                                            class="form-select @error('position_id') is-invalid @enderror"
                                                            required>
                                                            <option value="">
                                                                 Pilih jabatan
                                                            </option>

                                                            @foreach ($positions as $position)
                                                                 <option value="{{ $position->id }}"
                                                                      data-department-id="{{ $position->department_id }}"
                                                                      @selected((string) old('position_id') === (string) $position->id)>
                                                                      {{ $position->name }}
                                                                 </option>
                                                            @endforeach
                                                       </select>
                                                  </div>

                                                  @error('position_id')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror

                                                  <div class="form-hint">
                                                       <i class="bi bi-info-circle"></i>
                                                       <span>
                                                            Pilihan jabatan mengikuti
                                                            departemen.
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   {{-- SECTION 3: PERSONAL --}}
                                   <div class="form-section personal-section">
                                        <div class="section-heading">
                                             <span class="section-number">03</span>

                                             <div>
                                                  <h5>Informasi Pribadi</h5>
                                                  <small>
                                                       Jenis kelamin, tempat, dan tanggal
                                                       lahir employee.
                                                  </small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-md-4">
                                                  <label for="gender" class="form-label">
                                                       Jenis Kelamin
                                                       <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-gender-ambiguous field-icon"></i>

                                                       <select id="gender" name="gender"
                                                            class="form-select @error('gender') is-invalid @enderror"
                                                            required>
                                                            <option value="">
                                                                 Pilih jenis kelamin
                                                            </option>
                                                            <option value="male" @selected(old('gender') === 'male')>
                                                                 Laki-laki
                                                            </option>
                                                            <option value="female" @selected(old('gender') === 'female')>
                                                                 Perempuan
                                                            </option>
                                                       </select>
                                                  </div>

                                                  @error('gender')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-md-4">
                                                  <label for="birth_place" class="form-label">
                                                       Tempat Lahir
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-geo-alt-fill field-icon"></i>

                                                       <input type="text" id="birth_place" name="birth_place"
                                                            maxlength="100"
                                                            class="form-control @error('birth_place') is-invalid @enderror"
                                                            placeholder="Contoh: Jakarta" value="{{ old('birth_place') }}"
                                                            autocomplete="off">
                                                  </div>

                                                  @error('birth_place')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-md-4">
                                                  <label for="birth_date" class="form-label">
                                                       Tanggal Lahir
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-calendar-event field-icon"></i>

                                                       <input type="date" id="birth_date" name="birth_date"
                                                            max="{{ now()->format('Y-m-d') }}"
                                                            class="form-control @error('birth_date') is-invalid @enderror"
                                                            value="{{ old('birth_date') }}">
                                                  </div>

                                                  @error('birth_date')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>

                                   {{-- SECTION 4: CONTACT --}}
                                   <div class="form-section contact-section">
                                        <div class="section-heading">
                                             <span class="section-number">04</span>

                                             <div>
                                                  <h5>Kontak dan Alamat</h5>
                                                  <small>
                                                       Informasi komunikasi employee.
                                                  </small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-md-6">
                                                  <label for="phone" class="form-label">
                                                       Nomor Telepon
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-telephone-fill field-icon"></i>

                                                       <input type="text" id="phone" name="phone" maxlength="30"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            placeholder="Contoh: 081234567890" value="{{ old('phone') }}"
                                                            inputmode="tel" autocomplete="tel">
                                                  </div>

                                                  @error('phone')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-md-6">
                                                  <label for="email" class="form-label">
                                                       Email Employee
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-envelope-fill field-icon"></i>

                                                       <input type="email" id="email" name="email"
                                                            maxlength="150"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="nama@perusahaan.com" value="{{ old('email') }}"
                                                            autocomplete="email">
                                                  </div>

                                                  @error('email')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-12">
                                                  <label for="address" class="form-label">
                                                       Alamat
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell field-shell-textarea">
                                                       <i class="bi bi-house-door-fill field-icon"></i>

                                                       <textarea id="address" name="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                                                            placeholder="Masukkan alamat lengkap employee...">{{ old('address') }}</textarea>
                                                  </div>

                                                  @error('address')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>

                                   {{-- SECTION 5: EMPLOYMENT --}}
                                   <div class="form-section employment-section">
                                        <div class="section-heading">
                                             <span class="section-number">05</span>

                                             <div>
                                                  <h5>Data Kepegawaian</h5>
                                                  <small>
                                                       Tanggal masuk, status kepegawaian,
                                                       dan gaji pokok.
                                                  </small>
                                             </div>
                                        </div>

                                        <div class="row g-4">
                                             <div class="col-md-4">
                                                  <label for="hire_date" class="form-label">
                                                       Tanggal Mulai Bekerja
                                                       <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-calendar-check-fill field-icon"></i>

                                                       <input type="date" id="hire_date" name="hire_date"
                                                            class="form-control @error('hire_date') is-invalid @enderror"
                                                            value="{{ old('hire_date', now()->format('Y-m-d')) }}"
                                                            required>
                                                  </div>

                                                  @error('hire_date')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-md-4">
                                                  <label for="employment_status" class="form-label">
                                                       Status Kepegawaian
                                                       <span class="required-mark">*</span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-briefcase-fill field-icon"></i>

                                                       <select id="employment_status" name="employment_status"
                                                            class="form-select @error('employment_status') is-invalid @enderror"
                                                            required>
                                                            <option value="">
                                                                 Pilih status kepegawaian
                                                            </option>

                                                            @foreach ($employmentOptions as $value => $label)
                                                                 <option value="{{ $value }}"
                                                                      @selected(old('employment_status') === $value)>
                                                                      {{ $label }}
                                                                 </option>
                                                            @endforeach
                                                       </select>
                                                  </div>

                                                  @error('employment_status')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror
                                             </div>

                                             <div class="col-md-4">
                                                  <label for="basic_salary" class="form-label">
                                                       Gaji Pokok
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-cash-stack field-icon"></i>

                                                       <input type="number" id="basic_salary" name="basic_salary"
                                                            min="0" step="0.01"
                                                            class="form-control @error('basic_salary') is-invalid @enderror"
                                                            placeholder="Contoh: 5000000"
                                                            value="{{ old('basic_salary') }}" inputmode="decimal">
                                                  </div>

                                                  @error('basic_salary')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror

                                                  <div class="form-hint">
                                                       <i class="bi bi-info-circle"></i>
                                                       <span>
                                                            Masukkan angka tanpa tanda titik
                                                            pemisah ribuan.
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   {{-- SECTION 6: PHOTO --}}
                                   <div class="form-section photo-section">
                                        <div class="section-heading">
                                             <span class="section-number">06</span>

                                             <div>
                                                  <h5>Foto Employee</h5>
                                                  <small>
                                                       Unggah foto profil employee.
                                                  </small>
                                             </div>
                                        </div>

                                        <div class="photo-upload-layout">
                                             <div id="photoPreview" class="photo-preview"
                                                  aria-label="Pratinjau foto employee">
                                                  <i id="photoPlaceholder" class="bi bi-person-bounding-box"></i>

                                                  <img id="photoPreviewImage" src=""
                                                       alt="Pratinjau foto employee">
                                             </div>

                                             <div>
                                                  <label for="photo" class="form-label">
                                                       Pilih Foto
                                                       <span class="optional-text">
                                                            Opsional
                                                       </span>
                                                  </label>

                                                  <div class="field-shell">
                                                       <i class="bi bi-image-fill field-icon"></i>

                                                       <input type="file" id="photo" name="photo"
                                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                                            class="form-control @error('photo') is-invalid @enderror">
                                                  </div>

                                                  @error('photo')
                                                       <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                       </div>
                                                  @enderror

                                                  <div class="photo-note mt-3">
                                                       <i class="bi bi-info-circle-fill me-1"></i>
                                                       Format yang diperbolehkan:
                                                       JPG, JPEG, PNG, atau WEBP.
                                                       Ukuran maksimal 2 MB.
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   {{-- SECTION 7: STATUS --}}
                                   <div class="form-section status-section">
                                        <div class="section-heading">
                                             <span class="section-number">07</span>

                                             <div>
                                                  <h5>Status Employee</h5>
                                                  <small>
                                                       Tentukan apakah employee langsung
                                                       aktif di sistem.
                                                  </small>
                                             </div>
                                        </div>

                                        <label class="form-label d-block">
                                             Pilih Status
                                             <span class="required-mark">*</span>
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
                                                            <small>
                                                                 Employee aktif dan dapat
                                                                 digunakan pada modul lain.
                                                            </small>
                                                       </span>
                                                  </label>
                                             </div>

                                             <div class="status-option">
                                                  <input type="radio" id="status_inactive" name="status"
                                                       value="inactive" @checked(old('status') === 'inactive')>

                                                  <label for="status_inactive" class="status-label status-label-inactive">
                                                       <span class="status-icon">
                                                            <i class="bi bi-pause-circle-fill"></i>
                                                       </span>

                                                       <span>
                                                            <strong>Inactive</strong>
                                                            <small>
                                                                 Data tersimpan, tetapi belum
                                                                 digunakan secara aktif.
                                                            </small>
                                                       </span>
                                                  </label>
                                             </div>
                                        </div>

                                        @error('status')
                                             <div class="invalid-feedback d-block">
                                                  {{ $message }}
                                             </div>
                                        @enderror
                                   </div>
                              </div>

                              <div class="form-actions">
                                   <div class="form-actions-note">
                                        <i class="bi bi-shield-check"></i>

                                        <span>
                                             Nomor employee dibuat otomatis. Pastikan
                                             email dan akun pengguna belum digunakan
                                             employee lain.
                                        </span>
                                   </div>

                                   <div class="action-buttons">
                                        <a href="{{ route('super-admin.employees.index') }}" class="btn-cancel">
                                             <i class="bi bi-x-lg"></i>
                                             Batal
                                        </a>

                                        <button type="submit" class="btn-save">
                                             <i class="bi bi-check2-circle"></i>
                                             Simpan Employee
                                        </button>
                                   </div>
                              </div>
                         </form>
                    </div>

                    {{-- INFO SIDEBAR --}}
                    <div class="col-xl-3">
                         <div class="sidebar-stack">
                              <div class="info-card">
                                   <div class="info-card-accent accent-purple"></div>

                                   <div class="info-card-body">
                                        <div class="info-card-title">
                                             <span>
                                                  <i class="bi bi-lightbulb-fill"></i>
                                             </span>

                                             <h5>Panduan Pengisian</h5>
                                        </div>

                                        <ul class="guide-list">
                                             <li class="guide-item">
                                                  <span class="guide-check">
                                                       <i class="bi bi-check-lg"></i>
                                                  </span>

                                                  <div>
                                                       <strong>
                                                            Nomor dibuat otomatis
                                                       </strong>

                                                       <p>
                                                            Sistem menggunakan format
                                                            EMP-0001 dan menaikkan nomor
                                                            secara berurutan.
                                                       </p>
                                                  </div>
                                             </li>

                                             <li class="guide-item">
                                                  <span class="guide-check">
                                                       <i class="bi bi-check-lg"></i>
                                                  </span>

                                                  <div>
                                                       <strong>
                                                            Jabatan harus sesuai departemen
                                                       </strong>

                                                       <p>
                                                            Pilihan jabatan akan disaring
                                                            berdasarkan departemen.
                                                       </p>
                                                  </div>
                                             </li>

                                             <li class="guide-item">
                                                  <span class="guide-check">
                                                       <i class="bi bi-check-lg"></i>
                                                  </span>

                                                  <div>
                                                       <strong>
                                                            Periksa data kepegawaian
                                                       </strong>

                                                       <p>
                                                            Pastikan tanggal masuk dan status
                                                            kerja sesuai dokumen HR.
                                                       </p>
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
                                                  style="background: linear-gradient(135deg, var(--emp-blue), var(--emp-cyan));">
                                                  <i class="bi bi-database-fill-check"></i>
                                             </span>

                                             <h5>Kolom Utama Database</h5>
                                        </div>

                                        <div class="database-fields">
                                             <div class="database-field">
                                                  <span class="database-field-icon">
                                                       <i class="bi bi-person-vcard"></i>
                                                  </span>

                                                  <div>
                                                       <strong>employee_number</strong>
                                                       <small>
                                                            Dibuat otomatis dan unik
                                                       </small>
                                                  </div>
                                             </div>

                                             <div class="database-field">
                                                  <span class="database-field-icon">
                                                       <i class="bi bi-person-fill"></i>
                                                  </span>

                                                  <div>
                                                       <strong>full_name</strong>
                                                       <small>
                                                            Wajib, maksimal 150 karakter
                                                       </small>
                                                  </div>
                                             </div>

                                             <div class="database-field">
                                                  <span class="database-field-icon">
                                                       <i class="bi bi-buildings-fill"></i>
                                                  </span>

                                                  <div>
                                                       <strong>
                                                            department_id / position_id
                                                       </strong>

                                                       <small>
                                                            Relasi ke departemen dan jabatan
                                                       </small>
                                                  </div>
                                             </div>

                                             <div class="database-field">
                                                  <span class="database-field-icon">
                                                       <i class="bi bi-briefcase-fill"></i>
                                                  </span>

                                                  <div>
                                                       <strong>employment_status</strong>
                                                       <small>
                                                            Status hubungan kerja employee
                                                       </small>
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
                                                  Foto disimpan pada disk
                                                  <b>public</b>. Data employee yang dihapus
                                                  menggunakan <b>soft delete</b> dan masih
                                                  dapat dipulihkan dari halaman trash.
                                             </p>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     @push('scripts')
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const departmentSelect =
                         document.getElementById('department_id');

                    const positionSelect =
                         document.getElementById('position_id');

                    const photoInput =
                         document.getElementById('photo');

                    const photoPreviewImage =
                         document.getElementById('photoPreviewImage');

                    const photoPlaceholder =
                         document.getElementById('photoPlaceholder');

                    if (departmentSelect && positionSelect) {
                         const originalPositionOptions = Array.from(
                              positionSelect.options
                         ).map(function(option) {
                              return option.cloneNode(true);
                         });

                         const oldPositionId = @json((string) old('position_id'));

                         function refreshPositions() {
                              const departmentId = departmentSelect.value;

                              positionSelect.innerHTML = '';

                              originalPositionOptions.forEach(function(option) {
                                   const optionDepartmentId =
                                        option.dataset.departmentId || '';

                                   if (
                                        option.value === '' ||
                                        departmentId === '' ||
                                        optionDepartmentId === departmentId
                                   ) {
                                        const clonedOption =
                                             option.cloneNode(true);

                                        if (
                                             String(clonedOption.value) ===
                                             String(oldPositionId)
                                        ) {
                                             clonedOption.selected = true;
                                        }

                                        positionSelect.appendChild(clonedOption);
                                   }
                              });

                              const selectedOption =
                                   positionSelect.options[
                                        positionSelect.selectedIndex
                                   ];

                              if (
                                   selectedOption &&
                                   selectedOption.value !== '' &&
                                   departmentId !== '' &&
                                   selectedOption.dataset.departmentId !==
                                   departmentId
                              ) {
                                   positionSelect.value = '';
                              }
                         }

                         departmentSelect.addEventListener(
                              'change',
                              refreshPositions
                         );

                         refreshPositions();
                    }

                    if (
                         photoInput &&
                         photoPreviewImage &&
                         photoPlaceholder
                    ) {
                         photoInput.addEventListener(
                              'change',
                              function(event) {
                                   const file = event.target.files[0];

                                   if (!file) {
                                        photoPreviewImage.removeAttribute('src');
                                        photoPreviewImage.style.display = 'none';
                                        photoPlaceholder.style.display = 'inline-block';
                                        return;
                                   }

                                   if (!file.type.startsWith('image/')) {
                                        photoInput.value = '';
                                        alert('File yang dipilih harus berupa gambar.');
                                        return;
                                   }

                                   if (file.size > 2 * 1024 * 1024) {
                                        photoInput.value = '';
                                        alert('Ukuran foto maksimal 2 MB.');
                                        return;
                                   }

                                   const reader = new FileReader();

                                   reader.onload = function(loadEvent) {
                                        photoPreviewImage.src =
                                             loadEvent.target.result;

                                        photoPreviewImage.style.display = 'block';
                                        photoPlaceholder.style.display = 'none';
                                   };

                                   reader.readAsDataURL(file);
                              }
                         );
                    }
               });
          </script>
     @endpush
@endsection
