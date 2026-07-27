@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
     <style>
          :root {
               --uc-primary: #6366f1;
               --uc-primary-dark: #4f46e5;
               --uc-primary-soft: #eef2ff;
               --uc-secondary: #a855f7;
               --uc-pink: #ec4899;
               --uc-orange: #f97316;
               --uc-cyan: #06b6d4;
               --uc-success: #16a34a;
               --uc-warning: #d97706;
               --uc-danger: #dc2626;
               --uc-dark: #172033;
               --uc-muted: #64748b;
               --uc-border: #dbe4f0;
               --uc-surface: #ffffff;
               --uc-background: #f8faff;
          }

          .user-create-page {
               position: relative;
               min-height: calc(100vh - 80px);
               padding: 30px 10px 48px;
               overflow: hidden;
               background:
                    radial-gradient(circle at 7% 5%, rgba(6, 182, 212, .18), transparent 24%),
                    radial-gradient(circle at 95% 8%, rgba(236, 72, 153, .16), transparent 26%),
                    radial-gradient(circle at 92% 96%, rgba(249, 115, 22, .14), transparent 25%),
                    radial-gradient(circle at 5% 95%, rgba(99, 102, 241, .15), transparent 26%),
                    linear-gradient(145deg, #f8fbff 0%, #fff8fc 46%, #fffdf6 100%);
          }

          .user-create-page::before {
               content: '';
               position: absolute;
               inset: 0;
               opacity: .32;
               pointer-events: none;
               background-image:
                    linear-gradient(rgba(99, 102, 241, .045) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99, 102, 241, .045) 1px, transparent 1px);
               background-size: 34px 34px;
               mask-image: linear-gradient(to bottom, #000, transparent 80%);
          }

          .user-create-shell {
               position: relative;
               z-index: 1;
               max-width: 1440px;
               margin: 0 auto;
          }

          .create-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 28px;
               padding: 34px 38px;
               margin-bottom: 24px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .48);
               border-radius: 26px;
               background: linear-gradient(115deg, #4f46e5 0%, #7c3aed 34%, #db2777 68%, #f97316 100%);
               box-shadow: 0 24px 55px rgba(99, 102, 241, .24), 0 10px 26px rgba(236, 72, 153, .12);
          }

          .create-hero::before,
          .create-hero::after {
               content: '';
               position: absolute;
               border-radius: 50%;
               pointer-events: none;
          }

          .create-hero::before {
               width: 310px;
               height: 310px;
               top: -190px;
               right: 15%;
               border: 48px solid rgba(255, 255, 255, .10);
          }

          .create-hero::after {
               width: 210px;
               height: 210px;
               right: -72px;
               bottom: -122px;
               background: rgba(255, 255, 255, .13);
               box-shadow: -300px -34px 0 18px rgba(255, 255, 255, .06);
          }

          .hero-content,
          .hero-action {
               position: relative;
               z-index: 1;
          }

          .hero-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               padding: 8px 13px;
               margin-bottom: 14px;
               font-size: 12px;
               font-weight: 850;
               letter-spacing: .09em;
               text-transform: uppercase;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 999px;
               background: rgba(255, 255, 255, .18);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .18);
               backdrop-filter: blur(10px);
          }

          .create-hero h1 {
               margin: 0 0 9px;
               font-size: clamp(28px, 3vw, 40px);
               font-weight: 900;
               letter-spacing: -.035em;
               text-shadow: 0 4px 18px rgba(76, 29, 149, .20);
          }

          .create-hero p {
               max-width: 720px;
               margin: 0;
               color: rgba(255, 255, 255, .90);
               font-size: 15px;
               line-height: 1.72;
          }

          .btn-back-modern {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 48px;
               padding: 11px 19px;
               color: #fff;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .52);
               border-radius: 14px;
               background: rgba(255, 255, 255, .18);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .20);
               backdrop-filter: blur(12px);
               transition: .22s ease;
          }

          .btn-back-modern:hover {
               color: #5b21b6;
               background: #fff;
               transform: translateY(-2px);
               box-shadow: 0 12px 22px rgba(76, 29, 149, .18);
          }

          .validation-alert {
               display: flex;
               gap: 14px;
               padding: 18px 20px;
               margin-bottom: 22px;
               color: #9f1239;
               border: 1px solid #fda4af;
               border-left: 5px solid #f43f5e;
               border-radius: 16px;
               background: linear-gradient(135deg, #fff1f2, #fff7ed);
               box-shadow: 0 12px 28px rgba(244, 63, 94, .10);
          }

          .validation-alert>i {
               flex: 0 0 auto;
               margin-top: 2px;
               color: #e11d48;
               font-size: 20px;
          }

          .validation-alert strong {
               display: block;
               margin-bottom: 5px;
               color: #881337;
          }

          .validation-alert ul {
               margin: 0;
               padding-left: 18px;
          }

          .create-layout {
               display: grid;
               grid-template-columns: minmax(290px, 360px) minmax(0, 1fr);
               gap: 24px;
               align-items: start;
          }

          .panel-card {
               position: relative;
               overflow: hidden;
               border: 1px solid rgba(215, 226, 240, .95);
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 46px rgba(79, 70, 229, .09), 0 6px 16px rgba(15, 23, 42, .04);
          }

          .profile-panel {
               position: sticky;
               top: 24px;
               border-top: 5px solid transparent;
               background:
                    linear-gradient(#fff, #fff) padding-box,
                    linear-gradient(90deg, #06b6d4, #6366f1, #ec4899) border-box;
          }

          .panel-heading {
               display: flex;
               align-items: flex-start;
               gap: 13px;
               padding: 22px 24px 19px;
               border-bottom: 1px solid #dce6f2;
               background: linear-gradient(135deg, #ecfeff 0%, #eef2ff 48%, #fdf2f8 100%);
          }

          .section-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 44px;
               width: 44px;
               height: 44px;
               color: #fff;
               font-size: 19px;
               border-radius: 14px;
               background: linear-gradient(135deg, var(--uc-primary), var(--uc-secondary));
               box-shadow: 0 8px 18px rgba(99, 102, 241, .22);
          }

          .panel-heading h2 {
               margin: 0 0 4px;
               color: var(--uc-dark);
               font-size: 17px;
               font-weight: 850;
          }

          .panel-heading p {
               margin: 0;
               color: var(--uc-muted);
               font-size: 13px;
               line-height: 1.5;
          }

          .profile-body {
               padding: 30px 24px 25px;
               text-align: center;
               background:
                    radial-gradient(circle at 50% 5%, rgba(99, 102, 241, .10), transparent 25%),
                    linear-gradient(180deg, #fff 0%, #fbfdff 100%);
          }

          .avatar-wrapper {
               position: relative;
               width: 166px;
               height: 166px;
               margin: 0 auto 21px;
          }

          .avatar-preview {
               position: relative;
               display: flex;
               align-items: center;
               justify-content: center;
               width: 100%;
               height: 100%;
               overflow: hidden;
               color: #6366f1;
               font-size: 56px;
               border: 7px solid #fff;
               border-radius: 50%;
               background: linear-gradient(145deg, #cffafe 0%, #e0e7ff 50%, #fce7f3 100%);
               box-shadow: 0 16px 36px rgba(99, 102, 241, .18), 0 0 0 3px rgba(168, 85, 247, .14);
          }

          .avatar-preview::after {
               content: '';
               position: absolute;
               inset: 0;
               border-radius: inherit;
               background: linear-gradient(135deg, rgba(255, 255, 255, .30), transparent 45%);
               pointer-events: none;
          }

          .avatar-preview img {
               display: none;
               width: 100%;
               height: 100%;
               object-fit: cover;
          }

          .avatar-preview.has-image img {
               display: block;
          }

          .avatar-preview.has-image .avatar-placeholder {
               display: none;
          }

          .avatar-camera {
               position: absolute;
               right: 7px;
               bottom: 7px;
               z-index: 2;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 44px;
               height: 44px;
               color: #fff;
               border: 4px solid #fff;
               border-radius: 50%;
               background: linear-gradient(135deg, #ec4899, #f97316);
               box-shadow: 0 9px 20px rgba(236, 72, 153, .28);
          }

          .upload-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               width: 100%;
               min-height: 47px;
               padding: 10px 16px;
               color: #4338ca;
               font-weight: 800;
               cursor: pointer;
               border: 1px solid #c7d2fe;
               border-radius: 14px;
               background: linear-gradient(135deg, #ecfeff, #eef2ff, #fdf2f8);
               box-shadow: 0 6px 16px rgba(99, 102, 241, .08);
               transition: .2s ease;
          }

          .upload-button:hover {
               color: #fff;
               border-color: transparent;
               background: linear-gradient(135deg, #06b6d4, #6366f1, #a855f7);
               transform: translateY(-2px);
               box-shadow: 0 12px 24px rgba(99, 102, 241, .22);
          }

          .upload-note {
               margin: 12px 0 0;
               color: var(--uc-muted);
               font-size: 12px;
               line-height: 1.6;
          }

          .profile-summary {
               margin-top: 22px;
               padding: 18px;
               text-align: left;
               border: 1px solid #e0e7ff;
               border-radius: 16px;
               background: linear-gradient(145deg, #f8fafc, #f5f3ff 48%, #fff1f2);
          }

          .profile-summary-title {
               margin-bottom: 12px;
               color: #4338ca;
               font-size: 13px;
               font-weight: 850;
          }

          .summary-item {
               display: flex;
               align-items: flex-start;
               gap: 10px;
               margin-bottom: 11px;
               color: #5b6474;
               font-size: 12px;
               line-height: 1.55;
          }

          .summary-item:last-child {
               margin-bottom: 0;
          }

          .summary-item i {
               margin-top: 1px;
               color: #22c55e;
               filter: drop-shadow(0 3px 6px rgba(34, 197, 94, .18));
          }

          .form-panel {
               padding: 0;
               border-top: 5px solid transparent;
               background:
                    linear-gradient(#fff, #fff) padding-box,
                    linear-gradient(90deg, #6366f1, #a855f7, #ec4899, #f97316) border-box;
          }

          .form-section {
               position: relative;
               padding: 26px;
               border-bottom: 1px solid var(--uc-border);
          }

          .form-section:nth-of-type(1) {
               background: linear-gradient(145deg, rgba(238, 242, 255, .78), rgba(255, 255, 255, .98) 42%);
          }

          .form-section:nth-of-type(2) {
               background: linear-gradient(145deg, rgba(250, 232, 255, .72), rgba(255, 255, 255, .98) 42%);
          }

          .form-section:nth-of-type(3) {
               background: linear-gradient(145deg, rgba(236, 253, 245, .72), rgba(255, 255, 255, .98) 42%);
          }

          .form-section:last-of-type {
               border-bottom: 0;
          }

          .form-section:nth-of-type(2) .section-icon {
               background: linear-gradient(135deg, #a855f7, #ec4899);
               box-shadow: 0 8px 18px rgba(236, 72, 153, .20);
          }

          .form-section:nth-of-type(3) .section-icon {
               background: linear-gradient(135deg, #10b981, #06b6d4);
               box-shadow: 0 8px 18px rgba(16, 185, 129, .20);
          }

          .section-heading {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 18px;
               margin-bottom: 23px;
          }

          .section-heading-main {
               display: flex;
               align-items: flex-start;
               gap: 13px;
          }

          .section-heading h2 {
               margin: 0 0 4px;
               color: var(--uc-dark);
               font-size: 18px;
               font-weight: 880;
          }

          .section-heading p {
               margin: 0;
               color: var(--uc-muted);
               font-size: 13px;
               line-height: 1.55;
          }

          .required-note {
               padding: 7px 10px;
               color: #7c3aed;
               font-size: 12px;
               font-weight: 750;
               white-space: nowrap;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .required-mark {
               color: var(--uc-danger);
          }

          .field-label {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
               margin-bottom: 8px;
               color: #334155;
               font-size: 13px;
               font-weight: 800;
          }

          .field-label small {
               color: #8b5cf6;
               font-size: 11px;
               font-weight: 700;
          }

          .input-group-modern {
               position: relative;
          }

          .input-leading-icon {
               position: absolute;
               top: 50%;
               left: 15px;
               z-index: 2;
               color: #818cf8;
               font-size: 17px;
               transform: translateY(-50%);
               pointer-events: none;
               transition: .18s ease;
          }

          .input-group-modern:focus-within .input-leading-icon {
               color: #7c3aed;
               transform: translateY(-50%) scale(1.06);
          }

          .form-control-modern {
               width: 100%;
               height: 51px;
               padding: 10px 44px;
               color: var(--uc-dark);
               font-size: 14px;
               border: 1px solid #cbd5e1;
               border-radius: 14px;
               outline: none;
               background: rgba(255, 255, 255, .96);
               box-shadow: inset 0 1px 2px rgba(15, 23, 42, .025);
               transition: border-color .18s ease, box-shadow .18s ease, background .18s ease, transform .18s ease;
          }

          .form-control-modern::placeholder {
               color: #a5afbf;
          }

          .form-control-modern:hover {
               border-color: #a5b4fc;
               background: #fff;
          }

          .form-control-modern:focus {
               border-color: #8b5cf6;
               background: #fff;
               box-shadow: 0 0 0 4px rgba(139, 92, 246, .11), 0 8px 20px rgba(99, 102, 241, .07);
               transform: translateY(-1px);
          }

          .form-control-modern.is-invalid {
               border-color: #fb7185;
               background: #fff7f8;
          }

          .form-control-modern.is-invalid:focus {
               box-shadow: 0 0 0 4px rgba(244, 63, 94, .11);
          }

          .password-toggle {
               position: absolute;
               top: 50%;
               right: 9px;
               z-index: 3;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 35px;
               height: 35px;
               padding: 0;
               color: #7c3aed;
               border: 0;
               border-radius: 10px;
               background: #f5f3ff;
               transform: translateY(-50%);
          }

          .password-toggle:hover {
               color: #fff;
               background: linear-gradient(135deg, #8b5cf6, #ec4899);
          }

          .field-error {
               display: flex;
               align-items: center;
               gap: 6px;
               margin-top: 7px;
               color: var(--uc-danger);
               font-size: 12px;
               font-weight: 700;
          }

          .field-help {
               margin-top: 8px;
               padding: 9px 11px;
               color: #64748b;
               font-size: 11px;
               line-height: 1.5;
               border-left: 3px solid #a78bfa;
               border-radius: 0 10px 10px 0;
               background: #faf5ff;
          }

          .role-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 13px;
          }

          .choice-input {
               position: absolute;
               width: 1px;
               height: 1px;
               overflow: hidden;
               opacity: 0;
               pointer-events: none;
          }

          .role-card {
               position: relative;
               display: flex;
               align-items: center;
               gap: 12px;
               min-height: 82px;
               padding: 14px;
               cursor: pointer;
               border: 1px solid var(--role-border, #dbe4f0);
               border-radius: 16px;
               background: linear-gradient(145deg, #fff, var(--role-bg, #f8fafc));
               box-shadow: 0 7px 18px rgba(15, 23, 42, .045);
               transition: .22s ease;
          }

          .role-card:hover {
               border-color: var(--role-color, #8b5cf6);
               transform: translateY(-3px);
               box-shadow: 0 14px 28px color-mix(in srgb, var(--role-color, #8b5cf6) 18%, transparent);
          }

          .role-grid>div:nth-child(6n+1) .role-card {
               --role-color: #4f46e5;
               --role-color-2: #7c3aed;
               --role-bg: #eef2ff;
               --role-icon-bg: #e0e7ff;
               --role-border: #c7d2fe;
          }

          .role-grid>div:nth-child(6n+2) .role-card {
               --role-color: #0891b2;
               --role-color-2: #06b6d4;
               --role-bg: #ecfeff;
               --role-icon-bg: #cffafe;
               --role-border: #a5f3fc;
          }

          .role-grid>div:nth-child(6n+3) .role-card {
               --role-color: #db2777;
               --role-color-2: #f43f5e;
               --role-bg: #fdf2f8;
               --role-icon-bg: #fce7f3;
               --role-border: #fbcfe8;
          }

          .role-grid>div:nth-child(6n+4) .role-card {
               --role-color: #ea580c;
               --role-color-2: #f59e0b;
               --role-bg: #fff7ed;
               --role-icon-bg: #ffedd5;
               --role-border: #fed7aa;
          }

          .role-grid>div:nth-child(6n+5) .role-card {
               --role-color: #16a34a;
               --role-color-2: #10b981;
               --role-bg: #f0fdf4;
               --role-icon-bg: #dcfce7;
               --role-border: #bbf7d0;
          }

          .role-grid>div:nth-child(6n+6) .role-card {
               --role-color: #9333ea;
               --role-color-2: #c026d3;
               --role-bg: #faf5ff;
               --role-icon-bg: #f3e8ff;
               --role-border: #e9d5ff;
          }

          .role-card-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 44px;
               width: 44px;
               height: 44px;
               color: var(--role-color, #6366f1);
               font-size: 18px;
               border-radius: 13px;
               background: var(--role-icon-bg, #eef2ff);
               box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .55);
               transition: .2s ease;
          }

          .role-card-title {
               display: block;
               color: var(--uc-dark);
               font-size: 12px;
               font-weight: 850;
               line-height: 1.35;
          }

          .role-check {
               position: absolute;
               top: 10px;
               right: 10px;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 21px;
               height: 21px;
               color: transparent;
               font-size: 11px;
               border: 1px solid var(--role-border, #cbd5e1);
               border-radius: 50%;
               background: #fff;
               transition: .2s ease;
          }

          .choice-input:focus-visible+.role-card,
          .choice-input:focus-visible+.status-card {
               outline: 3px solid rgba(139, 92, 246, .22);
               outline-offset: 3px;
          }

          .choice-input:checked+.role-card {
               border-color: var(--role-color, #6366f1);
               background: linear-gradient(145deg, #fff, var(--role-bg, #eef2ff));
               box-shadow: 0 0 0 2px color-mix(in srgb, var(--role-color, #6366f1) 72%, white),
                    0 15px 30px color-mix(in srgb, var(--role-color, #6366f1) 18%, transparent);
               transform: translateY(-2px);
          }

          .choice-input:checked+.role-card .role-card-icon {
               color: #fff;
               background: linear-gradient(135deg, var(--role-color, #6366f1), var(--role-color-2, #a855f7));
               box-shadow: 0 8px 18px color-mix(in srgb, var(--role-color, #6366f1) 28%, transparent);
          }

          .choice-input:checked+.role-card .role-check {
               color: #fff;
               border-color: var(--role-color, #6366f1);
               background: var(--role-color, #6366f1);
          }

          .status-grid {
               display: grid;
               grid-template-columns: repeat(3, minmax(0, 1fr));
               gap: 13px;
          }

          .status-card {
               position: relative;
               display: flex;
               align-items: center;
               gap: 12px;
               min-height: 72px;
               padding: 14px 15px;
               overflow: hidden;
               cursor: pointer;
               border: 1px solid color-mix(in srgb, var(--status-color, #6366f1) 28%, #e2e8f0);
               border-radius: 15px;
               background: linear-gradient(145deg, #fff, var(--status-bg, #eef2ff));
               box-shadow: 0 7px 18px rgba(15, 23, 42, .045);
               transition: .22s ease;
          }

          .status-card::after {
               content: '';
               position: absolute;
               top: -26px;
               right: -26px;
               width: 68px;
               height: 68px;
               border-radius: 50%;
               background: color-mix(in srgb, var(--status-color, #6366f1) 12%, transparent);
          }

          .status-card:hover {
               border-color: var(--status-color, #6366f1);
               transform: translateY(-2px);
               box-shadow: 0 13px 26px color-mix(in srgb, var(--status-color, #6366f1) 14%, transparent);
          }

          .status-dot {
               position: relative;
               z-index: 1;
               flex: 0 0 auto;
               width: 12px;
               height: 12px;
               border: 3px solid color-mix(in srgb, var(--status-color, #6366f1) 52%, white);
               border-radius: 50%;
               background: #fff;
               box-sizing: content-box;
          }

          .status-copy {
               position: relative;
               z-index: 1;
          }

          .status-copy strong {
               display: block;
               color: var(--uc-dark);
               font-size: 13px;
               font-weight: 850;
          }

          .status-copy small {
               display: block;
               margin-top: 3px;
               color: var(--uc-muted);
               font-size: 11px;
               line-height: 1.4;
          }

          .choice-input:checked+.status-card {
               border-color: var(--status-color, var(--uc-primary));
               background: linear-gradient(145deg, #fff, var(--status-bg, var(--uc-primary-soft)));
               box-shadow: 0 0 0 2px color-mix(in srgb, var(--status-color, #6366f1) 62%, white),
                    0 13px 26px color-mix(in srgb, var(--status-color, #6366f1) 16%, transparent);
               transform: translateY(-2px);
          }

          .choice-input:checked+.status-card .status-dot {
               border-color: #fff;
               background: var(--status-color, var(--uc-primary));
               box-shadow: 0 0 0 3px var(--status-color, var(--uc-primary));
          }

          .empty-role-state {
               grid-column: 1 / -1;
               display: flex;
               align-items: center;
               gap: 10px;
               padding: 15px 17px;
               color: #92400e;
               border: 1px solid #fcd34d;
               border-radius: 14px;
               background: linear-gradient(135deg, #fffbeb, #fff7ed);
               font-size: 13px;
               box-shadow: 0 8px 18px rgba(245, 158, 11, .08);
          }

          .form-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               padding: 21px 26px;
               border-top: 1px solid #e2e8f0;
               background: linear-gradient(90deg, #eef2ff 0%, #faf5ff 44%, #fff1f2 75%, #fff7ed 100%);
          }

          .footer-info {
               display: flex;
               align-items: center;
               gap: 9px;
               color: #5b6474;
               font-size: 12px;
               font-weight: 650;
          }

          .footer-info i {
               color: #7c3aed;
          }

          .footer-actions {
               display: flex;
               align-items: center;
               justify-content: flex-end;
               gap: 10px;
          }

          .btn-cancel-modern,
          .btn-save-modern {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 9px;
               min-height: 47px;
               padding: 11px 21px;
               font-size: 14px;
               font-weight: 850;
               text-decoration: none;
               border-radius: 14px;
               transition: .22s ease;
          }

          .btn-cancel-modern {
               color: #4f46e5;
               border: 1px solid #c7d2fe;
               background: rgba(255, 255, 255, .92);
               box-shadow: 0 6px 15px rgba(99, 102, 241, .07);
          }

          .btn-cancel-modern:hover {
               color: #fff;
               border-color: transparent;
               background: linear-gradient(135deg, #64748b, #475569);
               transform: translateY(-2px);
          }

          .btn-save-modern {
               color: #fff;
               border: 0;
               background: linear-gradient(115deg, #4f46e5 0%, #7c3aed 38%, #db2777 72%, #f97316 100%);
               background-size: 180% 100%;
               box-shadow: 0 11px 24px rgba(124, 58, 237, .25), 0 5px 12px rgba(236, 72, 153, .12);
          }

          .btn-save-modern:hover {
               color: #fff;
               background-position: 100% 0;
               transform: translateY(-2px);
               box-shadow: 0 16px 30px rgba(124, 58, 237, .29), 0 8px 16px rgba(236, 72, 153, .16);
          }

          .btn-save-modern:active {
               transform: translateY(0);
          }

          .btn-save-modern:disabled {
               cursor: not-allowed;
               opacity: .72;
               transform: none;
          }

          @media (max-width: 1199.98px) {
               .create-layout {
                    grid-template-columns: 300px minmax(0, 1fr);
               }

               .role-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 991.98px) {
               .create-layout {
                    grid-template-columns: 1fr;
               }

               .profile-panel {
                    position: static;
               }

               .profile-body {
                    display: grid;
                    grid-template-columns: auto 1fr;
                    gap: 24px;
                    align-items: center;
                    text-align: left;
               }

               .avatar-wrapper {
                    margin-bottom: 0;
               }

               .profile-summary {
                    margin-top: 16px;
               }
          }

          @media (max-width: 767.98px) {
               .user-create-page {
                    padding: 18px 0 32px;
               }

               .create-hero {
                    align-items: flex-start;
                    flex-direction: column;
                    padding: 26px 24px;
                    border-radius: 21px;
               }

               .hero-action,
               .btn-back-modern {
                    width: 100%;
               }

               .profile-body {
                    display: block;
                    text-align: center;
               }

               .avatar-wrapper {
                    margin-bottom: 20px;
               }

               .profile-summary {
                    text-align: left;
               }

               .form-section,
               .form-footer {
                    padding: 21px;
               }

               .section-heading {
                    flex-direction: column;
               }

               .role-grid,
               .status-grid {
                    grid-template-columns: 1fr;
               }

               .form-footer {
                    align-items: stretch;
                    flex-direction: column;
               }

               .footer-actions {
                    width: 100%;
               }

               .btn-cancel-modern,
               .btn-save-modern {
                    flex: 1 1 0;
               }
          }

          @media (max-width: 479.98px) {
               .footer-actions {
                    flex-direction: column-reverse;
               }

               .btn-cancel-modern,
               .btn-save-modern {
                    width: 100%;
               }
          }

          @supports not (color: color-mix(in srgb, red 50%, blue)) {

               .role-card:hover,
               .choice-input:checked+.role-card,
               .status-card:hover,
               .choice-input:checked+.status-card {
                    box-shadow: 0 14px 28px rgba(99, 102, 241, .16);
               }
          }
     </style>


     <div class="user-create-page">
          <div class="container-fluid">
               <header class="create-hero">
                    <div class="hero-content">
                         <div class="hero-eyebrow">
                              <i class="bi bi-shield-check"></i>
                              Manajemen Pengguna
                         </div>
                         <h1>Tambah Pengguna Baru</h1>
                         <p>
                              Lengkapi identitas pengguna, tentukan hak akses, dan atur status akun untuk memberikan akses ke
                              sistem perusahaan.
                         </p>
                    </div>

                    <div class="hero-action">
                         <a href="{{ route('super-admin.users.index') }}" class="btn-back-modern">
                              <i class="bi bi-arrow-left"></i>
                              Kembali ke Daftar
                         </a>
                    </div>
               </header>

               @if ($errors->any())
                    <div class="validation-alert" role="alert">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                         <div>
                              <strong>Data belum dapat disimpan.</strong>
                              <ul>
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                    </div>
               @endif

               <form id="createUserForm" action="{{ route('super-admin.users.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="create-layout">
                         <aside class="panel-card profile-panel">
                              <div class="panel-heading">
                                   <span class="section-icon">
                                        <i class="bi bi-person-bounding-box"></i>
                                   </span>
                                   <div>
                                        <h2>Foto Profil</h2>
                                        <p>Gunakan foto yang jelas agar pengguna mudah dikenali.</p>
                                   </div>
                              </div>

                              <div class="profile-body">
                                   <div>
                                        <div class="avatar-wrapper">
                                             <div class="avatar-preview" id="avatarPreviewBox">
                                                  <span class="avatar-placeholder" id="avatarPlaceholder">
                                                       <i class="bi bi-person-fill"></i>
                                                  </span>
                                                  <img id="preview" src="" alt="Pratinjau foto pengguna">
                                             </div>
                                             <span class="avatar-camera">
                                                  <i class="bi bi-camera-fill"></i>
                                             </span>
                                        </div>

                                        <label for="photoInput" class="upload-button">
                                             <i class="bi bi-cloud-arrow-up"></i>
                                             Pilih Foto
                                        </label>
                                        <input id="photoInput" type="file" name="photo"
                                             accept="image/png,image/jpeg,image/jpg,image/webp" hidden>

                                        @error('photo')
                                             <div class="field-error justify-content-center">
                                                  <i class="bi bi-exclamation-circle"></i>
                                                  {{ $message }}
                                             </div>
                                        @enderror

                                        <p class="upload-note">
                                             Format JPG, PNG, atau WEBP. Ukuran maksimal 2 MB.
                                        </p>
                                   </div>

                                   <div class="profile-summary">
                                        <div class="profile-summary-title">Panduan akun</div>
                                        <div class="summary-item">
                                             <i class="bi bi-check-circle-fill"></i>
                                             <span>Pastikan email pengguna aktif dan dapat menerima informasi akun.</span>
                                        </div>
                                        <div class="summary-item">
                                             <i class="bi bi-check-circle-fill"></i>
                                             <span>Pilih role sesuai tanggung jawab dan kewenangan pengguna.</span>
                                        </div>
                                        <div class="summary-item">
                                             <i class="bi bi-check-circle-fill"></i>
                                             <span>Password minimal 8 karakter dan sebaiknya menggunakan kombinasi
                                                  karakter.</span>
                                        </div>
                                   </div>
                              </div>
                         </aside>

                         <main class="panel-card form-panel">
                              <section class="form-section">
                                   <div class="section-heading">
                                        <div class="section-heading-main">
                                             <span class="section-icon">
                                                  <i class="bi bi-person-vcard"></i>
                                             </span>
                                             <div>
                                                  <h2>Informasi Pengguna</h2>
                                                  <p>Masukkan data identitas dan informasi kontak pengguna.</p>
                                             </div>
                                        </div>
                                        <div class="required-note">
                                             <span class="required-mark">*</span> Wajib diisi
                                        </div>
                                   </div>

                                   <div class="row g-3">
                                        <div class="col-md-6">
                                             <label for="name" class="field-label">
                                                  <span>Nama Lengkap <span class="required-mark">*</span></span>
                                             </label>
                                             <div class="input-group-modern">
                                                  <i class="bi bi-person input-leading-icon"></i>
                                                  <input id="name" type="text" name="name"
                                                       class="form-control-modern @error('name') is-invalid @enderror"
                                                       value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                                                       autocomplete="name" required>
                                             </div>
                                             @error('name')
                                                  <div class="field-error">
                                                       <i class="bi bi-exclamation-circle"></i>
                                                       {{ $message }}
                                                  </div>
                                             @enderror
                                        </div>

                                        <div class="col-md-6">
                                             <label for="username" class="field-label">
                                                  <span>Username <span class="required-mark">*</span></span>
                                                  <small>Tanpa spasi</small>
                                             </label>
                                             <div class="input-group-modern">
                                                  <i class="bi bi-at input-leading-icon"></i>
                                                  <input id="username" type="text" name="username"
                                                       class="form-control-modern @error('username') is-invalid @enderror"
                                                       value="{{ old('username') }}" placeholder="Contoh: andi.saputra"
                                                       autocomplete="username" required>
                                             </div>
                                             @error('username')
                                                  <div class="field-error">
                                                       <i class="bi bi-exclamation-circle"></i>
                                                       {{ $message }}
                                                  </div>
                                             @enderror
                                        </div>

                                        <div class="col-md-6">
                                             <label for="email" class="field-label">
                                                  <span>Email <span class="required-mark">*</span></span>
                                             </label>
                                             <div class="input-group-modern">
                                                  <i class="bi bi-envelope input-leading-icon"></i>
                                                  <input id="email" type="email" name="email"
                                                       class="form-control-modern @error('email') is-invalid @enderror"
                                                       value="{{ old('email') }}" placeholder="nama@perusahaan.com"
                                                       autocomplete="email" required>
                                             </div>
                                             @error('email')
                                                  <div class="field-error">
                                                       <i class="bi bi-exclamation-circle"></i>
                                                       {{ $message }}
                                                  </div>
                                             @enderror
                                        </div>

                                        <div class="col-md-6">
                                             <label for="phone" class="field-label">
                                                  <span>Nomor Telepon</span>
                                                  <small>Opsional</small>
                                             </label>
                                             <div class="input-group-modern">
                                                  <i class="bi bi-telephone input-leading-icon"></i>
                                                  <input id="phone" type="tel" name="phone"
                                                       class="form-control-modern @error('phone') is-invalid @enderror"
                                                       value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                                                       autocomplete="tel" inputmode="tel">
                                             </div>
                                             @error('phone')
                                                  <div class="field-error">
                                                       <i class="bi bi-exclamation-circle"></i>
                                                       {{ $message }}
                                                  </div>
                                             @enderror
                                        </div>

                                        <div class="col-12">
                                             <label for="password" class="field-label">
                                                  <span>Password <span class="required-mark">*</span></span>
                                                  <small>Minimal 8 karakter</small>
                                             </label>
                                             <div class="input-group-modern">
                                                  <i class="bi bi-lock input-leading-icon"></i>
                                                  <input id="password" type="password" name="password"
                                                       class="form-control-modern @error('password') is-invalid @enderror"
                                                       placeholder="Masukkan password pengguna" autocomplete="new-password"
                                                       minlength="8" required>
                                                  <button type="button" class="password-toggle" id="passwordToggle"
                                                       aria-label="Tampilkan password" aria-pressed="false">
                                                       <i class="bi bi-eye" id="passwordToggleIcon"></i>
                                                  </button>
                                             </div>
                                             @error('password')
                                                  <div class="field-error">
                                                       <i class="bi bi-exclamation-circle"></i>
                                                       {{ $message }}
                                                  </div>
                                             @enderror
                                             <div class="field-help">
                                                  Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk
                                                  keamanan yang lebih baik.
                                             </div>
                                        </div>
                                   </div>
                              </section>

                              <section class="form-section">
                                   <div class="section-heading">
                                        <div class="section-heading-main">
                                             <span class="section-icon">
                                                  <i class="bi bi-shield-lock"></i>
                                             </span>
                                             <div>
                                                  <h2>Hak Akses Sistem</h2>
                                                  <p>Tentukan role yang sesuai dengan tugas pengguna.</p>
                                             </div>
                                        </div>
                                   </div>

                                   <label class="field-label mb-3">
                                        <span>Role Pengguna <span class="required-mark">*</span></span>
                                   </label>

                                   <div class="role-grid">
                                        @forelse ($roles as $role)
                                             @php
                                                  $roleName = strtolower($role->name);
                                                  $roleLabel = ucwords(str_replace(['_', '-'], ' ', $role->name));
                                                  $roleIcon = match (true) {
                                                      str_contains($roleName, 'super') => 'bi-stars',
                                                      str_contains($roleName, 'admin') => 'bi-shield-check',
                                                      str_contains($roleName, 'direktur'),
                                                      str_contains($roleName, 'executive')
                                                          => 'bi-briefcase',
                                                      str_contains($roleName, 'manager') => 'bi-diagram-3',
                                                      str_contains($roleName, 'hr'),
                                                      str_contains($roleName, 'human')
                                                          => 'bi-people',
                                                      str_contains($roleName, 'finance'),
                                                      str_contains($roleName, 'keuangan')
                                                          => 'bi-cash-coin',
                                                      str_contains($roleName, 'operasional') => 'bi-gear',
                                                      default => 'bi-person-gear',
                                                  };
                                             @endphp

                                             <div>
                                                  <input class="choice-input" type="radio" name="role_id"
                                                       id="role_{{ $role->id }}" value="{{ $role->id }}"
                                                       {{ (string) old('role_id') === (string) $role->id ? 'checked' : '' }}
                                                       required>
                                                  <label class="role-card" for="role_{{ $role->id }}">
                                                       <span class="role-card-icon">
                                                            <i class="bi {{ $roleIcon }}"></i>
                                                       </span>
                                                       <span class="role-card-title">{{ $roleLabel }}</span>
                                                       <span class="role-check">
                                                            <i class="bi bi-check-lg"></i>
                                                       </span>
                                                  </label>
                                             </div>
                                        @empty
                                             <div class="empty-role-state">
                                                  <i class="bi bi-exclamation-triangle-fill"></i>
                                                  Role belum tersedia. Tambahkan data role terlebih dahulu.
                                             </div>
                                        @endforelse
                                   </div>

                                   @error('role_id')
                                        <div class="field-error mt-2">
                                             <i class="bi bi-exclamation-circle"></i>
                                             {{ $message }}
                                        </div>
                                   @enderror
                              </section>

                              <section class="form-section">
                                   <div class="section-heading">
                                        <div class="section-heading-main">
                                             <span class="section-icon">
                                                  <i class="bi bi-toggle-on"></i>
                                             </span>
                                             <div>
                                                  <h2>Status Akun</h2>
                                                  <p>Atur apakah akun langsung dapat digunakan setelah dibuat.</p>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="status-grid">
                                        @foreach ($statuses as $status)
                                             @php
                                                  $statusKey = strtolower($status);
                                                  $statusLabel = ucwords(str_replace(['_', '-'], ' ', $status));
                                                  $statusColor = match ($statusKey) {
                                                      'active', 'aktif' => '#16a34a',
                                                      'inactive', 'nonaktif' => '#0284c7',
                                                      'suspended', 'ditangguhkan' => '#d97706',
                                                      default => '#2563eb',
                                                  };
                                                  $statusBackground = match ($statusKey) {
                                                      'active', 'aktif' => '#f0fdf4',
                                                      'inactive', 'nonaktif' => '#f0f9ff',
                                                      'suspended', 'ditangguhkan' => '#fffbeb',
                                                      default => '#eff6ff',
                                                  };
                                                  $statusDescription = match ($statusKey) {
                                                      'active', 'aktif' => 'Dapat masuk dan menggunakan sistem.',
                                                      'inactive', 'nonaktif' => 'Akun belum dapat digunakan.',
                                                      'suspended', 'ditangguhkan' => 'Akses akun dihentikan sementara.',
                                                      default => 'Status akses pengguna.',
                                                  };
                                             @endphp

                                             <div>
                                                  <input class="choice-input" type="radio" name="status"
                                                       id="status_{{ $loop->index }}" value="{{ $status }}"
                                                       {{ old('status', $loop->first ? $status : null) === $status ? 'checked' : '' }}
                                                       required>
                                                  <label class="status-card" for="status_{{ $loop->index }}"
                                                       style="--status-color: {{ $statusColor }}; --status-bg: {{ $statusBackground }};">
                                                       <span class="status-dot"></span>
                                                       <span class="status-copy">
                                                            <strong>{{ $statusLabel }}</strong>
                                                            <small>{{ $statusDescription }}</small>
                                                       </span>
                                                  </label>
                                             </div>
                                        @endforeach
                                   </div>

                                   @error('status')
                                        <div class="field-error mt-2">
                                             <i class="bi bi-exclamation-circle"></i>
                                             {{ $message }}
                                        </div>
                                   @enderror
                              </section>

                              <footer class="form-footer">
                                   <div class="footer-info">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Periksa kembali data sebelum menyimpan pengguna.
                                   </div>

                                   <div class="footer-actions">
                                        <a href="{{ route('super-admin.users.index') }}" class="btn-cancel-modern">
                                             <i class="bi bi-x-lg"></i>
                                             Batal
                                        </a>
                                        <button type="submit" class="btn-save-modern" id="submitButton">
                                             <i class="bi bi-check2-circle"></i>
                                             <span>Simpan Pengguna</span>
                                        </button>
                                   </div>
                              </footer>
                         </main>
                    </div>
               </form>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const photoInput = document.getElementById('photoInput');
               const preview = document.getElementById('preview');
               const previewBox = document.getElementById('avatarPreviewBox');
               const passwordInput = document.getElementById('password');
               const passwordToggle = document.getElementById('passwordToggle');
               const passwordToggleIcon = document.getElementById('passwordToggleIcon');
               const form = document.getElementById('createUserForm');
               const submitButton = document.getElementById('submitButton');

               let previewUrl = null;

               photoInput?.addEventListener('change', function(event) {
                    const file = event.target.files?.[0];

                    if (!file) {
                         preview.removeAttribute('src');
                         previewBox.classList.remove('has-image');
                         return;
                    }

                    if (!file.type.startsWith('image/')) {
                         event.target.value = '';
                         alert('File yang dipilih harus berupa gambar.');
                         return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                         event.target.value = '';
                         alert('Ukuran foto maksimal 2 MB.');
                         return;
                    }

                    if (previewUrl) {
                         URL.revokeObjectURL(previewUrl);
                    }

                    previewUrl = URL.createObjectURL(file);
                    preview.src = previewUrl;
                    previewBox.classList.add('has-image');
               });

               passwordToggle?.addEventListener('click', function() {
                    const showPassword = passwordInput.type === 'password';

                    passwordInput.type = showPassword ? 'text' : 'password';
                    passwordToggle.setAttribute('aria-pressed', String(showPassword));
                    passwordToggle.setAttribute(
                         'aria-label',
                         showPassword ? 'Sembunyikan password' : 'Tampilkan password'
                    );
                    passwordToggleIcon.className = showPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
               });

               form?.addEventListener('submit', function() {
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span>Menyimpan...</span>
                `;
               });
          });
     </script>
@endsection
