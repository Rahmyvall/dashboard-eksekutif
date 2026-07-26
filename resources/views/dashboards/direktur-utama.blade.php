@extends('layouts.app')

@section('title', 'Dashboard Direktur Utama')

@section('content')
     @php
          /*
|--------------------------------------------------------------------------
| DATA STATISTIK
|--------------------------------------------------------------------------
| Data ini dapat dipindahkan ke Controller
| setelah terhubung database.
*/

          $statistics = [
              [
                  'label' => 'Kinerja Operasional Aktif',
                  'value' => 42,
                  'suffix' => '',
                  'icon' => 'activity',
                  'description' => '18 pekerjaan prioritas tinggi',
                  'trend' => '+8,4%',
                  'trend_type' => 'up',
                  'theme' => 'orange',
              ],
              [
                  'label' => 'Selesai Hari Ini',
                  'value' => 31,
                  'suffix' => '',
                  'icon' => 'check-circle',
                  'description' => '73,8% dari target harian',
                  'trend' => '+12,6%',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Risiko Operasional',
                  'value' => 6,
                  'suffix' => '',
                  'icon' => 'clock',
                  'description' => 'Memerlukan tindak lanjut',
                  'trend' => '+2',
                  'trend_type' => 'down',
                  'theme' => 'red',
              ],
              [
                  'label' => 'Efektivitas Organisasi',
                  'value' => 87,
                  'suffix' => '%',
                  'icon' => 'cpu',
                  'description' => 'Masih dalam batas aman',
                  'trend' => '+4,2%',
                  'trend_type' => 'up',
                  'theme' => 'blue',
              ],
          ];

          /*
        |--------------------------------------------------------------------------
        | DATA KINERJA MINGGUAN
        |--------------------------------------------------------------------------
        */

          $weeklyPerformance = [
              [
                  'day' => 'Sen',
                  'full_day' => 'Senin',
                  'scheduled' => 67,
                  'completed' => 60,
              ],
              [
                  'day' => 'Sel',
                  'full_day' => 'Selasa',
                  'scheduled' => 76,
                  'completed' => 69,
              ],
              [
                  'day' => 'Rab',
                  'full_day' => 'Rabu',
                  'scheduled' => 81,
                  'completed' => 71,
              ],
              [
                  'day' => 'Kam',
                  'full_day' => 'Kamis',
                  'scheduled' => 93,
                  'completed' => 83,
              ],
              [
                  'day' => 'Jum',
                  'full_day' => 'Jumat',
                  'scheduled' => 98,
                  'completed' => 88,
              ],
              [
                  'day' => 'Sab',
                  'full_day' => 'Sabtu',
                  'scheduled' => 90,
                  'completed' => 81,
              ],
              [
                  'day' => 'Min',
                  'full_day' => 'Minggu',
                  'scheduled' => 100,
                  'completed' => 74,
              ],
          ];

          /*
        |--------------------------------------------------------------------------
        | DATA PRIORITAS
        |--------------------------------------------------------------------------
        */

          $operationalPriorities = [
              [
                  'title' => 'Pekerjaan terlambat',
                  'description' => 'Enam pekerjaan perlu dijadwalkan ulang dan dikonfirmasi kepada tim terkait.',
                  'icon' => 'clock',
                  'status' => 'Mendesak',
                  'status_class' => 'danger',
                  'action' => 'Tinjau pekerjaan',
              ],
              [
                  'title' => 'Ketersediaan sumber daya',
                  'description' => 'Satu tim lapangan memiliki tingkat beban kerja di atas batas strategis.',
                  'icon' => 'users',
                  'status' => 'Perhatian',
                  'status_class' => 'warning',
                  'action' => 'Atur tim',
              ],
              [
                  'title' => 'Inspeksi peralatan',
                  'description' => 'Dua perangkat strategis harus diperiksa sebelum digunakan kembali.',
                  'icon' => 'tool',
                  'status' => 'Terjadwal',
                  'status_class' => 'info',
                  'action' => 'Lihat inspeksi',
              ],
              [
                  'title' => 'Koordinasi vendor',
                  'description' => 'Konfirmasi kedatangan vendor dan kelengkapan material masih menunggu.',
                  'icon' => 'truck',
                  'status' => 'Menunggu',
                  'status_class' => 'neutral',
                  'action' => 'Hubungi vendor',
              ],
          ];

          /*
        |--------------------------------------------------------------------------
        | DATA JADWAL PEKERJAAN
        |--------------------------------------------------------------------------
        */

          $operationalSchedules = [
              [
                  'code' => 'OPR-2601',
                  'task' => 'Inspeksi lokasi Gedung A',
                  'category' => 'Inspeksi',
                  'team' => 'Tim Lapangan 1',
                  'leader' => 'Andi Saputra',
                  'time' => '08.00 – 09.30',
                  'location' => 'Gedung A',
                  'progress' => 100,
                  'status' => 'Selesai',
                  'priority' => 'Normal',
              ],
              [
                  'code' => 'OPR-2602',
                  'task' => 'Pemeliharaan Unit Produksi B',
                  'category' => 'Pemeliharaan',
                  'team' => 'Tim Teknik 2',
                  'leader' => 'Rizky Maulana',
                  'time' => '10.00 – 12.30',
                  'location' => 'Unit Produksi B',
                  'progress' => 68,
                  'status' => 'Berjalan',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'OPR-2603',
                  'task' => 'Instalasi perangkat strategis',
                  'category' => 'Instalasi',
                  'team' => 'Tim Teknik 3',
                  'leader' => 'Dimas Pratama',
                  'time' => '13.00 – 15.00',
                  'location' => 'Area Strategis C',
                  'progress' => 25,
                  'status' => 'Terjadwal',
                  'priority' => 'Normal',
              ],
              [
                  'code' => 'OPR-2604',
                  'task' => 'Validasi hasil pekerjaan vendor',
                  'category' => 'Validasi',
                  'team' => 'Tim Quality Assurance',
                  'leader' => 'Nadia Putri',
                  'time' => '15.30 – 16.30',
                  'location' => 'Gudang Utama',
                  'progress' => 0,
                  'status' => 'Terjadwal',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'OPR-2605',
                  'task' => 'Pengiriman material lapangan',
                  'category' => 'Logistik',
                  'team' => 'Tim Logistik',
                  'leader' => 'Fajar Ramadhan',
                  'time' => '16.00 – 17.30',
                  'location' => 'Lokasi Proyek D',
                  'progress' => 10,
                  'status' => 'Tertunda',
                  'priority' => 'Mendesak',
              ],
          ];

          /*
        |--------------------------------------------------------------------------
        | DATA BEBAN TIM
        |--------------------------------------------------------------------------
        */

          $teamWorkloads = [
              [
                  'name' => 'Tim Lapangan 1',
                  'members' => 8,
                  'active_jobs' => 6,
                  'load' => 76,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Tim Teknik 2',
                  'members' => 6,
                  'active_jobs' => 5,
                  'load' => 92,
                  'status' => 'Tinggi',
              ],
              [
                  'name' => 'Tim Teknik 3',
                  'members' => 7,
                  'active_jobs' => 4,
                  'load' => 64,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Tim Logistik',
                  'members' => 5,
                  'active_jobs' => 3,
                  'load' => 58,
                  'status' => 'Normal',
              ],
          ];

          /*
        |--------------------------------------------------------------------------
        | DATA AKTIVITAS
        |--------------------------------------------------------------------------
        */

          $operationalActivities = [
              [
                  'title' => 'Pekerjaan berhasil diselesaikan',
                  'description' => 'Inspeksi lokasi Gedung A selesai tanpa kendala strategis.',
                  'time' => '14 menit lalu',
                  'icon' => 'check-circle',
                  'theme' => 'green',
              ],
              [
                  'title' => 'Jadwal pekerjaan diperbarui',
                  'description' => 'Pemeliharaan Unit Produksi B dimundurkan selama 30 menit.',
                  'time' => '28 menit lalu',
                  'icon' => 'calendar',
                  'theme' => 'blue',
              ],
              [
                  'title' => 'Keterlambatan pekerjaan dilaporkan',
                  'description' => 'Pengiriman material terkendala karena kendaraan strategis belum tersedia.',
                  'time' => '50 menit lalu',
                  'icon' => 'alert-triangle',
                  'theme' => 'red',
              ],
              [
                  'title' => 'Konfirmasi vendor diterima',
                  'description' => 'Vendor pemeliharaan mengonfirmasi jadwal kedatangan untuk besok.',
                  'time' => '1 jam lalu',
                  'icon' => 'truck',
                  'theme' => 'orange',
              ],
              [
                  'title' => 'Penugasan tim baru',
                  'description' => 'Tim Teknik 3 ditugaskan menangani instalasi perangkat strategis.',
                  'time' => '2 jam lalu',
                  'icon' => 'users',
                  'theme' => 'purple',
              ],
          ];

          $currentUserName = auth()->user()->name ?? 'Direktur Utama';
     @endphp

     <style>
          /*
                  |--------------------------------------------------------------------------
                  | DASHBOARD VARIABLES
                  |--------------------------------------------------------------------------
                  */

          .director-dashboard {
               --op-primary: #ea580c;
               --op-primary-dark: #c2410c;
               --op-primary-soft: rgba(234, 88, 12, 0.12);
               --op-secondary: #dc2626;
               --op-success: #16a34a;
               --op-warning: #d97706;
               --op-danger: #dc2626;
               --op-info: #2563eb;
               --op-purple: #7c3aed;
               --op-heading: #172033;
               --op-text: #5f6b7a;
               --op-muted: #8b95a5;
               --op-border: #e9edf3;
               --op-background: #f4f6fa;
               --op-card: #ffffff;
               --op-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
               --op-shadow-hover: 0 18px 40px rgba(15, 23, 42, 0.11);

               width: 100%;
               min-height: 100vh;
               padding: 24px;
               background:
                    radial-gradient(circle at top right,
                         rgba(234, 88, 12, 0.07),
                         transparent 26%),
                    var(--op-background);
               color: var(--op-text);
          }

          html[data-theme="dark"] .director-dashboard,
          body.dark-theme .director-dashboard,
          body.dark-mode .director-dashboard {
               --op-heading: #f8fafc;
               --op-text: #cbd5e1;
               --op-muted: #94a3b8;
               --op-border: rgba(148, 163, 184, 0.16);
               --op-background: #0f172a;
               --op-card: #182235;
               --op-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
               --op-shadow-hover: 0 18px 40px rgba(0, 0, 0, 0.28);
          }

          .director-dashboard *,
          .director-dashboard *::before,
          .director-dashboard *::after {
               box-sizing: border-box;
          }

          /*
                  |--------------------------------------------------------------------------
                  | HEADER
                  |--------------------------------------------------------------------------
                  */

          .director-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 24px;
               min-height: 210px;
               margin-bottom: 24px;
               padding: 34px 38px;
               border-radius: 24px;
               background:
                    linear-gradient(125deg,
                         rgba(124, 45, 18, 0.97),
                         rgba(234, 88, 12, 0.96) 50%,
                         rgba(220, 38, 38, 0.9));
               box-shadow: 0 22px 48px rgba(194, 65, 12, 0.24);
          }

          .director-hero::before {
               position: absolute;
               top: -120px;
               right: -80px;
               width: 360px;
               height: 360px;
               border: 70px solid rgba(255, 255, 255, 0.07);
               border-radius: 50%;
               content: "";
          }

          .director-hero::after {
               position: absolute;
               right: 210px;
               bottom: -130px;
               width: 270px;
               height: 270px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.06);
               content: "";
          }

          .director-hero-content {
               position: relative;
               z-index: 2;
               max-width: 720px;
          }

          .director-role-badge {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 18px;
               padding: 8px 13px;
               border: 1px solid rgba(255, 255, 255, 0.22);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.13);
               color: #ffffff;
               font-size: 12px;
               font-weight: 700;
               letter-spacing: 0.04em;
               text-transform: uppercase;
               backdrop-filter: blur(10px);
          }

          .director-role-badge .badge-indicator {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #86efac;
               box-shadow: 0 0 0 5px rgba(134, 239, 172, 0.18);
          }

          .director-hero h1 {
               margin: 0 0 12px;
               color: #ffffff;
               font-size: clamp(28px, 4vw, 42px);
               font-weight: 800;
               letter-spacing: -0.035em;
               line-height: 1.12;
          }

          .director-hero-description {
               max-width: 650px;
               margin: 0;
               color: rgba(255, 255, 255, 0.83);
               font-size: 15px;
               line-height: 1.75;
          }

          .director-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 18px;
               margin-top: 20px;
          }

          .director-hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.86);
               font-size: 12px;
               font-weight: 600;
          }

          .director-hero-meta-item svg {
               width: 15px;
               height: 15px;
          }

          .director-hero-actions {
               position: relative;
               z-index: 2;
               display: flex;
               flex-shrink: 0;
               flex-direction: column;
               gap: 11px;
               width: 205px;
          }

          .director-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 44px;
               padding: 11px 17px;
               border: 0;
               border-radius: 12px;
               font-size: 13px;
               font-weight: 700;
               cursor: pointer;
               transition:
                    transform 0.2s ease,
                    background 0.2s ease,
                    box-shadow 0.2s ease;
          }

          .director-button:hover {
               transform: translateY(-2px);
          }

          .director-button svg {
               width: 16px;
               height: 16px;
          }

          .director-button-primary {
               background: #ffffff;
               color: var(--op-primary-dark);
               box-shadow: 0 10px 22px rgba(124, 45, 18, 0.2);
          }

          .director-button-secondary {
               border: 1px solid rgba(255, 255, 255, 0.24);
               background: rgba(255, 255, 255, 0.12);
               color: #ffffff;
               backdrop-filter: blur(10px);
          }

          /*
                  |--------------------------------------------------------------------------
                  | GRID
                  |--------------------------------------------------------------------------
                  */

          .director-stat-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 18px;
               margin-bottom: 24px;
          }

          .director-main-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.7fr) minmax(320px, 0.8fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .director-secondary-grid {
               display: grid;
               grid-template-columns: minmax(320px, 0.78fr) minmax(0, 1.45fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .director-bottom-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.7fr);
               gap: 22px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | STATISTIC CARDS
                  |--------------------------------------------------------------------------
                  */

          .director-stat-card {
               position: relative;
               overflow: hidden;
               min-height: 170px;
               padding: 21px;
               border: 1px solid var(--op-border);
               border-radius: 18px;
               background: var(--op-card);
               box-shadow: var(--op-shadow);
               transition:
                    transform 0.25s ease,
                    box-shadow 0.25s ease,
                    border-color 0.25s ease;
          }

          .director-stat-card:hover {
               transform: translateY(-4px);
               border-color: rgba(234, 88, 12, 0.25);
               box-shadow: var(--op-shadow-hover);
          }

          .director-stat-card::after {
               position: absolute;
               top: -28px;
               right: -28px;
               width: 95px;
               height: 95px;
               border-radius: 50%;
               content: "";
               opacity: 0.7;
          }

          .director-stat-card.theme-orange::after {
               background: rgba(234, 88, 12, 0.08);
          }

          .director-stat-card.theme-green::after {
               background: rgba(22, 163, 74, 0.08);
          }

          .director-stat-card.theme-red::after {
               background: rgba(220, 38, 38, 0.08);
          }

          .director-stat-card.theme-blue::after {
               background: rgba(37, 99, 235, 0.08);
          }

          .director-stat-top {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 16px;
          }

          .director-stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
          }

          .director-stat-icon svg {
               width: 21px;
               height: 21px;
          }

          .theme-orange .director-stat-icon {
               background: rgba(234, 88, 12, 0.12);
               color: #ea580c;
          }

          .theme-green .director-stat-icon {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .theme-red .director-stat-icon {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .theme-blue .director-stat-icon {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .director-stat-trend {
               display: inline-flex;
               align-items: center;
               gap: 4px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 11px;
               font-weight: 800;
          }

          .director-stat-trend svg {
               width: 12px;
               height: 12px;
          }

          .director-stat-trend.up {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .director-stat-trend.down {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .director-stat-label {
               margin: 18px 0 7px;
               color: var(--op-muted);
               font-size: 12px;
               font-weight: 700;
          }

          .director-stat-value {
               display: flex;
               align-items: baseline;
               gap: 2px;
               margin: 0;
               color: var(--op-heading);
               font-size: 29px;
               font-weight: 800;
               letter-spacing: -0.03em;
               line-height: 1;
          }

          .director-stat-description {
               margin: 10px 0 0;
               color: var(--op-text);
               font-size: 11px;
               line-height: 1.5;
          }

          /*
                  |--------------------------------------------------------------------------
                  | COMMON CARD
                  |--------------------------------------------------------------------------
                  */

          .director-card {
               border: 1px solid var(--op-border);
               border-radius: 20px;
               background: var(--op-card);
               box-shadow: var(--op-shadow);
          }

          .director-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               padding: 22px 23px 17px;
               border-bottom: 1px solid var(--op-border);
          }

          .director-card-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .director-card-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 39px;
               height: 39px;
               border-radius: 12px;
               background: var(--op-primary-soft);
               color: var(--op-primary);
          }

          .director-card-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .director-card-title {
               margin: 0;
               color: var(--op-heading);
               font-size: 16px;
               font-weight: 800;
               letter-spacing: -0.015em;
          }

          .director-card-subtitle {
               margin: 5px 0 0;
               color: var(--op-muted);
               font-size: 11px;
               line-height: 1.55;
          }

          .director-card-action {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 8px 10px;
               border: 1px solid var(--op-border);
               border-radius: 10px;
               background: transparent;
               color: var(--op-text);
               font-size: 11px;
               font-weight: 700;
               cursor: pointer;
          }

          .director-card-action svg {
               width: 14px;
               height: 14px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | CHART
                  |--------------------------------------------------------------------------
                  */

          .director-chart-body {
               padding: 22px 23px 24px;
          }

          .director-chart-summary {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 22px;
          }

          .director-chart-legends {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
          }

          .director-chart-legend {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               color: var(--op-text);
               font-size: 11px;
               font-weight: 700;
          }

          .director-chart-legend-dot {
               width: 9px;
               height: 9px;
               border-radius: 3px;
          }

          .director-chart-legend-dot.scheduled {
               background: rgba(234, 88, 12, 0.26);
          }

          .director-chart-legend-dot.completed {
               background: var(--op-primary);
          }

          .director-chart-rate {
               text-align: right;
          }

          .director-chart-rate strong {
               display: block;
               color: var(--op-heading);
               font-size: 19px;
               font-weight: 800;
          }

          .director-chart-rate span {
               color: var(--op-muted);
               font-size: 10px;
          }

          .director-chart-area {
               position: relative;
               display: grid;
               grid-template-columns: 34px minmax(0, 1fr);
               gap: 11px;
               height: 285px;
          }

          .director-chart-y-axis {
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               height: 245px;
               padding-top: 2px;
               color: var(--op-muted);
               font-size: 9px;
               text-align: right;
          }

          .director-chart-content {
               position: relative;
               height: 285px;
          }

          .director-chart-lines {
               position: absolute;
               inset: 0 0 40px;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               pointer-events: none;
          }

          .director-chart-line {
               width: 100%;
               border-top: 1px dashed var(--op-border);
          }

          .director-chart-columns {
               position: absolute;
               inset: 0 0 0;
               display: grid;
               grid-template-columns: repeat(7, minmax(30px, 1fr));
               gap: 12px;
          }

          .director-chart-column {
               display: flex;
               flex-direction: column;
               align-items: center;
               min-width: 0;
          }

          .director-chart-bars {
               display: flex;
               align-items: flex-end;
               justify-content: center;
               gap: 5px;
               width: 100%;
               height: 245px;
          }

          .director-chart-bar {
               position: relative;
               width: min(16px, 38%);
               min-height: 4px;
               border-radius: 6px 6px 3px 3px;
               cursor: pointer;
               transition:
                    filter 0.2s ease,
                    transform 0.2s ease;
          }

          .director-chart-bar:hover {
               z-index: 3;
               filter: brightness(0.93);
               transform: scaleX(1.08);
          }

          .director-chart-bar.scheduled {
               background: rgba(234, 88, 12, 0.26);
          }

          .director-chart-bar.completed {
               background: linear-gradient(to top, #c2410c, #fb923c);
               box-shadow: 0 5px 12px rgba(234, 88, 12, 0.2);
          }

          .director-chart-tooltip {
               position: absolute;
               bottom: calc(100% + 8px);
               left: 50%;
               z-index: 10;
               min-width: 65px;
               padding: 6px 8px;
               border-radius: 7px;
               background: #172033;
               color: #ffffff;
               font-size: 9px;
               font-weight: 700;
               text-align: center;
               white-space: nowrap;
               opacity: 0;
               pointer-events: none;
               transform: translateX(-50%) translateY(3px);
               transition:
                    opacity 0.2s ease,
                    transform 0.2s ease;
          }

          .director-chart-bar:hover .director-chart-tooltip {
               opacity: 1;
               transform: translateX(-50%) translateY(0);
          }

          .director-chart-day {
               margin-top: 13px;
               color: var(--op-muted);
               font-size: 10px;
               font-weight: 700;
          }

          /*
                  |--------------------------------------------------------------------------
                  | OPERATIONAL HEALTH
                  |--------------------------------------------------------------------------
                  */

          .director-health-body {
               padding: 24px;
          }

          .director-utilization {
               display: flex;
               align-items: center;
               gap: 22px;
               margin-bottom: 25px;
               padding-bottom: 24px;
               border-bottom: 1px solid var(--op-border);
          }

          .director-utilization-ring {
               position: relative;
               display: grid;
               flex-shrink: 0;
               width: 118px;
               height: 118px;
               place-items: center;
               border-radius: 50%;
               background:
                    conic-gradient(var(--op-primary) 0deg 313deg,
                         var(--op-border) 313deg 360deg);
          }

          .director-utilization-ring::before {
               position: absolute;
               width: 88px;
               height: 88px;
               border-radius: 50%;
               background: var(--op-card);
               content: "";
          }

          .director-utilization-value {
               position: relative;
               z-index: 2;
               color: var(--op-heading);
               font-size: 24px;
               font-weight: 800;
          }

          .director-utilization-details h3 {
               margin: 0 0 6px;
               color: var(--op-heading);
               font-size: 14px;
               font-weight: 800;
          }

          .director-utilization-details p {
               margin: 0 0 11px;
               color: var(--op-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .director-utilization-status {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 9px;
               border-radius: 999px;
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
               font-size: 10px;
               font-weight: 800;
          }

          .director-utilization-status::before {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: #22c55e;
               content: "";
          }

          .director-health-list {
               display: flex;
               flex-direction: column;
               gap: 17px;
          }

          .director-health-item-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 8px;
          }

          .director-health-item-label {
               color: var(--op-text);
               font-size: 11px;
               font-weight: 700;
          }

          .director-health-item-value {
               color: var(--op-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .director-progress {
               overflow: hidden;
               width: 100%;
               height: 7px;
               border-radius: 999px;
               background: var(--op-border);
          }

          .director-progress-bar {
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, var(--op-primary-dark), #fb923c);
          }

          .director-progress-bar.success {
               background: linear-gradient(90deg, #15803d, #4ade80);
          }

          .director-progress-bar.info {
               background: linear-gradient(90deg, #1d4ed8, #60a5fa);
          }

          .director-progress-bar.warning {
               background: linear-gradient(90deg, #b45309, #fbbf24);
          }

          /*
                  |--------------------------------------------------------------------------
                  | PRIORITY
                  |--------------------------------------------------------------------------
                  */

          .director-priority-list {
               display: flex;
               flex-direction: column;
          }

          .director-priority-item {
               display: flex;
               gap: 13px;
               padding: 17px 21px;
               border-bottom: 1px solid var(--op-border);
               transition: background 0.2s ease;
          }

          .director-priority-item:last-child {
               border-bottom: 0;
          }

          .director-priority-item:hover {
               background: rgba(234, 88, 12, 0.035);
          }

          .director-priority-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 38px;
               height: 38px;
               border-radius: 11px;
               background: var(--op-primary-soft);
               color: var(--op-primary);
          }

          .director-priority-icon svg {
               width: 17px;
               height: 17px;
          }

          .director-priority-content {
               min-width: 0;
               flex: 1;
          }

          .director-priority-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 10px;
          }

          .director-priority-title {
               margin: 1px 0 0;
               color: var(--op-heading);
               font-size: 12px;
               font-weight: 800;
          }

          .director-priority-description {
               margin: 6px 0 11px;
               color: var(--op-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .director-priority-action {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               border: 0;
               background: transparent;
               color: var(--op-primary);
               font-size: 10px;
               font-weight: 800;
               cursor: pointer;
          }

          .director-priority-action svg {
               width: 12px;
               height: 12px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | BADGES
                  |--------------------------------------------------------------------------
                  */

          .director-badge {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 5px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 9px;
               font-weight: 800;
               white-space: nowrap;
          }

          .director-badge::before {
               width: 5px;
               height: 5px;
               border-radius: 50%;
               background: currentColor;
               content: "";
          }

          .director-badge.success {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .director-badge.info {
               background: rgba(37, 99, 235, 0.1);
               color: #2563eb;
          }

          .director-badge.warning {
               background: rgba(217, 119, 6, 0.1);
               color: #b45309;
          }

          .director-badge.danger {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .director-badge.neutral {
               background: rgba(100, 116, 139, 0.12);
               color: #64748b;
          }

          /*
                  |--------------------------------------------------------------------------
                  | TABLE
                  |--------------------------------------------------------------------------
                  */

          .director-schedule-card {
               overflow: hidden;
          }

          .director-schedule-toolbar {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 14px 20px;
               border-bottom: 1px solid var(--op-border);
          }

          .director-filter-list {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .director-filter-button {
               padding: 7px 10px;
               border: 1px solid var(--op-border);
               border-radius: 8px;
               background: transparent;
               color: var(--op-muted);
               font-size: 9px;
               font-weight: 800;
               cursor: pointer;
               transition:
                    background 0.2s ease,
                    color 0.2s ease,
                    border-color 0.2s ease;
          }

          .director-filter-button:hover,
          .director-filter-button.active {
               border-color: var(--op-primary);
               background: var(--op-primary);
               color: #ffffff;
          }

          .director-search {
               position: relative;
               width: 195px;
          }

          .director-search svg {
               position: absolute;
               top: 50%;
               left: 11px;
               width: 14px;
               height: 14px;
               color: var(--op-muted);
               transform: translateY(-50%);
          }

          .director-search input {
               width: 100%;
               height: 35px;
               padding: 7px 11px 7px 33px;
               border: 1px solid var(--op-border);
               border-radius: 9px;
               outline: none;
               background: transparent;
               color: var(--op-heading);
               font-size: 10px;
          }

          .director-search input:focus {
               border-color: var(--op-primary);
               box-shadow: 0 0 0 3px var(--op-primary-soft);
          }

          .director-table-wrapper {
               overflow-x: auto;
          }

          .director-table {
               width: 100%;
               min-width: 840px;
               border-collapse: collapse;
          }

          .director-table th {
               padding: 12px 16px;
               border-bottom: 1px solid var(--op-border);
               background: rgba(148, 163, 184, 0.045);
               color: var(--op-muted);
               font-size: 9px;
               font-weight: 800;
               letter-spacing: 0.04em;
               text-align: left;
               text-transform: uppercase;
          }

          .director-table td {
               padding: 15px 16px;
               border-bottom: 1px solid var(--op-border);
               color: var(--op-text);
               font-size: 10px;
               vertical-align: middle;
          }

          .director-table tbody tr {
               transition: background 0.2s ease;
          }

          .director-table tbody tr:hover {
               background: rgba(234, 88, 12, 0.028);
          }

          .director-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .director-task {
               display: flex;
               align-items: center;
               gap: 11px;
               min-width: 215px;
          }

          .director-task-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 35px;
               height: 35px;
               border-radius: 10px;
               background: var(--op-primary-soft);
               color: var(--op-primary);
          }

          .director-task-icon svg {
               width: 15px;
               height: 15px;
          }

          .director-task-title {
               display: block;
               margin-bottom: 3px;
               color: var(--op-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .director-task-code {
               color: var(--op-muted);
               font-size: 9px;
          }

          .director-team-name {
               display: block;
               color: var(--op-heading);
               font-weight: 700;
          }

          .director-team-leader {
               display: block;
               margin-top: 3px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .director-time {
               color: var(--op-heading);
               font-weight: 700;
          }

          .director-location {
               display: block;
               margin-top: 3px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .director-table-progress {
               min-width: 105px;
          }

          .director-table-progress-top {
               display: flex;
               justify-content: space-between;
               margin-bottom: 6px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .director-table-progress .director-progress {
               height: 6px;
          }

          .director-action-menu {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 31px;
               height: 31px;
               border: 1px solid var(--op-border);
               border-radius: 8px;
               background: transparent;
               color: var(--op-muted);
               cursor: pointer;
          }

          .director-action-menu svg {
               width: 15px;
               height: 15px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | TEAM WORKLOAD
                  |--------------------------------------------------------------------------
                  */

          .director-team-list {
               padding: 4px 21px 9px;
          }

          .director-team-item {
               padding: 17px 0;
               border-bottom: 1px solid var(--op-border);
          }

          .director-team-item:last-child {
               border-bottom: 0;
          }

          .director-team-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 10px;
          }

          .director-team-header h4 {
               margin: 0;
               color: var(--op-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .director-team-metadata {
               display: flex;
               gap: 14px;
               margin-top: 4px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .director-team-percentage {
               color: var(--op-heading);
               font-size: 12px;
               font-weight: 800;
          }

          /*
                  |--------------------------------------------------------------------------
                  | ACTIVITY
                  |--------------------------------------------------------------------------
                  */

          .director-activity-list {
               padding: 4px 21px 10px;
          }

          .director-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 17px 0;
          }

          .director-activity-item:not(:last-child)::before {
               position: absolute;
               top: 52px;
               bottom: -3px;
               left: 18px;
               width: 1px;
               background: var(--op-border);
               content: "";
          }

          .director-activity-icon {
               position: relative;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 37px;
               height: 37px;
               border: 4px solid var(--op-card);
               border-radius: 50%;
          }

          .director-activity-icon svg {
               width: 14px;
               height: 14px;
          }

          .director-activity-icon.green {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .director-activity-icon.blue {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .director-activity-icon.red {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .director-activity-icon.orange {
               background: rgba(234, 88, 12, 0.12);
               color: #ea580c;
          }

          .director-activity-icon.purple {
               background: rgba(124, 58, 237, 0.12);
               color: #7c3aed;
          }

          .director-activity-content {
               min-width: 0;
               flex: 1;
          }

          .director-activity-content h4 {
               margin: 1px 0 5px;
               color: var(--op-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .director-activity-content p {
               margin: 0;
               color: var(--op-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .director-activity-time {
               margin-top: 6px;
               color: var(--op-primary);
               font-size: 9px;
               font-weight: 700;
          }

          /*
                  |--------------------------------------------------------------------------
                  | EMPTY STATE
                  |--------------------------------------------------------------------------
                  */

          .director-empty-state {
               display: none;
               padding: 38px 20px;
               text-align: center;
          }

          .director-empty-state svg {
               width: 34px;
               height: 34px;
               margin-bottom: 10px;
               color: var(--op-muted);
          }

          .director-empty-state h4 {
               margin: 0 0 5px;
               color: var(--op-heading);
               font-size: 13px;
          }

          .director-empty-state p {
               margin: 0;
               color: var(--op-muted);
               font-size: 10px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | RESPONSIVE
                  |--------------------------------------------------------------------------
                  */

          @media (max-width: 1280px) {
               .director-stat-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .director-main-grid,
               .director-secondary-grid,
               .director-bottom-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 768px) {
               .director-dashboard {
                    padding: 15px;
               }

               .director-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    min-height: auto;
                    padding: 26px 23px;
                    border-radius: 19px;
               }

               .director-hero-actions {
                    flex-direction: row;
                    width: 100%;
               }

               .director-hero-actions .director-button {
                    flex: 1;
               }

               .director-stat-grid {
                    grid-template-columns: 1fr;
                    gap: 13px;
               }

               .director-stat-card {
                    min-height: auto;
               }

               .director-card-header,
               .director-schedule-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .director-search {
                    width: 100%;
               }

               .director-chart-area {
                    grid-template-columns: 26px minmax(0, 1fr);
               }

               .director-chart-columns {
                    gap: 5px;
               }

               .director-chart-bars {
                    gap: 3px;
               }

               .director-utilization {
                    align-items: flex-start;
               }
          }

          @media (max-width: 520px) {
               .director-hero-actions {
                    flex-direction: column;
               }

               .director-chart-summary {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .director-chart-rate {
                    text-align: left;
               }

               .director-chart-column:nth-child(even) .director-chart-day {
                    opacity: 0.55;
               }

               .director-utilization {
                    align-items: center;
                    flex-direction: column;
                    text-align: center;
               }
          }
     </style>

     <div class="director-dashboard">

          {{-- ================================================================
             HEADER
        ================================================================= --}}

          <section class="director-hero">
               <div class="director-hero-content">
                    <div class="director-role-badge">
                         <span class="badge-indicator"></span>
                         Direktur Utama
                    </div>

                    <h1>Executive Strategic Control Center</h1>

                    <p class="director-hero-description">
                         Selamat datang, {{ $currentUserName }}. Pantau pekerjaan aktif,
                         utilisasi sumber daya, jadwal lapangan, keterlambatan, serta
                         kondisi strategis perusahaan dari satu dashboard terintegrasi.
                    </p>

                    <div class="director-hero-meta">
                         <span class="director-hero-meta-item">
                              <i data-feather="calendar"></i>
                              {{ now()->translatedFormat('l, d F Y') }}
                         </span>

                         <span class="director-hero-meta-item">
                              <i data-feather="clock"></i>
                              Shift strategis aktif
                         </span>

                         <span class="director-hero-meta-item">
                              <i data-feather="wifi"></i>
                              Sistem terhubung
                         </span>
                    </div>
               </div>

               <div class="director-hero-actions">
                    <button type="button" class="director-button director-button-primary">
                         <i data-feather="plus-circle"></i>
                         Buat Keputusan
                    </button>

                    <button type="button" class="director-button director-button-secondary">
                         <i data-feather="download"></i>
                         Unduh Executive Report
                    </button>
               </div>
          </section>

          {{-- ================================================================
             STATISTICS
        ================================================================= --}}

          <section class="director-stat-grid">
               @foreach ($statistics as $statistic)
                    <article class="director-stat-card theme-{{ $statistic['theme'] }}">
                         <div class="director-stat-top">
                              <span class="director-stat-icon">
                                   <i data-feather="{{ $statistic['icon'] }}"></i>
                              </span>

                              <span class="director-stat-trend {{ $statistic['trend_type'] }}">
                                   <i
                                        data-feather="{{ $statistic['trend_type'] === 'up' ? 'trending-up' : 'alert-circle' }}"></i>

                                   {{ $statistic['trend'] }}
                              </span>
                         </div>

                         <p class="director-stat-label">
                              {{ $statistic['label'] }}
                         </p>

                         <h2 class="director-stat-value">
                              <span>{{ $statistic['value'] }}</span>
                              <span>{{ $statistic['suffix'] }}</span>
                         </h2>

                         <p class="director-stat-description">
                              {{ $statistic['description'] }}
                         </p>
                    </article>
               @endforeach
          </section>

          {{-- ================================================================
             CHART DAN KONDISI OPERASIONAL
        ================================================================= --}}

          <section class="director-main-grid">

               {{-- Kinerja mingguan --}}
               <article class="director-card">
                    <header class="director-card-header">
                         <div class="director-card-heading">
                              <span class="director-card-heading-icon">
                                   <i data-feather="bar-chart-2"></i>
                              </span>

                              <div>
                                   <h2 class="director-card-title">
                                        Kinerja Strategis Mingguan
                                   </h2>

                                   <p class="director-card-subtitle">
                                        Perbandingan pekerjaan dijadwalkan dan diselesaikan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="director-card-action">
                              Minggu ini
                              <i data-feather="chevron-down"></i>
                         </button>
                    </header>

                    <div class="director-chart-body">
                         <div class="director-chart-summary">
                              <div class="director-chart-legends">
                                   <span class="director-chart-legend">
                                        <span class="director-chart-legend-dot scheduled"></span>
                                        Dijadwalkan
                                   </span>

                                   <span class="director-chart-legend">
                                        <span class="director-chart-legend-dot completed"></span>
                                        Diselesaikan
                                   </span>
                              </div>

                              <div class="director-chart-rate">
                                   <strong>84,6%</strong>
                                   <span>Rata-rata penyelesaian</span>
                              </div>
                         </div>

                         <div class="director-chart-area">
                              <div class="director-chart-y-axis">
                                   <span>100</span>
                                   <span>75</span>
                                   <span>50</span>
                                   <span>25</span>
                                   <span>0</span>
                              </div>

                              <div class="director-chart-content">
                                   <div class="director-chart-lines">
                                        <span class="director-chart-line"></span>
                                        <span class="director-chart-line"></span>
                                        <span class="director-chart-line"></span>
                                        <span class="director-chart-line"></span>
                                        <span class="director-chart-line"></span>
                                   </div>

                                   <div class="director-chart-columns">
                                        @foreach ($weeklyPerformance as $performance)
                                             <div class="director-chart-column">
                                                  <div class="director-chart-bars">
                                                       <div class="director-chart-bar scheduled"
                                                            style="height: {{ $performance['scheduled'] }}%;">
                                                            <span class="director-chart-tooltip">
                                                                 {{ $performance['scheduled'] }} dijadwalkan
                                                            </span>
                                                       </div>

                                                       <div class="director-chart-bar completed"
                                                            style="height: {{ $performance['completed'] }}%;">
                                                            <span class="director-chart-tooltip">
                                                                 {{ $performance['completed'] }} selesai
                                                            </span>
                                                       </div>
                                                  </div>

                                                  <span class="director-chart-day" title="{{ $performance['full_day'] }}">
                                                       {{ $performance['day'] }}
                                                  </span>
                                             </div>
                                        @endforeach
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>

               {{-- Kondisi strategis --}}
               <article class="director-card">
                    <header class="director-card-header">
                         <div class="director-card-heading">
                              <span class="director-card-heading-icon">
                                   <i data-feather="pie-chart"></i>
                              </span>

                              <div>
                                   <h2 class="director-card-title">
                                        Kondisi Strategis
                                   </h2>

                                   <p class="director-card-subtitle">
                                        Ringkasan kapasitas dan kesehatan strategis.
                                   </p>
                              </div>
                         </div>
                    </header>

                    <div class="director-health-body">
                         <div class="director-utilization">
                              <div class="director-utilization-ring">
                                   <span class="director-utilization-value">87%</span>
                              </div>

                              <div class="director-utilization-details">
                                   <h3>Utilisasi sumber daya</h3>

                                   <p>
                                        Penggunaan tenaga kerja dan perangkat strategis
                                        masih berada dalam batas yang ditetapkan.
                                   </p>

                                   <span class="director-utilization-status">
                                        Kondisi aman
                                   </span>
                              </div>
                         </div>

                         <div class="director-health-list">
                              <div>
                                   <div class="director-health-item-top">
                                        <span class="director-health-item-label">
                                             Penyelesaian pekerjaan
                                        </span>

                                        <span class="director-health-item-value">
                                             84%
                                        </span>
                                   </div>

                                   <div class="director-progress">
                                        <div class="director-progress-bar success" style="width: 84%;"></div>
                                   </div>
                              </div>

                              <div>
                                   <div class="director-health-item-top">
                                        <span class="director-health-item-label">
                                             Ketersediaan personel
                                        </span>

                                        <span class="director-health-item-value">
                                             91%
                                        </span>
                                   </div>

                                   <div class="director-progress">
                                        <div class="director-progress-bar info" style="width: 91%;"></div>
                                   </div>
                              </div>

                              <div>
                                   <div class="director-health-item-top">
                                        <span class="director-health-item-label">
                                             Kesiapan peralatan
                                        </span>

                                        <span class="director-health-item-value">
                                             78%
                                        </span>
                                   </div>

                                   <div class="director-progress">
                                        <div class="director-progress-bar warning" style="width: 78%;"></div>
                                   </div>
                              </div>

                              <div>
                                   <div class="director-health-item-top">
                                        <span class="director-health-item-label">
                                             Kepatuhan jadwal
                                        </span>

                                        <span class="director-health-item-value">
                                             86%
                                        </span>
                                   </div>

                                   <div class="director-progress">
                                        <div class="director-progress-bar" style="width: 86%;"></div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
             PRIORITAS DAN JADWAL
        ================================================================= --}}

          <section class="director-secondary-grid">

               {{-- Prioritas --}}
               <article class="director-card">
                    <header class="director-card-header">
                         <div class="director-card-heading">
                              <span class="director-card-heading-icon">
                                   <i data-feather="alert-octagon"></i>
                              </span>

                              <div>
                                   <h2 class="director-card-title">
                                        Prioritas Strategis
                                   </h2>

                                   <p class="director-card-subtitle">
                                        Item yang membutuhkan tindak lanjut.
                                   </p>
                              </div>
                         </div>

                         <span class="director-badge danger">
                              4 prioritas
                         </span>
                    </header>

                    <div class="director-priority-list">
                         @foreach ($operationalPriorities as $priority)
                              <div class="director-priority-item">
                                   <span class="director-priority-icon">
                                        <i data-feather="{{ $priority['icon'] }}"></i>
                                   </span>

                                   <div class="director-priority-content">
                                        <div class="director-priority-heading">
                                             <h3 class="director-priority-title">
                                                  {{ $priority['title'] }}
                                             </h3>

                                             <span class="director-badge {{ $priority['status_class'] }}">
                                                  {{ $priority['status'] }}
                                             </span>
                                        </div>

                                        <p class="director-priority-description">
                                             {{ $priority['description'] }}
                                        </p>

                                        <button type="button" class="director-priority-action">
                                             {{ $priority['action'] }}
                                             <i data-feather="arrow-right"></i>
                                        </button>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Jadwal --}}
               <article class="director-card director-schedule-card">
                    <header class="director-card-header">
                         <div class="director-card-heading">
                              <span class="director-card-heading-icon">
                                   <i data-feather="clipboard"></i>
                              </span>

                              <div>
                                   <h2 class="director-card-title">
                                        Jadwal Strategis Hari Ini
                                   </h2>

                                   <p class="director-card-subtitle">
                                        Monitoring pekerjaan, tim, waktu, dan progres.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="director-card-action">
                              <i data-feather="calendar"></i>
                              Lihat kalender
                         </button>
                    </header>

                    <div class="director-schedule-toolbar">
                         <div class="director-filter-list">
                              <button type="button" class="director-filter-button active" data-operation-filter="semua">
                                   Semua
                              </button>

                              <button type="button" class="director-filter-button" data-operation-filter="berjalan">
                                   Berjalan
                              </button>

                              <button type="button" class="director-filter-button" data-operation-filter="terjadwal">
                                   Terjadwal
                              </button>

                              <button type="button" class="director-filter-button" data-operation-filter="tertunda">
                                   Tertunda
                              </button>

                              <button type="button" class="director-filter-button" data-operation-filter="selesai">
                                   Selesai
                              </button>
                         </div>

                         <label class="director-search">
                              <i data-feather="search"></i>

                              <input type="search" id="operationsScheduleSearch" placeholder="Cari pekerjaan..."
                                   autocomplete="off">
                         </label>
                    </div>

                    <div class="director-table-wrapper">
                         <table class="director-table">
                              <thead>
                                   <tr>
                                        <th>Pekerjaan</th>
                                        <th>Tim</th>
                                        <th>Waktu dan lokasi</th>
                                        <th>Progres</th>
                                        <th>Status</th>
                                        <th></th>
                                   </tr>
                              </thead>

                              <tbody id="operationsScheduleBody">
                                   @foreach ($operationalSchedules as $schedule)
                                        @php
                                             $scheduleStatusClass = match ($schedule['status']) {
                                                 'Selesai' => 'success',
                                                 'Berjalan' => 'info',
                                                 'Terjadwal' => 'neutral',
                                                 'Tertunda' => 'danger',
                                                 default => 'warning',
                                             };

                                             $scheduleIcon = match ($schedule['category']) {
                                                 'Inspeksi' => 'search',
                                                 'Pemeliharaan' => 'tool',
                                                 'Instalasi' => 'settings',
                                                 'Validasi' => 'check-square',
                                                 'Logistik' => 'truck',
                                                 default => 'briefcase',
                                             };
                                        @endphp

                                        <tr data-operation-row
                                             data-operation-status="{{ strtolower($schedule['status']) }}"
                                             data-operation-keyword="{{ strtolower(
                                                 $schedule['task'] .
                                                     ' ' .
                                                     $schedule['code'] .
                                                     ' ' .
                                                     $schedule['team'] .
                                                     ' ' .
                                                     $schedule['leader'] .
                                                     ' ' .
                                                     $schedule['location'],
                                             ) }}">
                                             <td>
                                                  <div class="director-task">
                                                       <span class="director-task-icon">
                                                            <i data-feather="{{ $scheduleIcon }}"></i>
                                                       </span>

                                                       <span>
                                                            <strong class="director-task-title">
                                                                 {{ $schedule['task'] }}
                                                            </strong>

                                                            <span class="director-task-code">
                                                                 {{ $schedule['code'] }}
                                                                 ·
                                                                 {{ $schedule['category'] }}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="director-team-name">
                                                       {{ $schedule['team'] }}
                                                  </span>

                                                  <span class="director-team-leader">
                                                       PIC: {{ $schedule['leader'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="director-time">
                                                       {{ $schedule['time'] }}
                                                  </span>

                                                  <span class="director-location">
                                                       {{ $schedule['location'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="director-table-progress">
                                                       <div class="director-table-progress-top">
                                                            <span>Progres</span>
                                                            <strong>
                                                                 {{ $schedule['progress'] }}%
                                                            </strong>
                                                       </div>

                                                       <div class="director-progress">
                                                            <div class="director-progress-bar
                                                        {{ $schedule['progress'] === 100 ? 'success' : '' }}"
                                                                 style="width: {{ $schedule['progress'] }}%;"></div>
                                                       </div>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="director-badge {{ $scheduleStatusClass }}">
                                                       {{ $schedule['status'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <button type="button" class="director-action-menu"
                                                       aria-label="Pilihan pekerjaan {{ $schedule['task'] }}">
                                                       <i data-feather="more-horizontal"></i>
                                                  </button>
                                             </td>
                                        </tr>
                                   @endforeach
                              </tbody>
                         </table>

                         <div class="director-empty-state" id="operationsScheduleEmpty">
                              <i data-feather="search"></i>

                              <h4>Pekerjaan tidak ditemukan</h4>

                              <p>
                                   Gunakan kata kunci atau filter status yang berbeda.
                              </p>
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
             BEBAN TIM DAN AKTIVITAS
        ================================================================= --}}

          <section class="director-bottom-grid">

               {{-- Beban kerja tim --}}
               <article class="director-card">
                    <header class="director-card-header">
                         <div class="director-card-heading">
                              <span class="director-card-heading-icon">
                                   <i data-feather="users"></i>
                              </span>

                              <div>
                                   <h2 class="director-card-title">
                                        Beban Kerja Tim
                                   </h2>

                                   <p class="director-card-subtitle">
                                        Kapasitas personel berdasarkan pekerjaan aktif.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="director-card-action">
                              Kelola tim
                              <i data-feather="arrow-up-right"></i>
                         </button>
                    </header>

                    <div class="director-team-list">
                         @foreach ($teamWorkloads as $team)
                              @php
                                   $loadClass = match (true) {
                                       $team['load'] >= 90 => 'warning',
                                       $team['load'] >= 75 => '',
                                       default => 'success',
                                   };
                              @endphp

                              <div class="director-team-item">
                                   <div class="director-team-header">
                                        <div>
                                             <h4>{{ $team['name'] }}</h4>

                                             <div class="director-team-metadata">
                                                  <span>
                                                       {{ $team['members'] }} personel
                                                  </span>

                                                  <span>
                                                       {{ $team['active_jobs'] }} pekerjaan aktif
                                                  </span>
                                             </div>
                                        </div>

                                        <span class="director-team-percentage">
                                             {{ $team['load'] }}%
                                        </span>
                                   </div>

                                   <div class="director-progress">
                                        <div class="director-progress-bar {{ $loadClass }}"
                                             style="width: {{ $team['load'] }}%;"></div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Aktivitas --}}
               <article class="director-card">
                    <header class="director-card-header">
                         <div class="director-card-heading">
                              <span class="director-card-heading-icon">
                                   <i data-feather="bell"></i>
                              </span>

                              <div>
                                   <h2 class="director-card-title">
                                        Aktivitas Strategis
                                   </h2>

                                   <p class="director-card-subtitle">
                                        Pembaruan strategis terbaru.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="director-card-action" id="markOperationsRead">
                              Tandai dibaca
                         </button>
                    </header>

                    <div class="director-activity-list" id="operationsActivityList">
                         @foreach ($operationalActivities as $activity)
                              <div class="director-activity-item" data-operation-activity>
                                   <span class="director-activity-icon {{ $activity['theme'] }}">
                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                   </span>

                                   <div class="director-activity-content">
                                        <h4>{{ $activity['title'] }}</h4>

                                        <p>{{ $activity['description'] }}</p>

                                        <div class="director-activity-time">
                                             {{ $activity['time'] }}
                                        </div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>
          </section>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               /*
               |--------------------------------------------------------------------------
               | FEATHER ICONS
               |--------------------------------------------------------------------------
               */

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }

               /*
               |--------------------------------------------------------------------------
               | FILTER JADWAL
               |--------------------------------------------------------------------------
               */

               const filterButtons = document.querySelectorAll(
                    '[data-operation-filter]'
               );

               const scheduleRows = document.querySelectorAll(
                    '[data-operation-row]'
               );

               const scheduleSearch = document.getElementById(
                    'operationsScheduleSearch'
               );

               const scheduleEmpty = document.getElementById(
                    'operationsScheduleEmpty'
               );

               let activeStatus = 'semua';

               function filterSchedules() {
                    const keyword = scheduleSearch ?
                         scheduleSearch.value.trim().toLowerCase() :
                         '';

                    let visibleRows = 0;

                    scheduleRows.forEach(function(row) {
                         const rowStatus = row.dataset.operationStatus || '';
                         const rowKeyword = row.dataset.operationKeyword || '';

                         const statusMatches =
                              activeStatus === 'semua' ||
                              rowStatus === activeStatus;

                         const keywordMatches =
                              keyword === '' ||
                              rowKeyword.includes(keyword);

                         const shouldShow = statusMatches && keywordMatches;

                         row.style.display = shouldShow ? '' : 'none';

                         if (shouldShow) {
                              visibleRows++;
                         }
                    });

                    if (scheduleEmpty) {
                         scheduleEmpty.style.display =
                              visibleRows === 0 ? 'block' : 'none';
                    }
               }

               filterButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                         filterButtons.forEach(function(item) {
                              item.classList.remove('active');
                         });

                         button.classList.add('active');

                         activeStatus =
                              button.dataset.operationFilter || 'semua';

                         filterSchedules();
                    });
               });

               if (scheduleSearch) {
                    scheduleSearch.addEventListener(
                         'input',
                         filterSchedules
                    );
               }

               /*
               |--------------------------------------------------------------------------
               | TANDAI AKTIVITAS DIBACA
               |--------------------------------------------------------------------------
               */

               const markReadButton = document.getElementById(
                    'markOperationsRead'
               );

               const activityItems = document.querySelectorAll(
                    '[data-operation-activity]'
               );

               if (markReadButton) {
                    markReadButton.addEventListener('click', function() {
                         activityItems.forEach(function(activity) {
                              activity.style.opacity = '0.58';
                         });

                         markReadButton.textContent = 'Sudah dibaca';
                         markReadButton.disabled = true;
                    });
               }
          });
     </script>

@endsection
