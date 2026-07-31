@extends('layouts.app')

@section('title', 'Sampah Jabatan')

@section('content')
     @php
          $search = isset($search) ? trim((string) $search) : trim((string) request('search', ''));

          $departmentId = isset($departmentId) ? (string) $departmentId : (string) request('department_id', '');

          $totalTrash = method_exists($positions, 'total') ? $positions->total() : $positions->count();

          $hasActiveFilters = $search !== '' || $departmentId !== '';

          $routeHas = static fn(string $name): bool => \Illuminate\Support\Facades\Route::has($name);
     @endphp

     <style>
          :root {
               --trash-primary: #6366f1;
               --trash-purple: #8b5cf6;
               --trash-cyan: #06b6d4;
               --trash-danger: #ef4444;
               --trash-success: #10b981;
               --trash-text: #24324a;
               --trash-muted: #718096;
               --trash-border: #e7eaf3;
          }

          .position-trash-page,
          .position-trash-page * {
               box-sizing: border-box;
          }

          .position-trash-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 4%, rgba(244, 63, 94, .13), transparent 25%),
                    radial-gradient(circle at 96% 7%, rgba(99, 102, 241, .16), transparent 25%),
                    linear-gradient(145deg, #fffdfd 0%, #faf7ff 48%, #f2fbff 100%);
          }

          .position-trash-container {
               width: 100%;
               max-width: 1500px;
               margin: 0 auto;
          }

          .position-trash-hero {
               position: relative;
               overflow: hidden;
               padding: 31px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .30), transparent 24%),
                    linear-gradient(120deg, #e11d48 0%, #8b5cf6 52%, #6366f1 100%);
               box-shadow: 0 22px 48px rgba(190, 24, 93, .18);
          }

          .position-trash-hero::after {
               position: absolute;
               right: -42px;
               bottom: -78px;
               width: 180px;
               height: 180px;
               content: '';
               border-radius: 45px;
               background: rgba(255, 255, 255, .13);
               transform: rotate(28deg);
          }

          .position-trash-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 20px;
               align-items: center;
               justify-content: space-between;
          }

          .position-trash-title-wrap {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .position-trash-hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               color: #be123c;
               align-items: center;
               justify-content: center;
               border-radius: 20px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 27px rgba(76, 29, 149, .16);
          }

          .position-trash-hero-icon svg {
               width: 28px;
               height: 28px;
          }

          .position-trash-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 2.5vw, 2.25rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .position-trash-hero p {
               max-width: 760px;
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .93);
               font-size: .91rem;
               line-height: 1.65;
          }

          .position-trash-back {
               display: inline-flex;
               min-height: 45px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .82rem;
               font-weight: 810;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .76);
               border-radius: 13px;
               background: rgba(255, 255, 255, .96);
               transition: .2s ease;
          }

          .position-trash-back:hover {
               color: #312e81;
               text-decoration: none;
               background: #fff;
               transform: translateY(-2px);
          }

          .position-trash-back svg {
               width: 17px;
               height: 17px;
          }

          .position-trash-alert {
               display: flex;
               gap: 13px;
               align-items: flex-start;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 17px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .position-trash-alert svg {
               flex: 0 0 auto;
               width: 20px;
               height: 20px;
               margin-top: 1px;
          }

          .position-trash-alert-success {
               color: #047857;
               border-left: 5px solid var(--trash-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .position-trash-alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--trash-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .position-trash-filter {
               padding: 21px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 22px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 15px 36px rgba(51, 65, 85, .075);
          }

          .position-trash-filter-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 17px;
               color: var(--trash-text);
               font-size: .94rem;
               font-weight: 820;
          }

          .position-trash-filter-title span {
               display: inline-flex;
               width: 38px;
               height: 38px;
               color: #be123c;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: #fff1f2;
          }

          .position-trash-filter-title svg {
               width: 18px;
               height: 18px;
          }

          .position-trash-label {
               margin-bottom: 7px;
               color: #52627a;
               font-size: .77rem;
               font-weight: 810;
          }

          .position-trash-control {
               min-height: 47px;
               color: var(--trash-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background: #fff;
               box-shadow: none;
          }

          .position-trash-control:focus {
               border-color: #fb7185;
               box-shadow: 0 0 0 .22rem rgba(244, 63, 94, .11);
          }

          .position-trash-search {
               position: relative;
          }

          .position-trash-search>svg {
               position: absolute;
               z-index: 2;
               top: 50%;
               left: 15px;
               width: 17px;
               height: 17px;
               color: #e11d48;
               pointer-events: none;
               transform: translateY(-50%);
          }

          .position-trash-search input {
               padding-left: 43px;
          }

          .position-trash-filter-actions {
               display: flex;
               height: 100%;
               gap: 10px;
               align-items: flex-end;
          }

          .position-trash-btn {
               display: inline-flex;
               min-height: 47px;
               padding: 10px 17px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 810;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .position-trash-btn svg {
               width: 17px;
               height: 17px;
          }

          .position-trash-btn-primary {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #e11d48, #8b5cf6);
               box-shadow: 0 10px 21px rgba(225, 29, 72, .20);
          }

          .position-trash-btn-primary:hover {
               color: #fff;
               transform: translateY(-2px);
          }

          .position-trash-btn-secondary {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .position-trash-btn-secondary:hover {
               color: #334155;
               text-decoration: none;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          .position-trash-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 23px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 43px rgba(51, 65, 85, .085);
          }

          .position-trash-card-header {
               display: flex;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               padding: 21px 24px;
               border-bottom: 1px solid #edf1f7;
               background: linear-gradient(90deg, #fff, #fff8fa, #f7f5ff);
          }

          .position-trash-card-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--trash-text);
               font-size: 1.04rem;
               font-weight: 830;
          }

          .position-trash-card-title span:first-child {
               display: inline-flex;
               width: 41px;
               height: 41px;
               color: #be123c;
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: #fff1f2;
          }

          .position-trash-card-title svg {
               width: 19px;
               height: 19px;
          }

          .position-trash-count {
               display: inline-flex;
               padding: 8px 12px;
               gap: 7px;
               align-items: center;
               color: #be123c;
               font-size: .75rem;
               font-weight: 820;
               border: 1px solid #fecdd3;
               border-radius: 999px;
               background: #fff1f2;
          }

          .position-trash-count svg {
               width: 15px;
               height: 15px;
          }

          .position-trash-card-body {
               padding: 10px 18px 20px;
          }

          .position-trash-table {
               min-width: 1050px;
               margin-bottom: 0;
          }

          .position-trash-table thead th {
               padding: 15px 13px;
               color: #52627a;
               font-size: .70rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               border-top: 0;
               border-bottom: 1px solid #e8edf4;
               background: #fbfcff;
          }

          .position-trash-table tbody td {
               padding: 16px 13px;
               color: #41506a;
               font-size: .84rem;
               vertical-align: middle;
               border-color: #eef2f7;
          }

          .position-trash-table tbody tr:hover {
               background: #fffafb;
          }

          .position-trash-number {
               display: inline-flex;
               width: 34px;
               height: 34px;
               color: #64748b;
               font-size: .76rem;
               font-weight: 810;
               align-items: center;
               justify-content: center;
               border: 1px solid #e2e8f0;
               border-radius: 11px;
               background: #f8fafc;
          }

          .position-trash-name {
               color: #334155;
               font-size: .88rem;
               font-weight: 820;
          }

          .position-trash-code {
               display: inline-flex;
               padding: 4px 8px;
               margin-top: 4px;
               color: #5b21b6;
               font-size: .68rem;
               font-weight: 850;
               border: 1px solid #ddd6fe;
               border-radius: 8px;
               background: #f5f3ff;
          }

          .position-trash-department {
               font-weight: 760;
          }

          .position-trash-department-code {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: .69rem;
          }

          .position-trash-level {
               display: inline-flex;
               padding: 7px 10px;
               color: #9a3412;
               font-size: .73rem;
               font-weight: 810;
               border: 1px solid #fed7aa;
               border-radius: 10px;
               background: #fff7ed;
          }

          .position-trash-date {
               color: #64748b;
               font-size: .76rem;
               line-height: 1.45;
          }

          .position-trash-date strong {
               display: block;
               color: #475569;
          }

          .position-trash-actions {
               display: flex;
               gap: 8px;
               justify-content: flex-end;
          }

          .position-trash-action {
               display: inline-flex;
               min-height: 38px;
               padding: 8px 11px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               font-size: .74rem;
               font-weight: 810;
               border-radius: 11px;
               transition: .2s ease;
          }

          .position-trash-action svg {
               width: 15px;
               height: 15px;
          }

          .position-trash-restore {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .position-trash-restore:hover {
               color: #065f46;
               background: #d1fae5;
               transform: translateY(-2px);
          }

          .position-trash-force {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .position-trash-force:hover {
               color: #9f1239;
               background: #ffe4e6;
               transform: translateY(-2px);
          }

          .position-trash-empty {
               padding: 68px 24px !important;
               text-align: center;
          }

          .position-trash-empty-icon {
               display: inline-flex;
               width: 76px;
               height: 76px;
               margin-bottom: 17px;
               color: #be123c;
               align-items: center;
               justify-content: center;
               border: 1px solid #fecdd3;
               border-radius: 23px;
               background: linear-gradient(135deg, #fff1f2, #f5f3ff);
          }

          .position-trash-empty-icon svg {
               width: 32px;
               height: 32px;
          }

          .position-trash-empty h3 {
               margin: 0 0 7px;
               color: var(--trash-text);
               font-size: 1.04rem;
               font-weight: 830;
          }

          .position-trash-empty p {
               max-width: 460px;
               margin: 0 auto;
               color: var(--trash-muted);
               font-size: .84rem;
               line-height: 1.65;
          }

          .position-trash-pagination {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               padding: 18px 7px 0;
               border-top: 1px solid #eef2f7;
          }

          .position-trash-pagination-info {
               color: var(--trash-muted);
               font-size: .78rem;
               font-weight: 650;
          }

          .position-trash-pagination-info strong {
               color: var(--trash-text);
          }

          @media (max-width: 991.98px) {
               .position-trash-hero-content {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .position-trash-back {
                    width: 100%;
               }
          }

          @media (max-width: 767.98px) {
               .position-trash-page {
                    padding: 20px 11px 34px;
               }

               .position-trash-hero {
                    padding: 23px 20px;
               }

               .position-trash-title-wrap {
                    align-items: flex-start;
               }

               .position-trash-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }

               .position-trash-card-header {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .position-trash-pagination {
                    justify-content: center;
                    text-align: center;
               }
          }

          @media (max-width: 479.98px) {
               .position-trash-title-wrap {
                    flex-direction: column;
               }

               .position-trash-filter-actions {
                    grid-template-columns: 1fr;
               }
          }
     </style>

     <div class="position-trash-page">
          <div class="position-trash-container">
               <section class="position-trash-hero">
                    <div class="position-trash-hero-content">
                         <div class="position-trash-title-wrap">
                              <span class="position-trash-hero-icon">
                                   <i data-feather="trash-2"></i>
                              </span>

                              <div>
                                   <h1>Sampah Jabatan</h1>

                                   <p>
                                        Kelola data jabatan yang telah dihapus. Data dapat
                                        dikembalikan atau dihapus secara permanen.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ route('super-admin.positions.index') }}" class="position-trash-back">
                              <i data-feather="arrow-left"></i>
                              <span>Kembali ke Daftar Jabatan</span>
                         </a>
                    </div>
               </section>

               @if (session('success'))
                    <div class="alert alert-dismissible fade show position-trash-alert position-trash-alert-success"
                         role="alert">
                         <i data-feather="check-circle"></i>

                         <span>{{ session('success') }}</span>

                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-dismissible fade show position-trash-alert position-trash-alert-danger"
                         role="alert">
                         <i data-feather="alert-circle"></i>

                         <span>{{ session('error') }}</span>

                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
               @endif

               <section class="position-trash-filter">
                    <div class="position-trash-filter-title">
                         <span>
                              <i data-feather="filter"></i>
                         </span>

                         <strong>Filter Data Sampah</strong>
                    </div>

                    <form method="GET" action="{{ route('super-admin.positions.trash') }}">
                         <div class="row g-3">
                              <div class="col-12 col-lg-5">
                                   <label for="trash-search" class="position-trash-label">
                                        Kata Kunci
                                   </label>

                                   <div class="position-trash-search">
                                        <i data-feather="search"></i>

                                        <input type="search" id="trash-search" name="search" value="{{ $search }}"
                                             class="form-control position-trash-control"
                                             placeholder="Cari kode, nama, deskripsi, atau departemen">
                                   </div>
                              </div>

                              <div class="col-12 col-lg-4">
                                   <label for="trash-department" class="position-trash-label">
                                        Departemen
                                   </label>

                                   <select id="trash-department" name="department_id"
                                        class="form-select position-trash-control">
                                        <option value="">Semua Departemen</option>

                                        @foreach ($departments as $department)
                                             <option value="{{ $department->id }}" @selected((string) $departmentId === (string) $department->id)>
                                                  {{ $department->code }}
                                                  —
                                                  {{ $department->name }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-lg-3">
                                   <div class="position-trash-filter-actions">
                                        <button type="submit" class="position-trash-btn position-trash-btn-primary">
                                             <i data-feather="search"></i>
                                             <span>Terapkan</span>
                                        </button>

                                        <a href="{{ route('super-admin.positions.trash') }}"
                                             class="position-trash-btn position-trash-btn-secondary">
                                             <i data-feather="rotate-ccw"></i>
                                             <span>Reset</span>
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </section>

               <section class="position-trash-card">
                    <header class="position-trash-card-header">
                         <h2 class="position-trash-card-title">
                              <span>
                                   <i data-feather="archive"></i>
                              </span>

                              <strong>Daftar Jabatan Terhapus</strong>
                         </h2>

                         <span class="position-trash-count">
                              <i data-feather="database"></i>
                              {{ number_format($totalTrash) }} data
                         </span>
                    </header>

                    <div class="position-trash-card-body">
                         <div class="table-responsive">
                              <table class="table position-trash-table">
                                   <thead>
                                        <tr>
                                             <th style="width: 68px;">No.</th>
                                             <th>Jabatan</th>
                                             <th>Departemen</th>
                                             <th>Level</th>
                                             <th>Dihapus Pada</th>
                                             <th class="text-end">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($positions as $position)
                                             <tr>
                                                  <td>
                                                       <span class="position-trash-number">
                                                            {{ $positions->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="position-trash-name">
                                                            {{ $position->name }}
                                                       </div>

                                                       <span class="position-trash-code">
                                                            {{ $position->code }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="position-trash-department">
                                                            {{ $position->department?->name ?? 'Departemen tidak tersedia' }}
                                                       </span>

                                                       <span class="position-trash-department-code">
                                                            {{ $position->department?->code ?? '—' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="position-trash-level">
                                                            Level {{ $position->level }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="position-trash-date">
                                                            <strong>
                                                                 {{ $position->deleted_at?->format('d M Y') ?? '—' }}
                                                            </strong>

                                                            <span>
                                                                 {{ $position->deleted_at?->format('H:i') ?? '—' }}
                                                                 WIB
                                                            </span>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <div class="position-trash-actions">
                                                            @if ($routeHas('super-admin.positions.restore'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.positions.restore', $position->getKey()) }}"
                                                                      onsubmit="return confirm(
                                                              'Kembalikan jabatan {{ addslashes($position->name) }}?'
                                                          );">
                                                                      @csrf

                                                                      <button type="submit"
                                                                           class="position-trash-action position-trash-restore">
                                                                           <i data-feather="rotate-ccw"></i>
                                                                           <span>Kembalikan</span>
                                                                      </button>
                                                                 </form>
                                                            @endif

                                                            @if ($routeHas('super-admin.positions.force-delete'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.positions.force-delete', $position->getKey()) }}"
                                                                      onsubmit="return confirm(
                                                              'PERINGATAN: Jabatan {{ addslashes($position->name) }} akan dihapus permanen dan tidak dapat dikembalikan. Lanjutkan?'
                                                          );">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="position-trash-action position-trash-force">
                                                                           <i data-feather="trash-2"></i>
                                                                           <span>Hapus Permanen</span>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="6" class="position-trash-empty">
                                                       <span class="position-trash-empty-icon">
                                                            <i
                                                                 data-feather="{{ $hasActiveFilters ? 'search' : 'trash' }}"></i>
                                                       </span>

                                                       <h3>
                                                            {{ $hasActiveFilters ? 'Data sampah tidak ditemukan' : 'Sampah jabatan kosong' }}
                                                       </h3>

                                                       <p>
                                                            @if ($hasActiveFilters)
                                                                 Tidak ada data yang cocok dengan filter.
                                                                 Silakan ubah atau reset filter.
                                                            @else
                                                                 Belum ada jabatan yang dipindahkan ke sampah.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilters)
                                                            <a href="{{ route('super-admin.positions.trash') }}"
                                                                 class="position-trash-btn position-trash-btn-secondary mt-3">
                                                                 <i data-feather="rotate-ccw"></i>
                                                                 <span>Reset Filter</span>
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($positions->hasPages())
                              <div class="position-trash-pagination">
                                   <div class="position-trash-pagination-info">
                                        Menampilkan
                                        <strong>{{ number_format($positions->firstItem() ?? 0) }}</strong>
                                        sampai
                                        <strong>{{ number_format($positions->lastItem() ?? 0) }}</strong>
                                        dari
                                        <strong>{{ number_format($positions->total()) }}</strong>
                                        data
                                   </div>

                                   <div>
                                        {{ $positions->onEachSide(1)->links() }}
                                   </div>
                              </div>
                         @endif
                    </div>
               </section>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
