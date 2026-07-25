@extends('layouts.app')


@section('title', 'Edit Pengguna')


@section('content')


     <style>
          /* ==============================
                                         PREMIUM USER EDIT PAGE
                                        ============================== */


          body {

               background: #f8fafc;

          }



          /* HEADER */


          .edit-header {


               background:

                    linear-gradient(135deg,
                         #eaebee,
                         #1e40af,
                         #0891b2);


               padding: 45px;


               border-radius: 30px;


               color: white;


               display: flex;


               justify-content: space-between;


               align-items: center;


               box-shadow:


                    0 25px 60px rgba(2, 6, 23, .35);


          }



          .edit-header h2 {


               font-size: 36px;


               font-weight: 900;


               margin-bottom: 8px;


          }


          .edit-header p {


               margin: 0;


               opacity: .85;


          }






          .btn-back {


               background: white;


               color: #1d4ed8;


               padding: 14px 28px;


               border-radius: 16px;


               font-weight: 800;


               text-decoration: none;


               transition: .3s;


          }



          .btn-back:hover {


               transform: translateY(-3px);


               color: #1e40af;


          }







          /* MAIN CARD */


          .edit-card {


               background: white;


               border-radius: 32px;


               padding: 40px;


               box-shadow:


                    0 20px 50px rgba(15, 23, 42, .08);


          }







          /* PROFILE */


          .profile-card {


               background:


                    linear-gradient(160deg,
                         #eff6ff,
                         #ffffff);


               border-radius: 28px;


               padding: 40px;


               text-align: center;


               border: 1px solid #dbeafe;


          }





          .avatar-large {


               width: 160px;


               height: 160px;


               border-radius: 50%;


               background:


                    linear-gradient(135deg,
                         #2563eb,
                         #06b6d4);


               display: flex;


               align-items: center;


               justify-content: center;


               margin: auto;


               color: white;


               font-size: 60px;


               font-weight: 900;


               border: 8px solid white;


               box-shadow:


                    0 20px 45px rgba(37, 99, 235, .35);


          }







          .account-badge {


               display: inline-block;


               margin-top: 20px;


               padding: 10px 25px;


               border-radius: 30px;


               background: #dbeafe;


               color: #2563eb;


               font-weight: 800;


          }







          /* FORM */


          .section-header {


               margin-bottom: 30px;


          }



          .section-header h4 {


               font-weight: 900;


               color: #0f172a;


          }



          .section-header p {


               color: #64748b;


          }







          .form-label {


               font-weight: 700;


               color: #334155;


          }




          .input-wrapper {


               position: relative;


          }



          .input-wrapper i {


               position: absolute;


               left: 16px;


               top: 17px;


               color: #64748b;


          }



          .form-control,
          .form-select {


               height: 54px;


               border-radius: 16px;


               border: 1px solid #e2e8f0;


          }



          .input-wrapper .form-control {


               padding-left: 48px;


          }



          .form-control:focus,
          .form-select:focus {


               border-color: #2563eb;


               box-shadow:


                    0 0 0 .25rem rgba(37, 99, 235, .15);


          }








          /* SECURITY */


          .security-box {


               background: #f8fafc;


               border-radius: 22px;


               padding: 25px;


               display: flex;


               gap: 20px;


               align-items: center;


          }



          .security-icon {


               width: 55px;


               height: 55px;


               border-radius: 18px;


               background: #dbeafe;


               color: #2563eb;


               display: flex;


               align-items: center;


               justify-content: center;


               font-size: 25px;


          }






          /* BUTTON */


          .btn-save {


               background:


                    linear-gradient(135deg,
                         #2563eb,
                         #06b6d4);


               color: white;


               border: none;


               padding: 15px 40px;


               border-radius: 18px;


               font-weight: 800;


               box-shadow:


                    0 15px 35px rgba(37, 99, 235, .35);


               transition: .3s;


          }



          .btn-save:hover {


               transform: translateY(-3px);


               color: white;


          }



          .btn-cancel {


               border-radius: 18px;


               padding: 15px 35px;


               font-weight: 700;


          }
     </style>








     <div class="container-fluid py-4">







          {{-- HEADER --}}


          <div class="edit-header mb-4">


               <div>


                    <h2>

                         Edit Pengguna

                    </h2>


                    <p>

                         Kelola informasi akun dan keamanan pengguna sistem

                    </p>


               </div>




               <a href="{{ route('admin.users.index') }}" class="btn-back">


                    <i class="bi bi-arrow-left me-2"></i>

                    Kembali


               </a>


          </div>









          @if ($errors->any())


               <div class="alert alert-danger">


                    <ul class="mb-0">


                         @foreach ($errors->all() as $error)
                              <li>

                                   {{ $error }}

                              </li>
                         @endforeach


                    </ul>


               </div>


          @endif







          <div class="edit-card">





               <form method="POST" action="{{ route('admin.users.update', $user->id) }}">


                    @csrf

                    @method('PUT')







                    <div class="row g-5">






                         {{-- PROFILE --}}


                         <div class="col-xl-4">



                              <div class="profile-card">


                                   <div class="avatar-large">


                                        {{ strtoupper(substr($user->name, 0, 1)) }}


                                   </div>




                                   <h3 class="fw-bold mt-4">


                                        {{ $user->name }}


                                   </h3>



                                   <p class="text-muted">


                                        {{ $user->email }}


                                   </p>



                                   <span class="account-badge">


                                        User Account


                                   </span>


                              </div>



                         </div>










                         {{-- FORM --}}


                         <div class="col-xl-8">



                              <div class="section-header">


                                   <h4>

                                        Informasi Akun

                                   </h4>


                                   <p>

                                        Perbarui data utama pengguna

                                   </p>


                              </div>







                              <div class="row g-4">


                                   {{-- Nama Lengkap --}}
                                   <div class="col-md-6">

                                        <label class="form-label">
                                             Nama Lengkap
                                        </label>


                                        <div class="input-wrapper">

                                             <i class="bi bi-person"></i>

                                             <input type="text" name="name" class="form-control"
                                                  value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap">

                                        </div>

                                   </div>





                                   {{-- Email --}}
                                   <div class="col-md-6">

                                        <label class="form-label">
                                             Email
                                        </label>


                                        <div class="input-wrapper">

                                             <i class="bi bi-envelope"></i>

                                             <input type="email" name="email" class="form-control"
                                                  value="{{ old('email', $user->email) }}"
                                                  placeholder="Masukkan email pengguna">

                                        </div>

                                   </div>



                                   {{-- Status Full Width --}}
                                   <div class="col-md-12">


                                        <label class="form-label">

                                             Status Akun

                                        </label>


                                        <select name="status" class="form-select">


                                             <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>

                                                  Active

                                             </option>


                                             <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>

                                                  Inactive

                                             </option>


                                             <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>

                                                  Suspended

                                             </option>


                                        </select>


                                   </div>

                                   {{-- Password Full Width --}}
                                   <div class="col-md-12">


                                        <label class="form-label">

                                             Password Baru

                                        </label>



                                        <div class="input-wrapper">


                                             <i class="bi bi-lock"></i>


                                             <input type="password" name="password" class="form-control"
                                                  placeholder="Kosongkan jika tidak ingin mengganti password">


                                        </div>


                                        <small class="text-muted">

                                             Password hanya diperbarui apabila kolom ini diisi.

                                        </small>


                                   </div>



                              </div>





                              <hr class="my-5">






                              <div class="security-box">


                                   <div class="security-icon">


                                        <i class="bi bi-shield-check"></i>


                                   </div>



                                   <div>


                                        <h6 class="fw-bold mb-1">

                                             Keamanan Akun

                                        </h6>


                                        <p class="text-muted mb-0">

                                             Password hanya diperbarui apabila kolom password diisi.

                                        </p>


                                   </div>


                              </div>







                         </div>






                    </div>









                    <div class="d-flex justify-content-end gap-3 mt-5">


                         <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-cancel">


                              Batal


                         </a>




                         <button type="submit" class="btn btn-save">


                              <i class="bi bi-save me-2"></i>

                              Simpan Perubahan


                         </button>


                    </div>






               </form>


          </div>






     </div>



@endsection
