@extends('layouts.app')

@section('title', 'Trash Department')

@section('content')
     <style>
          :root {
               --dept-primary: #6366f1;
               --dept-primary-dark: #4f46e5;
               --dept-secondary: #06b6d4;
               --dept-purple: #8b5cf6;
               --dept-pink: #ec4899;
               --dept-success: #10b981;
               --dept-warning: #f59e0b;
               --dept-danger: #ef4444;
               --dept-text: #24324a;
               --dept-muted: #718096;
               --dept-border: #e7eaf3;
               --dept-white: #ffffff;
          }

          .department-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 4% 4%, rgba(251, 113, 133, .17), transparent 24%),
                    radial-gradient(circle at 97% 8%, rgba(34, 211, 238, .19), transparent 25%),
                    radial-gradient(circle at 86% 94%, rgba(167, 139, 250, .15), transparent 22%),
                    linear-gradient(145deg, #fffdfd 0%, #faf7ff 46%, #f0fbff 100%);
          }

          .department-container {
               max-width: 1580px;
               margin: 0 auto;
          }

          .department-hero {
               position: relative;
               overflow: hidden;
               padding: 34px;
               margin-bottom: 22px;
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, .72);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 17%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #fb7185 0%, #ec4899 38%, #8b5cf6 70%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(236, 72, 153, .19);
          }

          .department-hero::before {
               position: absolute;
               top: -76px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .department-hero::after {
               position: absolute;
               right: -36px;
               bottom: -78px;
               width: 180px;
               height: 180px;
               content: '';
               border-radius: 45px;
               background: rgba(255, 255, 255, .12);
               transform: rotate(28deg);
          }

          .hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .hero-title-wrap {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               color: #e11d48;
               font-size: 1.75rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 14px 28px rgba(136, 19, 55, .15);
          }

          .department-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.5vw, 2.4rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .department-hero p {
               max-width: 750px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .97rem;
               line-height: 1.7;
          }

          .btn-back {
               display: inline-flex;
               min-height: 48px;
               padding: 11px 18px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #9f1239;
               font-size: .88rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .82);
               border-radius: 14px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(136, 19, 55, .14);
               transition: .22s ease;
          }

          .btn-back:hover {
               color: #881337;
               background: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(136, 19, 55, .2);
          }

          .custom-alert {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 16px 18px;
               margin-bottom: 18px;
               font-weight: 700;
               border: 0;
               border-radius: 16px;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
          }

          .custom-alert.alert-success {
               color: #047857;
               border-left: 5px solid var(--dept-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .custom-alert.alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--dept-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .stats-row {
               margin-bottom: 22px;
          }

          .stat-card {
               position: relative;
               min-height: 134px;
               padding: 22px;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .95);
               border-radius: 22px;
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
               transition: .23s ease;
          }

          .stat-card:hover {
               transform: translateY(-4px);
               box-shadow: 0 20px 40px rgba(51, 65, 85, .12);
          }

          .stat-card::after {
               position: absolute;
               right: -28px;
               bottom: -40px;
               width: 128px;
               height: 128px;
               content: '';
               border-radius: 50%;
               background: rgba(255, 255, 255, .5);
          }

          .stat-trash {
               color: #be123c;
               background: linear-gradient(135deg, #fff1f2, #ffe4e6);
          }

          .stat-page {
               color: #6d28d9;
               background: linear-gradient(135deg, #f5f3ff, #ede9fe);
          }

          .stat-info {
               color: #0369a1;
               background: linear-gradient(135deg, #f0f9ff, #cffafe);
          }

          .stat-card-inner {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: center;
               justify-content: space-between;
          }

          .stat-title {
               margin-bottom: 7px;
               font-size: .74rem;
               font-weight: 850;
               letter-spacing: .075em;
               text-transform: uppercase;
               opacity: .78;
          }

          .stat-value {
               font-size: 2.25rem;
               font-weight: 850;
               line-height: 1;
               letter-spacing: -.04em;
          }

          .stat-caption {
               margin-top: 8px;
               font-size: .8rem;
               font-weight: 650;
               opacity: .72;
          }

          .stat-icon {
               display: inline-flex;
               flex: 0 0 54px;
               width: 54px;
               height: 54px;
               font-size: 1.4rem;
               align-items: center;
               justify-content: center;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 17px;
               background: rgba(255, 255, 255, .72);
               box-shadow: 0 10px 22px rgba(15, 23, 42, .08);
          }

          .trash-card {
               overflow: hidden;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 24px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 18px 45px rgba(51, 65, 85, .09);
               backdrop-filter: blur(10px);
          }

          .trash-card-header {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 22px 24px;
               border-bottom: 1px solid #eef2f7;
               background: linear-gradient(90deg, #ffffff 0%, #fff7f9 45%, #f5f3ff 72%, #f2fbff 100%);
          }

          .list-title {
               display: flex;
               gap: 11px;
               align-items: center;
               margin: 0;
               color: var(--dept-text);
               font-size: 1.1rem;
               font-weight: 850;
          }

          .list-title-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               color: #e11d48;
               align-items: center;
               justify-content: center;
               border-radius: 13px;
               background: linear-gradient(135deg, #fff1f2, #ffe4e6);
          }

          .list-subtitle {
               margin: 5px 0 0 53px;
               color: var(--dept-muted);
               font-size: .82rem;
          }

          .filter-form {
               display: flex;
               gap: 9px;
               align-items: center;
               width: min(100%, 440px);
          }

          .search-box {
               position: relative;
               flex: 1;
          }

          .search-box i {
               position: absolute;
               top: 50%;
               left: 15px;
               z-index: 2;
               color: #f43f5e;
               transform: translateY(-50%);
          }

          .search-box .form-control {
               height: 45px;
               padding-right: 14px;
               padding-left: 42px;
               color: var(--dept-text);
               font-size: .87rem;
               border: 1px solid #e7d9e2;
               border-radius: 14px;
               background: #ffffff;
               box-shadow: none;
          }

          .search-box .form-control:focus {
               border-color: #fb7185;
               box-shadow: 0 0 0 4px rgba(244, 63, 94, .1);
          }

          .btn-filter,
          .btn-reset {
               display: inline-flex;
               min-height: 45px;
               padding: 0 15px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               font-size: .81rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               transition: .2s ease;
          }

          .btn-filter {
               color: #ffffff;
               border: 0;
               background: linear-gradient(135deg, #f43f5e, #ec4899, #8b5cf6);
               box-shadow: 0 9px 18px rgba(236, 72, 153, .2);
          }

          .btn-filter:hover {
               color: #ffffff;
               transform: translateY(-2px);
               box-shadow: 0 13px 24px rgba(236, 72, 153, .27);
          }

          .btn-reset {
               color: #64748b;
               border: 1px solid #e2e8f0;
               background: #ffffff;
          }

          .btn-reset:hover {
               color: #334155;
               background: #f8fafc;
          }

          .trash-card-body {
               padding: 10px 18px 20px;
          }

          .trash-table {
               min-width: 980px;
               margin-bottom: 0;
          }

          .trash-table thead th {
               padding: 15px 13px;
               color: #59677d;
               font-size: .71rem;
               font-weight: 850;
               letter-spacing: .07em;
               white-space: nowrap;
               text-transform: uppercase;
               vertical-align: middle;
               border: 0;
               background: linear-gradient(180deg, #fff8fa, #f7f3ff);
          }

          .trash-table thead th:first-child {
               border-radius: 12px 0 0 12px;
          }

          .trash-table thead th:last-child {
               border-radius: 0 12px 12px 0;
          }

          .trash-table tbody td {
               padding: 17px 13px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
               background: transparent;
          }

          .trash-table tbody tr {
               transition: .2s ease;
          }

          .trash-table tbody tr:hover td {
               background: #fffafd;
          }

          .trash-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .number-badge {
               display: inline-flex;
               width: 34px;
               height: 34px;
               color: #e11d48;
               font-size: .78rem;
               font-weight: 850;
               align-items: center;
               justify-content: center;
               border-radius: 11px;
               background: #fff1f2;
          }

          .code-label {
               display: inline-flex;
               gap: 6px;
               align-items: center;
               padding: 7px 11px;
               color: #6d28d9;
               font-size: .75rem;
               font-weight: 850;
               letter-spacing: .045em;
               border: 1px solid #ddd6fe;
               border-radius: 10px;
               background: linear-gradient(135deg, #f5f3ff, #ede9fe);
          }

          .department-name {
               display: block;
               margin-bottom: 4px;
               color: var(--dept-text);
               font-size: .92rem;
               font-weight: 850;
          }

          .department-description {
               display: inline-block;
               max-width: 350px;
               color: #7c8aa0;
               font-size: .78rem;
               line-height: 1.5;
          }

          .deleted-badge {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               padding: 8px 11px;
               color: #be123c;
               font-size: .74rem;
               font-weight: 800;
               white-space: nowrap;
               border: 1px solid #fecdd3;
               border-radius: 999px;
               background: #fff1f2;
          }

          .status-badge {
               display: inline-flex;
               gap: 6px;
               align-items: center;
               padding: 7px 10px;
               font-size: .72rem;
               font-weight: 800;
               white-space: nowrap;
               border: 1px solid transparent;
               border-radius: 999px;
          }

          .status-active {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .status-inactive {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .action-group {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               white-space: nowrap;
          }

          .action-btn {
               display: inline-flex;
               min-height: 39px;
               padding: 0 13px;
               gap: 7px;
               align-items: center;
               justify-content: center;
               font-size: .77rem;
               font-weight: 800;
               border: 0;
               border-radius: 12px;
               transition: .2s ease;
          }

          .btn-restore {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .btn-restore:hover {
               color: #ffffff;
               background: #10b981;
               transform: translateY(-2px);
               box-shadow: 0 9px 18px rgba(16, 185, 129, .24);
          }

          .btn-force-delete {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .btn-force-delete:hover {
               color: #ffffff;
               background: #ef4444;
               transform: translateY(-2px);
               box-shadow: 0 9px 18px rgba(239, 68, 68, .24);
          }

          .empty-state {
               padding: 65px 20px !important;
               text-align: center;
          }

          .empty-icon {
               display: inline-flex;
               width: 82px;
               height: 82px;
               margin-bottom: 16px;
               color: #e11d48;
               font-size: 2rem;
               align-items: center;
               justify-content: center;
               border-radius: 24px;
               background: linear-gradient(135deg, #fff1f2, #f5f3ff, #e0f2fe);
               box-shadow: 0 14px 30px rgba(225, 29, 72, .12);
          }

          .empty-state h5 {
               margin-bottom: 7px;
               color: #334155;
               font-weight: 850;
          }

          .empty-state p {
               margin: 0;
               color: #94a3b8;
               font-size: .87rem;
          }

          .pagination-wrapper {
               display: flex;
               padding: 18px 6px 2px;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               border-top: 1px solid #f1f5f9;
          }

          .pagination-info {
               color: var(--dept-muted);
               font-size: .78rem;
          }

          .pagination-wrapper .pagination {
               margin-bottom: 0;
               gap: 5px;
          }

          .pagination-wrapper .page-link {
               min-width: 38px;
               color: #e11d48;
               text-align: center;
               border: 1px solid #ffe4e6;
               border-radius: 10px !important;
               background: #ffffff;
               box-shadow: none;
          }

          .pagination-wrapper .page-item.active .page-link {
               color: #ffffff;
               border-color: #ec4899;
               background: linear-gradient(135deg, #f43f5e, #ec4899, #8b5cf6);
               box-shadow: 0 7px 15px rgba(236, 72, 153, .24);
          }

          .warning-note {
               display: flex;
               gap: 12px;
               align-items: flex-start;
               padding: 17px 19px;
               margin-bottom: 20px;
               color: #92400e;
               border: 1px solid #fde68a;
               border-radius: 17px;
               background: linear-gradient(135deg, #fffbeb, #fff7ed);
               box-shadow: 0 10px 24px rgba(245, 158, 11, .07);
          }

          .warning-note-icon {
               display: inline-flex;
               flex: 0 0 40px;
               width: 40px;
               height: 40px;
               color: #ffffff;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               background: var(--dept-warning);
          }

          .warning-note strong {
               display: block;
               margin-bottom: 4px;
               font-size: .84rem;
          }

          .warning-note p {
               margin: 0;
               font-size: .78rem;
               line-height: 1.55;
          }

          @media (max-width: 991.98px) {
               .department-page {
                    padding: 20px 12px 34px;
               }

               .department-hero {
                    padding: 27px;
               }

               .hero-content,
               .trash-card-header {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .btn-back,
               .filter-form {
                    width: 100%;
               }
          }

          @media (max-width: 767.98px) {
               .department-hero {
                    padding: 23px;
                    border-radius: 22px;
               }

               .hero-title-wrap {
                    align-items: flex-start;
               }

               .hero-icon {
                    flex-basis: 53px;
                    width: 53px;
                    height: 53px;
                    font-size: 1.35rem;
                    border-radius: 16px;
               }

               .trash-card {
                    border-radius: 20px;
               }

               .trash-card-header {
                    padding: 19px;
               }

               .trash-card-body {
                    padding: 8px 12px 18px;
               }

               .list-subtitle {
                    margin-left: 0;
               }

               .filter-form {
                    align-items: stretch;
                    flex-direction: column;
               }

               .btn-filter,
               .btn-reset {
                    width: 100%;
               }

               .pagination-wrapper {
                    align-items: center;
                    flex-direction: column;
                    justify-content: center;
                    text-align: center;
               }
          }

          @media (max-width: 470px) {
               .hero-title-wrap {
                    flex-direction: column;
               }
          }
     </style>

     <div class="department-page">
          <div class="department-container">
               <div class="department-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <div class="hero-icon">
                                   <i class="bi bi-trash3-fill"></i>
                              </div>

                              <div>
                                   <h1>Trash Department</h1>
                                   <p>
                                        Kelola department yang sudah dihapus, pulihkan data yang masih diperlukan,
                                        atau hapus permanen jika data benar-benar tidak digunakan lagi.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ route('super-admin.departments.index') }}" class="btn-back">
                              <i class="bi bi-arrow-left"></i>
                              Kembali ke Department
                         </a>
                    </div>
               </div>

               @if (session('success'))
                    <div class="alert alert-success custom-alert" role="alert">
                         <i class="bi bi-check-circle-fill"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert-danger custom-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <div class="warning-note">
                    <span class="warning-note-icon">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                    </span>
                    <div>
                         <strong>Perhatian sebelum menghapus permanen</strong>
                         <p>
                              Tombol hapus permanen tidak dapat dibatalkan. Gunakan tombol pulihkan jika department masih
                              dibutuhkan.
                         </p>
                    </div>
               </div>

               <div class="row g-3 stats-row">
                    <div class="col-12 col-md-4">
                         <div class="stat-card stat-trash h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Total Trash</div>
                                        <div class="stat-value">{{ $departments->total() }}</div>
                                        <div class="stat-caption">Seluruh data terhapus</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-trash3-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-md-4">
                         <div class="stat-card stat-page h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Data Halaman Ini</div>
                                        <div class="stat-value">{{ $departments->count() }}</div>
                                        <div class="stat-caption">Data yang sedang ditampilkan</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-md-4">
                         <div class="stat-card stat-info h-100">
                              <div class="stat-card-inner">
                                   <div>
                                        <div class="stat-title">Halaman Aktif</div>
                                        <div class="stat-value">{{ $departments->currentPage() }}</div>
                                        <div class="stat-caption">Dari {{ $departments->lastPage() }} halaman</div>
                                   </div>
                                   <div class="stat-icon">
                                        <i class="bi bi-layers-fill"></i>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="trash-card">
                    <div class="trash-card-header">
                         <div>
                              <h5 class="list-title">
                                   <span class="list-title-icon">
                                        <i class="bi bi-archive-fill"></i>
                                   </span>
                                   Department Terhapus
                              </h5>
                              <p class="list-subtitle">
                                   Data menggunakan soft delete dan masih dapat dipulihkan.
                              </p>
                         </div>

                         <form action="{{ route('super-admin.departments.trash') }}" method="GET" class="filter-form">
                              <div class="search-box">
                                   <i class="bi bi-search"></i>
                                   <input type="text" name="search" value="{{ $search }}" class="form-control"
                                        placeholder="Cari kode, nama, atau deskripsi..." autocomplete="off">
                              </div>

                              <button type="submit" class="btn-filter">
                                   <i class="bi bi-funnel-fill"></i>
                                   Cari
                              </button>

                              @if ($search !== '')
                                   <a href="{{ route('super-admin.departments.trash') }}" class="btn-reset">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        Reset
                                   </a>
                              @endif
                         </form>
                    </div>

                    <div class="trash-card-body">
                         <div class="table-responsive">
                              <table class="table trash-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="6%">No</th>
                                             <th>Kode</th>
                                             <th>Department</th>
                                             <th>Status Sebelumnya</th>
                                             <th>Waktu Dihapus</th>
                                             <th width="22%" class="text-center">Aksi</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($departments as $department)
                                             <tr>
                                                  <td>
                                                       <span class="number-badge">
                                                            {{ $departments->firstItem() + $loop->index }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="code-label">
                                                            <i class="bi bi-hash"></i>
                                                            {{ $department->code }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       <span class="department-name">{{ $department->name }}</span>
                                                       <span class="department-description">
                                                            {{ \Illuminate\Support\Str::limit($department->description ?: 'Tidak ada deskripsi department.', 85) }}
                                                       </span>
                                                  </td>

                                                  <td>
                                                       @if ($department->status === 'active')
                                                            <span class="status-badge status-active">
                                                                 <i class="bi bi-check-circle-fill"></i>
                                                                 Aktif
                                                            </span>
                                                       @else
                                                            <span class="status-badge status-inactive">
                                                                 <i class="bi bi-x-circle-fill"></i>
                                                                 Tidak Aktif
                                                            </span>
                                                       @endif
                                                  </td>

                                                  <td>
                                                       <span class="deleted-badge">
                                                            <i class="bi bi-clock-history"></i>
                                                            {{ $department->deleted_at?->format('d M Y, H:i') ?? 'Tidak tersedia' }}
                                                       </span>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="action-group">
                                                            <form action="{{ route('super-admin.departments.restore', $department->id) }}"
                                                                 method="POST" class="d-inline"
                                                                 onsubmit="return confirm('Pulihkan department {{ addslashes($department->name) }}?');">
                                                                 @csrf
                                                                 @method('PATCH')

                                                                 <button type="submit" class="action-btn btn-restore"
                                                                      title="Pulihkan department">
                                                                      <i class="bi bi-arrow-counterclockwise"></i>
                                                                      Pulihkan
                                                                 </button>
                                                            </form>

                                                            <form action="{{ route('super-admin.departments.force-delete', $department->id) }}"
                                                                 method="POST" class="d-inline"
                                                                 onsubmit="return confirm('PERINGATAN: Department {{ addslashes($department->name) }} akan dihapus permanen dan tidak dapat dikembalikan. Lanjutkan?');">
                                                                 @csrf
                                                                 @method('DELETE')

                                                                 <button type="submit" class="action-btn btn-force-delete"
                                                                      title="Hapus permanen">
                                                                      <i class="bi bi-trash3-fill"></i>
                                                                      Permanen
                                                                 </button>
                                                            </form>
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="6" class="empty-state">
                                                       <div class="empty-icon">
                                                            <i class="bi bi-trash3"></i>
                                                       </div>

                                                       @if ($search !== '')
                                                            <h5>Data Tidak Ditemukan</h5>
                                                            <p>Tidak ada department terhapus yang cocok dengan pencarian
                                                                 “{{ $search }}”.</p>
                                                       @else
                                                            <h5>Trash Masih Kosong</h5>
                                                            <p>Belum ada department yang dipindahkan ke tempat sampah.</p>
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($departments->hasPages())
                              <div class="pagination-wrapper">
                                   <div class="pagination-info">
                                        Menampilkan {{ $departments->firstItem() }}–{{ $departments->lastItem() }}
                                        dari {{ $departments->total() }} data terhapus.
                                   </div>

                                   {{ $departments->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
