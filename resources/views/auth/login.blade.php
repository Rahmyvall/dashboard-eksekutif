<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Login Sistem Monitoring Produktivitas Karyawan dan Transaksi Jasa">
     <meta name="theme-color" content="#071a38">

     <title>Login | Monitoring Produktivitas & Transaksi Jasa</title>

     <style>
          :root {
               --primary: #2563eb;
               --primary-dark: #1d4ed8;
               --primary-soft: #eff6ff;
               --secondary: #06b6d4;
               --success: #16a34a;
               --success-soft: #f0fdf4;
               --danger: #dc2626;
               --danger-soft: #fef2f2;
               --warning: #f59e0b;
               --ink: #0f172a;
               --text: #334155;
               --muted: #64748b;
               --border: #dbe5f1;
               --surface: #ffffff;
               --page: #f4f7fb;
               --shadow: 0 30px 80px rgba(15, 23, 42, 0.14);
               --radius-lg: 28px;
               --radius-md: 16px;
               --radius-sm: 12px;
          }

          * {
               box-sizing: border-box;
          }

          html {
               min-height: 100%;
               background: var(--page);
          }

          body {
               min-height: 100vh;
               margin: 0;
               overflow-x: hidden;
               color: var(--text);
               background: var(--page);
               font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                    "Segoe UI", sans-serif;
               -webkit-font-smoothing: antialiased;
               text-rendering: optimizeLegibility;
          }

          button,
          input,
          select {
               font: inherit;
          }

          button,
          a,
          input,
          select {
               -webkit-tap-highlight-color: transparent;
          }

          a {
               color: inherit;
               text-decoration: none;
          }

          svg {
               display: block;
          }

          .auth-layout {
               display: grid;
               grid-template-columns: minmax(0, 1.18fr) minmax(470px, 0.82fr);
               min-height: 100vh;
          }

          /* ================================================================
           PANEL INFORMASI
        ================================================================= */

          .auth-visual {
               position: relative;
               isolation: isolate;
               display: flex;
               min-height: 100vh;
               overflow: hidden;
               color: #ffffff;
               background:
                    radial-gradient(circle at 18% 18%, rgba(34, 211, 238, 0.28), transparent 26%),
                    radial-gradient(circle at 86% 77%, rgba(59, 130, 246, 0.34), transparent 31%),
                    linear-gradient(138deg, #06152e 0%, #0a2d5b 51%, #1554b8 100%);
          }

          .auth-visual::before {
               position: absolute;
               inset: 0;
               z-index: -3;
               content: "";
               opacity: 0.12;
               background-image:
                    linear-gradient(rgba(255, 255, 255, 0.18) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.18) 1px, transparent 1px);
               background-size: 54px 54px;
               mask-image: linear-gradient(to bottom right, black, transparent 77%);
          }

          .auth-visual::after {
               position: absolute;
               top: -160px;
               right: -170px;
               z-index: -2;
               width: 530px;
               height: 530px;
               content: "";
               border: 1px solid rgba(255, 255, 255, 0.16);
               border-radius: 50%;
               box-shadow:
                    0 0 0 65px rgba(255, 255, 255, 0.025),
                    0 0 0 130px rgba(255, 255, 255, 0.018);
          }

          .visual-inner {
               position: relative;
               display: flex;
               flex: 1;
               flex-direction: column;
               justify-content: space-between;
               width: 100%;
               max-width: 920px;
               min-height: 100vh;
               margin: 0 auto;
               padding: 42px clamp(42px, 6vw, 84px) 34px;
          }

          .brand {
               display: inline-flex;
               align-items: center;
               gap: 14px;
               width: fit-content;
          }

          .brand-mark {
               display: grid;
               width: 54px;
               height: 54px;
               place-items: center;
               border: 1px solid rgba(255, 255, 255, 0.22);
               border-radius: 17px;
               background: rgba(255, 255, 255, 0.11);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
               backdrop-filter: blur(14px);
          }

          .brand-mark svg {
               width: 28px;
               height: 28px;
          }

          .brand-copy strong,
          .brand-copy span {
               display: block;
          }

          .brand-copy strong {
               font-size: 18px;
               line-height: 1.25;
               letter-spacing: -0.2px;
          }

          .brand-copy span {
               margin-top: 4px;
               font-size: 11px;
               color: rgba(255, 255, 255, 0.68);
               letter-spacing: 0.7px;
               text-transform: uppercase;
          }

          .visual-content {
               width: 100%;
               max-width: 720px;
               margin: auto 0;
               padding: 46px 0;
          }

          .eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               margin-bottom: 22px;
               padding: 8px 13px;
               border: 1px solid rgba(255, 255, 255, 0.17);
               border-radius: 999px;
               color: #dff8ff;
               background: rgba(255, 255, 255, 0.075);
               font-size: 11px;
               font-weight: 700;
               letter-spacing: 0.55px;
               text-transform: uppercase;
               backdrop-filter: blur(12px);
          }

          .eyebrow-dot {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #67e8f9;
               box-shadow: 0 0 0 5px rgba(103, 232, 249, 0.12);
          }

          .visual-title {
               max-width: 710px;
               margin: 0;
               font-size: clamp(40px, 4.6vw, 68px);
               line-height: 1.04;
               letter-spacing: -2.7px;
          }

          .visual-title span {
               color: #67e8f9;
          }

          .visual-description {
               max-width: 640px;
               margin: 22px 0 0;
               color: rgba(255, 255, 255, 0.73);
               font-size: 15px;
               line-height: 1.85;
          }

          .feature-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 13px;
               margin-top: 34px;
          }

          .feature-card {
               min-height: 132px;
               padding: 18px;
               border: 1px solid rgba(255, 255, 255, 0.13);
               border-radius: 18px;
               background: rgba(255, 255, 255, 0.075);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.09);
               backdrop-filter: blur(16px);
          }

          .feature-icon {
               display: grid;
               width: 38px;
               height: 38px;
               margin-bottom: 16px;
               place-items: center;
               border-radius: 11px;
               color: #a5f3fc;
               background: rgba(103, 232, 249, 0.11);
          }

          .feature-icon svg {
               width: 19px;
               height: 19px;
          }

          .feature-card strong {
               display: block;
               margin-bottom: 6px;
               font-size: 13px;
          }

          .feature-card span {
               display: block;
               color: rgba(255, 255, 255, 0.6);
               font-size: 11px;
               line-height: 1.55;
          }

          .visual-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               color: rgba(255, 255, 255, 0.48);
               font-size: 11px;
          }

          .system-status {
               display: inline-flex;
               align-items: center;
               gap: 8px;
          }

          .system-status::before {
               width: 7px;
               height: 7px;
               content: "";
               border-radius: 50%;
               background: #4ade80;
               box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.1);
          }

          /* ================================================================
           PANEL LOGIN
        ================================================================= */

          .auth-form-panel {
               position: relative;
               display: flex;
               min-height: 100vh;
               align-items: center;
               justify-content: center;
               padding: 42px clamp(24px, 4vw, 64px);
               overflow: hidden;
               background:
                    radial-gradient(circle at 98% 2%, rgba(37, 99, 235, 0.13), transparent 28%),
                    radial-gradient(circle at 5% 96%, rgba(6, 182, 212, 0.09), transparent 27%),
                    var(--page);
          }

          .auth-form-panel::before {
               position: absolute;
               top: 72px;
               right: -58px;
               width: 170px;
               height: 170px;
               content: "";
               border: 28px solid rgba(37, 99, 235, 0.035);
               border-radius: 50%;
          }

          .login-shell {
               position: relative;
               z-index: 1;
               width: 100%;
               max-width: 500px;
          }

          .mobile-brand {
               display: none;
               align-items: center;
               gap: 12px;
               margin-bottom: 22px;
          }

          .mobile-brand-mark {
               display: grid;
               width: 44px;
               height: 44px;
               place-items: center;
               border-radius: 13px;
               color: #ffffff;
               background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
               box-shadow: 0 11px 25px rgba(37, 99, 235, 0.25);
          }

          .mobile-brand-mark svg {
               width: 23px;
               height: 23px;
          }

          .mobile-brand-copy strong,
          .mobile-brand-copy span {
               display: block;
          }

          .mobile-brand-copy strong {
               color: var(--ink);
               font-size: 15px;
          }

          .mobile-brand-copy span {
               margin-top: 3px;
               color: var(--muted);
               font-size: 10px;
          }

          .login-card {
               width: 100%;
               padding: clamp(28px, 4vw, 44px);
               border: 1px solid rgba(219, 229, 241, 0.92);
               border-radius: var(--radius-lg);
               background: rgba(255, 255, 255, 0.96);
               box-shadow: var(--shadow);
               backdrop-filter: blur(18px);
          }

          .login-header {
               margin-bottom: 28px;
          }

          .login-icon {
               display: grid;
               width: 48px;
               height: 48px;
               margin-bottom: 18px;
               place-items: center;
               border-radius: 14px;
               color: var(--primary);
               background: linear-gradient(135deg, #eaf2ff, #ecfeff);
               box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.08);
          }

          .login-icon svg {
               width: 23px;
               height: 23px;
          }

          .login-title {
               margin: 0;
               color: var(--ink);
               font-size: clamp(27px, 3vw, 34px);
               line-height: 1.2;
               letter-spacing: -0.85px;
          }

          .login-subtitle {
               max-width: 405px;
               margin: 10px 0 0;
               color: var(--muted);
               font-size: 13px;
               line-height: 1.7;
          }

          /* Alert */

          .alert {
               position: relative;
               display: flex;
               align-items: flex-start;
               gap: 11px;
               margin-bottom: 18px;
               padding: 14px 44px 14px 14px;
               border: 1px solid;
               border-radius: 13px;
               font-size: 12px;
               line-height: 1.6;
          }

          .alert svg {
               flex: 0 0 auto;
               width: 18px;
               height: 18px;
               margin-top: 1px;
          }

          .alert-success {
               border-color: #bbf7d0;
               color: #166534;
               background: var(--success-soft);
          }

          .alert-danger {
               border-color: #fecaca;
               color: #991b1b;
               background: var(--danger-soft);
          }

          .alert-content {
               min-width: 0;
               flex: 1;
          }

          .alert-content ul {
               margin: 0;
               padding-left: 17px;
          }

          .alert-close {
               position: absolute;
               top: 9px;
               right: 9px;
               display: grid;
               width: 28px;
               height: 28px;
               padding: 0;
               place-items: center;
               border: 0;
               border-radius: 8px;
               color: currentColor;
               background: transparent;
               cursor: pointer;
               opacity: 0.65;
          }

          .alert-close:hover {
               background: rgba(15, 23, 42, 0.06);
               opacity: 1;
          }

          .alert-close svg {
               width: 15px;
               height: 15px;
               margin: 0;
          }

          /* Form */

          .form-group {
               margin-bottom: 19px;
          }

          .label-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               margin-bottom: 8px;
          }

          .form-label {
               color: var(--ink);
               font-size: 12px;
               font-weight: 750;
          }

          .forgot-link {
               color: var(--primary);
               font-size: 11px;
               font-weight: 750;
               transition: color 0.2s ease;
          }

          .forgot-link:hover {
               color: var(--primary-dark);
               text-decoration: underline;
               text-underline-offset: 3px;
          }

          .field {
               position: relative;
          }

          .field-icon {
               position: absolute;
               top: 50%;
               left: 16px;
               z-index: 2;
               color: #94a3b8;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .field-icon svg {
               width: 19px;
               height: 19px;
          }

          .form-control,
          .form-select {
               width: 100%;
               height: 54px;
               border: 1px solid var(--border);
               border-radius: 14px;
               outline: 0;
               color: var(--ink);
               background: #fbfdff;
               font-size: 13px;
               transition:
                    border-color 0.2s ease,
                    box-shadow 0.2s ease,
                    background 0.2s ease,
                    transform 0.2s ease;
          }

          .form-control {
               padding: 12px 48px 12px 48px;
          }

          .form-select {
               padding: 12px 45px 12px 48px;
               cursor: pointer;
               appearance: none;
          }

          .form-control::placeholder {
               color: #a8b3c3;
          }

          .form-control:hover,
          .form-select:hover {
               border-color: #bdcbe0;
          }

          .form-control:focus,
          .form-select:focus {
               border-color: var(--primary);
               background: #ffffff;
               box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.105);
          }

          .form-control.is-invalid,
          .form-select.is-invalid {
               border-color: var(--danger);
               background: #fffafa;
          }

          .form-control.is-invalid:focus,
          .form-select.is-invalid:focus {
               box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.09);
          }

          .select-chevron {
               position: absolute;
               top: 50%;
               right: 16px;
               color: #94a3b8;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .select-chevron svg {
               width: 17px;
               height: 17px;
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 8px;
               display: grid;
               width: 38px;
               height: 38px;
               padding: 0;
               place-items: center;
               border: 0;
               border-radius: 10px;
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
               background: var(--primary-soft);
          }

          .password-toggle svg {
               width: 19px;
               height: 19px;
          }

          .password-toggle .icon-eye-off {
               display: none;
          }

          .password-toggle.is-visible .icon-eye {
               display: none;
          }

          .password-toggle.is-visible .icon-eye-off {
               display: block;
          }

          .error-text {
               display: flex;
               align-items: flex-start;
               gap: 6px;
               margin-top: 7px;
               color: var(--danger);
               font-size: 11px;
               line-height: 1.45;
          }

          .error-text svg {
               flex: 0 0 auto;
               width: 14px;
               height: 14px;
               margin-top: 1px;
          }

          .form-options {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               margin: 1px 0 22px;
          }

          .remember {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               cursor: pointer;
               user-select: none;
          }

          .remember input {
               width: 17px;
               height: 17px;
               margin: 0;
               accent-color: var(--primary);
               cursor: pointer;
          }

          .remember span,
          .secure-badge {
               color: var(--muted);
               font-size: 11px;
          }

          .secure-badge {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               color: var(--success);
               font-weight: 650;
          }

          .secure-badge svg {
               width: 15px;
               height: 15px;
          }

          .submit-button {
               position: relative;
               display: flex;
               width: 100%;
               min-height: 54px;
               align-items: center;
               justify-content: center;
               gap: 10px;
               overflow: hidden;
               border: 0;
               border-radius: 14px;
               color: #ffffff;
               background: linear-gradient(100deg, #1d4ed8 0%, #2563eb 52%, #06b6d4 100%);
               box-shadow: 0 14px 30px rgba(37, 99, 235, 0.25);
               font-size: 13px;
               font-weight: 800;
               letter-spacing: 0.1px;
               cursor: pointer;
               transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease,
                    opacity 0.2s ease;
          }

          .submit-button::before {
               position: absolute;
               inset: 0;
               content: "";
               background: linear-gradient(120deg, transparent 20%, rgba(255, 255, 255, 0.16), transparent 80%);
               transform: translateX(-110%);
               transition: transform 0.65s ease;
          }

          .submit-button:hover:not(:disabled) {
               box-shadow: 0 18px 36px rgba(37, 99, 235, 0.31);
               transform: translateY(-1px);
          }

          .submit-button:hover:not(:disabled)::before {
               transform: translateX(110%);
          }

          .submit-button:active:not(:disabled) {
               transform: translateY(0);
          }

          .submit-button:disabled {
               cursor: wait;
               opacity: 0.76;
          }

          .submit-button svg {
               width: 18px;
               height: 18px;
          }

          .spinner {
               width: 18px;
               height: 18px;
               border: 2px solid rgba(255, 255, 255, 0.36);
               border-top-color: #ffffff;
               border-radius: 50%;
               animation: spin 0.72s linear infinite;
          }

          @keyframes spin {
               to {
                    transform: rotate(360deg);
               }
          }

          .login-footer {
               margin: 26px 0 0;
               padding-top: 20px;
               border-top: 1px solid #edf1f6;
               text-align: center;
               color: #94a3b8;
               font-size: 10.5px;
               line-height: 1.65;
          }

          .login-footer strong {
               color: #64748b;
               font-weight: 700;
          }

          /* Accessibility */

          :focus-visible {
               outline: 3px solid rgba(37, 99, 235, 0.26);
               outline-offset: 3px;
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

          /* Responsive */

          @media (max-width: 1180px) {
               .auth-layout {
                    grid-template-columns: minmax(0, 1fr) minmax(440px, 0.85fr);
               }

               .visual-inner {
                    padding-right: 48px;
                    padding-left: 48px;
               }

               .visual-title {
                    font-size: clamp(38px, 4.4vw, 58px);
               }

               .feature-card {
                    min-height: 142px;
               }
          }

          @media (max-width: 980px) {
               .auth-layout {
                    display: block;
               }

               .auth-visual {
                    display: none;
               }

               .auth-form-panel {
                    min-height: 100vh;
                    padding: 30px 24px;
               }

               .mobile-brand {
                    display: flex;
               }
          }

          @media (max-width: 560px) {
               .auth-form-panel {
                    align-items: flex-start;
                    padding: 22px 15px;
               }

               .login-shell {
                    margin: auto 0;
               }

               .login-card {
                    padding: 26px 20px;
                    border-radius: 22px;
                    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.12);
               }

               .login-title {
                    font-size: 27px;
               }

               .login-subtitle {
                    font-size: 12px;
               }

               .form-options {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }

          @media (max-width: 390px) {
               .login-card {
                    padding: 23px 17px;
               }

               .mobile-brand {
                    margin-left: 2px;
               }
          }
     </style>
</head>

<body>
     <main class="auth-layout">
          {{-- =============================================================
             PANEL KIRI: INFORMASI SISTEM
        ============================================================== --}}
          <section class="auth-visual" aria-label="Informasi aplikasi">
               <div class="visual-inner">
                    <a href="{{ route('login') }}" class="brand" aria-label="Halaman login aplikasi">
                         <span class="brand-mark" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                   <path d="M4 19V9" stroke-linecap="round" />
                                   <path d="M10 19V5" stroke-linecap="round" />
                                   <path d="M16 19v-7" stroke-linecap="round" />
                                   <path d="M22 19H2" stroke-linecap="round" />
                                   <path d="m4 6 5-3 6 4 5-4" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                         </span>

                         <span class="brand-copy">
                              <strong>WorkTrack Monitor</strong>
                              <span>Business Performance System</span>
                         </span>
                    </a>

                    <div class="visual-content">
                         <div class="eyebrow">
                              <span class="eyebrow-dot" aria-hidden="true"></span>
                              Monitoring Operasional Terintegrasi
                         </div>

                         <h1 class="visual-title">
                              Produktivitas tim dan transaksi jasa dalam
                              <span>satu kendali.</span>
                         </h1>

                         <p class="visual-description">
                              Pantau aktivitas karyawan, capaian pekerjaan, transaksi layanan,
                              serta performa operasional secara terstruktur untuk mendukung
                              keputusan yang lebih cepat dan akurat.
                         </p>

                         <div class="feature-grid" aria-label="Fitur utama aplikasi">
                              <article class="feature-card">
                                   <span class="feature-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.8">
                                             <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                  stroke-linecap="round" />
                                             <circle cx="9" cy="7" r="4" />
                                             <path d="M22 21v-2a4 4 0 0 0-3-3.87" stroke-linecap="round" />
                                             <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke-linecap="round" />
                                        </svg>
                                   </span>
                                   <strong>Produktivitas Karyawan</strong>
                                   <span>Rekap aktivitas dan pencapaian kerja secara terukur.</span>
                              </article>

                              <article class="feature-card">
                                   <span class="feature-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.8">
                                             <path d="M6 2h9l5 5v15H6z" stroke-linejoin="round" />
                                             <path d="M14 2v6h6" stroke-linejoin="round" />
                                             <path d="M9 13h8M9 17h6" stroke-linecap="round" />
                                        </svg>
                                   </span>
                                   <strong>Transaksi Jasa</strong>
                                   <span>Kelola data layanan, nilai transaksi, dan status proses.</span>
                              </article>

                              <article class="feature-card">
                                   <span class="feature-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.8">
                                             <path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round" />
                                             <path d="m7 16 4-5 3 3 5-7" stroke-linecap="round"
                                                  stroke-linejoin="round" />
                                        </svg>
                                   </span>
                                   <strong>Laporan Eksekutif</strong>
                                   <span>Informasi ringkas untuk evaluasi dan pengambilan keputusan.</span>
                              </article>
                         </div>
                    </div>

                    <footer class="visual-footer">
                         <span>&copy; {{ now()->year }} WorkTrack Monitor</span>
                         <span class="system-status">Sistem siap digunakan</span>
                    </footer>
               </div>
          </section>

          {{-- =============================================================
             PANEL KANAN: FORM LOGIN
        ============================================================== --}}
          <section class="auth-form-panel" aria-label="Form login">
               <div class="login-shell">
                    <a href="{{ route('login') }}" class="mobile-brand" aria-label="Halaman login aplikasi">
                         <span class="mobile-brand-mark" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                   <path d="M4 19V9" stroke-linecap="round" />
                                   <path d="M10 19V5" stroke-linecap="round" />
                                   <path d="M16 19v-7" stroke-linecap="round" />
                                   <path d="M22 19H2" stroke-linecap="round" />
                                   <path d="m4 6 5-3 6 4 5-4" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                         </span>
                         <span class="mobile-brand-copy">
                              <strong>WorkTrack Monitor</strong>
                              <span>Produktivitas & Transaksi Jasa</span>
                         </span>
                    </a>

                    <div class="login-card">
                         <header class="login-header">
                              <div class="login-icon" aria-hidden="true">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="4" y="10" width="16" height="11" rx="3" />
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-linecap="round" />
                                        <path d="M12 14v3" stroke-linecap="round" />
                                   </svg>
                              </div>

                              <h2 class="login-title">Selamat datang kembali</h2>
                              <p class="login-subtitle">
                                   Masuk menggunakan akun dan hak akses yang telah diberikan
                                   untuk membuka dashboard monitoring.
                              </p>
                         </header>

                         {{-- Pesan sukses --}}
                         @if (session()->has('success'))
                              <div class="alert alert-success" role="status">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="m8 12 2.5 2.5L16 9" stroke-linecap="round" stroke-linejoin="round" />
                                   </svg>
                                   <div class="alert-content">{{ session('success') }}</div>
                                   <button type="button" class="alert-close" aria-label="Tutup pesan">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                             <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" />
                                        </svg>
                                   </button>
                              </div>
                         @endif

                         {{-- Pesan error dari session --}}
                         @if (session()->has('error'))
                              <div class="alert alert-danger" role="alert">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 8v5M12 16.5h.01" stroke-linecap="round" />
                                   </svg>
                                   <div class="alert-content">{{ session('error') }}</div>
                                   <button type="button" class="alert-close" aria-label="Tutup pesan">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                             <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" />
                                        </svg>
                                   </button>
                              </div>
                         @endif

                         {{-- Ringkasan validasi --}}
                         @if ($errors->any())
                              <div class="alert alert-danger" role="alert">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 8v5M12 16.5h.01" stroke-linecap="round" />
                                   </svg>
                                   <div class="alert-content">
                                        <ul>
                                             @foreach ($errors->all() as $error)
                                                  <li>{{ $error }}</li>
                                             @endforeach
                                        </ul>
                                   </div>
                                   <button type="button" class="alert-close" aria-label="Tutup pesan">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                             <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" />
                                        </svg>
                                   </button>
                              </div>
                         @endif

                         <form id="loginForm" action="{{ route('login.process') }}" method="POST" novalidate>
                              @csrf

                              {{-- Email --}}
                              <div class="form-group">
                                   <div class="label-row">
                                        <label for="email" class="form-label">Alamat Email</label>
                                   </div>

                                   <div class="field">
                                        <span class="field-icon" aria-hidden="true">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="1.8">
                                                  <rect x="3" y="5" width="18" height="14" rx="3" />
                                                  <path d="m5 8 7 5 7-5" stroke-linecap="round"
                                                       stroke-linejoin="round" />
                                             </svg>
                                        </span>

                                        <input type="email" id="email" name="email"
                                             value="{{ old('email') }}"
                                             class="form-control @error('email') is-invalid @enderror"
                                             placeholder="nama@perusahaan.com" autocomplete="email" inputmode="email"
                                             maxlength="150" aria-invalid="@error('email') true @else false @enderror"
                                             @error('email') aria-describedby="emailError" @enderror required autofocus>
                                   </div>

                                   @error('email')
                                        <span id="emailError" class="error-text" role="alert">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="2" aria-hidden="true">
                                                  <circle cx="12" cy="12" r="9" />
                                                  <path d="M12 8v5M12 16.5h.01" stroke-linecap="round" />
                                             </svg>
                                             {{ $message }}
                                        </span>
                                   @enderror
                              </div>

                              {{-- Password --}}
                              <div class="form-group">
                                   <div class="label-row">
                                        <label for="password" class="form-label">Password</label>

                                        @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                             <a href="{{ route('password.request') }}" class="forgot-link">
                                                  Lupa password?
                                             </a>
                                        @endif
                                   </div>

                                   <div class="field">
                                        <span class="field-icon" aria-hidden="true">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="1.8">
                                                  <rect x="4" y="10" width="16" height="11" rx="3" />
                                                  <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-linecap="round" />
                                             </svg>
                                        </span>

                                        <input type="password" id="password" name="password"
                                             class="form-control @error('password') is-invalid @enderror"
                                             placeholder="Masukkan password" autocomplete="current-password"
                                             maxlength="255"
                                             aria-invalid="@error('password') true @else false @enderror"
                                             @error('password') aria-describedby="passwordError" @enderror required>

                                        <button type="button" id="togglePassword" class="password-toggle"
                                             aria-label="Tampilkan password" aria-pressed="false">
                                             <svg class="icon-eye" viewBox="0 0 24 24" fill="none"
                                                  stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                  <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" />
                                                  <circle cx="12" cy="12" r="2.5" />
                                             </svg>
                                             <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none"
                                                  stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                  <path d="m3 3 18 18" stroke-linecap="round" />
                                                  <path d="M10.6 6.2A10.9 10.9 0 0 1 12 6c6.5 0 10 6 10 6a17.5 17.5 0 0 1-2.2 3"
                                                       stroke-linecap="round" />
                                                  <path d="M6.5 6.5C3.6 8.3 2 12 2 12s3.5 6 10 6a10.8 10.8 0 0 0 4.1-.8"
                                                       stroke-linecap="round" />
                                                  <path d="M9.9 9.9A3 3 0 0 0 14.1 14" stroke-linecap="round" />
                                             </svg>
                                        </button>
                                   </div>

                                   @error('password')
                                        <span id="passwordError" class="error-text" role="alert">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="2" aria-hidden="true">
                                                  <circle cx="12" cy="12" r="9" />
                                                  <path d="M12 8v5M12 16.5h.01" stroke-linecap="round" />
                                             </svg>
                                             {{ $message }}
                                        </span>
                                   @enderror
                              </div>

                              {{-- Role --}}
                              <div class="form-group">
                                   <div class="label-row">
                                        <label for="role_id" class="form-label">Hak Akses</label>
                                   </div>

                                   <div class="field">
                                        <span class="field-icon" aria-hidden="true">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="1.8">
                                                  <circle cx="9" cy="8" r="3" />
                                                  <path d="M3.5 19a5.5 5.5 0 0 1 11 0" stroke-linecap="round" />
                                                  <path d="M16 11h5M18.5 8.5v5" stroke-linecap="round" />
                                             </svg>
                                        </span>

                                        <select id="role_id" name="role_id"
                                             class="form-select @error('role_id') is-invalid @enderror"
                                             aria-invalid="@error('role_id') true @else false @enderror"
                                             @error('role_id') aria-describedby="roleError" @enderror required>
                                             <option value="">Pilih hak akses akun</option>

                                             @forelse (($roles ?? collect()) as $role)
                                                  <option value="{{ $role->id }}" @selected((string) old('role_id') === (string) $role->id)>
                                                       {{ ucwords(strtolower(str_replace('_', ' ', $role->name))) }}
                                                  </option>
                                             @empty
                                                  <option value="" disabled>Data role belum tersedia</option>
                                             @endforelse
                                        </select>

                                        <span class="select-chevron" aria-hidden="true">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="2">
                                                  <path d="m7 10 5 5 5-5" stroke-linecap="round"
                                                       stroke-linejoin="round" />
                                             </svg>
                                        </span>
                                   </div>

                                   @error('role_id')
                                        <span id="roleError" class="error-text" role="alert">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                  stroke-width="2" aria-hidden="true">
                                                  <circle cx="12" cy="12" r="9" />
                                                  <path d="M12 8v5M12 16.5h.01" stroke-linecap="round" />
                                             </svg>
                                             {{ $message }}
                                        </span>
                                   @enderror
                              </div>

                              <div class="form-options">
                                   <label for="remember" class="remember">
                                        <input type="checkbox" id="remember" name="remember" value="1"
                                             @checked(old('remember'))>
                                        <span>Ingat saya di perangkat ini</span>
                                   </label>

                                   <span class="secure-badge">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" aria-hidden="true">
                                             <path d="M12 3 5 6v5c0 4.8 3 8.5 7 10 4-1.5 7-5.2 7-10V6l-7-3Z"
                                                  stroke-linejoin="round" />
                                             <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Akses aman
                                   </span>
                              </div>

                              <button type="submit" id="loginButton" class="submit-button">
                                   <span id="buttonIcon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                             <path d="M5 12h14M14 7l5 5-5 5" stroke-linecap="round"
                                                  stroke-linejoin="round" />
                                        </svg>
                                   </span>
                                   <span id="buttonText">Masuk ke Dashboard</span>
                              </button>
                         </form>

                         <footer class="login-footer">
                              <strong>&copy; {{ now()->year }} WorkTrack Monitor</strong><br>
                              Monitoring Produktivitas Karyawan dan Transaksi Jasa
                         </footer>
                    </div>
               </div>
          </section>
     </main>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const form = document.getElementById('loginForm');
               const submitButton = document.getElementById('loginButton');
               const buttonIcon = document.getElementById('buttonIcon');
               const buttonText = document.getElementById('buttonText');
               const passwordInput = document.getElementById('password');
               const passwordToggle = document.getElementById('togglePassword');
               const alertCloseButtons = document.querySelectorAll('.alert-close');
               const firstInvalidField = document.querySelector('.is-invalid');

               // Tampilkan atau sembunyikan password.
               if (passwordInput && passwordToggle) {
                    passwordToggle.addEventListener('click', function() {
                         const passwordIsHidden = passwordInput.type === 'password';

                         passwordInput.type = passwordIsHidden ? 'text' : 'password';
                         passwordToggle.classList.toggle('is-visible', passwordIsHidden);
                         passwordToggle.setAttribute('aria-pressed', String(passwordIsHidden));
                         passwordToggle.setAttribute(
                              'aria-label',
                              passwordIsHidden ? 'Sembunyikan password' : 'Tampilkan password'
                         );

                         passwordInput.focus({
                              preventScroll: true
                         });
                         const passwordLength = passwordInput.value.length;
                         passwordInput.setSelectionRange(passwordLength, passwordLength);
                    });
               }

               // Menutup alert tanpa memuat ulang halaman.
               alertCloseButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                         const alert = button.closest('.alert');
                         if (alert) {
                              alert.remove();
                         }
                    });
               });

               // Fokus otomatis ke field yang gagal divalidasi server.
               if (firstInvalidField) {
                    window.setTimeout(function() {
                         firstInvalidField.focus();
                    }, 100);
               }

               // Validasi bawaan browser dan state loading saat form dikirim.
               if (form && submitButton) {
                    form.addEventListener('submit', function(event) {
                         if (!form.checkValidity()) {
                              event.preventDefault();
                              form.reportValidity();
                              return;
                         }

                         submitButton.disabled = true;
                         submitButton.setAttribute('aria-busy', 'true');

                         if (buttonIcon) {
                              buttonIcon.innerHTML = '<span class="spinner"></span>';
                         }

                         if (buttonText) {
                              buttonText.textContent = 'Memverifikasi akun...';
                         }
                    });
               }
          });
     </script>
</body>

</html>
