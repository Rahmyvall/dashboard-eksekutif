@extends('layouts.app')


@section('title', 'Executive User Profile')


@section('content')


     <style>
          /* =====================================
                       PREMIUM USER PROFILE DASHBOARD
                    ===================================== */


          body {

               background: #f1f5f9;

          }



          /* HERO */


          .profile-header {


               background:

                    linear-gradient(135deg,
                         #a3a9c2,
                         #1e40af,
                         #0891b2);


               padding: 45px;


               border-radius: 32px;


               color: white;


               box-shadow:

                    0 25px 60px rgba(30, 64, 175, .35);


               display: flex;


               justify-content: space-between;


               align-items: center;


          }



          .profile-header h1 {


               font-size: 36px;

               font-weight: 900;


          }



          .profile-header p {


               opacity: .85;

               margin: 0;


          }





          .btn-back {


               background: white;


               color: #2563eb;


               border-radius: 15px;


               padding: 13px 25px;


               font-weight: 700;


          }






          /* PROFILE CARD */


          .profile-container {


               background: white;


               border-radius: 32px;


               padding: 40px;


               box-shadow:

                    0 20px 45px rgba(15, 23, 42, .08);


          }





          /* AVATAR */


          .avatar-premium {


               width: 150px;


               height: 150px;


               border-radius: 50%;


               background:


                    linear-gradient(135deg,
                         #2563eb,
                         #06b6d4);


               color: white;


               display: flex;


               align-items: center;


               justify-content: center;


               font-size: 55px;


               font-weight: 900;


               box-shadow:

                    0 20px 40px rgba(37, 99, 235, .35);


          }





          .user-name {


               font-size: 30px;


               font-weight: 900;


               color: #0f172a;


          }




          .user-email {


               color: #64748b;


          }





          /* STATUS */


          .status-card {


               padding: 18px;


               border-radius: 20px;


               background: #f8fafc;


          }





          .status-active {


               background: #dcfce7;

               color: #16a34a;

          }



          .status-inactive {


               background: #fef3c7;

               color: #d97706;

          }



          .status-suspended {


               background: #fee2e2;

               color: #dc2626;

          }




          .status-badge {


               padding: 10px 18px;


               border-radius: 20px;


               font-weight: 800;


          }







          /* INFORMATION */


          .info-card {


               background: #f8fafc;


               padding: 25px;


               border-radius: 22px;


               height: 100%;


               transition: .3s;


          }



          .info-card:hover {


               background: white;


               box-shadow:


                    0 15px 30px rgba(0, 0, 0, .08);


               transform: translateY(-5px);


          }



          .info-title {


               font-size: 12px;


               color: #64748b;


               font-weight: 800;


               text-transform: uppercase;


          }



          .info-value {


               font-size: 18px;


               font-weight: 800;


               color: #1e293b;


          }








          /* BUTTON */


          .btn-edit {


               background:

                    linear-gradient(135deg,
                         #2563eb,
                         #06b6d4);


               border: none;


               color: white;


               padding: 14px 35px;


               border-radius: 16px;


               font-weight: 800;


          }



          .btn-edit:hover {


               color: white;


               transform: translateY(-3px);


          }







          /* TIMELINE */


          .timeline {


               border-left: 3px solid #dbeafe;


               padding-left: 25px;


          }



          .timeline-item {


               margin-bottom: 25px;


          }



          .timeline-dot {


               width: 14px;


               height: 14px;


               background: #2563eb;


               border-radius: 50%;


               margin-left: -34px;


          }
     </style>








     <div class="container-fluid py-4">







          {{-- HEADER --}}


          <div class="profile-header mb-4">


               <div>


                    <h1>

                         Executive User Profile

                    </h1>


                    <p>

                         Detail informasi akun dan aktivitas pengguna sistem

                    </p>


               </div>




               <a href="{{ route('admin.users.index') }}" class="btn btn-back">


                    <i class="bi bi-arrow-left me-2"></i>

                    Kembali


               </a>



          </div>









          <div class="profile-container">






               <div class="text-center mb-5">



                    <div class="avatar-premium mx-auto">


                         {{ strtoupper(substr($user->name, 0, 1)) }}


                    </div>




                    <h2 class="user-name mt-4">


                         {{ $user->name }}


                    </h2>



                    <p class="user-email">


                         {{ $user->email }}


                    </p>



               </div>









               {{-- INFORMATION --}}


               <div class="row g-4">





                    <div class="col-xl-4 col-md-6">


                         <div class="info-card">


                              <div class="info-title">

                                   Nama Lengkap

                              </div>


                              <div class="info-value">

                                   {{ $user->name }}

                              </div>


                         </div>


                    </div>







                    <div class="col-xl-4 col-md-6">


                         <div class="info-card">


                              <div class="info-title">

                                   Email

                              </div>


                              <div class="info-value">

                                   {{ $user->email }}

                              </div>


                         </div>


                    </div>







                    <div class="col-xl-4 col-md-6">


                         <div class="info-card">


                              <div class="info-title">

                                   Status Akun

                              </div>



                              <div class="mt-2">


                                   @if ($user->status == 'active')
                                        <span class="status-badge status-active">

                                             ● Active

                                        </span>
                                   @elseif($user->status == 'inactive')
                                        <span class="status-badge status-inactive">

                                             ● Inactive

                                        </span>
                                   @else
                                        <span class="status-badge status-suspended">

                                             ● Suspended

                                        </span>
                                   @endif


                              </div>


                         </div>


                    </div>









                    <div class="col-xl-4 col-md-6">


                         <div class="info-card">


                              <div class="info-title">

                                   Login Terakhir

                              </div>


                              <div class="info-value mt-2">


                                   {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum Login' }}


                              </div>


                         </div>


                    </div>







                    <div class="col-xl-4 col-md-6">


                         <div class="info-card">


                              <div class="info-title">

                                   Email Verification

                              </div>


                              <div class="info-value mt-2">


                                   @if ($user->email_verified_at)
                                        <span class="text-success">

                                             Verified

                                        </span>
                                   @else
                                        <span class="text-warning">

                                             Belum Verified

                                        </span>
                                   @endif


                              </div>


                         </div>


                    </div>








                    <div class="col-xl-4 col-md-6">


                         <div class="info-card">


                              <div class="info-title">

                                   Tanggal Registrasi

                              </div>


                              <div class="info-value mt-2">


                                   {{ $user->created_at->format('d M Y') }}


                              </div>


                         </div>


                    </div>




               </div>









               <hr class="my-5">








               <h5 class="fw-bold mb-4">

                    Aktivitas Pengguna

               </h5>





               <div class="timeline">


                    <div class="timeline-item">


                         <div class="timeline-dot"></div>


                         <strong>

                              Akun dibuat

                         </strong>


                         <p class="text-muted mb-0">

                              {{ $user->created_at->diffForHumans() }}

                         </p>


                    </div>






                    <div class="timeline-item">


                         <div class="timeline-dot"></div>


                         <strong>

                              Login terakhir

                         </strong>


                         <p class="text-muted mb-0">


                              {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum ada aktivitas login' }}


                         </p>


                    </div>


               </div>









               <div class="text-end mt-5">


                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-edit">


                         <i class="bi bi-pencil me-2"></i>


                         Edit Pengguna


                    </a>


               </div>






          </div>






     </div>



@endsection
