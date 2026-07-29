@extends('layouts.app')

@section('title', 'Tambah Role')

@section('content')

     <style>
          :root {

               --role-primary: #4f46e5;
               --role-secondary: #9333ea;
               --role-pink: #ec4899;
               --role-orange: #f97316;

               --role-dark: #172033;
               --role-muted: #64748b;

               --role-border: #e2e8f0;

          }


          .role-create-page {

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



          /*
     |--------------------------------------------------------------------------
     | MAIN CARD
     |--------------------------------------------------------------------------
     */


          .role-card {

               overflow: hidden;

               border-radius: 28px;

               border: 1px solid rgba(255, 255, 255, .7);

               background: rgba(255, 255, 255, .95);

               box-shadow:

                    0 25px 60px rgba(15, 23, 42, .12);

          }



          /*
     |--------------------------------------------------------------------------
     | HERO
     |--------------------------------------------------------------------------
     */


          .role-hero {

               position: relative;

               padding: 40px;

               color: white;

               overflow: hidden;

               background:

                    linear-gradient(120deg,
                         #4338ca,
                         #7c3aed,
                         #db2777,
                         #f97316);

          }


          .role-hero::before {

               content: "";

               position: absolute;

               width: 280px;

               height: 280px;

               right: -80px;

               top: -100px;

               border-radius: 50%;

               background: rgba(255, 255, 255, .12);

          }



          .role-hero-content {

               position: relative;

               z-index: 2;

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

               letter-spacing: .08em;

               text-transform: uppercase;

          }



          .role-hero h1 {

               margin: 0;

               font-size: 38px;

               font-weight: 900;

          }



          .role-hero p {

               margin-top: 10px;

               max-width: 650px;

               opacity: .9;

               line-height: 1.7;

          }



          .btn-back {

               display: inline-flex;

               align-items: center;

               gap: 8px;

               padding: 12px 20px;

               color: white;

               border-radius: 14px;

               border: 1px solid rgba(255, 255, 255, .35);

               background: rgba(255, 255, 255, .15);

               text-decoration: none;

               font-weight: 700;

               transition: .25s;

          }


          .btn-back:hover {

               color: #4f46e5;

               background: white;

          }



          /*
     |--------------------------------------------------------------------------
     | BODY
     |--------------------------------------------------------------------------
     */


          .role-body {

               padding: 40px;

          }



          .section-title {

               display: flex;

               align-items: center;

               gap: 15px;

               margin-bottom: 25px;

          }



          .section-icon {

               width: 50px;

               height: 50px;

               display: flex;

               align-items: center;

               justify-content: center;

               border-radius: 16px;

               color: white;

               font-size: 22px;

               background:

                    linear-gradient(135deg,
                         #6366f1,
                         #a855f7);

          }


          .section-title h3 {

               margin: 0;

               font-size: 20px;

               font-weight: 850;

               color: var(--role-dark);

          }


          .section-title p {

               margin: 3px 0 0;

               color: var(--role-muted);

               font-size: 13px;

          }



          /*
     |--------------------------------------------------------------------------
     | INPUT
     |--------------------------------------------------------------------------
     */


          .input-box {

               position: relative;

          }



          .input-icon {

               position: absolute;

               top: 50%;

               left: 15px;

               transform: translateY(-50%);

               color: #818cf8;

               font-size: 18px;

          }



          .form-control-modern,

          .form-select-modern {

               height: 54px;

               width: 100%;

               padding-left: 45px;

               border-radius: 15px;

               border: 1px solid #cbd5e1;

               transition: .2s;

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



          /*
     |--------------------------------------------------------------------------
     | PREVIEW
     |--------------------------------------------------------------------------
     */


          .preview-role {

               margin-top: 25px;

               padding: 25px;

               border-radius: 22px;

               background:

                    linear-gradient(135deg,
                         #eef2ff,
                         #faf5ff);

               border: 1px solid #ddd6fe;

          }


          .preview-role h5 {

               font-weight: 800;

               color: #4338ca;

          }



          .preview-item {

               display: flex;

               align-items: center;

               gap: 15px;

               margin-top: 15px;

          }


          .preview-icon {

               width: 55px;

               height: 55px;

               display: flex;

               align-items: center;

               justify-content: center;

               border-radius: 18px;

               color: white;

               font-size: 22px;

               background:

                    linear-gradient(135deg,
                         #4f46e5,
                         #ec4899);

          }



          /*
     |--------------------------------------------------------------------------
     | INFO
     |--------------------------------------------------------------------------
     */


          .info-box {

               padding: 20px;

               margin-top: 25px;

               border-radius: 18px;

               background: #eff6ff;

               border: 1px solid #bfdbfe;

               color: #1e40af;

          }



          /*
     |--------------------------------------------------------------------------
     | BUTTON
     |--------------------------------------------------------------------------
     */


          .btn-save {

               padding: 13px 28px;

               border-radius: 15px;

               font-weight: 800;

               background:

                    linear-gradient(135deg,
                         #4f46e5,
                         #9333ea);

               border: none;

          }



          .btn-save:hover {

               transform: translateY(-2px);

               box-shadow:

                    0 15px 30px rgba(79, 70, 229, .3);

          }



          @media(max-width:768px) {

               .role-body,
               .role-hero {

                    padding: 25px;

               }


               .role-hero h1 {

                    font-size: 30px;

               }

          }
     </style>



     <div class="role-create-page">

          <div class="container-fluid">


               <div class="role-card">



                    {{-- HERO --}}
                    <div class="role-hero">


                         <div class="role-hero-content">


                              <div class="role-badge">

                                   <i class="bi bi-shield-lock-fill"></i>

                                   Role Management

                              </div>



                              <h1>
                                   Tambah Role Baru
                              </h1>



                              <p>

                                   Buat role baru untuk mengatur hak akses pengguna
                                   berdasarkan sistem permission aplikasi.

                              </p>


                              <div class="mt-4">

                                   <a href="{{ route('super-admin.roles.index') }}" class="btn-back">

                                        <i class="bi bi-arrow-left"></i>

                                        Kembali

                                   </a>

                              </div>



                         </div>


                    </div>




                    {{-- BODY --}}
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




                         <form method="POST" action="{{ route('super-admin.roles.store') }}">

                              @csrf



                              <div class="section-title">

                                   <div class="section-icon">

                                        <i class="bi bi-shield-plus"></i>

                                   </div>


                                   <div>

                                        <h3>
                                             Informasi Role
                                        </h3>

                                        <p>
                                             Data akan tersimpan pada tabel roles.
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


                                             <input type="text" name="name" value="{{ old('name') }}"
                                                  class="form-control-modern" placeholder="contoh : super_admin" required>


                                        </div>


                                        <small class="text-muted">

                                             Gunakan format:
                                             super_admin, manager, staff

                                        </small>



                                   </div>





                                   <div class="col-lg-6">


                                        <label class="form-label fw-bold">

                                             Guard Name

                                             <span class="text-danger">*</span>

                                        </label>



                                        <select name="guard_name" class="form-select-modern">


                                             <option value="web">

                                                  web

                                             </option>


                                             <option value="api">

                                                  api

                                             </option>


                                        </select>


                                   </div>



                              </div>






                              <div class="preview-role">


                                   <h5>

                                        Preview Role

                                   </h5>


                                   <div class="preview-item">


                                        <div class="preview-icon">

                                             <i class="bi bi-shield-check"></i>

                                        </div>



                                        <div>


                                             <strong id="previewName">

                                                  {{ old('name', 'Nama Role') }}

                                             </strong>


                                             <br>


                                             <small>

                                                  Guard:

                                                  <span id="previewGuard">
                                                       web
                                                  </span>

                                             </small>


                                        </div>


                                   </div>


                              </div>






                              <div class="info-box">


                                   <i class="bi bi-info-circle-fill me-2"></i>


                                   Database:

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

                                        Batal

                                   </a>



                                   <button type="submit" class="btn btn-primary btn-save">

                                        <i class="bi bi-save me-2"></i>

                                        Simpan Role

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
                         document.querySelector('[name="name"]');

                    const guard =
                         document.querySelector('[name="guard_name"]');


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
