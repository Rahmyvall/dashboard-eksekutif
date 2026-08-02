@extends('layouts.app')

@section('title', 'Edit Cabang')

@section('content')
     @php
          $selectedManager = (string) old('manager_id', $branch->manager_id ?? '');
          $selectedStatus = (string) old('status', (string) ($branch->status ?? '1'));
     @endphp
<style>
          .branch-create-page {
               --bc-primary: #0f8f83;
               --bc-primary-hover: #0b7d74;
               --bc-primary-soft: #e8fbf7;
               --bc-accent: #2dd4bf;
               --bc-heading: #164e63;
               --bc-text: #263b45;
               --bc-muted: #607681;
               --bc-border: #cfe5e4;
               --bc-surface: #ffffff;
               --bc-surface-soft: #f7fcfb;
               min-height: calc(100vh - 70px);
               width: 100%;
               padding: 22px 24px 42px;
               color: var(--bc-text);
               background:
                    radial-gradient(circle at 100% 0, rgba(45, 212, 191, .15), transparent 29%),
                    radial-gradient(circle at 0 100%, rgba(125, 211, 252, .12), transparent 27%),
                    linear-gradient(180deg, #f8fffe 0%, #f4fbfb 48%, #f7fafc 100%);
          }

          .branch-create-page * {
               box-sizing: border-box;
          }

          .branch-create-shell {
               width: 100%;
               max-width: 1480px;
               margin: 0 auto;
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
               color: var(--bc-text) !important;
               border: 1px solid #bde7e2;
               border-radius: 22px;
               background:
                    radial-gradient(circle at 90% 10%, rgba(255, 255, 255, .92), transparent 23%),
                    radial-gradient(circle at 72% 112%, rgba(45, 212, 191, .19), transparent 39%),
                    linear-gradient(135deg, #ffffff 0%, #f1fffc 38%, #e5faf6 72%, #d9f5ef 100%);
               box-shadow: 0 18px 42px rgba(15, 118, 110, .10);
          }

          .branch-create-hero::after {
               content: '';
               position: absolute;
               right: -92px;
               bottom: -140px;
               width: 270px;
               height: 270px;
               border: 38px solid rgba(20, 184, 166, .10);
               border-radius: 50%;
               pointer-events: none;
          }

          .branch-create-hero>* {
               position: relative;
               z-index: 1;
          }

          .branch-create-page .branch-create-hero h1 {
               margin: 0 0 7px;
               color: var(--bc-heading) !important;
               font-size: clamp(1.75rem, 3vw, 2.45rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .branch-create-page .branch-create-hero p {
               max-width: 900px;
               margin: 0;
               color: #55717a !important;
               font-size: .94rem;
               line-height: 1.65;
          }

          .branch-create-back {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 44px;
               padding: 10px 15px;
               color: #0f766e !important;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid #8fdad1;
               border-radius: 12px;
               background: rgba(255, 255, 255, .90);
               box-shadow: 0 7px 18px rgba(15, 118, 110, .08);
               transition: .2s ease;
          }

          .branch-create-back:hover {
               color: #0b655f !important;
               border-color: #5eead4;
               background: #f0fdfa;
               transform: translateY(-1px);
          }

          .branch-create-card {
               width: 100%;
               overflow: hidden;
               border: 1px solid var(--bc-border);
               border-radius: 20px;
               background: rgba(255, 255, 255, .99);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .065);
          }

          .branch-form-section {
               padding: 28px 30px;
               border-bottom: 1px solid #e2efee;
          }

          .branch-form-section:last-of-type {
               border-bottom: 0;
          }

          .branch-section-heading {
               margin-bottom: 18px;
               padding-bottom: 13px;
               border-bottom: 1px dashed #d7e9e7;
          }

          .branch-section-heading h2 {
               margin: 0 0 5px;
               color: #176b68 !important;
               font-size: 1.08rem;
               font-weight: 850;
          }

          .branch-section-heading p {
               margin: 0;
               color: var(--bc-muted) !important;
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
               color: #34515c !important;
               font-size: .76rem;
               font-weight: 800;
          }

          .branch-label small {
               color: #81949c !important;
               font-size: .67rem;
          }

          .branch-control {
               width: 100%;
               min-height: 47px;
               padding: 10px 13px;
               color: #263b45 !important;
               font-size: .82rem;
               border: 1px solid #cbdedc;
               border-radius: 12px;
               outline: none;
               background: #ffffff;
               transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
          }

          .branch-control::placeholder {
               color: #9aabb1;
          }

          .branch-control:hover {
               border-color: #9fd8d2;
          }

          textarea.branch-control {
               min-height: 110px;
               resize: vertical;
          }

          .branch-control:focus {
               border-color: #2bb9aa;
               background: #fcfffe;
               box-shadow: 0 0 0 4px rgba(45, 212, 191, .13);
          }

          .branch-control[readonly] {
               color: #176b68 !important;
               font-weight: 850;
               letter-spacing: .04em;
               border-color: #8fddd4;
               border-style: dashed;
               background: #effcf9;
          }

          .branch-error {
               margin-top: 6px;
               color: #c2414b;
               font-size: .7rem;
               font-weight: 700;
          }

          .branch-help {
               margin-top: 6px;
               color: #6d838c;
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
               color: #0f766e;
               font-size: .62rem;
               font-weight: 850;
               border: 1px solid #a7e7df;
               border-radius: 999px;
               background: #e8fbf7;
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
               min-height: 82px;
               padding: 15px;
               color: #34515c;
               cursor: pointer;
               border: 1px solid var(--bc-border);
               border-radius: 13px;
               background: #ffffff;
               transition: .18s ease;
          }

          .branch-status-card:hover {
               border-color: #9fddd6;
               background: #fbfffe;
          }

          .branch-status-card strong {
               display: block;
               margin-bottom: 4px;
               color: #284750;
               font-size: .82rem;
          }

          .branch-status-card small {
               color: var(--bc-muted);
               font-size: .69rem;
               line-height: 1.45;
          }

          .branch-status-option input:checked+.branch-status-card {
               color: #176b68;
               border-color: #39b9ac;
               background: #eafaf7;
               box-shadow: 0 0 0 1px rgba(15, 143, 131, .35);
          }

          .branch-status-option input:checked+.branch-status-card strong {
               color: #0f766e;
          }

          .branch-form-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               padding: 20px 30px;
               border-top: 1px solid #d8ebe8;
               background: linear-gradient(90deg, #f5fcfb 0%, #eefaf8 100%);
          }

          .branch-footer-note {
               color: #687f88;
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
               transition: .2s ease;
          }

          .branch-button.cancel {
               color: #526873 !important;
               border: 1px solid #cbdedc;
               background: #ffffff;
          }

          .branch-button.cancel:hover {
               color: #176b68 !important;
               border-color: #9fddd6;
               background: #f3fcfa;
          }

          .branch-button.submit {
               color: #ffffff !important;
               border: 1px solid #16a99a;
               background: linear-gradient(135deg, #2bc7b6 0%, #149e91 100%);
               box-shadow: 0 9px 20px rgba(20, 158, 145, .20);
          }

          .branch-button.submit:hover:not(:disabled) {
               color: #ffffff !important;
               background: linear-gradient(135deg, #24b9aa 0%, #0f8f83 100%);
               transform: translateY(-1px);
               box-shadow: 0 12px 24px rgba(20, 158, 145, .24);
          }

          .branch-button.submit:disabled {
               cursor: not-allowed;
               opacity: .7;
               box-shadow: none;
          }

          .branch-status-option input:focus-visible+.branch-status-card {
               outline: 3px solid rgba(45, 212, 191, .24);
               outline-offset: 2px;
          }

          .branch-control.is-invalid {
               border-color: #e66a73;
               box-shadow: 0 0 0 4px rgba(230, 106, 115, .09);
          }

          .branch-validation-alert {
               margin-bottom: 16px;
               padding: 14px 16px;
               color: #9f3039;
               font-size: .78rem;
               border: 1px solid #f3bec3;
               border-radius: 13px;
               background: #fff7f8;
               box-shadow: 0 8px 20px rgba(159, 48, 57, .05);
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

               .branch-actions {
                    width: 100%;
               }

               .branch-button {
                    flex: 1 1 0;
               }
          }

          @media (max-width: 479.98px) {
               .branch-actions {
                    flex-direction: column;
               }

               .branch-button {
                    width: 100%;
               }
          }
     </style>

     <div class="branch-create-page">
          <div class="branch-create-shell">
               <header class="branch-create-hero">
                    <div>
                         <h1>Edit Data Cabang</h1>
                         <p>
                              Perbarui identitas, penanggung jawab, kontak, dan status operasional cabang.
                              Kode cabang tetap dikunci agar konsistensi data terjaga.
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

               <form id="branchEditForm" method="POST" action="{{ route('branches.update', $branch->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="branch-create-card">
                         <section class="branch-form-section">
                              <div class="branch-section-heading">
                                   <h2>Identitas Cabang</h2>
                                   <p>Periksa kembali nama dan alamat resmi cabang.</p>
                              </div>

                              <div class="branch-grid">
                                   <div class="branch-field">
                                        <label for="branch_name" class="branch-label">
                                             <span>Nama Cabang *</span>
                                             <small>Maksimal 100 karakter</small>
                                        </label>
                                        <input id="branch_name" name="branch_name" type="text" class="branch-control"
                                             value="{{ old('branch_name', $branch->branch_name) }}" placeholder="Contoh: Cabang Sragen"
                                             maxlength="100" autocomplete="organization" required>
                                        @error('branch_name')
                                             <div class="branch-error">{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="branch-field">
                                        <label for="branch_code_preview" class="branch-label">
                                             <span>Kode Cabang</span>
                                             <small>Terkunci</small>
                                        </label>

                                        <div class="branch-code-preview-box">
                                             <input id="branch_code_preview" type="text" class="branch-control"
                                                  value="{{ $branch->branch_code }}" readonly aria-readonly="true">
                                             <span class="branch-code-preview-badge">Terkunci</span>
                                        </div>

                                        <div class="branch-help">
                                             Kode cabang ditetapkan oleh sistem dan tidak dapat diubah dari halaman ini.
                                        </div>
                                   </div>

                                   <div class="branch-field full">
                                        <label for="address" class="branch-label">
                                             <span>Alamat Cabang *</span>
                                        </label>
                                        <textarea id="address" name="address" class="branch-control" placeholder="Masukkan alamat lengkap cabang" required>{{ old('address', $branch->address) }}</textarea>
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
                                             value="{{ old('phone', $branch->phone) }}" placeholder="Contoh: 0271 123456" maxlength="20"
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
                                             value="{{ old('email', $branch->email) }}" placeholder="sragen@perusahaan.com" maxlength="100"
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
                                   <p>Pilih status operasional yang sesuai dengan kondisi terbaru cabang.</p>
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
                                   Perubahan akan disimpan sesuai kebijakan persetujuan yang berlaku.
                              </div>

                              <div class="branch-actions">
                                   <a href="{{ route('branches.index') }}" class="branch-button cancel">
                                        Batal
                                   </a>
                                   <button type="submit" class="branch-button submit" id="submitBranchButton">
                                        <i class="bi bi-send-check-fill"></i>
                                        <span>Simpan Perubahan</span>
                                   </button>
                              </div>
                         </footer>
                    </div>
               </form>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const form = document.getElementById('branchEditForm');
               const submitButton = document.getElementById('submitBranchButton');

               function clearInvalidState(event) {
                    if (event.target instanceof HTMLElement) {
                         event.target.classList.remove('is-invalid');
                    }
               }

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
                         if (label) label.textContent = 'Menyimpan...';
                    }
               });
          });
     </script>
@endsection
