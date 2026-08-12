<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Invoice {{ $invoice->invoice_number }}</title>
     <style>
          * {
               box-sizing: border-box;
          }

          body {
               margin: 0;
               color: #1e293b;
               font: 13px/1.55 'Segoe UI', Tahoma, sans-serif;
               background: #eef2f7;
          }

          .print-actions {
               display: flex;
               justify-content: center;
               gap: 10px;
               margin: 16px auto;
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
               background: #334155;
          }

          .print-actions a {
               color: #334155;
               background: #e2e8f0;
          }

          .paper {
               width: 210mm;
               min-height: 297mm;
               margin: 14px auto 30px;
               padding: 14mm;
               background: #fff;
               box-shadow: 0 10px 30px rgba(15, 23, 42, 0.14);
          }

          .header {
               display: flex;
               justify-content: space-between;
               gap: 18px;
               align-items: flex-start;
               padding-bottom: 14px;
               border-bottom: 3px solid #334155;
          }

          .brand h1 {
               margin: 0;
               color: #0f172a;
               font-size: 24px;
               letter-spacing: -0.02em;
          }

          .brand p {
               margin: 4px 0 0;
               color: #64748b;
               font-size: 12px;
          }

          .meta {
               text-align: right;
          }

          .meta .title {
               color: #0f172a;
               font-size: 21px;
               font-weight: 800;
               letter-spacing: 0.04em;
          }

          .meta .number {
               color: #334155;
               font-size: 14px;
               font-weight: 700;
          }

          .summary {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 10px 14px;
               margin-top: 16px;
               padding: 12px;
               border: 1px solid #dbe3ef;
               border-radius: 8px;
               background: #f8fafc;
          }

          .label {
               display: block;
               color: #64748b;
               font-size: 10px;
               font-weight: 700;
               text-transform: uppercase;
               letter-spacing: 0.06em;
               margin-bottom: 3px;
          }

          .value {
               color: #0f172a;
               font-size: 12px;
               font-weight: 700;
          }

          .section-title {
               margin: 20px 0 8px;
               color: #1e293b;
               font-size: 13px;
               font-weight: 800;
               letter-spacing: 0.04em;
               text-transform: uppercase;
          }

          table {
               width: 100%;
               border-collapse: collapse;
          }

          .items th,
          .items td,
          .payments th,
          .payments td {
               padding: 8px 7px;
               border: 1px solid #dbe3ef;
               vertical-align: top;
          }

          .items th,
          .payments th {
               color: #334155;
               font-size: 10px;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: 0.05em;
               background: #f1f5f9;
          }

          .items td,
          .payments td {
               font-size: 11px;
               color: #1f2937;
          }

          .text-right {
               text-align: right;
          }

          .muted {
               color: #64748b;
          }

          .totals {
               width: 360px;
               margin: 16px 0 0 auto;
               border: 1px solid #dbe3ef;
               border-radius: 8px;
               overflow: hidden;
          }

          .totals-row {
               display: flex;
               justify-content: space-between;
               padding: 8px 10px;
               border-bottom: 1px solid #e5ebf4;
               font-size: 12px;
          }

          .totals-row:last-child {
               border-bottom: 0;
          }

          .totals-row.grand {
               background: #f1f5f9;
               font-size: 13px;
               font-weight: 800;
          }

          .notes {
               margin-top: 16px;
               padding: 10px;
               border: 1px solid #dbe3ef;
               border-radius: 8px;
               background: #fcfdff;
               font-size: 12px;
               min-height: 54px;
          }

          .signatures {
               display: grid;
               grid-template-columns: 1fr 1fr;
               gap: 28px;
               margin-top: 34px;
          }

          .sign-box {
               text-align: center;
          }

          .line {
               margin-top: 56px;
               border-top: 1px solid #94a3b8;
               padding-top: 6px;
               font-size: 11px;
               color: #334155;
               font-weight: 700;
          }

          .footer {
               margin-top: 18px;
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
                    padding: 12mm;
                    box-shadow: none;
               }
          }

          @media (max-width: 900px) {
               .paper {
                    width: auto;
                    margin: 0;
               }

               .header {
                    display: block;
               }

               .meta {
                    text-align: left;
                    margin-top: 10px;
               }

               .summary {
                    grid-template-columns: 1fr 1fr;
               }

               .signatures {
                    grid-template-columns: 1fr;
               }

               .totals {
                    width: 100%;
               }
          }
     </style>
</head>

<body>
     <div class="print-actions">
          <button type="button" onclick="window.print()">Cetak Invoice</button>
          <a href="{{ route('super-admin.invoices.show', $invoice) }}">Kembali</a>
     </div>

     <main class="paper">
          <header class="header">
               <div class="brand">
                    <h1>Dashboard Eksekutif</h1>
                    <p>Dokumen resmi tagihan layanan</p>
                    <p>Alamat perusahaan | Telepon | Email perusahaan</p>
               </div>
               <div class="meta">
                    <div class="title">INVOICE</div>
                    <div class="number">{{ $invoice->invoice_number }}</div>
               </div>
          </header>

          <section class="summary">
               <div>
                    <span class="label">Kepada</span>
                    <div class="value">
                         {{ $invoice->serviceOrder?->customer?->display_name ?? ($invoice->serviceOrder?->customer?->name ?? '-') }}
                    </div>
               </div>
               <div>
                    <span class="label">Service Order</span>
                    <div class="value">{{ $invoice->serviceOrder?->order_number ?? '-' }}</div>
               </div>
               <div>
                    <span class="label">Tanggal Invoice</span>
                    <div class="value">{{ $invoice->invoice_date?->format('d F Y') ?? '-' }}</div>
               </div>
               <div>
                    <span class="label">Jatuh Tempo</span>
                    <div class="value">{{ $invoice->due_date?->format('d F Y') ?? '-' }}</div>
               </div>
          </section>

          <h3 class="section-title">Rincian Layanan</h3>
          <table class="items">
               <thead>
                    <tr>
                         <th style="width: 40px;" class="text-right">No</th>
                         <th>Layanan</th>
                         <th style="width: 80px;" class="text-right">Qty</th>
                         <th style="width: 110px;" class="text-right">Harga</th>
                         <th style="width: 110px;" class="text-right">Diskon</th>
                         <th style="width: 130px;" class="text-right">Subtotal</th>
                    </tr>
               </thead>
               <tbody>
                    @forelse ($invoice->serviceOrder?->items ?? [] as $index => $item)
                         <tr>
                              <td class="text-right">{{ $index + 1 }}</td>
                              <td>
                                   {{ $item->service?->name ?? '-' }}
                                   <div class="muted">{{ $item->notes ?: '-' }}</div>
                              </td>
                              <td class="text-right">{{ number_format((float) $item->quantity, 2, ',', '.') }}</td>
                              <td class="text-right">Rp {{ number_format((float) $item->unit_price, 2, ',', '.') }}
                              </td>
                              <td class="text-right">Rp {{ number_format((float) $item->discount, 2, ',', '.') }}</td>
                              <td class="text-right">Rp {{ number_format((float) $item->subtotal, 2, ',', '.') }}</td>
                         </tr>
                    @empty
                         <tr>
                              <td colspan="6" class="text-center muted">Tidak ada item layanan.</td>
                         </tr>
                    @endforelse
               </tbody>
          </table>

          <div class="totals">
               <div class="totals-row"><span>Subtotal</span><strong>Rp
                         {{ number_format((float) $invoice->subtotal, 2, ',', '.') }}</strong></div>
               <div class="totals-row"><span>Diskon</span><strong>Rp
                         {{ number_format((float) $invoice->discount, 2, ',', '.') }}</strong></div>
               <div class="totals-row"><span>Pajak</span><strong>Rp
                         {{ number_format((float) $invoice->tax, 2, ',', '.') }}</strong></div>
               <div class="totals-row grand"><span>Total Tagihan</span><strong>Rp
                         {{ number_format((float) $invoice->total_amount, 2, ',', '.') }}</strong></div>
          </div>

          <h3 class="section-title">Riwayat Pembayaran</h3>
          <table class="payments">
               <thead>
                    <tr>
                         <th style="width: 40px;" class="text-right">No</th>
                         <th>No Pembayaran</th>
                         <th>Tanggal</th>
                         <th>Metode</th>
                         <th>Status</th>
                         <th style="width: 130px;" class="text-right">Jumlah</th>
                    </tr>
               </thead>
               <tbody>
                    @php
                         $payments = $invoice->payments ?? collect();
                         $confirmedTotal = (float) $payments
                             ->where('status', \App\Models\Payment::STATUS_CONFIRMED)
                             ->sum('amount');
                    @endphp
                    @forelse ($payments as $index => $payment)
                         <tr>
                              <td class="text-right">{{ $index + 1 }}</td>
                              <td>{{ $payment->payment_number }}</td>
                              <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
                              <td>{{ ucwords(str_replace('_', ' ', (string) $payment->payment_method)) }}</td>
                              <td>{{ ucfirst((string) $payment->status) }}</td>
                              <td class="text-right">Rp {{ number_format((float) $payment->amount, 2, ',', '.') }}</td>
                         </tr>
                    @empty
                         <tr>
                              <td colspan="6" class="text-center muted">Belum ada pembayaran.</td>
                         </tr>
                    @endforelse
               </tbody>
          </table>

          <div class="totals" style="margin-top: 10px;">
               <div class="totals-row"><span>Total Terbayar (Terkonfirmasi)</span><strong>Rp
                         {{ number_format($confirmedTotal, 2, ',', '.') }}</strong></div>
               <div class="totals-row grand"><span>Sisa Tagihan</span><strong>Rp
                         {{ number_format(max(0, (float) $invoice->total_amount - $confirmedTotal), 2, ',', '.') }}</strong>
               </div>
          </div>

          <div class="notes">
               <span class="label">Catatan Invoice</span>
               {{ $invoice->notes ?: '-' }}
          </div>

          <section class="signatures">
               <div class="sign-box">
                    <div class="muted">Mengetahui,</div>
                    <div class="line">Keuangan</div>
               </div>
               <div class="sign-box">
                    <div class="muted">{{ now()->format('d F Y') }}</div>
                    <div class="line">Pelanggan</div>
               </div>
          </section>

          <div class="footer">
               Dicetak pada {{ now()->format('d F Y H:i') }}
          </div>
     </main>
</body>

</html>
