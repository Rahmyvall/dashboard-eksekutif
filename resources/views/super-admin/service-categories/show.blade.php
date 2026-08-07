@extends('layouts.app')

@section('title', 'Detail Kategori Layanan')

@section('content')
     <style>
          :root {
               --sc-primary: #6366f1;
               --sc-primary-dark: #4f46e5;
               --sc-secondary: #06b6d4;
               --sc-danger: #ef4444;
               --sc-success: #10b981;
               --sc-warning: #f59e0b;
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

          .sc-card {
               width: 100%;
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .sc-card-header {
               padding: 22px 25px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff, #faf8ff 48%, #f0fbff);
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
               padding: 26px;
          }

          .sc-detail-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 18px;
          }

          .sc-detail-item {
               padding: 18px;
               border: 1px solid #e8edf5;
               border-radius: 16px;
               background: #fbfdff;
          }

          .sc-detail-item.full {
               grid-column: 1 / -1;
          }

          .sc-detail-label {
               display: block;
               margin-bottom: 7px;
               color: var(--sc-muted);
               font-size: .76rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .04em;
          }

          .sc-detail-value {
               color: var(--sc-text);
               font-size: .95rem;
               font-weight: 650;
               line-height: 1.65;
               word-break: break-word;
          }

          .sc-description {
               white-space: pre-line;
          }

          .sc-badge {
               display: inline-flex;
               padding: 7px 12px;
               align-items: center;
               gap: 6px;
               border-radius: 999px;
               font-size: .75rem;
               font-weight: 800;
          }

          .sc-badge-active {
               color: #047857;
               background: #d1fae5;
          }

          .sc-badge-inactive {
               color: #b45309;
               background: #fef3c7;
          }

          .sc-actions {
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
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
          }

          .sc-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .sc-btn-danger {
               color: #fff;
               background: #ef4444;
          }

          @media (max-width: 767.98px) {
               .sc-page {
                    padding: 20px 12px 34px;
               }

               .sc-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 23px 20px;
               }

               .sc-card-body {
                    padding: 21px 18px;
               }

               .sc-detail-grid {
                    grid-template-columns: 1fr;
               }

               .sc-detail-item.full {
                    grid-column: auto;
               }

               .sc-actions {
                    flex-direction: column-reverse;
               }

               .sc-actions .sc-btn,
               .sc-actions form,
               .sc-actions form button {
                    width: 100%;
               }
          }
     </style>

     <div class="sc-page">
          <div class="sc-container">

               <div class="sc-hero">
                    <div class="sc-hero-main">
                         <div class="sc-hero-icon">
                              <i class="bi bi-folder2-open"></i>
                         </div>

                         <div>
                              <h1>Detail Kategori Layanan</h1>
                              <p>Informasi lengkap kategori layanan yang tersimpan pada sistem.</p>
                         </div>
                    </div>

                    <a href="{{ route('super-admin.service-categories.index') }}" class="sc-btn sc-btn-secondary">
                         <i class="bi bi-arrow-left"></i>
                         Kembali
                    </a>
               </div>

               <div class="sc-card">

                    <div class="sc-card-header">
                         <h5 class="sc-card-title">
                              <span class="sc-title-icon">
                                   <i class="bi bi-info-circle"></i>
                              </span>
                              Informasi Kategori
                         </h5>
                    </div>

                    <div class="sc-card-body">

                         <div class="sc-detail-grid">

                              <div class="sc-detail-item">
                                   <span class="sc-detail-label">
                                        ID
                                   </span>

                                   <div class="sc-detail-value">
                                        #{{ $serviceCategory->id }}
                                   </div>
                              </div>

                              <div class="sc-detail-item">
                                   <span class="sc-detail-label">
                                        Kode Kategori
                                   </span>

                                   <div class="sc-detail-value">
                                        {{ $serviceCategory->code }}
                                   </div>
                              </div>

                              <div class="sc-detail-item">
                                   <span class="sc-detail-label">
                                        Nama Kategori
                                   </span>

                                   <div class="sc-detail-value">
                                        {{ $serviceCategory->name }}
                                   </div>
                              </div>

                              <div class="sc-detail-item">
                                   <span class="sc-detail-label">
                                        Status
                                   </span>

                                   <div class="sc-detail-value">

                                        @if ($serviceCategory->status === 'active')
                                             <span class="sc-badge sc-badge-active">
                                                  <i class="bi bi-check-circle-fill"></i>
                                                  Aktif
                                             </span>
                                        @else
                                             <span class="sc-badge sc-badge-inactive">
                                                  <i class="bi bi-dash-circle-fill"></i>
                                                  Tidak Aktif
                                             </span>
                                        @endif

                                   </div>
                              </div>

                              <div class="sc-detail-item full">
                                   <span class="sc-detail-label">
                                        Deskripsi
                                   </span>

                                   <div class="sc-detail-value sc-description">
                                        {{ $serviceCategory->description ?: '-' }}
                                   </div>
                              </div>

                              <div class="sc-detail-item">
                                   <span class="sc-detail-label">
                                        Dibuat Pada
                                   </span>

                                   <div class="sc-detail-value">
                                        {{ $serviceCategory->created_at ? $serviceCategory->created_at->format('d/m/Y H:i') : '-' }}
                                   </div>
                              </div>

                              <div class="sc-detail-item">
                                   <span class="sc-detail-label">
                                        Terakhir Diperbarui
                                   </span>

                                   <div class="sc-detail-value">
                                        {{ $serviceCategory->updated_at ? $serviceCategory->updated_at->format('d/m/Y H:i') : '-' }}
                                   </div>
                              </div>

                         </div>

                         <div class="sc-actions">

                              <form action="{{ route('super-admin.service-categories.destroy', $serviceCategory->id) }}"
                                   method="POST"
                                   onsubmit="return confirm('Yakin ingin memindahkan kategori ini ke sampah?')">
                                   @csrf
                                   @method('DELETE')

                                   <button type="submit" class="sc-btn sc-btn-danger">
                                        <i class="bi bi-trash3"></i>
                                        Hapus
                                   </button>
                              </form>

                              <a href="{{ route('super-admin.service-categories.edit', $serviceCategory->id) }}"
                                   class="sc-btn sc-btn-primary">
                                   <i class="bi bi-pencil-square"></i>
                                   Edit Data
                              </a>

                         </div>

                    </div>
               </div>

          </div>
     </div>
@endsection
