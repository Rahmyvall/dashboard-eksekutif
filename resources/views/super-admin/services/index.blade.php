@extends('layouts.app')

@section('page-title', 'Service Management')

@section('content')
     <style>
          :root {
               --service-primary: #4f46e5;
               --service-purple: #7c3aed;
               --service-cyan: #0891b2;
               --service-text: #1e293b;
               --service-muted: #64748b;
          }

          .service-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 45px;
               background: radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .18), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .16), transparent 25%),
                    linear-gradient(145deg, #fbfdff, #f7f5ff 52%, #f0fbff);
          }

          .service-container {
               max-width: 1680px;
               margin: 0 auto;
          }

          .service-hero {
               position: relative;
               overflow: hidden;
               padding: 32px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .7);
               border-radius: 28px;
               background: radial-gradient(circle at 86% 18%, rgba(255, 255, 255, .3), transparent 23%),
                    linear-gradient(120deg, #6366f1, #7c3aed 48%, #06b6d4);
               box-shadow: 0 22px 52px rgba(79, 70, 229, .22);
          }

          .service-hero::after {
               position: absolute;
               right: 7%;
               bottom: -125px;
               width: 250px;
               height: 250px;
               content: '';
               border: 30px solid rgba(255, 255, 255, .1);
               border-radius: 50%;
               box-shadow: 0 0 0 25px rgba(255, 255, 255, .05);
          }

          .service-hero-content,
          .service-title-row,
          .service-actions {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
          }

          .service-hero-content {
               position: relative;
               z-index: 1;
          }

          .service-heading {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .service-hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               align-items: center;
               justify-content: center;
               color: var(--service-primary);
               font-size: 1.7rem;
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .18);
          }

          .service-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.8vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .service-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .9);
               line-height: 1.65;
          }

          .service-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               margin-top: 16px;
          }

          .service-hero-meta span {
               padding: 6px 11px;
               font-size: .74rem;
               font-weight: 700;
               border: 1px solid rgba(255, 255, 255, .24);
               border-radius: 999px;
               background: rgba(255, 255, 255, .12);
          }

          .btn-service-primary,
          .btn-service-secondary {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 17px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border-radius: 14px;
               transition: .2s ease;
          }

          .btn-service-primary {
               color: #4338ca;
               background: #fff;
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
          }

          .btn-service-primary:hover {
               color: #312e81;
               transform: translateY(-2px);
          }

          .btn-service-secondary {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .35);
               background: rgba(255, 255, 255, .13);
          }

          .btn-service-secondary:hover {
               color: #fff;
               background: rgba(255, 255, 255, .23);
          }

          .service-stat {
               position: relative;
               height: 100%;
               padding: 21px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .95);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: .22s ease;
          }

          .service-stat:hover {
               transform: translateY(-4px);
               box-shadow: 0 20px 38px rgba(51, 65, 85, .13);
          }

          .service-stat::after {
               position: absolute;
               right: -32px;
               bottom: -45px;
               width: 125px;
               height: 125px;
               content: '';
               border: 18px solid rgba(255, 255, 255, .38);
               border-radius: 50%;
          }

          .stat-total {
               color: #4338ca;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .stat-active {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #ccfbf1);
          }

          .stat-inactive {
               color: #be123c;
               background: linear-gradient(135deg, #fff1f2, #ffe4e6);
          }

          .stat-page {
               color: #b45309;
               background: linear-gradient(135deg, #fff7ed, #fef3c7);
          }

          .stat-inner {
               display: flex;
               align-items: center;
               justify-content: space-between;
          }

          .stat-title {
               margin-bottom: 7px;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .76;
          }

          .stat-value {
               font-size: 2.2rem;
               font-weight: 850;
               line-height: 1;
          }

          .stat-caption {
               margin-top: 8px;
               font-size: .78rem;
               font-weight: 650;
               opacity: .7;
          }

          .stat-icon {
               display: inline-flex;
               width: 54px;
               height: 54px;
               align-items: center;
               justify-content: center;
               font-size: 1.35rem;
               border-radius: 17px;
               background: rgba(255, 255, 255, .72);
          }

          .service-card {
               padding: 20px;
               margin-top: 22px;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 23px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .075);
          }

          .filter-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 16px;
               color: var(--service-text);
               font-size: .94rem;
               font-weight: 820;
          }

          .filter-control {
               min-height: 47px;
               color: var(--service-text);
               border: 1px solid #dbe3ef;
               border-radius: 13px;
          }

          .search-shell {
               position: relative;
          }

          .search-shell i {
               position: absolute;
               top: 50%;
               left: 15px;
               color: #818cf8;
               transform: translateY(-50%);
          }

          .search-shell input {
               padding-left: 42px;
          }

          .filter-actions {
               display: flex;
               gap: 9px;
               align-items: center;
          }

          .btn-filter,
          .btn-reset {
               display: inline-flex;
               min-height: 47px;
               padding: 0 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
          }

          .btn-filter {
               flex: 1;
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, var(--service-primary), var(--service-purple), var(--service-cyan));
          }

          .btn-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .service-table-card {
               overflow: hidden;
               padding: 0;
          }

          .service-table-header {
               padding: 22px 24px;
               border-bottom: 1px solid #edf1f7;
               background: linear-gradient(90deg, #fff, #faf8ff 52%, #f0fbff);
          }

          .list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--service-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--service-muted);
               font-size: .81rem;
          }

          .result-badge {
               display: inline-flex;
               padding: 8px 12px;
               gap: 7px;
               align-items: center;
               color: #6d28d9;
               font-size: .76rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .service-table-body {
               padding: 10px 18px 20px;
          }

          .service-table {
               min-width: 1120px;
               margin-bottom: 0;
          }

          .service-table thead th {
               padding: 15px 13px;
               color: #52627a;
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               border: 0;
               background: linear-gradient(180deg, #fafbff, #f2f5ff);
          }

          .service-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
          }

          .service-table tbody tr {
               transition: .18s ease;
          }

          .service-table tbody tr:hover {
               background: #fafbff;
          }

          .number-badge {
               display: inline-flex;
               width: 35px;
               height: 35px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .77rem;
               font-weight: 820;
               border-radius: 11px;
               background: #eef2ff;
          }

          .code-badge {
               display: inline-flex;
               padding: 7px 10px;
               color: #4338ca;
               font-size: .76rem;
               font-weight: 850;
               letter-spacing: .04em;
               border: 1px solid #c7d2fe;
               border-radius: 10px;
               background: #eef2ff;
          }

          .service-name {
               display: block;
               color: #1e293b;
               font-size: .94rem;
               font-weight: 820;
          }

          .service-id {
               display: inline-flex;
               margin-top: 5px;
               padding: 4px 8px;
               color: #6d28d9;
               font-size: .7rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 8px;
               background: #f5f3ff;
          }

          .description-text {
               display: block;
               max-width: 300px;
               color: #64748b;
               font-size: .82rem;
               line-height: 1.55;
          }

          .category-pill,
          .unit-pill {
               display: inline-flex;
               padding: 6px 10px;
               color: #475569;
               font-size: .76rem;
               font-weight: 700;
               border: 1px solid #e2e8f0;
               border-radius: 9px;
               background: #f8fafc;
          }

          .price-value {
               display: block;
               color: #0f172a;
               font-size: .9rem;
               font-weight: 850;
               white-space: nowrap;
          }

          .duration-value {
               display: block;
               color: #475569;
               font-size: .82rem;
               font-weight: 700;
               white-space: nowrap;
          }

          .custom-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 6px;
               align-items: center;
               font-size: .73rem;
               font-weight: 780;
               white-space: nowrap;
               border: 1px solid transparent;
               border-radius: 999px;
          }

          .badge-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .badge-inactive {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .action-group {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               white-space: nowrap;
          }

          .action-btn {
               display: inline-flex;
               width: 38px;
               height: 38px;
               padding: 0;
               align-items: center;
               justify-content: center;
               border: 0;
               border-radius: 12px;
               transition: .2s ease;
          }

          .action-btn:hover {
               transform: translateY(-2px);
          }

          .btn-view {
               color: #0369a1;
               background: #e0f2fe;
          }

          .btn-edit {
               color: #a16207;
               background: #fef3c7;
          }

          .btn-toggle-active {
               color: #047857;
               background: #d1fae5;
          }

          .btn-toggle-inactive,
          .btn-delete {
               color: #be123c;
               background: #ffe4e6;
          }

          .empty-state {
               padding: 65px 20px !important;
               text-align: center;
          }

          .empty-icon {
               display: inline-flex;
               width: 84px;
               height: 84px;
               margin-bottom: 16px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: 2rem;
               border-radius: 25px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe, #fce7f3);
          }

          .pagination-wrapper {
               display: flex;
               padding: 18px 6px 2px;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               border-top: 1px solid #f1f5f9;
          }

          .pagination-info {
               color: #718096;
               font-size: .78rem;
               font-weight: 650;
          }

          @media (max-width: 991.98px) {

               .service-hero-content,
               .service-table-header {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .service-actions {
                    width: 100%;
               }

               .btn-service-primary,
               .btn-service-secondary {
                    flex: 1;
               }
          }

          @media (max-width: 767.98px) {
               .service-page {
                    padding: 20px 12px 34px;
               }

               .service-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .service-heading {
                    align-items: flex-start;
               }

               .service-actions,
               .filter-actions {
                    align-items: stretch;
                    flex-direction: column;
               }

               .btn-service-primary,
               .btn-service-secondary,
               .btn-filter,
               .btn-reset {
                    width: 100%;
               }

               .pagination-wrapper {
                    flex-direction: column;
                    justify-content: center;
               }
          }
     </style>

     @php
          $serviceCollection = $services->getCollection();
          $activeOnPage = $serviceCollection->where('status', 'active')->count();
          $inactiveOnPage = $serviceCollection->where('status', 'inactive')->count();
          $currentSearch = request('search', '');
          $currentStatus = request('status', '');
          $currentCategory = (string) request('category', '');
          $currentSort = request('sort', 'latest');
          $currentPerPage = (string) request('per_page', 10);
          $hasActiveFilter =
              $currentSearch !== '' ||
              $currentStatus !== '' ||
              $currentCategory !== '' ||
              $currentSort !== 'latest' ||
              $currentPerPage !== '10';
          $statusLabels = ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'];
          $statusIcons = ['active' => 'bi-check-circle-fill', 'inactive' => 'bi-x-circle-fill'];
          $sortOptions = [
              'latest' => 'Terbaru',
              'oldest' => 'Terlama',
              'name_asc' => 'Nama A–Z',
              'name_desc' => 'Nama Z–A',
              'price_asc' => 'Harga terendah',
              'price_desc' => 'Harga tertinggi',
          ];
     @endphp

     <div class="service-page">
          <div class="service-container">
               <div class="service-hero">
                    <div class="service-hero-content">
                         <div class="service-heading">
                              <div class="service-hero-icon"><i class="bi bi-briefcase-fill"></i></div>
                              <div>
                                   <h1>Service Management</h1>
                                   <p>Kelola layanan perusahaan berdasarkan struktur tabel <strong>services</strong>:
                                        kategori, kode, harga, unit, durasi, dan status.</p>
                                   <div class="service-hero-meta"><span><i class="bi bi-database-check me-1"></i> Database
                                             terintegrasi</span><span><i class="bi bi-clock-history me-1"></i> Durasi
                                             layanan</span></div>
                              </div>
                         </div>
                         <div class="service-actions">
                              <a href="{{ route('super-admin.services.create') }}" class="btn-service-primary"><i
                                        class="bi bi-plus-circle-fill"></i> Tambah Service</a>
                         </div>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert"><i
                              class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
               @endif
               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert"><i
                              class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div>
               @endif
               @if ($errors->any())
                    <div class="alert alert-danger custom-alert" role="alert"><strong><i
                                   class="bi bi-exclamation-octagon-fill me-2"></i>Terjadi kesalahan:</strong>
                         <ul class="mb-0">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <div class="row g-3">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="service-stat stat-total">
                              <div class="stat-inner">
                                   <div>
                                        <div class="stat-title">Total Service</div>
                                        <div class="stat-value">{{ number_format($services->total()) }}</div>
                                        <div class="stat-caption">Sesuai filter aktif</div>
                                   </div>
                                   <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
                              </div>
                         </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="service-stat stat-active">
                              <div class="stat-inner">
                                   <div>
                                        <div class="stat-title">Aktif</div>
                                        <div class="stat-value">{{ number_format($activeOnPage) }}</div>
                                        <div class="stat-caption">Pada halaman ini</div>
                                   </div>
                                   <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                              </div>
                         </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="service-stat stat-inactive">
                              <div class="stat-inner">
                                   <div>
                                        <div class="stat-title">Tidak Aktif</div>
                                        <div class="stat-value">{{ number_format($inactiveOnPage) }}</div>
                                        <div class="stat-caption">Pada halaman ini</div>
                                   </div>
                                   <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                              </div>
                         </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="service-stat stat-page">
                              <div class="stat-inner">
                                   <div>
                                        <div class="stat-title">Halaman</div>
                                        <div class="stat-value">{{ $services->currentPage() }}</div>
                                        <div class="stat-caption">Dari {{ $services->lastPage() }} halaman</div>
                                   </div>
                                   <div class="stat-icon"><i class="bi bi-files"></i></div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="service-card">
                    <div class="filter-title"><i class="bi bi-funnel-fill"></i> Pencarian dan Filter Service</div>
                    <form method="GET" action="{{ route('super-admin.services.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-lg-4"><label for="search"
                                        class="form-label fw-semibold small text-secondary">Cari Service</label>
                                   <div class="search-shell"><i class="bi bi-search"></i><input type="text" id="search"
                                             name="search" class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Kode, nama, unit, deskripsi..." autocomplete="off"></div>
                              </div>
                              <div class="col-12 col-md-6 col-lg-2"><label for="status"
                                        class="form-label fw-semibold small text-secondary">Status</label><select
                                        id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua Status</option>
                                        @foreach ($statuses as $statusKey => $statusLabel)
                                             <option value="{{ $statusKey }}" @selected($currentStatus === $statusKey)>
                                                  {{ $statusLabel }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="col-12 col-md-6 col-lg-2"><label for="category"
                                        class="form-label fw-semibold small text-secondary">Kategori</label><select
                                        id="category" name="category" class="form-select filter-control">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                             <option value="{{ $category->id }}" @selected($currentCategory === (string) $category->id)>
                                                  {{ $category->name }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="col-12 col-md-6 col-lg-2"><label for="sort"
                                        class="form-label fw-semibold small text-secondary">Urutkan</label><select
                                        id="sort" name="sort" class="form-select filter-control">
                                        @foreach ($sortOptions as $sortValue => $sortLabel)
                                             <option value="{{ $sortValue }}" @selected($currentSort === $sortValue)>
                                                  {{ $sortLabel }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="col-12 col-md-6 col-lg-2">
                                   <div class="filter-actions"><button type="submit" class="btn-filter"><i
                                                  class="bi bi-search"></i> Terapkan</button><a
                                             href="{{ route('super-admin.services.index') }}" class="btn-reset"><i
                                                  class="bi bi-arrow-counterclockwise"></i> Reset</a></div>
                              </div>
                         </div>
                    </form>
               </div>

               <div class="service-card service-table-card">
                    <div class="service-table-header">
                         <div>
                              <h5 class="list-title"><span class="list-title-icon"><i class="bi bi-list-ul"></i></span>
                                   Daftar Service</h5>
                              <p class="list-subtitle">Kolom mengikuti tabel services: service_code, name, description,
                                   base_price, duration, unit, dan status.</p>
                         </div>
                         <span class="result-badge"><i class="bi bi-database-fill"></i>
                              {{ number_format($services->total()) }} data ditemukan</span>
                    </div>
                    <div class="service-table-body">
                         <div class="table-responsive">
                              <table class="table service-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="5%">No</th>
                                             <th width="12%">Kode</th>
                                             <th width="19%">Service</th>
                                             <th width="21%">Kategori / Deskripsi</th>
                                             <th width="13%">Harga</th>
                                             <th width="10%">Durasi</th>
                                             <th width="10%">Status</th>
                                             <th width="10%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse ($services as $service)
                                             @php $normalizedStatus = in_array(strtolower((string) $service->status), ['active', 'inactive'], true) ? strtolower((string) $service->status) : 'inactive'; @endphp
                                             <tr>
                                                  <td><span
                                                            class="number-badge">{{ $services->firstItem() + $loop->index }}</span>
                                                  </td>
                                                  <td><span class="code-badge">{{ $service->service_code }}</span></td>
                                                  <td><span class="service-name">{{ $service->name }}</span><span
                                                            class="service-id">ID #{{ $service->id }}</span></td>
                                                  <td><span class="category-pill"><i
                                                                 class="bi bi-tag-fill me-1"></i>{{ $service->category?->name ?? 'Tanpa kategori' }}</span><span
                                                            class="description-text mt-2"
                                                            title="{{ $service->description }}">{{ filled($service->description) ? \Illuminate\Support\Str::limit($service->description, 90) : 'Tidak ada deskripsi.' }}</span>
                                                  </td>
                                                  <td><span class="price-value">{{ $service->formatted_price }}</span><span
                                                            class="unit-pill mt-1">/ {{ $service->unit }}</span></td>
                                                  <td><span class="duration-value"><i
                                                                 class="bi bi-stopwatch me-1"></i>{{ $service->estimated_duration_minutes ? $service->estimated_duration_minutes . ' menit' : '-' }}</span>
                                                  </td>
                                                  <td><span class="custom-badge badge-{{ $normalizedStatus }}"><i
                                                                 class="bi {{ $statusIcons[$normalizedStatus] }}"></i>{{ $statusLabels[$normalizedStatus] }}</span>
                                                  </td>
                                                  <td class="text-center">
                                                       <div class="action-group"><a
                                                                 href="{{ route('super-admin.services.show', $service) }}"
                                                                 class="btn action-btn btn-view" title="Lihat detail"
                                                                 aria-label="Lihat detail {{ $service->name }}"><i
                                                                      class="bi bi-eye-fill"></i></a><a
                                                                 href="{{ route('super-admin.services.edit', $service) }}"
                                                                 class="btn action-btn btn-edit" title="Edit service"
                                                                 aria-label="Edit {{ $service->name }}"><i
                                                                      class="bi bi-pencil-fill"></i></a>
                                                            <form action="{{ route('super-admin.services.toggle-status', $service) }}"
                                                                 method="POST" class="d-inline"
                                                                 onsubmit="return confirm('Yakin ingin mengubah status service ini?')">
                                                                 @csrf @method('PATCH')<button type="submit"
                                                                      class="btn action-btn {{ $normalizedStatus === 'active' ? 'btn-toggle-inactive' : 'btn-toggle-active' }}"
                                                                      title="Ubah status"><i
                                                                           class="bi {{ $normalizedStatus === 'active' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i></button>
                                                            </form>
                                                            <form action="{{ route('super-admin.services.destroy', $service) }}"
                                                                 method="POST" class="d-inline"
                                                                 onsubmit="return confirm('Yakin ingin memindahkan service ini ke recycle bin?')">
                                                                 @csrf @method('DELETE')<button type="submit"
                                                                      class="btn action-btn btn-delete"
                                                                      title="Hapus service"><i
                                                                           class="bi bi-trash3-fill"></i></button></form>
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="8" class="empty-state">
                                                       <div class="empty-icon"><i class="bi bi-briefcase-fill"></i></div>
                                                       <h5>Data Service Tidak Ditemukan</h5>
                                                       <p>{{ $hasActiveFilter ? 'Tidak ada service yang sesuai dengan pencarian atau filter.' : 'Tambahkan service pertama untuk mulai mengelola layanan.' }}
                                                       </p>
                                                       @if ($hasActiveFilter)
                                                            <a href="{{ route('super-admin.services.index') }}"
                                                                 class="btn-reset"><i
                                                                      class="bi bi-arrow-counterclockwise"></i> Hapus
                                                            Filter</a>@else<a
                                                                 href="{{ route('super-admin.services.create') }}"
                                                                 class="btn-filter"><i class="bi bi-plus-circle-fill"></i>
                                                                 Tambah Service</a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                         @if ($services->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">Menampilkan
                                        {{ $services->firstItem() }}–{{ $services->lastItem() }} dari
                                        {{ $services->total() }} data</div>{{ $services->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
