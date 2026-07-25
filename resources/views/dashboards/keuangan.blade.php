extends('layouts.app')

@section('title', 'Dashboard Keuangan')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Keuangan',
              'role_label' => 'Finance Dashboard',
              'headline' => 'Financial Monitoring Center',
              'description' =>
                  'Pantau pendapatan, pengeluaran, arus kas, piutang, transaksi, dan indikator kesehatan keuangan.',
              'accent' => '#059669',
              'accent_secondary' => '#0f766e',
              'statistics' => [
                  [
                      'label' => 'Pendapatan Bulan Ini',
                      'value' => 'Rp486,03 Jt',
                      'icon' => 'trending-up',
                      'note' => '+8,5% dari bulan lalu',
                  ],
                  [
                      'label' => 'Pengeluaran',
                      'value' => 'Rp387,22 Jt',
                      'icon' => 'credit-card',
                      'note' => '79,7% dari pendapatan',
                  ],
                  [
                      'label' => 'Arus Kas Bersih',
                      'value' => 'Rp98,81 Jt',
                      'icon' => 'dollar-sign',
                      'note' => 'Positif',
                  ],
                  [
                      'label' => 'Piutang Jatuh Tempo',
                      'value' => 'Rp42,50 Jt',
                      'icon' => 'alert-circle',
                      'note' => '12 invoice',
                  ],
              ],
              'chart' => [
                  'title' => 'Arus Kas Perusahaan',
                  'subtitle' => 'Perbandingan kas masuk dan kas keluar selama tujuh periode.',
                  'series_1_label' => 'Kas Masuk',
                  'series_2_label' => 'Kas Keluar',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 66,
                          'series_2' => 52,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 71,
                          'series_2' => 55,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 70,
                          'series_2' => 56,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 79,
                          'series_2' => 61,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 85,
                          'series_2' => 67,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 90,
                          'series_2' => 72,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 100,
                          'series_2' => 80,
                      ],
                  ],
              ],
              'priority_title' => 'Prioritas Keuangan',
              'priorities' => [
                  [
                      'title' => 'Penagihan piutang',
                      'description' => 'Dua belas invoice telah memasuki jatuh tempo.',
                      'icon' => 'file-minus',
                  ],
                  [
                      'title' => 'Rekonsiliasi bank',
                      'description' => 'Rekonsiliasi rekening utama belum selesai.',
                      'icon' => 'repeat',
                  ],
                  [
                      'title' => 'Persetujuan pembayaran',
                      'description' => 'Delapan transaksi menunggu otorisasi.',
                      'icon' => 'check-square',
                  ],
                  [
                      'title' => 'Penyusunan laporan',
                      'description' => 'Laporan bulan berjalan masuk tahap finalisasi.',
                      'icon' => 'file-text',
                  ],
              ],
              'table' => [
                  'title' => 'Transaksi Keuangan Terbaru',
                  'headers' => ['Referensi', 'Jenis', 'Nominal', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'TRX-260724-01',
                          'col2' => 'Penerimaan',
                          'col3' => 'Rp12,50 Jt',
                          'col4' => 'Terverifikasi',
                      ],
                      [
                          'col1' => 'TRX-260724-02',
                          'col2' => 'Pembayaran',
                          'col3' => 'Rp8,75 Jt',
                          'col4' => 'Menunggu',
                      ],
                      [
                          'col1' => 'TRX-260724-03',
                          'col2' => 'Penerimaan',
                          'col3' => 'Rp6,25 Jt',
                          'col4' => 'Terverifikasi',
                      ],
                      [
                          'col1' => 'TRX-260724-04',
                          'col2' => 'Pembayaran',
                          'col3' => 'Rp4,80 Jt',
                          'col4' => 'Diproses',
                      ],
                  ],
              ],
              'activity_title' => 'Aktivitas Keuangan',
              'activities' => [
                  [
                      'title' => 'Penerimaan diverifikasi',
                      'description' => 'Transaksi Rp12,50 juta telah direkonsiliasi.',
                      'time' => '10 menit lalu',
                      'icon' => 'check-circle',
                  ],
                  [
                      'title' => 'Pembayaran menunggu otorisasi',
                      'description' => 'Satu transaksi memerlukan persetujuan pejabat.',
                      'time' => '26 menit lalu',
                      'icon' => 'clock',
                  ],
                  [
                      'title' => 'Invoice baru diterbitkan',
                      'description' => 'Empat invoice pelanggan berhasil dibuat.',
                      'time' => '45 menit lalu',
                      'icon' => 'file-plus',
                  ],
                  [
                      'title' => 'Laporan kas diperbarui',
                      'description' => 'Posisi kas harian telah diperbarui.',
                      'time' => 'Kemarin',
                      'icon' => 'bar-chart-2',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
