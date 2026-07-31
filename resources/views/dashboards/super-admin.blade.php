@extends('layouts.app')

@section('title', 'Dashboard Super Admin | Monitoring Kinerja & Kepuasan Pelanggan')

@section('content')
     @php
          /*
        |--------------------------------------------------------------------------
        | DASHBOARD SUPER ADMIN
        |--------------------------------------------------------------------------
        | Data contoh di bawah dapat dipindahkan ke controller/service.
        | Struktur variabel dipertahankan agar view dapat langsung digunakan
        | sebagai prototipe sebelum seluruh modul terhubung ke database.
        */

          $currentUser = auth()->user();
          $currentUserName = $currentUser?->name ?? 'Super Admin';
          $currentUserRole = strtoupper(str_replace('_', ' ', $currentUser?->role ?? 'SUPER_ADMIN'));

          $usersUrl = Route::has('admin.users.index') ? route('admin.users.index') : '#';
          $reportsUrl = Route::has('super-admin.reports.index') ? route('super-admin.reports.index') : '#';
          $settingsUrl = Route::has('super-admin.settings.index') ? route('super-admin.settings.index') : '#';
          $surveysUrl = Route::has('super-admin.surveys.index') ? route('super-admin.surveys.index') : '#';
          $complaintsUrl = Route::has('super-admin.complaints.index') ? route('super-admin.complaints.index') : '#';

          $dashboardStatistics = $dashboardStatistics ?? [
              [
                  'label' => 'Capaian Kinerja',
                  'value' => 86.4,
                  'suffix' => '%',
                  'icon' => 'activity',
                  'description' => 'Rata-rata realisasi KPI seluruh unit kerja',
                  'trend' => '+4,2%',
                  'trend_type' => 'up',
                  'theme' => 'indigo',
              ],
              [
                  'label' => 'Indeks Kepuasan',
                  'value' => 88.7,
                  'suffix' => '%',
                  'icon' => 'smile',
                  'description' => '1.284 respons pelanggan pada periode berjalan',
                  'trend' => '+2,8%',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Keluhan Aktif',
                  'value' => 14,
                  'suffix' => '',
                  'icon' => 'message-square',
                  'description' => '5 keluhan melewati target waktu penyelesaian',
                  'trend' => '-6',
                  'trend_type' => 'up',
                  'theme' => 'orange',
              ],
              [
                  'label' => 'Pengguna Aktif',
                  'value' => 126,
                  'suffix' => '',
                  'icon' => 'users',
                  'description' => '8 role dan 12 unit kerja terhubung ke sistem',
                  'trend' => '+9',
                  'trend_type' => 'up',
                  'theme' => 'blue',
              ],
          ];

          $performanceTrend = $performanceTrend ?? [
              ['month' => 'Jan', 'full_month' => 'Januari', 'target' => 90, 'actual' => 82, 'satisfaction' => 84],
              ['month' => 'Feb', 'full_month' => 'Februari', 'target' => 90, 'actual' => 84, 'satisfaction' => 85],
              ['month' => 'Mar', 'full_month' => 'Maret', 'target' => 91, 'actual' => 86, 'satisfaction' => 87],
              ['month' => 'Apr', 'full_month' => 'April', 'target' => 91, 'actual' => 85, 'satisfaction' => 86],
              ['month' => 'Mei', 'full_month' => 'Mei', 'target' => 92, 'actual' => 88, 'satisfaction' => 89],
              ['month' => 'Jun', 'full_month' => 'Juni', 'target' => 92, 'actual' => 89, 'satisfaction' => 90],
          ];

          $actualAverage =
              count($performanceTrend) > 0
                  ? round(array_sum(array_column($performanceTrend, 'actual')) / count($performanceTrend), 1)
                  : 0;

          $targetAverage =
              count($performanceTrend) > 0
                  ? round(array_sum(array_column($performanceTrend, 'target')) / count($performanceTrend), 1)
                  : 0;

          $satisfactionSummary = $satisfactionSummary ?? [
              'score' => 88.7,
              'respondents' => 1284,
              'response_rate' => 81.2,
              'positive' => 76,
              'neutral' => 17,
              'negative' => 7,
              'items' => [
                  ['label' => 'Kualitas pelayanan', 'value' => 91, 'class' => 'success'],
                  ['label' => 'Kecepatan respons', 'value' => 86, 'class' => 'info'],
                  ['label' => 'Penyelesaian keluhan', 'value' => 82, 'class' => 'warning'],
                  ['label' => 'Kemudahan akses layanan', 'value' => 89, 'class' => 'primary'],
              ],
          ];

          $satisfactionAngle = round(min(100, max(0, (float) $satisfactionSummary['score'])) * 3.6, 1);

          $monitoringPriorities = $monitoringPriorities ?? [
              [
                  'title' => 'KPI unit belum mencapai target',
                  'description' => 'Tiga unit kerja masih berada di bawah 80% dan memerlukan rencana perbaikan.',
                  'icon' => 'trending-down',
                  'status' => 'Mendesak',
                  'status_class' => 'danger',
                  'action' => 'Tinjau kinerja',
                  'url' => $reportsUrl,
              ],
              [
                  'title' => 'Keluhan melewati SLA',
                  'description' => 'Lima keluhan pelanggan belum selesai setelah melewati batas waktu layanan.',
                  'icon' => 'clock',
                  'status' => 'Perhatian',
                  'status_class' => 'warning',
                  'action' => 'Buka keluhan',
                  'url' => $complaintsUrl,
              ],
              [
                  'title' => 'Respons survei masih rendah',
                  'description' => 'Unit Layanan Digital mencatat tingkat respons survei sebesar 61%.',
                  'icon' => 'bar-chart',
                  'status' => 'Dipantau',
                  'status_class' => 'info',
                  'action' => 'Lihat survei',
                  'url' => $surveysUrl,
              ],
              [
                  'title' => 'Akun tidak aktif',
                  'description' => 'Tujuh akun belum melakukan login selama lebih dari 60 hari.',
                  'icon' => 'user-x',
                  'status' => 'Administratif',
                  'status_class' => 'neutral',
                  'action' => 'Kelola akun',
                  'url' => $usersUrl,
              ],
          ];

          $unitMonitoring = $unitMonitoring ?? [
              [
                  'code' => 'UNIT-001',
                  'unit' => 'Layanan Pelanggan',
                  'leader' => 'Dewi Lestari',
                  'target' => 92,
                  'realization' => 94,
                  'satisfaction' => 93,
                  'complaints' => 2,
                  'status' => 'Sangat Baik',
                  'updated_at' => 'Hari ini, 06.45',
              ],
              [
                  'code' => 'UNIT-002',
                  'unit' => 'Operasional',
                  'leader' => 'Bagus Pratama',
                  'target' => 90,
                  'realization' => 87,
                  'satisfaction' => 86,
                  'complaints' => 3,
                  'status' => 'Baik',
                  'updated_at' => 'Hari ini, 06.32',
              ],
              [
                  'code' => 'UNIT-003',
                  'unit' => 'Layanan Digital',
                  'leader' => 'Rizky Maulana',
                  'target' => 91,
                  'realization' => 79,
                  'satisfaction' => 81,
                  'complaints' => 5,
                  'status' => 'Perlu Perhatian',
                  'updated_at' => 'Hari ini, 06.20',
              ],
              [
                  'code' => 'UNIT-004',
                  'unit' => 'Administrasi',
                  'leader' => 'Nadia Putri',
                  'target' => 88,
                  'realization' => 86,
                  'satisfaction' => 88,
                  'complaints' => 1,
                  'status' => 'Baik',
                  'updated_at' => 'Kemarin, 17.40',
              ],
              [
                  'code' => 'UNIT-005',
                  'unit' => 'Pengaduan & Tindak Lanjut',
                  'leader' => 'Arif Setiawan',
                  'target' => 90,
                  'realization' => 76,
                  'satisfaction' => 78,
                  'complaints' => 8,
                  'status' => 'Kritis',
                  'updated_at' => 'Kemarin, 16.55',
              ],
              [
                  'code' => 'UNIT-006',
                  'unit' => 'Quality Assurance',
                  'leader' => 'Rina Maharani',
                  'target' => 93,
                  'realization' => 91,
                  'satisfaction' => 90,
                  'complaints' => 0,
                  'status' => 'Sangat Baik',
                  'updated_at' => 'Kemarin, 16.10',
              ],
          ];

          $channelPerformance = $channelPerformance ?? [
              [
                  'name' => 'WhatsApp',
                  'responses' => 438,
                  'score' => 91,
                  'icon' => 'message-circle',
                  'class' => 'success',
              ],
              ['name' => 'Email', 'responses' => 286, 'score' => 87, 'icon' => 'mail', 'class' => 'info'],
              ['name' => 'Telepon', 'responses' => 245, 'score' => 85, 'icon' => 'phone', 'class' => 'warning'],
              [
                  'name' => 'Layanan Langsung',
                  'responses' => 315,
                  'score' => 90,
                  'icon' => 'map-pin',
                  'class' => 'primary',
              ],
          ];

          $roleSummary = $roleSummary ?? [
              ['name' => 'Super Admin', 'users' => 2, 'active' => 2, 'icon' => 'shield'],
              ['name' => 'Direktur Utama', 'users' => 1, 'active' => 1, 'icon' => 'briefcase'],
              ['name' => 'Kepala Unit', 'users' => 12, 'active' => 12, 'icon' => 'layers'],
              ['name' => 'Petugas Layanan', 'users' => 87, 'active' => 81, 'icon' => 'headphones'],
              ['name' => 'Auditor', 'users' => 8, 'active' => 7, 'icon' => 'check-square'],
              ['name' => 'Viewer', 'users' => 23, 'active' => 23, 'icon' => 'eye'],
          ];

          $systemActivities = $systemActivities ?? [
              [
                  'title' => 'Laporan kinerja diperbarui',
                  'description' => 'Unit Layanan Pelanggan mengirim realisasi KPI periode Juli 2026.',
                  'time' => '8 menit lalu',
                  'icon' => 'activity',
                  'theme' => 'green',
              ],
              [
                  'title' => 'Survei kepuasan baru diterbitkan',
                  'description' => 'Survei layanan triwulan III telah aktif untuk seluruh kanal pelayanan.',
                  'time' => '24 menit lalu',
                  'icon' => 'clipboard',
                  'theme' => 'blue',
              ],
              [
                  'title' => 'Keluhan dieskalasi',
                  'description' => 'Keluhan KLP-2607-014 dinaikkan ke Kepala Unit karena melewati SLA.',
                  'time' => '41 menit lalu',
                  'icon' => 'alert-triangle',
                  'theme' => 'red',
              ],
              [
                  'title' => 'Akun pengguna dibuat',
                  'description' => 'Akun baru untuk petugas layanan Unit Operasional berhasil diaktifkan.',
                  'time' => '1 jam lalu',
                  'icon' => 'user-plus',
                  'theme' => 'purple',
              ],
              [
                  'title' => 'Pencadangan sistem selesai',
                  'description' => 'Backup database harian berhasil tanpa kesalahan.',
                  'time' => '2 jam lalu',
                  'icon' => 'database',
                  'theme' => 'orange',
              ],
          ];

          $quickActions = [
              [
                  'label' => 'Kelola Pengguna',
                  'description' => 'Akun, role, dan status',
                  'icon' => 'users',
                  'url' => $usersUrl,
              ],
              [
                  'label' => 'Kelola Survei',
                  'description' => 'Form dan periode survei',
                  'icon' => 'clipboard',
                  'url' => $surveysUrl,
              ],
              [
                  'label' => 'Kelola Keluhan',
                  'description' => 'SLA dan tindak lanjut',
                  'icon' => 'message-square',
                  'url' => $complaintsUrl,
              ],
              [
                  'label' => 'Pengaturan Sistem',
                  'description' => 'Konfigurasi aplikasi',
                  'icon' => 'settings',
                  'url' => $settingsUrl,
              ],
          ];
     @endphp

     <style>
          .sad-dashboard {
               --sad-primary: #4f46e5;
               --sad-primary-dark: #3730a3;
               --sad-primary-soft: rgba(79, 70, 229, 0.12);
               --sad-secondary: #0f766e;
               --sad-success: #16a34a;
               --sad-warning: #d97706;
               --sad-danger: #dc2626;
               --sad-info: #2563eb;
               --sad-purple: #7c3aed;
               --sad-heading: #172033;
               --sad-text: #5f6b7a;
               --sad-muted: #8b95a5;
               --sad-border: #e8ecf3;
               --sad-background: #f4f6fa;
               --sad-card: #ffffff;
               --sad-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
               --sad-shadow-hover: 0 18px 42px rgba(15, 23, 42, 0.11);

               width: 100%;
               min-height: 100vh;
               padding: 24px;
               background:
                    radial-gradient(circle at top right, rgba(79, 70, 229, 0.08), transparent 28%),
                    radial-gradient(circle at bottom left, rgba(15, 118, 110, 0.05), transparent 24%),
                    var(--sad-background);
               color: var(--sad-text);
          }

          html[data-theme="dark"] .sad-dashboard,
          body.dark-theme .sad-dashboard,
          body.dark-mode .sad-dashboard {
               --sad-heading: #f8fafc;
               --sad-text: #cbd5e1;
               --sad-muted: #94a3b8;
               --sad-border: rgba(148, 163, 184, 0.16);
               --sad-background: #0f172a;
               --sad-card: #182235;
               --sad-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
               --sad-shadow-hover: 0 18px 42px rgba(0, 0, 0, 0.3);
          }

          .sad-dashboard *,
          .sad-dashboard *::before,
          .sad-dashboard *::after {
               box-sizing: border-box;
          }

          .sad-dashboard a {
               text-decoration: none;
          }

          .sad-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 28px;
               min-height: 230px;
               margin-bottom: 24px;
               padding: 36px 40px;
               border-radius: 24px;
               background:
                    linear-gradient(125deg, rgba(30, 27, 75, 0.98), rgba(79, 70, 229, 0.96) 52%, rgba(15, 118, 110, 0.93));
               box-shadow: 0 24px 52px rgba(67, 56, 202, 0.25);
          }

          .sad-hero::before {
               position: absolute;
               top: -135px;
               right: -78px;
               width: 390px;
               height: 390px;
               border: 72px solid rgba(255, 255, 255, 0.07);
               border-radius: 50%;
               content: "";
          }

          .sad-hero::after {
               position: absolute;
               right: 230px;
               bottom: -150px;
               width: 290px;
               height: 290px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.055);
               content: "";
          }

          .sad-hero-content,
          .sad-hero-actions {
               position: relative;
               z-index: 2;
          }

          .sad-hero-content {
               max-width: 760px;
          }

          .sad-role-badge {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               margin-bottom: 18px;
               padding: 8px 13px;
               border: 1px solid rgba(255, 255, 255, 0.22);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.13);
               color: #ffffff;
               font-size: 11px;
               font-weight: 800;
               letter-spacing: 0.05em;
               text-transform: uppercase;
               backdrop-filter: blur(10px);
          }

          .sad-role-badge::before {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #86efac;
               box-shadow: 0 0 0 5px rgba(134, 239, 172, 0.18);
               content: "";
          }

          .sad-hero h1 {
               margin: 0 0 12px;
               color: #ffffff;
               font-size: clamp(29px, 4vw, 43px);
               font-weight: 800;
               letter-spacing: -0.038em;
               line-height: 1.12;
          }

          .sad-hero-description {
               max-width: 700px;
               margin: 0;
               color: rgba(255, 255, 255, 0.84);
               font-size: 14px;
               line-height: 1.75;
          }

          .sad-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 19px;
               margin-top: 21px;
          }

          .sad-hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.88);
               font-size: 11px;
               font-weight: 700;
          }

          .sad-hero-meta-item svg {
               width: 15px;
               height: 15px;
          }

          .sad-hero-actions {
               display: flex;
               flex-shrink: 0;
               flex-direction: column;
               gap: 11px;
               width: 218px;
          }

          .sad-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 45px;
               padding: 11px 17px;
               border: 0;
               border-radius: 12px;
               font-size: 12px;
               font-weight: 800;
               cursor: pointer;
               transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
          }

          .sad-button:hover {
               transform: translateY(-2px);
          }

          .sad-button svg {
               width: 16px;
               height: 16px;
          }

          .sad-button-primary {
               background: #ffffff;
               color: var(--sad-primary-dark);
               box-shadow: 0 11px 24px rgba(30, 27, 75, 0.22);
          }

          .sad-button-secondary {
               border: 1px solid rgba(255, 255, 255, 0.24);
               background: rgba(255, 255, 255, 0.12);
               color: #ffffff;
               backdrop-filter: blur(10px);
          }

          .sad-stat-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 18px;
               margin-bottom: 24px;
          }

          .sad-main-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.7fr) minmax(330px, 0.8fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .sad-secondary-grid {
               display: grid;
               grid-template-columns: minmax(315px, 0.76fr) minmax(0, 1.5fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .sad-bottom-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.08fr) minmax(0, 1fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .sad-footer-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.18fr) minmax(320px, 0.72fr);
               gap: 22px;
          }

          .sad-stat-card {
               position: relative;
               overflow: hidden;
               min-height: 174px;
               padding: 21px;
               border: 1px solid var(--sad-border);
               border-radius: 18px;
               background: var(--sad-card);
               box-shadow: var(--sad-shadow);
               transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
          }

          .sad-stat-card:hover {
               transform: translateY(-4px);
               border-color: rgba(79, 70, 229, 0.26);
               box-shadow: var(--sad-shadow-hover);
          }

          .sad-stat-card::after {
               position: absolute;
               top: -31px;
               right: -31px;
               width: 102px;
               height: 102px;
               border-radius: 50%;
               content: "";
          }

          .sad-stat-card.theme-indigo::after {
               background: rgba(79, 70, 229, 0.09);
          }

          .sad-stat-card.theme-green::after {
               background: rgba(22, 163, 74, 0.09);
          }

          .sad-stat-card.theme-orange::after {
               background: rgba(217, 119, 6, 0.09);
          }

          .sad-stat-card.theme-blue::after {
               background: rgba(37, 99, 235, 0.09);
          }

          .sad-stat-top {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 15px;
          }

          .sad-stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
          }

          .sad-stat-icon svg {
               width: 21px;
               height: 21px;
          }

          .theme-indigo .sad-stat-icon {
               background: rgba(79, 70, 229, 0.12);
               color: #4f46e5;
          }

          .theme-green .sad-stat-icon {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .theme-orange .sad-stat-icon {
               background: rgba(217, 119, 6, 0.12);
               color: #d97706;
          }

          .theme-blue .sad-stat-icon {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .sad-stat-trend {
               display: inline-flex;
               align-items: center;
               gap: 4px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 10px;
               font-weight: 800;
          }

          .sad-stat-trend svg {
               width: 12px;
               height: 12px;
          }

          .sad-stat-trend.up {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .sad-stat-trend.down {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .sad-stat-label {
               margin: 18px 0 7px;
               color: var(--sad-muted);
               font-size: 11px;
               font-weight: 800;
          }

          .sad-stat-value {
               display: flex;
               align-items: baseline;
               gap: 2px;
               margin: 0;
               color: var(--sad-heading);
               font-size: 29px;
               font-weight: 800;
               letter-spacing: -0.03em;
               line-height: 1;
          }

          .sad-stat-description {
               margin: 10px 0 0;
               color: var(--sad-text);
               font-size: 10px;
               line-height: 1.55;
          }

          .sad-card {
               border: 1px solid var(--sad-border);
               border-radius: 20px;
               background: var(--sad-card);
               box-shadow: var(--sad-shadow);
          }

          .sad-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               padding: 22px 23px 17px;
               border-bottom: 1px solid var(--sad-border);
          }

          .sad-card-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .sad-card-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 39px;
               height: 39px;
               border-radius: 12px;
               background: var(--sad-primary-soft);
               color: var(--sad-primary);
          }

          .sad-card-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .sad-card-title {
               margin: 0;
               color: var(--sad-heading);
               font-size: 15px;
               font-weight: 800;
               letter-spacing: -0.015em;
          }

          .sad-card-subtitle {
               margin: 5px 0 0;
               color: var(--sad-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .sad-card-action {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 8px 10px;
               border: 1px solid var(--sad-border);
               border-radius: 10px;
               background: transparent;
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 800;
               cursor: pointer;
               white-space: nowrap;
          }

          .sad-card-action:hover {
               border-color: var(--sad-primary);
               color: var(--sad-primary);
          }

          .sad-card-action svg {
               width: 14px;
               height: 14px;
          }

          .sad-chart-body {
               padding: 22px 23px 24px;
          }

          .sad-chart-summary {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 22px;
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
               width: 9px;
               height: 9px;
               border-radius: 3px;
          }

          .sad-chart-legend-dot.target {
               background: rgba(79, 70, 229, 0.24);
          }

          .sad-chart-legend-dot.actual {
               background: var(--sad-primary);
          }

          .sad-chart-rate {
               text-align: right;
          }

          .sad-chart-rate strong {
               display: block;
               color: var(--sad-heading);
               font-size: 19px;
               font-weight: 800;
          }

          .sad-chart-rate span {
               color: var(--sad-muted);
               font-size: 9px;
          }

          .sad-chart-area {
               position: relative;
               display: grid;
               grid-template-columns: 34px minmax(0, 1fr);
               gap: 11px;
               height: 285px;
          }

          .sad-chart-y-axis {
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               height: 245px;
               padding-top: 2px;
               color: var(--sad-muted);
               font-size: 9px;
               text-align: right;
          }

          .sad-chart-content {
               position: relative;
               height: 285px;
          }

          .sad-chart-lines {
               position: absolute;
               inset: 0 0 40px;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               pointer-events: none;
          }

          .sad-chart-line {
               width: 100%;
               border-top: 1px dashed var(--sad-border);
          }

          .sad-chart-columns {
               position: absolute;
               inset: 0;
               display: grid;
               grid-template-columns: repeat(6, minmax(38px, 1fr));
               gap: 15px;
          }

          .sad-chart-column {
               display: flex;
               flex-direction: column;
               align-items: center;
               min-width: 0;
          }

          .sad-chart-bars {
               display: flex;
               align-items: flex-end;
               justify-content: center;
               gap: 6px;
               width: 100%;
               height: 245px;
          }

          .sad-chart-bar {
               position: relative;
               width: min(18px, 39%);
               min-height: 4px;
               border-radius: 6px 6px 3px 3px;
               cursor: pointer;
               transition: filter 0.2s ease, transform 0.2s ease;
          }

          .sad-chart-bar:hover {
               z-index: 3;
               filter: brightness(0.94);
               transform: scaleX(1.08);
          }

          .sad-chart-bar.target {
               background: rgba(79, 70, 229, 0.24);
          }

          .sad-chart-bar.actual {
               background: linear-gradient(to top, #3730a3, #818cf8);
               box-shadow: 0 5px 12px rgba(79, 70, 229, 0.2);
          }

          .sad-chart-tooltip {
               position: absolute;
               bottom: calc(100% + 8px);
               left: 50%;
               z-index: 10;
               min-width: 72px;
               padding: 6px 8px;
               border-radius: 7px;
               background: #172033;
               color: #ffffff;
               font-size: 9px;
               font-weight: 800;
               text-align: center;
               white-space: nowrap;
               opacity: 0;
               pointer-events: none;
               transform: translateX(-50%) translateY(3px);
               transition: opacity 0.2s ease, transform 0.2s ease;
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

          .sad-satisfaction-body {
               padding: 24px;
          }

          .sad-score-summary {
               display: flex;
               align-items: center;
               gap: 22px;
               margin-bottom: 24px;
               padding-bottom: 23px;
               border-bottom: 1px solid var(--sad-border);
          }

          .sad-score-ring {
               position: relative;
               display: grid;
               flex-shrink: 0;
               width: 120px;
               height: 120px;
               place-items: center;
               border-radius: 50%;
          }

          .sad-score-ring::before {
               position: absolute;
               width: 89px;
               height: 89px;
               border-radius: 50%;
               background: var(--sad-card);
               content: "";
          }

          .sad-score-ring-value {
               position: relative;
               z-index: 2;
               color: var(--sad-heading);
               font-size: 23px;
               font-weight: 800;
          }

          .sad-score-details h3 {
               margin: 0 0 6px;
               color: var(--sad-heading);
               font-size: 14px;
               font-weight: 800;
          }

          .sad-score-details p {
               margin: 0 0 11px;
               color: var(--sad-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .sad-score-status {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 9px;
               border-radius: 999px;
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
               font-size: 9px;
               font-weight: 800;
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
               padding: 11px 9px;
               border: 1px solid var(--sad-border);
               border-radius: 11px;
               text-align: center;
          }

          .sad-sentiment-card strong {
               display: block;
               margin-bottom: 4px;
               color: var(--sad-heading);
               font-size: 15px;
               font-weight: 800;
          }

          .sad-sentiment-card span {
               color: var(--sad-muted);
               font-size: 8px;
               font-weight: 800;
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

          .sad-health-label {
               color: var(--sad-text);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-health-value {
               color: var(--sad-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-progress {
               overflow: hidden;
               width: 100%;
               height: 7px;
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

          .sad-badge {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 5px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 8px;
               font-weight: 800;
               white-space: nowrap;
          }

          .sad-badge::before {
               width: 5px;
               height: 5px;
               border-radius: 50%;
               background: currentColor;
               content: "";
          }

          .sad-badge.success {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .sad-badge.info {
               background: rgba(37, 99, 235, 0.1);
               color: #2563eb;
          }

          .sad-badge.warning {
               background: rgba(217, 119, 6, 0.1);
               color: #b45309;
          }

          .sad-badge.danger {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .sad-badge.neutral {
               background: rgba(100, 116, 139, 0.12);
               color: #64748b;
          }

          .sad-priority-list {
               display: flex;
               flex-direction: column;
          }

          .sad-priority-item {
               display: flex;
               gap: 13px;
               padding: 17px 21px;
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
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 38px;
               height: 38px;
               border-radius: 11px;
               background: var(--sad-primary-soft);
               color: var(--sad-primary);
          }

          .sad-priority-icon svg {
               width: 17px;
               height: 17px;
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
               font-size: 11px;
               font-weight: 800;
          }

          .sad-priority-description {
               margin: 6px 0 11px;
               color: var(--sad-muted);
               font-size: 9px;
               line-height: 1.6;
          }

          .sad-priority-action {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               border: 0;
               background: transparent;
               color: var(--sad-primary);
               font-size: 9px;
               font-weight: 800;
               cursor: pointer;
          }

          .sad-priority-action svg {
               width: 12px;
               height: 12px;
          }

          .sad-monitoring-card {
               overflow: hidden;
          }

          .sad-table-toolbar {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 14px 20px;
               border-bottom: 1px solid var(--sad-border);
          }

          .sad-filter-list {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .sad-filter-button {
               padding: 7px 10px;
               border: 1px solid var(--sad-border);
               border-radius: 8px;
               background: transparent;
               color: var(--sad-muted);
               font-size: 8px;
               font-weight: 800;
               cursor: pointer;
               transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
          }

          .sad-filter-button:hover,
          .sad-filter-button.active {
               border-color: var(--sad-primary);
               background: var(--sad-primary);
               color: #ffffff;
          }

          .sad-search {
               position: relative;
               width: 215px;
          }

          .sad-search svg {
               position: absolute;
               top: 50%;
               left: 11px;
               width: 14px;
               height: 14px;
               color: var(--sad-muted);
               transform: translateY(-50%);
          }

          .sad-search input {
               width: 100%;
               height: 35px;
               padding: 7px 11px 7px 33px;
               border: 1px solid var(--sad-border);
               border-radius: 9px;
               outline: none;
               background: transparent;
               color: var(--sad-heading);
               font-size: 9px;
          }

          .sad-search input:focus {
               border-color: var(--sad-primary);
               box-shadow: 0 0 0 3px var(--sad-primary-soft);
          }

          .sad-table-wrapper {
               overflow-x: auto;
          }

          .sad-table {
               width: 100%;
               min-width: 940px;
               border-collapse: collapse;
          }

          .sad-table th {
               padding: 12px 15px;
               border-bottom: 1px solid var(--sad-border);
               background: rgba(148, 163, 184, 0.045);
               color: var(--sad-muted);
               font-size: 8px;
               font-weight: 800;
               letter-spacing: 0.045em;
               text-align: left;
               text-transform: uppercase;
          }

          .sad-table td {
               padding: 15px;
               border-bottom: 1px solid var(--sad-border);
               color: var(--sad-text);
               font-size: 9px;
               vertical-align: middle;
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
               min-width: 220px;
          }

          .sad-unit-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 36px;
               height: 36px;
               border-radius: 10px;
               background: var(--sad-primary-soft);
               color: var(--sad-primary);
          }

          .sad-unit-icon svg {
               width: 15px;
               height: 15px;
          }

          .sad-unit-name {
               display: block;
               margin-bottom: 3px;
               color: var(--sad-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-unit-code {
               color: var(--sad-muted);
               font-size: 8px;
          }

          .sad-leader {
               display: block;
               color: var(--sad-heading);
               font-weight: 800;
          }

          .sad-updated {
               display: block;
               margin-top: 3px;
               color: var(--sad-muted);
               font-size: 8px;
          }

          .sad-score-cell {
               min-width: 108px;
          }

          .sad-score-cell-top {
               display: flex;
               justify-content: space-between;
               margin-bottom: 6px;
               color: var(--sad-muted);
               font-size: 8px;
          }

          .sad-score-cell-top strong {
               color: var(--sad-heading);
          }

          .sad-score-cell .sad-progress {
               height: 6px;
          }

          .sad-complaint-count {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               color: var(--sad-heading);
               font-weight: 800;
          }

          .sad-complaint-count svg {
               width: 13px;
               height: 13px;
               color: var(--sad-muted);
          }

          .sad-action-menu {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 31px;
               height: 31px;
               border: 1px solid var(--sad-border);
               border-radius: 8px;
               background: transparent;
               color: var(--sad-muted);
               cursor: pointer;
          }

          .sad-action-menu:hover {
               border-color: var(--sad-primary);
               color: var(--sad-primary);
          }

          .sad-action-menu svg {
               width: 15px;
               height: 15px;
          }

          .sad-empty-state {
               display: none;
               padding: 38px 20px;
               text-align: center;
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
               font-size: 12px;
          }

          .sad-empty-state p {
               margin: 0;
               color: var(--sad-muted);
               font-size: 9px;
          }

          .sad-channel-list,
          .sad-role-list,
          .sad-activity-list {
               padding: 4px 21px 10px;
          }

          .sad-channel-item,
          .sad-role-item {
               padding: 16px 0;
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
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 34px;
               height: 34px;
               border-radius: 10px;
               background: var(--sad-primary-soft);
               color: var(--sad-primary);
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
               font-size: 10px;
               font-weight: 800;
          }

          .sad-channel-meta,
          .sad-role-meta {
               display: block;
               margin-top: 3px;
               color: var(--sad-muted);
               font-size: 8px;
          }

          .sad-channel-score,
          .sad-role-count {
               color: var(--sad-heading);
               font-size: 12px;
               font-weight: 800;
          }

          .sad-role-count small {
               display: block;
               margin-top: 2px;
               color: var(--sad-muted);
               font-size: 7px;
               font-weight: 700;
               text-align: right;
          }

          .sad-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 17px 0;
          }

          .sad-activity-item:not(:last-child)::before {
               position: absolute;
               top: 52px;
               bottom: -3px;
               left: 18px;
               width: 1px;
               background: var(--sad-border);
               content: "";
          }

          .sad-activity-icon {
               position: relative;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 37px;
               height: 37px;
               border: 4px solid var(--sad-card);
               border-radius: 50%;
          }

          .sad-activity-icon svg {
               width: 14px;
               height: 14px;
          }

          .sad-activity-icon.green {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .sad-activity-icon.blue {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .sad-activity-icon.red {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .sad-activity-icon.orange {
               background: rgba(217, 119, 6, 0.12);
               color: #d97706;
          }

          .sad-activity-icon.purple {
               background: rgba(124, 58, 237, 0.12);
               color: #7c3aed;
          }

          .sad-activity-content {
               min-width: 0;
               flex: 1;
          }

          .sad-activity-content h4 {
               margin: 1px 0 5px;
               color: var(--sad-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-activity-content p {
               margin: 0;
               color: var(--sad-muted);
               font-size: 9px;
               line-height: 1.6;
          }

          .sad-activity-time {
               margin-top: 6px;
               color: var(--sad-primary);
               font-size: 8px;
               font-weight: 800;
          }

          .sad-quick-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 12px;
               padding: 21px;
          }

          .sad-quick-action {
               display: flex;
               align-items: center;
               gap: 12px;
               min-height: 82px;
               padding: 14px;
               border: 1px solid var(--sad-border);
               border-radius: 14px;
               background: transparent;
               color: var(--sad-text);
               transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
          }

          .sad-quick-action:hover {
               transform: translateY(-2px);
               border-color: var(--sad-primary);
               background: var(--sad-primary-soft);
          }

          .sad-quick-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 39px;
               height: 39px;
               border-radius: 11px;
               background: var(--sad-primary-soft);
               color: var(--sad-primary);
          }

          .sad-quick-icon svg {
               width: 17px;
               height: 17px;
          }

          .sad-quick-action strong {
               display: block;
               color: var(--sad-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .sad-quick-action span {
               display: block;
               margin-top: 4px;
               color: var(--sad-muted);
               font-size: 8px;
          }

          @media (max-width: 1280px) {
               .sad-stat-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .sad-main-grid,
               .sad-secondary-grid,
               .sad-bottom-grid,
               .sad-footer-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 768px) {
               .sad-dashboard {
                    padding: 15px;
               }

               .sad-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    min-height: auto;
                    padding: 27px 23px;
                    border-radius: 19px;
               }

               .sad-hero-actions {
                    flex-direction: row;
                    width: 100%;
               }

               .sad-hero-actions .sad-button {
                    flex: 1;
               }

               .sad-stat-grid {
                    grid-template-columns: 1fr;
                    gap: 13px;
               }

               .sad-stat-card {
                    min-height: auto;
               }

               .sad-card-header,
               .sad-table-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .sad-search {
                    width: 100%;
               }

               .sad-chart-area {
                    grid-template-columns: 26px minmax(0, 1fr);
               }

               .sad-chart-columns {
                    gap: 6px;
               }

               .sad-chart-bars {
                    gap: 3px;
               }

               .sad-score-summary {
                    align-items: flex-start;
               }
          }

          @media (max-width: 520px) {
               .sad-hero-actions {
                    flex-direction: column;
               }

               .sad-chart-summary {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .sad-chart-rate {
                    text-align: left;
               }

               .sad-score-summary {
                    align-items: center;
                    flex-direction: column;
                    text-align: center;
               }

               .sad-sentiment-grid {
                    grid-template-columns: 1fr;
               }

               .sad-quick-grid {
                    grid-template-columns: 1fr;
               }
          }
     </style>

     <div class="sad-dashboard">
          <section class="sad-hero">
               <div class="sad-hero-content">
                    <div class="sad-role-badge">{{ $currentUserRole }}</div>

                    <h1>Monitoring Kinerja & Kepuasan Pelanggan</h1>

                    <p class="sad-hero-description">
                         Selamat datang, {{ $currentUserName }}. Pantau capaian kinerja unit,
                         indeks kepuasan pelanggan, penyelesaian keluhan, aktivitas pengguna,
                         serta kondisi sistem melalui satu pusat kendali Super Admin.
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

                    <a href="{{ $reportsUrl }}" class="sad-button sad-button-secondary">
                         <i data-feather="download"></i>
                         Unduh Laporan
                    </a>
               </div>
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

          <section class="sad-main-grid">
               <article class="sad-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="bar-chart-2"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Tren Capaian Kinerja</h2>
                                   <p class="sad-card-subtitle">
                                        Perbandingan target dan realisasi KPI organisasi selama enam bulan terakhir.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="sad-card-action">
                              6 bulan terakhir
                              <i data-feather="chevron-down"></i>
                         </button>
                    </header>

                    <div class="sad-chart-body">
                         <div class="sad-chart-summary">
                              <div class="sad-chart-legends">
                                   <span class="sad-chart-legend">
                                        <span class="sad-chart-legend-dot target"></span>
                                        Target KPI
                                   </span>

                                   <span class="sad-chart-legend">
                                        <span class="sad-chart-legend-dot actual"></span>
                                        Realisasi KPI
                                   </span>
                              </div>

                              <div class="sad-chart-rate">
                                   <strong>{{ number_format($actualAverage, 1, ',', '.') }}%</strong>
                                   <span>Rata-rata realisasi dari target
                                        {{ number_format($targetAverage, 1, ',', '.') }}%</span>
                              </div>
                         </div>

                         <div class="sad-chart-area">
                              <div class="sad-chart-y-axis">
                                   <span>100</span>
                                   <span>75</span>
                                   <span>50</span>
                                   <span>25</span>
                                   <span>0</span>
                              </div>

                              <div class="sad-chart-content">
                                   <div class="sad-chart-lines">
                                        <span class="sad-chart-line"></span>
                                        <span class="sad-chart-line"></span>
                                        <span class="sad-chart-line"></span>
                                        <span class="sad-chart-line"></span>
                                        <span class="sad-chart-line"></span>
                                   </div>

                                   <div class="sad-chart-columns">
                                        @foreach ($performanceTrend as $performance)
                                             <div class="sad-chart-column">
                                                  <div class="sad-chart-bars">
                                                       <div class="sad-chart-bar target"
                                                            style="height: {{ $performance['target'] }}%;">
                                                            <span class="sad-chart-tooltip">Target
                                                                 {{ $performance['target'] }}%</span>
                                                       </div>

                                                       <div class="sad-chart-bar actual"
                                                            style="height: {{ $performance['actual'] }}%;">
                                                            <span class="sad-chart-tooltip">
                                                                 Realisasi {{ $performance['actual'] }}%<br>
                                                                 Kepuasan {{ $performance['satisfaction'] }}%
                                                            </span>
                                                       </div>
                                                  </div>

                                                  <span class="sad-chart-month" title="{{ $performance['full_month'] }}">
                                                       {{ $performance['month'] }}
                                                  </span>
                                             </div>
                                        @endforeach
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>

               <article class="sad-card">

                    <header class="sad-card-header">
                         <div class="sad-card-heading">

                              <span class="sad-card-heading-icon">
                                   <i data-feather="git-branch"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">
                                        Data Cabang
                                   </h2>

                                   <p class="sad-card-subtitle">
                                        Ringkasan status dan perkembangan cabang perusahaan.
                                   </p>
                              </div>

                         </div>
                    </header>


                    <div class="sad-satisfaction-body">


                         {{-- SUMMARY CABANG --}}
                         <div class="sad-score-summary">

                              <div class="sad-score-ring"
                                   style="
                 background:
                 conic-gradient(
                    var(--sad-success) 0deg {{ $branchAngle }}deg,
                    var(--sad-border) {{ $branchAngle }}deg 360deg
                 );">

                                   <span class="sad-score-ring-value">
                                        {{ $branchSummary['active_percentage'] }}%
                                   </span>

                              </div>


                              <div class="sad-score-details">

                                   <h3>
                                        {{ $branchSummary['active'] }}
                                        Cabang Aktif
                                   </h3>


                                   <p>
                                        Dari total
                                        {{ number_format($branchSummary['total'], 0, ',', '.') }}
                                        cabang yang terdaftar.
                                   </p>


                                   <span class="sad-score-status">
                                        Operasional berjalan
                                   </span>

                              </div>

                         </div>



                         {{-- STATUS CABANG --}}
                         <div class="sad-sentiment-grid">


                              <div class="sad-sentiment-card">

                                   <strong>
                                        {{ $branchSummary['active'] }}
                                   </strong>

                                   <span>
                                        Aktif
                                   </span>

                              </div>



                              <div class="sad-sentiment-card">

                                   <strong>
                                        {{ $branchSummary['pending'] }}
                                   </strong>

                                   <span>
                                        Approval
                                   </span>

                              </div>



                              <div class="sad-sentiment-card">

                                   <strong>
                                        {{ $branchSummary['inactive'] }}
                                   </strong>

                                   <span>
                                        Nonaktif
                                   </span>

                              </div>


                         </div>




                         {{-- DETAIL --}}
                         <div class="sad-health-list">


                              <div>

                                   <div class="sad-health-top">

                                        <span class="sad-health-label">
                                             Cabang Aktif
                                        </span>


                                        <span class="sad-health-value">
                                             {{ $branchSummary['active_percentage'] }}%
                                        </span>

                                   </div>


                                   <div class="sad-progress">

                                        <div class="sad-progress-bar success"
                                             style="
                         width:
                         {{ $branchSummary['active_percentage'] }}%;
                         ">
                                        </div>

                                   </div>


                              </div>




                              <div>

                                   <div class="sad-health-top">

                                        <span class="sad-health-label">
                                             Menunggu Approval
                                        </span>


                                        <span class="sad-health-value">
                                             {{ $branchSummary['pending_percentage'] }}%
                                        </span>

                                   </div>


                                   <div class="sad-progress">

                                        <div class="sad-progress-bar warning"
                                             style="
                         width:
                         {{ $branchSummary['pending_percentage'] }}%;
                         ">
                                        </div>

                                   </div>

                              </div>




                              <div>

                                   <div class="sad-health-top">

                                        <span class="sad-health-label">
                                             Cabang Nonaktif
                                        </span>


                                        <span class="sad-health-value">
                                             {{ $branchSummary['inactive_percentage'] }}%
                                        </span>

                                   </div>


                                   <div class="sad-progress">

                                        <div class="sad-progress-bar danger"
                                             style="
                         width:
                         {{ $branchSummary['inactive_percentage'] }}%;
                         ">
                                        </div>

                                   </div>

                              </div>


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
                                        Temuan yang memerlukan keputusan atau tindak lanjut administrator.
                                   </p>
                              </div>
                         </div>

                         <span class="sad-badge danger">{{ count($monitoringPriorities) }} prioritas</span>
                    </header>

                    <div class="sad-priority-list">
                         @foreach ($monitoringPriorities as $priority)
                              <div class="sad-priority-item">
                                   <span class="sad-priority-icon">
                                        <i data-feather="{{ $priority['icon'] }}"></i>
                                   </span>

                                   <div class="sad-priority-content">
                                        <div class="sad-priority-heading">
                                             <h3 class="sad-priority-title">{{ $priority['title'] }}</h3>
                                             <span
                                                  class="sad-badge {{ $priority['status_class'] }}">{{ $priority['status'] }}</span>
                                        </div>

                                        <p class="sad-priority-description">{{ $priority['description'] }}</p>

                                        <a href="{{ $priority['url'] }}" class="sad-priority-action">
                                             {{ $priority['action'] }}
                                             <i data-feather="arrow-right"></i>
                                        </a>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               <article class="sad-card sad-monitoring-card">
                    <header class="sad-card-header">
                         <div class="sad-card-heading">
                              <span class="sad-card-heading-icon">
                                   <i data-feather="briefcase"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Data Jabatan</h2>
                                   <p class="sad-card-subtitle">
                                        Daftar jabatan berdasarkan departemen, level, dan status aktif.
                                   </p>
                              </div>
                         </div>

                         @if (Route::has('super-admin.positions.index'))
                              <a href="{{ route('super-admin.positions.index') }}" class="sad-card-action">
                                   <i data-feather="list"></i>
                                   Lihat semua jabatan
                              </a>
                         @endif
                    </header>

                    <div class="sad-table-toolbar">
                         <div class="sad-filter-list">
                              <button type="button" class="sad-filter-button active" data-position-filter="semua">
                                   Semua
                              </button>

                              <button type="button" class="sad-filter-button" data-position-filter="active">
                                   Aktif
                              </button>

                              <button type="button" class="sad-filter-button" data-position-filter="inactive">
                                   Tidak Aktif
                              </button>
                         </div>

                         <label class="sad-search">
                              <i data-feather="search"></i>

                              <input type="search" id="positionSearch"
                                   placeholder="Cari jabatan, kode, atau departemen..." autocomplete="off">
                         </label>
                    </div>

                    <div class="sad-table-wrapper">
                         <table class="sad-table">
                              <thead>
                                   <tr>
                                        <th>Jabatan</th>
                                        <th>Departemen</th>
                                        <th>Level</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Diperbarui</th>
                                        <th></th>
                                   </tr>
                              </thead>

                              <tbody id="positionTableBody">
                                   @forelse ($positions as $position)
                                        @php
                                             $status = strtolower($position->status);

                                             $statusClass = match ($status) {
                                                 'active' => 'success',
                                                 'inactive' => 'danger',
                                                 default => 'neutral',
                                             };

                                             $statusLabel = match ($status) {
                                                 'active' => 'Aktif',
                                                 'inactive' => 'Tidak Aktif',
                                                 default => ucfirst($position->status),
                                             };

                                             $levelClass = match (true) {
                                                 $position->level >= 4 => 'danger',
                                                 $position->level === 3 => 'warning',
                                                 $position->level === 2 => 'info',
                                                 default => 'neutral',
                                             };

                                             $levelLabel = match ((int) $position->level) {
                                                 1 => 'Staff',
                                                 2 => 'Supervisor',
                                                 3 => 'Manager',
                                                 4 => 'Direktur',
                                                 default => 'Level ' . $position->level,
                                             };

                                             $departmentName = $position->department?->name ?? 'Tanpa Departemen';

                                             $searchKeyword = strtolower(
                                                 $position->name .
                                                     ' ' .
                                                     $position->code .
                                                     ' ' .
                                                     $departmentName .
                                                     ' ' .
                                                     $position->status .
                                                     ' ' .
                                                     $levelLabel,
                                             );
                                        @endphp

                                        <tr data-position-row data-position-status="{{ $status }}"
                                             data-position-keyword="{{ $searchKeyword }}">
                                             <td>
                                                  <div class="sad-unit-cell">
                                                       <span class="sad-unit-icon">
                                                            <i data-feather="briefcase"></i>
                                                       </span>

                                                       <span>
                                                            <strong class="sad-unit-name">
                                                                 {{ $position->name }}
                                                            </strong>

                                                            <span class="sad-unit-code">
                                                                 {{ $position->code }}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="sad-leader">
                                                       {{ $departmentName }}
                                                  </span>

                                                  @if ($position->department?->code)
                                                       <span class="sad-updated">
                                                            {{ $position->department->code }}
                                                       </span>
                                                  @endif
                                             </td>

                                             <td>
                                                  <span class="sad-badge {{ $levelClass }}">
                                                       {{ $levelLabel }}
                                                  </span>

                                                  <span class="sad-updated">
                                                       Level {{ $position->level }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-description">
                                                       {{ \Illuminate\Support\Str::limit($position->description ?? 'Tidak ada deskripsi.', 80) }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-badge {{ $statusClass }}">
                                                       {{ $statusLabel }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="sad-leader">
                                                       {{ $position->updated_at?->format('d M Y') }}
                                                  </span>

                                                  <span class="sad-updated">
                                                       {{ $position->updated_at?->diffForHumans() }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="sad-row-actions">
                                                       @if (Route::has('super-admin.positions.show'))
                                                            <a href="{{ route('super-admin.positions.show', $position) }}"
                                                                 class="sad-action-menu"
                                                                 aria-label="Lihat jabatan {{ $position->name }}"
                                                                 title="Detail">
                                                                 <i data-feather="eye"></i>
                                                            </a>
                                                       @endif

                                                       @if (Route::has('super-admin.positions.edit'))
                                                            <a href="{{ route('super-admin.positions.edit', $position) }}"
                                                                 class="sad-action-menu"
                                                                 aria-label="Edit jabatan {{ $position->name }}"
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
                                                       <i data-feather="briefcase"></i>
                                                       <h4>Data jabatan belum tersedia</h4>
                                                       <p>Tambahkan data jabatan untuk menampilkannya di sini.</p>
                                                  </div>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>

                         @if ($positions->isNotEmpty())
                              <div class="sad-empty-state" id="positionEmptyState">
                                   <i data-feather="search"></i>
                                   <h4>Data jabatan tidak ditemukan</h4>
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
                                   <i data-feather="radio"></i>
                              </span>

                              <div>
                                   <h2 class="sad-card-title">Kepuasan Berdasarkan Kanal</h2>
                                   <p class="sad-card-subtitle">
                                        Perbandingan skor pengalaman pelanggan pada setiap kanal layanan.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ $surveysUrl }}" class="sad-card-action">
                              Kelola survei
                              <i data-feather="arrow-up-right"></i>
                         </a>
                    </header>

                    <div class="sad-channel-list">
                         @foreach ($channelPerformance as $channel)
                              <div class="sad-channel-item">
                                   <div class="sad-channel-header">
                                        <div class="sad-channel-identity">
                                             <span class="sad-channel-icon">
                                                  <i data-feather="{{ $channel['icon'] }}"></i>
                                             </span>

                                             <span>
                                                  <strong class="sad-channel-name">{{ $channel['name'] }}</strong>
                                                  <span class="sad-channel-meta">
                                                       {{ number_format($channel['responses'], 0, ',', '.') }} respons
                                                       pelanggan
                                                  </span>
                                             </span>
                                        </div>

                                        <span class="sad-channel-score">{{ $channel['score'] }}%</span>
                                   </div>

                                   <div class="sad-progress">
                                        <div class="sad-progress-bar {{ $channel['class'] }}"
                                             style="width: {{ $channel['score'] }}%;"></div>
                                   </div>
                              </div>
                         @endforeach
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
                                        Ringkasan pengguna berdasarkan role dan status keaktifan.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ $usersUrl }}" class="sad-card-action">
                              Kelola pengguna
                              <i data-feather="arrow-up-right"></i>
                         </a>
                    </header>

                    <div class="sad-role-list">
                         @foreach ($roleSummary as $role)
                              <div class="sad-role-item">
                                   <div class="sad-role-header">
                                        <div class="sad-role-identity">
                                             <span class="sad-role-icon">
                                                  <i data-feather="{{ $role['icon'] }}"></i>
                                             </span>

                                             <span>
                                                  <strong class="sad-role-name">{{ $role['name'] }}</strong>
                                                  <span class="sad-role-meta">{{ $role['active'] }} akun aktif</span>
                                             </span>
                                        </div>

                                        <span class="sad-role-count">
                                             {{ $role['users'] }}
                                             <small>pengguna</small>
                                        </span>
                                   </div>

                                   <div class="sad-progress">
                                        <div class="sad-progress-bar {{ $role['active'] === $role['users'] ? 'success' : 'info' }}"
                                             style="width: {{ $role['users'] > 0 ? round(($role['active'] / $role['users']) * 100) : 0 }}%;">
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
                                        Audit singkat perubahan data dan aktivitas penting dalam aplikasi.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="sad-card-action" id="markSystemRead">Tandai dibaca</button>
                    </header>

                    <div class="sad-activity-list" id="systemActivityList">
                         @foreach ($systemActivities as $activity)
                              <div class="sad-activity-item" data-system-activity>
                                   <span class="sad-activity-icon {{ $activity['theme'] }}">
                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                   </span>

                                   <div class="sad-activity-content">
                                        <h4>{{ $activity['title'] }}</h4>
                                        <p>{{ $activity['description'] }}</p>
                                        <div class="sad-activity-time">{{ $activity['time'] }}</div>
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
                              <a href="{{ $action['url'] }}" class="sad-quick-action">
                                   <span class="sad-quick-icon">
                                        <i data-feather="{{ $action['icon'] }}"></i>
                                   </span>

                                   <span>
                                        <strong>{{ $action['label'] }}</strong>
                                        <span>{{ $action['description'] }}</span>
                                   </span>
                              </a>
                         @endforeach
                    </div>
               </article>
          </section>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               if (typeof feather !== 'undefined') {
                    feather.replace();
               }

               const filterButtons = document.querySelectorAll('[data-unit-filter]');
               const unitRows = document.querySelectorAll('[data-unit-row]');
               const unitSearch = document.getElementById('unitMonitoringSearch');
               const unitEmpty = document.getElementById('unitMonitoringEmpty');
               let activeStatus = 'semua';

               function filterUnitMonitoring() {
                    const keyword = unitSearch ? unitSearch.value.trim().toLowerCase() : '';
                    let visibleRows = 0;

                    unitRows.forEach(function(row) {
                         const rowStatus = row.dataset.unitStatus || '';
                         const rowKeyword = row.dataset.unitKeyword || '';
                         const statusMatches = activeStatus === 'semua' || rowStatus === activeStatus;
                         const keywordMatches = keyword === '' || rowKeyword.includes(keyword);
                         const shouldShow = statusMatches && keywordMatches;

                         row.style.display = shouldShow ? '' : 'none';

                         if (shouldShow) {
                              visibleRows += 1;
                         }
                    });

                    if (unitEmpty) {
                         unitEmpty.style.display = visibleRows === 0 ? 'block' : 'none';
                    }
               }

               filterButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                         filterButtons.forEach(function(item) {
                              item.classList.remove('active');
                         });

                         button.classList.add('active');
                         activeStatus = button.dataset.unitFilter || 'semua';
                         filterUnitMonitoring();
                    });
               });

               if (unitSearch) {
                    unitSearch.addEventListener('input', filterUnitMonitoring);
               }

               const markReadButton = document.getElementById('markSystemRead');
               const activityItems = document.querySelectorAll('[data-system-activity]');

               if (markReadButton) {
                    markReadButton.addEventListener('click', function() {
                         activityItems.forEach(function(activity) {
                              activity.style.opacity = '0.55';
                         });

                         markReadButton.textContent = 'Sudah dibaca';
                         markReadButton.disabled = true;
                    });
               }

               const liveClock = document.getElementById('sadLiveClock');

               function updateClock() {
                    if (!liveClock) {
                         return;
                    }

                    const currentTime = new Date();
                    liveClock.textContent = currentTime.toLocaleTimeString('id-ID', {
                         hour: '2-digit',
                         minute: '2-digit',
                         second: '2-digit',
                         hour12: false,
                         timeZone: 'Asia/Jakarta'
                    }) + ' WIB';
               }

               updateClock();
               window.setInterval(updateClock, 1000);
          });
     </script>
     @push('scripts')
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('positionSearch');
                    const filterButtons = document.querySelectorAll('[data-position-filter]');
                    const positionRows = document.querySelectorAll('[data-position-row]');
                    const emptyState = document.getElementById('positionEmptyState');

                    let activeFilter = 'semua';

                    function filterPositions() {
                         const keyword = searchInput ?
                              searchInput.value.toLowerCase().trim() :
                              '';

                         let visibleRows = 0;

                         positionRows.forEach(function(row) {
                              const positionStatus =
                                   row.dataset.positionStatus?.toLowerCase() || '';

                              const positionKeyword =
                                   row.dataset.positionKeyword?.toLowerCase() || '';

                              const matchesStatus =
                                   activeFilter === 'semua' ||
                                   positionStatus === activeFilter;

                              const matchesKeyword =
                                   keyword === '' ||
                                   positionKeyword.includes(keyword);

                              const shouldShow = matchesStatus && matchesKeyword;

                              row.style.display = shouldShow ? '' : 'none';

                              if (shouldShow) {
                                   visibleRows++;
                              }
                         });

                         if (emptyState) {
                              emptyState.classList.toggle('is-visible', visibleRows === 0);
                         }
                    }

                    filterButtons.forEach(function(button) {
                         button.addEventListener('click', function() {
                              filterButtons.forEach(function(item) {
                                   item.classList.remove('active');
                              });

                              button.classList.add('active');
                              activeFilter = button.dataset.positionFilter.toLowerCase();

                              filterPositions();
                         });
                    });

                    if (searchInput) {
                         searchInput.addEventListener('input', filterPositions);
                    }
               });
          </script>
     @endpush
@endsection
