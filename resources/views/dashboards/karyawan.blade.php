@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Karyawan',
              'role_label' => 'Employee Workspace',
              'headline' => 'Dashboard Aktivitas Karyawan',
              'description' =>
                  'Lihat kehadiran, tugas, produktivitas, jadwal, dan informasi pekerjaan pribadi dalam satu halaman.',
              'accent' => '#0284c7',
              'accent_secondary' => '#06b6d4',
              'statistics' => [
                  [
                      'label' => 'Kehadiran Bulan Ini',
                      'value' => '96%',
                      'icon' => 'calendar',
                      'note' => '23 dari 24 hari kerja',
                  ],
                  [
                      'label' => 'Tugas Selesai',
                      'value' => '18',
                      'icon' => 'check-square',
                      'note' => '4 selesai minggu ini',
                  ],
                  [
                      'label' => 'Produktivitas',
                      'value' => '91%',
                      'icon' => 'trending-up',
                      'note' => '+3% dari bulan lalu',
                  ],
                  [
                      'label' => 'Sisa Cuti',
                      'value' => '8 Hari',
                      'icon' => 'coffee',
                      'note' => 'Cuti tahunan tersedia',
                  ],
              ],
              'chart' => [
                  'title' => 'Progres Pekerjaan Mingguan',
                  'subtitle' => 'Perbandingan tugas masuk dan tugas selesai.',
                  'series_1_label' => 'Tugas Masuk',
                  'series_2_label' => 'Tugas Selesai',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 43,
                          'series_2' => 29,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 71,
                          'series_2' => 57,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 57,
                          'series_2' => 57,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 86,
                          'series_2' => 71,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 71,
                          'series_2' => 71,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 29,
                          'series_2' => 29,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 14,
                          'series_2' => 14,
                      ],
                  ],
              ],
              'priority_title' => 'Agenda Hari Ini',
              'priorities' => [
                  [
                      'title' => 'Selesaikan laporan harian',
                      'description' => 'Batas pengumpulan pukul 16.00.',
                      'icon' => 'file-text',
                  ],
                  [
                      'title' => 'Rapat koordinasi tim',
                      'description' => 'Rapat dimulai pukul 10.00.',
                      'icon' => 'users',
                  ],
                  [
                      'title' => 'Perbarui progres tugas',
                      'description' => 'Pastikan seluruh progres tercatat di sistem.',
                      'icon' => 'refresh-cw',
                  ],
                  [
                      'title' => 'Review instruksi manager',
                      'description' => 'Terdapat satu instruksi baru untuk ditinjau.',
                      'icon' => 'message-square',
                  ],
              ],
              'table' => [
                  'title' => 'Daftar Tugas Aktif',
                  'headers' => ['Tugas', 'Deadline', 'Progres', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'Laporan operasional',
                          'col2' => 'Hari ini',
                          'col3' => '80%',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'Rekap data layanan',
                          'col2' => 'Besok',
                          'col3' => '65%',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'Pembaruan dokumen',
                          'col2' => '26 Jul',
                          'col3' => '100%',
                          'col4' => 'Selesai',
                      ],
                      [
                          'col1' => 'Validasi transaksi',
                          'col2' => '28 Jul',
                          'col3' => '40%',
                          'col4' => 'Berjalan',
                      ],
                  ],
              ],
              'activity_title' => 'Aktivitas Saya',
              'activities' => [
                  [
                      'title' => 'Absensi berhasil',
                      'description' => 'Kehadiran hari ini tercatat pukul 07.54.',
                      'time' => '2 jam lalu',
                      'icon' => 'check-circle',
                  ],
                  [
                      'title' => 'Tugas baru diberikan',
                      'description' => 'Manager menambahkan tugas rekap data layanan.',
                      'time' => '3 jam lalu',
                      'icon' => 'clipboard',
                  ],
                  [
                      'title' => 'Komentar baru',
                      'description' => 'Terdapat komentar pada tugas laporan operasional.',
                      'time' => '4 jam lalu',
                      'icon' => 'message-circle',
                  ],
                  [
                      'title' => 'Dokumen disetujui',
                      'description' => 'Pembaruan dokumen telah disetujui manager.',
                      'time' => 'Kemarin',
                      'icon' => 'file-check',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
