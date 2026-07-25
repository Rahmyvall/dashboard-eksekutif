@extends('layouts.app')

@section('title', 'Dashboard Direktur Utama')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Direktur Utama',
              'role_label' => 'Direktur Utama',
              'headline' => 'Executive Business Overview',
              'description' =>
                  'Pantau kinerja perusahaan, kondisi keuangan, kualitas layanan, dan isu strategis melalui satu ringkasan eksekutif.',
              'accent' => '#2563eb',
              'accent_secondary' => '#7c3aed',
              'statistics' => [
                  [
                      'label' => 'Total Pendapatan',
                      'value' => 'Rp486,03 Jt',
                      'icon' => 'trending-up',
                      'note' => '+8,5% dari periode lalu',
                  ],
                  [
                      'label' => 'Laba Bersih',
                      'value' => 'Rp98,81 Jt',
                      'icon' => 'dollar-sign',
                      'note' => 'Margin laba 20,3%',
                  ],
                  [
                      'label' => 'Kepuasan Pelanggan',
                      'value' => '94,5%',
                      'icon' => 'smile',
                      'note' => 'Kategori sangat baik',
                  ],
                  [
                      'label' => 'Isu Strategis',
                      'value' => '4',
                      'icon' => 'alert-triangle',
                      'note' => 'Memerlukan keputusan',
                  ],
              ],
              'chart' => [
                  'title' => 'Tren Pendapatan dan Laba',
                  'subtitle' => 'Perbandingan kinerja keuangan selama tujuh periode.',
                  'series_1_label' => 'Pendapatan',
                  'series_2_label' => 'Laba Bersih',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 60,
                          'series_2' => 12,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 66,
                          'series_2' => 14,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 64,
                          'series_2' => 13,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 73,
                          'series_2' => 16,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 81,
                          'series_2' => 18,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 89,
                          'series_2' => 19,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 98,
                          'series_2' => 20,
                      ],
                  ],
              ],
              'priority_title' => 'Prioritas Direksi',
              'priorities' => [
                  [
                      'title' => 'Ekspansi layanan digital',
                      'description' => 'Target implementasi tahap pertama bulan berjalan.',
                      'icon' => 'compass',
                  ],
                  [
                      'title' => 'Efisiensi biaya operasional',
                      'description' => 'Evaluasi biaya antar-departemen sedang berlangsung.',
                      'icon' => 'sliders',
                  ],
                  [
                      'title' => 'Peningkatan SLA pelanggan',
                      'description' => 'Perlu percepatan penyelesaian keluhan prioritas.',
                      'icon' => 'clock',
                  ],
                  [
                      'title' => 'Penguatan kontrol internal',
                      'description' => 'Audit akses dan transaksi dijadwalkan minggu ini.',
                      'icon' => 'shield',
                  ],
              ],
              'table' => [
                  'title' => 'Kinerja Unit Bisnis',
                  'headers' => ['Unit', 'Pendapatan', 'Pencapaian', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'Pelayanan',
                          'col2' => 'Rp142 Jt',
                          'col3' => '96%',
                          'col4' => 'Baik',
                      ],
                      [
                          'col1' => 'Operasional',
                          'col2' => 'Rp128 Jt',
                          'col3' => '91%',
                          'col4' => 'Baik',
                      ],
                      [
                          'col1' => 'Konsultasi',
                          'col2' => 'Rp125 Jt',
                          'col3' => '104%',
                          'col4' => 'Melampaui',
                      ],
                      [
                          'col1' => 'Proyek Khusus',
                          'col2' => 'Rp91 Jt',
                          'col3' => '83%',
                          'col4' => 'Perhatian',
                      ],
                  ],
              ],
              'activity_title' => 'Keputusan dan Aktivitas Terbaru',
              'activities' => [
                  [
                      'title' => 'Rencana ekspansi disetujui',
                      'description' => 'Tahap awal ekspansi layanan digital telah disetujui.',
                      'time' => '20 menit lalu',
                      'icon' => 'check-circle',
                  ],
                  [
                      'title' => 'Laporan keuangan diperbarui',
                      'description' => 'Rekap bulan berjalan telah tersedia untuk direview.',
                      'time' => '1 jam lalu',
                      'icon' => 'file-text',
                  ],
                  [
                      'title' => 'Keluhan prioritas meningkat',
                      'description' => 'Empat kasus memerlukan koordinasi lintas unit.',
                      'time' => '2 jam lalu',
                      'icon' => 'alert-circle',
                  ],
                  [
                      'title' => 'Audit akses dijadwalkan',
                      'description' => 'Tim auditor akan melakukan pemeriksaan akses sistem.',
                      'time' => 'Kemarin',
                      'icon' => 'shield',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
