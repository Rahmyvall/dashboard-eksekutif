@extends('layouts.app')

@section('title', 'Detail Pengeluaran - Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa')

@section('content')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          .xpd-page {
               --xpd-ink: #10233f;
               --xpd-muted: #617692;
               --xpd-line: #d7e2ef;
               --xpd-surface: #ffffff;
               --xpd-primary: #0f766e;
               --xpd-deep: #115e59;
               --xpd-accent: #0ea5e9;
               min-height: calc(100vh - 70px);
               padding: 24px 18px 48px;
               color: var(--xpd-ink);
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 8% 8%, rgba(14, 165, 233, 0.13), transparent 26%),
                    radial-gradient(circle at 93% 10%, rgba(20, 184, 166, 0.11), transparent 30%),
                    radial-gradient(circle at 80% 88%, rgba(59, 130, 246, 0.07), transparent 24%),
                    linear-gradient(160deg, #f8fcff 0%, #f1f7fb 48%, #eef4fa 100%);
          }

          .xpd-wrap {
               max-width: 1180px;
               margin: 0 auto;
          }

          /* ── Hero ── */
          .xpd-hero {
               position: relative;
               overflow: hidden;
               display: grid;
               grid-template-columns: 1fr auto;
               align-items: center;
               gap: 18px;
               padding: 28px 32px;
               margin-bottom: 22px;
               border-radius: 24px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, 0.28);
               background:
                    radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.22), transparent 24%),
                    linear-gradient(124deg, #0f766e 0%, #0369a1 54%, #0ea5e9 100%);
               box-shadow: 0 24px 54px rgba(3, 105, 161, 0.28);
          }

          .xpd-hero::after {
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

          .xpd-hero::before {
               content: '';
               position: absolute;
               width: 200px;
               height: 200px;
               border-radius: 32px;
               left: -38px;
               bottom: -80px;
               transform: rotate(25deg);
               background: rgba(255, 255, 255, 0.10);
               pointer-events: none;
          }

          .xpd-hero>* {
               position: relative;
               z-index: 1;
          }

          .xpd-hero-breadcrumb {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               margin-bottom: 8px;
               font-size: 0.70rem;
               font-weight: 700;
               letter-spacing: 0.07em;
               text-transform: uppercase;
               color: rgba(255, 255, 255, 0.72);
          }

          .xpd-hero-breadcrumb .bi {
               font-size: 10px;
          }

          .xpd-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.15rem, 2vw, 1.75rem);
               font-weight: 700;
               letter-spacing: -0.02em;
               line-height: 1.2;
          }

          .xpd-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, 0.88);
               font-size: 0.88rem;
               line-height: 1.6;
          }

          .xpd-hero-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-top: 14px;
               padding: 6px 12px;
               border: 1px solid rgba(255, 255, 255, 0.35);
               border-radius: 999px;
               background: rgba(255, 255, 255, 0.13);
               font-size: 0.70rem;
               font-weight: 700;
               letter-spacing: 0.04em;
               backdrop-filter: blur(6px);
          }

          .xpd-hero-badge .dot {
               width: 7px;
               height: 7px;
               border-radius: 50%;
               background: #86efac;
               animation: xpdPulse 1.8s infinite;
          }

          @keyframes xpdPulse {

               0%,
               100% {
                    opacity: 1;
                    transform: scale(1);
               }

               50% {
                    opacity: 0.6;
                    transform: scale(1.3);
               }
          }

          .xpd-hero-actions {
               display: flex;
               align-items: center;
               gap: 10px;
               flex-wrap: wrap;
          }

          .xpd-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 44px;
               min-width: 44px;
               height: 44px;
               padding: 0;
               border-radius: 13px;
               text-decoration: none;
               transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
               border: 1px solid rgba(255, 255, 255, 0.8);
               background: #fff;
               color: #0b3b66;
          }

          .xpd-btn-ghost {
               border-color: rgba(255, 255, 255, 0.42);
               background: rgba(255, 255, 255, 0.15);
               color: #fff;
               backdrop-filter: blur(8px);
          }

          .xpd-btn:hover {
               transform: translateY(-2px);
               box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
               text-decoration: none;
          }

          .xpd-btn .bi {
               font-size: 16px;
               width: 16px;
               height: 16px;
               display: inline-flex;
               align-items: center;
               justify-content: center;
          }

          /* ── Alert ── */
          .xpd-alert {
               display: flex;
               align-items: center;
               gap: 10px;
               padding: 13px 18px;
               margin-bottom: 20px;
               border-radius: 14px;
               font-size: 0.88rem;
               font-weight: 600;
               background: #ecfdf5;
               border: 1px solid #6ee7b7;
               color: #065f46;
          }

          /* ── Summary cards ── */
          .xpd-stats {
               display: grid;
               grid-template-columns: repeat(3, 1fr);
               gap: 14px;
               margin-bottom: 20px;
          }

          @media (max-width: 767px) {
               .xpd-stats {
                    grid-template-columns: 1fr;
               }
          }

          .xpd-stat-card {
               display: flex;
               align-items: center;
               gap: 16px;
               padding: 18px 20px;
               border-radius: 18px;
               background: #fff;
               border: 1px solid var(--xpd-line);
               box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
               transition: transform 0.18s, box-shadow 0.18s;
          }

          .xpd-stat-card:hover {
               transform: translateY(-2px);
               box-shadow: 0 10px 28px rgba(15, 23, 42, 0.09);
          }

          .xpd-stat-icon {
               display: flex;
               align-items: center;
               justify-content: center;
               width: 46px;
               min-width: 46px;
               height: 46px;
               border-radius: 14px;
               font-size: 20px;
          }

          .xpd-stat-icon.teal {
               background: #dff7f3;
               color: #0f766e;
          }

          .xpd-stat-icon.blue {
               background: #dbeafe;
               color: #1d4ed8;
          }

          .xpd-stat-icon.amber {
               background: #fef3c7;
               color: #b45309;
          }

          .xpd-stat-label {
               font-size: 0.68rem;
               font-weight: 700;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               color: var(--xpd-muted);
               margin-bottom: 4px;
          }

          .xpd-stat-value {
               font-size: 1.05rem;
               font-weight: 800;
               color: var(--xpd-ink);
               line-height: 1.2;
          }

          /* ── Main card ── */
          .xpd-card {
               border: 1px solid var(--xpd-line);
               border-radius: 22px;
               background: #fff;
               box-shadow: 0 8px 32px rgba(15, 23, 42, 0.06);
               overflow: hidden;
          }

          .xpd-card-header {
               display: flex;
               align-items: center;
               gap: 12px;
               padding: 20px 26px;
               border-bottom: 1px solid var(--xpd-line);
               background: linear-gradient(to right, #f8fafc, #f0f7ff);
          }

          .xpd-card-header-icon {
               display: flex;
               align-items: center;
               justify-content: center;
               width: 38px;
               height: 38px;
               border-radius: 11px;
               background: linear-gradient(135deg, #0f766e, #0369a1);
               color: #fff;
               font-size: 16px;
          }

          .xpd-card-header h5 {
               margin: 0;
               font-weight: 800;
               font-size: 0.95rem;
               color: var(--xpd-ink);
               letter-spacing: -0.01em;
          }

          .xpd-card-header span {
               margin: 0;
               font-size: 0.75rem;
               color: var(--xpd-muted);
          }

          .xpd-card-body {
               padding: 26px;
          }

          /* ── Field items ── */
          .xpd-field {
               display: flex;
               flex-direction: column;
               gap: 4px;
               padding: 16px 18px;
               border-radius: 14px;
               background: #f8fafc;
               border: 1px solid #eaf0f8;
               height: 100%;
          }

          .xpd-field:hover {
               background: #f0f7ff;
               border-color: #bfdbfe;
          }

          .xpd-field-label {
               display: flex;
               align-items: center;
               gap: 6px;
               font-size: 0.68rem;
               font-weight: 700;
               letter-spacing: 0.08em;
               text-transform: uppercase;
               color: var(--xpd-muted);
          }

          .xpd-field-label .bi {
               font-size: 12px;
               color: var(--xpd-accent);
          }

          .xpd-field-value {
               font-size: 0.97rem;
               font-weight: 700;
               color: var(--xpd-ink);
               line-height: 1.45;
          }

          .xpd-field-value.big {
               font-size: 1.2rem;
               font-weight: 800;
               color: #0f766e;
          }

          /* ── Category pill ── */
          .xpd-pill {
               display: inline-flex;
               align-items: center;
               gap: 5px;
               padding: 5px 12px;
               border-radius: 999px;
               font-size: 0.76rem;
               font-weight: 700;
               color: #0f766e;
               background: #dff7f3;
               border: 1px solid #a7f3d0;
          }

          /* ── Description field ── */
          .xpd-field.desc .xpd-field-value {
               font-weight: 500;
               color: #374151;
               line-height: 1.65;
          }

          /* ── Attachment button ── */
          .xpd-attach-btn {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 8px 16px;
               border-radius: 10px;
               font-size: 0.82rem;
               font-weight: 700;
               text-decoration: none;
               border: 1.5px solid #bfdbfe;
               background: #eff6ff;
               color: #1d4ed8;
               transition: background 0.16s, box-shadow 0.16s, transform 0.16s;
          }

          .xpd-attach-btn:hover {
               background: #dbeafe;
               box-shadow: 0 4px 12px rgba(29, 78, 216, 0.14);
               transform: translateY(-1px);
               text-decoration: none;
               color: #1d4ed8;
          }

          /* ── Divider ── */
          .xpd-divider {
               border: none;
               border-top: 1px solid var(--xpd-line);
               margin: 22px 0;
          }

          /* ── Footer meta ── */
          .xpd-meta-row {
               display: flex;
               align-items: center;
               gap: 8px;
               font-size: 0.78rem;
               color: var(--xpd-muted);
               font-weight: 500;
          }

          .xpd-meta-row .bi {
               font-size: 14px;
               color: var(--xpd-accent);
          }

          @media (max-width: 991px) {
               .xpd-hero {
                    grid-template-columns: 1fr;
               }

               .xpd-hero-actions {
                    justify-content: flex-start;
               }
          }
     </style>

     <div class="xpd-page">
          <div class="xpd-wrap">

               {{-- ── Hero ── --}}
               <div class="xpd-hero">
                    <div>
                         <div class="xpd-hero-breadcrumb">
                              <i class="bi bi-house-door"></i>
                              Dashboard
                              <i class="bi bi-chevron-right"></i>
                              <a href="{{ route('super-admin.expenses.index') }}"
                                   style="color:inherit;text-decoration:none;">Pengeluaran</a>
                              <i class="bi bi-chevron-right"></i>
                              Detail
                         </div>
                         <h1>Detail Pengeluaran</h1>
                         <p>Rincian biaya operasional untuk monitoring produktivitas karyawan dan transaksi jasa.</p>
                         <div class="xpd-hero-badge">
                              <span class="dot"></span>
                              Data Tersimpan
                         </div>
                    </div>
                    <div class="xpd-hero-actions">
                         <a href="{{ route('super-admin.expenses.edit', $expense) }}" class="xpd-btn"
                              aria-label="Edit Pengeluaran" title="Edit Pengeluaran">
                              <i class="bi bi-pencil-square"></i>
                         </a>
                         <a href="{{ route('super-admin.expenses.index') }}" class="xpd-btn xpd-btn-ghost"
                              aria-label="Kembali ke Daftar" title="Kembali ke Daftar">
                              <i class="bi bi-arrow-left"></i>
                         </a>
                    </div>
               </div>

               {{-- ── Alert ── --}}
               @if (session('success'))
                    <div class="xpd-alert">
                         <i class="bi bi-check-circle-fill"></i>
                         {{ session('success') }}
                    </div>
               @endif

               {{-- ── Summary Stats ── --}}
               <div class="xpd-stats">
                    <div class="xpd-stat-card">
                         <div class="xpd-stat-icon teal">
                              <i class="bi bi-calendar3"></i>
                         </div>
                         <div>
                              <div class="xpd-stat-label">Tanggal</div>
                              <div class="xpd-stat-value">{{ optional($expense->expense_date)->format('d M Y') ?? '-' }}
                              </div>
                         </div>
                    </div>
                    <div class="xpd-stat-card">
                         <div class="xpd-stat-icon blue">
                              <i class="bi bi-cash-stack"></i>
                         </div>
                         <div>
                              <div class="xpd-stat-label">Nominal</div>
                              <div class="xpd-stat-value">{{ $expense->formatted_amount }}</div>
                         </div>
                    </div>
                    <div class="xpd-stat-card">
                         <div class="xpd-stat-icon amber">
                              <i class="bi bi-tag"></i>
                         </div>
                         <div>
                              <div class="xpd-stat-label">Kategori</div>
                              <div class="xpd-stat-value">{{ ucfirst((string) $expense->category) }}</div>
                         </div>
                    </div>
               </div>

               {{-- ── Main Detail Card ── --}}
               <div class="xpd-card">
                    <div class="xpd-card-header">
                         <div class="xpd-card-header-icon">
                              <i class="bi bi-file-earmark-text"></i>
                         </div>
                         <div>
                              <h5>Informasi Pengeluaran</h5>
                              <span>Rincian lengkap data pengeluaran</span>
                         </div>
                    </div>

                    <div class="xpd-card-body">
                         <div class="row g-3">
                              <div class="col-lg-3 col-md-6">
                                   <div class="xpd-field">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-calendar-event"></i>
                                             Tanggal
                                        </div>
                                        <div class="xpd-field-value">
                                             {{ optional($expense->expense_date)->format('d M Y') ?? '-' }}
                                        </div>
                                        <div style="font-size:0.73rem;color:#94a3b8;margin-top:2px;">
                                             {{ optional($expense->expense_date)->format('l') ?? '' }}
                                        </div>
                                   </div>
                              </div>

                              <div class="col-lg-3 col-md-6">
                                   <div class="xpd-field">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-tag"></i>
                                             Kategori
                                        </div>
                                        <div class="xpd-field-value" style="margin-top:2px;">
                                             <span class="xpd-pill">
                                                  <i class="bi bi-circle-fill" style="font-size:6px;"></i>
                                                  {{ ucfirst((string) $expense->category) }}
                                             </span>
                                        </div>
                                   </div>
                              </div>

                              <div class="col-lg-3 col-md-6">
                                   <div class="xpd-field">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-cash-coin"></i>
                                             Nominal
                                        </div>
                                        <div class="xpd-field-value big">{{ $expense->formatted_amount }}</div>
                                   </div>
                              </div>

                              <div class="col-lg-3 col-md-6">
                                   <div class="xpd-field">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-receipt"></i>
                                             Service Order
                                        </div>
                                        <div class="xpd-field-value">
                                             {{ $expense->serviceOrder?->order_number ?? '-' }}
                                        </div>
                                   </div>
                              </div>

                              <div class="col-12">
                                   <div class="xpd-field desc">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-chat-text"></i>
                                             Deskripsi
                                        </div>
                                        <div class="xpd-field-value">{{ $expense->description ?: '-' }}</div>
                                   </div>
                              </div>
                         </div>

                         <hr class="xpd-divider">

                         <div class="row g-3">
                              <div class="col-md-6">
                                   <div class="xpd-field">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-person-circle"></i>
                                             Dibuat Oleh
                                        </div>
                                        <div class="xpd-field-value">{{ $expense->creator?->name ?? '-' }}</div>
                                   </div>
                              </div>

                              <div class="col-md-6">
                                   <div class="xpd-field">
                                        <div class="xpd-field-label">
                                             <i class="bi bi-paperclip"></i>
                                             Lampiran
                                        </div>
                                        <div class="xpd-field-value" style="margin-top:4px;">
                                             @if ($expense->attachment_url)
                                                  <a href="{{ $expense->attachment_url }}" target="_blank"
                                                       class="xpd-attach-btn" rel="noopener noreferrer">
                                                       <i class="bi bi-file-earmark-arrow-down"></i>
                                                       Lihat Lampiran
                                                  </a>
                                             @else
                                                  <span style="color:#94a3b8;font-weight:500;font-size:0.88rem;">
                                                       <i class="bi bi-dash-circle" style="margin-right:4px;"></i>
                                                       Tidak ada lampiran
                                                  </span>
                                             @endif
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <hr class="xpd-divider">

                         <div class="d-flex flex-wrap gap-4">
                              @if ($expense->created_at)
                                   <div class="xpd-meta-row">
                                        <i class="bi bi-clock-history"></i>
                                        Dibuat: {{ $expense->created_at->format('d M Y, H:i') }}
                                   </div>
                              @endif
                              @if ($expense->updated_at && $expense->updated_at->ne($expense->created_at))
                                   <div class="xpd-meta-row">
                                        <i class="bi bi-pencil"></i>
                                        Diperbarui: {{ $expense->updated_at->format('d M Y, H:i') }}
                                   </div>
                              @endif
                         </div>
                    </div>
               </div>

          </div>
     </div>
@endsection
