@extends('layouts.app')

@section('title', 'Detail Role')

@section('content')

     <style>
          .role-detail-page {

               min-height: calc(100vh - 80px);

               padding: 35px 15px 60px;

               background:
                    radial-gradient(circle at top left,
                         rgba(99, 102, 241, .18),
                         transparent 25%),

                    radial-gradient(circle at bottom right,
                         rgba(236, 72, 153, .15),
                         transparent 25%),

                    linear-gradient(135deg,
                         #f8fafc,
                         #fff7ed);

          }



          .role-card {

               overflow: hidden;

               border-radius: 28px;

               background: #ffffff;

               border: 1px solid #e2e8f0;

               box-shadow:
                    0 25px 60px rgba(15, 23, 42, .12);

          }




          /*
     |--------------------------------------------------------------------------
     | HEADER
     |--------------------------------------------------------------------------
     */


          .role-hero {

               padding: 40px;

               color: white;

               background:

                    linear-gradient(120deg,
                         #2563eb,
                         #7c3aed,
                         #db2777);

          }



          .role-badge {

               display: inline-flex;

               align-items: center;

               gap: 8px;

               padding: 8px 15px;

               border-radius: 999px;

               margin-bottom: 15px;

               font-size: 12px;

               font-weight: 800;

               letter-spacing: .08em;

               background:
                    rgba(255, 255, 255, .18);

               border:
                    1px solid rgba(255, 255, 255, .3);

          }



          .role-hero h1 {

               margin: 0;

               font-size: 38px;

               font-weight: 900;

          }



          .role-hero p {

               margin-top: 10px;

               opacity: .9;

          }



          .hero-actions {

               margin-top: 25px;

               display: flex;

               gap: 12px;

          }



          .btn-action {

               display: inline-flex;

               align-items: center;

               gap: 8px;

               padding: 12px 20px;

               border-radius: 14px;

               font-weight: 700;

               text-decoration: none;

          }



          .btn-edit {

               color: #4f46e5;

               background: white;

          }



          .btn-back {

               color: white;

               background:
                    rgba(255, 255, 255, .18);

               border:
                    1px solid rgba(255, 255, 255, .3);

          }



          /*
     |--------------------------------------------------------------------------
     | BODY
     |--------------------------------------------------------------------------
     */


          .role-body {

               padding: 40px;

          }



          .info-grid {

               display: grid;

               grid-template-columns:
                    repeat(auto-fit, minmax(250px, 1fr));

               gap: 25px;

          }



          .info-card {

               padding: 25px;

               border-radius: 22px;

               border: 1px solid #e2e8f0;

               background: #ffffff;

               transition: .25s;

          }



          .info-card:hover {

               transform: translateY(-3px);

               box-shadow:
                    0 15px 35px rgba(15, 23, 42, .08);

          }



          .info-icon {

               width: 55px;

               height: 55px;

               display: flex;

               align-items: center;

               justify-content: center;

               border-radius: 18px;

               color: white;

               font-size: 24px;

               margin-bottom: 18px;

               background:

                    linear-gradient(135deg,
                         #6366f1,
                         #ec4899);

          }



          .info-label {

               color: #64748b;

               font-size: 13px;

               font-weight: 700;

               text-transform: uppercase;

               letter-spacing: .05em;

          }



          .info-value {

               margin-top: 8px;

               color: #172033;

               font-size: 20px;

               font-weight: 850;

          }





          /*
     |--------------------------------------------------------------------------
     | PERMISSION / USER
     |--------------------------------------------------------------------------
     */


          .section-box {

               margin-top: 30px;

               padding: 25px;

               border-radius: 22px;

               background:

                    linear-gradient(135deg,
                         #eef2ff,
                         #faf5ff);

               border: 1px solid #ddd6fe;

          }



          .section-title {

               display: flex;

               align-items: center;

               gap: 12px;

               margin-bottom: 20px;

          }



          .section-title i {

               width: 42px;

               height: 42px;

               display: flex;

               align-items: center;

               justify-content: center;

               border-radius: 14px;

               color: white;

               background: #6366f1;

          }



          .section-title h4 {

               margin: 0;

               font-weight: 850;

          }



          .badge-item {

               display: inline-flex;

               align-items: center;

               gap: 7px;

               padding: 8px 14px;

               margin: 5px;

               border-radius: 999px;

               background: white;

               border: 1px solid #ddd6fe;

               color: #4338ca;

               font-weight: 700;

               font-size: 13px;

          }



          .empty-state {

               padding: 25px;

               text-align: center;

               color: #64748b;

          }



          .empty-state i {

               display: block;

               font-size: 40px;

               margin-bottom: 10px;

          }
     </style>



     <div class="role-detail-page">


          <div class="container-fluid">


               <div class="role-card">



                    {{-- HEADER --}}
                    <div class="role-hero">


                         <div class="role-badge">

                              <i class="bi bi-eye-fill"></i>

                              ROLE DETAIL

                         </div>



                         <h1>

                              {{ $role->name }}

                         </h1>



                         <p>

                              Detail informasi role dan permission yang terhubung.

                         </p>




                         <div class="hero-actions">


                              <a href="{{ route('super-admin.roles.edit', $role->id) }}" class="btn-action btn-edit">

                                   <i class="bi bi-pencil-square"></i>

                                   Edit Role

                              </a>



                              <a href="{{ route('super-admin.roles.index') }}" class="btn-action btn-back">

                                   <i class="bi bi-arrow-left"></i>

                                   Kembali

                              </a>



                         </div>



                    </div>





                    {{-- BODY --}}
                    <div class="role-body">





                         <div class="info-grid">



                              <div class="info-card">


                                   <div class="info-icon">

                                        <i class="bi bi-shield-lock"></i>

                                   </div>


                                   <div class="info-label">

                                        Nama Role

                                   </div>


                                   <div class="info-value">

                                        {{ $role->name }}

                                   </div>


                              </div>







                              <div class="info-card">


                                   <div class="info-icon">

                                        <i class="bi bi-globe"></i>

                                   </div>


                                   <div class="info-label">

                                        Guard Name

                                   </div>


                                   <div class="info-value">

                                        {{ $role->guard_name }}

                                   </div>


                              </div>







                              <div class="info-card">


                                   <div class="info-icon">

                                        <i class="bi bi-calendar-check"></i>

                                   </div>


                                   <div class="info-label">

                                        Dibuat

                                   </div>


                                   <div class="info-value">

                                        {{ optional($role->created_at)->format('d M Y') }}

                                   </div>


                              </div>







                              <div class="info-card">


                                   <div class="info-icon">

                                        <i class="bi bi-arrow-clockwise"></i>

                                   </div>


                                   <div class="info-label">

                                        Update Terakhir

                                   </div>


                                   <div class="info-value">

                                        {{ optional($role->updated_at)->format('d M Y') }}

                                   </div>


                              </div>



                         </div>









                         {{-- USERS --}}
                         <div class="section-box">


                              <div class="section-title">


                                   <i class="bi bi-people-fill"></i>


                                   <h4>

                                        Pengguna Role

                                   </h4>


                              </div>



                              @if ($role->users && $role->users->count())


                                   @foreach ($role->users as $user)
                                        <span class="badge-item">

                                             <i class="bi bi-person-fill"></i>

                                             {{ $user->name }}

                                        </span>
                                   @endforeach
                              @else
                                   <div class="empty-state">

                                        <i class="bi bi-person-x"></i>

                                        Belum ada pengguna menggunakan role ini.

                                   </div>


                              @endif



                         </div>








                         {{-- PERMISSIONS --}}
                         <div class="section-box">


                              <div class="section-title">


                                   <i class="bi bi-key-fill"></i>


                                   <h4>

                                        Permission

                                   </h4>


                              </div>




                              @if ($role->permissions && $role->permissions->count())



                                   @foreach ($role->permissions as $permission)
                                        <span class="badge-item">


                                             <i class="bi bi-check-circle"></i>


                                             {{ $permission->name }}


                                        </span>
                                   @endforeach
                              @else
                                   <div class="empty-state">

                                        <i class="bi bi-key"></i>

                                        Belum ada permission.

                                   </div>


                              @endif



                         </div>







                    </div>



               </div>



          </div>



     </div>



@endsection
