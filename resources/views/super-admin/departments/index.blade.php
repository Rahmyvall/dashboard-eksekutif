@extends('layouts.app')

@section('title', 'Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa')

@section('content')
     <style>
          :root {
               --dept-primary: #0f766e;
               --dept-primary-dark: #115e59;
               --dept-secondary: #0ea5e9;
               --dept-accent: #2563eb;
               --dept-success: #15803d;
               --dept-warning: #b45309;
               --dept-danger: #be123c;
               --dept-text: #0f172a;
               --dept-muted: #64748b;
               --dept-border: #dbe4e8;
               --dept-white: #ffffff;
               --dept-soft-blue: #eff6ff;
               --dept-soft-teal: #ecfeff;
               --dept-soft-green: #ecfdf5;
               --dept-soft-orange: #fffbeb;
          }

          .department-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               color: var(--dept-text);
               font-family: 'Plus Jakarta Sans', 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 7% 2%, rgba(45, 212, 191, .15), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(56, 189, 248, .18), transparent 25%),
                    radial-gradient(circle at 88% 94%, rgba(148, 163, 184, .16), transparent 22%),
                    linear-gradient(145deg, #f7fffd 0%, #f4fbfc 48%, #f2f8fb 100%);
          }

          .department-container {
               max-width: 1580px;
               margin: 0 auto;
          }

          /* HERO */
          .department-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #173c42;
               border: 1px solid #b9e8df;
               border-radius: 28px;
               background:
                    radial-gradient(circle at 90% 12%, rgba(255, 255, 255, .82), transparent 24%),
                    radial-gradient(circle at 72% 112%, rgba(45, 212, 191, .20), transparent 38%),
                    linear-gradient(135deg, #fbfffe 0%, #effcf9 38%, #e1f8f3 72%, #d7f4ef 100%);
               box-shadow: 0 18px 46px rgba(15, 118, 110, .12);
          }

          .department-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 1px solid rgba(15, 118, 110, .10);
               border-radius: 50%;
          }

          .department-hero::after {
               position: absolute;
               right: -34px;
               bottom: -78px;
               width: 180px;
               height: 180px;
               content: '';
               border-radius: 45px;
               background: rgba(20, 184, 166, .11);
               transform: rotate(28deg);
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
               color: #ffffff;
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(15, 118, 110, .12);
               border-radius: 20px;
               background: linear-gradient(135deg, #14b8a6, #0f8f83);
               box-shadow: 0 10px 24px rgba(15, 118, 110, .20);
          }

          .hero-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 7px;
               color: #0f766e;
               font-size: .72rem;
               font-weight: 800;
               letter-spacing: .13em;
               text-transform: uppercase;
          }

          .department-hero h1 {
               margin: 0;
               color: #12383e;
               font-size: clamp(1.7rem, 2.7vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
               line-height: 1.12;
          }

          .department-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: #4b6870;
               font-size: .97rem;
               line-height: 1.7;
          }

          .hero-meta {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 10px;
               margin-top: 16px;
               max-width: 760px;
          }

          .hero-meta-item {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
               padding: 10px 11px;
               border: 1px solid rgba(15, 118, 110, .18);
               border-radius: 12px;
               background: rgba(255, 255, 255, .72);
               backdrop-filter: blur(8px);
          }

          .hero-meta-label {
               color: #54737a;
               font-size: .68rem;
               font-weight: 750;
               letter-spacing: .04em;
               text-transform: uppercase;
          }

          .hero-meta-value {
               color: #0f766e;
               font-size: .95rem;
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
               color: #ffffff;
               font-size: .88rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid #0f8f83;
               border-radius: 14px;
               background: linear-gradient(135deg, #14b8a6, #0f8f83);
               box-shadow: 0 10px 22px rgba(15, 118, 110, .20);
               transition: .22s ease;
          }

          .btn-hero:hover {
               color: #ffffff;
               background: linear-gradient(135deg, #0f9488, #0f766e);
               transform: translateY(-2px);
               box-shadow: 0 14px 26px rgba(15, 118, 110, .24);
          }

          .btn-hero-soft {
               color: #0f766e;
               border-color: #8ddfd3;
               background: rgba(255, 255, 255, .84);
               backdrop-filter: blur(10px);
          }

          .btn-hero-soft:hover {
               color: #0f5f57;
               background: rgba(255, 255, 255, .98);
          }

          /* ALERT */
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

          .custom-alert i {
               font-size: 1.2rem;
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--dept-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--dept-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          /* STATISTICS */
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
               width: 128px;
               height: 128px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .5);
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
               position: relative;
               z-index: 2;
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
               letter-spacing: -.04em;
          }

          .stat-caption {
               margin-top: 8px;
               font-size: .8rem;
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
               font-size: 1.42rem;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 17px;
               background: rgba(255, 255, 255, .72);
               box-shadow: 0 10px 22px rgba(15, 23, 42, .07);
          }

          /* FILTER */
          .filter-card {
               padding: 20px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 22px;
               background: rgba(255, 255, 255, .93);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
               backdrop-filter: blur(12px);
          }

          .filter-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 15px;
               color: var(--dept-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .filter-title span {
               display: grid;
               width: 36px;
               height: 36px;
               place-items: center;
               color: var(--dept-primary-dark);
               border-radius: 11px;
               background: var(--dept-soft-teal);
          }

          .filter-control {
               min-height: 47px;
               color: var(--dept-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #ffffff;
               box-shadow: none;
          }

          .filter-control:focus {
               border-color: #2dd4bf;
               box-shadow: 0 0 0 .22rem rgba(45, 212, 191, .17);
          }

          .search-shell {
               position: relative;
          }

          .search-shell i {
               position: absolute;
               top: 50%;
               left: 15px;
               color: #0f766e;
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
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg, var(--dept-primary), #0f8f83, var(--dept-secondary));
               box-shadow: 0 10px 21px rgba(15, 118, 110, .22);
          }

          .btn-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(15, 118, 110, .28);
          }

          .btn-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #ffffff;
          }

          .btn-reset:hover {
               color: #334155;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          /* TABLE CARD */
          .department-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .department-card-header {
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
               color: var(--dept-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: var(--dept-primary-dark);
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--dept-muted);
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

          .department-card-body {
               padding: 10px 18px 20px;
          }

          .department-table {
               min-width: 900px;
               margin-bottom: 0;
          }

          .department-table thead th {
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

          .department-table thead th:first-child {
               border-radius: 12px 0 0 12px;
          }

          .department-table thead th:last-child {
               border-radius: 0 12px 12px 0;
          }

          .department-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
               background: transparent;
          }

          .department-table tbody tr {
               transition: .2s ease;
          }

          .department-table tbody tr:hover td {
               background: #fafbff;
          }

          .department-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .number-badge {
               display: inline-flex;
               width: 35px;
               height: 35px;
               align-items: center;
               justify-content: center;
               color: var(--dept-primary-dark);
               font-size: .77rem;
               font-weight: 820;
               border-radius: 11px;
               background: #eef2ff;
          }

          .code-label {
               display: inline-flex;
               padding: 7px 11px;
               gap: 6px;
               align-items: center;
               color: #6d28d9;
               font-size: .76rem;
               font-weight: 830;
               letter-spacing: .04em;
               border: 1px solid #ddd6fe;
               border-radius: 10px;
               background: linear-gradient(135deg, #f5f3ff, #ede9fe);
          }

          .department-info {
               display: flex;
               gap: 12px;
               align-items: center;
          }

          .department-avatar {
               display: grid;
               flex: 0 0 43px;
               width: 43px;
               height: 43px;
               place-items: center;
               color: #ffffff;
               font-size: 1rem;
               font-weight: 850;
               border-radius: 14px;
               background: linear-gradient(135deg, #818cf8, #8b5cf6, #22d3ee);
               box-shadow: 0 9px 18px rgba(99, 102, 241, .18);
          }

          .department-name {
               display: block;
               margin-bottom: 4px;
               color: #1e293b;
               font-size: .94rem;
               font-weight: 820;
          }

          .department-description {
               display: block;
               max-width: 410px;
               color: #7c8aa0;
               font-size: .78rem;
               line-height: 1.5;
          }

          .custom-badge {
               display: inline-flex;
               padding: 7px 11px;
               gap: 6px;
               align-items: center;
               font-size: .74rem;
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

          .date-value {
               display: block;
               color: #475569;
               font-size: .8rem;
               font-weight: 730;
          }

          .date-caption {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: .7rem;
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
               font-size: .92rem;
               border: 0;
               border-radius: 12px;
               transition: .21s ease;
          }

          .action-btn:hover {
               transform: translateY(-2px) scale(1.03);
          }

          .btn-view {
               color: #0369a1;
               background: #e0f2fe;
          }

          .btn-view:hover {
               color: #ffffff;
               background: #0ea5e9;
               box-shadow: 0 8px 18px rgba(14, 165, 233, .25);
          }

          .btn-edit {
               color: #a16207;
               background: #fef3c7;
          }

          .btn-edit:hover {
               color: #ffffff;
               background: #f59e0b;
               box-shadow: 0 8px 18px rgba(245, 158, 11, .25);
          }

          .btn-delete {
               color: #be123c;
               background: #ffe4e6;
          }

          .btn-delete:hover {
               color: #ffffff;
               background: #f43f5e;
               box-shadow: 0 8px 18px rgba(244, 63, 94, .25);
          }

          /* EMPTY */
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
               color: var(--dept-primary-dark);
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

          .empty-add-button {
               display: inline-flex;
               min-height: 44px;
               padding: 0 17px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #ffffff;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               background: linear-gradient(135deg, var(--dept-primary), var(--dept-purple), var(--dept-secondary));
               box-shadow: 0 10px 20px rgba(99, 102, 241, .2);
          }

          .empty-add-button:hover {
               color: #ffffff;
               transform: translateY(-2px);
          }

          /* PAGINATION */
          .pagination-wrapper {
               display: flex;
               padding: 18px 6px 2px;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
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
               color: var(--dept-primary-dark);
               text-align: center;
               border: 1px solid #e0e7ff;
               border-radius: 10px !important;
               background: #ffffff;
               box-shadow: none;
          }

          .pagination-wrapper .page-item.active .page-link {
               color: #ffffff;
               border-color: var(--dept-primary);
               background: linear-gradient(135deg, var(--dept-primary), #0f8f83);
               box-shadow: 0 7px 15px rgba(15, 118, 110, .25);
          }

          @media (max-width: 991.98px) {
               .department-page {
                    padding: 20px 12px 34px;
               }

               .department-hero {
                    padding: 27px;
               }

               .hero-content,
               .department-card-header {
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

               .btn-filter,
               .btn-reset {
                    flex: 1;
               }
          }

          @media (max-width: 767.98px) {
               .department-hero {
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

               .department-hero p {
                    font-size: .87rem;
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

               .department-card {
                    border-radius: 20px;
               }

               .department-card-header {
                    padding: 19px;
               }

               .department-card-body {
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
          $activeOnPage = $departments->getCollection()->where('status', 'active')->count();
          $inactiveOnPage = $departments->getCollection()->where('status', 'inactive')->count();
          $currentSearch = request('search', $search ?? '');
          $currentStatus = request('status', $status ?? '');

          /*
           * Hak kelola Department hanya untuk Super Admin.
           * Role lain tetap dapat melihat daftar dan halaman detail.
           */
          $currentUser = auth()->user();
          $canManageDepartments = false;

          if ($currentUser) {
              if (method_exists($currentUser, 'hasRole')) {
                  $canManageDepartments = $currentUser->hasRole('super_admin');
              } else {
                  $rawRole =
                      data_get($currentUser, 'role.slug') ??
                      (data_get($currentUser, 'role.name') ??
                          (data_get($currentUser, 'role_name') ?? data_get($currentUser, 'role')));

                  if (is_object($rawRole) || is_array($rawRole)) {
                      $rawRole = data_get($rawRole, 'slug') ?? (data_get($rawRole, 'name') ?? '');
                  }

                  $normalizedRole = \Illuminate\Support\Str::of((string) $rawRole)
                      ->trim()
                      ->lower()
                      ->replace(['-', ' '], '_')
                      ->replaceMatches('/_+/', '_')
                      ->toString();

                  $canManageDepartments = in_array($normalizedRole, ['super_admin', 'superadmin'], true);
              }
          }
     @endphp

     <div class="department-page">
          <div class="department-container">

               {{-- HERO --}}
               <div class="department-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-buildings-fill"></i>
                              </div>

                              <div>
                                   <span class="hero-eyebrow">
                                        <i class="bi bi-activity"></i>
                                        Dashboard Monitoring
                                   </span>
                                   <h1>Produktivitas Karyawan dan Transaksi Jasa</h1>
                                   <p>
                                        Monitor performa tiap departemen, jaga kualitas layanan, dan percepat
                                        pengambilan keputusan operasional lewat tampilan ringkas yang modern.
                                   </p>

                                   <div class="hero-meta">
                                        <div class="hero-meta-item">
                                             <span class="hero-meta-label">Total Department</span>
                                             <span class="hero-meta-value">{{ $departments->total() }}</span>
                                        </div>
                                        <div class="hero-meta-item">
                                             <span class="hero-meta-label">Aktif di Halaman</span>
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
                              @if ($canManageDepartments)
                                   @if (Route::has('super-admin.departments.trash'))
                                        <a href="{{ route('super-admin.departments.trash') }}"
                                             class="btn-hero btn-hero-soft">
                                             <i class="bi bi-trash3-fill"></i>
                                             Data Terhapus
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.departments.create'))
                                        <a href="{{ route('super-admin.departments.create') }}" class="btn-hero">
                                             <i class="bi bi-plus-circle-fill"></i>
                                             Tambah Department
                                        </a>
                                   @endif
                              @else
                                   <span class="btn-hero btn-hero-soft" title="Akses hanya untuk melihat data">
                                        <i class="bi bi-eye-fill"></i>
                                        Mode Lihat
                                   </span>
                              @endif
                         </div>
                    </div>
               </div>

               {{-- ALERT SUCCESS --}}
               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               {{-- ALERT ERROR --}}
               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               {{-- STATISTICS --}}
               <div class="row g-3 stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-total h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Hasil</div>
                                        <div class="stat-value">{{ $departments->total() }}</div>
                                        <div class="stat-caption">Data sesuai filter</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-building-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-active h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Aktif</div>
                                        <div class="stat-value">{{ $activeOnPage }}</div>
                                        <div class="stat-caption">Pada halaman ini</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-check2-circle"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-inactive h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Tidak Aktif</div>
                                        <div class="stat-value">{{ $inactiveOnPage }}</div>
                                        <div class="stat-caption">Pada halaman ini</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-pause-circle-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-page h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Halaman</div>
                                        <div class="stat-value">{{ $departments->currentPage() }}</div>
                                        <div class="stat-caption">Dari {{ $departments->lastPage() }} halaman</div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               {{-- FILTER --}}
               <div class="filter-card">
                    <div class="filter-title">
                         <span><i class="bi bi-funnel-fill"></i></span>
                         Pencarian dan Filter
                    </div>

                    <form method="GET" action="{{ route('super-admin.departments.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-lg-6">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Department
                                   </label>

                                   <div class="search-shell">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="search" name="search"
                                             class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Cari berdasarkan kode, nama, atau deskripsi..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-5 col-lg-3">
                                   <label for="status" class="form-label fw-semibold small text-secondary">
                                        Status
                                   </label>

                                   <select id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua Status</option>
                                        <option value="active" @selected($currentStatus === 'active')>Aktif</option>
                                        <option value="inactive" @selected($currentStatus === 'inactive')>Tidak Aktif</option>
                                   </select>
                              </div>

                              <div class="col-12 col-md-7 col-lg-3">
                                   <div class="filter-actions">
                                        <button type="submit" class="btn-filter">
                                             <i class="bi bi-search"></i>
                                             Terapkan
                                        </button>

                                        <a href="{{ route('super-admin.departments.index') }}" class="btn-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               {{-- TABLE CARD --}}
               <div class="department-card">
                    <div class="department-card-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon">
                                        <i class="bi bi-list-ul"></i>
                                   </span>
                                   Daftar Department
                              </h5>

                              <p class="list-subtitle">
                                   Menampilkan kode, nama, deskripsi, status, dan waktu pembaruan department.
                              </p>
                         </div>

                         <span class="result-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ $departments->total() }} data ditemukan
                         </span>
                    </div>

                    <div class="department-card-body">
                         <div class="table-responsive">
                              <table class="table department-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="6%">No</th>
                                             <th width="13%">Kode</th>
                                             <th>Department</th>
                                             <th width="14%">Status</th>
                                             <th width="17%">Diperbarui</th>
                                             <th width="16%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($departments as $department)
                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $departments->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="code-label">
                                                            <i class="bi bi-hash"></i>
                                                            {{ $department->code }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="department-info">
                                                            <div class="department-avatar">
                                                                 {{ strtoupper(substr($department->name, 0, 1)) }}
                                                            </div>

                                                            <div>
                                                                 <span class="department-name">
                                                                      {{ $department->name }}
                                                                 </span>

                                                                 <span class="department-description">
                                                                      {{ $department->description
                                                                          ? \Illuminate\Support\Str::limit($department->description, 100)
                                                                          : 'Belum ada deskripsi untuk department ini.' }}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       @if ($department->status === 'active')
                                                            <span class="custom-badge badge-active">
                                                                 <i class="bi bi-check-circle-fill"></i>
                                                                 Aktif
                                                            </span>
                                                       @else
                                                            <span class="custom-badge badge-inactive">
                                                                 <i class="bi bi-x-circle-fill"></i>
                                                                 Tidak Aktif
                                                            </span>
                                                       @endif
                                                  </td>

                                                  <td>
                                                       <span class="date-value">
                                                            {{ optional($department->updated_at)->format('d M Y') ?? '-' }}
                                                       </span>
                                                       <span class="date-caption">
                                                            {{ optional($department->updated_at)->format('H:i') ?? '-' }}
                                                            WIB
                                                       </span>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            @if (Route::has('super-admin.departments.show'))
                                                                 <a href="{{ route('super-admin.departments.show', $department) }}"
                                                                      class="btn action-btn btn-view" title="Lihat detail"
                                                                      aria-label="Lihat detail {{ $department->name }}">
                                                                      <i class="bi bi-eye-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($canManageDepartments)
                                                                 @if (Route::has('super-admin.departments.edit'))
                                                                      <a href="{{ route('super-admin.departments.edit', $department) }}"
                                                                           class="btn action-btn btn-edit"
                                                                           title="Edit department"
                                                                           aria-label="Edit {{ $department->name }}">
                                                                           <i class="bi bi-pencil-fill"></i>
                                                                      </a>
                                                                 @endif

                                                                 @if (Route::has('super-admin.departments.destroy'))
                                                                      <form action="{{ route('super-admin.departments.destroy', $department) }}"
                                                                           method="POST" class="d-inline"
                                                                           onsubmit="return confirm('Yakin ingin menghapus department {{ addslashes($department->name) }}?')">
                                                                           @csrf
                                                                           @method('DELETE')

                                                                           <button type="submit"
                                                                                class="btn action-btn btn-delete"
                                                                                title="Hapus department"
                                                                                aria-label="Hapus {{ $department->name }}">
                                                                                <i class="bi bi-trash3-fill"></i>
                                                                           </button>
                                                                      </form>
                                                                 @endif
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="6" class="empty-state">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-folder2-open"></i>
                                                       </div>

                                                       <h5>Data Department Tidak Ditemukan</h5>

                                                       <p>
                                                            @if ($currentSearch !== '' || $currentStatus !== '')
                                                                 Tidak ada data yang sesuai dengan pencarian atau filter yang
                                                                 digunakan.
                                                            @else
                                                                 Tambahkan department baru untuk mulai mengelola data
                                                                 organisasi.
                                                            @endif
                                                       </p>

                                                       @if ($currentSearch !== '' || $currentStatus !== '')
                                                            <a href="{{ route('super-admin.departments.index') }}"
                                                                 class="empty-add-button">
                                                                 <i class="bi bi-arrow-counterclockwise"></i>
                                                                 Hapus Filter
                                                            </a>
                                                       @elseif ($canManageDepartments && Route::has('super-admin.departments.create'))
                                                            <a href="{{ route('super-admin.departments.create') }}"
                                                                 class="empty-add-button">
                                                                 <i class="bi bi-plus-circle-fill"></i>
                                                                 Tambah Department
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($departments->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan {{ $departments->firstItem() }}–{{ $departments->lastItem() }}
                                        dari {{ $departments->total() }} data
                                   </div>

                                   {{ $departments->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
