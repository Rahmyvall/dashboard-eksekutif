@php
     $isEdit = isset($performanceIndicator) && $performanceIndicator->exists;

     $formAction = $isEdit
         ? route('super-admin.performance-indicators.update', $performanceIndicator)
         : route('super-admin.performance-indicators.store');

     $selectedDirection = old(
         'target_direction',
         $isEdit
             ? $performanceIndicator->target_direction
             : $defaultDirection ?? \App\Models\PerformanceIndicator::DIRECTION_INCREASE,
     );

     $selectedStatus = old(
         'status',
         $isEdit ? $performanceIndicator->status : $defaultStatus ?? \App\Models\PerformanceIndicator::STATUS_ACTIVE,
     );

     $cancelRoute = $isEdit
         ? route('super-admin.performance-indicators.show', $performanceIndicator)
         : route('super-admin.performance-indicators.index');
@endphp

<style>
     .indicator-form-grid {
          display: grid;
          grid-template-columns: minmax(0, 1fr) 330px;
          gap: 22px;
          align-items: start;
     }

     .indicator-form-card,
     .indicator-side-card {
          overflow: hidden;
          border: 1px solid rgba(226, 232, 240, .92);
          border-radius: 24px;
          background: rgba(255, 255, 255, .96);
          box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
     }

     .indicator-form-header {
          display: flex;
          gap: 14px;
          align-items: center;
          padding: 22px 24px;
          border-bottom: 1px solid #edf2f7;
          background: linear-gradient(90deg, #fff 0%, #faf8ff 48%, #f0fbff 100%);
     }

     .indicator-form-icon {
          display: inline-flex;
          width: 46px;
          height: 46px;
          flex: 0 0 46px;
          align-items: center;
          justify-content: center;
          color: #4f46e5;
          font-size: 1.25rem;
          border-radius: 14px;
          background: linear-gradient(135deg, #eef2ff, #e0f2fe);
     }

     .indicator-form-title {
          margin: 0;
          color: #24324a;
          font-size: 1.08rem;
          font-weight: 850;
     }

     .indicator-form-subtitle {
          margin: 4px 0 0;
          color: #718096;
          font-size: .81rem;
          line-height: 1.6;
     }

     .indicator-form-body {
          padding: 26px 24px 10px;
     }

     .indicator-field {
          margin-bottom: 22px;
     }

     .indicator-label {
          display: flex;
          gap: 7px;
          align-items: center;
          margin-bottom: 8px;
          color: #334155;
          font-size: .86rem;
          font-weight: 800;
     }

     .indicator-required {
          color: #e11d48;
     }

     .indicator-control {
          min-height: 49px;
          color: #24324a;
          font-size: .9rem;
          border: 1px solid #dbe3ef;
          border-radius: 13px;
          background: #fff;
          box-shadow: none;
          transition: border-color .2s ease, box-shadow .2s ease;
     }

     textarea.indicator-control {
          min-height: 132px;
          resize: vertical;
     }

     .indicator-control:focus {
          border-color: #818cf8;
          box-shadow: 0 0 0 4px rgba(99, 102, 241, .12);
     }

     .indicator-control.is-invalid {
          border-color: #f43f5e;
     }

     .indicator-help {
          margin-top: 7px;
          color: #94a3b8;
          font-size: .75rem;
          line-height: 1.55;
     }

     .indicator-invalid-feedback {
          display: block;
          margin-top: 7px;
          color: #be123c;
          font-size: .76rem;
          font-weight: 700;
     }

     .indicator-input-group {
          position: relative;
     }

     .indicator-input-suffix {
          position: absolute;
          top: 50%;
          right: 15px;
          color: #64748b;
          font-size: .85rem;
          font-weight: 800;
          pointer-events: none;
          transform: translateY(-50%);
     }

     .indicator-input-group .indicator-control {
          padding-right: 48px;
     }

     .indicator-form-footer {
          display: flex;
          gap: 12px;
          align-items: center;
          justify-content: flex-end;
          padding: 20px 24px 24px;
          border-top: 1px solid #edf2f7;
          background: #fbfdff;
     }

     .indicator-btn {
          display: inline-flex;
          min-height: 47px;
          padding: 0 18px;
          gap: 9px;
          align-items: center;
          justify-content: center;
          font-size: .86rem;
          font-weight: 820;
          text-decoration: none;
          border-radius: 13px;
          transition: .2s ease;
     }

     .indicator-btn-primary {
          color: #fff;
          border: 0;
          background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
          box-shadow: 0 12px 24px rgba(99, 102, 241, .18);
     }

     .indicator-btn-primary:hover {
          color: #fff;
          transform: translateY(-1px);
     }

     .indicator-btn-secondary {
          color: #64748b;
          border: 1px solid #dbe3ef;
          background: #fff;
     }

     .indicator-side-card {
          position: sticky;
          top: 88px;
     }

     .indicator-side-header {
          padding: 20px 20px 16px;
          color: #4338ca;
          border-bottom: 1px solid #edf2f7;
          background: linear-gradient(135deg, #eef2ff, #f5f3ff, #ecfeff);
     }

     .indicator-side-header h5 {
          display: flex;
          gap: 9px;
          align-items: center;
          margin: 0;
          font-size: .95rem;
          font-weight: 850;
     }

     .indicator-side-body {
          padding: 18px 20px 22px;
     }

     .indicator-guide-list {
          padding: 0;
          margin: 0;
          list-style: none;
     }

     .indicator-guide-list li {
          display: flex;
          gap: 10px;
          align-items: flex-start;
          padding: 11px 0;
          color: #52627a;
          font-size: .79rem;
          line-height: 1.6;
          border-bottom: 1px dashed #e2e8f0;
     }

     .indicator-guide-list li:last-child {
          border-bottom: 0;
     }

     .indicator-guide-list i {
          margin-top: 3px;
          color: #6366f1;
     }

     .indicator-error-box {
          padding: 16px 18px;
          margin-bottom: 20px;
          color: #b91c1c;
          border-left: 5px solid #ef4444;
          border-radius: 15px;
          background: linear-gradient(135deg, #fff1f2, #fee2e2);
          box-shadow: 0 10px 24px rgba(15, 23, 42, .05);
     }

     .indicator-error-box strong {
          display: block;
          margin-bottom: 8px;
     }

     .indicator-error-box ul {
          margin: 0;
          padding-left: 20px;
          font-size: .82rem;
     }

     @media (max-width: 991.98px) {
          .indicator-form-grid {
               grid-template-columns: 1fr;
          }

          .indicator-side-card {
               position: static;
          }
     }

     @media (max-width: 575.98px) {

          .indicator-form-body,
          .indicator-form-header,
          .indicator-form-footer {
               padding-right: 18px;
               padding-left: 18px;
          }

          .indicator-form-footer {
               align-items: stretch;
               flex-direction: column-reverse;
          }

          .indicator-btn {
               width: 100%;
          }
     }
</style>

@if ($errors->any())
     <div class="indicator-error-box" role="alert">
          <strong>
               <i class="bi bi-exclamation-triangle-fill me-1"></i>
               Data belum dapat disimpan.
          </strong>

          <ul>
               @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
               @endforeach
          </ul>
     </div>
@endif

<form method="POST" action="{{ $formAction }}" novalidate>
     @csrf

     @if ($isEdit)
          @method('PUT')
     @endif

     <div class="indicator-form-grid">
          <div class="indicator-form-card">
               <div class="indicator-form-header">
                    <span class="indicator-form-icon">
                         <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-square-fill' }}"></i>
                    </span>

                    <div>
                         <h2 class="indicator-form-title">
                              {{ $isEdit ? 'Ubah Data Indikator' : 'Data Indikator Baru' }}
                         </h2>
                         <p class="indicator-form-subtitle">
                              Lengkapi kode, nama, satuan, bobot, arah target, dan status indikator.
                         </p>
                    </div>
               </div>

               <div class="indicator-form-body">
                    <div class="row g-3">
                         <div class="col-12 col-lg-5">
                              <div class="indicator-field">
                                   <label for="code" class="indicator-label">
                                        <i class="bi bi-upc-scan"></i>
                                        Kode Indikator
                                        <span class="indicator-required">*</span>
                                   </label>

                                   <input type="text" id="code" name="code"
                                        value="{{ old('code', $isEdit ? $performanceIndicator->code : '') }}"
                                        class="form-control indicator-control @error('code') is-invalid @enderror"
                                        maxlength="30" placeholder="Contoh: KPI-001" autocomplete="off"
                                        oninput="this.value = this.value.toUpperCase()" required>

                                   <div class="indicator-help">
                                        Maksimal 30 karakter. Gunakan huruf, angka, titik, garis bawah, garis miring,
                                        atau tanda hubung.
                                   </div>

                                   @error('code')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <div class="col-12 col-lg-7">
                              <div class="indicator-field">
                                   <label for="name" class="indicator-label">
                                        <i class="bi bi-card-heading"></i>
                                        Nama Indikator
                                        <span class="indicator-required">*</span>
                                   </label>

                                   <input type="text" id="name" name="name"
                                        value="{{ old('name', $isEdit ? $performanceIndicator->name : '') }}"
                                        class="form-control indicator-control @error('name') is-invalid @enderror"
                                        maxlength="150" placeholder="Contoh: Persentase Kehadiran Pegawai"
                                        autocomplete="off" required>

                                   <div class="indicator-help">
                                        Gunakan nama yang spesifik dan mudah dipahami oleh pengguna sistem.
                                   </div>

                                   @error('name')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <div class="col-12">
                              <div class="indicator-field">
                                   <label for="description" class="indicator-label">
                                        <i class="bi bi-text-paragraph"></i>
                                        Deskripsi
                                   </label>

                                   <textarea id="description" name="description"
                                        class="form-control indicator-control @error('description') is-invalid @enderror"
                                        placeholder="Jelaskan tujuan, ruang lingkup, atau cara membaca indikator...">{{ old('description', $isEdit ? $performanceIndicator->description : '') }}</textarea>

                                   <div class="indicator-help">
                                        Deskripsi bersifat opsional, tetapi disarankan agar indikator tidak menimbulkan
                                        penafsiran ganda.
                                   </div>

                                   @error('description')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <div class="col-12 col-md-6">
                              <div class="indicator-field">
                                   <label for="unit" class="indicator-label">
                                        <i class="bi bi-rulers"></i>
                                        Satuan
                                        <span class="indicator-required">*</span>
                                   </label>

                                   <input type="text" id="unit" name="unit"
                                        value="{{ old('unit', $isEdit ? $performanceIndicator->unit : '') }}"
                                        class="form-control indicator-control @error('unit') is-invalid @enderror"
                                        maxlength="50" placeholder="Contoh: %, Hari, Orang, Rupiah" autocomplete="off"
                                        required>

                                   <div class="indicator-help">
                                        Satuan digunakan untuk menjelaskan nilai target dan realisasi.
                                   </div>

                                   @error('unit')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <div class="col-12 col-md-6">
                              <div class="indicator-field">
                                   <label for="weight" class="indicator-label">
                                        <i class="bi bi-percent"></i>
                                        Bobot
                                        <span class="indicator-required">*</span>
                                   </label>

                                   <div class="indicator-input-group">
                                        <input type="number" id="weight" name="weight"
                                             value="{{ old('weight', $isEdit ? $performanceIndicator->weight : '0.00') }}"
                                             class="form-control indicator-control @error('weight') is-invalid @enderror"
                                             min="0" max="100" step="0.01" inputmode="decimal" required>
                                        <span class="indicator-input-suffix">%</span>
                                   </div>

                                   <div class="indicator-help">
                                        Nilai bobot harus berada pada rentang 0 sampai 100 dengan maksimal dua angka
                                        desimal.
                                   </div>

                                   @error('weight')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <div class="col-12 col-md-6">
                              <div class="indicator-field">
                                   <label for="target_direction" class="indicator-label">
                                        <i class="bi bi-signpost-split-fill"></i>
                                        Arah Target
                                        <span class="indicator-required">*</span>
                                   </label>

                                   <select id="target_direction" name="target_direction"
                                        class="form-select indicator-control @error('target_direction') is-invalid @enderror"
                                        required>
                                        <option value="" disabled>Pilih arah target</option>

                                        @foreach ($directionOptions as $value => $label)
                                             <option value="{{ $value }}" @selected($selectedDirection === (string) $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>

                                   <div class="indicator-help">
                                        Menentukan cara sistem menilai apakah realisasi dianggap semakin baik atau harus
                                        tepat sasaran.
                                   </div>

                                   @error('target_direction')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>

                         <div class="col-12 col-md-6">
                              <div class="indicator-field">
                                   <label for="status" class="indicator-label">
                                        <i class="bi bi-toggle-on"></i>
                                        Status
                                        <span class="indicator-required">*</span>
                                   </label>

                                   <select id="status" name="status"
                                        class="form-select indicator-control @error('status') is-invalid @enderror"
                                        required>
                                        <option value="" disabled>Pilih status</option>

                                        @foreach ($statusOptions as $value => $label)
                                             <option value="{{ $value }}" @selected($selectedStatus === (string) $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>

                                   <div class="indicator-help">
                                        Hanya indikator aktif yang sebaiknya digunakan dalam proses penilaian berjalan.
                                   </div>

                                   @error('status')
                                        <span class="indicator-invalid-feedback">{{ $message }}</span>
                                   @enderror
                              </div>
                         </div>
                    </div>
               </div>

               <div class="indicator-form-footer">
                    <a href="{{ $cancelRoute }}" class="indicator-btn indicator-btn-secondary">
                         <i class="bi bi-x-circle"></i>
                         Batal
                    </a>

                    <button type="submit" class="indicator-btn indicator-btn-primary">
                         <i class="bi {{ $isEdit ? 'bi-check2-circle' : 'bi-save2-fill' }}"></i>
                         {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Indikator' }}
                    </button>
               </div>
          </div>

          <aside class="indicator-side-card">
               <div class="indicator-side-header">
                    <h5>
                         <i class="bi bi-lightbulb-fill"></i>
                         Panduan Pengisian
                    </h5>
               </div>

               <div class="indicator-side-body">
                    <ul class="indicator-guide-list">
                         <li>
                              <i class="bi bi-check-circle-fill"></i>
                              <span>Kode indikator harus unik agar tidak tertukar dengan indikator lain.</span>
                         </li>
                         <li>
                              <i class="bi bi-check-circle-fill"></i>
                              <span>Nama indikator harus menggambarkan aspek kinerja yang benar-benar diukur.</span>
                         </li>
                         <li>
                              <i class="bi bi-check-circle-fill"></i>
                              <span>Pastikan satuan konsisten dengan target dan realisasi yang akan dimasukkan.</span>
                         </li>
                         <li>
                              <i class="bi bi-check-circle-fill"></i>
                              <span>Jumlah bobot seluruh indikator pada satu kelompok penilaian idealnya dikendalikan
                                   agar tidak melebihi 100%.</span>
                         </li>
                         <li>
                              <i class="bi bi-check-circle-fill"></i>
                              <span>Pilih arah target secara tepat karena pilihan ini memengaruhi perhitungan
                                   capaian.</span>
                         </li>
                    </ul>
               </div>
          </aside>
     </div>
</form>
