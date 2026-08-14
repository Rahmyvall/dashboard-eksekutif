@extends('layouts.app')

@section('title', 'Buat Pengajuan Cuti')

@section('content')
     @php
          $authUser = auth()->user();
          $isKaryawan = $authUser && method_exists($authUser, 'hasRole') && $authUser->hasRole('karyawan');
          $backRoute = $isKaryawan ? 'leave-requests.mine' : 'super-admin.leave-requests.index';

          if (!\Illuminate\Support\Facades\Route::has($backRoute)) {
              $backRoute = 'leave-requests.mine';
          }
     @endphp

     <style>
          @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          .lrf-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 44px;
               color: #1f2a44;
               font-family: 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 7% 8%, rgba(14, 165, 233, .12), transparent 24%),
                    radial-gradient(circle at 93% 10%, rgba(16, 185, 129, .13), transparent 26%),
                    linear-gradient(155deg, #f7fcff 0%, #eff8ff 52%, #ecfbf7 100%);
          }

          .lrf-wrap {
               max-width: 1360px;
               margin: 0 auto;
          }

          .lrf-hero {
               border-radius: 24px;
               padding: 24px 28px;
               margin-bottom: 16px;
               color: #fff;
               background: linear-gradient(124deg, #0f766e 0%, #0369a1 58%, #2563eb 100%);
               box-shadow: 0 22px 48px rgba(3, 105, 161, .24);
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 10px;
          }

          .lrf-hero h4 {
               margin: 0;
               font-family: 'Sora', 'Manrope', sans-serif;
               font-size: clamp(1.05rem, 2vw, 1.45rem);
          }

          .lrf-hero p {
               margin: 6px 0 0;
               font-size: .84rem;
               color: rgba(255, 255, 255, .93);
          }

          .lrf-card {
               border: 1px solid #dbe6f2;
               border-radius: 20px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 30px rgba(15, 23, 42, .08);
               padding: 18px;
          }

          .lrf-card .form-control,
          .lrf-card .form-select {
               min-height: 43px;
               border: 1px solid #d5e2ef;
               border-radius: 11px;
               font-size: .84rem;
          }

          .lrf-card .form-label {
               font-weight: 700;
               color: #3b4d65;
               font-size: .8rem;
          }
     </style>

     <div class="lrf-page">
          <div class="lrf-wrap">
               <div class="lrf-hero">
                    <div>
                         <h4>Buat Pengajuan Cuti</h4>
                         <p>Produktivitas Karyawan dan Transaksi Jasa</p>
                    </div>
                    <a href="{{ route($backRoute) }}" class="btn btn-light btn-sm" title="Kembali" aria-label="Kembali">
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

               <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data"
                    class="lrf-card">
                    @csrf

                    <div class="row g-3">
                         <div class="col-md-6">
                              <label class="form-label">Pegawai</label>
                              <select name="employee_id" class="form-select">
                                   <option value="">Pilih Pegawai</option>
                                   @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" @selected((string) old('employee_id', (string) ($currentEmployeeId ?? '')) === (string) $employee->id)>
                                             {{ $employee->full_name }} ({{ $employee->employee_number }})
                                        </option>
                                   @endforeach
                              </select>
                         </div>

                         <div class="col-md-6">
                              <label class="form-label">Jenis Cuti</label>
                              <input type="text" name="leave_type" value="{{ old('leave_type') }}" class="form-control"
                                   required>
                         </div>

                         <div class="col-md-6">
                              <label class="form-label">Tanggal Mulai</label>
                              <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control"
                                   required>
                         </div>

                         <div class="col-md-6">
                              <label class="form-label">Tanggal Selesai</label>
                              <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control"
                                   required>
                         </div>

                         <div class="col-12">
                              <label class="form-label">Alasan</label>
                              <textarea name="reason" rows="4" class="form-control" required>{{ old('reason') }}</textarea>
                         </div>

                         <div class="col-12">
                              <label class="form-label">Lampiran (opsional)</label>
                              <input type="file" name="attachment" class="form-control">
                         </div>
                    </div>

                    <div class="mt-3">
                         <button type="submit" class="btn btn-primary" title="Simpan Pengajuan"
                              aria-label="Simpan Pengajuan">
                              <i class="bi bi-check2-circle"></i>
                         </button>
                    </div>
               </form>
          </div>
     </div>
@endsection
