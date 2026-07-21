@extends('layouts.app')

@section('content')
     <style>
          /* =========================================
                           TRANSITION PERUBAHAN TEMA
                        ========================================= */

          body,
          .content,
          .content-header,
          .content-body,
          .modern-card,
          .service-box,
          .activity,
          .card,
          .list-group-item {
               transition:
                    background-color 0.3s ease,
                    color 0.3s ease,
                    border-color 0.3s ease,
                    box-shadow 0.3s ease;
          }

          /* =========================================
                           WHITE THEME
                        ========================================= */

          body.white-theme {
               background-color: #f8fafc !important;
               color: #0f172a;
          }

          body.white-theme .content,
          body.white-theme .content-header,
          body.white-theme .content-body {
               background-color: #f8fafc !important;
               color: #0f172a;
          }

          body.white-theme .modern-card {
               background-color: #ffffff !important;
               color: #0f172a;
               border: none;
               border-radius: 24px;
               box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
          }

          body.white-theme .service-box,
          body.white-theme .activity {
               background-color: #f8fafc !important;
               color: #0f172a;
          }

          body.white-theme .card,
          body.white-theme .card-header,
          body.white-theme .card-body,
          body.white-theme .card-footer {
               background-color: #ffffff;
               color: #0f172a;
          }

          body.white-theme .list-group-item {
               background-color: #ffffff;
               color: #0f172a;
               border-color: #e2e8f0;
          }

          /* =========================================
                           BLACK THEME
                        ========================================= */

          body.black-theme {
               background-color: #0f1115 !important;
               color: #f8fafc !important;
          }

          body.black-theme .content,
          body.black-theme .content-header,
          body.black-theme .content-body {
               background-color: #0f1115 !important;
               color: #f8fafc !important;
          }

          body.black-theme .modern-card {
               background-color: #1a1d24 !important;
               color: #f8fafc !important;
               border: 1px solid #2d313a;
               border-radius: 24px;
               box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
          }

          body.black-theme .service-box,
          body.black-theme .activity {
               background-color: #242832 !important;
               color: #f8fafc !important;
               border: 1px solid #303541;
          }

          body.black-theme .card,
          body.black-theme .card-header,
          body.black-theme .card-body,
          body.black-theme .card-footer {
               background-color: #1a1d24 !important;
               color: #f8fafc !important;
               border-color: #303541 !important;
          }

          body.black-theme .list-group-item {
               background-color: #1a1d24 !important;
               color: #f8fafc !important;
               border-color: #303541 !important;
          }

          body.black-theme h1,
          body.black-theme h2,
          body.black-theme h3,
          body.black-theme h4,
          body.black-theme h5,
          body.black-theme h6,
          body.black-theme p,
          body.black-theme span,
          body.black-theme label {
               color: inherit;
          }

          body.black-theme .text-muted,
          body.black-theme .tx-color-03 {
               color: #a8b0bd !important;
          }

          /* =========================================
                           HERO DASHBOARD
                        ========================================= */

          .hero-dashboard {
               background:
                    linear-gradient(135deg,
                         #0f172a,
                         #2563eb,
                         #7c3aed);

               padding: 35px;
               border-radius: 30px;
               color: #ffffff;
          }

          body.black-theme .hero-dashboard {
               background:
                    linear-gradient(135deg,
                         #111827,
                         #1e3a8a,
                         #4c1d95);
          }

          .hero-dashboard h1,
          .hero-dashboard h2,
          .hero-dashboard h3,
          .hero-dashboard h4,
          .hero-dashboard h5,
          .hero-dashboard h6,
          .hero-dashboard p,
          .hero-dashboard span {
               color: #ffffff !important;
          }

          /* =========================================
                           GLOBAL CARD
                        ========================================= */

          .modern-card {
               border: none;
               border-radius: 24px;
          }

          /* =========================================
                           KPI CARD
                        ========================================= */

          .kpi-card {
               padding: 25px;
               border-radius: 24px;
               color: #ffffff;
               height: 180px;
               position: relative;
               overflow: hidden;
          }

          .kpi-card::after {
               content: "";
               position: absolute;
               right: -30px;
               top: -30px;
               width: 130px;
               height: 130px;
               background: rgba(255, 255, 255, 0.15);
               border-radius: 50%;
          }

          .kpi-card h1 {
               font-size: 40px;
               font-weight: 800;
               color: #ffffff !important;
          }

          .kpi-card p,
          .kpi-card span,
          .kpi-card h5,
          .kpi-card h6 {
               color: #ffffff !important;
          }

          .kpi-blue {
               background:
                    linear-gradient(135deg, #2563eb, #38bdf8);
          }

          .kpi-green {
               background:
                    linear-gradient(135deg, #059669, #34d399);
          }

          .kpi-purple {
               background:
                    linear-gradient(135deg, #7c3aed, #c084fc);
          }

          .kpi-red {
               background:
                    linear-gradient(135deg, #dc2626, #fb7185);
          }

          /* =========================================
                           ICON
                        ========================================= */

          .icon-modern {
               width: 55px;
               height: 55px;
               display: flex;
               align-items: center;
               justify-content: center;
               border-radius: 18px;
               background: rgba(255, 255, 255, 0.25);
               font-size: 25px;
          }

          /* =========================================
                           CHART
                        ========================================= */

          .chart-container {
               padding: 25px;
          }

          body.black-theme .chart-container {
               color: #f8fafc;
          }

          /* =========================================
                           STATUS DAN ACTIVITY
                        ========================================= */

          .service-box {
               padding: 18px;
               border-radius: 18px;
               margin-bottom: 15px;
          }

          .activity {
               display: flex;
               gap: 15px;
               padding: 15px;
               border-radius: 16px;
               margin-bottom: 12px;
          }

          /* =========================================
                           CARD DAN CHART
                        ========================================= */

          .card-total-sales {
               height: 100%;
          }

          #flotChart7 {
               width: 100%;
               height: 430px;
               min-height: 430px;
          }

          .card-transactions,
          .card-deal {
               width: 100%;
          }

          .card-transactions .list-group-item {
               display: flex;
               align-items: center;
          }

          .card-transactions .list-group-item h6 {
               margin-bottom: 3px;
               font-size: 13px;
          }

          /* =========================================
                           RESPONSIVE
                        ========================================= */

          @media (max-width: 1199px) {
               #flotChart7 {
                    height: 320px;
                    min-height: 320px;
               }
          }
     </style>
     {{-- HEADER --}}


     <div class="hero-dashboard mb-4">

          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

               <div>
                    <h2>
                         Dashboard Business Intelligence
                    </h2>

                    <p class="mb-0">
                         Analisis pendapatan dan kualitas pelayanan perusahaan jasa
                    </p>
               </div>

               <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-light">
                         <i data-feather="download" class="mg-r-5"></i>
                         Unduh Laporan
                    </button>
               </div>

          </div>

     </div>


     {{-- KPI SECTION --}}
     <div class="row">

          <!-- Total Pendapatan -->
          <div class="col-xl-3 col-md-6 mb-4">
               <div class="kpi-card kpi-blue">

                    <div class="icon-modern">
                         <i data-feather="dollar-sign"></i>
                    </div>

                    <h1>
                         Rp486,03 Juta
                    </h1>

                    <p>
                         Total Pendapatan
                    </p>

                    <small>
                         Naik 8,5% dari periode sebelumnya
                    </small>

               </div>
          </div>


          <!-- Laba Bersih -->
          <div class="col-xl-3 col-md-6 mb-4">
               <div class="kpi-card kpi-purple">

                    <div class="icon-modern">
                         <i data-feather="trending-up"></i>
                    </div>

                    <h1>
                         Rp98,81 Juta
                    </h1>

                    <p>
                         Laba Bersih
                    </p>

                    <small>
                         Margin laba mencapai 20,3%
                    </small>

               </div>
          </div>


          <!-- Kepuasan Pelanggan -->
          <div class="col-xl-3 col-md-6 mb-4">
               <div class="kpi-card kpi-purple">

                    <div class="icon-modern">
                         <i data-feather="smile"></i>
                    </div>

                    <h1>
                         94,5%
                    </h1>

                    <p>
                         Indeks Kepuasan Pelanggan
                    </p>

                    <small>
                         Kualitas pelayanan sangat baik
                    </small>

               </div>
          </div>


          <!-- Keluhan Aktif -->
          <div class="col-xl-3 col-md-6 mb-4">
               <div class="kpi-card kpi-red">

                    <div class="icon-modern">
                         <i data-feather="alert-triangle"></i>
                    </div>

                    <h1>
                         25
                    </h1>

                    <p>
                         Keluhan Aktif
                    </p>

                    <small>
                         Memerlukan tindak lanjut
                    </small>

               </div>
          </div>

     </div>
     <div class="row align-items-stretch">

          <!-- Analisis Pendapatan -->
          <div class="col-md-12 col-xl-8 mg-t-15 mg-sm-t-20">
               <div class="card card-hover card-total-sales h-100">

                    <div class="card-header bg-transparent pd-y-15 pd-l-15 pd-sm-l-20 pd-r-15 bd-b-0">
                         <div>
                              <h6 class="card-title mg-b-0">
                                   Analisis Pendapatan Perusahaan
                              </h6>

                              <small class="text-muted">
                                   Perbandingan pendapatan berdasarkan jenis layanan
                              </small>
                         </div>

                         <nav class="nav">
                              <a href="#" class="link-gray-500" title="Bantuan">
                                   <i data-feather="help-circle" class="svg-16"></i>
                              </a>

                              <a href="#" class="link-gray-500" title="Menu lainnya">
                                   <i data-feather="more-vertical" class="svg-16"></i>
                              </a>
                         </nav>
                    </div>

                    <div class="card-body pd-x-15 pd-sm-x-20 pd-t-5">

                         <div class="d-flex flex-column flex-sm-row align-items-start justify-content-between mg-b-15">

                              <div class="total-sales-info order-2 order-sm-0">

                                   <div>
                                        <label>Total Pendapatan</label>
                                        <h5>Rp486.030.000</h5>
                                   </div>

                                   <div>
                                        <label>Laba Bersih</label>
                                        <h5>Rp98.809.700</h5>
                                   </div>

                                   <div>
                                        <label>Jumlah Transaksi</label>
                                        <h5>36.760</h5>
                                   </div>

                              </div>

                              <div class="order-1 order-sm-0 mg-sm-t-7 mg-b-15 mg-sm-b-0">

                                   <button type="button" class="btn btn-xs btn-white pd-x-10">
                                        Triwulanan
                                        <i class="icon ion-ios-arrow-down mg-l-5"></i>
                                   </button>

                                   <button type="button" class="btn btn-xs btn-white pd-x-10">
                                        Semua Layanan
                                        <i class="icon ion-ios-arrow-down mg-l-5"></i>
                                   </button>

                              </div>

                         </div>

                         <div class="flot-wrapper">

                              <div class="chart-legend">
                                   <label>
                                        <span class="bg-blue"></span>
                                        Layanan Offline
                                   </label>

                                   <label>
                                        <span class="bg-green"></span>
                                        Layanan Online
                                   </label>
                              </div>

                              <div id="flotChart7" class="flot-chart"></div>

                         </div>

                    </div>
               </div>
          </div>


          <!-- Kolom Kanan -->
          <div class="col-md-12 col-xl-4 mg-t-15 mg-sm-t-20">
               <div class="d-flex flex-column h-100">

                    <!-- Transaksi Terbaru -->
                    <div class="card card-hover card-transactions">

                         <div class="card-header bg-transparent">
                              <div>
                                   <h6 class="card-title mg-b-0">
                                        Transaksi Terbaru
                                   </h6>

                                   <small class="text-muted">
                                        Aktivitas pembayaran pelanggan
                                   </small>
                              </div>

                              <nav class="nav nav-card-icon">
                                   <a href="#" title="Unduh">
                                        <i data-feather="download"></i>
                                   </a>

                                   <a href="#" title="Cetak">
                                        <i data-feather="printer"></i>
                                   </a>

                                   <a href="#" title="Menu lainnya">
                                        <i data-feather="more-horizontal"></i>
                                   </a>
                              </nav>
                         </div>

                         <ul class="list-group list-group-flush">

                              <li class="list-group-item">
                                   <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-primary-light">
                                             <i data-feather="shopping-bag" class="svg-18"></i>
                                        </span>
                                   </div>

                                   <div class="mg-l-10 mg-sm-l-15">
                                        <h6>Pembayaran Layanan - TRX001</h6>
                                        <small>Hari ini, 21 Juli 2026</small>
                                   </div>

                                   <div class="mg-l-auto tx-right">
                                        <h6>Rp1.500.000</h6>
                                        <small class="d-none d-sm-inline">
                                             Transfer bank
                                        </small>
                                   </div>
                              </li>

                              <li class="list-group-item">
                                   <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-teal-light">
                                             <i data-feather="credit-card" class="svg-18"></i>
                                        </span>
                                   </div>

                                   <div class="mg-l-10 mg-sm-l-15">
                                        <h6>Pembayaran Layanan - TRX002</h6>
                                        <small>Hari ini, 21 Juli 2026</small>
                                   </div>

                                   <div class="mg-l-auto tx-right">
                                        <h6>Rp750.000</h6>
                                        <small class="d-none d-sm-inline">
                                             Pembayaran digital
                                        </small>
                                   </div>
                              </li>

                              <li class="list-group-item">
                                   <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-pink-light">
                                             <i data-feather="file-text" class="svg-18"></i>
                                        </span>
                                   </div>

                                   <div class="mg-l-10 mg-sm-l-15">
                                        <h6>Pembayaran Layanan - TRX003</h6>
                                        <small>Kemarin, 20 Juli 2026</small>
                                   </div>

                                   <div class="mg-l-auto tx-right">
                                        <h6>Rp2.000.000</h6>
                                        <small class="d-none d-sm-inline">
                                             Pembayaran tunai
                                        </small>
                                   </div>
                              </li>

                         </ul>

                         <div class="card-footer bg-transparent">
                              <a href="#">
                                   Lihat Semua Transaksi
                                   <i class="icon ion-chevron-right"></i>
                                   <i class="icon ion-chevron-right"></i>
                              </a>
                         </div>

                    </div>


                    <!-- Layanan Pendapatan Tertinggi -->
                    <div class="card card-hover card-deal mg-t-20 flex-grow-1">

                         <div class="card-header bg-transparent bd-b-0">
                              <div>
                                   <h6 class="card-title mg-b-0">
                                        Layanan dengan Pendapatan Tertinggi
                                   </h6>

                                   <small class="text-muted">
                                        Berdasarkan periode berjalan
                                   </small>
                              </div>

                              <nav class="nav nav-card-icon">
                                   <a href="#" title="Simpan">
                                        <i data-feather="save"></i>
                                   </a>

                                   <a href="#" title="Cetak">
                                        <i data-feather="printer"></i>
                                   </a>

                                   <a href="#" title="Menu lainnya">
                                        <i data-feather="more-horizontal"></i>
                                   </a>
                              </nav>
                         </div>

                         <div class="card-body">
                              <div class="d-flex align-items-center">

                                   <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-primary-light">
                                             <i data-feather="briefcase" class="svg-18"></i>
                                        </span>
                                   </div>

                                   <div class="mg-l-15">
                                        <h6 class="mg-b-3">Layanan Konsultasi</h6>
                                        <small class="text-muted">1.245 transaksi</small>
                                   </div>

                                   <div class="mg-l-auto tx-right">
                                        <h6 class="mg-b-3">Rp125.000.000</h6>
                                        <small class="text-success">Naik 12,5%</small>
                                   </div>

                              </div>
                         </div>

                    </div>

               </div>
          </div>

     </div>
     <div class="row">

          <!-- Target Penyelesaian Layanan Harian -->
          <div class="col-md-6 col-xl-4 mg-t-15 mg-sm-t-20 order-md-1 order-xl-0">
               <div class="card card-hover card-chart-one ht-md-100p">

                    <div class="card-header bg-transparent pd-b-15-f bd-b-0">
                         <div>
                              <h6 class="card-title mg-b-0">
                                   Target Penyelesaian Layanan Harian
                              </h6>

                              <small class="text-muted">
                                   Monitoring jumlah layanan pelanggan yang diselesaikan
                              </small>
                         </div>

                         <nav class="nav">
                              <a href="#" class="link-gray-500" title="Informasi">
                                   <i data-feather="help-circle" class="svg-16"></i>
                              </a>

                              <a href="#" class="link-gray-500" title="Menu lainnya">
                                   <i data-feather="more-vertical" class="svg-16"></i>
                              </a>
                         </nav>
                    </div>

                    <div class="card-body pd-t-0 d-block">

                         <div class="d-flex align-items-center justify-content-between mg-b-10">

                              <p class="tx-11 tx-color-03 mg-b-0">
                                   Hari ini, 21 Juli 2026
                              </p>

                              <div class="chart-legend">
                                   <label>
                                        <span class="bg-brand-01"></span>
                                        Target tercapai
                                   </label>
                              </div>

                         </div>

                         <!-- Grafik pencapaian layanan -->
                         <div id="flotChart5" class="flot-chart-two"></div>

                    </div>

                    <div class="card-footer bg-transparent pd-y-15 pd-x-20">

                         <div class="row row-sm tx-13">

                              <div class="col">
                                   <label class="tx-13 mg-b-3">
                                        Target Layanan
                                   </label>

                                   <h4 class="mg-b-0">
                                        60
                                   </h4>

                                   <small class="tx-11 tx-color-04">
                                        Layanan per hari
                                   </small>
                              </div>

                              <div class="col">
                                   <label class="tx-13 mg-b-3">
                                        Selesai Hari Ini
                                   </label>

                                   <h4 class="mg-b-0">
                                        48
                                   </h4>

                                   <small class="tx-11 tx-color-04">
                                        80% target tercapai
                                   </small>
                              </div>

                         </div>

                    </div>

               </div>
          </div>


          <!-- Pendapatan Berdasarkan Cabang -->
          <div class="col-md-12 col-xl-8 mg-t-15 mg-sm-t-20">
               <div class="card card-hover card-sale-location ht-md-100p">

                    <div class="card-header bg-transparent">

                         <div>
                              <h6 class="card-title mg-b-0">
                                   Pendapatan Berdasarkan Cabang
                              </h6>

                              <small class="text-muted">
                                   Perbandingan pendapatan perusahaan berdasarkan wilayah operasional
                              </small>
                         </div>

                         <nav class="nav">
                              <a href="#" class="link-gray-500" title="Informasi">
                                   <i data-feather="help-circle" class="svg-16"></i>
                              </a>

                              <a href="#" class="link-gray-500" title="Menu lainnya">
                                   <i data-feather="more-vertical" class="svg-16"></i>
                              </a>
                         </nav>

                    </div>

                    <div class="card-body">

                         <!-- Daftar cabang -->
                         <div class="list-group-wrapper order-2 order-md-0 mg-t-20 mg-sm-t-30 mg-md-t-0">

                              <label class="content-label mg-b-8">
                                   Cabang dengan Pendapatan Tertinggi
                              </label>

                              <ul class="list-group list-group-flush mg-b-15">

                                   <li class="list-group-item">
                                        <span class="bg-primary"></span>
                                        <span>Jakarta</span>
                                        <span class="tx-medium">Rp125 Juta</span>
                                   </li>

                                   <li class="list-group-item">
                                        <span class="bg-teal"></span>
                                        <span>Surabaya</span>
                                        <span class="tx-medium">Rp108 Juta</span>
                                   </li>

                                   <li class="list-group-item">
                                        <span class="bg-warning"></span>
                                        <span>Bandung</span>
                                        <span class="tx-medium">Rp95 Juta</span>
                                   </li>

                                   <li class="list-group-item">
                                        <span class="bg-pink"></span>
                                        <span>Medan</span>
                                        <span class="tx-medium">Rp82 Juta</span>
                                   </li>

                                   <li class="list-group-item">
                                        <span class="bg-purple"></span>
                                        <span>Semarang</span>
                                        <span class="tx-medium">Rp74 Juta</span>
                                   </li>

                                   <li class="list-group-item">
                                        <span class="bg-success"></span>
                                        <span>Makassar</span>
                                        <span class="tx-medium">Rp68 Juta</span>
                                   </li>

                              </ul>

                              <a href="#" class="d-flex align-items-center tx-12">
                                   Lihat laporan lengkap
                                   <i class="icon ion-android-arrow-forward mg-l-5"></i>
                              </a>

                         </div>

                         <!-- Peta wilayah -->
                         <div class="vmap-wrapper">
                              <div id="vmap" class="vmap order-1 order-md-0"></div>
                         </div>

                    </div>

               </div>
          </div>

     </div>
@endsection
@push('script')
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               if (typeof feather !== 'undefined') {
                    feather.replace();
               }
          });
     </script>
     <script>
          $(document).ready(function() {

               const chartElement = $('#flotChart7');

               // Pastikan elemen grafik tersedia
               if (chartElement.length === 0) {
                    console.error('Elemen #flotChart7 tidak ditemukan.');
                    return;
               }

               // Pastikan library Flot sudah dimuat
               if (typeof $.plot !== 'function') {
                    console.error(
                         'Library Flot belum dimuat. Periksa jquery.flot.js.'
                    );
                    return;
               }

               const offlineSales = [
                    [1, 120000],
                    [2, 145000],
                    [3, 130000],
                    [4, 175000],
                    [5, 160000],
                    [6, 210000],
                    [7, 195000],
                    [8, 240000],
                    [9, 225000],
                    [10, 270000],
                    [11, 255000],
                    [12, 300000]
               ];

               const onlineSales = [
                    [1, 80000],
                    [2, 95000],
                    [3, 110000],
                    [4, 105000],
                    [5, 140000],
                    [6, 155000],
                    [7, 170000],
                    [8, 165000],
                    [9, 200000],
                    [10, 215000],
                    [11, 235000],
                    [12, 260000]
               ];

               const chartData = [{
                         label: 'Offline Sales',
                         data: offlineSales,
                         color: '#0168fa'
                    },
                    {
                         label: 'Online Sales',
                         data: onlineSales,
                         color: '#10b759'
                    }
               ];

               const chartOptions = {
                    series: {
                         lines: {
                              show: true,
                              lineWidth: 2,
                              fill: 0.08
                         },
                         points: {
                              show: true,
                              radius: 3,
                              lineWidth: 2,
                              fill: true,
                              fillColor: '#ffffff'
                         },
                         shadowSize: 0
                    },

                    grid: {
                         borderWidth: 0,
                         hoverable: true,
                         clickable: true,
                         labelMargin: 10
                    },

                    legend: {
                         show: false
                    },

                    xaxis: {
                         ticks: [
                              [1, 'Jan'],
                              [2, 'Feb'],
                              [3, 'Mar'],
                              [4, 'Apr'],
                              [5, 'May'],
                              [6, 'Jun'],
                              [7, 'Jul'],
                              [8, 'Aug'],
                              [9, 'Sep'],
                              [10, 'Oct'],
                              [11, 'Nov'],
                              [12, 'Dec']
                         ],
                         tickColor: 'transparent'
                    },

                    yaxis: {
                         min: 0,
                         tickColor: '#e5e7eb',
                         tickFormatter: function(value) {
                              return '$' + (value / 1000) + 'K';
                         }
                    }
               };

               $.plot(chartElement, chartData, chartOptions);

               // Menampilkan icon Feather
               if (typeof feather !== 'undefined') {
                    feather.replace();
               }
          });
     </script>
     <script>
          $(document).ready(function() {

               /*
               |--------------------------------------------------------------------------
               | FEATHER ICON
               |--------------------------------------------------------------------------
               */
               if (typeof feather !== 'undefined') {
                    feather.replace();
               }


               /*
               |--------------------------------------------------------------------------
               | GRAFIK TARGET PENYELESAIAN LAYANAN HARIAN
               |--------------------------------------------------------------------------
               */

               const chartLayanan = $('#flotChart5');

               if (
                    chartLayanan.length > 0 &&
                    typeof $.plot === 'function'
               ) {
                    const dataLayanan = [
                         [1, 42],
                         [2, 48],
                         [3, 45],
                         [4, 51],
                         [5, 54],
                         [6, 49],
                         [7, 48]
                    ];

                    $.plot(
                         chartLayanan,
                         [{
                              label: 'Layanan selesai',
                              data: dataLayanan,
                              color: '#0168fa',
                              lines: {
                                   show: true,
                                   lineWidth: 2,
                                   fill: true,
                                   fillColor: {
                                        colors: [{
                                                  opacity: 0.25
                                             },
                                             {
                                                  opacity: 0.03
                                             }
                                        ]
                                   }
                              },
                              points: {
                                   show: true,
                                   radius: 3,
                                   lineWidth: 2,
                                   fillColor: '#ffffff'
                              }
                         }], {
                              series: {
                                   shadowSize: 0
                              },

                              grid: {
                                   borderWidth: 0,
                                   hoverable: true,
                                   clickable: true,
                                   labelMargin: 10
                              },

                              legend: {
                                   show: false
                              },

                              xaxis: {
                                   ticks: [
                                        [1, 'Sen'],
                                        [2, 'Sel'],
                                        [3, 'Rab'],
                                        [4, 'Kam'],
                                        [5, 'Jum'],
                                        [6, 'Sab'],
                                        [7, 'Min']
                                   ],
                                   tickColor: 'transparent',
                                   font: {
                                        size: 10,
                                        color: '#8392a5'
                                   }
                              },

                              yaxis: {
                                   min: 0,
                                   max: 60,
                                   ticks: 4,
                                   tickColor: '#e5e9f2',
                                   font: {
                                        size: 10,
                                        color: '#8392a5'
                                   }
                              }
                         }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | TOOLTIP GRAFIK
                    |--------------------------------------------------------------------------
                    */

                    $('<div id="chartTooltip5"></div>')
                         .css({
                              position: 'absolute',
                              display: 'none',
                              padding: '7px 10px',
                              background: '#001737',
                              color: '#ffffff',
                              borderRadius: '4px',
                              fontSize: '11px',
                              zIndex: 10000,
                              pointerEvents: 'none'
                         })
                         .appendTo('body');

                    chartLayanan.bind(
                         'plothover',
                         function(event, position, item) {
                              if (item) {
                                   const jumlah = item.datapoint[1];

                                   $('#chartTooltip5')
                                        .html(
                                             '<strong>' +
                                             jumlah +
                                             '</strong> layanan selesai'
                                        )
                                        .css({
                                             top: item.pageY - 40,
                                             left: item.pageX + 10
                                        })
                                        .fadeIn(150);
                              } else {
                                   $('#chartTooltip5').hide();
                              }
                         }
                    );
               } else {
                    console.warn(
                         'Flot Chart belum tersedia atau elemen #flotChart5 tidak ditemukan.'
                    );
               }


               /*
               |--------------------------------------------------------------------------
               | PETA PENDAPATAN BERDASARKAN WILAYAH
               |--------------------------------------------------------------------------
               */

               const mapPendapatan = $('#vmap');

               if (
                    mapPendapatan.length > 0 &&
                    typeof $.fn.vectorMap === 'function'
               ) {
                    mapPendapatan.vectorMap({
                         map: 'world_en',

                         backgroundColor: 'transparent',

                         color: '#d9e2ec',

                         hoverOpacity: 0.8,

                         selectedColor: '#0168fa',

                         enableZoom: true,

                         showTooltip: true,

                         normalizeFunction: 'polynomial',

                         selectedRegions: ['id'],

                         borderColor: '#ffffff',

                         borderWidth: 1,

                         borderOpacity: 1,

                         onRegionOver: function(event, code, region) {
                              if (code.toLowerCase() === 'id') {
                                   $('#jqvmap1_' + code).attr(
                                        'fill',
                                        '#0168fa'
                                   );
                              }
                         },

                         onLabelShow: function(event, label, code) {
                              if (code.toLowerCase() === 'id') {
                                   label.html(
                                        '<strong>Indonesia</strong>' +
                                        '<br>Kontribusi pendapatan: Rp552 juta'
                                   );
                              }
                         },

                         onRegionClick: function(event) {
                              event.preventDefault();
                         }
                    });
               } else {
                    console.warn(
                         'Vector Map belum tersedia atau elemen #vmap tidak ditemukan.'
                    );
               }


               /*
               |--------------------------------------------------------------------------
               | RESIZE GRAFIK SAAT UKURAN LAYAR BERUBAH
               |--------------------------------------------------------------------------
               */

               let resizeTimer;

               $(window).on('resize', function() {
                    clearTimeout(resizeTimer);

                    resizeTimer = setTimeout(function() {
                         if (
                              chartLayanan.length > 0 &&
                              typeof $.plot === 'function'
                         ) {
                              chartLayanan.trigger('resize');
                         }
                    }, 200);
               });

          });
     </script>
@endpush
