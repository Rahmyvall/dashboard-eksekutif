@extends('layouts.app')

@section('title')
     Manajemen Jadwal Kerja
@endsection

@section('content')
     @php
          $authUser = auth()->user();

          $isSuperAdmin = isset($isSuperAdmin)
              ? (bool) $isSuperAdmin
              : $authUser && method_exists($authUser, 'hasRole') && $authUser->hasRole('super_admin');

          $canManageWorkSchedules = $isSuperAdmin;

          $printMode = isset($printMode) ? (bool) $printMode : false;

          $printedAt = $printedAt ?? now();

          $search = isset($search) ? trim((string) $search) : trim((string) request('search', ''));

          $status = isset($status) ? (string) $status : (string) request('status', '');

          $filteredTotal = method_exists($workSchedules, 'total') ? $workSchedules->total() : $workSchedules->count();

          $currentPageCount = $workSchedules->count();

          $currentCollection = method_exists($workSchedules, 'getCollection')
              ? $workSchedules->getCollection()
              : collect($workSchedules);

          $activeOnPage = $currentCollection->where('status', 'active')->count();

          $averageWorkingHours = $currentCollection->isNotEmpty()
              ? round((float) $currentCollection->avg('working_hours'), 2)
              : 0;

          $hasActiveFilters = $search !== '' || $status !== '';

          $routeHas = static fn(string $name): bool => \Illuminate\Support\Facades\Route::has($name);

          $statusLabel = static function (?string $value): string {
              return match ($value) {
                  'active' => 'Aktif',
                  'inactive' => 'Tidak Aktif',
                  default => 'Tidak Diketahui',
              };
          };

          $formatTime = static function ($value): string {
              if (blank($value)) {
                  return '—';
              }

              return substr((string) $value, 0, 5);
          };

          $isOvernightShift = static function ($startTime, $endTime): bool {
              if (blank($startTime) || blank($endTime)) {
                  return false;
              }

              return substr((string) $endTime, 0, 5) <= substr((string) $startTime, 0, 5);
          };
     @endphp

     <style>
          :root {
               --schedule-primary: #6366f1;
               --schedule-primary-dark: #4f46e5;
               --schedule-secondary: #06b6d4;
               --schedule-purple: #8b5cf6;
               --schedule-pink: #ec4899;
               --schedule-success: #10b981;
               --schedule-warning: #f59e0b;
               --schedule-danger: #ef4444;
               --schedule-info: #0ea5e9;
               --schedule-text: #24324a;
               --schedule-muted: #718096;
               --schedule-border: #e7eaf3;
               --schedule-white: #ffffff;
               --schedule-soft-blue: #eef7ff;
               --schedule-soft-purple: #f3f0ff;
               --schedule-soft-green: #ecfdf5;
               --schedule-soft-orange: #fff7e8;
               --schedule-soft-red: #fff1f2;
          }

          .schedule-page,
          .schedule-page * {
               box-sizing: border-box;
          }

          .schedule-page {
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

          .schedule-page::before,
          .schedule-page::after {
               position: absolute;
               z-index: 0;
               content: '';
               pointer-events: none;
               border-radius: 999px;
               filter: blur(4px);
          }

          .schedule-page::before {
               top: 220px;
               left: -150px;
               width: 300px;
               height: 300px;
               background: rgba(139, 92, 246, .08);
          }

          .schedule-page::after {
               right: -150px;
               bottom: 70px;
               width: 320px;
               height: 320px;
               background: rgba(6, 182, 212, .08);
          }

          .schedule-container {
               position: relative;
               z-index: 1;
               width: 100%;
               max-width: 1580px;
               margin: 0 auto;
          }

          /* ================================================================
                          HERO
                       ================================================================= */

          .schedule-hero {
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

          .schedule-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .schedule-hero::after {
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

          .schedule-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .schedule-hero-title-wrap {
               display: flex;
               min-width: 0;
               gap: 17px;
               align-items: center;
          }

          .schedule-hero-icon {
               display: inline-flex;
               flex: 0 0 66px;
               width: 66px;
               height: 66px;
               color: var(--schedule-primary-dark);
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .17);
          }

          .schedule-hero-icon svg {
               width: 29px;
               height: 29px;
          }

          .schedule-hero h1 {
               margin: 0;
               font-size: clamp(1.72rem, 2.5vw, 2.42rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .schedule-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .96rem;
               line-height: 1.72;
          }

          .schedule-hero-actions {
               display: flex;
               flex: 0 0 auto;
               gap: 10px;
               align-items: center;
          }

          .schedule-hero-button {
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

          .schedule-hero-button svg {
               width: 17px;
               height: 17px;
          }

          .schedule-hero-button:hover {
               color: #312e81;
               text-decoration: none;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(76, 29, 149, .22);
          }

          .schedule-hero-button.is-soft {
               color: #ffffff;
               border-color: rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .16);
               backdrop-filter: blur(10px);
          }

          .schedule-hero-button.is-soft:hover {
               color: #ffffff;
               background: rgba(255, 255, 255, .25);
          }

          /* ================================================================
                          ALERT
                       ================================================================= */

          .schedule-alert {
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

          .schedule-alert svg {
               flex: 0 0 auto;
               width: 20px;
               height: 20px;
               margin-top: 1px;
          }

          .schedule-alert-success {
               color: #047857;
               border-left: 5px solid var(--schedule-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .schedule-alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--schedule-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .schedule-alert-close {
               padding: 0;
               margin-left: auto;
               color: currentColor;
               line-height: 1;
               border: 0;
               background: transparent;
               opacity: .65;
          }

          .schedule-alert-close:hover {
               opacity: 1;
          }

          /* ================================================================
                          STATISTICS
                       ================================================================= */

          .schedule-stats-row {
               margin-bottom: 22px;
          }

          .schedule-stat-card {
               position: relative;
               min-height: 138px;
               padding: 22px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .95);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: transform .22s ease, box-shadow .22s ease;
          }

          .schedule-stat-card:hover {
               transform: translateY(-4px);
               box-shadow: 0 20px 40px rgba(51, 65, 85, .12);
          }

          .schedule-stat-card::after {
               position: absolute;
               right: -28px;
               bottom: -40px;
               width: 128px;
               height: 128px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .52);
          }

          .schedule-stat-total {
               color: #4338ca;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .schedule-stat-page {
               color: #0369a1;
               background: linear-gradient(135deg, #eff6ff, #cffafe);
          }

          .schedule-stat-active {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #ccfbf1);
          }

          .schedule-stat-department {
               color: #b45309;
               background: linear-gradient(135deg, #fff7ed, #fef3c7);
          }

          .schedule-stat-inner {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
          }

          .schedule-stat-title {
               margin-bottom: 7px;
               font-size: .73rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .schedule-stat-value {
               font-size: 2.25rem;
               font-weight: 850;
               line-height: 1;
               letter-spacing: -.04em;
          }

          .schedule-stat-caption {
               margin-top: 8px;
               font-size: .79rem;
               font-weight: 650;
               opacity: .72;
          }

          .schedule-stat-icon {
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

          .schedule-stat-icon svg {
               width: 23px;
               height: 23px;
          }

          /* ================================================================
                          FILTER
                       ================================================================= */

          .schedule-filter-card {
               padding: 22px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .90);
               border-radius: 22px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
               backdrop-filter: blur(12px);
          }

          .schedule-filter-heading {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 17px;
               color: var(--schedule-text);
               font-size: .94rem;
               font-weight: 820;
          }

          .schedule-filter-heading-icon {
               display: inline-flex;
               width: 38px;
               height: 38px;
               color: var(--schedule-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: var(--schedule-soft-purple);
          }

          .schedule-filter-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .schedule-filter-label {
               margin-bottom: 7px;
               color: #52627a;
               font-size: .77rem;
               font-weight: 800;
               letter-spacing: .025em;
          }

          .schedule-filter-control {
               min-height: 47px;
               color: var(--schedule-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #ffffff;
               box-shadow: none;
          }

          .schedule-filter-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .11);
          }

          .schedule-search-shell {
               position: relative;
          }

          .schedule-search-shell>svg {
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

          .schedule-search-shell .form-control {
               padding-left: 43px;
          }

          .schedule-filter-actions {
               display: flex;
               height: 100%;
               gap: 10px;
               align-items: flex-end;
          }

          .schedule-button-filter,
          .schedule-button-reset {
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

          .schedule-button-filter svg,
          .schedule-button-reset svg {
               width: 17px;
               height: 17px;
          }

          .schedule-button-filter {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg,
                         var(--schedule-primary),
                         var(--schedule-purple),
                         var(--schedule-secondary));
               box-shadow: 0 10px 21px rgba(99, 102, 241, .22);
          }

          .schedule-button-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(99, 102, 241, .28);
          }

          .schedule-button-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #ffffff;
          }

          .schedule-button-reset:hover {
               color: #334155;
               text-decoration: none;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          .schedule-active-filter {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               align-items: center;
               padding-top: 15px;
               margin-top: 17px;
               border-top: 1px dashed #dbe3ef;
          }

          .schedule-active-filter-label {
               color: var(--schedule-muted);
               font-size: .77rem;
               font-weight: 750;
          }

          .schedule-filter-chip {
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

          .schedule-filter-chip svg {
               width: 13px;
               height: 13px;
          }

          /* ================================================================
                          TABLE CARD
                       ================================================================= */

          .schedule-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .90);
               border-radius: 24px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .schedule-card-header {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #ffffff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .schedule-list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--schedule-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .schedule-list-title-icon {
               display: inline-flex;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               color: var(--schedule-primary-dark);
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .schedule-list-title-icon svg {
               width: 20px;
               height: 20px;
          }

          .schedule-list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--schedule-muted);
               font-size: .81rem;
          }

          .schedule-result-badge {
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

          .schedule-result-badge svg {
               width: 15px;
               height: 15px;
          }

          .schedule-card-body {
               padding: 10px 18px 20px;
          }

          .schedule-table {
               min-width: 1120px;
               margin-bottom: 0;
          }

          .schedule-table thead th {
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

          .schedule-table tbody td {
               padding: 16px 13px;
               color: #41506a;
               font-size: .85rem;
               vertical-align: middle;
               border-color: #eef2f7;
          }

          .schedule-table tbody tr {
               transition: background .18s ease, transform .18s ease;
          }

          .schedule-table tbody tr:hover {
               background: linear-gradient(90deg, #fbfdff, #faf8ff);
          }

          .schedule-row-number {
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

          .schedule-name-cell {
               display: flex;
               min-width: 230px;
               gap: 12px;
               align-items: center;
          }

          .schedule-avatar {
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

          .schedule-name {
               margin-bottom: 4px;
               color: var(--schedule-text);
               font-size: .90rem;
               font-weight: 820;
               line-height: 1.35;
          }

          .schedule-code {
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

          .schedule-department {
               display: flex;
               min-width: 170px;
               gap: 9px;
               align-items: center;
          }

          .schedule-department-icon {
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

          .schedule-department-icon svg {
               width: 16px;
               height: 16px;
          }

          .schedule-department-name {
               color: #334155;
               font-weight: 750;
               line-height: 1.35;
          }

          .schedule-department-code {
               margin-top: 3px;
               color: #94a3b8;
               font-size: .69rem;
               font-weight: 700;
          }

          .schedule-level {
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

          .schedule-level svg {
               width: 14px;
               height: 14px;
          }

          .schedule-description {
               display: block;
               max-width: 320px;
               color: #64748b;
               line-height: 1.56;
          }

          .schedule-description-empty {
               color: #a8b2c1;
               font-style: italic;
          }

          .schedule-status {
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

          .schedule-status-dot {
               width: 7px;
               height: 7px;
               border-radius: 999px;
               box-shadow: 0 0 0 4px rgba(255, 255, 255, .55);
          }

          .schedule-status.is-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .schedule-status.is-active .schedule-status-dot {
               background: var(--schedule-success);
          }

          .schedule-status.is-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .schedule-status.is-inactive .schedule-status-dot {
               background: #f43f5e;
          }

          .schedule-date {
               min-width: 112px;
               color: #64748b;
               font-size: .76rem;
               line-height: 1.45;
          }

          .schedule-date strong {
               display: block;
               margin-bottom: 3px;
               color: #475569;
               font-size: .78rem;
          }

          .schedule-actions {
               display: flex;
               min-width: 130px;
               gap: 7px;
               align-items: center;
               justify-content: flex-end;
          }

          .schedule-action-button {
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

          .schedule-action-button svg {
               width: 16px;
               height: 16px;
          }

          .schedule-action-button:hover {
               text-decoration: none;
               transform: translateY(-2px);
          }

          .schedule-action-show {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .schedule-action-show:hover {
               color: #075985;
               background: #e0f2fe;
               box-shadow: 0 9px 17px rgba(14, 165, 233, .14);
          }

          .schedule-action-edit {
               color: #a16207;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .schedule-action-edit:hover {
               color: #854d0e;
               background: #fef3c7;
               box-shadow: 0 9px 17px rgba(245, 158, 11, .14);
          }

          .schedule-action-delete {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .schedule-action-delete:hover {
               color: #9f1239;
               background: #ffe4e6;
               box-shadow: 0 9px 17px rgba(244, 63, 94, .14);
          }

          .schedule-empty-state {
               padding: 68px 24px !important;
               text-align: center;
          }

          .schedule-empty-icon {
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

          .schedule-empty-icon svg {
               width: 33px;
               height: 33px;
          }

          .schedule-empty-title {
               margin: 0 0 7px;
               color: var(--schedule-text);
               font-size: 1.05rem;
               font-weight: 830;
          }

          .schedule-empty-description {
               max-width: 480px;
               margin: 0 auto;
               color: var(--schedule-muted);
               font-size: .85rem;
               line-height: 1.65;
          }

          /* ================================================================
                          PAGINATION
                       ================================================================= */

          .schedule-pagination-wrap {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               padding: 18px 7px 0;
               border-top: 1px solid #eef2f7;
          }

          .schedule-pagination-info {
               color: var(--schedule-muted);
               font-size: .78rem;
               font-weight: 650;
          }

          .schedule-pagination-info strong {
               color: var(--schedule-text);
          }

          .schedule-pagination-wrap .pagination {
               gap: 5px;
               margin: 0;
          }

          .schedule-pagination-wrap .page-link {
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

          .schedule-pagination-wrap .page-link:hover {
               color: #4f46e5;
               border-color: #c7d2fe;
               background: #eef2ff;
          }

          .schedule-pagination-wrap .page-item.active .page-link {
               color: #ffffff;
               border-color: transparent;
               background: linear-gradient(135deg, #6366f1, #8b5cf6);
               box-shadow: 0 8px 17px rgba(99, 102, 241, .20);
          }

          .schedule-pagination-wrap .page-item.disabled .page-link {
               color: #cbd5e1;
               background: #f8fafc;
          }

          /* ================================================================
                          RESPONSIVE
                       ================================================================= */

          @media (max-width: 1199.98px) {
               .schedule-hero-content {
                    align-items: flex-start;
               }

               .schedule-hero-actions {
                    flex-direction: column;
                    align-items: stretch;
               }

               .schedule-hero-button {
                    width: 100%;
               }
          }

          @media (max-width: 991.98px) {
               .schedule-page {
                    padding: 22px 14px 34px;
               }

               .schedule-hero {
                    padding: 27px;
                    border-radius: 23px;
               }

               .schedule-hero-content {
                    flex-direction: column;
               }

               .schedule-hero-actions {
                    width: 100%;
                    flex-direction: row;
               }

               .schedule-card-header {
                    align-items: flex-start;
               }
          }

          @media (max-width: 767.98px) {
               .schedule-hero {
                    padding: 23px 20px;
               }

               .schedule-hero-title-wrap {
                    align-items: flex-start;
               }

               .schedule-hero-icon {
                    flex-basis: 54px;
                    width: 54px;
                    height: 54px;
                    border-radius: 17px;
               }

               .schedule-hero h1 {
                    font-size: 1.57rem;
               }

               .schedule-hero p {
                    font-size: .88rem;
               }

               .schedule-hero-actions {
                    flex-direction: column;
               }

               .schedule-filter-card {
                    padding: 18px;
               }

               .schedule-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }

               .schedule-card-header {
                    flex-direction: column;
               }

               .schedule-result-badge {
                    margin-left: 53px;
               }

               .schedule-pagination-wrap {
                    justify-content: center;
                    text-align: center;
               }
          }

          @media (max-width: 479.98px) {
               .schedule-page {
                    padding-right: 10px;
                    padding-left: 10px;
               }

               .schedule-hero-title-wrap {
                    flex-direction: column;
               }

               .schedule-hero-actions,
               .schedule-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr;
               }

               .schedule-list-subtitle,
               .schedule-result-badge {
                    margin-left: 0;
               }
          }

          /*
                    |--------------------------------------------------------------------------
                    | Penyesuaian komponen Work Schedule
                    |--------------------------------------------------------------------------
                    | Hanya menambahkan gaya untuk data khusus jadwal kerja.
                    | Struktur warna, kartu, hero, filter, tabel, dan responsivitas tetap
                    | mengikuti template sumber.
                    */

          .schedule-time-range {
               display: flex;
               min-width: 215px;
               gap: 10px;
               align-items: center;
          }

          .schedule-time-box {
               display: inline-flex;
               min-width: 72px;
               padding: 8px 10px;
               gap: 6px;
               align-items: center;
               justify-content: center;
               color: #334155;
               font-size: .78rem;
               font-weight: 820;
               border: 1px solid #dbeafe;
               border-radius: 11px;
               background: #f8fbff;
          }

          .schedule-time-box svg {
               width: 14px;
               height: 14px;
               color: #4f46e5;
          }

          .schedule-time-arrow {
               display: inline-flex;
               color: #94a3b8;
               align-items: center;
               justify-content: center;
          }

          .schedule-time-arrow svg {
               width: 16px;
               height: 16px;
          }

          .schedule-shift-note {
               display: inline-flex;
               margin-top: 6px;
               padding: 4px 8px;
               gap: 5px;
               align-items: center;
               color: #0369a1;
               font-size: .67rem;
               font-weight: 780;
               border: 1px solid #bae6fd;
               border-radius: 999px;
               background: #f0f9ff;
          }

          .schedule-shift-note.is-overnight {
               color: #6d28d9;
               border-color: #ddd6fe;
               background: #f5f3ff;
          }

          .schedule-shift-note svg {
               width: 12px;
               height: 12px;
          }

          .schedule-metric {
               display: inline-flex;
               min-width: 118px;
               padding: 8px 11px;
               gap: 8px;
               align-items: center;
               color: #475569;
               font-size: .76rem;
               font-weight: 800;
               border: 1px solid #e2e8f0;
               border-radius: 11px;
               background: #f8fafc;
          }

          .schedule-metric svg {
               width: 15px;
               height: 15px;
          }

          .schedule-metric.is-tolerance {
               color: #9a3412;
               border-color: #fed7aa;
               background: #fff7ed;
          }

          .schedule-metric.is-duration {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .schedule-action-status {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .schedule-action-status:hover {
               color: #065f46;
               background: #d1fae5;
               box-shadow: 0 9px 17px rgba(16, 185, 129, .14);
          }

          .schedule-action-status.is-inactive-action {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .schedule-action-status.is-inactive-action:hover {
               color: #9f1239;
               background: #ffe4e6;
               box-shadow: 0 9px 17px rgba(244, 63, 94, .14);
          }

          .schedule-actions {
               min-width: 178px;
          }

          /* ================================================================
                          PRINT SELURUH TABEL
                       ================================================================= */

          .schedule-print-only {
               display: none;
          }

          .schedule-print-header {
               padding: 0 0 18px;
               margin-bottom: 14px;
               text-align: center;
               border-bottom: 2px solid #1e293b;
          }

          .schedule-print-header h1 {
               margin: 0 0 7px;
               color: #0f172a;
               font-size: 1.35rem;
               font-weight: 850;
          }

          .schedule-print-header p {
               margin: 3px 0;
               color: #475569;
               font-size: .78rem;
          }

          .schedule-page.is-print-mode {
               padding: 22px;
               background: #ffffff;
          }

          .schedule-page.is-print-mode .schedule-no-print,
          .schedule-page.is-print-mode .schedule-card-header,
          .schedule-page.is-print-mode .schedule-pagination-wrap {
               display: none !important;
          }

          .schedule-page.is-print-mode .schedule-print-only {
               display: block;
          }

          .schedule-page.is-print-mode .schedule-card {
               border-radius: 0;
               box-shadow: none;
          }

          .schedule-page.is-print-mode .schedule-card-body {
               padding: 18px;
          }

          @media print {
               @page {
                    size: A4 landscape;
                    margin: 8mm;
               }

               html,
               body {
                    width: 100%;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #ffffff !important;
               }

               body * {
                    visibility: hidden !important;
               }

               .schedule-print-area,
               .schedule-print-area * {
                    visibility: visible !important;
               }

               .schedule-print-area {
                    position: absolute !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow: visible !important;
                    border: 0 !important;
                    border-radius: 0 !important;
                    background: #ffffff !important;
                    box-shadow: none !important;
               }

               .schedule-print-only {
                    display: block !important;
               }

               .schedule-card-header,
               .schedule-pagination-wrap,
               .schedule-no-print,
               .schedule-no-print-column {
                    display: none !important;
               }

               .schedule-card-body {
                    padding: 0 !important;
               }

               .table-responsive {
                    overflow: visible !important;
               }

               .schedule-table {
                    width: 100% !important;
                    min-width: 0 !important;
                    margin: 0 !important;
                    table-layout: auto !important;
                    border-collapse: collapse !important;
               }

               .schedule-table thead {
                    display: table-header-group;
               }

               .schedule-table tr {
                    break-inside: avoid;
                    page-break-inside: avoid;
               }

               .schedule-table thead th,
               .schedule-table tbody td {
                    padding: 6px 5px !important;
                    color: #111827 !important;
                    font-size: 8.5px !important;
                    line-height: 1.25 !important;
                    border: 1px solid #cbd5e1 !important;
                    background: #ffffff !important;
                    box-shadow: none !important;
               }

               .schedule-table thead th {
                    font-weight: 800 !important;
                    text-align: center;
                    background: #f1f5f9 !important;
               }

               .schedule-row-number {
                    width: auto !important;
                    height: auto !important;
                    padding: 0 !important;
                    border: 0 !important;
                    background: transparent !important;
               }

               .schedule-name-cell,
               .schedule-time-range,
               .schedule-actions {
                    min-width: 0 !important;
               }

               .schedule-avatar {
                    display: none !important;
               }

               .schedule-name {
                    margin-bottom: 2px !important;
                    font-size: 9px !important;
               }

               .schedule-code,
               .schedule-time-box,
               .schedule-shift-note,
               .schedule-metric,
               .schedule-status {
                    min-width: 0 !important;
                    padding: 3px 5px !important;
                    font-size: 8px !important;
                    border-radius: 4px !important;
                    box-shadow: none !important;
               }

               .schedule-time-range {
                    gap: 3px !important;
               }

               .schedule-time-arrow {
                    display: none !important;
               }

               .schedule-shift-note {
                    margin-top: 3px !important;
               }

               .schedule-date {
                    min-width: 0 !important;
                    font-size: 8px !important;
               }

               .schedule-date strong {
                    margin-bottom: 1px !important;
                    font-size: 8px !important;
               }

               svg {
                    width: 10px !important;
                    height: 10px !important;
               }
          }
     </style>

     <div class="schedule-page {{ $printMode ? 'is-print-mode' : '' }}">
          <div class="schedule-container">
               {{-- ============================================================
                    HERO
               ============================================================= --}}
               <section class="schedule-hero schedule-no-print">
                    <div class="schedule-hero-content">
                         <div class="schedule-hero-title-wrap">
                              <div class="schedule-hero-icon" aria-hidden="true">
                                   <i data-feather="calendar"></i>
                              </div>

                              <div>
                                   <h1>Manajemen Jadwal Kerja</h1>

                                   <p>
                                        Kelola nama jadwal, jam masuk, jam pulang, toleransi keterlambatan,
                                        durasi kerja, dan status jadwal karyawan.
                                   </p>
                              </div>
                         </div>

                         @if (
                             $routeHas('super-admin.work-schedules.print') ||
                                 ($canManageWorkSchedules && $routeHas('super-admin.work-schedules.create')))
                              <div class="schedule-hero-actions">
                                   @if ($routeHas('super-admin.work-schedules.print'))
                                        <a href="{{ route('super-admin.work-schedules.print', request()->only(['search', 'status'])) }}"
                                             class="schedule-hero-button is-soft" target="_blank" rel="noopener"
                                             title="Cetak seluruh isi tabel sesuai filter">
                                             <i data-feather="printer"></i>
                                             <span>Cetak Seluruh Tabel</span>
                                        </a>
                                   @endif

                                   @if ($canManageWorkSchedules && $routeHas('super-admin.work-schedules.create'))
                                        <a href="{{ route('super-admin.work-schedules.create') }}"
                                             class="schedule-hero-button">
                                             <i data-feather="plus"></i>
                                             <span>Tambah Jadwal</span>
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
                    <div class="alert alert-dismissible fade show schedule-alert schedule-alert-success schedule-no-print"
                         role="alert">
                         <i data-feather="check-circle"></i>

                         <span>{{ session('success') }}</span>

                         <button type="button" class="schedule-alert-close" data-bs-dismiss="alert"
                              aria-label="Tutup pesan">
                              <i data-feather="x"></i>
                         </button>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-dismissible fade show schedule-alert schedule-alert-danger schedule-no-print"
                         role="alert">
                         <i data-feather="alert-circle"></i>

                         <span>{{ session('error') }}</span>

                         <button type="button" class="schedule-alert-close" data-bs-dismiss="alert"
                              aria-label="Tutup pesan">
                              <i data-feather="x"></i>
                         </button>
                    </div>
               @endif

               {{-- ============================================================
                    STATISTICS
               ============================================================= --}}
               <div class="row g-3 schedule-stats-row schedule-no-print">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="schedule-stat-card schedule-stat-total">
                              <div class="schedule-stat-inner">
                                   <div>
                                        <div class="schedule-stat-title">Hasil Ditemukan</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($filteredTotal) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Total sesuai filter saat ini
                                        </div>
                                   </div>

                                   <span class="schedule-stat-icon">
                                        <i data-feather="layers"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="schedule-stat-card schedule-stat-page">
                              <div class="schedule-stat-inner">
                                   <div>
                                        <div class="schedule-stat-title">Data Halaman</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($currentPageCount) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Jadwal pada halaman ini
                                        </div>
                                   </div>

                                   <span class="schedule-stat-icon">
                                        <i data-feather="file-text"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="schedule-stat-card schedule-stat-active">
                              <div class="schedule-stat-inner">
                                   <div>
                                        <div class="schedule-stat-title">Aktif di Halaman</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($activeOnPage) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Jadwal aktif yang sedang tampil
                                        </div>
                                   </div>

                                   <span class="schedule-stat-icon">
                                        <i data-feather="check-circle"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="schedule-stat-card schedule-stat-department">
                              <div class="schedule-stat-inner">
                                   <div>
                                        <div class="schedule-stat-title">Rata-rata Durasi</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($averageWorkingHours, 2) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Jam kerja pada halaman ini
                                        </div>
                                   </div>

                                   <span class="schedule-stat-icon">
                                        <i data-feather="clock"></i>
                                   </span>
                              </div>
                         </article>
                    </div>
               </div>

               {{-- ============================================================
                    FILTER
               ============================================================= --}}
               <section class="schedule-filter-card schedule-no-print">
                    <div class="schedule-filter-heading">
                         <span class="schedule-filter-heading-icon">
                              <i data-feather="filter"></i>
                         </span>

                         <span>Filter dan Pencarian Jadwal Kerja</span>
                    </div>

                    <form method="GET" action="{{ route('super-admin.work-schedules.index') }}">
                         <div class="row g-3">
                              <div class="col-12 col-lg-7">
                                   <label for="work-schedule-search" class="schedule-filter-label">
                                        Kata Kunci
                                   </label>

                                   <div class="schedule-search-shell">
                                        <i data-feather="search"></i>

                                        <input type="search" id="work-schedule-search" name="search"
                                             class="form-control schedule-filter-control" value="{{ $search }}"
                                             placeholder="Cari nama jadwal, jam masuk, atau jam pulang" autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-lg-3">
                                   <label for="work-schedule-status" class="schedule-filter-label">
                                        Status
                                   </label>

                                   <select id="work-schedule-status" name="status"
                                        class="form-select schedule-filter-control">
                                        <option value="">Semua Status</option>

                                        <option value="active" @selected($status === 'active')>
                                             Aktif
                                        </option>

                                        <option value="inactive" @selected($status === 'inactive')>
                                             Tidak Aktif
                                        </option>
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-lg-2">
                                   <div class="schedule-filter-actions">
                                        <button type="submit" class="schedule-button-filter">
                                             <i data-feather="search"></i>
                                             <span>Terapkan</span>
                                        </button>

                                        <a href="{{ route('super-admin.work-schedules.index') }}"
                                             class="schedule-button-reset" title="Hapus seluruh filter">
                                             <i data-feather="rotate-ccw"></i>
                                             <span>Reset</span>
                                        </a>
                                   </div>
                              </div>
                         </div>

                         @if ($hasActiveFilters)
                              <div class="schedule-active-filter">
                                   <span class="schedule-active-filter-label">
                                        Filter aktif:
                                   </span>

                                   @if ($search !== '')
                                        <span class="schedule-filter-chip">
                                             <i data-feather="search"></i>
                                             Kata kunci: {{ $search }}
                                        </span>
                                   @endif

                                   @if ($status !== '')
                                        <span class="schedule-filter-chip">
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
               <section class="schedule-card schedule-print-area">
                    <header class="schedule-card-header">
                         <div>
                              <h2 class="schedule-list-title">
                                   <span class="schedule-list-title-icon">
                                        <i data-feather="list"></i>
                                   </span>

                                   <span>Daftar Jadwal Kerja</span>
                              </h2>

                              <p class="schedule-list-subtitle">
                                   Data jadwal kerja ditampilkan berdasarkan jam masuk dan nama jadwal.
                              </p>
                         </div>

                         <span class="schedule-result-badge">
                              <i data-feather="{{ $hasActiveFilters ? 'filter' : 'database' }}"></i>

                              {{ number_format($filteredTotal) }}

                              {{ $hasActiveFilters ? 'hasil filter' : 'data ditemukan' }}
                         </span>
                    </header>

                    <div class="schedule-card-body">
                         @if ($printMode)
                              <div class="schedule-print-header schedule-print-only">
                                   <h1>DAFTAR JADWAL KERJA</h1>

                                   <p>
                                        Dicetak pada:
                                        {{ $printedAt instanceof \DateTimeInterface ? $printedAt->format('d-m-Y H:i') : $printedAt }}
                                        WIB
                                   </p>

                                   <p>
                                        Total data: {{ number_format($filteredTotal) }}

                                        @if ($search !== '')
                                             | Kata kunci: {{ $search }}
                                        @endif

                                        @if ($status !== '')
                                             | Status: {{ $statusLabel($status) }}
                                        @endif
                                   </p>
                              </div>
                         @endif

                         <div class="table-responsive">
                              <table class="table schedule-table">
                                   <thead>
                                        <tr>
                                             <th style="width: 68px;">No.</th>
                                             <th>Nama Jadwal</th>
                                             <th>Rentang Jam</th>
                                             <th>Toleransi</th>
                                             <th>Durasi Kerja</th>
                                             <th>Status</th>
                                             <th>Diperbarui</th>
                                             @unless ($printMode)
                                                  <th class="text-end schedule-no-print-column">Aksi</th>
                                             @endunless
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($workSchedules as $workSchedule)
                                             @php
                                                  $initials = \Illuminate\Support\Str::of($workSchedule->name)
                                                      ->explode(' ')
                                                      ->filter()
                                                      ->take(2)
                                                      ->map(
                                                          static fn($word) => \Illuminate\Support\Str::upper(
                                                              \Illuminate\Support\Str::substr($word, 0, 1),
                                                          ),
                                                      )
                                                      ->implode('');

                                                  $initials = $initials !== '' ? $initials : 'JK';

                                                  $isActive = $workSchedule->status === 'active';

                                                  $overnight = $isOvernightShift(
                                                      $workSchedule->start_time,
                                                      $workSchedule->end_time,
                                                  );
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="schedule-row-number">
                                                            {{ method_exists($workSchedules, 'firstItem')
                                                                ? ($workSchedules->firstItem() ?? 1) + $loop->index
                                                                : $loop->iteration }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="schedule-name-cell">
                                                            <span class="schedule-avatar" aria-hidden="true">
                                                                 {{ $initials }}
                                                            </span>

                                                            <div>
                                                                 <div class="schedule-name">
                                                                      {{ $workSchedule->name }}
                                                                 </div>

                                                                 <span class="schedule-code">
                                                                      ID #{{ $workSchedule->getKey() }}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <div>
                                                            <div class="schedule-time-range">
                                                                 <span class="schedule-time-box">
                                                                      <i data-feather="log-in"></i>
                                                                      {{ $formatTime($workSchedule->start_time) }}
                                                                 </span>

                                                                 <span class="schedule-time-arrow" aria-hidden="true">
                                                                      <i data-feather="arrow-right"></i>
                                                                 </span>

                                                                 <span class="schedule-time-box">
                                                                      <i data-feather="log-out"></i>
                                                                      {{ $formatTime($workSchedule->end_time) }}
                                                                 </span>
                                                            </div>

                                                            <span
                                                                 class="schedule-shift-note {{ $overnight ? 'is-overnight' : '' }}">
                                                                 <i data-feather="{{ $overnight ? 'moon' : 'sun' }}"></i>

                                                                 {{ $overnight ? 'Shift lintas hari' : 'Shift hari yang sama' }}
                                                            </span>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <span class="schedule-metric is-tolerance">
                                                            <i data-feather="alert-circle"></i>

                                                            {{ number_format((int) $workSchedule->late_tolerance_minutes) }}
                                                            menit
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="schedule-metric is-duration">
                                                            <i data-feather="clock"></i>

                                                            {{ number_format((float) $workSchedule->working_hours, 2) }}
                                                            jam
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span
                                                            class="schedule-status {{ $isActive ? 'is-active' : 'is-inactive' }}">
                                                            <span class="schedule-status-dot"></span>

                                                            {{ $statusLabel($workSchedule->status) }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="schedule-date">
                                                            <strong>
                                                                 {{ $workSchedule->updated_at?->format('d M Y') ?? '—' }}
                                                            </strong>

                                                            <span>
                                                                 {{ $workSchedule->updated_at?->format('H:i') ?? '—' }}
                                                                 WIB
                                                            </span>
                                                       </div>
                                                  </td>

                                                  @unless ($printMode)
                                                       <td class="schedule-no-print-column">
                                                            <div class="schedule-actions">
                                                                 @if ($routeHas('super-admin.work-schedules.show'))
                                                                      <a href="{{ route('super-admin.work-schedules.show', $workSchedule) }}"
                                                                           class="schedule-action-button schedule-action-show"
                                                                           title="Lihat detail {{ $workSchedule->name }}"
                                                                           aria-label="Lihat detail {{ $workSchedule->name }}">
                                                                           <i data-feather="eye"></i>
                                                                      </a>
                                                                 @endif

                                                                 @if ($canManageWorkSchedules && $routeHas('super-admin.work-schedules.toggle-status'))
                                                                      <form method="POST"
                                                                           action="{{ route('super-admin.work-schedules.toggle-status', $workSchedule) }}"
                                                                           class="d-inline"
                                                                           onsubmit="return confirm('Yakin ingin mengubah status jadwal {{ addslashes($workSchedule->name) }}?');">
                                                                           @csrf
                                                                           @method('PATCH')

                                                                           <button type="submit"
                                                                                class="schedule-action-button schedule-action-status {{ $isActive ? 'is-inactive-action' : '' }}"
                                                                                title="{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} {{ $workSchedule->name }}"
                                                                                aria-label="{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} {{ $workSchedule->name }}">
                                                                                <i
                                                                                     data-feather="{{ $isActive ? 'pause-circle' : 'play-circle' }}"></i>
                                                                           </button>
                                                                      </form>
                                                                 @endif

                                                                 @if ($canManageWorkSchedules && $routeHas('super-admin.work-schedules.edit'))
                                                                      <a href="{{ route('super-admin.work-schedules.edit', $workSchedule) }}"
                                                                           class="schedule-action-button schedule-action-edit"
                                                                           title="Edit {{ $workSchedule->name }}"
                                                                           aria-label="Edit {{ $workSchedule->name }}">
                                                                           <i data-feather="edit-3"></i>
                                                                      </a>
                                                                 @endif

                                                                 @if ($canManageWorkSchedules && $routeHas('super-admin.work-schedules.destroy'))
                                                                      <form method="POST"
                                                                           action="{{ route('super-admin.work-schedules.destroy', $workSchedule) }}"
                                                                           class="d-inline"
                                                                           onsubmit="return confirm('Yakin ingin menghapus jadwal {{ addslashes($workSchedule->name) }} secara permanen?');">
                                                                           @csrf
                                                                           @method('DELETE')

                                                                           <button type="submit"
                                                                                class="schedule-action-button schedule-action-delete"
                                                                                title="Hapus {{ $workSchedule->name }}"
                                                                                aria-label="Hapus {{ $workSchedule->name }}">
                                                                                <i data-feather="trash-2"></i>
                                                                           </button>
                                                                      </form>
                                                                 @endif
                                                            </div>
                                                       </td>
                                                  @endunless
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="{{ $printMode ? 7 : 8 }}" class="schedule-empty-state">
                                                       <span class="schedule-empty-icon">
                                                            <i
                                                                 data-feather="{{ $hasActiveFilters ? 'search' : 'calendar' }}"></i>
                                                       </span>

                                                       <h3 class="schedule-empty-title">
                                                            {{ $hasActiveFilters ? 'Jadwal kerja tidak ditemukan' : 'Data jadwal kerja belum tersedia' }}
                                                       </h3>

                                                       <p class="schedule-empty-description">
                                                            @if ($hasActiveFilters)
                                                                 Tidak ada data yang cocok dengan filter saat ini.
                                                                 Ubah kata kunci atau reset filter untuk melihat data
                                                                 lainnya.
                                                            @else
                                                                 Belum ada jadwal kerja yang tercatat.
                                                                 Super Admin dapat menambahkan jadwal baru melalui
                                                                 tombol Tambah Jadwal.
                                                            @endif
                                                       </p>

                                                       @if (!$printMode && $hasActiveFilters)
                                                            <a href="{{ route('super-admin.work-schedules.index') }}"
                                                                 class="schedule-button-reset mt-3">
                                                                 <i data-feather="rotate-ccw"></i>
                                                                 <span>Reset Filter</span>
                                                            </a>
                                                       @elseif (!$printMode && $canManageWorkSchedules && $routeHas('super-admin.work-schedules.create'))
                                                            <a href="{{ route('super-admin.work-schedules.create') }}"
                                                                 class="schedule-button-filter mt-3">
                                                                 <i data-feather="plus"></i>
                                                                 <span>Tambah Jadwal Pertama</span>
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @unless ($printMode)
                              @if (method_exists($workSchedules, 'hasPages') && $workSchedules->hasPages())
                                   <div class="schedule-pagination-wrap">
                                        <div class="schedule-pagination-info">
                                             Menampilkan
                                             <strong>
                                                  {{ number_format($workSchedules->firstItem() ?? 0) }}
                                             </strong>
                                             sampai
                                             <strong>
                                                  {{ number_format($workSchedules->lastItem() ?? 0) }}
                                             </strong>
                                             dari
                                             <strong>
                                                  {{ number_format($workSchedules->total()) }}
                                             </strong>
                                             data
                                        </div>

                                        <div>
                                             {{ $workSchedules->onEachSide(1)->links() }}
                                        </div>
                                   </div>
                              @elseif ($filteredTotal > 0)
                                   <div class="schedule-pagination-wrap">
                                        <div class="schedule-pagination-info">
                                             Menampilkan seluruh
                                             <strong>{{ number_format($filteredTotal) }}</strong>
                                             data pada halaman ini.
                                        </div>
                                   </div>
                              @endif
                         @endunless
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

                    @if ($printMode)
                         window.setTimeout(function() {
                              window.print();
                         }, 450);

                         window.addEventListener('afterprint', function() {
                              window.close();
                         });
                    @endif
               });
          </script>
     @endonce
@endsection
