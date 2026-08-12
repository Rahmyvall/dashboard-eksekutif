<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Invoice {{ $invoice->invoice_number }}</title>
     <style>
          :root {
               --inv-primary: #4f46e5;
               --inv-secondary: #0891b2;
               --inv-text: #1e293b;
               --inv-muted: #64748b;
               --inv-border: #dbe3ef;
               --inv-soft: #f8fafc;
               --inv-soft-2: #eef2ff;
               --inv-success: #059669;
               --inv-warning: #b45309;
          }

          * {
               box-sizing: border-box;
          }

          body {
               margin: 0;
               color: var(--inv-text);
               font: 13px/1.6 'Segoe UI', Tahoma, sans-serif;
               background:
                    radial-gradient(circle at 8% 8%, rgba(79, 70, 229, .14), transparent 24%),
                    radial-gradient(circle at 95% 12%, rgba(8, 145, 178, .14), transparent 26%),
                    #eef2f7;
          }

          .print-actions {
               display: flex;
               justify-content: center;
               flex-wrap: wrap;
               gap: 10px;
               margin: 16px auto;
          }

          .print-actions button,
          .print-actions a {
               display: inline-flex;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               border: 0;
               border-radius: 10px;
               font-weight: 800;
               font-size: 12px;
               letter-spacing: .02em;
               cursor: pointer;
               text-decoration: none;
          }

          .print-actions button {
               color: #ffffff;
               background: linear-gradient(135deg, var(--inv-primary), #7c3aed);
               box-shadow: 0 10px 18px rgba(79, 70, 229, .24);
          }

          .print-actions a {
               color: #334155;
               background: #e2e8f0;
          }

          .paper {
               width: 210mm;
               min-height: 297mm;
               margin: 14px auto 30px;
               padding: 13mm;
               background: #ffffff;
               border-radius: 14px;
               box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16);
          }

          .header {
               display: flex;
               justify-content: space-between;
               gap: 18px;
               align-items: flex-start;
               padding: 14px;
               border: 1px solid #cfd8ea;
               border-radius: 12px;
               background:
                    radial-gradient(circle at 90% 0%, rgba(255, 255, 255, .24), transparent 22%),
                    linear-gradient(120deg, #4f46e5 0%, #7c3aed 48%, #0891b2 100%);
               color: #ffffff;
          }

          .brand h1 {
               margin: 0;
               font-size: 24px;
               letter-spacing: -0.02em;
               color: #ffffff;
          }

          .brand p {
               margin: 4px 0 0;
               color: rgba(255, 255, 255, .92);
               font-size: 12px;
          }

          .meta {
               text-align: right;
          }

          .meta .title {
               font-size: 21px;
               font-weight: 900;
               letter-spacing: 0.06em;
          }

          .meta .number {
               margin-top: 2px;
               font-size: 13px;
               font-weight: 750;
          }

          .kpi-grid {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 10px;
               margin-top: 12px;
          }

          .kpi-item {
               padding: 10px;
               border: 1px solid var(--inv-border);
               border-radius: 10px;
               background: var(--inv-soft);
          }

          .kpi-label {
               display: block;
               color: var(--inv-muted);
               font-size: 9px;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .07em;
               margin-bottom: 4px;
          }

          .kpi-value {
               color: #0f172a;
               font-size: 13px;
               font-weight: 800;
               line-height: 1.3;
          }

          .summary {
               display: grid;
               grid-template-columns: 1.2fr .8fr;
               gap: 12px;
               margin-top: 14px;
          }

          .summary-card {
               padding: 12px;
               border: 1px solid var(--inv-border);
               border-radius: 10px;
               background: #ffffff;
          }

          .summary-card.soft {
               background: var(--inv-soft-2);
          }

          .summary-fields {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 10px;
          }

          .label {
               display: block;
               color: var(--inv-muted);
               font-size: 9px;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .07em;
               margin-bottom: 3px;
          }

          .value {
               color: #0f172a;
               font-size: 12px;
               font-weight: 700;
               line-height: 1.45;
          }

          .section-title {
               margin: 18px 0 8px;
               color: #0f172a;
               font-size: 12px;
               font-weight: 850;
               letter-spacing: 0.05em;
               text-transform: uppercase;
          }

          table {
               width: 100%;
               border-collapse: collapse;
          }

          .items,
          .payments {
               overflow: hidden;
               border: 1px solid var(--inv-border);
               border-radius: 10px;
          }

          .items th,
          .items td,
          .payments th,
          .payments td {
               padding: 8px 7px;
               border: 1px solid var(--inv-border);
               vertical-align: top;
          }

          .items th,
          .payments th {
               color: #334155;
               font-size: 9px;
               font-weight: 850;
               text-transform: uppercase;
               letter-spacing: 0.06em;
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

          .text-center {
               text-align: center;
          }

          .muted {
               color: var(--inv-muted);
          }

          .payment-status {
               display: inline-flex;
               padding: 2px 7px;
               border-radius: 999px;
               font-size: 10px;
               font-weight: 800;
          }

          .payment-status.confirmed {
               color: #047857;
               background: #dcfce7;
          }

          .payment-status.pending {
               color: #b45309;
               background: #fef3c7;
          }

          .payment-status.cancelled,
          .payment-status.refunded {
               color: #be123c;
               background: #ffe4e6;
          }

          .totals {
               width: 370px;
               margin: 14px 0 0 auto;
               border: 1px solid var(--inv-border);
               border-radius: 10px;
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
               background: #eef2ff;
               font-size: 13px;
               font-weight: 850;
               color: #312e81;
          }

          .totals-row.success {
               background: #ecfdf5;
               color: var(--inv-success);
               font-weight: 800;
          }

          .totals-row.warning {
               background: #fff7ed;
               color: var(--inv-warning);
               font-weight: 800;
          }

          .notes {
               margin-top: 14px;
               padding: 10px;
               border: 1px solid var(--inv-border);
               border-radius: 10px;
               background: #fcfdff;
               font-size: 12px;
               min-height: 54px;
          }

          .signatures {
               display: grid;
               grid-template-columns: 1fr 1fr;
               gap: 28px;
               margin-top: 30px;
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
                    padding: 10mm;
                    border-radius: 0;
                    box-shadow: none;
               }

               .header {
                    border-color: #64748b;
                    color: #ffffff;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
               }
          }

          @media (max-width: 900px) {
               .paper {
                    width: auto;
                    margin: 0;
                    border-radius: 0;
               }

               .header {
                    display: block;
               }

               .meta {
                    text-align: left;
                    margin-top: 10px;
               }

               .summary {
                    grid-template-columns: 1fr;
               }

               .summary-fields,
               .kpi-grid {
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
     @php
          $payments = $invoice->payments ?? collect();
          $confirmedTotal = (float) $payments->where('status', \App\Models\Payment::STATUS_CONFIRMED)->sum('amount');
          $remainingAmount = max(0, (float) $invoice->total_amount - $confirmedTotal);
          $invoiceStatus = strtolower((string) $invoice->payment_status);
          $invoiceStatusLabel = \Illuminate\Support\Str::of($invoiceStatus)->replace('_', ' ')->title();
     @endphp

     <div class="print-actions">
          <button type="button" onclick="window.print()">Cetak Invoice</button>
          <a href="{{ route('super-admin.invoices.show', $invoice) }}">Kembali</a>
     </div>

     <main class="paper">
          <header class="header">
               <div class="brand">
                    <h1>Dashboard Monitoring Transaksi Jasa</h1>
                    <p>Dokumen resmi tagihan layanan dan ringkasan monitoring pembayaran</p>
                    <p>Alamat perusahaan | Telepon | Email perusahaan</p>
               </div>
               <div class="meta">
                    <div class="title">INVOICE</div>
                    <div class="number">{{ $invoice->invoice_number }}</div>
               </div>
          </header>

          <section class="kpi-grid">
               <div class="kpi-item">
                    <span class="kpi-label">Total Tagihan</span>
                    <span class="kpi-value">Rp {{ number_format((float) $invoice->total_amount, 2, ',', '.') }}</span>
               </div>
               <div class="kpi-item">
                    <span class="kpi-label">Total Terbayar</span>
                    <span class="kpi-value">Rp {{ number_format($confirmedTotal, 2, ',', '.') }}</span>
               </div>
               <div class="kpi-item">
                    <span class="kpi-label">Sisa Tagihan</span>
                    <span class="kpi-value">Rp {{ number_format($remainingAmount, 2, ',', '.') }}</span>
               </div>
               <div class="kpi-item">
                    <span class="kpi-label">Status Invoice</span>
                    <span class="kpi-value">{{ $invoiceStatusLabel }}</span>
               </div>
          </section>

          <section class="summary">
               <div class="summary-card">
                    <span class="label">Kepada</span>
                    <div class="value" style="margin-bottom: 8px;">
                         {{ $invoice->serviceOrder?->customer?->display_name ?? ($invoice->serviceOrder?->customer?->name ?? '-') }}
                    </div>

                    <div class="summary-fields">
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
                         <div>
                              <span class="label">Status Transaksi</span>
                              <div class="value">{{ $invoiceStatusLabel }}</div>
                         </div>
                    </div>
               </div>

               <div class="summary-card soft">
                    <span class="label">Monitoring Produktivitas dan Transaksi</span>
                    <div class="value" style="margin-bottom: 8px;">
                         Invoice ini merupakan bagian dari dashboard monitoring untuk menilai performa penyelesaian
                         order dan kecepatan pembayaran layanan.
                    </div>
                    <div class="muted" style="font-size: 11px;">
                         Fokus indikator: ketepatan tagihan, konfirmasi pembayaran, dan kontrol sisa piutang.
                    </div>
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
                    @forelse ($payments as $index => $payment)
                         <tr>
                              <td class="text-right">{{ $index + 1 }}</td>
                              <td>{{ $payment->payment_number }}</td>
                              <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
                              <td>{{ ucwords(str_replace('_', ' ', (string) $payment->payment_method)) }}</td>
                              <td>
                                   <span class="payment-status {{ strtolower((string) $payment->status) }}">
                                        {{ ucfirst((string) $payment->status) }}
                                   </span>
                              </td>
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
               <div class="totals-row success"><span>Total Terbayar (Terkonfirmasi)</span><strong>Rp
                         {{ number_format($confirmedTotal, 2, ',', '.') }}</strong></div>
               <div class="totals-row warning"><span>Sisa Tagihan</span><strong>Rp
                         {{ number_format($remainingAmount, 2, ',', '.') }}</strong>
               </div>
               <div class="totals-row grand"><span>Ringkasan Status Invoice</span><strong>
                         {{ $invoiceStatusLabel }}</strong>
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
