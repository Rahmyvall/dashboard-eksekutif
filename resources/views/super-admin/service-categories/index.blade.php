@extends('layouts.app')

@section('title', 'Dashboard Monitoring Produktivitas Karyawan')

@section('content')
     <style>
          :root {
               --category-primary: #6366f1;
               --category-primary-dark: #4f46e5;
               --category-secondary: #06b6d4;
               --category-purple: #8b5cf6;
               --category-success: #10b981;
               --category-warning: #f59e0b;
               --category-danger: #ef4444;
               --category-info: #0ea5e9;
               --category-text: #24324a;
               --category-muted: #718096;
               --category-border: #e7eaf3;
               --category-white: #ffffff;
          }

          .category-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .category-container {
               max-width: 1680px;
               margin: 0 auto;
          }

          .category-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .7);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 42%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .category-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .hero-title-wrap {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               color: var(--category-primary-dark);
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .category-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.5vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .category-hero p {
               max-width: 780px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .97rem;
               line-height: 1.7;
          }

          .hero-actions {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               align-items: center;
               justify-content: flex-end;
          }

          .btn-hero,
          .btn-trash-link {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               font-size: .88rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border-radius: 14px;
               transition: .22s ease;
          }

          .btn-hero {
               color: #4338ca;
               border: 1px solid rgba(255, 255, 255, .8);
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
          }

          .btn-hero:hover {
               color: #312e81;
               background: #fff;
               transform: translateY(-2px);
          }

          .btn-trash-link {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .13);
          }

          .btn-trash-link:hover {
               color: #fff;
               background: rgba(255, 255, 255, .22);
               transform: translateY(-2px);
          }

          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: flex-start;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--category-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--category-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .custom-alert ul {
               margin: 0;
               padding-left: 18px;
          }

          .stats-row {
               margin-bottom: 22px;
          }

          .stat-card {
               position: relative;
               min-height: 138px;
               padding: 22px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .95);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: .23s ease;
          }

          .stat-card:hover {
               transform: translateY(-4px);
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

          .stat-card-inner {
               display: flex;
               align-items: center;
               justify-content: space-between;
          }

          .stat-title {
               margin-bottom: 7px;
               font-size: .74rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .stat-value {
               font-size: 2.25rem;
               font-weight: 850;
               line-height: 1;
          }

          .stat-caption {
               margin-top: 8px;
               font-size: .8rem;
               font-weight: 650;
               opacity: .72;
          }

          .stat-icon {
               display: inline-flex;
               width: 54px;
               height: 54px;
               align-items: center;
               justify-content: center;
               font-size: 1.42rem;
               border-radius: 17px;
               background: rgba(255, 255, 255, .72);
          }

          .monitoring-row {
               margin-bottom: 22px;
          }

          .monitoring-card {
               height: 100%;
               padding: 20px;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 22px;
               background: rgba(255, 255, 255, .93);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
          }

          .monitoring-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 15px;
               color: var(--category-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .monitoring-title span {
               display: grid;
               width: 36px;
               height: 36px;
               place-items: center;
               color: var(--category-primary-dark);
               border-radius: 11px;
               background: #edf2ff;
          }

          .monitor-mini {
               height: 100%;
               padding: 14px;
               border: 1px solid #e5eaf4;
               border-radius: 14px;
               background: linear-gradient(160deg, #ffffff 0%, #f8fbff 100%);
          }

          .monitor-mini-label {
               display: block;
               margin-bottom: 6px;
               color: #64748b;
               font-size: .71rem;
               font-weight: 780;
               letter-spacing: .05em;
               text-transform: uppercase;
          }

          .monitor-mini-value {
               display: block;
               color: #0f172a;
               font-size: 1.35rem;
               font-weight: 850;
               line-height: 1.1;
               letter-spacing: -.02em;
          }

          .monitor-mini-caption {
               margin-top: 7px;
               color: #64748b;
               font-size: .75rem;
               font-weight: 650;
          }

          .filter-card {
               padding: 20px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 22px;
               background: rgba(255, 255, 255, .93);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
          }

          .filter-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 15px;
               color: var(--category-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .filter-control {
               min-height: 47px;
               color: var(--category-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #fff;
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

          .search-shell .form-control {
               padding-left: 42px;
          }

          .filter-actions {
               display: flex;
               gap: 10px;
               align-items: center;
          }

          .btn-filter,
          .btn-reset {
               display: inline-flex;
               min-height: 47px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .btn-filter {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg,
                         var(--category-primary),
                         var(--category-purple),
                         var(--category-secondary));
          }

          .btn-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .category-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .category-card-header {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #fff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--category-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: var(--category-primary-dark);
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--category-muted);
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

          .category-card-body {
               padding: 10px 18px 20px;
          }

          .category-table {
               min-width: 1120px;
               margin-bottom: 0;
          }

          .category-table thead th {
               padding: 15px 13px;
               color: #52627a;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               vertical-align: middle;
               border: 0;
               background: linear-gradient(180deg, #fafbff, #f2f5ff);
          }

          .category-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
          }

          .number-badge {
               display: inline-flex;
               width: 35px;
               height: 35px;
               align-items: center;
               justify-content: center;
               color: var(--category-primary-dark);
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

          .category-name {
               display: block;
               color: #1e293b;
               font-size: .94rem;
               font-weight: 820;
          }

          .category-id {
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
               max-width: 390px;
               color: #64748b;
               font-size: .82rem;
               line-height: 1.6;
          }

          .date-value {
               display: block;
               color: #475569;
               font-size: .82rem;
               font-weight: 760;
          }

          .date-caption {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: .7rem;
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
               transition: .21s ease;
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

          .btn-toggle-inactive {
               color: #be123c;
               background: #ffe4e6;
          }

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
               color: var(--category-primary-dark);
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

               .hero-content,
               .category-card-header {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hero-actions,
               .filter-actions {
                    width: 100%;
               }

               .btn-hero,
               .btn-trash-link,
               .btn-filter,
               .btn-reset {
                    flex: 1;
               }
          }

          @media (max-width: 767.98px) {
               .category-page {
                    padding: 20px 12px 34px;
               }

               .category-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .hero-title-wrap {
                    align-items: flex-start;
               }

               .hero-actions,
               .filter-actions {
                    flex-direction: column;
               }

               .btn-hero,
               .btn-trash-link,
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
          $categoryCollection = $serviceCategories->getCollection();

          $activeOnPage = $categoryCollection->where('status', 'active')->count();
          $inactiveOnPage = $categoryCollection->where('status', 'inactive')->count();

          $currentSearch = request('search', '');
          $currentStatus = request('status', '');
          $currentSort = request('sort', 'latest');
          $currentPerPage = (string) request('per_page', 10);

          $hasActiveFilter =
              $currentSearch !== '' || $currentStatus !== '' || $currentSort !== 'latest' || $currentPerPage !== '10';

          $statusLabels = [
              'active' => 'Aktif',
              'inactive' => 'Tidak Aktif',
          ];

          $statusIcons = [
              'active' => 'bi-check-circle-fill',
              'inactive' => 'bi-x-circle-fill',
          ];

          $sortOptions = [
              'latest' => 'Terbaru',
              'oldest' => 'Terlama',
              'name_asc' => 'Nama A–Z',
              'name_desc' => 'Nama Z–A',
              'code_asc' => 'Kode A–Z',
              'code_desc' => 'Kode Z–A',
          ];

          $monitoringStats = array_replace(
              [
                  'employees_total' => 0,
                  'employees_active' => 0,
                  'activities_today' => 0,
                  'activities_pending_verify' => 0,
                  'service_orders_this_month' => 0,
                  'service_orders_processing' => 0,
                  'invoices_unpaid' => 0,
                  'payments_pending' => 0,
                  'payments_confirmed_this_month' => 0,
                  'service_revenue_this_month' => 0,
              ],
              is_array($monitoringStats ?? null) ? $monitoringStats : [],
          );
     @endphp

     <div class="category-page">
          <div class="category-container">
               <div class="category-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-speedometer2"></i>
                              </div>

                              <div>
                                   <h1>Dashboard Monitoring Produktivitas Karyawan</h1>
                                   <p>
                                        Pantau produktivitas tim dan transaksi jasa dari aktivitas harian,
                                        service order, invoice, serta status pembayaran dalam satu dashboard.
                                   </p>
                              </div>
                         </div>

                         <div class="hero-actions">
                              @if (Route::has('super-admin.employee-activities.index'))
                                   <a href="{{ route('super-admin.employee-activities.index') }}" class="btn-trash-link">
                                        <i class="bi bi-activity"></i>
                                        Aktivitas
                                   </a>
                              @endif

                              @if (Route::has('super-admin.service-orders.index'))
                                   <a href="{{ route('super-admin.service-orders.index') }}" class="btn-trash-link">
                                        <i class="bi bi-receipt"></i>
                                        Service Order
                                   </a>
                              @endif

                              @if (Route::has('super-admin.service-categories.trashed'))
                                   <a href="{{ route('super-admin.service-categories.trashed') }}" class="btn-trash-link">
                                        <i class="bi bi-trash3-fill"></i>
                                        Recycle Bin
                                   </a>
                              @endif

                              @if (Route::has('super-admin.service-categories.create'))
                                   <a href="{{ route('super-admin.service-categories.create') }}" class="btn-hero">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        Tambah Kategori
                                   </a>
                              @endif
                         </div>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               @if ($errors->any())
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-octagon-fill"></i>
                         <div>
                              <strong>Terjadi kesalahan:</strong>
                              <ul>
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    </div>
               @endif

               <div class="row g-3 stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-total h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Karyawan</div>
                                        <div class="stat-value">
                                             {{ number_format((int) $monitoringStats['employees_total']) }}</div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $monitoringStats['employees_active']) }} karyawan aktif
                                        </div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-people-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-active h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Aktivitas Hari Ini</div>
                                        <div class="stat-value">
                                             {{ number_format((int) $monitoringStats['activities_today']) }}</div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $monitoringStats['activities_pending_verify']) }}
                                             pending verifikasi</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-activity"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-inactive h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Order Jasa Bulan Ini</div>
                                        <div class="stat-value">
                                             {{ number_format((int) $monitoringStats['service_orders_this_month']) }}</div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $monitoringStats['service_orders_processing']) }} sedang
                                             diproses</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-box-seam"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-page h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Payment Terkonfirmasi</div>
                                        <div class="stat-value">Rp
                                             {{ number_format((float) $monitoringStats['payments_confirmed_this_month'], 0, ',', '.') }}
                                        </div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $monitoringStats['invoices_unpaid']) }} invoice belum
                                             lunas</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-cash-stack"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="row g-3 monitoring-row">
                    <div class="col-12">
                         <div class="monitoring-card">
                              <div class="monitoring-title">
                                   <span>
                                        <i class="bi bi-graph-up-arrow"></i>
                                   </span>
                                   Monitoring Produktivitas dan Transaksi Jasa
                              </div>

                              <div class="row g-3">
                                   <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Aktivitas Terverifikasi</span>
                                             <span class="monitor-mini-value">
                                                  {{ number_format(max((int) $monitoringStats['activities_today'] - (int) $monitoringStats['activities_pending_verify'], 0)) }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  dari {{ number_format((int) $monitoringStats['activities_today']) }}
                                                  aktivitas hari ini
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Pendapatan Jasa/Bulan</span>
                                             <span class="monitor-mini-value">
                                                  Rp
                                                  {{ number_format((float) $monitoringStats['service_revenue_this_month'], 0, ',', '.') }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  berdasarkan invoice bulan berjalan
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Payment Pending</span>
                                             <span class="monitor-mini-value">
                                                  {{ number_format((int) $monitoringStats['payments_pending']) }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  menunggu tindak lanjut konfirmasi
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Kategori Aktif (Halaman)</span>
                                             <span
                                                  class="monitor-mini-value">{{ number_format((int) $activeOnPage) }}</span>
                                             <div class="monitor-mini-caption">
                                                  {{ number_format((int) $serviceCategories->total()) }} kategori sesuai
                                                  filter
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="filter-card">
                    <div class="filter-title">
                         <i class="bi bi-funnel-fill"></i>
                         Pencarian dan Filter Kategori Layanan
                    </div>

                    <form method="GET" action="{{ route('super-admin.service-categories.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-lg-4">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Kategori
                                   </label>

                                   <div class="search-shell">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="search" name="search"
                                             class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Cari kode, nama, atau deskripsi..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="status" class="form-label fw-semibold small text-secondary">
                                        Status
                                   </label>

                                   <select id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua Status</option>

                                        @foreach ($statuses as $statusKey => $statusLabel)
                                             @php
                                                  $optionValue = is_string($statusKey)
                                                      ? $statusKey
                                                      : (string) $statusLabel;

                                                  $optionLabel = is_string($statusKey)
                                                      ? $statusLabel
                                                      : $statusLabels[$optionValue] ??
                                                          \Illuminate\Support\Str::of($optionValue)
                                                              ->replace('_', ' ')
                                                              ->title();
                                             @endphp

                                             <option value="{{ $optionValue }}" @selected($currentStatus === $optionValue)>
                                                  {{ $optionLabel }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="sort" class="form-label fw-semibold small text-secondary">
                                        Urutkan
                                   </label>

                                   <select id="sort" name="sort" class="form-select filter-control">
                                        @foreach ($sortOptions as $sortValue => $sortLabel)
                                             <option value="{{ $sortValue }}" @selected($currentSort === $sortValue)>
                                                  {{ $sortLabel }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="per_page" class="form-label fw-semibold small text-secondary">
                                        Data per Halaman
                                   </label>

                                   <select id="per_page" name="per_page" class="form-select filter-control">
                                        @foreach ([10, 25, 50, 100] as $pageSize)
                                             <option value="{{ $pageSize }}" @selected($currentPerPage === (string) $pageSize)>
                                                  {{ $pageSize }} data
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <div class="filter-actions">
                                        <button type="submit" class="btn-filter">
                                             <i class="bi bi-search"></i>
                                             Terapkan
                                        </button>

                                        <a href="{{ route('super-admin.service-categories.index') }}" class="btn-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               <div class="category-card">
                    <div class="category-card-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon">
                                        <i class="bi bi-list-ul"></i>
                                   </span>
                                   Daftar Kategori Layanan
                              </h5>

                              <p class="list-subtitle">
                                   Menampilkan kode, nama, deskripsi, status, dan tanggal dibuat.
                              </p>
                         </div>

                         <span class="result-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ $serviceCategories->total() }} data ditemukan
                         </span>
                    </div>

                    <div class="category-card-body">
                         <div class="table-responsive">
                              <table class="table category-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="5%">No</th>
                                             <th width="12%">Kode</th>
                                             <th width="20%">Nama Kategori</th>
                                             <th width="28%">Deskripsi</th>
                                             <th width="12%">Status</th>
                                             <th width="11%">Dibuat</th>
                                             <th width="12%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($serviceCategories as $serviceCategory)
                                             @php
                                                  $categoryStatus = strtolower((string) $serviceCategory->status);

                                                  $normalizedStatus = in_array(
                                                      $categoryStatus,
                                                      ['active', 'inactive'],
                                                      true,
                                                  )
                                                      ? $categoryStatus
                                                      : 'inactive';
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $serviceCategories->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="code-badge">
                                                            {{ $serviceCategory->code }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="category-name">
                                                            {{ $serviceCategory->name }}
                                                       </span>

                                                       <span class="category-id">
                                                            ID #{{ $serviceCategory->id }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="description-text"
                                                            title="{{ $serviceCategory->description }}">
                                                            {{ filled($serviceCategory->description)
                                                                ? \Illuminate\Support\Str::limit($serviceCategory->description, 120)
                                                                : 'Tidak ada deskripsi.' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="custom-badge badge-{{ $normalizedStatus }}">
                                                            <i class="bi {{ $statusIcons[$normalizedStatus] }}"></i>
                                                            {{ $statusLabels[$normalizedStatus] }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="date-value">
                                                            {{ optional($serviceCategory->created_at)->format('d M Y') ?? '-' }}
                                                       </span>
                                                       <span class="date-caption">
                                                            {{ optional($serviceCategory->created_at)->format('H:i') ?? '-' }}
                                                       </span>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            @if (Route::has('super-admin.service-categories.show'))
                                                                 <a href="{{ route('super-admin.service-categories.show', $serviceCategory) }}"
                                                                      class="btn action-btn btn-view" title="Lihat detail"
                                                                      aria-label="Lihat detail {{ $serviceCategory->name }}">
                                                                      <i class="bi bi-eye-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.service-categories.edit'))
                                                                 <a href="{{ route('super-admin.service-categories.edit', $serviceCategory) }}"
                                                                      class="btn action-btn btn-edit" title="Edit kategori"
                                                                      aria-label="Edit {{ $serviceCategory->name }}">
                                                                      <i class="bi bi-pencil-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if (Route::has('super-admin.service-categories.toggle-status'))
                                                                 <form action="{{ route('super-admin.service-categories.toggle-status', $serviceCategory) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Yakin ingin mengubah status kategori ini?')">
                                                                      @csrf
                                                                      @method('PATCH')

                                                                      <button type="submit"
                                                                           class="btn action-btn {{ $normalizedStatus === 'active' ? 'btn-toggle-inactive' : 'btn-toggle-active' }}"
                                                                           title="{{ $normalizedStatus === 'active' ? 'Nonaktifkan kategori' : 'Aktifkan kategori' }}"
                                                                           aria-label="Ubah status {{ $serviceCategory->name }}">
                                                                           <i
                                                                                class="bi {{ $normalizedStatus === 'active' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif

                                                            @if (Route::has('super-admin.service-categories.destroy'))
                                                                 <form action="{{ route('super-admin.service-categories.destroy', $serviceCategory) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Yakin ingin memindahkan kategori layanan ini ke recycle bin?')">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="btn action-btn btn-delete"
                                                                           title="Hapus kategori"
                                                                           aria-label="Hapus {{ $serviceCategory->name }}">
                                                                           <i class="bi bi-trash3-fill"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="7" class="empty-state">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-grid-1x2-fill"></i>
                                                       </div>

                                                       <h5>Data Kategori Layanan Tidak Ditemukan</h5>

                                                       <p>
                                                            @if ($hasActiveFilter)
                                                                 Tidak ada kategori layanan yang sesuai dengan
                                                                 pencarian atau filter.
                                                            @else
                                                                 Tambahkan kategori layanan pertama untuk mulai
                                                                 mengelola data layanan.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilter)
                                                            <a href="{{ route('super-admin.service-categories.index') }}"
                                                                 class="btn-reset">
                                                                 <i class="bi bi-arrow-counterclockwise"></i>
                                                                 Hapus Filter
                                                            </a>
                                                       @elseif (Route::has('super-admin.service-categories.create'))
                                                            <a href="{{ route('super-admin.service-categories.create') }}"
                                                                 class="btn-filter">
                                                                 <i class="bi bi-plus-circle-fill"></i>
                                                                 Tambah Kategori
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($serviceCategories->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan
                                        {{ $serviceCategories->firstItem() }}–{{ $serviceCategories->lastItem() }}
                                        dari {{ $serviceCategories->total() }} data
                                   </div>

                                   {{ $serviceCategories->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
