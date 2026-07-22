<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="utf-8">

     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <meta name="description" content="Dashboard Eksekutif Monitoring Produktivitas Karyawan dan Transaksi Jasa">

     <meta name="author" content="Administrator">

     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/assets/img/logo.png') }}">

     <title>Login | Dashboard Eksekutif</title>

     <!-- Font Awesome -->
     <link href="{{ asset('backend/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

     <!-- Bootstrap/Cassie -->
     <link rel="stylesheet" href="{{ asset('backend/assets/css/cassie.css') }}">

     <style>
          :root {
               --primary: #2563eb;
               --primary-dark: #1d4ed8;
               --secondary: #06b6d4;
               --success: #10b981;
               --dark: #0f172a;
               --dark-soft: #1e293b;
               --text: #334155;
               --muted: #64748b;
               --border: #dbe4f0;
               --light: #f8fafc;
               --white: #ffffff;
          }

          * {
               box-sizing: border-box;
          }

          html,
          body {
               min-height: 100%;
          }

          body {
               margin: 0;
               min-height: 100vh;
               font-family: Arial, Helvetica, sans-serif;
               color: var(--text);
               background: #eef4ff;
          }

          a {
               text-decoration: none;
          }

          .login-page {
               position: relative;
               display: grid;
               grid-template-columns: minmax(0, 1.15fr) minmax(420px, 0.85fr);
               min-height: 100vh;
               overflow: hidden;
          }

          /* =========================================================
           BAGIAN KIRI
        ========================================================= */

          .login-hero {
               position: relative;
               display: flex;
               flex-direction: column;
               justify-content: space-between;
               min-height: 100vh;
               padding: 45px 65px 35px;
               overflow: hidden;
               color: var(--white);
               background:
                    radial-gradient(circle at 15% 20%,
                         rgba(6, 182, 212, 0.25),
                         transparent 35%),
                    radial-gradient(circle at 85% 80%,
                         rgba(59, 130, 246, 0.35),
                         transparent 38%),
                    linear-gradient(135deg,
                         #071426 0%,
                         #0f2f5f 48%,
                         #1d4ed8 100%);
          }

          .login-hero::before,
          .login-hero::after {
               position: absolute;
               content: "";
               border-radius: 50%;
               pointer-events: none;
          }

          .login-hero::before {
               top: -180px;
               right: -130px;
               width: 420px;
               height: 420px;
               border: 1px solid rgba(255, 255, 255, 0.14);
          }

          .login-hero::after {
               bottom: -230px;
               left: -170px;
               width: 500px;
               height: 500px;
               background: rgba(255, 255, 255, 0.04);
          }

          .brand {
               position: relative;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               gap: 14px;
               color: var(--white);
          }

          .brand-icon {
               display: flex;
               align-items: center;
               justify-content: center;
               width: 52px;
               height: 52px;
               border: 1px solid rgba(255, 255, 255, 0.24);
               border-radius: 16px;
               font-size: 22px;
               color: var(--white);
               background: rgba(255, 255, 255, 0.12);
               box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
               backdrop-filter: blur(8px);
          }

          .brand-name {
               display: block;
               margin: 0;
               font-size: 20px;
               font-weight: 700;
               line-height: 1.2;
          }

          .brand-description {
               display: block;
               margin-top: 3px;
               font-size: 12px;
               color: rgba(255, 255, 255, 0.72);
          }

          .hero-content {
               position: relative;
               z-index: 2;
               width: 100%;
               max-width: 760px;
               margin: auto;
               padding: 25px 0;
          }

          .hero-badge {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 18px;
               padding: 8px 14px;
               border: 1px solid rgba(255, 255, 255, 0.17);
               border-radius: 50px;
               font-size: 12px;
               font-weight: 600;
               color: #dbeafe;
               background: rgba(255, 255, 255, 0.08);
               backdrop-filter: blur(8px);
          }

          .hero-badge i {
               color: #67e8f9;
          }

          .hero-title {
               max-width: 650px;
               margin: 0 0 14px;
               font-size: clamp(32px, 4vw, 52px);
               font-weight: 700;
               line-height: 1.14;
               letter-spacing: -1px;
               color: var(--white);
          }

          .hero-title span {
               color: #67e8f9;
          }

          .hero-text {
               max-width: 610px;
               margin: 0;
               font-size: 15px;
               line-height: 1.8;
               color: rgba(255, 255, 255, 0.76);
          }

          /* =========================================================
           ILUSTRASI DASHBOARD
        ========================================================= */

          .dashboard-illustration {
               position: relative;
               width: min(100%, 690px);
               margin: 32px auto 0;
               animation: floatDashboard 5s ease-in-out infinite;
          }

          .dashboard-illustration svg {
               display: block;
               width: 100%;
               height: auto;
               overflow: visible;
               filter: drop-shadow(0 30px 40px rgba(0, 0, 0, 0.25));
          }

          .chart-line {
               stroke-dasharray: 400;
               stroke-dashoffset: 400;
               animation: drawChart 3.5s ease-in-out infinite alternate;
          }

          .chart-bar-1 {
               transform-origin: bottom;
               animation: barAnimation 2.2s ease-in-out infinite alternate;
          }

          .chart-bar-2 {
               transform-origin: bottom;
               animation: barAnimation 2.6s ease-in-out infinite alternate;
          }

          .chart-bar-3 {
               transform-origin: bottom;
               animation: barAnimation 2.9s ease-in-out infinite alternate;
          }

          .chart-bar-4 {
               transform-origin: bottom;
               animation: barAnimation 3.1s ease-in-out infinite alternate;
          }

          .pulse-circle {
               transform-origin: center;
               animation: pulseCircle 2s ease-in-out infinite;
          }

          .floating-card-one {
               animation: floatingCardOne 4s ease-in-out infinite;
          }

          .floating-card-two {
               animation: floatingCardTwo 4.5s ease-in-out infinite;
          }

          @keyframes floatDashboard {

               0%,
               100% {
                    transform: translateY(0);
               }

               50% {
                    transform: translateY(-12px);
               }
          }

          @keyframes drawChart {
               to {
                    stroke-dashoffset: 0;
               }
          }

          @keyframes barAnimation {
               0% {
                    transform: scaleY(0.75);
                    opacity: 0.7;
               }

               100% {
                    transform: scaleY(1);
                    opacity: 1;
               }
          }

          @keyframes pulseCircle {

               0%,
               100% {
                    transform: scale(1);
                    opacity: 1;
               }

               50% {
                    transform: scale(1.12);
                    opacity: 0.75;
               }
          }

          @keyframes floatingCardOne {

               0%,
               100% {
                    transform: translateY(0);
               }

               50% {
                    transform: translateY(-9px);
               }
          }

          @keyframes floatingCardTwo {

               0%,
               100% {
                    transform: translateY(0);
               }

               50% {
                    transform: translateY(10px);
               }
          }

          /* =========================================================
           STATISTIK KECIL
        ========================================================= */

          .hero-statistics {
               display: grid;
               grid-template-columns: repeat(3, 1fr);
               gap: 14px;
               margin-top: 24px;
          }

          .hero-stat-card {
               padding: 15px 16px;
               border: 1px solid rgba(255, 255, 255, 0.13);
               border-radius: 15px;
               background: rgba(255, 255, 255, 0.08);
               backdrop-filter: blur(10px);
          }

          .hero-stat-value {
               display: block;
               margin-bottom: 4px;
               font-size: 20px;
               font-weight: 700;
               color: var(--white);
          }

          .hero-stat-label {
               display: block;
               font-size: 11px;
               color: rgba(255, 255, 255, 0.67);
          }

          .hero-footer {
               position: relative;
               z-index: 2;
               margin: 0;
               font-size: 12px;
               color: rgba(255, 255, 255, 0.55);
          }

          /* =========================================================
           BAGIAN FORM LOGIN
        ========================================================= */

          .login-section {
               position: relative;
               display: flex;
               align-items: center;
               justify-content: center;
               min-height: 100vh;
               padding: 40px;
               background:
                    radial-gradient(circle at 100% 0%,
                         rgba(37, 99, 235, 0.11),
                         transparent 35%),
                    var(--light);
          }

          .login-section::before {
               position: absolute;
               top: 35px;
               right: 40px;
               width: 80px;
               height: 80px;
               content: "";
               border: 12px solid rgba(37, 99, 235, 0.06);
               border-radius: 24px;
               transform: rotate(20deg);
          }

          .login-card {
               position: relative;
               z-index: 2;
               width: 100%;
               max-width: 480px;
               padding: 42px;
               border: 1px solid rgba(219, 228, 240, 0.9);
               border-radius: 26px;
               background: rgba(255, 255, 255, 0.96);
               box-shadow:
                    0 30px 70px rgba(15, 23, 42, 0.11),
                    0 3px 15px rgba(15, 23, 42, 0.05);
          }

          .mobile-brand {
               display: none;
               align-items: center;
               gap: 12px;
               margin-bottom: 28px;
          }

          .mobile-brand .brand-icon {
               width: 45px;
               height: 45px;
               border: none;
               border-radius: 13px;
               font-size: 18px;
               background: linear-gradient(135deg,
                         var(--primary),
                         var(--secondary));
          }

          .mobile-brand .brand-name {
               color: var(--dark);
               font-size: 17px;
          }

          .mobile-brand .brand-description {
               color: var(--muted);
          }

          .login-heading {
               margin-bottom: 28px;
          }

          .login-heading-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 44px;
               height: 44px;
               margin-bottom: 18px;
               border-radius: 13px;
               font-size: 17px;
               color: var(--primary);
               background: #eaf1ff;
          }

          .login-title {
               margin: 0 0 9px;
               font-size: 29px;
               font-weight: 700;
               letter-spacing: -0.5px;
               color: var(--dark);
          }

          .login-subtitle {
               margin: 0;
               font-size: 14px;
               line-height: 1.7;
               color: var(--muted);
          }

          /* =========================================================
           ALERT
        ========================================================= */

          .custom-alert {
               position: relative;
               margin-bottom: 20px;
               padding: 14px 42px 14px 45px;
               border: none;
               border-radius: 13px;
               font-size: 13px;
          }

          .custom-alert>i {
               position: absolute;
               top: 17px;
               left: 17px;
          }

          .custom-alert.alert-success {
               color: #065f46;
               background: #ecfdf5;
          }

          .custom-alert.alert-danger {
               color: #991b1b;
               background: #fef2f2;
          }

          .custom-alert ul {
               margin-top: 5px;
               margin-bottom: 0;
               padding-left: 18px;
          }

          .custom-alert .close {
               top: 4px;
               right: 5px;
               font-size: 18px;
          }

          /* =========================================================
           FORM
        ========================================================= */

          .form-group {
               margin-bottom: 20px;
          }

          .form-label {
               display: flex;
               align-items: center;
               justify-content: space-between;
               margin-bottom: 8px;
               font-size: 13px;
               font-weight: 600;
               color: var(--dark-soft);
          }

          .forgot-password {
               font-size: 12px;
               font-weight: 600;
               color: var(--primary);
               transition: color 0.2s ease;
          }

          .forgot-password:hover {
               color: var(--primary-dark);
               text-decoration: none;
          }

          .input-wrapper {
               position: relative;
          }

          .input-icon {
               position: absolute;
               top: 50%;
               left: 17px;
               z-index: 3;
               color: #94a3b8;
               transform: translateY(-50%);
               transition: color 0.2s ease;
          }

          .login-input {
               width: 100%;
               height: 52px;
               padding: 12px 48px 12px 47px;
               border: 1px solid var(--border);
               border-radius: 13px;
               outline: none;
               font-size: 13px;
               color: var(--dark);
               background: #fbfdff;
               box-shadow: none;
               transition:
                    border-color 0.2s ease,
                    box-shadow 0.2s ease,
                    background 0.2s ease;
          }

          .login-input::placeholder {
               color: #a1aec0;
          }

          .login-input:focus {
               border-color: var(--primary);
               background: var(--white);
               box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
          }

          .input-wrapper:focus-within .input-icon {
               color: var(--primary);
          }

          .login-input.is-invalid {
               border-color: #ef4444;
               background-image: none;
          }

          .login-input.is-invalid:focus {
               box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 9px;
               z-index: 4;
               display: flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               padding: 0;
               border: none;
               border-radius: 9px;
               outline: none;
               color: #64748b;
               background: transparent;
               transform: translateY(-50%);
               cursor: pointer;
               transition:
                    color 0.2s ease,
                    background 0.2s ease;
          }

          .password-toggle:hover {
               color: var(--primary);
               background: #eff6ff;
          }

          .password-toggle:focus {
               outline: none;
               box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
          }

          .error-message {
               display: block;
               margin-top: 6px;
               font-size: 12px;
               color: #dc2626;
          }

          /* =========================================================
           REMEMBER
        ========================================================= */

          .form-options {
               display: flex;
               align-items: center;
               justify-content: space-between;
               margin-top: 3px;
               margin-bottom: 23px;
          }

          .remember-wrapper {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               margin: 0;
               cursor: pointer;
          }

          .remember-checkbox {
               width: 17px;
               height: 17px;
               accent-color: var(--primary);
               cursor: pointer;
          }

          .remember-label {
               margin: 0;
               font-size: 12px;
               color: var(--muted);
               cursor: pointer;
          }

          .secure-text {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               font-size: 11px;
               color: #94a3b8;
          }

          .secure-text i {
               color: var(--success);
          }

          /* =========================================================
           BUTTON LOGIN
        ========================================================= */

          .login-button {
               position: relative;
               display: flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               width: 100%;
               min-height: 52px;
               padding: 13px 20px;
               overflow: hidden;
               border: none;
               border-radius: 13px;
               outline: none;
               font-size: 13px;
               font-weight: 700;
               letter-spacing: 0.2px;
               color: var(--white);
               background: linear-gradient(90deg,
                         var(--primary),
                         var(--secondary));
               box-shadow: 0 12px 25px rgba(37, 99, 235, 0.25);
               cursor: pointer;
               transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease;
          }

          .login-button::before {
               position: absolute;
               top: 0;
               left: -100%;
               width: 100%;
               height: 100%;
               content: "";
               background: linear-gradient(90deg,
                         transparent,
                         rgba(255, 255, 255, 0.24),
                         transparent);
               transition: left 0.5s ease;
          }

          .login-button:hover {
               color: var(--white);
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(37, 99, 235, 0.34);
          }

          .login-button:hover::before {
               left: 100%;
          }

          .login-button:focus {
               outline: none;
               box-shadow:
                    0 0 0 4px rgba(37, 99, 235, 0.15),
                    0 12px 25px rgba(37, 99, 235, 0.25);
          }

          .login-footer {
               margin: 28px 0 0;
               padding-top: 22px;
               border-top: 1px solid #edf2f7;
               text-align: center;
               font-size: 11px;
               line-height: 1.6;
               color: #94a3b8;
          }

          /* =========================================================
           RESPONSIVE
        ========================================================= */

          @media (max-width: 1100px) {
               .login-page {
                    grid-template-columns: minmax(0, 1fr) minmax(390px, 0.8fr);
               }

               .login-hero {
                    padding-right: 42px;
                    padding-left: 42px;
               }

               .login-section {
                    padding: 30px;
               }

               .login-card {
                    padding: 36px;
               }
          }

          @media (max-width: 900px) {
               .login-page {
                    display: block;
               }

               .login-hero {
                    display: none;
               }

               .login-section {
                    min-height: 100vh;
                    padding: 25px;
               }

               .mobile-brand {
                    display: flex;
               }
          }

          @media (max-width: 575px) {
               .login-section {
                    align-items: flex-start;
                    padding: 18px;
               }

               .login-section::before {
                    display: none;
               }

               .login-card {
                    max-width: none;
                    margin-top: 14px;
                    padding: 29px 23px;
                    border-radius: 21px;
               }

               .login-title {
                    font-size: 25px;
               }

               .login-subtitle {
                    font-size: 13px;
               }

               .form-options {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 12px;
               }

               .secure-text {
                    margin-left: 26px;
               }
          }

          @media (prefers-reduced-motion: reduce) {

               *,
               *::before,
               *::after {
                    scroll-behavior: auto !important;
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
               }
          }
     </style>
</head>

<body>

     <main class="login-page">

          <!-- =====================================================
             BAGIAN KIRI: INFORMASI DAN ANIMASI
        ====================================================== -->
          <section class="login-hero">

               <a href="{{ url('/') }}" class="brand">
                    <div class="brand-icon">
                         <i class="fas fa-chart-line"></i>
                    </div>

                    <div>
                         <span class="brand-name">
                              Executive Dashboard
                         </span>

                         <span class="brand-description">
                              Productivity & Service Analytics
                         </span>
                    </div>
               </a>

               <div class="hero-content">

                    <div class="hero-badge">
                         <i class="fas fa-circle"></i>
                         Sistem Monitoring Terintegrasi
                    </div>

                    <h1 class="hero-title">
                         Pantau Kinerja Bisnis dalam
                         <span>Satu Dashboard</span>
                    </h1>

                    <p class="hero-text">
                         Analisis produktivitas karyawan, perkembangan transaksi
                         jasa, dan pencapaian indikator kinerja secara cepat,
                         akurat, dan terintegrasi.
                    </p>

                    <!-- Ilustrasi dashboard dibuat langsung dengan SVG -->
                    <div class="dashboard-illustration" aria-hidden="true">
                         <svg viewBox="0 0 760 400" xmlns="http://www.w3.org/2000/svg">
                              <defs>
                                   <linearGradient id="dashboardBackground" x1="0" y1="0" x2="1"
                                        y2="1">
                                        <stop offset="0%" stop-color="#ffffff" />
                                        <stop offset="100%" stop-color="#eaf4ff" />
                                   </linearGradient>

                                   <linearGradient id="blueGradient" x1="0" y1="0" x2="1"
                                        y2="1">
                                        <stop offset="0%" stop-color="#2563eb" />
                                        <stop offset="100%" stop-color="#06b6d4" />
                                   </linearGradient>

                                   <linearGradient id="greenGradient" x1="0" y1="1" x2="0"
                                        y2="0">
                                        <stop offset="0%" stop-color="#10b981" />
                                        <stop offset="100%" stop-color="#6ee7b7" />
                                   </linearGradient>

                                   <filter id="dashboardShadow" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="15" stdDeviation="15"
                                             flood-color="#020617" flood-opacity="0.25" />
                                   </filter>
                              </defs>

                              <!-- Dashboard utama -->
                              <rect x="55" y="45" width="650" height="305" rx="25"
                                   fill="url(#dashboardBackground)" filter="url(#dashboardShadow)" />

                              <!-- Sidebar -->
                              <rect x="55" y="45" width="108" height="305" rx="25" fill="#0f2748" />

                              <rect x="55" y="72" width="108" height="278" fill="#0f2748" />

                              <!-- Logo sidebar -->
                              <circle cx="109" cy="84" r="20" fill="url(#blueGradient)" />

                              <path d="M100 85L106 91L119 76" fill="none" stroke="#ffffff" stroke-width="4"
                                   stroke-linecap="round" stroke-linejoin="round" />

                              <!-- Menu sidebar -->
                              <rect x="76" y="128" width="67" height="26" rx="8" fill="#2563eb" />

                              <circle cx="89" cy="141" r="4" fill="#ffffff" />
                              <rect x="99" y="137" width="32" height="7" rx="3" fill="#ffffff" />

                              <circle cx="89" cy="179" r="4" fill="#7890ab" />
                              <rect x="99" y="175" width="32" height="7" rx="3" fill="#7890ab" />

                              <circle cx="89" cy="216" r="4" fill="#7890ab" />
                              <rect x="99" y="212" width="32" height="7" rx="3" fill="#7890ab" />

                              <circle cx="89" cy="253" r="4" fill="#7890ab" />
                              <rect x="99" y="249" width="32" height="7" rx="3" fill="#7890ab" />

                              <!-- Header -->
                              <rect x="187" y="72" width="160" height="12" rx="6" fill="#172b4d" />

                              <rect x="187" y="94" width="105" height="7" rx="3.5" fill="#a8b6c8" />

                              <circle cx="663" cy="84" r="17" fill="#dbeafe" />

                              <circle cx="663" cy="80" r="6" fill="#2563eb" />

                              <path d="M652 94C654 87 672 87 674 94" fill="#2563eb" />

                              <!-- KPI Card 1 -->
                              <rect x="187" y="123" width="145" height="75" rx="15" fill="#eff6ff" />

                              <circle cx="212" cy="147" r="14" fill="#dbeafe" />

                              <path d="M205 151L211 145L216 148L221 141" fill="none" stroke="#2563eb"
                                   stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

                              <rect x="239" y="137" width="60" height="7" rx="3" fill="#90a4bc" />
                              <rect x="239" y="151" width="42" height="12" rx="4" fill="#172b4d" />
                              <rect x="204" y="178" width="88" height="6" rx="3" fill="#bfdbfe" />

                              <!-- KPI Card 2 -->
                              <rect x="347" y="123" width="145" height="75" rx="15" fill="#ecfdf5" />

                              <circle cx="372" cy="147" r="14" fill="#d1fae5" />

                              <path d="M366 147H378M372 141V153" stroke="#10b981" stroke-width="3"
                                   stroke-linecap="round" />

                              <rect x="399" y="137" width="60" height="7" rx="3" fill="#90a4bc" />
                              <rect x="399" y="151" width="42" height="12" rx="4" fill="#172b4d" />
                              <rect x="364" y="178" width="88" height="6" rx="3" fill="#a7f3d0" />

                              <!-- KPI Card 3 -->
                              <rect x="507" y="123" width="168" height="75" rx="15" fill="#f5f3ff" />

                              <circle class="pulse-circle" cx="532" cy="147" r="14" fill="#ede9fe" />

                              <path d="M525 149L530 144L534 148L540 140" fill="none" stroke="#7c3aed"
                                   stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

                              <rect x="559" y="137" width="75" height="7" rx="3" fill="#90a4bc" />
                              <rect x="559" y="151" width="49" height="12" rx="4" fill="#172b4d" />
                              <rect x="524" y="178" width="110" height="6" rx="3" fill="#ddd6fe" />

                              <!-- Line chart -->
                              <rect x="187" y="216" width="300" height="108" rx="15" fill="#ffffff"
                                   stroke="#e5edf6" />

                              <rect x="207" y="233" width="93" height="8" rx="4" fill="#172b4d" />
                              <rect x="207" y="249" width="58" height="6" rx="3" fill="#a8b6c8" />

                              <line x1="207" y1="300" x2="463" y2="300" stroke="#dce6f1" />
                              <line x1="207" y1="275" x2="463" y2="275" stroke="#edf2f7" />
                              <line x1="207" y1="258" x2="207" y2="300" stroke="#dce6f1" />

                              <path class="chart-line" d="M210 292
                               C235 285, 245 291, 265 276
                               C290 257, 305 280, 330 265
                               C352 252, 368 263, 390 244
                               C412 226, 433 246, 462 225" fill="none" stroke="url(#blueGradient)"
                                   stroke-width="5" stroke-linecap="round" />

                              <circle cx="462" cy="225" r="7" fill="#06b6d4" />
                              <circle cx="462" cy="225" r="12" fill="#06b6d4" opacity="0.16" />

                              <!-- Bar chart -->
                              <rect x="503" y="216" width="172" height="108" rx="15" fill="#ffffff"
                                   stroke="#e5edf6" />

                              <rect x="521" y="233" width="83" height="8" rx="4" fill="#172b4d" />
                              <rect x="521" y="249" width="48" height="6" rx="3" fill="#a8b6c8" />

                              <rect class="chart-bar-1" x="528" y="278" width="19" height="29" rx="5"
                                   fill="#bfdbfe" />

                              <rect class="chart-bar-2" x="559" y="265" width="19" height="42" rx="5"
                                   fill="#93c5fd" />

                              <rect class="chart-bar-3" x="590" y="247" width="19" height="60" rx="5"
                                   fill="#3b82f6" />

                              <rect class="chart-bar-4" x="621" y="258" width="19" height="49" rx="5"
                                   fill="url(#greenGradient)" />

                              <!-- Floating card kiri -->
                              <g class="floating-card-one">
                                   <rect x="13" y="180" width="122" height="67" rx="15" fill="#ffffff"
                                        filter="url(#dashboardShadow)" />

                                   <circle cx="38" cy="206" r="13" fill="#d1fae5" />

                                   <path d="M32 206L37 211L45 201" fill="none" stroke="#10b981" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />

                                   <rect x="58" y="195" width="54" height="7" rx="3"
                                        fill="#9badc1" />
                                   <rect x="58" y="209" width="39" height="10" rx="4"
                                        fill="#172b4d" />
                                   <rect x="30" y="229" width="76" height="5" rx="2.5"
                                        fill="#d1fae5" />
                              </g>

                              <!-- Floating card kanan -->
                              <g class="floating-card-two">
                                   <rect x="638" y="20" width="110" height="62" rx="15" fill="#ffffff"
                                        filter="url(#dashboardShadow)" />

                                   <circle cx="661" cy="45" r="12" fill="#dbeafe" />

                                   <path d="M655 48L660 43L664 46L669 39" fill="none" stroke="#2563eb"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

                                   <rect x="681" y="35" width="48" height="7" rx="3"
                                        fill="#9badc1" />
                                   <rect x="681" y="49" width="33" height="9" rx="4"
                                        fill="#172b4d" />
                              </g>
                         </svg>
                    </div>

                    <div class="hero-statistics">

                         <div class="hero-stat-card">
                              <span class="hero-stat-value">98%</span>

                              <span class="hero-stat-label">
                                   Target Produktivitas
                              </span>
                         </div>

                         <div class="hero-stat-card">
                              <span class="hero-stat-value">1.248</span>

                              <span class="hero-stat-label">
                                   Transaksi Jasa
                              </span>
                         </div>

                         <div class="hero-stat-card">
                              <span class="hero-stat-value">24/7</span>

                              <span class="hero-stat-label">
                                   Monitoring Data
                              </span>
                         </div>

                    </div>

               </div>

               <p class="hero-footer">
                    &copy; {{ date('Y') }} Executive Monitoring System
               </p>

          </section>

          <!-- =====================================================
             BAGIAN KANAN: FORM LOGIN
        ====================================================== -->
          <section class="login-section">

               <div class="login-card">

                    <!-- Muncul hanya pada layar kecil -->
                    <div class="mobile-brand">

                         <div class="brand-icon">
                              <i class="fas fa-chart-line"></i>
                         </div>

                         <div>
                              <span class="brand-name">
                                   Executive Dashboard
                              </span>

                              <span class="brand-description">
                                   Productivity & Service Analytics
                              </span>
                         </div>

                    </div>

                    <div class="login-heading">

                         <div class="login-heading-icon">
                              <i class="fas fa-user-lock"></i>
                         </div>

                         <h2 class="login-title">
                              Selamat Datang!
                         </h2>

                         <p class="login-subtitle">
                              Silakan masukkan email dan password untuk mengakses
                              Dashboard Eksekutif.
                         </p>

                    </div>

                    <!-- Pesan berhasil -->
                    @if (session('success'))
                         <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                              <i class="fas fa-check-circle"></i>

                              {{ session('success') }}

                              <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                   <span aria-hidden="true">&times;</span>
                              </button>
                         </div>
                    @endif

                    <!-- Pesan error umum -->
                    @if ($errors->any())
                         <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                              <i class="fas fa-exclamation-circle"></i>

                              <strong>Login gagal.</strong>

                              <ul>
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>

                              <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                   <span aria-hidden="true">&times;</span>
                              </button>
                         </div>
                    @endif

                    {{-- Pesan error umum dari proses login --}}
                    @if (session('error'))
                         <div class="alert alert-danger" role="alert">
                              <i class="fas fa-exclamation-circle"></i>
                              {{ session('error') }}
                         </div>
                    @endif

                    @if (session('success'))
                         <div class="alert alert-success" role="alert">
                              <i class="fas fa-check-circle"></i>
                              {{ session('success') }}
                         </div>
                    @endif

                    {{-- Pesan sukses --}}
                    @if (session('success'))
                         <div class="alert alert-success" role="alert">
                              <i class="fas fa-check-circle" aria-hidden="true"></i>
                              {{ session('success') }}
                         </div>
                    @endif

                    <form action="{{ route('login.process') }}" method="POST" id="loginForm" novalidate>
                         @csrf

                         {{-- Email --}}
                         <div class="form-group">
                              <label for="email" class="form-label">
                                   Alamat Email
                              </label>

                              <div class="input-wrapper">
                                   <i class="fas fa-envelope input-icon" aria-hidden="true"></i>

                                   <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="login-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        placeholder="contoh@email.com" autocomplete="email" inputmode="email"
                                        spellcheck="false" maxlength="150" autofocus required
                                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                        @error('email')
                    aria-describedby="emailError"
                @enderror>
                              </div>

                              @error('email')
                                   <span id="emailError" class="error-message" role="alert">
                                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>

                                        {{ $message }}
                                   </span>
                              @enderror
                         </div>

                         {{-- Password --}}
                         <div class="form-group">
                              <div class="form-label password-label-wrapper">
                                   <label for="password">
                                        Password
                                   </label>

                                   @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="forgot-password">
                                             Lupa password?
                                        </a>
                                   @endif
                              </div>

                              <div class="input-wrapper">
                                   <i class="fas fa-lock input-icon" aria-hidden="true"></i>

                                   <input type="password" id="password" name="password"
                                        class="login-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        placeholder="Masukkan password" autocomplete="current-password"
                                        maxlength="255" required
                                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                        @error('password')
                    aria-describedby="passwordError"
                @enderror>

                                   <button type="button" id="togglePassword" class="password-toggle"
                                        aria-label="Tampilkan password" aria-controls="password"
                                        aria-pressed="false">
                                        <i id="passwordIcon" class="far fa-eye" aria-hidden="true"></i>
                                   </button>
                              </div>

                              @error('password')
                                   <span id="passwordError" class="error-message" role="alert">
                                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>

                                        {{ $message }}
                                   </span>
                              @enderror
                         </div>

                         {{-- Remember me --}}
                         <div class="form-options">
                              <label for="remember" class="remember-wrapper">
                                   <input type="checkbox" id="remember" name="remember" value="1"
                                        class="remember-checkbox" @checked(old('remember'))>

                                   <span class="remember-label">
                                        Ingat saya
                                   </span>
                              </label>

                              <span class="secure-text">
                                   <i class="fas fa-shield-alt" aria-hidden="true"></i>

                                   Akses terlindungi
                              </span>
                         </div>

                         {{-- Tombol login --}}
                         <button type="submit" id="loginButton" class="login-button">
                              <i id="loginButtonIcon" class="fas fa-sign-in-alt" aria-hidden="true"></i>

                              <span id="loginButtonText">
                                   Masuk ke Dashboard
                              </span>
                         </button>
                    </form>
                    <p class="login-footer">
                         &copy; {{ date('Y') }}
                         Dashboard Eksekutif<br>

                         Monitoring Produktivitas Karyawan dan Transaksi Jasa
                    </p>

               </div>

          </section>

     </main>

     <!-- JavaScript Bootstrap -->
     <script src="{{ asset('backend/lib/jquery/jquery.min.js') }}"></script>

     <script src="{{ asset('backend/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const loginForm = document.getElementById('loginForm');
               const loginButton = document.getElementById('loginButton');
               const loginButtonIcon =
                    document.getElementById('loginButtonIcon');
               const loginButtonText =
                    document.getElementById('loginButtonText');

               if (!loginForm || !loginButton) {
                    return;
               }

               loginForm.addEventListener('submit', function() {
                    loginButton.disabled = true;

                    if (loginButtonIcon) {
                         loginButtonIcon.className =
                              'fas fa-spinner fa-spin';
                    }

                    if (loginButtonText) {
                         loginButtonText.textContent =
                              'Memproses login...';
                    }
               });
          });
     </script>

</body>

</html>
