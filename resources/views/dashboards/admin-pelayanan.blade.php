@extends('layouts.app')

@section('title', 'Dashboard Admin Pelayanan')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Admin Pelayanan',
              'role_label' => 'Admin Pelayanan',
              'headline' => 'Customer Service Operations',
              'description' =>
                  'Pantau antrean pelayanan, penyelesaian permintaan, keluhan pelanggan, dan kualitas respons secara real time.',
              'accent' => '#0891b2',
              'accent_secondary' => '#2563eb',
              'statistics' => [
                  [
                      'label' => 'Permintaan Hari Ini',
                      'value' => '86',
                      'icon' => 'inbox',
                      'note' => '+12 dari kemarin',
                  ],
                  [
                      'label' => 'Selesai Ditangani',
                      'value' => '71',
                      'icon' => 'check-circle',
                      'note' => '82,6% terselesaikan',
                  ],
                  [
                      'label' => 'Keluhan Aktif',
                      'value' => '11',
                      'icon' => 'alert-triangle',
                      'note' => '3 prioritas tinggi',
                  ],
                  [
                      'label' => 'Kepuasan Layanan',
                      'value' => '94,5%',
                      'icon' => 'smile',
                      'note' => 'Kategori sangat baik',
                  ],
              ],
              'chart' => [
                  'title' => 'Volume dan Penyelesaian Layanan',
                  'subtitle' => 'Perbandingan jumlah permintaan masuk dan terselesaikan.',
                  'series_1_label' => 'Permintaan Masuk',
                  'series_2_label' => 'Terselesaikan',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 58,
                          'series_2' => 51,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 67,
                          'series_2' => 59,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 72,
                          'series_2' => 64,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 79,
                          'series_2' => 68,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 84,
                          'series_2' => 73,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 76,
                          'series_2' => 69,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 86,
                          'series_2' => 71,
                      ],
                  ],
              ],
              'priority_title' => 'Antrean Prioritas',
              'priorities' => [
                  [
                      'title' => 'Keluhan pembayaran',
                      'description' => 'Tiga tiket perlu konfirmasi bagian keuangan.',
                      'icon' => 'credit-card',
                  ],
                  [
                      'title' => 'Permintaan perubahan data',
                      'description' => 'Lima permintaan menunggu verifikasi identitas.',
                      'icon' => 'edit-3',
                  ],
                  [
                      'title' => 'Tindak lanjut pelanggan',
                      'description' => 'Hubungi kembali delapan pelanggan hari ini.',
                      'icon' => 'phone-call',
                  ],
                  [
                      'title' => 'Evaluasi SLA',
                      'description' => 'Dua kasus melewati target respons.',
                      'icon' => 'clock',
                  ],
              ],
              'table' => [
                  'title' => 'Tiket Pelayanan Terbaru',
                  'headers' => ['Nomor', 'Kategori', 'Pelanggan', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'TKT-24071',
                          'col2' => 'Pembayaran',
                          'col3' => 'PT Maju Jaya',
                          'col4' => 'Prioritas',
                      ],
                      [
                          'col1' => 'TKT-24072',
                          'col2' => 'Perubahan Data',
                          'col3' => 'Rina Putri',
                          'col4' => 'Berjalan',
                      ],
                      [
                          'col1' => 'TKT-24073',
                          'col2' => 'Informasi Layanan',
                          'col3' => 'CV Sejahtera',
                          'col4' => 'Selesai',
                      ],
                      [
                          'col1' => 'TKT-24074',
                          'col2' => 'Keluhan',
                          'col3' => 'Budi Santoso',
                          'col4' => 'Berjalan',
                      ],
                  ],
              ],
              'activity_title' => 'Aktivitas Pelayanan',
              'activities' => [
                  [
                      'title' => 'Tiket prioritas dibuat',
                      'description' => 'Keluhan pembayaran ditandai sebagai prioritas.',
                      'time' => '9 menit lalu',
                      'icon' => 'alert-circle',
                  ],
                  [
                      'title' => 'Permintaan selesai',
                      'description' => 'Tiket TKT-24073 telah diselesaikan.',
                      'time' => '22 menit lalu',
                      'icon' => 'check-circle',
                  ],
                  [
                      'title' => 'Pelanggan dihubungi',
                      'description' => 'Tindak lanjut untuk CV Sejahtera telah dilakukan.',
                      'time' => '40 menit lalu',
                      'icon' => 'phone',
                  ],
                  [
                      'title' => 'SLA diperbarui',
                      'description' => 'Parameter waktu respons layanan telah diperbarui.',
                      'time' => 'Kemarin',
                      'icon' => 'clock',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
