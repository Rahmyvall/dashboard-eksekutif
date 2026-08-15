@extends('layouts.app')

@section('title', 'Manajemen Jabatan')

@section('content')
     @php
          $authUser = auth()->user();

          $rawActiveRole =
              session('active_role_name') ??
              (session('active_role') ??
                  (data_get($authUser, 'active_role_name') ??
                      (data_get($authUser, 'role_name') ??
                          (data_get($authUser, 'role') ??
                              ($authUser && method_exists($authUser, 'getRoleNames')
                                  ? $authUser->getRoleNames()->first()
                                  : null)))));

          $normalizedRole = strtolower(str_replace(['-', ' '], '_', trim((string) ($rawActiveRole ?? ''))));

          $isSuperAdmin = isset($isSuperAdmin)
              ? (bool) $isSuperAdmin
              : ($authUser &&
                      method_exists($authUser, 'hasRole') &&
                      ($authUser->hasRole('super_admin') ||
                          $authUser->hasRole('super admin') ||
                          $authUser->hasRole('superadministrator'))) ||
                  in_array(
                      $normalizedRole,
                      ['super_admin', 'superadmin', 'super_administrator', 'superadministrator'],
                      true,
                  );

          $canManagePositions = $isSuperAdmin;

          $routeHas = static fn(string $name): bool => \Illuminate\Support\Facades\Route::has($name);

          $search = isset($search) ? trim((string) $search) : trim((string) request('search', ''));

          $status = isset($status) ? (string) $status : (string) request('status', '');

          $departmentId = isset($departmentId) ? (string) $departmentId : (string) request('department_id', '');

          $level = isset($level) ? (string) $level : (string) request('level', '');

          $filteredTotal = method_exists($positions, 'total') ? $positions->total() : $positions->count();

          $currentPageCount = $positions->count();

          $currentCollection = method_exists($positions, 'getCollection')
              ? $positions->getCollection()
              : collect($positions);

          $activeOnPage = $currentCollection->where('status', \App\Models\Position::STATUS_ACTIVE)->count();

          $departmentsOnPage = $currentCollection->pluck('department_id')->filter()->unique()->count();

          $hasActiveFilters = $search !== '' || $status !== '' || $departmentId !== '' || $level !== '';

          $statusLabel = static function (?string $value): string {
              return match ($value) {
                  \App\Models\Position::STATUS_ACTIVE => 'Aktif',
                  \App\Models\Position::STATUS_INACTIVE => 'Tidak Aktif',
                  default => 'Tidak Diketahui',
              };
          };

          $levelLabel = static function (int|string|null $value): string {
              $numericLevel = (int) $value;

              return match ($numericLevel) {
                  1 => 'Staff',
                  2 => 'Senior Staff',
                  3 => 'Supervisor',
                  4 => 'Manager',
                  5 => 'Direktur',
                  default => 'Level ' . $numericLevel,
              };
          };
     @endphp

     <style>
          :root {
               --position-primary: #6366f1;
               --position-primary-dark: #4f46e5;
               --position-secondary: #06b6d4;
               --position-purple: #8b5cf6;
               --position-pink: #ec4899;
               --position-success: #10b981;
               --position-warning: #f59e0b;
               --position-danger: #ef4444;
               --position-info: #0ea5e9;
               --position-text: #24324a;
               --position-muted: #718096;
               --position-border: #e7eaf3;
               --position-white: #ffffff;
               --position-soft-blue: #eef7ff;
               --position-soft-purple: #f3f0ff;
               --position-soft-green: #ecfdf5;
               --position-soft-orange: #fff7e8;
               --position-soft-red: #fff1f2;
          }

          .position-page,
          .position-page * {
               box-sizing: border-box;
          }

          .position-page {
               position: relative;
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               overflow: hidden;
               background:
                    radial-gradient(circle at 4% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    radial-gradient(circle at 88% 94%, rgba(244, 114, 182, .14), transparent 22%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .position-page::before,
          .position-page::after {
               position: absolute;
               z-index: 0;
               content: '';
               pointer-events: none;
               border-radius: 999px;
               filter: blur(4px);
          }

          .position-page::before {
               top: 220px;
               left: -150px;
               width: 300px;
               height: 300px;
               background: rgba(139, 92, 246, .08);
          }

          .position-page::after {
               right: -150px;
               bottom: 70px;
               width: 320px;
               height: 320px;
               background: rgba(6, 182, 212, .08);
          }

          .position-container {
               position: relative;
               z-index: 1;
               width: 100%;
               max-width: 1580px;
               margin: 0 auto;
          }

          /* ================================================================
                     HERO
                  ================================================================= */

          .position-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, .70);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #6366f1 0%, #8b5cf6 43%, #06b6d4 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .position-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .position-hero::after {
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

          .position-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .position-hero-title-wrap {
               display: flex;
               min-width: 0;
               gap: 17px;
               align-items: center;
          }

          .position-hero-icon {
               display: inline-flex;
               flex: 0 0 66px;
               width: 66px;
               height: 66px;
               color: var(--position-primary-dark);
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .17);
          }

          .position-hero-icon svg {
               width: 29px;
               height: 29px;
          }

          .position-hero h1 {
               margin: 0;
               font-size: clamp(1.72rem, 2.5vw, 2.42rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .position-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .96rem;
               line-height: 1.72;
          }

          .position-hero-actions {
               display: flex;
               flex: 0 0 auto;
               gap: 10px;
               align-items: center;
          }

          .position-hero-button {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .87rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .80);
               border-radius: 14px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
               transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
          }

          .position-hero-button svg {
               width: 17px;
               height: 17px;
          }

          .position-hero-button:hover {
               color: #312e81;
               text-decoration: none;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(76, 29, 149, .22);
          }

          .position-hero-button.is-soft {
               color: #ffffff;
               border-color: rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .16);
               backdrop-filter: blur(10px);
          }

          .position-hero-button.is-soft:hover {
               color: #ffffff;
               background: rgba(255, 255, 255, .25);
          }

          /* ================================================================
                     ALERT
                  ================================================================= */

          .position-alert {
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

          .position-alert svg {
               flex: 0 0 auto;
               width: 20px;
               height: 20px;
               margin-top: 1px;
          }

          .position-alert-success {
               color: #047857;
               border-left: 5px solid var(--position-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .position-alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--position-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .position-alert-close {
               padding: 0;
               margin-left: auto;
               color: currentColor;
               line-height: 1;
               border: 0;
               background: transparent;
               opacity: .65;
          }

          .position-alert-close:hover {
               opacity: 1;
          }

          /* ================================================================
                     STATISTICS
                  ================================================================= */

          .position-stats-row {
               margin-bottom: 22px;
          }

          .position-stat-card {
               position: relative;
               min-height: 138px;
               padding: 22px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .95);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: transform .22s ease, box-shadow .22s ease;
          }

          .position-stat-card:hover {
               transform: translateY(-4px);
               box-shadow: 0 20px 40px rgba(51, 65, 85, .12);
          }

          .position-stat-card::after {
               position: absolute;
               right: -28px;
               bottom: -40px;
               width: 128px;
               height: 128px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .52);
          }

          .position-stat-total {
               color: #4338ca;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .position-stat-page {
               color: #0369a1;
               background: linear-gradient(135deg, #eff6ff, #cffafe);
          }

          .position-stat-active {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #ccfbf1);
          }

          .position-stat-department {
               color: #b45309;
               background: linear-gradient(135deg, #fff7ed, #fef3c7);
          }

          .position-stat-inner {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
          }

          .position-stat-title {
               margin-bottom: 7px;
               font-size: .73rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .position-stat-value {
               font-size: 2.25rem;
               font-weight: 850;
               line-height: 1;
               letter-spacing: -.04em;
          }

          .position-stat-caption {
               margin-top: 8px;
               font-size: .79rem;
               font-weight: 650;
               opacity: .72;
          }

          .position-stat-icon {
               display: inline-flex;
               flex: 0 0 54px;
               width: 54px;
               height: 54px;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 17px;
               background: rgba(255, 255, 255, .74);
               box-shadow: 0 10px 22px rgba(15, 23, 42, .07);
          }

          .position-stat-icon svg {
               width: 23px;
               height: 23px;
          }

          /* ================================================================
                     FILTER
                  ================================================================= */

          .position-filter-card {
               padding: 22px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .90);
               border-radius: 22px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
               backdrop-filter: blur(12px);
          }

          .position-filter-heading {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 17px;
               color: var(--position-text);
               font-size: .94rem;
               font-weight: 820;
          }

          .position-filter-heading-icon {
               display: inline-flex;
               width: 38px;
               height: 38px;
               color: var(--position-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: var(--position-soft-purple);
          }

          .position-filter-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .position-filter-label {
               margin-bottom: 7px;
               color: #52627a;
               font-size: .77rem;
               font-weight: 800;
               letter-spacing: .025em;
          }

          .position-filter-control {
               min-height: 47px;
               color: var(--position-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #ffffff;
               box-shadow: none;
          }

          .position-filter-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .11);
          }

          .position-search-shell {
               position: relative;
          }

          .position-search-shell>svg {
               position: absolute;
               z-index: 2;
               top: 50%;
               left: 15px;
               width: 17px;
               height: 17px;
               color: #818cf8;
               pointer-events: none;
               transform: translateY(-50%);
          }

          .position-search-shell .form-control {
               padding-left: 43px;
          }

          .position-filter-actions {
               display: flex;
               height: 100%;
               gap: 10px;
               align-items: flex-end;
          }

          .position-button-filter,
          .position-button-reset {
               display: inline-flex;
               min-height: 47px;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .85rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
          }

          .position-button-filter svg,
          .position-button-reset svg {
               width: 17px;
               height: 17px;
          }

          .position-button-filter {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg,
                         var(--position-primary),
                         var(--position-purple),
                         var(--position-secondary));
               box-shadow: 0 10px 21px rgba(99, 102, 241, .22);
          }

          .position-button-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(99, 102, 241, .28);
          }

          .position-button-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #ffffff;
          }

          .position-button-reset:hover {
               color: #334155;
               text-decoration: none;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          .position-active-filter {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               align-items: center;
               padding-top: 15px;
               margin-top: 17px;
               border-top: 1px dashed #dbe3ef;
          }

          .position-active-filter-label {
               color: var(--position-muted);
               font-size: .77rem;
               font-weight: 750;
          }

          .position-filter-chip {
               display: inline-flex;
               min-height: 31px;
               padding: 5px 10px;
               gap: 6px;
               align-items: center;
               color: #5b21b6;
               font-size: .75rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .position-filter-chip svg {
               width: 13px;
               height: 13px;
          }

          /* ================================================================
                     TABLE CARD
                  ================================================================= */

          .position-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .90);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .position-card-header {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #ffffff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .position-list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--position-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .position-list-title-icon {
               display: inline-flex;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               color: var(--position-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .position-list-title-icon svg {
               width: 20px;
               height: 20px;
          }

          .position-list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--position-muted);
               font-size: .81rem;
          }

          .position-result-badge {
               display: inline-flex;
               flex: 0 0 auto;
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

          .position-result-badge svg {
               width: 15px;
               height: 15px;
          }

          .position-card-body {
               padding: 10px 18px 20px;
          }

          .position-table {
               min-width: 1120px;
               margin-bottom: 0;
          }

          .position-table thead th {
               padding: 15px 13px;
               color: #52627a;
               font-size: .70rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               vertical-align: middle;
               border-top: 0;
               border-bottom: 1px solid #e8edf4;
               background: #fbfcff;
          }

          .position-table tbody td {
               padding: 16px 13px;
               color: #41506a;
               font-size: .85rem;
               vertical-align: middle;
               border-color: #eef2f7;
          }

          .position-table tbody tr {
               transition: background .18s ease, transform .18s ease;
          }

          .position-table tbody tr:hover {
               background: linear-gradient(90deg, #fbfdff, #faf8ff);
          }

          .position-row-number {
               display: inline-flex;
               width: 34px;
               height: 34px;
               color: #64748b;
               font-size: .77rem;
               font-weight: 800;
               align-items: center;
               justify-content: center;
               border: 1px solid #e2e8f0;
               border-radius: 11px;
               background: #f8fafc;
          }

          .position-name-cell {
               display: flex;
               min-width: 230px;
               gap: 12px;
               align-items: center;
          }

          .position-avatar {
               display: inline-flex;
               flex: 0 0 44px;
               width: 44px;
               height: 44px;
               color: #ffffff;
               font-size: .91rem;
               font-weight: 850;
               align-items: center;
               justify-content: center;
               border-radius: 14px;
               background:
                    radial-gradient(circle at 28% 20%, rgba(255, 255, 255, .30), transparent 30%),
                    linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
               box-shadow: 0 9px 18px rgba(99, 102, 241, .18);
          }

          .position-name {
               margin-bottom: 4px;
               color: var(--position-text);
               font-size: .90rem;
               font-weight: 820;
               line-height: 1.35;
          }

          .position-code {
               display: inline-flex;
               padding: 4px 8px;
               color: #5b21b6;
               font-size: .68rem;
               font-weight: 850;
               letter-spacing: .045em;
               border: 1px solid #ddd6fe;
               border-radius: 8px;
               background: #f5f3ff;
          }

          .position-department {
               display: flex;
               min-width: 170px;
               gap: 9px;
               align-items: center;
          }

          .position-department-icon {
               display: inline-flex;
               flex: 0 0 34px;
               width: 34px;
               height: 34px;
               color: #0369a1;
               align-items: center;
               justify-content: center;
               border-radius: 10px;
               background: #e0f2fe;
          }

          .position-department-icon svg {
               width: 16px;
               height: 16px;
          }

          .position-department-name {
               color: #334155;
               font-weight: 750;
               line-height: 1.35;
          }

          .position-department-code {
               margin-top: 3px;
               color: #94a3b8;
               font-size: .69rem;
               font-weight: 700;
          }

          .position-level {
               display: inline-flex;
               min-width: 108px;
               padding: 7px 10px;
               gap: 7px;
               align-items: center;
               color: #9a3412;
               font-size: .74rem;
               font-weight: 800;
               border: 1px solid #fed7aa;
               border-radius: 10px;
               background: #fff7ed;
          }

          .position-level svg {
               width: 14px;
               height: 14px;
          }

          .position-description {
               display: block;
               max-width: 320px;
               color: #64748b;
               line-height: 1.56;
          }

          .position-description-empty {
               color: #a8b2c1;
               font-style: italic;
          }

          .position-status {
               display: inline-flex;
               min-width: 104px;
               padding: 7px 10px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               font-size: .73rem;
               font-weight: 850;
               border-radius: 999px;
          }

          .position-status-dot {
               width: 7px;
               height: 7px;
               border-radius: 999px;
               box-shadow: 0 0 0 4px rgba(255, 255, 255, .55);
          }

          .position-status.is-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .position-status.is-active .position-status-dot {
               background: var(--position-success);
          }

          .position-status.is-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .position-status.is-inactive .position-status-dot {
               background: #f43f5e;
          }

          .position-date {
               min-width: 112px;
               color: #64748b;
               font-size: .76rem;
               line-height: 1.45;
          }

          .position-date strong {
               display: block;
               margin-bottom: 3px;
               color: #475569;
               font-size: .78rem;
          }

          .position-actions {
               display: flex;
               min-width: 130px;
               gap: 7px;
               align-items: center;
               justify-content: flex-end;
          }

          .position-action-button {
               display: inline-flex;
               width: 37px;
               height: 37px;
               padding: 0;
               align-items: center;
               justify-content: center;
               text-decoration: none;
               border-radius: 11px;
               transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
          }

          .position-action-button svg {
               width: 16px;
               height: 16px;
          }

          .position-action-button:hover {
               text-decoration: none;
               transform: translateY(-2px);
          }

          .position-action-show {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .position-action-show:hover {
               color: #075985;
               background: #e0f2fe;
               box-shadow: 0 9px 17px rgba(14, 165, 233, .14);
          }

          .position-action-edit {
               color: #a16207;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .position-action-edit:hover {
               color: #854d0e;
               background: #fef3c7;
               box-shadow: 0 9px 17px rgba(245, 158, 11, .14);
          }

          .position-action-delete {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .position-action-delete:hover {
               color: #9f1239;
               background: #ffe4e6;
               box-shadow: 0 9px 17px rgba(244, 63, 94, .14);
          }

          .position-empty-state {
               padding: 68px 24px !important;
               text-align: center;
          }

          .position-empty-icon {
               display: inline-flex;
               width: 78px;
               height: 78px;
               margin-bottom: 17px;
               color: #7c3aed;
               align-items: center;
               justify-content: center;
               border: 1px solid #ddd6fe;
               border-radius: 24px;
               background: linear-gradient(135deg, #f5f3ff, #e0f2fe);
               box-shadow: 0 14px 28px rgba(99, 102, 241, .10);
          }

          .position-empty-icon svg {
               width: 33px;
               height: 33px;
          }

          .position-empty-title {
               margin: 0 0 7px;
               color: var(--position-text);
               font-size: 1.05rem;
               font-weight: 830;
          }

          .position-empty-description {
               max-width: 480px;
               margin: 0 auto;
               color: var(--position-muted);
               font-size: .85rem;
               line-height: 1.65;
          }

          /* ================================================================
                     PAGINATION
                  ================================================================= */

          .position-pagination-wrap {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               padding: 18px 7px 0;
               border-top: 1px solid #eef2f7;
          }

          .position-pagination-info {
               color: var(--position-muted);
               font-size: .78rem;
               font-weight: 650;
          }

          .position-pagination-info strong {
               color: var(--position-text);
          }

          .position-pagination-wrap .pagination {
               gap: 5px;
               margin: 0;
          }

          .position-pagination-wrap .page-link {
               min-width: 37px;
               min-height: 37px;
               padding: 8px 11px;
               color: #64748b;
               font-size: .78rem;
               font-weight: 750;
               text-align: center;
               border: 1px solid #e2e8f0;
               border-radius: 10px !important;
               background: #ffffff;
               box-shadow: none;
          }

          .position-pagination-wrap .page-link:hover {
               color: #4f46e5;
               border-color: #c7d2fe;
               background: #eef2ff;
          }

          .position-pagination-wrap .page-item.active .page-link {
               color: #ffffff;
               border-color: transparent;
               background: linear-gradient(135deg, #6366f1, #8b5cf6);
               box-shadow: 0 8px 17px rgba(99, 102, 241, .20);
          }

          .position-pagination-wrap .page-item.disabled .page-link {
               color: #cbd5e1;
               background: #f8fafc;
          }

          /* ================================================================
                     RESPONSIVE
                  ================================================================= */

          @media (max-width: 1199.98px) {
               .position-hero-content {
                    align-items: flex-start;
               }

               .position-hero-actions {
                    flex-direction: column;
                    align-items: stretch;
               }

               .position-hero-button {
                    width: 100%;
               }
          }

          @media (max-width: 991.98px) {
               .position-page {
                    padding: 22px 14px 34px;
               }

               .position-hero {
                    padding: 27px;
                    border-radius: 23px;
               }

               .position-hero-content {
                    flex-direction: column;
               }

               .position-hero-actions {
                    width: 100%;
                    flex-direction: row;
               }

               .position-card-header {
                    align-items: flex-start;
               }
          }

          @media (max-width: 767.98px) {
               .position-hero {
                    padding: 23px 20px;
               }

               .position-hero-title-wrap {
                    align-items: flex-start;
               }

               .position-hero-icon {
                    flex-basis: 54px;
                    width: 54px;
                    height: 54px;
                    border-radius: 17px;
               }

               .position-hero h1 {
                    font-size: 1.57rem;
               }

               .position-hero p {
                    font-size: .88rem;
               }

               .position-hero-actions {
                    flex-direction: column;
               }

               .position-filter-card {
                    padding: 18px;
               }

               .position-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }

               .position-card-header {
                    flex-direction: column;
               }

               .position-result-badge {
                    margin-left: 53px;
               }

               .position-pagination-wrap {
                    justify-content: center;
                    text-align: center;
               }
          }

          @media (max-width: 479.98px) {
               .position-page {
                    padding-right: 10px;
                    padding-left: 10px;
               }

               .position-hero-title-wrap {
                    flex-direction: column;
               }

               .position-hero-actions,
               .position-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr;
               }

               .position-list-subtitle,
               .position-result-badge {
                    margin-left: 0;
               }
          }
     </style>

     <div class="position-page">
          <div class="position-container">
               {{-- ============================================================
                 HERO
            ============================================================= --}}
               <section class="position-hero">
                    <div class="position-hero-content">
                         <div class="position-hero-title-wrap">
                              <div class="position-hero-icon" aria-hidden="true">
                                   <i data-feather="briefcase"></i>
                              </div>

                              <div>
                                   <h1>Manajemen Jabatan</h1>

                                   <p>
                                        Kelola struktur jabatan, level organisasi, status, dan keterkaitan
                                        jabatan dengan setiap departemen perusahaan.
                                   </p>
                              </div>
                         </div>

                         @if ($canManagePositions)
                              <div class="position-hero-actions">
                                   @if ($routeHas('super-admin.positions.trash'))
                                        <a href="{{ route('super-admin.positions.trash') }}"
                                             class="position-hero-button is-soft">
                                             <i data-feather="trash-2"></i>
                                             <span>Sampah</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.positions.create'))
                                        <a href="{{ route('super-admin.positions.create') }}" class="position-hero-button">
                                             <i data-feather="plus"></i>
                                             <span>Tambah Jabatan</span>
                                        </a>
                                   @endif
                              </div>
                         @endif
                    </div>
               </section>

               {{-- ============================================================
                 FLASH MESSAGE
            ============================================================= --}}
               @if (session('success'))
                    <div class="alert alert-dismissible fade show position-alert position-alert-success" role="alert">
                         <i data-feather="check-circle"></i>

                         <span>{{ session('success') }}</span>

                         <button type="button" class="position-alert-close" data-bs-dismiss="alert"
                              aria-label="Tutup pesan">
                              <i data-feather="x"></i>
                         </button>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-dismissible fade show position-alert position-alert-danger" role="alert">
                         <i data-feather="alert-circle"></i>

                         <span>{{ session('error') }}</span>

                         <button type="button" class="position-alert-close" data-bs-dismiss="alert"
                              aria-label="Tutup pesan">
                              <i data-feather="x"></i>
                         </button>
                    </div>
               @endif

               {{-- ============================================================
                 STATISTICS
            ============================================================= --}}
               <div class="row g-3 position-stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="position-stat-card position-stat-total">
                              <div class="position-stat-inner">
                                   <div>
                                        <div class="position-stat-title">Hasil Ditemukan</div>
                                        <div class="position-stat-value">
                                             {{ number_format($filteredTotal) }}
                                        </div>
                                        <div class="position-stat-caption">
                                             Total sesuai filter saat ini
                                        </div>
                                   </div>

                                   <span class="position-stat-icon">
                                        <i data-feather="layers"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="position-stat-card position-stat-page">
                              <div class="position-stat-inner">
                                   <div>
                                        <div class="position-stat-title">Data Halaman</div>
                                        <div class="position-stat-value">
                                             {{ number_format($currentPageCount) }}
                                        </div>
                                        <div class="position-stat-caption">
                                             Data tampil pada halaman ini
                                        </div>
                                   </div>

                                   <span class="position-stat-icon">
                                        <i data-feather="file-text"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="position-stat-card position-stat-active">
                              <div class="position-stat-inner">
                                   <div>
                                        <div class="position-stat-title">Aktif di Halaman</div>
                                        <div class="position-stat-value">
                                             {{ number_format($activeOnPage) }}
                                        </div>
                                        <div class="position-stat-caption">
                                             Jabatan aktif yang sedang tampil
                                        </div>
                                   </div>

                                   <span class="position-stat-icon">
                                        <i data-feather="check-circle"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="position-stat-card position-stat-department">
                              <div class="position-stat-inner">
                                   <div>
                                        <div class="position-stat-title">Departemen Terwakili</div>
                                        <div class="position-stat-value">
                                             {{ number_format($departmentsOnPage) }}
                                        </div>
                                        <div class="position-stat-caption">
                                             Departemen pada halaman ini
                                        </div>
                                   </div>

                                   <span class="position-stat-icon">
                                        <i data-feather="grid"></i>
                                   </span>
                              </div>
                         </article>
                    </div>
               </div>

               {{-- ============================================================
                 FILTER
            ============================================================= --}}
               <section class="position-filter-card">
                    <div class="position-filter-heading">
                         <span class="position-filter-heading-icon">
                              <i data-feather="filter"></i>
                         </span>

                         <span>Filter dan Pencarian Jabatan</span>
                    </div>

                    <form method="GET" action="{{ route('super-admin.positions.index') }}">
                         <div class="row g-3">
                              <div class="col-12 col-lg-4">
                                   <label for="position-search" class="position-filter-label">
                                        Kata Kunci
                                   </label>

                                   <div class="position-search-shell">
                                        <i data-feather="search"></i>

                                        <input type="search" id="position-search" name="search"
                                             class="form-control position-filter-control" value="{{ $search }}"
                                             placeholder="Cari kode, nama, deskripsi, atau departemen" autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-4 col-lg-2">
                                   <label for="position-department" class="position-filter-label">
                                        Departemen
                                   </label>

                                   <select id="position-department" name="department_id"
                                        class="form-select position-filter-control">
                                        <option value="">Semua Departemen</option>

                                        @foreach ($departments as $department)
                                             <option value="{{ $department->getKey() }}" @selected((string) $departmentId === (string) $department->getKey())>
                                                  {{ $department->code }} — {{ $department->name }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-4 col-lg-2">
                                   <label for="position-level" class="position-filter-label">
                                        Level Jabatan
                                   </label>

                                   <select id="position-level" name="level" class="form-select position-filter-control">
                                        <option value="">Semua Level</option>

                                        @foreach ($levels as $levelOption)
                                             <option value="{{ $levelOption }}" @selected((string) $level === (string) $levelOption)>
                                                  Level {{ $levelOption }} — {{ $levelLabel($levelOption) }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-4 col-lg-2">
                                   <label for="position-status" class="position-filter-label">
                                        Status
                                   </label>

                                   <select id="position-status" name="status"
                                        class="form-select position-filter-control">
                                        <option value="">Semua Status</option>

                                        <option value="{{ \App\Models\Position::STATUS_ACTIVE }}"
                                             @selected($status === \App\Models\Position::STATUS_ACTIVE)>
                                             Aktif
                                        </option>

                                        <option value="{{ \App\Models\Position::STATUS_INACTIVE }}"
                                             @selected($status === \App\Models\Position::STATUS_INACTIVE)>
                                             Tidak Aktif
                                        </option>
                                   </select>
                              </div>

                              <div class="col-12 col-lg-2">
                                   <div class="position-filter-actions">
                                        <button type="submit" class="position-button-filter">
                                             <i data-feather="search"></i>
                                             <span>Terapkan</span>
                                        </button>

                                        <a href="{{ route('super-admin.positions.index') }}" class="position-button-reset"
                                             title="Hapus seluruh filter">
                                             <i data-feather="rotate-ccw"></i>
                                             <span>Reset</span>
                                        </a>
                                   </div>
                              </div>
                         </div>

                         @if ($hasActiveFilters)
                              <div class="position-active-filter">
                                   <span class="position-active-filter-label">
                                        Filter aktif:
                                   </span>

                                   @if ($search !== '')
                                        <span class="position-filter-chip">
                                             <i data-feather="search"></i>
                                             Kata kunci: {{ $search }}
                                        </span>
                                   @endif

                                   @if ($departmentId !== '')
                                        @php
                                             $selectedDepartment = $departments->firstWhere('id', (int) $departmentId);
                                        @endphp

                                        <span class="position-filter-chip">
                                             <i data-feather="grid"></i>
                                             Departemen:
                                             {{ $selectedDepartment?->name ?? 'ID ' . $departmentId }}
                                        </span>
                                   @endif

                                   @if ($level !== '')
                                        <span class="position-filter-chip">
                                             <i data-feather="bar-chart-2"></i>
                                             Level {{ $level }}
                                        </span>
                                   @endif

                                   @if ($status !== '')
                                        <span class="position-filter-chip">
                                             <i data-feather="activity"></i>
                                             Status: {{ $statusLabel($status) }}
                                        </span>
                                   @endif
                              </div>
                         @endif
                    </form>
               </section>

               {{-- ============================================================
                 TABLE
            ============================================================= --}}
               <section class="position-card">
                    <header class="position-card-header">
                         <div>
                              <h2 class="position-list-title">
                                   <span class="position-list-title-icon">
                                        <i data-feather="list"></i>
                                   </span>

                                   <span>Daftar Jabatan</span>
                              </h2>

                              <p class="position-list-subtitle">
                                   Data jabatan tersusun berdasarkan level tertinggi dan nama.
                              </p>
                         </div>

                         <span class="position-result-badge">
                              <i data-feather="{{ $hasActiveFilters ? 'filter' : 'database' }}"></i>
                              {{ number_format($filteredTotal) }}
                              {{ $hasActiveFilters ? 'hasil filter' : 'data ditemukan' }}
                         </span>
                    </header>

                    <div class="position-card-body">
                         <div class="table-responsive">
                              <table class="table position-table">
                                   <thead>
                                        <tr>
                                             <th style="width: 68px;">No.</th>
                                             <th>Jabatan</th>
                                             <th>Departemen</th>
                                             <th>Level</th>
                                             <th>Deskripsi</th>
                                             <th>Status</th>
                                             <th>Diperbarui</th>
                                             <th class="text-end">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($positions as $position)
                                             @php
                                                  $initials = \Illuminate\Support\Str::of($position->name)
                                                      ->explode(' ')
                                                      ->filter()
                                                      ->take(2)
                                                      ->map(
                                                          static fn($word) => \Illuminate\Support\Str::upper(
                                                              \Illuminate\Support\Str::substr($word, 0, 1),
                                                          ),
                                                      )
                                                      ->implode('');

                                                  $initials = $initials !== '' ? $initials : 'JB';

                                                  $isActive = $position->status === \App\Models\Position::STATUS_ACTIVE;
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="position-row-number">
                                                            {{ $positions->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="position-name-cell">
                                                            <span class="position-avatar" aria-hidden="true">
                                                                 {{ $initials }}
                                                            </span>

                                                            <div>
                                                                 <div class="position-name">
                                                                      {{ $position->name }}
                                                                 </div>

                                                                 <span class="position-code">
                                                                      {{ $position->code }}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <div class="position-department">
                                                            <span class="position-department-icon" aria-hidden="true">
                                                                 <i data-feather="grid"></i>
                                                            </span>

                                                            <div>
                                                                 <div class="position-department-name">
                                                                      {{ $position->department?->name ?? 'Departemen tidak tersedia' }}
                                                                 </div>

                                                                 <div class="position-department-code">
                                                                      {{ $position->department?->code ?? '—' }}
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <span class="position-level">
                                                            <i data-feather="bar-chart-2"></i>
                                                            Level {{ $position->level }}
                                                            · {{ $levelLabel($position->level) }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       @if (filled($position->description))
                                                            <span class="position-description"
                                                                 title="{{ $position->description }}">
                                                                 {{ \Illuminate\Support\Str::limit($position->description, 90) }}
                                                            </span>
                                                       @else
                                                            <span class="position-description position-description-empty">
                                                                 Belum ada deskripsi
                                                            </span>
                                                       @endif
                                                  </td>

                                                  <td>
                                                       <span
                                                            class="position-status {{ $isActive ? 'is-active' : 'is-inactive' }}">
                                                            <span class="position-status-dot"></span>
                                                            {{ $statusLabel($position->status) }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="position-date">
                                                            <strong>
                                                                 {{ $position->updated_at?->format('d M Y') ?? '—' }}
                                                            </strong>

                                                            <span>
                                                                 {{ $position->updated_at?->format('H:i') ?? '—' }} WIB
                                                            </span>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <div class="position-actions">
                                                            @if ($routeHas('super-admin.positions.show'))
                                                                 <a href="{{ route('super-admin.positions.show', $position) }}"
                                                                      class="position-action-button position-action-show"
                                                                      title="Lihat detail {{ $position->name }}"
                                                                      aria-label="Lihat detail {{ $position->name }}">
                                                                      <i data-feather="eye"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($canManagePositions && $routeHas('super-admin.positions.edit'))
                                                                 <a href="{{ route('super-admin.positions.edit', $position) }}"
                                                                      class="position-action-button position-action-edit"
                                                                      title="Edit {{ $position->name }}"
                                                                      aria-label="Edit {{ $position->name }}">
                                                                      <i data-feather="edit-3"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($canManagePositions && $routeHas('super-admin.positions.destroy'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.positions.destroy', $position) }}"
                                                                      class="d-inline"
                                                                      onsubmit="return confirm(
                                                              'Yakin ingin menghapus jabatan {{ addslashes($position->name) }}? Data akan dipindahkan ke sampah.'
                                                          );">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="position-action-button position-action-delete"
                                                                           title="Hapus {{ $position->name }}"
                                                                           aria-label="Hapus {{ $position->name }}">
                                                                           <i data-feather="trash-2"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="8" class="position-empty-state">
                                                       <span class="position-empty-icon">
                                                            <i
                                                                 data-feather="{{ $hasActiveFilters ? 'search' : 'briefcase' }}"></i>
                                                       </span>

                                                       <h3 class="position-empty-title">
                                                            {{ $hasActiveFilters ? 'Jabatan tidak ditemukan' : 'Data jabatan belum tersedia' }}
                                                       </h3>

                                                       <p class="position-empty-description">
                                                            @if ($hasActiveFilters)
                                                                 Tidak ada data yang cocok dengan filter saat ini.
                                                                 Ubah kata kunci atau reset filter untuk melihat data
                                                                 lainnya.
                                                            @else
                                                                 Belum ada jabatan yang tercatat. Super Admin dapat
                                                                 menambahkan jabatan baru melalui tombol Tambah Jabatan.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilters)
                                                            <a href="{{ route('super-admin.positions.index') }}"
                                                                 class="position-button-reset mt-3">
                                                                 <i data-feather="rotate-ccw"></i>
                                                                 <span>Reset Filter</span>
                                                            </a>
                                                       @elseif ($canManagePositions && $routeHas('super-admin.positions.create'))
                                                            <a href="{{ route('super-admin.positions.create') }}"
                                                                 class="position-button-filter mt-3">
                                                                 <i data-feather="plus"></i>
                                                                 <span>Tambah Jabatan Pertama</span>
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($positions->hasPages())
                              <div class="position-pagination-wrap">
                                   <div class="position-pagination-info">
                                        Menampilkan
                                        <strong>
                                             {{ number_format($positions->firstItem() ?? 0) }}
                                        </strong>
                                        sampai
                                        <strong>
                                             {{ number_format($positions->lastItem() ?? 0) }}
                                        </strong>
                                        dari
                                        <strong>
                                             {{ number_format($positions->total()) }}
                                        </strong>
                                        data
                                   </div>

                                   <div>
                                        {{ $positions->onEachSide(1)->links() }}
                                   </div>
                              </div>
                         @elseif ($filteredTotal > 0)
                              <div class="position-pagination-wrap">
                                   <div class="position-pagination-info">
                                        Menampilkan seluruh
                                        <strong>{{ number_format($filteredTotal) }}</strong>
                                        data pada halaman ini.
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
