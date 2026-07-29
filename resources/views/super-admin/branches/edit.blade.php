@extends('layouts.app')

@section('title', 'Edit Cabang')

@push('styles')
<style>
    .branch-edit-page {
        --be-primary: #2563eb;
        --be-primary-dark: #1d4ed8;
        --be-primary-soft: #eff6ff;
        --be-success: #16a34a;
        --be-danger: #dc2626;
        --be-warning: #d97706;
        --be-text: #0f172a;
        --be-muted: #64748b;
        --be-border: #e2e8f0;
        --be-surface: #ffffff;
        --be-background: #f8fafc;
        min-height: calc(100vh - 70px);
        color: var(--be-text);
        background:
            radial-gradient(circle at top right, rgba(37, 99, 235, .08), transparent 28%),
            radial-gradient(circle at bottom left, rgba(14, 165, 233, .06), transparent 22%),
            var(--be-background);
    }

    .branch-edit-page .edit-shell {
        max-width: 1480px;
        margin: 0 auto;
    }

    .branch-edit-page .page-hero {
        position: relative;
        overflow: hidden;
        padding: 30px 32px;
        border: 0;
        border-radius: 24px;
        color: #ffffff;
        background:
            radial-gradient(circle at 92% 10%, rgba(255, 255, 255, .22), transparent 22%),
            radial-gradient(circle at 76% 95%, rgba(255, 255, 255, .12), transparent 28%),
            linear-gradient(135deg, #1e3a8a 0%, #2563eb 52%, #0ea5e9 100%);
        box-shadow: 0 20px 45px rgba(37, 99, 235, .20);
    }

    .branch-edit-page .page-hero::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        right: -90px;
        bottom: -120px;
        border: 32px solid rgba(255, 255, 255, .10);
        border-radius: 50%;
    }

    .branch-edit-page .hero-content,
    .branch-edit-page .hero-action {
        position: relative;
        z-index: 2;
    }

    .branch-edit-page .hero-heading {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .branch-edit-page .hero-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 58px;
        border: 1px solid rgba(255, 255, 255, .35);
        border-radius: 18px;
        background: rgba(255, 255, 255, .16);
        backdrop-filter: blur(10px);
        font-size: 1.55rem;
    }

    .branch-edit-page .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        margin-bottom: .35rem;
        color: rgba(255, 255, 255, .78);
        font-size: .74rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .branch-edit-page .hero-title {
        margin: 0 0 .4rem;
        font-size: clamp(1.65rem, 3vw, 2.2rem);
        font-weight: 850;
        letter-spacing: -.035em;
    }

    .branch-edit-page .hero-description {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, .82);
        line-height: 1.65;
    }

    .branch-edit-page .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        min-height: 46px;
        padding: .75rem 1.05rem;
        border: 1px solid rgba(255, 255, 255, .55);
        border-radius: 14px;
        color: #ffffff;
        background: rgba(255, 255, 255, .12);
        backdrop-filter: blur(8px);
        font-weight: 750;
        text-decoration: none;
        transition: transform .2s ease, background-color .2s ease, color .2s ease;
    }

    .branch-edit-page .btn-back:hover {
        color: var(--be-primary-dark);
        background: #ffffff;
        transform: translateY(-2px);
    }

    .branch-edit-page .hero-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .branch-edit-page .btn-print-hero {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        min-height: 46px;
        padding: .75rem 1.05rem;
        border: 1px solid rgba(255, 255, 255, .28);
        border-radius: 14px;
        color: #1e40af;
        background: #ffffff;
        font-weight: 800;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .branch-edit-page .btn-print-hero:hover {
        color: #1e3a8a;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, .16);
    }

    .branch-edit-page .validation-alert {
        display: flex;
        gap: 14px;
        padding: 18px 20px;
        border: 1px solid #fecaca;
        border-radius: 17px;
        color: #991b1b;
        background: #fff7f7;
        box-shadow: 0 10px 24px rgba(220, 38, 38, .06);
    }

    .branch-edit-page .validation-alert > i {
        margin-top: 2px;
        font-size: 1.25rem;
    }

    .branch-edit-page .validation-alert strong {
        display: block;
        margin-bottom: 5px;
        color: #7f1d1d;
    }

    .branch-edit-page .validation-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .branch-edit-page .edit-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 350px;
        gap: 24px;
        align-items: start;
    }

    .branch-edit-page .surface-card {
        border: 1px solid rgba(226, 232, 240, .95);
        border-radius: 22px;
        background: rgba(255, 255, 255, .98);
        box-shadow: 0 16px 42px rgba(15, 23, 42, .07);
    }

    .branch-edit-page .form-card {
        overflow: hidden;
    }

    .branch-edit-page .form-section {
        padding: 25px;
        border-bottom: 1px solid var(--be-border);
    }

    .branch-edit-page .form-section:last-of-type {
        border-bottom: 0;
    }

    .branch-edit-page .section-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 22px;
    }

    .branch-edit-page .section-heading-main {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .branch-edit-page .section-icon {
        width: 43px;
        height: 43px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 43px;
        border-radius: 13px;
        color: var(--be-primary);
        background: var(--be-primary-soft);
        font-size: 1.1rem;
    }

    .branch-edit-page .section-heading h2 {
        margin: 0 0 4px;
        color: var(--be-text);
        font-size: 1.05rem;
        font-weight: 850;
    }

    .branch-edit-page .section-heading p {
        margin: 0;
        color: var(--be-muted);
        font-size: .82rem;
        line-height: 1.55;
    }

    .branch-edit-page .required-note {
        color: var(--be-muted);
        font-size: .74rem;
        white-space: nowrap;
    }

    .branch-edit-page .required-mark {
        color: var(--be-danger);
    }

    .branch-edit-page .field-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
        color: #334155;
        font-size: .8rem;
        font-weight: 750;
    }

    .branch-edit-page .field-label small {
        color: #94a3b8;
        font-size: .68rem;
        font-weight: 650;
    }

    .branch-edit-page .input-wrap {
        position: relative;
    }

    .branch-edit-page .input-leading-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        z-index: 2;
        color: #94a3b8;
        font-size: 1rem;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .branch-edit-page .textarea-icon {
        top: 17px;
        transform: none;
    }

    .branch-edit-page .form-control-modern,
    .branch-edit-page .form-select-modern {
        width: 100%;
        min-height: 50px;
        padding: 10px 42px 10px 44px;
        border: 1px solid #cbd5e1;
        border-radius: 13px;
        color: var(--be-text);
        background: #ffffff;
        font-size: .87rem;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .branch-edit-page textarea.form-control-modern {
        min-height: 125px;
        padding-top: 14px;
        resize: vertical;
    }

    .branch-edit-page .form-select-modern {
        appearance: auto;
        padding-right: 36px;
    }

    .branch-edit-page .form-control-modern::placeholder {
        color: #a8b2c1;
    }

    .branch-edit-page .form-control-modern[readonly] {
        color: #475569;
        cursor: not-allowed;
        border-style: dashed;
        background: #f8fafc;
        box-shadow: none;
    }

    .branch-edit-page .auto-code-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        color: #1d4ed8;
        font-size: .66rem;
        font-weight: 800;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        background: #eff6ff;
    }

    .branch-edit-page .form-control-modern:hover,
    .branch-edit-page .form-select-modern:hover {
        border-color: #94a3b8;
    }

    .branch-edit-page .form-control-modern:focus,
    .branch-edit-page .form-select-modern:focus {
        border-color: var(--be-primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
    }

    .branch-edit-page .input-wrap:focus-within .input-leading-icon {
        color: var(--be-primary);
    }

    .branch-edit-page .form-control-modern.is-invalid,
    .branch-edit-page .form-select-modern.is-invalid {
        border-color: #ef4444;
        background: #fffafa;
    }

    .branch-edit-page .field-error {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 7px;
        color: var(--be-danger);
        font-size: .73rem;
        font-weight: 650;
    }

    .branch-edit-page .field-help {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-top: 7px;
        color: #94a3b8;
        font-size: .7rem;
        line-height: 1.5;
    }

    .branch-edit-page .character-count {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .branch-edit-page .status-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .branch-edit-page .status-input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
    }

    .branch-edit-page .status-card {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 78px;
        padding: 15px;
        border: 1px solid var(--be-border);
        border-radius: 15px;
        background: #ffffff;
        cursor: pointer;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .branch-edit-page .status-card:hover {
        border-color: #93c5fd;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(37, 99, 235, .07);
    }

    .branch-edit-page .status-icon {
        width: 43px;
        height: 43px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 43px;
        border-radius: 13px;
        font-size: 1.1rem;
    }

    .branch-edit-page .status-active .status-icon {
        color: #15803d;
        background: #dcfce7;
    }

    .branch-edit-page .status-inactive .status-icon {
        color: #b91c1c;
        background: #fee2e2;
    }

    .branch-edit-page .status-copy strong {
        display: block;
        margin-bottom: 3px;
        color: var(--be-text);
        font-size: .84rem;
        font-weight: 800;
    }

    .branch-edit-page .status-copy small {
        display: block;
        color: var(--be-muted);
        font-size: .7rem;
        line-height: 1.45;
    }

    .branch-edit-page .status-input:focus-visible + .status-card {
        outline: 3px solid rgba(37, 99, 235, .20);
        outline-offset: 2px;
    }

    .branch-edit-page .status-input:checked + .status-card.status-active {
        border-color: #22c55e;
        background: #f0fdf4;
        box-shadow: 0 0 0 1px #22c55e, 0 12px 24px rgba(22, 163, 74, .08);
    }

    .branch-edit-page .status-input:checked + .status-card.status-inactive {
        border-color: #ef4444;
        background: #fef2f2;
        box-shadow: 0 0 0 1px #ef4444, 0 12px 24px rgba(220, 38, 38, .07);
    }

    .branch-edit-page .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 20px 25px;
        background: #f8fafc;
    }

    .branch-edit-page .footer-info {
        display: flex;
        align-items: center;
        gap: 9px;
        color: var(--be-muted);
        font-size: .74rem;
    }

    .branch-edit-page .footer-info i {
        color: var(--be-primary);
    }

    .branch-edit-page .footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .branch-edit-page .btn-cancel,
    .branch-edit-page .btn-print,
    .branch-edit-page .btn-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 11px 20px;
        border-radius: 13px;
        font-size: .85rem;
        font-weight: 800;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .branch-edit-page .btn-cancel {
        border: 1px solid #cbd5e1;
        color: #475569;
        background: #ffffff;
    }

    .branch-edit-page .btn-cancel:hover {
        border-color: #94a3b8;
        color: var(--be-text);
        background: #f8fafc;
    }

    .branch-edit-page .btn-print {
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        background: #eff6ff;
    }

    .branch-edit-page .btn-print:hover {
        border-color: #93c5fd;
        color: #1e40af;
        background: #dbeafe;
        transform: translateY(-2px);
    }

    .branch-edit-page .btn-save {
        border: 0;
        color: #ffffff;
        background: linear-gradient(135deg, var(--be-primary), var(--be-primary-dark));
        box-shadow: 0 10px 22px rgba(37, 99, 235, .24);
    }

    .branch-edit-page .btn-save:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 14px 26px rgba(37, 99, 235, .28);
    }

    .branch-edit-page .btn-save:disabled {
        cursor: not-allowed;
        opacity: .72;
        transform: none;
    }

    .branch-edit-page .side-column {
        position: sticky;
        top: 22px;
        display: grid;
        gap: 20px;
    }

    .branch-edit-page .preview-card {
        overflow: hidden;
    }

    .branch-edit-page .preview-cover {
        position: relative;
        height: 115px;
        background:
            radial-gradient(circle at 85% 20%, rgba(255, 255, 255, .24), transparent 22%),
            linear-gradient(135deg, #1e3a8a, #2563eb 58%, #0ea5e9);
    }

    .branch-edit-page .preview-cover::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        right: -45px;
        bottom: -70px;
        border: 18px solid rgba(255, 255, 255, .10);
        border-radius: 50%;
    }

    .branch-edit-page .preview-building {
        position: absolute;
        left: 22px;
        bottom: -31px;
        z-index: 2;
        width: 66px;
        height: 66px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 5px solid #ffffff;
        border-radius: 20px;
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        box-shadow: 0 12px 24px rgba(37, 99, 235, .22);
        font-size: 1.5rem;
    }

    .branch-edit-page .preview-body {
        padding: 47px 22px 22px;
    }

    .branch-edit-page .preview-code {
        display: inline-flex;
        align-items: center;
        padding: .35rem .6rem;
        margin-bottom: .7rem;
        border: 1px solid #bfdbfe;
        border-radius: 9px;
        color: #1d4ed8;
        background: #eff6ff;
        font-size: .7rem;
        font-weight: 850;
        letter-spacing: .045em;
        text-transform: uppercase;
    }

    .branch-edit-page .preview-name {
        margin-bottom: .45rem;
        color: var(--be-text);
        font-size: 1.1rem;
        font-weight: 850;
        word-break: break-word;
    }

    .branch-edit-page .preview-address {
        display: flex;
        align-items: flex-start;
        gap: .5rem;
        min-height: 42px;
        margin-bottom: 18px;
        color: var(--be-muted);
        font-size: .78rem;
        line-height: 1.55;
    }

    .branch-edit-page .preview-address i {
        margin-top: 2px;
        color: #ef4444;
    }

    .branch-edit-page .preview-details {
        display: grid;
        gap: 11px;
        padding-top: 17px;
        border-top: 1px dashed #cbd5e1;
    }

    .branch-edit-page .preview-item {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        color: #475569;
        font-size: .76rem;
    }

    .branch-edit-page .preview-item-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 32px;
        border-radius: 10px;
        color: var(--be-primary);
        background: var(--be-primary-soft);
    }

    .branch-edit-page .preview-item-content {
        min-width: 0;
    }

    .branch-edit-page .preview-item-label {
        display: block;
        margin-bottom: 1px;
        color: #94a3b8;
        font-size: .64rem;
        font-weight: 750;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .branch-edit-page .preview-item-value {
        display: block;
        overflow: hidden;
        color: #334155;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .branch-edit-page .preview-status {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .4rem .65rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 800;
    }

    .branch-edit-page .preview-status::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .branch-edit-page .preview-status.active {
        color: #166534;
        background: #dcfce7;
    }

    .branch-edit-page .preview-status.active::before {
        background: #22c55e;
    }

    .branch-edit-page .preview-status.inactive {
        color: #991b1b;
        background: #fee2e2;
    }

    .branch-edit-page .preview-status.inactive::before {
        background: #ef4444;
    }

    .branch-edit-page .guide-card {
        padding: 21px;
    }

    .branch-edit-page .guide-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        color: var(--be-text);
        font-size: .88rem;
        font-weight: 850;
    }

    .branch-edit-page .guide-title-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        color: #b45309;
        background: #fffbeb;
    }

    .branch-edit-page .guide-list {
        display: grid;
        gap: 12px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .branch-edit-page .guide-list li {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        color: var(--be-muted);
        font-size: .74rem;
        line-height: 1.55;
    }

    .branch-edit-page .guide-list i {
        margin-top: 2px;
        color: var(--be-success);
    }

    @media (max-width: 1199.98px) {
        .branch-edit-page .edit-layout {
            grid-template-columns: minmax(0, 1fr) 310px;
        }
    }

    @media (max-width: 991.98px) {
        .branch-edit-page .edit-layout {
            grid-template-columns: 1fr;
        }

        .branch-edit-page .side-column {
            position: static;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .branch-edit-page {
            padding-top: 1rem !important;
        }

        .branch-edit-page .page-hero {
            padding: 24px;
            border-radius: 20px;
        }

        .branch-edit-page .hero-wrapper {
            align-items: flex-start !important;
            flex-direction: column;
        }

        .branch-edit-page .hero-action {
            width: 100%;
            justify-content: stretch;
        }

        .branch-edit-page .btn-back,
        .branch-edit-page .btn-print-hero {
            flex: 1 1 0;
        }

        .branch-edit-page .hero-heading {
            align-items: flex-start;
        }

        .branch-edit-page .hero-icon {
            width: 50px;
            height: 50px;
            flex-basis: 50px;
            border-radius: 15px;
        }

        .branch-edit-page .form-section,
        .branch-edit-page .form-footer {
            padding: 20px;
        }

        .branch-edit-page .section-heading,
        .branch-edit-page .form-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .branch-edit-page .status-grid,
        .branch-edit-page .side-column {
            grid-template-columns: 1fr;
        }

        .branch-edit-page .footer-actions {
            width: 100%;
        }

        .branch-edit-page .btn-cancel,
        .branch-edit-page .btn-print,
        .branch-edit-page .btn-save {
            flex: 1 1 0;
        }
    }

    @media (max-width: 479.98px) {
        .branch-edit-page .footer-actions {
            flex-direction: column-reverse;
        }

        .branch-edit-page .btn-cancel,
        .branch-edit-page .btn-print,
        .branch-edit-page .btn-save {
            width: 100%;
        }
    }

    .branch-print-sheet {
        display: none;
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
            background: #ffffff !important;
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
            background: #ffffff;
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
            width: 54px;
            height: 54px;
            border-radius: 14px;
            color: #ffffff;
            background: #2563eb;
            font-size: 25px;
        }

        .branch-print-header h1 {
            margin: 0 0 4px;
            font-size: 22px;
            font-weight: 800;
        }

        .branch-print-header p {
            margin: 0;
            color: #64748b;
            font-size: 11px;
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
            grid-template-columns: minmax(0, 1fr) 145px;
            gap: 18px;
            margin: 22px 0;
            padding: 20px;
            border: 1px solid #dbeafe;
            border-radius: 14px;
            background: #f8fbff;
        }

        .branch-print-summary-label {
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
<div class="container-fluid branch-edit-page py-4 px-3 px-lg-4">
    <div class="edit-shell">

        {{-- Page Header --}}
        <header class="page-hero mb-4">
            <div class="hero-wrapper d-flex align-items-center justify-content-between gap-4">
                <div class="hero-content">
                    <div class="hero-heading">
                        <span class="hero-icon">
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <div class="hero-eyebrow">
                                <i class="bi bi-diagram-3-fill"></i>
                                Branch Management
                            </div>
                            <h1 class="hero-title">Edit Data Cabang</h1>
                            <p class="hero-description">
                                Perbarui informasi cabang, kontak operasional, kepala cabang, dan status tanpa mengubah kode cabang yang telah dibuat sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="hero-action">
                    <button type="button" class="btn-print-hero js-print-branch">
                        <i class="bi bi-printer-fill"></i>
                        Cetak Data
                    </button>
                    <a href="{{ route('branches.index') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </header>

        <div class="d-flex flex-wrap align-items-center gap-2 mb-4" aria-label="Identitas cabang yang diedit">
            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                <i class="bi bi-upc-scan me-1"></i>{{ $branch->branch_code }}
            </span>
            <span class="badge rounded-pill text-bg-light border text-secondary px-3 py-2">
                <i class="bi bi-pencil-square me-1"></i>Mode Edit
            </span>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="validation-alert mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Perubahan data cabang belum dapat disimpan.</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form
            id="editBranchForm"
            action="{{ route('branches.update', $branch->id) }}"
            method="POST"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="edit-layout">
                <main class="surface-card form-card">

                    {{-- Basic Information --}}
                    <section class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-main">
                                <span class="section-icon">
                                    <i class="bi bi-building"></i>
                                </span>
                                <div>
                                    <h2>Informasi Utama Cabang</h2>
                                    <p>Masukkan identitas utama yang akan digunakan untuk mengenali cabang.</p>
                                </div>
                            </div>
                            <span class="required-note">
                                <span class="required-mark">*</span> Wajib diisi
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="branch_code" class="field-label">
                                    <span>Kode Cabang</span>
                                    <span class="auto-code-badge">
                                        <i class="bi bi-magic"></i>
                                        Otomatis
                                    </span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-upc-scan input-leading-icon"></i>
                                    <input
                                        id="branch_code"
                                        type="text"
                                        class="form-control-modern"
                                        value="{{ $branch->branch_code }}"
                                        data-preview-code="{{ $branch->branch_code }}"
                                        readonly
                                        tabindex="-1"
                                        aria-describedby="branchCodeHelp"
                                    >
                                </div>
                                <div id="branchCodeHelp" class="field-help">
                                    <span>Kode dibuat otomatis oleh sistem dan tidak dapat diubah dari halaman edit.</span>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <label for="branch_name" class="field-label">
                                    <span>Nama Cabang <span class="required-mark">*</span></span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-buildings input-leading-icon"></i>
                                    <input
                                        id="branch_name"
                                        type="text"
                                        name="branch_name"
                                        class="form-control-modern @error('branch_name') is-invalid @enderror"
                                        value="{{ old('branch_name', $branch->branch_name) }}"
                                        placeholder="Contoh: Cabang Jakarta Pusat"
                                        maxlength="100"
                                        autocomplete="organization"
                                        required
                                    >
                                </div>
                                @error('branch_name')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="field-help">
                                    <span>Gunakan nama resmi cabang perusahaan.</span>
                                    <span class="character-count" data-count-for="branch_name">0/100</span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="address" class="field-label">
                                    <span>Alamat Cabang <span class="required-mark">*</span></span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-geo-alt input-leading-icon textarea-icon"></i>
                                    <textarea
                                        id="address"
                                        name="address"
                                        class="form-control-modern @error('address') is-invalid @enderror"
                                        placeholder="Masukkan alamat lengkap cabang, nama jalan, kecamatan, kota, dan kode pos."
                                        maxlength="500"
                                        required
                                    >{{ old('address', $branch->address) }}</textarea>
                                </div>
                                @error('address')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="field-help">
                                    <span>Alamat lengkap membantu proses koordinasi dan identifikasi lokasi.</span>
                                    <span class="character-count" data-count-for="address">0/500</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Management & Contact --}}
                    <section class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-main">
                                <span class="section-icon">
                                    <i class="bi bi-person-workspace"></i>
                                </span>
                                <div>
                                    <h2>Penanggung Jawab dan Kontak</h2>
                                    <p>Tentukan kepala cabang serta informasi komunikasi operasional.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="manager_id" class="field-label">
                                    <span>Kepala Cabang</span>
                                    <small>Opsional</small>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-person-badge input-leading-icon"></i>
                                    <select
                                        id="manager_id"
                                        name="manager_id"
                                        class="form-select-modern @error('manager_id') is-invalid @enderror"
                                    >
                                        <option value="">Belum menentukan kepala cabang</option>
                                        @forelse(($managers ?? collect()) as $manager)
                                            <option
                                                value="{{ $manager->id }}"
                                                data-manager-name="{{ $manager->name }}"
                                                @selected((string) old('manager_id', $branch->manager_id) === (string) $manager->id)
                                            >
                                                {{ $manager->name }}{{ !empty($manager->email) ? ' — '.$manager->email : '' }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Data calon kepala cabang belum tersedia</option>
                                        @endforelse
                                    </select>
                                </div>
                                @error('manager_id')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="field-help">
                                    <span>Penanggung jawab dapat ditentukan sekarang atau diperbarui setelah cabang dibuat.</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="field-label">
                                    <span>Nomor Telepon <span class="required-mark">*</span></span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-telephone input-leading-icon"></i>
                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        class="form-control-modern @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $branch->phone) }}"
                                        placeholder="Contoh: 021-5551234"
                                        maxlength="20"
                                        autocomplete="tel"
                                        required
                                    >
                                </div>
                                @error('phone')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="field-help">
                                    <span>Gunakan nomor kantor atau kontak operasional aktif.</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="field-label">
                                    <span>Email Cabang <span class="required-mark">*</span></span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-envelope input-leading-icon"></i>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        class="form-control-modern @error('email') is-invalid @enderror"
                                        value="{{ old('email', $branch->email) }}"
                                        placeholder="cabang@perusahaan.com"
                                        maxlength="100"
                                        autocomplete="email"
                                        required
                                    >
                                </div>
                                @error('email')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="field-help">
                                    <span>Gunakan email resmi yang dapat menerima korespondensi.</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Operational Status --}}
                    <section class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-main">
                                <span class="section-icon">
                                    <i class="bi bi-toggles"></i>
                                </span>
                                <div>
                                    <h2>Status Operasional</h2>
                                    <p>Perbarui status operasional cabang sesuai kondisi saat ini.</p>
                                </div>
                            </div>
                        </div>

                        <div class="status-grid">
                            <div>
                                <input
                                    id="status_active"
                                    class="status-input"
                                    type="radio"
                                    name="status"
                                    value="1"
                                    @checked((string) old('status', (string) $branch->status) === '1')
                                >
                                <label for="status_active" class="status-card status-active">
                                    <span class="status-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                    <span class="status-copy">
                                        <strong>Aktif</strong>
                                        <small>Cabang dapat digunakan dalam proses operasional sistem.</small>
                                    </span>
                                </label>
                            </div>

                            <div>
                                <input
                                    id="status_inactive"
                                    class="status-input"
                                    type="radio"
                                    name="status"
                                    value="0"
                                    @checked((string) old('status', (string) $branch->status) === '0')
                                >
                                <label for="status_inactive" class="status-card status-inactive">
                                    <span class="status-icon">
                                        <i class="bi bi-pause-circle-fill"></i>
                                    </span>
                                    <span class="status-copy">
                                        <strong>Nonaktif</strong>
                                        <small>Data tetap tersimpan, tetapi cabang belum digunakan.</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        @error('status')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </section>

                    {{-- Update Actions --}}
                    <footer class="form-footer">
                        <div class="footer-info">
                            <i class="bi bi-shield-check"></i>
                            <span>Periksa kembali perubahan sebelum memperbarui data cabang.</span>
                        </div>

                        <div class="footer-actions">
                            <a href="{{ route('branches.index') }}" class="btn-cancel">
                                <i class="bi bi-x-lg"></i>
                                Batal
                            </a>
                            <button type="button" class="btn-print js-print-branch">
                                <i class="bi bi-printer-fill"></i>
                                Cetak
                            </button>
                            <button id="updateBranchButton" type="submit" class="btn-save">
                                <i class="bi bi-floppy-fill"></i>
                                <span>Perbarui Cabang</span>
                            </button>
                        </div>
                    </footer>
                </main>

                {{-- Live Preview & Guide --}}
                <aside class="side-column">
                    <section class="surface-card preview-card" aria-label="Pratinjau cabang">
                        <div class="preview-cover">
                            <span class="preview-building">
                                <i class="bi bi-building-fill"></i>
                            </span>
                        </div>

                        <div class="preview-body">
                            <span id="previewCode" class="preview-code">{{ $branch->branch_code }}</span>
                            <h2 id="previewName" class="preview-name">{{ old('branch_name', $branch->branch_name) }}</h2>
                            <div class="preview-address">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span id="previewAddress">{{ old('address', $branch->address) }}</span>
                            </div>

                            <div class="preview-details">
                                <div class="preview-item">
                                    <span class="preview-item-icon">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </span>
                                    <span class="preview-item-content">
                                        <span class="preview-item-label">Kepala Cabang</span>
                                        <span id="previewManager" class="preview-item-value">Belum ditentukan</span>
                                    </span>
                                </div>

                                <div class="preview-item">
                                    <span class="preview-item-icon">
                                        <i class="bi bi-telephone-fill"></i>
                                    </span>
                                    <span class="preview-item-content">
                                        <span class="preview-item-label">Telepon</span>
                                        <span id="previewPhone" class="preview-item-value">Belum diisi</span>
                                    </span>
                                </div>

                                <div class="preview-item">
                                    <span class="preview-item-icon">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                    <span class="preview-item-content">
                                        <span class="preview-item-label">Email</span>
                                        <span id="previewEmail" class="preview-item-value">Belum diisi</span>
                                    </span>
                                </div>

                                <div class="preview-item">
                                    <span class="preview-item-icon">
                                        <i class="bi bi-activity"></i>
                                    </span>
                                    <span class="preview-item-content">
                                        <span class="preview-item-label">Status</span>
                                        <span id="previewStatus" class="preview-status active">Aktif</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="surface-card guide-card">
                        <div class="guide-title">
                            <span class="guide-title-icon">
                                <i class="bi bi-lightbulb-fill"></i>
                            </span>
                            Panduan Pengisian
                        </div>

                        <ul class="guide-list">
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Kode cabang dibuat otomatis dan tetap permanen setelah cabang disimpan.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Pastikan email dan nomor telepon merupakan kontak operasional aktif.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Kepala cabang dapat diganti dengan pengguna aktif yang tersedia.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Gunakan status nonaktif apabila operasional cabang sedang dihentikan sementara.</span>
                            </li>
                        </ul>
                    </section>
                </aside>
            </div>
        </form>

        {{-- Printable Branch Profile --}}
        <section id="branchPrintSheet" class="branch-print-sheet" aria-hidden="true">
            <header class="branch-print-header">
                <div class="branch-print-brand">
                    <span class="branch-print-logo">
                        <i class="bi bi-building-fill"></i>
                    </span>
                    <div>
                        <h1>Data Cabang Perusahaan</h1>
                        <p>Dokumen informasi dan status operasional cabang</p>
                    </div>
                </div>
                <span id="printBranchCode" class="branch-print-code">{{ $branch->branch_code }}</span>
            </header>

            <div class="branch-print-summary">
                <div>
                    <div class="branch-print-summary-label">Nama dan Alamat Cabang</div>
                    <h2 id="printBranchName" class="branch-print-name">{{ old('branch_name', $branch->branch_name) }}</h2>
                    <p id="printBranchAddress" class="branch-print-address">{{ old('address', $branch->address) }}</p>
                </div>
                <div class="branch-print-status-wrap">
                    <span id="printBranchStatus" class="branch-print-status active">Aktif</span>
                </div>
            </div>

            <h3 class="branch-print-section-title">Informasi Cabang</h3>
            <table class="branch-print-table">
                <tbody>
                    <tr>
                        <th>Kode Cabang</th>
                        <td id="printTableCode">{{ $branch->branch_code }}</td>
                    </tr>
                    <tr>
                        <th>Nama Cabang</th>
                        <td id="printTableName">{{ old('branch_name', $branch->branch_name) }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td id="printTableAddress">{{ old('address', $branch->address) }}</td>
                    </tr>
                    <tr>
                        <th>Kepala Cabang</th>
                        <td id="printTableManager">Belum ditentukan</td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon</th>
                        <td id="printTablePhone">{{ old('phone', $branch->phone) }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td id="printTableEmail">{{ old('email', $branch->email) }}</td>
                    </tr>
                    <tr>
                        <th>Status Operasional</th>
                        <td id="printTableStatus">Aktif</td>
                    </tr>
                </tbody>
            </table>

            <footer class="branch-print-footer">
                <div>
                    <div>Dicetak dari sistem Branch Management</div>
                    <div id="printTimestamp">Waktu cetak akan ditampilkan saat dokumen dicetak.</div>
                </div>
                <div class="branch-print-signature">
                    <div class="branch-print-signature-space"></div>
                    <div class="branch-print-signature-line">Penanggung Jawab</div>
                </div>
            </footer>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('editBranchForm');
        const updateButton = document.getElementById('updateBranchButton');

        const fields = {
            name: document.getElementById('branch_name'),
            address: document.getElementById('address'),
            manager: document.getElementById('manager_id'),
            phone: document.getElementById('phone'),
            email: document.getElementById('email')
        };

        const preview = {
            code: document.getElementById('previewCode'),
            name: document.getElementById('previewName'),
            address: document.getElementById('previewAddress'),
            manager: document.getElementById('previewManager'),
            phone: document.getElementById('previewPhone'),
            email: document.getElementById('previewEmail'),
            status: document.getElementById('previewStatus')
        };

        const printView = {
            code: document.getElementById('printBranchCode'),
            name: document.getElementById('printBranchName'),
            address: document.getElementById('printBranchAddress'),
            status: document.getElementById('printBranchStatus'),
            tableCode: document.getElementById('printTableCode'),
            tableName: document.getElementById('printTableName'),
            tableAddress: document.getElementById('printTableAddress'),
            tableManager: document.getElementById('printTableManager'),
            tablePhone: document.getElementById('printTablePhone'),
            tableEmail: document.getElementById('printTableEmail'),
            tableStatus: document.getElementById('printTableStatus'),
            timestamp: document.getElementById('printTimestamp')
        };

        const fallback = function (value, defaultValue) {
            const normalized = String(value || '').trim();
            return normalized !== '' ? normalized : defaultValue;
        };

        const updateCharacterCount = function (input) {
            const counter = document.querySelector('[data-count-for="' + input.id + '"]');
            if (!counter) return;

            const maximum = input.getAttribute('maxlength') || 0;
            counter.textContent = input.value.length + '/' + maximum;
        };

        const updatePreview = function () {
            const branchCode = @json($branch->branch_code);
            const branchName = fallback(fields.name.value, 'Nama Cabang');
            const branchAddress = fallback(fields.address.value, 'Alamat cabang belum diisi.');
            const branchPhone = fallback(fields.phone.value, 'Belum diisi');
            const branchEmail = fallback(fields.email.value, 'Belum diisi');

            let managerName = 'Belum ditentukan';
            if (fields.manager.selectedIndex > 0) {
                const selectedManager = fields.manager.options[fields.manager.selectedIndex];
                managerName = selectedManager.dataset.managerName || selectedManager.textContent.trim();
            }

            const selectedStatus = document.querySelector('input[name="status"]:checked');
            const isActive = !selectedStatus || selectedStatus.value === '1';
            const statusText = isActive ? 'Aktif' : 'Nonaktif';

            preview.code.textContent = branchCode;
            preview.name.textContent = branchName;
            preview.address.textContent = branchAddress;
            preview.manager.textContent = managerName;
            preview.phone.textContent = branchPhone;
            preview.email.textContent = branchEmail;
            preview.status.textContent = statusText;
            preview.status.classList.toggle('active', isActive);
            preview.status.classList.toggle('inactive', !isActive);

            printView.code.textContent = branchCode;
            printView.name.textContent = branchName;
            printView.address.textContent = branchAddress;
            printView.status.textContent = statusText;
            printView.status.classList.toggle('active', isActive);
            printView.status.classList.toggle('inactive', !isActive);
            printView.tableCode.textContent = branchCode;
            printView.tableName.textContent = branchName;
            printView.tableAddress.textContent = branchAddress;
            printView.tableManager.textContent = managerName;
            printView.tablePhone.textContent = branchPhone;
            printView.tableEmail.textContent = branchEmail;
            printView.tableStatus.textContent = statusText;
        };

        const formatPrintTimestamp = function () {
            return new Intl.DateTimeFormat('id-ID', {
                dateStyle: 'full',
                timeStyle: 'short'
            }).format(new Date());
        };

        const printBranch = function () {
            updatePreview();
            printView.timestamp.textContent = 'Dicetak pada ' + formatPrintTimestamp();
            window.print();
        };

        Object.values(fields).forEach(function (field) {
            if (!field) return;

            field.addEventListener('input', function () {
                updateCharacterCount(field);
                updatePreview();
            });

            field.addEventListener('change', updatePreview);
            updateCharacterCount(field);
        });

        document.querySelectorAll('input[name="status"]').forEach(function (statusInput) {
            statusInput.addEventListener('change', updatePreview);
        });

        document.querySelectorAll('.js-print-branch').forEach(function (printButton) {
            printButton.addEventListener('click', printBranch);
        });

        if (form && updateButton) {
            form.addEventListener('submit', function () {
                updateButton.disabled = true;
                updateButton.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Memperbarui...</span>';
            });
        }

        updatePreview();
    });
</script>
@endpush