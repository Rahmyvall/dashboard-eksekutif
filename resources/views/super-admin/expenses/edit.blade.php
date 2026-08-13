@extends('layouts.app')

@section('title', 'Edit Pengeluaran - Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa')

@section('content')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          /* ── Root variables ── */
          .xpe-page {
               --xpe-ink: #10233f;
               --xpe-muted: #617692;
               --xpe-line: #d7e2ef;
               --xpe-surface: #ffffff;
               --xpe-primary: #0f766e;
               --xpe-accent: #0ea5e9;
               min-height: calc(100vh - 70px);
               padding: 24px 18px 52px;
               color: var(--xpe-ink);
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 8% 8%, rgba(14, 165, 233, 0.13), transparent 26%),
                    radial-gradient(circle at 93% 10%, rgba(20, 184, 166, 0.11), transparent 30%),
                    radial-gradient(circle at 80% 88%, rgba(59, 130, 246, 0.07), transparent 24%),
                    linear-gradient(160deg, #f8fcff 0%, #f1f7fb 48%, #eef4fa 100%);
          }

          .xpe-wrap {
               max-width: 1100px;
               margin: 0 auto;
          }

          /* ── Hero ── */
          .xpe-hero {
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

          .xpe-hero::after {
               content: '';
               position: absolute;
               width: 300px;
               height: 300px;
               border-radius: 50%;
               right: -100px;
               top: -140px;
               background: rgba(255, 255, 255, 0.12);
               pointer-events: none;
          }

          .xpe-hero::before {
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

          .xpe-hero>* {
               position: relative;
               z-index: 1;
          }

          .xpe-hero-breadcrumb {
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

          .xpe-hero-breadcrumb a {
               color: inherit;
               text-decoration: none;
          }

          .xpe-hero-breadcrumb .bi {
               font-size: 10px;
          }

          .xpe-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.15rem, 2vw, 1.75rem);
               font-weight: 700;
               letter-spacing: -0.02em;
               line-height: 1.2;
          }

          .xpe-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, 0.88);
               font-size: 0.88rem;
               line-height: 1.6;
          }

          .xpe-hero-badge {
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

          .xpe-hero-badge .bi {
               font-size: 12px;
               color: #fde68a;
          }

          .xpe-hero-actions {
               display: flex;
               align-items: center;
               gap: 10px;
          }

          .xpe-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 44px;
               min-width: 44px;
               height: 44px;
               padding: 0;
               border-radius: 13px;
               text-decoration: none;
               transition: transform 0.18s, box-shadow 0.18s;
               border: 1px solid rgba(255, 255, 255, 0.8);
               background: #fff;
               color: #0b3b66;
          }

          .xpe-btn-ghost {
               border-color: rgba(255, 255, 255, 0.42);
               background: rgba(255, 255, 255, 0.15);
               color: #fff;
               backdrop-filter: blur(8px);
          }

          .xpe-btn:hover {
               transform: translateY(-2px);
               box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
               text-decoration: none;
          }

          .xpe-btn .bi {
               font-size: 16px;
               width: 16px;
               height: 16px;
               display: inline-flex;
               align-items: center;
               justify-content: center;
          }

          /* ── Error alert ── */
          .xpe-alert-danger {
               display: flex;
               gap: 14px;
               padding: 16px 20px;
               margin-bottom: 20px;
               border-radius: 14px;
               background: #fff1f2;
               border: 1px solid #fca5a5;
               color: #991b1b;
          }

          .xpe-alert-danger .xpe-alert-icon {
               font-size: 20px;
               flex-shrink: 0;
               color: #ef4444;
               margin-top: 1px;
          }

          .xpe-alert-danger ul {
               margin: 0;
               padding-left: 18px;
               font-size: 0.86rem;
               font-weight: 600;
               line-height: 1.7;
          }

          @media (max-width: 991px) {
               .xpe-hero {
                    grid-template-columns: 1fr;
               }

               .xpe-hero-actions {
                    justify-content: flex-start;
               }
          }
     </style>

     <div class="xpe-page">
          <div class="xpe-wrap">

               {{-- ── Hero ── --}}
               <div class="xpe-hero">
                    <div>
                         <div class="xpe-hero-breadcrumb">
                              <i class="bi bi-house-door"></i>
                              Dashboard
                              <i class="bi bi-chevron-right"></i>
                              <a href="{{ route('super-admin.expenses.index') }}">Pengeluaran</a>
                              <i class="bi bi-chevron-right"></i>
                              <a href="{{ route('super-admin.expenses.show', $expense) }}">Detail</a>
                              <i class="bi bi-chevron-right"></i>
                              Edit
                         </div>
                         <h1>Edit Pengeluaran</h1>
                         <p>Perbarui detail biaya agar pelaporan produktivitas dan transaksi tetap akurat.</p>
                         <div class="xpe-hero-badge">
                              <i class="bi bi-pencil-square"></i>
                              Mode Edit
                         </div>
                    </div>
                    <a href="{{ route('super-admin.expenses.show', $expense) }}"
                         class="btn btn-light border-0 d-inline-flex align-items-center justify-content-center icon-only"
                         aria-label="Detail" title="Detail">
                         <i class="bi bi-eye" aria-hidden="true"></i>
                    </a>
               </div>

               {{-- ── Validation errors ── --}}
               @if ($errors->any())
                    <div class="xpe-alert-danger">
                         <i class="bi bi-exclamation-triangle-fill xpe-alert-icon"></i>
                         <ul>
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <form action="{{ route('super-admin.expenses.update', $expense) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('super-admin.expenses.partials.form', [
                        'expense' => $expense,
                        'orders' => $orders,
                        'selectedOrder' => null,
                    ])
               </form>

          </div>
     </div>
@endsection
