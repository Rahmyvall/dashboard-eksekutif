@extends('layouts.app')

@section('page-title', 'Catat Pembayaran')

@section('content')
     <style>
          :root {
               --pay: #0f766e;
               --pay-dark: #115e59;
               --pay-cyan: #0891b2;
               --ink: #172033;
               --muted: #64748b;
               --line: #e2e8f0
          }

          .pay-create {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 55px;
               background: radial-gradient(circle at 4% 5%, rgba(20, 184, 166, .14), transparent 24%), radial-gradient(circle at 96% 8%, rgba(99, 102, 241, .14), transparent 26%), linear-gradient(145deg, #f8fbff, #f7f4ff 55%, #effcff)
          }

          .pay-create-wrap {
               max-width: 1450px;
               margin: auto
          }

          .pay-hero {
               position: relative;
               overflow: hidden;
               padding: 30px 34px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background: radial-gradient(circle at 85% 20%, rgba(255, 255, 255, .24), transparent 24%), linear-gradient(120deg, #0f766e, #0891b2 55%, #4f46e5);
               box-shadow: 0 22px 52px rgba(15, 118, 110, .2)
          }

          .pay-hero:after {
               position: absolute;
               right: 7%;
               bottom: -145px;
               width: 280px;
               height: 280px;
               content: '';
               border: 32px solid rgba(255, 255, 255, .1);
               border-radius: 50%;
               box-shadow: 0 0 0 25px rgba(255, 255, 255, .05)
          }

          .pay-hero-row {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 20px
          }

          .pay-heading {
               display: flex;
               align-items: center;
               gap: 17px
          }

          .pay-icon {
               display: inline-flex;
               flex: 0 0 66px;
               width: 66px;
               height: 66px;
               align-items: center;
               justify-content: center;
               color: var(--pay);
               font-size: 1.75rem;
               border-radius: 21px;
               background: #fff;
               box-shadow: 0 12px 24px rgba(15, 118, 110, .18)
          }

          .pay-hero h1 {
               margin: 0;
               font-size: clamp(1.75rem, 3vw, 2.45rem);
               font-weight: 850;
               letter-spacing: -.045em
          }

          .pay-hero p {
               max-width: 650px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .9);
               line-height: 1.6
          }

          .pay-back {
               display: inline-flex;
               position: relative;
               z-index: 1;
               min-height: 45px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #fff;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .38);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
               font-weight: 800
          }

          .pay-back:hover {
               color: #fff;
               background: rgba(255, 255, 255, .22)
          }

          .pay-layout {
               display: grid;
               grid-template-columns: minmax(0, 1fr) 345px;
               gap: 22px;
               align-items: start
          }

          .pay-card {
               padding: 25px;
               border: 1px solid rgba(226, 232, 240, .95);
               border-radius: 23px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .075)
          }

          .pay-side {
               display: grid;
               gap: 18px;
               position: sticky;
               top: 20px
          }

          .pay-side-card {
               padding: 21px;
               border: 1px solid rgba(226, 232, 240, .9);
               border-radius: 21px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 14px 34px rgba(51, 65, 85, .07)
          }

          .pay-side-title {
               display: flex;
               gap: 9px;
               align-items: center;
               margin: 0 0 17px;
               color: #24324a;
               font-size: .94rem;
               font-weight: 850
          }

          .pay-side-title i {
               color: var(--pay)
          }

          .pay-preview {
               position: relative;
               overflow: hidden;
               padding: 20px;
               color: #fff;
               border-radius: 18px;
               background: linear-gradient(135deg, #0f766e, #0891b2 58%, #4f46e5)
          }

          .pay-preview:after {
               position: absolute;
               right: -35px;
               bottom: -45px;
               width: 135px;
               height: 135px;
               content: '';
               border: 19px solid rgba(255, 255, 255, .12);
               border-radius: 50%
          }

          .preview-label,
          .preview-value,
          .preview-meta {
               position: relative;
               z-index: 1
          }

          .preview-label {
               font-size: .68rem;
               font-weight: 800;
               letter-spacing: .1em;
               text-transform: uppercase;
               opacity: .78
          }

          .preview-value {
               min-height: 35px;
               margin: 8px 0 16px;
               font-size: 1.45rem;
               font-weight: 900
          }

          .preview-meta {
               display: flex;
               justify-content: space-between;
               gap: 12px;
               padding-top: 14px;
               border-top: 1px solid rgba(255, 255, 255, .24);
               font-size: .78rem
          }

          .preview-status {
               padding: 5px 9px;
               border-radius: 999px;
               background: rgba(255, 255, 255, .16);
               font-weight: 800
          }

          .pay-tips {
               display: grid;
               gap: 12px;
               margin: 0;
               padding: 0;
               list-style: none
          }

          .pay-tips li {
               display: flex;
               gap: 10px;
               color: #64748b;
               font-size: .8rem;
               line-height: 1.5
          }

          .pay-tips i {
               margin-top: 3px;
               color: #0d9488
          }

          .pay-stat {
               display: flex;
               justify-content: space-between;
               gap: 12px;
               padding: 12px 0;
               border-bottom: 1px solid #eef2f7;
               font-size: .82rem
          }

          .pay-stat:last-child {
               border-bottom: 0
          }

          .pay-stat strong {
               color: #1e293b
          }

          .pay-section {
               padding-bottom: 14px;
               margin: 0 0 20px;
               border-bottom: 1px solid #edf2f7
          }

          .pay-section-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px
          }

          .pay-section-title {
               display: flex;
               gap: 10px;
               align-items: center;
               margin: 0;
               color: #24324a;
               font-size: 1rem;
               font-weight: 850
          }

          .pay-section-title i {
               display: inline-flex;
               width: 32px;
               height: 32px;
               align-items: center;
               justify-content: center;
               color: var(--pay);
               border-radius: 10px;
               background: #ccfbf1;
               font-size: .85rem
          }

          .required-note {
               color: #94a3b8;
               font-size: .74rem
          }

          .pay-label {
               display: flex;
               justify-content: space-between;
               gap: 8px;
               margin-bottom: 7px;
               color: #475569;
               font-size: .76rem;
               font-weight: 850
          }

          .pay-control {
               min-height: 47px !important;
               padding: 10px 13px !important;
               border: 1px solid #dbe3ee !important;
               border-radius: 12px !important;
               box-shadow: none !important;
               transition: .2s
          }

          .pay-control:focus {
               border-color: #2dd4bf !important;
               box-shadow: 0 0 0 4px rgba(20, 184, 166, .12) !important
          }

          .pay-help {
               display: block;
               margin-top: 6px;
               color: #94a3b8;
               font-size: .72rem
          }

          .pay-upload {
               position: relative;
               display: flex;
               min-height: 125px;
               align-items: center;
               justify-content: center;
               padding: 17px;
               text-align: center;
               border: 1.5px dashed #99f6e4;
               border-radius: 15px;
               background: #f0fdfa;
               cursor: pointer;
               transition: .2s
          }

          .pay-upload:hover {
               border-color: var(--pay);
               background: #ccfbf1
          }

          .pay-upload input {
               position: absolute;
               width: 100%;
               height: 100%;
               opacity: 0;
               cursor: pointer
          }

          .upload-icon {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               margin-bottom: 8px;
               color: var(--pay);
               border-radius: 13px;
               background: #ccfbf1;
               font-size: 1.1rem
          }

          .upload-title {
               display: block;
               color: #115e59;
               font-weight: 850
          }

          .upload-subtitle {
               display: block;
               margin-top: 3px;
               color: #64748b;
               font-size: .72rem
          }

          .pay-alert {
               padding: 15px 17px;
               border: 1px solid #fecdd3;
               border-radius: 14px;
               background: #fff1f2;
               color: #9f1239
          }

          .pay-alert ul {
               margin: 0;
               padding-left: 20px
          }

          .pay-actions {
               display: flex;
               justify-content: flex-end;
               gap: 10px;
               padding-top: 20px;
               margin-top: 22px;
               border-top: 1px solid #edf2f7
          }

          .pay-cancel {
               display: inline-flex;
               min-height: 47px;
               padding: 11px 18px;
               align-items: center;
               color: #475569;
               text-decoration: none;
               border: 1px solid #dbe3ee;
               border-radius: 12px;
               background: #fff;
               font-weight: 800
          }

          .pay-submit {
               display: inline-flex;
               min-height: 47px;
               padding: 11px 19px;
               gap: 8px;
               align-items: center;
               color: #fff;
               border: 0;
               border-radius: 12px;
               background: linear-gradient(135deg, #0f766e, #0891b2);
               box-shadow: 0 9px 18px rgba(15, 118, 110, .18);
               font-weight: 850;
               transition: .2s
          }

          .pay-submit:hover {
               transform: translateY(-2px);
               box-shadow: 0 12px 22px rgba(15, 118, 110, .25)
          }

          @media(max-width:950px) {
               .pay-layout {
                    grid-template-columns: 1fr
               }

               .pay-side {
                    position: static;
                    grid-template-columns: repeat(2, 1fr)
               }
          }

          @media(max-width:650px) {

               .pay-hero-row,
               .pay-section-row {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .pay-back {
                    justify-content: center
               }

               .pay-side {
                    grid-template-columns: 1fr
               }

               .pay-actions {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .pay-cancel,
               .pay-submit {
                    justify-content: center
               }

               .pay-card {
                    padding: 18px
               }
          }
     </style>

     <div class="pay-create">
          <div class="pay-create-wrap">
               <header class="pay-hero">
                    <div class="pay-hero-row">
                         <div class="pay-heading"><span class="pay-icon"><i class="fas fa-money-bill-transfer"></i></span>
                              <div>
                                   <h1>Catat Pembayaran</h1>
                                   <p>Dokumentasikan penerimaan pembayaran dan hubungkan dengan service order atau invoice
                                        secara aman.</p>
                              </div>
                         </div><a class="pay-back" href="{{ route('super-admin.payments.index') }}"><i
                                   class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
                    </div>
               </header>

               <div class="pay-layout">
                    <main class="pay-card">
                         @if ($errors->any())
                              <div class="pay-alert mb-4"><strong><i class="fas fa-circle-exclamation me-1"></i> Periksa
                                        kembali data berikut:</strong>
                                   <ul class="mt-2">
                                        @foreach ($errors->all() as $error)
                                             <li>{{ $error }}</li>
                                        @endforeach
                                   </ul>
                              </div>
                         @endif
                         <form method="POST" action="{{ route('super-admin.payments.store') }}"
                              enctype="multipart/form-data" id="paymentCreateForm">@csrf
                              <section class="pay-section">
                                   <div class="pay-section-row">
                                        <h2 class="pay-section-title"><i class="fas fa-link"></i> Relasi Transaksi</h2><span
                                             class="required-note">* Wajib diisi</span>
                                   </div>
                              </section>
                              <div class="row g-3 mb-4">
                                   <div class="col-md-6"><label class="pay-label" for="service_order_id">Service Order
                                             <span>*</span></label><select class="form-select pay-control"
                                             id="service_order_id" name="service_order_id" required>
                                             <option value="">Pilih service order</option>
                                             @foreach ($orders as $order)
                                                  <option value="{{ $order->id }}" @selected(old('service_order_id', $selectedOrder?->id) == $order->id)>
                                                       {{ $order->order_number }} —
                                                       {{ $order->customer?->display_name ?? $order->customer?->name }}
                                                  </option>
                                             @endforeach
                                        </select>
                                        <small class="pay-help">Pilih transaksi layanan yang menerima pembayaran.</small>
                                   </div>
                                   <div class="col-md-6"><label class="pay-label" for="invoice_id">Invoice <span
                                                  class="required-note">Opsional</span></label><select
                                             class="form-select pay-control" id="invoice_id" name="invoice_id">
                                             <option value="">Tanpa invoice</option>
                                             @foreach ($invoices as $invoice)
                                                  <option value="{{ $invoice->id }}"
                                                       data-order-id="{{ $invoice->service_order_id }}"
                                                       @selected(old('invoice_id') == $invoice->id)>
                                                       {{ $invoice->invoice_number }} —
                                                       {{ $invoice->serviceOrder?->order_number }}</option>
                                             @endforeach
                                        </select>
                                        <small class="pay-help">Hubungkan pembayaran ke invoice jika tersedia.</small>
                                   </div>
                              </div>

                              <section class="pay-section">
                                   <h2 class="pay-section-title"><i class="fas fa-receipt"></i> Detail Pembayaran</h2>
                              </section>
                              <div class="row g-3 mb-4">
                                   <div class="col-md-4"><label class="pay-label" for="payment_date">Tanggal Pembayaran
                                             <span>*</span></label><input class="form-control pay-control" type="date"
                                             id="payment_date" name="payment_date"
                                             value="{{ old('payment_date', now()->toDateString()) }}" required></div>
                                   <div class="col-md-4"><label class="pay-label" for="payment_method">Metode Pembayaran
                                             <span>*</span></label><select class="form-select pay-control"
                                             id="payment_method" name="payment_method" required>
                                             @foreach ($methods as $method)
                                                  <option value="{{ $method }}" @selected(old('payment_method', $methods[0] ?? 'cash') === $method)>
                                                       {{ ucwords(str_replace('_', ' ', $method)) }}</option>
                                             @endforeach
                                        </select>
                                   </div>
                                   <div class="col-md-4"><label class="pay-label" for="amount">Jumlah Pembayaran
                                             <span>*</span></label>
                                        <div class="input-group"><span
                                                  class="input-group-text bg-white border-end-0">Rp</span><input
                                                  class="form-control pay-control border-start-0" type="number"
                                                  step="0.01" min="0.01" id="amount" name="amount"
                                                  value="{{ old('amount') }}" placeholder="0,00" required></div>
                                   </div>
                                   <div class="col-md-6"><label class="pay-label" for="reference_preview">Nomor
                                             Referensi</label><input class="form-control pay-control" id="reference_preview"
                                             value="Dibuat otomatis saat disimpan" readonly><small class="pay-help">Nomor
                                             referensi dibuat otomatis oleh sistem.</small></div>
                                   <div class="col-md-6"><label class="pay-label" for="status">Status Pembayaran
                                             <span>*</span></label><select class="form-select pay-control" id="status"
                                             name="status" required>
                                             @foreach ($statuses as $status)
                                                  <option value="{{ $status }}" @selected(old('status', 'confirmed') === $status)>
                                                       {{ ucfirst($status) }}</option>
                                             @endforeach
                                        </select></div>
                              </div>

                              <section class="pay-section">
                                   <h2 class="pay-section-title"><i class="fas fa-paperclip"></i> Bukti & Catatan</h2>
                              </section>
                              <div class="row g-3">
                                   <div class="col-md-6"><label class="pay-label" for="proof_of_payment">Bukti Pembayaran
                                             <span class="required-note">Opsional</span></label><label class="pay-upload"
                                             for="proof_of_payment"><input type="file" id="proof_of_payment"
                                                  name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"><span><span
                                                       class="upload-icon"><i
                                                            class="fas fa-cloud-arrow-up"></i></span><span
                                                       class="upload-title" id="uploadTitle">Unggah bukti
                                                       pembayaran</span><span class="upload-subtitle"
                                                       id="uploadSubtitle">JPG, PNG, atau PDF · Maksimal 5
                                                       MB</span></span></label></div>
                                   <div class="col-md-6"><label class="pay-label" for="notes">Catatan <span
                                                  class="required-note">Opsional</span></label>
                                        <textarea class="form-control pay-control" id="notes" name="notes" rows="5"
                                             placeholder="Tambahkan catatan pembayaran...">{{ old('notes') }}</textarea>
                                   </div>
                              </div>
                              <div class="pay-actions"><a class="pay-cancel"
                                        href="{{ route('super-admin.payments.index') }}">Batal</a><button
                                        class="pay-submit" type="submit"><i class="fas fa-check"></i> Simpan
                                        Pembayaran</button></div>
                         </form>
                    </main>

                    <aside class="pay-side">
                         <div class="pay-side-card">
                              <h3 class="pay-side-title"><i class="fas fa-eye"></i> Pratinjau Pembayaran</h3>
                              <div class="pay-preview"><span class="preview-label">Total diterima</span>
                                   <div class="preview-value" id="amountPreview">Rp 0,00</div>
                                   <div class="preview-meta"><span id="methodPreview">Cash</span><span
                                             class="preview-status" id="statusPreview">Confirmed</span></div>
                              </div>
                              <div class="pay-stat"><span>Service Order</span><strong id="orderPreview">Belum
                                        dipilih</strong></div>
                              <div class="pay-stat"><span>Tanggal</span><strong
                                        id="datePreview">{{ now()->format('d/m/Y') }}</strong></div>
                              <div class="pay-stat"><span>Referensi</span><strong id="referencePreview">-</strong></div>
                         </div>
                         <div class="pay-side-card">
                              <h3 class="pay-side-title"><i class="fas fa-shield-halved"></i> Panduan Pengisian</h3>
                              <ul class="pay-tips">
                                   <li><i class="fas fa-check-circle"></i><span>Pastikan service order sesuai dengan
                                             transaksi pelanggan.</span></li>
                                   <li><i class="fas fa-check-circle"></i><span>Nominal pembayaran tidak boleh melebihi sisa
                                             tagihan.</span></li>
                                   <li><i class="fas fa-check-circle"></i><span>Gunakan status <strong>Confirmed</strong>
                                             setelah pembayaran diterima.</span></li>
                                   <li><i class="fas fa-check-circle"></i><span>Simpan bukti pembayaran untuk kebutuhan
                                             audit.</span></li>
                              </ul>
                         </div>
                    </aside>
               </div>
          </div>
     </div>
@endsection

@push('script')
     <script>
          (function() {
               const amount = document.getElementById('amount'),
                    amountPreview = document.getElementById('amountPreview'),
                    method = document.getElementById('payment_method'),
                    methodPreview = document.getElementById('methodPreview'),
                    status = document.getElementById('status'),
                    statusPreview = document.getElementById('statusPreview'),
                    order = document.getElementById('service_order_id'),
                    invoice = document.getElementById('invoice_id'),
                    orderPreview = document.getElementById('orderPreview'),
                    date = document.getElementById('payment_date'),
                    datePreview = document.getElementById('datePreview'),
                    referencePreview = document.getElementById('referencePreview'),
                    file = document.getElementById('proof_of_payment'),
                    uploadTitle = document.getElementById('uploadTitle'),
                    uploadSubtitle = document.getElementById('uploadSubtitle');
               const money = value => new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
               }).format(Number(value || 0));
               const refresh = () => {
                    amountPreview.textContent = money(amount.value);
                    methodPreview.textContent = method.options[method.selectedIndex]?.text || '-';
                    statusPreview.textContent = status.options[status.selectedIndex]?.text || '-';
                    orderPreview.textContent = order.options[order.selectedIndex]?.text?.split(' — ')[0] ||
                         'Belum dipilih';
                    datePreview.textContent = date.value ? date.value.split('-').reverse().join('/') : '-';
                    referencePreview.textContent = 'Otomatis';
               };
               const filterInvoices = () => {
                    const orderId = order.value;
                    let selectedIsVisible = invoice.value === '';

                    Array.from(invoice.options).forEach(option => {
                         if (!option.value) {
                              option.hidden = false;
                              return;
                         }

                         const visible = !orderId || option.dataset.orderId === orderId;
                         option.hidden = !visible;
                         if (option.value === invoice.value && visible) {
                              selectedIsVisible = true;
                         }
                    });

                    if (!selectedIsVisible) {
                         invoice.value = '';
                    }
               };
               [amount, method, status, order, date].forEach(el => el.addEventListener('input', refresh));
               order.addEventListener('change', filterInvoices);
               file.addEventListener('change', () => {
                    const selected = file.files[0];
                    if (selected) {
                         uploadTitle.textContent = selected.name;
                         uploadSubtitle.textContent = (selected.size / 1024 / 1024).toFixed(2) +
                              ' MB · File siap diunggah';
                    }
               });
               filterInvoices();
               refresh();
          })();
     </script>
@endpush
