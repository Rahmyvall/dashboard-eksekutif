@extends('layouts.app')

@section('title', 'Dashboard Monitoring Produktivitas Karyawan')

@section('content')
     <style>
          :root {
               --employee-primary: #6366f1;
               --employee-primary-dark: #4f46e5;
               --employee-secondary: #06b6d4;
               --employee-purple: #8b5cf6;
               --employee-pink: #ec4899;
               --employee-success: #10b981;
               --employee-warning: #f59e0b;
               --employee-danger: #ef4444;
               --employee-text: #24324a;
               --employee-muted: #718096;
               --employee-border: #e7eaf3;
               --employee-white: #ffffff;
               --employee-soft-blue: #eef7ff;
               --employee-soft-purple: #f3f0ff;
               --employee-soft-green: #ecfdf5;
               --employee-soft-orange: #fff7e8;
          }

          .employee-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    radial-gradient(circle at 88% 94%, rgba(244, 114, 182, .14), transparent 22%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .employee-container {
               max-width: 1680px;
               margin: 0 auto;
          }

          /* HERO */
          .employee-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, .7);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 42%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .employee-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .employee-hero::after {
               position: absolute;
               right: -34px;
               bottom: -78px;
               width: 180px;
               height: 180px;
               content: '';
               border-radius: 45px;
               background: rgba(255, 255, 255, .12);
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
               color: var(--employee-primary-dark);
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .employee-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.5vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .employee-hero p {
               max-width: 780px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .97rem;
               line-height: 1.7;
          }

          .hero-actions {
               display: flex;
               gap: 10px;
               flex-wrap: wrap;
               align-items: center;
               justify-content: flex-end;
          }

          .btn-hero {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .88rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 14px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
               transition: .22s ease;
          }

          .btn-hero:hover {
               color: #312e81;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(76, 29, 149, .22);
          }

          .btn-hero-soft {
               color: #ffffff;
               border-color: rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .16);
               backdrop-filter: blur(10px);
          }

          .btn-hero-soft:hover {
               color: #ffffff;
               background: rgba(255, 255, 255, .24);
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

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--employee-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--employee-danger);
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

          .monitoring-row {
               margin-bottom: 22px;
          }

          .monitoring-card,
          .crud-card {
               height: 100%;
               padding: 20px;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 22px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 16px 35px rgba(51, 65, 85, .08);
               backdrop-filter: blur(10px);
          }

          .monitoring-title,
          .crud-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 16px;
               color: #1e293b;
               font-size: .92rem;
               font-weight: 830;
               letter-spacing: .02em;
               text-transform: uppercase;
          }

          .monitoring-title i,
          .crud-title i {
               display: grid;
               width: 34px;
               height: 34px;
               place-items: center;
               color: #4338ca;
               border-radius: 11px;
               background: #eef2ff;
          }

          .monitor-mini {
               padding: 14px;
               border: 1px solid #e5eaf4;
               border-radius: 15px;
               background: linear-gradient(160deg, #ffffff 0%, #f8fbff 100%);
          }

          .monitor-mini-label {
               display: block;
               margin-bottom: 6px;
               color: #64748b;
               font-size: .72rem;
               font-weight: 780;
               letter-spacing: .05em;
               text-transform: uppercase;
          }

          .monitor-mini-value {
               display: block;
               color: #0f172a;
               font-size: 1.45rem;
               font-weight: 860;
               letter-spacing: -.02em;
               line-height: 1;
          }

          .monitor-mini-caption {
               margin-top: 7px;
               color: #64748b;
               font-size: .76rem;
               font-weight: 650;
          }

          .crud-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 10px;
          }

          .crud-btn {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 12px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #334155;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid #d9e2ef;
               border-radius: 12px;
               background: #ffffff;
               transition: .2s ease;
          }

          .crud-btn i {
               font-size: .95rem;
          }

          .crud-btn:hover {
               color: #0f172a;
               transform: translateY(-2px);
               box-shadow: 0 10px 20px rgba(15, 23, 42, .08);
          }

          .crud-btn-primary {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #22d3ee);
          }

          .crud-btn-primary:hover {
               color: #ffffff;
               box-shadow: 0 12px 24px rgba(99, 102, 241, .3);
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
               color: var(--employee-text);
               font-size: .93rem;
               font-weight: 820;
          }

          .filter-title span {
               display: grid;
               width: 36px;
               height: 36px;
               place-items: center;
               color: var(--employee-primary-dark);
               border-radius: 11px;
               background: var(--employee-soft-purple);
          }

          .filter-control {
               min-height: 47px;
               color: var(--employee-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #ffffff;
               box-shadow: none;
          }

          .filter-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .11);
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
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg,
                         var(--employee-primary),
                         var(--employee-purple),
                         var(--employee-secondary));
               box-shadow: 0 10px 21px rgba(99, 102, 241, .22);
          }

          .btn-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(99, 102, 241, .28);
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

          /* TABLE */
          .employee-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .employee-card-header {
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
               color: var(--employee-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               color: var(--employee-primary-dark);
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--employee-muted);
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

          .employee-card-body {
               padding: 10px 18px 20px;
          }

          .employee-table {
               min-width: 1450px;
               margin-bottom: 0;
          }

          .employee-table thead th {
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

          .employee-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
               background: transparent;
          }

          .employee-table tbody tr {
               transition: .2s ease;
          }

          .employee-table tbody tr:hover td {
               background: #fafbff;
          }

          .number-badge {
               display: inline-flex;
               width: 35px;
               height: 35px;
               align-items: center;
               justify-content: center;
               color: var(--employee-primary-dark);
               font-size: .77rem;
               font-weight: 820;
               border-radius: 11px;
               background: #eef2ff;
          }

          .employee-info {
               display: flex;
               gap: 12px;
               align-items: center;
               min-width: 245px;
          }

          .employee-avatar {
               display: grid;
               flex: 0 0 48px;
               width: 48px;
               height: 48px;
               overflow: hidden;
               place-items: center;
               color: #ffffff;
               font-size: 1rem;
               font-weight: 850;
               border-radius: 14px;
               background: linear-gradient(135deg, #818cf8, #8b5cf6, #22d3ee);
               box-shadow: 0 9px 18px rgba(99, 102, 241, .18);
          }

          .employee-avatar img {
               width: 100%;
               height: 100%;
               object-fit: cover;
          }

          .employee-name {
               display: block;
               margin-bottom: 4px;
               color: #1e293b;
               font-size: .94rem;
               font-weight: 820;
          }

          .employee-number {
               display: inline-flex;
               padding: 4px 8px;
               gap: 5px;
               align-items: center;
               color: #6d28d9;
               font-size: .7rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 8px;
               background: #f5f3ff;
          }

          .info-main {
               display: block;
               color: #334155;
               font-size: .82rem;
               font-weight: 760;
          }

          .info-sub {
               display: block;
               max-width: 220px;
               margin-top: 4px;
               overflow: hidden;
               color: #94a3b8;
               font-size: .72rem;
               text-overflow: ellipsis;
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

          .badge-employment {
               color: #1d4ed8;
               border-color: #bfdbfe;
               background: #eff6ff;
          }

          .salary-value {
               display: block;
               color: #475569;
               font-size: .8rem;
               font-weight: 760;
               white-space: nowrap;
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
               color: var(--employee-primary-dark);
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
               background: linear-gradient(135deg,
                         var(--employee-primary),
                         var(--employee-purple),
                         var(--employee-secondary));
               box-shadow: 0 10px 20px rgba(99, 102, 241, .2);
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
               color: var(--employee-primary-dark);
               text-align: center;
               border: 1px solid #e0e7ff;
               border-radius: 10px !important;
               background: #ffffff;
               box-shadow: none;
          }

          .pagination-wrapper .page-item.active .page-link {
               color: #ffffff;
               border-color: var(--employee-primary);
               background: linear-gradient(135deg, var(--employee-primary), var(--employee-purple));
               box-shadow: 0 7px 15px rgba(99, 102, 241, .25);
          }

          @media (max-width: 991.98px) {
               .employee-page {
                    padding: 20px 12px 34px;
               }

               .employee-hero {
                    padding: 27px;
               }

               .hero-content,
               .employee-card-header {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hero-actions,
               .filter-actions {
                    width: 100%;
               }

               .crud-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
               }

               .btn-hero,
               .btn-filter,
               .btn-reset {
                    flex: 1;
               }
          }

          @media (max-width: 767.98px) {
               .employee-hero {
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

               .employee-hero p {
                    font-size: .87rem;
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

               .crud-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .employee-card {
                    border-radius: 20px;
               }

               .employee-card-header {
                    padding: 19px;
               }

               .employee-card-body {
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
          $employeeCollection = $employees->getCollection();

          $activeOnPage = $employeeCollection->where('status', 'active')->count();

          $inactiveOnPage = $employeeCollection->where('status', 'inactive')->count();

          $currentSearch = request('search', $search ?? '');
          $currentDepartmentId = (string) request('department_id', $departmentId ?? '');
          $currentPositionId = (string) request('position_id', $positionId ?? '');
          $currentGender = request('gender', $gender ?? '');
          $currentEmploymentStatus = request('employment_status', $employmentStatus ?? '');
          $currentStatus = request('status', $status ?? '');

          $hasActiveFilter =
              $currentSearch !== '' ||
              $currentDepartmentId !== '' ||
              $currentPositionId !== '' ||
              $currentGender !== '' ||
              $currentEmploymentStatus !== '' ||
              $currentStatus !== '';

          $currentUser = auth()->user();
          $canManageEmployees = false;
          $canOpenEmployeeTrash = false;

          if ($currentUser) {
              if (method_exists($currentUser, 'hasAnyRole')) {
                  $canManageEmployees = $currentUser->hasAnyRole(['super_admin', 'hrd_manager']);

                  $canOpenEmployeeTrash = $currentUser->hasRole('super_admin');
              } elseif (method_exists($currentUser, 'hasRole')) {
                  $canManageEmployees = $currentUser->hasRole('super_admin') || $currentUser->hasRole('hrd_manager');

                  $canOpenEmployeeTrash = $currentUser->hasRole('super_admin');
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

                  $canManageEmployees = in_array(
                      $normalizedRole,
                      ['super_admin', 'superadmin', 'hrd_manager', 'hrd'],
                      true,
                  );

                  $canOpenEmployeeTrash = in_array($normalizedRole, ['super_admin', 'superadmin'], true);
              }
          }

          $employmentLabels = [
              'permanent' => 'Tetap',
              'contract' => 'Kontrak',
              'probation' => 'Probation',
              'internship' => 'Magang',
              'outsourcing' => 'Outsourcing',
          ];

          $genderLabels = [
              'male' => 'Laki-laki',
              'female' => 'Perempuan',
              'l' => 'Laki-laki',
              'p' => 'Perempuan',
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

          $isSuperAdmin = $canOpenEmployeeTrash;
     @endphp

     <div class="employee-page">
          <div class="employee-container">
               {{-- HERO --}}
               <div class="employee-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-speedometer2"></i>
                              </div>

                              <div>
                                   <h1>Dashboard Monitoring Produktivitas Karyawan</h1>
                                   <p>
                                        Pantau produktivitas tim dan alur transaksi jasa
                                        dari aktivitas harian, service order, invoice,
                                        hingga status pembayaran dalam satu layar modern.
                                   </p>
                              </div>
                         </div>

                         <div class="hero-actions">
                              @if (Route::has('super-admin.employee-activities.index'))
                                   <a href="{{ route('super-admin.employee-activities.index') }}"
                                        class="btn-hero btn-hero-soft">
                                        <i class="bi bi-activity"></i>
                                        Aktivitas
                                   </a>
                              @endif

                              @if (Route::has('super-admin.service-orders.index'))
                                   <a href="{{ route('super-admin.service-orders.index') }}" class="btn-hero btn-hero-soft">
                                        <i class="bi bi-receipt"></i>
                                        Service Order
                                   </a>
                              @endif

                              @if ($canOpenEmployeeTrash && Route::has('super-admin.employees.trash'))
                                   <a href="{{ route('super-admin.employees.trash') }}" class="btn-hero btn-hero-soft">
                                        <i class="bi bi-trash3-fill"></i>
                                        Data Terhapus
                                   </a>
                              @endif

                              @if ($canManageEmployees && Route::has('super-admin.employees.create'))
                                   <a href="{{ route('super-admin.employees.create') }}" class="btn-hero">
                                        <i class="bi bi-person-plus-fill"></i>
                                        Tambah Employee
                                   </a>
                              @else
                                   <span class="btn-hero btn-hero-soft" title="Akses hanya untuk melihat data">
                                        <i class="bi bi-eye-fill"></i>
                                        Mode Lihat
                                   </span>
                              @endif
                         </div>
                    </div>
               </div>

               {{-- ALERT --}}
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

               {{-- STATISTICS --}}
               <div class="row g-3 stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <div class="stat-card stat-total h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Karyawan</div>
                                        <div class="stat-value">
                                             {{ number_format((int) $monitoringStats['employees_total']) }}
                                        </div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $employees->total()) }} data sesuai filter
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
                                             {{ number_format((int) $monitoringStats['activities_today']) }}
                                        </div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $monitoringStats['activities_pending_verify']) }}
                                             menunggu verifikasi
                                        </div>
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
                                             {{ number_format((int) $monitoringStats['service_orders_this_month']) }}
                                        </div>
                                        <div class="stat-caption">
                                             {{ number_format((int) $monitoringStats['service_orders_processing']) }} order
                                             sedang diproses
                                        </div>
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
                                        <div class="stat-title">Transaksi Terkonfirmasi</div>
                                        <div class="stat-value">
                                             Rp
                                             {{ number_format((float) $monitoringStats['payments_confirmed_this_month'], 0, ',', '.') }}
                                        </div>
                                        <div class="stat-caption">
                                             Invoice belum lunas:
                                             {{ number_format((int) $monitoringStats['invoices_unpaid']) }}
                                        </div>
                                   </div>

                                   <div class="stat-icon">
                                        <i class="bi bi-cash-stack"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="row g-3 monitoring-row">
                    <div class="col-12 col-xxl-8">
                         <div class="monitoring-card">
                              <div class="monitoring-title">
                                   <i class="bi bi-graph-up-arrow"></i>
                                   Monitoring Produktivitas dan Transaksi Jasa
                              </div>

                              <div class="row g-3">
                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Karyawan Aktif</span>
                                             <span class="monitor-mini-value">
                                                  {{ number_format((int) $monitoringStats['employees_active']) }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  dari {{ number_format((int) $monitoringStats['employees_total']) }} total
                                                  karyawan
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Aktivitas Terverifikasi</span>
                                             <span class="monitor-mini-value">
                                                  {{ number_format(max((int) $monitoringStats['activities_today'] - (int) $monitoringStats['activities_pending_verify'], 0)) }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  {{ number_format((int) $monitoringStats['activities_pending_verify']) }}
                                                  aktivitas pending
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Pendapatan Jasa/Bulan</span>
                                             <span class="monitor-mini-value">
                                                  Rp
                                                  {{ number_format((float) $monitoringStats['service_revenue_this_month'], 0, ',', '.') }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  dari data invoice bulan berjalan
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitor-mini">
                                             <span class="monitor-mini-label">Payment Pending</span>
                                             <span class="monitor-mini-value">
                                                  {{ number_format((int) $monitoringStats['payments_pending']) }}
                                             </span>
                                             <div class="monitor-mini-caption">
                                                  perlu tindak lanjut konfirmasi
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-xxl-4">
                         <div class="crud-card">
                              <div class="crud-title">
                                   <i class="bi bi-grid-3x3-gap-fill"></i>
                                   Tombol Aksi CRUD
                              </div>

                              <div class="crud-grid">
                                   @if ($canManageEmployees && Route::has('super-admin.employees.create'))
                                        <a href="{{ route('super-admin.employees.create') }}"
                                             class="crud-btn crud-btn-primary">
                                             <i class="bi bi-person-plus-fill"></i>
                                             Tambah Employee
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.employees.index'))
                                        <a href="{{ route('super-admin.employees.index') }}" class="crud-btn">
                                             <i class="bi bi-people-fill"></i>
                                             Daftar Employee
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.employee-activities.index'))
                                        <a href="{{ route('super-admin.employee-activities.index') }}" class="crud-btn">
                                             <i class="bi bi-activity"></i>
                                             Aktivitas
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.employee-activities.create'))
                                        <a href="{{ route('super-admin.employee-activities.create') }}" class="crud-btn">
                                             <i class="bi bi-plus-square-fill"></i>
                                             Input Aktivitas
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.service-orders.index'))
                                        <a href="{{ route('super-admin.service-orders.index') }}" class="crud-btn">
                                             <i class="bi bi-receipt"></i>
                                             Service Order
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin && Route::has('super-admin.service-orders.create'))
                                        <a href="{{ route('super-admin.service-orders.create') }}" class="crud-btn">
                                             <i class="bi bi-file-earmark-plus-fill"></i>
                                             Buat Order
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.invoices.index'))
                                        <a href="{{ route('super-admin.invoices.index') }}" class="crud-btn">
                                             <i class="bi bi-receipt-cutoff"></i>
                                             Daftar Invoice
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin && Route::has('super-admin.invoices.create'))
                                        <a href="{{ route('super-admin.invoices.create') }}" class="crud-btn">
                                             <i class="bi bi-file-earmark-plus"></i>
                                             Buat Invoice
                                        </a>
                                   @endif

                                   @if (Route::has('super-admin.payments.index'))
                                        <a href="{{ route('super-admin.payments.index') }}" class="crud-btn">
                                             <i class="bi bi-cash-coin"></i>
                                             Daftar Payment
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin && Route::has('super-admin.payments.create'))
                                        <a href="{{ route('super-admin.payments.create') }}" class="crud-btn">
                                             <i class="bi bi-wallet2"></i>
                                             Input Payment
                                        </a>
                                   @endif
                              </div>
                         </div>
                    </div>
               </div>

               {{-- FILTER --}}
               <div class="filter-card">
                    <div class="filter-title">
                         <span>
                              <i class="bi bi-funnel-fill"></i>
                         </span>
                         Pencarian dan Filter Employee
                    </div>

                    <form method="GET" action="{{ route('super-admin.employees.index') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-12 col-lg-4">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Employee
                                   </label>

                                   <div class="search-shell">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="search" name="search"
                                             class="form-control filter-control" value="{{ $currentSearch }}"
                                             placeholder="Nomor, nama, email, telepon, departemen..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="department_id" class="form-label fw-semibold small text-secondary">
                                        Departemen
                                   </label>

                                   <select id="department_id" name="department_id" class="form-select filter-control">
                                        <option value="">Semua Departemen</option>

                                        @foreach ($departments as $department)
                                             <option value="{{ $department->id }}" @selected($currentDepartmentId === (string) $department->id)>
                                                  {{ $department->code }}
                                                  — {{ $department->name }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="position_id" class="form-label fw-semibold small text-secondary">
                                        Jabatan
                                   </label>

                                   <select id="position_id" name="position_id" class="form-select filter-control">
                                        <option value="">Semua Jabatan</option>

                                        @foreach ($positions as $position)
                                             <option value="{{ $position->id }}"
                                                  data-department-id="{{ $position->department_id }}"
                                                  @selected($currentPositionId === (string) $position->id)>
                                                  {{ $position->name }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="gender" class="form-label fw-semibold small text-secondary">
                                        Jenis Kelamin
                                   </label>

                                   <select id="gender" name="gender" class="form-select filter-control">
                                        <option value="">Semua Gender</option>
                                        <option value="male" @selected($currentGender === 'male')>
                                             Laki-laki
                                        </option>
                                        <option value="female" @selected($currentGender === 'female')>
                                             Perempuan
                                        </option>
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <label for="employment_status" class="form-label fw-semibold small text-secondary">
                                        Kepegawaian
                                   </label>

                                   <select id="employment_status" name="employment_status"
                                        class="form-select filter-control">
                                        <option value="">Semua Status</option>

                                        @foreach ($employmentLabels as $value => $label)
                                             <option value="{{ $value }}" @selected($currentEmploymentStatus === $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-5 col-lg-2">
                                   <label for="status" class="form-label fw-semibold small text-secondary">
                                        Status Data
                                   </label>

                                   <select id="status" name="status" class="form-select filter-control">
                                        <option value="">Semua Status</option>
                                        <option value="active" @selected($currentStatus === 'active')>
                                             Aktif
                                        </option>
                                        <option value="inactive" @selected($currentStatus === 'inactive')>
                                             Tidak Aktif
                                        </option>
                                   </select>
                              </div>

                              <div class="col-12 col-md-7 col-lg-4">
                                   <div class="filter-actions">
                                        <button type="submit" class="btn-filter">
                                             <i class="bi bi-search"></i>
                                             Terapkan
                                        </button>

                                        <a href="{{ route('super-admin.employees.index') }}" class="btn-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               {{-- TABLE CARD --}}
               <div class="employee-card">
                    <div class="employee-card-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon">
                                        <i class="bi bi-list-ul"></i>
                                   </span>
                                   Daftar Employee
                              </h5>

                              <p class="list-subtitle">
                                   Menampilkan identitas, organisasi, kontak, status,
                                   gaji pokok, dan tanggal mulai bekerja.
                              </p>
                         </div>

                         <span class="result-badge">
                              <i class="bi bi-database-fill"></i>
                              {{ $employees->total() }} data ditemukan
                         </span>
                    </div>

                    <div class="employee-card-body">
                         <div class="table-responsive">
                              <table class="table employee-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="4%">No</th>
                                             <th width="18%">Employee</th>
                                             <th width="14%">Departemen / Jabatan</th>
                                             <th width="14%">Kontak</th>
                                             <th width="10%">Gender</th>
                                             <th width="11%">Kepegawaian</th>
                                             <th width="10%">Gaji Pokok</th>
                                             <th width="9%">Mulai Kerja</th>
                                             <th width="8%">Status</th>
                                             <th width="12%" class="text-center">
                                                  Aksi
                                             </th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($employees as $employee)
                                             @php
                                                  $photoUrl = null;

                                                  if (isset($employee->photo_url) && filled($employee->photo_url)) {
                                                      $photoUrl = $employee->photo_url;
                                                  } elseif (filled($employee->photo_path)) {
                                                      $photoUrl = \Illuminate\Support\Str::startsWith(
                                                          $employee->photo_path,
                                                          ['http://', 'https://'],
                                                      )
                                                          ? $employee->photo_path
                                                          : \Illuminate\Support\Facades\Storage::disk('public')->url(
                                                              $employee->photo_path,
                                                          );
                                                  }

                                                  $employeeInitial = strtoupper(
                                                      mb_substr(trim((string) $employee->full_name), 0, 1),
                                                  );

                                                  $employeeGender =
                                                      $genderLabels[strtolower((string) $employee->gender)] ??
                                                      \Illuminate\Support\Str::of((string) $employee->gender)
                                                          ->replace('_', ' ')
                                                          ->title();

                                                  $employeeStatusLabel =
                                                      $employmentLabels[
                                                          strtolower((string) $employee->employment_status)
                                                      ] ??
                                                      \Illuminate\Support\Str::of((string) $employee->employment_status)
                                                          ->replace('_', ' ')
                                                          ->title();
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $employees->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="employee-info">
                                                            <div class="employee-avatar">
                                                                 @if ($photoUrl)
                                                                      <img src="{{ $photoUrl }}"
                                                                           alt="Foto {{ $employee->full_name }}"
                                                                           loading="lazy"
                                                                           onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                                                      <span style="display: none;">
                                                                           {{ $employeeInitial }}
                                                                      </span>
                                                                 @else
                                                                      {{ $employeeInitial }}
                                                                 @endif
                                                            </div>

                                                            <div>
                                                                 <span class="employee-name">
                                                                      {{ $employee->full_name }}
                                                                 </span>

                                                                 <span class="employee-number">
                                                                      <i class="bi bi-person-vcard"></i>
                                                                      {{ $employee->employee_number }}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <span class="info-main">
                                                            {{ $employee->department?->name ?? 'Belum ditentukan' }}
                                                       </span>

                                                       <span class="info-sub">
                                                            {{ $employee->position?->name ?? 'Belum ada jabatan' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="info-main">
                                                            {{ $employee->email ?: 'Email belum tersedia' }}
                                                       </span>

                                                       <span class="info-sub">
                                                            {{ $employee->phone ?: 'Telepon belum tersedia' }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="info-main">
                                                            {{ $employeeGender }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="custom-badge badge-employment">
                                                            <i class="bi bi-briefcase-fill"></i>
                                                            {{ $employeeStatusLabel }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="salary-value">
                                                            @if (!is_null($employee->basic_salary))
                                                                 Rp
                                                                 {{ number_format((float) $employee->basic_salary, 0, ',', '.') }}
                                                            @else
                                                                 -
                                                            @endif
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="date-value">
                                                            {{ optional($employee->hire_date)->format('d M Y') ?? '-' }}
                                                       </span>

                                                       <span class="date-caption">
                                                            Bergabung
                                                       </span>
                                                  </td>

                                                  <td>
                                                       @if ($employee->status === 'active')
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

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            @if (Route::has('super-admin.employees.show'))
                                                                 <a href="{{ route('super-admin.employees.show', $employee) }}"
                                                                      class="btn action-btn btn-view" title="Lihat detail"
                                                                      aria-label="Lihat detail {{ $employee->full_name }}">
                                                                      <i class="bi bi-eye-fill"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($canManageEmployees)
                                                                 @if (Route::has('super-admin.employees.edit'))
                                                                      <a href="{{ route('super-admin.employees.edit', $employee) }}"
                                                                           class="btn action-btn btn-edit"
                                                                           title="Edit employee"
                                                                           aria-label="Edit {{ $employee->full_name }}">
                                                                           <i class="bi bi-pencil-fill"></i>
                                                                      </a>
                                                                 @endif

                                                                 @if (Route::has('super-admin.employees.destroy'))
                                                                      <form action="{{ route('super-admin.employees.destroy', $employee) }}"
                                                                           method="POST" class="d-inline"
                                                                           onsubmit="return confirm('Yakin ingin menghapus employee ini?')">
                                                                           @csrf
                                                                           @method('DELETE')

                                                                           <button type="submit"
                                                                                class="btn action-btn btn-delete"
                                                                                title="Hapus employee"
                                                                                aria-label="Hapus {{ $employee->full_name }}">
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
                                                  <td colspan="10" class="empty-state">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-person-x-fill"></i>
                                                       </div>

                                                       <h5>Data Employee Tidak Ditemukan</h5>

                                                       <p>
                                                            @if ($hasActiveFilter)
                                                                 Tidak ada employee yang sesuai
                                                                 dengan pencarian atau filter.
                                                            @else
                                                                 Tambahkan employee pertama
                                                                 untuk mulai mengelola data SDM.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilter)
                                                            <a href="{{ route('super-admin.employees.index') }}"
                                                                 class="empty-add-button">
                                                                 <i class="bi bi-arrow-counterclockwise"></i>
                                                                 Hapus Filter
                                                            </a>
                                                       @elseif ($canManageEmployees && Route::has('super-admin.employees.create'))
                                                            <a href="{{ route('super-admin.employees.create') }}"
                                                                 class="empty-add-button">
                                                                 <i class="bi bi-person-plus-fill"></i>
                                                                 Tambah Employee
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($employees->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan
                                        {{ $employees->firstItem() }}–{{ $employees->lastItem() }}
                                        dari {{ $employees->total() }} data
                                   </div>

                                   {{ $employees->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>

     @push('scripts')
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const departmentSelect =
                         document.getElementById('department_id');

                    const positionSelect =
                         document.getElementById('position_id');

                    if (!departmentSelect || !positionSelect) {
                         return;
                    }

                    const originalPositionOptions = Array.from(
                         positionSelect.options
                    ).map(function(option) {
                         return option.cloneNode(true);
                    });

                    const selectedPositionId = @json($currentPositionId);

                    function refreshPositions() {
                         const departmentId = departmentSelect.value;

                         positionSelect.innerHTML = '';

                         originalPositionOptions.forEach(function(option) {
                              const optionDepartmentId =
                                   option.dataset.departmentId || '';

                              if (
                                   option.value === '' ||
                                   departmentId === '' ||
                                   optionDepartmentId === departmentId
                              ) {
                                   const clonedOption = option.cloneNode(true);

                                   if (
                                        String(clonedOption.value) ===
                                        String(selectedPositionId)
                                   ) {
                                        clonedOption.selected = true;
                                   }

                                   positionSelect.appendChild(clonedOption);
                              }
                         });

                         const selectedOption =
                              positionSelect.querySelector(
                                   'option:checked'
                              );

                         if (
                              selectedOption &&
                              departmentId !== '' &&
                              selectedOption.dataset.departmentId !== departmentId
                         ) {
                              positionSelect.value = '';
                         }
                    }

                    departmentSelect.addEventListener(
                         'change',
                         refreshPositions
                    );

                    refreshPositions();
               });
          </script>
     @endpush
@endsection
