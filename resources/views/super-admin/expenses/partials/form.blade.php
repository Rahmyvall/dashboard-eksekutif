@php
     $orderId = old('service_order_id', $expense->service_order_id ?? ($selectedOrder->id ?? null));
@endphp

<style>
     /* ── Form card ── */
     .xpf-card {
          border: 1px solid #d7e2ef;
          border-radius: 22px;
          background: #fff;
          box-shadow: 0 8px 32px rgba(15, 23, 42, 0.06);
          overflow: hidden;
     }

     .xpf-card-header {
          display: flex;
          align-items: center;
          gap: 12px;
          padding: 20px 26px;
          border-bottom: 1px solid #e8f0f8;
          background: linear-gradient(to right, #f8fafc, #f0f7ff);
     }

     .xpf-card-header-icon {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 40px;
          height: 40px;
          border-radius: 12px;
          background: linear-gradient(135deg, #0f766e, #0369a1);
          color: #fff;
          font-size: 17px;
          flex-shrink: 0;
     }

     .xpf-card-header h5 {
          margin: 0;
          font-weight: 800;
          font-size: 0.97rem;
          color: #10233f;
          letter-spacing: -0.01em;
     }

     .xpf-card-header span {
          font-size: 0.76rem;
          color: #617692;
          display: block;
          margin-top: 2px;
     }

     .xpf-card-body {
          padding: 26px;
     }

     /* ── Section label ── */
     .xpf-section-label {
          display: flex;
          align-items: center;
          gap: 8px;
          margin-bottom: 14px;
          font-size: 0.70rem;
          font-weight: 800;
          letter-spacing: 0.10em;
          text-transform: uppercase;
          color: #617692;
     }

     .xpf-section-label::after {
          content: '';
          flex: 1;
          height: 1px;
          background: #e2eaf4;
     }

     /* ── Form labels ── */
     .xpf-card .form-label {
          display: flex;
          align-items: center;
          gap: 6px;
          font-size: 0.73rem;
          letter-spacing: 0.07em;
          text-transform: uppercase;
          color: #617692;
          font-weight: 700;
          margin-bottom: 7px;
     }

     .xpf-card .form-label .bi {
          font-size: 13px;
          color: #0ea5e9;
     }

     /* ── Inputs ── */
     .xpf-card .form-control,
     .xpf-card .form-select {
          min-height: 46px;
          border-radius: 12px;
          border: 1.5px solid #d4deea;
          font-size: 0.92rem;
          font-weight: 500;
          color: #10233f;
          background: #fafcff;
          transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
     }

     .xpf-card .form-control:focus,
     .xpf-card .form-select:focus {
          border-color: #38bdf8;
          background: #fff;
          box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.14);
          outline: none;
     }

     .xpf-card .form-control.is-invalid,
     .xpf-card .form-select.is-invalid {
          border-color: #f87171;
          background: #fff5f5;
     }

     .xpf-card .form-control.is-invalid:focus,
     .xpf-card .form-select.is-invalid:focus {
          box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.16);
     }

     .xpf-card textarea.form-control {
          min-height: 114px;
          resize: vertical;
          line-height: 1.65;
     }

     .xpf-card .invalid-feedback {
          font-size: 0.78rem;
          font-weight: 600;
          margin-top: 5px;
     }

     /* ── File input ── */
     .xpf-file-wrap {
          position: relative;
     }

     .xpf-card input[type="file"].form-control {
          padding: 10px 14px;
          cursor: pointer;
     }

     .xpf-file-hint {
          margin-top: 6px;
          font-size: 0.73rem;
          color: #94a3b8;
          font-weight: 500;
     }

     /* ── Current attachment ── */
     .xpf-attachment-box {
          display: flex;
          align-items: center;
          gap: 12px;
          padding: 13px 16px;
          border-radius: 12px;
          background: #eff6ff;
          border: 1.5px solid #bfdbfe;
     }

     .xpf-attachment-box .xpf-attach-icon {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 36px;
          height: 36px;
          border-radius: 10px;
          background: #dbeafe;
          color: #1d4ed8;
          font-size: 16px;
          flex-shrink: 0;
     }

     .xpf-attachment-box .xpf-attach-meta {
          flex: 1;
          font-size: 0.80rem;
          font-weight: 700;
          color: #1e3a5f;
     }

     .xpf-attachment-box .xpf-attach-meta span {
          display: block;
          font-size: 0.72rem;
          font-weight: 500;
          color: #617692;
          margin-top: 2px;
     }

     .xpf-attach-link {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: 7px 14px;
          border-radius: 9px;
          font-size: 0.78rem;
          font-weight: 700;
          text-decoration: none;
          border: 1.5px solid #93c5fd;
          background: #fff;
          color: #1d4ed8;
          transition: background 0.16s, box-shadow 0.16s;
          flex-shrink: 0;
     }

     .xpf-attach-link:hover {
          background: #dbeafe;
          box-shadow: 0 4px 10px rgba(29, 78, 216, 0.12);
          text-decoration: none;
          color: #1d4ed8;
     }

     /* ── Checkbox ── */
     .xpf-check-label {
          font-size: 0.82rem;
          font-weight: 600;
          color: #ef4444;
          cursor: pointer;
     }

     .xpf-check-label input {
          accent-color: #ef4444;
          margin-right: 6px;
     }

     /* ── Divider ── */
     .xpf-divider {
          border: none;
          border-top: 1px solid #e2eaf4;
          margin: 22px 0;
     }

     /* ── Action buttons ── */
     .xpf-actions {
          display: flex;
          align-items: center;
          gap: 10px;
          flex-wrap: wrap;
          margin-top: 22px;
     }

     .xpf-submit-btn {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 12px 28px;
          border-radius: 13px;
          font-size: 0.88rem;
          font-weight: 800;
          border: none;
          cursor: pointer;
          text-decoration: none;
          transition: transform 0.18s, box-shadow 0.18s;
          background: linear-gradient(135deg, #0f766e, #0369a1);
          color: #fff;
          box-shadow: 0 6px 18px rgba(3, 105, 161, 0.26);
     }

     .xpf-submit-btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 10px 26px rgba(3, 105, 161, 0.34);
          color: #fff;
     }

     .xpf-submit-btn .bi {
          font-size: 16px;
     }

     .xpf-cancel-btn {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 12px 22px;
          border-radius: 13px;
          font-size: 0.88rem;
          font-weight: 700;
          text-decoration: none;
          border: 1.5px solid #d7e2ef;
          background: #fff;
          color: #617692;
          transition: border-color 0.18s, background 0.18s, transform 0.18s;
     }

     .xpf-cancel-btn:hover {
          border-color: #94a3b8;
          background: #f1f5f9;
          color: #334155;
          transform: translateY(-1px);
          text-decoration: none;
     }

     .xpf-cancel-btn .bi,
     .xpf-submit-btn .bi {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          width: 16px;
          height: 16px;
          font-size: 16px;
     }
</style>

<div class="xpf-card">
     <div class="xpf-card-header">
          <div class="xpf-card-header-icon">
               <i class="bi bi-pencil-square"></i>
          </div>
          <div>
               <h5>Form Pengeluaran</h5>
               <span>Lengkapi informasi biaya agar dashboard monitoring tetap akurat dan mudah dianalisis</span>
          </div>
     </div>

     <div class="xpf-card-body">

          {{-- Section: Transaksi --}}
          <div class="xpf-section-label">
               <i class="bi bi-receipt" style="color:#0ea5e9;font-size:13px;"></i>
               Informasi Transaksi
          </div>

          <div class="row g-3 mb-2">
               <div class="col-md-6">
                    <label for="service_order_id" class="form-label">
                         <i class="bi bi-receipt"></i>
                         Service Order <span
                              style="font-weight:500;text-transform:none;letter-spacing:0;color:#94a3b8;">(Opsional)</span>
                    </label>
                    <select id="service_order_id" name="service_order_id"
                         class="form-select @error('service_order_id') is-invalid @enderror">
                         <option value="">Tanpa service order</option>
                         @foreach ($orders as $order)
                              <option value="{{ $order->id }}" @selected((string) $orderId === (string) $order->id)>
                                   {{ $order->order_number }}
                                   @if ($order->relationLoaded('customer') && $order->customer)
                                        — {{ $order->customer->name }}
                                   @endif
                              </option>
                         @endforeach
                    </select>
                    @error('service_order_id')
                         <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-3">
                    <label for="expense_date" class="form-label">
                         <i class="bi bi-calendar-event"></i>
                         Tanggal Pengeluaran
                    </label>
                    <input type="date" id="expense_date" name="expense_date"
                         class="form-control @error('expense_date') is-invalid @enderror"
                         value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}" required>
                    @error('expense_date')
                         <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-3">
                    <label for="amount" class="form-label">
                         <i class="bi bi-cash-coin"></i>
                         Nominal
                    </label>
                    <input type="number" step="0.01" min="0.01" id="amount" name="amount"
                         class="form-control @error('amount') is-invalid @enderror"
                         value="{{ old('amount', $expense->amount) }}" required>
                    @error('amount')
                         <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>
          </div>

          <hr class="xpf-divider">

          {{-- Section: Detail --}}
          <div class="xpf-section-label">
               <i class="bi bi-file-text" style="color:#0ea5e9;font-size:13px;"></i>
               Detail Pengeluaran
          </div>

          <div class="row g-3 mb-2">
               <div class="col-md-4">
                    <label for="category" class="form-label">
                         <i class="bi bi-tag"></i>
                         Kategori
                    </label>
                    <input type="text" id="category" name="category"
                         class="form-control @error('category') is-invalid @enderror"
                         value="{{ old('category', $expense->category) }}" maxlength="100" required
                         placeholder="cth: Operasional, Bahan Baku…">
                    @error('category')
                         <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-8">
                    <label for="description" class="form-label">
                         <i class="bi bi-chat-text"></i>
                         Deskripsi
                    </label>
                    <textarea id="description" name="description" rows="3"
                         class="form-control @error('description') is-invalid @enderror" required
                         placeholder="Deskripsikan pengeluaran ini…">{{ old('description', $expense->description) }}</textarea>
                    @error('description')
                         <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>
          </div>

          <hr class="xpf-divider">

          {{-- Section: Lampiran --}}
          <div class="xpf-section-label">
               <i class="bi bi-paperclip" style="color:#0ea5e9;font-size:13px;"></i>
               Lampiran
          </div>

          <div class="row g-3">
               <div class="col-md-6">
                    <label for="attachment" class="form-label">
                         <i class="bi bi-cloud-arrow-up"></i>
                         Upload Lampiran
                    </label>
                    <div class="xpf-file-wrap">
                         <input type="file" id="attachment" name="attachment"
                              class="form-control @error('attachment') is-invalid @enderror"
                              accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="xpf-file-hint">
                         <i class="bi bi-info-circle" style="margin-right:4px;"></i>
                         Format: JPG, PNG, PDF — Maks. 5 MB
                    </div>
                    @error('attachment')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               @if (!empty($expense->attachment_path))
                    <div class="col-md-6">
                         <label class="form-label">
                              <i class="bi bi-paperclip"></i>
                              Lampiran Saat Ini
                         </label>
                         <div class="xpf-attachment-box">
                              <div class="xpf-attach-icon">
                                   <i class="bi bi-file-earmark-text"></i>
                              </div>
                              <div class="xpf-attach-meta">
                                   Lampiran Tersedia
                                   <span>Klik tombol untuk melihat file</span>
                              </div>
                              <a href="{{ $expense->attachment_url }}" target="_blank" class="xpf-attach-link"
                                   rel="noopener noreferrer" aria-label="Lihat Lampiran">
                                   <i class="bi bi-box-arrow-up-right"></i>
                                   Lihat
                              </a>
                         </div>

                         @if (request()->routeIs('super-admin.expenses.edit'))
                              <div class="mt-2">
                                   <label class="xpf-check-label">
                                        <input class="form-check-input" type="checkbox" id="remove_attachment"
                                             name="remove_attachment" value="1">
                                        Hapus lampiran saat ini
                                   </label>
                              </div>
                         @endif
                    </div>
               @endif
          </div>

     </div>
</div>

<div class="xpf-actions">
     <button type="submit" class="xpf-submit-btn">
          <i class="bi bi-check2-circle"></i>
          Simpan Perubahan
     </button>
     <a href="{{ route('super-admin.expenses.index') }}" class="xpf-cancel-btn">
          <i class="bi bi-x-lg"></i>
          Batal
     </a>
</div>
