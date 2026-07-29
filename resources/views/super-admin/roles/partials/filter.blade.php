{{-- 
|--------------------------------------------------------------------------
| ROLE FILTER
|--------------------------------------------------------------------------
| Database roles:
| - name
| - guard_name
|--------------------------------------------------------------------------
--}}


<style>
     .role-filter-card {

          padding: 25px;

          margin-bottom: 25px;

          border-radius: 22px;

          background: #ffffff;

          border: 1px solid #e2e8f0;

          box-shadow:
               0 15px 35px rgba(15, 23, 42, .06);

     }



     .filter-title {

          display: flex;

          align-items: center;

          gap: 12px;

          margin-bottom: 20px;

     }



     .filter-title-icon {

          width: 45px;

          height: 45px;

          display: flex;

          align-items: center;

          justify-content: center;

          border-radius: 14px;

          color: white;

          background:

               linear-gradient(135deg,
                    #4f46e5,
                    #9333ea);

     }



     .filter-title h4 {

          margin: 0;

          font-size: 18px;

          font-weight: 850;

          color: #172033;

     }



     .filter-title p {

          margin: 3px 0 0;

          color: #64748b;

          font-size: 13px;

     }



     .filter-label {

          margin-bottom: 8px;

          font-size: 13px;

          font-weight: 750;

          color: #334155;

     }



     .filter-input-group {

          position: relative;

     }



     .filter-icon {

          position: absolute;

          top: 50%;

          left: 15px;

          transform: translateY(-50%);

          color: #818cf8;

          z-index: 2;

     }



     .filter-control {

          height: 48px;

          width: 100%;

          padding-left: 45px;

          border-radius: 14px;

          border: 1px solid #cbd5e1;

     }



     .filter-select {

          height: 48px;

          border-radius: 14px;

          border: 1px solid #cbd5e1;

     }



     .filter-control:focus,

     .filter-select:focus {

          border-color: #6366f1;

          box-shadow:

               0 0 0 4px rgba(99, 102, 241, .12);

     }



     .btn-filter {

          height: 48px;

          padding: 0 22px;

          border-radius: 14px;

          font-weight: 750;

     }



     .btn-reset {

          height: 48px;

          display: flex;

          align-items: center;

          justify-content: center;

          border-radius: 14px;

          font-weight: 700;

     }
</style>



<div class="role-filter-card">



     <div class="filter-title">


          <div class="filter-title-icon">

               <i class="bi bi-funnel-fill"></i>

          </div>



          <div>

               <h4>

                    Filter Role

               </h4>


               <p>

                    Cari role berdasarkan nama atau guard sistem.

               </p>


          </div>



     </div>






     <form method="GET" action="{{ route('super-admin.roles.index') }}">



          <div class="row g-3 align-items-end">





               {{-- SEARCH --}}
               <div class="col-lg-6">


                    <label class="filter-label">

                         Pencarian Role

                    </label>



                    <div class="filter-input-group">


                         <i class="bi bi-search filter-icon"></i>



                         <input type="search" name="search" class="filter-control" value="{{ request('search') }}"
                              placeholder="Cari nama role..." autocomplete="off">


                    </div>



               </div>







               {{-- GUARD --}}
               <div class="col-lg-3">


                    <label class="filter-label">

                         Guard Name

                    </label>



                    <select name="guard_name" class="filter-select w-100">


                         <option value="">

                              Semua Guard

                         </option>


                         <option value="web" @selected(request('guard_name') == 'web')>

                              web

                         </option>


                         <option value="api" @selected(request('guard_name') == 'api')>

                              api

                         </option>



                    </select>



               </div>







               {{-- BUTTON --}}
               <div class="col-lg-3">


                    <div class="d-flex gap-2">



                         <button type="submit" class="btn btn-primary btn-filter flex-fill">


                              <i class="bi bi-search me-1"></i>

                              Cari


                         </button>





                         <a href="{{ route('super-admin.roles.index') }}"
                              class="btn btn-outline-secondary btn-reset px-3" title="Reset Filter">


                              <i class="bi bi-arrow-counterclockwise"></i>


                         </a>




                    </div>


               </div>







          </div>




     </form>


</div>
