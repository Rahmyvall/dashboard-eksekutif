@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

     <style>
          .role-edit-page {

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

               background: white;

               border: 1px solid #e2e8f0;

               box-shadow:
                    0 25px 60px rgba(15, 23, 42, .12);

          }



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

               margin-bottom: 15px;

               border-radius: 999px;

               background: rgba(255, 255, 255, .18);

               border: 1px solid rgba(255, 255, 255, .3);

               font-size: 12px;

               font-weight: 800;

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



          .btn-back {

               display: inline-flex;

               align-items: center;

               gap: 8px;

               padding: 12px 20px;

               margin-top: 20px;

               color: white;

               text-decoration: none;

               border-radius: 14px;

               background: rgba(255, 255, 255, .18);

               border: 1px solid rgba(255, 255, 255, .3);

          }



          .btn-back:hover {

               background: white;

               color: #4f46e5;

          }



          .role-body {

               padding: 40px;

          }



          .section-title {

               display: flex;

               align-items: center;

               gap: 15px;

               margin-bottom: 30px;

          }



          .section-icon {

               width: 52px;

               height: 52px;

               display: flex;

               align-items: center;

               justify-content: center;

               color: white;

               font-size: 22px;

               border-radius: 16px;

               background:
                    linear-gradient(135deg,
                         #4f46e5,
                         #a855f7);

          }



          .section-title h3 {

               margin: 0;

               font-weight: 850;

               color: #172033;

          }



          .section-title p {

               margin: 3px 0 0;

               color: #64748b;

               font-size: 13px;

          }



          .input-box {

               position: relative;

          }



          .input-icon {

               position: absolute;

               top: 50%;

               left: 16px;

               transform: translateY(-50%);

               color: #818cf8;

          }



          .form-control-modern,
          .form-select-modern {


               width: 100%;

               height: 55px;

               border-radius: 15px;

               border: 1px solid #cbd5e1;

               padding-left: 45px;

          }



          .form-select-modern {

               padding-left: 15px;

          }



          .form-control-modern:focus,
          .form-select-modern:focus {


               border-color: #6366f1;

               box-shadow:

                    0 0 0 5px rgba(99, 102, 241, .12);

          }



          .preview-box {


               margin-top: 30px;

               padding: 25px;

               border-radius: 22px;

               background:

                    linear-gradient(135deg,
                         #eef2ff,
                         #faf5ff);

               border: 1px solid #ddd6fe;


          }



          .preview-icon {


               width: 60px;

               height: 60px;

               display: flex;

               align-items: center;

               justify-content: center;

               color: white;

               font-size: 25px;

               border-radius: 20px;

               background:

                    linear-gradient(135deg,
                         #6366f1,
                         #ec4899);

          }



          .info-box {


               margin-top: 25px;

               padding: 20px;

               border-radius: 18px;

               background: #eff6ff;

               border: 1px solid #bfdbfe;

               color: #1e40af;

          }



          .btn-update {


               padding: 13px 28px;

               border-radius: 15px;

               font-weight: 800;

               color: white;

               border: none;

               background:

                    linear-gradient(135deg,
                         #2563eb,
                         #7c3aed);


          }



          .btn-update:hover {

               transform: translateY(-2px);

               box-shadow:

                    0 15px 30px rgba(37, 99, 235, .3);

          }
     </style>



     <div class="role-edit-page">


          <div class="container-fluid">


               <div class="role-card">



                    {{-- HEADER --}}
                    <div class="role-hero">


                         <div class="role-badge">

                              <i class="bi bi-pencil-square"></i>

                              Role Management

                         </div>



                         <h1>
                              Edit Role
                         </h1>



                         <p>

                              Perbarui nama role dan guard akses sistem.

                         </p>



                         <a href="{{ route('super-admin.roles.index') }}" class="btn-back">

                              <i class="bi bi-arrow-left"></i>

                              Kembali

                         </a>



                    </div>





                    <div class="role-body">



                         @if ($errors->any())

                              <div class="alert alert-danger rounded-4">

                                   <ul class="mb-0">

                                        @foreach ($errors->all() as $error)
                                             <li>
                                                  {{ $error }}
                                             </li>
                                        @endforeach

                                   </ul>

                              </div>

                         @endif





                         <form method="POST" action="{{ route('super-admin.roles.update', $role->id) }}">


                              @csrf

                              @method('PUT')





                              <div class="section-title">


                                   <div class="section-icon">

                                        <i class="bi bi-shield-check"></i>

                                   </div>



                                   <div>

                                        <h3>
                                             Informasi Role
                                        </h3>


                                        <p>
                                             Edit data role sesuai kebutuhan akses sistem.
                                        </p>


                                   </div>


                              </div>







                              <div class="row g-4">



                                   <div class="col-lg-6">


                                        <label class="form-label fw-bold">

                                             Nama Role

                                             <span class="text-danger">*</span>

                                        </label>



                                        <div class="input-box">


                                             <i class="bi bi-person-badge input-icon"></i>



                                             <input type="text" name="name" id="roleName" class="form-control-modern"
                                                  value="{{ old('name', $role->name) }}" placeholder="contoh: manager"
                                                  required>


                                        </div>


                                        <small class="text-muted">

                                             Gunakan format lowercase dan underscore.

                                        </small>



                                   </div>







                                   <div class="col-lg-6">


                                        <label class="form-label fw-bold">

                                             Guard Name

                                             <span class="text-danger">*</span>

                                        </label>



                                        <select name="guard_name" id="guardName" class="form-select-modern">


                                             <option value="web" @selected(old('guard_name', $role->guard_name) == 'web')>

                                                  web

                                             </option>


                                             <option value="api" @selected(old('guard_name', $role->guard_name) == 'api')>

                                                  api

                                             </option>



                                        </select>


                                   </div>



                              </div>








                              <div class="preview-box">


                                   <div class="d-flex align-items-center gap-3">


                                        <div class="preview-icon">


                                             <i class="bi bi-shield-lock-fill"></i>


                                        </div>




                                        <div>


                                             <h5 class="mb-1 fw-bold" id="previewName">

                                                  {{ $role->name }}

                                             </h5>


                                             <span class="text-muted">

                                                  Guard:

                                                  <b id="previewGuard">
                                                       {{ $role->guard_name }}
                                                  </b>


                                             </span>


                                        </div>



                                   </div>


                              </div>







                              <div class="info-box">


                                   <i class="bi bi-database-fill-check me-2"></i>


                                   Data akan diperbarui pada tabel:

                                   <b>roles</b>


                                   <br>


                                   Field:

                                   <b>name</b>

                                   dan

                                   <b>guard_name</b>



                              </div>








                              <div class="d-flex justify-content-between mt-4">


                                   <a href="{{ route('super-admin.roles.index') }}"
                                        class="btn btn-light border rounded-3 px-4">


                                        <i class="bi bi-x-lg"></i>

                                        Batal


                                   </a>





                                   <button type="submit" class="btn btn-update">


                                        <i class="bi bi-save me-2"></i>

                                        Update Role


                                   </button>



                              </div>







                         </form>


                    </div>



               </div>


          </div>


     </div>





     <script>
          document.addEventListener(
               'DOMContentLoaded',
               function() {


                    const name =
                         document.getElementById('roleName');


                    const guard =
                         document.getElementById('guardName');


                    const previewName =
                         document.getElementById('previewName');


                    const previewGuard =
                         document.getElementById('previewGuard');



                    name.addEventListener(
                         'input',
                         function() {

                              previewName.innerHTML =
                                   this.value || 'Nama Role';

                         });



                    guard.addEventListener(
                         'change',
                         function() {

                              previewGuard.innerHTML =
                                   this.value;

                         });


               });
     </script>


@endsection
