@extends('layouts.app')

@section('title', 'Dashboard Monitoring Produktivitas Karyawan')

@section('content')
     @php
          /*
        |--------------------------------------------------------------------------
        | PERSIAPAN DATA DAN HAK AKSES
        |--------------------------------------------------------------------------
        */
          $authUser = auth()->user();

          $normalizeRole = static function ($role): string {
              if (is_object($role) || is_array($role)) {
                  $role = data_get($role, 'slug') ?? (data_get($role, 'name') ?? '');
              }

              return strtolower(str_replace(['-', ' '], '_', trim((string) $role)));
          };

          $roleNameCandidates = [];

          if ($authUser) {
              if (method_exists($authUser, 'getRoleNames')) {
                  $roleNameCandidates = $authUser->getRoleNames()->all();
              }

              if (empty($roleNameCandidates)) {
                  $roleNameCandidates[] =
                      data_get($authUser, 'active_role_name') ??
                      (data_get($authUser, 'role_name') ??
                          (data_get($authUser, 'role.slug') ??
                              (data_get($authUser, 'role.name') ?? (data_get($authUser, 'role') ?? ''))));
              }
          }

          $normalizedRoleNames = collect($roleNameCandidates)->map($normalizeRole)->filter()->values()->all();

          $hasRole = static function (string $role) use ($authUser, $normalizeRole, $normalizedRoleNames): bool {
              if (!$authUser) {
                  return false;
              }

              $normalizedInput = $normalizeRole($role);

              if (method_exists($authUser, 'hasRole') && $authUser->hasRole($role)) {
                  return true;
              }

              if (method_exists($authUser, 'hasRole') && $authUser->hasRole(str_replace('_', ' ', $role))) {
                  return true;
              }

              return in_array($normalizedInput, $normalizedRoleNames, true) ||
                  in_array(
                      str_replace('_', ' ', $normalizedInput),
                      array_map($normalizeRole, $normalizedRoleNames),
                      true,
                  );
          };

          $isSuperAdmin = $hasRole('super_admin') || $hasRole('super admin') || $hasRole('superadministrator');
          $isPelayanan = $hasRole('admin_pelayanan') || $hasRole('admin pelayanan') || $hasRole('adminpelayanan');
          $isOperasional =
              $hasRole('admin_operasional') || $hasRole('admin operasional') || $hasRole('adminoperasional');

          /*
           * Sesuai routes/web.php:
           * - Super Admin, Pelayanan, dan Operasional dapat mengelola pelanggan.
           * - Hanya Super Admin dapat membuka recycle bin.
           */
          $canManageCustomers = $isSuperAdmin || $isPelayanan || $isOperasional;
          $canAccessTrash = $isSuperAdmin;

          $routeHas = static fn(string $routeName): bool => \Illuminate\Support\Facades\Route::has($routeName);

          /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */
          $search = isset($search) ? trim((string) $search) : trim((string) request('search', ''));

          $status = isset($status) ? trim((string) $status) : trim((string) request('status', ''));

          $customerType = isset($customerType)
              ? trim((string) $customerType)
              : trim((string) request('customer_type', ''));

          $customerTypeOptions = isset($customerTypeOptions)
              ? $customerTypeOptions
              : \App\Models\Customer::customerTypeOptions();

          $statusOptions = isset($statusOptions) ? $statusOptions : \App\Models\Customer::statusOptions();

          $hasActiveFilters = $search !== '' || $status !== '' || $customerType !== '';

          /*
        |--------------------------------------------------------------------------
        | STATISTIK HALAMAN
        |--------------------------------------------------------------------------
        */
          $filteredTotal = method_exists($customers, 'total') ? $customers->total() : $customers->count();

          $currentPageCount = $customers->count();

          $currentCollection = method_exists($customers, 'getCollection')
              ? $customers->getCollection()
              : collect($customers);

          $activeOnPage = $currentCollection->where('status', \App\Models\Customer::STATUS_ACTIVE)->count();

          $companyOnPage = $currentCollection->where('customer_type', \App\Models\Customer::TYPE_COMPANY)->count();

          $individualOnPage = $currentCollection
              ->where('customer_type', \App\Models\Customer::TYPE_INDIVIDUAL)
              ->count();

          $typeLabel = static function (?string $value): string {
              return match ($value) {
                  \App\Models\Customer::TYPE_COMPANY => 'Perusahaan',
                  \App\Models\Customer::TYPE_INDIVIDUAL => 'Perorangan',
                  default => 'Tidak Diketahui',
              };
          };

          $statusLabel = static function (?string $value): string {
              return match ($value) {
                  \App\Models\Customer::STATUS_ACTIVE => 'Aktif',
                  \App\Models\Customer::STATUS_INACTIVE => 'Tidak Aktif',
                  default => 'Tidak Diketahui',
              };
          };

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

     <style>
          :root {
               --customer-primary: #4f46e5;
               --customer-primary-light: #6366f1;
               --customer-secondary: #06b6d4;
               --customer-purple: #8b5cf6;
               --customer-pink: #ec4899;
               --customer-success: #10b981;
               --customer-warning: #f59e0b;
               --customer-danger: #ef4444;
               --customer-info: #0ea5e9;
               --customer-text: #24324a;
               --customer-muted: #718096;
               --customer-border: #e5eaf2;
               --customer-white: #ffffff;
               --customer-soft-indigo: #eef2ff;
               --customer-soft-purple: #f5f3ff;
               --customer-soft-blue: #eff6ff;
               --customer-soft-cyan: #ecfeff;
               --customer-soft-green: #ecfdf5;
               --customer-soft-orange: #fff7ed;
               --customer-soft-red: #fff1f2;
          }

          .customer-page,
          .customer-page * {
               box-sizing: border-box;
          }

          .customer-page {
               position: relative;
               min-height: calc(100vh - 70px);
               padding: 30px 18px 46px;
               overflow: hidden;
               background:
                    radial-gradient(circle at 3% 4%, rgba(99, 102, 241, .18), transparent 24%),
                    radial-gradient(circle at 97% 9%, rgba(6, 182, 212, .18), transparent 25%),
                    radial-gradient(circle at 88% 94%, rgba(236, 72, 153, .12), transparent 22%),
                    linear-gradient(145deg, #fbfdff 0%, #f8f7ff 48%, #f1fbff 100%);
          }

          .customer-page::before,
          .customer-page::after {
               position: absolute;
               z-index: 0;
               content: '';
               pointer-events: none;
               border-radius: 999px;
          }

          .customer-page::before {
               top: 260px;
               left: -170px;
               width: 330px;
               height: 330px;
               background: rgba(139, 92, 246, .07);
          }

          .customer-page::after {
               right: -170px;
               bottom: 80px;
               width: 350px;
               height: 350px;
               background: rgba(6, 182, 212, .07);
          }

          .customer-container {
               position: relative;
               z-index: 1;
               width: 100%;
               max-width: 1600px;
               margin: 0 auto;
          }

          /* ================================================================
                                    HERO
                                 ================================================================= */

          .customer-hero {
               position: relative;
               padding: 34px;
               margin-bottom: 22px;
               overflow: hidden;
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, .68);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 87% 14%, rgba(255, 255, 255, .32), transparent 22%),
                    linear-gradient(120deg, #4f46e5 0%, #7c3aed 44%, #0891b2 100%);
               box-shadow: 0 24px 54px rgba(79, 70, 229, .22);
          }

          .customer-hero::before {
               position: absolute;
               top: -95px;
               right: 10%;
               width: 235px;
               height: 235px;
               content: '';
               border: 38px solid rgba(255, 255, 255, .11);
               border-radius: 50%;
          }

          .customer-hero::after {
               position: absolute;
               right: -40px;
               bottom: -90px;
               width: 200px;
               height: 200px;
               content: '';
               border-radius: 48px;
               background: rgba(255, 255, 255, .11);
               transform: rotate(29deg);
          }

          .customer-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .customer-hero-heading {
               display: flex;
               min-width: 0;
               gap: 18px;
               align-items: center;
          }

          .customer-hero-icon {
               display: inline-flex;
               flex: 0 0 68px;
               width: 68px;
               height: 68px;
               color: var(--customer-primary);
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .84);
               border-radius: 21px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 30px rgba(49, 46, 129, .18);
          }

          .customer-hero-icon svg {
               width: 30px;
               height: 30px;
          }

          .customer-hero h1 {
               margin: 0;
               font-size: clamp(1.75rem, 2.5vw, 2.45rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .customer-hero p {
               max-width: 790px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .96rem;
               line-height: 1.72;
          }

          .customer-hero-actions {
               display: flex;
               flex: 0 0 auto;
               gap: 10px;
               flex-wrap: wrap;
               align-items: center;
               justify-content: flex-end;
          }

          .customer-hero-button {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 14px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 12px 25px rgba(49, 46, 129, .18);
               transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
          }

          .customer-hero-button svg {
               width: 17px;
               height: 17px;
          }

          .customer-hero-button:hover {
               color: #312e81;
               text-decoration: none;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 31px rgba(49, 46, 129, .23);
          }

          .customer-hero-button.is-soft {
               color: #ffffff;
               border-color: rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .15);
               backdrop-filter: blur(11px);
          }

          .customer-hero-button.is-soft:hover {
               color: #ffffff;
               background: rgba(255, 255, 255, .24);
          }

          /* ================================================================
                                    ALERT
                                 ================================================================= */

          .customer-alert {
               display: flex;
               padding: 16px 18px;
               gap: 13px;
               align-items: flex-start;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 17px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .customer-alert svg {
               flex: 0 0 auto;
               width: 20px;
               height: 20px;
               margin-top: 1px;
          }

          .customer-alert-success {
               color: #047857;
               border-left: 5px solid var(--customer-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .customer-alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--customer-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .customer-alert-close {
               padding: 0;
               margin-left: auto;
               color: currentColor;
               line-height: 1;
               border: 0;
               background: transparent;
               opacity: .65;
          }

          .customer-alert-close:hover {
               opacity: 1;
          }

          /* ================================================================
                                    STATISTICS
                                 ================================================================= */

          .customer-stats-row {
               margin-bottom: 22px;
          }

          .customer-stat-card {
               position: relative;
               min-height: 140px;
               padding: 22px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .96);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: transform .22s ease, box-shadow .22s ease;
          }

          .customer-stat-card:hover {
               transform: translateY(-4px);
               box-shadow: 0 20px 42px rgba(51, 65, 85, .12);
          }

          .customer-stat-card::after {
               position: absolute;
               right: -30px;
               bottom: -42px;
               width: 132px;
               height: 132px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .54);
          }

          .customer-stat-total {
               color: #4338ca;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .customer-stat-active {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #ccfbf1);
          }

          .customer-stat-company {
               color: #0369a1;
               background: linear-gradient(135deg, #eff6ff, #cffafe);
          }

          .customer-stat-individual {
               color: #a21caf;
               background: linear-gradient(135deg, #fdf4ff, #fae8ff);
          }

          .customer-stat-inner {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
          }

          .customer-stat-title {
               margin-bottom: 7px;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .customer-stat-value {
               font-size: 2.25rem;
               font-weight: 850;
               line-height: 1;
               letter-spacing: -.04em;
          }

          .customer-stat-caption {
               margin-top: 8px;
               font-size: .78rem;
               font-weight: 650;
               opacity: .72;
          }

          .customer-stat-icon {
               display: inline-flex;
               flex: 0 0 55px;
               width: 55px;
               height: 55px;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .84);
               border-radius: 17px;
               background: rgba(255, 255, 255, .76);
               box-shadow: 0 10px 22px rgba(15, 23, 42, .07);
          }

          .customer-stat-icon svg {
               width: 23px;
               height: 23px;
          }

          .monitoring-row {
               margin-bottom: 22px;
          }

          .monitoring-card,
          .quick-action-card {
               height: 100%;
               padding: 20px;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 22px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
               backdrop-filter: blur(12px);
          }

          .monitoring-title,
          .quick-action-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 16px;
               color: var(--customer-text);
               font-size: .92rem;
               font-weight: 830;
               letter-spacing: .02em;
               text-transform: uppercase;
          }

          .monitoring-title i,
          .quick-action-title i {
               display: inline-flex;
               width: 34px;
               height: 34px;
               align-items: center;
               justify-content: center;
               color: var(--customer-primary);
               border-radius: 10px;
               background: var(--customer-soft-indigo);
          }

          .monitoring-title i svg,
          .quick-action-title i svg {
               width: 16px;
               height: 16px;
          }

          .monitoring-mini {
               height: 100%;
               padding: 14px;
               border: 1px solid #e5eaf4;
               border-radius: 14px;
               background: linear-gradient(160deg, #ffffff 0%, #f8fbff 100%);
          }

          .monitoring-mini-label {
               display: block;
               margin-bottom: 6px;
               color: #64748b;
               font-size: .71rem;
               font-weight: 780;
               letter-spacing: .05em;
               text-transform: uppercase;
          }

          .monitoring-mini-value {
               display: block;
               color: #0f172a;
               font-size: 1.35rem;
               font-weight: 850;
               letter-spacing: -.02em;
               line-height: 1.15;
          }

          .monitoring-mini-caption {
               margin-top: 7px;
               color: #64748b;
               font-size: .75rem;
               font-weight: 650;
          }

          .quick-action-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 10px;
          }

          .quick-action-button {
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
               transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
          }

          .quick-action-button i svg {
               width: 15px;
               height: 15px;
          }

          .quick-action-button:hover {
               color: #0f172a;
               text-decoration: none;
               transform: translateY(-2px);
               box-shadow: 0 10px 20px rgba(15, 23, 42, .08);
          }

          .quick-action-button.is-primary {
               color: #ffffff;
               border-color: transparent;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #22d3ee);
          }

          .quick-action-button.is-primary:hover {
               color: #ffffff;
               box-shadow: 0 12px 24px rgba(99, 102, 241, .3);
          }

          /* ================================================================
                                    FILTER
                                 ================================================================= */

          .customer-filter-card {
               padding: 22px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 22px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
               backdrop-filter: blur(12px);
          }

          .customer-filter-heading {
               display: flex;
               gap: 10px;
               align-items: center;
               margin-bottom: 17px;
               color: var(--customer-text);
               font-size: .94rem;
               font-weight: 820;
          }

          .customer-filter-heading-icon {
               display: inline-flex;
               width: 38px;
               height: 38px;
               color: var(--customer-primary);
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: var(--customer-soft-purple);
          }

          .customer-filter-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .customer-filter-label {
               margin-bottom: 7px;
               color: #52627a;
               font-size: .77rem;
               font-weight: 800;
               letter-spacing: .025em;
          }

          .customer-filter-control {
               min-height: 47px;
               color: var(--customer-text);
               font-size: .87rem;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
               background-color: #ffffff;
               box-shadow: none;
          }

          .customer-filter-control:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .22rem rgba(99, 102, 241, .11);
          }

          .customer-search-shell {
               position: relative;
          }

          .customer-search-shell>svg {
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

          .customer-search-shell .form-control {
               padding-left: 43px;
          }

          .customer-filter-actions {
               display: flex;
               height: 100%;
               gap: 10px;
               align-items: flex-end;
          }

          .customer-button-primary,
          .customer-button-reset {
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

          .customer-button-primary svg,
          .customer-button-reset svg {
               width: 17px;
               height: 17px;
          }

          .customer-button-primary {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg,
                         var(--customer-primary-light),
                         var(--customer-purple),
                         var(--customer-secondary));
               box-shadow: 0 10px 21px rgba(99, 102, 241, .22);
          }

          .customer-button-primary:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(99, 102, 241, .28);
          }

          .customer-button-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #ffffff;
          }

          .customer-button-reset:hover {
               color: #334155;
               text-decoration: none;
               background: #f8fafc;
               transform: translateY(-2px);
          }

          .customer-active-filters {
               display: flex;
               flex-wrap: wrap;
               padding-top: 15px;
               gap: 8px;
               align-items: center;
               margin-top: 17px;
               border-top: 1px dashed #dbe3ef;
          }

          .customer-active-filter-label {
               color: var(--customer-muted);
               font-size: .77rem;
               font-weight: 750;
          }

          .customer-filter-chip {
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

          .customer-filter-chip svg {
               width: 13px;
               height: 13px;
          }

          /* ================================================================
                                    TABLE CARD
                                 ================================================================= */

          .customer-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .customer-card-header {
               display: flex;
               padding: 22px 24px;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #ffffff 0%, #faf8ff 48%, #f0fbff 100%);
          }

          .customer-list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--customer-text);
               font-size: 1.1rem;
               font-weight: 830;
          }

          .customer-list-title-icon {
               display: inline-flex;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               color: var(--customer-primary);
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
          }

          .customer-list-title-icon svg {
               width: 20px;
               height: 20px;
          }

          .customer-list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--customer-muted);
               font-size: .81rem;
          }

          .customer-result-badge {
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

          .customer-result-badge svg {
               width: 15px;
               height: 15px;
          }

          .customer-card-body {
               padding: 10px 18px 20px;
          }

          .customer-table {
               min-width: 1380px;
               margin-bottom: 0;
          }

          .customer-table thead th {
               padding: 15px 13px;
               color: #52627a;
               font-size: .69rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               vertical-align: middle;
               border-top: 0;
               border-bottom: 1px solid #e8edf4;
               background: #fbfcff;
          }

          .customer-table tbody td {
               padding: 16px 13px;
               color: #41506a;
               font-size: .84rem;
               vertical-align: middle;
               border-color: #eef2f7;
          }

          .customer-table tbody tr {
               transition: background .18s ease;
          }

          .customer-table tbody tr:hover {
               background: linear-gradient(90deg, #fbfdff, #faf8ff);
          }

          .customer-row-number {
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

          .customer-identity {
               display: flex;
               min-width: 245px;
               gap: 12px;
               align-items: center;
          }

          .customer-avatar {
               display: inline-flex;
               flex: 0 0 46px;
               width: 46px;
               height: 46px;
               color: #ffffff;
               font-size: .91rem;
               font-weight: 850;
               align-items: center;
               justify-content: center;
               border-radius: 15px;
               background:
                    radial-gradient(circle at 28% 20%, rgba(255, 255, 255, .30), transparent 30%),
                    linear-gradient(135deg, #4f46e5, #8b5cf6, #06b6d4);
               box-shadow: 0 9px 19px rgba(99, 102, 241, .18);
          }

          .customer-name {
               margin-bottom: 4px;
               color: var(--customer-text);
               font-size: .90rem;
               font-weight: 820;
               line-height: 1.35;
          }

          .customer-company {
               margin-bottom: 5px;
               color: #64748b;
               font-size: .73rem;
               font-weight: 650;
               line-height: 1.35;
          }

          .customer-code {
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

          .customer-type {
               display: inline-flex;
               min-width: 114px;
               padding: 7px 10px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               font-size: .73rem;
               font-weight: 820;
               border-radius: 999px;
          }

          .customer-type svg {
               width: 14px;
               height: 14px;
          }

          .customer-type.is-company {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .customer-type.is-individual {
               color: #7e22ce;
               border: 1px solid #e9d5ff;
               background: #faf5ff;
          }

          .customer-contact {
               min-width: 205px;
          }

          .customer-contact-item {
               display: flex;
               gap: 8px;
               align-items: center;
               color: #475569;
               line-height: 1.45;
          }

          .customer-contact-item+.customer-contact-item {
               margin-top: 7px;
          }

          .customer-contact-item svg {
               flex: 0 0 auto;
               width: 14px;
               height: 14px;
               color: #818cf8;
          }

          .customer-contact-item a {
               max-width: 190px;
               overflow: hidden;
               color: inherit;
               text-decoration: none;
               text-overflow: ellipsis;
               white-space: nowrap;
          }

          .customer-contact-item a:hover {
               color: var(--customer-primary);
          }

          .customer-muted-value {
               color: #a8b2c1;
               font-style: italic;
          }

          .customer-address {
               display: block;
               max-width: 245px;
               min-width: 190px;
               color: #64748b;
               line-height: 1.58;
          }

          .customer-tax {
               display: inline-flex;
               min-width: 138px;
               max-width: 180px;
               padding: 7px 10px;
               gap: 7px;
               align-items: center;
               color: #9a3412;
               font-size: .73rem;
               font-weight: 760;
               border: 1px solid #fed7aa;
               border-radius: 10px;
               background: #fff7ed;
          }

          .customer-tax svg {
               flex: 0 0 auto;
               width: 14px;
               height: 14px;
          }

          .customer-status {
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

          .customer-status-dot {
               width: 7px;
               height: 7px;
               border-radius: 999px;
               box-shadow: 0 0 0 4px rgba(255, 255, 255, .58);
          }

          .customer-status.is-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .customer-status.is-active .customer-status-dot {
               background: var(--customer-success);
          }

          .customer-status.is-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .customer-status.is-inactive .customer-status-dot {
               background: #f43f5e;
          }

          .customer-date {
               min-width: 120px;
               color: #64748b;
               font-size: .76rem;
               line-height: 1.45;
          }

          .customer-date strong {
               display: block;
               margin-bottom: 3px;
               color: #475569;
               font-size: .78rem;
          }

          .customer-actions {
               display: flex;
               min-width: 130px;
               gap: 7px;
               align-items: center;
               justify-content: flex-end;
          }

          .customer-action-button {
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

          .customer-action-button svg {
               width: 16px;
               height: 16px;
          }

          .customer-action-button:hover {
               text-decoration: none;
               transform: translateY(-2px);
          }

          .customer-action-show {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .customer-action-show:hover {
               color: #075985;
               background: #e0f2fe;
               box-shadow: 0 9px 17px rgba(14, 165, 233, .14);
          }

          .customer-action-edit {
               color: #a16207;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .customer-action-edit:hover {
               color: #854d0e;
               background: #fef3c7;
               box-shadow: 0 9px 17px rgba(245, 158, 11, .14);
          }

          .customer-action-delete {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .customer-action-delete:hover {
               color: #9f1239;
               background: #ffe4e6;
               box-shadow: 0 9px 17px rgba(244, 63, 94, .14);
          }

          .customer-empty-state {
               padding: 70px 24px !important;
               text-align: center;
          }

          .customer-empty-icon {
               display: inline-flex;
               width: 80px;
               height: 80px;
               margin-bottom: 18px;
               color: #7c3aed;
               align-items: center;
               justify-content: center;
               border: 1px solid #ddd6fe;
               border-radius: 25px;
               background: linear-gradient(135deg, #f5f3ff, #e0f2fe);
               box-shadow: 0 14px 28px rgba(99, 102, 241, .10);
          }

          .customer-empty-icon svg {
               width: 34px;
               height: 34px;
          }

          .customer-empty-title {
               margin: 0 0 7px;
               color: var(--customer-text);
               font-size: 1.06rem;
               font-weight: 830;
          }

          .customer-empty-description {
               max-width: 500px;
               margin: 0 auto;
               color: var(--customer-muted);
               font-size: .85rem;
               line-height: 1.68;
          }

          /* ================================================================
                                    PAGINATION
                                 ================================================================= */

          .customer-pagination-wrap {
               display: flex;
               flex-wrap: wrap;
               padding: 18px 7px 0;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               border-top: 1px solid #eef2f7;
          }

          .customer-pagination-info {
               color: var(--customer-muted);
               font-size: .78rem;
               font-weight: 650;
          }

          .customer-pagination-info strong {
               color: var(--customer-text);
          }

          .customer-pagination-wrap .pagination {
               gap: 5px;
               margin: 0;
          }

          .customer-pagination-wrap .page-link {
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

          .customer-pagination-wrap .page-link:hover {
               color: #4f46e5;
               border-color: #c7d2fe;
               background: #eef2ff;
          }

          .customer-pagination-wrap .page-item.active .page-link {
               color: #ffffff;
               border-color: transparent;
               background: linear-gradient(135deg, #6366f1, #8b5cf6);
               box-shadow: 0 8px 17px rgba(99, 102, 241, .20);
          }

          .customer-pagination-wrap .page-item.disabled .page-link {
               color: #cbd5e1;
               background: #f8fafc;
          }

          /* ================================================================
                                    RESPONSIVE
                                 ================================================================= */

          @media (max-width: 1199.98px) {
               .customer-hero-content {
                    align-items: flex-start;
               }

               .customer-hero-actions {
                    flex-direction: column;
                    align-items: stretch;
               }

               .customer-hero-button {
                    width: 100%;
               }
          }

          @media (max-width: 991.98px) {
               .customer-page {
                    padding: 22px 14px 36px;
               }

               .customer-hero {
                    padding: 27px;
                    border-radius: 23px;
               }

               .customer-hero-content {
                    flex-direction: column;
               }

               .customer-hero-actions {
                    width: 100%;
                    flex-direction: row;
               }

               .customer-card-header {
                    align-items: flex-start;
               }

               .quick-action-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
               }
          }

          @media (max-width: 767.98px) {
               .customer-hero {
                    padding: 23px 20px;
               }

               .customer-hero-heading {
                    align-items: flex-start;
               }

               .customer-hero-icon {
                    flex-basis: 55px;
                    width: 55px;
                    height: 55px;
                    border-radius: 17px;
               }

               .customer-hero h1 {
                    font-size: 1.58rem;
               }

               .customer-hero p {
                    font-size: .88rem;
               }

               .customer-hero-actions {
                    flex-direction: column;
               }

               .quick-action-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .customer-filter-card {
                    padding: 18px;
               }

               .customer-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }

               .customer-card-header {
                    flex-direction: column;
               }

               .customer-result-badge {
                    margin-left: 53px;
               }

               .customer-pagination-wrap {
                    justify-content: center;
                    text-align: center;
               }
          }

          @media (max-width: 479.98px) {
               .customer-page {
                    padding-right: 10px;
                    padding-left: 10px;
               }

               .customer-hero-heading {
                    flex-direction: column;
               }

               .customer-hero-actions,
               .customer-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr;
               }

               .quick-action-grid {
                    grid-template-columns: 1fr;
               }

               .customer-list-subtitle,
               .customer-result-badge {
                    margin-left: 0;
               }
          }
     </style>

     <div class="customer-page">
          <div class="customer-container">
               {{-- ============================================================
                 HERO
            ============================================================= --}}
               <section class="customer-hero">
                    <div class="customer-hero-content">
                         <div class="customer-hero-heading">
                              <div class="customer-hero-icon" aria-hidden="true">
                                   <i data-feather="users"></i>
                              </div>

                              <div>
                                   <h1>Dashboard Monitoring Produktivitas Karyawan</h1>

                                   <p>
                                        Pantau performa karyawan dan transaksi jasa dari aktivitas harian,
                                        service order, invoice, hingga status pembayaran dalam satu tampilan modern.
                                   </p>
                              </div>
                         </div>

                         <div class="customer-hero-actions">
                              @if ($routeHas('super-admin.employee-activities.index'))
                                   <a href="{{ route('super-admin.employee-activities.index') }}"
                                        class="customer-hero-button is-soft">
                                        <i data-feather="activity"></i>
                                        <span>Aktivitas</span>
                                   </a>
                              @endif

                              @if ($routeHas('super-admin.service-orders.index'))
                                   <a href="{{ route('super-admin.service-orders.index') }}"
                                        class="customer-hero-button is-soft">
                                        <i data-feather="clipboard"></i>
                                        <span>Service Order</span>
                                   </a>
                              @endif

                              @if ($canAccessTrash && $routeHas('super-admin.customers.trash'))
                                   <a href="{{ route('super-admin.customers.trash') }}" class="customer-hero-button is-soft">
                                        <i data-feather="trash-2"></i>
                                        <span>Sampah</span>
                                   </a>
                              @endif

                              @if ($canManageCustomers && $routeHas('super-admin.customers.create'))
                                   <a href="{{ route('super-admin.customers.create') }}" class="customer-hero-button">
                                        <i data-feather="user-plus"></i>
                                        <span>Tambah Pelanggan</span>
                                   </a>
                              @endif
                         </div>
                    </div>
               </section>

               {{-- ============================================================
                 FLASH MESSAGE
            ============================================================= --}}
               @if (session('success'))
                    <div class="alert alert-dismissible fade show customer-alert customer-alert-success" role="alert">
                         <i data-feather="check-circle"></i>
                         <span>{{ session('success') }}</span>

                         <button type="button" class="customer-alert-close" data-bs-dismiss="alert"
                              aria-label="Tutup pesan">
                              <i data-feather="x"></i>
                         </button>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-dismissible fade show customer-alert customer-alert-danger" role="alert">
                         <i data-feather="alert-circle"></i>
                         <span>{{ session('error') }}</span>

                         <button type="button" class="customer-alert-close" data-bs-dismiss="alert"
                              aria-label="Tutup pesan">
                              <i data-feather="x"></i>
                         </button>
                    </div>
               @endif

               {{-- ============================================================
                 STATISTICS
            ============================================================= --}}
               <div class="row g-3 customer-stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="customer-stat-card customer-stat-total">
                              <div class="customer-stat-inner">
                                   <div>
                                        <div class="customer-stat-title">Total Karyawan</div>
                                        <div class="customer-stat-value">
                                             {{ number_format((int) $monitoringStats['employees_total']) }}
                                        </div>
                                        <div class="customer-stat-caption">
                                             {{ number_format((int) $monitoringStats['employees_active']) }} karyawan aktif
                                        </div>
                                   </div>

                                   <span class="customer-stat-icon">
                                        <i data-feather="users"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="customer-stat-card customer-stat-active">
                              <div class="customer-stat-inner">
                                   <div>
                                        <div class="customer-stat-title">Aktivitas Hari Ini</div>
                                        <div class="customer-stat-value">
                                             {{ number_format((int) $monitoringStats['activities_today']) }}
                                        </div>
                                        <div class="customer-stat-caption">
                                             {{ number_format((int) $monitoringStats['activities_pending_verify']) }} pending
                                             verifikasi
                                        </div>
                                   </div>

                                   <span class="customer-stat-icon">
                                        <i data-feather="activity"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="customer-stat-card customer-stat-company">
                              <div class="customer-stat-inner">
                                   <div>
                                        <div class="customer-stat-title">Order Jasa Bulan Ini</div>
                                        <div class="customer-stat-value">
                                             {{ number_format((int) $monitoringStats['service_orders_this_month']) }}
                                        </div>
                                        <div class="customer-stat-caption">
                                             {{ number_format((int) $monitoringStats['service_orders_processing']) }} sedang
                                             diproses
                                        </div>
                                   </div>

                                   <span class="customer-stat-icon">
                                        <i data-feather="package"></i>
                                   </span>
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="customer-stat-card customer-stat-individual">
                              <div class="customer-stat-inner">
                                   <div>
                                        <div class="customer-stat-title">Payment Terkonfirmasi</div>
                                        <div class="customer-stat-value">
                                             Rp
                                             {{ number_format((float) $monitoringStats['payments_confirmed_this_month'], 0, ',', '.') }}
                                        </div>
                                        <div class="customer-stat-caption">
                                             {{ number_format((int) $monitoringStats['invoices_unpaid']) }} invoice belum
                                             lunas
                                        </div>
                                   </div>

                                   <span class="customer-stat-icon">
                                        <i data-feather="dollar-sign"></i>
                                   </span>
                              </div>
                         </article>
                    </div>
               </div>

               <div class="row g-3 monitoring-row">
                    <div class="col-12 col-xxl-8">
                         <section class="monitoring-card">
                              <div class="monitoring-title">
                                   <i data-feather="bar-chart-2"></i>
                                   <span>Monitoring Produktivitas dan Transaksi Jasa</span>
                              </div>

                              <div class="row g-3">
                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitoring-mini">
                                             <span class="monitoring-mini-label">Aktivitas Terverifikasi</span>
                                             <span class="monitoring-mini-value">
                                                  {{ number_format(max((int) $monitoringStats['activities_today'] - (int) $monitoringStats['activities_pending_verify'], 0)) }}
                                             </span>
                                             <div class="monitoring-mini-caption">
                                                  dari {{ number_format((int) $monitoringStats['activities_today']) }}
                                                  aktivitas hari ini
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitoring-mini">
                                             <span class="monitoring-mini-label">Pendapatan Jasa/Bulan</span>
                                             <span class="monitoring-mini-value">
                                                  Rp
                                                  {{ number_format((float) $monitoringStats['service_revenue_this_month'], 0, ',', '.') }}
                                             </span>
                                             <div class="monitoring-mini-caption">
                                                  berdasarkan invoice bulan berjalan
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitoring-mini">
                                             <span class="monitoring-mini-label">Payment Pending</span>
                                             <span class="monitoring-mini-value">
                                                  {{ number_format((int) $monitoringStats['payments_pending']) }}
                                             </span>
                                             <div class="monitoring-mini-caption">
                                                  membutuhkan tindak lanjut
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-xl-3">
                                        <div class="monitoring-mini">
                                             <span class="monitoring-mini-label">Pelanggan Aktif (Halaman)</span>
                                             <span class="monitoring-mini-value">
                                                  {{ number_format((int) $activeOnPage) }}
                                             </span>
                                             <div class="monitoring-mini-caption">
                                                  dari {{ number_format((int) $currentPageCount) }} data halaman saat ini
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </section>
                    </div>

                    <div class="col-12 col-xxl-4">
                         <section class="quick-action-card">
                              <div class="quick-action-title">
                                   <i data-feather="grid"></i>
                                   <span>Button Aksi Cepat</span>
                              </div>

                              <div class="quick-action-grid">
                                   @if ($canManageCustomers && $routeHas('super-admin.customers.create'))
                                        <a href="{{ route('super-admin.customers.create') }}"
                                             class="quick-action-button is-primary">
                                             <i data-feather="user-plus"></i>
                                             <span>Tambah Pelanggan</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.customers.index'))
                                        <a href="{{ route('super-admin.customers.index') }}" class="quick-action-button">
                                             <i data-feather="users"></i>
                                             <span>Daftar Pelanggan</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.employee-activities.index'))
                                        <a href="{{ route('super-admin.employee-activities.index') }}"
                                             class="quick-action-button">
                                             <i data-feather="activity"></i>
                                             <span>Data Aktivitas</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.employee-activities.create'))
                                        <a href="{{ route('super-admin.employee-activities.create') }}"
                                             class="quick-action-button">
                                             <i data-feather="plus-square"></i>
                                             <span>Input Aktivitas</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.service-orders.index'))
                                        <a href="{{ route('super-admin.service-orders.index') }}"
                                             class="quick-action-button">
                                             <i data-feather="clipboard"></i>
                                             <span>Service Order</span>
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin && $routeHas('super-admin.service-orders.create'))
                                        <a href="{{ route('super-admin.service-orders.create') }}"
                                             class="quick-action-button">
                                             <i data-feather="file-plus"></i>
                                             <span>Buat Order</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.invoices.index'))
                                        <a href="{{ route('super-admin.invoices.index') }}" class="quick-action-button">
                                             <i data-feather="file-text"></i>
                                             <span>Daftar Invoice</span>
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin && $routeHas('super-admin.invoices.create'))
                                        <a href="{{ route('super-admin.invoices.create') }}" class="quick-action-button">
                                             <i data-feather="file-plus"></i>
                                             <span>Buat Invoice</span>
                                        </a>
                                   @endif

                                   @if ($routeHas('super-admin.payments.index'))
                                        <a href="{{ route('super-admin.payments.index') }}" class="quick-action-button">
                                             <i data-feather="credit-card"></i>
                                             <span>Daftar Payment</span>
                                        </a>
                                   @endif

                                   @if ($isSuperAdmin && $routeHas('super-admin.payments.create'))
                                        <a href="{{ route('super-admin.payments.create') }}" class="quick-action-button">
                                             <i data-feather="dollar-sign"></i>
                                             <span>Input Payment</span>
                                        </a>
                                   @endif
                              </div>
                         </section>
                    </div>
               </div>

               {{-- ============================================================
                 FILTER
            ============================================================= --}}
               <section class="customer-filter-card">
                    <div class="customer-filter-heading">
                         <span class="customer-filter-heading-icon">
                              <i data-feather="filter"></i>
                         </span>

                         <span>Pencarian dan Filter Pelanggan</span>
                    </div>

                    <form method="GET" action="{{ route('super-admin.customers.index') }}">
                         <div class="row g-3">
                              <div class="col-12 col-xl-5">
                                   <label for="customer-search" class="form-label customer-filter-label">
                                        Pencarian
                                   </label>

                                   <div class="customer-search-shell">
                                        <i data-feather="search"></i>

                                        <input type="search" id="customer-search" name="search"
                                             value="{{ $search }}" class="form-control customer-filter-control"
                                             placeholder="Kode, nama, perusahaan, telepon, email, NPWP..."
                                             autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label for="customer-type" class="form-label customer-filter-label">
                                        Jenis Pelanggan
                                   </label>

                                   <select id="customer-type" name="customer_type"
                                        class="form-select customer-filter-control">
                                        <option value="">Semua Jenis</option>

                                        @foreach ($customerTypeOptions as $value => $label)
                                             <option value="{{ $value }}" @selected($customerType === (string) $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label for="customer-status" class="form-label customer-filter-label">
                                        Status
                                   </label>

                                   <select id="customer-status" name="status"
                                        class="form-select customer-filter-control">
                                        <option value="">Semua Status</option>

                                        @foreach ($statusOptions as $value => $label)
                                             <option value="{{ $value }}" @selected($status === (string) $value)>
                                                  {{ $label }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-xl-3">
                                   <div class="customer-filter-actions">
                                        <button type="submit" class="customer-button-primary">
                                             <i data-feather="search"></i>
                                             <span>Terapkan Filter</span>
                                        </button>

                                        <a href="{{ route('super-admin.customers.index') }}"
                                             class="customer-button-reset">
                                             <i data-feather="rotate-ccw"></i>
                                             <span>Reset</span>
                                        </a>
                                   </div>
                              </div>
                         </div>

                         @if ($hasActiveFilters)
                              <div class="customer-active-filters">
                                   <span class="customer-active-filter-label">
                                        Filter aktif:
                                   </span>

                                   @if ($search !== '')
                                        <span class="customer-filter-chip">
                                             <i data-feather="search"></i>
                                             Kata kunci: {{ $search }}
                                        </span>
                                   @endif

                                   @if ($customerType !== '')
                                        <span class="customer-filter-chip">
                                             <i data-feather="users"></i>
                                             Jenis: {{ $typeLabel($customerType) }}
                                        </span>
                                   @endif

                                   @if ($status !== '')
                                        <span class="customer-filter-chip">
                                             <i data-feather="activity"></i>
                                             Status: {{ $statusLabel($status) }}
                                        </span>
                                   @endif
                              </div>
                         @endif
                    </form>
               </section>

               {{-- ============================================================
                 CUSTOMER TABLE
            ============================================================= --}}
               <section class="customer-card">
                    <header class="customer-card-header">
                         <div>
                              <h2 class="customer-list-title">
                                   <span class="customer-list-title-icon">
                                        <i data-feather="list"></i>
                                   </span>

                                   <span>Daftar Pelanggan</span>
                              </h2>

                              <p class="customer-list-subtitle">
                                   Informasi pelanggan yang tersimpan dan belum dihapus.
                              </p>
                         </div>

                         <span class="customer-result-badge">
                              <i data-feather="layers"></i>
                              {{ number_format($currentPageCount) }} data di halaman ini
                         </span>
                    </header>

                    <div class="customer-card-body">
                         <div class="table-responsive">
                              <table class="table customer-table align-middle">
                                   <thead>
                                        <tr>
                                             <th style="width: 72px;">No.</th>
                                             <th>Pelanggan</th>
                                             <th>Jenis</th>
                                             <th>Kontak</th>
                                             <th>Alamat</th>
                                             <th>Nomor Pajak</th>
                                             <th>Status</th>
                                             <th>Diperbarui</th>
                                             <th class="text-end">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($customers as $customer)
                                             @php
                                                  $customerName = trim((string) $customer->name);

                                                  $initials = \Illuminate\Support\Str::of($customerName)
                                                      ->explode(' ')
                                                      ->filter()
                                                      ->take(2)
                                                      ->map(
                                                          static fn($word): string => \Illuminate\Support\Str::upper(
                                                              \Illuminate\Support\Str::substr((string) $word, 0, 1),
                                                          ),
                                                      )
                                                      ->implode('');

                                                  $displayInitials = $initials !== '' ? $initials : 'CU';

                                                  $isCompany =
                                                      $customer->customer_type === \App\Models\Customer::TYPE_COMPANY;
                                             @endphp

                                             <tr>
                                                  <td>
                                                       <span class="customer-row-number">
                                                            {{ method_exists($customers, 'firstItem') ? ($customers->firstItem() ?? 1) + $loop->index : $loop->iteration }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="customer-identity">
                                                            <span class="customer-avatar" aria-hidden="true">
                                                                 {{ $displayInitials }}
                                                            </span>

                                                            <div>
                                                                 <div class="customer-name">
                                                                      {{ $customer->name }}
                                                                 </div>

                                                                 @if ($isCompany && filled($customer->company_name))
                                                                      <div class="customer-company">
                                                                           {{ $customer->company_name }}
                                                                      </div>
                                                                 @endif

                                                                 <span class="customer-code">
                                                                      {{ $customer->customer_code }}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <span
                                                            class="customer-type {{ $isCompany ? 'is-company' : 'is-individual' }}">
                                                            <i data-feather="{{ $isCompany ? 'briefcase' : 'user' }}"></i>
                                                            {{ $typeLabel($customer->customer_type) }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="customer-contact">
                                                            @if (filled($customer->phone))
                                                                 <div class="customer-contact-item">
                                                                      <i data-feather="phone"></i>
                                                                      <a href="tel:{{ $customer->phone }}">
                                                                           {{ $customer->phone }}
                                                                      </a>
                                                                 </div>
                                                            @else
                                                                 <div class="customer-contact-item customer-muted-value">
                                                                      <i data-feather="phone-off"></i>
                                                                      Telepon belum tersedia
                                                                 </div>
                                                            @endif

                                                            @if (filled($customer->email))
                                                                 <div class="customer-contact-item">
                                                                      <i data-feather="mail"></i>
                                                                      <a href="mailto:{{ $customer->email }}"
                                                                           title="{{ $customer->email }}">
                                                                           {{ $customer->email }}
                                                                      </a>
                                                                 </div>
                                                            @else
                                                                 <div class="customer-contact-item customer-muted-value">
                                                                      <i data-feather="mail"></i>
                                                                      Email belum tersedia
                                                                 </div>
                                                            @endif
                                                       </div>
                                                  </td>

                                                  <td>
                                                       @if (filled($customer->address))
                                                            <span class="customer-address"
                                                                 title="{{ $customer->address }}">
                                                                 {{ \Illuminate\Support\Str::limit($customer->address, 90) }}
                                                            </span>
                                                       @else
                                                            <span class="customer-muted-value">
                                                                 Alamat belum tersedia
                                                            </span>
                                                       @endif
                                                  </td>

                                                  <td>
                                                       @if (filled($customer->tax_number))
                                                            <span class="customer-tax">
                                                                 <i data-feather="file-text"></i>
                                                                 {{ $customer->tax_number }}
                                                            </span>
                                                       @else
                                                            <span class="customer-muted-value">
                                                                 Tidak tersedia
                                                            </span>
                                                       @endif
                                                  </td>

                                                  <td>
                                                       <span
                                                            class="customer-status {{ $customer->status === \App\Models\Customer::STATUS_ACTIVE ? 'is-active' : 'is-inactive' }}">
                                                            <span class="customer-status-dot"></span>
                                                            {{ $statusLabel($customer->status) }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <div class="customer-date">
                                                            <strong>
                                                                 {{ optional($customer->updated_at)->format('d M Y') ?? '-' }}
                                                            </strong>

                                                            <span>
                                                                 {{ optional($customer->updated_at)->format('H:i') ?? '-' }}
                                                                 WIB
                                                            </span>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <div class="customer-actions">
                                                            @if ($routeHas('super-admin.customers.show'))
                                                                 <a href="{{ route('super-admin.customers.show', $customer) }}"
                                                                      class="customer-action-button customer-action-show"
                                                                      title="Lihat detail {{ $customer->name }}"
                                                                      aria-label="Lihat detail {{ $customer->name }}">
                                                                      <i data-feather="eye"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($canManageCustomers && $routeHas('super-admin.customers.edit'))
                                                                 <a href="{{ route('super-admin.customers.edit', $customer) }}"
                                                                      class="customer-action-button customer-action-edit"
                                                                      title="Edit {{ $customer->name }}"
                                                                      aria-label="Edit {{ $customer->name }}">
                                                                      <i data-feather="edit-3"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($canManageCustomers && $routeHas('super-admin.customers.destroy'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.customers.destroy', $customer) }}"
                                                                      class="d-inline"
                                                                      onsubmit="return confirm(
                                                              'Yakin ingin menghapus pelanggan {{ addslashes($customer->name) }}? Data akan dipindahkan ke sampah.'
                                                          );">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="customer-action-button customer-action-delete"
                                                                           title="Hapus {{ $customer->name }}"
                                                                           aria-label="Hapus {{ $customer->name }}">
                                                                           <i data-feather="trash-2"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="9" class="customer-empty-state">
                                                       <span class="customer-empty-icon">
                                                            <i
                                                                 data-feather="{{ $hasActiveFilters ? 'search' : 'users' }}"></i>
                                                       </span>

                                                       <h3 class="customer-empty-title">
                                                            {{ $hasActiveFilters ? 'Pelanggan tidak ditemukan' : 'Data pelanggan belum tersedia' }}
                                                       </h3>

                                                       <p class="customer-empty-description">
                                                            @if ($hasActiveFilters)
                                                                 Tidak ada pelanggan yang sesuai dengan kata kunci
                                                                 atau filter yang dipilih. Ubah filter atau tampilkan
                                                                 kembali seluruh data pelanggan.
                                                            @else
                                                                 Belum ada pelanggan yang tercatat di dalam sistem.
                                                                 Tambahkan pelanggan pertama untuk mulai mengelola
                                                                 data pelanggan perusahaan.
                                                            @endif
                                                       </p>

                                                       @if ($hasActiveFilters)
                                                            <a href="{{ route('super-admin.customers.index') }}"
                                                                 class="customer-button-reset mt-3">
                                                                 <i data-feather="rotate-ccw"></i>
                                                                 <span>Reset Filter</span>
                                                            </a>
                                                       @elseif ($canManageCustomers && $routeHas('super-admin.customers.create'))
                                                            <a href="{{ route('super-admin.customers.create') }}"
                                                                 class="customer-button-primary mt-3">
                                                                 <i data-feather="user-plus"></i>
                                                                 <span>Tambah Pelanggan Pertama</span>
                                                            </a>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($customers->hasPages())
                              <div class="customer-pagination-wrap">
                                   <div class="customer-pagination-info">
                                        Menampilkan
                                        <strong>
                                             {{ number_format($customers->firstItem() ?? 0) }}
                                        </strong>
                                        sampai
                                        <strong>
                                             {{ number_format($customers->lastItem() ?? 0) }}
                                        </strong>
                                        dari
                                        <strong>
                                             {{ number_format($customers->total()) }}
                                        </strong>
                                        pelanggan
                                   </div>

                                   <div>
                                        {{ $customers->onEachSide(1)->links() }}
                                   </div>
                              </div>
                         @elseif ($filteredTotal > 0)
                              <div class="customer-pagination-wrap">
                                   <div class="customer-pagination-info">
                                        Menampilkan seluruh
                                        <strong>{{ number_format($filteredTotal) }}</strong>
                                        pelanggan pada halaman ini.
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
