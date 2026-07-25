@extends('layouts.app')

@section('title', 'Dashboard Manager Departemen')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Manager Departemen',
              'role_label' => 'Manager Departemen',
              'headline' => 'Department Performance Dashboard',
              'description' =>
                  'Pantau target departemen, produktivitas anggota tim, progres pekerjaan, dan kendala operasional harian.',
              'accent' => '#0f766e',
              'accent_secondary' => '#2563eb',
              'statistics' => [
                  [
                      'label' => 'Anggota Tim',
                      'value' => '24',
                      'icon' => 'users',
                      'note' => '22 aktif hari ini',
                  ],
                  [
                      'label' => 'Target Tercapai',
                      'value' => '88%',
                      'icon' => 'target',
                      'note' => '+5% dari minggu lalu',
                  ],
                  [
                      'label' => 'Tugas Berjalan',
                      'value' => '36',
                      'icon' => 'clipboard',
                      'note' => '18 prioritas tinggi',
                  ],
                  [
                      'label' => 'Tugas Terlambat',
                      'value' => '5',
                      'icon' => 'alert-octagon',
                      'note' => 'Perlu tindak lanjut',
                  ],
              ],
              'chart' => [
                  'title' => 'Kinerja Departemen',
                  'subtitle' => 'Perbandingan target dan realisasi selama tujuh periode.',
                  'series_1_label' => 'Target',
                  'series_2_label' => 'Realisasi',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 70,
                          'series_2' => 64,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 75,
                          'series_2' => 72,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 78,
                          'series_2' => 74,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 82,
                          'series_2' => 79,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 86,
                          'series_2' => 84,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 90,
                          'series_2' => 86,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 94,
                          'series_2' => 88,
                      ],
                  ],
              ],
              'priority_title' => 'Prioritas Departemen',
              'priorities' => [
                  [
                      'title' => 'Penyelesaian backlog',
                      'description' => 'Lima pekerjaan terlambat perlu diselesaikan hari ini.',
                      'icon' => 'layers',
                  ],
                  [
                      'title' => 'Review target mingguan',
                      'description' => 'Evaluasi target dilakukan bersama seluruh koordinator.',
                      'icon' => 'target',
                  ],
                  [
                      'title' => 'Pembagian beban kerja',
                      'description' => 'Redistribusi pekerjaan untuk dua anggota tim.',
                      'icon' => 'shuffle',
                  ],
                  [
                      'title' => 'Koordinasi lintas unit',
                      'description' => 'Satu pekerjaan menunggu keputusan unit terkait.',
                      'icon' => 'git-merge',
                  ],
              ],
              'table' => [
                  'title' => 'Ringkasan Kinerja Tim',
                  'headers' => ['Tim', 'Target', 'Realisasi', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'Tim A',
                          'col2' => '25',
                          'col3' => '24',
                          'col4' => 'Baik',
                      ],
                      [
                          'col1' => 'Tim B',
                          'col2' => '22',
                          'col3' => '20',
                          'col4' => 'Baik',
                      ],
                      [
                          'col1' => 'Tim C',
                          'col2' => '18',
                          'col3' => '15',
                          'col4' => 'Perhatian',
                      ],
                      [
                          'col1' => 'Tim D',
                          'col2' => '20',
                          'col3' => '19',
                          'col4' => 'Baik',
                      ],
                  ],
              ],
              'activity_title' => 'Aktivitas Departemen',
              'activities' => [
                  [
                      'title' => 'Tugas prioritas selesai',
                      'description' => 'Tim A menyelesaikan pekerjaan prioritas lebih awal.',
                      'time' => '15 menit lalu',
                      'icon' => 'check-circle',
                  ],
                  [
                      'title' => 'Target mingguan diperbarui',
                      'description' => 'Target seluruh tim telah disesuaikan.',
                      'time' => '40 menit lalu',
                      'icon' => 'target',
                  ],
                  [
                      'title' => 'Kendala operasional dilaporkan',
                      'description' => 'Satu kendala membutuhkan dukungan unit operasional.',
                      'time' => '1 jam lalu',
                      'icon' => 'alert-triangle',
                  ],
                  [
                      'title' => 'Rapat koordinasi dibuat',
                      'description' => 'Rapat lintas unit dijadwalkan besok pagi.',
                      'time' => 'Kemarin',
                      'icon' => 'calendar',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
