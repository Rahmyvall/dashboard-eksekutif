<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">

     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <meta name="description" content="Login Dashboard Eksekutif Monitoring Produktivitas Karyawan dan Transaksi Jasa">

     <title>Login | Dashboard Eksekutif</title>

     <style>
          :root {
               --primary: #2563eb;
               --primary-dark: #1d4ed8;
               --secondary: #06b6d4;
               --success: #059669;
               --danger: #dc2626;
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
               font-family:
                    Inter,
                    Arial,
                    Helvetica,
                    sans-serif;
               color: var(--text);
               background: var(--light);
          }

          button,
          input {
               font: inherit;
          }

          a {
               color: inherit;
               text-decoration: none;
          }

          .login-page {
               display: grid;
               grid-template-columns:
                    minmax(0, 1.15fr) minmax(420px, 0.85fr);
               min-height: 100vh;
          }

          /*
        |--------------------------------------------------------------------------
        | Sisi kiri
        |--------------------------------------------------------------------------
        */

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
                         rgba(6, 182, 212, 0.28),
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
               border: 1px solid rgba(255, 255, 255, 0.15);
          }

          .login-hero::after {
               bottom: -230px;
               left: -170px;
               width: 500px;
               height: 500px;
               background: rgba(255, 255, 255, 0.05);
          }

          .brand {
               position: relative;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               gap: 14px;
               width: max-content;
          }

          .brand-icon {
               display: flex;
               align-items: center;
               justify-content: center;
               width: 52px;
               height: 52px;
               border: 1px solid rgba(255, 255, 255, 0.25);
               border-radius: 16px;
               font-size: 23px;
               background: rgba(255, 255, 255, 0.13);
               box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16);
          }

          .brand-name {
               display: block;
               font-size: 20px;
               font-weight: 700;
               line-height: 1.2;
          }

          .brand-description {
               display: block;
               margin-top: 4px;
               font-size: 12px;
               color: rgba(255, 255, 255, 0.72);
          }

          .hero-content {
               position: relative;
               z-index: 2;
               width: 100%;
               max-width: 700px;
               margin: auto;
          }

          .hero-badge {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               margin-bottom: 20px;
               padding: 8px 14px;
               border: 1px solid rgba(255, 255, 255, 0.17);
               border-radius: 999px;
               font-size: 12px;
               font-weight: 600;
               color: #dbeafe;
               background: rgba(255, 255, 255, 0.08);
          }

          .badge-dot {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #67e8f9;
               box-shadow: 0 0 0 5px rgba(103, 232, 249, 0.12);
          }

          .hero-title {
               max-width: 650px;
               margin: 0 0 16px;
               font-size: clamp(34px, 4vw, 54px);
               font-weight: 800;
               line-height: 1.12;
               letter-spacing: -1.5px;
          }

          .hero-title span {
               color: #67e8f9;
          }

          .hero-text {
               max-width: 610px;
               margin: 0;
               font-size: 15px;
               line-height: 1.8;
               color: rgba(255, 255, 255, 0.77);
          }

          .dashboard-preview {
               margin-top: 36px;
               padding: 22px;
               border: 1px solid rgba(255, 255, 255, 0.14);
               border-radius: 24px;
               background: rgba(255, 255, 255, 0.08);
               box-shadow: 0 28px 55px rgba(0, 0, 0, 0.2);
               backdrop-filter: blur(12px);
          }

          .preview-header {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 18px;
          }

          .preview-title {
               font-size: 14px;
               font-weight: 700;
          }

          .preview-status {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               font-size: 11px;
               color: #bbf7d0;
          }

          .preview-status::before {
               width: 7px;
               height: 7px;
               content: "";
               border-radius: 50%;
               background: #34d399;
          }

          .preview-grid {
               display: grid;
               grid-template-columns: repeat(3, 1fr);
               gap: 12px;
          }

          .preview-card {
               min-height: 100px;
               padding: 16px;
               border-radius: 16px;
               background: rgba(255, 255, 255, 0.1);
          }

          .preview-card span {
               display: block;
          }

          .preview-value {
               margin-bottom: 7px;
               font-size: 24px;
               font-weight: 800;
          }

          .preview-label {
               font-size: 11px;
               line-height: 1.5;
               color: rgba(255, 255, 255, 0.68);
          }

          .hero-footer {
               position: relative;
               z-index: 2;
               margin: 0;
               font-size: 12px;
               color: rgba(255, 255, 255, 0.55);
          }

          /*
        |--------------------------------------------------------------------------
        | Sisi kanan
        |--------------------------------------------------------------------------
        */

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

          .login-card {
               position: relative;
               z-index: 2;
               width: 100%;
               max-width: 480px;
               padding: 42px;
               border: 1px solid rgba(219, 228, 240, 0.95);
               border-radius: 26px;
               background: rgba(255, 255, 255, 0.98);
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
               color: var(--white);
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
               width: 46px;
               height: 46px;
               margin-bottom: 18px;
               border-radius: 14px;
               font-size: 21px;
               color: var(--primary);
               background: #eaf1ff;
          }

          .login-title {
               margin: 0 0 9px;
               font-size: 29px;
               font-weight: 800;
               letter-spacing: -0.6px;
               color: var(--dark);
          }

          .login-subtitle {
               margin: 0;
               font-size: 14px;
               line-height: 1.7;
               color: var(--muted);
          }

          /*
        |--------------------------------------------------------------------------
        | Alert
        |--------------------------------------------------------------------------
        */

          .alert {
               margin-bottom: 20px;
               padding: 14px 16px;
               border-radius: 13px;
               font-size: 13px;
               line-height: 1.6;
          }

          .alert-success {
               border: 1px solid #a7f3d0;
               color: #065f46;
               background: #ecfdf5;
          }

          .alert-danger {
               border: 1px solid #fecaca;
               color: #991b1b;
               background: #fef2f2;
          }

          .alert-title {
               display: block;
               margin-bottom: 4px;
               font-weight: 700;
          }

          .alert ul {
               margin: 5px 0 0;
               padding-left: 18px;
          }

          /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */

          .form-group {
               margin-bottom: 20px;
          }

          .form-label-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               margin-bottom: 8px;
          }

          .form-label {
               margin: 0;
               font-size: 13px;
               font-weight: 700;
               color: var(--dark-soft);
          }

          .forgot-password {
               font-size: 12px;
               font-weight: 700;
               color: var(--primary);
          }

          .forgot-password:hover {
               color: var(--primary-dark);
               text-decoration: underline;
          }

          .input-wrapper {
               position: relative;
          }

          .input-icon {
               position: absolute;
               top: 50%;
               left: 17px;
               z-index: 2;
               font-size: 17px;
               color: #94a3b8;
               transform: translateY(-50%);
               pointer-events: none;
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

          .login-input.is-invalid {
               border-color: #ef4444;
               background: #fffafa;
          }

          .login-input.is-invalid:focus {
               box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 9px;
               z-index: 3;
               display: flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               padding: 0;
               border: none;
               border-radius: 9px;
               color: #64748b;
               background: transparent;
               transform: translateY(-50%);
               cursor: pointer;
          }

          .password-toggle:hover {
               color: var(--primary);
               background: #eff6ff;
          }

          .password-toggle:focus-visible {
               outline: 3px solid rgba(37, 99, 235, 0.2);
          }

          .error-message {
               display: block;
               margin-top: 7px;
               font-size: 12px;
               line-height: 1.5;
               color: var(--danger);
          }

          /*
        |--------------------------------------------------------------------------
        | Opsi form
        |--------------------------------------------------------------------------
        */

          .form-options {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 15px;
               margin: 2px 0 23px;
          }

          .remember-wrapper {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               cursor: pointer;
          }

          .remember-checkbox {
               width: 17px;
               height: 17px;
               accent-color: var(--primary);
               cursor: pointer;
          }

          .remember-label {
               font-size: 12px;
               color: var(--muted);
          }

          .secure-text {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               font-size: 11px;
               color: #94a3b8;
          }

          .secure-icon {
               color: var(--success);
          }

          /*
        |--------------------------------------------------------------------------
        | Tombol
        |--------------------------------------------------------------------------
        */

          .login-button {
               display: flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               width: 100%;
               min-height: 52px;
               padding: 13px 20px;
               border: none;
               border-radius: 13px;
               font-size: 13px;
               font-weight: 800;
               letter-spacing: 0.2px;
               color: var(--white);
               background: linear-gradient(90deg,
                         var(--primary),
                         var(--secondary));
               box-shadow: 0 12px 25px rgba(37, 99, 235, 0.25);
               cursor: pointer;
               transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease,
                    opacity 0.2s ease;
          }

          .login-button:hover:not(:disabled) {
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(37, 99, 235, 0.34);
          }

          .login-button:disabled {
               cursor: wait;
               opacity: 0.72;
          }

          .login-footer {
               margin: 28px 0 0;
               padding-top: 22px;
               border-top: 1px solid #edf2f7;
               text-align: center;
               font-size: 11px;
               line-height: 1.7;
               color: #94a3b8;
          }

          .spinner {
               width: 16px;
               height: 16px;
               border: 2px solid rgba(255, 255, 255, 0.35);
               border-top-color: var(--white);
               border-radius: 50%;
               animation: spin 0.75s linear infinite;
          }

          @keyframes spin {
               to {
                    transform: rotate(360deg);
               }
          }

          /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

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

               .login-card {
                    max-width: none;
                    margin-top: 14px;
                    padding: 29px 23px;
                    border-radius: 21px;
               }

               .login-title {
                    font-size: 25px;
               }

               .form-options {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 12px;
               }
          }

          @media (prefers-reduced-motion: reduce) {

               *,
               *::before,
               *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
               }
          }
     </style>
</head>

<body>
     <main class="login-page">

          {{-- Sisi kiri --}}
          <section class="login-hero">
               <a href="{{ route('login') }}" class="brand">
                    <div class="brand-icon" aria-hidden="true">
                         📊
                    </div>

                    <div>
                         <span class="brand-name">
                              Executive Dashboard
                         </span>

                         <span class="brand-description">
                              Productivity &amp; Service Analytics
                         </span>
                    </div>
               </a>

               <div class="hero-content">
                    <div class="hero-badge">
                         <span class="badge-dot" aria-hidden="true"></span>

                         Sistem Monitoring Terintegrasi
                    </div>

                    <h1 class="hero-title">
                         Pantau Kinerja Bisnis dalam
                         <span>Satu Dashboard</span>
                    </h1>

                    <p class="hero-text">
                         Analisis produktivitas karyawan, perkembangan
                         transaksi jasa, dan pencapaian indikator kinerja
                         secara cepat, akurat, dan terintegrasi.
                    </p>

                    <div class="dashboard-preview">
                         <div class="preview-header">
                              <span class="preview-title">
                                   Ringkasan performa
                              </span>

                              <span class="preview-status">
                                   Sistem aktif
                              </span>
                         </div>

                         <div class="preview-grid">
                              <div class="preview-card">
                                   <span class="preview-value">
                                        98%
                                   </span>

                                   <span class="preview-label">
                                        Target produktivitas
                                   </span>
                              </div>

                              <div class="preview-card">
                                   <span class="preview-value">
                                        1.248
                                   </span>

                                   <span class="preview-label">
                                        Transaksi jasa
                                   </span>
                              </div>

                              <div class="preview-card">
                                   <span class="preview-value">
                                        24/7
                                   </span>

                                   <span class="preview-label">
                                        Monitoring data
                                   </span>
                              </div>
                         </div>
                    </div>
               </div>

               <p class="hero-footer">
                    &copy; {{ now()->year }} Executive Monitoring System
               </p>
          </section>

          {{-- Sisi kanan --}}
          <section class="login-section">
               <div class="login-card">

                    <div class="mobile-brand">
                         <div class="brand-icon" aria-hidden="true">
                              📊
                         </div>

                         <div>
                              <span class="brand-name">
                                   Executive Dashboard
                              </span>

                              <span class="brand-description">
                                   Productivity &amp; Service Analytics
                              </span>
                         </div>
                    </div>

                    <header class="login-heading">
                         <div class="login-heading-icon" aria-hidden="true">
                              🔐
                         </div>

                         <h1 class="login-title">
                              Selamat Datang
                         </h1>

                         <p class="login-subtitle">
                              Masukkan email dan password untuk mengakses
                              Dashboard Eksekutif.
                         </p>
                    </header>

                    {{-- Pesan sukses --}}
                    @if (session()->has('success'))
                         <div class="alert alert-success" role="status">
                              {{ session('success') }}
                         </div>
                    @endif

                    {{-- Pesan error dari session --}}
                    @if (session()->has('error'))
                         <div class="alert alert-danger" role="alert">
                              {{ session('error') }}
                         </div>
                    @endif

                    {{-- Daftar kesalahan validasi --}}
                    @if ($errors->any())
                         <div class="alert alert-danger" role="alert">
                              <span class="alert-title">
                                   Login gagal.
                              </span>

                              <ul>
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    @endif

                    <form id="loginForm" action="{{ route('login.process') }}" method="POST">
                         @csrf

                         {{-- Email --}}
                         <div class="form-group">
                              <div class="form-label-row">
                                   <label for="email" class="form-label">
                                        Alamat Email
                                   </label>
                              </div>

                              <div class="input-wrapper">
                                   <span class="input-icon" aria-hidden="true">
                                        ✉
                                   </span>

                                   <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="login-input @error('email') is-invalid @enderror"
                                        placeholder="contoh@email.com" autocomplete="email" inputmode="email"
                                        spellcheck="false" maxlength="150" autofocus required
                                        @error('email')
                                    aria-invalid="true"
                                    aria-describedby="emailError"
                                @else
                                    aria-invalid="false"
                                @enderror>
                              </div>

                              @error('email')
                                   <span id="emailError" class="error-message" role="alert">
                                        {{ $message }}
                                   </span>
                              @enderror
                         </div>

                         {{-- Password --}}
                         <div class="form-group">
                              <div class="form-label-row">
                                   <label for="password" class="form-label">
                                        Password
                                   </label>

                                   @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="forgot-password">
                                             Lupa password?
                                        </a>
                                   @endif
                              </div>

                              <div class="input-wrapper">
                                   <span class="input-icon" aria-hidden="true">
                                        🔒
                                   </span>

                                   <input type="password" id="password" name="password"
                                        class="login-input @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password" autocomplete="current-password" maxlength="255"
                                        required
                                        @error('password')
                                    aria-invalid="true"
                                    aria-describedby="passwordError"
                                @else
                                    aria-invalid="false"
                                @enderror>

                                   <button type="button" id="togglePassword" class="password-toggle"
                                        aria-label="Tampilkan password" aria-controls="password" aria-pressed="false">
                                        <span id="passwordToggleIcon">
                                             👁
                                        </span>
                                   </button>
                              </div>

                              @error('password')
                                   <span id="passwordError" class="error-message" role="alert">
                                        {{ $message }}
                                   </span>
                              @enderror
                         </div>

                         {{-- Ingat saya --}}
                         <div class="form-options">
                              <label for="remember" class="remember-wrapper">
                                   <input type="checkbox" id="remember" name="remember" value="1"
                                        class="remember-checkbox" @checked(old('remember'))>

                                   <span class="remember-label">
                                        Ingat saya
                                   </span>
                              </label>

                              <span class="secure-text">
                                   <span class="secure-icon" aria-hidden="true">
                                        ✓
                                   </span>

                                   Akses terlindungi
                              </span>
                         </div>

                         {{-- Tombol login --}}
                         <button type="submit" id="loginButton" class="login-button">
                              <span id="loginButtonIcon" aria-hidden="true">
                                   ➜
                              </span>

                              <span id="loginButtonText">
                                   Masuk ke Dashboard
                              </span>
                         </button>
                    </form>

                    <p class="login-footer">
                         &copy; {{ now()->year }} Dashboard Eksekutif
                         <br>
                         Monitoring Produktivitas Karyawan dan Transaksi Jasa
                    </p>
               </div>
          </section>
     </main>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const loginForm =
                    document.getElementById('loginForm');

               const loginButton =
                    document.getElementById('loginButton');

               const loginButtonIcon =
                    document.getElementById('loginButtonIcon');

               const loginButtonText =
                    document.getElementById('loginButtonText');

               const passwordInput =
                    document.getElementById('password');

               const togglePassword =
                    document.getElementById('togglePassword');

               const passwordToggleIcon =
                    document.getElementById('passwordToggleIcon');

               if (togglePassword && passwordInput) {
                    togglePassword.addEventListener(
                         'click',
                         function() {
                              const passwordIsHidden =
                                   passwordInput.type === 'password';

                              passwordInput.type =
                                   passwordIsHidden ?
                                   'text' :
                                   'password';

                              togglePassword.setAttribute(
                                   'aria-pressed',
                                   passwordIsHidden ?
                                   'true' :
                                   'false'
                              );

                              togglePassword.setAttribute(
                                   'aria-label',
                                   passwordIsHidden ?
                                   'Sembunyikan password' :
                                   'Tampilkan password'
                              );

                              if (passwordToggleIcon) {
                                   passwordToggleIcon.textContent =
                                        passwordIsHidden ?
                                        '🙈' :
                                        '👁';
                              }
                         }
                    );
               }

               if (loginForm && loginButton) {
                    loginForm.addEventListener(
                         'submit',
                         function() {
                              loginButton.disabled = true;

                              if (loginButtonIcon) {
                                   loginButtonIcon.className = 'spinner';
                                   loginButtonIcon.textContent = '';
                              }

                              if (loginButtonText) {
                                   loginButtonText.textContent =
                                        'Memproses login...';
                              }
                         }
                    );
               }
          });
     </script>
</body>

</html>
