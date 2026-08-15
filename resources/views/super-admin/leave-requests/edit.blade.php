@extends('layouts.app')

@section('title', 'Edit Pengajuan Cuti')

@section('content')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          :root {
               --lrf-bg: #f4f8ff;
               --lrf-panel: rgba(255, 255, 255, 0.96);
               --lrf-border: rgba(148, 163, 184, 0.24);
               --lrf-text: #15243d;
               --lrf-muted: #64748b;
               --lrf-primary: #0f766e;
               --lrf-secondary: #2563eb;
               --lrf-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
          }

          .lrf-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 44px;
               color: var(--lrf-text);
               font-family: 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 7% 8%, rgba(14, 165, 233, 0.12), transparent 22%),
                    radial-gradient(circle at 94% 10%, rgba(16, 185, 129, 0.12), transparent 24%),
                    linear-gradient(145deg, #f9fcff 0%, #f3f8ff 50%, #f0fdf9 100%);
          }

          .lrf-wrap {
               max-width: 1500px;
               margin: 0 auto;
          }

          .lrf-hero {
               position: relative;
               overflow: hidden;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 12px;
               border: 1px solid rgba(255, 255, 255, 0.76);
               border-radius: 28px;
               padding: 28px 30px;
               margin-bottom: 18px;
               color: #fff;
               background:
                    radial-gradient(circle at 88% 18%, rgba(255, 255, 255, 0.25), transparent 20%),
                    radial-gradient(circle at 15% 100%, rgba(16, 185, 129, 0.18), transparent 28%),
                    linear-gradient(128deg, #0f766e 0%, #0369a1 54%, #2563eb 100%);
               box-shadow: 0 26px 60px rgba(3, 105, 161, 0.22);
          }

          .lrf-hero::before {
               position: absolute;
               top: -80px;
               right: 10%;
               width: 240px;
               height: 240px;
               content: '';
               border: 1px solid rgba(255, 255, 255, 0.12);
               border-radius: 50%;
          }

          .lrf-hero h4 {
               margin: 0;
               font-family: 'Sora', 'Manrope', sans-serif;
               font-size: clamp(1.2rem, 2vw, 1.7rem);
               font-weight: 800;
               letter-spacing: -0.04em;
          }

          .lrf-hero p {
               margin: 7px 0 0;
               font-size: 0.9rem;
               color: rgba(255, 255, 255, 0.9);
          }

          .lrf-back {
               position: relative;
               z-index: 1;
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 42px;
               height: 42px;
               border-radius: 12px;
               background: rgba(255, 255, 255, 0.96);
               color: #0f172a;
               border: 1px solid rgba(255, 255, 255, 0.3);
               box-shadow: 0 12px 24px rgba(15, 23, 42, 0.14);
          }

          .lrf-card {
               border: 1px solid var(--lrf-border);
               border-radius: 24px;
               background: var(--lrf-panel);
               box-shadow: var(--lrf-shadow);
               padding: 22px;
          }

          .lrf-card .form-control,
          .lrf-card .form-select {
               min-height: 46px;
               border: 1px solid rgba(148, 163, 184, 0.25);
               border-radius: 12px;
               background: rgba(248, 250, 252, 0.9);
               font-size: 0.84rem;
               color: var(--lrf-text);
               box-shadow: none;
          }

          .lrf-card .form-control:focus,
          .lrf-card .form-select:focus {
               border-color: rgba(37, 99, 235, 0.6);
               box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
          }

          .lrf-card textarea.form-control {
               min-height: 140px;
          }

          .lrf-card .form-label {
               display: block;
               margin-bottom: 8px;
               font-weight: 800;
               color: #334155;
               font-size: 0.76rem;
               letter-spacing: 0.04em;
               text-transform: uppercase;
          }

          .lrf-submit {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               gap: 8px;
               min-height: 46px;
               padding: 0 20px;
               border: none;
               border-radius: 12px;
               color: #fff;
               font-weight: 800;
               background: linear-gradient(135deg, #0f766e, #2563eb);
               box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
          }

          .lrf-submit:hover {
               filter: brightness(1.03);
          }

          .form-check-input:checked {
               background-color: #2563eb;
               border-color: #2563eb;
          }

          @media (max-width: 767px) {
               .lrf-page {
                    padding-left: 12px;
                    padding-right: 12px;
               }

               .lrf-hero {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 22px 18px;
               }
          }
     </style>

     <div class="lrf-page">
          <div class="lrf-wrap">
               <div class="lrf-hero">
                    <div>
                         <h4>Edit Pengajuan Cuti</h4>
                         <p>Produktivitas Karyawan dan Transaksi Jasa</p>
                    </div>
                    <a href="{{ route('super-admin.leave-requests.show', $leaveRequest) }}" class="lrf-back" title="Kembali"
                         aria-label="Kembali">
                         <i class="bi bi-arrow-left"></i>
                    </a>
               </div>

               @if ($errors->any())
                    <div class="alert alert-danger">
                         <ul class="mb-0">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <form method="POST" action="{{ route('super-admin.leave-requests.update', $leaveRequest) }}"
                    enctype="multipart/form-data" class="lrf-card">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                         <div class="col-md-6">
                              <label class="form-label">Pegawai</label>
                              <select name="employee_id" class="form-select">
                                   @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" @selected((string) old('employee_id', (string) $leaveRequest->employee_id) === (string) $employee->id)>
                                             {{ $employee->full_name }} ({{ $employee->employee_number }})
                                        </option>
                                   @endforeach
                              </select>
                         </div>

                         <div class="col-md-6">
                              <label class="form-label">Jenis Cuti</label>
                              <input type="text" name="leave_type"
                                   value="{{ old('leave_type', $leaveRequest->leave_type) }}" class="form-control" required>
                         </div>

                         <div class="col-md-6">
                              <label class="form-label">Tanggal Mulai</label>
                              <input type="date" name="start_date"
                                   value="{{ old('start_date', optional($leaveRequest->start_date)->format('Y-m-d')) }}"
                                   class="form-control" required>
                         </div>

                         <div class="col-md-6">
                              <label class="form-label">Tanggal Selesai</label>
                              <input type="date" name="end_date"
                                   value="{{ old('end_date', optional($leaveRequest->end_date)->format('Y-m-d')) }}"
                                   class="form-control" required>
                         </div>

                         <div class="col-12">
                              <label class="form-label">Alasan</label>
                              <textarea name="reason" rows="4" class="form-control" required>{{ old('reason', $leaveRequest->reason) }}</textarea>
                         </div>

                         <div class="col-12">
                              <label class="form-label">Lampiran (opsional)</label>
                              <input type="file" name="attachment" class="form-control">
                              @if ($leaveRequest->attachment_path)
                                   <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="remove_attachment"
                                             name="remove_attachment" value="1">
                                        <label class="form-check-label" for="remove_attachment">Hapus lampiran saat
                                             ini</label>
                                   </div>
                              @endif
                         </div>
                    </div>

                    <div class="mt-4">
                         <button type="submit" class="lrf-submit" title="Simpan Perubahan" aria-label="Simpan Perubahan">
                              <i class="bi bi-save2"></i>
                              Simpan Perubahan
                         </button>
                    </div>
               </form>
          </div>
     </div>
@endsection
