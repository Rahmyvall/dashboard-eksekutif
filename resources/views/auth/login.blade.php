<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">

     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <meta name="description" content="Login sistem monitoring produktivitas karyawan dan kepuasan pelanggan">

     <title>Login | Executive Monitoring System</title>

     <style>
          :root {
               --primary: #2563eb;
               --primary-dark: #1d4ed8;
               --secondary: #06b6d4;
               --success: #059669;
               --danger: #dc2626;
               --dark: #0f172a;
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
          input,
          select {
               font: inherit;
          }

          a {
               color: inherit;
               text-decoration: none;
          }

          .login-page {
               display: grid;
               grid-template-columns:
                    minmax(0, 1.1fr) minmax(420px, 0.9fr);
               min-height: 100vh;
          }

          /*
        |--------------------------------------------------------------------------
        | Bagian kiri
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
          }

          .brand-name {
               display: block;
               font-size: 20px;
               font-weight: 700;
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
               max-width: 680px;
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
          }

          .hero-title {
               margin: 0 0 16px;
               font-size: clamp(34px, 4vw, 54px);
               line-height: 1.12;
               letter-spacing: -1.5px;
          }

          .hero-title span {
               color: #67e8f9;
          }

          .hero-text {
               max-width: 600px;
               margin: 0;
               font-size: 15px;
               line-height: 1.8;
               color: rgba(255, 255, 255, 0.77);
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
        | Bagian kanan
        |--------------------------------------------------------------------------
        */

          .login-section {
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
               width: 100%;
               max-width: 480px;
               padding: 42px;
               border: 1px solid var(--border);
               border-radius: 26px;
               background: var(--white);
               box-shadow:
                    0 30px 70px rgba(15, 23, 42, 0.11),
                    0 3px 15px rgba(15, 23, 42, 0.05);
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

          .alert ul {
               margin: 0;
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
               font-size: 13px;
               font-weight: 700;
               color: var(--dark);
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

          .login-input,
          .login-select {
               width: 100%;
               height: 52px;
               border: 1px solid var(--border);
               border-radius: 13px;
               outline: none;
               font-size: 13px;
               color: var(--dark);
               background: #fbfdff;
          }

          .login-input {
               padding: 12px 48px 12px 47px;
          }

          .login-select {
               padding: 12px 46px 12px 47px;
               cursor: pointer;
               appearance: none;
          }

          .login-input:focus,
          .login-select:focus {
               border-color: var(--primary);
               background: var(--white);
               box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
          }

          .login-input.is-invalid,
          .login-select.is-invalid {
               border-color: var(--danger);
               background: #fffafa;
          }

          .select-arrow {
               position: absolute;
               top: 50%;
               right: 17px;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 9px;
               display: flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               border: none;
               border-radius: 9px;
               background: transparent;
               transform: translateY(-50%);
               cursor: pointer;
          }

          .error-message {
               display: block;
               margin-top: 7px;
               font-size: 12px;
               color: var(--danger);
          }

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
          }

          .remember-label,
          .secure-text {
               font-size: 12px;
               color: var(--muted);
          }

          .secure-text {
               color: var(--success);
          }

          .login-button {
               display: flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               width: 100%;
               min-height: 52px;
               border: none;
               border-radius: 13px;
               font-size: 13px;
               font-weight: 800;
               color: var(--white);
               background:
                    linear-gradient(90deg,
                         var(--primary),
                         var(--secondary));
               cursor: pointer;
          }

          .login-button:disabled {
               opacity: 0.7;
               cursor: wait;
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
          }

          @media (max-width: 575px) {
               .login-section {
                    padding: 18px;
               }

               .login-card {
                    padding: 29px 23px;
                    border-radius: 21px;
               }

               .form-options {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }
     </style>
</head>

<body>
     <main class="login-page">

          {{-- Bagian kiri --}}
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
                         Analisis produktivitas karyawan, transaksi jasa,
                         kepuasan pelanggan, serta pencapaian indikator
                         kinerja secara cepat dan terintegrasi.
                    </p>

               </div>

               <p class="hero-footer">
                    &copy; {{ now()->year }}
                    Executive Monitoring System
               </p>

          </section>

          {{-- Bagian kanan --}}
          <section class="login-section">

               <div class="login-card">

                    <header class="login-heading">

                         <div class="login-heading-icon" aria-hidden="true">
                              🔐
                         </div>

                         <h1 class="login-title">
                              Selamat Datang
                         </h1>

                         <p class="login-subtitle">
                              Masukkan email, password, dan pilih role
                              yang akan digunakan.
                         </p>

                    </header>

                    @if (session()->has('success'))
                         <div class="alert alert-success" role="status">
                              {{ session('success') }}
                         </div>
                    @endif

                    @if (session()->has('error'))
                         <div class="alert alert-danger" role="alert">
                              {{ session('error') }}
                         </div>
                    @endif

                    @if ($errors->any())
                         <div class="alert alert-danger" role="alert">
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
                                        placeholder="contoh@email.com" autocomplete="email" maxlength="150" required
                                        autofocus>

                              </div>

                              @error('email')
                                   <span class="error-message" role="alert">
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
                                        required>

                                   <button type="button" id="togglePassword" class="password-toggle"
                                        aria-label="Tampilkan password">
                                        <span id="passwordToggleIcon">
                                             👁
                                        </span>
                                   </button>

                              </div>

                              @error('password')
                                   <span class="error-message" role="alert">
                                        {{ $message }}
                                   </span>
                              @enderror

                         </div>

                         {{-- Role --}}
                         <div class="form-group">

                              <div class="form-label-row">
                                   <label for="role_id" class="form-label">
                                        Akses Sebagai
                                   </label>
                              </div>

                              <div class="input-wrapper">

                                   <span class="input-icon" aria-hidden="true">
                                        👥
                                   </span>

                                   <select id="role_id" name="role_id"
                                        class="login-select @error('role_id') is-invalid @enderror" required>
                                        <option value="">
                                             Pilih role akses
                                        </option>

                                        @forelse (($roles ?? collect()) as $role)
                                             <option value="{{ $role->id }}" @selected((string) old('role_id') === (string) $role->id)>
                                                  {{ ucwords(strtolower(str_replace('_', ' ', $role->name))) }}
                                             </option>
                                        @empty
                                             <option value="" disabled>
                                                  Data role belum tersedia
                                             </option>
                                        @endforelse
                                   </select>

                                   <span class="select-arrow" aria-hidden="true">
                                        ▼
                                   </span>

                              </div>

                              @error('role_id')
                                   <span class="error-message" role="alert">
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
                                   ✓ Akses terlindungi
                              </span>

                         </div>

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
                         &copy; {{ now()->year }}
                         Executive Monitoring System
                         <br>
                         Monitoring Kinerja dan Kepuasan Pelanggan
                    </p>

               </div>

          </section>

     </main>

     <script>
          document.addEventListener(
               'DOMContentLoaded',
               function() {
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
                         document.getElementById(
                              'passwordToggleIcon'
                         );

                    if (togglePassword && passwordInput) {
                         togglePassword.addEventListener(
                              'click',
                              function() {
                                   const isHidden =
                                        passwordInput.type === 'password';

                                   passwordInput.type =
                                        isHidden ?
                                        'text' :
                                        'password';

                                   togglePassword.setAttribute(
                                        'aria-label',
                                        isHidden ?
                                        'Sembunyikan password' :
                                        'Tampilkan password'
                                   );

                                   passwordToggleIcon.textContent =
                                        isHidden ?
                                        '🙈' :
                                        '👁';
                              }
                         );
                    }

                    if (loginForm && loginButton) {
                         loginForm.addEventListener(
                              'submit',
                              function() {
                                   loginButton.disabled = true;

                                   loginButtonIcon.className =
                                        'spinner';

                                   loginButtonIcon.textContent = '';

                                   loginButtonText.textContent =
                                        'Memproses login...';
                              }
                         );
                    }
               }
          );
     </script>
</body>

</html>
