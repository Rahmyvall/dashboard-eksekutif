@extends('layouts.app')

@section('title', 'Dashboard Admin Operasional')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Admin Operasional',
              'role_label' => 'Admin Operasional',
              'headline' => 'Operational Control Center',
              'description' =>
                  'Pantau pekerjaan aktif, utilisasi sumber daya, jadwal lapangan, keterlambatan, dan insiden operasional.',
              'accent' => '#ea580c',
              'accent_secondary' => '#dc2626',
              'statistics' => [
                  [
                      'label' => 'Pekerjaan Aktif',
                      'value' => '42',
                      'icon' => 'activity',
                      'note' => '18 prioritas tinggi',
                  ],
                  [
                      'label' => 'Selesai Hari Ini',
                      'value' => '31',
                      'icon' => 'check-circle',
                      'note' => '73,8% target harian',
                  ],
                  [
                      'label' => 'Pekerjaan Terlambat',
                      'value' => '6',
                      'icon' => 'clock',
                      'note' => 'Perlu eskalasi',
                  ],
                  [
                      'label' => 'Utilisasi Sumber Daya',
                      'value' => '87%',
                      'icon' => 'cpu',
                      'note' => 'Masih dalam batas aman',
                  ],
              ],
              'chart' => [
                  'title' => 'Kinerja Operasional Harian',
                  'subtitle' => 'Perbandingan pekerjaan dijadwalkan dan diselesaikan.',
                  'series_1_label' => 'Dijadwalkan',
                  'series_2_label' => 'Selesai',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 67,
                          'series_2' => 60,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 76,
                          'series_2' => 69,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 81,
                          'series_2' => 71,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 93,
                          'series_2' => 83,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 98,
                          'series_2' => 88,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 90,
                          'series_2' => 81,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 100,
                          'series_2' => 74,
                      ],
                  ],
              ],
              'priority_title' => 'Prioritas Operasional',
              'priorities' => [
                  [
                      'title' => 'Pekerjaan terlambat',
                      'description' => 'Enam pekerjaan perlu penjadwalan ulang.',
                      'icon' => 'clock',
                  ],
                  [
                      'title' => 'Ketersediaan sumber daya',
                      'description' => 'Satu tim lapangan memiliki beban di atas target.',
                      'icon' => 'users',
                  ],
                  [
                      'title' => 'Inspeksi peralatan',
                      'description' => 'Pemeriksaan dua perangkat dijadwalkan sore ini.',
                      'icon' => 'tool',
                  ],
                  [
                      'title' => 'Koordinasi vendor',
                      'description' => 'Konfirmasi kedatangan vendor masih menunggu.',
                      'icon' => 'truck',
                  ],
              ],
              'table' => [
                  'title' => 'Jadwal Operasional',
                  'headers' => ['Pekerjaan', 'Tim', 'Waktu', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'Inspeksi lokasi A',
                          'col2' => 'Tim 1',
                          'col3' => '08.00',
                          'col4' => 'Selesai',
                      ],
                      [
                          'col1' => 'Pemeliharaan unit B',
                          'col2' => 'Tim 2',
                          'col3' => '10.00',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'Instalasi perangkat',
                          'col2' => 'Tim 3',
                          'col3' => '13.00',
                          'col4' => 'Terjadwal',
                      ],
                      [
                          'col1' => 'Validasi hasil kerja',
                          'col2' => 'Tim QA',
                          'col3' => '15.30',
                          'col4' => 'Terjadwal',
                      ],
                  ],
              ],
              'activity_title' => 'Aktivitas Operasional',
              'activities' => [
                  [
                      'title' => 'Pekerjaan selesai',
                      'description' => 'Inspeksi lokasi A selesai tanpa kendala.',
                      'time' => '14 menit lalu',
                      'icon' => 'check-circle',
                  ],
                  [
                      'title' => 'Jadwal diperbarui',
                      'description' => 'Pemeliharaan unit B dimundurkan 30 menit.',
                      'time' => '28 menit lalu',
                      'icon' => 'calendar',
                  ],
                  [
                      'title' => 'Keterlambatan dilaporkan',
                      'description' => 'Satu pekerjaan terkendala ketersediaan material.',
                      'time' => '50 menit lalu',
                      'icon' => 'alert-triangle',
                  ],
                  [
                      'title' => 'Vendor dikonfirmasi',
                      'description' => 'Jadwal vendor untuk besok telah dikonfirmasi.',
                      'time' => 'Kemarin',
                      'icon' => 'truck',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
