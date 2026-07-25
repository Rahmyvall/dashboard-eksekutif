@extends('layouts.app')


@section('title', 'Tambah Pengguna Baru')



@section('content')


     <style>
          /* =====================================
                            CORPORATE USER CREATION UI
                         ===================================== */


          body {

               background: #f1f5f9;

          }



          /* HEADER */


          .create-header {


               background:

                    linear-gradient(135deg,
                         #c6d1e9,
                         #1e40af);


               padding: 40px;


               border-radius: 26px;


               color: white;


               display: flex;


               justify-content: space-between;


               align-items: center;


               box-shadow:

                    0 20px 45px rgba(142, 151, 172, 0.25);


          }



          .create-header h1 {


               font-size: 32px;

               font-weight: 900;

               margin-bottom: 8px;


          }



          .create-header p {


               opacity: .8;

               margin: 0;


          }






          .btn-back {


               background: rgba(255, 255, 255, .15);


               border:

                    1px solid rgba(255, 255, 255, .25);


               color: white;


               padding: 14px 25px;


               border-radius: 15px;


               font-weight: 600;


               transition: .3s;


          }



          .btn-back:hover {


               background: white;


               color: #2563eb;


          }








          /* MAIN */


          .create-card {


               background: white;


               padding: 35px;


               border-radius: 28px;


               box-shadow:


                    0 15px 35px rgba(15, 23, 42, .08);


          }







          .section-card {


               background: #f8fafc;


               padding: 25px;


               border-radius: 22px;


               border: 1px solid #e2e8f0;


          }





          .section-title {


               font-size: 18px;


               font-weight: 800;


               color: #0f172a;


               margin-bottom: 20px;


          }









          /* PHOTO */


          .photo-container {


               text-align: center;


          }



          .avatar-box {


               width: 170px;


               height: 170px;


               margin: auto;


               border-radius: 50%;


               overflow: hidden;


               border: 8px solid white;


               box-shadow:

                    0 10px 30px rgba(0, 0, 0, .15);


          }



          .avatar-box img {


               width: 100%;


               height: 100%;


               object-fit: cover;


          }






          .upload-btn {


               margin-top: 20px;


               border-radius: 14px;


               padding: 12px 25px;


          }








          /* INPUT */


          .form-label {


               font-weight: 700;


               color: #334155;


          }




          .input-box {


               position: relative;


          }




          .input-box i {


               position: absolute;


               left: 16px;


               top: 16px;


               color: #64748b;


          }




          .form-control {


               height: 52px;


               padding-left: 45px;


               border-radius: 15px;


               border: 1px solid #cbd5e1;


          }



          .form-control:focus {


               border-color: #2563eb;


               box-shadow:

                    0 0 0 .2rem rgba(37, 99, 235, .15);


          }







          /* ROLE CARD */


          .role-grid {


               display: grid;


               grid-template-columns: repeat(3, 1fr);


               gap: 15px;


          }




          .role-item input {


               display: none;


          }




          .role-item label {


               background: white;


               border: 2px solid #e2e8f0;


               border-radius: 16px;


               padding: 18px;


               text-align: center;


               cursor: pointer;


               font-weight: 700;


               transition: .3s;


          }





          .role-item label:hover {


               border-color: #2563eb;


          }





          .role-item input:checked+label {


               background: #2563eb;


               color: white;


               border-color: #2563eb;


          }










          /* STATUS */


          .status-grid {


               display: flex;


               gap: 15px;


          }





          .status-item input {


               display: none;


          }





          .status-item label {


               padding: 12px 20px;


               background: white;


               border-radius: 30px;


               border: 2px solid #e2e8f0;


               cursor: pointer;


               font-weight: 700;


          }





          .status-item input:checked+label {


               background: #16a34a;


               color: white;


               border-color: #16a34a;


          }









          /* BUTTON */


          .footer-action {


               border-top:

                    1px solid #e2e8f0;


               margin-top: 30px;


               padding-top: 25px;


          }



          .btn-save {


               background:

                    linear-gradient(135deg,
                         #2563eb,
                         #1d4ed8);


               color: white;


               padding: 15px 40px;


               border-radius: 16px;


               font-weight: 800;


               border: none;


               box-shadow:

                    0 10px 20px rgba(37, 99, 235, .25);


          }



          .btn-save:hover {


               transform: translateY(-2px);


               color: white;


          }
     </style>








     <div class="container-fluid py-4">





          {{-- HEADER --}}


          <div class="create-header mb-4">


               <div>


                    <h1>

                         <i class="bi bi-person-plus-fill"></i>

                         Tambah Pengguna

                    </h1>


                    <p>

                         Membuat akun baru dan mengatur hak akses pengguna perusahaan

                    </p>


               </div>




               <a href="{{ route('admin.users.index') }}" class="btn-back">


                    <i class="bi bi-arrow-left"></i>

                    Kembali


               </a>


          </div>










          @if ($errors->any())


               <div class="alert alert-danger">


                    <ul class="mb-0">


                         @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                         @endforeach


                    </ul>


               </div>


          @endif







          <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">


               @csrf





               <div class="create-card">





                    <div class="row g-4">






                         {{-- LEFT --}}


                         <div class="col-xl-4">



                              <div class="section-card">



                                   <h5 class="section-title">

                                        Foto Profil

                                   </h5>




                                   <div class="photo-container">



                                        <div class="avatar-box">


                                             <img id="preview"
                                                  src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff&size=200">


                                        </div>





                                        <label class="btn btn-outline-primary upload-btn">


                                             <i class="bi bi-camera"></i>

                                             Upload Foto


                                             <input type="file" name="photo" hidden accept="image/*"
                                                  onchange="previewImage(event)">


                                        </label>



                                        <p class="text-muted mt-3">

                                             PNG/JPG maksimal 2MB

                                        </p>



                                   </div>



                              </div>





                         </div>









                         {{-- RIGHT --}}


                         <div class="col-xl-8">






                              <div class="section-card mb-4">


                                   <h5 class="section-title">

                                        Informasi Pengguna

                                   </h5>



                                   <div class="row g-4">





                                        <div class="col-md-6">


                                             <label class="form-label">

                                                  Nama Lengkap

                                             </label>


                                             <div class="input-box">


                                                  <i class="bi bi-person"></i>


                                                  <input class="form-control" name="name" value="{{ old('name') }}"
                                                       placeholder="Nama lengkap">


                                             </div>


                                        </div>






                                        <div class="col-md-6">


                                             <label class="form-label">

                                                  Username

                                             </label>


                                             <div class="input-box">


                                                  <i class="bi bi-person-badge"></i>


                                                  <input class="form-control" name="username" value="{{ old('username') }}"
                                                       placeholder="Username">


                                             </div>


                                        </div>






                                        <div class="col-md-6">


                                             <label class="form-label">

                                                  Email

                                             </label>


                                             <div class="input-box">


                                                  <i class="bi bi-envelope"></i>


                                                  <input type="email" class="form-control" name="email"
                                                       value="{{ old('email') }}" placeholder="email@company.com">


                                             </div>


                                        </div>







                                        <div class="col-md-6">


                                             <label class="form-label">

                                                  Nomor Telepon

                                             </label>


                                             <div class="input-box">


                                                  <i class="bi bi-telephone"></i>


                                                  <input class="form-control" name="phone" value="{{ old('phone') }}"
                                                       placeholder="08xxxxxxxx">


                                             </div>


                                        </div>





                                        <div class="col-md-12">


                                             <label class="form-label">

                                                  Password

                                             </label>


                                             <div class="input-box">


                                                  <i class="bi bi-lock"></i>


                                                  <input type="password" class="form-control" name="password"
                                                       placeholder="Minimal 8 karakter">


                                             </div>


                                        </div>



                                   </div>



                              </div>









                              <div class="section-card">


                                   <h5 class="section-title">

                                        Hak Akses Sistem

                                   </h5>




                                   <label class="form-label">

                                        Role Pengguna

                                   </label>


                                   <div class="role-grid mb-4">

                                        @forelse ($roles as $role)
                                             <div class="role-item">

                                                  <input type="radio" name="role_id" id="role_{{ $role->id }}"
                                                       value="{{ $role->id }}"
                                                       {{ old('role_id') == $role->id ? 'checked' : '' }}>

                                                  <label for="role_{{ $role->id }}">

                                                       {{ strtoupper(str_replace('_', ' ', $role->name)) }}

                                                  </label>

                                             </div>


                                        @empty

                                             <div class="alert alert-warning">
                                                  Role belum tersedia.
                                             </div>
                                        @endforelse


                                   </div>








                                   <label class="form-label">

                                        Status Akun

                                   </label>



                                   <div class="status-grid">


                                        @foreach ($statuses as $status)
                                             <div class="status-item">


                                                  <input type="radio" name="status" id="status{{ $status }}"
                                                       value="{{ $status }}">


                                                  <label for="status{{ $status }}">

                                                       {{ ucfirst($status) }}

                                                  </label>


                                             </div>
                                        @endforeach



                                   </div>



                              </div>









                              <div class="footer-action text-end">


                                   <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-lg me-2">


                                        Batal


                                   </a>




                                   <button class="btn-save">


                                        <i class="bi bi-check-circle"></i>

                                        Simpan Pengguna


                                   </button>



                              </div>







                         </div>






                    </div>




          </form>




     </div>








     <script>
          function previewImage(event) {


               let img =
                    document.getElementById('preview');


               img.src =
                    URL.createObjectURL(
                         event.target.files[0]
                    );


          }
     </script>






@endsection
