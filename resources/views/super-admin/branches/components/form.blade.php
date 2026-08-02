@php
     /*
      |--------------------------------------------------------------------------
      | Shared Branch Form
      |--------------------------------------------------------------------------
      | Partial ini dapat digunakan pada create.blade.php dan edit.blade.php.
      |
      | Contoh create:
      | @include('branches.form', ['submitLabel' => 'Ajukan Cabang'])
      |
      | Contoh edit:
      | @include('branches.form', [
      |     'branch' => $branch,
      |     'submitLabel' => 'Simpan Perubahan',
      | ])
      */

     $branchModel = $branch ?? null;
     $isEditMode = (bool) ($branchModel?->exists ?? false);

     $selectedManager = (string) old(
         'manager_id',
         $isEditMode ? ($branchModel->manager_id ?? '') : ''
     );

     $selectedStatus = (string) old(
         'status',
         $isEditMode ? (string) ($branchModel->status ?? '1') : '1'
     );

     $cancelUrl = $cancelUrl ?? route('branches.index');
     $submitLabel = $submitLabel ?? ($isEditMode ? 'Simpan Perubahan' : 'Ajukan Cabang');
     $submitLoadingLabel = $submitLoadingLabel ?? ($isEditMode ? 'Menyimpan...' : 'Mengajukan...');
     $submitIcon = $submitIcon ?? ($isEditMode ? 'bi-save2-fill' : 'bi-send-check-fill');

     $footerNote = $footerNote ?? (
         $isEditMode
              ? 'Perubahan akan diproses sesuai kebijakan persetujuan yang berlaku.'
              : 'Kode cabang dibuat oleh server dan tidak dapat diubah secara manual.'
     );
@endphp

@once
     <style>
          .branch-form-ui {
               --bf-primary: #159f93;
               --bf-primary-hover: #0f887e;
               --bf-primary-soft: #e9faf7;
               --bf-heading: #176b68;
               --bf-text: #304852;
               --bf-muted: #6c828b;
               --bf-border: #d2e6e4;
               --bf-border-strong: #9fd9d3;
               --bf-surface: #ffffff;
               --bf-surface-soft: #f7fcfb;
               --bf-danger: #c84b57;
               width: 100%;
               color: var(--bf-text);
          }

          .branch-form-ui,
          .branch-form-ui * {
               box-sizing: border-box;
          }

          .branch-form-card {
               width: 100%;
               overflow: hidden;
               border: 1px solid var(--bf-border);
               border-radius: 20px;
               background: var(--bf-surface);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .065);
          }

          .branch-form-section {
               padding: 28px 30px;
               border-bottom: 1px solid #e5efee;
               background: #ffffff;
          }

          .branch-form-section:last-of-type {
               border-bottom: 0;
          }

          .branch-form-heading {
               display: flex;
               align-items: flex-start;
               gap: 12px;
               margin-bottom: 20px;
               padding-bottom: 14px;
               border-bottom: 1px dashed #d8e9e7;
          }

          .branch-form-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               color: #128579;
               font-size: 1rem;
               border: 1px solid #bde7e2;
               border-radius: 13px;
               background: #effbf9;
          }

          .branch-form-heading h2 {
               margin: 0 0 4px;
               color: var(--bf-heading) !important;
               font-size: 1.05rem;
               font-weight: 850;
               line-height: 1.35;
          }

          .branch-form-heading p {
               margin: 0;
               color: var(--bf-muted) !important;
               font-size: .76rem;
               line-height: 1.55;
          }

          .branch-form-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 20px;
          }

          .branch-form-field {
               min-width: 0;
          }

          .branch-form-field.full {
               grid-column: 1 / -1;
          }

          .branch-form-label {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
               margin-bottom: 7px;
               color: #39545e !important;
               font-size: .76rem;
               font-weight: 800;
          }

          .branch-form-label small {
               color: #879aa1 !important;
               font-size: .66rem;
               font-weight: 700;
          }

          .branch-required {
               color: #c84b57;
          }

          .branch-form-control {
               display: block;
               width: 100%;
               min-height: 47px;
               padding: 10px 13px;
               color: #2e4650 !important;
               font-size: .82rem;
               line-height: 1.5;
               border: 1px solid #cbdedc;
               border-radius: 12px;
               outline: 0;
               background: #ffffff;
               transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
          }

          .branch-form-control::placeholder {
               color: #9aabb1;
               opacity: 1;
          }

          .branch-form-control:hover {
               border-color: var(--bf-border-strong);
          }

          .branch-form-control:focus {
               border-color: #36b9ad;
               background: #fcfffe;
               box-shadow: 0 0 0 4px rgba(45, 212, 191, .13);
          }

          textarea.branch-form-control {
               min-height: 116px;
               resize: vertical;
          }

          select.branch-form-control {
               cursor: pointer;
          }

          .branch-form-control[readonly] {
               padding-right: 92px;
               color: #176b68 !important;
               font-weight: 850;
               letter-spacing: .035em;
               cursor: default;
               border-color: #93ddd5;
               border-style: dashed;
               background: #effbf9;
          }

          .branch-form-control.is-invalid {
               border-color: #e36a74;
               background: #fffdfd;
               box-shadow: 0 0 0 4px rgba(227, 106, 116, .08);
          }

          .branch-form-error {
               display: flex;
               align-items: flex-start;
               gap: 5px;
               margin-top: 6px;
               color: var(--bf-danger);
               font-size: .7rem;
               font-weight: 750;
               line-height: 1.45;
          }

          .branch-form-help {
               margin-top: 6px;
               color: #71868e;
               font-size: .69rem;
               line-height: 1.5;
          }

          .branch-code-box {
               position: relative;
          }

          .branch-code-badge {
               position: absolute;
               top: 50%;
               right: 11px;
               display: inline-flex;
               align-items: center;
               gap: 4px;
               padding: 4px 7px;
               color: #0f766e;
               font-size: .61rem;
               font-weight: 850;
               border: 1px solid #a9e4de;
               border-radius: 999px;
               background: #e8faf7;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .branch-status-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 12px;
          }

          .branch-status-option {
               position: relative;
               min-width: 0;
          }

          .branch-status-option input {
               position: absolute;
               width: 1px;
               height: 1px;
               opacity: 0;
               pointer-events: none;
          }

          .branch-status-card {
               display: flex;
               align-items: flex-start;
               gap: 11px;
               min-height: 88px;
               padding: 15px;
               color: #39545e;
               cursor: pointer;
               border: 1px solid var(--bf-border);
               border-radius: 14px;
               background: #ffffff;
               transition: border-color .18s ease, background .18s ease, box-shadow .18s ease, transform .18s ease;
          }

          .branch-status-card:hover {
               border-color: #a7ddd7;
               background: #fbfffe;
               transform: translateY(-1px);
          }

          .branch-status-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 38px;
               width: 38px;
               height: 38px;
               color: #168f84;
               border-radius: 12px;
               background: #ecfaf7;
          }

          .branch-status-option.inactive .branch-status-icon {
               color: #75888f;
               background: #f1f5f6;
          }

          .branch-status-copy {
               min-width: 0;
          }

          .branch-status-card strong {
               display: block;
               margin-bottom: 4px;
               color: #2c4a53 !important;
               font-size: .82rem;
          }

          .branch-status-card small {
               display: block;
               color: var(--bf-muted) !important;
               font-size: .69rem;
               line-height: 1.45;
          }

          .branch-status-option input:checked + .branch-status-card {
               border-color: #48bcb1;
               background: #ecfaf7;
               box-shadow: 0 0 0 1px rgba(21, 159, 147, .28);
          }

          .branch-status-option input:checked + .branch-status-card strong {
               color: #0f766e !important;
          }

          .branch-status-option input:focus-visible + .branch-status-card {
               outline: 3px solid rgba(45, 212, 191, .23);
               outline-offset: 2px;
          }

          .branch-form-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               padding: 20px 30px;
               border-top: 1px solid #d9eae8;
               background: linear-gradient(90deg, #f8fdfc 0%, #eefaf8 100%);
          }

          .branch-form-note {
               display: flex;
               align-items: flex-start;
               gap: 7px;
               max-width: 620px;
               color: #6b8189;
               font-size: .72rem;
               line-height: 1.5;
          }

          .branch-form-note i {
               margin-top: 1px;
               color: #159f93;
          }

          .branch-form-actions {
               display: flex;
               align-items: center;
               gap: 9px;
               flex: 0 0 auto;
          }

          .branch-form-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 46px;
               padding: 11px 18px;
               font-size: .82rem;
               font-weight: 850;
               line-height: 1.2;
               text-decoration: none;
               border-radius: 11px;
               transition: .2s ease;
          }

          .branch-form-button.cancel {
               color: #536a73 !important;
               border: 1px solid #cbdedc;
               background: #ffffff;
          }

          .branch-form-button.cancel:hover {
               color: #176b68 !important;
               border-color: #9fddd6;
               background: #f3fcfa;
               transform: translateY(-1px);
          }

          .branch-form-button.submit {
               color: #ffffff !important;
               border: 1px solid #19a99c;
               background: linear-gradient(135deg, #36c8b9 0%, #159f93 100%);
               box-shadow: 0 9px 20px rgba(21, 159, 147, .20);
          }

          .branch-form-button.submit:hover:not(:disabled) {
               color: #ffffff !important;
               border-color: #0f887e;
               background: linear-gradient(135deg, #2bbcad 0%, #0f887e 100%);
               transform: translateY(-1px);
               box-shadow: 0 12px 24px rgba(21, 159, 147, .24);
          }

          .branch-form-button.submit:focus-visible,
          .branch-form-button.cancel:focus-visible {
               outline: 3px solid rgba(45, 212, 191, .24);
               outline-offset: 2px;
          }

          .branch-form-button.submit:disabled {
               cursor: not-allowed;
               opacity: .68;
               box-shadow: none;
               transform: none;
          }

          @media (max-width: 767.98px) {
               .branch-form-section {
                    padding: 22px 18px;
               }

               .branch-form-grid,
               .branch-status-grid {
                    grid-template-columns: 1fr;
               }

               .branch-form-field.full {
                    grid-column: auto;
               }

               .branch-form-footer {
                    align-items: stretch;
                    flex-direction: column;
                    padding: 18px;
               }

               .branch-form-actions {
                    width: 100%;
               }

               .branch-form-button {
                    flex: 1 1 0;
               }
          }

          @media (max-width: 479.98px) {
               .branch-form-heading-icon {
                    display: none;
               }

               .branch-form-actions {
                    flex-direction: column-reverse;
               }

               .branch-form-button {
                    width: 100%;
               }
          }

          @media (prefers-reduced-motion: reduce) {
               .branch-form-ui *,
               .branch-form-ui *::before,
               .branch-form-ui *::after {
                    scroll-behavior: auto !important;
                    transition: none !important;
               }
          }
     </style>
@endonce

<div class="branch-form-ui"
     data-branch-form-root
     data-edit-mode="{{ $isEditMode ? '1' : '0' }}"
     data-loading-label="{{ $submitLoadingLabel }}">

     <div class="branch-form-card">
          <section class="branch-form-section">
               <div class="branch-form-heading">
                    <div class="branch-form-heading-icon">
                         <i class="bi bi-building"></i>
                    </div>
                    <div>
                         <h2>Identitas Cabang</h2>
                         <p>
                              {{ $isEditMode
                                   ? 'Periksa dan perbarui nama serta alamat resmi cabang.'
                                   : 'Nama cabang digunakan sebagai dasar pembuatan kode cabang otomatis.' }}
                         </p>
                    </div>
               </div>

               <div class="branch-form-grid">
                    <div class="branch-form-field">
                         <label for="branch_name" class="branch-form-label">
                              <span>Nama Cabang <span class="branch-required">*</span></span>
                              <small>Maksimal 100 karakter</small>
                         </label>

                         <input
                              id="branch_name"
                              name="branch_name"
                              type="text"
                              class="branch-form-control @error('branch_name') is-invalid @enderror"
                              value="{{ old('branch_name', $branchModel->branch_name ?? '') }}"
                              placeholder="Contoh: Cabang Sragen"
                              maxlength="100"
                              autocomplete="organization"
                              required>

                         @error('branch_name')
                              <div class="branch-form-error" role="alert">
                                   <i class="bi bi-exclamation-circle-fill"></i>
                                   <span>{{ $message }}</span>
                              </div>
                         @enderror
                    </div>

                    <div class="branch-form-field">
                         <label for="branch_code_preview" class="branch-form-label">
                              <span>{{ $isEditMode ? 'Kode Cabang' : 'Preview Kode Cabang' }}</span>
                              <small>{{ $isEditMode ? 'Terkunci' : 'Otomatis' }}</small>
                         </label>

                         <div class="branch-code-box">
                              <input
                                   id="branch_code_preview"
                                   type="text"
                                   class="branch-form-control"
                                   value="{{ $isEditMode ? ($branchModel->branch_code ?? 'CBG-XXX-001') : 'CBG-XXX-001' }}"
                                   readonly
                                   aria-readonly="true">

                              <span class="branch-code-badge">
                                   <i class="bi {{ $isEditMode ? 'bi-lock-fill' : 'bi-stars' }}"></i>
                                   {{ $isEditMode ? 'Terkunci' : 'Preview' }}
                              </span>
                         </div>

                         <div class="branch-form-help">
                              @if ($isEditMode)
                                   Kode cabang telah ditetapkan oleh sistem dan tidak dapat diubah dari halaman ini.
                              @else
                                   Nomor urut final dapat berubah menjadi 002, 003, dan seterusnya apabila kode lokasi yang sama sudah tersedia.
                              @endif
                         </div>
                    </div>

                    <div class="branch-form-field full">
                         <label for="address" class="branch-form-label">
                              <span>Alamat Cabang <span class="branch-required">*</span></span>
                         </label>

                         <textarea
                              id="address"
                              name="address"
                              class="branch-form-control @error('address') is-invalid @enderror"
                              placeholder="Masukkan alamat lengkap cabang"
                              required>{{ old('address', $branchModel->address ?? '') }}</textarea>

                         @error('address')
                              <div class="branch-form-error" role="alert">
                                   <i class="bi bi-exclamation-circle-fill"></i>
                                   <span>{{ $message }}</span>
                              </div>
                         @enderror
                    </div>
               </div>
          </section>

          <section class="branch-form-section">
               <div class="branch-form-heading">
                    <div class="branch-form-heading-icon">
                         <i class="bi bi-person-vcard"></i>
                    </div>
                    <div>
                         <h2>Penanggung Jawab dan Kontak</h2>
                         <p>Lengkapi kepala cabang serta informasi kontak operasional.</p>
                    </div>
               </div>

               <div class="branch-form-grid">
                    <div class="branch-form-field full">
                         <label for="manager_id" class="branch-form-label">
                              <span>Kepala Cabang</span>
                              <small>Opsional</small>
                         </label>

                         <select
                              id="manager_id"
                              name="manager_id"
                              class="branch-form-control @error('manager_id') is-invalid @enderror">
                              <option value="">Belum ditentukan</option>

                              @foreach ($managers ?? collect() as $manager)
                                   <option value="{{ $manager->id }}" @selected($selectedManager === (string) $manager->id)>
                                        {{ $manager->name }}
                                        @if (!empty($manager->email))
                                             — {{ $manager->email }}
                                        @endif
                                   </option>
                              @endforeach
                         </select>

                         @error('manager_id')
                              <div class="branch-form-error" role="alert">
                                   <i class="bi bi-exclamation-circle-fill"></i>
                                   <span>{{ $message }}</span>
                              </div>
                         @enderror
                    </div>

                    <div class="branch-form-field">
                         <label for="phone" class="branch-form-label">
                              <span>Nomor Telepon <span class="branch-required">*</span></span>
                         </label>

                         <input
                              id="phone"
                              name="phone"
                              type="text"
                              class="branch-form-control @error('phone') is-invalid @enderror"
                              value="{{ old('phone', $branchModel->phone ?? '') }}"
                              placeholder="Contoh: 0271 123456"
                              maxlength="20"
                              autocomplete="tel"
                              inputmode="tel"
                              required>

                         @error('phone')
                              <div class="branch-form-error" role="alert">
                                   <i class="bi bi-exclamation-circle-fill"></i>
                                   <span>{{ $message }}</span>
                              </div>
                         @enderror
                    </div>

                    <div class="branch-form-field">
                         <label for="email" class="branch-form-label">
                              <span>Email Cabang <span class="branch-required">*</span></span>
                         </label>

                         <input
                              id="email"
                              name="email"
                              type="email"
                              class="branch-form-control @error('email') is-invalid @enderror"
                              value="{{ old('email', $branchModel->email ?? '') }}"
                              placeholder="sragen@perusahaan.com"
                              maxlength="100"
                              autocomplete="email"
                              required>

                         @error('email')
                              <div class="branch-form-error" role="alert">
                                   <i class="bi bi-exclamation-circle-fill"></i>
                                   <span>{{ $message }}</span>
                              </div>
                         @enderror
                    </div>
               </div>
          </section>

          <section class="branch-form-section">
               <div class="branch-form-heading">
                    <div class="branch-form-heading-icon">
                         <i class="bi bi-toggles"></i>
                    </div>
                    <div>
                         <h2>Status Operasional</h2>
                         <p>
                              {{ $isEditMode
                                   ? 'Pilih status yang sesuai dengan kondisi operasional cabang terbaru.'
                                   : 'Status akan diterapkan setelah proses persetujuan selesai.' }}
                         </p>
                    </div>
               </div>

               <div class="branch-status-grid">
                    <div class="branch-status-option">
                         <input
                              id="status_active"
                              type="radio"
                              name="status"
                              value="1"
                              @checked($selectedStatus === '1')
                              required>

                         <label for="status_active" class="branch-status-card">
                              <span class="branch-status-icon">
                                   <i class="bi bi-check-circle-fill"></i>
                              </span>
                              <span class="branch-status-copy">
                                   <strong>Aktif</strong>
                                   <small>Cabang siap digunakan untuk kegiatan operasional.</small>
                              </span>
                         </label>
                    </div>

                    <div class="branch-status-option inactive">
                         <input
                              id="status_inactive"
                              type="radio"
                              name="status"
                              value="0"
                              @checked($selectedStatus === '0')
                              required>

                         <label for="status_inactive" class="branch-status-card">
                              <span class="branch-status-icon">
                                   <i class="bi bi-pause-circle-fill"></i>
                              </span>
                              <span class="branch-status-copy">
                                   <strong>Nonaktif</strong>
                                   <small>Cabang disimpan tetapi belum atau tidak sedang dioperasikan.</small>
                              </span>
                         </label>
                    </div>
               </div>

               @error('status')
                    <div class="branch-form-error" role="alert">
                         <i class="bi bi-exclamation-circle-fill"></i>
                         <span>{{ $message }}</span>
                    </div>
               @enderror
          </section>

          <footer class="branch-form-footer">
               <div class="branch-form-note">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>{{ $footerNote }}</span>
               </div>

               <div class="branch-form-actions">
                    <a href="{{ $cancelUrl }}" class="branch-form-button cancel">
                         <i class="bi bi-x-lg"></i>
                         <span>Batal</span>
                    </a>

                    <button type="submit" class="branch-form-button submit" data-branch-submit>
                         <i class="bi {{ $submitIcon }}"></i>
                         <span data-submit-label>{{ $submitLabel }}</span>
                    </button>
               </div>
          </footer>
     </div>
</div>

@once
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const aliases = {
                    BANDUNG: 'BDG',
                    BANYUMAS: 'BMS',
                    BATAM: 'BTM',
                    BEKASI: 'BKS',
                    BOGOR: 'BGR',
                    CIREBON: 'CRB',
                    DEPOK: 'DPK',
                    JAKARTA: 'JKT',
                    KARAWANG: 'KRW',
                    MALANG: 'MLG',
                    MEDAN: 'MDN',
                    PALEMBANG: 'PLB',
                    PEKANBARU: 'PKU',
                    SEMARANG: 'SMG',
                    SOLO: 'SLO',
                    SRAGEN: 'SRG',
                    SURABAYA: 'SBY',
                    SURAKARTA: 'SKT',
                    TANGERANG: 'TGR',
                    YOGYAKARTA: 'YGY'
               };

               const ignoredLocationWords = new Set([
                    'CABANG',
                    'KOTA',
                    'KAB',
                    'KABUPATEN'
               ]);

               function normalizeText(value) {
                    return String(value || '')
                         .normalize('NFD')
                         .replace(/[\u0300-\u036f]/g, '')
                         .toUpperCase()
                         .trim()
                         .replace(/^CABANG[\s\-_]+/, '')
                         .replace(/[^A-Z0-9\s]/g, ' ')
                         .replace(/\s+/g, ' ')
                         .trim();
               }

               function getLocationName(branchName) {
                    const cleanName = normalizeText(branchName);

                    if (!cleanName) {
                         return '';
                    }

                    const words = cleanName
                         .split(' ')
                         .filter(Boolean)
                         .filter(function(word) {
                              return !ignoredLocationWords.has(word);
                         });

                    return words[0] || '';
               }

               function makeLocationCode(branchName) {
                    const locationName = getLocationName(branchName);

                    if (!locationName) {
                         return 'XXX';
                    }

                    if (aliases[locationName]) {
                         return aliases[locationName];
                    }

                    const consonants = locationName.replace(/[AEIOU]/g, '');

                    if (consonants.length >= 3) {
                         return consonants.substring(0, 3);
                    }

                    return locationName.substring(0, 3).padEnd(3, 'X');
               }

               document.querySelectorAll('[data-branch-form-root]').forEach(function(root) {
                    const form = root.closest('form');
                    const isEditMode = root.dataset.editMode === '1';
                    const branchNameInput = root.querySelector('#branch_name');
                    const branchCodePreview = root.querySelector('#branch_code_preview');
                    const submitButton = root.querySelector('[data-branch-submit]');
                    const submitLabel = root.querySelector('[data-submit-label]');
                    const loadingLabel = root.dataset.loadingLabel || 'Menyimpan...';

                    function updateCodePreview() {
                         if (isEditMode || !branchCodePreview) {
                              return;
                         }

                         const locationCode = makeLocationCode(branchNameInput?.value || '');
                         branchCodePreview.value = `CBG-${locationCode}-001`;
                    }

                    function clearInvalidState(event) {
                         if (event.target instanceof HTMLElement) {
                              event.target.classList.remove('is-invalid');
                         }
                    }

                    branchNameInput?.addEventListener('input', updateCodePreview);

                    form?.querySelectorAll('input, textarea, select').forEach(function(field) {
                         field.addEventListener('input', clearInvalidState);
                         field.addEventListener('change', clearInvalidState);
                    });

                    form?.addEventListener('submit', function(event) {
                         if (!form.checkValidity()) {
                              event.preventDefault();
                              event.stopPropagation();

                              form.querySelectorAll(':invalid').forEach(function(field) {
                                   field.classList.add('is-invalid');
                              });

                              form.querySelector(':invalid')?.focus();
                              return;
                         }

                         if (submitButton) {
                              submitButton.disabled = true;
                              submitButton.setAttribute('aria-busy', 'true');
                         }

                         if (submitLabel) {
                              submitLabel.textContent = loadingLabel;
                         }
                    });

                    updateCodePreview();
               });
          });
     </script>
@endonce
