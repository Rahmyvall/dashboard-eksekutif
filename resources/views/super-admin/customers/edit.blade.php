@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
     @php
          $customerTypeOptions = isset($customerTypeOptions)
              ? $customerTypeOptions
              : \App\Models\Customer::customerTypeOptions();

          $statusOptions = isset($statusOptions) ? $statusOptions : \App\Models\Customer::statusOptions();

          $selectedType = old('customer_type', $customer->customer_type);
     @endphp


     <style>
          :root {
               --cust-primary: #4f46e5;
               --cust-primary-2: #7c3aed;
               --cust-cyan: #0891b2;
               --cust-success: #059669;
               --cust-warning: #d97706;
               --cust-danger: #e11d48;
               --cust-text: #26344d;
               --cust-muted: #718096;
               --cust-border: #e5eaf2;
               --cust-bg: #f7f9fc;
          }

          .customer-form-page,
          .customer-detail-page,
          .customer-form-page *,
          .customer-detail-page * {
               box-sizing: border-box;
          }

          .customer-form-page,
          .customer-detail-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 44px;
               background:
                    radial-gradient(circle at 3% 5%, rgba(99, 102, 241, .16), transparent 24%),
                    radial-gradient(circle at 97% 8%, rgba(6, 182, 212, .14), transparent 24%),
                    linear-gradient(145deg, #fcfdff 0%, #f8f7ff 52%, #f2fbff 100%);
          }

          .customer-shell {
               width: 100%;
               max-width: 1380px;
               margin: 0 auto;
          }

          .customer-page-hero {
               position: relative;
               display: flex;
               padding: 28px 30px;
               gap: 20px;
               align-items: center;
               justify-content: space-between;
               margin-bottom: 22px;
               overflow: hidden;
               color: #fff;
               border-radius: 25px;
               background:
                    radial-gradient(circle at 86% 20%, rgba(255, 255, 255, .28), transparent 20%),
                    linear-gradient(120deg, #4f46e5 0%, #7c3aed 48%, #0891b2 100%);
               box-shadow: 0 22px 48px rgba(79, 70, 229, .20);
          }

          .customer-page-hero::after {
               position: absolute;
               right: -48px;
               bottom: -82px;
               width: 190px;
               height: 190px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .11);
          }

          .customer-page-heading,
          .customer-page-actions {
               position: relative;
               z-index: 2;
          }

          .customer-page-heading {
               display: flex;
               min-width: 0;
               gap: 16px;
               align-items: center;
          }

          .customer-page-heading-icon {
               display: inline-flex;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               color: var(--cust-primary);
               align-items: center;
               justify-content: center;
               border-radius: 19px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 12px 28px rgba(49, 46, 129, .18);
          }

          .customer-page-heading-icon svg {
               width: 28px;
               height: 28px;
          }

          .customer-page-hero h1 {
               margin: 0;
               font-size: clamp(1.6rem, 2.4vw, 2.2rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .customer-page-hero p {
               max-width: 790px;
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .92);
               font-size: .91rem;
               line-height: 1.65;
          }

          .customer-page-actions {
               display: flex;
               flex: 0 0 auto;
               gap: 10px;
          }

          .customer-hero-link {
               display: inline-flex;
               min-height: 45px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #fff;
               font-size: .82rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .38);
               border-radius: 13px;
               background: rgba(255, 255, 255, .14);
               transition: .2s ease;
               backdrop-filter: blur(10px);
          }

          .customer-hero-link.is-light {
               color: #4338ca;
               border-color: rgba(255, 255, 255, .84);
               background: rgba(255, 255, 255, .96);
          }

          .customer-hero-link:hover {
               color: #fff;
               text-decoration: none;
               background: rgba(255, 255, 255, .23);
               transform: translateY(-2px);
          }

          .customer-hero-link.is-light:hover {
               color: #312e81;
               background: #fff;
          }

          .customer-hero-link svg {
               width: 16px;
               height: 16px;
          }

          .customer-alert {
               display: flex;
               padding: 15px 17px;
               gap: 11px;
               align-items: flex-start;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 15px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .customer-alert-danger {
               color: #b91c1c;
               border-left: 5px solid #ef4444;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .customer-alert-success {
               color: #047857;
               border-left: 5px solid #10b981;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .customer-alert svg {
               flex: 0 0 auto;
               width: 19px;
               height: 19px;
               margin-top: 1px;
          }

          .customer-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 23px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 44px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .customer-card+.customer-card {
               margin-top: 20px;
          }

          .customer-card-header {
               display: flex;
               padding: 20px 23px;
               gap: 15px;
               align-items: center;
               justify-content: space-between;
               border-bottom: 1px solid #edf1f6;
               background: linear-gradient(90deg, #fff 0%, #faf8ff 52%, #f0fbff 100%);
          }

          .customer-card-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin: 0;
               color: var(--cust-text);
               font-size: 1.02rem;
               font-weight: 830;
          }

          .customer-card-title-icon {
               display: inline-flex;
               flex: 0 0 40px;
               width: 40px;
               height: 40px;
               color: var(--cust-primary);
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .customer-card-title-icon svg {
               width: 19px;
               height: 19px;
          }

          .customer-card-subtitle {
               margin: 5px 0 0 50px;
               color: var(--cust-muted);
               font-size: .78rem;
          }

          .customer-card-body {
               padding: 24px;
          }

          .customer-form-label {
               margin-bottom: 7px;
               color: #52627a;
               font-size: .78rem;
               font-weight: 800;
               letter-spacing: .02em;
          }

          .customer-required {
               color: var(--cust-danger);
          }

          .customer-form-control,
          .customer-form-select {
               min-height: 47px;
               color: var(--cust-text);
               font-size: .88rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #fff;
               box-shadow: none;
          }

          textarea.customer-form-control {
               min-height: 118px;
               resize: vertical;
          }

          .customer-form-control:focus,
          .customer-form-select:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .11);
          }

          .customer-form-control.is-invalid,
          .customer-form-select.is-invalid {
               border-color: #fb7185;
               background-image: none;
          }

          .customer-help {
               margin-top: 6px;
               color: #8792a5;
               font-size: .72rem;
               line-height: 1.45;
          }

          .customer-invalid-feedback {
               display: block;
               margin-top: 6px;
               color: #dc2626;
               font-size: .75rem;
               font-weight: 700;
          }

          .customer-input-shell {
               position: relative;
          }

          .customer-input-shell>svg {
               position: absolute;
               z-index: 2;
               top: 50%;
               left: 14px;
               width: 16px;
               height: 16px;
               color: #818cf8;
               pointer-events: none;
               transform: translateY(-50%);
          }

          .customer-input-shell.is-textarea>svg {
               top: 16px;
               transform: none;
          }

          .customer-input-shell .customer-form-control,
          .customer-input-shell .customer-form-select {
               padding-left: 41px;
          }

          .customer-section-note {
               display: flex;
               padding: 14px 15px;
               gap: 10px;
               align-items: flex-start;
               margin-bottom: 20px;
               color: #475569;
               font-size: .79rem;
               line-height: 1.58;
               border: 1px solid #c7d2fe;
               border-radius: 14px;
               background: linear-gradient(135deg, #eef2ff, #f0f9ff);
          }

          .customer-section-note svg {
               flex: 0 0 auto;
               width: 17px;
               height: 17px;
               margin-top: 2px;
               color: #4f46e5;
          }

          .customer-form-actions {
               display: flex;
               padding-top: 22px;
               gap: 10px;
               align-items: center;
               justify-content: flex-end;
               margin-top: 22px;
               border-top: 1px solid #edf1f6;
          }

          .customer-button {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 17px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .customer-button svg {
               width: 16px;
               height: 16px;
          }

          .customer-button-primary {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #7c3aed, #0891b2);
               box-shadow: 0 10px 23px rgba(99, 102, 241, .22);
          }

          .customer-button-primary:hover {
               color: #fff;
               text-decoration: none;
               transform: translateY(-2px);
               box-shadow: 0 14px 27px rgba(99, 102, 241, .28);
          }

          .customer-button-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .customer-button-secondary:hover {
               color: #334155;
               text-decoration: none;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          .customer-button-danger {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .customer-button-danger:hover {
               color: #9f1239;
               text-decoration: none;
               background: #ffe4e6;
               transform: translateY(-2px);
          }

          .customer-summary {
               display: grid;
               grid-template-columns: minmax(250px, 1.1fr) minmax(250px, .9fr);
               gap: 20px;
          }

          .customer-profile-panel {
               padding: 26px;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 22px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 17px 38px rgba(51, 65, 85, .08);
          }

          .customer-profile-head {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .customer-avatar-large {
               display: inline-flex;
               flex: 0 0 74px;
               width: 74px;
               height: 74px;
               color: #fff;
               font-size: 1.35rem;
               font-weight: 850;
               align-items: center;
               justify-content: center;
               border-radius: 22px;
               background:
                    radial-gradient(circle at 28% 20%, rgba(255, 255, 255, .28), transparent 30%),
                    linear-gradient(135deg, #4f46e5, #8b5cf6, #06b6d4);
               box-shadow: 0 13px 28px rgba(99, 102, 241, .20);
          }

          .customer-profile-name {
               margin: 0;
               color: var(--cust-text);
               font-size: 1.3rem;
               font-weight: 850;
               line-height: 1.25;
          }

          .customer-profile-company {
               margin-top: 4px;
               color: #64748b;
               font-size: .84rem;
               font-weight: 650;
          }

          .customer-profile-code {
               display: inline-flex;
               padding: 5px 9px;
               margin-top: 8px;
               color: #5b21b6;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .04em;
               border: 1px solid #ddd6fe;
               border-radius: 8px;
               background: #f5f3ff;
          }

          .customer-badges {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               margin-top: 20px;
          }

          .customer-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 7px;
               align-items: center;
               font-size: .73rem;
               font-weight: 820;
               border-radius: 999px;
          }

          .customer-badge svg {
               width: 14px;
               height: 14px;
          }

          .customer-badge-company {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .customer-badge-individual {
               color: #7e22ce;
               border: 1px solid #e9d5ff;
               background: #faf5ff;
          }

          .customer-badge-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .customer-badge-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .customer-contact-actions {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 10px;
               margin-top: 22px;
          }

          .customer-contact-link {
               display: flex;
               min-height: 46px;
               padding: 10px 12px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #475569;
               font-size: .79rem;
               font-weight: 780;
               text-decoration: none;
               border: 1px solid #dbe3ef;
               border-radius: 12px;
               background: #fff;
               transition: .2s ease;
          }

          .customer-contact-link:hover {
               color: #4f46e5;
               text-decoration: none;
               border-color: #c7d2fe;
               background: #eef2ff;
               transform: translateY(-2px);
          }

          .customer-contact-link.is-disabled {
               color: #a8b2c1;
               pointer-events: none;
               background: #f8fafc;
          }

          .customer-contact-link svg {
               width: 16px;
               height: 16px;
          }

          .customer-info-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .customer-info-item {
               min-height: 108px;
               padding: 16px;
               border: 1px solid #e5eaf2;
               border-radius: 15px;
               background: #fff;
          }

          .customer-info-label {
               display: flex;
               gap: 7px;
               align-items: center;
               margin-bottom: 9px;
               color: #718096;
               font-size: .72rem;
               font-weight: 820;
               letter-spacing: .045em;
               text-transform: uppercase;
          }

          .customer-info-label svg {
               width: 14px;
               height: 14px;
               color: #818cf8;
          }

          .customer-info-value {
               color: #334155;
               font-size: .88rem;
               font-weight: 700;
               line-height: 1.6;
               word-break: break-word;
          }

          .customer-info-value.is-empty {
               color: #a8b2c1;
               font-style: italic;
               font-weight: 600;
          }

          .customer-wide {
               grid-column: 1 / -1;
          }

          @media (max-width: 991.98px) {
               .customer-page-hero {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .customer-page-actions {
                    width: 100%;
               }

               .customer-hero-link {
                    flex: 1 1 auto;
               }

               .customer-summary {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767.98px) {

               .customer-form-page,
               .customer-detail-page {
                    padding: 20px 12px 34px;
               }

               .customer-page-hero {
                    padding: 23px 20px;
               }

               .customer-page-heading {
                    align-items: flex-start;
               }

               .customer-page-heading-icon {
                    flex-basis: 52px;
                    width: 52px;
                    height: 52px;
                    border-radius: 16px;
               }

               .customer-page-actions,
               .customer-form-actions {
                    flex-direction: column;
                    align-items: stretch;
               }

               .customer-card-body {
                    padding: 20px;
               }

               .customer-button,
               .customer-hero-link {
                    width: 100%;
               }

               .customer-info-grid,
               .customer-contact-actions {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 479.98px) {
               .customer-page-heading {
                    flex-direction: column;
               }

               .customer-profile-head {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }
     </style>


     <div class="customer-form-page">
          <div class="customer-shell">
               <section class="customer-page-hero">
                    <div class="customer-page-heading">
                         <span class="customer-page-heading-icon">
                              <i data-feather="edit-3"></i>
                         </span>

                         <div>
                              <h1>Edit Pelanggan</h1>
                              <p>
                                   Perbarui informasi pelanggan
                                   <strong>{{ $customer->name }}</strong>
                                   dengan tetap menjaga kode pelanggan tetap unik.
                              </p>
                         </div>
                    </div>

                    <div class="customer-page-actions">
                         <a href="{{ route('super-admin.customers.show', $customer) }}" class="customer-hero-link is-light">
                              <i data-feather="eye"></i>
                              <span>Lihat Detail</span>
                         </a>

                         <a href="{{ route('super-admin.customers.index') }}" class="customer-hero-link">
                              <i data-feather="arrow-left"></i>
                              <span>Kembali</span>
                         </a>
                    </div>
               </section>

               @if ($errors->any())
                    <div class="customer-alert customer-alert-danger" role="alert">
                         <i data-feather="alert-circle"></i>

                         <div>
                              <strong>Perubahan belum dapat disimpan.</strong>
                              Periksa kembali kolom yang ditandai pada formulir.
                         </div>
                    </div>
               @endif

               <form method="POST" action="{{ route('super-admin.customers.update', $customer) }}" id="customer-form"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <section class="customer-card">
                         <header class="customer-card-header">
                              <div>
                                   <h2 class="customer-card-title">
                                        <span class="customer-card-title-icon">
                                             <i data-feather="user"></i>
                                        </span>
                                        <span>Identitas Pelanggan</span>
                                   </h2>

                                   <p class="customer-card-subtitle">
                                        Ubah informasi utama pelanggan sesuai data terbaru.
                                   </p>
                              </div>
                         </header>

                         <div class="customer-card-body">
                              <div class="customer-section-note">
                                   <i data-feather="clock"></i>
                                   <span>
                                        Terakhir diperbarui:
                                        <strong>
                                             {{ optional($customer->updated_at)->format('d M Y H:i') ?? '-' }} WIB
                                        </strong>
                                   </span>
                              </div>

                              <div class="row g-4">
                                   <div class="col-12 col-lg-4">
                                        <label for="customer_code" class="form-label customer-form-label">
                                             Kode Pelanggan
                                             <span class="customer-required">*</span>
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="hash"></i>

                                             <input type="text" id="customer_code" name="customer_code"
                                                  value="{{ old('customer_code', $customer->customer_code) }}" maxlength="50"
                                                  class="form-control customer-form-control @error('customer_code') is-invalid @enderror"
                                                  autocomplete="off" required>
                                        </div>

                                        @error('customer_code')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12 col-lg-4">
                                        <label for="customer_type" class="form-label customer-form-label">
                                             Jenis Pelanggan
                                             <span class="customer-required">*</span>
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="users"></i>

                                             <select id="customer_type" name="customer_type"
                                                  class="form-select customer-form-select @error('customer_type') is-invalid @enderror"
                                                  required>
                                                  @foreach ($customerTypeOptions as $value => $label)
                                                       <option value="{{ $value }}" @selected($selectedType === (string) $value)>
                                                            {{ $label }}
                                                       </option>
                                                  @endforeach
                                             </select>
                                        </div>

                                        @error('customer_type')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12 col-lg-4">
                                        <label for="status" class="form-label customer-form-label">
                                             Status Pelanggan
                                             <span class="customer-required">*</span>
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="activity"></i>

                                             <select id="status" name="status"
                                                  class="form-select customer-form-select @error('status') is-invalid @enderror"
                                                  required>
                                                  @foreach ($statusOptions as $value => $label)
                                                       <option value="{{ $value }}" @selected(old('status', $customer->status) === (string) $value)>
                                                            {{ $label }}
                                                       </option>
                                                  @endforeach
                                             </select>
                                        </div>

                                        @error('status')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12 col-lg-6">
                                        <label for="name" class="form-label customer-form-label">
                                             Nama Pelanggan / Penanggung Jawab
                                             <span class="customer-required">*</span>
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="user"></i>

                                             <input type="text" id="name" name="name"
                                                  value="{{ old('name', $customer->name) }}" maxlength="150"
                                                  class="form-control customer-form-control @error('name') is-invalid @enderror"
                                                  autocomplete="name" required>
                                        </div>

                                        @error('name')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12 col-lg-6" id="company-name-wrapper">
                                        <label for="company_name" class="form-label customer-form-label">
                                             Nama Perusahaan
                                             <span class="customer-required" id="company-required-mark">*</span>
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="briefcase"></i>

                                             <input type="text" id="company_name" name="company_name"
                                                  value="{{ old('company_name', $customer->company_name) }}" maxlength="150"
                                                  class="form-control customer-form-control @error('company_name') is-invalid @enderror">
                                        </div>

                                        <div class="customer-help">
                                             Wajib untuk pelanggan perusahaan.
                                        </div>

                                        @error('company_name')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>
                              </div>
                         </div>
                    </section>

                    <section class="customer-card">
                         <header class="customer-card-header">
                              <div>
                                   <h2 class="customer-card-title">
                                        <span class="customer-card-title-icon">
                                             <i data-feather="phone-call"></i>
                                        </span>
                                        <span>Kontak dan Administrasi</span>
                                   </h2>

                                   <p class="customer-card-subtitle">
                                        Perbarui kontak, alamat, dan nomor pajak pelanggan.
                                   </p>
                              </div>
                         </header>

                         <div class="customer-card-body">
                              <div class="row g-4">
                                   <div class="col-12 col-lg-6">
                                        <label for="phone" class="form-label customer-form-label">
                                             Nomor Telepon
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="phone"></i>

                                             <input type="text" id="phone" name="phone"
                                                  value="{{ old('phone', $customer->phone) }}" maxlength="30"
                                                  class="form-control customer-form-control @error('phone') is-invalid @enderror"
                                                  autocomplete="tel">
                                        </div>

                                        @error('phone')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12 col-lg-6">
                                        <label for="email" class="form-label customer-form-label">
                                             Alamat Email
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="mail"></i>

                                             <input type="email" id="email" name="email"
                                                  value="{{ old('email', $customer->email) }}" maxlength="150"
                                                  class="form-control customer-form-control @error('email') is-invalid @enderror"
                                                  autocomplete="email">
                                        </div>

                                        @error('email')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12 col-lg-6">
                                        <label for="tax_number" class="form-label customer-form-label">
                                             Nomor Pajak / NPWP
                                        </label>

                                        <div class="customer-input-shell">
                                             <i data-feather="file-text"></i>

                                             <input type="text" id="tax_number" name="tax_number"
                                                  value="{{ old('tax_number', $customer->tax_number) }}" maxlength="100"
                                                  class="form-control customer-form-control @error('tax_number') is-invalid @enderror">
                                        </div>

                                        @error('tax_number')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>

                                   <div class="col-12">
                                        <label for="address" class="form-label customer-form-label">
                                             Alamat Lengkap
                                        </label>

                                        <div class="customer-input-shell is-textarea">
                                             <i data-feather="map-pin"></i>

                                             <textarea id="address" name="address"
                                                  class="form-control customer-form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>
                                        </div>

                                        @error('address')
                                             <span class="customer-invalid-feedback">
                                                  {{ $message }}
                                             </span>
                                        @enderror
                                   </div>
                              </div>

                              <div class="customer-form-actions">
                                   <a href="{{ route('super-admin.customers.show', $customer) }}"
                                        class="customer-button customer-button-secondary">
                                        <i data-feather="x"></i>
                                        <span>Batal</span>
                                   </a>

                                   <button type="submit" class="customer-button customer-button-primary">
                                        <i data-feather="save"></i>
                                        <span>Simpan Perubahan</span>
                                   </button>
                              </div>
                         </div>
                    </section>
               </form>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const typeSelect = document.getElementById('customer_type');
                    const companyWrapper = document.getElementById('company-name-wrapper');
                    const companyInput = document.getElementById('company_name');
                    const requiredMark = document.getElementById('company-required-mark');

                    const syncCompanyField = function() {
                         const isCompany = typeSelect?.value === 'company';

                         if (companyWrapper) {
                              companyWrapper.style.display = isCompany ? '' : 'none';
                         }

                         if (companyInput) {
                              companyInput.required = isCompany;

                              if (!isCompany) {
                                   companyInput.value = '';
                              }
                         }

                         if (requiredMark) {
                              requiredMark.style.display = isCompany ? '' : 'none';
                         }
                    };

                    typeSelect?.addEventListener('change', syncCompanyField);
                    syncCompanyField();

                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
