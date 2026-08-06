<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <title>Laporan Performance Indicators</title>
     <style>
          @page {
               margin: 22px 24px;
          }

          body {
               margin: 0;
               color: #1f2937;
               font-family: DejaVu Sans, sans-serif;
               font-size: 9px;
          }

          .header {
               margin-bottom: 14px;
               padding-bottom: 10px;
               border-bottom: 2px solid #4f46e5;
          }

          .header h1 {
               margin: 0 0 5px;
               color: #312e81;
               font-size: 18px;
          }

          .header p {
               margin: 0;
               color: #64748b;
               font-size: 9px;
          }

          .summary {
               width: 100%;
               margin-bottom: 13px;
               border-collapse: separate;
               border-spacing: 6px 0;
          }

          .summary td {
               width: 25%;
               padding: 8px 10px;
               border: 1px solid #dbe3ef;
               background: #f8fafc;
          }

          .summary .label {
               display: block;
               margin-bottom: 3px;
               color: #64748b;
               font-size: 7px;
               font-weight: bold;
               text-transform: uppercase;
          }

          .summary .value {
               color: #111827;
               font-size: 14px;
               font-weight: bold;
          }

          .filter-info {
               margin-bottom: 10px;
               padding: 7px 9px;
               border: 1px solid #e5e7eb;
               background: #fafafa;
               color: #475569;
          }

          table.data {
               width: 100%;
               border-collapse: collapse;
               table-layout: fixed;
          }

          table.data th {
               padding: 7px 5px;
               color: #ffffff;
               background: #4f46e5;
               border: 1px solid #4338ca;
               font-size: 7px;
               text-align: center;
               text-transform: uppercase;
          }

          table.data td {
               padding: 6px 5px;
               border: 1px solid #dbe3ef;
               vertical-align: top;
               word-wrap: break-word;
          }

          table.data tr:nth-child(even) td {
               background: #f8fafc;
          }

          .center {
               text-align: center;
          }

          .right {
               text-align: right;
          }

          .status-active {
               color: #047857;
               font-weight: bold;
          }

          .status-inactive {
               color: #be123c;
               font-weight: bold;
          }

          .footer {
               margin-top: 10px;
               color: #94a3b8;
               font-size: 7px;
               text-align: right;
          }
     </style>
</head>

<body>
     <div class="header">
          <h1>Laporan Performance Indicators</h1>
          <p>
               Dicetak pada {{ $generatedAt->translatedFormat('d F Y H:i') }} WIB.
               Data mengikuti pencarian, filter, dan pengurutan pada halaman daftar.
          </p>
     </div>

     <div class="filter-info">
          <strong>Filter:</strong>
          Pencarian = {{ filled($filters['search'] ?? null) ? $filters['search'] : 'Semua' }};
          Status = {{ $statusOptions[$filters['status'] ?? ''] ?? 'Semua' }};
          Arah target = {{ $directionOptions[$filters['target_direction'] ?? ''] ?? 'Semua' }};
          Urut = {{ $filters['sort_by'] ?? 'code' }} {{ strtoupper($filters['sort_direction'] ?? 'asc') }}.
     </div>

     <table class="data">
          <thead>
               <tr>
                    <th style="width: 3%;">No.</th>
                    <th style="width: 8%;">Kode</th>
                    <th style="width: 16%;">Nama</th>
                    <th style="width: 24%;">Deskripsi</th>
                    <th style="width: 7%;">Satuan</th>
                    <th style="width: 7%;">Bobot</th>
                    <th style="width: 15%;">Arah Target</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 12%;">Diperbarui</th>
               </tr>
          </thead>
          <tbody>
               @forelse ($performanceIndicators as $performanceIndicator)
                    <tr>
                         <td class="center">{{ $loop->iteration }}</td>
                         <td>{{ $performanceIndicator->code }}</td>
                         <td>{{ $performanceIndicator->name }}</td>
                         <td>{{ $performanceIndicator->description ?: '-' }}</td>
                         <td class="center">{{ $performanceIndicator->unit }}</td>
                         <td class="right">
                              {{ number_format((float) $performanceIndicator->weight, 2, ',', '.') }}%
                         </td>
                         <td>
                              {{ $directionOptions[$performanceIndicator->target_direction] ?? $performanceIndicator->target_direction }}
                         </td>
                         <td
                              class="center {{ $performanceIndicator->status === 'active' ? 'status-active' : 'status-inactive' }}">
                              {{ $statusOptions[$performanceIndicator->status] ?? $performanceIndicator->status }}
                         </td>
                         <td class="center">
                              {{ optional($performanceIndicator->updated_at)->format('d/m/Y H:i') ?? '-' }}
                         </td>
                    </tr>
               @empty
                    <tr>
                         <td colspan="9" class="center">Tidak ada data yang sesuai filter.</td>
                    </tr>
               @endforelse
          </tbody>
     </table>

     <div class="footer">
          Sistem Monitoring Kinerja - Performance Indicator Management
     </div>
</body>

</html>
