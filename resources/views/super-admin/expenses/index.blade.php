@extends('layouts.app')

@section('title', 'Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa - Pengeluaran')

@section('content')
     @php
          $rows = $expenses->getCollection();
          $totalAmount = (float) $rows->sum('amount');
          $withAttachment = $rows->filter(fn($item) => filled($item->attachment_path))->count();
          $linkedToOrder = $rows->filter(fn($item) => filled($item->service_order_id))->count();
     @endphp

     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          /* ── Root ── */
          .xpi-page {
               --xpi-ink: #10233f;
               --xpi-muted: #617692;
               --xpi-line: #d7e2ef;
               --xpi-surface: #ffffff;
               --xpi-primary: #0f766e;
               --xpi-accent: #0ea5e9;
               min-height: calc(100vh - 70px);
               padding: 24px 18px 48px;
               color: var(--xpi-ink);
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 8% 8%, rgba(14, 165, 233, 0.13), transparent 26%),
                    radial-gradient(circle at 93% 11%, rgba(20, 184, 166, 0.11), transparent 28%),
                    radial-gradient(circle at 82% 88%, rgba(59, 130, 246, 0.07), transparent 24%),
                    linear-gradient(160deg, #f8fcff 0%, #f1f7fb 48%, #eef4fa 100%);
          }

          .xpi-wrap {
               max-width: 1600px;
               margin: 0 auto;
          }

          /* ── Hero ── */
          .xpi-hero {
               position: relative;
               overflow: hidden;
               display: grid;
               grid-template-columns: 1fr auto;
               align-items: center;
               gap: 18px;
               padding: 28px 32px;
               margin-bottom: 20px;
               border-radius: 24px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, 0.28);
               background:
                    radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.22), transparent 24%),
                    linear-gradient(124deg, #0f766e 0%, #0369a1 54%, #0ea5e9 100%);
               box-shadow: 0 24px 54px rgba(3, 105, 161, 0.28);
          }

          .xpi-hero::after {
               content: '';
               position: absolute;
               width: 300px;
               height: 300px;
               border-radius: 50%;
               right: -100px;
               top: -140px;
               background: rgba(255, 255, 255, 0.13);
               pointer-events: none;
          }

          .xpi-hero::before {
               content: '';
               position: absolute;
               width: 200px;
               height: 200px;
               border-radius: 32px;
               left: -38px;
               bottom: -80px;
               transform: rotate(25deg);
               background: rgba(255, 255, 255, 0.09);
               pointer-events: none;
          }

          .xpi-hero>* {
               position: relative;
               z-index: 1;
          }

          .xpi-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.15rem, 2vw, 1.8rem);
               font-weight: 700;
               letter-spacing: -0.02em;
               line-height: 1.25;
          }

          .xpi-hero p {
               margin: 8px 0 0;
               max-width: 720px;
               color: rgba(255, 255, 255, 0.88);
               font-size: 0.88rem;
               line-height: 1.65;
          }

          .xpi-hero-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-top: 14px;
               padding: 6px 13px;
               border: 1px solid rgba(255, 255, 255, 0.35);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.13);
               font-size: 0.70rem;
               font-weight: 700;
               letter-spacing: 0.04em;
               backdrop-filter: blur(6px);
          }

          .xpi-hero-badge .dot {
               width: 7px;
               height: 7px;
               border-radius: 50%;
               background: #86efac;
               animation: xpiPulse 1.8s infinite;
          }

          @keyframes xpiPulse {

               0%,
               100% {
                    box-shadow: 0 0 0 0 rgba(134, 239, 172, 0.7);
               }

               60% {
                    box-shadow: 0 0 0 7px rgba(134, 239, 172, 0);
               }
          }

          .xpi-hero-actions {
               display: flex;
               align-items: center;
               gap: 10px;
               flex-wrap: wrap;
          }

          /* ── Hero buttons ── */
          .xpi-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               padding: 0 20px;
               height: 44px;
               border-radius: 13px;
               font-size: 0.82rem;
               font-weight: 800;
               text-decoration: none;
               line-height: 1;
               white-space: nowrap;
               transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
          }

          .xpi-btn-solid {
               color: #0b3b66;
               border: 1px solid rgba(255, 255, 255, 0.85);
               background: #fff;
          }

          .xpi-btn-ghost {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, 0.42);
               background: rgba(255, 255, 255, 0.15);
               backdrop-filter: blur(8px);
          }

          .xpi-btn:hover {
               transform: translateY(-2px);
               box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
               text-decoration: none;
          }

          .xpi-btn .bi {
               font-size: 15px;
               width: 15px;
               height: 15px;
               display: inline-flex;
               align-items: center;
               justify-content: center;
          }

          /* ── Alerts ── */
          .xpi-alert {
               display: flex;
               align-items: center;
               gap: 10px;
               padding: 13px 18px;
               margin-bottom: 18px;
               border-radius: 14px;
               font-size: 0.87rem;
               font-weight: 600;
          }

          .xpi-alert-success {
               background: #ecfdf5;
               border: 1px solid #6ee7b7;
               color: #065f46;
          }

          .xpi-alert-danger {
               background: #fff1f2;
               border: 1px solid #fca5a5;
               color: #991b1b;
          }

          /* ── KPI grid ── */
          .xpi-kpi-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 14px;
               margin-bottom: 20px;
          }

          .xpi-kpi {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               gap: 14px;
               padding: 18px 20px;
               border-radius: 18px;
               background: var(--xpi-surface);
               border: 1px solid var(--xpi-line);
               box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
               transition: transform 0.18s, box-shadow 0.18s;
          }

          .xpi-kpi:hover {
               transform: translateY(-2px);
               box-shadow: 0 10px 30px rgba(15, 23, 42, 0.10);
          }

          .xpi-kpi::after {
               content: '';
               position: absolute;
               width: 80px;
               height: 80px;
               border-radius: 50%;
               right: -22px;
               top: -22px;
               background: linear-gradient(145deg, rgba(14, 165, 233, 0.12), rgba(20, 184, 166, 0.08));
               pointer-events: none;
          }

          .xpi-kpi-icon {
               display: flex;
               align-items: center;
               justify-content: center;
               width: 48px;
               min-width: 48px;
               height: 48px;
               border-radius: 14px;
               font-size: 20px;
               position: relative;
               z-index: 1;
          }

          .xpi-kpi-icon.teal {
               background: #dff7f3;
               color: #0f766e;
          }

          .xpi-kpi-icon.blue {
               background: #dbeafe;
               color: #1d4ed8;
          }

          .xpi-kpi-icon.amber {
               background: #fef3c7;
               color: #b45309;
          }

          .xpi-kpi-icon.sky {
               background: #e0f2fe;
               color: #0369a1;
          }

          .xpi-kpi-body {
               position: relative;
               z-index: 1;
               min-width: 0;
          }

          .xpi-kpi-label {
               font-size: 0.68rem;
               font-weight: 700;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               color: var(--xpi-muted);
               margin-bottom: 5px;
               white-space: nowrap;
          }

          .xpi-kpi-value {
               font-size: 1.18rem;
               font-weight: 800;
               color: var(--xpi-ink);
               line-height: 1.2;
               white-space: nowrap;
               overflow: hidden;
               text-overflow: ellipsis;
          }

          /* ── Generic card ── */
          .xpi-card {
               border: 1px solid var(--xpi-line);
               border-radius: 20px;
               background: var(--xpi-surface);
               box-shadow: 0 6px 24px rgba(15, 23, 42, 0.06);
               overflow: hidden;
          }

          .xpi-card-header {
               display: flex;
               align-items: center;
               gap: 12px;
               padding: 18px 24px;
               border-bottom: 1px solid #e8f0f8;
               background: linear-gradient(to right, #f8fafc, #f0f7ff);
          }

          .xpi-card-header-icon {
               display: flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               border-radius: 10px;
               font-size: 16px;
               flex-shrink: 0;
          }

          .xpi-card-header-icon.green {
               background: linear-gradient(135deg, #0f766e, #0369a1);
               color: #fff;
          }

          .xpi-card-header-icon.blue {
               background: linear-gradient(135deg, #1d4ed8, #0369a1);
               color: #fff;
          }

          .xpi-card-header h5 {
               margin: 0;
               font-weight: 800;
               font-size: 0.94rem;
               color: var(--xpi-ink);
               letter-spacing: -0.01em;
          }

          .xpi-card-header p {
               margin: 2px 0 0;
               font-size: 0.75rem;
               color: var(--xpi-muted);
          }

          .xpi-card-body {
               padding: 22px 24px;
          }

          /* ── Filter inputs ── */
          .xpi-filter .form-control,
          .xpi-filter .form-select {
               min-height: 44px;
               border-radius: 11px;
               border: 1.5px solid #d4deea;
               font-size: 0.88rem;
               font-weight: 500;
               color: var(--xpi-ink);
               background: #fafcff;
               transition: border-color 0.18s, box-shadow 0.18s;
          }

          .xpi-filter .form-control:focus,
          .xpi-filter .form-select:focus {
               border-color: #38bdf8;
               background: #fff;
               box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.14);
               outline: none;
          }

          .xpi-filter .form-control::placeholder {
               color: #94a3b8;
          }

          /* ── Filter action buttons ── */
          .xpi-filter-btn {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 0 18px;
               height: 44px;
               border-radius: 11px;
               font-size: 0.83rem;
               font-weight: 700;
               border: none;
               cursor: pointer;
               text-decoration: none;
               transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
               white-space: nowrap;
          }

          .xpi-filter-btn .bi {
               font-size: 14px;
               width: 14px;
               height: 14px;
               display: inline-flex;
               align-items: center;
               justify-content: center;
          }

          .xpi-filter-btn-primary {
               background: linear-gradient(135deg, #0f766e, #0369a1);
               color: #fff;
               box-shadow: 0 4px 12px rgba(3, 105, 161, 0.22);
          }

          .xpi-filter-btn-primary:hover {
               transform: translateY(-1px);
               box-shadow: 0 8px 20px rgba(3, 105, 161, 0.30);
               color: #fff;
               text-decoration: none;
          }

          .xpi-filter-btn-reset {
               background: #fff;
               color: var(--xpi-muted);
               border: 1.5px solid var(--xpi-line);
          }

          .xpi-filter-btn-reset:hover {
               background: #f1f5f9;
               border-color: #94a3b8;
               color: var(--xpi-ink);
               transform: translateY(-1px);
               text-decoration: none;
          }

          /* ── Table ── */
          .xpi-table thead th {
               font-size: 0.68rem;
               letter-spacing: 0.09em;
               text-transform: uppercase;
               color: var(--xpi-muted);
               background: #f2f8fd;
               border-bottom: 2px solid #e2ecf5;
               padding: 14px 16px;
               white-space: nowrap;
               font-weight: 700;
          }

          .xpi-table tbody td {
               padding: 13px 16px;
               vertical-align: middle;
               border-color: #edf2f8;
               font-size: 0.88rem;
               color: var(--xpi-ink);
          }

          .xpi-table tbody tr {
               transition: background 0.14s;
          }

          .xpi-table tbody tr:hover {
               background: #f3f9ff;
          }

          .xpi-table .col-amount {
               font-weight: 800;
               color: #0f766e;
          }

          .xpi-table .col-date {
               font-weight: 600;
               color: #374151;
               white-space: nowrap;
          }

          .xpi-table .col-order {
               font-family: 'Sora', monospace;
               font-size: 0.80rem;
               font-weight: 700;
               color: #1d4ed8;
          }

          .xpi-table .col-creator {
               font-size: 0.84rem;
               color: var(--xpi-muted);
          }

          .xpi-table .col-desc {
               max-width: 280px;
               color: #4b5563;
          }

          /* ── Category pill ── */
          .xpi-pill {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               padding: 4px 11px;
               border-radius: 999px;
               font-size: 0.72rem;
               font-weight: 700;
               color: #0f766e;
               background: #dff7f3;
               border: 1px solid #a7f3d0;
               white-space: nowrap;
          }

          /* ── Row action buttons ── */
          .xpi-row-actions {
               display: flex;
               align-items: center;
               gap: 5px;
          }

          .xpi-action {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 32px;
               height: 32px;
               border-radius: 9px;
               font-size: 13px;
               text-decoration: none;
               border: 1.5px solid;
               transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
               cursor: pointer;
               background: transparent;
               padding: 0;
               line-height: 1;
          }

          .xpi-action:hover {
               transform: translateY(-1px);
               box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
               text-decoration: none;
          }

          .xpi-action .bi {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 13px;
               height: 13px;
               font-size: 13px;
          }

          .xpi-action-view {
               color: #0891b2;
               border-color: #a5f3fc;
               background: #ecfeff;
          }

          .xpi-action-edit {
               color: #b45309;
               border-color: #fcd34d;
               background: #fffbeb;
          }

          .xpi-action-delete {
               color: #dc2626;
               border-color: #fca5a5;
               background: #fff1f2;
          }

          .xpi-action-view:hover {
               background: #cffafe;
               color: #0e7490;
          }

          .xpi-action-edit:hover {
               background: #fef3c7;
               color: #92400e;
          }

          .xpi-action-delete:hover {
               background: #fee2e2;
               color: #b91c1c;
          }

          /* ── Empty state ── */
          .xpi-empty {
               padding: 52px 24px;
               text-align: center;
          }

          .xpi-empty-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 64px;
               height: 64px;
               border-radius: 20px;
               background: #f1f5f9;
               color: #94a3b8;
               font-size: 28px;
               margin-bottom: 16px;
          }

          .xpi-empty h6 {
               font-weight: 700;
               font-size: 0.95rem;
               color: var(--xpi-ink);
               margin-bottom: 6px;
          }

          .xpi-empty p {
               font-size: 0.83rem;
               color: var(--xpi-muted);
               margin: 0;
          }

          /* ── Responsive ── */
          @media (max-width: 992px) {
               .xpi-hero {
                    grid-template-columns: 1fr;
               }

               .xpi-hero-actions {
                    justify-content: flex-start;
               }

               .xpi-kpi-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 576px) {
               .xpi-page {
                    padding: 14px 10px 32px;
               }

               .xpi-kpi-grid {
                    grid-template-columns: 1fr;
               }

               .xpi-hero {
                    padding: 22px 18px;
               }

               .xpi-btn {
                    padding: 0 14px;
                    font-size: 0.78rem;
               }
          }
     </style>

     <div class="xpi-page">
          <div class="xpi-wrap">

               {{-- ── Hero ── --}}
               <section class="xpi-hero">
                    <div>
                         <h1>Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa</h1>
                         <p>Panel pengeluaran dipakai untuk menjaga akurasi biaya operasional, memetakan pengeluaran per
                              service order, dan mempercepat evaluasi performa finansial tim.</p>
                         <div class="xpi-hero-badge">
                              <span class="dot"></span>
                              Data sinkron real-time
                         </div>
                    </div>
                    <div class="xpi-hero-actions">
                         <a href="{{ route('super-admin.expenses.create') }}" class="xpi-btn xpi-btn-solid"
                              aria-label="Tambah Pengeluaran" title="Tambah Pengeluaran">
                              <i class="bi bi-plus-lg"></i>
                              Tambah
                         </a>
                         <a href="{{ route('dashboard') }}" class="xpi-btn xpi-btn-ghost" aria-label="Ke Dashboard"
                              title="Ke Dashboard">
                              <i class="bi bi-speedometer2"></i>
                              Dashboard
                         </a>
                    </div>
               </section>

               {{-- ── KPI Cards ── --}}
               <div class="xpi-kpi-grid">
                    <div class="xpi-kpi">
                         <div class="xpi-kpi-icon teal">
                              <i class="bi bi-collection"></i>
                         </div>
                         <div class="xpi-kpi-body">
                              <div class="xpi-kpi-label">Total Baris (Halaman)</div>
                              <div class="xpi-kpi-value">{{ number_format($rows->count(), 0, ',', '.') }}</div>
                         </div>
                    </div>
                    <div class="xpi-kpi">
                         <div class="xpi-kpi-icon blue">
                              <i class="bi bi-cash-stack"></i>
                         </div>
                         <div class="xpi-kpi-body">
                              <div class="xpi-kpi-label">Total Nominal (Halaman)</div>
                              <div class="xpi-kpi-value">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                         </div>
                    </div>
                    <div class="xpi-kpi">
                         <div class="xpi-kpi-icon amber">
                              <i class="bi bi-receipt-cutoff"></i>
                         </div>
                         <div class="xpi-kpi-body">
                              <div class="xpi-kpi-label">Terkait Service Order</div>
                              <div class="xpi-kpi-value">{{ number_format($linkedToOrder, 0, ',', '.') }}</div>
                         </div>
                    </div>
                    <div class="xpi-kpi">
                         <div class="xpi-kpi-icon sky">
                              <i class="bi bi-paperclip"></i>
                         </div>
                         <div class="xpi-kpi-body">
                              <div class="xpi-kpi-label">Memiliki Lampiran</div>
                              <div class="xpi-kpi-value">{{ number_format($withAttachment, 0, ',', '.') }}</div>
                         </div>
                    </div>
               </div>

               {{-- ── Alerts ── --}}
               @if (session('success'))
                    <div class="xpi-alert xpi-alert-success">
                         <i class="bi bi-check-circle-fill"></i>
                         {{ session('success') }}
                    </div>
               @endif

               @if (session('error'))
                    <div class="xpi-alert xpi-alert-danger">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         {{ session('error') }}
                    </div>
               @endif

               {{-- ── Filter ── --}}
               <div class="xpi-card mb-4">
                    <div class="xpi-card-header">
                         <div class="xpi-card-header-icon blue">
                              <i class="bi bi-funnel"></i>
                         </div>
                         <div>
                              <h5>Filter Data Pengeluaran</h5>
                              <p>Gunakan kombinasi filter untuk analisis biaya yang lebih presisi</p>
                         </div>
                    </div>
                    <div class="xpi-card-body xpi-filter">
                         <form method="GET" action="{{ route('super-admin.expenses.index') }}" class="row g-2">
                              <div class="col-lg-3 col-md-6">
                                   <input type="text" name="search" class="form-control"
                                        placeholder="Cari kategori / deskripsi / order / customer"
                                        value="{{ request('search') }}">
                              </div>

                              <div class="col-lg-2 col-md-4">
                                   <select name="category" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                             <option value="{{ $category }}" @selected((string) request('category') === (string) $category)>
                                                  {{ ucfirst((string) $category) }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-lg-3 col-md-4">
                                   <select name="service_order_id" class="form-select">
                                        <option value="">Semua Service Order</option>
                                        @foreach ($orders as $order)
                                             <option value="{{ $order->id }}" @selected((string) request('service_order_id') === (string) $order->id)>
                                                  {{ $order->order_number }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-lg-2 col-md-4">
                                   <input type="date" name="start_date" class="form-control" title="Tanggal Mulai"
                                        value="{{ request('start_date') }}">
                              </div>

                              <div class="col-lg-2 col-md-4">
                                   <input type="date" name="end_date" class="form-control" title="Tanggal Akhir"
                                        value="{{ request('end_date') }}">
                              </div>

                              <div class="col-lg-2 col-md-4">
                                   <select name="per_page" class="form-select">
                                        @foreach ([10, 25, 50, 100] as $size)
                                             <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                                                  {{ $size }} / halaman
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 d-flex gap-2 flex-wrap">
                                   <button type="submit" class="xpi-filter-btn xpi-filter-btn-primary">
                                        <i class="bi bi-funnel-fill"></i>
                                        Terapkan Filter
                                   </button>
                                   <a href="{{ route('super-admin.expenses.index') }}"
                                        class="xpi-filter-btn xpi-filter-btn-reset">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        Reset
                                   </a>
                              </div>
                         </form>
                    </div>
               </div>

               {{-- ── Table ── --}}
               <div class="xpi-card">
                    <div class="xpi-card-header">
                         <div class="xpi-card-header-icon green">
                              <i class="bi bi-table"></i>
                         </div>
                         <div>
                              <h5>Daftar Pengeluaran</h5>
                              <p>
                                   {{ $expenses->total() }} total data
                                   &mdash; halaman {{ $expenses->currentPage() }} dari {{ $expenses->lastPage() }}
                              </p>
                         </div>
                    </div>
                    <div class="table-responsive">
                         <table class="table xpi-table table-hover mb-0 align-middle">
                              <thead>
                                   <tr>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Service Order</th>
                                        <th>Nominal</th>
                                        <th>Dibuat Oleh</th>
                                        <th style="width:120px;">Aksi</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse ($expenses as $expense)
                                        <tr>
                                             <td class="col-date">
                                                  {{ optional($expense->expense_date)->format('d M Y') }}
                                             </td>
                                             <td>
                                                  <span class="xpi-pill">
                                                       <i class="bi bi-circle-fill" style="font-size:5px;"></i>
                                                       {{ ucfirst((string) $expense->category) }}
                                                  </span>
                                             </td>
                                             <td class="col-desc">
                                                  {{ \Illuminate\Support\Str::limit((string) $expense->description, 72) }}
                                             </td>
                                             <td class="col-order">
                                                  {{ $expense->serviceOrder?->order_number ?? '—' }}
                                             </td>
                                             <td class="col-amount">
                                                  {{ $expense->formatted_amount }}
                                             </td>
                                             <td class="col-creator">
                                                  {{ $expense->creator?->name ?? '—' }}
                                             </td>
                                             <td>
                                                  <div class="xpi-row-actions">
                                                       <a href="{{ route('super-admin.expenses.show', $expense) }}"
                                                            class="xpi-action xpi-action-view" aria-label="Detail"
                                                            title="Detail">
                                                            <i class="bi bi-eye"></i>
                                                       </a>
                                                       <a href="{{ route('super-admin.expenses.edit', $expense) }}"
                                                            class="xpi-action xpi-action-edit" aria-label="Edit"
                                                            title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                       </a>
                                                       <form action="{{ route('super-admin.expenses.destroy', $expense) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="xpi-action xpi-action-delete"
                                                                 aria-label="Hapus" title="Hapus">
                                                                 <i class="bi bi-trash"></i>
                                                            </button>
                                                       </form>
                                                  </div>
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="7">
                                                  <div class="xpi-empty">
                                                       <div class="xpi-empty-icon">
                                                            <i class="bi bi-inbox"></i>
                                                       </div>
                                                       <h6>Belum ada data pengeluaran</h6>
                                                       <p>Coba ubah filter atau tambah pengeluaran baru.</p>
                                                  </div>
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>
               </div>

               {{-- ── Pagination ── --}}
               <div class="mt-4">
                    {{ $expenses->links() }}
               </div>

          </div>
     </div>
@endsection
