@extends('layouts.app')

@section('page-title', 'Tambah Service')

@section('content')
     <style>
          .service-form-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 48px;
               background: radial-gradient(circle at 6% 5%, rgba(129, 140, 248, .18), transparent 24%), radial-gradient(circle at 95% 8%, rgba(34, 211, 238, .16), transparent 25%), linear-gradient(145deg, #fbfdff, #f7f5ff 52%, #f0fbff);
          }

          .service-form-container {
               max-width: 1440px;
               margin: 0 auto;
          }

          .service-form-hero {
               position: relative;
               overflow: hidden;
               padding: 30px 34px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background: radial-gradient(circle at 88% 18%, rgba(255, 255, 255, .3), transparent 23%), linear-gradient(120deg, #6366f1, #7c3aed 48%, #0891b2);
               box-shadow: 0 22px 52px rgba(79, 70, 229, .21);
          }

          .service-form-hero::after {
               position: absolute;
               right: 8%;
               bottom: -120px;
               width: 240px;
               height: 240px;
               content: '';
               border: 28px solid rgba(255, 255, 255, .1);
               border-radius: 50%;
          }

          .service-form-hero-inner {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 20px;
          }

          .service-form-title {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .service-form-icon {
               display: inline-flex;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.65rem;
               border-radius: 19px;
               background: rgba(255, 255, 255, .95);
          }

          .service-form-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 3vw, 2.25rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .service-form-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .9);
          }

          .back-service {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #fff;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .35);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
          }

          .back-service:hover {
               color: #fff;
               background: rgba(255, 255, 255, .22);
          }

          .service-form-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .service-create-layout {
               display: grid;
               grid-template-columns: minmax(0, 1fr) 330px;
               gap: 22px;
               align-items: start;
          }

          .service-side-column {
               display: grid;
               gap: 18px;
               position: sticky;
               top: 20px;
          }

          .service-side-card {
               padding: 21px;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 21px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 34px rgba(51, 65, 85, .07);
          }

          .service-side-title {
               display: flex;
               gap: 9px;
               align-items: center;
               margin-bottom: 17px;
               color: #24324a;
               font-size: .94rem;
               font-weight: 830;
          }

          .service-preview {
               position: relative;
               overflow: hidden;
               padding: 19px;
               color: #fff;
               border-radius: 18px;
               background: linear-gradient(135deg, #4f46e5, #7c3aed 55%, #0891b2);
          }

          .service-preview::after {
               position: absolute;
               right: -30px;
               bottom: -45px;
               width: 120px;
               height: 120px;
               content: '';
               border: 17px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .preview-label {
               position: relative;
               z-index: 1;
               margin-bottom: 8px;
               font-size: .68rem;
               font-weight: 800;
               letter-spacing: .1em;
               opacity: .76;
               text-transform: uppercase;
          }

          .preview-name {
               position: relative;
               z-index: 1;
               min-height: 27px;
               font-size: 1.15rem;
               font-weight: 820;
          }

          .preview-code {
               position: relative;
               z-index: 1;
               margin-top: 5px;
               font-size: .78rem;
               opacity: .84;
          }

          .preview-divider {
               height: 1px;
               margin: 17px 0;
               background: rgba(255, 255, 255, .22);
          }

          .preview-meta {
               position: relative;
               z-index: 1;
               display: flex;
               justify-content: space-between;
               gap: 10px;
               font-size: .78rem;
          }

          .preview-price {
               font-size: 1.05rem;
               font-weight: 850;
          }

          .preview-status {
               padding: 5px 9px;
               font-weight: 750;
               border-radius: 999px;
               background: rgba(255, 255, 255, .16);
          }

          .service-tips {
               display: grid;
               gap: 13px;
               margin: 0;
               padding: 0;
               list-style: none;
          }

          .service-tip {
               display: flex;
               gap: 10px;
               color: #64748b;
               font-size: .8rem;
               line-height: 1.55;
          }

          .service-tip i {
               flex: 0 0 20px;
               color: #6366f1;
               font-size: .95rem;
          }

          .service-schema-list {
               display: grid;
               gap: 9px;
               margin: 0;
               padding: 0;
               list-style: none;
          }

          .service-schema-list li {
               display: flex;
               align-items: center;
               justify-content: space-between;
               color: #64748b;
               font-size: .77rem;
          }

          .service-schema-list code {
               padding: 3px 7px;
               color: #4338ca;
               border-radius: 6px;
               background: #eef2ff;
          }

          .service-schema-list span {
               color: #94a3b8;
          }

          .service-form-header {
               padding: 21px 26px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff, #faf8ff 50%, #f0fbff);
          }

          .service-form-header h5 {
               margin: 0;
               color: #24324a;
               font-size: 1.05rem;
               font-weight: 830;
          }

          .service-form-header p {
               margin: 5px 0 0;
               color: #718096;
               font-size: .8rem;
          }

          .service-form-body {
               padding: 28px;
          }

          .field-label {
               margin-bottom: 7px;
               color: #475569;
               font-size: .84rem;
               font-weight: 780;
          }

          .required {
               color: #ef4444;
          }

          .field-control {
               min-height: 48px;
               color: #24324a;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
          }

          textarea.field-control {
               min-height: 145px;
               resize: vertical;
          }

          .field-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .13);
          }

          .field-help {
               margin-top: 6px;
               color: #94a3b8;
               font-size: .75rem;
          }

          .form-section {
               padding-bottom: 25px;
               margin-bottom: 25px;
               border-bottom: 1px solid #eef2f7;
          }

          .form-section-title {
               display: flex;
               gap: 9px;
               align-items: center;
               margin-bottom: 18px;
               color: #334155;
               font-size: .92rem;
               font-weight: 820;
          }

          .form-section-title i {
               color: #6366f1;
          }

          .service-note {
               padding: 15px 17px;
               color: #075985;
               font-size: .8rem;
               line-height: 1.6;
               border: 1px solid #bae6fd;
               border-radius: 14px;
               background: #f0f9ff;
          }

          .service-form-actions {
               display: flex;
               gap: 10px;
               justify-content: flex-end;
               padding-top: 23px;
          }

          .form-btn {
               display: inline-flex;
               min-height: 47px;
               padding: 0 19px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .85rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
          }

          .form-btn-primary {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
          }

          .form-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .alert-form {
               border: 0;
               border-radius: 15px;
          }

          @media (max-width: 767.98px) {
               .service-form-page {
                    padding: 20px 12px 34px;
               }

               .service-form-hero {
                    padding: 24px 20px;
               }

               .service-form-hero-inner {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .service-create-layout {
                    grid-template-columns: 1fr;
               }

               .service-side-column {
                    position: static;
                    grid-row: 1;
               }

               .service-form-body {
                    padding: 21px 18px;
               }

               .service-form-actions {
                    flex-direction: column-reverse;
               }

               .form-btn {
                    width: 100%;
               }
          }
     </style>

     <div class="service-form-page">
          <div class="service-form-container">
               <div class="service-form-hero">
                    <div class="service-form-hero-inner">
                         <div class="service-form-title">
                              <span class="service-form-icon"><i class="bi bi-plus-circle-fill"></i></span>
                              <div>
                                   <h1>Tambah Service Baru</h1>
                                   <p>Isi informasi layanan sesuai struktur tabel <strong>services</strong>.</p>
                              </div>
                         </div>
                         <a href="{{ route('super-admin.services.index') }}" class="back-service"><i
                                   class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
                    </div>
               </div>

               @if ($errors->any())
                    <div class="alert alert-danger alert-form" role="alert"><strong><i
                                   class="bi bi-exclamation-octagon-fill me-2"></i>Periksa kembali data berikut:</strong>
                         <ul class="mb-0 mt-2">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <div class="service-create-layout">
                    <div class="service-form-card">
                         <div class="service-form-header">
                              <h5><i class="bi bi-pencil-square me-2 text-primary"></i>Informasi Service</h5>
                              <p>Field bertanda <span class="required">*</span> wajib diisi.</p>
                         </div>
                         <div class="service-form-body">
                              <form method="POST" action="{{ route('super-admin.services.store') }}">
                                   @csrf
                                   <div class="form-section">
                                        <div class="form-section-title"><i class="bi bi-card-text"></i> Identitas Service
                                        </div>
                                        <div class="row g-4">
                                             <div class="col-md-6"><label for="service_code" class="field-label">Kode Service
                                                       <span class="required">*</span></label><input type="text"
                                                       id="service_code" name="service_code"
                                                       value="{{ old('service_code', \App\Models\Service::nextServiceCode()) }}"
                                                       class="form-control field-control @error('service_code') is-invalid @enderror"
                                                       maxlength="50" placeholder="SVC-001" readonly required>
                                                  @error('service_code')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                                  <div class="field-help">
                                                       Kode dibuat otomatis oleh sistem dan tidak dapat diubah.</div>
                                             </div>
                                             <div class="col-md-6"><label for="service_category_id"
                                                       class="field-label">Kategori
                                                       Service <span class="required">*</span></label><select
                                                       id="service_category_id" name="service_category_id"
                                                       class="form-select field-control @error('service_category_id') is-invalid @enderror"
                                                       required>
                                                       <option value="">Pilih kategori service</option>
                                                       @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @selected(old('service_category_id') == $category->id)>
                                                                 {{ $category->code }} — {{ $category->name }}</option>
                                                       @endforeach
                                                  </select>
                                                  @error('service_category_id')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-12"><label for="name" class="field-label">Nama Service <span
                                                            class="required">*</span></label><input type="text"
                                                       id="name" name="name" value="{{ old('name') }}"
                                                       class="form-control field-control @error('name') is-invalid @enderror"
                                                       maxlength="150" placeholder="Masukkan nama layanan" required>
                                                  @error('name')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-12"><label for="description"
                                                       class="field-label">Deskripsi</label>
                                                  <textarea id="description" name="description"
                                                       class="form-control field-control @error('description') is-invalid @enderror"
                                                       placeholder="Jelaskan layanan secara singkat...">{{ old('description') }}</textarea>
                                                  @error('description')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>

                                   <div class="form-section">
                                        <div class="form-section-title"><i class="bi bi-cash-coin"></i> Harga dan Durasi
                                        </div>
                                        <div class="row g-4">
                                             <div class="col-md-4"><label for="base_price" class="field-label">Harga Dasar
                                                       <span class="required">*</span></label>
                                                  <div class="input-group"><span class="input-group-text">Rp</span><input
                                                            type="number" id="base_price" name="base_price"
                                                            value="{{ old('base_price', 0) }}"
                                                            class="form-control field-control @error('base_price') is-invalid @enderror"
                                                            min="0" step="0.01" required></div>
                                                  @error('base_price')
                                                       <div class="text-danger small mt-1">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-md-4"><label for="estimated_duration_minutes"
                                                       class="field-label">Estimasi Durasi</label>
                                                  <div class="input-group"><input type="number"
                                                            id="estimated_duration_minutes" name="estimated_duration_minutes"
                                                            value="{{ old('estimated_duration_minutes') }}"
                                                            class="form-control field-control @error('estimated_duration_minutes') is-invalid @enderror"
                                                            min="1" placeholder="Contoh: 60"><span
                                                            class="input-group-text">menit</span></div>
                                                  @error('estimated_duration_minutes')
                                                       <div class="text-danger small mt-1">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-md-4"><label for="unit" class="field-label">Unit <span
                                                            class="required">*</span></label><input type="text"
                                                       id="unit" name="unit" value="{{ old('unit', 'service') }}"
                                                       class="form-control field-control @error('unit') is-invalid @enderror"
                                                       maxlength="50" placeholder="service, paket, jam..." required>
                                                  @error('unit')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>

                                   <div class="row g-4 align-items-end">
                                        <div class="col-md-6"><label for="status" class="field-label">Status <span
                                                       class="required">*</span></label><select id="status"
                                                  name="status"
                                                  class="form-select field-control @error('status') is-invalid @enderror"
                                                  required>
                                                  @foreach ($statuses as $statusValue => $statusLabel)
                                                       <option value="{{ $statusValue }}" @selected(old('status', 'active') === $statusValue)>
                                                            {{ $statusLabel }}</option>
                                                  @endforeach
                                             </select>
                                             @error('status')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                        </div>
                                        <div class="col-md-6">
                                             <div class="service-note"><i class="bi bi-info-circle-fill me-1"></i> Service
                                                  berstatus aktif dapat digunakan pada transaksi dan pemesanan layanan.</div>
                                        </div>
                                   </div>

                                   <div class="service-form-actions"><a href="{{ route('super-admin.services.index') }}"
                                             class="form-btn form-btn-secondary"><i class="bi bi-x-circle"></i>
                                             Batal</a><button type="submit" class="form-btn form-btn-primary"><i
                                                  class="bi bi-check-circle-fill"></i> Simpan Service</button></div>
                              </form>
                         </div>
                    </div>

                    <aside class="service-side-column">
                         <div class="service-side-card">
                              <div class="service-side-title"><i class="bi bi-eye-fill text-primary"></i> Preview Service
                              </div>
                              <div class="service-preview">
                                   <div class="preview-label">Service baru</div>
                                   <div class="preview-name" id="preview-name">Nama service</div>
                                   <div class="preview-code" id="preview-code">SVC-000</div>
                                   <div class="preview-divider"></div>
                                   <div class="preview-meta"><span class="preview-price" id="preview-price">Rp
                                             0</span><span class="preview-status" id="preview-status">Aktif</span></div>
                              </div>
                         </div>

                         <div class="service-side-card">
                              <div class="service-side-title"><i class="bi bi-lightbulb-fill text-warning"></i> Tips
                                   Pengisian</div>
                              <ul class="service-tips">
                                   <li class="service-tip"><i class="bi bi-check-circle-fill"></i><span>Gunakan kode unik
                                             dan konsisten, contohnya <strong>SVC-001</strong>.</span></li>
                                   <li class="service-tip"><i class="bi bi-check-circle-fill"></i><span>Harga dasar dapat
                                             diubah saat transaksi sesuai kebijakan perusahaan.</span></li>
                                   <li class="service-tip"><i class="bi bi-check-circle-fill"></i><span>Service inactive
                                             tidak disarankan untuk transaksi baru.</span></li>
                              </ul>
                         </div>

                         <div class="service-side-card">
                              <div class="service-side-title"><i class="bi bi-database-fill text-info"></i> Kolom Database
                              </div>
                              <ul class="service-schema-list">
                                   <li><code>service_code</code><span>varchar(50)</span></li>
                                   <li><code>base_price</code><span>decimal(15,2)</span></li>
                                   <li><code>unit</code><span>varchar(50)</span></li>
                                   <li><code>status</code><span>active/inactive</span></li>
                              </ul>
                         </div>
                    </aside>
               </div>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const nameInput = document.getElementById('name');
               const codeInput = document.getElementById('service_code');
               const priceInput = document.getElementById('base_price');
               const statusInput = document.getElementById('status');
               const previewName = document.getElementById('preview-name');
               const previewCode = document.getElementById('preview-code');
               const previewPrice = document.getElementById('preview-price');
               const previewStatus = document.getElementById('preview-status');

               const updatePreview = function() {
                    previewName.textContent = nameInput.value.trim() || 'Nama service';
                    previewCode.textContent = codeInput.value.trim().toUpperCase() || 'SVC-000';
                    previewPrice.textContent = 'Rp ' + Number(priceInput.value || 0).toLocaleString('id-ID');
                    previewStatus.textContent = statusInput.options[statusInput.selectedIndex]?.text ||
                         'Aktif';
               };

               [nameInput, codeInput, priceInput, statusInput].forEach(function(input) {
                    input.addEventListener('input', updatePreview);
                    input.addEventListener('change', updatePreview);
               });
               updatePreview();
          });
     </script>
@endsection
