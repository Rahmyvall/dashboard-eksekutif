@extends('layouts.app')

@section('page-title', 'Detail Pembayaran')

@section('content')
     <style>
          .payment-show {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 48px;
               background: linear-gradient(145deg, #f8fbff, #f7f4ff 55%, #effcff)
          }

          .payment-show-wrap {
               max-width: 1120px;
               margin: auto
          }

          .payment-show-hero {
               padding: 30px 32px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 25px;
               background: linear-gradient(120deg, #0f766e, #0891b2 55%, #4f46e5);
               box-shadow: 0 20px 48px rgba(15, 118, 110, .18)
          }

          .payment-show-row {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 18px
          }

          .payment-show h1 {
               margin: 0;
               font-size: clamp(1.7rem, 3vw, 2.3rem);
               font-weight: 850
          }

          .payment-show-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .85)
          }

          .show-actions {
               display: flex;
               gap: 9px;
               flex-wrap: wrap
          }

          .show-btn {
               display: inline-flex;
               min-height: 43px;
               padding: 9px 14px;
               gap: 7px;
               align-items: center;
               color: #0f766e;
               text-decoration: none;
               border-radius: 11px;
               background: #fff;
               font-weight: 800
          }

          .show-btn-danger {
               color: #be123c;
               background: #fff1f2;
               border: 0
          }

          .show-btn-print {
               color: #075985;
               background: #e0f2fe;
          }

          .show-card {
               padding: 24px;
               margin-top: 20px;
               border: 1px solid #e2e8f0;
               border-radius: 22px;
               background: #fff;
               box-shadow: 0 16px 40px rgba(51, 65, 85, .07)
          }

          .show-title {
               padding-bottom: 12px;
               margin: 0 0 18px;
               color: #0f766e;
               border-bottom: 1px solid #e2e8f0;
               font-size: 1rem;
               font-weight: 850
          }

          .detail-grid {
               display: grid;
               grid-template-columns: repeat(3, 1fr);
               gap: 18px
          }

          .detail-item small {
               display: block;
               margin-bottom: 5px;
               color: #94a3b8;
               font-size: .7rem;
               font-weight: 800;
               letter-spacing: .06em;
               text-transform: uppercase
          }

          .detail-item strong {
               color: #1e293b;
               font-size: 1rem
          }

          .detail-item.full {
               grid-column: 1/-1
          }

          .status {
               display: inline-flex;
               padding: 6px 11px;
               border-radius: 999px;
               font-size: .75rem;
               font-weight: 800
          }

          .status-confirmed {
               color: #047857;
               background: #d1fae5
          }

          .status-pending {
               color: #b45309;
               background: #fef3c7
          }

          .status-cancelled,
          .status-refunded {
               color: #be123c;
               background: #ffe4e6
          }

          .amount {
               color: #0f766e;
               font-size: 1.4rem !important;
               font-weight: 900
          }

          .inline-form {
               display: inline-flex;
               gap: 8px;
               align-items: center
          }

          .inline-form select {
               min-height: 38px;
               padding: 6px 10px;
               border: 1px solid #dbe3ee;
               border-radius: 9px
          }

          .btn-status {
               min-height: 38px;
               padding: 6px 11px;
               color: #fff;
               border: 0;
               border-radius: 9px;
               background: #0f766e;
               font-weight: 750
          }

          .proof-link {
               color: #0f766e;
               font-weight: 750;
               text-decoration: none
          }

          .proof-visual {
               display: grid;
               grid-template-columns: minmax(0, 1fr) minmax(300px, 390px);
               gap: 20px;
               align-items: start;
          }

          .proof-photo {
               display: block;
               width: 100%;
               max-height: 360px;
               object-fit: contain;
               border: 1px solid #dbe3ee;
               border-radius: 16px;
               background: #f8fafc;
          }

          .camera-box {
               overflow: hidden;
               border: 1px solid #dbe3ee;
               border-radius: 16px;
               background: #0f172a;
          }

          .camera-video,
          .camera-canvas {
               display: block;
               width: 100%;
               min-height: 215px;
               object-fit: cover;
               background: #020617;
          }

          .camera-canvas {
               display: none;
          }

          .camera-controls {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               padding: 13px;
          }

          .camera-button {
               min-height: 38px;
               padding: 8px 12px;
               color: #fff;
               border: 0;
               border-radius: 9px;
               background: #0f766e;
               font-size: .76rem;
               font-weight: 800;
          }

          .camera-button.secondary {
               background: #334155;
          }

          .camera-button:disabled {
               cursor: not-allowed;
               opacity: .45;
          }

          .camera-status {
               padding: 0 13px 13px;
               color: #cbd5e1;
               font-size: .74rem;
          }

          .proof-caption {
               margin-top: 8px;
               color: #94a3b8;
               font-size: .74rem;
          }

          @media(max-width:750px) {

               .payment-show-row,
               .detail-grid {
                    display: grid;
                    grid-template-columns: 1fr
               }

               .show-actions {
                    margin-top: 15px
               }

               .proof-visual {
                    grid-template-columns: 1fr;
               }
          }
     </style>
     <div class="payment-show">
          <div class="payment-show-wrap">
               <div class="payment-show-hero">
                    <div class="payment-show-row">
                         <div>
                              <h1>{{ $payment->payment_number }}</h1>
                              <p>Detail transaksi pembayaran</p>
                         </div>
                         <div class="show-actions"><a class="show-btn show-btn-print" target="_blank"
                                   href="{{ route('super-admin.payments.print', $payment) }}"><i class="fas fa-print"></i>
                                   Cetak</a><a class="show-btn" href="{{ route('super-admin.payments.edit', $payment) }}"><i
                                        class="fas fa-edit"></i>
                                   Edit</a><a class="show-btn" href="{{ route('super-admin.payments.index') }}"><i
                                        class="fas fa-list"></i> Daftar</a></div>
                    </div>
               </div>
               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif @if (session('error'))
                         <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="show-card">
                         <h5 class="show-title">Ringkasan Pembayaran</h5>
                         <div class="detail-grid">
                              <div class="detail-item"><small>Jumlah</small><strong class="amount">Rp
                                        {{ number_format((float) $payment->amount, 2, ',', '.') }}</strong></div>
                              <div class="detail-item"><small>Status</small><span
                                        class="status status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                              </div>
                              <div class="detail-item">
                                   <small>Tanggal</small><strong>{{ $payment->payment_date?->format('d F Y') ?? '-' }}</strong>
                              </div>
                              <div class="detail-item">
                                   <small>Metode</small><strong>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</strong>
                              </div>
                              <div class="detail-item">
                                   <small>Referensi</small><strong>{{ $payment->reference_number ?: '-' }}</strong>
                              </div>
                              <div class="detail-item"><small>Diterima
                                        oleh</small><strong>{{ $payment->receiver?->name ?? 'Sistem' }}</strong></div>
                              <div class="detail-item full"><small>Bukti Pembayaran</small>
                                   @if ($payment->proof_of_payment_path)
                                        <a class="proof-link" target="_blank"
                                             href="{{ asset('storage/' . $payment->proof_of_payment_path) }}"><i
                                                  class="fas fa-paperclip"></i> Lihat bukti
                                        pembayaran</a>@else<strong>-</strong>
                                   @endif
                              </div>
                              <div class="detail-item full">
                                   <small>Catatan</small><strong>{{ $payment->notes ?: '-' }}</strong>
                              </div>
                         </div>
                    </div>
                    <div class="show-card">
                         <h5 class="show-title">Relasi Transaksi</h5>
                         <div class="detail-grid">
                              <div class="detail-item"><small>Service
                                        Order</small><strong>{{ $payment->serviceOrder?->order_number ?? '-' }}</strong>
                              </div>
                              <div class="detail-item">
                                   <small>Customer</small><strong>{{ $payment->serviceOrder?->customer?->display_name ?? ($payment->serviceOrder?->customer?->name ?? '-') }}</strong>
                              </div>
                              <div class="detail-item">
                                   <small>Invoice</small><strong>{{ $payment->invoice?->invoice_number ?? '-' }}</strong>
                              </div>
                         </div>
                    </div>
                    <div class="show-card">
                         <h5 class="show-title"><i class="fas fa-camera me-2"></i>Bukti Visual Pembayaran</h5>
                         <div class="proof-visual">
                              <div>
                                   @if (
                                       $payment->proof_of_payment_path &&
                                           in_array(strtolower(pathinfo($payment->proof_of_payment_path, PATHINFO_EXTENSION)), [
                                               'jpg',
                                               'jpeg',
                                               'png',
                                               'webp',
                                           ]))
                                        <img class="proof-photo"
                                             src="{{ asset('storage/' . $payment->proof_of_payment_path) }}"
                                             alt="Bukti pembayaran {{ $payment->payment_number }}">
                                        <div class="proof-caption">Foto bukti pembayaran yang tersimpan.</div>
                                   @elseif ($payment->proof_of_payment_path)
                                        <a class="proof-link" target="_blank"
                                             href="{{ asset('storage/' . $payment->proof_of_payment_path) }}"><i
                                                  class="fas fa-file-arrow-up me-1"></i> Buka file bukti pembayaran</a>
                                   @else
                                        <div class="text-muted py-5 text-center"><i
                                                  class="fas fa-image fa-2x mb-2"></i><br>Belum ada foto bukti pembayaran.
                                        </div>
                                   @endif
                              </div>
                              <div>
                                   <div class="camera-box">
                                        <video class="camera-video" id="cameraVideo" autoplay playsinline></video>
                                        <canvas class="camera-canvas" id="cameraCanvas"></canvas>
                                        <div class="camera-controls">
                                             <button class="camera-button" type="button" id="startCamera"><i
                                                       class="fas fa-video me-1"></i> Buka Kamera</button>
                                             <button class="camera-button" type="button" id="capturePhoto" disabled><i
                                                       class="fas fa-camera me-1"></i> Ambil Foto</button>
                                             <button class="camera-button secondary" type="button" id="stopCamera"
                                                  disabled><i class="fas fa-stop me-1"></i> Tutup</button>
                                        </div>
                                        <div class="camera-status" id="cameraStatus">Izinkan akses kamera untuk mengambil
                                             foto bukti.</div>
                                   </div>
                                   <form class="mt-2" method="POST"
                                        action="{{ route('super-admin.payments.capture-proof', $payment) }}"
                                        id="captureForm">
                                        @csrf
                                        <input type="hidden" name="image_data" id="imageData">
                                        <button class="camera-button w-100" type="submit" id="savePhoto" disabled><i
                                                  class="fas fa-cloud-arrow-up me-1"></i> Simpan Foto Kamera</button>
                                   </form>
                              </div>
                         </div>
                    </div>
                    <div class="show-card">
                         <h5 class="show-title">Perbarui Status</h5>
                         <form class="inline-form" method="POST"
                              action="{{ route('super-admin.payments.status', $payment) }}">@csrf @method('PATCH')<select
                                   name="status">
                                   @foreach (['pending', 'confirmed', 'cancelled', 'refunded'] as $status)
                                        <option value="{{ $status }}" @selected($payment->status === $status)>
                                             {{ ucfirst($status) }}</option>
                                   @endforeach
                              </select><input class="form-control" name="notes"
                                   placeholder="Catatan status (opsional)"><button class="btn-status" type="submit">Simpan
                                   Status</button></form>
                    </div>
          </div>
     </div>
@endsection

@push('script')
     <script>
          (function() {
               const video = document.getElementById('cameraVideo');
               const canvas = document.getElementById('cameraCanvas');
               const start = document.getElementById('startCamera');
               const capture = document.getElementById('capturePhoto');
               const stop = document.getElementById('stopCamera');
               const save = document.getElementById('savePhoto');
               const imageData = document.getElementById('imageData');
               const status = document.getElementById('cameraStatus');
               let stream = null;

               if (!video) {
                    return;
               }

               start.addEventListener('click', async function() {
                    if (!navigator.mediaDevices?.getUserMedia) {
                         status.textContent = 'Browser ini tidak mendukung akses kamera.';
                         return;
                    }

                    try {
                         stream = await navigator.mediaDevices.getUserMedia({
                              video: {
                                   facingMode: 'environment'
                              },
                              audio: false,
                         });
                         video.srcObject = stream;
                         capture.disabled = false;
                         stop.disabled = false;
                         start.disabled = true;
                         status.textContent =
                              'Kamera aktif. Arahkan kamera ke bukti pembayaran atau orang yang menyerahkan pembayaran.';
                    } catch (error) {
                         status.textContent =
                              'Akses kamera ditolak atau tidak tersedia. Periksa izin browser/perangkat.';
                    }
               });

               capture.addEventListener('click', function() {
                    if (!stream || !video.videoWidth) {
                         status.textContent = 'Kamera belum siap.';
                         return;
                    }

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    imageData.value = canvas.toDataURL('image/jpeg', 0.88);
                    video.style.display = 'none';
                    canvas.style.display = 'block';
                    save.disabled = false;
                    status.textContent = 'Foto berhasil diambil. Klik Simpan Foto Kamera untuk menyimpannya.';
               });

               stop.addEventListener('click', function() {
                    if (stream) {
                         stream.getTracks().forEach(track => track.stop());
                         stream = null;
                    }
                    video.srcObject = null;
                    video.style.display = 'block';
                    canvas.style.display = 'none';
                    capture.disabled = true;
                    stop.disabled = true;
                    start.disabled = false;
                    status.textContent = 'Kamera ditutup.';
               });

               window.addEventListener('beforeunload', function() {
                    if (stream) {
                         stream.getTracks().forEach(track => track.stop());
                    }
               });
          }());
     </script>
@endpush
