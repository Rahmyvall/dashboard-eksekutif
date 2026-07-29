@extends('layouts.app')

@section('title', 'Tambah Cabang')

@section('content')
     @php
          $selectedManager = (string) old('manager_id', '');
          $selectedStatus = (string) old('status', '1');
     @endphp

     <style>
          .branch-create-page {
               --bc-primary: #0f766e;
               --bc-primary-dark: #115e59;
               --bc-primary-soft: #ccfbf1;
               --bc-accent: #14b8a6;
               --bc-text: #0f172a;
               --bc-muted: #64748b;
               --bc-border: #dbe4e8;
               min-height: calc(100vh - 70px);
               width: 100%;
               padding: 22px 24px 42px;
               color: var(--bc-text);
               background:
                    radial-gradient(circle at 100% 0, rgba(20, 184, 166, .14), transparent 30%),
                    radial-gradient(circle at 0 100%, rgba(15, 118, 110, .09), transparent 26%),
                    linear-gradient(180deg, #f0fdfa 0%, #f8fafc 48%, #f1f5f9 100%);
          }

          .branch-create-shell {
               width: 100%;
               max-width: none;
               margin: 0;
          }

          .branch-create-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 28px;
               width: 100%;
               margin-bottom: 22px;
               padding: 30px 34px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .14);
               border-radius: 22px;
               background:
                    radial-gradient(circle at 88% 10%, rgba(255, 255, 255, .18), transparent 24%),
                    linear-gradient(135deg, #0f172a 0%, #134e4a 50%, #0f766e 100%);
               box-shadow: 0 22px 48px rgba(15, 118, 110, .22);
          }

          .branch-create-hero::after {
               content: '';
               position: absolute;
               right: -90px;
               bottom: -130px;
               width: 260px;
               height: 260px;
               border: 36px solid rgba(255, 255, 255, .08);
               border-radius: 50%;
               pointer-events: none;
          }

          .branch-create-hero>* {
               position: relative;
               z-index: 1;
          }

          .branch-create-hero h1 {
               margin: 0 0 6px;
               font-size: clamp(1.8rem, 3vw, 2.6rem);
               font-weight: 850;
          }

          .branch-create-hero p {
               max-width: 900px;
               margin: 0;
               color: rgba(255, 255, 255, .78);
               font-size: .94rem;
               line-height: 1.6;
          }

          .branch-create-back {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 43px;
               padding: 10px 14px;
               color: #fff;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .34);
               border-radius: 12px;
               background: rgba(255, 255, 255, .12);
               backdrop-filter: blur(8px);
               transition: .2s ease;
          }

          .branch-create-back:hover {
               color: #fff;
               background: rgba(255, 255, 255, .22);
               transform: translateY(-1px);
          }

          .branch-create-card {
               width: 100%;
               overflow: hidden;
               border: 1px solid var(--bc-border);
               border-radius: 20px;
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 16px 40px rgba(15, 23, 42, .07);
          }

          .branch-form-section {
               padding: 28px 30px;
               border-bottom: 1px solid var(--bc-border);
          }

          .branch-form-section:last-of-type {
               border-bottom: 0;
          }

          .branch-section-heading {
               margin-bottom: 18px;
          }

          .branch-section-heading h2 {
               margin: 0 0 5px;
               color: #134e4a;
               font-size: 1.08rem;
               font-weight: 850;
          }

          .branch-section-heading p {
               margin: 0;
               color: var(--bc-muted);
               font-size: .77rem;
          }

          .branch-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 20px;
          }

          .branch-field.full {
               grid-column: 1 / -1;
          }

          .branch-label {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
               margin-bottom: 7px;
               color: #334155;
               font-size: .76rem;
               font-weight: 800;
          }

          .branch-label small {
               color: #94a3b8;
               font-size: .67rem;
          }

          .branch-control {
               width: 100%;
               min-height: 47px;
               padding: 10px 13px;
               color: var(--bc-text);
               font-size: .82rem;
               border: 1px solid #cbd5e1;
               border-radius: 12px;
               outline: none;
               background: #fff;
          }

          textarea.branch-control {
               min-height: 110px;
               resize: vertical;
          }

          .branch-control:focus {
               border-color: var(--bc-accent);
               box-shadow: 0 0 0 4px rgba(20, 184, 166, .12);
          }

          .branch-control[readonly] {
               color: var(--bc-primary-dark);
               font-weight: 850;
               letter-spacing: .04em;
               border-color: #5eead4;
               border-style: dashed;
               background: #f0fdfa;
          }

          .branch-error {
               margin-top: 6px;
               color: #dc2626;
               font-size: .7rem;
               font-weight: 700;
          }

          .branch-help {
               margin-top: 6px;
               color: #64748b;
               font-size: .69rem;
               line-height: 1.5;
          }

          .branch-code-preview-box {
               position: relative;
          }

          .branch-code-preview-badge {
               position: absolute;
               top: 50%;
               right: 11px;
               padding: 4px 7px;
               color: #115e59;
               font-size: .62rem;
               font-weight: 850;
               border-radius: 999px;
               background: #ccfbf1;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .branch-status-options {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 12px;
          }

          .branch-status-option {
               position: relative;
          }

          .branch-status-option input {
               position: absolute;
               opacity: 0;
          }

          .branch-status-card {
               display: block;
               padding: 14px;
               cursor: pointer;
               border: 1px solid var(--bc-border);
               border-radius: 13px;
               background: #fff;
          }

          .branch-status-card strong {
               display: block;
               margin-bottom: 3px;
               font-size: .8rem;
          }

          .branch-status-card small {
               color: var(--bc-muted);
               font-size: .68rem;
          }

          .branch-status-option input:checked+.branch-status-card {
               color: #134e4a;
               border-color: var(--bc-primary);
               background: var(--bc-primary-soft);
               box-shadow: 0 0 0 1px var(--bc-primary);
          }

          .branch-form-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               padding: 20px 30px;
               border-top: 1px solid var(--bc-border);
               background: #f0fdfa;
          }

          .branch-footer-note {
               color: var(--bc-muted);
               font-size: .72rem;
          }

          .branch-actions {
               display: flex;
               gap: 9px;
          }

          .branch-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 46px;
               padding: 11px 18px;
               font-size: .82rem;
               font-weight: 850;
               text-decoration: none;
               border-radius: 11px;
          }

          .branch-button.cancel {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #fff;
          }

          .branch-button.submit {
               color: #fff;
               border: 1px solid var(--bc-primary);
               background: linear-gradient(135deg, var(--bc-primary), var(--bc-primary-dark));
               box-shadow: 0 10px 22px rgba(15, 118, 110, .24);
               transition: .2s ease;
          }

          .branch-button.submit:hover:not(:disabled) {
               transform: translateY(-1px);
               box-shadow: 0 13px 26px rgba(15, 118, 110, .28);
          }

          .branch-button.submit:disabled {
               cursor: not-allowed;
               opacity: .7;
               box-shadow: none;
          }

          .branch-status-option input:focus-visible+.branch-status-card {
               outline: 3px solid rgba(20, 184, 166, .24);
               outline-offset: 2px;
          }

          .branch-control.is-invalid {
               border-color: #ef4444;
               box-shadow: 0 0 0 4px rgba(239, 68, 68, .08);
          }

          .branch-validation-alert {
               margin-bottom: 16px;
               padding: 14px 16px;
               color: #991b1b;
               font-size: .78rem;
               border: 1px solid #fecaca;
               border-radius: 13px;
               background: #fff7f7;
          }

          @media (max-width: 767.98px) {
               .branch-create-page {
                    padding: 12px 10px 28px;
               }

               .branch-create-hero {
                    padding: 24px 20px;
                    border-radius: 18px;
               }

               .branch-form-section {
                    padding: 22px 18px;
               }

               .branch-form-footer {
                    padding: 18px;
               }

               .branch-create-hero,
               .branch-form-footer {
                    align-items: stretch;
                    flex-direction: column;
               }

               .branch-create-back {
                    width: 100%;
               }

               .branch-grid,
               .branch-status-options {
                    grid-template-columns: 1fr;
               }

               .branch-field.full {
                    grid-column: auto;
               }

               .branch-actions,
               .branch-button {
                    width: 100%;
               }
          }
     </style>

     <div class="branch-create-page">
          <div class="branch-create-shell">
               <header class="branch-create-hero">
                    <div>
                         <h1>Tambah Cabang Baru</h1>
                         <p>
                              Kode cabang dibuat otomatis berdasarkan nama lokasi.
                              Contoh: Cabang Sragen menjadi CBG-SRG-001.
                         </p>
                    </div>

                    <a href="{{ route('branches.index') }}" class="branch-create-back">
                         <i class="bi bi-arrow-left"></i>
                         Kembali
                    </a>
               </header>

               @if ($errors->any())
                    <div class="branch-validation-alert">
                         <strong>Data belum dapat disimpan.</strong>
                         <ul class="mb-0 mt-2">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <form id="branchCreateForm" method="POST" action="{{ route('branches.store') }}">
                    @csrf

                    <div class="branch-create-card">
                         <section class="branch-form-section">
                              <div class="branch-section-heading">
                                   <h2>Identitas Cabang</h2>
                                   <p>Nama cabang akan menjadi dasar pembentukan kode cabang.</p>
                              </div>

                              <div class="branch-grid">
                                   <div class="branch-field">
                                        <label for="branch_name" class="branch-label">
                                             <span>Nama Cabang *</span>
                                             <small>Maksimal 100 karakter</small>
                                        </label>
                                        <input id="branch_name" name="branch_name" type="text" class="branch-control"
                                             value="{{ old('branch_name') }}" placeholder="Contoh: Cabang Sragen"
                                             maxlength="100" autocomplete="organization" required>
                                        @error('branch_name')
                                             <div class="branch-error">{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="branch-field">
                                        <label for="branch_code_preview" class="branch-label">
                                             <span>Preview Kode Cabang</span>
                                             <small>Otomatis</small>
                                        </label>

                                        <div class="branch-code-preview-box">
                                             <input id="branch_code_preview" type="text" class="branch-control"
                                                  value="CBG-XXX-001" readonly aria-readonly="true">
                                             <span class="branch-code-preview-badge">Preview</span>
                                        </div>

                                        <div class="branch-help">
                                             Nomor urut final dapat menjadi 002, 003, dan seterusnya
                                             apabila kode lokasi yang sama sudah tersedia di database.
                                        </div>
                                   </div>

                                   <div class="branch-field full">
                                        <label for="address" class="branch-label">
                                             <span>Alamat Cabang *</span>
                                        </label>
                                        <textarea id="address" name="address" class="branch-control" placeholder="Masukkan alamat lengkap cabang" required>{{ old('address') }}</textarea>
                                        @error('address')
                                             <div class="branch-error">{{ $message }}</div>
                                        @enderror
                                   </div>
                              </div>
                         </section>

                         <section class="branch-form-section">
                              <div class="branch-section-heading">
                                   <h2>Penanggung Jawab dan Kontak</h2>
                                   <p>Lengkapi kepala cabang serta kontak operasional.</p>
                              </div>

                              <div class="branch-grid">
                                   <div class="branch-field full">
                                        <label for="manager_id" class="branch-label">
                                             <span>Kepala Cabang</span>
                                             <small>Opsional</small>
                                        </label>
                                        <select id="manager_id" name="manager_id" class="branch-control">
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
                                             <div class="branch-error">{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="branch-field">
                                        <label for="phone" class="branch-label">
                                             <span>Nomor Telepon *</span>
                                        </label>
                                        <input id="phone" name="phone" type="text" class="branch-control"
                                             value="{{ old('phone') }}" placeholder="Contoh: 0271 123456" maxlength="20"
                                             autocomplete="tel" inputmode="tel" required>
                                        @error('phone')
                                             <div class="branch-error">{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="branch-field">
                                        <label for="email" class="branch-label">
                                             <span>Email Cabang *</span>
                                        </label>
                                        <input id="email" name="email" type="email" class="branch-control"
                                             value="{{ old('email') }}" placeholder="sragen@perusahaan.com" maxlength="100"
                                             autocomplete="email" required>
                                        @error('email')
                                             <div class="branch-error">{{ $message }}</div>
                                        @enderror
                                   </div>
                              </div>
                         </section>

                         <section class="branch-form-section">
                              <div class="branch-section-heading">
                                   <h2>Status Operasional</h2>
                                   <p>Status akan diterapkan setelah approval final.</p>
                              </div>

                              <div class="branch-status-options">
                                   <div class="branch-status-option">
                                        <input id="status_active" type="radio" name="status" value="1"
                                             @checked($selectedStatus === '1') required>
                                        <label for="status_active" class="branch-status-card">
                                             <strong>Aktif</strong>
                                             <small>Cabang siap digunakan untuk operasional.</small>
                                        </label>
                                   </div>

                                   <div class="branch-status-option">
                                        <input id="status_inactive" type="radio" name="status" value="0"
                                             @checked($selectedStatus === '0') required>
                                        <label for="status_inactive" class="branch-status-card">
                                             <strong>Nonaktif</strong>
                                             <small>Cabang disimpan tetapi belum dioperasikan.</small>
                                        </label>
                                   </div>
                              </div>

                              @error('status')
                                   <div class="branch-error">{{ $message }}</div>
                              @enderror
                         </section>

                         <footer class="branch-form-footer">
                              <div class="branch-footer-note">
                                   Kode cabang hanya dibuat oleh server dan tidak dapat diubah manual.
                              </div>

                              <div class="branch-actions">
                                   <a href="{{ route('branches.index') }}" class="branch-button cancel">
                                        Batal
                                   </a>
                                   <button type="submit" class="branch-button submit" id="submitBranchButton">
                                        <i class="bi bi-send-check-fill"></i>
                                        <span>Ajukan Cabang</span>
                                   </button>
                              </div>
                         </footer>
                    </div>
               </form>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const form = document.getElementById('branchCreateForm');
               const branchNameInput = document.getElementById('branch_name');
               const branchCodePreview = document.getElementById('branch_code_preview');
               const submitButton = document.getElementById('submitBranchButton');

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
                         .filter(word => !ignoredLocationWords.has(word));

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

               function updateCodePreview() {
                    if (!branchCodePreview) {
                         return;
                    }

                    const locationCode = makeLocationCode(branchNameInput?.value || '');
                    branchCodePreview.value = `CBG-${locationCode}-001`;
               }

               function clearInvalidState(event) {
                    const field = event.target;

                    if (field instanceof HTMLElement) {
                         field.classList.remove('is-invalid');
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

                         const label = submitButton.querySelector('span');

                         if (label) {
                              label.textContent = 'Mengajukan...';
                         }
                    }
               });

               updateCodePreview();
          });
     </script>
@endsection
