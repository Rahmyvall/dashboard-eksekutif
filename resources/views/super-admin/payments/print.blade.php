<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Bukti Pembayaran {{ $payment->payment_number }}</title>
     <style>
          :root {
               --pay-primary: #0f766e;
               --pay-secondary: #0ea5e9;
               --pay-text: #1f2937;
               --pay-muted: #64748b;
               --pay-border: #dbe3ef;
               --pay-soft: #f8fafc;
          }

          * {
               box-sizing: border-box;
          }

          body {
               margin: 0;
               color: var(--pay-text);
               font: 13px/1.55 'Segoe UI', Tahoma, sans-serif;
               background:
                    radial-gradient(circle at 8% 8%, rgba(15, 118, 110, 0.16), transparent 24%),
                    radial-gradient(circle at 92% 12%, rgba(14, 165, 233, 0.14), transparent 26%),
                    #eef3f8;
          }

          .paper {
               width: 210mm;
               min-height: 297mm;
               margin: 16px auto 26px;
               padding: 12mm;
               background: #fff;
               border-radius: 12px;
               box-shadow: 0 12px 30px rgba(15, 23, 42, 0.14);
               border: 1px solid #d9e2ef;
          }

          .doc-header {
               display: flex;
               justify-content: space-between;
               gap: 20px;
               align-items: flex-start;
               padding: 14px;
               border: 1px solid #bde8e1;
               border-radius: 12px;
               background:
                    radial-gradient(circle at 90% 0%, rgba(255, 255, 255, .26), transparent 24%),
                    linear-gradient(125deg, #0f766e 0%, #0891b2 52%, #4f46e5 100%);
               color: #fff;
          }

          .brand h1 {
               margin: 0;
               color: #fff;
               font-size: 22px;
               letter-spacing: -0.01em;
          }

          .brand p {
               margin: 3px 0 0;
               color: rgba(255, 255, 255, 0.9);
               font-size: 12px;
          }

          .doc-meta {
               text-align: right;
          }

          .doc-meta .title {
               color: #fff;
               font-size: 17px;
               font-weight: 800;
               letter-spacing: 0.04em;
          }

          .doc-meta .number {
               color: #e2fafe;
               font-size: 14px;
               font-weight: 700;
               margin-top: 2px;
          }

          .kpi-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 10px;
               margin-top: 12px;
          }

          .kpi-card {
               padding: 10px;
               border: 1px solid var(--pay-border);
               border-radius: 10px;
               background: var(--pay-soft);
          }

          .kpi-label {
               color: var(--pay-muted);
               font-size: 9px;
               font-weight: 800;
               letter-spacing: .07em;
               text-transform: uppercase;
          }

          .kpi-value {
               margin-top: 4px;
               color: #0f172a;
               font-size: 12px;
               font-weight: 800;
               line-height: 1.3;
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
               border-radius: 10px;
               overflow: hidden;
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

          .amount-table .highlight th,
          .amount-table .highlight td {
               color: #1e3a8a;
               font-weight: 800;
               background: #eef2ff;
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

               .paper {
                    width: auto;
                    min-height: 0;
                    margin: 0;
                    padding: 8mm;
                    border-radius: 0;
                    box-shadow: none;
                    border: 0;
               }

               .doc-header {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
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

               .kpi-grid {
                    grid-template-columns: 1fr 1fr;
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
          $confirmedCurrent = $payment->status === \App\Models\Payment::STATUS_CONFIRMED ? $currentAmount : 0.0;
          $remaining = max(0.0, $invoiceTotal - ($confirmedBefore + $confirmedCurrent));
     @endphp

     <main class="paper">
          <header class="doc-header">
               <div class="brand">
                    <h1>Dashboard Monitoring Transaksi Jasa</h1>
                    <p>{{ $companyName }} - {{ $companyTagline }}</p>
               </div>
               <div class="doc-meta">
                    <div class="title">PAYMENT RECEIPT</div>
                    <div class="number">{{ $payment->payment_number }}</div>
               </div>
          </header>

          <section class="kpi-grid">
               <div class="kpi-card">
                    <span class="kpi-label">Nominal Pembayaran</span>
                    <div class="kpi-value">Rp {{ number_format($currentAmount, 2, ',', '.') }}</div>
               </div>
               <div class="kpi-card">
                    <span class="kpi-label">Status Pembayaran</span>
                    <div class="kpi-value">{{ ucfirst((string) $payment->status) }}</div>
               </div>
               <div class="kpi-card">
                    <span class="kpi-label">Total Invoice</span>
                    <div class="kpi-value">Rp {{ number_format($invoiceTotal, 2, ',', '.') }}</div>
               </div>
               <div class="kpi-card">
                    <span class="kpi-label">Sisa Tagihan</span>
                    <div class="kpi-value">Rp {{ number_format($remaining, 2, ',', '.') }}</div>
               </div>
          </section>

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
               <tr class="highlight">
                    <th>Nominal Terkonfirmasi Dokumen Ini</th>
                    <td>Rp {{ number_format($confirmedCurrent, 2, ',', '.') }}</td>
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
