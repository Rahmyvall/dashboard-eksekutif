@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<style>
     :root {
          --uc-primary: #2563eb;
          --uc-primary-dark: #1d4ed8;
          --uc-primary-soft: #eff6ff;
          --uc-secondary: #0891b2;
          --uc-accent: #7c3aed;
          --uc-teal: #0d9488;
          --uc-success: #16a34a;
          --uc-warning: #d97706;
          --uc-danger: #dc2626;
          --uc-dark: #0f172a;
          --uc-slate: #1e293b;
          --uc-muted: #64748b;
          --uc-muted-light: #94a3b8;
          --uc-border: #e2e8f0;
          --uc-surface: #ffffff;
          --uc-bg: #f4f7fb;
          --uc-shadow: 0 4px 24px rgba(15,23,42,.07);
          --uc-shadow-md: 0 12px 40px rgba(15,23,42,.10);
          --uc-shadow-lg: 0 24px 60px rgba(15,23,42,.13);
     }

     .uc-page {
          min-height: calc(100vh - 80px);
          padding: 28px 0 64px;
          background: var(--uc-bg);
     }

     /* Hero */
     .uc-hero {
          position: relative;
          overflow: hidden;
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 24px;
          padding: 36px 44px;
          margin-bottom: 28px;
          border-radius: 24px;
          background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0c4a6e 70%, #0369a1 100%);
          box-shadow: 0 20px 60px rgba(15,23,42,.32), inset 0 1px 0 rgba(255,255,255,.08);
     }

     .uc-hero::before {
          content: '';
          position: absolute;
          top: -80px;
          right: -60px;
          width: 260px;
          height: 260px;
          border-radius: 50%;
          border: 44px solid rgba(255,255,255,.06);
          pointer-events: none;
     }

     .uc-hero::after {
          content: '';
          position: absolute;
          left: 38%;
          bottom: -72px;
          width: 180px;
          height: 180px;
          border-radius: 50%;
          background: rgba(56,189,248,.12);
          pointer-events: none;
     }

     .uc-hero-glow {
          position: absolute;
          top: -40px;
          right: 25%;
          width: 300px;
          height: 200px;
          border-radius: 50%;
          background: radial-gradient(circle, rgba(56,189,248,.18), transparent 65%);
          pointer-events: none;
     }

     .uc-hero-content, .uc-hero-action { position: relative; z-index: 2; }

     .uc-eyebrow {
          display: inline-flex;
          align-items: center;
          gap: 7px;
          padding: 6px 14px;
          margin-bottom: 14px;
          font-size: 11px;
          font-weight: 700;
          letter-spacing: .08em;
          text-transform: uppercase;
          color: #7dd3fc;
          border: 1px solid rgba(125,211,252,.28);
          border-radius: 999px;
          background: rgba(125,211,252,.10);
          backdrop-filter: blur(8px);
     }

     .uc-hero h1 {
          margin: 0 0 10px;
          font-size: clamp(26px, 3vw, 38px);
          font-weight: 800;
          letter-spacing: -.03em;
          color: #fff;
          line-height: 1.15;
     }

     .uc-hero p {
          max-width: 640px;
          margin: 0;
          color: rgba(255,255,255,.70);
          font-size: 14.5px;
          line-height: 1.72;
     }

     .uc-breadcrumb {
          display: flex;
          align-items: center;
          gap: 6px;
          margin-top: 16px;
          font-size: 12px;
          color: rgba(255,255,255,.50);
     }

     .uc-breadcrumb a { color: rgba(255,255,255,.78); text-decoration: none; font-weight: 600; transition: color .2s; }
     .uc-breadcrumb a:hover { color: #7dd3fc; }
     .uc-breadcrumb i { font-size: 10px; }

     .uc-btn-back {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          min-height: 46px;
          padding: 10px 20px;
          color: #fff;
          font-size: 13.5px;
          font-weight: 700;
          text-decoration: none;
          white-space: nowrap;
          border: 1px solid rgba(255,255,255,.24);
          border-radius: 12px;
          background: rgba(255,255,255,.10);
          backdrop-filter: blur(10px);
          transition: .2s ease;
     }

     .uc-btn-back:hover {
          color: var(--uc-slate);
          background: #fff;
          border-color: transparent;
          transform: translateY(-2px);
          box-shadow: 0 10px 24px rgba(15,23,42,.20);
     }

     /* Alert */
     .uc-alert {
          display: flex;
          gap: 14px;
          padding: 16px 20px;
          margin-bottom: 22px;
          border: 1px solid #fca5a5;
          border-left: 4px solid #ef4444;
          border-radius: 14px;
          background: #fff7f7;
          box-shadow: 0 6px 20px rgba(239,68,68,.08);
     }

     .uc-alert > i { color: #ef4444; font-size: 18px; margin-top: 2px; flex: 0 0 auto; }
     .uc-alert strong { display: block; color: #991b1b; margin-bottom: 5px; font-size: 13.5px; }
     .uc-alert ul { margin: 0; padding-left: 18px; color: #b91c1c; font-size: 13px; }

     /* Layout */
     .uc-layout {
          display: grid;
          grid-template-columns: 300px minmax(0, 1fr);
          gap: 22px;
          align-items: start;
     }

     /* Card */
     .uc-card {
          border: 1px solid var(--uc-border);
          border-radius: 20px;
          background: var(--uc-surface);
          box-shadow: var(--uc-shadow);
          overflow: hidden;
          transition: box-shadow .3s ease;
     }

     .uc-card:hover { box-shadow: var(--uc-shadow-md); }

     /* Profile panel */
     .uc-profile-panel {
          position: sticky;
          top: 24px;
          border-top: 3px solid transparent;
          background:
               linear-gradient(var(--uc-surface), var(--uc-surface)) padding-box,
               linear-gradient(180deg, #2563eb, #0891b2) border-box;
     }

     .uc-panel-head {
          display: flex;
          align-items: flex-start;
          gap: 12px;
          padding: 20px 22px 18px;
          border-bottom: 1px solid #f1f5f9;
          background: linear-gradient(135deg, #f8faff, #f0f9ff);
     }

     .uc-panel-icon {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          flex: 0 0 42px;
          width: 42px;
          height: 42px;
          color: #fff;
          font-size: 17px;
          border-radius: 12px;
          background: linear-gradient(135deg, #2563eb, #0891b2);
          box-shadow: 0 6px 16px rgba(37,99,235,.22);
     }

     .uc-panel-head h2 { margin: 0 0 3px; color: var(--uc-dark); font-size: 15px; font-weight: 800; }
     .uc-panel-head p { margin: 0; color: var(--uc-muted); font-size: 12.5px; line-height: 1.5; }

     /* Avatar */
     .uc-profile-body {
          padding: 28px 22px 24px;
          text-align: center;
          background: linear-gradient(180deg, #f8faff 0%, #fff 60%);
     }

     .uc-avatar-wrap {
          position: relative;
          width: 148px;
          height: 148px;
          margin: 0 auto 20px;
     }

     .uc-avatar-track {
          position: absolute;
          inset: -5px;
          border-radius: 50%;
          padding: 3px;
          background: conic-gradient(#2563eb, #0891b2, #7c3aed, #2563eb);
          animation: avatarSpin 5s linear infinite;
     }

     .uc-avatar-track::before {
          content: '';
          position: absolute;
          inset: 3px;
          border-radius: 50%;
          background: #f8faff;
     }

     @keyframes avatarSpin { to { transform: rotate(360deg); } }

     .uc-avatar-preview {
          position: relative;
          z-index: 1;
          display: flex;
          align-items: center;
          justify-content: center;
          width: 100%;
          height: 100%;
          overflow: hidden;
          color: #2563eb;
          font-size: 46px;
          border: 4px solid #fff;
          border-radius: 50%;
          background: linear-gradient(145deg, #dbeafe, #e0f2fe, #ede9fe);
          box-shadow: 0 12px 32px rgba(37,99,235,.16);
     }

     .uc-avatar-preview img { display: none; width: 100%; height: 100%; object-fit: cover; }
     .uc-avatar-preview.has-image img { display: block; }
     .uc-avatar-preview.has-image .uc-avatar-icon { display: none; }

     .uc-avatar-cam {
          position: absolute;
          right: 2px;
          bottom: 2px;
          z-index: 2;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          width: 38px;
          height: 38px;
          color: #fff;
          font-size: 14px;
          border: 3px solid #fff;
          border-radius: 50%;
          background: linear-gradient(135deg, #2563eb, #0891b2);
          box-shadow: 0 6px 16px rgba(37,99,235,.28);
          cursor: pointer;
          transition: .2s ease;
     }

     .uc-avatar-cam:hover { transform: scale(1.12); box-shadow: 0 10px 22px rgba(37,99,235,.36); }

     .uc-upload-btn {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 7px;
          width: 100%;
          min-height: 44px;
          padding: 9px 16px;
          color: #2563eb;
          font-size: 13px;
          font-weight: 700;
          cursor: pointer;
          border: 1.5px dashed #93c5fd;
          border-radius: 12px;
          background: #f0f7ff;
          transition: .2s ease;
     }

     .uc-upload-btn:hover {
          color: #fff;
          border-style: solid;
          border-color: transparent;
          background: linear-gradient(135deg, #2563eb, #0891b2);
          transform: translateY(-1px);
          box-shadow: 0 8px 20px rgba(37,99,235,.20);
     }

     .uc-upload-note { margin: 10px 0 0; color: var(--uc-muted-light); font-size: 11.5px; line-height: 1.6; }

     /* Live preview */
     .uc-preview-card {
          margin-top: 20px;
          padding: 16px;
          border: 1px solid #e2e8f0;
          border-radius: 14px;
          background: #f8faff;
          text-align: left;
     }

     .uc-preview-title {
          display: flex;
          align-items: center;
          gap: 6px;
          margin-bottom: 12px;
          font-size: 10.5px;
          font-weight: 800;
          letter-spacing: .07em;
          text-transform: uppercase;
          color: #2563eb;
     }

     .uc-preview-row {
          display: flex;
          align-items: flex-start;
          gap: 9px;
          margin-bottom: 9px;
          font-size: 12px;
          color: #475569;
          line-height: 1.5;
     }

     .uc-preview-row:last-child { margin-bottom: 0; }

     .uc-preview-icon {
          flex: 0 0 24px;
          width: 24px;
          height: 24px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: 7px;
          font-size: 12px;
          background: rgba(37,99,235,.08);
          color: #2563eb;
     }

     .uc-preview-val { flex: 1; font-weight: 700; color: var(--uc-dark); word-break: break-all; }
     .uc-preview-empty { color: #94a3b8; font-style: italic; font-weight: 400; }

     .uc-preview-badge {
          display: inline-flex;
          align-items: center;
          gap: 4px;
          padding: 3px 9px;
          border-radius: 999px;
          font-size: 11px;
          font-weight: 700;
          letter-spacing: .03em;
          background: #eff6ff;
          color: #2563eb;
          border: 1px solid #bfdbfe;
     }

     /* Form panel */
     .uc-form-panel {
          border-top: 3px solid transparent;
          background:
               linear-gradient(var(--uc-surface), var(--uc-surface)) padding-box,
               linear-gradient(90deg, #2563eb, #0891b2, #7c3aed) border-box;
     }

     /* Sections */
     .uc-section {
          position: relative;
          padding: 28px;
          border-bottom: 1px solid #f1f5f9;
     }

     .uc-section:last-of-type { border-bottom: 0; }

     .uc-section-head {
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          gap: 16px;
          margin-bottom: 24px;
     }

     .uc-section-head-main { display: flex; align-items: flex-start; gap: 12px; }

     .uc-step {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          width: 26px;
          height: 26px;
          flex: 0 0 auto;
          font-size: 11px;
          font-weight: 900;
          border-radius: 50%;
          color: #fff;
          background: linear-gradient(135deg, #2563eb, #0891b2);
          box-shadow: 0 4px 10px rgba(37,99,235,.28);
          margin-right: 6px;
     }

     .uc-section-head h2 { margin: 0 0 4px; color: var(--uc-dark); font-size: 17px; font-weight: 800; }
     .uc-section-head p { margin: 0; color: var(--uc-muted); font-size: 13px; line-height: 1.5; }

     .uc-req-note {
          padding: 5px 10px;
          color: #7c3aed;
          font-size: 11.5px;
          font-weight: 700;
          white-space: nowrap;
          border: 1px solid #ddd6fe;
          border-radius: 999px;
          background: #f5f3ff;
     }

     .uc-req-mark { color: #ef4444; }

     /* Labels & inputs */
     .uc-label {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 8px;
          margin-bottom: 7px;
          color: #334155;
          font-size: 12.5px;
          font-weight: 700;
     }

     .uc-label small { color: #7c3aed; font-size: 11px; font-weight: 600; }

     .uc-input-wrap { position: relative; }

     .uc-input-icon {
          position: absolute;
          top: 50%;
          left: 14px;
          z-index: 2;
          color: #93c5fd;
          font-size: 15px;
          transform: translateY(-50%);
          pointer-events: none;
          transition: .18s ease;
     }

     .uc-input-wrap:focus-within .uc-input-icon { color: #2563eb; transform: translateY(-50%) scale(1.08); }

     .uc-input {
          width: 100%;
          height: 50px;
          padding: 10px 42px;
          color: var(--uc-dark);
          font-size: 13.5px;
          border: 1.5px solid #e2e8f0;
          border-radius: 12px;
          outline: none;
          background: #fff;
          box-shadow: 0 1px 3px rgba(15,23,42,.04);
          transition: border-color .18s, box-shadow .18s, transform .18s;
     }

     .uc-input::placeholder { color: #b0bac8; }
     .uc-input:hover { border-color: #93c5fd; }
     .uc-input:focus {
          border-color: #2563eb;
          box-shadow: 0 0 0 3px rgba(37,99,235,.12), 0 6px 16px rgba(37,99,235,.07);
          transform: translateY(-1px);
     }
     .uc-input.is-invalid { border-color: #fca5a5; background: #fff7f7; }
     .uc-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.10); }

     .uc-pw-toggle {
          position: absolute;
          top: 50%;
          right: 8px;
          z-index: 3;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          width: 34px;
          height: 34px;
          padding: 0;
          color: #2563eb;
          border: 0;
          border-radius: 9px;
          background: #eff6ff;
          transform: translateY(-50%);
          cursor: pointer;
          transition: .2s ease;
     }

     .uc-pw-toggle:hover { color: #fff; background: linear-gradient(135deg, #2563eb, #0891b2); }

     .uc-field-error { display: flex; align-items: center; gap: 6px; margin-top: 6px; color: #ef4444; font-size: 12px; font-weight: 600; }

     .uc-field-help {
          margin-top: 8px;
          padding: 8px 12px;
          color: #475569;
          font-size: 11.5px;
          line-height: 1.55;
          border-left: 3px solid #93c5fd;
          border-radius: 0 9px 9px 0;
          background: #f0f9ff;
     }

     /* Strength meter */
     .uc-strength { margin-top: 10px; }

     .uc-strength-bars { display: flex; gap: 4px; margin-bottom: 5px; }

     .uc-strength-bar { flex: 1; height: 3px; border-radius: 999px; background: #e2e8f0; transition: background .3s; }
     .uc-strength-lbl { font-size: 11px; font-weight: 700; color: #94a3b8; transition: color .3s; }

     .uc-strength[data-lvl="1"] .uc-strength-bar:nth-child(-n+1) { background: #ef4444; }
     .uc-strength[data-lvl="2"] .uc-strength-bar:nth-child(-n+2) { background: #f97316; }
     .uc-strength[data-lvl="3"] .uc-strength-bar:nth-child(-n+3) { background: #eab308; }
     .uc-strength[data-lvl="4"] .uc-strength-bar:nth-child(-n+4) { background: #22c55e; }
     .uc-strength[data-lvl="1"] .uc-strength-lbl { color: #ef4444; }
     .uc-strength[data-lvl="2"] .uc-strength-lbl { color: #f97316; }
     .uc-strength[data-lvl="3"] .uc-strength-lbl { color: #eab308; }
     .uc-strength[data-lvl="4"] .uc-strength-lbl { color: #22c55e; }

     /* Role grid */
     .uc-role-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }

     .uc-choice-input { position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0; pointer-events: none; }

     .uc-role-card {
          position: relative;
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 9px;
          min-height: 96px;
          padding: 16px 12px;
          cursor: pointer;
          border: 1.5px solid #e2e8f0;
          border-radius: 16px;
          background: #fff;
          box-shadow: 0 2px 8px rgba(15,23,42,.04);
          transition: .22s cubic-bezier(.34,1.56,.64,1);
          text-align: center;
     }

     .uc-role-card:hover { border-color: var(--rc, #2563eb); transform: translateY(-3px); box-shadow: 0 10px 28px rgba(37,99,235,.12); }

     .uc-role-grid > div:nth-child(6n+1) { --rc:#2563eb; --rc2:#0891b2; --rbi:#dbeafe; --rci:#eff6ff; }
     .uc-role-grid > div:nth-child(6n+2) { --rc:#7c3aed; --rc2:#db2777; --rbi:#ede9fe; --rci:#faf5ff; }
     .uc-role-grid > div:nth-child(6n+3) { --rc:#0d9488; --rc2:#0891b2; --rbi:#ccfbf1; --rci:#f0fdfa; }
     .uc-role-grid > div:nth-child(6n+4) { --rc:#d97706; --rc2:#ea580c; --rbi:#fde68a; --rci:#fffbeb; }
     .uc-role-grid > div:nth-child(6n+5) { --rc:#16a34a; --rc2:#0d9488; --rbi:#bbf7d0; --rci:#f0fdf4; }
     .uc-role-grid > div:nth-child(6n+6) { --rc:#db2777; --rc2:#e11d48; --rbi:#fce7f3; --rci:#fdf2f8; }

     .uc-role-card-icon {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          width: 44px;
          height: 44px;
          color: var(--rc, #2563eb);
          font-size: 19px;
          border-radius: 12px;
          background: var(--rci, #eff6ff);
          border: 1px solid var(--rbi, #dbeafe);
          transition: .2s ease;
     }

     .uc-role-card-title { display: block; color: var(--uc-dark); font-size: 11.5px; font-weight: 800; line-height: 1.35; }

     .uc-role-check {
          position: absolute;
          top: 9px;
          right: 9px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          width: 20px;
          height: 20px;
          color: transparent;
          font-size: 10px;
          border: 1.5px solid #e2e8f0;
          border-radius: 50%;
          background: #fff;
          transition: .2s ease;
     }

     .uc-choice-input:focus-visible + .uc-role-card { outline: 3px solid rgba(37,99,235,.18); outline-offset: 3px; }

     .uc-choice-input:checked + .uc-role-card {
          border-color: var(--rc, #2563eb);
          background: var(--rci, #eff6ff);
          box-shadow: 0 0 0 2.5px var(--rc, #2563eb), 0 12px 30px rgba(37,99,235,.14);
          transform: translateY(-3px);
     }

     .uc-choice-input:checked + .uc-role-card .uc-role-card-icon {
          color: #fff;
          background: linear-gradient(135deg, var(--rc, #2563eb), var(--rc2, #0891b2));
          box-shadow: 0 6px 16px rgba(37,99,235,.24);
          border-color: transparent;
     }

     .uc-choice-input:checked + .uc-role-card .uc-role-check {
          color: #fff;
          border-color: var(--rc, #2563eb);
          background: var(--rc, #2563eb);
     }

     .uc-empty-role {
          grid-column: 1 / -1;
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 14px 16px;
          color: #92400e;
          border: 1px solid #fde68a;
          border-radius: 12px;
          background: #fffbeb;
          font-size: 13px;
     }

     /* Status grid */
     .uc-status-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }

     .uc-status-card {
          position: relative;
          display: flex;
          align-items: center;
          gap: 12px;
          min-height: 70px;
          padding: 14px 16px;
          overflow: hidden;
          cursor: pointer;
          border: 1.5px solid #e2e8f0;
          border-radius: 14px;
          background: #fff;
          box-shadow: 0 2px 8px rgba(15,23,42,.04);
          transition: .2s ease;
     }

     .uc-status-card::after {
          content: '';
          position: absolute;
          top: -22px;
          right: -22px;
          width: 62px;
          height: 62px;
          border-radius: 50%;
          background: var(--sc, #eff6ff);
          opacity: .5;
     }

     .uc-status-card:hover { border-color: var(--sv, #2563eb); transform: translateY(-2px); box-shadow: 0 10px 24px rgba(37,99,235,.10); }

     .uc-status-dot { position: relative; z-index: 1; flex: 0 0 auto; width: 10px; height: 10px; border: 2.5px solid #e2e8f0; border-radius: 50%; background: #fff; }

     .uc-status-copy { position: relative; z-index: 1; }
     .uc-status-copy strong { display: block; color: var(--uc-dark); font-size: 13px; font-weight: 800; }
     .uc-status-copy small { display: block; margin-top: 2px; color: var(--uc-muted); font-size: 11px; line-height: 1.4; }

     .uc-choice-input:checked + .uc-status-card {
          border-color: var(--sv, #2563eb);
          background: linear-gradient(145deg, #fff, var(--sc, #eff6ff));
          box-shadow: 0 0 0 2.5px var(--sv, #2563eb), 0 10px 22px rgba(37,99,235,.12);
          transform: translateY(-2px);
     }

     .uc-choice-input:checked + .uc-status-card .uc-status-dot {
          border-color: #fff;
          background: var(--sv, #2563eb);
          box-shadow: 0 0 0 2px var(--sv, #2563eb);
     }

     /* Footer */
     .uc-footer {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 16px;
          padding: 20px 28px;
          border-top: 1px solid #f1f5f9;
          background: linear-gradient(90deg, #f8faff, #f5f3ff 50%, #f0fdf4);
     }

     .uc-footer-info { display: flex; align-items: center; gap: 8px; color: #475569; font-size: 12px; font-weight: 600; }
     .uc-footer-info i { color: #2563eb; }

     .uc-footer-actions { display: flex; align-items: center; gap: 10px; }

     .uc-btn-cancel {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          min-height: 46px;
          padding: 10px 20px;
          color: #475569;
          font-size: 13.5px;
          font-weight: 700;
          text-decoration: none;
          border: 1.5px solid #e2e8f0;
          border-radius: 12px;
          background: #fff;
          transition: .2s ease;
     }

     .uc-btn-cancel:hover { color: #1e293b; border-color: #cbd5e1; background: #f8fafc; transform: translateY(-1px); }

     .uc-btn-save {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          min-height: 46px;
          padding: 10px 24px;
          color: #fff;
          font-size: 13.5px;
          font-weight: 700;
          border: 0;
          border-radius: 12px;
          background: linear-gradient(135deg, #1d4ed8, #2563eb, #0891b2);
          background-size: 180% 100%;
          box-shadow: 0 8px 22px rgba(37,99,235,.28);
          cursor: pointer;
          transition: .22s ease;
     }

     .uc-btn-save:hover { color: #fff; background-position: 100% 0; transform: translateY(-2px); box-shadow: 0 14px 28px rgba(37,99,235,.34); }
     .uc-btn-save:disabled { opacity: .72; cursor: not-allowed; transform: none; }

     /* Responsive */
     @media (max-width: 1199px) {
          .uc-layout { grid-template-columns: 280px minmax(0, 1fr); }
          .uc-role-grid { grid-template-columns: repeat(2, 1fr); }
     }
     @media (max-width: 991px) {
          .uc-layout { grid-template-columns: 1fr; }
          .uc-profile-panel { position: static; }
          .uc-profile-body { display: grid; grid-template-columns: auto 1fr; gap: 22px; align-items: center; text-align: left; }
          .uc-avatar-wrap { margin-bottom: 0; }
     }
     @media (max-width: 767px) {
          .uc-hero { flex-direction: column; align-items: flex-start; padding: 26px 22px; border-radius: 18px; }
          .uc-btn-back, .uc-hero-action { width: 100%; }
          .uc-profile-body { display: block; text-align: center; }
          .uc-avatar-wrap { margin-bottom: 18px; }
          .uc-section, .uc-footer { padding: 20px; }
          .uc-section-head { flex-direction: column; }
          .uc-role-grid, .uc-status-grid { grid-template-columns: 1fr; }
          .uc-footer { flex-direction: column; align-items: stretch; }
          .uc-footer-actions { width: 100%; }
          .uc-btn-cancel, .uc-btn-save { flex: 1; justify-content: center; }
     }
     @media (max-width: 479px) {
          .uc-footer-actions { flex-direction: column-reverse; }
          .uc-btn-cancel, .uc-btn-save { width: 100%; }
     }
</style>

<div class="uc-page">
     <div class="container-fluid" style="position:relative;z-index:1;">

          {{-- Hero --}}
          <header class="uc-hero mb-4">
               <div class="uc-hero-glow"></div>
               <div class="uc-hero-content">
                    <div class="uc-eyebrow">
                         <i class="bi bi-person-plus-fill"></i>
                         Manajemen Pengguna
                    </div>
                    <h1>Tambah Pengguna Baru</h1>
                    <p>
                         Lengkapi identitas pengguna, pilih hak akses role, dan atur status akun untuk
                         memberikan akses ke sistem monitoring produktivitas &amp; transaksi jasa.
                    </p>
                    <nav class="uc-breadcrumb">
                         <i class="bi bi-house-fill"></i>
                         <a href="{{ route('super-admin.users.index') }}">Pengguna</a>
                         <i class="bi bi-chevron-right"></i>
                         <span>Tambah Baru</span>
                    </nav>
               </div>
               <div class="uc-hero-action">
                    <a href="{{ route('super-admin.users.index') }}" class="uc-btn-back">
                         <i class="bi bi-arrow-left"></i>
                         Kembali ke Daftar
                    </a>
               </div>
          </header>

          {{-- Validation --}}
          @if ($errors->any())
               <div class="uc-alert" role="alert">
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

          {{-- Form --}}
          <form id="ucForm" action="{{ route('super-admin.users.store') }}" method="POST"
               enctype="multipart/form-data" novalidate>
               @csrf

               <div class="uc-layout">

                    {{-- Profile panel --}}
                    <aside class="uc-card uc-profile-panel">
                         <div class="uc-panel-head">
                              <span class="uc-panel-icon">
                                   <i class="bi bi-person-bounding-box"></i>
                              </span>
                              <div>
                                   <h2>Foto Profil</h2>
                                   <p>Gunakan foto yang jelas agar mudah dikenali.</p>
                              </div>
                         </div>

                         <div class="uc-profile-body">
                              <div>
                                   <div class="uc-avatar-wrap">
                                        <div class="uc-avatar-track"></div>
                                        <div class="uc-avatar-preview" id="ucAvatarBox">
                                             <span class="uc-avatar-icon"><i class="bi bi-person-fill"></i></span>
                                             <img id="ucPreview" src="" alt="Pratinjau foto">
                                        </div>
                                        <label for="ucPhotoInput" class="uc-avatar-cam" title="Ganti foto">
                                             <i class="bi bi-camera-fill"></i>
                                        </label>
                                   </div>

                                   <label for="ucPhotoInput" class="uc-upload-btn">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        Pilih Foto
                                   </label>
                                   <input id="ucPhotoInput" type="file" name="photo"
                                        accept="image/png,image/jpeg,image/jpg,image/webp" hidden>

                                   @error('photo')
                                        <div class="uc-field-error justify-content-center mt-2">
                                             <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                   @enderror

                                   <p class="uc-upload-note">Format JPG, PNG, WEBP &bull; Maks. 2 MB</p>
                              </div>

                              <div class="uc-preview-card">
                                   <div class="uc-preview-title">
                                        <i class="bi bi-eye"></i> Pratinjau Data
                                   </div>
                                   <div class="uc-preview-row">
                                        <span class="uc-preview-icon"><i class="bi bi-person"></i></span>
                                        <span class="uc-preview-val" id="lpName" data-empty="Nama belum diisi">
                                             <span class="uc-preview-empty">Nama belum diisi</span>
                                        </span>
                                   </div>
                                   <div class="uc-preview-row">
                                        <span class="uc-preview-icon"><i class="bi bi-at"></i></span>
                                        <span class="uc-preview-val" id="lpUsername" data-empty="Username belum diisi">
                                             <span class="uc-preview-empty">Username belum diisi</span>
                                        </span>
                                   </div>
                                   <div class="uc-preview-row">
                                        <span class="uc-preview-icon"><i class="bi bi-envelope"></i></span>
                                        <span class="uc-preview-val" id="lpEmail" data-empty="Email belum diisi">
                                             <span class="uc-preview-empty">Email belum diisi</span>
                                        </span>
                                   </div>
                                   <div class="uc-preview-row">
                                        <span class="uc-preview-icon"><i class="bi bi-shield-check"></i></span>
                                        <span class="uc-preview-val" id="lpRole" data-empty="Role belum dipilih">
                                             <span class="uc-preview-empty">Role belum dipilih</span>
                                        </span>
                                   </div>
                              </div>
                         </div>
                    </aside>

                    {{-- Form panel --}}
                    <main class="uc-card uc-form-panel">

                         {{-- Section 1 --}}
                         <section class="uc-section">
                              <div class="uc-section-head">
                                   <div class="uc-section-head-main">
                                        <span class="uc-panel-icon" style="background:linear-gradient(135deg,#2563eb,#0891b2);">
                                             <i class="bi bi-person-vcard"></i>
                                        </span>
                                        <div>
                                             <h2><span class="uc-step">1</span>Informasi Pengguna</h2>
                                             <p>Identitas, kontak, dan kredensial masuk pengguna.</p>
                                        </div>
                                   </div>
                                   <div class="uc-req-note"><span class="uc-req-mark">*</span> Wajib diisi</div>
                              </div>

                              <div class="row g-3">
                                   <div class="col-md-6">
                                        <label for="ucName" class="uc-label">
                                             <span>Nama Lengkap <span class="uc-req-mark">*</span></span>
                                        </label>
                                        <div class="uc-input-wrap">
                                             <i class="bi bi-person uc-input-icon"></i>
                                             <input id="ucName" type="text" name="name"
                                                  class="uc-input @error('name') is-invalid @enderror"
                                                  value="{{ old('name') }}" placeholder="Contoh: Andi Saputra"
                                                  autocomplete="name" required>
                                        </div>
                                        @error('name')
                                             <div class="uc-field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="col-md-6">
                                        <label for="ucUsername" class="uc-label">
                                             <span>Username <span class="uc-req-mark">*</span></span>
                                             <small>Tanpa spasi</small>
                                        </label>
                                        <div class="uc-input-wrap">
                                             <i class="bi bi-at uc-input-icon"></i>
                                             <input id="ucUsername" type="text" name="username"
                                                  class="uc-input @error('username') is-invalid @enderror"
                                                  value="{{ old('username') }}" placeholder="Contoh: andi.saputra"
                                                  autocomplete="username" required>
                                        </div>
                                        @error('username')
                                             <div class="uc-field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="col-md-6">
                                        <label for="ucEmail" class="uc-label">
                                             <span>Email <span class="uc-req-mark">*</span></span>
                                        </label>
                                        <div class="uc-input-wrap">
                                             <i class="bi bi-envelope uc-input-icon"></i>
                                             <input id="ucEmail" type="email" name="email"
                                                  class="uc-input @error('email') is-invalid @enderror"
                                                  value="{{ old('email') }}" placeholder="nama@perusahaan.com"
                                                  autocomplete="email" required>
                                        </div>
                                        @error('email')
                                             <div class="uc-field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="col-md-6">
                                        <label for="ucPhone" class="uc-label">
                                             <span>Nomor Telepon</span>
                                             <small>Opsional</small>
                                        </label>
                                        <div class="uc-input-wrap">
                                             <i class="bi bi-telephone uc-input-icon"></i>
                                             <input id="ucPhone" type="tel" name="phone"
                                                  class="uc-input @error('phone') is-invalid @enderror"
                                                  value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                                                  autocomplete="tel" inputmode="tel">
                                        </div>
                                        @error('phone')
                                             <div class="uc-field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                        @enderror
                                   </div>

                                   <div class="col-12">
                                        <label for="ucPassword" class="uc-label">
                                             <span>Password <span class="uc-req-mark">*</span></span>
                                             <small>Minimal 8 karakter</small>
                                        </label>
                                        <div class="uc-input-wrap">
                                             <i class="bi bi-lock uc-input-icon"></i>
                                             <input id="ucPassword" type="password" name="password"
                                                  class="uc-input @error('password') is-invalid @enderror"
                                                  placeholder="Masukkan password pengguna"
                                                  autocomplete="new-password" minlength="8" required>
                                             <button type="button" class="uc-pw-toggle" id="ucPwToggle"
                                                  aria-label="Tampilkan password" aria-pressed="false">
                                                  <i class="bi bi-eye" id="ucPwIcon"></i>
                                             </button>
                                        </div>
                                        @error('password')
                                             <div class="uc-field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                        @enderror
                                        <div class="uc-strength" id="ucStrength" data-lvl="0">
                                             <div class="uc-strength-bars">
                                                  <div class="uc-strength-bar"></div>
                                                  <div class="uc-strength-bar"></div>
                                                  <div class="uc-strength-bar"></div>
                                                  <div class="uc-strength-bar"></div>
                                             </div>
                                             <span class="uc-strength-lbl" id="ucStrengthLbl">Ketikkan password</span>
                                        </div>
                                        <div class="uc-field-help">
                                             Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk keamanan optimal.
                                        </div>
                                   </div>
                              </div>
                         </section>

                         {{-- Section 2: Role --}}
                         <section class="uc-section">
                              <div class="uc-section-head">
                                   <div class="uc-section-head-main">
                                        <span class="uc-panel-icon" style="background:linear-gradient(135deg,#7c3aed,#0891b2);">
                                             <i class="bi bi-shield-lock"></i>
                                        </span>
                                        <div>
                                             <h2><span class="uc-step" style="background:linear-gradient(135deg,#7c3aed,#0891b2);">2</span>Hak Akses Sistem</h2>
                                             <p>Pilih role yang sesuai dengan tugas pengguna dalam sistem.</p>
                                        </div>
                                   </div>
                              </div>

                              <label class="uc-label mb-3">
                                   <span>Role Pengguna <span class="uc-req-mark">*</span></span>
                              </label>

                              <div class="uc-role-grid">
                                   @forelse ($roles as $role)
                                        @php
                                             $roleName  = strtolower($role->name);
                                             $roleLabel = ucwords(str_replace(['_', '-'], ' ', $role->name));
                                             $roleIcon  = match (true) {
                                                 str_contains($roleName, 'super')                                            => 'bi-stars',
                                                 str_contains($roleName, 'direktur') || str_contains($roleName, 'executive') => 'bi-briefcase-fill',
                                                 str_contains($roleName, 'hrd')      || str_contains($roleName, 'human')     => 'bi-people-fill',
                                                 str_contains($roleName, 'manager')                                          => 'bi-diagram-3-fill',
                                                 str_contains($roleName, 'karyawan')                                         => 'bi-person-badge-fill',
                                                 str_contains($roleName, 'pelayanan')                                        => 'bi-headset',
                                                 str_contains($roleName, 'operasional')                                      => 'bi-gear-fill',
                                                 str_contains($roleName, 'finance') || str_contains($roleName, 'keuangan')   => 'bi-cash-coin',
                                                 str_contains($roleName, 'auditor') || str_contains($roleName, 'audit')      => 'bi-clipboard-check-fill',
                                                 str_contains($roleName, 'admin')                                            => 'bi-shield-check',
                                                 default                                                                     => 'bi-person-gear',
                                             };
                                        @endphp
                                        <div>
                                             <input class="uc-choice-input" type="radio" name="role_id"
                                                  id="role_{{ $role->id }}" value="{{ $role->id }}"
                                                  data-label="{{ $roleLabel }}"
                                                  {{ (string) old('role_id') === (string) $role->id ? 'checked' : '' }}
                                                  required>
                                             <label class="uc-role-card" for="role_{{ $role->id }}">
                                                  <span class="uc-role-card-icon"><i class="bi {{ $roleIcon }}"></i></span>
                                                  <span class="uc-role-card-title">{{ $roleLabel }}</span>
                                                  <span class="uc-role-check"><i class="bi bi-check-lg"></i></span>
                                             </label>
                                        </div>
                                   @empty
                                        <div class="uc-empty-role">
                                             <i class="bi bi-exclamation-triangle-fill"></i>
                                             Role belum tersedia. Tambahkan data role terlebih dahulu.
                                        </div>
                                   @endforelse
                              </div>

                              @error('role_id')
                                   <div class="uc-field-error mt-2"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                              @enderror
                         </section>

                         {{-- Section 3: Status --}}
                         <section class="uc-section">
                              <div class="uc-section-head">
                                   <div class="uc-section-head-main">
                                        <span class="uc-panel-icon" style="background:linear-gradient(135deg,#0d9488,#16a34a);">
                                             <i class="bi bi-toggle-on"></i>
                                        </span>
                                        <div>
                                             <h2><span class="uc-step" style="background:linear-gradient(135deg,#0d9488,#16a34a);">3</span>Status Akun</h2>
                                             <p>Atur apakah akun dapat digunakan segera setelah dibuat.</p>
                                        </div>
                                   </div>
                              </div>

                              <div class="uc-status-grid">
                                   @foreach ($statuses as $status)
                                        @php
                                             $sk    = strtolower($status);
                                             $sl    = ucwords(str_replace(['_', '-'], ' ', $status));
                                             $sv    = match ($sk) {
                                                 'active',   'aktif'        => '#16a34a',
                                                 'inactive', 'nonaktif'     => '#0284c7',
                                                 'suspended','ditangguhkan' => '#d97706',
                                                 default                    => '#2563eb',
                                             };
                                             $sc    = match ($sk) {
                                                 'active',   'aktif'        => '#f0fdf4',
                                                 'inactive', 'nonaktif'     => '#f0f9ff',
                                                 'suspended','ditangguhkan' => '#fffbeb',
                                                 default                    => '#eff6ff',
                                             };
                                             $sdesc = match ($sk) {
                                                 'active',   'aktif'        => 'Dapat masuk dan menggunakan sistem.',
                                                 'inactive', 'nonaktif'     => 'Akun belum dapat digunakan.',
                                                 'suspended','ditangguhkan' => 'Akses akun dihentikan sementara.',
                                                 default                    => 'Status akses pengguna.',
                                             };
                                        @endphp
                                        <div>
                                             <input class="uc-choice-input" type="radio" name="status"
                                                  id="status_{{ $loop->index }}" value="{{ $status }}"
                                                  {{ old('status', $loop->first ? $status : null) === $status ? 'checked' : '' }}
                                                  required>
                                             <label class="uc-status-card" for="status_{{ $loop->index }}"
                                                  style="--sv:{{ $sv }};--sc:{{ $sc }};">
                                                  <span class="uc-status-dot"></span>
                                                  <span class="uc-status-copy">
                                                       <strong>{{ $sl }}</strong>
                                                       <small>{{ $sdesc }}</small>
                                                  </span>
                                             </label>
                                        </div>
                                   @endforeach
                              </div>

                              @error('status')
                                   <div class="uc-field-error mt-2"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                              @enderror
                         </section>

                         <footer class="uc-footer">
                              <div class="uc-footer-info">
                                   <i class="bi bi-info-circle-fill"></i>
                                   Periksa kembali data sebelum menyimpan pengguna baru.
                              </div>
                              <div class="uc-footer-actions">
                                   <a href="{{ route('super-admin.users.index') }}" class="uc-btn-cancel">
                                        <i class="bi bi-x-lg"></i>
                                        Batal
                                   </a>
                                   <button type="submit" class="uc-btn-save" id="ucSubmit">
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
document.addEventListener('DOMContentLoaded', function () {
     const photoInput  = document.getElementById('ucPhotoInput');
     const preview     = document.getElementById('ucPreview');
     const avatarBox   = document.getElementById('ucAvatarBox');
     const pwInput     = document.getElementById('ucPassword');
     const pwToggle    = document.getElementById('ucPwToggle');
     const pwIcon      = document.getElementById('ucPwIcon');
     const form        = document.getElementById('ucForm');
     const submitBtn   = document.getElementById('ucSubmit');
     const strength    = document.getElementById('ucStrength');
     const strengthLbl = document.getElementById('ucStrengthLbl');
     const lpName      = document.getElementById('lpName');
     const lpUsername  = document.getElementById('lpUsername');
     const lpEmail     = document.getElementById('lpEmail');
     const lpRole      = document.getElementById('lpRole');
     let blobUrl = null;

     photoInput?.addEventListener('change', function (e) {
          const file = e.target.files?.[0];
          if (!file) { preview.removeAttribute('src'); avatarBox.classList.remove('has-image'); return; }
          if (!file.type.startsWith('image/')) { e.target.value = ''; alert('File harus berupa gambar.'); return; }
          if (file.size > 2 * 1024 * 1024) { e.target.value = ''; alert('Ukuran foto maksimal 2 MB.'); return; }
          if (blobUrl) URL.revokeObjectURL(blobUrl);
          blobUrl = URL.createObjectURL(file);
          preview.src = blobUrl;
          avatarBox.classList.add('has-image');
     });

     pwToggle?.addEventListener('click', function () {
          const show = pwInput.type === 'password';
          pwInput.type = show ? 'text' : 'password';
          pwToggle.setAttribute('aria-pressed', String(show));
          pwToggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
          pwIcon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
     });

     function calcStrength(v) {
          if (!v) return 0;
          let s = 0;
          if (v.length >= 8)          s++;
          if (/[A-Z]/.test(v))        s++;
          if (/[0-9]/.test(v))        s++;
          if (/[^A-Za-z0-9]/.test(v)) s++;
          return s;
     }

     const strengthMsgs = ['', 'Sangat lemah', 'Lemah', 'Cukup kuat', 'Kuat'];
     pwInput?.addEventListener('input', function () {
          const lvl = calcStrength(this.value);
          strength.dataset.lvl = lvl;
          strengthLbl.textContent = this.value ? strengthMsgs[lvl] : 'Ketikkan password';
     });

     function liveUpdate(node, val) {
          node.innerHTML = val
               ? `<span>${val}</span>`
               : `<span class="uc-preview-empty">${node.dataset.empty}</span>`;
     }

     const nameInput     = document.getElementById('ucName');
     const usernameInput = document.getElementById('ucUsername');
     const emailInput    = document.getElementById('ucEmail');

     nameInput?.addEventListener('input',     () => liveUpdate(lpName,     nameInput.value.trim()));
     usernameInput?.addEventListener('input', () => liveUpdate(lpUsername, usernameInput.value.trim()));
     emailInput?.addEventListener('input',    () => liveUpdate(lpEmail,    emailInput.value.trim()));

     document.querySelectorAll('input[name="role_id"]').forEach(r => {
          r.addEventListener('change', function () {
               lpRole.innerHTML = `<span class="uc-preview-badge"><i class="bi bi-shield-check"></i>${this.dataset.label}</span>`;
          });
     });

     if (nameInput?.value)     liveUpdate(lpName,     nameInput.value.trim());
     if (usernameInput?.value) liveUpdate(lpUsername, usernameInput.value.trim());
     if (emailInput?.value)    liveUpdate(lpEmail,    emailInput.value.trim());

     const checkedRole = document.querySelector('input[name="role_id"]:checked');
     if (checkedRole) lpRole.innerHTML = `<span class="uc-preview-badge"><i class="bi bi-shield-check"></i>${checkedRole.dataset.label}</span>`;

     form?.addEventListener('submit', function () {
          submitBtn.disabled = true;
          submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan&hellip;</span>`;
     });
});
</script>
@endsection
