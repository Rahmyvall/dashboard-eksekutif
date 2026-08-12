@extends('layouts.app')

@section('title', 'Dashboard Super Admin | Monitoring Kinerja Organisasi')

@section('content')
     @php
          /*
          |--------------------------------------------------------------------------
          | DASHBOARD SUPER ADMIN
          |--------------------------------------------------------------------------
          | Nilai utama berasal dari DashboardController. Safe default hanya
          | mencegah error ketika salah satu modul belum memiliki data.
          */

          $currentUser = auth()->user();
          $currentUserName = $currentUser?->name ?? 'Super Admin';

          $currentUserRole =
              $activeRoleLabel ??
              \Illuminate\Support\Str::of($activeRoleName ?? 'super_admin')
                  ->replace('_', ' ')
                  ->upper()
                  ->toString();

          /*
          |--------------------------------------------------------------------------
          | URL MODUL
          |--------------------------------------------------------------------------
          */

          $usersUrl =
              $usersUrl ??
              (\Illuminate\Support\Facades\Route::has('super-admin.users.index')
                  ? route('super-admin.users.index')
                  : '#');

          $positionsUrl =
              $positionsUrl ??
              (\Illuminate\Support\Facades\Route::has('super-admin.positions.index')
                  ? route('super-admin.positions.index')
                  : '#');

          $performancePeriodsUrl =
              $performancePeriodsUrl ??
              (\Illuminate\Support\Facades\Route::has('super-admin.performance-periods.index')
                  ? route('super-admin.performance-periods.index')
                  : '#');

          $performanceIndicatorsUrl =
              $performanceIndicatorsUrl ??
              (\Illuminate\Support\Facades\Route::has('super-admin.performance-indicators.index')
                  ? route('super-admin.performance-indicators.index')
                  : '#');

          $serviceCategoriesUrl =
              $serviceCategoriesUrl ??
              (\Illuminate\Support\Facades\Route::has('super-admin.service-categories.index')
                  ? route('super-admin.service-categories.index')
                  : '#');

          $settingsUrl = \Illuminate\Support\Facades\Route::has('super-admin.settings.index')
              ? route('super-admin.settings.index')
              : '#';

          /*
          |--------------------------------------------------------------------------
          | SAFE DEFAULTS DATABASE
          |--------------------------------------------------------------------------
          */

          $positions = collect($positions ?? []);
          $performancePeriods = collect($performancePeriods ?? []);
          $performanceIndicators = collect($performanceIndicators ?? []);
          $serviceCategories = collect($serviceCategories ?? []);
          $services = collect($services ?? []);
          $indicatorWeightChart = collect($indicatorWeightChart ?? []);
          $indicatorDirectionSummary = collect($indicatorDirectionSummary ?? []);
          $departmentSummary = collect($departmentSummary ?? []);
          $roleSummary = collect($roleSummary ?? []);
          $monitoringPriorities = collect($monitoringPriorities ?? []);

          $invoicePaymentLineChart = array_merge(
              [
                  'labels' => [],
                  'invoice_totals' => [],
                  'payment_totals' => [],
                  'invoice_counts' => [],
                  'payment_counts' => [],
                  'invoice_sum' => 0.0,
                  'payment_sum' => 0.0,
                  'max_amount' => 1.0,
              ],
              $invoicePaymentLineChart ?? [],
          );

          $branchSummary = array_merge(
              [
                  'total' => 0,
                  'active' => 0,
                  'pending' => 0,
                  'inactive' => 0,
                  'active_percentage' => 0,
                  'pending_percentage' => 0,
                  'inactive_percentage' => 0,
              ],
              $branchSummary ?? [],
          );

          $branchTotal = max(1, (int) ($branchSummary['total'] ?? 0));

          $branchSummary['active_percentage'] =
              (float) ($branchSummary['active_percentage'] ??
                  round(((int) $branchSummary['active'] / $branchTotal) * 100, 1));

          $branchSummary['pending_percentage'] =
              (float) ($branchSummary['pending_percentage'] ??
                  round(((int) $branchSummary['pending'] / $branchTotal) * 100, 1));

          $branchSummary['inactive_percentage'] =
              (float) ($branchSummary['inactive_percentage'] ??
                  round(((int) $branchSummary['inactive'] / $branchTotal) * 100, 1));

          $branchAngle = min(360, max(0, (float) ($branchAngle ?? $branchSummary['active_percentage'] * 3.6)));

          $performancePeriodSummary = array_merge(
              [
                  'total' => $performancePeriods->count(),
                  'draft' => $performancePeriods->where('status', 'draft')->count(),
                  'active' => $performancePeriods->where('status', 'active')->count(),
                  'completed' => $performancePeriods->where('status', 'completed')->count(),
                  'inactive' => $performancePeriods->where('status', 'inactive')->count(),
                  'current' => 0,
                  'upcoming' => 0,
                  'expired' => 0,
              ],
              $performancePeriodSummary ?? [],
          );

          $currentPerformancePeriod = $currentPerformancePeriod ?? null;

          $indicatorSummary = array_merge(
              [
                  'total' => $performanceIndicators->count(),
                  'active' => $performanceIndicators->where('status', 'active')->count(),
                  'inactive' => $performanceIndicators->where('status', 'inactive')->count(),
                  'active_percentage' => 0.0,
                  'total_active_weight' => 0.0,
                  'average_weight' => 0.0,
              ],
              $indicatorSummary ?? [],
          );

          $indicatorTotal = max(0, (int) $indicatorSummary['total']);
          $indicatorActive = max(0, (int) $indicatorSummary['active']);
          $indicatorInactive = max(0, (int) $indicatorSummary['inactive']);

          $indicatorSummary['active_percentage'] =
              $indicatorTotal > 0
                  ? min(
                      100,
                      max(
                          0,
                          (float) ($indicatorSummary['active_percentage'] ?:
                          round(($indicatorActive / $indicatorTotal) * 100, 1)),
                      ),
                  )
                  : 0.0;

          $indicatorSummary['total_active_weight'] = max(0, (float) $indicatorSummary['total_active_weight']);

          $indicatorSummary['average_weight'] = max(0, (float) $indicatorSummary['average_weight']);

          if ($indicatorDirectionSummary->isEmpty()) {
              $indicatorDirectionSummary = collect([
                  [
                      'key' => 'increase',
                      'total' => 0,
                      'percentage' => 0.0,
                      'class' => 'success',
                  ],
                  [
                      'key' => 'decrease',
                      'total' => 0,
                      'percentage' => 0.0,
                      'class' => 'warning',
                  ],
                  [
                      'key' => 'exact',
                      'total' => 0,
                      'percentage' => 0.0,
                      'class' => 'info',
                  ],
              ]);
          }

          $indicatorAngle = min(360, max(0, (float) ($indicatorAngle ?? $indicatorSummary['active_percentage'] * 3.6)));

          /*
          |--------------------------------------------------------------------------
          | RINGKASAN KATEGORI LAYANAN
          |--------------------------------------------------------------------------
          */

          $serviceCategorySummary = array_merge(
              [
                  'total' => $serviceCategories->count(),
                  'active' => $serviceCategories->where('status', 'active')->count(),
                  'inactive' => $serviceCategories->where('status', 'inactive')->count(),
                  'active_percentage' => 0.0,
              ],
              $serviceCategorySummary ?? [],
          );

          $serviceSummary = array_merge(
              [
                  'total' => $services->count(),
                  'active' => $services->where('status', 'active')->count(),
                  'inactive' => $services->where('status', 'inactive')->count(),
                  'active_percentage' => 0.0,
                  'average_price' => 0.0,
              ],
              $serviceSummary ?? [],
          );

          $serviceTotal = max(0, (int) $serviceSummary['total']);
          $serviceActive = max(0, (int) $serviceSummary['active']);
          $serviceInactive = max(0, (int) $serviceSummary['inactive']);
          $serviceSummary['active_percentage'] =
              $serviceTotal > 0 ? round(($serviceActive / $serviceTotal) * 100, 1) : 0.0;

          $serviceCategoryTotal = max(0, (int) ($serviceCategorySummary['total'] ?? 0));
          $serviceCategoryActive = max(0, (int) ($serviceCategorySummary['active'] ?? 0));
          $serviceCategoryInactive = max(0, (int) ($serviceCategorySummary['inactive'] ?? 0));

          $serviceCategorySummary['active_percentage'] =
              $serviceCategoryTotal > 0
                  ? min(
                      100,
                      max(
                          0,
                          (float) ($serviceCategorySummary['active_percentage'] ?? 0 ?:
                          round(($serviceCategoryActive / $serviceCategoryTotal) * 100, 1)),
                      ),
                  )
                  : 0.0;

          $indicatorChartColumnCount = max(1, $indicatorWeightChart->count());

          $totalActivePositions = (int) ($totalActivePositions ?? $positions->where('status', 'active')->count());

          $totalUsers = (int) $roleSummary->sum(fn($role) => (int) data_get($role, 'users', 0));

          $totalRoles = $roleSummary->count();

          /*
          |--------------------------------------------------------------------------
          | STATISTIK UTAMA
          |--------------------------------------------------------------------------
          */

          $dashboardStatistics = [
              [
                  'label' => 'Total Cabang',
                  'value' => (int) $branchSummary['total'],
                  'suffix' => '',
                  'icon' => 'git-branch',
                  'description' =>
                      number_format((int) $branchSummary['active'], 0, ',', '.') . ' cabang aktif beroperasi',
                  'trend' => number_format($branchSummary['active_percentage'], 1, ',', '.') . '% aktif',
                  'trend_type' => 'up',
                  'theme' => 'indigo',
              ],
              [
                  'label' => 'Jabatan Aktif',
                  'value' => $totalActivePositions,
                  'suffix' => '',
                  'icon' => 'briefcase',
                  'description' => 'Jabatan aktif pada struktur organisasi',
                  'trend' => 'Master data',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Periode Penilaian Aktif',
                  'value' => (int) $performancePeriodSummary['active'],
                  'suffix' => '',
                  'icon' => 'calendar',
                  'description' => (int) $performancePeriodSummary['current'] . ' periode sedang berjalan saat ini',
                  'trend' => (int) $performancePeriodSummary['draft'] . ' draft',
                  'trend_type' => 'up',
                  'theme' => 'orange',
              ],
              [
                  'label' => 'Indikator Kinerja Aktif',
                  'value' => $indicatorActive,
                  'suffix' => '',
                  'icon' => 'target',
                  'description' =>
                      number_format($indicatorSummary['total_active_weight'], 2, ',', '.') .
                      '% total bobot indikator aktif',
                  'trend' => number_format($indicatorSummary['active_percentage'], 1, ',', '.') . '% aktif',
                  'trend_type' => 'up',
                  'theme' => 'purple',
              ],
              [
                  'label' => 'Kategori Layanan',
                  'value' => $serviceCategoryTotal,
                  'suffix' => '',
                  'icon' => 'layers',
                  'description' =>
                      number_format($serviceCategoryActive, 0, ',', '.') .
                      ' kategori aktif dari ' .
                      number_format($serviceCategoryTotal, 0, ',', '.') .
                      ' kategori',
                  'trend' => number_format($serviceCategorySummary['active_percentage'], 1, ',', '.') . '% aktif',
                  'trend_type' => 'up',
                  'theme' => 'blue',
              ],
              [
                  'label' => 'Service Aktif',
                  'value' => $serviceActive,
                  'suffix' => '',
                  'icon' => 'briefcase',
                  'description' =>
                      number_format($serviceTotal, 0, ',', '.') .
                      ' service terdaftar dengan ' .
                      number_format($serviceInactive, 0, ',', '.') .
                      ' nonaktif',
                  'trend' => number_format($serviceSummary['active_percentage'], 1, ',', '.') . '% aktif',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Pengguna Terdaftar',
                  'value' => $totalUsers,
                  'suffix' => '',
                  'icon' => 'users',
                  'description' => $totalRoles . ' role terhubung ke sistem',
                  'trend' => 'Terkelola',
                  'trend_type' => 'up',
                  'theme' => 'indigo',
              ],
          ];

          /*
          |--------------------------------------------------------------------------
          | DATA PENDUKUNG DASHBOARD
          |--------------------------------------------------------------------------
          */

          if ($roleSummary->isEmpty()) {
              $roleSummary = collect([
                  ['name' => 'Super Admin', 'users' => 0, 'active' => 0, 'icon' => 'shield'],
                  ['name' => 'Direktur Utama', 'users' => 0, 'active' => 0, 'icon' => 'briefcase'],
                  ['name' => 'HRD Manager', 'users' => 0, 'active' => 0, 'icon' => 'users'],
              ]);
          }

          $systemActivities = collect(
              $systemActivities ?? [
                  [
                      'title' => 'Dashboard siap digunakan',
                      'description' => 'Data utama berhasil dimuat dari database.',
                      'time' => 'Baru saja',
                      'icon' => 'check-circle',
                      'theme' => 'green',
                  ],
                  [
                      'title' => 'Indikator kinerja terhubung',
                      'description' =>
                          number_format($indicatorTotal, 0, ',', '.') .
                          ' indikator tersedia pada modul performance indicators.',
                      'time' => 'Hari ini',
                      'icon' => 'target',
                      'theme' => 'purple',
                  ],
                  [
                      'title' => 'Periode penilaian terhubung',
                      'description' =>
                          number_format((int) $performancePeriodSummary['total'], 0, ',', '.') .
                          ' periode penilaian tersedia.',
                      'time' => 'Hari ini',
                      'icon' => 'calendar',
                      'theme' => 'blue',
                  ],
                  [
                      'title' => 'Kategori layanan terhubung',
                      'description' =>
                          number_format($serviceCategoryTotal, 0, ',', '.') .
                          ' kategori tersedia: ' .
                          number_format($serviceCategoryActive, 0, ',', '.') .
                          ' aktif dan ' .
                          number_format($serviceCategoryInactive, 0, ',', '.') .
                          ' tidak aktif.',
                      'time' => 'Hari ini',
                      'icon' => 'layers',
                      'theme' => 'blue',
                  ],
              ],
          );

          $quickActions = [
              [
                  'label' => 'Service Layanan',
                  'description' => 'Kode, harga, unit, durasi, dan status service',
                  'icon' => 'briefcase',
                  'url' => $servicesUrl ?? '#',
              ],
              [
                  'label' => 'Kategori Layanan',
                  'description' => 'Kode, nama, deskripsi, status, dan sampah data',
                  'icon' => 'layers',
                  'url' => $serviceCategoriesUrl,
              ],
              [
                  'label' => 'Indikator Kinerja',
                  'description' => 'Kode, bobot, arah target, dan status',
                  'icon' => 'target',
                  'url' => $performanceIndicatorsUrl,
              ],
              [
                  'label' => 'Periode Penilaian',
                  'description' => 'Atur periode dan status',
                  'icon' => 'calendar',
                  'url' => $performancePeriodsUrl,
              ],
              [
                  'label' => 'Kelola Pengguna',
                  'description' => 'Akun, role, dan status',
                  'icon' => 'users',
                  'url' => $usersUrl,
              ],
              [
                  'label' => 'Kelola Jabatan',
                  'description' => 'Struktur dan level jabatan',
                  'icon' => 'briefcase',
                  'url' => $positionsUrl,
              ],
          ];
     @endphp

     <style>
          .sad-dashboard {
               --sad-primary: #4f46e5;
               --sad-primary-dark: #312e81;
               --sad-primary-soft: rgba(79, 70, 229, 0.10);
               --sad-accent: #06b6d4;
               --sad-success: #16a34a;
               --sad-warning: #d97706;
               --sad-danger: #dc2626;
               --sad-info: #0284c7;
               --sad-purple: #7c3aed;
               --sad-heading: #101828;
               --sad-text: #475467;
               --sad-muted: #667085;
               --sad-border: #e4e7ec;
               --sad-background: #f6f8fc;
               --sad-card: #ffffff;
               --sad-card-soft: #f9fafb;
               --sad-shadow: 0 12px 34px rgba(16, 24, 40, 0.07);
               --sad-shadow-hover: 0 22px 52px rgba(16, 24, 40, 0.12);

               width: auto;
               min-height: 100vh;
               margin: -24px;
               padding: 30px clamp(20px, 3vw, 48px) 48px;
               overflow-x: hidden;
               color: var(--sad-text);
               background:
                    radial-gradient(circle at 96% 0%, rgba(79, 70, 229, 0.12), transparent 30%),
                    radial-gradient(circle at 0% 42%, rgba(6, 182, 212, 0.08), transparent 26%),
                    linear-gradient(180deg, #f8faff 0%, var(--sad-background) 38%, #f3f6fb 100%);
               font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
          }

          html[data-theme="dark"] .sad-dashboard,
          body.dark-theme .sad-dashboard,
          body.dark-mode .sad-dashboard {
               --sad-heading: #f8fafc;
               --sad-text: #cbd5e1;
               --sad-muted: #94a3b8;
               --sad-border: rgba(148, 163, 184, 0.17);
               --sad-background: #0b1220;
               --sad-card: #121c2d;
               --sad-card-soft: #172235;
               --sad-primary-soft: rgba(129, 140, 248, 0.14);
               --sad-shadow: 0 12px 34px rgba(0, 0, 0, 0.23);
               --sad-shadow-hover: 0 22px 52px rgba(0, 0, 0, 0.34);

               background:
                    radial-gradient(circle at 96% 0%, rgba(99, 102, 241, 0.18), transparent 30%),
                    radial-gradient(circle at 0% 42%, rgba(6, 182, 212, 0.10), transparent 26%),
                    var(--sad-background);
          }

          .sad-dashboard *,
          .sad-dashboard *::before,
          .sad-dashboard *::after {
               box-sizing: border-box;
          }

          .sad-dashboard a {
               text-decoration: none;
          }

          .sad-dashboard button,
          .sad-dashboard input {
               font: inherit;
          }

          /* HERO */
          .sad-hero {
               position: relative;
               isolation: isolate;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 34px;
               min-height: 300px;
               padding: clamp(34px, 4.5vw, 58px);
               margin-bottom: 22px;
               border: 1px solid rgba(255, 255, 255, 0.18);
               border-radius: 30px;
               background:
                    radial-gradient(circle at 82% 15%, rgba(255, 255, 255, 0.16), transparent 22%),
                    linear-gradient(118deg, #101828 0%, #312e81 48%, #0891b2 100%);
               box-shadow: 0 30px 72px rgba(49, 46, 129, 0.26);
          }

          .sad-hero::before {
               position: absolute;
               z-index: -1;
               top: -245px;
               right: -120px;
               width: 550px;
               height: 550px;
               border: 90px solid rgba(255, 255, 255, 0.065);
               border-radius: 50%;
               content: "";
          }

          .sad-hero::after {
               position: absolute;
               z-index: -1;
               right: 28%;
               bottom: -230px;
               width: 410px;
               height: 410px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.045);
               content: "";
          }

          .sad-hero-content {
               position: relative;
               z-index: 2;
               max-width: 900px;
          }

          .sad-role-badge {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               padding: 9px 14px;
               margin-bottom: 22px;
               color: #ffffff;
               font-size: 11px;
               font-weight: 850;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               border: 1px solid rgba(255, 255, 255, 0.24);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.12);
               backdrop-filter: blur(12px);
          }

          .sad-role-badge::before {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #86efac;
               box-shadow: 0 0 0 5px rgba(134, 239, 172, 0.17);
               content: "";
          }

          .sad-hero h1 {
               max-width: 850px;
               margin: 0 0 16px;
               color: #ffffff;
               font-size: clamp(34px, 4.2vw, 58px);
               font-weight: 850;
               letter-spacing: -0.045em;
               line-height: 1.04;
          }

          .sad-hero-description {
               max-width: 820px;
               margin: 0;
               color: rgba(255, 255, 255, 0.82);
               font-size: 15px;
               line-height: 1.78;
          }

          .sad-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 12px 22px;
               margin-top: 26px;
          }

          .sad-hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.86);
               font-size: 12px;
               font-weight: 700;
          }

          .sad-hero-meta-item svg {
               width: 16px;
               height: 16px;
          }

          .sad-hero-actions {
               position: relative;
               z-index: 2;
               display: flex;
               flex: 0 0 238px;
               flex-direction: column;
               gap: 12px;
          }

          .sad-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 50px;
               padding: 13px 18px;
               border: 0;
               border-radius: 14px;
               font-size: 12px;
               font-weight: 850;
               cursor: pointer;
               transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
          }

          .sad-button:hover {
               transform: translateY(-2px);
          }

          .sad-button svg {
               width: 17px;
               height: 17px;
          }

          .sad-button-primary {
               color: var(--sad-primary-dark);
               background: #ffffff;
               box-shadow: 0 14px 30px rgba(16, 24, 40, 0.24);
          }

          .sad-button-secondary {
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, 0.24);
               background: rgba(255, 255, 255, 0.11);
               backdrop-filter: blur(10px);
          }

          /* CURRENT PERIOD */
          .sad-period-overview {
               display: grid;
               grid-template-columns: minmax(0, 1.45fr) minmax(360px, 0.8fr) auto;
               gap: 22px;
               align-items: center;
               padding: 22px 24px;
               margin-bottom: 24px;
               border: 1px solid rgba(79, 70, 229, 0.15);
               border-radius: 22px;
               background:
                    linear-gradient(115deg, rgba(79, 70, 229, 0.07), rgba(6, 182, 212, 0.035) 52%, transparent),
                    var(--sad-card);
               box-shadow: var(--sad-shadow);
          }

          .sad-period-main {
               display: flex;
               gap: 15px;
               align-items: center;
               min-width: 0;
          }

          .sad-period-icon {
               display: grid;
               flex: 0 0 52px;
               width: 52px;
               height: 52px;
               place-items: center;
               color: #ffffff;
               border-radius: 16px;
               background: linear-gradient(135deg, var(--sad-primary), var(--sad-accent));
               box-shadow: 0 12px 24px rgba(79, 70, 229, 0.20);
          }

          .sad-period-icon svg {
               width: 22px;
               height: 22px;
          }

          .sad-period-kicker {
               display: block;
               margin-bottom: 4px;
               color: var(--sad-primary);
               font-size: 10px;
               font-weight: 850;
               letter-spacing: 0.08em;
               text-transform: uppercase;
          }

          .sad-period-title {
               display: block;
               overflow: hidden;
               margin-bottom: 5px;
               color: var(--sad-heading);
               font-size: 16px;
               font-weight: 850;
               text-overflow: ellipsis;
               white-space: nowrap;
          }

          .sad-period-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 7px 14px;
               color: var(--sad-muted);
               font-size: 11px;
          }

          .sad-period-summary {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 9px;
          }

          .sad-period-summary-item {
               padding: 10px 8px;
               text-align: center;
               border: 1px solid var(--sad-border);
               border-radius: 12px;
               background: rgba(255, 255, 255, 0.48);
          }

          html[data-theme="dark"] .sad-period-summary-item,
          body.dark-theme .sad-period-summary-item,
          body.dark-mode .sad-period-summary-item {
               background: rgba(255, 255, 255, 0.025);
          }

          .sad-period-summary-item strong {
               display: block;
               color: var(--sad-heading);
               font-size: 16px;
               font-weight: 850;
          }

          .sad-period-summary-item span {
               display: block;
               margin-top: 2px;
               color: var(--sad-muted);
               font-size: 8px;
               font-weight: 800;
               text-transform: uppercase;
          }

          .sad-period-link {
               display: inline-flex;
               min-height: 42px;
               padding: 0 16px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               color: #ffffff;
               font-size: 11px;
               font-weight: 850;
               white-space: nowrap;
               border-radius: 12px;
               background: linear-gradient(135deg, var(--sad-primary), #6366f1);
               box-shadow: 0 10px 22px rgba(79, 70, 229, 0.18);
          }

          .sad-period-link svg {
               width: 15px;
               height: 15px;
          }

          /* KPI */
          .sad-stat-grid {
               display: grid;
               grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
               gap: 20px;
               margin-bottom: 24px;
          }

          .sad-stat-card {
               position: relative;
               isolation: isolate;
               overflow: hidden;
               min-height: 190px;
               padding: 23px;
               border: 1px solid var(--sad-border);
               border-radius: 22px;
               background: var(--sad-card);
               box-shadow: var(--sad-shadow);
               transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
          }

          .sad-stat-card::before {
               position: absolute;
               z-index: -1;
               inset: 0 auto 0 0;
               width: 4px;
               border-radius: 22px 0 0 22px;
               background: linear-gradient(180deg, var(--sad-primary), #818cf8);
               content: "";
          }

          .sad-stat-card::after {
               position: absolute;
               z-index: -1;
               top: -42px;
               right: -42px;
               width: 128px;
               height: 128px;
               border-radius: 50%;
               background: rgba(79, 70, 229, 0.07);
               content: "";
          }

          .sad-stat-card.theme-green::before {
               background: linear-gradient(180deg, #16a34a, #4ade80);
          }

          .sad-stat-card.theme-green::after {
               background: rgba(22, 163, 74, 0.07);
          }

          .sad-stat-card.theme-orange::before {
               background: linear-gradient(180deg, #d97706, #fbbf24);
          }

          .sad-stat-card.theme-orange::after {
               background: rgba(217, 119, 6, 0.07);
          }

          .sad-stat-card.theme-blue::before {
               background: linear-gradient(180deg, #2563eb, #38bdf8);
          }

          .sad-stat-card.theme-blue::after {
               background: rgba(37, 99, 235, 0.07);
          }

          .sad-stat-card.theme-purple::before {
               background: linear-gradient(180deg, #7c3aed, #a78bfa);
          }

          .sad-stat-card.theme-purple::after {
               background: rgba(124, 58, 237, 0.07);
          }

          .sad-stat-card:hover {
               transform: translateY(-4px);
               border-color: rgba(79, 70, 229, 0.24);
               box-shadow: var(--sad-shadow-hover);
          }

          .sad-stat-top {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 14px;
          }

          .sad-stat-icon {
               display: grid;
               width: 51px;
               height: 51px;
               place-items: center;
               color: var(--sad-primary);
               border-radius: 16px;
               background: var(--sad-primary-soft);
          }

          .sad-stat-icon svg {
               width: 23px;
               height: 23px;
          }

          .theme-green .sad-stat-icon {
               color: #15803d;
               background: rgba(22, 163, 74, 0.11);
          }

          .theme-orange .sad-stat-icon {
               color: #b45309;
               background: rgba(217, 119, 6, 0.11);
          }

          .theme-blue .sad-stat-icon {
               color: #1d4ed8;
               background: rgba(37, 99, 235, 0.11);
          }

          .theme-purple .sad-stat-icon {
               color: #6d28d9;
               background: rgba(124, 58, 237, 0.11);
          }

          .sad-stat-trend {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               padding: 6px 9px;
               color: #15803d;
               font-size: 9px;
               font-weight: 850;
               border-radius: 999px;
               background: rgba(22, 163, 74, 0.10);
          }

          .sad-stat-trend svg {
               width: 12px;
               height: 12px;
          }

          .sad-stat-label {
               margin: 21px 0 7px;
               color: var(--sad-muted);
               font-size: 11px;
               font-weight: 850;
               letter-spacing: 0.02em;
          }

          .sad-stat-value {
               display: flex;
               align-items: baseline;
               gap: 2px;
               margin: 0;
               color: var(--sad-heading);
               font-size: clamp(30px, 3vw, 40px);
               font-weight: 850;
               letter-spacing: -0.04em;
               line-height: 1;
          }

          .sad-stat-description {
               margin: 11px 0 0;
               color: var(--sad-text);
               font-size: 11px;
               line-height: 1.58;
          }

          /* LAYOUT + CARDS */
          .sad-main-grid,
          .sad-secondary-grid,
          .sad-bottom-grid,
          .sad-footer-grid {
               display: grid;
               gap: 24px;
               margin-bottom: 24px;
          }

          .sad-main-grid {
               grid-template-columns: minmax(0, 1.7fr) minmax(350px, 0.72fr);
          }

          .sad-secondary-grid {
               grid-template-columns: minmax(330px, 0.72fr) minmax(0, 1.55fr);
          }

          .sad-bottom-grid {
               grid-template-columns: repeat(2, minmax(0, 1fr));
          }

          .sad-footer-grid {
               grid-template-columns: minmax(0, 1.15fr) minmax(350px, 0.85fr);
               margin-bottom: 0;
          }

          .sad-card {
               overflow: hidden;
               border: 1px solid var(--sad-border);
               border-radius: 24px;
               background: var(--sad-card);
               box-shadow: var(--sad-shadow);
               transition: box-shadow 0.24s ease, border-color 0.24s ease;
          }

          .sad-card:hover {
               border-color: rgba(79, 70, 229, 0.18);
               box-shadow: var(--sad-shadow-hover);
          }

          .sad-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               padding: 23px 25px 19px;
               border-bottom: 1px solid var(--sad-border);
               background:
                    linear-gradient(180deg, rgba(79, 70, 229, 0.024), transparent);
          }

          .sad-card-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .sad-card-heading-icon {
               display: grid;
               flex: 0 0 43px;
               width: 43px;
               height: 43px;
               place-items: center;
               color: var(--sad-primary);
               border-radius: 14px;
               background: var(--sad-primary-soft);
          }

          .sad-card-heading-icon svg {
               width: 19px;
               height: 19px;
          }

          .sad-card-title {
               margin: 0;
               color: var(--sad-heading);
               font-size: 16px;
               font-weight: 850;
               letter-spacing: -0.015em;
          }

          .sad-card-subtitle {
               max-width: 640px;
               margin: 5px 0 0;
               color: var(--sad-muted);
               font-size: 11px;
               line-height: 1.58;
          }

          .sad-card-action {
               display: inline-flex;
               min-height: 37px;
               padding: 8px 11px;
               gap: 6px;
               align-items: center;
               justify-content: center;
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 850;
               white-space: nowrap;
               border: 1px solid var(--sad-border);
               border-radius: 10px;
               background: var(--sad-card);
               cursor: pointer;
               transition: 0.2s ease;
          }

          .sad-card-action:hover {
               color: var(--sad-primary);
               border-color: rgba(79, 70, 229, 0.35);
               background: var(--sad-primary-soft);
          }

          .sad-card-action svg {
               width: 14px;
               height: 14px;
          }

          /* CHART */
          .sad-chart-body {
               padding: 25px 25px 27px;
          }

          .sad-chart-summary {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 26px;
          }

          .sad-chart-legends {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
          }

          .sad-chart-legend {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-chart-legend-dot {
               width: 10px;
               height: 10px;
               border-radius: 3px;
          }

          .sad-chart-legend-dot.target {
               background: rgba(79, 70, 229, 0.23);
          }

          .sad-chart-legend-dot.actual {
               background: linear-gradient(135deg, var(--sad-primary), #818cf8);
          }

          .sad-chart-rate {
               text-align: right;
          }

          .sad-chart-rate strong {
               display: block;
               color: var(--sad-heading);
               font-size: 23px;
               font-weight: 850;
          }

          .sad-chart-rate span {
               color: var(--sad-muted);
               font-size: 10px;
          }

          .sad-chart-area {
               display: grid;
               grid-template-columns: 34px minmax(0, 1fr);
               gap: 11px;
               height: 342px;
               overflow-x: auto;
               overflow-y: hidden;
               scrollbar-width: thin;
          }

          .sad-chart-y-axis {
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               height: 285px;
               padding-top: 2px;
               color: var(--sad-muted);
               font-size: 9px;
               text-align: right;
          }

          .sad-chart-content {
               position: relative;
               min-width: max(680px, calc(var(--sad-chart-columns, 1) * 82px));
               height: 330px;
          }

          .sad-chart-lines {
               position: absolute;
               inset: 0 0 45px;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               pointer-events: none;
          }

          .sad-chart-line {
               border-top: 1px dashed var(--sad-border);
          }

          .sad-chart-columns {
               position: absolute;
               inset: 0;
               display: grid;
               grid-template-columns:
                    repeat(var(--sad-chart-columns, 1), minmax(54px, 1fr));
               gap: 14px;
          }

          .sad-chart-column {
               display: flex;
               flex-direction: column;
               align-items: center;
          }

          .sad-chart-bars {
               display: flex;
               align-items: flex-end;
               justify-content: center;
               gap: 6px;
               width: 100%;
               height: 285px;
          }

          .sad-chart-bar {
               position: relative;
               width: min(22px, 40%);
               min-height: 4px;
               border-radius: 8px 8px 4px 4px;
               cursor: pointer;
               transition: transform 0.2s ease, filter 0.2s ease;
          }

          .sad-chart-bar:hover {
               z-index: 3;
               filter: brightness(0.96);
               transform: scaleX(1.08);
          }

          .sad-chart-bar.target {
               background: rgba(79, 70, 229, 0.22);
          }

          .sad-chart-bar.actual {
               background: linear-gradient(to top, var(--sad-primary-dark), #818cf8);
               box-shadow: 0 6px 13px rgba(79, 70, 229, 0.20);
          }

          .sad-chart-tooltip {
               position: absolute;
               z-index: 10;
               bottom: calc(100% + 8px);
               left: 50%;
               min-width: 88px;
               padding: 7px 9px;
               color: #ffffff;
               font-size: 9px;
               font-weight: 800;
               line-height: 1.45;
               text-align: center;
               white-space: nowrap;
               border-radius: 8px;
               background: #101828;
               opacity: 0;
               pointer-events: none;
               transform: translateX(-50%) translateY(3px);
               transition: 0.2s ease;
          }

          .sad-chart-bar:hover .sad-chart-tooltip {
               opacity: 1;
               transform: translateX(-50%) translateY(0);
          }

          .sad-chart-month {
               margin-top: 13px;
               color: var(--sad-muted);
               font-size: 10px;
               font-weight: 800;
          }

          /* INVOICE VS PAYMENT LINE CHART */
          .sad-line-segment-body {
               padding: 18px 20px;
          }

          .sad-line-segment-summary {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 10px;
               margin-bottom: 14px;
          }

          .sad-line-stat {
               padding: 12px;
               border: 1px solid var(--sad-border);
               border-radius: 12px;
               background: var(--sad-card-soft);
          }

          .sad-line-stat-label {
               display: block;
               margin-bottom: 6px;
               color: var(--sad-muted);
               font-size: 9px;
               font-weight: 850;
               text-transform: uppercase;
               letter-spacing: 0.06em;
          }

          .sad-line-stat-value {
               display: block;
               color: var(--sad-heading);
               font-size: 16px;
               font-weight: 850;
          }

          .sad-line-canvas-wrap {
               overflow-x: auto;
               padding-bottom: 4px;
          }

          .sad-line-canvas {
               min-width: 620px;
               width: 100%;
               height: auto;
               display: block;
          }

          .sad-line-grid {
               stroke: var(--sad-border);
               stroke-width: 1;
               stroke-dasharray: 4 6;
          }

          .sad-line-axis {
               stroke: var(--sad-border);
               stroke-width: 1;
          }

          .sad-line-axis-label {
               fill: var(--sad-muted);
               font-size: 9px;
               font-weight: 700;
          }

          .sad-line-series-invoice,
          .sad-line-series-payment {
               fill: none;
               stroke-width: 3;
               stroke-linejoin: round;
               stroke-linecap: round;
          }

          .sad-line-series-invoice {
               stroke: #4f46e5;
          }

          .sad-line-series-payment {
               stroke: #0ea5a4;
          }

          .sad-line-point-invoice,
          .sad-line-point-payment {
               stroke-width: 2;
               cursor: pointer;
               transition: transform 0.18s ease, r 0.18s ease;
          }

          .sad-line-point-invoice:hover,
          .sad-line-point-payment:hover,
          .sad-line-point-invoice.is-active,
          .sad-line-point-payment.is-active {
               r: 5;
               transform: scale(1.02);
          }

          .sad-line-point-invoice {
               fill: #ffffff;
               stroke: #4f46e5;
          }

          .sad-line-point-payment {
               fill: #ffffff;
               stroke: #0ea5a4;
          }

          .sad-line-legend {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
               margin-top: 10px;
          }

          .sad-line-legend-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-line-legend-swatch {
               width: 24px;
               height: 3px;
               border-radius: 999px;
          }

          .sad-line-legend-swatch.invoice {
               background: #4f46e5;
          }

          .sad-line-legend-swatch.payment {
               background: #0ea5a4;
          }

          .sad-line-click-result {
               margin-top: 11px;
               padding: 10px 12px;
               border: 1px dashed var(--sad-border);
               border-radius: 11px;
               background: var(--sad-card-soft);
          }

          .sad-line-click-title {
               margin: 0 0 5px;
               color: var(--sad-heading);
               font-size: 11px;
               font-weight: 850;
          }

          .sad-line-click-values {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 16px;
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 800;
          }

          /* BRANCH HEALTH */
          .sad-satisfaction-body {
               padding: 25px;
          }

          .sad-score-summary {
               display: flex;
               align-items: center;
               gap: 23px;
               padding-bottom: 24px;
               margin-bottom: 23px;
               border-bottom: 1px solid var(--sad-border);
          }

          .sad-score-ring {
               position: relative;
               display: grid;
               flex: 0 0 132px;
               width: 132px;
               height: 132px;
               place-items: center;
               border-radius: 50%;
               box-shadow: 0 10px 28px rgba(16, 24, 40, 0.08);
          }

          .sad-score-ring::before {
               position: absolute;
               width: 96px;
               height: 96px;
               border-radius: 50%;
               background: var(--sad-card);
               content: "";
          }

          .sad-score-ring-value {
               position: relative;
               z-index: 2;
               color: var(--sad-heading);
               font-size: 25px;
               font-weight: 850;
          }

          .sad-score-details h3 {
               margin: 0 0 6px;
               color: var(--sad-heading);
               font-size: 16px;
               font-weight: 850;
          }

          .sad-score-details p {
               margin: 0 0 11px;
               color: var(--sad-muted);
               font-size: 11px;
               line-height: 1.58;
          }

          .sad-score-status {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 9px;
               color: #15803d;
               font-size: 9px;
               font-weight: 850;
               border-radius: 999px;
               background: rgba(22, 163, 74, 0.10);
          }

          .sad-score-status::before {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: #22c55e;
               content: "";
          }

          .sad-sentiment-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 9px;
               margin-bottom: 22px;
          }

          .sad-sentiment-card {
               padding: 13px 9px;
               text-align: center;
               border: 1px solid var(--sad-border);
               border-radius: 12px;
               background: var(--sad-card-soft);
          }

          .sad-sentiment-card strong {
               display: block;
               margin-bottom: 4px;
               color: var(--sad-heading);
               font-size: 18px;
               font-weight: 850;
          }

          .sad-sentiment-card span {
               color: var(--sad-muted);
               font-size: 8px;
               font-weight: 850;
               text-transform: uppercase;
          }

          .sad-health-list {
               display: flex;
               flex-direction: column;
               gap: 16px;
          }

          .sad-health-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 8px;
          }

          .sad-health-label,
          .sad-health-value {
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 850;
          }

          .sad-health-value {
               color: var(--sad-heading);
          }

          .sad-progress {
               overflow: hidden;
               width: 100%;
               height: 8px;
               border-radius: 999px;
               background: var(--sad-border);
          }

          .sad-progress-bar {
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, var(--sad-primary-dark), #818cf8);
          }

          .sad-progress-bar.success {
               background: linear-gradient(90deg, #15803d, #4ade80);
          }

          .sad-progress-bar.info {
               background: linear-gradient(90deg, #1d4ed8, #60a5fa);
          }

          .sad-progress-bar.warning {
               background: linear-gradient(90deg, #b45309, #fbbf24);
          }

          .sad-progress-bar.danger {
               background: linear-gradient(90deg, #b91c1c, #f87171);
          }

          .sad-progress-bar.primary {
               background: linear-gradient(90deg, #3730a3, #818cf8);
          }

          /* BADGES */
          .sad-badge {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 5px;
               padding: 6px 9px;
               font-size: 9px;
               font-weight: 850;
               white-space: nowrap;
               border-radius: 999px;
          }

          .sad-badge::before {
               width: 5px;
               height: 5px;
               border-radius: 50%;
               background: currentColor;
               content: "";
          }

          .sad-badge.success {
               color: #15803d;
               background: rgba(22, 163, 74, 0.10);
          }

          .sad-badge.info {
               color: #1d4ed8;
               background: rgba(37, 99, 235, 0.10);
          }

          .sad-badge.warning {
               color: #b45309;
               background: rgba(217, 119, 6, 0.10);
          }

          .sad-badge.danger {
               color: #b91c1c;
               background: rgba(220, 38, 38, 0.10);
          }

          .sad-badge.neutral {
               color: #64748b;
               background: rgba(100, 116, 139, 0.12);
          }

          /* PRIORITIES */
          .sad-priority-list {
               display: flex;
               flex-direction: column;
          }

          .sad-priority-item {
               display: flex;
               gap: 14px;
               padding: 19px 22px;
               border-bottom: 1px solid var(--sad-border);
               transition: background 0.2s ease;
          }

          .sad-priority-item:last-child {
               border-bottom: 0;
          }

          .sad-priority-item:hover {
               background: rgba(79, 70, 229, 0.035);
          }

          .sad-priority-icon {
               display: grid;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               place-items: center;
               color: var(--sad-primary);
               border-radius: 13px;
               background: var(--sad-primary-soft);
          }

          .sad-priority-icon svg {
               width: 18px;
               height: 18px;
          }

          .sad-priority-content {
               min-width: 0;
               flex: 1;
          }

          .sad-priority-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 10px;
          }

          .sad-priority-title {
               margin: 1px 0 0;
               color: var(--sad-heading);
               font-size: 12px;
               font-weight: 850;
          }

          .sad-priority-description {
               margin: 7px 0 11px;
               color: var(--sad-muted);
               font-size: 10px;
               line-height: 1.62;
          }

          .sad-priority-action {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               color: var(--sad-primary);
               font-size: 10px;
               font-weight: 850;
          }

          .sad-priority-action svg {
               width: 12px;
               height: 12px;
          }

          /* TABLE */
          .sad-monitoring-card {
               overflow: hidden;
          }

          .sad-table-toolbar {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               padding: 15px 21px;
               border-bottom: 1px solid var(--sad-border);
               background: var(--sad-card-soft);
          }

          .sad-filter-list {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .sad-filter-button {
               min-height: 33px;
               padding: 7px 11px;
               color: var(--sad-muted);
               font-size: 9px;
               font-weight: 850;
               border: 1px solid var(--sad-border);
               border-radius: 9px;
               background: var(--sad-card);
               cursor: pointer;
               transition: 0.2s ease;
          }

          .sad-filter-button:hover,
          .sad-filter-button.active {
               color: #ffffff;
               border-color: var(--sad-primary);
               background: linear-gradient(135deg, var(--sad-primary), #6366f1);
               box-shadow: 0 7px 16px rgba(79, 70, 229, 0.18);
          }

          .sad-search {
               position: relative;
               width: min(100%, 290px);
          }

          .sad-search svg {
               position: absolute;
               top: 50%;
               left: 12px;
               width: 14px;
               height: 14px;
               color: var(--sad-muted);
               transform: translateY(-50%);
          }

          .sad-search input {
               width: 100%;
               height: 39px;
               padding: 8px 12px 8px 35px;
               color: var(--sad-heading);
               font-size: 10px;
               outline: none;
               border: 1px solid var(--sad-border);
               border-radius: 10px;
               background: var(--sad-card);
          }

          .sad-search input:focus {
               border-color: rgba(79, 70, 229, 0.50);
               box-shadow: 0 0 0 4px var(--sad-primary-soft);
          }

          .sad-table-wrapper {
               overflow-x: auto;
          }

          .sad-table {
               width: 100%;
               min-width: 1010px;
               border-collapse: collapse;
          }

          .sad-table th {
               position: sticky;
               top: 0;
               z-index: 1;
               padding: 13px 16px;
               color: var(--sad-muted);
               font-size: 9px;
               font-weight: 850;
               letter-spacing: 0.05em;
               text-align: left;
               text-transform: uppercase;
               border-bottom: 1px solid var(--sad-border);
               background: var(--sad-card-soft);
          }

          .sad-table td {
               padding: 16px;
               color: var(--sad-text);
               font-size: 10px;
               vertical-align: middle;
               border-bottom: 1px solid var(--sad-border);
          }

          .sad-table tbody tr {
               transition: background 0.2s ease;
          }

          .sad-table tbody tr:hover {
               background: rgba(79, 70, 229, 0.028);
          }

          .sad-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .sad-unit-cell {
               display: flex;
               align-items: center;
               gap: 11px;
               min-width: 225px;
          }

          .sad-unit-icon {
               display: grid;
               flex: 0 0 40px;
               width: 40px;
               height: 40px;
               place-items: center;
               color: var(--sad-primary);
               border-radius: 12px;
               background: var(--sad-primary-soft);
          }

          .sad-unit-icon svg {
               width: 16px;
               height: 16px;
          }

          .sad-unit-name {
               display: block;
               margin-bottom: 3px;
               color: var(--sad-heading);
               font-size: 11px;
               font-weight: 850;
          }

          .sad-unit-code {
               color: var(--sad-muted);
               font-size: 9px;
          }

          .sad-leader {
               display: block;
               color: var(--sad-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-updated,
          .sad-description {
               display: block;
               margin-top: 3px;
               color: var(--sad-muted);
               font-size: 9px;
               line-height: 1.5;
          }

          .sad-row-actions {
               display: flex;
               align-items: center;
               gap: 6px;
          }

          .sad-action-menu {
               display: grid;
               width: 34px;
               height: 34px;
               place-items: center;
               color: var(--sad-muted);
               border: 1px solid var(--sad-border);
               border-radius: 10px;
               background: var(--sad-card);
               transition: 0.2s ease;
          }

          .sad-action-menu:hover {
               color: #ffffff;
               border-color: var(--sad-primary);
               background: var(--sad-primary);
               transform: translateY(-1px);
          }

          .sad-action-menu svg {
               width: 14px;
               height: 14px;
          }

          .sad-empty-state {
               display: none;
               padding: 38px 20px;
               text-align: center;
          }

          .sad-empty-state.is-visible {
               display: block;
          }

          .sad-empty-state svg {
               width: 34px;
               height: 34px;
               margin-bottom: 10px;
               color: var(--sad-muted);
          }

          .sad-empty-state h4 {
               margin: 0 0 5px;
               color: var(--sad-heading);
               font-size: 13px;
          }

          .sad-empty-state p {
               margin: 0;
               color: var(--sad-muted);
               font-size: 10px;
          }

          .sad-empty-state strong {
               display: block;
               margin-bottom: 6px;
               color: var(--sad-heading);
               font-size: 13px;
          }

          .sad-empty-state span {
               display: block;
               color: var(--sad-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .sad-health-label {
               display: inline-flex;
               align-items: center;
               gap: 7px;
          }

          .sad-health-label svg {
               width: 14px;
               height: 14px;
          }

          /* LISTS */
          .sad-channel-list,
          .sad-role-list,
          .sad-activity-list {
               padding: 4px 23px 10px;
          }

          .sad-channel-item,
          .sad-role-item {
               padding: 17px 0;
               border-bottom: 1px solid var(--sad-border);
          }

          .sad-channel-item:last-child,
          .sad-role-item:last-child {
               border-bottom: 0;
          }

          .sad-channel-header,
          .sad-role-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 10px;
          }

          .sad-channel-identity,
          .sad-role-identity {
               display: flex;
               align-items: center;
               gap: 10px;
          }

          .sad-channel-icon,
          .sad-role-icon {
               display: grid;
               flex: 0 0 38px;
               width: 38px;
               height: 38px;
               place-items: center;
               color: var(--sad-primary);
               border-radius: 12px;
               background: var(--sad-primary-soft);
          }

          .sad-channel-icon svg,
          .sad-role-icon svg {
               width: 15px;
               height: 15px;
          }

          .sad-channel-name,
          .sad-role-name {
               display: block;
               color: var(--sad-heading);
               font-size: 11px;
               font-weight: 850;
          }

          .sad-channel-meta,
          .sad-role-meta {
               display: block;
               margin-top: 3px;
               color: var(--sad-muted);
               font-size: 9px;
          }

          .sad-channel-score,
          .sad-role-count {
               color: var(--sad-heading);
               font-size: 14px;
               font-weight: 850;
          }

          .sad-role-count small {
               display: block;
               margin-top: 2px;
               color: var(--sad-muted);
               font-size: 7px;
               font-weight: 700;
               text-align: right;
          }

          /* ACTIVITY */
          .sad-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 18px 0;
          }

          .sad-activity-item:not(:last-child)::before {
               position: absolute;
               top: 55px;
               bottom: -3px;
               left: 19px;
               width: 1px;
               background: var(--sad-border);
               content: "";
          }

          .sad-activity-icon {
               position: relative;
               z-index: 2;
               display: grid;
               flex: 0 0 40px;
               width: 40px;
               height: 40px;
               place-items: center;
               border: 4px solid var(--sad-card);
               border-radius: 50%;
          }

          .sad-activity-icon svg {
               width: 14px;
               height: 14px;
          }

          .sad-activity-icon.green {
               color: #16a34a;
               background: rgba(22, 163, 74, 0.12);
          }

          .sad-activity-icon.blue {
               color: #2563eb;
               background: rgba(37, 99, 235, 0.12);
          }

          .sad-activity-icon.red {
               color: #dc2626;
               background: rgba(220, 38, 38, 0.12);
          }

          .sad-activity-icon.orange {
               color: #d97706;
               background: rgba(217, 119, 6, 0.12);
          }

          .sad-activity-icon.purple {
               color: #7c3aed;
               background: rgba(124, 58, 237, 0.12);
          }

          .sad-activity-content {
               min-width: 0;
               flex: 1;
          }

          .sad-activity-content h4 {
               margin: 1px 0 5px;
               color: var(--sad-heading);
               font-size: 11px;
               font-weight: 850;
          }

          .sad-activity-content p {
               margin: 0;
               color: var(--sad-muted);
               font-size: 10px;
               line-height: 1.58;
          }

          .sad-activity-time {
               margin-top: 6px;
               color: var(--sad-primary);
               font-size: 9px;
               font-weight: 850;
          }

          /* QUICK ACTION */
          .sad-quick-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 13px;
               padding: 22px;
          }

          .sad-quick-action {
               display: flex;
               align-items: center;
               gap: 11px;
               min-height: 90px;
               padding: 15px;
               color: var(--sad-text);
               border: 1px solid var(--sad-border);
               border-radius: 15px;
               background:
                    linear-gradient(145deg, rgba(79, 70, 229, 0.04), transparent 72%);
               transition: 0.2s ease;
          }

          .sad-quick-action:hover {
               color: var(--sad-text);
               border-color: rgba(79, 70, 229, 0.38);
               background: var(--sad-primary-soft);
               transform: translateY(-2px);
          }

          .sad-quick-icon {
               display: grid;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               place-items: center;
               color: var(--sad-primary);
               border-radius: 13px;
               background: var(--sad-primary-soft);
          }

          .sad-quick-icon svg {
               width: 17px;
               height: 17px;
          }

          .sad-quick-action strong {
               display: block;
               color: var(--sad-heading);
               font-size: 11px;
               font-weight: 850;
          }

          .sad-quick-action span span {
               display: block;
               margin-top: 4px;
               color: var(--sad-muted);
               font-size: 9px;
          }

          /* RESPONSIVE */
          @media (max-width: 1260px) {
               .sad-period-overview {
                    grid-template-columns: 1fr auto;
               }

               .sad-period-summary {
                    grid-column: 1 / -1;
                    grid-row: 2;
               }

               .sad-stat-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .sad-main-grid,
               .sad-secondary-grid,
               .sad-footer-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 991px) {
               .sad-dashboard {
                    margin: -20px;
                    padding: 24px;
               }

               .sad-hero {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .sad-hero-actions {
                    flex: none;
                    flex-direction: row;
                    width: 100%;
               }

               .sad-hero-actions .sad-button {
                    flex: 1;
               }

               .sad-bottom-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767px) {
               .sad-dashboard {
                    margin: -15px;
                    padding: 16px 14px 34px;
               }

               .sad-hero {
                    padding: 28px 22px;
                    border-radius: 24px;
               }

               .sad-hero h1 {
                    font-size: clamp(31px, 10vw, 43px);
               }

               .sad-hero-description {
                    font-size: 13px;
               }

               .sad-period-overview {
                    grid-template-columns: 1fr;
                    padding: 20px;
               }

               .sad-period-summary {
                    grid-column: auto;
                    grid-row: auto;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .sad-period-link {
                    width: 100%;
               }

               .sad-stat-grid {
                    grid-template-columns: 1fr;
                    gap: 14px;
               }

               .sad-stat-card {
                    min-height: auto;
               }

               .sad-card-header,
               .sad-table-toolbar,
               .sad-chart-summary {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .sad-card-action,
               .sad-search {
                    width: 100%;
               }

               .sad-chart-rate {
                    text-align: left;
               }

               .sad-score-summary {
                    align-items: center;
                    flex-direction: column;
                    text-align: center;
               }

               .sad-line-segment-summary {
                    grid-template-columns: 1fr;
               }

               .sad-line-click-values {
                    display: grid;
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 520px) {
               .sad-hero-actions {
                    flex-direction: column;
               }

               .sad-chart-area {
                    grid-template-columns: 27px minmax(0, 1fr);
               }

               .sad-chart-columns {
                    gap: 5px;
               }

               .sad-chart-bars {
                    gap: 3px;
               }

               .sad-sentiment-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
               }

               .sad-quick-grid {
                    grid-template-columns: 1fr;
               }
          }

          /* ======================================================================
                                           HERO BRIGHTNESS PATCH
                                           Mempertahankan struktur template yang ada dan hanya memperbaiki
                                           warna hero agar lebih terang, kontras, dan mudah dibaca.
                                           ====================================================================== */
          .sad-dashboard .sad-hero {
               border-color: rgba(255, 255, 255, 0.34);
               background:
                    radial-gradient(circle at 86% 14%,
                         rgba(255, 255, 255, 0.30),
                         transparent 25%),
                    radial-gradient(circle at 55% 110%,
                         rgba(255, 255, 255, 0.13),
                         transparent 35%),
                    linear-gradient(112deg,
                         #4f46e5 0%,
                         #2563eb 46%,
                         #06b6d4 100%) !important;
               box-shadow:
                    0 28px 65px rgba(37, 99, 235, 0.26),
                    inset 0 1px 0 rgba(255, 255, 255, 0.22);
          }

          .sad-dashboard .sad-hero::before {
               border-color: rgba(255, 255, 255, 0.12);
          }

          .sad-dashboard .sad-hero::after {
               background: rgba(255, 255, 255, 0.10);
          }

          .sad-dashboard .sad-hero h1 {
               color: #ffffff !important;
               -webkit-text-fill-color: #ffffff !important;
               text-shadow: 0 3px 16px rgba(30, 58, 138, 0.28);
          }

          .sad-dashboard .sad-hero-description {
               color: rgba(255, 255, 255, 0.96) !important;
               -webkit-text-fill-color: rgba(255, 255, 255, 0.96) !important;
               text-shadow: 0 2px 9px rgba(30, 58, 138, 0.20);
          }

          .sad-dashboard .sad-hero-meta-item {
               color: rgba(255, 255, 255, 0.96) !important;
               -webkit-text-fill-color: rgba(255, 255, 255, 0.96) !important;
          }

          .sad-dashboard .sad-hero-meta-item svg {
               color: #dbeafe !important;
               stroke: currentColor !important;
          }

          .sad-dashboard .sad-role-badge {
               color: #ffffff !important;
               -webkit-text-fill-color: #ffffff !important;
               border-color: rgba(255, 255, 255, 0.58);
               background: rgba(255, 255, 255, 0.20);
               box-shadow: 0 8px 22px rgba(30, 58, 138, 0.16);
          }

          .sad-dashboard .sad-button-primary {
               color: #1e3a8a !important;
               -webkit-text-fill-color: #1e3a8a !important;
               background: #ffffff !important;
               border: 1px solid rgba(255, 255, 255, 0.82);
               box-shadow: 0 14px 30px rgba(30, 58, 138, 0.20);
          }

          .sad-dashboard .sad-button-primary svg {
               color: #1e3a8a !important;
               stroke: currentColor !important;
          }

          .sad-dashboard .sad-button-secondary {
               color: #ffffff !important;
               -webkit-text-fill-color: #ffffff !important;
               border-color: rgba(255, 255, 255, 0.70);
               background: rgba(255, 255, 255, 0.18) !important;
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.14);
          }

          .sad-dashboard .sad-button-secondary:hover {
               background: rgba(255, 255, 255, 0.28) !important;
          }

          .sad-dashboard .sad-button-secondary svg {
               color: #ffffff !important;
               stroke: currentColor !important;
          }

          html[data-theme="dark"] .sad-dashboard .sad-hero,
          body.dark-theme .sad-dashboard .sad-hero,
          body.dark-mode .sad-dashboard .sad-hero {
               background:
                    radial-gradient(circle at 86% 14%,
                         rgba(255, 255, 255, 0.22),
                         transparent 25%),
                    linear-gradient(112deg,
                         #4338ca 0%,
                         #2563eb 48%,
                         #0891b2 100%) !important;
          }
     </style>

     <div class="sad-dashboard">
          <section class="sad-hero">
               <div class="sad-hero-content">
                    <div class="sad-role-badge">{{ $currentUserRole }}</div>

                    <h1>Pusat Kendali Super Admin</h1>

                    <p class="sad-hero-description">
                         Selamat datang, {{ $currentUserName }}. Pantau struktur organisasi,
                         kategori layanan, periode penilaian, status cabang, pengguna, dan aktivitas sistem
                         melalui dashboard eksekutif yang terpusat.
                    </p>

                    <div class="sad-hero-meta">
                         <span class="sad-hero-meta-item">
                              <i data-feather="calendar"></i>
                              {{ now()->translatedFormat('l, d F Y') }}
                         </span>

                         <span class="sad-hero-meta-item">
                              <i data-feather="clock"></i>
                              <span id="sadLiveClock">{{ now()->format('H:i:s') }} WIB</span>
                         </span>

                         <span class="sad-hero-meta-item">
                              <i data-feather="database"></i>
                              Sinkronisasi data aktif
                         </span>

                         <span class="sad-hero-meta-item">
                              <i data-feather="shield"></i>
                              Akses administrator tertinggi
                         </span>
                    </div>
               </div>

               <div class="sad-hero-actions">
                    <a href="{{ $usersUrl }}" class="sad-button sad-button-primary">
                         <i data-feather="users"></i>
                         Kelola Pengguna
                    </a>

                    <a href="{{ $serviceCategoriesUrl }}" class="sad-button sad-button-secondary">
                         <i data-feather="layers"></i>
                         Kategori Layanan
                    </a>
               </div>
          </section>

          <section class="sad-period-overview">
               <div class="sad-period-main">
                    <span class="sad-period-icon">
                         <i data-feather="calendar"></i>
                    </span>

                    <div>
                         <span class="sad-period-kicker">
                              Periode Penilaian Saat Ini
                         </span>

                         @if ($currentPerformancePeriod)
                              @php
                                   $currentPeriodName = (string) data_get(
                                       $currentPerformancePeriod,
                                       'name',
                                       'Periode Aktif',
                                   );

                                   $currentPeriodType = (string) data_get(
                                       $currentPerformancePeriod,
                                       'period_type',
                                       '-',
                                   );

                                   $currentStartDate = null;
                                   $currentEndDate = null;

                                   try {
                                       $currentStartValue = data_get($currentPerformancePeriod, 'start_date');

                                       if ($currentStartValue) {
                                           $currentStartDate =
                                               $currentStartValue instanceof \Illuminate\Support\Carbon
                                                   ? $currentStartValue
                                                   : \Illuminate\Support\Carbon::parse($currentStartValue);
                                       }
                                   } catch (\Throwable) {
                                       $currentStartDate = null;
                                   }

                                   try {
                                       $currentEndValue = data_get($currentPerformancePeriod, 'end_date');

                                       if ($currentEndValue) {
                                           $currentEndDate =
                                               $currentEndValue instanceof \Illuminate\Support\Carbon
                                                   ? $currentEndValue
                                                   : \Illuminate\Support\Carbon::parse($currentEndValue);
                                       }
                                   } catch (\Throwable) {
                                       $currentEndDate = null;
                                   }
                              @endphp

                              <strong class="sad-period-title">
                                   {{ $currentPeriodName }}
                              </strong>

                              <span class="sad-period-meta">
                                   <span>
                                        {{ $currentStartDate?->format('d M Y') ?? '-' }}
                                        –
                                        {{ $currentEndDate?->format('d M Y') ?? '-' }}
                                   </span>

                                   <span>
                                        {{ \Illuminate\Support\Str::of($currentPeriodType)->replace('_', ' ')->title() }}
                                   </span>

                                   <span class="sad-badge success">Aktif</span>
                              </span>
                         @else
                              <strong class="sad-period-title">
                                   Belum ada periode aktif
                              </strong>

                              <span class="sad-period-meta">
                                   Buat atau aktifkan periode penilaian untuk memulai monitoring.
                              </span>
                         @endif
                    </div>
               </div>

               <div class="sad-period-summary">
                    <div class="sad-period-summary-item">
                         <strong>{{ $performancePeriodSummary['total'] }}</strong>
                         <span>Total</span>
                    </div>

                    <div class="sad-period-summary-item">
                         <strong>{{ $performancePeriodSummary['active'] }}</strong>
                         <span>Aktif</span>
                    </div>

                    <div class="sad-period-summary-item">
                         <strong>{{ $performancePeriodSummary['draft'] }}</strong>
                         <span>Draft</span>
                    </div>

                    <div class="sad-period-summary-item">
                         <strong>{{ $performancePeriodSummary['completed'] }}</strong>
                         <span>Selesai</span>
                    </div>
               </div>

               <a href="{{ $performancePeriodsUrl }}" class="sad-period-link">
                    Kelola Periode
                    <i data-feather="arrow-right"></i>
               </a>
          </section>

          <section class="sad-stat-grid">
               @foreach ($dashboardStatistics as $statistic)
                    <article class="sad-stat-card theme-{{ $statistic['theme'] }}">
                         <div class="sad-stat-top">
                              <span class="sad-stat-icon">
                                   <i data-feather="{{ $statistic['icon'] }}"></i>
                              </span>

                              <span class="sad-stat-trend {{ $statistic['trend_type'] }}">
                                   <i
                                        data-feather="{{ $statistic['trend_type'] === 'up' ? 'trending-up' : 'trending-down' }}"></i>
                                   {{ $statistic['trend'] }}
                              </span>
                         </div>

                         <p class="sad-stat-label">{{ $statistic['label'] }}</p>

                         <h2 class="sad-stat-value">
                              <span>{{ is_float($statistic['value']) ? number_format($statistic['value'], 1, ',', '.') : number_format($statistic['value'], 0, ',', '.') }}</span>
                              <span>{{ $statistic['suffix'] }}</span>
                         </h2>

                         <p class="sad-stat-description">{{ $statistic['description'] }}</p>
                    </article>
               @endforeach
          </section>

          @php
               $lineLabels = collect($invoicePaymentLineChart['labels'] ?? [])->values();
               $lineInvoiceTotals = collect($invoicePaymentLineChart['invoice_totals'] ?? [])
                   ->map(fn($value) => (float) $value)
                   ->values();
               $linePaymentTotals = collect($invoicePaymentLineChart['payment_totals'] ?? [])
                   ->map(fn($value) => (float) $value)
                   ->values();
               $lineInvoiceCounts = collect($invoicePaymentLineChart['invoice_counts'] ?? [])
                   ->map(fn($value) => (int) $value)
                   ->values();
               $linePaymentCounts = collect($invoicePaymentLineChart['payment_counts'] ?? [])
                   ->map(fn($value) => (int) $value)
                   ->values();

               $lineCount = max(1, $lineLabels->count());
               $svgWidth = 860;
               $svgHeight = 250;
               $paddingLeft = 52;
               $paddingTop = 18;
               $paddingRight = 24;
               $paddingBottom = 42;
               $plotWidth = max(1, $svgWidth - $paddingLeft - $paddingRight);
               $plotHeight = max(1, $svgHeight - $paddingTop - $paddingBottom);
               $xStep = $lineCount > 1 ? $plotWidth / ($lineCount - 1) : 0;

               $lineMaxAmount = max(1.0, (float) ($invoicePaymentLineChart['max_amount'] ?? 1));
               $lineInvoiceSum = (float) ($invoicePaymentLineChart['invoice_sum'] ?? 0);
               $linePaymentSum = (float) ($invoicePaymentLineChart['payment_sum'] ?? 0);
               $lineGrandSum = $lineInvoiceSum + $linePaymentSum;

               $buildPoint = static function (float $value, int $index) use (
                   $paddingLeft,
                   $paddingTop,
                   $plotHeight,
                   $lineMaxAmount,
                   $xStep,
               ): array {
                   $safeValue = max(0.0, $value);
                   $x = $paddingLeft + $index * $xStep;
                   $y = $paddingTop + $plotHeight * (1 - min(1, $safeValue / $lineMaxAmount));

                   return [
                       'x' => round($x, 2),
                       'y' => round($y, 2),
                   ];
               };

               $lineInvoicePoints = $lineInvoiceTotals
                   ->map(fn(float $value, int $index) => $buildPoint($value, $index))
                   ->values();

               $linePaymentPoints = $linePaymentTotals
                   ->map(fn(float $value, int $index) => $buildPoint($value, $index))
                   ->values();

               $lineInvoicePolyline = $lineInvoicePoints
                   ->map(fn(array $point): string => $point['x'] . ',' . $point['y'])
                   ->implode(' ');

               $linePaymentPolyline = $linePaymentPoints
                   ->map(fn(array $point): string => $point['x'] . ',' . $point['y'])
                   ->implode(' ');

               $lineGridTicks = collect([0, 25, 50, 75, 100])
                   ->map(function (int $percent) use ($paddingTop, $plotHeight, $lineMaxAmount): array {
                       $y = $paddingTop + $plotHeight * (1 - $percent / 100);

                       return [
                           'y' => round($y, 2),
                           'label' => 'Rp ' . number_format(($lineMaxAmount * $percent) / 100, 0, ',', '.'),
                       ];
                   })
                   ->values();
          @endphp

          <section class="sad-card" style="margin-bottom: 24px;">
               <header class="sad-card-header">
                    <div class="sad-card-heading">
                         <span class="sad-card-heading-icon">
                              <i data-feather="activity"></i>
                         </span>
                         <div>
                              <h2 class="sad-card-title">Line Segment Invoices vs Payments</h2>
                              <p class="sad-card-subtitle">Tren nominal bulanan untuk invoice dan payment dalam
                                   {{ $lineLabels->count() }} bulan terakhir.</p>
                         </div>
                    </div>
               </header>

               <div class="sad-line-segment-body">
                    <div class="sad-line-segment-summary">
                         <div class="sad-line-stat">
                              <span class="sad-line-stat-label">Total Invoice</span>
                              <span class="sad-line-stat-value">Rp
                                   {{ number_format($lineInvoiceSum, 0, ',', '.') }}</span>
                         </div>
                         <div class="sad-line-stat">
                              <span class="sad-line-stat-label">Total Payment</span>
                              <span class="sad-line-stat-value">Rp
                                   {{ number_format($linePaymentSum, 0, ',', '.') }}</span>
                         </div>
                         <div class="sad-line-stat">
                              <span class="sad-line-stat-label">Total Keseluruhan</span>
                              <span class="sad-line-stat-value">Rp {{ number_format($lineGrandSum, 0, ',', '.') }}</span>
                         </div>
                    </div>

                    @if ($lineLabels->isNotEmpty())
                         <div class="sad-line-canvas-wrap">
                              <svg class="sad-line-canvas" viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}"
                                   role="img" aria-label="Grafik line segment invoice dan payment per bulan">
                                   <line class="sad-line-axis" x1="{{ $paddingLeft }}"
                                        y1="{{ $paddingTop + $plotHeight }}" x2="{{ $svgWidth - $paddingRight }}"
                                        y2="{{ $paddingTop + $plotHeight }}"></line>

                                   @foreach ($lineGridTicks as $tick)
                                        <line class="sad-line-grid" x1="{{ $paddingLeft }}" y1="{{ $tick['y'] }}"
                                             x2="{{ $svgWidth - $paddingRight }}" y2="{{ $tick['y'] }}"></line>
                                        <text class="sad-line-axis-label" x="8"
                                             y="{{ $tick['y'] + 3 }}">{{ $tick['label'] }}</text>
                                   @endforeach

                                   @if ($lineInvoicePolyline !== '')
                                        <polyline class="sad-line-series-invoice" points="{{ $lineInvoicePolyline }}">
                                        </polyline>
                                   @endif

                                   @if ($linePaymentPolyline !== '')
                                        <polyline class="sad-line-series-payment" points="{{ $linePaymentPolyline }}">
                                        </polyline>
                                   @endif

                                   @foreach ($lineLabels as $index => $label)
                                        @php
                                             $pointX =
                                                 (float) ($lineInvoicePoints[$index]['x'] ??
                                                     $paddingLeft + $index * $xStep);
                                             $invoicePoint = $lineInvoicePoints[$index] ?? [
                                                 'x' => $pointX,
                                                 'y' => $paddingTop + $plotHeight,
                                             ];
                                             $paymentPoint = $linePaymentPoints[$index] ?? [
                                                 'x' => $pointX,
                                                 'y' => $paddingTop + $plotHeight,
                                             ];
                                        @endphp

                                        <text class="sad-line-axis-label" x="{{ $pointX }}" y="{{ $svgHeight - 16 }}"
                                             text-anchor="middle">{{ $label }}</text>

                                        <circle class="sad-line-point-invoice" cx="{{ $invoicePoint['x'] }}"
                                             cy="{{ $invoicePoint['y'] }}" r="4" data-line-point
                                             data-month="{{ $label }}"
                                             data-invoice-amount="{{ (float) ($lineInvoiceTotals[$index] ?? 0) }}"
                                             data-payment-amount="{{ (float) ($linePaymentTotals[$index] ?? 0) }}"
                                             data-invoice-count="{{ (int) ($lineInvoiceCounts[$index] ?? 0) }}"
                                             data-payment-count="{{ (int) ($linePaymentCounts[$index] ?? 0) }}">
                                             <title>Invoice {{ $label }}: Rp
                                                  {{ number_format((float) ($lineInvoiceTotals[$index] ?? 0), 0, ',', '.') }}
                                                  ({{ (int) ($lineInvoiceCounts[$index] ?? 0) }} data)</title>
                                        </circle>

                                        <circle class="sad-line-point-payment" cx="{{ $paymentPoint['x'] }}"
                                             cy="{{ $paymentPoint['y'] }}" r="4" data-line-point
                                             data-month="{{ $label }}"
                                             data-invoice-amount="{{ (float) ($lineInvoiceTotals[$index] ?? 0) }}"
                                             data-payment-amount="{{ (float) ($linePaymentTotals[$index] ?? 0) }}"
                                             data-invoice-count="{{ (int) ($lineInvoiceCounts[$index] ?? 0) }}"
                                             data-payment-count="{{ (int) ($linePaymentCounts[$index] ?? 0) }}">
                                             <title>Payment {{ $label }}: Rp
                                                  {{ number_format((float) ($linePaymentTotals[$index] ?? 0), 0, ',', '.') }}
                                                  ({{ (int) ($linePaymentCounts[$index] ?? 0) }} data)</title>
                                        </circle>
                                   @endforeach
                              </svg>
                         </div>

                         <div class="sad-line-click-result" id="lineChartClickResult"
                              data-default-title="Total Keseluruhan (Semua Bulan)"
                              data-default-invoice="{{ $lineInvoiceSum }}" data-default-payment="{{ $linePaymentSum }}"
                              data-default-total="{{ $lineGrandSum }}"
                              data-default-invoice-count="{{ array_sum($lineInvoiceCounts->all()) }}"
                              data-default-payment-count="{{ array_sum($linePaymentCounts->all()) }}">
                              <p class="sad-line-click-title" id="lineChartClickTitle">Total Keseluruhan (Semua Bulan)</p>
                              <div class="sad-line-click-values">
                                   <span id="lineChartClickInvoice">Invoice: Rp
                                        {{ number_format($lineInvoiceSum, 0, ',', '.') }}
                                        ({{ number_format(array_sum($lineInvoiceCounts->all()), 0, ',', '.') }} data)</span>
                                   <span id="lineChartClickPayment">Payment: Rp
                                        {{ number_format($linePaymentSum, 0, ',', '.') }}
                                        ({{ number_format(array_sum($linePaymentCounts->all()), 0, ',', '.') }} data)</span>
                                   <span id="lineChartClickTotal">Total Gabungan: Rp
                                        {{ number_format($lineGrandSum, 0, ',', '.') }}</span>
                              </div>
                         </div>
                    @else
                         <div class="sad-empty-state is-visible">
                              <i data-feather="activity"></i>
                              <h4>Data invoice/payment belum tersedia</h4>
                              <p>Tambahkan transaksi invoice dan payment untuk menampilkan line segment.</p>
                         </div>
                    @endif

                    <div class="sad-line-legend">
                         <span class="sad-line-legend-item"><span
                                   class="sad-line-legend-swatch invoice"></span>Invoices</span>
                         <span class="sad-line-legend-item"><span
                                   class="sad-line-legend-swatch payment"></span>Payments</span>
                    </div>
               </div>
          </section>

          @php
               $directionLabels = [
                   'increase' => 'Semakin besar semakin baik',
                   'decrease' => 'Semakin kecil semakin baik',
                   'exact' => 'Harus sesuai target',
               ];

               $directionIcons = [
                   'increase' => 'trending-up',
                   'decrease' => 'trending-down',
                   'exact' => 'crosshair',
               ];
          @endphp

          <section class="sad-card sad-monitoring-card" style="margin-bottom: 24px;">
               <header class="sad-card-header">
                    <div class="sad-card-heading">
                         <span class="sad-card-heading-icon"><i data-feather="briefcase"></i></span>
                         <div>
                              <h2 class="sad-card-title">Service Terbaru</h2>
                              <p class="sad-card-subtitle">Data terbaru dari tabel <code>services</code> berdasarkan kode,
                                   kategori, harga, durasi, dan status.</p>
                         </div>
                    </div>
                    @if (!empty($servicesUrl) && $servicesUrl !== '#')
                         <a href="{{ $servicesUrl }}" class="sad-card-action">Kelola service <i
                                   data-feather="arrow-up-right"></i></a>
                    @endif
               </header>
               <div class="sad-table-wrapper">
                    <table class="sad-table">
                         <thead>
                              <tr>
                                   <th>Service</th>
                                   <th>Kategori</th>
                                   <th>Harga</th>
                                   <th>Durasi</th>
                                   <th>Status</th>
                                   <th>Diperbarui</th>
                              </tr>
                         </thead>
                         <tbody>
                              @forelse ($services->take(5) as $service)
                                   @php $serviceStatus = strtolower((string) data_get($service, 'status', 'inactive')); @endphp
                                   <tr>
                                        <td><span
                                                  class="sad-unit-name">{{ data_get($service, 'name', 'Tanpa nama') }}</span><span
                                                  class="sad-unit-code">{{ data_get($service, 'service_code', '-') }}</span>
                                        </td>
                                        <td>{{ data_get($service, 'category_name', 'Tanpa kategori') }}</td>
                                        <td><span class="sad-leader">Rp
                                                  {{ number_format((float) data_get($service, 'base_price', 0), 0, ',', '.') }}</span><span
                                                  class="sad-updated">/ {{ data_get($service, 'unit', 'service') }}</span>
                                        </td>
                                        <td>{{ data_get($service, 'estimated_duration_minutes') ? data_get($service, 'estimated_duration_minutes') . ' menit' : '-' }}
                                        </td>
                                        <td><span
                                                  class="sad-badge {{ $serviceStatus === 'active' ? 'success' : 'danger' }}">{{ $serviceStatus === 'active' ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </td>
                                        <td>{{ optional(data_get($service, 'updated_at'))->format('d M Y') ?? '-' }}</td>
                                   </tr>
                              @empty
                                   <tr>
                                        <td colspan="6">
                                             <div class="sad-empty-state is-visible"><i data-feather="briefcase"></i>
                                                  <h4>Service belum tersedia</h4>
                                                  <p>Tambahkan service untuk menampilkannya di dashboard.</p>
                                             </div>
                                        </td>
                                   </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>
          </section>

          <section class="sad-main-grid">
               {{-- Grafik bobot indikator --}}
               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="bar-chart-2"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Distribusi Bobot Indikator Kinerja</h2>
                                   <p class="sad-card-subtitle">
                                        Perbandingan bobot indikator aktif berdasarkan data
                                        <code>performance_indicators</code>.
                                   </p>
                              </div>
                         </div>

                         <span class="sad-card-action">
                              Top {{ $indicatorWeightChart->count() }} indikator aktif
                         </span>
                    </header>

                    <div class="sad-chart-body">
                         <div class="sad-chart-summary">
                              <div class="sad-chart-legends">
                                   <span class="sad-chart-legend">
                                        <span class="sad-chart-legend-dot actual"></span>
                                        Bobot indikator
                                   </span>
                              </div>

                              <div class="sad-chart-rate">
                                   <strong>
                                        {{ number_format($indicatorSummary['average_weight'], 2, ',', '.') }}%
                                   </strong>
                                   <span>
                                        Rata-rata bobot dari
                                        {{ number_format($indicatorSummary['active'], 0, ',', '.') }}
                                        indikator aktif
                                   </span>
                              </div>
                         </div>

                         @if ($indicatorWeightChart->isNotEmpty())
                              <div class="sad-chart-area">
                                   <div class="sad-chart-y-axis">
                                        <span>100</span>
                                        <span>75</span>
                                        <span>50</span>
                                        <span>25</span>
                                        <span>0</span>
                                   </div>

                                   <div class="sad-chart-content"
                                        style="--sad-chart-columns: {{ $indicatorChartColumnCount }};">
                                        <div class="sad-chart-lines">
                                             <span class="sad-chart-line"></span>
                                             <span class="sad-chart-line"></span>
                                             <span class="sad-chart-line"></span>
                                             <span class="sad-chart-line"></span>
                                             <span class="sad-chart-line"></span>
                                        </div>

                                        <div class="sad-chart-columns">
                                             @foreach ($indicatorWeightChart as $indicator)
                                                  @php
                                                       $safeWeight = max(0, min(100, (float) $indicator['weight']));
                                                  @endphp

                                                  <div class="sad-chart-column">
                                                       <div class="sad-chart-bars">
                                                            <div class="sad-chart-bar actual"
                                                                 style="height: {{ $safeWeight }}%;" role="img"
                                                                 aria-label="Bobot {{ $indicator['code'] }} sebesar {{ $safeWeight }} persen">
                                                                 <span class="sad-chart-tooltip">
                                                                      {{ $indicator['code'] }}<br>
                                                                      {{ $indicator['name'] }}<br>
                                                                      Bobot:
                                                                      {{ number_format($safeWeight, 2, ',', '.') }}%
                                                                 </span>
                                                            </div>
                                                       </div>

                                                       <span class="sad-chart-month"
                                                            title="{{ $indicator['code'] }} — {{ $indicator['name'] }}">
                                                            {{ $indicator['code'] }}
                                                       </span>
                                                  </div>
                                             @endforeach
                                        </div>
                                   </div>
                              </div>
                         @else
                              <div class="sad-empty-state is-visible">
                                   <i data-feather="bar-chart"></i>
                                   <strong>Belum ada indikator aktif</strong>
                                   <span>
                                        Tambahkan atau aktifkan indikator agar grafik bobot dapat ditampilkan.
                                   </span>
                              </div>
                         @endif
                    </div>
               </article>

               {{-- Ringkasan status dan arah target --}}
               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="target"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Ringkasan Performance Indicator</h2>
                                   <p class="sad-card-subtitle">
                                        Komposisi status, total bobot aktif, dan arah target indikator.
                                   </p>
                              </div>
                         </div>
                    </header>

                    <div class="sad-satisfaction-body">
                         {{-- Ring status aktif --}}
                         <div class="sad-score-summary">
                              <div class="sad-score-ring"
                                   style="
                        background:
                        conic-gradient(
                            var(--sad-success) 0deg {{ $indicatorAngle }}deg,
                            var(--sad-border) {{ $indicatorAngle }}deg 360deg
                        );
                    ">
                                   <span class="sad-score-ring-value">
                                        {{ number_format($indicatorSummary['active_percentage'], 1, ',', '.') }}%
                                   </span>
                              </div>

                              <div class="sad-score-details">
                                   <h3>
                                        {{ number_format($indicatorSummary['active'], 0, ',', '.') }}
                                        Indikator Aktif
                                   </h3>

                                   <p>
                                        Dari total
                                        {{ number_format($indicatorSummary['total'], 0, ',', '.') }}
                                        indikator yang terdaftar.
                                   </p>

                                   <span class="sad-score-status">
                                        Total bobot aktif:
                                        {{ number_format($indicatorSummary['total_active_weight'], 2, ',', '.') }}%
                                   </span>
                              </div>
                         </div>

                         {{-- Ringkasan status --}}
                         <div class="sad-sentiment-grid">
                              <div class="sad-sentiment-card">
                                   <strong>
                                        {{ number_format($indicatorSummary['total'], 0, ',', '.') }}
                                   </strong>
                                   <span>Total</span>
                              </div>

                              <div class="sad-sentiment-card">
                                   <strong>
                                        {{ number_format($indicatorSummary['active'], 0, ',', '.') }}
                                   </strong>
                                   <span>Aktif</span>
                              </div>

                              <div class="sad-sentiment-card">
                                   <strong>
                                        {{ number_format($indicatorSummary['inactive'], 0, ',', '.') }}
                                   </strong>
                                   <span>Nonaktif</span>
                              </div>
                         </div>

                         {{-- Distribusi arah target --}}
                         <div class="sad-health-list">
                              @forelse ($indicatorDirectionSummary as $direction)
                                   @php
                                        $directionKey = (string) data_get($direction, 'key', '');
                                        $directionTotal = max(0, (int) data_get($direction, 'total', 0));
                                        $directionPercentage = min(
                                            100,
                                            max(0, (float) data_get($direction, 'percentage', 0)),
                                        );
                                        $directionClass = in_array(
                                            data_get($direction, 'class'),
                                            ['success', 'warning', 'info', 'danger', 'primary'],
                                            true,
                                        )
                                            ? data_get($direction, 'class')
                                            : 'info';
                                   @endphp

                                   <div>
                                        <div class="sad-health-top">
                                             <span class="sad-health-label">
                                                  <i data-feather="{{ $directionIcons[$directionKey] ?? 'circle' }}"></i>
                                                  {{ $directionLabels[$directionKey] ?? \Illuminate\Support\Str::of($directionKey)->replace('_', ' ')->title() }}
                                             </span>

                                             <span class="sad-health-value">
                                                  {{ number_format($directionPercentage, 1, ',', '.') }}%
                                                  ({{ $directionTotal }})
                                             </span>
                                        </div>

                                        <div class="sad-progress">
                                             <div class="sad-progress-bar {{ $directionClass }}"
                                                  style="width: {{ $directionPercentage }}%;"></div>
                                        </div>
                                   </div>
                              @empty
                                   <div class="sad-empty-state is-visible">
                                        <i data-feather="target"></i>
                                        <strong>Distribusi arah target belum tersedia</strong>
                                   </div>
                              @endforelse
                         </div>
                    </div>
               </article>
          </section>

          <section class="sad-secondary-grid">
               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="alert-octagon"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Prioritas Monitoring</h2>
                                   <p class="sad-card-subtitle">
                                        Menampilkan maksimal 5 temuan prioritas yang memerlukan tindak lanjut administrator.
                                   </p>
                              </div>
                         </div>

                         <span class="sad-badge danger">
                              {{ count($monitoringPriorities) }} prioritas
                         </span>
                    </header>

                    <div class="sad-priority-list">
                         @forelse ($monitoringPriorities->take(5) as $priority)
                              @php
                                   $priorityIcon = (string) data_get($priority, 'icon', 'alert-circle');
                                   $priorityTitle = (string) data_get($priority, 'title', 'Prioritas');
                                   $priorityStatus = (string) data_get($priority, 'status', 'Monitoring');
                                   $priorityStatusClass = (string) data_get($priority, 'status_class', 'neutral');
                                   $priorityDescription = (string) data_get($priority, 'description', '-');
                                   $priorityAction = (string) data_get($priority, 'action', 'Lihat');
                                   $priorityUrl = (string) data_get($priority, 'url', '#');
                              @endphp

                              <div class="sad-priority-item">
                                   <span class="sad-priority-icon">
                                        <i data-feather="{{ $priorityIcon }}"></i>
                                   </span>

                                   <div class="sad-priority-content">
                                        <div class="sad-priority-heading">
                                             <h3 class="sad-priority-title">
                                                  {{ $priorityTitle }}
                                             </h3>

                                             <span class="sad-badge {{ $priorityStatusClass }}">
                                                  {{ $priorityStatus }}
                                             </span>
                                        </div>

                                        <p class="sad-priority-description">
                                             {{ $priorityDescription }}
                                        </p>

                                        <a href="{{ $priorityUrl }}" class="sad-priority-action">
                                             {{ $priorityAction }}
                                             <i data-feather="arrow-right"></i>
                                        </a>
                                   </div>
                              </div>
                         @empty
                              <div class="sad-empty-state is-visible">
                                   <i data-feather="check-circle"></i>
                                   <h4>Tidak ada prioritas mendesak</h4>
                                   <p>Seluruh data utama dalam kondisi terpantau.</p>
                              </div>
                         @endforelse
                    </div>
               </article>

               <article class="sad-card sad-monitoring-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="calendar"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Periode Penilaian</h2>
                                   <p class="sad-card-subtitle">
                                        Menampilkan maksimal 5 periode terbaru berdasarkan rentang tanggal, jenis, dan
                                        status.
                                   </p>
                              </div>
                         </div>

                         @if (\Illuminate\Support\Facades\Route::has('super-admin.performance-periods.index'))
                              <a href="{{ route('super-admin.performance-periods.index') }}" class="sad-card-action">
                                   <i data-feather="list"></i>
                                   Lihat semua periode
                              </a>
                         @endif
                    </header>

                    <div class="sad-table-toolbar">
                         <div class="sad-filter-list">
                              <button type="button" class="sad-filter-button active" data-period-filter="semua">
                                   Semua
                              </button>

                              <button type="button" class="sad-filter-button" data-period-filter="draft">
                                   Draft
                              </button>

                              <button type="button" class="sad-filter-button" data-period-filter="active">
                                   Aktif
                              </button>

                              <button type="button" class="sad-filter-button" data-period-filter="completed">
                                   Selesai
                              </button>

                              <button type="button" class="sad-filter-button" data-period-filter="inactive">
                                   Tidak Aktif
                              </button>
                         </div>

                         <label class="sad-search">
                              <i data-feather="search"></i>

                              <input type="search" id="performancePeriodSearch"
                                   placeholder="Cari nama, jenis, atau status periode..." autocomplete="off">
                         </label>
                    </div>

                    <div class="sad-table-wrapper">
                         <table class="sad-table">
                              <thead>
                                   <tr>
                                        <th>Periode</th>
                                        <th>Rentang Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Diperbarui</th>
                                        <th></th>
                                   </tr>
                              </thead>

                              <tbody id="performancePeriodTableBody">
                                   @forelse ($performancePeriods->take(5) as $performancePeriod)
                                        @php
                                             $periodId = data_get($performancePeriod, 'id', '-');
                                             $periodName = (string) data_get($performancePeriod, 'name', 'Tanpa nama');

                                             $status = strtolower(
                                                 (string) data_get($performancePeriod, 'status', 'inactive'),
                                             );

                                             $type = strtolower(
                                                 (string) data_get($performancePeriod, 'period_type', '-'),
                                             );

                                             $statusClass = match ($status) {
                                                 'active' => 'success',
                                                 'draft' => 'warning',
                                                 'completed' => 'info',
                                                 'inactive' => 'danger',
                                                 default => 'neutral',
                                             };

                                             $statusLabel = match ($status) {
                                                 'active' => 'Aktif',
                                                 'draft' => 'Draft',
                                                 'completed' => 'Selesai',
                                                 'inactive' => 'Tidak Aktif',
                                                 default => \Illuminate\Support\Str::of($status)
                                                     ->replace('_', ' ')
                                                     ->title()
                                                     ->toString(),
                                             };

                                             $typeLabel = match ($type) {
                                                 'monthly' => 'Bulanan',
                                                 'quarterly' => 'Kuartalan',
                                                 'semester' => 'Semester',
                                                 'annual' => 'Tahunan',
                                                 default => \Illuminate\Support\Str::of($type)
                                                     ->replace('_', ' ')
                                                     ->title()
                                                     ->toString(),
                                             };

                                             $startDate = null;
                                             $endDate = null;
                                             $updatedAt = null;

                                             try {
                                                 $startValue = data_get($performancePeriod, 'start_date');

                                                 if ($startValue) {
                                                     $startDate =
                                                         $startValue instanceof \Illuminate\Support\Carbon
                                                             ? $startValue
                                                             : \Illuminate\Support\Carbon::parse($startValue);
                                                 }
                                             } catch (\Throwable) {
                                                 $startDate = null;
                                             }

                                             try {
                                                 $endValue = data_get($performancePeriod, 'end_date');

                                                 if ($endValue) {
                                                     $endDate =
                                                         $endValue instanceof \Illuminate\Support\Carbon
                                                             ? $endValue
                                                             : \Illuminate\Support\Carbon::parse($endValue);
                                                 }
                                             } catch (\Throwable) {
                                                 $endDate = null;
                                             }

                                             try {
                                                 $updatedValue = data_get($performancePeriod, 'updated_at');

                                                 if ($updatedValue) {
                                                     $updatedAt =
                                                         $updatedValue instanceof \Illuminate\Support\Carbon
                                                             ? $updatedValue
                                                             : \Illuminate\Support\Carbon::parse($updatedValue);
                                                 }
                                             } catch (\Throwable) {
                                                 $updatedAt = null;
                                             }

                                             $duration =
                                                 $startDate && $endDate
                                                     ? (int) $startDate->diffInDays($endDate) + 1
                                                     : null;

                                             $searchKeyword = strtolower(
                                                 trim(
                                                     $periodName .
                                                         ' ' .
                                                         $type .
                                                         ' ' .
                                                         $typeLabel .
                                                         ' ' .
                                                         $status .
                                                         ' ' .
                                                         $statusLabel,
                                                 ),
                                             );
                                        @endphp

                                        <tr data-period-row data-period-status="{{ $status }}"
                                             data-period-keyword="{{ $searchKeyword }}">
                                             <td>
                                                  <div class="sad-unit-cell">
                                                       <span class="sad-unit-icon">
                                                            <i data-feather="calendar"></i>
                                                       </span>

                                                       <span>
                                                            <strong class="sad-unit-name">
                                                                 {{ $periodName }}
                                                            </strong>

                                                            <span class="sad-unit-code">
                                                                 ID #{{ $periodId }}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="sad-leader">
                                                       {{ $startDate?->format('d M Y') ?? '-' }}
                                                  </span>

                                                  <span class="sad-updated">
                                                       s.d. {{ $endDate?->format('d M Y') ?? '-' }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-leader">
                                                       {{ $duration !== null ? $duration . ' hari' : '-' }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-badge info">
                                                       {{ $typeLabel }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-badge {{ $statusClass }}">
                                                       {{ $statusLabel }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-leader">
                                                       {{ $updatedAt?->format('d M Y') ?? '-' }}
                                                  </span>

                                                  <span class="sad-updated">
                                                       {{ $updatedAt?->diffForHumans() ?? '-' }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="sad-row-actions">
                                                       @if (\Illuminate\Support\Facades\Route::has('super-admin.performance-periods.show'))
                                                            <a href="{{ route('super-admin.performance-periods.show', $periodId) }}"
                                                                 class="sad-action-menu"
                                                                 aria-label="Lihat periode {{ $periodName }}"
                                                                 title="Detail">
                                                                 <i data-feather="eye"></i>
                                                            </a>
                                                       @endif

                                                       @if (\Illuminate\Support\Facades\Route::has('super-admin.performance-periods.edit'))
                                                            <a href="{{ route('super-admin.performance-periods.edit', $periodId) }}"
                                                                 class="sad-action-menu"
                                                                 aria-label="Edit periode {{ $periodName }}"
                                                                 title="Edit">
                                                                 <i data-feather="edit-2"></i>
                                                            </a>
                                                       @endif
                                                  </div>
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="7">
                                                  <div class="sad-empty-state is-visible">
                                                       <i data-feather="calendar"></i>
                                                       <h4>Data periode belum tersedia</h4>
                                                       <p>Tambahkan periode penilaian untuk menampilkannya di sini.</p>
                                                  </div>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>

                         @if ($performancePeriods->isNotEmpty())
                              <div class="sad-empty-state" id="performancePeriodEmptyState">
                                   <i data-feather="search"></i>
                                   <h4>Data periode tidak ditemukan</h4>
                                   <p>Gunakan kata kunci atau filter status yang berbeda.</p>
                              </div>
                         @endif
                    </div>
               </article>
          </section>


          <section class="sad-bottom-grid">
               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="target"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Indikator Kinerja Terbaru</h2>
                                   <p class="sad-card-subtitle">
                                        Menampilkan maksimal 5 indikator terbaru berdasarkan kode, bobot,
                                        arah target, dan status.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ $performanceIndicatorsUrl }}" class="sad-card-action">
                              Kelola indikator
                              <i data-feather="arrow-up-right"></i>
                         </a>
                    </header>

                    <div class="sad-channel-list">
                         @forelse ($performanceIndicators->take(5) as $indicator)
                              @php
                                   $indicatorStatus = strtolower((string) data_get($indicator, 'status', 'inactive'));

                                   $indicatorDirection = strtolower(
                                       (string) data_get($indicator, 'target_direction', 'exact'),
                                   );

                                   $indicatorWeight = min(100, max(0, (float) data_get($indicator, 'weight', 0)));

                                   $indicatorProgressClass = match ($indicatorDirection) {
                                       'increase' => 'success',
                                       'decrease' => 'warning',
                                       'exact' => 'info',
                                       default => 'primary',
                                   };
                              @endphp

                              <div class="sad-channel-item">
                                   <div class="sad-channel-header">
                                        <div class="sad-channel-identity">
                                             <span class="sad-channel-icon">
                                                  <i
                                                       data-feather="{{ $directionIcons[$indicatorDirection] ?? 'target' }}"></i>
                                             </span>

                                             <span>
                                                  <strong class="sad-channel-name">
                                                       {{ data_get($indicator, 'code', '-') }}
                                                       —
                                                       {{ data_get($indicator, 'name', 'Tanpa nama') }}
                                                  </strong>

                                                  <span class="sad-channel-meta">
                                                       {{ $directionLabels[$indicatorDirection] ?? \Illuminate\Support\Str::of($indicatorDirection)->replace('_', ' ')->title() }}
                                                       ·
                                                       {{ data_get($indicator, 'unit', '-') }}
                                                       ·
                                                       {{ $indicatorStatus === 'active' ? 'Aktif' : 'Tidak aktif' }}
                                                  </span>
                                             </span>
                                        </div>

                                        <span class="sad-channel-score">
                                             {{ number_format($indicatorWeight, 2, ',', '.') }}%
                                        </span>
                                   </div>

                                   <div class="sad-progress">
                                        <div class="sad-progress-bar {{ $indicatorProgressClass }}"
                                             style="width: {{ $indicatorWeight }}%;"></div>
                                   </div>
                              </div>
                         @empty
                              <div class="sad-empty-state is-visible">
                                   <i data-feather="target"></i>
                                   <h4>Indikator belum tersedia</h4>
                                   <p>Tambahkan indikator kinerja agar data tampil di dashboard.</p>
                              </div>
                         @endforelse
                    </div>
               </article>

               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="shield"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Pengguna dan Hak Akses</h2>
                                   <p class="sad-card-subtitle">
                                        Menampilkan maksimal 5 role pengguna berdasarkan status keaktifan.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ $usersUrl }}" class="sad-card-action">
                              Kelola pengguna
                              <i data-feather="arrow-up-right"></i>
                         </a>
                    </header>

                    <div class="sad-role-list">
                         @foreach ($roleSummary->take(5) as $role)
                              @php
                                   $roleName = (string) data_get($role, 'name', 'Role');
                                   $roleIcon = (string) data_get($role, 'icon', 'user');
                                   $roleUsers = max(0, (int) data_get($role, 'users', 0));
                                   $roleActive = max(0, (int) data_get($role, 'active', 0));
                                   $rolePercentage =
                                       $roleUsers > 0 ? min(100, max(0, round(($roleActive / $roleUsers) * 100))) : 0;
                              @endphp

                              <div class="sad-role-item">
                                   <div class="sad-role-header">
                                        <div class="sad-role-identity">
                                             <span class="sad-role-icon">
                                                  <i data-feather="{{ $roleIcon }}"></i>
                                             </span>

                                             <span>
                                                  <strong class="sad-role-name">{{ $roleName }}</strong>
                                                  <span class="sad-role-meta">{{ $roleActive }} akun aktif</span>
                                             </span>
                                        </div>

                                        <span class="sad-role-count">
                                             {{ $roleUsers }}
                                             <small>pengguna</small>
                                        </span>
                                   </div>

                                   <div class="sad-progress">
                                        <div class="sad-progress-bar {{ $roleUsers > 0 && $roleActive >= $roleUsers ? 'success' : 'info' }}"
                                             style="width: {{ $rolePercentage }}%;">
                                        </div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>
          </section>

          <section class="sad-footer-grid">
               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="bell"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Aktivitas Sistem Terbaru</h2>
                                   <p class="sad-card-subtitle">
                                        Menampilkan maksimal 5 aktivitas sistem terbaru dan paling relevan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="sad-card-action" id="markSystemRead">Tandai dibaca</button>
                    </header>

                    <div class="sad-activity-list" id="systemActivityList">
                         @foreach ($systemActivities->take(5) as $activity)
                              @php
                                   $activityTheme = (string) data_get($activity, 'theme', 'blue');
                                   $activityIcon = (string) data_get($activity, 'icon', 'activity');
                                   $activityTitle = (string) data_get($activity, 'title', 'Aktivitas sistem');
                                   $activityDescription = (string) data_get($activity, 'description', '-');
                                   $activityTime = (string) data_get($activity, 'time', '-');
                              @endphp

                              <div class="sad-activity-item" data-system-activity>
                                   <span class="sad-activity-icon {{ $activityTheme }}">
                                        <i data-feather="{{ $activityIcon }}"></i>
                                   </span>

                                   <div class="sad-activity-content">
                                        <h4>{{ $activityTitle }}</h4>
                                        <p>{{ $activityDescription }}</p>
                                        <div class="sad-activity-time">{{ $activityTime }}</div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="zap"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Akses Cepat Super Admin</h2>
                                   <p class="sad-card-subtitle">
                                        Pintasan menuju modul pengelolaan utama aplikasi.
                                   </p>
                              </div>
                         </div>
                    </header>

                    <div class="sad-quick-grid">
                         @foreach ($quickActions as $action)
                              @php
                                   $actionUrl = (string) data_get($action, 'url', '#');
                                   $actionIcon = (string) data_get($action, 'icon', 'grid');
                                   $actionLabel = (string) data_get($action, 'label', 'Menu');
                                   $actionDescription = (string) data_get($action, 'description', '-');
                              @endphp

                              <a href="{{ $actionUrl }}" class="sad-quick-action">
                                   <span class="sad-quick-icon">
                                        <i data-feather="{{ $actionIcon }}"></i>
                                   </span>

                                   <span>
                                        <strong>{{ $actionLabel }}</strong>
                                        <span>{{ $actionDescription }}</span>
                                   </span>
                              </a>
                         @endforeach
                    </div>
               </article>
          </section>
     </div>

     @push('scripts')
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }

                    const liveClock = document.getElementById('sadLiveClock');

                    function updateClock() {
                         if (!liveClock) {
                              return;
                         }

                         liveClock.textContent = new Date().toLocaleTimeString(
                              'id-ID', {
                                   hour: '2-digit',
                                   minute: '2-digit',
                                   second: '2-digit',
                                   hour12: false,
                                   timeZone: 'Asia/Jakarta'
                              }
                         ) + ' WIB';
                    }

                    updateClock();
                    window.setInterval(updateClock, 1000);

                    const markReadButton = document.getElementById('markSystemRead');
                    const activityItems = document.querySelectorAll('[data-system-activity]');

                    markReadButton?.addEventListener('click', function() {
                         activityItems.forEach(function(activity) {
                              activity.style.opacity = '0.48';
                         });

                         markReadButton.textContent = 'Sudah dibaca';
                         markReadButton.disabled = true;
                    });

                    const periodSearch = document.getElementById(
                         'performancePeriodSearch'
                    );

                    const periodRows = Array.from(
                         document.querySelectorAll('[data-period-row]')
                    );

                    const periodButtons = Array.from(
                         document.querySelectorAll('[data-period-filter]')
                    );

                    const periodEmptyState = document.getElementById(
                         'performancePeriodEmptyState'
                    );

                    let activePeriodStatus = 'semua';

                    function filterPerformancePeriods() {
                         const keyword = (
                              periodSearch?.value || ''
                         ).trim().toLowerCase();

                         let visibleCount = 0;

                         periodRows.forEach(function(row) {
                              const status = (
                                   row.dataset.periodStatus || ''
                              ).toLowerCase();

                              const searchableText = (
                                   row.dataset.periodKeyword || ''
                              ).toLowerCase();

                              const statusMatches =
                                   activePeriodStatus === 'semua' ||
                                   status === activePeriodStatus;

                              const keywordMatches =
                                   keyword === '' ||
                                   searchableText.includes(keyword);

                              const isVisible = statusMatches && keywordMatches;

                              row.hidden = !isVisible;

                              if (isVisible) {
                                   visibleCount++;
                              }
                         });

                         periodEmptyState?.classList.toggle(
                              'is-visible',
                              periodRows.length > 0 && visibleCount === 0
                         );

                         if (typeof feather !== 'undefined') {
                              feather.replace();
                         }
                    }

                    periodButtons.forEach(function(button) {
                         button.addEventListener('click', function() {
                              activePeriodStatus =
                                   button.dataset.periodFilter || 'semua';

                              periodButtons.forEach(function(item) {
                                   item.classList.remove('active');
                              });

                              button.classList.add('active');
                              filterPerformancePeriods();
                         });
                    });

                    periodSearch?.addEventListener(
                         'input',
                         filterPerformancePeriods
                    );

                    const lineChartResult = document.getElementById('lineChartClickResult');
                    const lineChartTitle = document.getElementById('lineChartClickTitle');
                    const lineChartInvoice = document.getElementById('lineChartClickInvoice');
                    const lineChartPayment = document.getElementById('lineChartClickPayment');
                    const lineChartTotal = document.getElementById('lineChartClickTotal');
                    const lineChartPoints = Array.from(document.querySelectorAll('[data-line-point]'));

                    function formatCurrency(value) {
                         return new Intl.NumberFormat('id-ID').format(Math.max(0, Number(value) || 0));
                    }

                    function formatCount(value) {
                         return new Intl.NumberFormat('id-ID').format(Math.max(0, Number(value) || 0));
                    }

                    function renderLineResult(title, invoiceAmount, paymentAmount, invoiceCount, paymentCount) {
                         if (!lineChartResult || !lineChartTitle || !lineChartInvoice || !lineChartPayment || !
                              lineChartTotal) {
                              return;
                         }

                         const totalAmount = Math.max(0, Number(invoiceAmount) || 0) + Math.max(0, Number(
                              paymentAmount) || 0);

                         lineChartTitle.textContent = title;
                         lineChartInvoice.textContent = 'Invoice: Rp ' + formatCurrency(invoiceAmount) + ' (' +
                              formatCount(invoiceCount) + ' data)';
                         lineChartPayment.textContent = 'Payment: Rp ' + formatCurrency(paymentAmount) + ' (' +
                              formatCount(paymentCount) + ' data)';
                         lineChartTotal.textContent = 'Total Gabungan: Rp ' + formatCurrency(totalAmount);
                    }

                    if (lineChartResult && lineChartPoints.length > 0) {
                         renderLineResult(
                              lineChartResult.dataset.defaultTitle || 'Total Keseluruhan (Semua Bulan)',
                              lineChartResult.dataset.defaultInvoice || 0,
                              lineChartResult.dataset.defaultPayment || 0,
                              lineChartResult.dataset.defaultInvoiceCount || 0,
                              lineChartResult.dataset.defaultPaymentCount || 0
                         );

                         lineChartPoints.forEach(function(point) {
                              point.addEventListener('click', function() {
                                   lineChartPoints.forEach(function(item) {
                                        item.classList.remove('is-active');
                                   });

                                   point.classList.add('is-active');

                                   const month = point.dataset.month || '-';
                                   const invoiceAmount = point.dataset.invoiceAmount || 0;
                                   const paymentAmount = point.dataset.paymentAmount || 0;
                                   const invoiceCount = point.dataset.invoiceCount || 0;
                                   const paymentCount = point.dataset.paymentCount || 0;

                                   renderLineResult(
                                        'Ringkasan Bulan ' + month,
                                        invoiceAmount,
                                        paymentAmount,
                                        invoiceCount,
                                        paymentCount
                                   );
                              });
                         });
                    }

                    filterPerformancePeriods();
               });
          </script>
     @endpush
@endsection
