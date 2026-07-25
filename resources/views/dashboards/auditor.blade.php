extends('layouts.app')

@section('title', 'Dashboard Auditor')

@section('content')
     @php
          $roleConfig = [
              'page_title' => 'Dashboard Auditor',
              'role_label' => 'Internal Audit',
              'headline' => 'Audit, Risk & Compliance Center',
              'description' =>
                  'Pantau pelaksanaan audit, temuan, tindak lanjut, kepatuhan, dan risiko pengendalian internal melalui satu dashboard terintegrasi.',
              'accent' => '#b45309',
              'accent_secondary' => '#7c2d12',

              'statistics' => [
                  [
                      'label' => 'Audit Berjalan',
                      'value' => '7',
                      'icon' => 'search',
                      'note' => '3 audit prioritas tinggi',
                  ],
                  [
                      'label' => 'Temuan Terbuka',
                      'value' => '18',
                      'icon' => 'alert-triangle',
                      'note' => '5 temuan risiko tinggi',
                  ],
                  [
                      'label' => 'Tindak Lanjut Selesai',
                      'value' => '82%',
                      'icon' => 'check-circle',
                      'note' => '+6% dari periode lalu',
                  ],
                  [
                      'label' => 'Tingkat Kepatuhan',
                      'value' => '94,6%',
                      'icon' => 'shield',
                      'note' => 'Kategori sangat baik',
                  ],
              ],

              'chart' => [
                  'title' => 'Progres Audit dan Tindak Lanjut',
                  'subtitle' => 'Perbandingan target penyelesaian audit dengan realisasi selama tujuh periode.',
                  'series_1_label' => 'Target',
                  'series_2_label' => 'Realisasi',
                  'rows' => [
                      [
                          'label' => 'Sen',
                          'series_1' => 62,
                          'series_2' => 54,
                      ],
                      [
                          'label' => 'Sel',
                          'series_1' => 68,
                          'series_2' => 61,
                      ],
                      [
                          'label' => 'Rab',
                          'series_1' => 74,
                          'series_2' => 67,
                      ],
                      [
                          'label' => 'Kam',
                          'series_1' => 80,
                          'series_2' => 72,
                      ],
                      [
                          'label' => 'Jum',
                          'series_1' => 86,
                          'series_2' => 78,
                      ],
                      [
                          'label' => 'Sab',
                          'series_1' => 92,
                          'series_2' => 81,
                      ],
                      [
                          'label' => 'Min',
                          'series_1' => 100,
                          'series_2' => 82,
                      ],
                  ],
              ],

              'priority_title' => 'Prioritas Audit',

              'priorities' => [
                  [
                      'title' => 'Temuan risiko tinggi',
                      'description' => 'Lima temuan membutuhkan rencana koreksi dan batas waktu yang jelas.',
                      'icon' => 'alert-octagon',
                  ],
                  [
                      'title' => 'Review akses pengguna',
                      'description' => 'Validasi kesesuaian role dan permission untuk akun berisiko.',
                      'icon' => 'key',
                  ],
                  [
                      'title' => 'Audit transaksi keuangan',
                      'description' => 'Pemeriksaan sampel transaksi bulan berjalan sedang dilaksanakan.',
                      'icon' => 'credit-card',
                  ],
                  [
                      'title' => 'Monitoring tindak lanjut',
                      'description' => 'Tujuh rekomendasi mendekati batas waktu penyelesaian.',
                      'icon' => 'clock',
                  ],
              ],

              'table' => [
                  'title' => 'Daftar Temuan Audit',
                  'headers' => ['Nomor Temuan', 'Area', 'Tingkat Risiko', 'Status'],
                  'rows' => [
                      [
                          'col1' => 'AUD-2026-071',
                          'col2' => 'Manajemen Akses',
                          'col3' => 'Tinggi',
                          'col4' => 'Tindak Lanjut',
                      ],
                      [
                          'col1' => 'AUD-2026-072',
                          'col2' => 'Keuangan',
                          'col3' => 'Sedang',
                          'col4' => 'Dalam Review',
                      ],
                      [
                          'col1' => 'AUD-2026-073',
                          'col2' => 'Operasional',
                          'col3' => 'Sedang',
                          'col4' => 'Diperbaiki',
                      ],
                      [
                          'col1' => 'AUD-2026-074',
                          'col2' => 'Pelayanan',
                          'col3' => 'Rendah',
                          'col4' => 'Ditutup',
                      ],
                  ],
              ],

              'activity_title' => 'Aktivitas Audit Terbaru',

              'activities' => [
                  [
                      'title' => 'Temuan baru dicatat',
                      'description' => 'Satu temuan risiko tinggi pada manajemen akses telah ditambahkan.',
                      'time' => '11 menit lalu',
                      'icon' => 'alert-triangle',
                  ],
                  [
                      'title' => 'Bukti tindak lanjut diterima',
                      'description' => 'Unit operasional mengunggah dokumen perbaikan untuk diverifikasi.',
                      'time' => '32 menit lalu',
                      'icon' => 'upload-cloud',
                  ],
                  [
                      'title' => 'Audit keuangan dimulai',
                      'description' => 'Pemeriksaan sampel transaksi bulan berjalan telah dimulai.',
                      'time' => '1 jam lalu',
                      'icon' => 'search',
                  ],
                  [
                      'title' => 'Rekomendasi dinyatakan selesai',
                      'description' => 'Dua rekomendasi telah diverifikasi dan ditutup.',
                      'time' => 'Kemarin',
                      'icon' => 'check-circle',
                  ],
              ],
          ];
     @endphp

     @include('dashboards.partials.role-dashboard', [
         'roleConfig' => $roleConfig,
     ])
@endsection
