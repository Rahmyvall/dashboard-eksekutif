<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


     <!-- Meta -->
     <meta name="description" content="Responsive Bootstrap 4 Dashboard and Admin Template">
     <meta name="author" content="ThemePixels">


     <!-- Favicon -->
     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/assets/img/logo.png') }}">


     <title>
          Dashboard Monitoring Kinerja & Kepuasan Pelanggan | @yield('page-title', 'Dashboard Eksekutif')
     </title>

     {{-- CSS --}}
     @stack('styles')


     <!-- vendor css -->
     <link href="{{ asset('backend/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

     <link href="{{ asset('backend/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
     <link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">
     <link href="{{ asset('backend/lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/template.css') }}">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">

     <!-- template css -->
     <link rel="stylesheet" href="{{ asset('backend/assets/css/cassie.css') }}">
     <style>
          /* =========================================
       PENGATURAN UMUM
    ========================================= */

          html,
          body {
               min-height: 100%;
          }

          body,
          .header,
          .sidebar,
          .sidebar-header,
          .content,
          .content-header,
          .content-body,
          .card,
          .dropdown-menu,
          .form-control {
               transition:
                    background-color 0.3s ease,
                    color 0.3s ease,
                    border-color 0.3s ease;
          }

          .flot-chart {
               height: 350px !important;
          }

          /* =========================================
       BLACK THEME
    ========================================= */

          body.black-theme {
               background-color: #121212 !important;
               color: #ffffff !important;
          }

          /* Header */
          body.black-theme .header {
               background-color: #1e1e1e !important;
               color: #ffffff !important;
               border-color: #333333 !important;
          }

          /* Sidebar */
          body.black-theme .sidebar {
               background: #181818 !important;
               color: #ffffff !important;
          }

          body.black-theme .sidebar-header {
               background-color: #181818 !important;
               border-bottom: 1px solid #333333 !important;
          }

          body.black-theme .sidebar-brand,
          body.black-theme .sidebar-body {
               background-color: transparent !important;
          }

          body.black-theme .sidebar-logo-text,
          body.black-theme .sidebar-logo-headline,
          body.black-theme .sidebar-logo-headline b,
          body.black-theme .sidebar-logo span {
               color: #ffffff !important;
          }

          /* Label sidebar */
          body.black-theme .sidebar .nav-label,
          body.black-theme .sidebar .content-label {
               color: #9e9e9e !important;
          }

          /* Menu sidebar */
          body.black-theme .sidebar .nav-sidebar .nav-link {
               color: #d8d8d8 !important;
               border-radius: 8px;
               margin: 3px 10px;
               padding: 11px 14px;
          }

          body.black-theme .sidebar .nav-sidebar .nav-link i,
          body.black-theme .sidebar .nav-sidebar .nav-link svg {
               color: #d8d8d8 !important;
               stroke: #d8d8d8 !important;
          }

          body.black-theme .sidebar .nav-sidebar .nav-link:hover {
               background-color: #292929 !important;
               color: #ffffff !important;
               transform: translateX(3px);
          }

          body.black-theme .sidebar .nav-sidebar .nav-link.active {
               background-color: #343434 !important;
               color: #ffffff !important;
               font-weight: 600;
               box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
          }

          body.black-theme .sidebar .nav-sidebar .nav-link.active i,
          body.black-theme .sidebar .nav-sidebar .nav-link.active svg {
               color: #ffffff !important;
               stroke: #ffffff !important;
          }

          /* Submenu sidebar */
          body.black-theme .sidebar .nav-sub {
               background-color: #202020 !important;
               margin: 4px 10px 8px;
               padding: 6px 0;
               border-radius: 8px;
          }

          body.black-theme .sidebar .nav-sub-link {
               color: #cfcfcf !important;
          }

          body.black-theme .sidebar .nav-sub-link:hover {
               color: #ffffff !important;
               background-color: #303030 !important;
          }

          body.black-theme .sidebar .nav-sub-link.active {
               color: #ffffff !important;
               background-color: #383838 !important;
               border-left: 3px solid #ffffff;
          }

          /* Area content */
          body.black-theme .content,
          body.black-theme .content-header,
          body.black-theme .content-body {
               background-color: #121212 !important;
               color: #ffffff !important;
          }

          body.black-theme .content-title,
          body.black-theme h1,
          body.black-theme h2,
          body.black-theme h3,
          body.black-theme h4,
          body.black-theme h5,
          body.black-theme h6,
          body.black-theme p,
          body.black-theme label {
               color: #ffffff;
          }

          /* Card */
          body.black-theme .card,
          body.black-theme .card-header,
          body.black-theme .card-body,
          body.black-theme .card-footer {
               background-color: #1e1e1e !important;
               color: #ffffff !important;
               border-color: #333333 !important;
          }

          /* Table */
          body.black-theme .table {
               color: #ffffff !important;
          }

          body.black-theme .table th,
          body.black-theme .table td {
               border-color: #3a3a3a !important;
          }

          body.black-theme .table-striped tbody tr:nth-of-type(odd) {
               background-color: #242424 !important;
          }

          /* Breadcrumb */
          body.black-theme .breadcrumb {
               background-color: transparent !important;
          }

          body.black-theme .breadcrumb-item,
          body.black-theme .breadcrumb-item a {
               color: #cccccc !important;
          }

          body.black-theme .breadcrumb-item.active {
               color: #ffffff !important;
          }

          /* Dropdown */
          body.black-theme .dropdown-menu {
               background-color: #242424 !important;
               border-color: #3a3a3a !important;
          }

          body.black-theme .dropdown-item {
               color: #ffffff !important;
          }

          body.black-theme .dropdown-item:hover,
          body.black-theme .dropdown-item:focus {
               background-color: #333333 !important;
               color: #ffffff !important;
          }

          /* Input */
          body.black-theme .form-control,
          body.black-theme .custom-select,
          body.black-theme select,
          body.black-theme textarea {
               background-color: #2b2b2b !important;
               color: #ffffff !important;
               border-color: #444444 !important;
          }

          body.black-theme .form-control::placeholder {
               color: #aaaaaa !important;
          }

          body.black-theme .form-control:focus {
               background-color: #303030 !important;
               color: #ffffff !important;
               border-color: #777777 !important;
               box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.08);
          }

          /* =========================================
       WHITE THEME
    ========================================= */

          body.white-theme {
               background-color: #ffffff !important;
               color: #212529 !important;
          }

          body.white-theme .header {
               background-color: #ffffff !important;
               color: #212529 !important;
          }

          body.white-theme .content,
          body.white-theme .content-header,
          body.white-theme .content-body {
               background-color: #ffffff !important;
               color: #212529 !important;
          }

          body.white-theme .card,
          body.white-theme .card-header,
          body.white-theme .card-body,
          body.white-theme .card-footer {
               background-color: #ffffff !important;
               color: #212529 !important;
          }

          /* =========================================
       LOGO SIDEBAR
    ========================================= */

          .sidebar-brand {
               display: flex;
               flex-direction: column;
               align-items: center;
               justify-content: center;
               width: 100%;
               text-align: center;
          }

          .sidebar-logo {
               display: flex;
               flex-direction: column;
               align-items: center;
               justify-content: center;
               width: 100%;
               padding: 15px 0;
               margin: 0 auto;
               text-align: center;
               text-decoration: none;
               box-sizing: border-box;
          }

          .sidebar-logo-image {
               display: block;
               width: auto;
               max-width: 130px;
               height: 80px;
               margin: 0 auto;
               padding: 0;
               object-fit: contain;
               object-position: center;
          }

          .sidebar-logo-text,
          .sidebar-logo-headline {
               display: block;
               width: 100%;
               margin: 6px auto 0;
               padding: 0 10px;
               font-size: 13px;
               font-weight: 600;
               line-height: 1.3;
               text-align: center;
               box-sizing: border-box;
          }

          /* =========================================
       SIDEBAR BIRU KHUSUS WHITE THEME
    ========================================= */

          body.white-theme .sidebar {
               background: linear-gradient(180deg,
                         #78a9e0 0%,
                         #78a9e0 55%,
                         #78a9e0 100%) !important;
               color: #ffffff !important;
          }

          body.white-theme .sidebar-header {
               background-color: #78a9e0 !important;
               border-bottom: 1px solid rgba(255, 255, 255, 0.15);
          }

          body.white-theme .sidebar-brand,
          body.white-theme .sidebar-body {
               background-color: transparent !important;
          }

          body.white-theme .sidebar-logo-text,
          body.white-theme .sidebar-logo-headline,
          body.white-theme .sidebar-logo-headline b {
               color: #ffffff !important;
          }

          body.white-theme .sidebar .nav-label,
          body.white-theme .sidebar .content-label {
               color: #e3f2fd !important;
          }

          body.white-theme .sidebar .nav-sidebar .nav-link {
               color: #e3f2fd !important;
               border-radius: 8px;
               margin: 3px 10px;
               padding: 11px 14px;
               transition: all 0.25s ease;
          }

          body.white-theme .sidebar .nav-sidebar .nav-link i,
          body.white-theme .sidebar .nav-sidebar .nav-link svg {
               color: #e3f2fd !important;
               stroke: #e3f2fd !important;
          }

          body.white-theme .sidebar .nav-sidebar .nav-link:hover {
               background-color: rgba(255, 255, 255, 0.15) !important;
               color: #ffffff !important;
               transform: translateX(3px);
          }

          body.white-theme .sidebar .nav-sidebar .nav-link.active {
               background-color: #ffffff !important;
               color: #0d47a1 !important;
               font-weight: 600;
               box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
          }

          body.white-theme .sidebar .nav-sidebar .nav-link.active i,
          body.white-theme .sidebar .nav-sidebar .nav-link.active svg {
               color: #0d47a1 !important;
               stroke: #0d47a1 !important;
          }

          body.white-theme .sidebar .nav-sub {
               background-color: rgba(0, 0, 0, 0.12) !important;
               margin: 4px 10px 8px;
               padding: 6px 0;
               border-radius: 8px;
          }

          body.white-theme .sidebar .nav-sub-link {
               color: #d9ecff !important;
               padding: 9px 15px 9px 45px;
          }

          body.white-theme .sidebar .nav-sub-link:hover {
               color: #ffffff !important;
               background-color: rgba(255, 255, 255, 0.12) !important;
          }

          body.white-theme .sidebar .nav-sub-link.active {
               color: #ffffff !important;
               background-color: rgba(255, 255, 255, 0.2) !important;
               font-weight: 600;
               border-left: 3px solid #ffffff;
          }

          /* Scrollbar */
          .sidebar-body::-webkit-scrollbar {
               width: 5px;
          }

          .sidebar-body::-webkit-scrollbar-thumb {
               background-color: rgba(255, 255, 255, 0.35);
               border-radius: 10px;
          }

          .sidebar-body::-webkit-scrollbar-track {
               background-color: transparent;
          }
     </style>
</head>
