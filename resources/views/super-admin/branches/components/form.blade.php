@php
     $editing = isset($branch) && $branch;
     $branchCode = $editing ? $branch->branch_code : null;
     $selectedManager = (string) old('manager_id', $editing ? $branch->manager_id : '');
     $selectedStatus = (string) old('status', $editing ? (int) $branch->status : 1);
     $submitText = $editing ? 'Simpan Perubahan' : 'Simpan Cabang';
@endphp

@once
     <style>
          .branch-form-partial {
               --bf-primary: #2563eb;
               --bf-primary-dark: #1d4ed8;
               --bf-primary-soft: #eff6ff;
               --bf-success: #16a34a;
               --bf-danger: #dc2626;
               --bf-text: #0f172a;
               --bf-muted: #64748b;
               --bf-border: #e2e8f0;
               color: var(--bf-text);
          }

          .branch-form-partial .bf-section {
               padding: 25px;
               border-bottom: 1px solid var(--bf-border);
          }

          .branch-form-partial .bf-section:last-of-type {
               border-bottom: 0;
          }

          .branch-form-partial .bf-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               margin-bottom: 22px;
          }

          .branch-form-partial .bf-heading-main {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .branch-form-partial .bf-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 43px;
               width: 43px;
               height: 43px;
               color: var(--bf-primary);
               font-size: 1.08rem;
               border-radius: 13px;
               background: var(--bf-primary-soft);
          }

          .branch-form-partial .bf-heading h2 {
               margin: 0 0 4px;
               font-size: 1.05rem;
               font-weight: 850;
          }

          .branch-form-partial .bf-heading p {
               margin: 0;
               color: var(--bf-muted);
               font-size: .82rem;
               line-height: 1.55;
          }

          .branch-form-partial .bf-required-note {
               color: var(--bf-muted);
               font-size: .74rem;
               white-space: nowrap;
          }

          .branch-form-partial .bf-required {
               color: var(--bf-danger);
          }

          .branch-form-partial .bf-label {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
               margin-bottom: 8px;
               color: #334155;
               font-size: .8rem;
               font-weight: 750;
          }

          .branch-form-partial .bf-label small {
               color: #94a3b8;
               font-size: .68rem;
               font-weight: 650;
          }

          .branch-form-partial .bf-input-wrap {
               position: relative;
          }

          .branch-form-partial .bf-leading-icon {
               position: absolute;
               top: 50%;
               left: 15px;
               z-index: 2;
               color: #94a3b8;
               font-size: 1rem;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .branch-form-partial .bf-textarea-icon {
               top: 16px;
               transform: none;
          }

          .branch-form-partial .bf-control,
          .branch-form-partial .bf-select {
               width: 100%;
               min-height: 50px;
               padding: 10px 14px 10px 43px;
               color: var(--bf-text);
               font-size: .86rem;
               border: 1px solid #cbd5e1;
               border-radius: 13px;
               outline: none;
               background: #fff;
               transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
          }

          .branch-form-partial textarea.bf-control {
               min-height: 118px;
               padding-top: 14px;
               resize: vertical;
          }

          .branch-form-partial .bf-control::placeholder {
               color: #a8b2c1;
          }

          .branch-form-partial .bf-control:hover,
          .branch-form-partial .bf-select:hover {
               border-color: #94a3b8;
          }

          .branch-form-partial .bf-control:focus,
          .branch-form-partial .bf-select:focus {
               border-color: var(--bf-primary);
               box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
          }

          .branch-form-partial .bf-control.is-invalid,
          .branch-form-partial .bf-select.is-invalid {
               border-color: #ef4444;
               background-color: #fffafa;
          }

          .branch-form-partial .bf-control[readonly] {
               color: #475569;
               cursor: not-allowed;
               background: #f8fafc;
          }

          .branch-form-partial .bf-error {
               display: flex;
               align-items: center;
               gap: 6px;
               margin-top: 7px;
               color: var(--bf-danger);
               font-size: .74rem;
               font-weight: 700;
          }

          .branch-form-partial .bf-help {
               margin-top: 7px;
               color: #94a3b8;
               font-size: .7rem;
               line-height: 1.55;
          }

          .branch-form-partial .bf-status-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 13px;
          }

          .branch-form-partial .bf-status-input {
               position: absolute;
               width: 1px;
               height: 1px;
               overflow: hidden;
               opacity: 0;
          }

          .branch-form-partial .bf-status-card {
               display: flex;
               align-items: center;
               gap: 12px;
               min-height: 72px;
               padding: 14px 15px;
               cursor: pointer;
               border: 1px solid var(--bf-border);
               border-radius: 15px;
               background: #fff;
               transition: .2s ease;
          }

          .branch-form-partial .bf-status-card:hover {
               border-color: #93c5fd;
               transform: translateY(-1px);
          }

          .branch-form-partial .bf-status-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               border-radius: 13px;
          }

          .branch-form-partial .bf-status-active .bf-status-icon {
               color: #15803d;
               background: #dcfce7;
          }

          .branch-form-partial .bf-status-inactive .bf-status-icon {
               color: #b91c1c;
               background: #fee2e2;
          }

          .branch-form-partial .bf-status-copy strong {
               display: block;
               font-size: .84rem;
               font-weight: 850;
          }

          .branch-form-partial .bf-status-copy small {
               display: block;
               margin-top: 2px;
               color: var(--bf-muted);
               font-size: .7rem;
               line-height: 1.4;
          }

          .branch-form-partial .bf-status-input:checked+.bf-status-card.bf-status-active {
               border-color: var(--bf-success);
               background: #f0fdf4;
               box-shadow: 0 0 0 1px var(--bf-success);
          }

          .branch-form-partial .bf-status-input:checked+.bf-status-card.bf-status-inactive {
               border-color: var(--bf-danger);
               background: #fff7f7;
               box-shadow: 0 0 0 1px var(--bf-danger);
          }

          .branch-form-partial .bf-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               padding: 20px 25px;
               border-top: 1px solid var(--bf-border);
               background: #f8fafc;
          }

          .branch-form-partial .bf-footer-info {
               display: flex;
               align-items: center;
               gap: 8px;
               color: var(--bf-muted);
               font-size: .74rem;
          }

          .branch-form-partial .bf-actions {
               display: flex;
               align-items: center;
               justify-content: flex-end;
               gap: 10px;
          }

          .branch-form-partial .bf-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 46px;
               padding: 11px 19px;
               border-radius: 13px;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               transition: .2s ease;
          }

          .branch-form-partial .bf-btn-cancel {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #fff;
          }

          .branch-form-partial .bf-btn-cancel:hover {
               color: var(--bf-text);
               border-color: #94a3b8;
               background: #f8fafc;
          }

          .branch-form-partial .bf-btn-save {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, var(--bf-primary), var(--bf-primary-dark));
               box-shadow: 0 10px 22px rgba(37, 99, 235, .22);
          }

          .branch-form-partial .bf-btn-save:hover {
               color: #fff;
               transform: translateY(-2px);
               box-shadow: 0 14px 26px rgba(37, 99, 235, .28);
          }

          .branch-form-partial .bf-btn-save:disabled {
               cursor: wait;
               opacity: .75;
               transform: none;
          }

          @media (max-width: 767.98px) {

               .branch-form-partial .bf-section,
               .branch-form-partial .bf-footer {
                    padding: 20px;
               }

               .branch-form-partial .bf-heading,
               .branch-form-partial .bf-footer {
                    align-items: stretch;
                    flex-direction: column;
               }

               .branch-form-partial .bf-status-grid {
                    grid-template-columns: 1fr;
               }

               .branch-form-partial .bf-actions,
               .branch-form-partial .bf-btn {
                    width: 100%;
               }

               .branch-form-partial .bf-btn {
                    flex: 1 1 0;
               }
          }

          @media (max-width: 479.98px) {
               .branch-form-partial .bf-actions {
                    flex-direction: column-reverse;
               }
          }
     </style>
@endonce

<div class="branch-form-partial">
     <section class="bf-section">
          <div class="bf-heading">
               <div class="bf-heading-main">
                    <span class="bf-heading-icon"><i class="bi bi-building"></i></span>
                    <div>
                         <h2>Identitas Cabang</h2>
                         <p>Masukkan nama cabang dan periksa kode cabang otomatis.</p>
                    </div>
               </div>
               <span class="bf-required-note"><span class="bf-required">*</span> Wajib diisi</span>
          </div>

          <div class="row g-3">
               <div class="col-md-5">
                    <label for="branch_code_display" class="bf-label">
                         <span>Kode Cabang</span>
                         <small>Otomatis</small>
                    </label>
                    <div class="bf-input-wrap">
                         <i class="bi bi-upc-scan bf-leading-icon"></i>
                         <input id="branch_code_display" type="text" class="bf-control"
                              value="{{ $branchCode ?: 'Dibuat otomatis setelah disimpan' }}" readonly
                              aria-readonly="true">
                    </div>
                    <div class="bf-help">Kode tidak dikirim dari form dan tidak dapat diubah manual.</div>
               </div>

               <div class="col-md-7">
                    <label for="branch_name" class="bf-label">
                         <span>Nama Cabang <span class="bf-required">*</span></span>
                         <small>Maksimal 100 karakter</small>
                    </label>
                    <div class="bf-input-wrap">
                         <i class="bi bi-buildings bf-leading-icon"></i>
                         <input id="branch_name" type="text" name="branch_name"
                              class="bf-control @error('branch_name') is-invalid @enderror"
                              value="{{ old('branch_name', $editing ? $branch->branch_name : '') }}"
                              placeholder="Contoh: Cabang Batam Center" maxlength="100" autocomplete="organization"
                              required>
                    </div>
                    @error('branch_name')
                         <div class="bf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
               </div>
          </div>
     </section>

     <section class="bf-section">
          <div class="bf-heading">
               <div class="bf-heading-main">
                    <span class="bf-heading-icon"><i class="bi bi-geo-alt"></i></span>
                    <div>
                         <h2>Lokasi dan Penanggung Jawab</h2>
                         <p>Tentukan alamat operasional dan kepala cabang.</p>
                    </div>
               </div>
          </div>

          <div class="row g-3">
               <div class="col-12">
                    <label for="address" class="bf-label">
                         <span>Alamat Cabang <span class="bf-required">*</span></span>
                    </label>
                    <div class="bf-input-wrap">
                         <i class="bi bi-pin-map bf-leading-icon bf-textarea-icon"></i>
                         <textarea id="address" name="address" class="bf-control @error('address') is-invalid @enderror"
                              placeholder="Masukkan alamat lengkap cabang" required>{{ old('address', $editing ? $branch->address : '') }}</textarea>
                    </div>
                    @error('address')
                         <div class="bf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-12">
                    <label for="manager_id" class="bf-label">
                         <span>Kepala Cabang</span>
                         <small>Opsional</small>
                    </label>
                    <div class="bf-input-wrap">
                         <i class="bi bi-person-badge bf-leading-icon"></i>
                         <select id="manager_id" name="manager_id"
                              class="bf-select @error('manager_id') is-invalid @enderror">
                              <option value="">Belum ditentukan</option>
                              @foreach ($managers ?? collect() as $manager)
                                   <option value="{{ $manager->id }}" @selected($selectedManager === (string) $manager->id)>
                                        {{ $manager->name }}@if (!empty($manager->email))
                                             — {{ $manager->email }}
                                        @endif
                                   </option>
                              @endforeach
                         </select>
                    </div>
                    @error('manager_id')
                         <div class="bf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                    @if (($managers ?? collect())->isEmpty())
                         <div class="bf-help text-warning-emphasis">
                              <i class="bi bi-exclamation-triangle me-1"></i>Belum ada pengguna aktif yang dapat
                              dipilih.
                         </div>
                    @endif
               </div>
          </div>
     </section>

     <section class="bf-section">
          <div class="bf-heading">
               <div class="bf-heading-main">
                    <span class="bf-heading-icon"><i class="bi bi-telephone"></i></span>
                    <div>
                         <h2>Kontak Operasional</h2>
                         <p>Gunakan nomor dan email resmi yang dapat dihubungi.</p>
                    </div>
               </div>
          </div>

          <div class="row g-3">
               <div class="col-md-6">
                    <label for="phone" class="bf-label">
                         <span>Nomor Telepon <span class="bf-required">*</span></span>
                         <small>Maksimal 20 karakter</small>
                    </label>
                    <div class="bf-input-wrap">
                         <i class="bi bi-telephone bf-leading-icon"></i>
                         <input id="phone" type="tel" name="phone"
                              class="bf-control @error('phone') is-invalid @enderror"
                              value="{{ old('phone', $editing ? $branch->phone : '') }}"
                              placeholder="Contoh: 0778 123456" maxlength="20" autocomplete="tel" required>
                    </div>
                    @error('phone')
                         <div class="bf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-6">
                    <label for="email" class="bf-label">
                         <span>Email Cabang <span class="bf-required">*</span></span>
                         <small>Maksimal 100 karakter</small>
                    </label>
                    <div class="bf-input-wrap">
                         <i class="bi bi-envelope bf-leading-icon"></i>
                         <input id="email" type="email" name="email"
                              class="bf-control @error('email') is-invalid @enderror"
                              value="{{ old('email', $editing ? $branch->email : '') }}"
                              placeholder="cabang@perusahaan.com" maxlength="100" autocomplete="email" required>
                    </div>
                    @error('email')
                         <div class="bf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
               </div>
          </div>
     </section>

     <section class="bf-section">
          <div class="bf-heading">
               <div class="bf-heading-main">
                    <span class="bf-heading-icon"><i class="bi bi-activity"></i></span>
                    <div>
                         <h2>Status Operasional</h2>
                         <p>Tentukan apakah cabang langsung aktif atau masih dalam persiapan.</p>
                    </div>
               </div>
          </div>

          <div class="bf-status-grid">
               <div>
                    <input id="status_active" class="bf-status-input" type="radio" name="status" value="1"
                         @checked($selectedStatus === '1') required>
                    <label for="status_active" class="bf-status-card bf-status-active">
                         <span class="bf-status-icon"><i class="bi bi-check-circle-fill"></i></span>
                         <span class="bf-status-copy">
                              <strong>Aktif</strong>
                              <small>Cabang dapat digunakan dalam proses operasional.</small>
                         </span>
                    </label>
               </div>

               <div>
                    <input id="status_inactive" class="bf-status-input" type="radio" name="status"
                         value="0" @checked($selectedStatus === '0') required>
                    <label for="status_inactive" class="bf-status-card bf-status-inactive">
                         <span class="bf-status-icon"><i class="bi bi-pause-circle-fill"></i></span>
                         <span class="bf-status-copy">
                              <strong>Nonaktif</strong>
                              <small>Cabang disimpan tetapi belum digunakan secara operasional.</small>
                         </span>
                    </label>
               </div>
          </div>

          @error('status')
               <div class="bf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
          @enderror
     </section>

     <footer class="bf-footer">
          <div class="bf-footer-info">
               <i class="bi bi-shield-check text-primary"></i>
               <span>Periksa kembali data sebelum disimpan.</span>
          </div>

          <div class="bf-actions">
               <a href="{{ route('super-admin.branches.index') }}" class="bf-btn bf-btn-cancel">
                    <i class="bi bi-x-lg"></i>Batal
               </a>
               <button type="submit" class="bf-btn bf-btn-save" data-branch-submit>
                    <i class="bi {{ $editing ? 'bi-check2-circle' : 'bi-floppy-fill' }}"></i>
                    <span>{{ $submitText }}</span>
               </button>
          </div>
     </footer>
</div>

@once
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               document.querySelectorAll('form').forEach(function(form) {
                    const submitButton = form.querySelector('[data-branch-submit]');

                    if (!submitButton) {
                         return;
                    }

                    form.addEventListener('submit', function() {
                         submitButton.disabled = true;
                         submitButton.querySelector('i').className = 'bi bi-arrow-repeat';
                         submitButton.querySelector('span').textContent = 'Menyimpan...';
                    });
               });
          });
     </script>
@endonce
