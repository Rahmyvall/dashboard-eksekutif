@extends('layouts.app')


@section('title', 'Executive User Recycle Bin')


@section('content')


     <style>
          /* =====================================
                            PREMIUM RECYCLE BIN DASHBOARD
                         ===================================== */


          body {

               background: #f8fafc;

          }





          /* HERO */

          .trash-hero {


               background:

                    linear-gradient(135deg,
                         #72bbdd,
                         #f0ecec,
                         #e9dede);


               padding: 45px;


               border-radius: 32px;


               color: white;


               display: flex;


               justify-content: space-between;


               align-items: center;


               box-shadow:


                    0 25px 60px rgba(173, 160, 160, 0.35);


          }





          .trash-hero h2 {


               font-size: 36px;

               font-weight: 900;


          }





          .trash-hero p {


               margin: 0;

               opacity: .85;


          }






          .btn-back {


               background: white;


               color: #b91c1c;


               padding: 14px 28px;


               border-radius: 16px;


               font-weight: 800;


               text-decoration: none;


               transition: .3s;


          }



          .btn-back:hover {


               transform: translateY(-3px);


               color: #7f1d1d;


          }







          /* KPI */


          .recycle-card {


               background: white;


               border-radius: 25px;


               padding: 30px;


               box-shadow:


                    0 15px 35px rgba(15, 23, 42, .08);


          }





          .recycle-icon {


               width: 65px;


               height: 65px;


               border-radius: 20px;


               display: flex;


               justify-content: center;


               align-items: center;


               font-size: 28px;


               background: #fee2e2;


               color: #dc2626;


          }





          .recycle-number {


               font-size: 34px;


               font-weight: 900;


          }







          /* TABLE CARD */


          .table-card {


               background: white;


               border-radius: 30px;


               padding: 35px;


               box-shadow:


                    0 20px 45px rgba(15, 23, 42, .08);


          }







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







          .avatar-trash {


               width: 52px;


               height: 52px;


               border-radius: 50%;


               background:


                    linear-gradient(135deg,
                         #dc2626,
                         #fb7185);


               color: white;


               font-weight: 900;


               display: flex;


               justify-content: center;


               align-items: center;


          }







          /* STATUS */


          .status-badge {


               padding: 8px 16px;


               border-radius: 20px;


               font-size: 13px;


               font-weight: 800;


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







          /* ACTION */


          .action-btn {


               border-radius: 14px;


               padding: 10px 18px;


               font-weight: 700;


               border: none;


               transition: .3s;


          }



          .action-btn:hover {


               transform: translateY(-3px);


          }



          .restore {


               background: #16a34a;


               color: white;


          }



          .restore:hover {


               background: #15803d;


               color: white;


          }



          .delete {


               background: #dc2626;


               color: white;


          }



          .delete:hover {


               background: #991b1b;


               color: white;


          }







          .empty-box {


               padding: 60px;


               text-align: center;


          }



          .empty-box i {


               font-size: 70px;


          }
     </style>









     <div class="container-fluid py-4">







          {{-- HEADER --}}


          <div class="trash-hero mb-4">



               <div>


                    <h2>

                         <i class="bi bi-trash3 me-2"></i>

                         Recycle Bin Management

                    </h2>


                    <p>

                         Pengelolaan akun pengguna yang telah dihapus sementara

                    </p>


               </div>




               <a href="{{ route('admin.users.index') }}" class="btn-back">


                    <i class="bi bi-arrow-left me-2"></i>

                    Kembali


               </a>


          </div>









          {{-- KPI --}}


          <div class="row mb-4">



               <div class="col-xl-4">


                    <div class="recycle-card">


                         <div class="d-flex align-items-center gap-3">


                              <div class="recycle-icon">


                                   <i class="bi bi-trash"></i>


                              </div>



                              <div>


                                   <small>

                                        Total Data Terhapus

                                   </small>


                                   <div class="recycle-number">


                                        {{ $users->total() }}


                                   </div>


                              </div>


                         </div>


                    </div>


               </div>


          </div>









          {{-- TABLE --}}


          <div class="table-card">





               <div class="d-flex justify-content-between mb-4">


                    <h4 class="fw-bold">

                         Pengguna Terhapus

                    </h4>



                    <span class="badge bg-danger px-3 py-2">


                         {{ $users->total() }}

                         Data


                    </span>


               </div>









               <div class="table-responsive">


                    <table class="table align-middle">


                         <thead>


                              <tr>


                                   <th>

                                        Pengguna

                                   </th>


                                   <th>

                                        Email

                                   </th>


                                   <th>

                                        Status

                                   </th>


                                   <th>

                                        Tanggal Hapus

                                   </th>


                                   <th>

                                        Aksi

                                   </th>


                              </tr>


                         </thead>





                         <tbody>



                              @forelse($users as $user)
                                   <tr>



                                        <td>


                                             <div class="d-flex align-items-center gap-3">


                                                  <div class="avatar-trash">


                                                       {{ strtoupper(substr($user->name, 0, 1)) }}


                                                  </div>


                                                  <div>


                                                       <strong>

                                                            {{ $user->name }}

                                                       </strong>


                                                  </div>


                                             </div>


                                        </td>






                                        <td>


                                             {{ $user->email }}


                                        </td>






                                        <td>


                                             @if ($user->status == 'active')
                                                  <span class="status-badge status-active">

                                                       Active

                                                  </span>
                                             @elseif($user->status == 'inactive')
                                                  <span class="status-badge status-inactive">

                                                       Inactive

                                                  </span>
                                             @else
                                                  <span class="status-badge status-suspended">

                                                       Suspended

                                                  </span>
                                             @endif


                                        </td>






                                        <td>


                                             {{ $user->deleted_at ? $user->deleted_at->format('d M Y H:i') : '-' }}


                                        </td>







                                        <td>


                                             <div class="d-flex gap-2">





                                                  <form method="POST"
                                                       action="{{ route('admin.users.restore', $user->id) }}">


                                                       @csrf


                                                       <button type="submit" class="action-btn restore">


                                                            <i class="bi bi-arrow-clockwise me-1"></i>


                                                            Restore


                                                       </button>


                                                  </form>









                                                  <form method="POST"
                                                       action="{{ route('admin.users.forceDelete', $user->id) }}"
                                                       onsubmit="return confirm('Hapus permanen pengguna ini? Data tidak dapat dikembalikan')">


                                                       @csrf

                                                       @method('DELETE')



                                                       <button type="submit" class="action-btn delete">


                                                            <i class="bi bi-trash me-1"></i>


                                                            Hapus


                                                       </button>


                                                  </form>





                                             </div>


                                        </td>




                                   </tr>





                              @empty



                                   <tr>


                                        <td colspan="5">


                                             <div class="empty-box">


                                                  <i class="bi bi-trash text-muted"></i>


                                                  <h5 class="mt-3 fw-bold">


                                                       Recycle Bin Kosong


                                                  </h5>


                                                  <p class="text-muted">


                                                       Tidak ada pengguna yang dihapus


                                                  </p>


                                             </div>


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
