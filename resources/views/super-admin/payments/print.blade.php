<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Pembayaran {{ $payment->payment_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; color: #172033; font: 14px/1.55 Arial, sans-serif; background: #f1f5f9; }
        .paper { width: 210mm; min-height: 148mm; margin: 24px auto; padding: 18mm; background: #fff; box-shadow: 0 8px 30px rgba(15,23,42,.12); }
        .brand { display:flex; justify-content:space-between; gap:24px; padding-bottom:18px; border-bottom:3px solid #0f766e; }
        .brand h1 { margin:0; color:#0f766e; font-size:25px; letter-spacing:-.03em; }
        .brand p { margin:4px 0 0; color:#64748b; }
        .receipt-title { text-align:right; }.receipt-title h2 { margin:0; font-size:22px; }.receipt-title span { color:#0f766e; font-weight:700; }
        .summary { display:grid; grid-template-columns:1fr 1fr; gap:12px 30px; margin:25px 0; padding:17px; border-radius:10px; background:#f8fafc; }
        .item label { display:block; margin-bottom:3px; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; }.item strong { font-size:14px; }
        .amount-box { display:flex; justify-content:space-between; align-items:center; padding:18px 0; margin-top:8px; border-top:1px solid #cbd5e1; border-bottom:1px solid #cbd5e1; }.amount-label { color:#64748b; font-weight:700; }.amount { color:#0f766e; font-size:23px; font-weight:800; }
        .notes { min-height:55px; margin-top:20px; padding:12px; border:1px solid #e2e8f0; border-radius:8px; }.notes label { display:block; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; }
        .footer { display:flex; justify-content:space-between; gap:20px; margin-top:35px; color:#64748b; font-size:11px; }.signature { width:180px; padding-top:45px; text-align:center; border-top:1px solid #94a3b8; }
        .print-actions { display:flex; justify-content:center; gap:10px; margin:18px auto; }.print-actions button,.print-actions a { padding:10px 16px; color:#fff; border:0; border-radius:7px; background:#0f766e; text-decoration:none; cursor:pointer; }.print-actions a { color:#334155; background:#e2e8f0; }
        @media print { body { background:#fff; }.paper { width:auto; min-height:0; margin:0; padding:0; box-shadow:none; }.print-actions { display:none; } }
        @media(max-width:700px) { .paper { width:auto; margin:0; padding:20px; }.brand,.amount-box,.footer { display:block; }.receipt-title { margin-top:18px; text-align:left; }.summary { grid-template-columns:1fr; }.signature { margin:35px 0 0; } }
    </style>
</head>
<body>
    <div class="print-actions"><button type="button" onclick="window.print()">Cetak Bukti</button><a href="{{ route('super-admin.payments.show', $payment) }}">Kembali</a></div>
    <main class="paper">
        <header class="brand"><div><h1>Dashboard Eksekutif</h1><p>Bukti penerimaan pembayaran layanan</p></div><div class="receipt-title"><h2>BUKTI PEMBAYARAN</h2><span>{{ $payment->payment_number }}</span></div></header>
        <section class="summary"><div class="item"><label>Customer</label><strong>{{ $payment->serviceOrder?->customer?->display_name ?? $payment->serviceOrder?->customer?->name ?? '-' }}</strong></div><div class="item"><label>Tanggal Pembayaran</label><strong>{{ $payment->payment_date?->format('d F Y') ?? '-' }}</strong></div><div class="item"><label>Service Order</label><strong>{{ $payment->serviceOrder?->order_number ?? '-' }}</strong></div><div class="item"><label>Invoice</label><strong>{{ $payment->invoice?->invoice_number ?? '-' }}</strong></div><div class="item"><label>Metode Pembayaran</label><strong>{{ ucwords(str_replace('_',' ',$payment->payment_method)) }}</strong></div><div class="item"><label>Nomor Referensi</label><strong>{{ $payment->reference_number ?: '-' }}</strong></div></section>
        <div class="amount-box"><span class="amount-label">TOTAL DITERIMA</span><span class="amount">Rp {{ number_format((float)$payment->amount,2,',','.') }}</span></div>
        <div class="notes"><label>Catatan</label>{{ $payment->notes ?: '-' }}</div>
        <footer class="footer"><div>Dicetak pada {{ now()->format('d F Y H:i') }}</div><div class="signature">Penerima Pembayaran<br><strong>{{ $payment->receiver?->name ?? 'Admin' }}</strong></div></footer>
    </main>
</body>
</html>
