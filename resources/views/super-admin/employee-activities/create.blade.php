@extends('layouts.app')

@section('title', 'Tambah Aktivitas Pegawai')

@section('content')
     <style>
          :root {
               --ea-primary: #0f766e;
               --ea-secondary: #0284c7;
               --ea-accent: #2563eb;
               --ea-text: #24324a;
               --ea-muted: #6b7a90;
               --ea-border: #e5ecf6;
          }

          .ea-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 42px;
               background: radial-gradient(circle at 8% 10%, rgba(20, 184, 166, .12), transparent 24%), radial-gradient(circle at 94% 7%, rgba(37, 99, 235, .12), transparent 26%), linear-gradient(145deg, #f9fcff 0%, #f8fbff 45%, #f1f8ff 100%);
          }

          .ea-container {
               max-width: 1560px;
               margin: 0 auto;
          }

          .ea-hero {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 28px;
               margin-bottom: 20px;
               color: #fff;
               border-radius: 26px;
               background: linear-gradient(125deg, #0f766e 0%, #0284c7 46%, #2563eb 100%);
               box-shadow: 0 20px 48px rgba(14, 116, 144, .22);
          }

          .ea-hero-title {
               display: flex;
               gap: 15px;
               align-items: center;
          }

          .ea-hero-icon {
               display: inline-flex;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               border-radius: 18px;
               background: rgba(255, 255, 255, .95);
          }

          .ea-hero-icon svg {
               width: 28px;
               height: 28px;
          }

          .ea-hero h1 {
               margin: 0;
               font-size: clamp(1.6rem, 2.5vw, 2.2rem);
               font-weight: 850;
               letter-spacing: -.03em;
          }

          .ea-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .92);
          }

          .ea-back-link {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               align-items: center;
               gap: 8px;
               color: #fff;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .35);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
          }

          .ea-card {
               overflow: hidden;
               border: 1px solid var(--ea-border);
               border-radius: 24px;
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 18px 40px rgba(51, 65, 85, .08);
          }

          .ea-card-head {
               padding: 22px 24px;
               border-bottom: 1px solid #edf2f7;
               background: linear-gradient(90deg, #ffffff 0%, #f6fbff 100%);
          }

          .ea-card-title {
               margin: 0;
               color: var(--ea-text);
               font-size: 1.05rem;
               font-weight: 840;
          }

          .ea-card-subtitle {
               margin: 5px 0 0;
               color: var(--ea-muted);
               font-size: .82rem;
          }

          .ea-card-body {
               padding: 24px;
          }

          .ea-form-grid {
               display: grid;
               gap: 18px;
          }

          .ea-form-section {
               padding: 20px;
               border: 1px solid #edf2f7;
               border-radius: 20px;
               background: linear-gradient(180deg, #ffffff, #fbfdff);
          }

          .ea-form-section-head {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 18px;
               color: var(--ea-text);
               font-weight: 820;
          }

          .ea-form-section-head span {
               display: inline-flex;
               width: 38px;
               height: 38px;
               align-items: center;
               justify-content: center;
               color: var(--ea-primary);
               border-radius: 12px;
               background: #ecfeff;
          }

          .ea-form-section-head svg {
               width: 18px;
               height: 18px;
          }

          .ea-label {
               margin-bottom: 7px;
               color: #44556d;
               font-size: .78rem;
               font-weight: 800;
          }

          .ea-required {
               color: #dc2626;
          }

          .ea-control {
               min-height: 47px;
               border: 1px solid #dbe5f1;
               border-radius: 13px;
          }

          .ea-textarea {
               min-height: 120px;
          }

          .ea-invalid-feedback {
               display: block;
               margin-top: 6px;
               color: #dc2626;
               font-size: .75rem;
               font-weight: 700;
          }

          .ea-error-summary {
               display: flex;
               gap: 12px;
               padding: 16px 18px;
               margin-bottom: 18px;
               color: #b91c1c;
               border-left: 4px solid #ef4444;
               border-radius: 16px;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .ea-error-summary svg {
               width: 20px;
               height: 20px;
               flex: 0 0 auto;
               margin-top: 2px;
          }

          .ea-error-summary ul {
               margin: 7px 0 0 18px;
          }

          .ea-duration-preview {
               display: flex;
               gap: 8px;
               align-items: center;
               justify-content: space-between;
               padding: 12px 14px;
               color: var(--ea-text);
               font-size: .8rem;
               font-weight: 800;
               border: 1px dashed #bfdbfe;
               border-radius: 14px;
               background: #f8fbff;
          }

          .ea-duration-preview svg {
               width: 16px;
               height: 16px;
          }

          .ea-status-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 12px;
          }

          .ea-status-item input {
               display: none;
          }

          .ea-status-label {
               display: block;
               padding: 14px;
               border: 1px solid #dbe5f1;
               border-radius: 16px;
               cursor: pointer;
               background: #fff;
          }

          .ea-status-label strong {
               display: block;
               color: var(--ea-text);
               font-size: .86rem;
          }

          .ea-status-label small {
               display: block;
               margin-top: 4px;
               color: var(--ea-muted);
               font-size: .73rem;
          }

          .ea-status-item input:checked+.ea-status-label {
               border-color: #67e8f9;
               box-shadow: 0 0 0 4px rgba(34, 211, 238, .12);
               background: linear-gradient(135deg, #f0fdfa, #eff6ff);
          }

          .ea-actions {
               display: flex;
               gap: 10px;
               justify-content: flex-end;
               margin-top: 20px;
          }

          .ea-btn,
          .ea-btn-secondary {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               align-items: center;
               justify-content: center;
               gap: 8px;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
          }

          .ea-btn {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #0f766e, #0284c7);
          }

          .ea-btn-secondary {
               color: #475569;
               border: 1px solid #dbe5f1;
               background: #fff;
          }

          @media (max-width: 991px) {
               .ea-hero {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .ea-status-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 575px) {
               .ea-page {
                    padding: 18px 12px 34px;
               }

               .ea-card-body {
                    padding: 18px;
               }

               .ea-actions {
                    flex-direction: column-reverse;
               }

               .ea-btn,
               .ea-btn-secondary,
               .ea-back-link {
                    width: 100%;
               }
          }
     </style>

     <div class="ea-page">
          <div class="ea-container">
               <section class="ea-hero">
                    <div class="ea-hero-title">
                         <span class="ea-hero-icon"><i data-feather="clipboard"></i></span>
                         <div>
                              <h1>Tambah Aktivitas Pegawai</h1>
                              <p>Catat aktivitas harian pegawai dengan informasi pekerjaan, volume, dan rentang waktu yang
                                   rapi.</p>
                         </div>
                    </div>
                    <a href="{{ route('super-admin.employee-activities.index') }}" class="ea-back-link"><i
                              data-feather="arrow-left"></i> Kembali ke daftar</a>
               </section>

               <section class="ea-card">
                    <div class="ea-card-head">
                         <h2 class="ea-card-title">Form Aktivitas</h2>
                         <p class="ea-card-subtitle">Lengkapi data utama, waktu kegiatan, dan status aktivitas sebelum
                              menyimpan.</p>
                    </div>
                    <div class="ea-card-body">
                         <form method="POST" action="{{ route('super-admin.employee-activities.store') }}">
                              @csrf
                              @include('super-admin.employee-activities._form')
                              <div class="ea-actions">
                                   <a href="{{ route('super-admin.employee-activities.index') }}"
                                        class="ea-btn-secondary">Batal</a>
                                   <button type="submit" class="ea-btn">Simpan Aktivitas</button>
                              </div>
                         </form>
                    </div>
               </section>
          </div>
     </div>
@endsection
