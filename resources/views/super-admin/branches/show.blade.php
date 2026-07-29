@extends('layouts.app')

@section('title', 'Detail Cabang')

@push('styles')
     <style>
          .branch-show-page {
               --bsh-primary: #2563eb;
               --bsh-primary-dark: #1d4ed8;
               --bsh-primary-soft: #eff6ff;
               --bsh-success: #16a34a;
               --bsh-danger: #dc2626;
               --bsh-text: #0f172a;
               --bsh-muted: #64748b;
               --bsh-border: #e2e8f0;
               --bsh-surface: #ffffff;
               --bsh-background: #f8fafc;
               min-height: calc(100vh - 70px);
               color: var(--bsh-text);
               background:
                    radial-gradient(circle at top right, rgba(37, 99, 235, .09), transparent 28%),
                    radial-gradient(circle at bottom left, rgba(14, 165, 233, .06), transparent 24%),
                    var(--bsh-background);
          }

          .branch-show-page .show-shell {
               max-width: 1480px;
               margin: 0 auto;
          }

          .branch-show-page .page-hero {
               position: relative;
               overflow: hidden;
               padding: 30px 32px;
               border-radius: 24px;
               color: #fff;
               background:
                    radial-gradient(circle at 91% 12%, rgba(255, 255, 255, .22), transparent 22%),
                    radial-gradient(circle at 75% 100%, rgba(255, 255, 255, .12), transparent 28%),
                    linear-gradient(135deg, #0f172a 0%, #1e3a8a 48%, #2563eb 78%, #0ea5e9 100%);
               box-shadow: 0 22px 48px rgba(30, 64, 175, .22);
          }

          .branch-show-page .page-hero::after {
               content: '';
               position: absolute;
               right: -90px;
               bottom: -125px;
               width: 230px;
               height: 230px;
               border: 34px solid rgba(255, 255, 255, .10);
               border-radius: 50%;
          }

          .branch-show-page .hero-content,
          .branch-show-page .hero-actions {
               position: relative;
               z-index: 2;
          }

          .branch-show-page .hero-heading {
               display: flex;
               align-items: center;
               gap: 16px;
          }

          .branch-show-page .hero-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 58px;
               width: 58px;
               height: 58px;
               border: 1px solid rgba(255, 255, 255, .34);
               border-radius: 18px;
               background: rgba(255, 255, 255, .14);
               backdrop-filter: blur(10px);
               font-size: 1.55rem;
          }

          .branch-show-page .hero-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 5px;
               color: rgba(255, 255, 255, .76);
               font-size: .74rem;
               font-weight: 800;
               letter-spacing: .12em;
               text-transform: uppercase;
          }

          .branch-show-page .hero-title {
               margin: 0 0 6px;
               font-size: clamp(1.7rem, 3vw, 2.25rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .branch-show-page .hero-description {
               max-width: 760px;
               margin: 0;
               color: rgba(255, 255, 255, .82);
               line-height: 1.65;
          }

          .branch-show-page .hero-actions {
               display: flex;
               align-items: center;
               justify-content: flex-end;
               gap: 10px;
               flex-wrap: wrap;
          }

          .branch-show-page .hero-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 46px;
               padding: 11px 17px;
               border-radius: 14px;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               transition: transform .2s ease, background-color .2s ease, color .2s ease, box-shadow .2s ease;
          }

          .branch-show-page .hero-button.edit {
               border: 1px solid rgba(255, 255, 255, .18);
               color: #1e40af;
               background: #fff;
          }

          .branch-show-page .hero-button.edit:hover {
               color: #1e3a8a;
               transform: translateY(-2px);
               box-shadow: 0 12px 24px rgba(15, 23, 42, .16);
          }

          .branch-show-page .hero-button.print,
          .branch-show-page .hero-button.back {
               border: 1px solid rgba(255, 255, 255, .38);
               color: #fff;
               background: rgba(255, 255, 255, .11);
               backdrop-filter: blur(8px);
          }

          .branch-show-page .hero-button.print:hover,
          .branch-show-page .hero-button.back:hover {
               color: #1e40af;
               background: #fff;
               transform: translateY(-2px);
          }

          .branch-show-page .detail-layout {
               display: grid;
               grid-template-columns: minmax(0, 1fr) 350px;
               gap: 24px;
               align-items: start;
          }

          .branch-show-page .surface-card {
               border: 1px solid rgba(226, 232, 240, .96);
               border-radius: 22px;
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 16px 42px rgba(15, 23, 42, .07);
          }

          .branch-show-page .profile-card {
               overflow: hidden;
          }

          .branch-show-page .profile-cover {
               position: relative;
               min-height: 175px;
               padding: 30px;
               color: #fff;
               background:
                    radial-gradient(circle at 88% 18%, rgba(255, 255, 255, .25), transparent 20%),
                    linear-gradient(135deg, #1e3a8a, #2563eb 58%, #0ea5e9);
          }

          .branch-show-page .profile-cover::after {
               content: '';
               position: absolute;
               right: -55px;
               bottom: -85px;
               width: 165px;
               height: 165px;
               border: 22px solid rgba(255, 255, 255, .10);
               border-radius: 50%;
          }

          .branch-show-page .profile-cover-content {
               position: relative;
               z-index: 2;
               display: flex;
               align-items: center;
               gap: 20px;
          }

          .branch-show-page .building-avatar {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 78px;
               width: 78px;
               height: 78px;
               border: 5px solid rgba(255, 255, 255, .90);
               border-radius: 23px;
               background: rgba(255, 255, 255, .14);
               box-shadow: 0 14px 30px rgba(15, 23, 42, .18);
               font-size: 2rem;
          }

          .branch-show-page .profile-code {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 6px 10px;
               margin-bottom: 8px;
               border: 1px solid rgba(255, 255, 255, .27);
               border-radius: 999px;
               background: rgba(255, 255, 255, .12);
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .06em;
               text-transform: uppercase;
          }

          .branch-show-page .profile-name {
               margin: 0 0 7px;
               font-size: clamp(1.35rem, 3vw, 2rem);
               font-weight: 850;
               letter-spacing: -.025em;
          }

          .branch-show-page .profile-address {
               display: flex;
               align-items: flex-start;
               gap: 8px;
               max-width: 760px;
               margin: 0;
               color: rgba(255, 255, 255, .82);
               font-size: .82rem;
               line-height: 1.6;
          }

          .branch-show-page .profile-address i {
               margin-top: 3px;
          }

          .branch-show-page .detail-section {
               padding: 26px;
               border-bottom: 1px solid var(--bsh-border);
          }

          .branch-show-page .detail-section:last-child {
               border-bottom: 0;
          }

          .branch-show-page .section-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
               margin-bottom: 20px;
          }

          .branch-show-page .section-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 43px;
               width: 43px;
               height: 43px;
               border-radius: 13px;
               color: var(--bsh-primary);
               background: var(--bsh-primary-soft);
               font-size: 1.1rem;
          }

          .branch-show-page .section-heading h2 {
               margin: 0 0 4px;
               font-size: 1.02rem;
               font-weight: 850;
          }

          .branch-show-page .section-heading p {
               margin: 0;
               color: var(--bsh-muted);
               font-size: .78rem;
               line-height: 1.55;
          }

          .branch-show-page .info-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .branch-show-page .info-item {
               display: flex;
               align-items: flex-start;
               gap: 13px;
               min-height: 92px;
               padding: 16px;
               border: 1px solid var(--bsh-border);
               border-radius: 16px;
               background: #fff;
               transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
          }

          .branch-show-page .info-item:hover {
               border-color: #bfdbfe;
               transform: translateY(-2px);
               box-shadow: 0 10px 22px rgba(37, 99, 235, .07);
          }

          .branch-show-page .info-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 42px;
               width: 42px;
               height: 42px;
               border-radius: 13px;
               color: var(--bsh-primary);
               background: var(--bsh-primary-soft);
               font-size: 1.05rem;
          }

          .branch-show-page .info-copy {
               min-width: 0;
          }

          .branch-show-page .info-label {
               display: block;
               margin-bottom: 5px;
               color: #94a3b8;
               font-size: .67rem;
               font-weight: 800;
               letter-spacing: .06em;
               text-transform: uppercase;
          }

          .branch-show-page .info-value {
               display: block;
               color: #334155;
               font-size: .86rem;
               font-weight: 750;
               line-height: 1.55;
               overflow-wrap: anywhere;
          }

          .branch-show-page .info-value a {
               color: inherit;
               text-decoration: none;
          }

          .branch-show-page .info-value a:hover {
               color: var(--bsh-primary);
               text-decoration: underline;
          }

          .branch-show-page .address-box {
               display: flex;
               align-items: flex-start;
               gap: 14px;
               padding: 18px;
               border: 1px solid #dbeafe;
               border-radius: 17px;
               background: #f8fbff;
          }

          .branch-show-page .address-box i {
               flex: 0 0 auto;
               margin-top: 2px;
               color: #ef4444;
               font-size: 1.2rem;
          }

          .branch-show-page .address-box p {
               margin: 0;
               color: #475569;
               font-size: .84rem;
               line-height: 1.75;
               white-space: pre-line;
          }

          .branch-show-page .side-column {
               position: sticky;
               top: 22px;
               display: grid;
               gap: 20px;
          }

          .branch-show-page .status-card {
               overflow: hidden;
          }

          .branch-show-page .status-header {
               padding: 20px 21px;
               border-bottom: 1px solid var(--bsh-border);
          }

          .branch-show-page .status-header h2,
          .branch-show-page .meta-title,
          .branch-show-page .action-title {
               margin: 0;
               font-size: .92rem;
               font-weight: 850;
          }

          .branch-show-page .status-body {
               padding: 22px;
               text-align: center;
          }

          .branch-show-page .status-visual {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 78px;
               height: 78px;
               margin-bottom: 15px;
               border-radius: 24px;
               font-size: 2rem;
          }

          .branch-show-page .status-visual.active {
               color: #15803d;
               background: #dcfce7;
               box-shadow: 0 0 0 9px #f0fdf4;
          }

          .branch-show-page .status-visual.inactive {
               color: #b91c1c;
               background: #fee2e2;
               box-shadow: 0 0 0 9px #fef2f2;
          }

          .branch-show-page .status-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 8px 13px;
               border-radius: 999px;
               font-size: .76rem;
               font-weight: 850;
          }

          .branch-show-page .status-badge::before {
               content: '';
               width: 8px;
               height: 8px;
               border-radius: 50%;
          }

          .branch-show-page .status-badge.active {
               color: #166534;
               background: #dcfce7;
          }

          .branch-show-page .status-badge.active::before {
               background: #22c55e;
          }

          .branch-show-page .status-badge.inactive {
               color: #991b1b;
               background: #fee2e2;
          }

          .branch-show-page .status-badge.inactive::before {
               background: #ef4444;
          }

          .branch-show-page .status-description {
               margin: 14px 0 0;
               color: var(--bsh-muted);
               font-size: .74rem;
               line-height: 1.6;
          }

          .branch-show-page .meta-card,
          .branch-show-page .action-card {
               padding: 21px;
          }

          .branch-show-page .meta-title,
          .branch-show-page .action-title {
               display: flex;
               align-items: center;
               gap: 10px;
               margin-bottom: 16px;
          }

          .branch-show-page .meta-title i,
          .branch-show-page .action-title i {
               color: var(--bsh-primary);
          }

          .branch-show-page .meta-list {
               display: grid;
               gap: 13px;
          }

          .branch-show-page .meta-row {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 15px;
               padding-bottom: 12px;
               border-bottom: 1px dashed #cbd5e1;
               font-size: .74rem;
          }

          .branch-show-page .meta-row:last-child {
               padding-bottom: 0;
               border-bottom: 0;
          }

          .branch-show-page .meta-label {
               color: var(--bsh-muted);
          }

          .branch-show-page .meta-value {
               color: #334155;
               font-weight: 750;
               text-align: right;
          }

          .branch-show-page .action-list {
               display: grid;
               gap: 10px;
          }

          .branch-show-page .action-button {
               display: flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               width: 100%;
               min-height: 45px;
               padding: 10px 14px;
               border-radius: 13px;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               transition: transform .2s ease, background-color .2s ease, box-shadow .2s ease;
          }

          .branch-show-page .action-button.edit {
               border: 0;
               color: #fff;
               background: linear-gradient(135deg, var(--bsh-primary), var(--bsh-primary-dark));
               box-shadow: 0 10px 21px rgba(37, 99, 235, .20);
          }

          .branch-show-page .action-button.edit:hover {
               color: #fff;
               transform: translateY(-2px);
               box-shadow: 0 14px 25px rgba(37, 99, 235, .25);
          }

          .branch-show-page .action-button.print {
               border: 1px solid #bfdbfe;
               color: #1d4ed8;
               background: #eff6ff;
          }

          .branch-show-page .action-button.print:hover {
               color: #1e40af;
               background: #dbeafe;
               transform: translateY(-2px);
          }

          .branch-show-page .action-button.back {
               border: 1px solid #cbd5e1;
               color: #475569;
               background: #fff;
          }

          .branch-show-page .action-button.back:hover {
               color: var(--bsh-text);
               background: #f8fafc;
          }

          .branch-print-sheet {
               display: none;
          }

          @media (max-width: 1199.98px) {
               .branch-show-page .detail-layout {
                    grid-template-columns: minmax(0, 1fr) 310px;
               }
          }

          @media (max-width: 991.98px) {
               .branch-show-page .detail-layout {
                    grid-template-columns: 1fr;
               }

               .branch-show-page .side-column {
                    position: static;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
               }
          }

          @media (max-width: 767.98px) {
               .branch-show-page {
                    padding-top: 1rem !important;
               }

               .branch-show-page .page-hero {
                    padding: 24px;
                    border-radius: 20px;
               }

               .branch-show-page .hero-wrapper {
                    align-items: flex-start !important;
                    flex-direction: column;
               }

               .branch-show-page .hero-actions {
                    width: 100%;
                    justify-content: stretch;
               }

               .branch-show-page .hero-button {
                    flex: 1 1 0;
               }

               .branch-show-page .profile-cover {
                    padding: 24px;
               }

               .branch-show-page .profile-cover-content {
                    align-items: flex-start;
               }

               .branch-show-page .building-avatar {
                    width: 64px;
                    height: 64px;
                    flex-basis: 64px;
                    border-radius: 19px;
               }

               .branch-show-page .detail-section {
                    padding: 21px;
               }

               .branch-show-page .info-grid,
               .branch-show-page .side-column {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 479.98px) {
               .branch-show-page .hero-actions {
                    flex-direction: column;
               }

               .branch-show-page .hero-button {
                    width: 100%;
               }

               .branch-show-page .profile-cover-content {
                    flex-direction: column;
               }
          }

          @media print {
               @page {
                    size: A4 portrait;
                    margin: 14mm;
               }

               html,
               body {
                    width: 210mm;
                    min-height: 297mm;
                    margin: 0 !important;
                    padding: 0 !important;
                    color: #111827 !important;
                    background: #fff !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
               }

               body * {
                    visibility: hidden !important;
               }

               .branch-print-sheet,
               .branch-print-sheet * {
                    visibility: visible !important;
               }

               .branch-print-sheet {
                    position: absolute;
                    inset: 0;
                    display: block !important;
                    width: 100%;
                    color: #111827;
                    background: #fff;
                    font-family: Arial, Helvetica, sans-serif;
               }

               .branch-print-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 20px;
                    padding-bottom: 18px;
                    border-bottom: 3px solid #2563eb;
               }

               .branch-print-brand {
                    display: flex;
                    align-items: center;
                    gap: 14px;
               }

               .branch-print-logo {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 56px;
                    height: 56px;
                    border-radius: 15px;
                    color: #fff;
                    background: #2563eb;
                    font-size: 26px;
               }

               .branch-print-header h1 {
                    margin: 0 0 4px;
                    font-size: 22px;
                    font-weight: 800;
               }

               .branch-print-header p {
                    margin: 0;
                    color: #64748b;
                    font-size: 10px;
               }

               .branch-print-code {
                    padding: 9px 13px;
                    border: 1px solid #bfdbfe;
                    border-radius: 9px;
                    color: #1d4ed8;
                    background: #eff6ff;
                    font-size: 12px;
                    font-weight: 800;
                    letter-spacing: .06em;
               }

               .branch-print-summary {
                    display: grid;
                    grid-template-columns: minmax(0, 1fr) 150px;
                    gap: 18px;
                    margin: 22px 0;
                    padding: 20px;
                    border: 1px solid #dbeafe;
                    border-radius: 14px;
                    background: #f8fbff;
               }

               .branch-print-label {
                    margin-bottom: 5px;
                    color: #64748b;
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: .08em;
                    text-transform: uppercase;
               }

               .branch-print-name {
                    margin: 0 0 8px;
                    font-size: 20px;
                    font-weight: 800;
               }

               .branch-print-address {
                    margin: 0;
                    color: #475569;
                    font-size: 12px;
                    line-height: 1.65;
                    white-space: pre-line;
               }

               .branch-print-status-wrap {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-left: 1px solid #dbeafe;
               }

               .branch-print-status {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    padding: 8px 13px;
                    border-radius: 999px;
                    font-size: 11px;
                    font-weight: 800;
               }

               .branch-print-status::before {
                    content: '';
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
               }

               .branch-print-status.active {
                    color: #166534;
                    background: #dcfce7;
               }

               .branch-print-status.active::before {
                    background: #22c55e;
               }

               .branch-print-status.inactive {
                    color: #991b1b;
                    background: #fee2e2;
               }

               .branch-print-status.inactive::before {
                    background: #ef4444;
               }

               .branch-print-section-title {
                    margin: 0 0 10px;
                    color: #1e3a8a;
                    font-size: 13px;
                    font-weight: 800;
                    letter-spacing: .04em;
                    text-transform: uppercase;
               }

               .branch-print-table {
                    width: 100%;
                    border-collapse: collapse;
                    table-layout: fixed;
               }

               .branch-print-table th,
               .branch-print-table td {
                    padding: 11px 12px;
                    border: 1px solid #e2e8f0;
                    text-align: left;
                    vertical-align: top;
                    font-size: 11px;
                    line-height: 1.5;
                    overflow-wrap: anywhere;
               }

               .branch-print-table th {
                    width: 28%;
                    color: #475569;
                    background: #f8fafc;
                    font-weight: 700;
               }

               .branch-print-footer {
                    display: flex;
                    align-items: flex-end;
                    justify-content: space-between;
                    gap: 20px;
                    margin-top: 28px;
                    padding-top: 14px;
                    border-top: 1px solid #cbd5e1;
                    color: #64748b;
                    font-size: 9px;
               }

               .branch-print-signature {
                    width: 190px;
                    color: #334155;
                    text-align: center;
               }

               .branch-print-signature-space {
                    height: 58px;
               }

               .branch-print-signature-line {
                    padding-top: 6px;
                    border-top: 1px solid #64748b;
                    font-size: 10px;
                    font-weight: 700;
               }
          }
     </style>
@endpush

@section('content')
     @php
          $currentUser = auth()->user();
          $canManageBranches =
              ($currentUser?->hasRole('super_admin') ?? false) ||
              ($currentUser?->hasRole('admin_operasional') ?? false);
     @endphp
     <div class="container-fluid branch-show-page py-4 px-3 px-lg-4">
          <div class="show-shell">
               <header class="page-hero mb-4">
                    <div class="hero-wrapper d-flex align-items-center justify-content-between gap-4">
                         <div class="hero-content">
                              <div class="hero-heading">
                                   <span class="hero-icon">
                                        <i class="bi bi-building-check"></i>
                                   </span>
                                   <div>
                                        <div class="hero-eyebrow">
                                             <i class="bi bi-diagram-3-fill"></i>
                                             Branch Management
                                        </div>
                                        <h1 class="hero-title">Detail Data Cabang</h1>
                                        <p class="hero-description">
                                             Lihat identitas, penanggung jawab, informasi kontak, status operasional, dan
                                             riwayat pencatatan cabang.
                                        </p>
                                   </div>
                              </div>
                         </div>

                         <div class="hero-actions">
                              <button type="button" class="hero-button print" onclick="window.print()">
                                   <i class="bi bi-printer-fill"></i>
                                   Cetak
                              </button>
                              @if ($canManageBranches && \Illuminate\Support\Facades\Route::has('branches.edit'))
                                   <a href="{{ route('branches.edit', $branch->id) }}" class="hero-button edit">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit Cabang
                                   </a>
                              @endif
                              <a href="{{ route('branches.index') }}" class="hero-button back">
                                   <i class="bi bi-arrow-left"></i>
                                   Kembali
                              </a>
                         </div>
                    </div>
               </header>

               <div class="detail-layout">
                    <main class="surface-card profile-card">
                         <section class="profile-cover">
                              <div class="profile-cover-content">
                                   <span class="building-avatar">
                                        <i class="bi bi-buildings-fill"></i>
                                   </span>
                                   <div>
                                        <span class="profile-code">
                                             <i class="bi bi-upc-scan"></i>
                                             {{ $branch->branch_code }}
                                        </span>
                                        <h2 class="profile-name">{{ $branch->branch_name }}</h2>
                                        <p class="profile-address">
                                             <i class="bi bi-geo-alt-fill"></i>
                                             <span>{{ $branch->address }}</span>
                                        </p>
                                   </div>
                              </div>
                         </section>

                         <section class="detail-section">
                              <div class="section-heading">
                                   <span class="section-icon">
                                        <i class="bi bi-info-circle"></i>
                                   </span>
                                   <div>
                                        <h2>Informasi Cabang</h2>
                                        <p>Identitas dan penanggung jawab utama cabang perusahaan.</p>
                                   </div>
                              </div>

                              <div class="info-grid">
                                   <article class="info-item">
                                        <span class="info-icon"><i class="bi bi-upc-scan"></i></span>
                                        <div class="info-copy">
                                             <span class="info-label">Kode Cabang</span>
                                             <span class="info-value">{{ $branch->branch_code }}</span>
                                        </div>
                                   </article>

                                   <article class="info-item">
                                        <span class="info-icon"><i class="bi bi-buildings"></i></span>
                                        <div class="info-copy">
                                             <span class="info-label">Nama Cabang</span>
                                             <span class="info-value">{{ $branch->branch_name }}</span>
                                        </div>
                                   </article>

                                   <article class="info-item">
                                        <span class="info-icon"><i class="bi bi-person-badge"></i></span>
                                        <div class="info-copy">
                                             <span class="info-label">Kepala Cabang</span>
                                             <span class="info-value">
                                                  {{ $branch->manager?->name ?? 'Belum ditentukan' }}
                                             </span>
                                        </div>
                                   </article>

                                   <article class="info-item">
                                        <span class="info-icon"><i class="bi bi-activity"></i></span>
                                        <div class="info-copy">
                                             <span class="info-label">Status Operasional</span>
                                             <span class="info-value">
                                                  {{ (int) $branch->status === 1 ? 'Aktif' : 'Nonaktif' }}
                                             </span>
                                        </div>
                                   </article>
                              </div>
                         </section>

                         <section class="detail-section">
                              <div class="section-heading">
                                   <span class="section-icon">
                                        <i class="bi bi-telephone"></i>
                                   </span>
                                   <div>
                                        <h2>Informasi Kontak</h2>
                                        <p>Kontak resmi yang digunakan untuk komunikasi operasional cabang.</p>
                                   </div>
                              </div>

                              <div class="info-grid">
                                   <article class="info-item">
                                        <span class="info-icon"><i class="bi bi-telephone-fill"></i></span>
                                        <div class="info-copy">
                                             <span class="info-label">Nomor Telepon</span>
                                             <span class="info-value">
                                                  <a href="tel:{{ $branch->phone }}">{{ $branch->phone }}</a>
                                             </span>
                                        </div>
                                   </article>

                                   <article class="info-item">
                                        <span class="info-icon"><i class="bi bi-envelope-fill"></i></span>
                                        <div class="info-copy">
                                             <span class="info-label">Email Cabang</span>
                                             <span class="info-value">
                                                  <a href="mailto:{{ $branch->email }}">{{ $branch->email }}</a>
                                             </span>
                                        </div>
                                   </article>
                              </div>
                         </section>

                         <section class="detail-section">
                              <div class="section-heading">
                                   <span class="section-icon">
                                        <i class="bi bi-geo-alt"></i>
                                   </span>
                                   <div>
                                        <h2>Alamat Lengkap</h2>
                                        <p>Lokasi resmi cabang yang tersimpan pada sistem.</p>
                                   </div>
                              </div>

                              <div class="address-box">
                                   <i class="bi bi-geo-alt-fill"></i>
                                   <p>{{ $branch->address }}</p>
                              </div>
                         </section>
                    </main>

                    <aside class="side-column">
                         <section class="surface-card status-card">
                              <div class="status-header">
                                   <h2>Status Cabang</h2>
                              </div>
                              <div class="status-body">
                                   @if ((int) $branch->status === 1)
                                        <div class="status-visual active">
                                             <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                        <div>
                                             <span class="status-badge active">Aktif</span>
                                             <p class="status-description">
                                                  Cabang tercatat aktif dan dapat menjalankan kegiatan operasional.
                                             </p>
                                        </div>
                                   @else
                                        <div class="status-visual inactive">
                                             <i class="bi bi-x-circle-fill"></i>
                                        </div>
                                        <div>
                                             <span class="status-badge inactive">Nonaktif</span>
                                             <p class="status-description">
                                                  Cabang sedang tidak aktif dan tidak menjalankan kegiatan operasional.
                                             </p>
                                        </div>
                                   @endif
                              </div>
                         </section>

                         <section class="surface-card meta-card">
                              <h2 class="meta-title">
                                   <i class="bi bi-clock-history"></i>
                                   Riwayat Data
                              </h2>
                              <div class="meta-list">
                                   <div class="meta-row">
                                        <span class="meta-label">ID Data</span>
                                        <span class="meta-value">#{{ $branch->id }}</span>
                                   </div>
                                   <div class="meta-row">
                                        <span class="meta-label">Dibuat</span>
                                        <span class="meta-value">
                                             {{ optional($branch->created_at)->format('d-m-Y H:i') ?? '-' }}
                                        </span>
                                   </div>
                                   <div class="meta-row">
                                        <span class="meta-label">Diperbarui</span>
                                        <span class="meta-value">
                                             {{ optional($branch->updated_at)->format('d-m-Y H:i') ?? '-' }}
                                        </span>
                                   </div>
                              </div>
                         </section>

                         <section class="surface-card action-card">
                              <h2 class="action-title">
                                   <i class="bi bi-lightning-charge-fill"></i>
                                   Aksi Cepat
                              </h2>
                              <div class="action-list">
                                   @if ($canManageBranches && \Illuminate\Support\Facades\Route::has('branches.edit'))
                                        <a href="{{ route('branches.edit', $branch->id) }}" class="action-button edit">
                                             <i class="bi bi-pencil-square"></i>
                                             Edit Data Cabang
                                        </a>
                                   @endif
                                   <button type="button" class="action-button print" onclick="window.print()">
                                        <i class="bi bi-printer-fill"></i>
                                        Cetak Profil Cabang
                                   </button>
                                   <a href="{{ route('branches.index') }}" class="action-button back">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali ke Daftar
                                   </a>
                              </div>
                         </section>
                    </aside>
               </div>
          </div>
     </div>

     {{-- Printable A4 Profile --}}
     <section class="branch-print-sheet" aria-hidden="true">
          <header class="branch-print-header">
               <div class="branch-print-brand">
                    <div class="branch-print-logo">
                         <i class="bi bi-buildings-fill"></i>
                    </div>
                    <div>
                         <h1>PROFIL DATA CABANG</h1>
                         <p>Dokumen informasi cabang perusahaan</p>
                    </div>
               </div>
               <div class="branch-print-code">{{ $branch->branch_code }}</div>
          </header>

          <section class="branch-print-summary">
               <div>
                    <div class="branch-print-label">Nama Cabang</div>
                    <h2 class="branch-print-name">{{ $branch->branch_name }}</h2>
                    <p class="branch-print-address">{{ $branch->address }}</p>
               </div>
               <div class="branch-print-status-wrap">
                    <span class="branch-print-status {{ (int) $branch->status === 1 ? 'active' : 'inactive' }}">
                         {{ (int) $branch->status === 1 ? 'Aktif' : 'Nonaktif' }}
                    </span>
               </div>
          </section>

          <h3 class="branch-print-section-title">Informasi Lengkap</h3>
          <table class="branch-print-table">
               <tbody>
                    <tr>
                         <th>Kode Cabang</th>
                         <td>{{ $branch->branch_code }}</td>
                    </tr>
                    <tr>
                         <th>Nama Cabang</th>
                         <td>{{ $branch->branch_name }}</td>
                    </tr>
                    <tr>
                         <th>Kepala Cabang</th>
                         <td>{{ $branch->manager?->name ?? 'Belum ditentukan' }}</td>
                    </tr>
                    <tr>
                         <th>Nomor Telepon</th>
                         <td>{{ $branch->phone }}</td>
                    </tr>
                    <tr>
                         <th>Email Cabang</th>
                         <td>{{ $branch->email }}</td>
                    </tr>
                    <tr>
                         <th>Status Operasional</th>
                         <td>{{ (int) $branch->status === 1 ? 'Aktif' : 'Nonaktif' }}</td>
                    </tr>
                    <tr>
                         <th>Alamat Lengkap</th>
                         <td style="white-space: pre-line;">{{ $branch->address }}</td>
                    </tr>
                    <tr>
                         <th>Tanggal Dibuat</th>
                         <td>{{ optional($branch->created_at)->format('d-m-Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                         <th>Terakhir Diperbarui</th>
                         <td>{{ optional($branch->updated_at)->format('d-m-Y H:i') ?? '-' }}</td>
                    </tr>
               </tbody>
          </table>

          <footer class="branch-print-footer">
               <div>
                    <div>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</div>
                    <div>Dokumen ini dihasilkan melalui sistem Branch Management.</div>
               </div>
               <div class="branch-print-signature">
                    <div>Penanggung Jawab</div>
                    <div class="branch-print-signature-space"></div>
                    <div class="branch-print-signature-line">
                         {{ $branch->manager?->name ?? '________________________' }}
                    </div>
               </div>
          </footer>
     </section>
@endsection
