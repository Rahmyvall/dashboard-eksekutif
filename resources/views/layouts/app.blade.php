<!DOCTYPE html>
<html lang="en">

<head>

     <title>
          Dashboard Monitoring Kinerja & Kepuasan Pelanggan |
          @yield('page-title', 'Dashboard Eksekutif')
     </title>


     @include('layouts.header')

</head>


<body>


     {{-- Sidebar --}}
     @include('layouts.sidebar')



     {{-- Content --}}
     <div class="content">


          {{-- Header --}}
          @include('layouts.topbar')



          {{-- Content Header --}}
          <div class="content-header">

               <div>

                    <nav aria-label="breadcrumb">

                         <ol class="breadcrumb">

                              <li class="breadcrumb-item">
                                   <a href="{{ route('dashboard') }}">
                                   </a>
                              </li>


                              @yield('breadcrumb')


                         </ol>

                    </nav>



                    @hasSection('page-description')
                         <p class="text-muted mb-0">

                              @yield('page-description')

                         </p>
                    @endif


               </div>


               <div>

                    @yield('page-action')

               </div>


          </div>




          {{-- Halaman --}}
          <div class="content-body">

               @yield('content')

          </div>



     </div>



     {{-- Javascript Library --}}
     @include('layouts.script')



     {{-- Javascript Custom Halaman --}}
     @stack('script')


</body>

</html>
