{{-- resources/views/super-admin/employees/_alerts.blade.php --}}

@once
     <style>
          .employee-alert {
               display: flex;
               gap: 13px;
               align-items: flex-start;
               padding: 16px 18px;
               margin-bottom: 18px;
               border: 1px solid transparent;
               border-radius: 17px;
               box-shadow: 0 10px 25px rgba(15, 23, 42, .07);
          }

          .employee-alert-icon {
               display: grid;
               flex: 0 0 40px;
               width: 40px;
               height: 40px;
               place-items: center;
               color: #fff;
               border-radius: 13px;
          }

          .employee-alert-content {
               flex: 1;
               min-width: 0;
          }

          .employee-alert-title {
               display: block;
               margin-bottom: 3px;
               font-size: .9rem;
               font-weight: 850;
          }

          .employee-alert-message,
          .employee-alert-list {
               margin: 0;
               font-size: .82rem;
               line-height: 1.6;
          }

          .employee-alert-list {
               padding-left: 18px;
          }

          .employee-alert-success {
               color: #047857;
               border-color: #a7f3d0;
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .employee-alert-success .employee-alert-icon {
               background: linear-gradient(135deg, #10b981, #059669);
          }

          .employee-alert-error {
               color: #b91c1c;
               border-color: #fecaca;
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .employee-alert-error .employee-alert-icon {
               background: linear-gradient(135deg, #f87171, #dc2626);
          }

          .employee-alert-warning {
               color: #92400e;
               border-color: #fde68a;
               background: linear-gradient(135deg, #fffbeb, #fef3c7);
          }

          .employee-alert-warning .employee-alert-icon {
               background: linear-gradient(135deg, #f59e0b, #d97706);
          }

          .employee-alert-info {
               color: #075985;
               border-color: #bae6fd;
               background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
          }

          .employee-alert-info .employee-alert-icon {
               background: linear-gradient(135deg, #0ea5e9, #0284c7);
          }
     </style>
@endonce

@if (session('success'))
     <div class="employee-alert employee-alert-success" role="alert">
          <div class="employee-alert-icon">
               <i class="bi bi-check-circle-fill"></i>
          </div>

          <div class="employee-alert-content">
               <span class="employee-alert-title">Berhasil</span>
               <p class="employee-alert-message">{{ session('success') }}</p>
          </div>
     </div>
@endif

@if (session('error'))
     <div class="employee-alert employee-alert-error" role="alert">
          <div class="employee-alert-icon">
               <i class="bi bi-exclamation-triangle-fill"></i>
          </div>

          <div class="employee-alert-content">
               <span class="employee-alert-title">Terjadi Kesalahan</span>
               <p class="employee-alert-message">{{ session('error') }}</p>
          </div>
     </div>
@endif

@if (session('warning'))
     <div class="employee-alert employee-alert-warning" role="alert">
          <div class="employee-alert-icon">
               <i class="bi bi-exclamation-circle-fill"></i>
          </div>

          <div class="employee-alert-content">
               <span class="employee-alert-title">Perhatian</span>
               <p class="employee-alert-message">{{ session('warning') }}</p>
          </div>
     </div>
@endif

@if (session('info'))
     <div class="employee-alert employee-alert-info" role="alert">
          <div class="employee-alert-icon">
               <i class="bi bi-info-circle-fill"></i>
          </div>

          <div class="employee-alert-content">
               <span class="employee-alert-title">Informasi</span>
               <p class="employee-alert-message">{{ session('info') }}</p>
          </div>
     </div>
@endif

@if ($errors->any())
     <div class="employee-alert employee-alert-error" role="alert">
          <div class="employee-alert-icon">
               <i class="bi bi-x-octagon-fill"></i>
          </div>

          <div class="employee-alert-content">
               <span class="employee-alert-title">
                    Data belum dapat diproses
               </span>

               <ul class="employee-alert-list">
                    @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
               </ul>
          </div>
     </div>
@endif
