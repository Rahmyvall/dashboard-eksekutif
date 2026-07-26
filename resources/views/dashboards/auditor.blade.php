@extends('layouts.app')

@section('title', 'Dashboard Auditor | Monitoring Kinerja & Kepuasan Pelanggan')

@section('content')
     @php
          $currentUser = auth()->user();
          $currentUserName = $currentUser?->name ?? 'Auditor';

          $statistics = [
              [
                  'label' => 'Kepatuhan KPI',
                  'value' => 94,
                  'suffix' => '%',
                  'icon' => 'check-circle',
                  'note' => 'Evaluasi kinerja sesuai standar',
              ],
              [
                  'label' => 'Temuan Audit Aktif',
                  'value' => 12,
                  'suffix' => '',
                  'icon' => 'alert-triangle',
                  'note' => '4 temuan prioritas tinggi',
              ],
              [
                  'label' => 'Kepuasan Pelanggan',
                  'value' => 88.7,
                  'suffix' => '%',
                  'icon' => 'smile',
                  'note' => 'Berdasarkan survei terbaru',
              ],
              [
                  'label' => 'Laporan Selesai',
                  'value' => 86,
                  'suffix' => '%',
                  'icon' => 'file-text',
                  'note' => 'Realisasi audit periode berjalan',
              ],
          ];

          $auditFindings = [
              [
                  'title' => 'SLA pelayanan melewati target',
                  'unit' => 'Pelayanan',
                  'level' => 'Tinggi',
                  'status' => 'Perlu Tindak Lanjut',
              ],
              [
                  'title' => 'Dokumentasi KPI belum lengkap',
                  'unit' => 'Operasional',
                  'level' => 'Sedang',
                  'status' => 'Dalam Review',
              ],
              [
                  'title' => 'Validasi data pelanggan',
                  'unit' => 'Customer Service',
                  'level' => 'Rendah',
                  'status' => 'Selesai',
              ],
          ];

          $unitPerformance = [
              ['name' => 'Pelayanan Pelanggan', 'score' => 92],
              ['name' => 'Operasional', 'score' => 86],
              ['name' => 'Keuangan', 'score' => 90],
              ['name' => 'SDM', 'score' => 84],
          ];

          $activities = [
              ['title' => 'Audit KPI periode Juli selesai', 'time' => '10 menit lalu', 'icon' => 'clipboard'],
              ['title' => 'Temuan baru dibuat', 'time' => '35 menit lalu', 'icon' => 'alert-circle'],
              ['title' => 'Laporan audit dikirim ke Direktur', 'time' => '1 jam lalu', 'icon' => 'send'],
          ];
     @endphp

     <div class="auditor-dashboard">
          <style>
               .auditor-dashboard {
                    padding: 24px;
                    background: #f5f7fb;
                    color: #334155;
                    min-height: 100vh
               }

               .aud-card {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 18px;
                    padding: 22px;
                    margin-bottom: 20px;
                    box-shadow: 0 8px 25px rgba(15, 23, 42, .06)
               }

               .aud-hero {
                    background: linear-gradient(135deg, #0f766e, #2563eb);
                    color: white;
                    padding: 35px;
                    border-radius: 24px;
                    margin-bottom: 24px
               }

               .aud-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 18px
               }

               .aud-title {
                    font-size: 32px;
                    font-weight: 800
               }

               .aud-card h2 {
                    font-size: 18px;
                    color: #0f172a
               }

               .aud-value {
                    font-size: 32px;
                    font-weight: 800;
                    color: #2563eb
               }

               .aud-row {
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0
               }

               .aud-bar {
                    height: 8px;
                    background: #e5e7eb;
                    border-radius: 20px;
                    overflow: hidden
               }

               .aud-bar span {
                    display: block;
                    height: 100%;
                    background: #2563eb
               }

               .badge {
                    padding: 5px 10px;
                    border-radius: 20px;
                    background: #fee2e2;
                    color: #b91c1c;
                    font-size: 12px
               }

               @media(max-width:900px) {
                    .aud-grid {
                         grid-template-columns: 1fr 1fr
                    }
               }

               @media(max-width:600px) {
                    .aud-grid {
                         grid-template-columns: 1fr
                    }
               }
          </style>

          <section class="aud-hero">
               <div> AUDITOR DASHBOARD</div>
               <h1 class="aud-title">Monitoring Audit Kinerja & Kepuasan Pelanggan</h1>
               <p>Selamat datang {{ $currentUserName }}. Pantau kepatuhan proses, kualitas layanan, KPI unit kerja, dan
                    tindak lanjut temuan audit.</p>
          </section>

          <section class="aud-grid">
               @foreach ($statistics as $item)
                    <div class="aud-card"><i data-feather="{{ $item['icon'] }}"></i>
                         <p>{{ $item['label'] }}</p>
                         <div class="aud-value">{{ $item['value'] }}{{ $item['suffix'] }}</div>
                         <small>{{ $item['note'] }}</small>
                    </div>
               @endforeach
          </section>

          <div class="aud-card">
               <h2>Monitoring Temuan Audit</h2>
               @foreach ($auditFindings as $item)
                    <div class="aud-row">
                         <div><b>{{ $item['title'] }}</b><br><small>{{ $item['unit'] }}</small></div><span
                              class="badge">{{ $item['level'] }}</span>
                    </div>
               @endforeach
          </div>

          <div class="aud-card">
               <h2>Kinerja Unit Terhadap Standar</h2>
               @foreach ($unitPerformance as $item)
                    <div class="aud-row"><b>{{ $item['name'] }}</b><span>{{ $item['score'] }}%</span></div>
                    <div class="aud-bar"><span style="width:{{ $item['score'] }}%"></span></div>
               @endforeach
          </div>

          <div class="aud-card">
               <h2>Aktivitas Audit Terbaru</h2>
               @foreach ($activities as $item)
                    <p><i data-feather="{{ $item['icon'] }}"></i> {{ $item['title'] }} - {{ $item['time'] }}</p>
               @endforeach
          </div>
     </div>

     <script>
          document.addEventListener('DOMContentLoaded', () => {
               if (typeof feather !== 'undefined') feather.replace();
          });
     </script>
@endsection
