<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Login â€“ Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa">
     <meta name="theme-color" content="#0f0c29">

     <title>Login | Dashboard Monitoring Produktivitas & Transaksi Jasa</title>

     <style>
          :root {
               --primary: #4f46e5;
               --primary-dark: #3730a3;
               --primary-light: #818cf8;
               --cyan: #06b6d4;
               --teal: #0d9488;
               --ink: #0f172a;
               --text: #334155;
               --muted: #64748b;
               --border: #e2e8f0;
               --surface: #ffffff;
               --page: #f4f6fb;
               --danger: #dc2626;
               --danger-soft: #fef2f2;
               --success: #16a34a;
               --success-soft: #f0fdf4;
               --radius-xl: 28px;
               --radius-lg: 20px;
               --radius-md: 14px;
               --radius-sm: 10px;
          }

          * {
               box-sizing: border-box;
          }

          html {
               min-height: 100%;
          }

          body {
               min-height: 100vh;
               margin: 0;
               overflow-x: hidden;
               color: var(--text);
               background: var(--page);
               font-family: 'Plus Jakarta Sans', Inter, ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
               -webkit-font-smoothing: antialiased;
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

          /* â”€â”€ LAYOUT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
          .auth-layout {
               display: grid;
               grid-template-columns: 1.15fr minmax(480px, 0.85fr);
               min-height: 100vh;
          }

          /* â”€â”€ LEFT PANEL â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
          .auth-visual {
               position: relative;
               isolation: isolate;
               display: flex;
               overflow: hidden;
               color: #fff;
               background:
                    radial-gradient(circle at 14% 20%, rgba(99, 102, 241, .45), transparent 32%),
                    radial-gradient(circle at 88% 78%, rgba(6, 182, 212, .38), transparent 34%),
                    radial-gradient(circle at 56% 55%, rgba(139, 92, 246, .22), transparent 28%),
                    linear-gradient(138deg, #0f0c29 0%, #1a1060 42%, #2d1b6e 72%, #0e3460 100%);
          }

          /* Dot grid overlay */
          .auth-visual::before {
               position: absolute;
               inset: 0;
               z-index: -2;
               content: '';
               opacity: .14;
               background-image:
                    radial-gradient(circle, rgba(255, 255, 255, .7) 1px, transparent 1px);
               background-size: 36px 36px;
               mask-image: linear-gradient(135deg, black 30%, transparent 85%);
          }

          /* Decorative circle */
          .auth-visual::after {
               position: absolute;
               top: -140px;
               right: -150px;
               z-index: -1;
               width: 500px;
               height: 500px;
               content: '';
               border: 1px solid rgba(255, 255, 255, .1);
               border-radius: 50%;
               box-shadow:
                    0 0 0 60px rgba(255, 255, 255, .03),
                    0 0 0 120px rgba(255, 255, 255, .02);
          }

          .visual-inner {
               position: relative;
               display: flex;
               flex: 1;
               flex-direction: column;
               justify-content: space-between;
               width: 100%;
               max-width: 900px;
               margin: 0 auto;
               padding: 40px clamp(40px, 5.5vw, 80px) 32px;
          }

          /* Brand */
          .brand {
               display: inline-flex;
               align-items: center;
               gap: 14px;
               width: fit-content;
          }

          .brand-mark {
               display: grid;
               width: 52px;
               height: 52px;
               place-items: center;
               border: 1px solid rgba(255, 255, 255, .18);
               border-radius: 16px;
               background: rgba(255, 255, 255, .1);
               backdrop-filter: blur(12px);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .16);
          }

          .brand-mark svg {
               width: 26px;
               height: 26px;
          }

          .brand-copy strong,
          .brand-copy span {
               display: block;
          }

          .brand-copy strong {
               font-size: 17px;
               font-weight: 800;
               letter-spacing: -.2px;
          }

          .brand-copy span {
               margin-top: 3px;
               font-size: 10.5px;
               color: rgba(255, 255, 255, .6);
               letter-spacing: .7px;
               text-transform: uppercase;
          }

          /* Hero content */
          .visual-content {
               width: 100%;
               max-width: 700px;
               margin: auto 0;
               padding: 42px 0;
          }

          .eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 9px;
               margin-bottom: 24px;
               padding: 7px 14px;
               border: 1px solid rgba(255, 255, 255, .16);
               border-radius: 999px;
               color: #bfdbfe;
               background: rgba(255, 255, 255, .07);
               font-size: 11px;
               font-weight: 700;
               letter-spacing: .6px;
               text-transform: uppercase;
               backdrop-filter: blur(10px);
          }

          .eyebrow-pulse {
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #67e8f9;
               box-shadow: 0 0 0 4px rgba(103, 232, 249, .15);
               animation: pulse 2.4s ease-in-out infinite;
          }

          @keyframes pulse {

               0%,
               100% {
                    box-shadow: 0 0 0 4px rgba(103, 232, 249, .15);
               }

               50% {
                    box-shadow: 0 0 0 8px rgba(103, 232, 249, .05);
               }
          }

          .visual-title {
               max-width: 680px;
               margin: 0;
               font-size: clamp(38px, 4.4vw, 62px);
               font-weight: 900;
               line-height: 1.05;
               letter-spacing: -2.5px;
          }

          .visual-title em {
               font-style: normal;
               background: linear-gradient(90deg, #67e8f9, #a78bfa);
               -webkit-background-clip: text;
               background-clip: text;
               -webkit-text-fill-color: transparent;
          }

          .visual-desc {
               max-width: 620px;
               margin: 20px 0 0;
               color: rgba(255, 255, 255, .7);
               font-size: 14.5px;
               line-height: 1.85;
          }

          /* Metric chips */
          .metric-row {
               display: flex;
               flex-wrap: wrap;
               gap: 10px;
               margin-top: 28px;
          }

          .metric-chip {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 10px 14px;
               border: 1px solid rgba(255, 255, 255, .14);
               border-radius: 14px;
               background: rgba(255, 255, 255, .08);
               backdrop-filter: blur(14px);
               font-size: 12px;
               font-weight: 700;
               line-height: 1;
          }

          .metric-chip-icon {
               display: grid;
               width: 28px;
               height: 28px;
               place-items: center;
               border-radius: 8px;
               background: rgba(255, 255, 255, .12);
          }

          .metric-chip-icon svg {
               width: 14px;
               height: 14px;
          }

          .metric-chip-body span {
               display: block;
          }

          .metric-chip-val {
               font-size: 13px;
               font-weight: 850;
               color: #fff;
          }

          .metric-chip-lbl {
               font-size: 9.5px;
               font-weight: 700;
               color: rgba(255, 255, 255, .58);
               letter-spacing: .4px;
               text-transform: uppercase;
               margin-top: 2px;
          }

          /* Feature cards */
          .feature-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 12px;
               margin-top: 28px;
          }

          .feature-card {
               padding: 18px;
               border: 1px solid rgba(255, 255, 255, .1);
               border-radius: 18px;
               background: rgba(255, 255, 255, .06);
               backdrop-filter: blur(14px);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .08);
               transition: background .2s ease;
          }

          .feature-card:hover {
               background: rgba(255, 255, 255, .1);
          }

          .feature-ic {
               display: grid;
               width: 36px;
               height: 36px;
               margin-bottom: 14px;
               place-items: center;
               border-radius: 10px;
               background: rgba(103, 232, 249, .12);
               color: #a5f3fc;
          }

          .feature-ic svg {
               width: 18px;
               height: 18px;
          }

          .feature-card strong {
               display: block;
               font-size: 12.5px;
               font-weight: 800;
               margin-bottom: 6px;
          }

          .feature-card span {
               display: block;
               font-size: 10.5px;
               color: rgba(255, 255, 255, .56);
               line-height: 1.6;
          }

          /* Footer */
          .visual-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               color: rgba(255, 255, 255, .42);
               font-size: 11px;
          }

          .status-pill {
               display: inline-flex;
               align-items: center;
               gap: 7px;
          }

          .status-dot {
               width: 7px;
               height: 7px;
               border-radius: 50%;
               background: #4ade80;
               box-shadow: 0 0 0 4px rgba(74, 222, 128, .1);
          }

          /* â”€â”€ RIGHT PANEL â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
          .auth-form-panel {
               position: relative;
               display: flex;
               min-height: 100vh;
               align-items: center;
               justify-content: center;
               padding: 40px clamp(22px, 4vw, 62px);
               overflow: hidden;
               background:
                    radial-gradient(circle at 95% 4%, rgba(79, 70, 229, .12), transparent 26%),
                    radial-gradient(circle at 4% 96%, rgba(6, 182, 212, .08), transparent 26%),
                    var(--page);
          }

          .auth-form-panel::before {
               position: absolute;
               top: 60px;
               right: -55px;
               width: 160px;
               height: 160px;
               content: '';
               border: 26px solid rgba(79, 70, 229, .04);
               border-radius: 50%;
          }

          .auth-form-panel::after {
               position: absolute;
               bottom: 60px;
               left: -45px;
               width: 130px;
               height: 130px;
               content: '';
               border: 22px solid rgba(6, 182, 212, .05);
               border-radius: 50%;
          }

          .login-shell {
               position: relative;
               z-index: 1;
               width: 100%;
               max-width: 500px;
          }

          /* Mobile brand */
          .mobile-brand {
               display: none;
               align-items: center;
               gap: 12px;
               margin-bottom: 24px;
          }

          .mobile-brand-mark {
               display: grid;
               width: 44px;
               height: 44px;
               place-items: center;
               border-radius: 13px;
               color: #fff;
               background: linear-gradient(135deg, var(--primary-dark), var(--cyan));
               box-shadow: 0 10px 24px rgba(79, 70, 229, .28);
          }

          .mobile-brand-mark svg {
               width: 22px;
               height: 22px;
          }

          .mobile-brand-copy strong {
               display: block;
               color: var(--ink);
               font-size: 15px;
               font-weight: 800;
          }

          .mobile-brand-copy span {
               display: block;
               margin-top: 2px;
               color: var(--muted);
               font-size: 10px;
          }

          /* Card */
          .login-card {
               width: 100%;
               padding: clamp(28px, 4vw, 46px);
               border: 1px solid rgba(226, 232, 240, .88);
               border-radius: var(--radius-xl);
               background: rgba(255, 255, 255, .97);
               box-shadow:
                    0 4px 6px rgba(15, 23, 42, .04),
                    0 24px 64px rgba(15, 23, 42, .10),
                    0 0 0 1px rgba(255, 255, 255, .6) inset;
               backdrop-filter: blur(20px);
          }

          /* Header */
          .login-header {
               margin-bottom: 28px;
          }

          .login-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 52px;
               height: 52px;
               margin-bottom: 20px;
               border-radius: 16px;
               color: var(--primary);
               background: linear-gradient(135deg, #eef2ff, #e0f2fe);
               box-shadow: 0 0 0 1px rgba(79, 70, 229, .1) inset;
          }

          .login-icon svg {
               width: 24px;
               height: 24px;
          }

          .login-badge {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 14px;
               padding: 5px 11px;
               border: 1px solid #c7d2fe;
               border-radius: 999px;
               color: var(--primary);
               background: #eef2ff;
               font-size: 10.5px;
               font-weight: 800;
               letter-spacing: .5px;
               text-transform: uppercase;
          }

          .login-badge-dot {
               width: 6px;
               height: 6px;
               border-radius: 50%;
               background: var(--primary-light);
          }

          .login-title {
               margin: 0;
               color: var(--ink);
               font-size: clamp(26px, 2.8vw, 32px);
               font-weight: 850;
               line-height: 1.2;
               letter-spacing: -.75px;
          }

          .login-subtitle {
               max-width: 400px;
               margin: 9px 0 0;
               color: var(--muted);
               font-size: 13px;
               line-height: 1.7;
          }

          /* Alerts */
          .alert {
               position: relative;
               display: flex;
               align-items: flex-start;
               gap: 11px;
               margin-bottom: 18px;
               padding: 13px 44px 13px 14px;
               border: 1px solid;
               border-radius: 13px;
               font-size: 12px;
               line-height: 1.6;
          }

          .alert svg {
               flex: 0 0 auto;
               width: 17px;
               height: 17px;
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
               padding-left: 16px;
          }

          .alert-close {
               position: absolute;
               top: 8px;
               right: 8px;
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
               opacity: .6;
               transition: opacity .15s, background .15s;
          }

          .alert-close:hover {
               background: rgba(15, 23, 42, .06);
               opacity: 1;
          }

          .alert-close svg {
               width: 14px;
               height: 14px;
               margin: 0;
          }

          /* Form */
          .form-group {
               margin-bottom: 18px;
          }

          .label-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
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
               transition: color .18s;
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
               width: 18px;
               height: 18px;
          }

          .form-control,
          .form-select {
               width: 100%;
               height: 52px;
               padding: 12px 48px;
               color: var(--ink);
               font-size: 13px;
               border: 1.5px solid var(--border);
               border-radius: var(--radius-md);
               background: #fafcff;
               outline: 0;
               transition: border-color .18s, box-shadow .18s, background .18s;
          }

          .form-select {
               padding-right: 44px;
               cursor: pointer;
               appearance: none;
          }

          .form-control::placeholder {
               color: #a8b3c3;
          }

          .form-control:hover,
          .form-select:hover {
               border-color: #c7d2e8;
          }

          .form-control:focus,
          .form-select:focus {
               border-color: var(--primary);
               background: #fff;
               box-shadow: 0 0 0 4px rgba(79, 70, 229, .1);
          }

          .form-control.is-invalid,
          .form-select.is-invalid {
               border-color: var(--danger);
               background: #fffafa;
          }

          .form-control.is-invalid:focus,
          .form-select.is-invalid:focus {
               box-shadow: 0 0 0 4px rgba(220, 38, 38, .09);
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
               width: 16px;
               height: 16px;
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 8px;
               display: grid;
               width: 36px;
               height: 36px;
               padding: 0;
               place-items: center;
               border: 0;
               border-radius: 10px;
               color: #64748b;
               background: transparent;
               transform: translateY(-50%);
               cursor: pointer;
               transition: color .18s, background .18s;
          }

          .password-toggle:hover {
               color: var(--primary);
               background: #eef2ff;
          }

          .password-toggle svg {
               width: 18px;
               height: 18px;
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
               line-height: 1.5;
          }

          .error-text svg {
               flex: 0 0 auto;
               width: 13px;
               height: 13px;
               margin-top: 1px;
          }

          /* Form options row */
          .form-options {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               margin: 2px 0 22px;
          }

          .remember {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               cursor: pointer;
               user-select: none;
          }

          .remember input {
               width: 16px;
               height: 16px;
               margin: 0;
               accent-color: var(--primary);
               cursor: pointer;
          }

          .remember span {
               color: var(--muted);
               font-size: 11px;
          }

          .secure-badge {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               color: var(--success);
               font-size: 11px;
               font-weight: 700;
          }

          .secure-badge svg {
               width: 14px;
               height: 14px;
          }

          /* Submit button */
          .submit-button {
               position: relative;
               display: flex;
               width: 100%;
               min-height: 52px;
               align-items: center;
               justify-content: center;
               gap: 10px;
               overflow: hidden;
               border: 0;
               border-radius: var(--radius-md);
               color: #fff;
               font-size: 13.5px;
               font-weight: 850;
               letter-spacing: .1px;
               cursor: pointer;
               background: linear-gradient(100deg, #3730a3 0%, #4f46e5 50%, #06b6d4 100%);
               box-shadow:
                    0 1px 2px rgba(79, 70, 229, .2),
                    0 12px 28px rgba(79, 70, 229, .28);
               transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
          }

          .submit-button::before {
               position: absolute;
               inset: 0;
               content: '';
               background: linear-gradient(120deg, transparent 20%, rgba(255, 255, 255, .18), transparent 80%);
               transform: translateX(-110%);
               transition: transform .6s ease;
          }

          .submit-button:hover:not(:disabled) {
               transform: translateY(-1px);
               box-shadow: 0 1px 2px rgba(79, 70, 229, .2), 0 16px 36px rgba(79, 70, 229, .34);
          }

          .submit-button:hover:not(:disabled)::before {
               transform: translateX(110%);
          }

          .submit-button:active:not(:disabled) {
               transform: translateY(0);
          }

          .submit-button:disabled {
               cursor: wait;
               opacity: .76;
          }

          .submit-button svg {
               width: 18px;
               height: 18px;
          }

          .spinner {
               width: 18px;
               height: 18px;
               border: 2px solid rgba(255, 255, 255, .35);
               border-top-color: #fff;
               border-radius: 50%;
               animation: spin .72s linear infinite;
          }

          @keyframes spin {
               to {
                    transform: rotate(360deg);
               }
          }

          /* Footer */
          .login-footer {
               margin: 24px 0 0;
               padding-top: 18px;
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
               outline: 3px solid rgba(79, 70, 229, .25);
               outline-offset: 3px;
          }

          @media (prefers-reduced-motion: reduce) {

               *,
               *::before,
               *::after {
                    animation-duration: .01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: .01ms !important;
               }
          }

          /* Responsive */
          @media (max-width: 1180px) {
               .auth-layout {
                    grid-template-columns: 1fr minmax(440px, .88fr);
               }

               .visual-inner {
                    padding-inline: 46px;
               }

               .visual-title {
                    font-size: clamp(36px, 4vw, 54px);
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
                    padding: 30px 22px;
               }

               .mobile-brand {
                    display: flex;
               }
          }

          @media (max-width: 560px) {
               .auth-form-panel {
                    align-items: flex-start;
                    padding: 22px 14px;
               }

               .login-shell {
                    margin: auto 0;
               }

               .login-card {
                    padding: 26px 20px;
                    border-radius: 22px;
                    box-shadow: 0 20px 52px rgba(15, 23, 42, .12);
               }

               .login-title {
                    font-size: 26px;
               }

               .form-options {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }

          @media (max-width: 380px) {
               .login-card {
                    padding: 22px 16px;
               }
          }
     </style>
</head>

<body>
     <main class="auth-layout">

          {{-- LEFT PANEL --}}
          <section class="auth-visual" aria-label="Informasi aplikasi">
               <div class="visual-inner">

                    <a href="{{ route('login') }}" class="brand" aria-label="Halaman login">
                         <span class="brand-mark" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                   <path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round" />
                                   <path d="m7 16 4-5 3 3 5-7" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                         </span>
                         <span class="brand-copy">
                              <strong>WorkTrack Monitor</strong>
                              <span>Business Performance System</span>
                         </span>
                    </a>

                    <div class="visual-content">
                         <div class="eyebrow">
                              <span class="eyebrow-pulse" aria-hidden="true"></span>
                              Monitoring Operasional Terintegrasi
                         </div>

                         <h1 class="visual-title">
                              Produktivitas tim &amp;<br>
                              transaksi jasa dalam<br>
                              <em>satu kendali.</em>
                         </h1>

                         <p class="visual-desc">
                              Pantau aktivitas karyawan, capaian pekerjaan, transaksi layanan, serta
                              performa operasional secara terstruktur untuk mendukung keputusan yang
                              lebih cepat dan akurat.
                         </p>

                         <div class="metric-row">
                              <div class="metric-chip">
                                   <span class="metric-chip-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.9">
                                             <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                  stroke-linecap="round" />
                                             <circle cx="9" cy="7" r="4" />
                                             <path d="M22 21v-2a4 4 0 0 0-3-3.87" stroke-linecap="round" />
                                             <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke-linecap="round" />
                                        </svg>
                                   </span>
                                   <span class="metric-chip-body">
                                        <span class="metric-chip-val">Produktivitas</span>
                                        <span class="metric-chip-lbl">Karyawan</span>
                                   </span>
                              </div>
                              <div class="metric-chip">
                                   <span class="metric-chip-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.9">
                                             <path d="M6 2h9l5 5v15H6z" stroke-linejoin="round" />
                                             <path d="M14 2v6h6" stroke-linejoin="round" />
                                             <path d="M9 13h8M9 17h6" stroke-linecap="round" />
                                        </svg>
                                   </span>
                                   <span class="metric-chip-body">
                                        <span class="metric-chip-val">Transaksi</span>
                                        <span class="metric-chip-lbl">Jasa</span>
                                   </span>
                              </div>
                              <div class="metric-chip">
                                   <span class="metric-chip-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.9">
                                             <path d="M12 3 5 6v5c0 4.8 3 8.5 7 10 4-1.5 7-5.2 7-10V6l-7-3Z"
                                                  stroke-linejoin="round" />
                                             <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                   </span>
                                   <span class="metric-chip-body">
                                        <span class="metric-chip-val">Laporan</span>
                                        <span class="metric-chip-lbl">Eksekutif</span>
                                   </span>
                              </div>
                         </div>

                         <div class="feature-grid" aria-label="Fitur utama">
                              <article class="feature-card">
                                   <span class="feature-ic" aria-hidden="true">
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
                                   <span class="feature-ic" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.8">
                                             <path d="M6 2h9l5 5v15H6z" stroke-linejoin="round" />
                                             <path d="M14 2v6h6" stroke-linejoin="round" />
                                             <path d="M9 13h8M9 17h6" stroke-linecap="round" />
                                        </svg>
                                   </span>
                                   <strong>Transaksi Jasa</strong>
                                   <span>Kelola layanan, nilai transaksi, dan status proses.</span>
                              </article>

                              <article class="feature-card">
                                   <span class="feature-ic" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.8">
                                             <path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round" />
                                             <path d="m7 16 4-5 3 3 5-7" stroke-linecap="round"
                                                  stroke-linejoin="round" />
                                        </svg>
                                   </span>
                                   <strong>Laporan Eksekutif</strong>
                                   <span>Ringkasan performa untuk evaluasi dan keputusan.</span>
                              </article>
                         </div>
                    </div>

                    <footer class="visual-footer">
                         <span>&copy; {{ now()->year }} WorkTrack Monitor</span>
                         <span class="status-pill">
                              <span class="status-dot" aria-hidden="true"></span>
                              Sistem siap digunakan
                         </span>
                    </footer>

               </div>
          </section>

          {{-- RIGHT PANEL --}}
          <section class="auth-form-panel" aria-label="Form login">
               <div class="login-shell">

                    <a href="{{ route('login') }}" class="mobile-brand" aria-label="Halaman login">
                         <span class="mobile-brand-mark" aria-hidden="true">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                   <path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round" />
                                   <path d="m7 16 4-5 3 3 5-7" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                         </span>
                         <span class="mobile-brand-copy">
                              <strong>WorkTrack Monitor</strong>
                              <span>Produktivitas &amp; Transaksi Jasa</span>
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

                              <div class="login-badge">
                                   <span class="login-badge-dot" aria-hidden="true"></span>
                                   Dashboard Monitoring
                              </div>

                              <h2 class="login-title">Selamat datang kembali</h2>
                              <p class="login-subtitle">
                                   Masuk menggunakan akun dan hak akses yang telah diberikan
                                   untuk membuka dashboard monitoring.
                              </p>
                         </header>

                         {{-- Alert sukses --}}
                         @if (session()->has('success'))
                              <div class="alert alert-success" role="status">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="m8 12 2.5 2.5L16 9" stroke-linecap="round" stroke-linejoin="round" />
                                   </svg>
                                   <div class="alert-content">{{ session('success') }}</div>
                                   <button type="button" class="alert-close" aria-label="Tutup">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                             <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" />
                                        </svg>
                                   </button>
                              </div>
                         @endif

                         {{-- Alert error session --}}
                         @if (session()->has('error'))
                              <div class="alert alert-danger" role="alert">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 8v5M12 16.5h.01" stroke-linecap="round" />
                                   </svg>
                                   <div class="alert-content">{{ session('error') }}</div>
                                   <button type="button" class="alert-close" aria-label="Tutup">
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
                                   <button type="button" class="alert-close" aria-label="Tutup">
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
                                             @error('email') aria-describedby="emailError" @enderror required
                                             autofocus>
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
                                             <a href="{{ route('password.request') }}" class="forgot-link">Lupa
                                                  password?</a>
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
                              Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa
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
               const closeButtons = document.querySelectorAll('.alert-close');
               const firstInvalid = document.querySelector('.is-invalid');

               if (passwordInput && passwordToggle) {
                    passwordToggle.addEventListener('click', function() {
                         const hidden = passwordInput.type === 'password';
                         passwordInput.type = hidden ? 'text' : 'password';
                         passwordToggle.classList.toggle('is-visible', hidden);
                         passwordToggle.setAttribute('aria-pressed', String(hidden));
                         passwordToggle.setAttribute('aria-label', hidden ? 'Sembunyikan password' :
                              'Tampilkan password');
                         passwordInput.focus({
                              preventScroll: true
                         });
                         const len = passwordInput.value.length;
                         passwordInput.setSelectionRange(len, len);
                    });
               }

               closeButtons.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                         btn.closest('.alert')?.remove();
                    });
               });

               if (firstInvalid) {
                    window.setTimeout(function() {
                         firstInvalid.focus();
                    }, 100);
               }

               if (form && submitButton) {
                    form.addEventListener('submit', function(e) {
                         if (!form.checkValidity()) {
                              e.preventDefault();
                              form.reportValidity();
                              return;
                         }
                         submitButton.disabled = true;
                         submitButton.setAttribute('aria-busy', 'true');
                         if (buttonIcon) buttonIcon.innerHTML = '<span class="spinner"></span>';
                         if (buttonText) buttonText.textContent = 'Memverifikasi akun...';
                    });
               }
          });
     </script>
</body>

</html>
