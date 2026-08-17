@extends('layouts.app')

@section('title')
     Monitoring Produktivitas Karyawan Dan Transaksi Jasa
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

          $inactiveOnPage = $currentPageCount - $activeOnPage;

          $overnightOnPage = $currentCollection
              ->filter(
                  static fn($workSchedule) => !blank(data_get($workSchedule, 'start_time')) &&
                      !blank(data_get($workSchedule, 'end_time')) &&
                      substr((string) data_get($workSchedule, 'end_time'), 0, 5) <=
                          substr((string) data_get($workSchedule, 'start_time'), 0, 5),
              )
              ->count();

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
               --schedule-primary: #0f766e;
               --schedule-primary-dark: #115e59;
               --schedule-secondary: #0891b2;
               --schedule-purple: #1d4ed8;
               --schedule-pink: #ea580c;
               --schedule-success: #10b981;
               --schedule-warning: #d97706;
               --schedule-danger: #dc2626;
               --schedule-info: #0284c7;
               --schedule-text: #1f2937;
               --schedule-muted: #64748b;
               --schedule-border: #e7eaf3;
               --schedule-white: #ffffff;
               --schedule-soft-blue: #edf9ff;
               --schedule-soft-purple: #ecfeff;
               --schedule-soft-green: #ecfdf5;
               --schedule-soft-orange: #fff7ed;
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
                    radial-gradient(circle at 4% 5%, rgba(14, 165, 233, .15), transparent 26%),
                    radial-gradient(circle at 96% 8%, rgba(16, 185, 129, .16), transparent 25%),
                    radial-gradient(circle at 88% 94%, rgba(249, 115, 22, .10), transparent 24%),
                    linear-gradient(140deg, #f8fafc 0%, #f0fdfa 45%, #eff6ff 100%);
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
               background: rgba(13, 148, 136, .08);
          }

          .schedule-page::after {
               right: -150px;
               bottom: 70px;
               width: 320px;
               height: 320px;
               background: rgba(2, 132, 199, .09);
          }

          @keyframes scheduleFadeUp {
               from {
                    opacity: 0;
                    transform: translateY(16px);
               }

               to {
                    opacity: 1;
                    transform: translateY(0);
               }
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
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .30), transparent 23%),
                    linear-gradient(120deg, #0f766e 0%, #0891b2 48%, #1d4ed8 100%);
               box-shadow: 0 22px 52px rgba(15, 118, 110, .24);
               animation: scheduleFadeUp .5s ease-out;
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
               align-items: stretch;
               justify-content: space-between;
          }

          .schedule-hero-copy {
               min-width: 0;
          }

          .schedule-hero-kicker {
               display: inline-flex;
               padding: 7px 12px;
               margin-bottom: 14px;
               gap: 7px;
               align-items: center;
               color: #ffffff;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               border: 1px solid rgba(255, 255, 255, .32);
               border-radius: 999px;
               background: rgba(255, 255, 255, .12);
               backdrop-filter: blur(10px);
          }

          .schedule-hero-kicker svg {
               width: 14px;
               height: 14px;
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
               color: #0f766e;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 14px 28px rgba(15, 23, 42, .16);
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

          .schedule-hero-insights {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 12px;
               margin-top: 22px;
          }

          .schedule-hero-insight {
               min-width: 0;
               padding: 14px 15px;
               border: 1px solid rgba(255, 255, 255, .18);
               border-radius: 17px;
               background: rgba(9, 14, 46, .16);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .10);
               backdrop-filter: blur(10px);
          }

          .schedule-hero-insight-label {
               display: block;
               margin-bottom: 6px;
               color: rgba(255, 255, 255, .72);
               font-size: .69rem;
               font-weight: 800;
               letter-spacing: .07em;
               text-transform: uppercase;
          }

          .schedule-hero-insight-value {
               display: block;
               color: #ffffff;
               font-size: 1.28rem;
               font-weight: 850;
               letter-spacing: -.03em;
               line-height: 1.1;
          }

          .schedule-hero-insight-note {
               display: block;
               margin-top: 5px;
               color: rgba(255, 255, 255, .78);
               font-size: .75rem;
          }

          .schedule-hero-actions {
               display: flex;
               flex: 0 0 auto;
               flex-direction: column;
               gap: 10px;
               align-items: stretch;
               justify-content: space-between;
               width: 260px;
          }

          .schedule-hero-panel {
               min-width: 240px;
               padding: 18px;
               border: 1px solid rgba(255, 255, 255, .18);
               border-radius: 22px;
               background: rgba(12, 18, 48, .20);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .12);
               backdrop-filter: blur(12px);
          }

          .schedule-hero-panel-title {
               margin: 0 0 12px;
               color: #ffffff;
               font-size: .93rem;
               font-weight: 820;
          }

          .schedule-hero-panel-list {
               display: grid;
               gap: 10px;
               margin-bottom: 16px;
          }

          .schedule-hero-panel-item {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               padding: 10px 12px;
               color: rgba(255, 255, 255, .9);
               font-size: .78rem;
               font-weight: 700;
               border-radius: 14px;
               background: rgba(255, 255, 255, .10);
          }

          .schedule-hero-panel-item strong {
               color: #ffffff;
               font-size: .9rem;
               font-weight: 850;
          }

          .schedule-hero-button {
               display: inline-flex;
               min-height: 48px;
               width: 100%;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               font-size: .87rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .80);
               border-radius: 14px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 12px 24px rgba(15, 23, 42, .16);
               transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
          }

          .schedule-hero-button svg {
               width: 17px;
               height: 17px;
          }

          .schedule-hero-button:hover {
               color: #115e59;
               text-decoration: none;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(15, 23, 42, .22);
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
               box-shadow: 0 15px 35px rgba(15, 23, 42, .09);
               transition: transform .22s ease, box-shadow .22s ease;
               animation: scheduleFadeUp .55s ease-out;
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
               color: #0f766e;
               background: linear-gradient(135deg, #ecfeff, #cffafe);
          }

          .schedule-stat-page {
               color: #0369a1;
               background: linear-gradient(135deg, #eff6ff, #dbeafe);
          }

          .schedule-stat-active {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .schedule-stat-department {
               color: #9a3412;
               background: linear-gradient(135deg, #fff7ed, #fed7aa);
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
               box-shadow: 0 15px 38px rgba(15, 23, 42, .08);
               backdrop-filter: blur(12px);
               position: relative;
               overflow: hidden;
               animation: scheduleFadeUp .62s ease-out;
          }

          .schedule-filter-card::before {
               position: absolute;
               top: 0;
               right: 0;
               left: 0;
               height: 4px;
               content: '';
               background: linear-gradient(90deg, #0f766e, #0891b2, #1d4ed8);
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
               align-items: stretch;
               justify-content: stretch;
          }

          .schedule-filter-actions>* {
               flex: 1 1 0;
          }

          .schedule-button-filter,
          .schedule-button-reset {
               display: inline-flex;
               min-height: 47px;
               height: 47px;
               width: 100%;
               padding: 0 18px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .85rem;
               font-weight: 800;
               line-height: 1;
               text-decoration: none;
               white-space: nowrap;
               cursor: pointer;
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
                         var(--schedule-secondary),
                         var(--schedule-secondary));
               box-shadow: 0 10px 21px rgba(15, 118, 110, .25);
          }

          .schedule-button-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(14, 116, 144, .28);
          }

          .schedule-button-filter:focus-visible,
          .schedule-button-reset:focus-visible {
               outline: 0;
               box-shadow: 0 0 0 .22rem rgba(15, 118, 110, .2);
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
               box-shadow: 0 18px 45px rgba(15, 23, 42, .10);
               backdrop-filter: blur(10px);
               position: relative;
               animation: scheduleFadeUp .68s ease-out;
          }

          .schedule-card::before {
               position: absolute;
               top: 0;
               right: 0;
               left: 0;
               height: 1px;
               content: '';
               background: linear-gradient(90deg, rgba(15, 118, 110, .3), rgba(2, 132, 199, .2), rgba(29, 78, 216, .14));
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
               padding: 14px 18px 20px;
          }

          .schedule-table-shell {
               overflow: hidden;
               border: 1px solid #edf2f7;
               border-radius: 20px;
               background: linear-gradient(180deg, rgba(255, 255, 255, .98), rgba(248, 251, 255, .95));
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

          .schedule-table tbody tr:nth-child(even):not(:hover) {
               background: rgba(248, 250, 252, .58);
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
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .45);
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
               min-width: 172px;
               gap: 8px;
               align-items: center;
               justify-content: flex-end;
               flex-wrap: nowrap;
          }

          .schedule-actions>* {
               flex: 0 0 auto;
          }

          .schedule-actions form {
               margin: 0;
          }

          .schedule-actions .d-inline {
               display: inline-flex !important;
               margin: 0;
               line-height: 0;
          }

          .schedule-action-button {
               display: inline-flex;
               flex: 0 0 38px;
               width: 38px;
               height: 38px;
               padding: 0;
               align-items: center;
               justify-content: center;
               text-decoration: none;
               border: 1px solid transparent;
               border-radius: 11px;
               appearance: none;
               cursor: pointer;
               box-shadow: 0 2px 4px rgba(15, 23, 42, .05);
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

          .schedule-action-button:focus-visible {
               outline: 0;
               box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .2);
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
               padding: 18px 8px 2px;
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
                    flex-direction: column;
               }

               .schedule-hero-actions {
                    width: 100%;
               }

               .schedule-hero-button {
                    width: 100%;
               }

               .schedule-hero-insights {
                    grid-template-columns: 1fr;
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
                    grid-template-columns: 1fr;
               }

               .schedule-hero-panel {
                    width: 100%;
                    min-width: 0;
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

               .schedule-hero-panel-item {
                    padding: 9px 10px;
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

               .schedule-hero-insight {
                    padding: 12px 13px;
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

               .schedule-hero-panel-list {
                    margin-bottom: 12px;
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
               padding: 18px 20px 16px;
               margin-bottom: 20px;
               border: 1px solid #d9e2ec;
               border-radius: 10px;
               background: #ffffff;
               box-shadow: 0 6px 16px rgba(15, 23, 42, .04);
          }

          .schedule-print-brand {
               display: flex;
               gap: 14px;
               align-items: center;
               margin-bottom: 12px;
               padding-bottom: 12px;
               border-bottom: 1px solid #e2e8f0;
          }

          .schedule-print-icon {
               display: inline-flex;
               width: 46px;
               height: 46px;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: #f1f5f9;
               border: 1px solid #dfe7f0;
               color: #0f172a;
          }

          .schedule-print-icon svg {
               width: 22px;
               height: 22px;
          }

          .schedule-print-kicker {
               display: inline-block;
               margin-bottom: 4px;
               color: #334155;
               font-size: .68rem;
               font-weight: 800;
               letter-spacing: .08em;
               text-transform: uppercase;
          }

          .schedule-print-header h1 {
               margin: 0;
               color: #0f172a;
               font-size: 1.45rem;
               font-weight: 850;
               line-height: 1.2;
          }

          .schedule-print-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 18px;
               align-items: center;
               justify-content: space-between;
               color: #334155;
          }

          .schedule-print-meta p {
               margin: 0;
               font-size: .76rem;
               line-height: 1.5;
          }

          .schedule-print-summary {
               display: inline-flex;
               flex-wrap: wrap;
               gap: 8px;
               justify-content: flex-end;
          }

          .schedule-print-pill {
               display: inline-flex;
               padding: 6px 10px;
               align-items: center;
               gap: 6px;
               border-radius: 999px;
               background: #f8fafc;
               border: 1px solid #dfe7f0;
               color: #0f172a;
               font-size: .7rem;
               font-weight: 700;
          }

          .schedule-page.is-print-mode {
               padding: 0;
               background: #ffffff;
          }

          .schedule-page.is-print-mode .schedule-no-print,
          .schedule-page.is-print-mode .schedule-card-header,
          .schedule-page.is-print-mode .schedule-pagination-wrap,
          .schedule-page.is-print-mode .schedule-stats-row,
          .schedule-page.is-print-mode .schedule-filter-card,
          .schedule-page.is-print-mode .schedule-hero {
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
                    size: A4 portrait;
                    margin: 12mm;
               }

               html,
               body {
                    width: 100%;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #ffffff !important;
                    color: #0f172a !important;
               }

               body * {
                    visibility: hidden !important;
               }

               .schedule-print-area,
               .schedule-print-area * {
                    visibility: visible !important;
               }

               .schedule-print-area {
                    position: relative !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow: visible !important;
                    border: 1px solid #dfe7f0 !important;
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
               .schedule-no-print-column,
               .schedule-stats-row,
               .schedule-filter-card,
               .schedule-hero {
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
                    border: 1px solid #cbd5e1 !important;
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

               .schedule-table-shell {
                    border: 0 !important;
                    border-radius: 0 !important;
                    background: #ffffff !important;
               }

               .schedule-print-header {
                    margin-bottom: 10px !important;
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

                              <div class="schedule-hero-copy">
                                   <span class="schedule-hero-kicker">
                                        <i data-feather="activity"></i>
                                        Productivity and Service Monitoring
                                   </span>

                                   <h1>Monitoring Produktivitas Karyawan Dan Transaksi Jasa</h1>

                                   <p>
                                        Pantau ritme operasional harian melalui jadwal kerja, indikator aktivitas,
                                        serta kesiapan transaksi jasa dalam satu tampilan eksekutif.
                                   </p>

                                   <div class="schedule-hero-insights">
                                        <div class="schedule-hero-insight">
                                             <span class="schedule-hero-insight-label">Total Jadwal</span>
                                             <span
                                                  class="schedule-hero-insight-value">{{ number_format($filteredTotal) }}</span>
                                             <span class="schedule-hero-insight-note">Tersedia sesuai filter aktif</span>
                                        </div>
                                        <div class="schedule-hero-insight">
                                             <span class="schedule-hero-insight-label">Shift Aktif</span>
                                             <span
                                                  class="schedule-hero-insight-value">{{ number_format($activeOnPage) }}</span>
                                             <span class="schedule-hero-insight-note">{{ number_format($inactiveOnPage) }}
                                                  nonaktif di halaman ini</span>
                                        </div>
                                        <div class="schedule-hero-insight">
                                             <span class="schedule-hero-insight-label">Lintas Hari</span>
                                             <span
                                                  class="schedule-hero-insight-value">{{ number_format($overnightOnPage) }}</span>
                                             <span class="schedule-hero-insight-note">Shift berakhir di hari
                                                  berikutnya</span>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         @if (
                             $canManageWorkSchedules &&
                                 ($routeHas('super-admin.work-schedules.print') || $routeHas('super-admin.work-schedules.create')))
                              <div class="schedule-hero-actions">
                                   <div class="schedule-hero-panel">
                                        <h2 class="schedule-hero-panel-title">Ringkasan Monitoring</h2>
                                        <div class="schedule-hero-panel-list">
                                             <div class="schedule-hero-panel-item">
                                                  <span>Produktivitas rata-rata</span>
                                                  <strong>{{ number_format($averageWorkingHours, 2) }} jam</strong>
                                             </div>
                                             <div class="schedule-hero-panel-item">
                                                  <span>Data terpantau</span>
                                                  <strong>{{ number_format($currentPageCount) }}</strong>
                                             </div>
                                        </div>

                                        @if ($hasActiveFilters)
                                             <div class="schedule-hero-panel-item">
                                                  <span>Status dashboard</span>
                                                  <strong>Dengan filter</strong>
                                             </div>
                                        @endif
                                   </div>

                                   @if ($canManageWorkSchedules && $routeHas('super-admin.work-schedules.print'))
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
                                        <div class="schedule-stat-title">Data Monitoring</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($filteredTotal) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Total data sesuai filter aktif
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
                                        <div class="schedule-stat-title">Tampilan Saat Ini</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($currentPageCount) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Rekaman pada halaman ini
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
                                        <div class="schedule-stat-title">Unit Aktif</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($activeOnPage) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Shift aktif yang sedang terpantau
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
                                        <div class="schedule-stat-title">Produktivitas Waktu</div>

                                        <div class="schedule-stat-value">
                                             {{ number_format($averageWorkingHours, 2) }}
                                        </div>

                                        <div class="schedule-stat-caption">
                                             Rata-rata jam kerja per shift
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

                         <span>Filter Data Monitoring</span>
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
                                   Data monitoring ditampilkan berdasarkan jadwal kerja, waktu shift, dan status
                                   operasional.
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
                                   <div class="schedule-print-brand">
                                        <span class="schedule-print-icon" aria-hidden="true">
                                             <i data-feather="activity"></i>
                                        </span>

                                        <div>
                                             <span class="schedule-print-kicker">Dashboard Monitoring</span>
                                             <h1>Produktivitas Karyawan &amp; Transaksi Jasa</h1>
                                        </div>
                                   </div>

                                   <div class="schedule-print-meta">
                                        <p>
                                             Dicetak pada:
                                             {{ $printedAt instanceof \DateTimeInterface ? $printedAt->format('d-m-Y H:i') : $printedAt }}
                                             WIB
                                        </p>

                                        <div class="schedule-print-summary">
                                             <span class="schedule-print-pill">Total:
                                                  {{ number_format($filteredTotal) }}</span>

                                             @if ($search !== '')
                                                  <span class="schedule-print-pill">Kata kunci: {{ $search }}</span>
                                             @endif

                                             @if ($status !== '')
                                                  <span class="schedule-print-pill">Status:
                                                       {{ $statusLabel($status) }}</span>
                                             @endif
                                        </div>
                                   </div>
                              </div>
                         @endif

                         <div class="schedule-table-shell">
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
                                                       @if ($canManageWorkSchedules)
                                                            <th class="text-end schedule-no-print-column">Aksi</th>
                                                       @endif
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
                                                                      <i
                                                                           data-feather="{{ $overnight ? 'moon' : 'sun' }}"></i>

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
                                                            @if ($canManageWorkSchedules)
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
                                                            @endif
                                                       @endunless
                                                  </tr>
                                             @empty
                                                  <tr>
                                                       <td colspan="{{ $printMode || !$canManageWorkSchedules ? 7 : 8 }}"
                                                            class="schedule-empty-state">
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
