{{-- 
|--------------------------------------------------------------------------
| ROLE PERMISSION COMPONENT
|--------------------------------------------------------------------------
| Assign permission ke role
|
| Database:
| permissions
| - name
| - guard_name
|
| pivot:
| role_has_permissions
|--------------------------------------------------------------------------
--}}


<style>
     .permission-card {

          margin-top: 30px;

          padding: 30px;

          border-radius: 24px;

          background: #ffffff;

          border: 1px solid #e2e8f0;

          box-shadow:
               0 15px 35px rgba(15, 23, 42, .06);

     }



     .permission-header {

          display: flex;

          align-items: center;

          justify-content: space-between;

          margin-bottom: 25px;

     }



     .permission-title {

          display: flex;

          align-items: center;

          gap: 15px;

     }



     .permission-icon {

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
                    #2563eb,
                    #9333ea);

     }



     .permission-title h4 {

          margin: 0;

          font-weight: 850;

          color: #172033;

     }



     .permission-title p {

          margin: 3px 0 0;

          color: #64748b;

          font-size: 13px;

     }





     .permission-count {

          padding: 8px 15px;

          border-radius: 999px;

          color: #4338ca;

          font-size: 13px;

          font-weight: 700;

          background: #eef2ff;

          border: 1px solid #c7d2fe;

     }





     .permission-grid {

          display: grid;

          grid-template-columns:

               repeat(auto-fit, minmax(250px, 1fr));

          gap: 15px;

     }





     .permission-item {

          position: relative;

     }



     .permission-input {

          position: absolute;

          opacity: 0;

     }



     .permission-label {


          display: flex;

          align-items: center;

          gap: 15px;

          padding: 18px;

          cursor: pointer;

          border-radius: 18px;

          background: #f8fafc;

          border: 1px solid #e2e8f0;

          transition: .25s;

     }



     .permission-label:hover {

          border-color: #818cf8;

          background: #eef2ff;

     }





     .permission-input:checked+.permission-label {

          border-color: #6366f1;

          background:

               linear-gradient(135deg,
                    #eef2ff,
                    #faf5ff);

          box-shadow:

               0 10px 25px rgba(99, 102, 241, .15);

     }





     .permission-check {


          width: 38px;

          height: 38px;

          display: flex;

          align-items: center;

          justify-content: center;

          border-radius: 12px;

          color: white;

          background: #cbd5e1;

     }



     .permission-input:checked+.permission-label .permission-check {


          background:

               linear-gradient(135deg,
                    #4f46e5,
                    #9333ea);


     }





     .permission-name {

          font-weight: 750;

          color: #172033;

     }



     .permission-guard {

          font-size: 12px;

          color: #64748b;

     }



     .empty-permission {

          padding: 40px;

          text-align: center;

          color: #64748b;

     }



     .empty-permission i {

          font-size: 45px;

          display: block;

          margin-bottom: 10px;

     }
</style>





<div class="permission-card">



     <div class="permission-header">



          <div class="permission-title">


               <div class="permission-icon">

                    <i class="bi bi-key-fill"></i>

               </div>



               <div>


                    <h4>

                         Permission Role

                    </h4>


                    <p>

                         Pilih hak akses yang diberikan kepada role ini.

                    </p>


               </div>


          </div>




          <span class="permission-count">

               {{ isset($permissions) ? $permissions->count() : 0 }}

               Permission

          </span>




     </div>








     @if (isset($permissions) && $permissions->count())



          <div class="permission-grid">



               @foreach ($permissions as $permission)
                    @php

                         $isChecked = isset($role) && $role->permissions->contains($permission->id);

                    @endphp




                    <div class="permission-item">



                         <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                              id="permission_{{ $permission->id }}" class="permission-input"
                              {{ $isChecked ? 'checked' : '' }}>




                         <label for="permission_{{ $permission->id }}" class="permission-label">



                              <div class="permission-check">


                                   <i class="bi bi-check-lg"></i>


                              </div>



                              <div>


                                   <div class="permission-name">

                                        {{ $permission->name }}

                                   </div>


                                   <div class="permission-guard">

                                        Guard:

                                        {{ $permission->guard_name }}

                                   </div>



                              </div>




                         </label>



                    </div>
               @endforeach



          </div>
     @else
          <div class="empty-permission">


               <i class="bi bi-key"></i>


               Belum ada permission tersedia.



          </div>



     @endif






</div>
