@php
     /*
    |--------------------------------------------------------------------------
    | KONFIGURASI FORM PELANGGAN
    |--------------------------------------------------------------------------
    |
    | Partial ini dapat dipakai untuk halaman create maupun edit.
    |
    | Create:
    | @include('super-admin.customers.partials.form')
    |
    | Edit:
    | @include('super-admin.customers.partials.form', ['customer' => $customer])
    |
    */
     $customerModel = $customer ?? null;

     $isEdit = $customerModel instanceof \App\Models\Customer && $customerModel->exists;

     $customerTypeOptions = isset($customerTypeOptions)
         ? $customerTypeOptions
         : \App\Models\Customer::customerTypeOptions();

     $statusOptions = isset($statusOptions) ? $statusOptions : \App\Models\Customer::statusOptions();

     $defaultCustomerType = $isEdit ? (string) $customerModel->customer_type : \App\Models\Customer::TYPE_INDIVIDUAL;

     $defaultStatus = $isEdit ? (string) $customerModel->status : \App\Models\Customer::STATUS_ACTIVE;

     $selectedCustomerType = old('customer_type', $defaultCustomerType);

     $selectedStatus = old('status', $defaultStatus);

     $formAction = $isEdit
         ? route('super-admin.customers.update', $customerModel)
         : route('super-admin.customers.store');

     $cancelUrl = $isEdit ? route('super-admin.customers.show', $customerModel) : route('super-admin.customers.index');

     $submitLabel = $isEdit ? 'Simpan Perubahan' : 'Simpan Pelanggan';

     $submitIcon = $isEdit ? 'refresh-cw' : 'save';
@endphp

<form method="POST" action="{{ $formAction }}" id="customer-form" data-customer-form novalidate>
     @csrf

     @if ($isEdit)
          @method('PUT')
     @endif

     {{-- ================================================================
         IDENTITAS PELANGGAN
    ================================================================= --}}
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
                         Informasi utama untuk mengidentifikasi setiap pelanggan.
                    </p>
               </div>
          </header>

          <div class="customer-card-body">
               <div class="customer-section-note">
                    <i data-feather="{{ $isEdit ? 'clock' : 'info' }}"></i>

                    <span>
                         @if ($isEdit)
                              Data terakhir diperbarui pada
                              <strong>
                                   {{ optional($customerModel->updated_at)->format('d M Y H:i') ?? '-' }}
                                   WIB
                              </strong>.
                         @else
                              Kode pelanggan harus unik. Gunakan pola yang konsisten,
                              misalnya <strong>CUST-0001</strong>.
                         @endif
                    </span>
               </div>

               <div class="row g-4">
                    {{-- Kode pelanggan --}}
                    <div class="col-12 col-lg-4">
                         <label for="customer_code" class="form-label customer-form-label">
                              Kode Pelanggan
                              <span class="customer-required">*</span>
                         </label>

                         <div class="customer-input-shell">
                              <i data-feather="hash"></i>

                              <input type="text" id="customer_code" name="customer_code"
                                   value="{{ old('customer_code', data_get($customerModel, 'customer_code')) }}"
                                   maxlength="50"
                                   class="form-control customer-form-control @error('customer_code') is-invalid @enderror"
                                   placeholder="CUST-0001" autocomplete="off" aria-describedby="customer-code-help"
                                   required>
                         </div>

                         <div id="customer-code-help" class="customer-help">
                              Maksimal 50 karakter dan tidak boleh sama dengan pelanggan lain.
                         </div>

                         @error('customer_code')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Jenis pelanggan --}}
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
                                        <option value="{{ $value }}" @selected($selectedCustomerType === (string) $value)>
                                             {{ $label }}
                                        </option>
                                   @endforeach
                              </select>
                         </div>

                         @error('customer_type')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Status --}}
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
                                        <option value="{{ $value }}" @selected($selectedStatus === (string) $value)>
                                             {{ $label }}
                                        </option>
                                   @endforeach
                              </select>
                         </div>

                         @error('status')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Nama pelanggan --}}
                    <div class="col-12 col-lg-6">
                         <label for="name" class="form-label customer-form-label">
                              Nama Pelanggan / Penanggung Jawab
                              <span class="customer-required">*</span>
                         </label>

                         <div class="customer-input-shell">
                              <i data-feather="user"></i>

                              <input type="text" id="name" name="name"
                                   value="{{ old('name', data_get($customerModel, 'name')) }}"
                                   maxlength="150"
                                   class="form-control customer-form-control @error('name') is-invalid @enderror"
                                   placeholder="Masukkan nama lengkap" autocomplete="name" required>
                         </div>

                         @error('name')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Nama perusahaan --}}
                    <div class="col-12 col-lg-6" id="company-name-wrapper" data-company-field>
                         <label for="company_name" class="form-label customer-form-label">
                              Nama Perusahaan

                              <span class="customer-required" id="company-required-mark" data-company-required>
                                   *
                              </span>
                         </label>

                         <div class="customer-input-shell">
                              <i data-feather="briefcase"></i>

                              <input type="text" id="company_name" name="company_name"
                                   value="{{ old('company_name', data_get($customerModel, 'company_name')) }}"
                                   maxlength="150"
                                   class="form-control customer-form-control @error('company_name') is-invalid @enderror"
                                   placeholder="PT, CV, yayasan, atau nama badan usaha" autocomplete="organization">
                         </div>

                         <div class="customer-help">
                              Wajib diisi ketika jenis pelanggan adalah perusahaan.
                         </div>

                         @error('company_name')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>
               </div>
          </div>
     </section>

     {{-- ================================================================
         KONTAK DAN ADMINISTRASI
    ================================================================= --}}
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
                         Informasi komunikasi, alamat, dan identitas pajak pelanggan.
                    </p>
               </div>
          </header>

          <div class="customer-card-body">
               <div class="row g-4">
                    {{-- Telepon --}}
                    <div class="col-12 col-lg-6">
                         <label for="phone" class="form-label customer-form-label">
                              Nomor Telepon
                         </label>

                         <div class="customer-input-shell">
                              <i data-feather="phone"></i>

                              <input type="text" id="phone" name="phone"
                                   value="{{ old('phone', data_get($customerModel, 'phone')) }}"
                                   maxlength="30"
                                   class="form-control customer-form-control @error('phone') is-invalid @enderror"
                                   placeholder="Contoh: 081234567890" autocomplete="tel" inputmode="tel">
                         </div>

                         <div class="customer-help">
                              Dapat menggunakan angka, spasi, tanda tambah, tanda hubung,
                              titik, atau tanda kurung.
                         </div>

                         @error('phone')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12 col-lg-6">
                         <label for="email" class="form-label customer-form-label">
                              Alamat Email
                         </label>

                         <div class="customer-input-shell">
                              <i data-feather="mail"></i>

                              <input type="email" id="email" name="email"
                                   value="{{ old('email', data_get($customerModel, 'email')) }}"
                                   maxlength="150"
                                   class="form-control customer-form-control @error('email') is-invalid @enderror"
                                   placeholder="nama@example.com" autocomplete="email" inputmode="email">
                         </div>

                         @error('email')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Nomor pajak --}}
                    <div class="col-12 col-lg-6">
                         <label for="tax_number" class="form-label customer-form-label">
                              Nomor Pajak / NPWP
                         </label>

                         <div class="customer-input-shell">
                              <i data-feather="file-text"></i>

                              <input type="text" id="tax_number" name="tax_number"
                                   value="{{ old('tax_number', data_get($customerModel, 'tax_number')) }}"
                                   maxlength="100"
                                   class="form-control customer-form-control @error('tax_number') is-invalid @enderror"
                                   placeholder="Masukkan nomor pajak bila tersedia" autocomplete="off">
                         </div>

                         @error('tax_number')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                         <label for="address" class="form-label customer-form-label">
                              Alamat Lengkap
                         </label>

                         <div class="customer-input-shell is-textarea">
                              <i data-feather="map-pin"></i>

                              <textarea id="address" name="address" rows="4"
                                   class="form-control customer-form-control @error('address') is-invalid @enderror"
                                   placeholder="Masukkan alamat lengkap pelanggan" autocomplete="street-address">{{ old('address', data_get($customerModel, 'address')) }}</textarea>
                         </div>

                         @error('address')
                              <span class="customer-invalid-feedback" role="alert">
                                   {{ $message }}
                              </span>
                         @enderror
                    </div>
               </div>

               <div class="customer-form-actions">
                    <a href="{{ $cancelUrl }}" class="customer-button customer-button-secondary">
                         <i data-feather="x"></i>
                         <span>Batal</span>
                    </a>

                    <button type="submit" class="customer-button customer-button-primary" data-submit-button>
                         <i data-feather="{{ $submitIcon }}"></i>
                         <span data-submit-label>
                              {{ $submitLabel }}
                         </span>
                    </button>
               </div>
          </div>
     </section>
</form>

@once
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const form = document.querySelector('[data-customer-form]');

               if (!form) {
                    return;
               }

               const typeSelect = form.querySelector('#customer_type');
               const companyWrapper = form.querySelector('[data-company-field]');
               const companyInput = form.querySelector('#company_name');
               const companyRequiredMark = form.querySelector(
                    '[data-company-required]'
               );
               const customerCodeInput = form.querySelector('#customer_code');
               const submitButton = form.querySelector('[data-submit-button]');
               const submitLabel = form.querySelector('[data-submit-label]');

               /*
                * Nama perusahaan hanya ditampilkan dan diwajibkan ketika
                * customer_type bernilai "company".
                *
                * Nilainya tidak langsung dihapus ketika tipe diubah menjadi
                * individual agar input tidak hilang apabila pengguna berubah
                * pikiran sebelum formulir dikirim. Controller tetap menyimpan
                * company_name sebagai null untuk pelanggan individual.
                */
               const syncCompanyField = function() {
                    const isCompany = typeSelect?.value === 'company';

                    if (companyWrapper) {
                         companyWrapper.hidden = !isCompany;
                    }

                    if (companyInput) {
                         companyInput.required = isCompany;
                         companyInput.disabled = false;
                         companyInput.setAttribute(
                              'aria-required',
                              isCompany ? 'true' : 'false'
                         );
                    }

                    if (companyRequiredMark) {
                         companyRequiredMark.hidden = !isCompany;
                    }
               };

               /*
                * Menyamakan tampilan kode dengan normalisasi server:
                * trim dan huruf kapital.
                */
               customerCodeInput?.addEventListener('input', function() {
                    const cursorPosition = this.selectionStart;

                    this.value = this.value.toUpperCase();

                    if (
                         cursorPosition !== null &&
                         typeof this.setSelectionRange === 'function'
                    ) {
                         this.setSelectionRange(
                              cursorPosition,
                              cursorPosition
                         );
                    }
               });

               customerCodeInput?.addEventListener('blur', function() {
                    this.value = this.value.trim().toUpperCase();
               });

               typeSelect?.addEventListener('change', syncCompanyField);
               syncCompanyField();

               /*
                * Mencegah pengiriman formulir ganda.
                */
               form.addEventListener('submit', function() {
                    if (!submitButton) {
                         return;
                    }

                    submitButton.disabled = true;
                    submitButton.setAttribute('aria-disabled', 'true');

                    if (submitLabel) {
                         submitLabel.textContent = 'Menyimpan...';
                    }
               });

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }
          });
     </script>
@endonce
