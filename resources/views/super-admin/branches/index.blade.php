@extends('layouts.app')

@section('title', 'Branch Management')

@section('content')
     <style>
          .branches-page {
               --bp-primary: #2563eb;
               --bp-primary-dark: #1d4ed8;
               --bp-sky: #0ea5e9;
               --bp-success: #16a34a;
               --bp-danger: #dc2626;
               --bp-warning: #d97706;
               --bp-text: #0f172a;
               --bp-muted: #64748b;
               --bp-border: #e2e8f0;
               --bp-soft: #f8fafc;
               --bp-white: #ffffff;
               min-height: calc(100vh - 70px);
               padding: 26px 18px 42px;
               color: var(--bp-text);
               background:
                    radial-gradient(circle at 100% 0, rgba(173, 189, 224, 0.08), transparent 28%),
                    radial-gradient(circle at 0 100%, rgba(143, 171, 184, 0.06), transparent 24%),
                    #f5f7fb;
          }

          .branches-page * {
               box-sizing: border-box;
          }

          .branches-shell {
               width: 100%;
               max-width: none;
               margin: 0;
          }

          .bp-hero {
               position: relative;
               overflow: hidden;
               margin-bottom: 22px;
               padding: 30px 32px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .15);
               border-radius: 24px;
               background:
                    radial-gradient(circle at 88% 18%,
                         rgba(255, 255, 255, .18),
                         transparent 22%),
                    radial-gradient(circle at 72% 110%,
                         rgba(45, 212, 191, .20),
                         transparent 36%),
                    linear-gradient(135deg,
                         #0f172a 0%,
                         #134e4a 45%,
                         #0f766e 72%,
                         #14b8a6 100%);
               box-shadow: 0 24px 54px rgba(30, 64, 175, .22);
          }

          .bp-hero::after {
               content: '';
               position: absolute;
               right: -105px;
               bottom: -150px;
               width: 290px;
               height: 290px;
               border: 42px solid rgba(255, 255, 255, .08);
               border-radius: 50%;
               pointer-events: none;
          }

          .bp-hero-grid {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 28px;
          }

          .bp-hero-main {
               display: flex;
               align-items: flex-start;
               gap: 16px;
               min-width: 0;
          }

          .bp-hero-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 58px;
               width: 58px;
               height: 58px;
               font-size: 1.45rem;
               border: 1px solid rgba(255, 255, 255, .28);
               border-radius: 18px;
               background: rgba(255, 255, 255, .13);
               backdrop-filter: blur(9px);
          }

          .bp-eyebrow {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               margin-bottom: 7px;
               color: rgba(255, 255, 255, .72);
               font-size: .72rem;
               font-weight: 800;
               letter-spacing: .13em;
               text-transform: uppercase;
          }

          .bp-hero h1 {
               margin: 0 0 7px;
               color: #fff;
               font-size: clamp(1.7rem, 3vw, 2.35rem);
               font-weight: 850;
               letter-spacing: -.04em;
          }

          .bp-hero p {
               max-width: 760px;
               margin: 0;
               color: rgba(255, 255, 255, .80);
               font-size: .92rem;
               line-height: 1.65;
          }

          .bp-hero-actions {
               display: flex;
               align-items: center;
               justify-content: flex-end;
               flex-wrap: wrap;
               gap: 10px;
               flex: 0 0 auto;
          }

          .bp-hero-btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 44px;
               padding: 10px 15px;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 12px;
               transition: .2s ease;
          }

          .bp-hero-btn.primary {
               color: var(--bp-primary-dark);
               border: 1px solid #fff;
               background: #fff;
               box-shadow: 0 10px 24px rgba(15, 23, 42, .20);
          }

          .bp-hero-btn.secondary {
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .28);
               background: rgba(255, 255, 255, .10);
               backdrop-filter: blur(8px);
          }

          .bp-hero-btn:hover {
               transform: translateY(-2px);
          }

          .bp-hero-btn.primary:hover {
               color: var(--bp-primary-dark);
               background: #eff6ff;
          }

          .bp-hero-btn.secondary:hover {
               color: #fff;
               background: rgba(255, 255, 255, .18);
          }

          .bp-alert {
               display: flex;
               align-items: flex-start;
               gap: 12px;
               margin-bottom: 18px;
               padding: 15px 17px;
               border-radius: 15px;
               box-shadow: 0 9px 24px rgba(15, 23, 42, .05);
          }

          .bp-alert.success {
               color: #166534;
               border: 1px solid #bbf7d0;
               background: #f0fdf4;
          }

          .bp-alert.error {
               color: #991b1b;
               border: 1px solid #fecaca;
               background: #fff7f7;
          }

          .bp-alert-icon {
               margin-top: 1px;
               font-size: 1.1rem;
          }

          .bp-alert-title {
               margin-bottom: 2px;
               font-size: .85rem;
               font-weight: 850;
          }

          .bp-alert-message {
               font-size: .8rem;
          }

          .bp-alert-close {
               margin-left: auto;
               padding: 0;
               color: currentColor;
               border: 0;
               background: transparent;
               opacity: .65;
          }

          .bp-stats {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 16px;
               margin-bottom: 20px;
          }

          .bp-stat-card,
          .bp-panel {
               border: 1px solid rgba(226, 232, 240, .95);
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 13px 34px rgba(15, 23, 42, .055);
          }

          .bp-stat-card {
               position: relative;
               overflow: hidden;
               min-height: 126px;
               padding: 18px;
               border-radius: 18px;
               transition: .2s ease;
          }

          .bp-stat-card::after {
               content: '';
               position: absolute;
               right: -35px;
               bottom: -45px;
               width: 100px;
               height: 100px;
               border-radius: 50%;
               background: var(--stat-soft, #eff6ff);
               opacity: .7;
          }

          .bp-stat-card:hover {
               transform: translateY(-3px);
               border-color: #bfdbfe;
               box-shadow: 0 18px 38px rgba(15, 23, 42, .09);
          }

          .bp-stat-top {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 14px;
          }

          .bp-stat-label {
               margin-bottom: 7px;
               color: var(--bp-muted);
               font-size: .69rem;
               font-weight: 850;
               letter-spacing: .09em;
               text-transform: uppercase;
          }

          .bp-stat-value {
               margin: 0;
               color: var(--bp-text);
               font-size: 1.85rem;
               font-weight: 850;
               letter-spacing: -.045em;
               line-height: 1;
          }

          .bp-stat-note {
               position: relative;
               z-index: 1;
               margin: 12px 0 0;
               color: var(--bp-muted);
               font-size: .75rem;
          }

          .bp-stat-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 46px;
               width: 46px;
               height: 46px;
               color: var(--stat-color, var(--bp-primary));
               font-size: 1.15rem;
               border-radius: 14px;
               background: var(--stat-soft, #eff6ff);
          }

          .bp-panel {
               overflow: hidden;
               border-radius: 20px;
          }

          .bp-filter-panel {
               margin-bottom: 20px;
               padding: 20px;
          }

          .bp-panel-heading {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
               margin-bottom: 16px;
          }

          .bp-section-title {
               display: flex;
               align-items: center;
               gap: 9px;
               margin: 0 0 4px;
               color: var(--bp-text);
               font-size: 1rem;
               font-weight: 850;
          }

          .bp-section-title i {
               color: var(--bp-primary);
          }

          .bp-section-copy {
               margin: 0;
               color: var(--bp-muted);
               font-size: .79rem;
          }

          .bp-chip {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 10px;
               color: #115e59;
               font-size: .72rem;
               font-weight: 800;
               white-space: nowrap;
               border: 1px solid #bfdbfe;
               border-radius: 999px;
               background: #eff6ff;
          }

          .bp-filter-grid {
               display: grid;
               grid-template-columns: minmax(0, 2fr) minmax(190px, .8fr) auto;
               gap: 12px;
               align-items: end;
          }

          .bp-label {
               display: block;
               margin-bottom: 7px;
               color: #334155;
               font-size: .76rem;
               font-weight: 800;
          }

          .bp-input-wrap {
               position: relative;
          }

          .bp-input-wrap>i {
               position: absolute;
               top: 50%;
               left: 14px;
               color: #94a3b8;
               transform: translateY(-50%);
               pointer-events: none;
          }

          .bp-control {
               width: 100%;
               min-height: 45px;
               padding: 10px 13px;
               color: var(--bp-text);
               font-size: .82rem;
               border: 1px solid var(--bp-border);
               border-radius: 12px;
               outline: 0;
               background: #fff;
               transition: .18s ease;
          }

          .bp-input-wrap .bp-control {
               padding-left: 41px;
          }

          .bp-control:hover {
               border-color: #cbd5e1;
          }

          .bp-control:focus {
               border-color: #93c5fd;
               box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
          }

          .bp-filter-actions {
               display: flex;
               gap: 8px;
          }

          .bp-button {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
               min-height: 45px;
               padding: 10px 15px;
               font-size: .8rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 12px;
               transition: .18s ease;
          }

          .bp-button.primary {
               color: #fff;
               border: 1px solid var(--bp-primary);
               background: var(--bp-primary);
               box-shadow: 0 8px 18px rgba(37, 99, 235, .20);
          }

          .bp-button.primary:hover {
               color: #fff;
               border-color: var(--bp-primary-dark);
               background: var(--bp-primary-dark);
               transform: translateY(-1px);
          }

          .bp-button.light {
               color: #475569;
               border: 1px solid var(--bp-border);
               background: #fff;
          }

          .bp-button.light:hover {
               color: var(--bp-text);
               background: var(--bp-soft);
          }

          .bp-list-heading {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px;
               padding: 18px 20px;
               border-bottom: 1px solid var(--bp-border);
          }

          .bp-table-wrap {
               overflow-x: auto;
          }

          .bp-table {
               width: 100%;
               margin: 0;
               border-collapse: collapse;
          }

          .bp-table thead th {
               padding: 12px 14px;
               color: #64748b;
               font-size: .66rem;
               font-weight: 850;
               letter-spacing: .075em;
               text-align: left;
               text-transform: uppercase;
               white-space: nowrap;
               border-bottom: 1px solid var(--bp-border);
               background: #f8fafc;
          }

          .bp-table tbody td {
               padding: 14px;
               color: #334155;
               font-size: .82rem;
               vertical-align: middle;
               border-bottom: 1px solid #edf2f7;
          }

          .bp-table tbody tr:last-child td {
               border-bottom: 0;
          }

          .bp-table tbody tr:hover {
               background: #f8fbff;
          }

          .bp-number {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 34px;
               height: 34px;
               color: #475569;
               font-size: .75rem;
               font-weight: 850;
               border-radius: 10px;
               background: #f1f5f9;
          }

          .bp-code {
               display: inline-flex;
               align-items: center;
               padding: 6px 9px;
               color: #4338ca;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .03em;
               border: 1px solid #bfdbfe;
               border-radius: 9px;
               background: #eff6ff;
          }

          .bp-branch-name {
               margin-bottom: 5px;
               color: var(--bp-text);
               font-weight: 850;
          }

          .bp-address {
               display: flex;
               align-items: flex-start;
               gap: 6px;
               max-width: 360px;
               color: var(--bp-muted);
               font-size: .74rem;
               line-height: 1.5;
          }

          .bp-manager {
               display: flex;
               align-items: center;
               gap: 9px;
               min-width: 170px;
          }

          .bp-manager-avatar {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               flex: 0 0 38px;
               width: 38px;
               height: 38px;
               color: #fff;
               font-size: .78rem;
               font-weight: 850;
               border-radius: 12px;
               background: linear-gradient(135deg, #8a99b9, #9cabb3);
               box-shadow: 0 7px 16px rgba(37, 99, 235, .17);
          }

          .bp-manager-name {
               color: var(--bp-text);
               font-size: .8rem;
               font-weight: 800;
          }

          .bp-manager-role {
               color: var(--bp-muted);
               font-size: .69rem;
          }

          .bp-unassigned {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 6px 9px;
               color: #92400e;
               font-size: .72rem;
               font-weight: 750;
               border-radius: 9px;
               background: #fffbeb;
          }

          .bp-contacts {
               display: grid;
               gap: 6px;
               min-width: 180px;
          }

          .bp-contact {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               max-width: 230px;
               color: #475569;
               font-size: .75rem;
               text-decoration: none;
          }

          .bp-contact:hover {
               color: var(--bp-primary);
          }

          .bp-contact span {
               overflow: hidden;
               text-overflow: ellipsis;
               white-space: nowrap;
          }

          .bp-empty-value {
               color: #94a3b8;
               font-style: italic;
          }

          .bp-status {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               padding: 7px 10px;
               font-size: .72rem;
               font-weight: 850;
               border-radius: 999px;
          }

          .bp-status::before {
               content: '';
               width: 7px;
               height: 7px;
               border-radius: 50%;
          }

          .bp-status.active {
               color: #166534;
               background: #dcfce7;
          }

          .bp-status.active::before {
               background: #aadfbd;
               box-shadow: 0 0 0 3px rgba(34, 197, 94, .14);
          }

          .bp-status.inactive {
               color: #991b1b;
               background: #fee2e2;
          }

          .bp-status.inactive::before {
               background: #ef4444;
               box-shadow: 0 0 0 3px rgba(239, 68, 68, .13);
          }

          .bp-approval {
               display: inline-flex;
               align-items: center;
               gap: 7px;
               max-width: 230px;
               padding: 7px 10px;
               font-size: .71rem;
               font-weight: 850;
               line-height: 1.35;
               border-radius: 999px;
          }

          .bp-approval i {
               flex: 0 0 auto;
          }

          .bp-approval.pending {
               color: #92400e;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .bp-approval.approved {
               color: #97e4b4;
               border: 1px solid #bbf7d0;
               background: #f0fdf4;
          }

          .bp-approval.rejected {
               color: #991b1b;
               border: 1px solid #fecaca;
               background: #fff7f7;
          }

          .bp-approval.draft {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #f8fafc;
          }

          .bp-actions {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 7px;
          }

          .bp-action {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 36px;
               height: 36px;
               padding: 0;
               font-size: .88rem;
               border-radius: 10px;
               transition: .18s ease;
          }

          .bp-action:hover {
               transform: translateY(-2px);
               box-shadow: 0 8px 16px rgba(15, 23, 42, .10);
          }

          .bp-action.view {
               color: #0e7490;
               border: 1px solid #a5f3fc;
               background: #ecfeff;
          }

          .bp-action.edit {
               color: #b45309;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .bp-action.delete,
          .bp-action.reject {
               color: #b91c1c;
               border: 1px solid #fecaca;
               background: #fef2f2;
          }

          .bp-action.approve {
               color: #86c99f;
               border: 1px solid #bbf7d0;
               background: #f0fdf4;
          }

          .bp-action-wide {
               width: auto;
               min-width: 86px;
               padding: 0 11px;
               gap: 6px;
               font-size: .72rem;
               font-weight: 850;
               white-space: nowrap;
          }

          .bp-action-note {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               padding: 7px 9px;
               color: #92400e;
               font-size: .69rem;
               font-weight: 800;
               border: 1px solid #fde68a;
               border-radius: 9px;
               background: #fffbeb;
          }

          .bp-actions form {
               margin: 0;
          }

          .bp-inline-form {
               display: inline-flex;
               margin: 0;
          }

          .bp-mobile-list {
               display: none;
               padding: 14px;
               background: #f8fafc;
          }

          .bp-mobile-card {
               margin-bottom: 12px;
               padding: 15px;
               border: 1px solid var(--bp-border);
               border-radius: 15px;
               background: #fff;
               box-shadow: 0 8px 20px rgba(15, 23, 42, .045);
          }

          .bp-mobile-card:last-child {
               margin-bottom: 0;
          }

          .bp-mobile-top {
               display: flex;
               align-items: flex-start;
               justify-content: space-between;
               gap: 12px;
               margin-bottom: 13px;
          }

          .bp-mobile-name {
               margin: 7px 0 0;
               color: var(--bp-text);
               font-size: .94rem;
               font-weight: 850;
          }

          .bp-mobile-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 10px;
               margin-bottom: 13px;
          }

          .bp-mobile-item {
               min-width: 0;
               padding: 10px;
               border-radius: 11px;
               background: #f8fafc;
          }

          .bp-mobile-item.full {
               grid-column: 1 / -1;
          }

          .bp-mobile-label {
               display: block;
               margin-bottom: 5px;
               color: #94a3b8;
               font-size: .63rem;
               font-weight: 850;
               letter-spacing: .07em;
               text-transform: uppercase;
          }

          .bp-mobile-value {
               color: #334155;
               font-size: .76rem;
               line-height: 1.5;
               word-break: break-word;
          }

          .bp-mobile-actions {
               display: grid;
               grid-template-columns: repeat(auto-fit, minmax(105px, 1fr));
               gap: 8px;
          }

          .bp-mobile-action {
               display: inline-flex;
               width: 100%;
               height: auto;
               align-items: center;
               justify-content: center;
               gap: 6px;
               min-height: 38px;
               padding: 8px;
               font-size: .72rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 10px;
          }

          .bp-empty-state {
               padding: 64px 20px;
               text-align: center;
          }

          .bp-empty-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 72px;
               height: 72px;
               margin-bottom: 15px;
               color: var(--bp-primary);
               font-size: 1.8rem;
               border-radius: 22px;
               background: #eff6ff;
          }

          .bp-empty-state h3 {
               margin: 0 0 7px;
               color: var(--bp-text);
               font-size: 1.05rem;
               font-weight: 850;
          }

          .bp-empty-state p {
               max-width: 460px;
               margin: 0 auto 15px;
               color: var(--bp-muted);
               font-size: .8rem;
               line-height: 1.6;
          }

          .bp-list-footer {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               padding: 14px 20px;
               border-top: 1px solid var(--bp-border);
               background: #fff;
          }

          .bp-result-info {
               color: var(--bp-muted);
               font-size: .75rem;
          }

          .branches-page .pagination {
               margin: 0;
          }

          .branches-page .pagination .page-link {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 35px;
               height: 35px;
               margin: 0 2px;
               color: #475569;
               font-size: .75rem;
               font-weight: 750;
               border-color: var(--bp-border);
               border-radius: 9px !important;
          }

          .branches-page .pagination .page-item.active .page-link {
               color: #fff;
               border-color: var(--bp-primary);
               background: var(--bp-primary);
          }

          .bp-dialog-backdrop {
               position: fixed;
               inset: 0;
               z-index: 1080;
               display: none;
               align-items: center;
               justify-content: center;
               padding: 18px;
               background: rgba(15, 23, 42, .56);
               backdrop-filter: blur(4px);
          }

          .bp-dialog-backdrop.show {
               display: flex;
          }

          .bp-dialog {
               width: min(100%, 460px);
               padding: 28px;
               text-align: center;
               border-radius: 22px;
               background: #fff;
               box-shadow: 0 30px 90px rgba(15, 23, 42, .28);
               animation: bpDialogIn .18s ease-out;
          }

          @keyframes bpDialogIn {
               from {
                    opacity: 0;
                    transform: translateY(10px) scale(.98);
               }

               to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
               }
          }

          .bp-dialog-icon {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 64px;
               height: 64px;
               margin-bottom: 14px;
               color: #b91c1c;
               font-size: 1.45rem;
               border-radius: 19px;
               background: #fee2e2;
          }

          .bp-dialog h2 {
               margin: 0 0 8px;
               color: var(--bp-text);
               font-size: 1.2rem;
               font-weight: 850;
          }

          .bp-dialog p {
               margin: 0 0 8px;
               color: var(--bp-muted);
               font-size: .82rem;
               line-height: 1.6;
          }

          .bp-dialog-note {
               display: flex;
               align-items: flex-start;
               gap: 8px;
               margin: 15px 0 19px;
               padding: 11px 12px;
               color: #92400e;
               font-size: .74rem;
               text-align: left;
               border: 1px solid #fde68a;
               border-radius: 11px;
               background: #fffbeb;
          }

          .bp-dialog-actions {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 9px;
          }

          body.bp-modal-open {
               overflow: hidden;
          }

          @media (max-width: 1199.98px) {
               .bp-stats {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .bp-filter-grid {
                    grid-template-columns: 1fr 220px;
               }

               .bp-filter-actions {
                    grid-column: 1 / -1;
                    justify-content: flex-end;
               }

               .bp-table-wrap {
                    display: none;
               }

               .bp-mobile-list {
                    display: block;
               }
          }

          @media (max-width: 767.98px) {
               .branches-page {
                    padding: 16px 10px 30px;
               }

               .bp-hero {
                    padding: 23px 20px;
                    border-radius: 20px;
               }

               .bp-hero-grid,
               .bp-hero-main {
                    align-items: flex-start;
               }

               .bp-hero-grid {
                    flex-direction: column;
               }

               .bp-hero-icon {
                    display: none;
               }

               .bp-hero-actions,
               .bp-hero-btn {
                    width: 100%;
               }

               .bp-hero-actions {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }

               .bp-stats {
                    gap: 11px;
               }

               .bp-stat-card {
                    min-height: 118px;
                    padding: 15px;
               }

               .bp-stat-value {
                    font-size: 1.55rem;
               }

               .bp-stat-icon {
                    width: 40px;
                    height: 40px;
                    flex-basis: 40px;
               }

               .bp-filter-panel {
                    padding: 16px;
               }

               .bp-filter-grid {
                    grid-template-columns: 1fr;
               }

               .bp-filter-actions {
                    grid-column: auto;
                    display: grid;
                    grid-template-columns: 1fr auto;
               }

               .bp-list-heading,
               .bp-list-footer {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .bp-list-heading {
                    padding: 16px;
               }

               .bp-list-footer {
                    padding: 14px 16px;
               }

               .bp-mobile-list {
                    padding: 10px;
               }
          }

          @media (max-width: 479.98px) {
               .bp-stats {
                    grid-template-columns: 1fr;
               }

               .bp-hero-actions {
                    grid-template-columns: 1fr;
               }

               .bp-mobile-grid {
                    grid-template-columns: 1fr;
               }

               .bp-mobile-item.full {
                    grid-column: auto;
               }

               .bp-mobile-actions {
                    grid-template-columns: 1fr;
               }

               .bp-dialog-actions {
                    grid-template-columns: 1fr;
               }
          }

          /*
                    |--------------------------------------------------------------------------
                    | NAVY–TEAL THEME OVERRIDE
                    |--------------------------------------------------------------------------
                    */
          .branches-page {
               --bp-primary: #0f766e;
               --bp-primary-dark: #115e59;
               --bp-sky: #14b8a6;
               --bp-success: #059669;
               --bp-danger: #e11d48;
               --bp-warning: #d97706;
               --bp-text: #0f172a;
               --bp-muted: #64748b;
               --bp-border: #dbe4e8;
               --bp-soft: #f8fafc;
               --bp-white: #ffffff;
               width: 100%;
               padding: 22px 24px 42px;
               background:
                    radial-gradient(circle at 100% 0, rgba(20, 184, 166, .13), transparent 30%),
                    radial-gradient(circle at 0 100%, rgba(79, 70, 229, .07), transparent 26%),
                    linear-gradient(180deg, #f0fdfa 0%, #f8fafc 47%, #f1f5f9 100%);
          }

          .bp-hero {
               border-color: rgba(255, 255, 255, .16);
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .20), transparent 22%),
                    radial-gradient(circle at 72% 110%, rgba(45, 212, 191, .16), transparent 35%),
                    linear-gradient(135deg, #0f172a 0%, #134e4a 48%, #0f766e 77%, #14b8a6 100%);
               box-shadow: 0 24px 58px rgba(15, 118, 110, .23);
          }

          .bp-hero-btn.primary {
               color: #115e59;
               border-color: #ffffff;
               background: #ffffff;
               box-shadow: 0 11px 25px rgba(15, 23, 42, .18);
          }

          .bp-hero-btn.primary:hover {
               color: #134e4a;
               background: #ecfdf5;
          }

          .bp-hero-btn.secondary:hover {
               background: rgba(255, 255, 255, .20);
          }

          .bp-stat-card,
          .bp-panel {
               border-color: rgba(203, 213, 225, .82);
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 14px 36px rgba(15, 23, 42, .065);
          }

          .bp-stat-card:hover {
               border-color: #99f6e4;
               box-shadow: 0 19px 42px rgba(15, 118, 110, .12);
          }

          .bp-section-title i,
          .bp-input-wrap>i {
               color: #0f766e;
          }

          .bp-chip {
               color: #115e59;
               border-color: #99f6e4;
               background: #f0fdfa;
          }

          .bp-control:focus {
               border-color: #2dd4bf;
               box-shadow: 0 0 0 4px rgba(20, 184, 166, .12);
          }

          .bp-button.primary {
               border-color: #0f766e;
               background: linear-gradient(135deg, #0f766e, #115e59);
               box-shadow: 0 9px 20px rgba(15, 118, 110, .24);
          }

          .bp-button.primary:hover {
               border-color: #134e4a;
               background: linear-gradient(135deg, #115e59, #134e4a);
               box-shadow: 0 12px 24px rgba(15, 118, 110, .28);
          }

          .bp-button.light:hover {
               border-color: #99f6e4;
               background: #f0fdfa;
          }

          .bp-list-heading {
               background:
                    linear-gradient(90deg, rgba(240, 253, 250, .92), rgba(255, 255, 255, .98));
          }

          .bp-table thead th {
               color: #475569;
               border-bottom-color: #dbe4e8;
               background: #f0fdfa;
          }

          .bp-table tbody tr:hover {
               background: #f7fffd;
          }

          .bp-number {
               color: #334155;
               background: #f1f5f9;
          }

          .bp-code {
               color: #4338ca;
               border-color: #c7d2fe;
               background: #eef2ff;
               box-shadow: inset 0 0 0 1px rgba(79, 70, 229, .03);
          }

          .bp-manager-avatar {
               background: linear-gradient(135deg, #4f46e5, #06b6d4);
               box-shadow: 0 8px 18px rgba(79, 70, 229, .20);
          }

          .bp-status.active {
               color: #047857;
               background: #d1fae5;
          }

          .bp-status.inactive {
               color: #be123c;
               background: #ffe4e6;
          }

          .bp-approval.pending {
               color: #92400e;
               border-color: #fcd34d;
               background: #fffbeb;
          }

          .bp-approval.approved {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .bp-approval.rejected {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .bp-approval.draft {
               color: #475569;
               border-color: #cbd5e1;
               background: #f8fafc;
          }

          .bp-action.view {
               color: #0f766e;
               border-color: #99f6e4;
               background: #f0fdfa;
          }

          .bp-action.edit {
               color: #a16207;
               border-color: #fde68a;
               background: #fffbeb;
          }

          .bp-action.delete,
          .bp-action.reject {
               color: #be123c;
               border-color: #fecdd3;
               background: #fff1f2;
          }

          .bp-action.approve {
               color: #047857;
               border-color: #a7f3d0;
               background: #ecfdf5;
          }

          .bp-action:hover {
               box-shadow: 0 9px 18px rgba(15, 118, 110, .13);
          }

          .bp-mobile-card {
               border-color: #dbe4e8;
               box-shadow: 0 9px 22px rgba(15, 23, 42, .05);
          }

          .bp-mobile-item {
               background: #f8fafc;
          }

          .branches-page .pagination .page-item.active .page-link {
               border-color: #0f766e;
               background: #0f766e;
          }

          .branches-page .pagination .page-link:hover {
               color: #115e59;
               border-color: #99f6e4;
               background: #f0fdfa;
          }

          .bp-dialog {
               border: 1px solid #dbe4e8;
          }

          @media (max-width: 767.98px) {
               .branches-page {
                    padding: 14px 10px 30px;
               }

               .bp-hero {
                    padding: 24px 20px;
               }
          }
     </style>

     @php
          $currentUser = auth()->user();

          $isSuperAdmin = $currentUser?->hasRole('super_admin') ?? false;
          $isDirektur = $currentUser?->hasRole('direktur_utama') ?? false;
          $isOperasional = $currentUser?->hasRole('admin_operasional') ?? false;
          $isAuditor = $currentUser?->hasRole('auditor_internal') ?? false;

          $currentUserId = (int) ($currentUser?->getKey() ?? 0);

          /*
           * Role approval dibuat eksplisit agar tidak bergantung pada array
           * getRoleNames(). Direktur hanya memproses tahap direktur_utama,
           * Auditor hanya memproses tahap auditor_internal, dan Super Admin
           * dapat memproses tahap yang sedang aktif.
           */
          $approvalRoleLabels = [
              'super_admin' => 'Super Admin',
              'direktur_utama' => 'Direktur Utama',
              'admin_operasional' => 'Admin Operasional',
              'auditor_internal' => 'Auditor Internal',
          ];

          $hasApproveRoute = \Illuminate\Support\Facades\Route::has('branches.approve');
          $hasRejectRoute = \Illuminate\Support\Facades\Route::has('branches.reject');

          // Semua role di atas boleh melihat daftar dan detail.
          $canViewBranches = $isSuperAdmin || $isDirektur || $isOperasional || $isAuditor;

          // Hanya Super Admin dan Admin Operasional yang boleh mengubah data.
          $canManageBranches = $isSuperAdmin || $isOperasional;

          // Recycle bin hanya untuk Super Admin.
          $canManageTrash = $isSuperAdmin;
          $hasTrashRoute = $canManageTrash && \Illuminate\Support\Facades\Route::has('branches.trash');

          $filterIsActive = request()->filled('search') || request()->filled('status');
     @endphp

     <div class="branches-page">
          <div class="branches-shell">
               @if (session('success'))
                    <div class="bp-alert success" data-dismissible-alert role="alert">
                         <i class="bi bi-check-circle-fill bp-alert-icon"></i>
                         <div>
                              <div class="bp-alert-title">Berhasil</div>
                              <div class="bp-alert-message">{{ session('success') }}</div>
                         </div>
                         <button type="button" class="bp-alert-close" data-close-alert aria-label="Tutup notifikasi">
                              <i class="bi bi-x-lg"></i>
                         </button>
                    </div>
               @endif

               @if (session('error'))
                    <div class="bp-alert error" data-dismissible-alert role="alert">
                         <i class="bi bi-exclamation-octagon-fill bp-alert-icon"></i>
                         <div>
                              <div class="bp-alert-title">Terjadi kesalahan</div>
                              <div class="bp-alert-message">{{ session('error') }}</div>
                         </div>
                         <button type="button" class="bp-alert-close" data-close-alert aria-label="Tutup notifikasi">
                              <i class="bi bi-x-lg"></i>
                         </button>
                    </div>
               @endif

               <header class="bp-hero">
                    <div class="bp-hero-grid">
                         <div class="bp-hero-main">
                              <div class="bp-hero-icon">
                                   <i class="bi bi-buildings-fill"></i>
                              </div>
                              <div>
                                   <div class="bp-eyebrow">
                                        <i class="bi bi-grid-fill"></i>
                                        Master Data
                                   </div>
                                   <h1>Branch Management</h1>
                                   <p>
                                        Kelola identitas cabang, penanggung jawab, informasi kontak, status operasional,
                                        serta proses persetujuan berjenjang sesuai role pengguna.
                                   </p>
                              </div>
                         </div>

                         @if ($hasTrashRoute || $canManageBranches)
                              <div class="bp-hero-actions">
                                   @if ($hasTrashRoute)
                                        <a href="{{ route('branches.trash') }}" class="bp-hero-btn secondary">
                                             <i class="bi bi-trash3-fill"></i>
                                             Sampah
                                             @if (($stats['deleted_branches'] ?? 0) > 0)
                                                  <span>({{ number_format($stats['deleted_branches']) }})</span>
                                             @endif
                                        </a>
                                   @endif

                                   @if ($canManageBranches && \Illuminate\Support\Facades\Route::has('branches.create'))
                                        <a href="{{ route('branches.create') }}" class="bp-hero-btn primary">
                                             <i class="bi bi-plus-lg"></i>
                                             Tambah Cabang
                                        </a>
                                   @endif
                              </div>
                         @endif
                    </div>
               </header>

               <section class="bp-stats" aria-label="Statistik cabang">
                    <article class="bp-stat-card" style="--stat-color:#4f46e5; --stat-soft:#e0e7ff;">
                         <div class="bp-stat-top">
                              <div>
                                   <div class="bp-stat-label">Total Cabang</div>
                                   <h2 class="bp-stat-value">{{ number_format($stats['total_branches'] ?? 0) }}</h2>
                              </div>
                              <div class="bp-stat-icon"><i class="bi bi-buildings"></i></div>
                         </div>
                         <p class="bp-stat-note">Seluruh cabang tersimpan</p>
                    </article>

                    <article class="bp-stat-card" style="--stat-color:#059669; --stat-soft:#d1fae5;">
                         <div class="bp-stat-top">
                              <div>
                                   <div class="bp-stat-label">Cabang Aktif</div>
                                   <h2 class="bp-stat-value">{{ number_format($stats['active_branches'] ?? 0) }}</h2>
                              </div>
                              <div class="bp-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                         </div>
                         <p class="bp-stat-note">Beroperasi saat ini</p>
                    </article>

                    <article class="bp-stat-card" style="--stat-color:#e11d48; --stat-soft:#ffe4e6;">
                         <div class="bp-stat-top">
                              <div>
                                   <div class="bp-stat-label">Cabang Nonaktif</div>
                                   <h2 class="bp-stat-value">{{ number_format($stats['inactive_branches'] ?? 0) }}</h2>
                              </div>
                              <div class="bp-stat-icon"><i class="bi bi-pause-circle-fill"></i></div>
                         </div>
                         <p class="bp-stat-note">Tidak sedang beroperasi</p>
                    </article>

                    <article class="bp-stat-card" style="--stat-color:#0891b2; --stat-soft:#cffafe;">
                         <div class="bp-stat-top">
                              <div>
                                   <div class="bp-stat-label">Kepala Cabang</div>
                                   <h2 class="bp-stat-value">{{ number_format($stats['manager_count'] ?? 0) }}</h2>
                              </div>
                              <div class="bp-stat-icon"><i class="bi bi-person-badge-fill"></i></div>
                         </div>
                         <p class="bp-stat-note">Penanggung jawab ditugaskan</p>
                    </article>
               </section>

               <section class="bp-panel bp-filter-panel">
                    <div class="bp-panel-heading">
                         <div>
                              <h2 class="bp-section-title">
                                   <i class="bi bi-funnel-fill"></i>
                                   Filter Data
                              </h2>
                              <p class="bp-section-copy">Cari berdasarkan kode, nama, email, atau status operasional.</p>
                         </div>

                         @if ($filterIsActive)
                              <span class="bp-chip">
                                   <i class="bi bi-filter-circle-fill"></i>
                                   Filter aktif
                              </span>
                         @endif
                    </div>

                    <form method="GET" action="{{ route('branches.index') }}">
                         <div class="bp-filter-grid">
                              <div>
                                   <label for="search" class="bp-label">Pencarian Cabang</label>
                                   <div class="bp-input-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="search" id="search" name="search" class="bp-control"
                                             value="{{ request('search') }}" placeholder="Kode, nama, atau email cabang"
                                             autocomplete="off">
                                   </div>
                              </div>

                              <div>
                                   <label for="status" class="bp-label">Status Operasional</label>
                                   <select id="status" name="status" class="bp-control">
                                        <option value="">Semua Status</option>
                                        <option value="1" @selected(request('status') === '1')>Aktif</option>
                                        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
                                   </select>
                              </div>

                              <div class="bp-filter-actions">
                                   <button type="submit" class="bp-button primary">
                                        <i class="bi bi-search"></i>
                                        Terapkan
                                   </button>

                                   @if ($filterIsActive)
                                        <a href="{{ route('branches.index') }}" class="bp-button light"
                                             title="Reset filter">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             <span class="d-none d-sm-inline">Reset</span>
                                        </a>
                                   @endif
                              </div>
                         </div>
                    </form>
               </section>

               <section class="bp-panel">
                    <div class="bp-list-heading">
                         <div>
                              <h2 class="bp-section-title">
                                   <i class="bi bi-list-ul"></i>
                                   Daftar Cabang
                              </h2>
                              <p class="bp-section-copy">Informasi cabang perusahaan yang tersimpan di dalam sistem.</p>
                         </div>
                         <span class="bp-chip">
                              <i class="bi bi-database-fill"></i>
                              {{ number_format($branches->total()) }} data
                         </span>
                    </div>

                    @if ($branches->count() > 0)
                         <div class="bp-table-wrap">
                              <table class="bp-table">
                                   <thead>
                                        <tr>
                                             <th class="text-center" style="width:64px;">No.</th>
                                             <th>Kode</th>
                                             <th>Informasi Cabang</th>
                                             <th>Kepala Cabang</th>
                                             <th>Kontak</th>
                                             <th>Status</th>
                                             <th>Persetujuan</th>
                                             <th class="text-center" style="width:330px;">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @foreach ($branches as $branch)
                                             @php
                                                  $approvalStatus = $branch->approval_status ?? 'approved';
                                                  $pendingApprovalRole = $branch->pending_approval_role ?? null;
                                                  $pendingApprovalRoleLabel =
                                                      $approvalRoleLabels[$pendingApprovalRole] ??
                                                      ($pendingApprovalRole
                                                          ? str($pendingApprovalRole)->replace('_', ' ')->title()
                                                          : null);

                                                  $roleMatchesCurrentStep =
                                                      ($isDirektur && $pendingApprovalRole === 'direktur_utama') ||
                                                      ($isAuditor && $pendingApprovalRole === 'auditor_internal');

                                                  $isRequestSubmitter =
                                                      $currentUserId > 0 &&
                                                      (int) $branch->submitted_by === $currentUserId;

                                                  $isPreviousApprover =
                                                      $currentUserId > 0 &&
                                                      $branch->last_approved_by !== null &&
                                                      (int) $branch->last_approved_by === $currentUserId;

                                                  $canApproveCurrentStep =
                                                      $approvalStatus === 'pending' &&
                                                      ($isSuperAdmin || $roleMatchesCurrentStep) &&
                                                      !$isRequestSubmitter &&
                                                      !$isPreviousApprover &&
                                                      $hasApproveRoute &&
                                                      $hasRejectRoute;

                                                  $waitingForMyApproval =
                                                      $approvalStatus === 'pending' &&
                                                      ($isSuperAdmin || $roleMatchesCurrentStep);

                                                  // Ketika masih pending, data dikunci dari edit/hapus.
                                                  $canModifyThisBranch =
                                                      $canManageBranches && $approvalStatus !== 'pending';
                                             @endphp
                                             <tr>
                                                  <td class="text-center">
                                                       <span
                                                            class="bp-number">{{ ($branches->firstItem() ?? 1) + $loop->index }}</span>
                                                  </td>
                                                  <td><span class="bp-code">{{ $branch->branch_code }}</span></td>
                                                  <td>
                                                       <div class="bp-branch-name">{{ $branch->branch_name }}</div>
                                                       <div class="bp-address">
                                                            <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                                                            <span>{{ $branch->address ?: 'Alamat belum tersedia' }}</span>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       @if ($branch->manager)
                                                            <div class="bp-manager">
                                                                 <div class="bp-manager-avatar">
                                                                      {{ mb_strtoupper(mb_substr($branch->manager->name, 0, 1)) }}
                                                                 </div>
                                                                 <div>
                                                                      <div class="bp-manager-name">
                                                                           {{ $branch->manager->name }}</div>
                                                                      <div class="bp-manager-role">Kepala Cabang</div>
                                                                 </div>
                                                            </div>
                                                       @else
                                                            <span class="bp-unassigned">
                                                                 <i class="bi bi-person-exclamation"></i>
                                                                 Belum ditentukan
                                                            </span>
                                                       @endif
                                                  </td>
                                                  <td>
                                                       <div class="bp-contacts">
                                                            @if ($branch->phone)
                                                                 <a href="tel:{{ $branch->phone }}" class="bp-contact">
                                                                      <i class="bi bi-telephone-fill text-success"></i>
                                                                      <span>{{ $branch->phone }}</span>
                                                                 </a>
                                                            @else
                                                                 <span class="bp-empty-value">Telepon belum tersedia</span>
                                                            @endif

                                                            @if ($branch->email)
                                                                 <a href="mailto:{{ $branch->email }}" class="bp-contact">
                                                                      <i class="bi bi-envelope-fill text-primary"></i>
                                                                      <span>{{ $branch->email }}</span>
                                                                 </a>
                                                            @else
                                                                 <span class="bp-empty-value">Email belum tersedia</span>
                                                            @endif
                                                       </div>
                                                  </td>
                                                  <td>
                                                       @if ((int) $branch->status === 1)
                                                            <span class="bp-status active">Aktif</span>
                                                       @else
                                                            <span class="bp-status inactive">Nonaktif</span>
                                                       @endif
                                                  </td>
                                                  <td>
                                                       @switch($approvalStatus)
                                                            @case('pending')
                                                                 <span class="bp-approval pending">
                                                                      <i class="bi bi-hourglass-split"></i>
                                                                      Menunggu Persetujuan
                                                                      {{ $pendingApprovalRoleLabel ?? 'Role Terkait' }}
                                                                 </span>
                                                            @break

                                                            @case('rejected')
                                                                 <span class="bp-approval rejected">
                                                                      <i class="bi bi-x-octagon-fill"></i>
                                                                      Ditolak
                                                                 </span>
                                                            @break

                                                            @case('draft')
                                                                 <span class="bp-approval draft">
                                                                      <i class="bi bi-file-earmark-text-fill"></i>
                                                                      Draft
                                                                 </span>
                                                            @break

                                                            @default
                                                                 <span class="bp-approval approved">
                                                                      <i class="bi bi-patch-check-fill"></i>
                                                                      Disetujui
                                                                 </span>
                                                       @endswitch
                                                  </td>
                                                  <td class="text-center">
                                                       <div class="bp-actions">
                                                            <a href="{{ route('branches.show', $branch->id) }}"
                                                                 class="bp-action view" title="Lihat detail"
                                                                 aria-label="Lihat detail {{ $branch->branch_name }}">
                                                                 <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                            @if ($canApproveCurrentStep)
                                                                 <form method="POST"
                                                                      action="{{ route('branches.approve', $branch->id) }}"
                                                                      class="bp-inline-form"
                                                                      onsubmit="return confirm('Setujui pengajuan cabang {{ addslashes($branch->branch_name) }}?')">
                                                                      @csrf
                                                                      @method('PATCH')
                                                                      <button type="submit"
                                                                           class="bp-action bp-action-wide approve"
                                                                           title="Setujui tahap {{ $pendingApprovalRoleLabel }}"
                                                                           aria-label="Setujui {{ $branch->branch_name }}">
                                                                           <i class="bi bi-check-lg"></i>
                                                                           <span>Setujui</span>
                                                                      </button>
                                                                 </form>

                                                                 <button type="button"
                                                                      class="bp-action bp-action-wide reject"
                                                                      data-reject-trigger
                                                                      data-reject-url="{{ route('branches.reject', $branch->id) }}"
                                                                      data-branch-name="{{ $branch->branch_name }}"
                                                                      data-approval-role="{{ $pendingApprovalRoleLabel }}"
                                                                      title="Tolak pengajuan"
                                                                      aria-label="Tolak {{ $branch->branch_name }}">
                                                                      <i class="bi bi-x-lg"></i>
                                                                      <span>Tolak</span>
                                                                 </button>
                                                            @elseif ($waitingForMyApproval && ($isRequestSubmitter || $isPreviousApprover))
                                                                 <span class="bp-action-note"
                                                                      title="Approval harus dilakukan oleh pengguna berbeda">
                                                                      <i class="bi bi-person-lock"></i>
                                                                      Pengguna lain
                                                                 </span>
                                                            @endif

                                                            @if ($canModifyThisBranch)
                                                                 <a href="{{ route('branches.edit', $branch->id) }}"
                                                                      class="bp-action edit" title="Edit cabang"
                                                                      aria-label="Edit {{ $branch->branch_name }}">
                                                                      <i class="bi bi-pencil-square"></i>
                                                                 </a>
                                                                 <button type="button" class="bp-action delete"
                                                                      data-delete-trigger
                                                                      data-delete-url="{{ route('branches.destroy', $branch->id) }}"
                                                                      data-branch-name="{{ $branch->branch_name }}"
                                                                      title="Pindahkan ke sampah"
                                                                      aria-label="Pindahkan {{ $branch->branch_name }} ke sampah">
                                                                      <i class="bi bi-trash3-fill"></i>
                                                                 </button>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @endforeach
                                   </tbody>
                              </table>
                         </div>

                         <div class="bp-mobile-list">
                              @foreach ($branches as $branch)
                                   @php
                                        $approvalStatus = $branch->approval_status ?? 'approved';
                                        $pendingApprovalRole = $branch->pending_approval_role ?? null;
                                        $pendingApprovalRoleLabel =
                                            $approvalRoleLabels[$pendingApprovalRole] ??
                                            ($pendingApprovalRole
                                                ? str($pendingApprovalRole)->replace('_', ' ')->title()
                                                : null);
                                        $roleMatchesCurrentStep =
                                            ($isDirektur && $pendingApprovalRole === 'direktur_utama') ||
                                            ($isAuditor && $pendingApprovalRole === 'auditor_internal');

                                        $isRequestSubmitter =
                                            $currentUserId > 0 && (int) $branch->submitted_by === $currentUserId;

                                        $isPreviousApprover =
                                            $currentUserId > 0 &&
                                            $branch->last_approved_by !== null &&
                                            (int) $branch->last_approved_by === $currentUserId;

                                        $canApproveCurrentStep =
                                            $approvalStatus === 'pending' &&
                                            ($isSuperAdmin || $roleMatchesCurrentStep) &&
                                            !$isRequestSubmitter &&
                                            !$isPreviousApprover &&
                                            $hasApproveRoute &&
                                            $hasRejectRoute;

                                        $waitingForMyApproval =
                                            $approvalStatus === 'pending' && ($isSuperAdmin || $roleMatchesCurrentStep);

                                        $canModifyThisBranch = $canManageBranches && $approvalStatus !== 'pending';
                                   @endphp
                                   <article class="bp-mobile-card">
                                        <div class="bp-mobile-top">
                                             <div>
                                                  <span class="bp-code">{{ $branch->branch_code }}</span>
                                                  <h3 class="bp-mobile-name">{{ $branch->branch_name }}</h3>
                                             </div>
                                             @if ((int) $branch->status === 1)
                                                  <span class="bp-status active">Aktif</span>
                                             @else
                                                  <span class="bp-status inactive">Nonaktif</span>
                                             @endif
                                        </div>

                                        <div class="bp-mobile-grid">
                                             <div class="bp-mobile-item full">
                                                  <span class="bp-mobile-label">Alamat</span>
                                                  <div class="bp-mobile-value">
                                                       {{ $branch->address ?: 'Alamat belum tersedia' }}</div>
                                             </div>
                                             <div class="bp-mobile-item">
                                                  <span class="bp-mobile-label">Kepala Cabang</span>
                                                  <div class="bp-mobile-value">
                                                       {{ $branch->manager?->name ?? 'Belum ditentukan' }}
                                                  </div>
                                             </div>
                                             <div class="bp-mobile-item">
                                                  <span class="bp-mobile-label">Telepon</span>
                                                  <div class="bp-mobile-value">{{ $branch->phone ?: '-' }}</div>
                                             </div>
                                             <div class="bp-mobile-item full">
                                                  <span class="bp-mobile-label">Email</span>
                                                  <div class="bp-mobile-value">{{ $branch->email ?: '-' }}</div>
                                             </div>
                                             <div class="bp-mobile-item full">
                                                  <span class="bp-mobile-label">Status Persetujuan</span>
                                                  <div class="bp-mobile-value">
                                                       @switch($approvalStatus)
                                                            @case('pending')
                                                                 <span class="bp-approval pending">
                                                                      <i class="bi bi-hourglass-split"></i>
                                                                      Menunggu Persetujuan
                                                                      {{ $pendingApprovalRoleLabel ?? 'Role Terkait' }}
                                                                 </span>
                                                            @break

                                                            @case('rejected')
                                                                 <span class="bp-approval rejected">
                                                                      <i class="bi bi-x-octagon-fill"></i> Ditolak
                                                                 </span>
                                                            @break

                                                            @case('draft')
                                                                 <span class="bp-approval draft">
                                                                      <i class="bi bi-file-earmark-text-fill"></i> Draft
                                                                 </span>
                                                            @break

                                                            @default
                                                                 <span class="bp-approval approved">
                                                                      <i class="bi bi-patch-check-fill"></i> Disetujui
                                                                 </span>
                                                       @endswitch
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="bp-mobile-actions">
                                             <a href="{{ route('branches.show', $branch->id) }}"
                                                  class="bp-mobile-action bp-action view">
                                                  <i class="bi bi-eye-fill"></i> Detail
                                             </a>
                                             @if ($canApproveCurrentStep)
                                                  <form method="POST"
                                                       action="{{ route('branches.approve', $branch->id) }}"
                                                       onsubmit="return confirm('Setujui pengajuan cabang {{ addslashes($branch->branch_name) }}?')">
                                                       @csrf
                                                       @method('PATCH')
                                                       <button type="submit" class="bp-mobile-action bp-action approve">
                                                            <i class="bi bi-check-lg"></i> Setujui
                                                       </button>
                                                  </form>

                                                  <button type="button" class="bp-mobile-action bp-action reject"
                                                       data-reject-trigger
                                                       data-reject-url="{{ route('branches.reject', $branch->id) }}"
                                                       data-branch-name="{{ $branch->branch_name }}"
                                                       data-approval-role="{{ $pendingApprovalRoleLabel }}">
                                                       <i class="bi bi-x-lg"></i> Tolak
                                                  </button>
                                             @elseif ($waitingForMyApproval && ($isRequestSubmitter || $isPreviousApprover))
                                                  <span class="bp-action-note">
                                                       <i class="bi bi-person-lock"></i>
                                                       Harus pengguna berbeda
                                                  </span>
                                             @endif

                                             @if ($canModifyThisBranch)
                                                  <a href="{{ route('branches.edit', $branch->id) }}"
                                                       class="bp-mobile-action bp-action edit">
                                                       <i class="bi bi-pencil-square"></i> Edit
                                                  </a>
                                                  <button type="button" class="bp-mobile-action bp-action delete"
                                                       data-delete-trigger
                                                       data-delete-url="{{ route('branches.destroy', $branch->id) }}"
                                                       data-branch-name="{{ $branch->branch_name }}">
                                                       <i class="bi bi-trash3-fill"></i> Hapus
                                                  </button>
                                             @endif
                                        </div>
                                   </article>
                              @endforeach
                         </div>
                    @else
                         <div class="bp-empty-state">
                              <div class="bp-empty-icon"><i class="bi bi-building-x"></i></div>
                              @if ($filterIsActive)
                                   <h3>Data cabang tidak ditemukan</h3>
                                   <p>Tidak ada cabang yang sesuai dengan kata kunci atau status yang dipilih. Reset filter
                                        untuk menampilkan seluruh data.</p>
                                   <a href="{{ route('branches.index') }}" class="bp-button light">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        Reset Filter
                                   </a>
                              @else
                                   <h3>Belum ada data cabang</h3>
                                   @if ($canManageBranches)
                                        <p>Tambahkan cabang pertama untuk mulai mengelola struktur operasional perusahaan.
                                        </p>
                                        <a href="{{ route('branches.create') }}" class="bp-button primary">
                                             <i class="bi bi-plus-lg"></i>
                                             Tambah Cabang
                                        </a>
                                   @else
                                        <p>Belum ada data cabang yang dapat ditampilkan.</p>
                                   @endif
                              @endif
                         </div>
                    @endif

                    @if ($branches->total() > 0)
                         <footer class="bp-list-footer">
                              <div class="bp-result-info">
                                   Menampilkan
                                   <strong>{{ $branches->firstItem() }}</strong>–<strong>{{ $branches->lastItem() }}</strong>
                                   dari <strong>{{ $branches->total() }}</strong> cabang
                              </div>
                              @if ($branches->hasPages())
                                   <div>{{ $branches->withQueryString()->links() }}</div>
                              @endif
                         </footer>
                    @endif
               </section>
          </div>
     </div>

     <div class="bp-dialog-backdrop" id="rejectBranchDialog" aria-hidden="true">
          <div class="bp-dialog" role="dialog" aria-modal="true" aria-labelledby="rejectDialogTitle">
               <div class="bp-dialog-icon" style="color:#b91c1c;background:#fee2e2;">
                    <i class="bi bi-x-octagon-fill"></i>
               </div>
               <h2 id="rejectDialogTitle">Tolak Pengajuan Cabang?</h2>
               <p>
                    Pengajuan cabang <strong id="rejectBranchName" class="text-dark">-</strong>
                    akan dikembalikan kepada pengaju.
               </p>

               <form id="rejectBranchForm" method="POST" action="">
                    @csrf
                    @method('PATCH')

                    <div style="margin:16px 0;text-align:left;">
                         <label for="rejectionNote" class="bp-label">
                              Alasan Penolakan <span class="text-danger">*</span>
                         </label>
                         <textarea id="rejectionNote" name="rejection_note" class="bp-control" rows="4" maxlength="1000" required
                              placeholder="Jelaskan alasan pengajuan ditolak"></textarea>
                         <small style="display:block;margin-top:6px;color:#64748b;font-size:.7rem;">
                              Alasan akan tersimpan pada riwayat persetujuan.
                         </small>
                    </div>

                    <div class="bp-dialog-actions">
                         <button type="button" class="bp-button light" id="cancelRejectButton">
                              <i class="bi bi-x-lg"></i>
                              Batal
                         </button>
                         <button type="submit" class="bp-button primary"
                              style="background:#dc2626;border-color:#dc2626;box-shadow:none;">
                              <i class="bi bi-x-octagon-fill"></i>
                              Tolak Pengajuan
                         </button>
                    </div>
               </form>
          </div>
     </div>

     <div class="bp-dialog-backdrop" id="deleteBranchDialog" aria-hidden="true">
          <div class="bp-dialog" role="dialog" aria-modal="true" aria-labelledby="deleteDialogTitle">
               <div class="bp-dialog-icon"><i class="bi bi-trash3-fill"></i></div>
               <h2 id="deleteDialogTitle">Pindahkan Cabang ke Sampah?</h2>
               <p>
                    Data cabang <strong id="deleteBranchName" class="text-dark">-</strong> akan dinonaktifkan dari daftar
                    utama.
               </p>
               <div class="bp-dialog-note">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <span>Data tidak langsung hilang permanen dan masih dapat dipulihkan melalui halaman Sampah.</span>
               </div>

               <form id="deleteBranchForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="bp-dialog-actions">
                         <button type="button" class="bp-button light" id="cancelDeleteButton">
                              <i class="bi bi-x-lg"></i>
                              Batal
                         </button>
                         <button type="submit" class="bp-button primary"
                              style="background:#dc2626;border-color:#dc2626;box-shadow:none;">
                              <i class="bi bi-trash3-fill"></i>
                              Pindahkan
                         </button>
                    </div>
               </form>
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', function() {
               document.querySelectorAll('[data-close-alert]').forEach(function(button) {
                    button.addEventListener('click', function() {
                         const alert = button.closest('[data-dismissible-alert]');
                         if (alert) alert.remove();
                    });
               });

               const rejectDialog = document.getElementById('rejectBranchDialog');
               const rejectForm = document.getElementById('rejectBranchForm');
               const rejectBranchName = document.getElementById('rejectBranchName');
               const rejectionNote = document.getElementById('rejectionNote');
               const cancelRejectButton = document.getElementById('cancelRejectButton');

               function closeRejectDialog() {
                    if (!rejectDialog || !rejectForm || !rejectBranchName || !rejectionNote) return;

                    rejectDialog.classList.remove('show');
                    rejectDialog.setAttribute('aria-hidden', 'true');
                    rejectForm.action = '';
                    rejectBranchName.textContent = '-';
                    rejectionNote.value = '';
                    document.body.classList.remove('bp-modal-open');
               }

               document.querySelectorAll('[data-reject-trigger]').forEach(function(trigger) {
                    trigger.addEventListener('click', function() {
                         if (!rejectDialog || !rejectForm || !rejectBranchName || !
                              rejectionNote) return;

                         rejectForm.action = trigger.dataset.rejectUrl || '';
                         rejectBranchName.textContent = trigger.dataset.branchName || '-';
                         rejectDialog.classList.add('show');
                         rejectDialog.setAttribute('aria-hidden', 'false');
                         document.body.classList.add('bp-modal-open');
                         rejectionNote.focus();
                    });
               });

               cancelRejectButton?.addEventListener('click', closeRejectDialog);

               rejectDialog?.addEventListener('click', function(event) {
                    if (event.target === rejectDialog) closeRejectDialog();
               });

               const dialog = document.getElementById('deleteBranchDialog');
               const deleteForm = document.getElementById('deleteBranchForm');
               const branchName = document.getElementById('deleteBranchName');
               const cancelButton = document.getElementById('cancelDeleteButton');

               if (!dialog || !deleteForm || !branchName || !cancelButton) return;

               function openDialog(trigger) {
                    deleteForm.action = trigger.dataset.deleteUrl || '';
                    branchName.textContent = trigger.dataset.branchName || '-';
                    dialog.classList.add('show');
                    dialog.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('bp-modal-open');
                    cancelButton.focus();
               }

               function closeDialog() {
                    dialog.classList.remove('show');
                    dialog.setAttribute('aria-hidden', 'true');
                    deleteForm.action = '';
                    branchName.textContent = '-';
                    document.body.classList.remove('bp-modal-open');
               }

               document.querySelectorAll('[data-delete-trigger]').forEach(function(trigger) {
                    trigger.addEventListener('click', function() {
                         openDialog(trigger);
                    });
               });

               cancelButton.addEventListener('click', closeDialog);

               dialog.addEventListener('click', function(event) {
                    if (event.target === dialog) closeDialog();
               });

               document.addEventListener('keydown', function(event) {
                    if (event.key !== 'Escape') return;

                    if (rejectDialog?.classList.contains('show')) {
                         closeRejectDialog();
                         return;
                    }

                    if (dialog.classList.contains('show')) {
                         closeDialog();
                    }
               });
          });
     </script>
@endsection
