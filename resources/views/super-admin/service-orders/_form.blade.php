@csrf

<style>
     .so-fieldset {
          border: 1px solid #e2e8f0;
          border-radius: 14px;
          padding: 16px;
          margin-bottom: 14px;
          background: #fff;
     }

     .so-fieldset-title {
          display: flex;
          align-items: center;
          gap: 8px;
          margin-bottom: 12px;
          color: #0f172a;
          font-weight: 800;
     }

     .so-label {
          display: flex;
          align-items: center;
          gap: 6px;
          font-size: .83rem;
          font-weight: 700;
          color: #334155;
     }

     .so-input {
          border-radius: 10px;
          border-color: #dbe3ee;
          min-height: 42px;
     }
</style>

<div class="so-fieldset">
     <div class="so-fieldset-title"><i class="bi bi-journal-text"></i>Informasi Pesanan</div>
     <div class="row g-3">
          <div class="col-md-6">
               <label class="so-label"><i class="bi bi-people"></i>Customer</label>
               <select name="customer_id" class="form-control so-input" required>
                    <option value="">Pilih Customer</option>
                    @foreach ($customers as $customer)
                         <option value="{{ $customer->id }}" @selected((string) old('customer_id', $serviceOrder->customer_id ?? '') === (string) $customer->id)>{{ $customer->name }}</option>
                    @endforeach
               </select>
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-calendar-event"></i>Tanggal Order</label>
               <input type="date" name="order_date" class="form-control so-input"
                    value="{{ old('order_date', isset($serviceOrder) ? optional($serviceOrder->order_date)->format('Y-m-d') : now()->toDateString()) }}"
                    required>
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-calendar2-week"></i>Tanggal Jadwal</label>
               <input type="date" name="scheduled_date" class="form-control so-input"
                    value="{{ old('scheduled_date', isset($serviceOrder) ? optional($serviceOrder->scheduled_date)->format('Y-m-d') : '') }}">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-percent"></i>Diskon</label>
               <input type="number" min="0" step="0.01" name="discount" class="form-control so-input"
                    value="{{ old('discount', $serviceOrder->discount ?? 0) }}">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-receipt"></i>Pajak</label>
               <input type="number" min="0" step="0.01" name="tax" class="form-control so-input"
                    value="{{ old('tax', $serviceOrder->tax ?? 0) }}">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-diagram-3"></i>Status</label>
               <select name="order_status" class="form-control so-input">
                    @foreach ($statuses as $status)
                         <option value="{{ $status }}" @selected(old('order_status', $serviceOrder->order_status ?? \App\Models\ServiceOrder::ORDER_STATUS_DRAFT) === $status)>
                              {{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
               </select>
          </div>

          <div class="col-12">
               <label class="so-label"><i class="bi bi-chat-left-text"></i>Catatan</label>
               <textarea name="notes" class="form-control so-input" rows="3">{{ old('notes', $serviceOrder->notes ?? '') }}</textarea>
          </div>
     </div>
</div>

<div class="so-fieldset">
     <div class="so-fieldset-title"><i class="bi bi-list-check"></i>Item Layanan</div>
     <p class="text-muted mb-3">Minimal 1 item layanan untuk setiap pesanan.</p>

     <div class="row g-3">
          <div class="col-md-4">
               <label class="so-label"><i class="bi bi-tools"></i>Layanan</label>
               <select name="items[0][service_id]" class="form-control so-input" required>
                    <option value="">Pilih Layanan</option>
                    @foreach ($services as $service)
                         <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
               </select>
          </div>

          <div class="col-md-4">
               <label class="so-label"><i class="bi bi-person-badge"></i>Karyawan</label>
               <select name="items[0][employee_id]" class="form-control so-input">
                    <option value="">Pilih Karyawan</option>
                    @foreach ($employees as $employee)
                         <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
               </select>
          </div>

          <div class="col-md-2">
               <label class="so-label"><i class="bi bi-123"></i>Qty</label>
               <input type="number" step="0.01" min="0.01" name="items[0][quantity]"
                    class="form-control so-input" value="1" required>
          </div>

          <div class="col-md-2">
               <label class="so-label"><i class="bi bi-cash-stack"></i>Harga</label>
               <input type="number" step="0.01" min="0" name="items[0][unit_price]"
                    class="form-control so-input" value="0">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-percent"></i>Diskon Item</label>
               <input type="number" step="0.01" min="0" name="items[0][discount]"
                    class="form-control so-input" value="0">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-calendar-event"></i>Mulai</label>
               <input type="date" name="items[0][start_date]" class="form-control so-input">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-calendar-check"></i>Selesai</label>
               <input type="date" name="items[0][completion_date]" class="form-control so-input">
          </div>

          <div class="col-md-3">
               <label class="so-label"><i class="bi bi-hourglass-split"></i>Status Item</label>
               <input type="text" name="items[0][status]" class="form-control so-input" value="pending">
          </div>

          <div class="col-12">
               <label class="so-label"><i class="bi bi-stickies"></i>Catatan Item</label>
               <textarea name="items[0][notes]" class="form-control so-input" rows="2"></textarea>
          </div>
     </div>
</div>
