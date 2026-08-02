@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
     @php
          $authUser = auth()->user();

          $hasRole = static function (string $role) use ($authUser): bool {
              return $authUser && method_exists($authUser, 'hasRole') && $authUser->hasRole($role);
          };

          $canManageCustomers = $hasRole('super_admin') || $hasRole('admin_pelayanan') || $hasRole('admin_operasional');

          $isCompany = $customer->customer_type === \App\Models\Customer::TYPE_COMPANY;

          $isActive = $customer->status === \App\Models\Customer::STATUS_ACTIVE;

          $typeLabel = $isCompany ? 'Perusahaan' : 'Perorangan';

          $statusLabel = $isActive ? 'Aktif' : 'Tidak Aktif';

          $initials = \Illuminate\Support\Str::of((string) $customer->name)
              ->explode(' ')
              ->filter()
              ->take(2)
              ->map(
                  static fn($word): string => \Illuminate\Support\Str::upper(
                      \Illuminate\Support\Str::substr((string) $word, 0, 1),
                  ),
              )
              ->implode('');

          $initials = $initials !== '' ? $initials : 'CU';
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


     <div class="customer-detail-page">
          <div class="customer-shell">
               <section class="customer-page-hero">
                    <div class="customer-page-heading">
                         <span class="customer-page-heading-icon">
                              <i data-feather="user"></i>
                         </span>

                         <div>
                              <h1>Detail Pelanggan</h1>
                              <p>
                                   Informasi lengkap pelanggan, kontak, administrasi,
                                   status, dan riwayat waktu pencatatan data.
                              </p>
                         </div>
                    </div>

                    <div class="customer-page-actions">
                         @if ($canManageCustomers)
                              <a href="{{ route('super-admin.customers.edit', $customer) }}"
                                   class="customer-hero-link is-light">
                                   <i data-feather="edit-3"></i>
                                   <span>Edit Pelanggan</span>
                              </a>
                         @endif

                         <a href="{{ route('super-admin.customers.index') }}" class="customer-hero-link">
                              <i data-feather="arrow-left"></i>
                              <span>Kembali ke Daftar</span>
                         </a>
                    </div>
               </section>

               @if (session('success'))
                    <div class="customer-alert customer-alert-success" role="alert">
                         <i data-feather="check-circle"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="customer-alert customer-alert-danger" role="alert">
                         <i data-feather="alert-circle"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <div class="customer-summary">
                    <section class="customer-profile-panel">
                         <div class="customer-profile-head">
                              <span class="customer-avatar-large">
                                   {{ $initials }}
                              </span>

                              <div>
                                   <h2 class="customer-profile-name">
                                        {{ $customer->name }}
                                   </h2>

                                   @if ($isCompany && filled($customer->company_name))
                                        <div class="customer-profile-company">
                                             {{ $customer->company_name }}
                                        </div>
                                   @endif

                                   <span class="customer-profile-code">
                                        {{ $customer->customer_code }}
                                   </span>
                              </div>
                         </div>

                         <div class="customer-badges">
                              <span
                                   class="customer-badge {{ $isCompany ? 'customer-badge-company' : 'customer-badge-individual' }}">
                                   <i data-feather="{{ $isCompany ? 'briefcase' : 'user' }}"></i>
                                   {{ $typeLabel }}
                              </span>

                              <span
                                   class="customer-badge {{ $isActive ? 'customer-badge-active' : 'customer-badge-inactive' }}">
                                   <i data-feather="{{ $isActive ? 'check-circle' : 'slash' }}"></i>
                                   {{ $statusLabel }}
                              </span>
                         </div>

                         <div class="customer-contact-actions">
                              @if (filled($customer->phone))
                                   <a href="tel:{{ $customer->phone }}" class="customer-contact-link">
                                        <i data-feather="phone"></i>
                                        <span>Hubungi Telepon</span>
                                   </a>
                              @else
                                   <span class="customer-contact-link is-disabled">
                                        <i data-feather="phone-off"></i>
                                        <span>Telepon Kosong</span>
                                   </span>
                              @endif

                              @if (filled($customer->email))
                                   <a href="mailto:{{ $customer->email }}" class="customer-contact-link">
                                        <i data-feather="mail"></i>
                                        <span>Kirim Email</span>
                                   </a>
                              @else
                                   <span class="customer-contact-link is-disabled">
                                        <i data-feather="mail"></i>
                                        <span>Email Kosong</span>
                                   </span>
                              @endif
                         </div>

                         @if ($canManageCustomers)
                              <div class="customer-form-actions">
                                   <a href="{{ route('super-admin.customers.edit', $customer) }}"
                                        class="customer-button customer-button-primary">
                                        <i data-feather="edit-3"></i>
                                        <span>Edit Data</span>
                                   </a>

                                   <form method="POST" action="{{ route('super-admin.customers.destroy', $customer) }}"
                                        onsubmit="return confirm('Yakin ingin memindahkan pelanggan ini ke sampah?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="customer-button customer-button-danger">
                                             <i data-feather="trash-2"></i>
                                             <span>Hapus</span>
                                        </button>
                                   </form>
                              </div>
                         @endif
                    </section>

                    <section class="customer-card">
                         <header class="customer-card-header">
                              <div>
                                   <h2 class="customer-card-title">
                                        <span class="customer-card-title-icon">
                                             <i data-feather="info"></i>
                                        </span>
                                        <span>Informasi Pelanggan</span>
                                   </h2>

                                   <p class="customer-card-subtitle">
                                        Data kontak dan administrasi pelanggan.
                                   </p>
                              </div>
                         </header>

                         <div class="customer-card-body">
                              <div class="customer-info-grid">
                                   <article class="customer-info-item">
                                        <div class="customer-info-label">
                                             <i data-feather="phone"></i>
                                             Nomor Telepon
                                        </div>

                                        <div class="customer-info-value {{ filled($customer->phone) ? '' : 'is-empty' }}">
                                             {{ filled($customer->phone) ? $customer->phone : 'Belum tersedia' }}
                                        </div>
                                   </article>

                                   <article class="customer-info-item">
                                        <div class="customer-info-label">
                                             <i data-feather="mail"></i>
                                             Alamat Email
                                        </div>

                                        <div class="customer-info-value {{ filled($customer->email) ? '' : 'is-empty' }}">
                                             {{ filled($customer->email) ? $customer->email : 'Belum tersedia' }}
                                        </div>
                                   </article>

                                   <article class="customer-info-item">
                                        <div class="customer-info-label">
                                             <i data-feather="file-text"></i>
                                             Nomor Pajak
                                        </div>

                                        <div
                                             class="customer-info-value {{ filled($customer->tax_number) ? '' : 'is-empty' }}">
                                             {{ filled($customer->tax_number) ? $customer->tax_number : 'Belum tersedia' }}
                                        </div>
                                   </article>

                                   <article class="customer-info-item">
                                        <div class="customer-info-label">
                                             <i data-feather="briefcase"></i>
                                             Nama Perusahaan
                                        </div>

                                        <div
                                             class="customer-info-value {{ filled($customer->company_name) ? '' : 'is-empty' }}">
                                             {{ filled($customer->company_name) ? $customer->company_name : 'Tidak berlaku' }}
                                        </div>
                                   </article>

                                   <article class="customer-info-item customer-wide">
                                        <div class="customer-info-label">
                                             <i data-feather="map-pin"></i>
                                             Alamat Lengkap
                                        </div>

                                        <div class="customer-info-value {{ filled($customer->address) ? '' : 'is-empty' }}">
                                             {{ filled($customer->address) ? $customer->address : 'Alamat belum tersedia' }}
                                        </div>
                                   </article>
                              </div>
                         </div>
                    </section>
               </div>

               <section class="customer-card">
                    <header class="customer-card-header">
                         <div>
                              <h2 class="customer-card-title">
                                   <span class="customer-card-title-icon">
                                        <i data-feather="clock"></i>
                                   </span>
                                   <span>Riwayat Data</span>
                              </h2>

                              <p class="customer-card-subtitle">
                                   Waktu pembuatan dan pembaruan terakhir data pelanggan.
                              </p>
                         </div>
                    </header>

                    <div class="customer-card-body">
                         <div class="customer-info-grid">
                              <article class="customer-info-item">
                                   <div class="customer-info-label">
                                        <i data-feather="calendar"></i>
                                        Dibuat Pada
                                   </div>

                                   <div class="customer-info-value">
                                        {{ optional($customer->created_at)->format('d M Y, H:i') ?? '-' }} WIB
                                   </div>
                              </article>

                              <article class="customer-info-item">
                                   <div class="customer-info-label">
                                        <i data-feather="refresh-cw"></i>
                                        Diperbarui Pada
                                   </div>

                                   <div class="customer-info-value">
                                        {{ optional($customer->updated_at)->format('d M Y, H:i') ?? '-' }} WIB
                                   </div>
                              </article>
                         </div>
                    </div>
               </section>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
