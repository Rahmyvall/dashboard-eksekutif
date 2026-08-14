@php
     $attendance = $attendance ?? null;
     $employees = $employees ?? collect();
     $statuses = $statuses ?? \App\Models\Attendance::statuses();
@endphp

<style>
     .attf-alert {
          border: 1px solid #fecaca;
          border-left: 4px solid #ef4444;
          border-radius: 14px;
          background: linear-gradient(130deg, #fff1f2 0%, #fee2e2 100%);
          color: #991b1b;
     }

     .attf-field {
          padding: 13px;
          border: 1px solid #dce8f2;
          border-radius: 14px;
          background: #fbfdff;
     }

     .attf-label {
          margin-bottom: 7px;
          color: #4c6179;
          font-size: .73rem;
          font-weight: 800;
          text-transform: uppercase;
          letter-spacing: .06em;
     }

     .attf-control {
          min-height: 42px;
          border: 1px solid #d4e0eb;
          border-radius: 11px;
     }

     .attf-control:focus {
          border-color: #38bdf8;
          box-shadow: 0 0 0 .2rem rgba(56, 189, 248, .18);
     }

     .attf-help {
          color: #60758d;
          font-size: .75rem;
     }
</style>

@if ($errors->any())
     <div class="alert attf-alert">
          <strong>Periksa kembali data yang diisi.</strong>
          <ul class="mb-0 mt-2">
               @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
               @endforeach
          </ul>
     </div>
@endif

<div class="row g-3">
     <div class="col-md-6">
          <div class="attf-field">
               <label for="employee_id" class="attf-label">Pegawai</label>
               <select name="employee_id" id="employee_id"
                    class="form-select attf-control @error('employee_id') is-invalid @enderror" required>
                    <option value="">Pilih pegawai</option>
                    @foreach ($employees as $employee)
                         <option value="{{ $employee->id }}" @selected((string) old('employee_id', $attendance?->employee_id) === (string) $employee->id)>
                              {{ $employee->full_name }}
                              @if (!empty($employee->employee_number))
                                   ({{ $employee->employee_number }})
                              @endif
                         </option>
                    @endforeach
               </select>
               @error('employee_id')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-6">
          <div class="attf-field">
               <label for="attendance_date" class="attf-label">Tanggal Kehadiran</label>
               <input type="date" name="attendance_date" id="attendance_date"
                    value="{{ old('attendance_date', optional($attendance?->attendance_date)->format('Y-m-d')) }}"
                    class="form-control attf-control @error('attendance_date') is-invalid @enderror" required>
               @error('attendance_date')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-4">
          <div class="attf-field">
               <label for="check_in" class="attf-label">Jam Masuk</label>
               <input type="time" name="check_in" id="check_in"
                    value="{{ old('check_in', $attendance?->check_in ? substr((string) $attendance->check_in, 0, 5) : '') }}"
                    class="form-control attf-control @error('check_in') is-invalid @enderror">
               @error('check_in')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-4">
          <div class="attf-field">
               <label for="check_out" class="attf-label">Jam Pulang</label>
               <input type="time" name="check_out" id="check_out"
                    value="{{ old('check_out', $attendance?->check_out ? substr((string) $attendance->check_out, 0, 5) : '') }}"
                    class="form-control attf-control @error('check_out') is-invalid @enderror">
               @error('check_out')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-4">
          <div class="attf-field">
               <label for="status" class="attf-label">Status</label>
               <select name="status" id="status"
                    class="form-select attf-control @error('status') is-invalid @enderror" required>
                    @foreach ($statuses as $status)
                         <option value="{{ $status }}" @selected(old('status', $attendance?->status ?? \App\Models\Attendance::STATUS_PRESENT) === $status)>
                              {{ ucfirst($status) }}
                         </option>
                    @endforeach
               </select>
               @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-4">
          <div class="attf-field">
               <label for="late_minutes" class="attf-label">Terlambat (menit)</label>
               <input type="number" min="0" name="late_minutes" id="late_minutes"
                    value="{{ old('late_minutes', $attendance?->late_minutes ?? 0) }}"
                    class="form-control attf-control @error('late_minutes') is-invalid @enderror">
               @error('late_minutes')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-4">
          <div class="attf-field">
               <label for="overtime_minutes" class="attf-label">Lembur (menit)</label>
               <input type="number" min="0" name="overtime_minutes" id="overtime_minutes"
                    value="{{ old('overtime_minutes', $attendance?->overtime_minutes ?? 0) }}"
                    class="form-control attf-control @error('overtime_minutes') is-invalid @enderror">
               @error('overtime_minutes')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-md-4">
          <div class="attf-field">
               <label for="work_duration_minutes" class="attf-label">Durasi Kerja (menit)</label>
               <input type="number" min="0" name="work_duration_minutes" id="work_duration_minutes"
                    value="{{ old('work_duration_minutes', $attendance?->work_duration_minutes ?? 0) }}"
                    class="form-control attf-control @error('work_duration_minutes') is-invalid @enderror">
               <div class="attf-help">Kosongkan atau isi 0 untuk hitung otomatis dari jam masuk/pulang.</div>
               @error('work_duration_minutes')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>

     <div class="col-12">
          <div class="attf-field">
               <label for="notes" class="attf-label">Catatan</label>
               <textarea name="notes" id="notes" rows="3"
                    class="form-control attf-control @error('notes') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('notes', $attendance?->notes) }}</textarea>
               @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
               @enderror
          </div>
     </div>
</div>
