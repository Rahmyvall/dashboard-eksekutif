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
          Dashboard Monitoring Kinerja & Kepuasan Pelanggan
     </title>

     {{-- CSS --}}
     @stack('styles')


     <!-- vendor css -->
     <link href="{{ asset('backend/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
     <link href="{{ asset('backend/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
     <link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">
     <link href="{{ asset('backend/lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/template.css') }}">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">

     <!-- template css -->
     <link rel="stylesheet" href="{{ asset('backend/assets/css/cassie.css') }}">
     <link rel="stylesheet" href="{{ asset('backend/assets/css/dashboard-ui.css') }}">
     <style>
          /* =====================================================
ROOT VARIABLE
===================================================== */

          :root {

               --transition-theme: .3s ease;

          }



          /* =====================================================
DEFAULT WHITE THEME
===================================================== */

          body.white-theme {


               --bg-page: #ffffff;

               --bg-card: #ffffff;

               --bg-header: #f8f9fa;

               --bg-table: #ffffff;

               --bg-table-head: #f1f3f5;

               --bg-hover: #eef4ff;


               --text-main: #212529;

               --text-muted: #6c757d;

               --border: #dee2e6;


          }



          /* =====================================================
BLACK THEME
===================================================== */
          /* =====================================================
SIDEBAR LOGO RESPONSIVE FIX
LOGO MENYESUAIKAN TEXT DIBAWAHNYA
===================================================== */


          /* Container logo */

          .sidebar-brand,
          .sidebar-logo,
          .sidebar-header .logo {


               width: 100% !important;

               display: flex !important;

               flex-direction: column !important;

               align-items: center !important;

               justify-content: center !important;

               text-align: center !important;

               overflow: hidden !important;

               padding: 12px 5px !important;


          }



          /* =====================================================
UKURAN GAMBAR LOGO
===================================================== */


          .sidebar-logo img,
          .sidebar-logo-image,
          .sidebar-brand img,
          .sidebar-header img {


               width: 105px !important;

               height: 105px !important;


               max-width: 105px !important;


               object-fit: contain !important;


               display: block !important;


               margin: 0 auto 8px auto !important;


          }



          /* =====================================================
JUDUL / TEXT LOGO
===================================================== */


          .sidebar-logo-text,
          .sidebar-logo-headline,
          .sidebar-brand span,
          .sidebar-brand h1,
          .sidebar-brand h2,
          .sidebar-brand h3 {


               display: block !important;


               width: 100% !important;


               max-width: 190px !important;


               margin: 5px auto 0 auto !important;


               padding: 0 !important;


               text-align: center !important;


               font-size: 13px !important;


               font-weight: 600 !important;


               line-height: 1.3 !important;


               white-space: normal !important;


               overflow-wrap: break-word !important;


          }



          /* Text kedua / deskripsi */

          .sidebar-logo-text small,
          .sidebar-brand small {


               display: block !important;


               margin-top: 3px !important;


               font-size: 11px !important;


               opacity: .85 !important;


          }





          /* =====================================================
DESKTOP
===================================================== */


          @media(min-width:1200px) {


               .sidebar-logo img,
               .sidebar-logo-image,
               .sidebar-brand img,
               .sidebar-header img {


                    width: 100px !important;

                    height: 100px !important;


               }


          }



          /* =====================================================
TABLET
===================================================== */


          @media(max-width:1199px) {


               .sidebar-logo img,
               .sidebar-logo-image,
               .sidebar-brand img,
               .sidebar-header img {


                    width: 70px !important;

                    height: 70px !important;


               }


          }



          /* =====================================================
MOBILE
===================================================== */


          @media(max-width:576px) {


               .sidebar-logo img,
               .sidebar-logo-image,
               .sidebar-brand img,
               .sidebar-header img {


                    width: 60px !important;

                    height: 60px !important;


               }



               .sidebar-logo-text,
               .sidebar-logo-headline {


                    font-size: 12px !important;


               }


          }

          body.black-theme {


               --bg-page: #121212;

               --bg-card: #1e1e1e;

               --bg-header: #242424;

               --bg-table: #1e1e1e;

               --bg-table-head: #292929;

               --bg-hover: #303030;


               --text-main: #ffffff;

               --text-muted: #bbbbbb;

               --border: #887070;


          }



          /* =====================================================
GLOBAL
===================================================== */


          body {

               transition:
                    background var(--transition-theme),
                    color var(--transition-theme);

          }




          body.white-theme,
          body.black-theme {


               background:
                    var(--bg-page) !important;


               color:
                    var(--text-main) !important;


          }



          /* =====================================================
ALL TEXT
===================================================== */


          body.white-theme *,
          body.black-theme * {


               border-color:
                    var(--border) !important;


          }



          body.white-theme h1,
          body.white-theme h2,
          body.white-theme h3,
          body.white-theme h4,
          body.white-theme h5,
          body.white-theme h6,
          body.white-theme p,
          body.white-theme label,
          body.white-theme span,
          body.white-theme small,
          body.white-theme td,
          body.white-theme th,


          body.black-theme h1,
          body.black-theme h2,
          body.black-theme h3,
          body.black-theme h4,
          body.black-theme h5,
          body.black-theme h6,
          body.black-theme p,
          body.black-theme label,
          body.black-theme span,
          body.black-theme small,
          body.black-theme td,
          body.black-theme th {


               color:
                    var(--text-main) !important;


          }




          /* =====================================================
HEADER
===================================================== */


          body.black-theme .header,
          body.white-theme .header,


          body.black-theme .content-header,
          body.white-theme .content-header {


               background:
                    var(--bg-card) !important;


               color:
                    var(--text-main) !important;


          }




          /* =====================================================
CARD
===================================================== */


          body.black-theme .card,
          body.white-theme .card,


          body.black-theme .card-header,
          body.white-theme .card-header,


          body.black-theme .card-body,
          body.white-theme .card-body,


          body.black-theme .card-footer,
          body.white-theme .card-footer {


               background:
                    var(--bg-card) !important;


               color:
                    var(--text-main) !important;


               border-color:
                    var(--border) !important;


          }




          /* =====================================================
TABLE BOOTSTRAP
===================================================== */


          body.black-theme table,
          body.black-theme .table {


               background:
                    var(--bg-table) !important;


               color:
                    var(--text-main) !important;


          }



          body.black-theme table thead th,
          body.black-theme .table thead th {


               background:
                    var(--bg-table-head) !important;


               color:
                    white !important;


          }



          body.black-theme table tbody td,
          body.black-theme .table tbody td {


               background:
                    var(--bg-table) !important;


               color:
                    white !important;


          }



          body.black-theme table tbody tr:hover td {


               background:
                    var(--bg-hover) !important;


          }





          /* WHITE TABLE */


          body.white-theme table,
          body.white-theme .table {


               background: white !important;


               color: #212529 !important;


          }



          body.white-theme table thead th,
          body.white-theme .table thead th {


               background: #f1f3f5 !important;


               color: #6f88a0 !important;


          }




          /* =====================================================
DATATABLE
===================================================== */


          body.black-theme .dataTables_wrapper,


          body.black-theme .dataTables_info,


          body.black-theme .dataTables_filter,


          body.black-theme .dataTables_length {


               color: white !important;


          }




          body.black-theme table.dataTable tbody tr {


               background: #1e1e1e !important;


               color: white !important;


          }




          /* =====================================================
INPUT
===================================================== */


          body.black-theme input,
          body.black-theme select,
          body.black-theme textarea,
          body.black-theme .form-control {


               background: #242424 !important;


               color: white !important;


               border-color: #555 !important;


          }



          body.white-theme input,
          body.white-theme select,
          body.white-theme textarea,
          body.white-theme .form-control {


               background: white !important;


               color: #5f81a3 !important;


          }



          /* =====================================================
SIDEBAR
===================================================== */


          body.black-theme .sidebar,
          body.black-theme .sidebar-header {


               background: #777070 !important;


               color: white !important;


          }



          body.white-theme .sidebar,
          body.white-theme .sidebar-header {


               background: #78a9e0 !important;


               color: white !important;


          }



          body.black-theme .sidebar .nav-link {


               color: #ddd !important;


          }



          body.white-theme .sidebar .nav-link {


               color: white !important;


          }




          /* =====================================================
DROPDOWN
===================================================== */


          body.black-theme .dropdown-menu {


               background: #504c4c !important;


          }



          body.black-theme .dropdown-item {


               color: white !important;


          }



          body.black-theme .dropdown-item:hover {


               background: #9c9191 !important;


          }




          /* =====================================================
BUTTON ICON
===================================================== */


          #themeToggle {


               border: none;

               background: transparent;

               cursor: pointer;


               color: inherit;


          }


          #themeIcon {


               width: 20px;

               height: 20px;


          }
     </style>





     <script>
          /* =====================================================
                                                                                               THEME ENGINE
                                                                                               ===================================================== */


          (function() {


               "use strict";


               const KEY = "dashboard-theme";


               const BLACK = "black-theme";

               const WHITE = "white-theme";





               function setTheme(theme) {



                    document.body.classList.remove(
                         BLACK,
                         WHITE
                    );



                    document.body.classList.add(
                         theme
                    );



                    localStorage.setItem(
                         KEY,
                         theme
                    );



                    changeIcon(theme);



               }





               function changeIcon(theme) {



                    const icon =
                         document.getElementById(
                              "themeIcon"
                         );



                    const button =
                         document.getElementById(
                              "themeToggle"
                         );



                    if (!icon)
                         return;




                    if (theme === BLACK) {



                         icon.setAttribute(
                              "data-feather",
                              "sun"
                         );



                         button.title =
                              "Switch White Theme";



                    } else {



                         icon.setAttribute(
                              "data-feather",
                              "moon"
                         );



                         button.title =
                              "Switch Black Theme";



                    }




                    if (window.feather) {

                         feather.replace();

                    }



               }





               function toggleTheme() {


                    const dark =
                         document.body.classList.contains(
                              BLACK
                         );



                    if (dark) {

                         setTheme(WHITE);

                    } else {

                         setTheme(BLACK);

                    }



               }







               document.addEventListener(
                    "DOMContentLoaded",
                    function() {



                         let saved =
                              localStorage.getItem(KEY);



                         if (saved) {

                              setTheme(saved);

                         } else {

                              setTheme(WHITE);

                         }




                         const button =
                              document.getElementById(
                                   "themeToggle"
                              );



                         if (button) {


                              button.addEventListener(
                                   "click",
                                   toggleTheme
                              );


                         }



                    }

               );



          })();
     </script>
</head>
