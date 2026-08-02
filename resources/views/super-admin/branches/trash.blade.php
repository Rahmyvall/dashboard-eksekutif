@extends('layouts.app')

@section('title', 'Sampah Cabang')

@section('content')
     @php
          $trashItems = $trashedBranches ?? $branches ?? collect();
          $trashTotal = method_exists($trashItems, 'total') ? $trashItems->total() : $trashItems->count();

          $restoreRouteName = \Illuminate\Support\Facades\Route::has('branches.restore')
               ? 'branches.restore'
               : null;

          $forceDeleteRouteName = \Illuminate\Support\Facades\Route::has('branches.force-delete')
               ? 'branches.force-delete'
               : (\Illuminate\Support\Facades\Route::has('branches.forceDelete')
                    ? 'branches.forceDelete'
                    : null);
     @endphp

     <style>
          .branch-trash-page {
               --bt-primary: #0f8f83;
               --bt-heading: #164e63;
               --bt-text: #2b424c;
               --bt-muted: #627983;
               --bt-border: #cfe5e4;
               min-height: calc(100vh - 70px);
               padding: 22px 24px 42px;
               color: var(--bt-text);
               background:
                    radial-gradient(circle at 100% 0, rgba(45, 212, 191, .14), transparent 29%),
                    radial-gradient(circle at 0 100%, rgba(125, 211, 252, .11), transparent 27%),
                    linear-gradient(180deg, #f8fffe 0%, #f4fbfb 48%, #f7fafc 100%);
          }

          .branch-trash-page * {
               box-sizing: border-box;
          }

          .branch-trash-shell {
               width: 100%;
               max-width: 1480px;
               margin: 0 auto;
          }

          .branch-trash-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 24px;
               margin-bottom: 20px;
               padding: 30px 34px;
               border: 1px solid #bde7e2;
               border-radius: 22px;
               background:
                    radial-gradient(circle at 90% 10%, rgba(255, 255, 255, .94), transparent 23%),
                    radial-gradient(circle at 72% 112%, rgba(45, 212, 191, .19), transparent 39%),
                    linear-gradient(135deg, #ffffff 0%, #f1fffc 38%, #e5faf6 72%, #d9f5ef 100%);
               box-shadow: 0 18px 42px rgba(15, 118, 110, .10);
          }

          .branch-trash-hero::after {
               content: '';
               position: absolute;
               right: -94px;
               bottom: -142px;
               width: 275px;
               height: 275px;
               border: 38px solid rgba(20, 184, 166, .10);
               border-radius: 50%;
               pointer-events: none;
          }

          .branch-trash-hero > * {
               position: relative;
               z-index: 1;
          }

          .branch-trash-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 7px;
               color: #168178;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .11em;
               text-transform: uppercase;
          }

          .branch-trash-page .branch-trash-hero h1 {
               margin: 0 0 7px;
               color: var(--bt-heading) !important;
               font-size: clamp(1.75rem, 3vw, 2.45rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .branch-trash-page .branch-trash-hero p {
               max-width: 820px;
               margin: 0;
               color: #55717a !important;
               font-size: .92rem;
               line-height: 1.65;
          }

          .branch-trash-back {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 44px;
               padding: 10px 15px;
               color: #0f766e !important;
               font-size: .8rem;
               font-weight: 850;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid #8fdad1;
               border-radius: 12px;
               background: rgba(255, 255, 255, .92);
               transition: .2s ease;
          }

          .branch-trash-back:hover {
               color: #0b655f !important;
               border-color: #5eead4;
               background: #f0fdfa;
               transform: translateY(-1px);
          }

          .branch-trash-alert {
               display: flex;
               align-items: flex-start;
               gap: 11px;
               margin-bottom: 16px;
               padding: 14px 16px;
               font-size: .78rem;
               border-radius: 13px;
               box-shadow: 0 8px 20px rgba(51, 65, 85, .045);
          }

          .branch-trash-alert.success {
               color: #08755e;
               border: 1px solid #a9ead8;
               background: #effbf7;
          }

          .branch-trash-alert.error {
               color: #a83a4a;
               border: 1px solid #f2c3cc;
               background: #fff5f7;
          }

          .branch-trash-panel {
               overflow: hidden;
               border: 1px solid var(--bt-border);
               border-radius: 19px;
               background: rgba(255, 255, 255, .99);
               box-shadow: 0 13px 34px rgba(51, 65, 85, .06);
          }

          .branch-trash-heading {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 18px 20px;
               border-bottom: 1px solid #dcecea;
               background: linear-gradient(90deg, #f3fcfa 0%, #ffffff 100%);
          }

          .branch-trash-title {
               margin: 0 0 4px;
               color: #176b68 !important;
               font-size: 1rem;
               font-weight: 850;
          }

          .branch-trash-copy {
               margin: 0;
               color: #6c828b;
               font-size: .76rem;
          }

          .branch-trash-count {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 7px 10px;
               color: #0f766e;
               font-size: .72rem;
               font-weight: 850;
               white-space: nowrap;
               border: 1px solid #a7e7df;
               border-radius: 999px;
               background: #eafaf7;
          }

          .branch-trash-table-wrap {
               overflow-x: auto;
          }

          .branch-trash-table {
               width: 100%;
               border-collapse: collapse;
          }

          .branch-trash-table th {
               padding: 12px 14px;
               color: #657b84;
               font-size: .67rem;
               font-weight: 850;
               letter-spacing: .07em;
               text-align: left;
               text-transform: uppercase;
               white-space: nowrap;
               border-bottom: 1px solid #dce8e7;
               background: #f7fcfb;
          }

          .branch-trash-table td {
               padding: 14px;
               color: #39515b;
               font-size: .8rem;
               vertical-align: middle;
               border-bottom: 1px solid #edf3f2;
          }

          .branch-trash-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .branch-trash-table tbody tr:hover {
               background: #fbfffe;
          }

          .branch-trash-code {
               display: inline-flex;
               padding: 6px 9px;
               color: #4f46a5;
               font-size: .71rem;
               font-weight: 850;
               border: 1px solid #d7d7fb;
               border-radius: 9px;
               background: #f3f3ff;
          }

          .branch-trash-name {
               margin-bottom: 4px;
               color: #284750;
               font-weight: 850;
          }

          .branch-trash-address {
               max-width: 390px;
               color: #728790;
               font-size: .72rem;
               line-height: 1.45;
          }

          .branch-trash-date {
               color: #586f79;
               font-size: .74rem;
               white-space: nowrap;
          }

          .branch-trash-actions {
               display: inline-flex;
               align-items: center;
               gap: 7px;
          }

          .branch-trash-actions form {
               margin: 0;
          }

          .branch-trash-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 6px;
               min-height: 38px;
               padding: 8px 11px;
               font-size: .72rem;
               font-weight: 850;
               white-space: nowrap;
               border-radius: 10px;
               transition: .18s ease;
          }

          .branch-trash-button.restore {
               color: #08755e;
               border: 1px solid #a9ead8;
               background: #effbf7;
          }

          .branch-trash-button.restore:hover {
               color: #06664f;
               border-color: #74d9c0;
               background: #e4f9f1;
               transform: translateY(-1px);
          }

          .branch-trash-button.delete {
               color: #b23b52;
               border: 1px solid #f2c3cc;
               background: #fff4f6;
          }

          .branch-trash-button.delete:hover {
               color: #9f2f45;
               border-color: #eaa4b1;
               background: #ffedf1;
               transform: translateY(-1px);
          }

          .branch-trash-mobile {
               display: none;
               padding: 12px;
               background: #f8fcfb;
          }

          .branch-trash-card {
               margin-bottom: 11px;
               padding: 15px;
               border: 1px solid #d7e8e6;
               border-radius: 14px;
               background: #ffffff;
               box-shadow: 0 8px 20px rgba(51, 65, 85, .045);
          }

          .branch-trash-card:last-child {
               margin-bottom: 0;
          }

          .branch-trash-card-top {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 10px;
               margin-bottom: 12px;
          }

          .branch-trash-card-name {
               margin: 7px 0 0;
               color: #284750;
               font-size: .92rem;
               font-weight: 850;
          }

          .branch-trash-card-info {
               display: grid;
               gap: 8px;
               margin-bottom: 12px;
          }

          .branch-trash-card-row {
               padding: 10px;
               color: #536b75;
               font-size: .74rem;
               line-height: 1.5;
               border-radius: 10px;
               background: #f7fbfa;
          }

          .branch-trash-card-label {
               display: block;
               margin-bottom: 4px;
               color: #82959c;
               font-size: .62rem;
               font-weight: 850;
               letter-spacing: .07em;
               text-transform: uppercase;
          }

          .branch-trash-card-actions {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 8px;
          }

          .branch-trash-card-actions .branch-trash-button {
               width: 100%;
          }

          .branch-trash-empty {
               padding: 62px 20px;
               text-align: center;
          }

          .branch-trash-empty-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 70px;
               height: 70px;
               margin-bottom: 14px;
               color: #0f8f83;
               font-size: 1.65rem;
               border: 1px solid #bce8e2;
               border-radius: 21px;
               background: #eafaf7;
          }

          .branch-trash-empty h3 {
               margin: 0 0 7px;
               color: #284750 !important;
               font-size: 1.03rem;
               font-weight: 850;
          }

          .branch-trash-empty p {
               max-width: 460px;
               margin: 0 auto 15px;
               color: #6a8089;
               font-size: .78rem;
               line-height: 1.6;
          }

          .branch-trash-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 13px;
               padding: 14px 20px;
               border-top: 1px solid #dcecea;
               background: #ffffff;
          }

          .branch-trash-result {
               color: #6c828b;
               font-size: .74rem;
          }

          .branch-trash-page .pagination {
               margin: 0;
          }

          .branch-trash-page .pagination .page-link {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 35px;
               height: 35px;
               margin: 0 2px;
               color: #536b75;
               font-size: .74rem;
               border-color: #d3e3e1;
               border-radius: 9px !important;
          }

          .branch-trash-page .pagination .page-item.active .page-link {
               color: #fff;
               border-color: #20ad9f;
               background: #20ad9f;
          }

          @media (max-width: 991.98px) {
               .branch-trash-table-wrap {
                    display: none;
               }

               .branch-trash-mobile {
                    display: block;
               }
          }

          @media (max-width: 767.98px) {
               .branch-trash-page {
                    padding: 12px 10px 28px;
               }

               .branch-trash-hero {
                    align-items: stretch;
                    flex-direction: column;
                    padding: 24px 20px;
                    border-radius: 18px;
               }

               .branch-trash-back {
                    width: 100%;
               }

               .branch-trash-heading,
               .branch-trash-footer {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }

          @media (max-width: 479.98px) {
               .branch-trash-card-actions {
                    grid-template-columns: 1fr;
               }
          }
     </style>

     <div class="branch-trash-page">
          <div class="branch-trash-shell">
               @if (session('success'))
                    <div class="branch-trash-alert success">
                         <i class="bi bi-check-circle-fill mt-1"></i>
                         <div>{{ session('success') }}</div>
                    </div>
               @endif

               @if (session('error'))
                    <div class="branch-trash-alert error">
                         <i class="bi bi-exclamation-octagon-fill mt-1"></i>
                         <div>{{ session('error') }}</div>
                    </div>
               @endif

               <header class="branch-trash-hero">
                    <div>
                         <div class="branch-trash-eyebrow">
                              <i class="bi bi-recycle"></i>
                              Recycle Bin
                         </div>
                         <h1>Sampah Cabang</h1>
                         <p>
                              Pulihkan data cabang yang masih dibutuhkan atau hapus permanen
                              setelah memastikan data tidak lagi digunakan.
                         </p>
                    </div>

                    <a href="{{ route('branches.index') }}" class="branch-trash-back">
                         <i class="bi bi-arrow-left"></i>
                         Kembali ke Daftar
                    </a>
               </header>

               <section class="branch-trash-panel">
                    <div class="branch-trash-heading">
                         <div>
                              <h2 class="branch-trash-title">Data Cabang Terhapus</h2>
                              <p class="branch-trash-copy">Data pada halaman ini sudah tidak tampil di daftar utama.</p>
                         </div>

                         <span class="branch-trash-count">
                              <i class="bi bi-trash3"></i>
                              {{ number_format($trashTotal) }} data
                         </span>
                    </div>

                    @if ($trashItems->count() > 0)
                         <div class="branch-trash-table-wrap">
                              <table class="branch-trash-table">
                                   <thead>
                                        <tr>
                                             <th>Kode</th>
                                             <th>Informasi Cabang</th>
                                             <th>Kepala Cabang</th>
                                             <th>Dihapus Pada</th>
                                             <th class="text-center">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @foreach ($trashItems as $branch)
                                             <tr>
                                                  <td>
                                                       <span class="branch-trash-code">{{ $branch->branch_code }}</span>
                                                  </td>
                                                  <td>
                                                       <div class="branch-trash-name">{{ $branch->branch_name }}</div>
                                                       <div class="branch-trash-address">
                                                            {{ $branch->address ?: 'Alamat belum tersedia.' }}
                                                       </div>
                                                  </td>
                                                  <td>{{ $branch->manager?->name ?? 'Belum ditentukan' }}</td>
                                                  <td>
                                                       <span class="branch-trash-date">
                                                            {{ optional($branch->deleted_at)->format('d M Y, H:i') ?? '-' }}
                                                       </span>
                                                  </td>
                                                  <td class="text-center">
                                                       <div class="branch-trash-actions">
                                                            @if ($restoreRouteName)
                                                                 <form method="POST"
                                                                      action="{{ route($restoreRouteName, $branch->id) }}"
                                                                      onsubmit="return confirm('Pulihkan cabang {{ addslashes($branch->branch_name) }}?')">
                                                                      @csrf
                                                                      @method('PATCH')
                                                                      <button type="submit" class="branch-trash-button restore">
                                                                           <i class="bi bi-arrow-counterclockwise"></i>
                                                                           Pulihkan
                                                                      </button>
                                                                 </form>
                                                            @endif

                                                            @if ($forceDeleteRouteName)
                                                                 <form method="POST"
                                                                      action="{{ route($forceDeleteRouteName, $branch->id) }}"
                                                                      onsubmit="return confirm('Hapus permanen cabang {{ addslashes($branch->branch_name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                                                      @csrf
                                                                      @method('DELETE')
                                                                      <button type="submit" class="branch-trash-button delete">
                                                                           <i class="bi bi-trash3-fill"></i>
                                                                           Hapus Permanen
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @endforeach
                                   </tbody>
                              </table>
                         </div>

                         <div class="branch-trash-mobile">
                              @foreach ($trashItems as $branch)
                                   <article class="branch-trash-card">
                                        <div class="branch-trash-card-top">
                                             <div>
                                                  <span class="branch-trash-code">{{ $branch->branch_code }}</span>
                                                  <h3 class="branch-trash-card-name">{{ $branch->branch_name }}</h3>
                                             </div>
                                             <i class="bi bi-trash3 text-secondary"></i>
                                        </div>

                                        <div class="branch-trash-card-info">
                                             <div class="branch-trash-card-row">
                                                  <span class="branch-trash-card-label">Alamat</span>
                                                  {{ $branch->address ?: 'Alamat belum tersedia.' }}
                                             </div>
                                             <div class="branch-trash-card-row">
                                                  <span class="branch-trash-card-label">Kepala Cabang</span>
                                                  {{ $branch->manager?->name ?? 'Belum ditentukan' }}
                                             </div>
                                             <div class="branch-trash-card-row">
                                                  <span class="branch-trash-card-label">Dihapus Pada</span>
                                                  {{ optional($branch->deleted_at)->format('d M Y, H:i') ?? '-' }}
                                             </div>
                                        </div>

                                        <div class="branch-trash-card-actions">
                                             @if ($restoreRouteName)
                                                  <form method="POST"
                                                       action="{{ route($restoreRouteName, $branch->id) }}"
                                                       onsubmit="return confirm('Pulihkan cabang {{ addslashes($branch->branch_name) }}?')">
                                                       @csrf
                                                       @method('PATCH')
                                                       <button type="submit" class="branch-trash-button restore">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                            Pulihkan
                                                       </button>
                                                  </form>
                                             @endif

                                             @if ($forceDeleteRouteName)
                                                  <form method="POST"
                                                       action="{{ route($forceDeleteRouteName, $branch->id) }}"
                                                       onsubmit="return confirm('Hapus permanen cabang {{ addslashes($branch->branch_name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                                       @csrf
                                                       @method('DELETE')
                                                       <button type="submit" class="branch-trash-button delete">
                                                            <i class="bi bi-trash3-fill"></i>
                                                            Hapus Permanen
                                                       </button>
                                                  </form>
                                             @endif
                                        </div>
                                   </article>
                              @endforeach
                         </div>
                    @else
                         <div class="branch-trash-empty">
                              <div class="branch-trash-empty-icon">
                                   <i class="bi bi-trash3"></i>
                              </div>
                              <h3>Sampah masih kosong</h3>
                              <p>Belum ada data cabang yang dipindahkan ke sampah.</p>
                              <a href="{{ route('branches.index') }}" class="branch-trash-back">
                                   <i class="bi bi-arrow-left"></i>
                                   Kembali ke Daftar
                              </a>
                         </div>
                    @endif

                    @if ($trashItems->count() > 0)
                         <footer class="branch-trash-footer">
                              <div class="branch-trash-result">
                                   Total <strong>{{ number_format($trashTotal) }}</strong> cabang berada di sampah.
                              </div>

                              @if (method_exists($trashItems, 'hasPages') && $trashItems->hasPages())
                                   <div>{{ $trashItems->withQueryString()->links() }}</div>
                              @endif
                         </footer>
                    @endif
               </section>
          </div>
     </div>
@endsection
