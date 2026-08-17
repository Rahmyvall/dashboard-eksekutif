@extends('layouts.app')

@section('title', 'Dashboard Admin Operasional')

@section('content')
     @php
          /*
        |--------------------------------------------------------------------------
        | DATA STATISTIK
        |--------------------------------------------------------------------------
        | Data ini dapat dipindahkan ke Controller setelah terhubung database.
        */

          $statistics = $statistics ?? [
              [
                  'label' => 'Pesanan Aktif',
                  'value' => 42,
                  'suffix' => '',
                  'icon' => 'activity',
                  'description' => 'Masuk dalam Proses Layanan',
                  'trend' => '+8,4%',
                  'trend_type' => 'up',
                  'theme' => 'orange',
              ],
              [
                  'label' => 'Jadwal Kerja',
                  'value' => 31,
                  'suffix' => '',
                  'icon' => 'check-circle',
                  'description' => 'Telah terjadwal hari ini',
                  'trend' => '+12,6%',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Kehadiran',
                  'value' => 6,
                  'suffix' => '',
                  'icon' => 'clock',
                  'description' => 'Perlu perhatian SDM Operasional',
                  'trend' => '+2',
                  'trend_type' => 'down',
                  'theme' => 'red',
              ],
              [
                  'label' => 'Layanan Selesai',
                  'value' => 87,
                  'suffix' => '%',
                  'icon' => 'cpu',
                  'description' => 'Kinerja operasional masih sehat',
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

          $weeklyPerformance = $weeklyPerformance ?? [
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

          $operationalPriorities = $operationalPriorities ?? [
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
                  'description' => 'Satu tim lapangan memiliki tingkat beban kerja di atas batas operasional.',
                  'icon' => 'users',
                  'status' => 'Perhatian',
                  'status_class' => 'warning',
                  'action' => 'Atur tim',
              ],
              [
                  'title' => 'Inspeksi peralatan',
                  'description' => 'Dua perangkat operasional harus diperiksa sebelum digunakan kembali.',
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

          $operationalSchedules = $operationalSchedules ?? [
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
                  'task' => 'Instalasi perangkat operasional',
                  'category' => 'Instalasi',
                  'team' => 'Tim Teknik 3',
                  'leader' => 'Dimas Pratama',
                  'time' => '13.00 – 15.00',
                  'location' => 'Area Operasional C',
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

          $teamWorkloads = $teamWorkloads ?? [
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

          $operationalActivities = $operationalActivities ?? [
              [
                  'title' => 'Pekerjaan berhasil diselesaikan',
                  'description' => 'Inspeksi lokasi Gedung A selesai tanpa kendala operasional.',
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
                  'description' => 'Pengiriman material terkendala karena kendaraan operasional belum tersedia.',
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
                  'description' => 'Tim Teknik 3 ditugaskan menangani instalasi perangkat operasional.',
                  'time' => '2 jam lalu',
                  'icon' => 'users',
                  'theme' => 'purple',
              ],
          ];

          $currentUserName = $currentUserName ?? (auth()->user()->name ?? 'Admin Operasional');
     @endphp

     <style>
          /*
                  |--------------------------------------------------------------------------
                  | DASHBOARD VARIABLES
                  |--------------------------------------------------------------------------
                  */

          .operational-dashboard {
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

          html[data-theme="dark"] .operational-dashboard,
          body.dark-theme .operational-dashboard,
          body.dark-mode .operational-dashboard {
               --op-heading: #f8fafc;
               --op-text: #cbd5e1;
               --op-muted: #94a3b8;
               --op-border: rgba(148, 163, 184, 0.16);
               --op-background: #0f172a;
               --op-card: #182235;
               --op-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
               --op-shadow-hover: 0 18px 40px rgba(0, 0, 0, 0.28);
          }

          .operational-dashboard *,
          .operational-dashboard *::before,
          .operational-dashboard *::after {
               box-sizing: border-box;
          }

          /*
                  |--------------------------------------------------------------------------
                  | HEADER
                  |--------------------------------------------------------------------------
                  */

          .operations-hero {
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

          .operations-hero::before {
               position: absolute;
               top: -120px;
               right: -80px;
               width: 360px;
               height: 360px;
               border: 70px solid rgba(255, 255, 255, 0.07);
               border-radius: 50%;
               content: "";
          }

          .operations-hero::after {
               position: absolute;
               right: 210px;
               bottom: -130px;
               width: 270px;
               height: 270px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.06);
               content: "";
          }

          .operations-hero-content {
               position: relative;
               z-index: 2;
               max-width: 720px;
          }

          .operations-role-badge {
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

          .operations-role-badge .badge-indicator {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #86efac;
               box-shadow: 0 0 0 5px rgba(134, 239, 172, 0.18);
          }

          .operations-hero h1 {
               margin: 0 0 12px;
               color: #ffffff;
               font-size: clamp(28px, 4vw, 42px);
               font-weight: 800;
               letter-spacing: -0.035em;
               line-height: 1.12;
          }

          .operations-hero-description {
               max-width: 650px;
               margin: 0;
               color: rgba(255, 255, 255, 0.83);
               font-size: 15px;
               line-height: 1.75;
          }

          .operations-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 18px;
               margin-top: 20px;
          }

          .operations-hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.86);
               font-size: 12px;
               font-weight: 600;
          }

          .operations-hero-meta-item svg {
               width: 15px;
               height: 15px;
          }

          .operations-hero-actions {
               position: relative;
               z-index: 2;
               display: flex;
               flex-shrink: 0;
               flex-direction: column;
               gap: 11px;
               width: 205px;
          }

          .operations-button {
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

          .operations-button:hover {
               transform: translateY(-2px);
          }

          .operations-button svg {
               width: 16px;
               height: 16px;
          }

          .operations-button-primary {
               background: #ffffff;
               color: var(--op-primary-dark);
               box-shadow: 0 10px 22px rgba(124, 45, 18, 0.2);
          }

          .operations-button-secondary {
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

          .operations-stat-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 18px;
               margin-bottom: 24px;
          }

          .operations-main-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.7fr) minmax(320px, 0.8fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .operations-secondary-grid {
               display: grid;
               grid-template-columns: minmax(320px, 0.78fr) minmax(0, 1.45fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .operations-bottom-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.7fr);
               gap: 22px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | STATISTIC CARDS
                  |--------------------------------------------------------------------------
                  */

          .operations-stat-card {
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

          .operations-stat-card:hover {
               transform: translateY(-4px);
               border-color: rgba(234, 88, 12, 0.25);
               box-shadow: var(--op-shadow-hover);
          }

          .operations-stat-card::after {
               position: absolute;
               top: -28px;
               right: -28px;
               width: 95px;
               height: 95px;
               border-radius: 50%;
               content: "";
               opacity: 0.7;
          }

          .operations-stat-card.theme-orange::after {
               background: rgba(234, 88, 12, 0.08);
          }

          .operations-stat-card.theme-green::after {
               background: rgba(22, 163, 74, 0.08);
          }

          .operations-stat-card.theme-red::after {
               background: rgba(220, 38, 38, 0.08);
          }

          .operations-stat-card.theme-blue::after {
               background: rgba(37, 99, 235, 0.08);
          }

          .operations-stat-top {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 16px;
          }

          .operations-stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
          }

          .operations-stat-icon svg {
               width: 21px;
               height: 21px;
          }

          .theme-orange .operations-stat-icon {
               background: rgba(234, 88, 12, 0.12);
               color: #ea580c;
          }

          .theme-green .operations-stat-icon {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .theme-red .operations-stat-icon {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .theme-blue .operations-stat-icon {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .operations-stat-trend {
               display: inline-flex;
               align-items: center;
               gap: 4px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 11px;
               font-weight: 800;
          }

          .operations-stat-trend svg {
               width: 12px;
               height: 12px;
          }

          .operations-stat-trend.up {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .operations-stat-trend.down {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .operations-stat-label {
               margin: 18px 0 7px;
               color: var(--op-muted);
               font-size: 12px;
               font-weight: 700;
          }

          .operations-stat-value {
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

          .operations-stat-description {
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

          .operations-card {
               border: 1px solid var(--op-border);
               border-radius: 20px;
               background: var(--op-card);
               box-shadow: var(--op-shadow);
          }

          .operations-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               padding: 22px 23px 17px;
               border-bottom: 1px solid var(--op-border);
          }

          .operations-card-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .operations-card-heading-icon {
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

          .operations-card-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .operations-card-title {
               margin: 0;
               color: var(--op-heading);
               font-size: 16px;
               font-weight: 800;
               letter-spacing: -0.015em;
          }

          .operations-card-subtitle {
               margin: 5px 0 0;
               color: var(--op-muted);
               font-size: 11px;
               line-height: 1.55;
          }

          .operations-card-action {
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

          .operations-card-action svg {
               width: 14px;
               height: 14px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | CHART
                  |--------------------------------------------------------------------------
                  */

          .operations-chart-body {
               padding: 22px 23px 24px;
          }

          .operations-chart-summary {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 22px;
          }

          .operations-chart-legends {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
          }

          .operations-chart-legend {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               color: var(--op-text);
               font-size: 11px;
               font-weight: 700;
          }

          .operations-chart-legend-dot {
               width: 9px;
               height: 9px;
               border-radius: 3px;
          }

          .operations-chart-legend-dot.scheduled {
               background: rgba(234, 88, 12, 0.26);
          }

          .operations-chart-legend-dot.completed {
               background: var(--op-primary);
          }

          .operations-chart-rate {
               text-align: right;
          }

          .operations-chart-rate strong {
               display: block;
               color: var(--op-heading);
               font-size: 19px;
               font-weight: 800;
          }

          .operations-chart-rate span {
               color: var(--op-muted);
               font-size: 10px;
          }

          .operations-chart-area {
               position: relative;
               display: grid;
               grid-template-columns: 34px minmax(0, 1fr);
               gap: 11px;
               height: 285px;
          }

          .operations-chart-y-axis {
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               height: 245px;
               padding-top: 2px;
               color: var(--op-muted);
               font-size: 9px;
               text-align: right;
          }

          .operations-chart-content {
               position: relative;
               height: 285px;
          }

          .operations-chart-lines {
               position: absolute;
               inset: 0 0 40px;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               pointer-events: none;
          }

          .operations-chart-line {
               width: 100%;
               border-top: 1px dashed var(--op-border);
          }

          .operations-chart-columns {
               position: absolute;
               inset: 0 0 0;
               display: grid;
               grid-template-columns: repeat(7, minmax(30px, 1fr));
               gap: 12px;
          }

          .operations-chart-column {
               display: flex;
               flex-direction: column;
               align-items: center;
               min-width: 0;
          }

          .operations-chart-bars {
               display: flex;
               align-items: flex-end;
               justify-content: center;
               gap: 5px;
               width: 100%;
               height: 245px;
          }

          .operations-chart-bar {
               position: relative;
               width: min(16px, 38%);
               min-height: 4px;
               border-radius: 6px 6px 3px 3px;
               cursor: pointer;
               transition:
                    filter 0.2s ease,
                    transform 0.2s ease;
          }

          .operations-chart-bar:hover {
               z-index: 3;
               filter: brightness(0.93);
               transform: scaleX(1.08);
          }

          .operations-chart-bar.scheduled {
               background: rgba(234, 88, 12, 0.26);
          }

          .operations-chart-bar.completed {
               background: linear-gradient(to top, #c2410c, #fb923c);
               box-shadow: 0 5px 12px rgba(234, 88, 12, 0.2);
          }

          .operations-chart-tooltip {
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

          .operations-chart-bar:hover .operations-chart-tooltip {
               opacity: 1;
               transform: translateX(-50%) translateY(0);
          }

          .operations-chart-day {
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

          .operations-health-body {
               padding: 24px;
          }

          .operations-utilization {
               display: flex;
               align-items: center;
               gap: 22px;
               margin-bottom: 25px;
               padding-bottom: 24px;
               border-bottom: 1px solid var(--op-border);
          }

          .operations-utilization-ring {
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

          .operations-utilization-ring::before {
               position: absolute;
               width: 88px;
               height: 88px;
               border-radius: 50%;
               background: var(--op-card);
               content: "";
          }

          .operations-utilization-value {
               position: relative;
               z-index: 2;
               color: var(--op-heading);
               font-size: 24px;
               font-weight: 800;
          }

          .operations-utilization-details h3 {
               margin: 0 0 6px;
               color: var(--op-heading);
               font-size: 14px;
               font-weight: 800;
          }

          .operations-utilization-details p {
               margin: 0 0 11px;
               color: var(--op-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .operations-utilization-status {
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

          .operations-utilization-status::before {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: #22c55e;
               content: "";
          }

          .operations-health-list {
               display: flex;
               flex-direction: column;
               gap: 17px;
          }

          .operations-health-item-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 8px;
          }

          .operations-health-item-label {
               color: var(--op-text);
               font-size: 11px;
               font-weight: 700;
          }

          .operations-health-item-value {
               color: var(--op-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .operations-progress {
               overflow: hidden;
               width: 100%;
               height: 7px;
               border-radius: 999px;
               background: var(--op-border);
          }

          .operations-progress-bar {
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, var(--op-primary-dark), #fb923c);
          }

          .operations-progress-bar.success {
               background: linear-gradient(90deg, #15803d, #4ade80);
          }

          .operations-progress-bar.info {
               background: linear-gradient(90deg, #1d4ed8, #60a5fa);
          }

          .operations-progress-bar.warning {
               background: linear-gradient(90deg, #b45309, #fbbf24);
          }

          /*
                  |--------------------------------------------------------------------------
                  | PRIORITY
                  |--------------------------------------------------------------------------
                  */

          .operations-priority-list {
               display: flex;
               flex-direction: column;
          }

          .operations-priority-item {
               display: flex;
               gap: 13px;
               padding: 17px 21px;
               border-bottom: 1px solid var(--op-border);
               transition: background 0.2s ease;
          }

          .operations-priority-item:last-child {
               border-bottom: 0;
          }

          .operations-priority-item:hover {
               background: rgba(234, 88, 12, 0.035);
          }

          .operations-priority-icon {
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

          .operations-priority-icon svg {
               width: 17px;
               height: 17px;
          }

          .operations-priority-content {
               min-width: 0;
               flex: 1;
          }

          .operations-priority-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 10px;
          }

          .operations-priority-title {
               margin: 1px 0 0;
               color: var(--op-heading);
               font-size: 12px;
               font-weight: 800;
          }

          .operations-priority-description {
               margin: 6px 0 11px;
               color: var(--op-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .operations-priority-action {
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

          .operations-priority-action svg {
               width: 12px;
               height: 12px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | BADGES
                  |--------------------------------------------------------------------------
                  */

          .operations-badge {
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

          .operations-badge::before {
               width: 5px;
               height: 5px;
               border-radius: 50%;
               background: currentColor;
               content: "";
          }

          .operations-badge.success {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .operations-badge.info {
               background: rgba(37, 99, 235, 0.1);
               color: #2563eb;
          }

          .operations-badge.warning {
               background: rgba(217, 119, 6, 0.1);
               color: #b45309;
          }

          .operations-badge.danger {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .operations-badge.neutral {
               background: rgba(100, 116, 139, 0.12);
               color: #64748b;
          }

          /*
                  |--------------------------------------------------------------------------
                  | TABLE
                  |--------------------------------------------------------------------------
                  */

          .operations-schedule-card {
               overflow: hidden;
          }

          .operations-schedule-toolbar {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 14px 20px;
               border-bottom: 1px solid var(--op-border);
          }

          .operations-filter-list {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .operations-filter-button {
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

          .operations-filter-button:hover,
          .operations-filter-button.active {
               border-color: var(--op-primary);
               background: var(--op-primary);
               color: #ffffff;
          }

          .operations-search {
               position: relative;
               width: 195px;
          }

          .operations-search svg {
               position: absolute;
               top: 50%;
               left: 11px;
               width: 14px;
               height: 14px;
               color: var(--op-muted);
               transform: translateY(-50%);
          }

          .operations-search input {
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

          .operations-search input:focus {
               border-color: var(--op-primary);
               box-shadow: 0 0 0 3px var(--op-primary-soft);
          }

          .operations-table-wrapper {
               overflow-x: auto;
          }

          .operations-table {
               width: 100%;
               min-width: 840px;
               border-collapse: collapse;
          }

          .operations-table th {
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

          .operations-table td {
               padding: 15px 16px;
               border-bottom: 1px solid var(--op-border);
               color: var(--op-text);
               font-size: 10px;
               vertical-align: middle;
          }

          .operations-table tbody tr {
               transition: background 0.2s ease;
          }

          .operations-table tbody tr:hover {
               background: rgba(234, 88, 12, 0.028);
          }

          .operations-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .operations-task {
               display: flex;
               align-items: center;
               gap: 11px;
               min-width: 215px;
          }

          .operations-task-icon {
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

          .operations-task-icon svg {
               width: 15px;
               height: 15px;
          }

          .operations-task-title {
               display: block;
               margin-bottom: 3px;
               color: var(--op-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .operations-task-code {
               color: var(--op-muted);
               font-size: 9px;
          }

          .operations-team-name {
               display: block;
               color: var(--op-heading);
               font-weight: 700;
          }

          .operations-team-leader {
               display: block;
               margin-top: 3px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .operations-time {
               color: var(--op-heading);
               font-weight: 700;
          }

          .operations-location {
               display: block;
               margin-top: 3px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .operations-table-progress {
               min-width: 105px;
          }

          .operations-table-progress-top {
               display: flex;
               justify-content: space-between;
               margin-bottom: 6px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .operations-table-progress .operations-progress {
               height: 6px;
          }

          .operations-action-menu {
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

          .operations-action-menu svg {
               width: 15px;
               height: 15px;
          }

          /*
                  |--------------------------------------------------------------------------
                  | TEAM WORKLOAD
                  |--------------------------------------------------------------------------
                  */

          .operations-team-list {
               padding: 4px 21px 9px;
          }

          .operations-team-item {
               padding: 17px 0;
               border-bottom: 1px solid var(--op-border);
          }

          .operations-team-item:last-child {
               border-bottom: 0;
          }

          .operations-team-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 10px;
          }

          .operations-team-header h4 {
               margin: 0;
               color: var(--op-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .operations-team-metadata {
               display: flex;
               gap: 14px;
               margin-top: 4px;
               color: var(--op-muted);
               font-size: 9px;
          }

          .operations-team-percentage {
               color: var(--op-heading);
               font-size: 12px;
               font-weight: 800;
          }

          /*
                  |--------------------------------------------------------------------------
                  | ACTIVITY
                  |--------------------------------------------------------------------------
                  */

          .operations-activity-list {
               padding: 4px 21px 10px;
          }

          .operations-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 17px 0;
          }

          .operations-activity-item:not(:last-child)::before {
               position: absolute;
               top: 52px;
               bottom: -3px;
               left: 18px;
               width: 1px;
               background: var(--op-border);
               content: "";
          }

          .operations-activity-icon {
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

          .operations-activity-icon svg {
               width: 14px;
               height: 14px;
          }

          .operations-activity-icon.green {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .operations-activity-icon.blue {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .operations-activity-icon.red {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .operations-activity-icon.orange {
               background: rgba(234, 88, 12, 0.12);
               color: #ea580c;
          }

          .operations-activity-icon.purple {
               background: rgba(124, 58, 237, 0.12);
               color: #7c3aed;
          }

          .operations-activity-content {
               min-width: 0;
               flex: 1;
          }

          .operations-activity-content h4 {
               margin: 1px 0 5px;
               color: var(--op-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .operations-activity-content p {
               margin: 0;
               color: var(--op-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .operations-activity-time {
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

          .operations-empty-state {
               display: none;
               padding: 38px 20px;
               text-align: center;
          }

          .operations-empty-state svg {
               width: 34px;
               height: 34px;
               margin-bottom: 10px;
               color: var(--op-muted);
          }

          .operations-empty-state h4 {
               margin: 0 0 5px;
               color: var(--op-heading);
               font-size: 13px;
          }

          .operations-empty-state p {
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
               .operations-stat-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .operations-main-grid,
               .operations-secondary-grid,
               .operations-bottom-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 768px) {
               .operational-dashboard {
                    padding: 15px;
               }

               .operations-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    min-height: auto;
                    padding: 26px 23px;
                    border-radius: 19px;
               }

               .operations-hero-actions {
                    flex-direction: row;
                    width: 100%;
               }

               .operations-hero-actions .operations-button {
                    flex: 1;
               }

               .operations-stat-grid {
                    grid-template-columns: 1fr;
                    gap: 13px;
               }

               .operations-stat-card {
                    min-height: auto;
               }

               .operations-card-header,
               .operations-schedule-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .operations-search {
                    width: 100%;
               }

               .operations-chart-area {
                    grid-template-columns: 26px minmax(0, 1fr);
               }

               .operations-chart-columns {
                    gap: 5px;
               }

               .operations-chart-bars {
                    gap: 3px;
               }

               .operations-utilization {
                    align-items: flex-start;
               }
          }

          @media (max-width: 520px) {
               .operations-hero-actions {
                    flex-direction: column;
               }

               .operations-chart-summary {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .operations-chart-rate {
                    text-align: left;
               }

               .operations-chart-column:nth-child(even) .operations-chart-day {
                    opacity: 0.55;
               }

               .operations-utilization {
                    align-items: center;
                    flex-direction: column;
                    text-align: center;
               }
          }
     </style>

     <div class="operational-dashboard">

          {{-- ================================================================
             HEADER
        ================================================================= --}}

          <section class="operations-hero">
               <div class="operations-hero-content">
                    <div class="operations-role-badge">
                         <span class="badge-indicator"></span>
                         Admin Operasional
                    </div>

                    <h1>Dashboard Admin Operasional</h1>

                    <p class="operations-hero-description">
                         Selamat datang, {{ $currentUserName }}. Pantau layanan aktif,
                         jadwal kerja, kebutuhan SDM, dan perkembangan operasional dari
                         modul yang tersedia di sidebar menu.
                    </p>

                    <div class="operations-hero-meta">
                         <span class="operations-hero-meta-item">
                              <i data-feather="calendar"></i>
                              {{ now()->translatedFormat('l, d F Y') }}
                         </span>

                         <span class="operations-hero-meta-item">
                              <i data-feather="clock"></i>
                              Shift operasional aktif
                         </span>

                         <span class="operations-hero-meta-item">
                              <i data-feather="wifi"></i>
                              Sistem terhubung
                         </span>
                    </div>
               </div>

               <div class="operations-hero-actions">
                    <button type="button" class="operations-button operations-button-primary">
                         <i data-feather="plus-circle"></i>
                         Tambah Pekerjaan
                    </button>

                    <button type="button" class="operations-button operations-button-secondary">
                         <i data-feather="download"></i>
                         Unduh Laporan
                    </button>
               </div>
          </section>

          {{-- ================================================================
             STATISTICS
        ================================================================= --}}

          <section class="operations-stat-grid">
               @foreach ($statistics as $statistic)
                    <article class="operations-stat-card theme-{{ $statistic['theme'] }}">
                         <div class="operations-stat-top">
                              <span class="operations-stat-icon">
                                   <i data-feather="{{ $statistic['icon'] }}"></i>
                              </span>

                              <span class="operations-stat-trend {{ $statistic['trend_type'] }}">
                                   <i
                                        data-feather="{{ $statistic['trend_type'] === 'up' ? 'trending-up' : 'alert-circle' }}"></i>

                                   {{ $statistic['trend'] }}
                              </span>
                         </div>

                         <p class="operations-stat-label">
                              {{ $statistic['label'] }}
                         </p>

                         <h2 class="operations-stat-value">
                              <span>{{ $statistic['value'] }}</span>
                              <span>{{ $statistic['suffix'] }}</span>
                         </h2>

                         <p class="operations-stat-description">
                              {{ $statistic['description'] }}
                         </p>
                    </article>
               @endforeach
          </section>

          {{-- ================================================================
             CHART DAN KONDISI OPERASIONAL
        ================================================================= --}}

          <section class="operations-main-grid">

               {{-- Kinerja mingguan --}}
               <article class="operations-card">
                    <header class="operations-card-header">
                         <div class="operations-card-heading">
                              <span class="operations-card-heading-icon">
                                   <i data-feather="bar-chart-2"></i>
                              </span>

                              <div>
                                   <h2 class="operations-card-title">
                                        Ringkasan Proses Layanan
                                   </h2>

                                   <p class="operations-card-subtitle">
                                        Perbandingan tugas terjadwal dan tugas yang telah diselesaikan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="operations-card-action">
                              Minggu ini
                              <i data-feather="chevron-down"></i>
                         </button>
                    </header>

                    <div class="operations-chart-body">
                         <div class="operations-chart-summary">
                              <div class="operations-chart-legends">
                                   <span class="operations-chart-legend">
                                        <span class="operations-chart-legend-dot scheduled"></span>
                                        Dijadwalkan
                                   </span>

                                   <span class="operations-chart-legend">
                                        <span class="operations-chart-legend-dot completed"></span>
                                        Diselesaikan
                                   </span>
                              </div>

                              <div class="operations-chart-rate">
                                   <strong>84,6%</strong>
                                   <span>Rata-rata penyelesaian</span>
                              </div>
                         </div>

                         <div class="operations-chart-area">
                              <div class="operations-chart-y-axis">
                                   <span>100</span>
                                   <span>75</span>
                                   <span>50</span>
                                   <span>25</span>
                                   <span>0</span>
                              </div>

                              <div class="operations-chart-content">
                                   <div class="operations-chart-lines">
                                        <span class="operations-chart-line"></span>
                                        <span class="operations-chart-line"></span>
                                        <span class="operations-chart-line"></span>
                                        <span class="operations-chart-line"></span>
                                        <span class="operations-chart-line"></span>
                                   </div>

                                   <div class="operations-chart-columns">
                                        @foreach ($weeklyPerformance as $performance)
                                             <div class="operations-chart-column">
                                                  <div class="operations-chart-bars">
                                                       <div class="operations-chart-bar scheduled"
                                                            style="height: {{ $performance['scheduled'] }}%;">
                                                            <span class="operations-chart-tooltip">
                                                                 {{ $performance['scheduled'] }} dijadwalkan
                                                            </span>
                                                       </div>

                                                       <div class="operations-chart-bar completed"
                                                            style="height: {{ $performance['completed'] }}%;">
                                                            <span class="operations-chart-tooltip">
                                                                 {{ $performance['completed'] }} selesai
                                                            </span>
                                                       </div>
                                                  </div>

                                                  <span class="operations-chart-day" title="{{ $performance['full_day'] }}">
                                                       {{ $performance['day'] }}
                                                  </span>
                                             </div>
                                        @endforeach
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>

               {{-- Kondisi operasional --}}
               <article class="operations-card">
                    <header class="operations-card-header">
                         <div class="operations-card-heading">
                              <span class="operations-card-heading-icon">
                                   <i data-feather="pie-chart"></i>
                              </span>

                              <div>
                                   <h2 class="operations-card-title">
                                        Status Modul Operasional
                                   </h2>

                                   <p class="operations-card-subtitle">
                                        Kondisi ketersediaan modul dan sumber daya terkait menu sidebar.
                                   </p>
                              </div>
                         </div>
                    </header>

                    <div class="operations-health-body">
                         <div class="operations-utilization">
                              <div class="operations-utilization-ring">
                                   <span class="operations-utilization-value">87%</span>
                              </div>

                              <div class="operations-utilization-details">
                                   <h3>Utilisasi sumber daya</h3>

                                   <p>
                                        Penggunaan tenaga kerja dan perangkat operasional
                                        masih berada dalam batas yang ditetapkan.
                                   </p>

                                   <span class="operations-utilization-status">
                                        Kondisi aman
                                   </span>
                              </div>
                         </div>

                         <div class="operations-health-list">
                              <div>
                                   <div class="operations-health-item-top">
                                        <span class="operations-health-item-label">
                                             Penyelesaian pekerjaan
                                        </span>

                                        <span class="operations-health-item-value">
                                             84%
                                        </span>
                                   </div>

                                   <div class="operations-progress">
                                        <div class="operations-progress-bar success" style="width: 84%;"></div>
                                   </div>
                              </div>

                              <div>
                                   <div class="operations-health-item-top">
                                        <span class="operations-health-item-label">
                                             Ketersediaan personel
                                        </span>

                                        <span class="operations-health-item-value">
                                             91%
                                        </span>
                                   </div>

                                   <div class="operations-progress">
                                        <div class="operations-progress-bar info" style="width: 91%;"></div>
                                   </div>
                              </div>

                              <div>
                                   <div class="operations-health-item-top">
                                        <span class="operations-health-item-label">
                                             Kesiapan peralatan
                                        </span>

                                        <span class="operations-health-item-value">
                                             78%
                                        </span>
                                   </div>

                                   <div class="operations-progress">
                                        <div class="operations-progress-bar warning" style="width: 78%;"></div>
                                   </div>
                              </div>

                              <div>
                                   <div class="operations-health-item-top">
                                        <span class="operations-health-item-label">
                                             Kepatuhan jadwal
                                        </span>

                                        <span class="operations-health-item-value">
                                             86%
                                        </span>
                                   </div>

                                   <div class="operations-progress">
                                        <div class="operations-progress-bar" style="width: 86%;"></div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
             PRIORITAS DAN JADWAL
        ================================================================= --}}

          <section class="operations-secondary-grid">

               {{-- Prioritas --}}
               <article class="operations-card">
                    <header class="operations-card-header">
                         <div class="operations-card-heading">
                              <span class="operations-card-heading-icon">
                                   <i data-feather="alert-octagon"></i>
                              </span>

                              <div>
                                   <h2 class="operations-card-title">
                                        Prioritas Tindakan
                                   </h2>

                                   <p class="operations-card-subtitle">
                                        Item yang perlu mendapat perhatian segera dari admin operasional.
                                   </p>
                              </div>
                         </div>

                         <span class="operations-badge danger">
                              4 prioritas
                         </span>
                    </header>

                    <div class="operations-priority-list">
                         @foreach ($operationalPriorities as $priority)
                              <div class="operations-priority-item">
                                   <span class="operations-priority-icon">
                                        <i data-feather="{{ $priority['icon'] }}"></i>
                                   </span>

                                   <div class="operations-priority-content">
                                        <div class="operations-priority-heading">
                                             <h3 class="operations-priority-title">
                                                  {{ $priority['title'] }}
                                             </h3>

                                             <span class="operations-badge {{ $priority['status_class'] }}">
                                                  {{ $priority['status'] }}
                                             </span>
                                        </div>

                                        <p class="operations-priority-description">
                                             {{ $priority['description'] }}
                                        </p>

                                        <button type="button" class="operations-priority-action">
                                             {{ $priority['action'] }}
                                             <i data-feather="arrow-right"></i>
                                        </button>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Jadwal --}}
               <article class="operations-card operations-schedule-card">
                    <header class="operations-card-header">
                         <div class="operations-card-heading">
                              <span class="operations-card-heading-icon">
                                   <i data-feather="clipboard"></i>
                              </span>

                              <div>
                                   <h2 class="operations-card-title">
                                        Jadwal Kerja &amp; Penugasan
                                   </h2>

                                   <p class="operations-card-subtitle">
                                        Monitoring pekerjaan, tim, waktu, dan progres dari menu Proses Layanan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="operations-card-action">
                              <i data-feather="calendar"></i>
                              Lihat kalender
                         </button>
                    </header>

                    <div class="operations-schedule-toolbar">
                         <div class="operations-filter-list">
                              <button type="button" class="operations-filter-button active"
                                   data-operation-filter="semua">
                                   Semua
                              </button>

                              <button type="button" class="operations-filter-button" data-operation-filter="berjalan">
                                   Berjalan
                              </button>

                              <button type="button" class="operations-filter-button" data-operation-filter="terjadwal">
                                   Terjadwal
                              </button>

                              <button type="button" class="operations-filter-button" data-operation-filter="tertunda">
                                   Tertunda
                              </button>

                              <button type="button" class="operations-filter-button" data-operation-filter="selesai">
                                   Selesai
                              </button>
                         </div>

                         <label class="operations-search">
                              <i data-feather="search"></i>

                              <input type="search" id="operationsScheduleSearch" placeholder="Cari pekerjaan..."
                                   autocomplete="off">
                         </label>
                    </div>

                    <div class="operations-table-wrapper">
                         <table class="operations-table">
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
                                                  <div class="operations-task">
                                                       <span class="operations-task-icon">
                                                            <i data-feather="{{ $scheduleIcon }}"></i>
                                                       </span>

                                                       <span>
                                                            <strong class="operations-task-title">
                                                                 {{ $schedule['task'] }}
                                                            </strong>

                                                            <span class="operations-task-code">
                                                                 {{ $schedule['code'] }}
                                                                 ·
                                                                 {{ $schedule['category'] }}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="operations-team-name">
                                                       {{ $schedule['team'] }}
                                                  </span>

                                                  <span class="operations-team-leader">
                                                       PIC: {{ $schedule['leader'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="operations-time">
                                                       {{ $schedule['time'] }}
                                                  </span>

                                                  <span class="operations-location">
                                                       {{ $schedule['location'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="operations-table-progress">
                                                       <div class="operations-table-progress-top">
                                                            <span>Progres</span>
                                                            <strong>
                                                                 {{ $schedule['progress'] }}%
                                                            </strong>
                                                       </div>

                                                       <div class="operations-progress">
                                                            <div class="operations-progress-bar
                                                        {{ $schedule['progress'] === 100 ? 'success' : '' }}"
                                                                 style="width: {{ $schedule['progress'] }}%;"></div>
                                                       </div>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="operations-badge {{ $scheduleStatusClass }}">
                                                       {{ $schedule['status'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <button type="button" class="operations-action-menu"
                                                       aria-label="Pilihan pekerjaan {{ $schedule['task'] }}">
                                                       <i data-feather="more-horizontal"></i>
                                                  </button>
                                             </td>
                                        </tr>
                                   @endforeach
                              </tbody>
                         </table>

                         <div class="operations-empty-state" id="operationsScheduleEmpty">
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

          <section class="operations-bottom-grid">

               {{-- Beban kerja tim --}}
               <article class="operations-card">
                    <header class="operations-card-header">
                         <div class="operations-card-heading">
                              <span class="operations-card-heading-icon">
                                   <i data-feather="users"></i>
                              </span>

                              <div>
                                   <h2 class="operations-card-title">
                                        Beban Kerja Tim
                                   </h2>

                                   <p class="operations-card-subtitle">
                                        Kapasitas personel sesuai jadwal kerja dan penugasan aktif.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="operations-card-action">
                              Kelola tim
                              <i data-feather="arrow-up-right"></i>
                         </button>
                    </header>

                    <div class="operations-team-list">
                         @foreach ($teamWorkloads as $team)
                              @php
                                   $loadClass = match (true) {
                                       $team['load'] >= 90 => 'warning',
                                       $team['load'] >= 75 => '',
                                       default => 'success',
                                   };
                              @endphp

                              <div class="operations-team-item">
                                   <div class="operations-team-header">
                                        <div>
                                             <h4>{{ $team['name'] }}</h4>

                                             <div class="operations-team-metadata">
                                                  <span>
                                                       {{ $team['members'] }} personel
                                                  </span>

                                                  <span>
                                                       {{ $team['active_jobs'] }} pekerjaan aktif
                                                  </span>
                                             </div>
                                        </div>

                                        <span class="operations-team-percentage">
                                             {{ $team['load'] }}%
                                        </span>
                                   </div>

                                   <div class="operations-progress">
                                        <div class="operations-progress-bar {{ $loadClass }}"
                                             style="width: {{ $team['load'] }}%;"></div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Aktivitas --}}
               <article class="operations-card">
                    <header class="operations-card-header">
                         <div class="operations-card-heading">
                              <span class="operations-card-heading-icon">
                                   <i data-feather="bell"></i>
                              </span>

                              <div>
                                   <h2 class="operations-card-title">
                                        Aktivitas Terbaru
                                   </h2>

                                   <p class="operations-card-subtitle">
                                        Pembaruan terkini dari modul operasional dan SDM.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="operations-card-action" id="markOperationsRead">
                              Tandai dibaca
                         </button>
                    </header>

                    <div class="operations-activity-list" id="operationsActivityList">
                         @foreach ($operationalActivities as $activity)
                              <div class="operations-activity-item" data-operation-activity>
                                   <span class="operations-activity-icon {{ $activity['theme'] }}">
                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                   </span>

                                   <div class="operations-activity-content">
                                        <h4>{{ $activity['title'] }}</h4>

                                        <p>{{ $activity['description'] }}</p>

                                        <div class="operations-activity-time">
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
