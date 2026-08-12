@extends('layouts.app')

@section('title', 'Manajemen Layanan – Monitoring Produktivitas Karyawan dan Transaksi Jasa')

@section('content')
     <style>
          :root {
               --svc-primary: #4f46e5;
               --svc-primary-dark: #3730a3;
               --svc-purple: #7c3aed;
               --svc-cyan: #0891b2;
               --svc-success: #047857;
               --svc-danger: #be123c;
               --svc-text: #0f172a;
               --svc-muted: #64748b;
               --svc-soft-indigo: #eef2ff;
          }

          .service-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 45px;
               color: var(--svc-text);
               font-family: 'Plus Jakarta Sans', 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 5% 4%, rgba(129, 140, 248, .16), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .15), transparent 25%),
                    radial-gradient(circle at 85% 92%, rgba(167, 139, 250, .14), transparent 22%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 50%, #f0fbff 100%);
          }

          .service-container {
               max-width: 1680px;
               margin: 0 auto;
          }

          /* HERO */
          .service-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .6);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .28), transparent 22%),
                    radial-gradient(circle at 68% 110%, rgba(99, 102, 241, .35), transparent 38%),
                    linear-gradient(120deg, #4f46e5 0%, #7c3aed 48%, #06b6d4 100%);
               box-shadow: 0 22px 52px rgba(79, 70, 229, .24);
          }

          .service-hero::before {
               position: absolute;
               top: -85px;
               right: 10%;
               width: 220px;
               height: 220px;
               content: '';
               border: 1px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .service-hero::after {
               position: absolute;
               right: -38px;
               bottom: -80px;
               width: 190px;
               height: 190px;
               content: '';
               border-radius: 48px;
               background: rgba(255, 255, 255, .08);
               transform: rotate(30deg);
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
               gap: 18px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 66px;
               width: 66px;
               height: 66px;
               color: var(--svc-primary);
               font-size: 1.8rem;
               align-items: center;
               justify-content: center;
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 12px 28px rgba(76, 29, 149, .20);
          }

          .hero-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 7px;
               color: rgba(255, 255, 255, .82);
               font-size: .72rem;
               font-weight: 800;
               letter-spacing: .13em;
               text-transform: uppercase;
          }

          .service-hero h1 {
               margin: 0;
               font-size: clamp(1.55rem, 2.5vw, 2.2rem);
               font-weight: 850;
               letter-spacing: -.035em;
               line-height: 1.12;
          }

          .service-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .88);
               font-size: .97rem;
               line-height: 1.7;
          }

          .hero-meta {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 10px;
               margin-top: 16px;
               max-width: 720px;
          }

          .hero-meta-item {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
               padding: 10px 12px;
               border: 1px solid rgba(255, 255, 255, .22);
               border-radius: 12px;
               background: rgba(255, 255, 255, .12);
               backdrop-filter: blur(8px);
          }

          .hero-meta-label {
               color: rgba(255, 255, 255, .72);
               font-size: .68rem;
               font-weight: 750;
               letter-spacing: .04em;
               text-transform: uppercase;
          }

          .hero-meta-value {
               color: #ffffff;
               font-size: .98rem;
               font-weight: 850;
               letter-spacing: -.02em;
          }

          .hero-actions {
               display: flex;
               gap: 10px;
               align-items: center;
          }

          .btn-hero {
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

          .btn-hero-solid {
               color: var(--svc-primary);
               border: 1px solid rgba(255, 255, 255, .85);
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 10px 22px rgba(76, 29, 149, .18);
          }

          .btn-hero-solid:hover {
               color: var(--svc-primary-dark);
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .24);
          }

          /* ALERTS */
          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--svc-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--svc-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          /* STAT CARDS */
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
               box-shadow: 0 20px 40px rgba(51, 65, 85, .12);
          }

          .stat-card::after {
               position: absolute;
               right: -28px;
               bottom: -40px;
               width: 130px;
               height: 130px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .48);
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
               position: relative;
               z-index: 2;
               display: flex;
               align-items: center;
               justify-content: space-between;
          }

          .stat-title {
               margin-bottom: 7px;
               font-size: .73rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .stat-value {
               font-size: 2.2rem;
               font-weight: 850;
               line-height: 1;
               letter-spacing: -.04em;
          }

          .stat-caption {
               margin-top: 8px;
               font-size: .78rem;
               font-weight: 650;
               opacity: .72;
          }

          .stat-icon {
               display: inline-flex;
               flex: 0 0 54px;
               width: 54px;
               height: 54px;
               align-items: center;
               justify-content: center;
               font-size: 1.4rem;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 17px;
               background: rgba(255, 255, 255, .72);
               box-shadow: 0 10px 22px rgba(15, 23, 42, .07);
          }

          /* FILTER CARD */
          .filter-card {
               padding: 20px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 22px;
               background: rgba(255, 255, 255, .93);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
               backdrop-filter: blur(12px);
          }

          .filter-heading {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 15px;
               color: var(--svc-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .filter-heading span {
               display: grid;
               width: 36px;
               height: 36px;
               place-items: center;
               color: var(--svc-primary-dark);
               border-radius: 11px;
               background: var(--svc-soft-indigo);
          }

          .filter-control {
               min-height: 47px;
               color: var(--svc-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #ffffff;
               box-shadow: none;
          }

          .filter-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(129, 140, 248, .17);
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

          .btn-filter {
               display: inline-flex;
               flex: 1;
               min-height: 47px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #ffffff;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               border: 0;
               border-radius: 13px;
               background: linear-gradient(135deg, var(--svc-primary), var(--svc-purple), var(--svc-cyan));
               box-shadow: 0 10px 21px rgba(79, 70, 229, .22);
               transition: .2s ease;
          }

          .btn-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 26px rgba(79, 70, 229, .30);
          }

          .btn-reset {
               display: inline-flex;
               min-height: 47px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #64748b;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background: #ffffff;
               transition: .2s ease;
          }

          .btn-reset:hover {
               color: #334155;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          /* TABLE CARD */
          .service-table-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
          }

          .service-table-header {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #ffffff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--svc-text);
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
               color: var(--svc-muted);
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
               padding: 13px 13px;
               color: #52627a;
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               vertical-align: middle;
               border: 0;
               background: linear-gradient(180deg, #f6f8ff, #eef2ff);
          }

          .service-table thead th:first-child {
               border-radius: 12px 0 0 12px;
          }

          .service-table thead th:last-child {
               border-radius: 0 12px 12px 0;
          }

          .service-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
               background: transparent;
          }

          .service-table tbody tr {
               transition: .18s ease;
          }

          .service-table tbody tr:hover td {
               background: #fafbff;
          }

          .service-table tbody tr:last-child td {
               border-bottom: 0;
          }

          /* TABLE CELL ELEMENTS */
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
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
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

          .category-pill {
               display: inline-flex;
               padding: 6px 10px;
               gap: 5px;
               align-items: center;
               color: #475569;
               font-size: .76rem;
               font-weight: 700;
               border: 1px solid #e2e8f0;
               border-radius: 9px;
               background: #f8fafc;
          }

          .description-text {
               display: block;
               max-width: 300px;
               color: #64748b;
               font-size: .8rem;
               line-height: 1.55;
          }

          .price-value {
               display: block;
               color: #0f172a;
               font-size: .9rem;
               font-weight: 850;
               white-space: nowrap;
          }

          .unit-pill {
               display: inline-flex;
               margin-top: 4px;
               padding: 4px 9px;
               color: #475569;
               font-size: .74rem;
               font-weight: 700;
               border: 1px solid #e2e8f0;
               border-radius: 8px;
               background: #f8fafc;
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

          /* ACTION BUTTONS */
          .action-group {
               display: inline-flex;
               gap: 6px;
               align-items: center;
          }

          .action-btn {
               display: inline-flex;
               width: 38px;
               height: 38px;
               padding: 0;
               align-items: center;
               justify-content: center;
               font-size: .88rem;
               text-decoration: none;
               border: 1.5px solid transparent;
               border-radius: 11px;
               cursor: pointer;
               transition: all .22s cubic-bezier(.4, 0, .2, 1);
          }

          .action-btn:hover {
               transform: translateY(-2px);
          }

          .btn-view {
               color: #0369a1;
               border-color: #bae6fd;
               background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
          }

          .btn-view:hover {
               color: #ffffff;
               border-color: #0ea5e9;
               background: linear-gradient(135deg, #0ea5e9, #0284c7);
               box-shadow: 0 8px 20px rgba(14, 165, 233, .30);
          }

          .btn-edit {
               color: #92400e;
               border-color: #fde68a;
               background: linear-gradient(135deg, #fffbeb, #fef3c7);
          }

          .btn-edit:hover {
               color: #ffffff;
               border-color: #f59e0b;
               background: linear-gradient(135deg, #f59e0b, #d97706);
               box-shadow: 0 8px 20px rgba(245, 158, 11, .30);
          }

          .btn-toggle-active {
               color: #047857;
               border-color: #a7f3d0;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .btn-toggle-active:hover {
               color: #ffffff;
               border-color: #10b981;
               background: linear-gradient(135deg, #10b981, #059669);
               box-shadow: 0 8px 20px rgba(16, 185, 129, .30);
          }

          .btn-toggle-inactive {
               color: #9f1239;
               border-color: #fecdd3;
               background: linear-gradient(135deg, #fff1f2, #ffe4e6);
          }

          .btn-toggle-inactive:hover {
               color: #ffffff;
               border-color: #f43f5e;
               background: linear-gradient(135deg, #f43f5e, #e11d48);
               box-shadow: 0 8px 20px rgba(244, 63, 94, .28);
          }

          .btn-delete {
               color: #9f1239;
               border-color: #fecdd3;
               background: linear-gradient(135deg, #fff1f2, #ffe4e6);
          }

          .btn-delete:hover {
               color: #ffffff;
               border-color: #f43f5e;
               background: linear-gradient(135deg, #f43f5e, #e11d48);
               box-shadow: 0 8px 20px rgba(244, 63, 94, .30);
          }

          /* EMPTY STATE */
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
               box-shadow: 0 14px 30px rgba(99, 102, 241, .14);
          }

          .empty-state h5 {
               margin-bottom: 7px;
               color: #334155;
               font-weight: 820;
          }

          .empty-state p {
               margin: 0 0 18px;
               color: #94a3b8;
               font-size: .87rem;
          }

          /* PAGINATION */
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

          .pagination-wrapper .pagination {
               margin-bottom: 0;
               gap: 5px;
          }

          .pagination-wrapper .page-link {
               min-width: 38px;
               color: var(--svc-primary-dark);
               text-align: center;
               border: 1px solid #e0e7ff;
               border-radius: 10px !important;
               background: #ffffff;
               box-shadow: none;
          }

          .pagination-wrapper .page-item.active .page-link {
               color: #ffffff;
               border-color: var(--svc-primary);
               background: linear-gradient(135deg, var(--svc-primary), #4338ca);
               box-shadow: 0 7px 15px rgba(79, 70, 229, .25);
          }

          @media (max-width: 991.98px) {
               .service-hero {
                    padding: 27px;
               }

               .hero-content,
               .service-table-header {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hero-actions {
                    width: 100%;
               }

               .hero-meta {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .btn-hero {
                    flex: 1;
               }

               .filter-actions {
                    width: 100%;
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

               .hero-title-wrap {
                    align-items: flex-start;
               }

               .hero-icon {
                    flex-basis: 52px;
                    width: 52px;
                    height: 52px;
                    font-size: 1.35rem;
                    border-radius: 16px;
               }

               .service-hero p {
                    font-size: .88rem;
               }

               .hero-meta {
                    grid-template-columns: 1fr;
                    max-width: 100%;
               }

               .hero-actions,
               .filter-actions {
                    flex-direction: column;
               }

               .btn-hero,
               .btn-filter,
               .btn-reset {
                    width: 100%;
               }

               .service-table-card {
                    border-radius: 20px;
               }

               .service-table-header {
                    padding: 19px;
               }

               .service-table-body {
                    padding: 8px 12px 18px;
               }

               .list-subtitle {
                    margin-left: 0;
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

               {{-- HERO --}}
               <div class="service-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-briefcase-fill"></i>
                              </div>

                              <div>
                                   <span class="hero-eyebrow">
                                        <i class="bi bi-activity"></i>
                                        Dashboard Monitoring
                                   </span>
                                   <h1>Produktivitas Karyawan dan Transaksi Jasa</h1>
                                   <p>
                                        Kelola katalog layanan perusahaan — kategori, kode, harga, durasi, dan status
                                        — dalam satu tampilan terpusat yang modern.
                                   </p>

                                   <div class="hero-meta">
                                        <div class="hero-meta-item">
                                             <span class="hero-meta-label">Total Service</span>
                                             <span class="hero-meta-value">{{ $services->total() }}</span>
                                        </div>
                                        <div class="hero-meta-item">
                                             <span class="hero-meta-label">Aktif</span>
                                             <span class="hero-meta-value">{{ $activeOnPage }}</span>
                                        </div>
                                        <div class="hero-meta-item">
                                             <span class="hero-meta-label">Tidak Aktif</span>
                                             <span class="hero-meta-value">{{ $inactiveOnPage }}</span>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="hero-actions">
                              <a href="{{ route('super-admin.services.create') }}" class="btn-hero btn-hero-solid">
                                   <i class="bi bi-plus-circle-fill"></i>
                                   Tambah Service
                              </a>
                         </div>
                    </div>
               </div>

               {{-- ALERTS --}}
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
                              <ul class="mb-0 mt-1">
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    </div>
               @endif

               {{-- STATISTICS --}}
               <div class="row g-3 stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-total h-100">
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
                         <div class="stat-card stat-active h-100">
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
                         <div class="stat-card stat-inactive h-100">
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
                         <div class="stat-card stat-page h-100">
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

               {{-- FILTER --}}
               <div class="filter-card">
                    <div class="filter-heading">
                         <span><i class="bi bi-funnel-fill"></i></span>
                         Pencarian dan Filter Service
                    </div>

                    <form method="GET" action="{{ route('super-admin.services.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-lg-4">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Service
                                   </label>
                                   <div class="search-shell">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="search" name="search"
                                             class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Kode, nama, unit, deskripsi..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="status" class="form-label fw-semibold small text-secondary">Status</label>
                                   <select id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua Status</option>
                                        @foreach ($statuses as $statusKey => $statusLabel)
                                             <option value="{{ $statusKey }}" @selected($currentStatus === $statusKey)>
                                                  {{ $statusLabel }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="category" class="form-label fw-semibold small text-secondary">Kategori</label>
                                   <select id="category" name="category" class="form-select filter-control">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                             <option value="{{ $category->id }}" @selected($currentCategory === (string) $category->id)>
                                                  {{ $category->name }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="sort"
                                        class="form-label fw-semibold small text-secondary">Urutkan</label>
                                   <select id="sort" name="sort" class="form-select filter-control">
                                        @foreach ($sortOptions as $sortValue => $sortLabel)
                                             <option value="{{ $sortValue }}" @selected($currentSort === $sortValue)>
                                                  {{ $sortLabel }}
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
                                        <a href="{{ route('super-admin.services.index') }}" class="btn-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               {{-- TABLE CARD --}}
               <div class="service-table-card">
                    <div class="service-table-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon"><i class="bi bi-list-ul"></i></span>
                                   Daftar Service
                              </h5>
                              <p class="list-subtitle">
                                   Kolom: service_code, name, description, base_price, duration, unit, dan status.
                              </p>
                         </div>

                         <span class="result-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ number_format($services->total()) }} data ditemukan
                         </span>
                    </div>

                    <div class="service-table-body">
                         <div class="table-responsive">
                              <table class="table service-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="5%">No</th>
                                             <th width="12%">Kode</th>
                                             <th width="18%">Service</th>
                                             <th width="20%">Kategori / Deskripsi</th>
                                             <th width="12%">Harga</th>
                                             <th width="9%">Durasi</th>
                                             <th width="9%">Status</th>
                                             <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($services as $service)
                                             @php
                                                  $normalizedStatus = in_array(
                                                      strtolower((string) $service->status),
                                                      ['active', 'inactive'],
                                                      true,
                                                  )
                                                      ? strtolower((string) $service->status)
                                                      : 'inactive';
                                             @endphp
                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $services->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="code-badge">{{ $service->service_code }}</span>
                                                  </td>

                                                  <td>
                                                       <span class="service-name">{{ $service->name }}</span>
                                                       <span class="service-id">ID #{{ $service->id }}</span>
                                                  </td>

                                                  <td>
                                                       <span class="category-pill">
                                                            <i class="bi bi-tag-fill"></i>
                                                            {{ $service->category?->name ?? 'Tanpa kategori' }}
                                                       </span>
                                                       <span class="description-text mt-2"
                                                            title="{{ $service->description }}">
                                                            {{ filled($service->description)
                                                                ? \Illuminate\Support\Str::limit($service->description, 90)
                                                                : 'Tidak ada deskripsi.' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="price-value">{{ $service->formatted_price }}</span>
                                                       <span class="unit-pill">/ {{ $service->unit }}</span>
                                                  </td>

                                                  <td>
                                                       <span class="duration-value">
                                                            <i class="bi bi-stopwatch me-1"></i>
                                                            {{ $service->estimated_duration_minutes ? $service->estimated_duration_minutes . ' mnt' : '-' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="custom-badge badge-{{ $normalizedStatus }}">
                                                            <i class="bi {{ $statusIcons[$normalizedStatus] }}"></i>
                                                            {{ $statusLabels[$normalizedStatus] }}
                                                       </span>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            <a href="{{ route('super-admin.services.show', $service) }}"
                                                                 class="action-btn btn-view" title="Detail"
                                                                 aria-label="Lihat detail {{ $service->name }}">
                                                                 <i class="bi bi-eye-fill"></i>
                                                            </a>

                                                            <a href="{{ route('super-admin.services.edit', $service) }}"
                                                                 class="action-btn btn-edit" title="Edit"
                                                                 aria-label="Edit {{ $service->name }}">
                                                                 <i class="bi bi-pencil-fill"></i>
                                                            </a>

                                                            <form action="{{ route('super-admin.services.toggle-status', $service) }}"
                                                                 method="POST" class="d-inline"
                                                                 onsubmit="return confirm('Yakin ubah status service \'{{ addslashes($service->name) }}\'?')">
                                                                 @csrf
                                                                 @method('PATCH')
                                                                 <button type="submit"
                                                                      class="action-btn {{ $normalizedStatus === 'active' ? 'btn-toggle-inactive' : 'btn-toggle-active' }}"
                                                                      title="{{ $normalizedStatus === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                                      <i
                                                                           class="bi {{ $normalizedStatus === 'active' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                                 </button>
                                                            </form>

                                                            <form action="{{ route('super-admin.services.destroy', $service) }}"
                                                                 method="POST" class="d-inline"
                                                                 onsubmit="return confirm('Yakin hapus service \'{{ addslashes($service->name) }}\'?')">
                                                                 @csrf
                                                                 @method('DELETE')
                                                                 <button type="submit" class="action-btn btn-delete"
                                                                      title="Hapus">
                                                                      <i class="bi bi-trash3-fill"></i>
                                                                 </button>
                                                            </form>
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="8" class="empty-state">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-briefcase-fill"></i>
                                                       </div>
                                                       <h5>Data Service Tidak Ditemukan</h5>
                                                       <p>
                                                            {{ $hasActiveFilter
                                                                ? 'Tidak ada service yang sesuai dengan pencarian atau filter.'
                                                                : 'Tambahkan service pertama untuk mulai mengelola layanan.' }}
                                                       </p>
                                                       @if ($hasActiveFilter)
                                                            <a href="{{ route('super-admin.services.index') }}"
                                                                 class="btn-reset">
                                                                 <i class="bi bi-arrow-counterclockwise"></i>
                                                                 Hapus Filter
                                                            </a>
                                                       @else
                                                            <a href="{{ route('super-admin.services.create') }}"
                                                                 class="btn-filter">
                                                                 <i class="bi bi-plus-circle-fill"></i>
                                                                 Tambah Service
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($services->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan {{ $services->firstItem() }}–{{ $services->lastItem() }}
                                        dari {{ $services->total() }} data
                                   </div>
                                   {{ $services->links() }}
                              </div>
                         @endif
                    </div>
               </div>

          </div>
     </div>
@endsection
