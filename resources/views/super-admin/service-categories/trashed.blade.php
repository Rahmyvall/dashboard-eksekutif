@extends('layouts.app')

@section('title', 'Sampah Kategori Layanan')

@section('content')
     <style>
          :root {
               --sc-primary: #6366f1;
               --sc-primary-dark: #4f46e5;
               --sc-secondary: #06b6d4;
               --sc-danger: #ef4444;
               --sc-success: #10b981;
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
               max-width: 100%;
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .sc-card-header {
               width: 100%;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 22px 25px;
               border-bottom: 1px solid #eef2f7;

               background:
                    linear-gradient(90deg,
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
               padding: 0;
          }

          .sc-table-wrap {
               width: 100%;
               overflow-x: auto;
               -webkit-overflow-scrolling: touch;
          }

          .sc-table {
               width: 100%;
               min-width: 950px;
               margin: 0;
               border-collapse: collapse;
          }

          .sc-table thead th {
               padding: 15px 16px;
               color: #64748b;
               font-size: .75rem;
               font-weight: 850;
               text-transform: uppercase;
               letter-spacing: .04em;
               white-space: nowrap;
               border-bottom: 1px solid #e5eaf2;
               background: #f8fafc;
          }

          .sc-table tbody td {
               padding: 16px;
               color: #334155;
               font-size: .85rem;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f6;
          }

          .sc-table tbody tr:last-child td {
               border-bottom: none;
          }

          .sc-table tbody tr:hover {
               background: #fafbff;
          }

          .sc-code {
               color: #4f46e5;
               font-weight: 800;
          }

          .sc-name {
               color: #24324a;
               font-weight: 750;
          }

          .sc-description {
               max-width: 320px;
               color: #64748b;
          }

          .sc-badge {
               display: inline-flex;
               padding: 6px 11px;
               gap: 5px;
               align-items: center;
               border-radius: 999px;
               font-size: .72rem;
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

          .sc-date {
               color: #64748b;
               font-size: .8rem;
               white-space: nowrap;
          }

          .sc-action-group {
               display: flex;
               align-items: center;
               gap: 7px;
               justify-content: flex-end;
               white-space: nowrap;
          }

          .sc-btn {
               display: inline-flex;
               min-height: 42px;
               padding: 0 15px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               border: 0;
               border-radius: 11px;
               cursor: pointer;
          }

          .sc-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .sc-btn-restore {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .sc-btn-danger {
               color: #fff;
               background: #ef4444;
          }

          .sc-alert {
               width: 100%;
               padding: 16px 18px;
               margin-bottom: 18px;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .sc-empty {
               padding: 60px 20px;
               text-align: center;
          }

          .sc-empty-icon {
               display: inline-flex;
               width: 72px;
               height: 72px;
               margin-bottom: 16px;
               align-items: center;
               justify-content: center;
               color: #94a3b8;
               font-size: 2rem;
               border-radius: 20px;
               background: #f1f5f9;
          }

          .sc-empty h5 {
               margin-bottom: 6px;
               color: #334155;
               font-weight: 800;
          }

          .sc-empty p {
               margin: 0;
               color: #94a3b8;
               font-size: .87rem;
          }

          .sc-pagination {
               padding: 20px 24px;
               border-top: 1px solid #eef2f7;
          }

          @media (max-width: 767.98px) {
               .sc-page {
                    padding: 20px 12px 34px;
               }

               .sc-hero {
                    align-items: stretch;
                    flex-direction: column;
                    padding: 23px 20px;
               }

               .sc-hero-main {
                    align-items: flex-start;
               }

               .sc-hero .sc-btn {
                    width: 100%;
               }

               .sc-card-header {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 18px;
               }
          }
     </style>

     <div class="sc-page">

          <div class="sc-container">

               <div class="sc-hero">

                    <div class="sc-hero-main">

                         <div class="sc-hero-icon">
                              <i class="bi bi-trash3"></i>
                         </div>

                         <div>
                              <h1>Sampah Kategori Layanan</h1>

                              <p>
                                   Kelola kategori layanan yang telah dihapus sementara.
                              </p>
                         </div>

                    </div>

                    <a href="{{ route('super-admin.service-categories.index') }}" class="sc-btn sc-btn-secondary">
                         <i class="bi bi-arrow-left"></i>
                         Kembali
                    </a>

               </div>


               {{-- SUCCESS --}}
               @if (session('success'))
                    <div class="alert alert-success sc-alert">

                         <i class="bi bi-check-circle-fill me-1"></i>

                         {{ session('success') }}

                    </div>
               @endif


               {{-- ERROR --}}
               @if (session('error'))
                    <div class="alert alert-danger sc-alert">

                         <i class="bi bi-exclamation-triangle-fill me-1"></i>

                         {{ session('error') }}

                    </div>
               @endif


               <div class="sc-card">

                    <div class="sc-card-header">

                         <h5 class="sc-card-title">

                              <span class="sc-title-icon">
                                   <i class="bi bi-trash"></i>
                              </span>

                              Data Terhapus

                         </h5>

                    </div>


                    <div class="sc-card-body">

                         @if ($serviceCategories->count() > 0)

                              <div class="sc-table-wrap">

                                   <table class="sc-table">

                                        <thead>
                                             <tr>

                                                  <th style="width: 70px;">
                                                       ID
                                                  </th>

                                                  <th>
                                                       Kode
                                                  </th>

                                                  <th>
                                                       Nama Kategori
                                                  </th>

                                                  <th>
                                                       Deskripsi
                                                  </th>

                                                  <th>
                                                       Status
                                                  </th>

                                                  <th>
                                                       Dihapus Pada
                                                  </th>

                                                  <th class="text-end">
                                                       Aksi
                                                  </th>

                                             </tr>
                                        </thead>


                                        <tbody>

                                             @foreach ($serviceCategories as $serviceCategory)
                                                  <tr>

                                                       {{-- ID --}}
                                                       <td>
                                                            #{{ $serviceCategory->id }}
                                                       </td>


                                                       {{-- CODE --}}
                                                       <td>

                                                            <span class="sc-code">
                                                                 {{ $serviceCategory->code }}
                                                            </span>

                                                       </td>


                                                       {{-- NAME --}}
                                                       <td>

                                                            <span class="sc-name">
                                                                 {{ $serviceCategory->name }}
                                                            </span>

                                                       </td>


                                                       {{-- DESCRIPTION --}}
                                                       <td>

                                                            <div class="sc-description">

                                                                 {{ \Illuminate\Support\Str::limit($serviceCategory->description ?: '-', 80) }}

                                                            </div>

                                                       </td>


                                                       {{-- STATUS --}}
                                                       <td>

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

                                                       </td>


                                                       {{-- DELETED AT --}}
                                                       <td>

                                                            <span class="sc-date">

                                                                 {{ $serviceCategory->deleted_at ? $serviceCategory->deleted_at->format('d/m/Y H:i') : '-' }}

                                                            </span>

                                                       </td>


                                                       {{-- ACTION --}}
                                                       <td>

                                                            <div class="sc-action-group">

                                                                 {{-- RESTORE --}}
                                                                 <form action="{{ route('super-admin.service-categories.restore', $serviceCategory->id) }}"
                                                                      method="POST">

                                                                      @csrf

                                                                      @method('PATCH')


                                                                      <button type="submit" class="sc-btn sc-btn-restore"
                                                                           onclick="return confirm(
                                                            'Pulihkan kategori layanan ini?'
                                                        )">

                                                                           <i class="bi bi-arrow-counterclockwise"></i>

                                                                           Pulihkan

                                                                      </button>

                                                                 </form>


                                                                 {{-- FORCE DELETE --}}
                                                                 <form action="{{ route('super-admin.service-categories.force-delete', $serviceCategory->id) }}"
                                                                      method="POST">

                                                                      @csrf

                                                                      @method('DELETE')


                                                                      <button type="submit" class="sc-btn sc-btn-danger"
                                                                           onclick="return confirm(
                                                            'PERINGATAN! Data akan dihapus permanen dan tidak dapat dipulihkan. Lanjutkan?'
                                                        )">

                                                                           <i class="bi bi-trash3-fill"></i>

                                                                           Hapus Permanen

                                                                      </button>

                                                                 </form>

                                                            </div>

                                                       </td>

                                                  </tr>
                                             @endforeach

                                        </tbody>

                                   </table>

                              </div>


                              {{-- PAGINATION --}}
                              @if (method_exists($serviceCategories, 'links'))
                                   <div class="sc-pagination">

                                        {{ $serviceCategories->links() }}

                                   </div>
                              @endif
                         @else
                              <div class="sc-empty">

                                   <div class="sc-empty-icon">
                                        <i class="bi bi-trash"></i>
                                   </div>

                                   <h5>Sampah masih kosong</h5>

                                   <p>
                                        Tidak ada kategori layanan yang sedang berada di sampah.
                                   </p>

                              </div>

                         @endif

                    </div>

               </div>

          </div>

     </div>
@endsection
