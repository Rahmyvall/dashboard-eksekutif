@extends('layouts.app')

@section('page-title', 'Edit Service')

@section('content')
     <style>
          .edit-service-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 48px;
               background: radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .18), transparent 24%), linear-gradient(145deg, #fbfdff, #f7f5ff 52%, #f0fbff);
          }

          .edit-service-container {
               max-width: 1440px;
               margin: 0 auto;
          }

          .edit-service-hero {
               position: relative;
               overflow: hidden;
               padding: 30px 34px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background: linear-gradient(120deg, #4f46e5, #7c3aed 50%, #0891b2);
               box-shadow: 0 22px 52px rgba(79, 70, 229, .21);
          }

          .edit-service-hero-inner,
          .edit-service-heading,
          .edit-service-actions {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
          }

          .edit-service-heading {
               justify-content: flex-start;
          }

          .edit-service-icon {
               display: inline-flex;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               font-size: 1.6rem;
               border-radius: 19px;
               background: rgba(255, 255, 255, .95);
               color: #4f46e5;
          }

          .edit-service-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 3vw, 2.25rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .edit-service-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .9);
          }

          .edit-back,
          .edit-btn {
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
          }

          .edit-back {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .35);
               background: rgba(255, 255, 255, .13);
          }

          .edit-back:hover {
               color: #fff;
               background: rgba(255, 255, 255, .22);
          }

          .edit-layout {
               display: grid;
               grid-template-columns: minmax(0, 1fr) 320px;
               gap: 22px;
               align-items: start;
          }

          .edit-card,
          .edit-side {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 23px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .08);
          }

          .edit-card-header {
               padding: 21px 26px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff, #faf8ff 50%, #f0fbff);
          }

          .edit-card-header h5 {
               margin: 0;
               color: #24324a;
               font-weight: 830;
          }

          .edit-card-header p {
               margin: 5px 0 0;
               color: #718096;
               font-size: .8rem;
          }

          .edit-card-body {
               padding: 28px;
          }

          .edit-section {
               padding-bottom: 25px;
               margin-bottom: 25px;
               border-bottom: 1px solid #eef2f7;
          }

          .edit-section-title {
               margin-bottom: 18px;
               color: #334155;
               font-size: .92rem;
               font-weight: 820;
          }

          .edit-section-title i {
               margin-right: 8px;
               color: #6366f1;
          }

          .edit-label {
               margin-bottom: 7px;
               color: #475569;
               font-size: .84rem;
               font-weight: 780;
          }

          .edit-control {
               min-height: 48px;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
          }

          textarea.edit-control {
               min-height: 145px;
               resize: vertical;
          }

          .edit-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .13);
          }

          .edit-code {
               color: #64748b;
               background: #f8fafc;
          }

          .edit-help {
               margin-top: 6px;
               color: #94a3b8;
               font-size: .75rem;
          }

          .edit-actions {
               display: flex;
               gap: 10px;
               justify-content: flex-end;
               padding-top: 23px;
          }

          .edit-btn-primary {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
          }

          .edit-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .edit-side {
               position: sticky;
               top: 20px;
               overflow: visible;
          }

          .edit-side-block {
               padding: 21px;
               border-bottom: 1px solid #eef2f7;
          }

          .edit-side-block:last-child {
               border-bottom: 0;
          }

          .edit-side-title {
               margin-bottom: 14px;
               color: #24324a;
               font-size: .93rem;
               font-weight: 830;
          }

          .edit-identity {
               padding: 18px;
               color: #fff;
               border-radius: 17px;
               background: linear-gradient(135deg, #4f46e5, #7c3aed 55%, #0891b2);
          }

          .edit-identity-code {
               margin-bottom: 7px;
               font-size: .72rem;
               font-weight: 750;
               letter-spacing: .08em;
               opacity: .8;
          }

          .edit-identity-name {
               font-size: 1.15rem;
               font-weight: 830;
          }

          .edit-identity-category {
               margin-top: 6px;
               font-size: .8rem;
               opacity: .84;
          }

          .edit-live-note {
               margin-top: 12px;
               color: #94a3b8;
               font-size: .74rem;
               line-height: 1.5;
          }

          .edit-detail-list {
               display: grid;
               gap: 11px;
               margin: 0;
               padding: 0;
               list-style: none;
          }

          .edit-detail-list li {
               display: flex;
               justify-content: space-between;
               gap: 10px;
               color: #64748b;
               font-size: .8rem;
          }

          .edit-detail-list strong {
               color: #1e293b;
               text-align: right;
          }

          @media (max-width: 991.98px) {
               .edit-layout {
                    grid-template-columns: 1fr;
               }

               .edit-side {
                    position: static;
               }
          }

          @media (max-width: 767.98px) {
               .edit-service-page {
                    padding: 20px 12px 34px;
               }

               .edit-service-hero {
                    padding: 24px 20px;
               }

               .edit-service-hero-inner {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .edit-card-body {
                    padding: 21px 18px;
               }

               .edit-actions {
                    flex-direction: column-reverse;
               }

               .edit-btn {
                    width: 100%;
               }
          }
     </style>

     <div class="edit-service-page">
          <div class="edit-service-container">
               <div class="edit-service-hero">
                    <div class="edit-service-hero-inner">
                         <div class="edit-service-heading"><span class="edit-service-icon"><i
                                        class="bi bi-pencil-square"></i></span>
                              <div>
                                   <h1>Edit Service</h1>
                                   <p>Perbarui informasi layanan tanpa mengubah kode service otomatis.</p>
                              </div>
                         </div><a href="{{ route('super-admin.services.show', $service) }}" class="edit-back"><i
                                   class="bi bi-arrow-left"></i> Kembali ke Detail</a>
                    </div>
               </div>

               @if ($errors->any())
                    <div class="alert alert-danger" role="alert"><strong><i
                                   class="bi bi-exclamation-octagon-fill me-2"></i>Periksa data berikut:</strong>
                         <ul class="mb-0 mt-2">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <div class="edit-layout">
                    <div class="edit-card">
                         <div class="edit-card-header">
                              <h5><i class="bi bi-sliders me-2 text-primary"></i>Informasi Service</h5>
                              <p>Field bertanda <span class="text-danger">*</span> wajib diisi.</p>
                         </div>
                         <div class="edit-card-body">
                              <form method="POST" action="{{ route('super-admin.services.update', $service) }}">@csrf
                                   @method('PUT')
                                   <div class="edit-section">
                                        <div class="edit-section-title"><i class="bi bi-card-text"></i>Identitas Service
                                        </div>
                                        <div class="row g-4">
                                             <div class="col-md-6"><label class="edit-label" for="service_code">Kode
                                                       Service</label><input id="service_code" name="service_code"
                                                       value="{{ old('service_code', $service->service_code) }}"
                                                       class="form-control edit-control edit-code" readonly required>
                                                  <div class="edit-help">Kode dibuat otomatis oleh sistem.</div>
                                             </div>
                                             <div class="col-md-6"><label class="edit-label"
                                                       for="service_category_id">Kategori Service <span
                                                            class="text-danger">*</span></label><select
                                                       id="service_category_id" name="service_category_id"
                                                       class="form-select edit-control @error('service_category_id') is-invalid @enderror"
                                                       required>
                                                       <option value="">Pilih kategori</option>
                                                       @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @selected(old('service_category_id', $service->service_category_id) == $category->id)>
                                                                 {{ $category->code }} — {{ $category->name }}</option>
                                                       @endforeach
                                                  </select>
                                                  @error('service_category_id')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-12"><label class="edit-label" for="name">Nama Service <span
                                                            class="text-danger">*</span></label><input id="name"
                                                       name="name" value="{{ old('name', $service->name) }}"
                                                       class="form-control edit-control @error('name') is-invalid @enderror"
                                                       maxlength="150" required>
                                                  @error('name')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-12"><label class="edit-label"
                                                       for="description">Deskripsi</label>
                                                  <textarea id="description" name="description"
                                                       class="form-control edit-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                                                  @error('description')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>
                                   <div class="edit-section">
                                        <div class="edit-section-title"><i class="bi bi-cash-coin"></i>Harga dan Durasi</div>
                                        <div class="row g-4">
                                             <div class="col-md-4"><label class="edit-label" for="base_price">Harga Dasar
                                                       <span class="text-danger">*</span></label>
                                                  <div class="input-group"><span class="input-group-text">Rp</span><input
                                                            type="number" id="base_price" name="base_price"
                                                            value="{{ old('base_price', $service->base_price) }}"
                                                            class="form-control edit-control @error('base_price') is-invalid @enderror"
                                                            min="0" step="0.01" required></div>
                                                  @error('base_price')
                                                       <div class="text-danger small mt-1">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                             <div class="col-md-4"><label class="edit-label"
                                                       for="estimated_duration_minutes">Estimasi Durasi</label>
                                                  <div class="input-group"><input type="number"
                                                            id="estimated_duration_minutes" name="estimated_duration_minutes"
                                                            value="{{ old('estimated_duration_minutes', $service->estimated_duration_minutes) }}"
                                                            class="form-control edit-control" min="1"><span
                                                            class="input-group-text">menit</span></div>
                                             </div>
                                             <div class="col-md-4"><label class="edit-label" for="unit">Unit <span
                                                            class="text-danger">*</span></label><input type="text"
                                                       id="unit" name="unit"
                                                       value="{{ old('unit', $service->unit) }}"
                                                       class="form-control edit-control @error('unit') is-invalid @enderror"
                                                       maxlength="50" required>
                                                  @error('unit')
                                                       <div class="invalid-feedback">{{ $message }}</div>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row g-4">
                                        <div class="col-md-6"><label class="edit-label" for="status">Status <span
                                                       class="text-danger">*</span></label><select id="status"
                                                  name="status" class="form-select edit-control" required>
                                                  @foreach ($statuses as $statusValue => $statusLabel)
                                                       <option value="{{ $statusValue }}" @selected(old('status', $service->status) === $statusValue)>
                                                            {{ $statusLabel }}</option>
                                                  @endforeach
                                             </select></div>
                                        <div class="col-md-6">
                                             <div class="alert alert-info mb-0"><i class="bi bi-info-circle-fill me-1"></i>
                                                  Perubahan akan langsung disimpan ke database.</div>
                                        </div>
                                   </div>
                                   <div class="edit-actions"><a href="{{ route('super-admin.services.show', $service) }}"
                                             class="edit-btn edit-btn-secondary">Batal</a><button type="submit"
                                             class="edit-btn edit-btn-primary"><i class="bi bi-check-circle-fill"></i>
                                             Simpan Perubahan</button></div>
                              </form>
                         </div>
                    </div>

                    <aside class="edit-side">
                         <div class="edit-side-block">
                              <div class="edit-side-title"><i class="bi bi-eye-fill text-primary me-1"></i>Ringkasan Saat
                                   Ini</div>
                              <div class="edit-identity">
                                   <div class="edit-identity-code">{{ $service->service_code }}</div>
                                   <div class="edit-identity-name" id="edit-preview-name">{{ $service->name }}</div>
                                   <div class="edit-identity-category"><i
                                             class="bi bi-tag-fill me-1"></i>{{ $service->category?->name ?? 'Tanpa kategori' }}
                                   </div>
                              </div>
                         </div>
                         <div class="edit-side-block">
                              <div class="edit-side-title"><i class="bi bi-list-check text-info me-1"></i>Detail Tersimpan
                              </div>
                              <ul class="edit-detail-list">
                                   <li>Harga <strong id="edit-preview-price">{{ $service->formatted_price }}</strong></li>
                                   <li>Unit <strong>{{ $service->unit }}</strong></li>
                                   <li>Durasi
                                        <strong>{{ $service->estimated_duration_minutes ? $service->estimated_duration_minutes . ' menit' : '-' }}</strong>
                                   </li>
                                   <li>Status <strong>{{ $service->status_label }}</strong></li>
                                   <li>Dibuat <strong>{{ optional($service->created_at)->format('d M Y H:i') }}</strong>
                                   </li>
                              </ul>
                         </div>
                         <div class="edit-live-note"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Preview
                              diperbarui otomatis saat data form berubah.</div>
                    </aside>
               </div>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const nameInput = document.getElementById('name');
               const priceInput = document.getElementById('base_price');
               const previewName = document.getElementById('edit-preview-name');
               const previewPrice = document.getElementById('edit-preview-price');

               function updateEditPreview() {
                    previewName.textContent = nameInput.value.trim() || 'Nama service';
                    previewPrice.textContent = 'Rp ' + Number(priceInput.value || 0).toLocaleString('id-ID');
               }

               nameInput.addEventListener('input', updateEditPreview);
               priceInput.addEventListener('input', updateEditPreview);
          });
     </script>
@endsection
