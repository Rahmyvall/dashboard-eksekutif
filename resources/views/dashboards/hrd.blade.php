@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('content')
     @php
          /*
        |--------------------------------------------------------------------------
        | DATA DASHBOARD HRD
        |--------------------------------------------------------------------------
        | Data contoh berikut dapat dipindahkan ke controller ketika modul HRD
        | sudah terhubung dengan database.
        */

          $statistics = [
              [
                  'label' => 'Total Karyawan',
                  'value' => 248,
                  'suffix' => '',
                  'icon' => 'users',
                  'description' => '236 aktif · 12 dalam masa percobaan',
                  'trend' => '+6',
                  'trend_type' => 'up',
                  'theme' => 'blue',
              ],
              [
                  'label' => 'Kehadiran Hari Ini',
                  'value' => 91,
                  'suffix' => '%',
                  'icon' => 'user-check',
                  'description' => '226 dari 248 karyawan tercatat hadir',
                  'trend' => '+2,3%',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Pengajuan Cuti',
                  'value' => 12,
                  'suffix' => '',
                  'icon' => 'calendar',
                  'description' => '5 pengajuan memerlukan persetujuan',
                  'trend' => '+3',
                  'trend_type' => 'down',
                  'theme' => 'orange',
              ],
              [
                  'label' => 'Posisi Lowong',
                  'value' => 7,
                  'suffix' => '',
                  'icon' => 'briefcase',
                  'description' => '3 posisi masuk kategori prioritas',
                  'trend' => '+2',
                  'trend_type' => 'up',
                  'theme' => 'red',
              ],
          ];

          $weeklyAttendance = [
              ['day' => 'Sen', 'full_day' => 'Senin', 'target' => 96, 'present' => 91],
              ['day' => 'Sel', 'full_day' => 'Selasa', 'target' => 96, 'present' => 93],
              ['day' => 'Rab', 'full_day' => 'Rabu', 'target' => 97, 'present' => 92],
              ['day' => 'Kam', 'full_day' => 'Kamis', 'target' => 96, 'present' => 90],
              ['day' => 'Jum', 'full_day' => 'Jumat', 'target' => 95, 'present' => 89],
              ['day' => 'Sab', 'full_day' => 'Sabtu', 'target' => 88, 'present' => 82],
              ['day' => 'Min', 'full_day' => 'Minggu', 'target' => 60, 'present' => 56],
          ];

          $attendanceTargetTotal = array_sum(array_column($weeklyAttendance, 'target'));
          $attendancePresentTotal = array_sum(array_column($weeklyAttendance, 'present'));
          $attendanceRate =
              $attendanceTargetTotal > 0 ? round(($attendancePresentTotal / $attendanceTargetTotal) * 100, 1) : 0;

          $hrPriorities = [
              [
                  'title' => 'Kontrak segera berakhir',
                  'description' =>
                      'Delapan kontrak karyawan akan berakhir dalam 30 hari dan perlu keputusan perpanjangan.',
                  'icon' => 'file-text',
                  'status' => 'Mendesak',
                  'status_class' => 'danger',
                  'action' => 'Tinjau kontrak',
              ],
              [
                  'title' => 'Persetujuan cuti tertunda',
                  'description' => 'Lima pengajuan cuti belum memperoleh keputusan dari atasan terkait.',
                  'icon' => 'calendar',
                  'status' => 'Perhatian',
                  'status_class' => 'warning',
                  'action' => 'Proses pengajuan',
              ],
              [
                  'title' => 'Rekrutmen posisi prioritas',
                  'description' =>
                      'Tiga posisi operasional membutuhkan kandidat agar target kebutuhan tenaga kerja terpenuhi.',
                  'icon' => 'user-plus',
                  'status' => 'Berjalan',
                  'status_class' => 'info',
                  'action' => 'Lihat kandidat',
              ],
              [
                  'title' => 'Evaluasi kinerja belum lengkap',
                  'description' => 'Dua departemen belum menyelesaikan penilaian kinerja periode berjalan.',
                  'icon' => 'clipboard',
                  'status' => 'Menunggu',
                  'status_class' => 'neutral',
                  'action' => 'Kirim pengingat',
              ],
          ];

          $hrSchedules = [
              [
                  'code' => 'HRD-2601',
                  'agenda' => 'Onboarding karyawan baru',
                  'category' => 'Onboarding',
                  'department' => 'Human Resources',
                  'pic' => 'Nadia Putri',
                  'time' => '08.00 – 09.30',
                  'location' => 'Ruang Training A',
                  'progress' => 100,
                  'status' => 'Selesai',
                  'priority' => 'Normal',
              ],
              [
                  'code' => 'HRD-2602',
                  'agenda' => 'Wawancara kandidat Finance',
                  'category' => 'Rekrutmen',
                  'department' => 'Finance',
                  'pic' => 'Rina Maharani',
                  'time' => '10.00 – 11.30',
                  'location' => 'Meeting Room 2',
                  'progress' => 60,
                  'status' => 'Berjalan',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'HRD-2603',
                  'agenda' => 'Validasi data payroll bulanan',
                  'category' => 'Payroll',
                  'department' => 'Human Resources',
                  'pic' => 'Dian Permata',
                  'time' => '11.00 – 13.00',
                  'location' => 'Ruang HRD',
                  'progress' => 45,
                  'status' => 'Berjalan',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'HRD-2604',
                  'agenda' => 'Evaluasi kinerja Departemen Operasional',
                  'category' => 'Evaluasi',
                  'department' => 'Operasional',
                  'pic' => 'Bagus Pratama',
                  'time' => '13.30 – 15.00',
                  'location' => 'Ruang Rapat Utama',
                  'progress' => 15,
                  'status' => 'Terjadwal',
                  'priority' => 'Normal',
              ],
              [
                  'code' => 'HRD-2605',
                  'agenda' => 'Pelatihan keselamatan kerja',
                  'category' => 'Pelatihan',
                  'department' => 'Produksi',
                  'pic' => 'Arif Setiawan',
                  'time' => '15.00 – 16.30',
                  'location' => 'Aula Perusahaan',
                  'progress' => 0,
                  'status' => 'Menunggu',
                  'priority' => 'Tinggi',
              ],
          ];

          $departmentCapacities = [
              [
                  'name' => 'Operasional',
                  'employees' => 76,
                  'present' => 70,
                  'capacity' => 92,
                  'status' => 'Tinggi',
              ],
              [
                  'name' => 'Produksi',
                  'employees' => 94,
                  'present' => 84,
                  'capacity' => 89,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Finance',
                  'employees' => 28,
                  'present' => 26,
                  'capacity' => 78,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Sales & Marketing',
                  'employees' => 34,
                  'present' => 31,
                  'capacity' => 84,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Human Resources',
                  'employees' => 16,
                  'present' => 15,
                  'capacity' => 73,
                  'status' => 'Normal',
              ],
          ];

          $hrActivities = [
              [
                  'title' => 'Karyawan baru berhasil ditambahkan',
                  'description' => 'Data dan akun kerja dua karyawan baru telah diaktifkan.',
                  'time' => '12 menit lalu',
                  'icon' => 'user-plus',
                  'theme' => 'green',
              ],
              [
                  'title' => 'Pengajuan cuti diperbarui',
                  'description' => 'Cuti tahunan milik Raka Pratama telah disetujui oleh atasan.',
                  'time' => '26 menit lalu',
                  'icon' => 'calendar',
                  'theme' => 'blue',
              ],
              [
                  'title' => 'Kontrak memerlukan tindak lanjut',
                  'description' => 'Sistem mendeteksi tiga kontrak berakhir dalam 14 hari.',
                  'time' => '43 menit lalu',
                  'icon' => 'alert-triangle',
                  'theme' => 'red',
              ],
              [
                  'title' => 'Jadwal wawancara dikonfirmasi',
                  'description' => 'Kandidat posisi Finance mengonfirmasi kehadiran pukul 10.00.',
                  'time' => '1 jam lalu',
                  'icon' => 'message-square',
                  'theme' => 'orange',
              ],
              [
                  'title' => 'Nilai evaluasi kinerja masuk',
                  'description' => 'Departemen Sales & Marketing telah menyelesaikan penilaian periode berjalan.',
                  'time' => '2 jam lalu',
                  'icon' => 'award',
                  'theme' => 'purple',
              ],
          ];

          $peopleHealth = [
              'score' => 91,
              'angle' => round(91 * 3.6),
              'items' => [
                  ['label' => 'Kehadiran karyawan', 'value' => 91, 'class' => 'success'],
                  ['label' => 'Penyelesaian evaluasi', 'value' => 78, 'class' => 'info'],
                  ['label' => 'Kepatuhan pelatihan', 'value' => 84, 'class' => 'warning'],
                  ['label' => 'Kelengkapan dokumen', 'value' => 96, 'class' => ''],
              ],
          ];

          $currentUserName = auth()->user()->name ?? 'HRD';
     @endphp

     <style>
          /*
                       |--------------------------------------------------------------------------
                       | DASHBOARD VARIABLES
                       |--------------------------------------------------------------------------
                       */

          .hrd-dashboard {
               --hr-primary: #4f46e5;
               --hr-primary-dark: #3730a3;
               --hr-primary-soft: rgba(79, 70, 229, 0.12);
               --hr-secondary: #dc2626;
               --hr-success: #16a34a;
               --hr-warning: #d97706;
               --hr-danger: #dc2626;
               --hr-info: #2563eb;
               --hr-purple: #7c3aed;
               --hr-heading: #172033;
               --hr-text: #5f6b7a;
               --hr-muted: #8b95a5;
               --hr-border: #e9edf3;
               --hr-background: #f4f6fa;
               --hr-card: #ffffff;
               --hr-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
               --hr-shadow-hover: 0 18px 40px rgba(15, 23, 42, 0.11);

               width: 100%;
               min-height: 100vh;
               padding: 24px;
               background:
                    radial-gradient(circle at top right,
                         rgba(79, 70, 229, 0.07),
                         transparent 26%),
                    var(--hr-background);
               color: var(--hr-text);
          }

          html[data-theme="dark"] .hrd-dashboard,
          body.dark-theme .hrd-dashboard,
          body.dark-mode .hrd-dashboard {
               --hr-heading: #f8fafc;
               --hr-text: #cbd5e1;
               --hr-muted: #94a3b8;
               --hr-border: rgba(148, 163, 184, 0.16);
               --hr-background: #0f172a;
               --hr-card: #182235;
               --hr-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
               --hr-shadow-hover: 0 18px 40px rgba(0, 0, 0, 0.28);
          }

          .hrd-dashboard *,
          .hrd-dashboard *::before,
          .hrd-dashboard *::after {
               box-sizing: border-box;
          }

          /*
                       |--------------------------------------------------------------------------
                       | HEADER
                       |--------------------------------------------------------------------------
                       */

          .hrd-hero {
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
                         rgba(49, 46, 129, 0.97),
                         rgba(79, 70, 229, 0.96) 50%,
                         rgba(220, 38, 38, 0.9));
               box-shadow: 0 22px 48px rgba(67, 56, 202, 0.24);
          }

          .hrd-hero::before {
               position: absolute;
               top: -120px;
               right: -80px;
               width: 360px;
               height: 360px;
               border: 70px solid rgba(255, 255, 255, 0.07);
               border-radius: 50%;
               content: "";
          }

          .hrd-hero::after {
               position: absolute;
               right: 210px;
               bottom: -130px;
               width: 270px;
               height: 270px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.06);
               content: "";
          }

          .hrd-hero-content {
               position: relative;
               z-index: 2;
               max-width: 720px;
          }

          .hrd-role-badge {
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

          .hrd-role-badge .badge-indicator {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #86efac;
               box-shadow: 0 0 0 5px rgba(134, 239, 172, 0.18);
          }

          .hrd-hero h1 {
               margin: 0 0 12px;
               color: #ffffff;
               font-size: clamp(28px, 4vw, 42px);
               font-weight: 800;
               letter-spacing: -0.035em;
               line-height: 1.12;
          }

          .hrd-hero-description {
               max-width: 650px;
               margin: 0;
               color: rgba(255, 255, 255, 0.83);
               font-size: 15px;
               line-height: 1.75;
          }

          .hrd-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 18px;
               margin-top: 20px;
          }

          .hrd-hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.86);
               font-size: 12px;
               font-weight: 600;
          }

          .hrd-hero-meta-item svg {
               width: 15px;
               height: 15px;
          }

          .hrd-hero-actions {
               position: relative;
               z-index: 2;
               display: flex;
               flex-shrink: 0;
               flex-direction: column;
               gap: 11px;
               width: 205px;
          }

          .hrd-button {
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

          .hrd-button:hover {
               transform: translateY(-2px);
          }

          .hrd-button svg {
               width: 16px;
               height: 16px;
          }

          .hrd-button-primary {
               background: #ffffff;
               color: var(--hr-primary-dark);
               box-shadow: 0 10px 22px rgba(49, 46, 129, 0.2);
          }

          .hrd-button-secondary {
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

          .hrd-stat-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 18px;
               margin-bottom: 24px;
          }

          .hrd-main-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.7fr) minmax(320px, 0.8fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .hrd-secondary-grid {
               display: grid;
               grid-template-columns: minmax(320px, 0.78fr) minmax(0, 1.45fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .hrd-bottom-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.7fr);
               gap: 22px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | STATISTIC CARDS
                       |--------------------------------------------------------------------------
                       */

          .hrd-stat-card {
               position: relative;
               overflow: hidden;
               min-height: 170px;
               padding: 21px;
               border: 1px solid var(--hr-border);
               border-radius: 18px;
               background: var(--hr-card);
               box-shadow: var(--hr-shadow);
               transition:
                    transform 0.25s ease,
                    box-shadow 0.25s ease,
                    border-color 0.25s ease;
          }

          .hrd-stat-card:hover {
               transform: translateY(-4px);
               border-color: rgba(79, 70, 229, 0.25);
               box-shadow: var(--hr-shadow-hover);
          }

          .hrd-stat-card::after {
               position: absolute;
               top: -28px;
               right: -28px;
               width: 95px;
               height: 95px;
               border-radius: 50%;
               content: "";
               opacity: 0.7;
          }

          .hrd-stat-card.theme-orange::after {
               background: rgba(79, 70, 229, 0.08);
          }

          .hrd-stat-card.theme-green::after {
               background: rgba(22, 163, 74, 0.08);
          }

          .hrd-stat-card.theme-red::after {
               background: rgba(220, 38, 38, 0.08);
          }

          .hrd-stat-card.theme-blue::after {
               background: rgba(37, 99, 235, 0.08);
          }

          .hrd-stat-top {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 16px;
          }

          .hrd-stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
          }

          .hrd-stat-icon svg {
               width: 21px;
               height: 21px;
          }

          .theme-orange .hrd-stat-icon {
               background: rgba(79, 70, 229, 0.12);
               color: #4f46e5;
          }

          .theme-green .hrd-stat-icon {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .theme-red .hrd-stat-icon {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .theme-blue .hrd-stat-icon {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .hrd-stat-trend {
               display: inline-flex;
               align-items: center;
               gap: 4px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 11px;
               font-weight: 800;
          }

          .hrd-stat-trend svg {
               width: 12px;
               height: 12px;
          }

          .hrd-stat-trend.up {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .hrd-stat-trend.down {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .hrd-stat-label {
               margin: 18px 0 7px;
               color: var(--hr-muted);
               font-size: 12px;
               font-weight: 700;
          }

          .hrd-stat-value {
               display: flex;
               align-items: baseline;
               gap: 2px;
               margin: 0;
               color: var(--hr-heading);
               font-size: 29px;
               font-weight: 800;
               letter-spacing: -0.03em;
               line-height: 1;
          }

          .hrd-stat-description {
               margin: 10px 0 0;
               color: var(--hr-text);
               font-size: 11px;
               line-height: 1.5;
          }

          /*
                       |--------------------------------------------------------------------------
                       | COMMON CARD
                       |--------------------------------------------------------------------------
                       */

          .hrd-card {
               border: 1px solid var(--hr-border);
               border-radius: 20px;
               background: var(--hr-card);
               box-shadow: var(--hr-shadow);
          }

          .hrd-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               padding: 22px 23px 17px;
               border-bottom: 1px solid var(--hr-border);
          }

          .hrd-card-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .hrd-card-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 39px;
               height: 39px;
               border-radius: 12px;
               background: var(--hr-primary-soft);
               color: var(--hr-primary);
          }

          .hrd-card-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .hrd-card-title {
               margin: 0;
               color: var(--hr-heading);
               font-size: 16px;
               font-weight: 800;
               letter-spacing: -0.015em;
          }

          .hrd-card-subtitle {
               margin: 5px 0 0;
               color: var(--hr-muted);
               font-size: 11px;
               line-height: 1.55;
          }

          .hrd-card-action {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 8px 10px;
               border: 1px solid var(--hr-border);
               border-radius: 10px;
               background: transparent;
               color: var(--hr-text);
               font-size: 11px;
               font-weight: 700;
               cursor: pointer;
          }

          .hrd-card-action svg {
               width: 14px;
               height: 14px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | CHART
                       |--------------------------------------------------------------------------
                       */

          .hrd-chart-body {
               padding: 22px 23px 24px;
          }

          .hrd-chart-summary {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 22px;
          }

          .hrd-chart-legends {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
          }

          .hrd-chart-legend {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               color: var(--hr-text);
               font-size: 11px;
               font-weight: 700;
          }

          .hrd-chart-legend-dot {
               width: 9px;
               height: 9px;
               border-radius: 3px;
          }

          .hrd-chart-legend-dot.scheduled {
               background: rgba(79, 70, 229, 0.26);
          }

          .hrd-chart-legend-dot.completed {
               background: var(--hr-primary);
          }

          .hrd-chart-rate {
               text-align: right;
          }

          .hrd-chart-rate strong {
               display: block;
               color: var(--hr-heading);
               font-size: 19px;
               font-weight: 800;
          }

          .hrd-chart-rate span {
               color: var(--hr-muted);
               font-size: 10px;
          }

          .hrd-chart-area {
               position: relative;
               display: grid;
               grid-template-columns: 34px minmax(0, 1fr);
               gap: 11px;
               height: 285px;
          }

          .hrd-chart-y-axis {
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               height: 245px;
               padding-top: 2px;
               color: var(--hr-muted);
               font-size: 9px;
               text-align: right;
          }

          .hrd-chart-content {
               position: relative;
               height: 285px;
          }

          .hrd-chart-lines {
               position: absolute;
               inset: 0 0 40px;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               pointer-events: none;
          }

          .hrd-chart-line {
               width: 100%;
               border-top: 1px dashed var(--hr-border);
          }

          .hrd-chart-columns {
               position: absolute;
               inset: 0 0 0;
               display: grid;
               grid-template-columns: repeat(7, minmax(30px, 1fr));
               gap: 12px;
          }

          .hrd-chart-column {
               display: flex;
               flex-direction: column;
               align-items: center;
               min-width: 0;
          }

          .hrd-chart-bars {
               display: flex;
               align-items: flex-end;
               justify-content: center;
               gap: 5px;
               width: 100%;
               height: 245px;
          }

          .hrd-chart-bar {
               position: relative;
               width: min(16px, 38%);
               min-height: 4px;
               border-radius: 6px 6px 3px 3px;
               cursor: pointer;
               transition:
                    filter 0.2s ease,
                    transform 0.2s ease;
          }

          .hrd-chart-bar:hover {
               z-index: 3;
               filter: brightness(0.93);
               transform: scaleX(1.08);
          }

          .hrd-chart-bar.scheduled {
               background: rgba(79, 70, 229, 0.26);
          }

          .hrd-chart-bar.completed {
               background: linear-gradient(to top, #3730a3, #818cf8);
               box-shadow: 0 5px 12px rgba(79, 70, 229, 0.2);
          }

          .hrd-chart-tooltip {
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

          .hrd-chart-bar:hover .hrd-chart-tooltip {
               opacity: 1;
               transform: translateX(-50%) translateY(0);
          }

          .hrd-chart-day {
               margin-top: 13px;
               color: var(--hr-muted);
               font-size: 10px;
               font-weight: 700;
          }

          /*
                       |--------------------------------------------------------------------------
                       | PEOPLE HEALTH
                       |--------------------------------------------------------------------------
                       */

          .hrd-health-body {
               padding: 24px;
          }

          .hrd-utilization {
               display: flex;
               align-items: center;
               gap: 22px;
               margin-bottom: 25px;
               padding-bottom: 24px;
               border-bottom: 1px solid var(--hr-border);
          }

          .hrd-utilization-ring {
               position: relative;
               display: grid;
               flex-shrink: 0;
               width: 118px;
               height: 118px;
               place-items: center;
               border-radius: 50%;
               background:
                    conic-gradient(var(--hr-primary) 0deg 313deg,
                         var(--hr-border) 313deg 360deg);
          }

          .hrd-utilization-ring::before {
               position: absolute;
               width: 88px;
               height: 88px;
               border-radius: 50%;
               background: var(--hr-card);
               content: "";
          }

          .hrd-utilization-value {
               position: relative;
               z-index: 2;
               color: var(--hr-heading);
               font-size: 24px;
               font-weight: 800;
          }

          .hrd-utilization-details h3 {
               margin: 0 0 6px;
               color: var(--hr-heading);
               font-size: 14px;
               font-weight: 800;
          }

          .hrd-utilization-details p {
               margin: 0 0 11px;
               color: var(--hr-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .hrd-utilization-status {
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

          .hrd-utilization-status::before {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: #22c55e;
               content: "";
          }

          .hrd-health-list {
               display: flex;
               flex-direction: column;
               gap: 17px;
          }

          .hrd-health-item-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 8px;
          }

          .hrd-health-item-label {
               color: var(--hr-text);
               font-size: 11px;
               font-weight: 700;
          }

          .hrd-health-item-value {
               color: var(--hr-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .hrd-progress {
               overflow: hidden;
               width: 100%;
               height: 7px;
               border-radius: 999px;
               background: var(--hr-border);
          }

          .hrd-progress-bar {
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, var(--hr-primary-dark), #818cf8);
          }

          .hrd-progress-bar.success {
               background: linear-gradient(90deg, #15803d, #4ade80);
          }

          .hrd-progress-bar.info {
               background: linear-gradient(90deg, #1d4ed8, #60a5fa);
          }

          .hrd-progress-bar.warning {
               background: linear-gradient(90deg, #b45309, #fbbf24);
          }

          /*
                       |--------------------------------------------------------------------------
                       | PRIORITY
                       |--------------------------------------------------------------------------
                       */

          .hrd-priority-list {
               display: flex;
               flex-direction: column;
          }

          .hrd-priority-item {
               display: flex;
               gap: 13px;
               padding: 17px 21px;
               border-bottom: 1px solid var(--hr-border);
               transition: background 0.2s ease;
          }

          .hrd-priority-item:last-child {
               border-bottom: 0;
          }

          .hrd-priority-item:hover {
               background: rgba(79, 70, 229, 0.035);
          }

          .hrd-priority-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 38px;
               height: 38px;
               border-radius: 11px;
               background: var(--hr-primary-soft);
               color: var(--hr-primary);
          }

          .hrd-priority-icon svg {
               width: 17px;
               height: 17px;
          }

          .hrd-priority-content {
               min-width: 0;
               flex: 1;
          }

          .hrd-priority-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 10px;
          }

          .hrd-priority-title {
               margin: 1px 0 0;
               color: var(--hr-heading);
               font-size: 12px;
               font-weight: 800;
          }

          .hrd-priority-description {
               margin: 6px 0 11px;
               color: var(--hr-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .hrd-priority-action {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               border: 0;
               background: transparent;
               color: var(--hr-primary);
               font-size: 10px;
               font-weight: 800;
               cursor: pointer;
          }

          .hrd-priority-action svg {
               width: 12px;
               height: 12px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | BADGES
                       |--------------------------------------------------------------------------
                       */

          .hrd-badge {
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

          .hrd-badge::before {
               width: 5px;
               height: 5px;
               border-radius: 50%;
               background: currentColor;
               content: "";
          }

          .hrd-badge.success {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .hrd-badge.info {
               background: rgba(37, 99, 235, 0.1);
               color: #2563eb;
          }

          .hrd-badge.warning {
               background: rgba(217, 119, 6, 0.1);
               color: #b45309;
          }

          .hrd-badge.danger {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .hrd-badge.neutral {
               background: rgba(100, 116, 139, 0.12);
               color: #64748b;
          }

          /*
                       |--------------------------------------------------------------------------
                       | TABLE
                       |--------------------------------------------------------------------------
                       */

          .hrd-schedule-card {
               overflow: hidden;
          }

          .hrd-schedule-toolbar {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 14px 20px;
               border-bottom: 1px solid var(--hr-border);
          }

          .hrd-filter-list {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .hrd-filter-button {
               padding: 7px 10px;
               border: 1px solid var(--hr-border);
               border-radius: 8px;
               background: transparent;
               color: var(--hr-muted);
               font-size: 9px;
               font-weight: 800;
               cursor: pointer;
               transition:
                    background 0.2s ease,
                    color 0.2s ease,
                    border-color 0.2s ease;
          }

          .hrd-filter-button:hover,
          .hrd-filter-button.active {
               border-color: var(--hr-primary);
               background: var(--hr-primary);
               color: #ffffff;
          }

          .hrd-search {
               position: relative;
               width: 195px;
          }

          .hrd-search svg {
               position: absolute;
               top: 50%;
               left: 11px;
               width: 14px;
               height: 14px;
               color: var(--hr-muted);
               transform: translateY(-50%);
          }

          .hrd-search input {
               width: 100%;
               height: 35px;
               padding: 7px 11px 7px 33px;
               border: 1px solid var(--hr-border);
               border-radius: 9px;
               outline: none;
               background: transparent;
               color: var(--hr-heading);
               font-size: 10px;
          }

          .hrd-search input:focus {
               border-color: var(--hr-primary);
               box-shadow: 0 0 0 3px var(--hr-primary-soft);
          }

          .hrd-table-wrapper {
               overflow-x: auto;
          }

          .hrd-table {
               width: 100%;
               min-width: 840px;
               border-collapse: collapse;
          }

          .hrd-table th {
               padding: 12px 16px;
               border-bottom: 1px solid var(--hr-border);
               background: rgba(148, 163, 184, 0.045);
               color: var(--hr-muted);
               font-size: 9px;
               font-weight: 800;
               letter-spacing: 0.04em;
               text-align: left;
               text-transform: uppercase;
          }

          .hrd-table td {
               padding: 15px 16px;
               border-bottom: 1px solid var(--hr-border);
               color: var(--hr-text);
               font-size: 10px;
               vertical-align: middle;
          }

          .hrd-table tbody tr {
               transition: background 0.2s ease;
          }

          .hrd-table tbody tr:hover {
               background: rgba(79, 70, 229, 0.028);
          }

          .hrd-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .hrd-task {
               display: flex;
               align-items: center;
               gap: 11px;
               min-width: 215px;
          }

          .hrd-task-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 35px;
               height: 35px;
               border-radius: 10px;
               background: var(--hr-primary-soft);
               color: var(--hr-primary);
          }

          .hrd-task-icon svg {
               width: 15px;
               height: 15px;
          }

          .hrd-task-title {
               display: block;
               margin-bottom: 3px;
               color: var(--hr-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .hrd-task-code {
               color: var(--hr-muted);
               font-size: 9px;
          }

          .hrd-team-name {
               display: block;
               color: var(--hr-heading);
               font-weight: 700;
          }

          .hrd-team-leader {
               display: block;
               margin-top: 3px;
               color: var(--hr-muted);
               font-size: 9px;
          }

          .hrd-time {
               color: var(--hr-heading);
               font-weight: 700;
          }

          .hrd-location {
               display: block;
               margin-top: 3px;
               color: var(--hr-muted);
               font-size: 9px;
          }

          .hrd-table-progress {
               min-width: 105px;
          }

          .hrd-table-progress-top {
               display: flex;
               justify-content: space-between;
               margin-bottom: 6px;
               color: var(--hr-muted);
               font-size: 9px;
          }

          .hrd-table-progress .hrd-progress {
               height: 6px;
          }

          .hrd-action-menu {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 31px;
               height: 31px;
               border: 1px solid var(--hr-border);
               border-radius: 8px;
               background: transparent;
               color: var(--hr-muted);
               cursor: pointer;
          }

          .hrd-action-menu svg {
               width: 15px;
               height: 15px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | TEAM WORKLOAD
                       |--------------------------------------------------------------------------
                       */

          .hrd-team-list {
               padding: 4px 21px 9px;
          }

          .hrd-team-item {
               padding: 17px 0;
               border-bottom: 1px solid var(--hr-border);
          }

          .hrd-team-item:last-child {
               border-bottom: 0;
          }

          .hrd-team-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 10px;
          }

          .hrd-team-header h4 {
               margin: 0;
               color: var(--hr-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .hrd-team-metadata {
               display: flex;
               gap: 14px;
               margin-top: 4px;
               color: var(--hr-muted);
               font-size: 9px;
          }

          .hrd-team-percentage {
               color: var(--hr-heading);
               font-size: 12px;
               font-weight: 800;
          }

          /*
                       |--------------------------------------------------------------------------
                       | ACTIVITY
                       |--------------------------------------------------------------------------
                       */

          .hrd-activity-list {
               padding: 4px 21px 10px;
          }

          .hrd-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 17px 0;
          }

          .hrd-activity-item:not(:last-child)::before {
               position: absolute;
               top: 52px;
               bottom: -3px;
               left: 18px;
               width: 1px;
               background: var(--hr-border);
               content: "";
          }

          .hrd-activity-icon {
               position: relative;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 37px;
               height: 37px;
               border: 4px solid var(--hr-card);
               border-radius: 50%;
          }

          .hrd-activity-icon svg {
               width: 14px;
               height: 14px;
          }

          .hrd-activity-icon.green {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .hrd-activity-icon.blue {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .hrd-activity-icon.red {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .hrd-activity-icon.orange {
               background: rgba(79, 70, 229, 0.12);
               color: #4f46e5;
          }

          .hrd-activity-icon.purple {
               background: rgba(124, 58, 237, 0.12);
               color: #7c3aed;
          }

          .hrd-activity-content {
               min-width: 0;
               flex: 1;
          }

          .hrd-activity-content h4 {
               margin: 1px 0 5px;
               color: var(--hr-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .hrd-activity-content p {
               margin: 0;
               color: var(--hr-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .hrd-activity-time {
               margin-top: 6px;
               color: var(--hr-primary);
               font-size: 9px;
               font-weight: 700;
          }

          /*
                       |--------------------------------------------------------------------------
                       | EMPTY STATE
                       |--------------------------------------------------------------------------
                       */

          .hrd-empty-state {
               display: none;
               padding: 38px 20px;
               text-align: center;
          }

          .hrd-empty-state svg {
               width: 34px;
               height: 34px;
               margin-bottom: 10px;
               color: var(--hr-muted);
          }

          .hrd-empty-state h4 {
               margin: 0 0 5px;
               color: var(--hr-heading);
               font-size: 13px;
          }

          .hrd-empty-state p {
               margin: 0;
               color: var(--hr-muted);
               font-size: 10px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | RESPONSIVE
                       |--------------------------------------------------------------------------
                       */

          @media (max-width: 1280px) {
               .hrd-stat-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .hrd-main-grid,
               .hrd-secondary-grid,
               .hrd-bottom-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 768px) {
               .hrd-dashboard {
                    padding: 15px;
               }

               .hrd-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    min-height: auto;
                    padding: 26px 23px;
                    border-radius: 19px;
               }

               .hrd-hero-actions {
                    flex-direction: row;
                    width: 100%;
               }

               .hrd-hero-actions .hrd-button {
                    flex: 1;
               }

               .hrd-stat-grid {
                    grid-template-columns: 1fr;
                    gap: 13px;
               }

               .hrd-stat-card {
                    min-height: auto;
               }

               .hrd-card-header,
               .hrd-schedule-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hrd-search {
                    width: 100%;
               }

               .hrd-chart-area {
                    grid-template-columns: 26px minmax(0, 1fr);
               }

               .hrd-chart-columns {
                    gap: 5px;
               }

               .hrd-chart-bars {
                    gap: 3px;
               }

               .hrd-utilization {
                    align-items: flex-start;
               }
          }

          @media (max-width: 520px) {
               .hrd-hero-actions {
                    flex-direction: column;
               }

               .hrd-chart-summary {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .hrd-chart-rate {
                    text-align: left;
               }

               .hrd-chart-column:nth-child(even) .hrd-chart-day {
                    opacity: 0.55;
               }

               .hrd-utilization {
                    align-items: center;
                    flex-direction: column;
                    text-align: center;
               }
          }
     </style>

     <div class="hrd-dashboard">

          {{-- ================================================================
            HEADER HRD
        ================================================================= --}}

          <section class="hrd-hero">
               <div class="hrd-hero-content">
                    <div class="hrd-role-badge">
                         <span class="badge-indicator"></span>
                         Human Resources Department
                    </div>

                    <h1>HR Workforce Management Center</h1>

                    <p class="hrd-hero-description">
                         Selamat datang, {{ $currentUserName }}. Kelola data karyawan,
                         kehadiran, cuti, rekrutmen, kontrak, payroll, evaluasi kinerja,
                         serta program pengembangan SDM dari satu dashboard terintegrasi.
                    </p>

                    <div class="hrd-hero-meta">
                         <span class="hrd-hero-meta-item">
                              <i data-feather="calendar"></i>
                              {{ now()->translatedFormat('l, d F Y') }}
                         </span>

                         <span class="hrd-hero-meta-item">
                              <i data-feather="users"></i>
                              Database karyawan aktif
                         </span>

                         <span class="hrd-hero-meta-item">
                              <i data-feather="shield"></i>
                              Data HR terlindungi
                         </span>
                    </div>
               </div>

               <div class="hrd-hero-actions">
                    <button type="button" class="hrd-button hrd-button-primary">
                         <i data-feather="user-plus"></i>
                         Tambah Karyawan
                    </button>

                    <button type="button" class="hrd-button hrd-button-secondary">
                         <i data-feather="download"></i>
                         Unduh Laporan HR
                    </button>
               </div>
          </section>

          {{-- ================================================================
            RINGKASAN HRD
        ================================================================= --}}

          <section class="hrd-stat-grid">
               @foreach ($statistics as $statistic)
                    <article class="hrd-stat-card theme-{{ $statistic['theme'] }}">
                         <div class="hrd-stat-top">
                              <span class="hrd-stat-icon">
                                   <i data-feather="{{ $statistic['icon'] }}"></i>
                              </span>

                              <span class="hrd-stat-trend {{ $statistic['trend_type'] }}">
                                   <i
                                        data-feather="{{ $statistic['trend_type'] === 'up' ? 'trending-up' : 'alert-circle' }}"></i>
                                   {{ $statistic['trend'] }}
                              </span>
                         </div>

                         <p class="hrd-stat-label">{{ $statistic['label'] }}</p>

                         <h2 class="hrd-stat-value">
                              <span>{{ $statistic['value'] }}</span>
                              <span>{{ $statistic['suffix'] }}</span>
                         </h2>

                         <p class="hrd-stat-description">
                              {{ $statistic['description'] }}
                         </p>
                    </article>
               @endforeach
          </section>

          {{-- ================================================================
            KEHADIRAN DAN KESEHATAN SDM
        ================================================================= --}}

          <section class="hrd-main-grid">

               {{-- Grafik kehadiran --}}
               <article class="hrd-card">
                    <header class="hrd-card-header">
                         <div class="hrd-card-heading">
                              <span class="hrd-card-heading-icon">
                                   <i data-feather="bar-chart-2"></i>
                              </span>

                              <div>
                                   <h2 class="hrd-card-title">Tren Kehadiran Mingguan</h2>
                                   <p class="hrd-card-subtitle">
                                        Perbandingan target dan realisasi kehadiran karyawan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="hrd-card-action">
                              Minggu ini
                              <i data-feather="chevron-down"></i>
                         </button>
                    </header>

                    <div class="hrd-chart-body">
                         <div class="hrd-chart-summary">
                              <div class="hrd-chart-legends">
                                   <span class="hrd-chart-legend">
                                        <span class="hrd-chart-legend-dot scheduled"></span>
                                        Target
                                   </span>

                                   <span class="hrd-chart-legend">
                                        <span class="hrd-chart-legend-dot completed"></span>
                                        Kehadiran
                                   </span>
                              </div>

                              <div class="hrd-chart-rate">
                                   <strong>{{ number_format($attendanceRate, 1, ',', '.') }}%</strong>
                                   <span>Realisasi terhadap target</span>
                              </div>
                         </div>

                         <div class="hrd-chart-area">
                              <div class="hrd-chart-y-axis">
                                   <span>100</span>
                                   <span>75</span>
                                   <span>50</span>
                                   <span>25</span>
                                   <span>0</span>
                              </div>

                              <div class="hrd-chart-content">
                                   <div class="hrd-chart-lines">
                                        <span class="hrd-chart-line"></span>
                                        <span class="hrd-chart-line"></span>
                                        <span class="hrd-chart-line"></span>
                                        <span class="hrd-chart-line"></span>
                                        <span class="hrd-chart-line"></span>
                                   </div>

                                   <div class="hrd-chart-columns">
                                        @foreach ($weeklyAttendance as $attendance)
                                             <div class="hrd-chart-column">
                                                  <div class="hrd-chart-bars">
                                                       <div class="hrd-chart-bar scheduled"
                                                            style="height: {{ $attendance['target'] }}%;">
                                                            <span class="hrd-chart-tooltip">
                                                                 Target {{ $attendance['target'] }}%
                                                            </span>
                                                       </div>

                                                       <div class="hrd-chart-bar completed"
                                                            style="height: {{ $attendance['present'] }}%;">
                                                            <span class="hrd-chart-tooltip">
                                                                 Hadir {{ $attendance['present'] }}%
                                                            </span>
                                                       </div>
                                                  </div>

                                                  <span class="hrd-chart-day" title="{{ $attendance['full_day'] }}">
                                                       {{ $attendance['day'] }}
                                                  </span>
                                             </div>
                                        @endforeach
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>

               {{-- Kesehatan SDM --}}
               <article class="hrd-card">
                    <header class="hrd-card-header">
                         <div class="hrd-card-heading">
                              <span class="hrd-card-heading-icon">
                                   <i data-feather="heart"></i>
                              </span>

                              <div>
                                   <h2 class="hrd-card-title">Kesehatan Organisasi</h2>
                                   <p class="hrd-card-subtitle">
                                        Ringkasan kesiapan, kepatuhan, dan administrasi SDM.
                                   </p>
                              </div>
                         </div>
                    </header>

                    <div class="hrd-health-body">
                         <div class="hrd-utilization">
                              <div class="hrd-utilization-ring"
                                   style="background: conic-gradient(
                                var(--hr-primary) 0deg {{ $peopleHealth['angle'] }}deg,
                                var(--hr-border) {{ $peopleHealth['angle'] }}deg 360deg
                            );">
                                   <span class="hrd-utilization-value">
                                        {{ $peopleHealth['score'] }}%
                                   </span>
                              </div>

                              <div class="hrd-utilization-details">
                                   <h3>Indeks kesiapan SDM</h3>

                                   <p>
                                        Kondisi tenaga kerja, administrasi, dan kepatuhan
                                        program HR masih berada dalam kategori sehat.
                                   </p>

                                   <span class="hrd-utilization-status">
                                        Kondisi baik
                                   </span>
                              </div>
                         </div>

                         <div class="hrd-health-list">
                              @foreach ($peopleHealth['items'] as $healthItem)
                                   <div>
                                        <div class="hrd-health-item-top">
                                             <span class="hrd-health-item-label">
                                                  {{ $healthItem['label'] }}
                                             </span>

                                             <span class="hrd-health-item-value">
                                                  {{ $healthItem['value'] }}%
                                             </span>
                                        </div>

                                        <div class="hrd-progress">
                                             <div class="hrd-progress-bar {{ $healthItem['class'] }}"
                                                  style="width: {{ $healthItem['value'] }}%;"></div>
                                        </div>
                                   </div>
                              @endforeach
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
            PRIORITAS DAN AGENDA HRD
        ================================================================= --}}

          <section class="hrd-secondary-grid">

               {{-- Prioritas HRD --}}
               <article class="hrd-card">
                    <header class="hrd-card-header">
                         <div class="hrd-card-heading">
                              <span class="hrd-card-heading-icon">
                                   <i data-feather="alert-octagon"></i>
                              </span>

                              <div>
                                   <h2 class="hrd-card-title">Prioritas HRD</h2>
                                   <p class="hrd-card-subtitle">
                                        Administrasi dan proses SDM yang perlu ditindaklanjuti.
                                   </p>
                              </div>
                         </div>

                         <span class="hrd-badge danger">
                              {{ count($hrPriorities) }} prioritas
                         </span>
                    </header>

                    <div class="hrd-priority-list">
                         @foreach ($hrPriorities as $priority)
                              <div class="hrd-priority-item">
                                   <span class="hrd-priority-icon">
                                        <i data-feather="{{ $priority['icon'] }}"></i>
                                   </span>

                                   <div class="hrd-priority-content">
                                        <div class="hrd-priority-heading">
                                             <h3 class="hrd-priority-title">
                                                  {{ $priority['title'] }}
                                             </h3>

                                             <span class="hrd-badge {{ $priority['status_class'] }}">
                                                  {{ $priority['status'] }}
                                             </span>
                                        </div>

                                        <p class="hrd-priority-description">
                                             {{ $priority['description'] }}
                                        </p>

                                        <button type="button" class="hrd-priority-action">
                                             {{ $priority['action'] }}
                                             <i data-feather="arrow-right"></i>
                                        </button>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Agenda HRD --}}
               <article class="hrd-card hrd-schedule-card">
                    <header class="hrd-card-header">
                         <div class="hrd-card-heading">
                              <span class="hrd-card-heading-icon">
                                   <i data-feather="clipboard"></i>
                              </span>

                              <div>
                                   <h2 class="hrd-card-title">Agenda HRD Hari Ini</h2>
                                   <p class="hrd-card-subtitle">
                                        Monitoring proses rekrutmen, payroll, evaluasi, dan pelatihan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="hrd-card-action">
                              <i data-feather="calendar"></i>
                              Lihat kalender
                         </button>
                    </header>

                    <div class="hrd-schedule-toolbar">
                         <div class="hrd-filter-list">
                              <button type="button" class="hrd-filter-button active" data-hr-filter="semua">
                                   Semua
                              </button>

                              <button type="button" class="hrd-filter-button" data-hr-filter="berjalan">
                                   Berjalan
                              </button>

                              <button type="button" class="hrd-filter-button" data-hr-filter="terjadwal">
                                   Terjadwal
                              </button>

                              <button type="button" class="hrd-filter-button" data-hr-filter="menunggu">
                                   Menunggu
                              </button>

                              <button type="button" class="hrd-filter-button" data-hr-filter="selesai">
                                   Selesai
                              </button>
                         </div>

                         <label class="hrd-search">
                              <i data-feather="search"></i>

                              <input type="search" id="hrScheduleSearch"
                                   placeholder="Cari agenda, departemen, atau PIC..." autocomplete="off">
                         </label>
                    </div>

                    <div class="hrd-table-wrapper">
                         <table class="hrd-table">
                              <thead>
                                   <tr>
                                        <th>Agenda</th>
                                        <th>Departemen</th>
                                        <th>Waktu dan lokasi</th>
                                        <th>Progres</th>
                                        <th>Status</th>
                                        <th></th>
                                   </tr>
                              </thead>

                              <tbody id="hrScheduleBody">
                                   @foreach ($hrSchedules as $schedule)
                                        @php
                                             $scheduleStatusClass = match ($schedule['status']) {
                                                 'Selesai' => 'success',
                                                 'Berjalan' => 'info',
                                                 'Terjadwal' => 'neutral',
                                                 'Menunggu' => 'warning',
                                                 'Tertunda' => 'danger',
                                                 default => 'neutral',
                                             };

                                             $scheduleIcon = match ($schedule['category']) {
                                                 'Onboarding' => 'user-plus',
                                                 'Rekrutmen' => 'search',
                                                 'Payroll' => 'credit-card',
                                                 'Evaluasi' => 'clipboard',
                                                 'Pelatihan' => 'book-open',
                                                 default => 'briefcase',
                                             };
                                        @endphp

                                        <tr data-hr-row data-hr-status="{{ strtolower($schedule['status']) }}"
                                             data-hr-keyword="{{ strtolower(
                                                 $schedule['agenda'] .
                                                     ' ' .
                                                     $schedule['code'] .
                                                     ' ' .
                                                     $schedule['department'] .
                                                     ' ' .
                                                     $schedule['pic'] .
                                                     ' ' .
                                                     $schedule['location'] .
                                                     ' ' .
                                                     $schedule['category'],
                                             ) }}">
                                             <td>
                                                  <div class="hrd-task">
                                                       <span class="hrd-task-icon">
                                                            <i data-feather="{{ $scheduleIcon }}"></i>
                                                       </span>

                                                       <span>
                                                            <strong class="hrd-task-title">
                                                                 {{ $schedule['agenda'] }}
                                                            </strong>

                                                            <span class="hrd-task-code">
                                                                 {{ $schedule['code'] }}
                                                                 ·
                                                                 {{ $schedule['category'] }}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="hrd-team-name">
                                                       {{ $schedule['department'] }}
                                                  </span>

                                                  <span class="hrd-team-leader">
                                                       PIC: {{ $schedule['pic'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="hrd-time">
                                                       {{ $schedule['time'] }}
                                                  </span>

                                                  <span class="hrd-location">
                                                       {{ $schedule['location'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="hrd-table-progress">
                                                       <div class="hrd-table-progress-top">
                                                            <span>Progres</span>
                                                            <strong>{{ $schedule['progress'] }}%</strong>
                                                       </div>

                                                       <div class="hrd-progress">
                                                            <div class="hrd-progress-bar {{ $schedule['progress'] === 100 ? 'success' : '' }}"
                                                                 style="width: {{ $schedule['progress'] }}%;"></div>
                                                       </div>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="hrd-badge {{ $scheduleStatusClass }}">
                                                       {{ $schedule['status'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <button type="button" class="hrd-action-menu"
                                                       aria-label="Pilihan agenda {{ $schedule['agenda'] }}">
                                                       <i data-feather="more-horizontal"></i>
                                                  </button>
                                             </td>
                                        </tr>
                                   @endforeach
                              </tbody>
                         </table>

                         <div class="hrd-empty-state" id="hrScheduleEmpty">
                              <i data-feather="search"></i>
                              <h4>Agenda tidak ditemukan</h4>
                              <p>Gunakan kata kunci atau filter status yang berbeda.</p>
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
            KAPASITAS DEPARTEMEN DAN AKTIVITAS
        ================================================================= --}}

          <section class="hrd-bottom-grid">

               {{-- Kapasitas departemen --}}
               <article class="hrd-card">
                    <header class="hrd-card-header">
                         <div class="hrd-card-heading">
                              <span class="hrd-card-heading-icon">
                                   <i data-feather="layers"></i>
                              </span>

                              <div>
                                   <h2 class="hrd-card-title">Kapasitas Departemen</h2>
                                   <p class="hrd-card-subtitle">
                                        Tingkat kehadiran dan pemanfaatan kapasitas tenaga kerja.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="hrd-card-action">
                              Kelola karyawan
                              <i data-feather="arrow-up-right"></i>
                         </button>
                    </header>

                    <div class="hrd-team-list">
                         @foreach ($departmentCapacities as $department)
                              @php
                                   $capacityClass = match (true) {
                                       $department['capacity'] >= 90 => 'warning',
                                       $department['capacity'] >= 75 => 'info',
                                       default => 'success',
                                   };
                              @endphp

                              <div class="hrd-team-item">
                                   <div class="hrd-team-header">
                                        <div>
                                             <h4>{{ $department['name'] }}</h4>

                                             <div class="hrd-team-metadata">
                                                  <span>{{ $department['employees'] }} karyawan</span>
                                                  <span>{{ $department['present'] }} hadir hari ini</span>
                                             </div>
                                        </div>

                                        <span class="hrd-team-percentage">
                                             {{ $department['capacity'] }}%
                                        </span>
                                   </div>

                                   <div class="hrd-progress">
                                        <div class="hrd-progress-bar {{ $capacityClass }}"
                                             style="width: {{ $department['capacity'] }}%;"></div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Aktivitas HRD --}}
               <article class="hrd-card">
                    <header class="hrd-card-header">
                         <div class="hrd-card-heading">
                              <span class="hrd-card-heading-icon">
                                   <i data-feather="bell"></i>
                              </span>

                              <div>
                                   <h2 class="hrd-card-title">Aktivitas HRD</h2>
                                   <p class="hrd-card-subtitle">
                                        Pembaruan terbaru dari proses administrasi dan pengelolaan SDM.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="hrd-card-action" id="markHrRead">
                              Tandai dibaca
                         </button>
                    </header>

                    <div class="hrd-activity-list" id="hrActivityList">
                         @foreach ($hrActivities as $activity)
                              <div class="hrd-activity-item" data-hr-activity>
                                   <span class="hrd-activity-icon {{ $activity['theme'] }}">
                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                   </span>

                                   <div class="hrd-activity-content">
                                        <h4>{{ $activity['title'] }}</h4>
                                        <p>{{ $activity['description'] }}</p>
                                        <div class="hrd-activity-time">{{ $activity['time'] }}</div>
                                   </div>
                              </div>
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

               const filterButtons = document.querySelectorAll('[data-hr-filter]');
               const scheduleRows = document.querySelectorAll('[data-hr-row]');
               const scheduleSearch = document.getElementById('hrScheduleSearch');
               const scheduleEmpty = document.getElementById('hrScheduleEmpty');

               let activeStatus = 'semua';

               function filterHrSchedules() {
                    const keyword = scheduleSearch ?
                         scheduleSearch.value.trim().toLowerCase() :
                         '';

                    let visibleRows = 0;

                    scheduleRows.forEach(function(row) {
                         const rowStatus = row.dataset.hrStatus || '';
                         const rowKeyword = row.dataset.hrKeyword || '';

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
                         activeStatus = button.dataset.hrFilter || 'semua';

                         filterHrSchedules();
                    });
               });

               if (scheduleSearch) {
                    scheduleSearch.addEventListener('input', filterHrSchedules);
               }

               const markReadButton = document.getElementById('markHrRead');
               const activityItems = document.querySelectorAll('[data-hr-activity]');

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
