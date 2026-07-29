{{-- 
|--------------------------------------------------------------------------
| ROLE FORM COMPONENT
|--------------------------------------------------------------------------
| Compatible:
| create.blade.php
| edit.blade.php
|
| Database:
| roles
| - name
| - guard_name
|--------------------------------------------------------------------------
--}}


<style>
     .role-form-section {

          padding: 30px;

          border-radius: 24px;

          background: #ffffff;

          border: 1px solid #e2e8f0;

     }



     .form-section-header {

          display: flex;

          align-items: center;

          gap: 15px;

          margin-bottom: 30px;

     }



     .form-section-icon {

          width: 52px;

          height: 52px;

          display: flex;

          align-items: center;

          justify-content: center;

          border-radius: 16px;

          color: white;

          font-size: 22px;

          background:

               linear-gradient(135deg,
                    #4f46e5,
                    #9333ea);

     }



     .form-section-header h4 {

          margin: 0;

          color: #172033;

          font-weight: 850;

     }



     .form-section-header p {

          margin: 4px 0 0;

          color: #64748b;

          font-size: 13px;

     }



     .form-label-modern {

          margin-bottom: 8px;

          color: #334155;

          font-size: 14px;

          font-weight: 750;

     }



     .input-wrapper {

          position: relative;

     }



     .input-icon {

          position: absolute;

          top: 50%;

          left: 16px;

          transform: translateY(-50%);

          color: #818cf8;

          font-size: 18px;

     }



     .input-modern {

          height: 55px;

          width: 100%;

          padding-left: 45px;

          border-radius: 15px;

          border: 1px solid #cbd5e1;

          transition: .2s;

     }



     .select-modern {

          height: 55px;

          width: 100%;

          border-radius: 15px;

          border: 1px solid #cbd5e1;

          padding: 0 18px;

     }



     .input-modern:focus,

     .select-modern:focus {

          border-color: #6366f1;

          box-shadow:

               0 0 0 5px rgba(99, 102, 241, .12);

     }



     .role-preview {

          margin-top: 30px;

          padding: 25px;

          border-radius: 22px;

          background:

               linear-gradient(135deg,
                    #eef2ff,
                    #faf5ff);

          border: 1px solid #ddd6fe;

     }



     .preview-avatar {

          width: 60px;

          height: 60px;

          display: flex;

          align-items: center;

          justify-content: center;

          border-radius: 20px;

          color: white;

          font-size: 25px;

          background:

               linear-gradient(135deg,
                    #2563eb,
                    #ec4899);

     }



     .database-info {

          margin-top: 25px;

          padding: 20px;

          border-radius: 18px;

          background: #eff6ff;

          border: 1px solid #bfdbfe;

          color: #1e40af;

     }
</style>





<div class="role-form-section">



     <div class="form-section-header">


          <div class="form-section-icon">

               <i class="bi bi-shield-lock-fill"></i>

          </div>



          <div>

               <h4>

                    Informasi Role

               </h4>


               <p>

                    Data role tersimpan pada tabel roles.

               </p>


          </div>


     </div>







     @if ($errors->any())

          <div class="alert alert-danger rounded-4">


               <strong>

                    Periksa kembali data:

               </strong>


               <ul class="mb-0 mt-2">


                    @foreach ($errors->all() as $error)
                         <li>

                              {{ $error }}

                         </li>
                    @endforeach


               </ul>


          </div>

     @endif







     <div class="row g-4">





          {{-- NAME --}}
          <div class="col-lg-6">


               <label class="form-label-modern">

                    Nama Role

                    <span class="text-danger">*</span>

               </label>



               <div class="input-wrapper">


                    <i class="bi bi-person-badge input-icon"></i>



                    <input type="text" name="name" id="roleName"
                         class="input-modern @error('name') is-invalid @enderror"
                         value="{{ old('name', $role->name ?? '') }}" placeholder="contoh: super_admin" required>


               </div>



               @error('name')
                    <div class="text-danger small mt-2">

                         {{ $message }}

                    </div>
               @enderror



               <small class="text-muted">

                    Gunakan huruf kecil dan underscore.

                    Contoh:

                    <code>super_admin</code>

               </small>


          </div>







          {{-- GUARD --}}
          <div class="col-lg-6">


               <label class="form-label-modern">

                    Guard Name

                    <span class="text-danger">*</span>

               </label>



               <select name="guard_name" id="guardName" class="select-modern @error('guard_name') is-invalid @enderror">


                    <option value="web" @selected(old('guard_name', $role->guard_name ?? 'web') == 'web')>

                         web

                    </option>



                    <option value="api" @selected(old('guard_name', $role->guard_name ?? '') == 'api')>

                         api

                    </option>



               </select>



               @error('guard_name')
                    <div class="text-danger small mt-2">

                         {{ $message }}

                    </div>
               @enderror



          </div>





     </div>










     {{-- PREVIEW --}}
     <div class="role-preview">


          <div class="d-flex align-items-center gap-3">


               <div class="preview-avatar">

                    <i class="bi bi-shield-check"></i>

               </div>



               <div>


                    <h5 class="mb-1 fw-bold" id="previewRoleName">

                         {{ $role->name ?? 'Nama Role' }}

                    </h5>



                    <div class="text-muted">


                         Guard:

                         <strong id="previewGuardName">

                              {{ $role->guard_name ?? 'web' }}

                         </strong>


                    </div>


               </div>


          </div>


     </div>








     {{-- DATABASE INFO --}}
     <div class="database-info">


          <i class="bi bi-database-check me-2"></i>


          <strong>

               Database Roles:

          </strong>


          <br>


          Field yang digunakan:

          <code>name</code>

          dan

          <code>guard_name</code>



     </div>






</div>







<script>
     document.addEventListener(
          'DOMContentLoaded',
          function() {


               const roleName =
                    document.getElementById('roleName');


               const guardName =
                    document.getElementById('guardName');


               const previewRoleName =
                    document.getElementById('previewRoleName');


               const previewGuardName =
                    document.getElementById('previewGuardName');



               if (roleName) {


                    roleName.addEventListener(
                         'input',
                         function() {

                              previewRoleName.innerHTML =
                                   this.value || 'Nama Role';

                         });

               }



               if (guardName) {


                    guardName.addEventListener(
                         'change',
                         function() {

                              previewGuardName.innerHTML =
                                   this.value;

                         });

               }



          });
</script>
