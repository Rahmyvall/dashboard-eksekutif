@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard HRD',
              'role_label' => 'Human Resources',
              'headline' => 'Human Capital Control Center',
              'description' =>
                  'Kelola tenaga kerja, kehadiran, rekrutmen, cuti, dan aktivitas SDM melalui dashboard HR terintegrasi.',
              'accent' => '#7c3aed',
              'accent_secondary' => '#ec4899',
              'statistics' => [
                  [
                      'label' => 'Total Karyawan',
                      'value' => '128',
                      'icon' => 'users',
                      'note' => '+6 karyawan tahun ini',
                  ],
                  [
                      'label' => 'Kehadiran Hari Ini',
                      'value' => '94,2%',
                      'icon' => 'user-check',
                      'note' => '121 hadir',
                  ],
                  [
                      'label' => 'Proses Rekrutmen',
                      'value' => '18',
                      'icon' => 'briefcase',
                      'note' => '7 tahap wawancara',
                  ],
                  [
                      'label' => 'Pengajuan Cuti',
                      'value' => '9',
                      'icon' => 'calendar',
                      'note' => '4 menunggu persetujuan',
                  ],
              ],
              'chart' => [
                  'title' => 'Kehadiran dan Produktivitas',
                  'subtitle' => 'Perbandingan persentase kehadiran dan produktivitas tim.',
                  'series_1_label' => 'Kehadiran',
                  'series_2_label' => 'Produktivitas',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 91,
                          'series_2' => 84,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 93,
                          'series_2' => 86,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 92,
                          'series_2' => 85,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 95,
                          'series_2' => 89,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 94,
                          'series_2' => 91,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 90,
                          'series_2' => 87,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 94,
                          'series_2' => 90,
                      ],
                  ],
              ],
              'priority_title' => 'Agenda HRD',
              'priorities' => [
                  [
                      'title' => 'Wawancara kandidat IT',
                      'description' => 'Tujuh kandidat memasuki tahap wawancara pengguna.',
                      'icon' => 'video',
                  ],
                  [
                      'title' => 'Evaluasi masa percobaan',
                      'description' => 'Lima karyawan akan dievaluasi minggu ini.',
                      'icon' => 'clipboard',
                  ],
                  [
                      'title' => 'Pembaruan data kontrak',
                      'description' => 'Dua belas kontrak perlu diperiksa kembali.',
                      'icon' => 'file-text',
                  ],
                  [
                      'title' => 'Pelatihan kepemimpinan',
                      'description' => 'Jadwal peserta dan fasilitator sedang difinalisasi.',
                      'icon' => 'award',
                  ],
              ],
              'table' => [
                  'title' => 'Pipeline Rekrutmen',
                  'headers' => ['Posisi', 'Pelamar', 'Tahap', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'IT Developer',
                          'col2' => '24',
                          'col3' => 'Wawancara User',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'Admin Pelayanan',
                          'col2' => '31',
                          'col3' => 'Psikotes',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'Finance Staff',
                          'col2' => '18',
                          'col3' => 'Screening',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'Supervisor Operasional',
                          'col2' => '12',
                          'col3' => 'Final Interview',
                          'col4' => 'Prioritas',
                      ],
                  ],
              ],
              'activity_title' => 'Aktivitas SDM Terbaru',
              'activities' => [
                  [
                      'title' => 'Karyawan baru diaktifkan',
                      'description' => 'Akun dan role untuk dua karyawan baru telah dibuat.',
                      'time' => '12 menit lalu',
                      'icon' => 'user-plus',
                  ],
                  [
                      'title' => 'Pengajuan cuti diterima',
                      'description' => 'Tiga pengajuan cuti telah disetujui.',
                      'time' => '35 menit lalu',
                      'icon' => 'calendar',
                  ],
                  [
                      'title' => 'Kontrak mendekati berakhir',
                      'description' => 'Empat kontrak berakhir dalam 30 hari.',
                      'time' => '1 jam lalu',
                      'icon' => 'alert-circle',
                  ],
                  [
                      'title' => 'Jadwal pelatihan dibuat',
                      'description' => 'Pelatihan pelayanan dijadwalkan pekan depan.',
                      'time' => 'Kemarin',
                      'icon' => 'book-open',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
