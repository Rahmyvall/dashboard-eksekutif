<!DOCTYPE html>
<html lang="en">

<head>

     <title>
          Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa |
          @yield('page-title', $__env->yieldContent('title', 'Dashboard Eksekutif'))
     </title>


     @include('layouts.header')

</head>

@php
     $authUser = auth()->user();
     $activeRoleName = (string) session('active_role_name', '');

     $normalizedActiveRole = strtolower(str_replace(['-', ' '], '_', trim($activeRoleName)));
     $isReadOnlyRole = in_array(
         $normalizedActiveRole,
         ['direktur_utama', 'direkturutama', 'executive', 'manager_departemen', 'managerdepartemen'],
         true,
     );

     if (!$isReadOnlyRole && $authUser && method_exists($authUser, 'hasAnyRole')) {
         $hasWritableRole = $authUser->hasAnyRole([
             'super_admin',
             'hrd_manager',
             'karyawan',
             'admin_pelayanan',
             'admin_operasional',
             'finance_staff',
             'auditor_internal',
         ]);

         if ($normalizedActiveRole !== '' || $hasWritableRole) {
             $isReadOnlyRole = false;
         } else {
             $isReadOnlyRole = $authUser->hasAnyRole(['direktur_utama', 'manager_departemen']);
         }
     }

     $bodyClass = $isReadOnlyRole ? 'readonly-role' : '';
@endphp

<body class="{{ $bodyClass }}" data-read-only-role="{{ $isReadOnlyRole ? '1' : '0' }}">
     <script>
          window.__READ_ONLY_ROLE__ = @json($isReadOnlyRole);
     </script>


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
