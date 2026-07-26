@extends('layouts.app')

@section('title', 'Dashboard Manager Departemen')

@section('content')


     @php

          $currentUserName = auth()->user()->name ?? 'Manager Departemen';

          $statistics = [
              [
                  'label' => 'Total Anggota',
                  'value' => 32,
                  'icon' => 'users',
                  'theme' => 'blue',
              ],

              [
                  'label' => 'Proyek Aktif',
                  'value' => 12,
                  'icon' => 'briefcase',
                  'theme' => 'purple',
              ],

              [
                  'label' => 'Target Tercapai',
                  'value' => '86%',
                  'icon' => 'target',
                  'theme' => 'green',
              ],

              [
                  'label' => 'Menunggu Approval',
                  'value' => 7,
                  'icon' => 'clock',
                  'theme' => 'orange',
              ],
          ];

          $departmentPerformance = [
              [
                  'name' => 'Produktivitas Tim',
                  'value' => 88,
              ],

              [
                  'name' => 'Kehadiran',
                  'value' => 94,
              ],

              [
                  'name' => 'Penyelesaian Target',
                  'value' => 86,
              ],

              [
                  'name' => 'Kualitas Pekerjaan',
                  'value' => 91,
              ],
          ];

          $teamMembers = [
              [
                  'name' => 'Andi Saputra',
                  'position' => 'Supervisor',
                  'performance' => 92,
                  'status' => 'Sangat Baik',
              ],

              [
                  'name' => 'Rizky Maulana',
                  'position' => 'Staff Senior',
                  'performance' => 85,
                  'status' => 'Baik',
              ],

              [
                  'name' => 'Dimas Pratama',
                  'position' => 'Staff',
                  'performance' => 78,
                  'status' => 'Perlu Evaluasi',
              ],
          ];

          $tasks = [
              [
                  'title' => 'Review laporan bulanan',
                  'deadline' => 'Hari ini',
                  'status' => 'Pending',
                  'type' => 'warning',
              ],

              [
                  'title' => 'Evaluasi KPI karyawan',
                  'deadline' => 'Besok',
                  'status' => 'Berjalan',
                  'type' => 'info',
              ],

              [
                  'title' => 'Persetujuan rencana kerja',
                  'deadline' => '25 Juli',
                  'status' => 'Selesai',
                  'type' => 'success',
              ],
          ];

          $activities = [
              [
                  'title' => 'Karyawan mengirim laporan',
                  'description' => 'Laporan kinerja mingguan telah masuk',
                  'time' => '15 menit lalu',
                  'icon' => 'file-text',
              ],

              [
                  'title' => 'Target departemen diperbarui',
                  'description' => 'Target Q3 berhasil disesuaikan',
                  'time' => '1 jam lalu',
                  'icon' => 'trending-up',
              ],
          ];

     @endphp



     <style>
          .manager-dashboard {


               --primary: #7c3aed;
               --primary-dark: #6d28d9;
               --success: #16a34a;
               --warning: #d97706;
               --danger: #dc2626;

               --background: #f8fafc;
               --card: #ffffff;
               --border: #e5e7eb;


               padding: 25px;
               background: var(--background);

               min-height: 100vh;


          }



          .manager-header {


               background:

                    linear-gradient(135deg,
                         #4c1d95,
                         #7c3aed);


               color: white;

               padding: 35px;

               border-radius: 25px;

               margin-bottom: 25px;


          }



          .manager-grid {


               display: grid;

               grid-template-columns:

                    repeat(4, 1fr);

               gap: 20px;


          }



          .manager-card {


               background: white;

               border: 1px solid var(--border);

               border-radius: 20px;

               padding: 22px;

               box-shadow:

                    0 10px 30px rgba(15, 23, 42, .06);


          }



          .manager-icon {


               width: 50px;

               height: 50px;

               border-radius: 15px;


               display: flex;

               align-items: center;

               justify-content: center;


               background: #f3e8ff;

               color: #7c3aed;


          }



          .manager-number {


               font-size: 32px;

               font-weight: 800;

               margin-top: 15px;


          }



          .progress {


               height: 8px;

               background: #e5e7eb;

               border-radius: 20px;

               overflow: hidden;


          }



          .progress span {


               display: block;

               height: 100%;

               background: #7c3aed;


          }



          .badge {


               padding: 5px 10px;

               border-radius: 20px;

               font-size: 12px;

               font-weight: 700;


          }


          .badge-success {

               background: #dcfce7;

               color: #15803d;

          }


          .badge-warning {

               background: #fef3c7;

               color: #b45309;

          }


          .badge-info {

               background: #dbeafe;

               color: #1d4ed8;

          }




          @media(max-width:900px) {


               .manager-grid {

                    grid-template-columns: 1fr;

               }


          }
     </style>




     <div class="manager-dashboard">



          <section class="manager-header">


               <h1>

                    Department Performance Center

               </h1>


               <p>

                    Selamat datang {{ $currentUserName }}.

                    Pantau performa tim, target departemen,
                    approval pekerjaan, dan evaluasi karyawan.

               </p>


          </section>




          <section class="manager-grid">


               @foreach ($statistics as $stat)
                    <div class="manager-card">


                         <div class="manager-icon">

                              <i data-feather="{{ $stat['icon'] }}"></i>

                         </div>


                         <h4>

                              {{ $stat['label'] }}

                         </h4>


                         <div class="manager-number">

                              {{ $stat['value'] }}

                         </div>


                    </div>
               @endforeach



          </section>




          <br>



          <div class="manager-grid">


               <div class="manager-card" style="grid-column:span 2">


                    <h3>

                         Performance Departemen

                    </h3>



                    @foreach ($departmentPerformance as $item)
                         <p>

                              {{ $item['name'] }}

                              <strong style="float:right">

                                   {{ $item['value'] }}%

                              </strong>

                         </p>


                         <div class="progress">

                              <span style="width:{{ $item['value'] }}%">
                              </span>

                         </div>


                         <br>
                    @endforeach


               </div>





               <div class="manager-card">


                    <h3>

                         Quick Approval

                    </h3>


                    @foreach ($tasks as $task)
                         <div style="
padding:12px 0;
border-bottom:1px solid #eee;
">


                              <strong>

                                   {{ $task['title'] }}

                              </strong>


                              <p>

                                   {{ $task['deadline'] }}

                              </p>


                              <span class="badge badge-{{ $task['type'] }}">

                                   {{ $task['status'] }}

                              </span>


                         </div>
                    @endforeach


               </div>



          </div>




          <br>




          <div class="manager-card">


               <h3>

                    Monitoring Anggota Tim

               </h3>



               @foreach ($teamMembers as $member)
                    <div style="
padding:15px 0;
border-bottom:1px solid #eee;
">


                         <strong>

                              {{ $member['name'] }}

                         </strong>


                         <p>

                              {{ $member['position'] }}

                         </p>


                         <div class="progress">

                              <span style="
width:{{ $member['performance'] }}%
">

                              </span>


                         </div>


                         <small>

                              {{ $member['performance'] }}%
                              -
                              {{ $member['status'] }}

                         </small>


                    </div>
               @endforeach



          </div>





          <br>



          <div class="manager-card">


               <h3>

                    Aktivitas Terbaru

               </h3>


               @foreach ($activities as $activity)
                    <div style="
padding:15px 0;
">


                         <i data-feather="{{ $activity['icon'] }}"></i>


                         <strong>

                              {{ $activity['title'] }}

                         </strong>


                         <p>

                              {{ $activity['description'] }}

                         </p>


                         <small>

                              {{ $activity['time'] }}

                         </small>


                    </div>
               @endforeach



          </div>




     </div>



@endsection
