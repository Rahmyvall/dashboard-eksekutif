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
          /* =====================
   BLACK THEME
===================== */

          body.black-theme {

               background: #121212;
               color: #ffffff;

          }

          .flot-chart {
               height: 350px !important;
          }

          /* Header */

          body.black-theme .header {

               background: #1e1e1e;

          }


          /* Sidebar */

          body.black-theme .sidebar {

               background: #181818;

          }


          body.black-theme .sidebar-logo span {

               color: white;

          }



          /* Content */

          body.black-theme .content,
          body.black-theme .content-header,
          body.black-theme .content-body {

               background: #2b2828ab;
               color: white;

          }


          /* Title */

          body.black-theme .content-title {

               color: white;

          }


          /* Breadcrumb */

          body.black-theme .breadcrumb-item {

               color: #ddd;

          }



          /* Dropdown */

          body.black-theme .dropdown-menu {

               background: #242424;

          }


          body.black-theme .dropdown-item {

               color: white;

          }


          body.black-theme .dropdown-item:hover {

               background: #333;

          }



          /* Input */

          body.black-theme .form-control {

               background: #2b2b2b;
               color: white;
               border-color: #444;

          }



          /* =====================
   WHITE THEME
===================== */


          body.white-theme {

               background: #ffffff;

          }

          /* Container logo */
          .sidebar-brand {
               display: flex;
               flex-direction: column;
               align-items: center;
               justify-content: center;

               width: 100%;
               text-align: center;
          }

          /* Link logo */
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

          /* Gambar logo */
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

          /* Teks logo */
          .sidebar-logo-text,
          .sidebar-logo-headline {
               display: block;

               width: 100%;
               margin: 6px auto 0;
               padding: 0 10px;

               color: #0e0d0d;
               font-size: 13px;
               font-weight: 600;
               line-height: 1.3;
               text-align: center;

               box-sizing: border-box;
          }

          /* =========================================
   SIDEBAR TEMA BIRU
========================================= */

          .sidebar {
               background: linear-gradient(180deg, #78a9e0 0%, #78a9e0 55%, #78a9e0 100%) !important;
               color: #ffffff;
          }

          /* Bagian logo */
          .sidebar-header {
               background-color: #78a9e0 !important;
               border-bottom: 1px solid rgba(255, 255, 255, 0.15);
          }

          .sidebar-brand {
               background-color: transparent !important;
          }

          /* Judul di bawah logo */
          .sidebar-logo-headline,
          .sidebar-logo-headline b {
               color: #ffffff !important;
          }

          /* Area menu */
          .sidebar-body {
               background-color: transparent !important;
          }

          /* Judul kelompok menu */
          .sidebar .nav-label,
          .sidebar .content-label {
               color: #bbdefb !important;
          }

          /* Menu utama */
          .sidebar .nav-sidebar .nav-link {
               color: #e3f2fd !important;
               border-radius: 8px;
               margin: 3px 10px;
               padding: 11px 14px;
               transition: all 0.25s ease;
          }

          /* Icon menu */
          .sidebar .nav-sidebar .nav-link i,
          .sidebar .nav-sidebar .nav-link svg {
               color: #e3f2fd !important;
               stroke: #e3f2fd !important;
          }

          /* Hover menu */
          .sidebar .nav-sidebar .nav-link:hover {
               background-color: rgba(255, 255, 255, 0.15) !important;
               color: #ffffff !important;
               transform: translateX(3px);
          }

          /* Menu aktif */
          .sidebar .nav-sidebar .nav-link.active {
               background-color: #ffffff !important;
               color: #0d47a1 !important;
               font-weight: 600;
               box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
          }

          .sidebar .nav-sidebar .nav-link.active i,
          .sidebar .nav-sidebar .nav-link.active svg {
               color: #81a7e0 !important;
               stroke: #81a7e0 !important;
          }

          /* Submenu */
          .sidebar .nav-sub {
               background-color: rgba(0, 0, 0, 0.12);
               margin: 4px 10px 8px;
               padding: 6px 0;
               border-radius: 8px;
          }

          /* Link submenu */
          .sidebar .nav-sub-link {
               color: #d9ecff !important;
               padding: 9px 15px 9px 45px;
               transition: all 0.2s ease;
          }

          /* Hover submenu */
          .sidebar .nav-sub-link:hover {
               color: #ffffff !important;
               background-color: rgba(255, 255, 255, 0.12);
          }

          /* Submenu aktif */
          .sidebar .nav-sub-link.active {
               color: #ffffff !important;
               background-color: rgba(255, 255, 255, 0.2);
               font-weight: 600;
               border-left: 3px solid #ffffff;
          }

          /* Scrollbar sidebar */
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

          body.white-theme .header {

               background: #ffffff;

          }
     </style>
</head>
