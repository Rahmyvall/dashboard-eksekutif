
extends('layouts.app')

@section('title', 'Dashboard Keuangan')

@section('content')
     @php
          /*
        |--------------------------------------------------------------------------
        | DATA DASHBOARD KEUANGAN
        |--------------------------------------------------------------------------
        | Data contoh berikut dapat dipindahkan ke controller ketika modul
        | keuangan sudah terhubung dengan database aplikasi.
        */

          $statistics = [
              [
                  'label' => 'Pendapatan Bulan Ini',
                  'value' => 'Rp1,28 M',
                  'suffix' => '',
                  'icon' => 'trending-up',
                  'description' => 'Realisasi 96,4% dari target Rp1,33 miliar',
                  'trend' => '+8,6%',
                  'trend_type' => 'up',
                  'theme' => 'green',
              ],
              [
                  'label' => 'Realisasi Anggaran',
                  'value' => '78,5',
                  'suffix' => '%',
                  'icon' => 'pie-chart',
                  'description' => 'Rp942 juta dari pagu Rp1,20 miliar',
                  'trend' => '+4,2%',
                  'trend_type' => 'up',
                  'theme' => 'blue',
              ],
              [
                  'label' => 'Piutang Pelanggan',
                  'value' => 'Rp186 Jt',
                  'suffix' => '',
                  'icon' => 'file-text',
                  'description' => 'Rp42 juta telah melewati jatuh tempo',
                  'trend' => '22,6%',
                  'trend_type' => 'down',
                  'theme' => 'orange',
              ],
              [
                  'label' => 'Refund Pelanggan',
                  'value' => '14',
                  'suffix' => '',
                  'icon' => 'refresh-cw',
                  'description' => '5 pengajuan menunggu verifikasi keuangan',
                  'trend' => '+3',
                  'trend_type' => 'down',
                  'theme' => 'red',
              ],
          ];

          $weeklyCashFlow = [
              ['period' => 'Sen', 'full_period' => 'Senin', 'target' => 88, 'actual' => 82],
              ['period' => 'Sel', 'full_period' => 'Selasa', 'target' => 92, 'actual' => 90],
              ['period' => 'Rab', 'full_period' => 'Rabu', 'target' => 96, 'actual' => 93],
              ['period' => 'Kam', 'full_period' => 'Kamis', 'target' => 90, 'actual' => 86],
              ['period' => 'Jum', 'full_period' => 'Jumat', 'target' => 98, 'actual' => 95],
              ['period' => 'Sab', 'full_period' => 'Sabtu', 'target' => 74, 'actual' => 68],
              ['period' => 'Min', 'full_period' => 'Minggu', 'target' => 62, 'actual' => 58],
          ];

          $cashFlowTargetTotal = array_sum(array_column($weeklyCashFlow, 'target'));
          $cashFlowActualTotal = array_sum(array_column($weeklyCashFlow, 'actual'));
          $cashFlowAchievement =
              $cashFlowTargetTotal > 0 ? round(($cashFlowActualTotal / $cashFlowTargetTotal) * 100, 1) : 0;

          $financePriorities = [
              [
                  'title' => 'Piutang melewati jatuh tempo',
                  'description' =>
                      'Tujuh invoice pelanggan senilai Rp42 juta membutuhkan penagihan dan konfirmasi pembayaran.',
                  'icon' => 'alert-triangle',
                  'status' => 'Mendesak',
                  'status_class' => 'danger',
                  'action' => 'Tinjau piutang',
              ],
              [
                  'title' => 'Realisasi anggaran melebihi batas',
                  'description' =>
                      'Belanja operasional Unit Layanan telah mencapai 93% sebelum akhir periode anggaran.',
                  'icon' => 'pie-chart',
                  'status' => 'Perhatian',
                  'status_class' => 'warning',
                  'action' => 'Periksa anggaran',
              ],
              [
                  'title' => 'Refund pelanggan belum diproses',
                  'description' =>
                      'Lima pengajuan refund telah lolos validasi layanan dan menunggu persetujuan pembayaran.',
                  'icon' => 'refresh-cw',
                  'status' => 'Berjalan',
                  'status_class' => 'info',
                  'action' => 'Proses refund',
              ],
              [
                  'title' => 'Rekonsiliasi bank belum selesai',
                  'description' =>
                      'Tiga transaksi masuk belum memiliki referensi pelanggan yang dapat dipadankan otomatis.',
                  'icon' => 'repeat',
                  'status' => 'Menunggu',
                  'status_class' => 'neutral',
                  'action' => 'Rekonsiliasi data',
              ],
          ];

          $financeTasks = [
              [
                  'code' => 'FIN-2601',
                  'agenda' => 'Rekonsiliasi penerimaan pelanggan',
                  'category' => 'Rekonsiliasi',
                  'department' => 'Keuangan',
                  'pic' => 'Nadia Putri',
                  'time' => '25 Jul 2026 · 09.30',
                  'location' => 'Bank BCA Operasional',
                  'progress' => 100,
                  'status' => 'Selesai',
                  'priority' => 'Normal',
              ],
              [
                  'code' => 'FIN-2602',
                  'agenda' => 'Penagihan invoice pelanggan korporat',
                  'category' => 'Piutang',
                  'department' => 'Penjualan',
                  'pic' => 'Rina Maharani',
                  'time' => '25 Jul 2026 · 11.00',
                  'location' => 'Transfer dan virtual account',
                  'progress' => 65,
                  'status' => 'Berjalan',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'FIN-2603',
                  'agenda' => 'Verifikasi pengeluaran operasional',
                  'category' => 'Pengeluaran',
                  'department' => 'Operasional',
                  'pic' => 'Dian Permata',
                  'time' => '25 Jul 2026 · 13.00',
                  'location' => 'Kas dan rekening operasional',
                  'progress' => 45,
                  'status' => 'Berjalan',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'FIN-2604',
                  'agenda' => 'Pembayaran refund pelanggan',
                  'category' => 'Refund',
                  'department' => 'Layanan Pelanggan',
                  'pic' => 'Bagus Pratama',
                  'time' => '26 Jul 2026 · 10.00',
                  'location' => 'Rekening tujuan pelanggan',
                  'progress' => 20,
                  'status' => 'Terjadwal',
                  'priority' => 'Tinggi',
              ],
              [
                  'code' => 'FIN-2605',
                  'agenda' => 'Penyusunan laporan realisasi anggaran',
                  'category' => 'Pelaporan',
                  'department' => 'Manajemen',
                  'pic' => 'Arif Setiawan',
                  'time' => '28 Jul 2026 · 15.00',
                  'location' => 'Dashboard eksekutif',
                  'progress' => 0,
                  'status' => 'Menunggu',
                  'priority' => 'Normal',
              ],
          ];

          $budgetRealizations = [
              [
                  'name' => 'Operasional',
                  'budget' => 420000000,
                  'spent' => 348600000,
                  'realization' => 83,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Layanan Pelanggan',
                  'budget' => 180000000,
                  'spent' => 167400000,
                  'realization' => 93,
                  'status' => 'Tinggi',
              ],
              [
                  'name' => 'Penjualan & Pemasaran',
                  'budget' => 260000000,
                  'spent' => 192400000,
                  'realization' => 74,
                  'status' => 'Normal',
              ],
              [
                  'name' => 'Quality Assurance',
                  'budget' => 145000000,
                  'spent' => 98600000,
                  'realization' => 68,
                  'status' => 'Aman',
              ],
              [
                  'name' => 'Teknologi Informasi',
                  'budget' => 195000000,
                  'spent' => 134550000,
                  'realization' => 69,
                  'status' => 'Aman',
              ],
          ];

          $financeActivities = [
              [
                  'title' => 'Pembayaran pelanggan terverifikasi',
                  'description' => 'Pembayaran invoice INV-2026-0718 senilai Rp28,5 juta telah dipadankan.',
                  'time' => '12 menit lalu',
                  'icon' => 'check-circle',
                  'theme' => 'green',
              ],
              [
                  'title' => 'Anggaran unit diperbarui',
                  'description' => 'Realisasi anggaran Layanan Pelanggan bertambah Rp7,2 juta.',
                  'time' => '26 menit lalu',
                  'icon' => 'pie-chart',
                  'theme' => 'blue',
              ],
              [
                  'title' => 'Invoice melewati jatuh tempo',
                  'description' => 'Sistem menandai dua invoice pelanggan yang belum dibayar selama 14 hari.',
                  'time' => '43 menit lalu',
                  'icon' => 'alert-triangle',
                  'theme' => 'red',
              ],
              [
                  'title' => 'Refund pelanggan disetujui',
                  'description' => 'Pengajuan refund RF-260724-03 telah disetujui untuk tahap pembayaran.',
                  'time' => '1 jam lalu',
                  'icon' => 'refresh-cw',
                  'theme' => 'orange',
              ],
              [
                  'title' => 'Laporan arus kas diperbarui',
                  'description' => 'Ringkasan penerimaan dan pengeluaran harian tersedia untuk manajemen.',
                  'time' => '2 jam lalu',
                  'icon' => 'bar-chart-2',
                  'theme' => 'purple',
              ],
          ];

          $financeHealth = [
              'score' => 89,
              'angle' => round(89 * 3.6),
              'items' => [
                  ['label' => 'Pencapaian penerimaan', 'value' => 96, 'class' => 'success'],
                  ['label' => 'Efisiensi penggunaan anggaran', 'value' => 87, 'class' => 'info'],
                  ['label' => 'Ketepatan rekonsiliasi', 'value' => 94, 'class' => 'success'],
                  ['label' => 'SLA penyelesaian refund', 'value' => 82, 'class' => 'warning'],
              ],
          ];

          $currentUserName = auth()->user()->name ?? 'Keuangan';
     @endphp

     <style>
          /*
                       |--------------------------------------------------------------------------
                       | FINANCE DASHBOARD VARIABLES
                       |--------------------------------------------------------------------------
                       */

          .finance-dashboard {
               --finance-primary: #047857;
               --finance-primary-dark: #065f46;
               --finance-primary-soft: rgba(4, 120, 87, 0.12);
               --finance-secondary: #0f766e;
               --finance-success: #16a34a;
               --finance-warning: #d97706;
               --finance-danger: #dc2626;
               --finance-info: #2563eb;
               --finance-purple: #7c3aed;
               --finance-heading: #172033;
               --finance-text: #5f6b7a;
               --finance-muted: #8b95a5;
               --finance-border: #e9edf3;
               --finance-background: #f4f6fa;
               --finance-card: #ffffff;
               --finance-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
               --finance-shadow-hover: 0 18px 40px rgba(15, 23, 42, 0.11);

               width: 100%;
               min-height: 100vh;
               padding: 24px;
               background:
                    radial-gradient(circle at top right,
                         rgba(4, 120, 87, 0.07),
                         transparent 26%),
                    var(--finance-background);
               color: var(--finance-text);
          }

          html[data-theme="dark"] .finance-dashboard,
          body.dark-theme .finance-dashboard,
          body.dark-mode .finance-dashboard {
               --finance-heading: #f8fafc;
               --finance-text: #cbd5e1;
               --finance-muted: #94a3b8;
               --finance-border: rgba(148, 163, 184, 0.16);
               --finance-background: #0f172a;
               --finance-card: #182235;
               --finance-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
               --finance-shadow-hover: 0 18px 40px rgba(0, 0, 0, 0.28);
          }

          .finance-dashboard *,
          .finance-dashboard *::before,
          .finance-dashboard *::after {
               box-sizing: border-box;
          }

          /*
                       |--------------------------------------------------------------------------
                       | HEADER
                       |--------------------------------------------------------------------------
                       */

          .finance-hero {
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
                         rgba(6, 78, 59, 0.98),
                         rgba(4, 120, 87, 0.96) 52%,
                         rgba(15, 118, 110, 0.92));
               box-shadow: 0 22px 48px rgba(4, 120, 87, 0.24);
          }

          .finance-hero::before {
               position: absolute;
               top: -120px;
               right: -80px;
               width: 360px;
               height: 360px;
               border: 70px solid rgba(255, 255, 255, 0.07);
               border-radius: 50%;
               content: "";
          }

          .finance-hero::after {
               position: absolute;
               right: 210px;
               bottom: -130px;
               width: 270px;
               height: 270px;
               border-radius: 50%;
               background: rgba(255, 255, 255, 0.06);
               content: "";
          }

          .finance-hero-content {
               position: relative;
               z-index: 2;
               max-width: 720px;
          }

          .finance-role-badge {
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

          .finance-role-badge .badge-indicator {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #86efac;
               box-shadow: 0 0 0 5px rgba(134, 239, 172, 0.18);
          }

          .finance-hero h1 {
               margin: 0 0 12px;
               color: #ffffff;
               font-size: clamp(28px, 4vw, 42px);
               font-weight: 800;
               letter-spacing: -0.035em;
               line-height: 1.12;
          }

          .finance-hero-description {
               max-width: 650px;
               margin: 0;
               color: rgba(255, 255, 255, 0.83);
               font-size: 15px;
               line-height: 1.75;
          }

          .finance-hero-meta {
               display: flex;
               flex-wrap: wrap;
               gap: 10px 18px;
               margin-top: 20px;
          }

          .finance-hero-meta-item {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               color: rgba(255, 255, 255, 0.86);
               font-size: 12px;
               font-weight: 600;
          }

          .finance-hero-meta-item svg {
               width: 15px;
               height: 15px;
          }

          .finance-hero-actions {
               position: relative;
               z-index: 2;
               display: flex;
               flex-shrink: 0;
               flex-direction: column;
               gap: 11px;
               width: 205px;
          }

          .finance-button {
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

          .finance-button:hover {
               transform: translateY(-2px);
          }

          .finance-button svg {
               width: 16px;
               height: 16px;
          }

          .finance-button-primary {
               background: #ffffff;
               color: var(--finance-primary-dark);
               box-shadow: 0 10px 22px rgba(6, 95, 70, 0.2);
          }

          .finance-button-secondary {
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

          .finance-stat-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 18px;
               margin-bottom: 24px;
          }

          .finance-main-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.7fr) minmax(320px, 0.8fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .finance-secondary-grid {
               display: grid;
               grid-template-columns: minmax(320px, 0.78fr) minmax(0, 1.45fr);
               gap: 22px;
               margin-bottom: 24px;
          }

          .finance-bottom-grid {
               display: grid;
               grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.7fr);
               gap: 22px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | STATISTIC CARDS
                       |--------------------------------------------------------------------------
                       */

          .finance-stat-card {
               position: relative;
               overflow: hidden;
               min-height: 170px;
               padding: 21px;
               border: 1px solid var(--finance-border);
               border-radius: 18px;
               background: var(--finance-card);
               box-shadow: var(--finance-shadow);
               transition:
                    transform 0.25s ease,
                    box-shadow 0.25s ease,
                    border-color 0.25s ease;
          }

          .finance-stat-card:hover {
               transform: translateY(-4px);
               border-color: rgba(4, 120, 87, 0.25);
               box-shadow: var(--finance-shadow-hover);
          }

          .finance-stat-card::after {
               position: absolute;
               top: -28px;
               right: -28px;
               width: 95px;
               height: 95px;
               border-radius: 50%;
               content: "";
               opacity: 0.7;
          }

          .finance-stat-card.theme-orange::after {
               background: rgba(217, 119, 6, 0.08);
          }

          .finance-stat-card.theme-green::after {
               background: rgba(22, 163, 74, 0.08);
          }

          .finance-stat-card.theme-red::after {
               background: rgba(220, 38, 38, 0.08);
          }

          .finance-stat-card.theme-blue::after {
               background: rgba(37, 99, 235, 0.08);
          }

          .finance-stat-top {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 16px;
          }

          .finance-stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               height: 46px;
               border-radius: 14px;
          }

          .finance-stat-icon svg {
               width: 21px;
               height: 21px;
          }

          .theme-orange .finance-stat-icon {
               background: rgba(217, 119, 6, 0.12);
               color: #d97706;
          }

          .theme-green .finance-stat-icon {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .theme-red .finance-stat-icon {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .theme-blue .finance-stat-icon {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .finance-stat-trend {
               display: inline-flex;
               align-items: center;
               gap: 4px;
               padding: 5px 8px;
               border-radius: 999px;
               font-size: 11px;
               font-weight: 800;
          }

          .finance-stat-trend svg {
               width: 12px;
               height: 12px;
          }

          .finance-stat-trend.up {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .finance-stat-trend.down {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .finance-stat-label {
               margin: 18px 0 7px;
               color: var(--finance-muted);
               font-size: 12px;
               font-weight: 700;
          }

          .finance-stat-value {
               display: flex;
               align-items: baseline;
               gap: 2px;
               margin: 0;
               color: var(--finance-heading);
               font-size: 29px;
               font-weight: 800;
               letter-spacing: -0.03em;
               line-height: 1;
          }

          .finance-stat-description {
               margin: 10px 0 0;
               color: var(--finance-text);
               font-size: 11px;
               line-height: 1.5;
          }

          /*
                       |--------------------------------------------------------------------------
                       | COMMON CARD
                       |--------------------------------------------------------------------------
                       */

          .finance-card {
               border: 1px solid var(--finance-border);
               border-radius: 20px;
               background: var(--finance-card);
               box-shadow: var(--finance-shadow);
          }

          .finance-card-header {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               padding: 22px 23px 17px;
               border-bottom: 1px solid var(--finance-border);
          }

          .finance-card-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .finance-card-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 39px;
               height: 39px;
               border-radius: 12px;
               background: var(--finance-primary-soft);
               color: var(--finance-primary);
          }

          .finance-card-heading-icon svg {
               width: 18px;
               height: 18px;
          }

          .finance-card-title {
               margin: 0;
               color: var(--finance-heading);
               font-size: 16px;
               font-weight: 800;
               letter-spacing: -0.015em;
          }

          .finance-card-subtitle {
               margin: 5px 0 0;
               color: var(--finance-muted);
               font-size: 11px;
               line-height: 1.55;
          }

          .finance-card-action {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 8px 10px;
               border: 1px solid var(--finance-border);
               border-radius: 10px;
               background: transparent;
               color: var(--finance-text);
               font-size: 11px;
               font-weight: 700;
               cursor: pointer;
          }

          .finance-card-action svg {
               width: 14px;
               height: 14px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | CHART
                       |--------------------------------------------------------------------------
                       */

          .finance-chart-body {
               padding: 22px 23px 24px;
          }

          .finance-chart-summary {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 22px;
          }

          .finance-chart-legends {
               display: flex;
               flex-wrap: wrap;
               gap: 16px;
          }

          .finance-chart-legend {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               color: var(--finance-text);
               font-size: 11px;
               font-weight: 700;
          }

          .finance-chart-legend-dot {
               width: 9px;
               height: 9px;
               border-radius: 3px;
          }

          .finance-chart-legend-dot.scheduled {
               background: rgba(4, 120, 87, 0.26);
          }

          .finance-chart-legend-dot.completed {
               background: var(--finance-primary);
          }

          .finance-chart-rate {
               text-align: right;
          }

          .finance-chart-rate strong {
               display: block;
               color: var(--finance-heading);
               font-size: 19px;
               font-weight: 800;
          }

          .finance-chart-rate span {
               color: var(--finance-muted);
               font-size: 10px;
          }

          .finance-chart-area {
               position: relative;
               display: grid;
               grid-template-columns: 34px minmax(0, 1fr);
               gap: 11px;
               height: 285px;
          }

          .finance-chart-y-axis {
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               height: 245px;
               padding-top: 2px;
               color: var(--finance-muted);
               font-size: 9px;
               text-align: right;
          }

          .finance-chart-content {
               position: relative;
               height: 285px;
          }

          .finance-chart-lines {
               position: absolute;
               inset: 0 0 40px;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               pointer-events: none;
          }

          .finance-chart-line {
               width: 100%;
               border-top: 1px dashed var(--finance-border);
          }

          .finance-chart-columns {
               position: absolute;
               inset: 0 0 0;
               display: grid;
               grid-template-columns: repeat(7, minmax(30px, 1fr));
               gap: 12px;
          }

          .finance-chart-column {
               display: flex;
               flex-direction: column;
               align-items: center;
               min-width: 0;
          }

          .finance-chart-bars {
               display: flex;
               align-items: flex-end;
               justify-content: center;
               gap: 5px;
               width: 100%;
               height: 245px;
          }

          .finance-chart-bar {
               position: relative;
               width: min(16px, 38%);
               min-height: 4px;
               border-radius: 6px 6px 3px 3px;
               cursor: pointer;
               transition:
                    filter 0.2s ease,
                    transform 0.2s ease;
          }

          .finance-chart-bar:hover {
               z-index: 3;
               filter: brightness(0.93);
               transform: scaleX(1.08);
          }

          .finance-chart-bar.scheduled {
               background: rgba(4, 120, 87, 0.26);
          }

          .finance-chart-bar.completed {
               background: linear-gradient(to top, #065f46, #34d399);
               box-shadow: 0 5px 12px rgba(4, 120, 87, 0.2);
          }

          .finance-chart-tooltip {
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

          .finance-chart-bar:hover .finance-chart-tooltip {
               opacity: 1;
               transform: translateX(-50%) translateY(0);
          }

          .finance-chart-day {
               margin-top: 13px;
               color: var(--finance-muted);
               font-size: 10px;
               font-weight: 700;
          }

          /*
                       |--------------------------------------------------------------------------
                       | FINANCIAL HEALTH
                       |--------------------------------------------------------------------------
                       */

          .finance-health-body {
               padding: 24px;
          }

          .finance-utilization {
               display: flex;
               align-items: center;
               gap: 22px;
               margin-bottom: 25px;
               padding-bottom: 24px;
               border-bottom: 1px solid var(--finance-border);
          }

          .finance-utilization-ring {
               position: relative;
               display: grid;
               flex-shrink: 0;
               width: 118px;
               height: 118px;
               place-items: center;
               border-radius: 50%;
               background:
                    conic-gradient(var(--finance-primary) 0deg 313deg,
                         var(--finance-border) 313deg 360deg);
          }

          .finance-utilization-ring::before {
               position: absolute;
               width: 88px;
               height: 88px;
               border-radius: 50%;
               background: var(--finance-card);
               content: "";
          }

          .finance-utilization-value {
               position: relative;
               z-index: 2;
               color: var(--finance-heading);
               font-size: 24px;
               font-weight: 800;
          }

          .finance-utilization-details h3 {
               margin: 0 0 6px;
               color: var(--finance-heading);
               font-size: 14px;
               font-weight: 800;
          }

          .finance-utilization-details p {
               margin: 0 0 11px;
               color: var(--finance-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .finance-utilization-status {
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

          .finance-utilization-status::before {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: #22c55e;
               content: "";
          }

          .finance-health-list {
               display: flex;
               flex-direction: column;
               gap: 17px;
          }

          .finance-health-item-top {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 8px;
          }

          .finance-health-item-label {
               color: var(--finance-text);
               font-size: 11px;
               font-weight: 700;
          }

          .finance-health-item-value {
               color: var(--finance-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .finance-progress {
               overflow: hidden;
               width: 100%;
               height: 7px;
               border-radius: 999px;
               background: var(--finance-border);
          }

          .finance-progress-bar {
               height: 100%;
               border-radius: inherit;
               background: linear-gradient(90deg, var(--finance-primary-dark), #34d399);
          }

          .finance-progress-bar.success {
               background: linear-gradient(90deg, #15803d, #4ade80);
          }

          .finance-progress-bar.info {
               background: linear-gradient(90deg, #1d4ed8, #60a5fa);
          }

          .finance-progress-bar.warning {
               background: linear-gradient(90deg, #b45309, #fbbf24);
          }

          /*
                       |--------------------------------------------------------------------------
                       | PRIORITY
                       |--------------------------------------------------------------------------
                       */

          .finance-priority-list {
               display: flex;
               flex-direction: column;
          }

          .finance-priority-item {
               display: flex;
               gap: 13px;
               padding: 17px 21px;
               border-bottom: 1px solid var(--finance-border);
               transition: background 0.2s ease;
          }

          .finance-priority-item:last-child {
               border-bottom: 0;
          }

          .finance-priority-item:hover {
               background: rgba(4, 120, 87, 0.035);
          }

          .finance-priority-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 38px;
               height: 38px;
               border-radius: 11px;
               background: var(--finance-primary-soft);
               color: var(--finance-primary);
          }

          .finance-priority-icon svg {
               width: 17px;
               height: 17px;
          }

          .finance-priority-content {
               min-width: 0;
               flex: 1;
          }

          .finance-priority-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 10px;
          }

          .finance-priority-title {
               margin: 1px 0 0;
               color: var(--finance-heading);
               font-size: 12px;
               font-weight: 800;
          }

          .finance-priority-description {
               margin: 6px 0 11px;
               color: var(--finance-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .finance-priority-action {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               border: 0;
               background: transparent;
               color: var(--finance-primary);
               font-size: 10px;
               font-weight: 800;
               cursor: pointer;
          }

          .finance-priority-action svg {
               width: 12px;
               height: 12px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | BADGES
                       |--------------------------------------------------------------------------
                       */

          .finance-badge {
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

          .finance-badge::before {
               width: 5px;
               height: 5px;
               border-radius: 50%;
               background: currentColor;
               content: "";
          }

          .finance-badge.success {
               background: rgba(22, 163, 74, 0.1);
               color: #15803d;
          }

          .finance-badge.info {
               background: rgba(37, 99, 235, 0.1);
               color: #2563eb;
          }

          .finance-badge.warning {
               background: rgba(217, 119, 6, 0.1);
               color: #b45309;
          }

          .finance-badge.danger {
               background: rgba(220, 38, 38, 0.1);
               color: #dc2626;
          }

          .finance-badge.neutral {
               background: rgba(100, 116, 139, 0.12);
               color: #64748b;
          }

          /*
                       |--------------------------------------------------------------------------
                       | TABLE
                       |--------------------------------------------------------------------------
                       */

          .finance-schedule-card {
               overflow: hidden;
          }

          .finance-schedule-toolbar {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               padding: 14px 20px;
               border-bottom: 1px solid var(--finance-border);
          }

          .finance-filter-list {
               display: flex;
               flex-wrap: wrap;
               gap: 7px;
          }

          .finance-filter-button {
               padding: 7px 10px;
               border: 1px solid var(--finance-border);
               border-radius: 8px;
               background: transparent;
               color: var(--finance-muted);
               font-size: 9px;
               font-weight: 800;
               cursor: pointer;
               transition:
                    background 0.2s ease,
                    color 0.2s ease,
                    border-color 0.2s ease;
          }

          .finance-filter-button:hover,
          .finance-filter-button.active {
               border-color: var(--finance-primary);
               background: var(--finance-primary);
               color: #ffffff;
          }

          .finance-search {
               position: relative;
               width: 195px;
          }

          .finance-search svg {
               position: absolute;
               top: 50%;
               left: 11px;
               width: 14px;
               height: 14px;
               color: var(--finance-muted);
               transform: translateY(-50%);
          }

          .finance-search input {
               width: 100%;
               height: 35px;
               padding: 7px 11px 7px 33px;
               border: 1px solid var(--finance-border);
               border-radius: 9px;
               outline: none;
               background: transparent;
               color: var(--finance-heading);
               font-size: 10px;
          }

          .finance-search input:focus {
               border-color: var(--finance-primary);
               box-shadow: 0 0 0 3px var(--finance-primary-soft);
          }

          .finance-table-wrapper {
               overflow-x: auto;
          }

          .finance-table {
               width: 100%;
               min-width: 840px;
               border-collapse: collapse;
          }

          .finance-table th {
               padding: 12px 16px;
               border-bottom: 1px solid var(--finance-border);
               background: rgba(148, 163, 184, 0.045);
               color: var(--finance-muted);
               font-size: 9px;
               font-weight: 800;
               letter-spacing: 0.04em;
               text-align: left;
               text-transform: uppercase;
          }

          .finance-table td {
               padding: 15px 16px;
               border-bottom: 1px solid var(--finance-border);
               color: var(--finance-text);
               font-size: 10px;
               vertical-align: middle;
          }

          .finance-table tbody tr {
               transition: background 0.2s ease;
          }

          .finance-table tbody tr:hover {
               background: rgba(4, 120, 87, 0.028);
          }

          .finance-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .finance-task {
               display: flex;
               align-items: center;
               gap: 11px;
               min-width: 215px;
          }

          .finance-task-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 35px;
               height: 35px;
               border-radius: 10px;
               background: var(--finance-primary-soft);
               color: var(--finance-primary);
          }

          .finance-task-icon svg {
               width: 15px;
               height: 15px;
          }

          .finance-task-title {
               display: block;
               margin-bottom: 3px;
               color: var(--finance-heading);
               font-size: 10px;
               font-weight: 800;
          }

          .finance-task-code {
               color: var(--finance-muted);
               font-size: 9px;
          }

          .finance-team-name {
               display: block;
               color: var(--finance-heading);
               font-weight: 700;
          }

          .finance-team-leader {
               display: block;
               margin-top: 3px;
               color: var(--finance-muted);
               font-size: 9px;
          }

          .finance-time {
               color: var(--finance-heading);
               font-weight: 700;
          }

          .finance-location {
               display: block;
               margin-top: 3px;
               color: var(--finance-muted);
               font-size: 9px;
          }

          .finance-table-progress {
               min-width: 105px;
          }

          .finance-table-progress-top {
               display: flex;
               justify-content: space-between;
               margin-bottom: 6px;
               color: var(--finance-muted);
               font-size: 9px;
          }

          .finance-table-progress .finance-progress {
               height: 6px;
          }

          .finance-action-menu {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 31px;
               height: 31px;
               border: 1px solid var(--finance-border);
               border-radius: 8px;
               background: transparent;
               color: var(--finance-muted);
               cursor: pointer;
          }

          .finance-action-menu svg {
               width: 15px;
               height: 15px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | BUDGET REALIZATION
                       |--------------------------------------------------------------------------
                       */

          .finance-team-list {
               padding: 4px 21px 9px;
          }

          .finance-team-item {
               padding: 17px 0;
               border-bottom: 1px solid var(--finance-border);
          }

          .finance-team-item:last-child {
               border-bottom: 0;
          }

          .finance-team-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 10px;
          }

          .finance-team-header h4 {
               margin: 0;
               color: var(--finance-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .finance-team-metadata {
               display: flex;
               gap: 14px;
               margin-top: 4px;
               color: var(--finance-muted);
               font-size: 9px;
          }

          .finance-team-percentage {
               color: var(--finance-heading);
               font-size: 12px;
               font-weight: 800;
          }

          /*
                       |--------------------------------------------------------------------------
                       | ACTIVITY
                       |--------------------------------------------------------------------------
                       */

          .finance-activity-list {
               padding: 4px 21px 10px;
          }

          .finance-activity-item {
               position: relative;
               display: flex;
               gap: 13px;
               padding: 17px 0;
          }

          .finance-activity-item:not(:last-child)::before {
               position: absolute;
               top: 52px;
               bottom: -3px;
               left: 18px;
               width: 1px;
               background: var(--finance-border);
               content: "";
          }

          .finance-activity-icon {
               position: relative;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex-shrink: 0;
               width: 37px;
               height: 37px;
               border: 4px solid var(--finance-card);
               border-radius: 50%;
          }

          .finance-activity-icon svg {
               width: 14px;
               height: 14px;
          }

          .finance-activity-icon.green {
               background: rgba(22, 163, 74, 0.12);
               color: #16a34a;
          }

          .finance-activity-icon.blue {
               background: rgba(37, 99, 235, 0.12);
               color: #2563eb;
          }

          .finance-activity-icon.red {
               background: rgba(220, 38, 38, 0.12);
               color: #dc2626;
          }

          .finance-activity-icon.orange {
               background: rgba(217, 119, 6, 0.12);
               color: #d97706;
          }

          .finance-activity-icon.purple {
               background: rgba(124, 58, 237, 0.12);
               color: #7c3aed;
          }

          .finance-activity-content {
               min-width: 0;
               flex: 1;
          }

          .finance-activity-content h4 {
               margin: 1px 0 5px;
               color: var(--finance-heading);
               font-size: 11px;
               font-weight: 800;
          }

          .finance-activity-content p {
               margin: 0;
               color: var(--finance-muted);
               font-size: 10px;
               line-height: 1.55;
          }

          .finance-activity-time {
               margin-top: 6px;
               color: var(--finance-primary);
               font-size: 9px;
               font-weight: 700;
          }

          /*
                       |--------------------------------------------------------------------------
                       | EMPTY STATE
                       |--------------------------------------------------------------------------
                       */

          .finance-empty-state {
               display: none;
               padding: 38px 20px;
               text-align: center;
          }

          .finance-empty-state svg {
               width: 34px;
               height: 34px;
               margin-bottom: 10px;
               color: var(--finance-muted);
          }

          .finance-empty-state h4 {
               margin: 0 0 5px;
               color: var(--finance-heading);
               font-size: 13px;
          }

          .finance-empty-state p {
               margin: 0;
               color: var(--finance-muted);
               font-size: 10px;
          }

          /*
                       |--------------------------------------------------------------------------
                       | RESPONSIVE
                       |--------------------------------------------------------------------------
                       */

          @media (max-width: 1280px) {
               .finance-stat-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .finance-main-grid,
               .finance-secondary-grid,
               .finance-bottom-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 768px) {
               .finance-dashboard {
                    padding: 15px;
               }

               .finance-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    min-height: auto;
                    padding: 26px 23px;
                    border-radius: 19px;
               }

               .finance-hero-actions {
                    flex-direction: row;
                    width: 100%;
               }

               .finance-hero-actions .finance-button {
                    flex: 1;
               }

               .finance-stat-grid {
                    grid-template-columns: 1fr;
                    gap: 13px;
               }

               .finance-stat-card {
                    min-height: auto;
               }

               .finance-card-header,
               .finance-schedule-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .finance-search {
                    width: 100%;
               }

               .finance-chart-area {
                    grid-template-columns: 26px minmax(0, 1fr);
               }

               .finance-chart-columns {
                    gap: 5px;
               }

               .finance-chart-bars {
                    gap: 3px;
               }

               .finance-utilization {
                    align-items: flex-start;
               }
          }

          @media (max-width: 520px) {
               .finance-hero-actions {
                    flex-direction: column;
               }

               .finance-chart-summary {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .finance-chart-rate {
                    text-align: left;
               }

               .finance-chart-column:nth-child(even) .finance-chart-day {
                    opacity: 0.55;
               }

               .finance-utilization {
                    align-items: center;
                    flex-direction: column;
                    text-align: center;
               }
          }
     </style>

     <div class="finance-dashboard">

          {{-- ================================================================
            HEADER KEUANGAN
        ================================================================= --}}

          <section class="finance-hero">
               <div class="finance-hero-content">
                    <div class="finance-role-badge">
                         <span class="badge-indicator"></span>
                         Finance & Accounting Department
                    </div>

                    <h1>Financial Performance Control Center</h1>

                    <p class="finance-hero-description">
                         Selamat datang, {{ $currentUserName }}. Pantau pendapatan, anggaran, piutang,
                         pembayaran, refund pelanggan, rekonsiliasi, dan kesehatan arus kas
                         untuk mendukung kinerja serta kualitas layanan secara terintegrasi.
                    </p>

                    <div class="finance-hero-meta">
                         <span class="finance-hero-meta-item">
                              <i data-feather="calendar"></i>
                              {{ now()->translatedFormat('l, d F Y') }}
                         </span>

                         <span class="finance-hero-meta-item">
                              <i data-feather="database"></i>
                              Data transaksi dan anggaran aktif
                         </span>

                         <span class="finance-hero-meta-item">
                              <i data-feather="shield"></i>
                              Kontrol keuangan terlindungi
                         </span>
                    </div>
               </div>

               <div class="finance-hero-actions">
                    <button type="button" class="finance-button finance-button-primary">
                         <i data-feather="plus-circle"></i>
                         Catat Transaksi
                    </button>

                    <button type="button" class="finance-button finance-button-secondary">
                         <i data-feather="download"></i>
                         Unduh Laporan Keuangan
                    </button>
               </div>
          </section>

          {{-- ================================================================
            RINGKASAN KEUANGAN
        ================================================================= --}}

          <section class="finance-stat-grid">
               @foreach ($statistics as $statistic)
                    <article class="finance-stat-card theme-{{ $statistic['theme'] }}">
                         <div class="finance-stat-top">
                              <span class="finance-stat-icon">
                                   <i data-feather="{{ $statistic['icon'] }}"></i>
                              </span>

                              <span class="finance-stat-trend {{ $statistic['trend_type'] }}">
                                   <i
                                        data-feather="{{ $statistic['trend_type'] === 'up' ? 'trending-up' : 'alert-circle' }}"></i>
                                   {{ $statistic['trend'] }}
                              </span>
                         </div>

                         <p class="finance-stat-label">{{ $statistic['label'] }}</p>

                         <h2 class="finance-stat-value">
                              <span>{{ $statistic['value'] }}</span>
                              <span>{{ $statistic['suffix'] }}</span>
                         </h2>

                         <p class="finance-stat-description">
                              {{ $statistic['description'] }}
                         </p>
                    </article>
               @endforeach
          </section>

          {{-- ================================================================
            ARUS KAS DAN KESEHATAN KEUANGAN
        ================================================================= --}}

          <section class="finance-main-grid">

               {{-- Grafik penerimaan --}}
               <article class="finance-card">
                    <header class="finance-card-header">
                         <div class="finance-card-heading">
                              <span class="finance-card-heading-icon">
                                   <i data-feather="bar-chart-2"></i>
                              </span>

                              <div>
                                   <h2 class="finance-card-title">Tren Penerimaan Mingguan</h2>
                                   <p class="finance-card-subtitle">
                                        Perbandingan target dan realisasi penerimaan pelanggan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="finance-card-action">
                              Minggu ini
                              <i data-feather="chevron-down"></i>
                         </button>
                    </header>

                    <div class="finance-chart-body">
                         <div class="finance-chart-summary">
                              <div class="finance-chart-legends">
                                   <span class="finance-chart-legend">
                                        <span class="finance-chart-legend-dot scheduled"></span>
                                        Target
                                   </span>

                                   <span class="finance-chart-legend">
                                        <span class="finance-chart-legend-dot completed"></span>
                                        Realisasi
                                   </span>
                              </div>

                              <div class="finance-chart-rate">
                                   <strong>{{ number_format($cashFlowAchievement, 1, ',', '.') }}%</strong>
                                   <span>Penerimaan terhadap target</span>
                              </div>
                         </div>

                         <div class="finance-chart-area">
                              <div class="finance-chart-y-axis">
                                   <span>100</span>
                                   <span>75</span>
                                   <span>50</span>
                                   <span>25</span>
                                   <span>0</span>
                              </div>

                              <div class="finance-chart-content">
                                   <div class="finance-chart-lines">
                                        <span class="finance-chart-line"></span>
                                        <span class="finance-chart-line"></span>
                                        <span class="finance-chart-line"></span>
                                        <span class="finance-chart-line"></span>
                                        <span class="finance-chart-line"></span>
                                   </div>

                                   <div class="finance-chart-columns">
                                        @foreach ($weeklyCashFlow as $cashFlow)
                                             <div class="finance-chart-column">
                                                  <div class="finance-chart-bars">
                                                       <div class="finance-chart-bar scheduled"
                                                            style="height: {{ $cashFlow['target'] }}%;">
                                                            <span class="finance-chart-tooltip">
                                                                 Target {{ $cashFlow['target'] }}%
                                                            </span>
                                                       </div>

                                                       <div class="finance-chart-bar completed"
                                                            style="height: {{ $cashFlow['actual'] }}%;">
                                                            <span class="finance-chart-tooltip">
                                                                 Realisasi {{ $cashFlow['actual'] }}%
                                                            </span>
                                                       </div>
                                                  </div>

                                                  <span class="finance-chart-day" title="{{ $cashFlow['full_period'] }}">
                                                       {{ $cashFlow['period'] }}
                                                  </span>
                                             </div>
                                        @endforeach
                                   </div>
                              </div>
                         </div>
                    </div>
               </article>

               {{-- Kesehatan keuangan --}}
               <article class="finance-card">
                    <header class="finance-card-header">
                         <div class="finance-card-heading">
                              <span class="finance-card-heading-icon">
                                   <i data-feather="heart"></i>
                              </span>

                              <div>
                                   <h2 class="finance-card-title">Kesehatan Keuangan</h2>
                                   <p class="finance-card-subtitle">
                                        Ringkasan likuiditas, efisiensi anggaran, dan ketepatan proses.
                                   </p>
                              </div>
                         </div>
                    </header>

                    <div class="finance-health-body">
                         <div class="finance-utilization">
                              <div class="finance-utilization-ring"
                                   style="background: conic-gradient(
                                var(--finance-primary) 0deg {{ $financeHealth['angle'] }}deg,
                                var(--finance-border) {{ $financeHealth['angle'] }}deg 360deg
                            );">
                                   <span class="finance-utilization-value">
                                        {{ $financeHealth['score'] }}%
                                   </span>
                              </div>

                              <div class="finance-utilization-details">
                                   <h3>Indeks kesehatan keuangan</h3>

                                   <p>
                                        Kinerja penerimaan, pengendalian anggaran, dan ketepatan
                                        proses keuangan berada dalam kategori sehat.
                                   </p>

                                   <span class="finance-utilization-status">
                                        Kondisi baik
                                   </span>
                              </div>
                         </div>

                         <div class="finance-health-list">
                              @foreach ($financeHealth['items'] as $healthItem)
                                   <div>
                                        <div class="finance-health-item-top">
                                             <span class="finance-health-item-label">
                                                  {{ $healthItem['label'] }}
                                             </span>

                                             <span class="finance-health-item-value">
                                                  {{ $healthItem['value'] }}%
                                             </span>
                                        </div>

                                        <div class="finance-progress">
                                             <div class="finance-progress-bar {{ $healthItem['class'] }}"
                                                  style="width: {{ $healthItem['value'] }}%;"></div>
                                        </div>
                                   </div>
                              @endforeach
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
            PRIORITAS DAN PROSES KEUANGAN
        ================================================================= --}}

          <section class="finance-secondary-grid">

               {{-- Prioritas Keuangan --}}
               <article class="finance-card">
                    <header class="finance-card-header">
                         <div class="finance-card-heading">
                              <span class="finance-card-heading-icon">
                                   <i data-feather="alert-octagon"></i>
                              </span>

                              <div>
                                   <h2 class="finance-card-title">Prioritas Keuangan</h2>
                                   <p class="finance-card-subtitle">
                                        Risiko dan proses keuangan yang perlu segera ditindaklanjuti.
                                   </p>
                              </div>
                         </div>

                         <span class="finance-badge danger">
                              {{ count($financePriorities) }} prioritas
                         </span>
                    </header>

                    <div class="finance-priority-list">
                         @foreach ($financePriorities as $priority)
                              <div class="finance-priority-item">
                                   <span class="finance-priority-icon">
                                        <i data-feather="{{ $priority['icon'] }}"></i>
                                   </span>

                                   <div class="finance-priority-content">
                                        <div class="finance-priority-heading">
                                             <h3 class="finance-priority-title">
                                                  {{ $priority['title'] }}
                                             </h3>

                                             <span class="finance-badge {{ $priority['status_class'] }}">
                                                  {{ $priority['status'] }}
                                             </span>
                                        </div>

                                        <p class="finance-priority-description">
                                             {{ $priority['description'] }}
                                        </p>

                                        <button type="button" class="finance-priority-action">
                                             {{ $priority['action'] }}
                                             <i data-feather="arrow-right"></i>
                                        </button>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Proses keuangan --}}
               <article class="finance-card finance-schedule-card">
                    <header class="finance-card-header">
                         <div class="finance-card-heading">
                              <span class="finance-card-heading-icon">
                                   <i data-feather="clipboard"></i>
                              </span>

                              <div>
                                   <h2 class="finance-card-title">Proses Keuangan Aktif</h2>
                                   <p class="finance-card-subtitle">
                                        Monitoring penerimaan, piutang, pengeluaran, refund, dan pelaporan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="finance-card-action">
                              <i data-feather="calendar"></i>
                              Lihat periode
                         </button>
                    </header>

                    <div class="finance-schedule-toolbar">
                         <div class="finance-filter-list">
                              <button type="button" class="finance-filter-button active" data-finance-filter="semua">
                                   Semua
                              </button>

                              <button type="button" class="finance-filter-button" data-finance-filter="berjalan">
                                   Berjalan
                              </button>

                              <button type="button" class="finance-filter-button" data-finance-filter="terjadwal">
                                   Terjadwal
                              </button>

                              <button type="button" class="finance-filter-button" data-finance-filter="menunggu">
                                   Menunggu
                              </button>

                              <button type="button" class="finance-filter-button" data-finance-filter="selesai">
                                   Selesai
                              </button>
                         </div>

                         <label class="finance-search">
                              <i data-feather="search"></i>

                              <input type="search" id="financeTaskSearch"
                                   placeholder="Cari dokumen, unit, kategori, atau PIC..." autocomplete="off">
                         </label>
                    </div>

                    <div class="finance-table-wrapper">
                         <table class="finance-table">
                              <thead>
                                   <tr>
                                        <th>Dokumen / proses</th>
                                        <th>Unit dan PIC</th>
                                        <th>Batas waktu dan kanal</th>
                                        <th>Progres</th>
                                        <th>Status</th>
                                        <th></th>
                                   </tr>
                              </thead>

                              <tbody id="financeTaskBody">
                                   @foreach ($financeTasks as $task)
                                        @php
                                             $taskStatusClass = match ($task['status']) {
                                                 'Selesai' => 'success',
                                                 'Berjalan' => 'info',
                                                 'Terjadwal' => 'neutral',
                                                 'Menunggu' => 'warning',
                                                 'Tertunda' => 'danger',
                                                 default => 'neutral',
                                             };

                                             $taskIcon = match ($task['category']) {
                                                 'Rekonsiliasi' => 'repeat',
                                                 'Piutang' => 'file-text',
                                                 'Pengeluaran' => 'credit-card',
                                                 'Refund' => 'refresh-cw',
                                                 'Pelaporan' => 'bar-chart-2',
                                                 default => 'dollar-sign',
                                             };
                                        @endphp

                                        <tr data-finance-row data-finance-status="{{ strtolower($task['status']) }}"
                                             data-finance-keyword="{{ strtolower(
                                                 $task['agenda'] .
                                                     ' ' .
                                                     $task['code'] .
                                                     ' ' .
                                                     $task['department'] .
                                                     ' ' .
                                                     $task['pic'] .
                                                     ' ' .
                                                     $task['location'] .
                                                     ' ' .
                                                     $task['category'],
                                             ) }}">
                                             <td>
                                                  <div class="finance-task">
                                                       <span class="finance-task-icon">
                                                            <i data-feather="{{ $taskIcon }}"></i>
                                                       </span>

                                                       <span>
                                                            <strong class="finance-task-title">
                                                                 {{ $task['agenda'] }}
                                                            </strong>

                                                            <span class="finance-task-code">
                                                                 {{ $task['code'] }}
                                                                 ·
                                                                 {{ $task['category'] }}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="finance-team-name">
                                                       {{ $task['department'] }}
                                                  </span>

                                                  <span class="finance-team-leader">
                                                       PIC: {{ $task['pic'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <span class="finance-time">
                                                       {{ $task['time'] }}
                                                  </span>

                                                  <span class="finance-location">
                                                       {{ $task['location'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <div class="finance-table-progress">
                                                       <div class="finance-table-progress-top">
                                                            <span>Progres</span>
                                                            <strong>{{ $task['progress'] }}%</strong>
                                                       </div>

                                                       <div class="finance-progress">
                                                            <div class="finance-progress-bar {{ $task['progress'] === 100 ? 'success' : '' }}"
                                                                 style="width: {{ $task['progress'] }}%;"></div>
                                                       </div>
                                                  </div>
                                             </td>

                                             <td>
                                                  <span class="finance-badge {{ $taskStatusClass }}">
                                                       {{ $task['status'] }}
                                                  </span>
                                             </td>

                                             <td>
                                                  <button type="button" class="finance-action-menu"
                                                       aria-label="Pilihan proses {{ $task['agenda'] }}">
                                                       <i data-feather="more-horizontal"></i>
                                                  </button>
                                             </td>
                                        </tr>
                                   @endforeach
                              </tbody>
                         </table>

                         <div class="finance-empty-state" id="financeTaskEmpty">
                              <i data-feather="search"></i>
                              <h4>Proses tidak ditemukan</h4>
                              <p>Gunakan kata kunci atau filter status yang berbeda.</p>
                         </div>
                    </div>
               </article>
          </section>

          {{-- ================================================================
            REALISASI ANGGARAN DAN AKTIVITAS
        ================================================================= --}}

          <section class="finance-bottom-grid">

               {{-- Realisasi anggaran unit --}}
               <article class="finance-card">
                    <header class="finance-card-header">
                         <div class="finance-card-heading">
                              <span class="finance-card-heading-icon">
                                   <i data-feather="layers"></i>
                              </span>

                              <div>
                                   <h2 class="finance-card-title">Realisasi Anggaran per Unit</h2>
                                   <p class="finance-card-subtitle">
                                        Perbandingan penggunaan anggaran dengan pagu setiap unit kerja.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="finance-card-action">
                              Kelola anggaran
                              <i data-feather="arrow-up-right"></i>
                         </button>
                    </header>

                    <div class="finance-team-list">
                         @foreach ($budgetRealizations as $budget)
                              @php
                                   $realizationClass = match (true) {
                                       $budget['realization'] >= 90 => 'warning',
                                       $budget['realization'] >= 75 => 'info',
                                       default => 'success',
                                   };
                              @endphp

                              <div class="finance-team-item">
                                   <div class="finance-team-header">
                                        <div>
                                             <h4>{{ $budget['name'] }}</h4>

                                             <div class="finance-team-metadata">
                                                  <span>Pagu Rp{{ number_format($budget['budget'], 0, ',', '.') }}</span>
                                                  <span>Terpakai Rp{{ number_format($budget['spent'], 0, ',', '.') }}</span>
                                             </div>
                                        </div>

                                        <span class="finance-team-percentage">
                                             {{ $budget['realization'] }}%
                                        </span>
                                   </div>

                                   <div class="finance-progress">
                                        <div class="finance-progress-bar {{ $realizationClass }}"
                                             style="width: {{ $budget['realization'] }}%;"></div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </article>

               {{-- Aktivitas Keuangan --}}
               <article class="finance-card">
                    <header class="finance-card-header">
                         <div class="finance-card-heading">
                              <span class="finance-card-heading-icon">
                                   <i data-feather="bell"></i>
                              </span>

                              <div>
                                   <h2 class="finance-card-title">Aktivitas Keuangan</h2>
                                   <p class="finance-card-subtitle">
                                        Pembaruan terbaru dari transaksi, anggaran, piutang, dan pembayaran pelanggan.
                                   </p>
                              </div>
                         </div>

                         <button type="button" class="finance-card-action" id="markFinanceRead">
                              Tandai dibaca
                         </button>
                    </header>

                    <div class="finance-activity-list" id="financeActivityList">
                         @foreach ($financeActivities as $activity)
                              <div class="finance-activity-item" data-finance-activity>
                                   <span class="finance-activity-icon {{ $activity['theme'] }}">
                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                   </span>

                                   <div class="finance-activity-content">
                                        <h4>{{ $activity['title'] }}</h4>
                                        <p>{{ $activity['description'] }}</p>
                                        <div class="finance-activity-time">{{ $activity['time'] }}</div>
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

               const filterButtons = document.querySelectorAll('[data-finance-filter]');
               const taskRows = document.querySelectorAll('[data-finance-row]');
               const taskSearch = document.getElementById('financeTaskSearch');
               const taskEmpty = document.getElementById('financeTaskEmpty');

               let activeStatus = 'semua';

               function filterFinanceTasks() {
                    const keyword = taskSearch ?
                         taskSearch.value.trim().toLowerCase() :
                         '';

                    let visibleTasks = 0;

                    taskRows.forEach(function(row) {
                         const rowStatus = row.dataset.financeStatus || '';
                         const rowKeyword = row.dataset.financeKeyword || '';

                         const statusMatches =
                              activeStatus === 'semua' ||
                              rowStatus === activeStatus;

                         const keywordMatches =
                              keyword === '' ||
                              rowKeyword.includes(keyword);

                         const shouldShow = statusMatches && keywordMatches;

                         row.style.display = shouldShow ? '' : 'none';

                         if (shouldShow) {
                              visibleTasks++;
                         }
                    });

                    if (taskEmpty) {
                         taskEmpty.style.display =
                              visibleTasks === 0 ? 'block' : 'none';
                    }
               }

               filterButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                         filterButtons.forEach(function(item) {
                              item.classList.remove('active');
                         });

                         button.classList.add('active');
                         activeStatus = button.dataset.financeFilter || 'semua';

                         filterFinanceTasks();
                    });
               });

               if (taskSearch) {
                    taskSearch.addEventListener('input', filterFinanceTasks);
               }

               const markReadButton = document.getElementById('markFinanceRead');
               const activityItems = document.querySelectorAll('[data-finance-activity]');

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