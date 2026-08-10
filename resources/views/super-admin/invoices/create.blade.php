@extends('layouts.app')

@section('page-title', 'Buat Invoice')

@section('content')
     <style>
          .inv-create {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 55px;
               background: radial-gradient(circle at 5% 5%, rgba(99, 102, 241, .15), transparent 25%), radial-gradient(circle at 95% 8%, rgba(8, 145, 178, .13), transparent 25%), linear-gradient(145deg, #f8fbff, #f7f4ff 55%, #effcff)
          }

          .inv-wrap {
               max-width: 1450px;
               margin: auto
          }

          .inv-hero {
               padding: 31px 34px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background: linear-gradient(120deg, #4338ca, #7c3aed 52%, #0891b2);
               box-shadow: 0 22px 52px rgba(67, 56, 202, .2)
          }

          .inv-hero-row,
          .inv-heading {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px
          }

          .inv-heading {
               justify-content: flex-start
          }

          .inv-icon {
               display: inline-flex;
               flex: 0 0 66px;
               width: 66px;
               height: 66px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.75rem;
               border-radius: 21px;
               background: #fff
          }

          .inv-hero h1 {
               margin: 0;
               font-size: clamp(1.75rem, 3vw, 2.45rem);
               font-weight: 850
          }

          .inv-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .88)
          }

          .inv-back {
               display: inline-flex;
               min-height: 45px;
               padding: 10px 16px;
               gap: 8px;
               align-items: center;
               color: #fff;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .4);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
               font-weight: 800
          }

          .inv-layout {
               display: grid;
               grid-template-columns: minmax(0, 1fr) 350px;
               gap: 22px;
               align-items: start
          }

          .inv-card,
          .inv-side-card {
               padding: 25px;
               border: 1px solid #e2e8f0;
               border-radius: 23px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 16px 40px rgba(51, 65, 85, .075)
          }

          .inv-side {
               display: grid;
               gap: 18px;
               position: sticky;
               top: 20px
          }

          .inv-side-card {
               padding: 21px
          }

          .inv-title {
               display: flex;
               gap: 10px;
               align-items: center;
               padding-bottom: 14px;
               margin: 0 0 20px;
               color: #24324a;
               border-bottom: 1px solid #edf2f7;
               font-size: 1rem;
               font-weight: 850
          }

          .inv-title i {
               display: inline-flex;
               width: 33px;
               height: 33px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               border-radius: 10px;
               background: #e0e7ff;
               font-size: .85rem
          }

          .inv-label {
               display: flex;
               justify-content: space-between;
               margin-bottom: 7px;
               color: #475569;
               font-size: .76rem;
               font-weight: 850
          }

          .inv-control {
               min-height: 47px !important;
               padding: 10px 13px !important;
               border: 1px solid #dbe3ee !important;
               border-radius: 12px !important
          }

          .inv-control:focus {
               border-color: #818cf8 !important;
               box-shadow: 0 0 0 4px rgba(99, 102, 241, .12) !important
          }

          .inv-help {
               display: block;
               margin-top: 6px;
               color: #94a3b8;
               font-size: .72rem
          }

          .inv-hint {
               padding: 15px;
               border-radius: 14px;
               color: #4338ca;
               background: #eef2ff;
               font-size: .8rem;
               line-height: 1.55
          }

          .inv-preview {
               position: relative;
               overflow: hidden;
               padding: 21px;
               color: #fff;
               border-radius: 18px;
               background: linear-gradient(135deg, #4338ca, #7c3aed 55%, #0891b2)
          }

          .inv-preview:after {
               position: absolute;
               right: -35px;
               bottom: -45px;
               width: 140px;
               height: 140px;
               content: '';
               border: 20px solid rgba(255, 255, 255, .12);
               border-radius: 50%
          }

          .preview-label,
          .preview-number,
          .preview-total {
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

          .preview-number {
               min-height: 30px;
               margin: 7px 0 22px;
               font-size: 1.15rem;
               font-weight: 850
          }

          .preview-total {
               font-size: 1.65rem;
               font-weight: 900
          }

          .inv-stat {
               display: flex;
               justify-content: space-between;
               gap: 12px;
               padding: 13px 0;
               border-bottom: 1px solid #eef2f7;
               color: #64748b;
               font-size: .8rem
          }

          .inv-stat:last-child {
               border-bottom: 0
          }

          .inv-stat strong {
               color: #1e293b
          }

          .inv-actions {
               display: flex;
               justify-content: flex-end;
               gap: 10px;
               padding-top: 21px;
               margin-top: 24px;
               border-top: 1px solid #edf2f7
          }

          .inv-cancel,
          .inv-save {
               display: inline-flex;
               min-height: 47px;
               padding: 11px 19px;
               gap: 8px;
               align-items: center;
               border-radius: 12px;
               font-weight: 850
          }

          .inv-cancel {
               color: #475569;
               text-decoration: none;
               border: 1px solid #dbe3ee;
               background: #fff
          }

          .inv-save {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #4338ca, #0891b2);
               box-shadow: 0 9px 18px rgba(67, 56, 202, .18)
          }

          .inv-alert {
               padding: 15px 17px;
               margin-bottom: 20px;
               border: 1px solid #fecdd3;
               border-radius: 14px;
               color: #9f1239;
               background: #fff1f2
          }

          .inv-alert ul {
               margin: 6px 0 0;
               padding-left: 20px
          }

          @media(max-width:950px) {
               .inv-layout {
                    grid-template-columns: 1fr
               }

               .inv-side {
                    position: static;
                    grid-template-columns: repeat(2, 1fr)
               }
          }

          @media(max-width:650px) {
               .inv-hero-row {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .inv-layout,
               .inv-side {
                    grid-template-columns: 1fr
               }

               .inv-actions {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .inv-cancel,
               .inv-save {
                    justify-content: center
               }

               .inv-card {
                    padding: 18px
               }
          }
     </style>
     <div class="inv-create">
          <div class="inv-wrap">
               <header class="inv-hero">
                    <div class="inv-hero-row">
                         <div class="inv-heading"><span class="inv-icon"><i class="fas fa-file-circle-plus"></i></span>
                              <div>
                                   <h1>Buat Invoice</h1>
                                   <p>Terbitkan tagihan baru berdasarkan service order yang belum memiliki invoice.</p>
                              </div>
                         </div><a class="inv-back" href="{{ route('super-admin.invoices.index') }}"><i
                                   class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
               </header>
               <div class="inv-layout">
                    <main class="inv-card">
                         @if ($errors->any())
                              <div class="inv-alert"><strong><i class="fas fa-circle-exclamation me-1"></i> Data belum
                                        lengkap</strong>
                                   <ul>
                                        @foreach ($errors->all() as $error)
                                             <li>{{ $error }}</li>
                                        @endforeach
                                   </ul>
                              </div>
                         @endif
                         <form method="POST" action="{{ route('super-admin.invoices.store') }}">
                              @csrf<h2 class="inv-title"><i class="fas fa-file-invoice"></i> Informasi Tagihan</h2>
                              <div class="row g-3">
                                   <div class="col-md-8"><label class="inv-label" for="service_order_id">Service Order
                                             <span>*</span></label><select class="form-select inv-control"
                                             id="service_order_id" name="service_order_id" required>
                                             <option value="">Pilih service order</option>
                                             @foreach ($orders as $order)
                                                  <option value="{{ $order->id }}" @selected(old('service_order_id', $selectedOrder?->id) == $order->id)>
                                                       {{ $order->order_number }} —
                                                       {{ $order->customer?->display_name ?? $order->customer?->name }}
                                                  </option>
                                             @endforeach
                                        </select><small class="inv-help">Invoice hanya dapat dibuat satu kali untuk setiap
                                             service order.</small></div>
                                   <div class="col-md-4"><label class="inv-label" for="invoice_date">Tanggal Invoice
                                             <span>*</span></label><input class="form-control inv-control" type="date"
                                             id="invoice_date" name="invoice_date"
                                             value="{{ old('invoice_date', now()->toDateString()) }}" required></div>
                                   <div class="col-md-4"><label class="inv-label" for="due_date">Jatuh Tempo</label><input
                                             class="form-control inv-control" type="date" id="due_date" name="due_date"
                                             value="{{ old('due_date') }}"></div>
                                   <div class="col-md-8">
                                        <div class="inv-hint"><i
                                                  class="fas fa-wand-magic-sparkles me-1"></i><strong>Otomatis:</strong>
                                             nomor invoice, subtotal, dan total akan dihitung sistem dari service order.
                                        </div>
                                   </div>
                              </div>
                              <h2 class="inv-title mt-4"><i class="fas fa-calculator"></i> Penyesuaian Nilai</h2>
                              <div class="row g-3">
                                   <div class="col-md-6"><label class="inv-label" for="discount">Diskon Tambahan</label>
                                        <div class="input-group"><span class="input-group-text bg-white">Rp</span><input
                                                  class="form-control inv-control" type="number" step="0.01"
                                                  min="0" id="discount" name="discount"
                                                  value="{{ old('discount', 0) }}"></div>
                                   </div>
                                   <div class="col-md-6"><label class="inv-label" for="tax">Pajak</label>
                                        <div class="input-group"><span class="input-group-text bg-white">Rp</span><input
                                                  class="form-control inv-control" type="number" step="0.01"
                                                  min="0" id="tax" name="tax" value="{{ old('tax', 0) }}">
                                        </div>
                                   </div>
                                   <div class="col-12"><label class="inv-label" for="notes">Catatan <span
                                                  class="text-muted">Opsional</span></label>
                                        <textarea class="form-control inv-control" id="notes" name="notes" rows="4"
                                             placeholder="Tambahkan catatan invoice...">{{ old('notes') }}</textarea>
                                   </div>
                              </div>
                              <div class="inv-actions"><a class="inv-cancel"
                                        href="{{ route('super-admin.invoices.index') }}">Batal</a><button class="inv-save"
                                        type="submit"><i class="fas fa-check"></i> Simpan Invoice</button></div>
                         </form>
                    </main>
                    <aside class="inv-side">
                         <div class="inv-side-card">
                              <h3 class="inv-title"><i class="fas fa-eye"></i> Pratinjau Invoice</h3>
                              <div class="inv-preview"><span class="preview-label">Invoice baru</span>
                                   <div class="preview-number">INV-otomatis</div><span
                                        class="preview-label">Penyesuaian</span>
                                   <div class="preview-total" id="totalPreview">Rp 0,00</div>
                              </div>
                              <div class="inv-stat"><span>Service Order</span><strong id="orderPreview">Belum
                                        dipilih</strong></div>
                              <div class="inv-stat"><span>Tanggal</span><strong
                                        id="datePreview">{{ now()->format('d/m/Y') }}</strong></div>
                              <div class="inv-stat"><span>Jatuh tempo</span><strong id="duePreview">-</strong></div>
                         </div>
                         <div class="inv-side-card">
                              <h3 class="inv-title"><i class="fas fa-shield-halved"></i> Informasi</h3>
                              <div class="inv-stat"><span>Status awal</span><strong>Unpaid</strong></div>
                              <div class="inv-stat"><span>Nomor invoice</span><strong>Otomatis</strong></div>
                              <p class="text-muted small mb-0 mt-3">Pastikan service order sudah memiliki total transaksi
                                   yang benar sebelum menerbitkan invoice.</p>
                         </div>
                    </aside>
               </div>
          </div>
     </div>
@endsection

@push('script')
     <script>
          (function() {
               const order = document.getElementById('service_order_id'),
                    date = document.getElementById('invoice_date'),
                    due = document.getElementById('due_date'),
                    discount = document.getElementById('discount'),
                    tax = document.getElementById('tax'),
                    total = document.getElementById('totalPreview'),
                    orderPreview = document.getElementById('orderPreview'),
                    datePreview = document.getElementById('datePreview'),
                    duePreview = document.getElementById('duePreview');
               const money = v => new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
               }).format(Number(v || 0));
               const refresh = () => {
                    total.textContent = money(Number(discount.value || 0) + Number(tax.value || 0));
                    orderPreview.textContent = order.options[order.selectedIndex]?.text?.split(' — ')[0] ||
                         'Belum dipilih';
                    datePreview.textContent = date.value ? date.value.split('-').reverse().join('/') : '-';
                    duePreview.textContent = due.value ? due.value.split('-').reverse().join('/') : '-';
               };
               [order, date, due, discount, tax].forEach(el => el.addEventListener('input', refresh));
               refresh();
          }());
     </script>
@endpush
