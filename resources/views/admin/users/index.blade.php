@extends('layouts.app')


@section('title', 'Executive User Monitoring')


@section('content')


     <style>
          /* ===============================
             PREMIUM EXECUTIVE UI
          ================================ */


          body {

               background: #f8fafc;

          }


          /* HERO */

          .hero-card {

               background:
                    linear-gradient(135deg,
                         #d6daee,
                         #1e40af,
                         #0891b2);

               padding: 40px;

               border-radius: 30px;

               color: white;

               display: flex;

               justify-content: space-between;

               align-items: center;

               box-shadow:
                    0 25px 60px rgba(30, 64, 175, .35);

          }


          .hero-card h2 {

               font-weight: 900;

               font-size: 34px;

          }


          .hero-card p {

               opacity: .85;

          }




          .btn-create {


               background: white;

               color: #1d4ed8;

               padding: 14px 30px;

               border-radius: 16px;

               font-weight: 800;

               text-decoration: none;


          }



          .btn-create:hover {

               background: #eff6ff;

               color: #1e40af;

          }






          /* KPI */

          .kpi-card {

               background: white;

               padding: 25px;

               border-radius: 24px;

               box-shadow:
                    0 15px 35px rgba(15, 23, 42, .08);

               transition: .3s;

          }



          .kpi-card:hover {

               transform: translateY(-5px);

          }



          .kpi-icon {

               width: 65px;

               height: 65px;

               border-radius: 20px;

               display: flex;

               align-items: center;

               justify-content: center;

               font-size: 26px;

          }



          .kpi-blue {

               background: #dbeafe;

               color: #2563eb;

          }


          .kpi-green {

               background: #dcfce7;

               color: #16a34a;

          }


          .kpi-orange {

               background: #ffedd5;

               color: #ea580c;

          }


          .kpi-red {

               background: #fee2e2;

               color: #dc2626;

          }



          .kpi-number {

               font-size: 32px;

               font-weight: 900;

          }







          /* CARD */

          .content-card {

               background: white;

               border-radius: 28px;

               padding: 30px;

               box-shadow:

                    0 15px 35px rgba(15, 23, 42, .08);

          }







          /* FORM */

          .form-control,
          .form-select {


               border-radius: 14px;

               padding: 13px;

               border: 1px solid #e2e8f0;


          }



          .form-control:focus,
          .form-select:focus {


               border-color: #2563eb;

               box-shadow:

                    0 0 0 .2rem rgba(37, 99, 235, .15);


          }







          /* TABLE */


          .table thead th {


               border: none;

               font-size: 12px;

               color: #64748b;

               text-transform: uppercase;


          }



          .table tbody tr {


               transition: .25s;


          }



          .table tbody tr:hover {


               background: #f8fafc;


          }



          .table td {


               padding: 20px;

               border: none;


          }







          .avatar {


               width: 50px;

               height: 50px;

               border-radius: 50%;


               background:

                    linear-gradient(135deg,
                         #2563eb,
                         #38bdf8);


               display: flex;

               align-items: center;

               justify-content: center;


               color: white;

               font-weight: 900;


          }








          /* STATUS */

          .badge-status {


               padding: 8px 16px;

               border-radius: 20px;

               font-weight: 700;

               font-size: 13px;


          }



          .active-status {

               background: #dcfce7;

               color: #16a34a;

          }



          .inactive-status {

               background: #fef3c7;

               color: #d97706;

          }



          .suspended-status {

               background: #fee2e2;

               color: #dc2626;

          }








          /* ACTION */

          .action-box {


               display: flex;

               gap: 8px;


          }



          .action-btn {


               width: 40px;

               height: 40px;

               border-radius: 12px;


               display: flex;

               justify-content: center;

               align-items: center;


               border: none;

               text-decoration: none;


               transition: .3s;


          }


          .action-btn:hover {

               transform: translateY(-3px);

          }



          .btn-view {

               background: #dbeafe;

               color: #2563eb;

          }


          .btn-edit {

               background: #fef3c7;

               color: #d97706;

          }


          .btn-delete {

               background: #fee2e2;

               color: #dc2626;

          }



          .pagination {

               margin-top: 25px;

          }
     </style>






     <div class="container-fluid py-4">





          <div class="hero-card mb-4">


               <div>


                    <h2>

                         Executive User Monitoring

                    </h2>


                    <p>

                         Monitoring akun pengguna dan aktivitas sistem perusahaan

                    </p>


               </div>



               <a href="{{ route('admin.users.create') }}" class="btn-create">


                    <i class="bi bi-person-plus me-2"></i>

                    Tambah Pengguna


               </a>


          </div>









          <div class="row g-4 mb-4">



               <div class="col-xl-3 col-md-6">

                    <div class="kpi-card">


                         <div class="d-flex align-items-center gap-3">


                              <div class="kpi-icon kpi-blue">

                                   <i class="bi bi-people"></i>

                              </div>


                              <div>

                                   <small>Total User</small>


                                   <div class="kpi-number">

                                        {{ $statistics['total_users'] }}

                                   </div>


                              </div>


                         </div>


                    </div>

               </div>






               <div class="col-xl-3 col-md-6">

                    <div class="kpi-card">


                         <div class="d-flex align-items-center gap-3">


                              <div class="kpi-icon kpi-green">

                                   <i class="bi bi-check-circle"></i>

                              </div>


                              <div>

                                   <small>User Aktif</small>


                                   <div class="kpi-number">

                                        {{ $statistics['active_users'] }}

                                   </div>


                              </div>


                         </div>


                    </div>

               </div>







               <div class="col-xl-3 col-md-6">

                    <div class="kpi-card">


                         <div class="d-flex align-items-center gap-3">


                              <div class="kpi-icon kpi-orange">

                                   <i class="bi bi-person-dash"></i>

                              </div>


                              <div>

                                   <small>User Tidak Aktif</small>


                                   <div class="kpi-number">

                                        {{ $statistics['inactive_users'] ?? 0 }}

                                   </div>


                              </div>


                         </div>


                    </div>

               </div>







               <div class="col-xl-3 col-md-6">

                    <div class="kpi-card">


                         <div class="d-flex align-items-center gap-3">


                              <div class="kpi-icon kpi-red">

                                   <i class="bi bi-clock-history"></i>

                              </div>


                              <div>

                                   <small>Login Activity</small>


                                   <div class="kpi-number">

                                        {{ $statistics['login_activity'] }}

                                   </div>


                              </div>


                         </div>


                    </div>

               </div>



          </div>









          <div class="content-card mb-4">


               <form method="GET">


                    <div class="row g-3">


                         <div class="col-md-9">


                              <input name="search" class="form-control" placeholder="Cari nama atau email..."
                                   value="{{ request('search') }}">



                         </div>



                         <div class="col-md-2">


                              <select name="status" class="form-select">


                                   <option value="">

                                        Semua Status

                                   </option>


                                   <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>

                                        Active

                                   </option>



                                   <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>

                                        Inactive

                                   </option>



                                   <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>

                                        Suspended

                                   </option>



                              </select>


                         </div>




                         <div class="col-md-1">


                              <button class="btn btn-primary w-100 h-100">

                                   <i class="bi bi-search"></i>

                              </button>


                         </div>


                    </div>


               </form>


          </div>









          <div class="content-card">


               <div class="d-flex justify-content-between mb-4">


                    <h4 class="fw-bold">

                         Daftar Pengguna

                    </h4>



                    <a href="{{ route('admin.users.trash') }}" class="btn btn-outline-danger">


                         <i class="bi bi-trash"></i>

                         Recycle Bin


                    </a>


               </div>






               <div class="table-responsive">


                    <table class="table align-middle">


                         <thead>

                              <tr>

                                   <th>User</th>

                                   <th>Email</th>

                                   <th>Status</th>

                                   <th>Login</th>

                                   <th>Dibuat</th>

                                   <th>Aksi</th>

                              </tr>

                         </thead>




                         <tbody>


                              @forelse($users as $user)
                                   <tr>


                                        <td>


                                             <div class="d-flex align-items-center gap-3">


                                                  <div class="avatar">

                                                       {{ strtoupper(substr($user->name, 0, 1)) }}

                                                  </div>


                                                  <strong>

                                                       {{ $user->name }}

                                                  </strong>


                                             </div>


                                        </td>



                                        <td>

                                             {{ $user->email }}

                                        </td>




                                        <td>


                                             @if ($user->status == 'active')
                                                  <span class="badge-status active-status">

                                                       Active

                                                  </span>
                                             @elseif($user->status == 'inactive')
                                                  <span class="badge-status inactive-status">

                                                       Inactive

                                                  </span>
                                             @else
                                                  <span class="badge-status suspended-status">

                                                       Suspended

                                                  </span>
                                             @endif


                                        </td>




                                        <td>

                                             {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum Login' }}

                                        </td>




                                        <td>

                                             {{ $user->created_at->format('d M Y') }}

                                        </td>




                                        <td>


                                             <div class="action-box">


                                                  <a href="{{ route('admin.users.show', $user->id) }}"
                                                       class="action-btn btn-view">

                                                       <i class="bi bi-eye"></i>

                                                  </a>



                                                  <a href="{{ route('admin.users.edit', $user->id) }}"
                                                       class="action-btn btn-edit">

                                                       <i class="bi bi-pencil"></i>

                                                  </a>




                                                  <form method="POST"
                                                       action="{{ route('admin.users.destroy', $user->id) }}"
                                                       onsubmit="return confirm('Hapus pengguna ini?')">


                                                       @csrf

                                                       @method('DELETE')


                                                       <button type="submit" class="action-btn btn-delete">

                                                            <i class="bi bi-trash"></i>

                                                       </button>


                                                  </form>


                                             </div>


                                        </td>


                                   </tr>


                              @empty


                                   <tr>

                                        <td colspan="6" class="text-center py-5">


                                             Belum ada data pengguna


                                        </td>

                                   </tr>
                              @endforelse


                         </tbody>


                    </table>


               </div>





               <div class="mt-4">

                    {{ $users->links() }}

               </div>



          </div>






     </div>


@endsection
