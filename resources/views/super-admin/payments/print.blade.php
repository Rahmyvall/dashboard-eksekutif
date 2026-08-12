<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Bukti Pembayaran {{ $payment->payment_number }}</title>
     <style>
          * {
               box-sizing: border-box;
          }

          body {
               margin: 0;
               color: #1f2937;
               font: 13px/1.55 'Segoe UI', Tahoma, sans-serif;
               background: #eef3f8;
          }

          .print-actions {
               display: flex;
               justify-content: center;
               gap: 10px;
               margin: 14px auto;
          }

          .print-actions button,
          .print-actions a {
               padding: 10px 16px;
               border: 0;
               border-radius: 8px;
               font-weight: 700;
               cursor: pointer;
               text-decoration: none;
          }

          .print-actions button {
               color: #fff;
               background: #0f766e;
          }

          .print-actions a {
               color: #334155;
               background: #dfe7f1;
          }

          .paper {
               width: 210mm;
               min-height: 297mm;
               margin: 12px auto 26px;
               padding: 12mm;
               background: #fff;
               box-shadow: 0 12px 30px rgba(15, 23, 42, 0.14);
               border: 1px solid #d9e2ef;
          }

          .doc-header {
               display: flex;
               justify-content: space-between;
               gap: 20px;
               align-items: flex-start;
               padding-bottom: 12px;
               border-bottom: 2px solid #0f766e;
          }

          .brand h1 {
               margin: 0;
               color: #0f172a;
               font-size: 22px;
               letter-spacing: -0.01em;
          }

          .brand p {
               margin: 3px 0 0;
               color: #64748b;
               font-size: 12px;
          }

          .doc-meta {
               text-align: right;
          }

          .doc-meta .title {
               color: #0f172a;
               font-size: 17px;
               font-weight: 800;
               letter-spacing: 0.04em;
          }

          .doc-meta .number {
               color: #0f766e;
               font-size: 14px;
               font-weight: 700;
               margin-top: 2px;
          }

          .section-title {
               margin: 18px 0 8px;
               color: #1e293b;
               font-size: 12px;
               font-weight: 800;
               letter-spacing: 0.05em;
               text-transform: uppercase;
          }

          .data-table,
          .amount-table {
               width: 100%;
               border-collapse: collapse;
               border: 1px solid #dbe3ef;
          }

          .data-table th,
          .data-table td,
          .amount-table th,
          .amount-table td {
               padding: 8px 10px;
               border-bottom: 1px solid #e6edf6;
               font-size: 12px;
               vertical-align: top;
          }

          .data-table th,
          .amount-table th {
               width: 28%;
               text-align: left;
               color: #475569;
               font-size: 11px;
               font-weight: 700;
               text-transform: uppercase;
               letter-spacing: 0.04em;
               background: #f8fafc;
          }

          .amount-table {
               margin-top: 8px;
          }

          .amount-table .grand th,
          .amount-table .grand td {
               color: #115e59;
               font-weight: 800;
               background: #ecfeff;
          }

          .notes {
               margin-top: 10px;
               padding: 10px 12px;
               border: 1px solid #dbe3ef;
               background: #fbfdff;
               font-size: 12px;
          }

          .footer {
               margin-top: 14px;
               color: #64748b;
               font-size: 10px;
               text-align: right;
          }

          @media print {
               body {
                    background: #fff;
               }

               .print-actions {
                    display: none;
               }

               .paper {
                    width: auto;
                    min-height: 0;
                    margin: 0;
                    padding: 0;
                    box-shadow: none;
                    border: 0;
               }
          }

          @media (max-width: 900px) {
               .paper {
                    width: auto;
                    margin: 0;
               }

               .doc-header {
                    display: block;
               }

               .doc-meta {
                    text-align: left;
                    margin-top: 10px;
               }

               .data-table th,
               .amount-table th {
                    width: 38%;
               }
          }
     </style>
</head>

<body>
     @php
          $companyName = config('app.name', 'Dashboard Eksekutif');
          $companyTagline = 'Bukti penerimaan pembayaran layanan';
          $invoice = $payment->invoice;
          $invoiceTotal = (float) ($invoice?->total_amount ?? 0);
          $confirmedBefore = 0.0;

          if ($invoice) {
              $confirmedBefore = (float) $invoice
                  ->payments()
                  ->where('status', \App\Models\Payment::STATUS_CONFIRMED)
                  ->where('id', '!=', $payment->id)
                  ->sum('amount');
          }

          $currentAmount = (float) $payment->amount;
          $remaining = max(
              0.0,
              $invoiceTotal -
                  ($confirmedBefore +
                      ($payment->status === \App\Models\Payment::STATUS_CONFIRMED ? $currentAmount : 0.0)),
          );
     @endphp

     <div class="print-actions">
          <button type="button" onclick="window.print()">Cetak Bukti</button>
          <a href="{{ route('super-admin.payments.show', $payment) }}">Kembali</a>
     </div>

     <main class="paper">
          <header class="doc-header">
               <div class="brand">
                    <h1>{{ $companyName }}</h1>
                    <p>{{ $companyTagline }}</p>
               </div>
               <div class="doc-meta">
                    <div class="title">PAYMENT RECEIPT</div>
                    <div class="number">{{ $payment->payment_number }}</div>
               </div>
          </header>

          <h3 class="section-title">Data Pembayaran</h3>
          <table class="data-table">
               <tr>
                    <th>Customer</th>
                    <td>{{ $payment->serviceOrder?->customer?->display_name ?? ($payment->serviceOrder?->customer?->name ?? '-') }}
                    </td>
               </tr>
               <tr>
                    <th>Tanggal Pembayaran</th>
                    <td>{{ $payment->payment_date?->format('d F Y') ?? '-' }}</td>
               </tr>
               <tr>
                    <th>Service Order</th>
                    <td>{{ $payment->serviceOrder?->order_number ?? '-' }}</td>
               </tr>
               <tr>
                    <th>Invoice</th>
                    <td>{{ $payment->invoice?->invoice_number ?? '-' }}</td>
               </tr>
               <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ ucwords(str_replace('_', ' ', (string) $payment->payment_method)) }}</td>
               </tr>
               <tr>
                    <th>Nomor Referensi</th>
                    <td>{{ $payment->reference_number ?: '-' }}</td>
               </tr>
               <tr>
                    <th>Status</th>
                    <td>{{ ucfirst((string) $payment->status) }}</td>
               </tr>
               <tr>
                    <th>Penerima</th>
                    <td>{{ $payment->receiver?->name ?? 'Admin' }}</td>
               </tr>
               <tr>
                    <th>No Bukti</th>
                    <td>{{ $payment->id }}</td>
               </tr>
          </table>

          <h3 class="section-title">Ringkasan Nilai</h3>
          <table class="amount-table">
               <tr>
                    <th>Total Tagihan Invoice</th>
                    <td>Rp {{ number_format($invoiceTotal, 2, ',', '.') }}</td>
               </tr>
               <tr>
                    <th>Total Terbayar Sebelumnya</th>
                    <td>Rp {{ number_format($confirmedBefore, 2, ',', '.') }}</td>
               </tr>
               <tr>
                    <th>Nominal Pembayaran Ini</th>
                    <td>Rp {{ number_format($currentAmount, 2, ',', '.') }}</td>
               </tr>
               <tr class="grand">
                    <th>Sisa Tagihan Setelah Pembayaran</th>
                    <td>Rp {{ number_format($remaining, 2, ',', '.') }}</td>
               </tr>
          </table>

          <div class="notes">
               <strong>Catatan:</strong> {{ $payment->notes ?: '-' }}
          </div>

          <div class="footer">Dicetak pada {{ now()->format('d F Y H:i') }}</div>
     </main>
</body>

</html>
