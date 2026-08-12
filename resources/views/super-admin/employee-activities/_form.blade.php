@php
     $employeeActivity = $employeeActivity ?? null;
     $employees = $employees ?? collect();
     $serviceOrders = $serviceOrders ?? collect();
     $statuses = $statuses ?? \App\Models\EmployeeActivity::availableStatuses();

     $selectedStatus = old('status', $employeeActivity?->status ?? \App\Models\EmployeeActivity::STATUS_SUBMITTED);
     $selectedEmployeeId = old('employee_id', $employeeActivity?->employee_id);
     $selectedServiceOrderId = old('service_order_id', $employeeActivity?->service_order_id);
     $startTime = old(
         'start_time',
         $employeeActivity?->start_time ? substr((string) $employeeActivity->start_time, 0, 5) : '',
     );
     $endTime = old('end_time', $employeeActivity?->end_time ? substr((string) $employeeActivity->end_time, 0, 5) : '');
     $quantity = old('quantity', $employeeActivity?->quantity ?? 1);
@endphp

@if ($errors->any())
     <div class="ea-error-summary">
          <i data-feather="alert-circle"></i>
          <div>
               <strong>Periksa kembali input aktivitas pegawai.</strong>
               <ul>
                    @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
               </ul>
          </div>
     </div>
@endif

<div class="ea-form-grid">
     <section class="ea-form-section">
          <div class="ea-form-section-head">
               <span><i data-feather="clipboard"></i></span>
               <strong>Informasi Aktivitas</strong>
          </div>

          <div class="row g-3">
               <div class="col-12 col-lg-6">
                    <label for="employee_id" class="ea-label">Pegawai <span class="ea-required">*</span></label>
                    <select id="employee_id" name="employee_id"
                         class="form-select ea-control @error('employee_id') is-invalid @enderror" required>
                         <option value="">Pilih pegawai</option>
                         @foreach ($employees as $employee)
                              <option value="{{ $employee->id }}" @selected((string) $selectedEmployeeId === (string) $employee->id)>
                                   {{ $employee->full_name ?? 'Tanpa nama' }}
                                   @if (!empty($employee->employee_number))
                                        - {{ $employee->employee_number }}
                                   @endif
                              </option>
                         @endforeach
                    </select>
                    @error('employee_id')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12 col-lg-6">
                    <label for="service_order_id" class="ea-label">Service Order</label>
                    <select id="service_order_id" name="service_order_id"
                         class="form-select ea-control @error('service_order_id') is-invalid @enderror">
                         <option value="">Tanpa service order</option>
                         @foreach ($serviceOrders as $serviceOrder)
                              <option value="{{ $serviceOrder->id }}" @selected((string) $selectedServiceOrderId === (string) $serviceOrder->id)>
                                   {{ $serviceOrder->order_number ?? 'SO #' . $serviceOrder->id }}
                                   @if ($serviceOrder->customer)
                                        - {{ $serviceOrder->customer->company_name ?? $serviceOrder->customer->name }}
                                   @endif
                              </option>
                         @endforeach
                    </select>
                    @error('service_order_id')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12 col-lg-4">
                    <label for="activity_date" class="ea-label">Tanggal Aktivitas <span
                              class="ea-required">*</span></label>
                    <input type="date" id="activity_date" name="activity_date"
                         value="{{ old('activity_date', optional($employeeActivity?->activity_date)->format('Y-m-d')) }}"
                         class="form-control ea-control @error('activity_date') is-invalid @enderror" required>
                    @error('activity_date')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12 col-lg-8">
                    <label for="activity_name" class="ea-label">Nama Aktivitas <span
                              class="ea-required">*</span></label>
                    <input type="text" id="activity_name" name="activity_name" maxlength="180"
                         value="{{ old('activity_name', $employeeActivity?->activity_name) }}"
                         class="form-control ea-control @error('activity_name') is-invalid @enderror"
                         placeholder="Contoh: Pemasangan unit, inspeksi lapangan, maintenance" required>
                    @error('activity_name')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12">
                    <label for="description" class="ea-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                         class="form-control ea-control ea-textarea @error('description') is-invalid @enderror"
                         placeholder="Tuliskan rincian pekerjaan yang dilakukan, hasil aktivitas, atau catatan operasional.">{{ old('description', $employeeActivity?->description) }}</textarea>
                    @error('description')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>
          </div>
     </section>

     <section class="ea-form-section">
          <div class="ea-form-section-head">
               <span><i data-feather="clock"></i></span>
               <strong>Volume dan Waktu</strong>
          </div>

          <div class="row g-3">
               <div class="col-12 col-md-6">
                    <label for="quantity" class="ea-label">Kuantitas <span class="ea-required">*</span></label>
                    <input type="number" id="quantity" name="quantity" min="0.01" step="0.01"
                         value="{{ $quantity }}"
                         class="form-control ea-control @error('quantity') is-invalid @enderror" required>
                    @error('quantity')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12 col-md-6">
                    <label for="unit" class="ea-label">Satuan</label>
                    <input type="text" id="unit" name="unit" maxlength="50"
                         value="{{ old('unit', $employeeActivity?->unit) }}"
                         class="form-control ea-control @error('unit') is-invalid @enderror"
                         placeholder="Contoh: unit, titik, tugas, jam">
                    @error('unit')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12 col-md-6">
                    <label for="start_time" class="ea-label">Jam Mulai</label>
                    <input type="time" id="start_time" name="start_time" step="60" value="{{ $startTime }}"
                         class="form-control ea-control @error('start_time') is-invalid @enderror">
                    @error('start_time')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12 col-md-6">
                    <label for="end_time" class="ea-label">Jam Selesai</label>
                    <input type="time" id="end_time" name="end_time" step="60" value="{{ $endTime }}"
                         class="form-control ea-control @error('end_time') is-invalid @enderror">
                    @error('end_time')
                         <span class="ea-invalid-feedback">{{ $message }}</span>
                    @enderror
               </div>

               <div class="col-12">
                    <div class="ea-duration-preview">
                         <span><i data-feather="timer"></i> Estimasi durasi</span>
                         <strong
                              id="eaDurationPreview">{{ $employeeActivity?->duration_minutes ? number_format((int) $employeeActivity->duration_minutes) . ' menit' : 'Otomatis dihitung dari jam mulai dan selesai' }}</strong>
                    </div>
               </div>
          </div>
     </section>

     <section class="ea-form-section">
          <div class="ea-form-section-head">
               <span><i data-feather="shield"></i></span>
               <strong>Status Aktivitas</strong>
          </div>

          <div class="ea-status-grid">
               @foreach ($statuses as $statusOption)
                    @php
                         $label = match ($statusOption) {
                             \App\Models\EmployeeActivity::STATUS_SUBMITTED => 'Submitted',
                             \App\Models\EmployeeActivity::STATUS_VERIFIED => 'Verified',
                             \App\Models\EmployeeActivity::STATUS_REJECTED => 'Rejected',
                             default => ucfirst(str_replace('_', ' ', (string) $statusOption)),
                         };
                    @endphp
                    <div class="ea-status-item">
                         <input type="radio" id="status-{{ $statusOption }}" name="status"
                              value="{{ $statusOption }}" @checked($selectedStatus === $statusOption) required>
                         <label for="status-{{ $statusOption }}"
                              class="ea-status-label ea-status-{{ $statusOption }}">
                              <strong>{{ $label }}</strong>
                              <small>Gunakan status ini untuk kondisi aktivitas saat ini.</small>
                         </label>
                    </div>
               @endforeach
          </div>
          @error('status')
               <span class="ea-invalid-feedback">{{ $message }}</span>
          @enderror
     </section>
</div>

@once
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const startTimeInput = document.getElementById('start_time');
               const endTimeInput = document.getElementById('end_time');
               const durationPreview = document.getElementById('eaDurationPreview');

               function updateDurationPreview() {
                    if (!durationPreview || !startTimeInput || !endTimeInput) {
                         return;
                    }

                    if (!startTimeInput.value || !endTimeInput.value) {
                         durationPreview.textContent = 'Otomatis dihitung dari jam mulai dan selesai';
                         return;
                    }

                    const start = startTimeInput.value.split(':').map(Number);
                    const end = endTimeInput.value.split(':').map(Number);

                    if (start.length < 2 || end.length < 2) {
                         durationPreview.textContent = 'Otomatis dihitung dari jam mulai dan selesai';
                         return;
                    }

                    let startMinutes = (start[0] * 60) + start[1];
                    let endMinutes = (end[0] * 60) + end[1];

                    if (endMinutes <= startMinutes) {
                         endMinutes += 24 * 60;
                    }

                    durationPreview.textContent = new Intl.NumberFormat('id-ID').format(endMinutes -
                         startMinutes) + ' menit';
               }

               startTimeInput?.addEventListener('input', updateDurationPreview);
               endTimeInput?.addEventListener('input', updateDurationPreview);
               updateDurationPreview();

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }
          });
     </script>
@endonce
