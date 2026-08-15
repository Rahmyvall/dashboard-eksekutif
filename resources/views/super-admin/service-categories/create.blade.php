@extends('layouts.app')

@section('title', 'Tambah Kategori Layanan')

@section('content')
     <style>
          :root {
               --sc-primary: #6366f1;
               --sc-primary-dark: #4f46e5;
               --sc-secondary: #06b6d4;
               --sc-danger: #ef4444;
               --sc-text: #24324a;
               --sc-muted: #718096;
          }

          .sc-page {
               width: 100%;
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .18), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .18), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          /*
                              |--------------------------------------------------------------------------
                              | FULL WIDTH CONTAINER
                              |--------------------------------------------------------------------------
                              | Sebelumnya max-width: 1080px.
                              | Sekarang dibuat full mengikuti area content.
                              */
          .sc-container {
               width: 100%;
               max-width: 100%;
               margin: 0;
          }

          .sc-hero {
               width: 100%;
               display: flex;
               gap: 22px;
               align-items: center;
               justify-content: space-between;
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 26px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 42%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .sc-hero-main {
               display: flex;
               gap: 18px;
               align-items: center;
          }

          .sc-hero-icon {
               display: inline-flex;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: var(--sc-primary-dark);
               font-size: 1.7rem;
               border-radius: 19px;
               background: rgba(255, 255, 255, .95);
          }

          .sc-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 2.5vw, 2.25rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .sc-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .94);
               line-height: 1.65;
          }

          /*
                              |--------------------------------------------------------------------------
                              | FULL WIDTH CARD
                              |--------------------------------------------------------------------------
                              */
          .sc-card {
               width: 100%;
               max-width: 100%;
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .sc-card-header {
               width: 100%;
               padding: 22px 25px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg,
                         #fff 0%,
                         #faf8ff 48%,
                         #f0fbff 100%);
          }

          .sc-card-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--sc-text);
               font-size: 1.08rem;
               font-weight: 830;
          }

          .sc-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: var(--sc-primary-dark);
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .sc-card-body {
               width: 100%;
               padding: 26px;
          }

          /*
                              |--------------------------------------------------------------------------
                              | FORM
                              |--------------------------------------------------------------------------
                              */
          .sc-card-body form {
               width: 100%;
          }

          .sc-label {
               color: #475569;
               font-size: .86rem;
               font-weight: 780;
          }

          .sc-required {
               color: var(--sc-danger);
          }

          .sc-control {
               width: 100%;
               min-height: 48px;
               color: var(--sc-text);
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #fff;
          }

          textarea.sc-control {
               width: 100%;
               min-height: 150px;
               resize: vertical;
          }

          .sc-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .13);
          }

          .sc-help {
               margin-top: 6px;
               color: var(--sc-muted);
               font-size: .76rem;
          }

          .sc-note {
               width: 100%;
               padding: 15px 17px;
               margin-top: 22px;
               color: #075985;
               font-size: .82rem;
               line-height: 1.6;
               border: 1px solid #bae6fd;
               border-radius: 14px;
               background: #f0f9ff;
          }

          .sc-actions {
               width: 100%;
               display: flex;
               gap: 10px;
               justify-content: flex-end;
               padding-top: 23px;
               margin-top: 24px;
               border-top: 1px solid #eef2f7;
          }

          .sc-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .85rem;
               font-weight: 800;
               text-decoration: none;
               border: 0;
               border-radius: 13px;
          }

          .sc-btn-primary {
               color: #fff;
               background: linear-gradient(135deg,
                         var(--sc-primary),
                         #8b5cf6,
                         var(--sc-secondary));
          }

          .sc-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .sc-alert {
               width: 100%;
               padding: 16px 18px;
               margin-bottom: 18px;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          /*
                              |--------------------------------------------------------------------------
                              | TABLET
                              |--------------------------------------------------------------------------
                              */
          @media (max-width: 991.98px) {
               .sc-page {
                    padding: 24px 16px 38px;
               }

               .sc-hero {
                    padding: 26px;
               }
          }

          /*
                              |--------------------------------------------------------------------------
                              | MOBILE
                              |--------------------------------------------------------------------------
                              */
          @media (max-width: 767.98px) {
               .sc-page {
                    width: 100%;
                    padding: 20px 12px 34px;
               }

               .sc-container {
                    width: 100%;
                    max-width: 100%;
               }

               .sc-hero {
                    width: 100%;
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 23px 20px;
               }

               .sc-hero-main {
                    width: 100%;
               }

               .sc-card {
                    width: 100%;
                    max-width: 100%;
               }

               .sc-card-body {
                    width: 100%;
                    padding: 21px 18px;
               }

               .sc-actions {
                    flex-direction: column-reverse;
               }

               .sc-actions .sc-btn {
                    width: 100%;
               }
          }
     </style>

     <div class="sc-page">

          <div class="sc-container">

               {{-- HEADER --}}
               <div class="sc-hero">

                    <div class="sc-hero-main">

                         <div class="sc-hero-icon">
                              <i class="bi bi-folder-plus"></i>
                         </div>

                         <div>
                              <h1>Tambah Kategori Layanan</h1>

                              <p>
                                   Tambahkan data baru ke tabel service_categories.
                              </p>
                         </div>

                    </div>


                    <a href="{{ route('super-admin.service-categories.index') }}" class="sc-btn sc-btn-secondary">
                         <i class="bi bi-arrow-left"></i>

                         Kembali
                    </a>

               </div>


               {{-- VALIDATION ERROR --}}
               @if ($errors->any())

                    <div class="alert alert-danger sc-alert" role="alert">

                         <div class="fw-bold mb-2">

                              <i class="bi bi-exclamation-triangle-fill me-1"></i>

                              Data belum dapat disimpan

                         </div>


                         <ul class="mb-0 ps-3">

                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach

                         </ul>

                    </div>

               @endif


               {{-- FORM CARD --}}
               <div class="sc-card">

                    <div class="sc-card-header">

                         <h5 class="sc-card-title">

                              <span class="sc-title-icon">

                                   <i class="bi bi-ui-checks-grid"></i>

                              </span>

                              Form Kategori Layanan

                         </h5>

                    </div>


                    <div class="sc-card-body">

                         <form action="{{ route('super-admin.service-categories.store') }}" method="POST">

                              @csrf


                              <div class="row g-4">

                                   {{-- CODE --}}
                                   <div class="col-12 col-md-5">

                                        <label for="code" class="form-label sc-label">
                                             Kode Kategori

                                             <span class="sc-required">*</span>
                                        </label>


                                        <input type="text" id="code" name="code"
                                             class="form-control sc-control @error('code') is-invalid @enderror"
                                             value="{{ old('code') }}" maxlength="30"
                                             placeholder="Otomatis dari nama kategori" autocomplete="off" readonly
                                             aria-readonly="true">


                                        @error('code')
                                             <div class="invalid-feedback">
                                                  {{ $message }}
                                             </div>
                                        @enderror


                                        <div class="sc-help">
                                             Kode dibuat otomatis dari nama kategori dan tetap unik.
                                        </div>

                                   </div>


                                   {{-- NAME --}}
                                   <div class="col-12 col-md-7">

                                        <label for="name" class="form-label sc-label">
                                             Nama Kategori

                                             <span class="sc-required">*</span>
                                        </label>


                                        <input type="text" id="name" name="name"
                                             class="form-control sc-control @error('name') is-invalid @enderror"
                                             value="{{ old('name') }}" maxlength="150"
                                             placeholder="Contoh: Layanan Konsultasi" autocomplete="off" required autofocus>


                                        @error('name')
                                             <div class="invalid-feedback">
                                                  {{ $message }}
                                             </div>
                                        @enderror

                                   </div>


                                   {{-- DESCRIPTION --}}
                                   <div class="col-12">

                                        <label for="description" class="form-label sc-label">
                                             Deskripsi
                                        </label>


                                        <textarea id="description" name="description"
                                             class="form-control sc-control
                                             @error('description') is-invalid @enderror"
                                             placeholder="Tuliskan deskripsi kategori layanan...">{{ old('description') }}</textarea>


                                        @error('description')
                                             <div class="invalid-feedback">
                                                  {{ $message }}
                                             </div>
                                        @enderror

                                   </div>


                                   {{-- STATUS --}}
                                   <div class="col-12 col-md-5">

                                        <label for="status" class="form-label sc-label">
                                             Status

                                             <span class="sc-required">*</span>
                                        </label>


                                        <select id="status" name="status"
                                             class="form-select sc-control
                                             @error('status') is-invalid @enderror"
                                             required>

                                             <option value="">
                                                  Pilih Status
                                             </option>


                                             @foreach ($statuses as $value => $label)
                                                  <option value="{{ $value }}" @selected(old('status', \App\Models\ServiceCategory::STATUS_ACTIVE) === (string) $value)>

                                                       {{ $label }}

                                                  </option>
                                             @endforeach

                                        </select>


                                        @error('status')
                                             <div class="invalid-feedback">
                                                  {{ $message }}
                                             </div>
                                        @enderror

                                   </div>

                              </div>


                              {{-- DATABASE INFO --}}
                              <div class="sc-note">

                                   <i class="bi bi-info-circle-fill me-1"></i>

                                   Kolom

                                   <strong>id</strong>,

                                   <strong>created_at</strong>,

                                   <strong>updated_at</strong>,

                                   dan

                                   <strong>deleted_at</strong>

                                   dikelola otomatis oleh Laravel dan database.

                              </div>


                              {{-- ACTION --}}
                              <div class="sc-actions">

                                   <a href="{{ route('super-admin.service-categories.index') }}"
                                        class="sc-btn sc-btn-secondary">

                                        <i class="bi bi-x-circle"></i>

                                        Batal

                                   </a>


                                   <button type="submit" class="sc-btn sc-btn-primary">

                                        <i class="bi bi-check-circle-fill"></i>

                                        Simpan Kategori

                                   </button>

                              </div>

                         </form>

                         <script>
                              document.addEventListener('DOMContentLoaded', function() {
                                   const nameInput = document.getElementById('name');
                                   const codeInput = document.getElementById('code');

                                   if (!nameInput || !codeInput) {
                                        return;
                                   }

                                   const makeCode = function(value) {
                                        const normalized = (value || '')
                                             .trim()
                                             .replace(/[^a-zA-Z0-9]+/g, ' ');
                                        const words = normalized.split(/\s+/).filter(Boolean);

                                        if (words.length === 0) {
                                             return 'SVC';
                                        }

                                        if (words.length === 1) {
                                             return words[0]
                                                  .replace(/[^a-zA-Z0-9]/g, '')
                                                  .slice(0, 4)
                                                  .toUpperCase() || 'SVC';
                                        }

                                        let code = '';

                                        words.forEach(function(word) {
                                             code += word.charAt(0).toUpperCase();
                                        });

                                        return code.slice(0, 4) || 'SVC';
                                   };

                                   const syncCode = function() {
                                        codeInput.value = makeCode(nameInput.value);
                                   };

                                   nameInput.addEventListener('input', syncCode);
                                   syncCode();
                              });
                         </script>

                    </div>

               </div>

          </div>

     </div>

@endsection
