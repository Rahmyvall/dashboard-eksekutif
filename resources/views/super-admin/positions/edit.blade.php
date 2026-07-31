@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('content')

     <style>
          :root {
               --pos-primary: #6366f1;
               --pos-primary-dark: #4f46e5;
               --pos-purple: #8b5cf6;
               --pos-cyan: #06b6d4;
               --pos-success: #10b981;
               --pos-danger: #ef4444;
               --pos-warning: #f59e0b;
               --pos-text: #24324a;
               --pos-muted: #718096;
               --pos-border: #e7eaf3;
          }

          .position-form-page,
          .position-form-page * {
               box-sizing: border-box;
          }

          .position-form-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 4%, rgba(129, 140, 248, .18), transparent 25%),
                    radial-gradient(circle at 96% 6%, rgba(34, 211, 238, .18), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f8f6ff 48%, #f0fbff 100%);
          }

          .position-form-container {
               width: 100%;
               max-width: 1580px;
               margin: 0 auto;
          }

          .position-form-hero {
               position: relative;
               overflow: hidden;
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .75);
               border-radius: 26px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .32), transparent 24%),
                    linear-gradient(120deg, #6366f1 0%, #8b5cf6 48%, #06b6d4 100%);
               box-shadow: 0 22px 48px rgba(99, 102, 241, .20);
          }

          .position-form-hero::after {
               position: absolute;
               right: -45px;
               bottom: -85px;
               width: 185px;
               height: 185px;
               content: '';
               border-radius: 46px;
               background: rgba(255, 255, 255, .13);
               transform: rotate(28deg);
          }

          .position-form-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
          }

          .position-form-title-wrap {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .position-form-hero-icon {
               display: inline-flex;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               color: var(--pos-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 19px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 13px 25px rgba(76, 29, 149, .16);
          }

          .position-form-hero-icon svg {
               width: 27px;
               height: 27px;
          }

          .position-form-hero h1 {
               margin: 0;
               font-size: clamp(1.58rem, 2.4vw, 2.15rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .position-form-hero p {
               max-width: 720px;
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .93);
               font-size: .92rem;
               line-height: 1.65;
          }

          .position-back-link {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
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
               background: rgba(255, 255, 255, .15);
               backdrop-filter: blur(10px);
               transition: .2s ease;
          }

          .position-back-link:hover {
               color: #fff;
               text-decoration: none;
               background: rgba(255, 255, 255, .25);
               transform: translateY(-2px);
          }

          .position-back-link svg {
               width: 17px;
               height: 17px;
          }

          .position-form-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .95);
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 44px rgba(51, 65, 85, .09);
          }

          .position-form-card-header {
               padding: 22px 25px;
               border-bottom: 1px solid #edf1f7;
               background: linear-gradient(90deg, #fff 0%, #faf8ff 52%, #f1fbff 100%);
          }

          .position-form-card-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--pos-text);
               font-size: 1.06rem;
               font-weight: 830;
          }

          .position-form-card-title span:first-child {
               display: inline-flex;
               width: 42px;
               height: 42px;
               color: var(--pos-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .position-form-card-title svg {
               width: 20px;
               height: 20px;
          }

          .position-form-card-subtitle {
               margin: 5px 0 0 53px;
               color: var(--pos-muted);
               font-size: .81rem;
          }

          .position-form-card-body {
               display: grid;
               grid-template-columns: minmax(0, 2fr) minmax(340px, 1fr);
               gap: 20px;
               padding: 26px;
               align-items: start;
          }

          .position-form-card-body>.position-error-summary {
               grid-column: 1 / -1;
               width: 100%;
               margin-bottom: 0;
          }

          .position-form-card-body>.position-section {
               width: 100%;
               margin-bottom: 0;
          }

          .position-form-card-body>.position-section:first-of-type {
               grid-column: 1;
               grid-row: span 2;
               min-height: 100%;
          }

          .position-form-card-body>.position-section:nth-of-type(2) {
               grid-column: 2;
          }

          .position-form-card-body>.position-section:nth-of-type(3) {
               grid-column: 2;
          }

          .position-section {
               padding: 21px;
               margin-bottom: 20px;
               border: 1px solid #edf1f7;
               border-radius: 19px;
               background: linear-gradient(145deg, #fff, #fcfdff);
          }

          .position-section:last-child {
               margin-bottom: 0;
          }

          .position-section-heading {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 18px;
               color: #334155;
               font-size: .93rem;
               font-weight: 820;
          }

          .position-section-heading span {
               display: inline-flex;
               width: 36px;
               height: 36px;
               color: #5b21b6;
               align-items: center;
               justify-content: center;
               border-radius: 11px;
               background: #f5f3ff;
          }

          .position-section-heading svg {
               width: 17px;
               height: 17px;
          }

          .position-label {
               margin-bottom: 7px;
               color: #475569;
               font-size: .78rem;
               font-weight: 810;
          }

          .position-required {
               color: #ef4444;
          }

          .position-control {
               min-height: 48px;
               color: #24324a;
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background: #fff;
               box-shadow: none;
          }

          textarea.position-control {
               min-height: 145px;
               resize: vertical;
          }

          .position-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .11);
          }

          .position-control.is-invalid {
               border-color: #f87171;
          }

          .position-input-shell {
               position: relative;
          }

          .position-input-shell>svg {
               position: absolute;
               z-index: 2;
               top: 50%;
               left: 15px;
               width: 17px;
               height: 17px;
               color: #818cf8;
               pointer-events: none;
               transform: translateY(-50%);
          }

          .position-input-shell .position-control {
               padding-left: 43px;
          }

          .position-help {
               display: block;
               margin-top: 7px;
               color: #94a3b8;
               font-size: .73rem;
               line-height: 1.5;
          }

          .position-invalid-feedback {
               display: block;
               margin-top: 7px;
               color: #dc2626;
               font-size: .75rem;
               font-weight: 700;
          }

          .position-error-summary {
               display: flex;
               gap: 13px;
               padding: 16px 18px;
               margin-bottom: 20px;
               color: #b91c1c;
               border-left: 5px solid #ef4444;
               border-radius: 16px;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .position-error-summary svg {
               flex: 0 0 auto;
               width: 20px;
               height: 20px;
               margin-top: 2px;
          }

          .position-error-summary strong {
               display: block;
               margin-bottom: 5px;
          }

          .position-error-summary ul {
               padding-left: 18px;
               margin: 0;
               font-size: .79rem;
          }

          .position-status-options {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 12px;
          }

          .position-status-option {
               position: relative;
          }

          .position-status-option input {
               position: absolute;
               opacity: 0;
               pointer-events: none;
          }

          .position-status-label {
               display: flex;
               min-height: 77px;
               padding: 14px;
               gap: 11px;
               align-items: center;
               cursor: pointer;
               border: 1px solid #dbe3ef;
               border-radius: 15px;
               background: #fff;
               transition: .2s ease;
          }

          .position-status-icon {
               display: inline-flex;
               flex: 0 0 40px;
               width: 40px;
               height: 40px;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
          }

          .position-status-icon svg {
               width: 18px;
               height: 18px;
          }

          .position-status-active .position-status-icon {
               color: #047857;
               background: #ecfdf5;
          }

          .position-status-inactive .position-status-icon {
               color: #be123c;
               background: #fff1f2;
          }

          .position-status-text strong {
               display: block;
               margin-bottom: 3px;
               color: #334155;
               font-size: .82rem;
          }

          .position-status-text small {
               color: #94a3b8;
               font-size: .71rem;
          }

          .position-status-option input:checked+.position-status-label {
               border-color: #818cf8;
               background: #f7f7ff;
               box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
          }

          .position-form-footer {
               display: flex;
               gap: 12px;
               align-items: center;
               justify-content: flex-end;
               padding: 20px 25px;
               border-top: 1px solid #edf1f7;
               background: #fbfcff;
          }

          .position-btn {
               display: inline-flex;
               min-height: 47px;
               padding: 10px 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 810;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .position-btn svg {
               width: 17px;
               height: 17px;
          }

          .position-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .position-btn-secondary:hover {
               color: #334155;
               text-decoration: none;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          .position-btn-primary {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
               box-shadow: 0 11px 23px rgba(99, 102, 241, .23);
          }

          .position-btn-primary:hover {
               color: #fff;
               transform: translateY(-2px);
               box-shadow: 0 15px 27px rgba(99, 102, 241, .29);
          }

          .position-char-count {
               float: right;
               color: #94a3b8;
               font-size: .71rem;
               font-weight: 700;
          }

          @media (max-width: 1199.98px) {
               .position-form-card-body {
                    grid-template-columns: 1fr;
               }

               .position-form-card-body>.position-section:first-of-type,
               .position-form-card-body>.position-section:nth-of-type(2),
               .position-form-card-body>.position-section:nth-of-type(3) {
                    grid-column: 1;
                    grid-row: auto;
               }
          }

          @media (max-width: 767.98px) {
               .position-form-page {
                    padding: 20px 11px 34px;
               }

               .position-form-hero {
                    padding: 23px 20px;
               }

               .position-form-hero-content {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .position-form-title-wrap {
                    align-items: flex-start;
               }

               .position-back-link {
                    width: 100%;
               }

               .position-form-card-body {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 16px;
                    padding: 18px;
               }

               .position-section {
                    padding: 17px;
               }

               .position-status-options {
                    grid-template-columns: 1fr;
               }

               .position-form-footer {
                    flex-direction: column-reverse;
               }

               .position-btn {
                    width: 100%;
               }
          }
     </style>


     <div class="position-form-page">
          <div class="position-form-container">
               <section class="position-form-hero">
                    <div class="position-form-hero-content">
                         <div class="position-form-title-wrap">
                              <span class="position-form-hero-icon">
                                   <i data-feather="edit-3"></i>
                              </span>

                              <div>
                                   <h1>Edit Jabatan</h1>
                                   <p>
                                        Perbarui informasi jabatan
                                        <strong>{{ $position->name }}</strong>
                                        tanpa mengubah data lain yang tidak diperlukan.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ route('super-admin.positions.index') }}" class="position-back-link">
                              <i data-feather="arrow-left"></i>
                              <span>Kembali ke Daftar</span>
                         </a>
                    </div>
               </section>

               <form method="POST" class="w-100" action="{{ route('super-admin.positions.update', $position) }}"
                    autocomplete="off">
                    @csrf
                    @method('PUT')

                    <section class="position-form-card">
                         <header class="position-form-card-header">
                              <h2 class="position-form-card-title">
                                   <span><i data-feather="briefcase"></i></span>
                                   <span>Perbarui Informasi Jabatan</span>
                              </h2>

                              <p class="position-form-card-subtitle">
                                   Terakhir diperbarui:
                                   {{ $position->updated_at?->format('d M Y, H:i') ?? '—' }} WIB
                              </p>
                         </header>

                         <div class="position-form-card-body">
                              @if ($errors->any())
                                   <div class="position-error-summary">
                                        <i data-feather="alert-circle"></i>

                                        <div>
                                             <strong>Periksa kembali data yang Anda masukkan.</strong>
                                             <ul>
                                                  @foreach ($errors->all() as $error)
                                                       <li>{{ $error }}</li>
                                                  @endforeach
                                             </ul>
                                        </div>
                                   </div>
                              @endif

                              <div class="position-section">
                                   <div class="position-section-heading">
                                        <span><i data-feather="info"></i></span>
                                        <strong>Data Utama</strong>
                                   </div>

                                   <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                             <label for="department_id" class="position-label">
                                                  Departemen <span class="position-required">*</span>
                                             </label>

                                             <select id="department_id" name="department_id"
                                                  class="form-select position-control @error('department_id') is-invalid @enderror"
                                                  required>
                                                  <option value="">Pilih departemen</option>

                                                  @foreach ($departments as $department)
                                                       <option value="{{ $department->id }}" @selected((string) old('department_id', $position->department_id) === (string) $department->id)>
                                                            {{ $department->code }} — {{ $department->name }}
                                                            @if ($department->status === \App\Models\Department::STATUS_INACTIVE)
                                                                 (Tidak Aktif)
                                                            @endif
                                                       </option>
                                                  @endforeach
                                             </select>

                                             @error('department_id')
                                                  <span class="position-invalid-feedback">{{ $message }}</span>
                                             @enderror
                                        </div>

                                        <div class="col-12 col-md-6">
                                             <label for="code" class="position-label">
                                                  Kode Jabatan <span class="position-required">*</span>
                                             </label>

                                             <div class="position-input-shell">
                                                  <i data-feather="hash"></i>

                                                  <input type="text" id="code" name="code" maxlength="30"
                                                       value="{{ old('code', $position->code) }}"
                                                       class="form-control position-control @error('code') is-invalid @enderror"
                                                       required>
                                             </div>

                                             <small class="position-help">
                                                  Kode harus unik dan maksimal 30 karakter.
                                             </small>

                                             @error('code')
                                                  <span class="position-invalid-feedback">{{ $message }}</span>
                                             @enderror
                                        </div>

                                        <div class="col-12 col-md-8">
                                             <label for="name" class="position-label">
                                                  Nama Jabatan <span class="position-required">*</span>
                                             </label>

                                             <div class="position-input-shell">
                                                  <i data-feather="briefcase"></i>

                                                  <input type="text" id="name" name="name" maxlength="150"
                                                       value="{{ old('name', $position->name) }}"
                                                       class="form-control position-control @error('name') is-invalid @enderror"
                                                       required>
                                             </div>

                                             @error('name')
                                                  <span class="position-invalid-feedback">{{ $message }}</span>
                                             @enderror
                                        </div>

                                        <div class="col-12 col-md-4">
                                             <label for="level" class="position-label">
                                                  Level Jabatan <span class="position-required">*</span>
                                             </label>

                                             <div class="position-input-shell">
                                                  <i data-feather="bar-chart-2"></i>

                                                  <input type="number" id="level" name="level" min="1"
                                                       max="65535" value="{{ old('level', $position->level) }}"
                                                       class="form-control position-control @error('level') is-invalid @enderror"
                                                       required>
                                             </div>

                                             @error('level')
                                                  <span class="position-invalid-feedback">{{ $message }}</span>
                                             @enderror
                                        </div>
                                   </div>
                              </div>

                              <div class="position-section">
                                   <div class="position-section-heading">
                                        <span><i data-feather="file-text"></i></span>
                                        <strong>Deskripsi Jabatan</strong>
                                   </div>

                                   <label for="description" class="position-label">
                                        Deskripsi
                                        <span id="description-count" class="position-char-count">0 karakter</span>
                                   </label>

                                   <textarea id="description" name="description"
                                        class="form-control position-control @error('description') is-invalid @enderror"
                                        placeholder="Tuliskan tanggung jawab atau gambaran singkat jabatan...">{{ old('description', $position->description) }}</textarea>

                                   @error('description')
                                        <span class="position-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>

                              <div class="position-section">
                                   <div class="position-section-heading">
                                        <span><i data-feather="activity"></i></span>
                                        <strong>Status Jabatan</strong>
                                   </div>

                                   @php
                                        $selectedStatus = old('status', $position->status);
                                   @endphp

                                   <div class="position-status-options">
                                        <div class="position-status-option">
                                             <input type="radio" id="status-active" name="status"
                                                  value="{{ \App\Models\Position::STATUS_ACTIVE }}"
                                                  @checked($selectedStatus === \App\Models\Position::STATUS_ACTIVE)>

                                             <label for="status-active" class="position-status-label position-status-active">
                                                  <span class="position-status-icon">
                                                       <i data-feather="check-circle"></i>
                                                  </span>

                                                  <span class="position-status-text">
                                                       <strong>Aktif</strong>
                                                       <small>Jabatan dapat digunakan pada data karyawan.</small>
                                                  </span>
                                             </label>
                                        </div>

                                        <div class="position-status-option">
                                             <input type="radio" id="status-inactive" name="status"
                                                  value="{{ \App\Models\Position::STATUS_INACTIVE }}"
                                                  @checked($selectedStatus === \App\Models\Position::STATUS_INACTIVE)>

                                             <label for="status-inactive"
                                                  class="position-status-label position-status-inactive">
                                                  <span class="position-status-icon">
                                                       <i data-feather="x-circle"></i>
                                                  </span>

                                                  <span class="position-status-text">
                                                       <strong>Tidak Aktif</strong>
                                                       <small>Jabatan disimpan tetapi tidak digunakan sementara.</small>
                                                  </span>
                                             </label>
                                        </div>
                                   </div>

                                   @error('status')
                                        <span class="position-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <footer class="position-form-footer">
                              <a href="{{ route('super-admin.positions.show', $position) }}"
                                   class="position-btn position-btn-secondary">
                                   <i data-feather="x"></i>
                                   <span>Batal</span>
                              </a>

                              <button type="submit" class="position-btn position-btn-primary">
                                   <i data-feather="save"></i>
                                   <span>Simpan Perubahan</span>
                              </button>
                         </footer>
                    </section>
               </form>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const codeInput = document.getElementById('code');
                    const descriptionInput = document.getElementById('description');
                    const descriptionCount = document.getElementById('description-count');

                    if (codeInput) {
                         codeInput.addEventListener('input', function() {
                              this.value = this.value
                                   .toUpperCase()
                                   .replace(/[^A-Z0-9_-]/g, '');
                         });
                    }

                    const updateDescriptionCount = function() {
                         if (descriptionInput && descriptionCount) {
                              descriptionCount.textContent =
                                   descriptionInput.value.length + ' karakter';
                         }
                    };

                    descriptionInput?.addEventListener('input', updateDescriptionCount);
                    updateDescriptionCount();

                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
