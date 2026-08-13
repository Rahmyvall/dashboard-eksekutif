@extends('layouts.app')

@section('page-title', 'Edit Riwayat Status Layanan')

@section('breadcrumb')
     <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Riwayat Status</a></li>
     <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@push('styles')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap');

          :root {
               --se-bg: #eef4fb;
               --se-ink: #0b1e3a;
               --se-sub: #607390;
               --se-line: #d4dfed;
               --se-primary: #0f6fc6;
               --se-primary-dark: #0a4b88;
               --se-accent: #ef7d22;
          }

          .se-page {
               min-height: calc(100vh - 70px);
               padding: 24px clamp(12px, 2vw, 28px) 40px;
               background:
                    radial-gradient(circle at 7% 8%, rgba(15, 111, 198, .14), transparent 24%),
                    radial-gradient(circle at 93% 10%, rgba(239, 125, 34, .14), transparent 24%),
                    var(--se-bg);
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
          }

          .se-wrap {
               width: 100%;
               max-width: none;
               margin: 0;
          }

          .se-topline {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 12px;
               margin-bottom: 10px;
               font-size: .78rem;
               color: #607391;
          }

          .se-topline .dot {
               display: inline-block;
               width: 8px;
               height: 8px;
               border-radius: 50%;
               background: #16a34a;
               margin-right: 8px;
               box-shadow: 0 0 0 8px rgba(22, 163, 74, .12);
          }

          .se-hero {
               position: relative;
               overflow: hidden;
               border-radius: 26px;
               padding: clamp(20px, 2vw, 30px);
               margin-bottom: 16px;
               color: #fff;
               background: linear-gradient(128deg, #0f6fc6 0%, #0a4b88 50%, #ef7d22 100%);
               box-shadow: 0 24px 56px rgba(10, 75, 136, .34);
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 12px;
               flex-wrap: wrap;
          }

          .se-hero::after {
               content: '';
               position: absolute;
               width: 300px;
               height: 300px;
               border-radius: 50%;
               right: -110px;
               top: -150px;
               background: rgba(255, 255, 255, .14);
          }

          .se-hero h4 {
               margin: 0;
               font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
               font-weight: 800;
               font-size: clamp(1.15rem, 1.8vw, 1.65rem);
               letter-spacing: -.015em;
          }

          .se-hero p {
               margin: 6px 0 0;
               opacity: .95;
               line-height: 1.6;
          }

          .se-hero>* {
               position: relative;
               z-index: 1;
          }

          .se-btn {
               border: 0;
               border-radius: 12px;
               padding: 10px 14px;
               font-weight: 700;
               text-decoration: none;
               display: inline-flex;
               align-items: center;
               gap: 8px;
               transition: .18s ease;
          }

          .se-btn:hover {
               transform: translateY(-1px);
               text-decoration: none;
          }

          .se-btn-back {
               background: #fff;
               color: #0a4b88;
          }

          .se-card {
               border: 1px solid var(--se-line);
               border-radius: 20px;
               background: #fff;
               box-shadow: 0 16px 34px rgba(10, 26, 56, .07);
          }

          .se-card-body {
               padding: clamp(18px, 1.6vw, 28px);
          }

          .se-label {
               display: block;
               font-size: .76rem;
               text-transform: uppercase;
               letter-spacing: .08em;
               color: var(--se-sub);
               font-weight: 700;
               margin-bottom: 6px;
          }

          .se-form .form-control,
          .se-form .form-select {
               border: 1px solid #d7e1ef;
               border-radius: 12px;
               min-height: 44px;
               font-size: .94rem;
               background: #fcfdff;
          }

          .se-form .form-control:focus,
          .se-form .form-select:focus {
               border-color: #7db8eb;
               box-shadow: 0 0 0 3px rgba(15, 111, 198, .12);
          }

          .se-submit {
               background: linear-gradient(120deg, var(--se-primary), var(--se-primary-dark));
               color: #fff;
               box-shadow: 0 10px 18px rgba(10, 75, 136, .24);
          }

          .se-delete {
               background: #fff1f2;
               color: #be123c;
               border: 1px solid #fecdd3;
          }

          .se-info {
               border-radius: 12px;
               background: linear-gradient(180deg, #f9fcff, #f3f8ff);
               border: 1px dashed #c8daef;
               padding: 14px;
               color: #314861;
               font-size: .9rem;
          }

          .se-alert {
               border-radius: 14px;
               border: 1px solid;
               padding: 10px 12px;
               margin-bottom: 12px;
               font-size: .92rem;
          }

          .se-alert-success {
               background: #ecfdf3;
               border-color: #b7ebcb;
               color: #166534;
          }

          .se-alert-danger {
               background: #fff1f2;
               border-color: #fecdd3;
               color: #9f1239;
          }

          .se-danger-box {
               margin-top: 12px;
               border-top: 1px dashed #d7dfec;
               padding-top: 12px;
          }

          @media (max-width: 767px) {
               .se-page {
                    padding: 18px 10px 28px;
               }

               .se-card-body {
                    padding: 16px;
               }

               .se-topline {
                    flex-direction: column;
                    align-items: flex-start;
               }
          }
     </style>
@endpush

@section('content')
     @php
          $history = $history ?? ($serviceOrderStatusHistory ?? null);

          $routeBases = ['super-admin.service-order-status-histories', 'super-admin.service_order_status_histories'];

          $resolveRoute = static function (string $action) use ($routeBases): ?string {
              foreach ($routeBases as $base) {
                  $candidate = $base . '.' . $action;
                  if (\Illuminate\Support\Facades\Route::has($candidate)) {
                      return $candidate;
                  }
              }

              return null;
          };

          $indexRoute = $resolveRoute('index');
          $updateRoute = $resolveRoute('update');
          $destroyRoute = $resolveRoute('destroy');
          $statusOptions = ['draft', 'pending', 'processing', 'completed', 'cancelled'];
     @endphp

     <div class="se-page">
          <div class="se-wrap">
               <div class="se-topline">
                    <div><span class="dot"></span>Editor Status Order Aktif</div>
                    <div>{{ now()->format('d M Y, H:i') }} WIB</div>
               </div>

               <section class="se-hero">
                    <div>
                         <h4><i class="bi bi-sliders me-2"></i>Edit Riwayat Status Layanan</h4>
                         <p>Perbarui transisi status untuk menjaga data monitoring operasional tetap presisi.</p>
                    </div>
                    <a href="{{ $indexRoute ? route($indexRoute) : url()->previous() }}" class="se-btn se-btn-back">
                         <i class="bi bi-arrow-left"></i> Kembali
                    </a>
               </section>

               @if (session('success'))
                    <div class="se-alert se-alert-success">{{ session('success') }}</div>
               @endif

               @if (session('error'))
                    <div class="se-alert se-alert-danger">{{ session('error') }}</div>
               @endif

               @if ($errors->any())
                    <div class="se-alert se-alert-danger">
                         <ul class="mb-0 ps-3">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <section class="se-card">
                    <div class="se-card-body">
                         <div class="se-info mb-3">
                              ID Riwayat: <strong>{{ $history->id ?? '-' }}</strong>
                              <span class="mx-2">|</span>
                              Order: <strong>{{ $history?->serviceOrder?->order_number ?? '-' }}</strong>
                         </div>

                         @if ($history && $updateRoute)
                              <form method="POST" action="{{ route($updateRoute, $history) }}" class="se-form row g-3">
                                   @csrf
                                   @method('PUT')

                                   <div class="col-md-6">
                                        <label class="se-label">Status Sebelumnya</label>
                                        <select name="previous_status"
                                             class="form-select @error('previous_status') is-invalid @enderror">
                                             <option value="">Initial</option>
                                             @foreach ($statusOptions as $status)
                                                  <option value="{{ $status }}" @selected(old('previous_status', $history->previous_status) === $status)>
                                                       {{ ucfirst($status) }}</option>
                                             @endforeach
                                        </select>
                                   </div>

                                   <div class="col-md-6">
                                        <label class="se-label">Status Baru</label>
                                        <select name="new_status"
                                             class="form-select @error('new_status') is-invalid @enderror" required>
                                             @foreach ($statusOptions as $status)
                                                  <option value="{{ $status }}" @selected(old('new_status', $history->new_status) === $status)>
                                                       {{ ucfirst($status) }}</option>
                                             @endforeach
                                        </select>
                                   </div>

                                   <div class="col-md-6">
                                        <label class="se-label">Diubah Oleh (User ID)</label>
                                        <input type="number" min="1" name="changed_by"
                                             class="form-control @error('changed_by') is-invalid @enderror"
                                             value="{{ old('changed_by', $history->changed_by) }}"
                                             placeholder="Kosongkan untuk user saat ini">
                                   </div>

                                   <div class="col-md-6">
                                        <label class="se-label">Waktu Perubahan</label>
                                        <input type="datetime-local" name="changed_at"
                                             class="form-control @error('changed_at') is-invalid @enderror"
                                             value="{{ old('changed_at', optional($history->changed_at)->format('Y-m-d\\TH:i')) }}">
                                   </div>

                                   <div class="col-12">
                                        <label class="se-label">Catatan</label>
                                        <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                             placeholder="Tambahkan alasan perubahan status jika diperlukan">{{ old('notes', $history->notes) }}</textarea>
                                   </div>

                                   <div class="col-12 d-flex flex-wrap gap-2 justify-content-between mt-2">
                                        <span></span>

                                        <button type="submit" class="se-btn se-submit">
                                             <i class="bi bi-check2-circle"></i> Simpan Perubahan
                                        </button>
                                   </div>
                              </form>

                              @if ($history && $destroyRoute)
                                   <div class="se-danger-box">
                                        <form method="POST" action="{{ route($destroyRoute, $history) }}" class="d-inline"
                                             onsubmit="return confirm('Hapus riwayat status ini?');">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="se-btn se-delete">
                                                  <i class="bi bi-trash"></i> Hapus Riwayat Ini
                                             </button>
                                        </form>
                                   </div>
                              @endif
                         @else
                              <div class="alert alert-warning mb-0">
                                   Data tidak ditemukan atau route update belum tersedia.
                              </div>
                         @endif
                    </div>
               </section>
          </div>
     </div>
@endsection
