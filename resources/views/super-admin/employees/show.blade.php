@extends('layouts.app')

@section('title', 'Detail Employee')

@section('content')
     @php
          $employmentLabels = [
              'permanent' => 'Karyawan Tetap',
              'contract' => 'Karyawan Kontrak',
              'probation' => 'Masa Percobaan',
              'internship' => 'Magang',
              'outsourcing' => 'Outsourcing',
          ];

          $genderLabels = [
              'male' => 'Laki-laki',
              'female' => 'Perempuan',
          ];

          $employmentLabel =
              $employmentLabels[$employee->employment_status] ??
              \Illuminate\Support\Str::of((string) $employee->employment_status)->replace('_', ' ')->title();

          $genderLabel =
              $genderLabels[$employee->gender] ??
              \Illuminate\Support\Str::of((string) $employee->gender)->replace('_', ' ')->title();

          $currentUser = auth()->user();
          $canManageEmployees = false;

          if ($currentUser && method_exists($currentUser, 'hasAnyRole')) {
              $canManageEmployees = $currentUser->hasAnyRole(['super_admin', 'hrd_manager']);
          } elseif ($currentUser && method_exists($currentUser, 'hasRole')) {
              $canManageEmployees = $currentUser->hasRole('super_admin') || $currentUser->hasRole('hrd_manager');
          }
     @endphp

     <style>
          .employee-show-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 6% 7%, rgba(99, 102, 241, .18), transparent 24%),
                    radial-gradient(circle at 94% 10%, rgba(6, 182, 212, .16), transparent 25%),
                    linear-gradient(145deg, #fbfdff, #f7f5ff 48%, #f0fbff);
          }

          .employee-show-container {
               max-width: 1480px;
               margin: 0 auto;
          }

          .employee-profile-hero {
               position: relative;
               overflow: hidden;
               padding: 31px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 27px;
               background:
                    radial-gradient(circle at 88% 18%, rgba(255, 255, 255, .3), transparent 25%),
                    linear-gradient(120deg, #6366f1, #8b5cf6 46%, #06b6d4);
               box-shadow: 0 22px 50px rgba(99, 102, 241, .22);
          }

          .employee-profile-main {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 22px;
               align-items: center;
               justify-content: space-between;
          }

          .employee-profile-identity {
               display: flex;
               gap: 18px;
               align-items: center;
          }

          .employee-profile-photo {
               display: grid;
               flex: 0 0 92px;
               width: 92px;
               height: 92px;
               overflow: hidden;
               place-items: center;
               color: #4f46e5;
               font-size: 2.1rem;
               font-weight: 850;
               border: 4px solid rgba(255, 255, 255, .78);
               border-radius: 25px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 15px 32px rgba(76, 29, 149, .19);
          }

          .employee-profile-photo img {
               width: 100%;
               height: 100%;
               object-fit: cover;
          }

          .employee-profile-hero h1 {
               margin: 0;
               font-size: clamp(1.7rem, 2.8vw, 2.45rem);
               font-weight: 850;
          }

          .employee-profile-number {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               padding: 6px 10px;
               margin-top: 9px;
               color: #4338ca;
               font-size: .76rem;
               font-weight: 850;
               border-radius: 9px;
               background: rgba(255, 255, 255, .94);
          }

          .employee-profile-actions {
               display: flex;
               gap: 10px;
               flex-wrap: wrap;
          }

          .employee-profile-btn {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               min-height: 46px;
               padding: 0 17px;
               color: #fff;
               font-size: .83rem;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 14px;
               background: rgba(255, 255, 255, .15);
          }

          .employee-profile-btn-primary {
               color: #4338ca;
               border-color: #fff;
               background: #fff;
          }

          .employee-detail-grid {
               display: grid;
               grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
               gap: 22px;
          }

          .employee-detail-card {
               overflow: hidden;
               border: 1px solid #e0e7ff;
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 44px rgba(51, 65, 85, .09);
          }

          .employee-detail-card-header {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 20px 22px;
               border-bottom: 1px solid #eef2ff;
               background: linear-gradient(100deg, #eef2ff, #eff6ff, #ecfeff);
          }

          .employee-detail-card-header span {
               display: grid;
               width: 42px;
               height: 42px;
               place-items: center;
               color: #fff;
               border-radius: 13px;
               background: linear-gradient(135deg, #6366f1, #0ea5e9);
          }

          .employee-detail-card-header h4 {
               margin: 0;
               color: #1e293b;
               font-size: .98rem;
               font-weight: 850;
          }

          .employee-detail-card-body {
               padding: 22px;
          }

          .employee-data-grid {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 15px;
          }

          .employee-data-item {
               min-width: 0;
               padding: 15px;
               border: 1px solid #e8edf5;
               border-radius: 15px;
               background: #fbfdff;
          }

          .employee-data-item-full {
               grid-column: 1 / -1;
          }

          .employee-data-label {
               display: block;
               margin-bottom: 6px;
               color: #94a3b8;
               font-size: .7rem;
               font-weight: 850;
               letter-spacing: .06em;
               text-transform: uppercase;
          }

          .employee-data-value {
               display: block;
               overflow-wrap: anywhere;
               color: #334155;
               font-size: .87rem;
               font-weight: 760;
               line-height: 1.55;
          }

          .employee-status-badge {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               padding: 7px 11px;
               font-size: .74rem;
               font-weight: 800;
               border-radius: 999px;
          }

          .employee-status-active {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .employee-status-inactive {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .employee-side-stack {
               display: grid;
               gap: 22px;
          }

          .employee-delete-form {
               margin: 0;
          }

          .employee-danger-btn {
               display: inline-flex;
               width: 100%;
               gap: 8px;
               align-items: center;
               justify-content: center;
               min-height: 46px;
               color: #be123c;
               font-size: .82rem;
               font-weight: 850;
               border: 1px solid #fecdd3;
               border-radius: 13px;
               background: #fff1f2;
          }

          @media (max-width: 991.98px) {
               .employee-detail-grid {
                    grid-template-columns: 1fr;
               }
          }

          @media (max-width: 767.98px) {
               .employee-show-page {
                    padding: 16px 12px 32px;
               }

               .employee-profile-main,
               .employee-profile-identity {
                    align-items: flex-start;
                    flex-direction: column;
               }

               .employee-profile-actions,
               .employee-profile-btn {
                    width: 100%;
               }

               .employee-profile-btn {
                    justify-content: center;
               }

               .employee-data-grid {
                    grid-template-columns: 1fr;
               }

               .employee-data-item-full {
                    grid-column: auto;
               }
          }
     </style>

     <div class="employee-show-page">
          <div class="employee-show-container">
               @include('super-admin.employees._alerts')

               <div class="employee-profile-hero">
                    <div class="employee-profile-main">
                         <div class="employee-profile-identity">
                              <div class="employee-profile-photo">
                                   @if ($employee->photo_url)
                                        <img src="{{ $employee->photo_url }}" alt="Foto {{ $employee->full_name }}">
                                   @else
                                        {{ strtoupper(mb_substr($employee->full_name, 0, 1)) }}
                                   @endif
                              </div>

                              <div>
                                   <h1>{{ $employee->full_name }}</h1>

                                   <span class="employee-profile-number">
                                        <i class="bi bi-person-vcard"></i>
                                        {{ $employee->employee_number }}
                                   </span>
                              </div>
                         </div>

                         <div class="employee-profile-actions">
                              <a href="{{ route('super-admin.employees.index') }}" class="employee-profile-btn">
                                   <i class="bi bi-arrow-left"></i>
                                   Kembali
                              </a>

                              @if ($canManageEmployees && Route::has('super-admin.employees.edit'))
                                   <a href="{{ route('super-admin.employees.edit', $employee) }}"
                                        class="employee-profile-btn employee-profile-btn-primary">
                                        <i class="bi bi-pencil-fill"></i>
                                        Edit Employee
                                   </a>
                              @endif
                         </div>
                    </div>
               </div>

               <div class="employee-detail-grid">
                    <div class="employee-detail-card">
                         <div class="employee-detail-card-header">
                              <span>
                                   <i class="bi bi-person-lines-fill"></i>
                              </span>

                              <h4>Informasi Lengkap Employee</h4>
                         </div>

                         <div class="employee-detail-card-body">
                              <div class="employee-data-grid">
                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Departemen
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->department?->name ?? '-' }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Jabatan
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->position?->name ?? '-' }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Jenis Kelamin
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $genderLabel }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Tempat, Tanggal Lahir
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->birth_place ?: '-' }}
                                             @if ($employee->birth_date)
                                                  ,
                                                  {{ $employee->birth_date->format('d M Y') }}
                                             @endif
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Email
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->email ?: '-' }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Telepon
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->phone ?: '-' }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item employee-data-item-full">
                                        <span class="employee-data-label">
                                             Alamat
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->address ?: '-' }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Tanggal Mulai Bekerja
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employee->hire_date ? $employee->hire_date->format('d M Y') : '-' }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Status Kepegawaian
                                        </span>
                                        <span class="employee-data-value">
                                             {{ $employmentLabel }}
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Gaji Pokok
                                        </span>
                                        <span class="employee-data-value">
                                             @if (!is_null($employee->basic_salary))
                                                  Rp
                                                  {{ number_format((float) $employee->basic_salary, 0, ',', '.') }}
                                             @else
                                                  -
                                             @endif
                                        </span>
                                   </div>

                                   <div class="employee-data-item">
                                        <span class="employee-data-label">
                                             Status Data
                                        </span>

                                        @if ($employee->status === 'active')
                                             <span class="employee-status-badge employee-status-active">
                                                  <i class="bi bi-check-circle-fill"></i>
                                                  Aktif
                                             </span>
                                        @else
                                             <span class="employee-status-badge employee-status-inactive">
                                                  <i class="bi bi-x-circle-fill"></i>
                                                  Tidak Aktif
                                             </span>
                                        @endif
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="employee-side-stack">
                         <div class="employee-detail-card">
                              <div class="employee-detail-card-header">
                                   <span>
                                        <i class="bi bi-person-lock"></i>
                                   </span>

                                   <h4>Akun Pengguna</h4>
                              </div>

                              <div class="employee-detail-card-body">
                                   <div class="employee-data-grid">
                                        <div class="employee-data-item employee-data-item-full">
                                             <span class="employee-data-label">Nama Akun</span>
                                             <span class="employee-data-value">
                                                  {{ $employee->user?->name ?? 'Tidak terhubung' }}
                                             </span>
                                        </div>

                                        <div class="employee-data-item employee-data-item-full">
                                             <span class="employee-data-label">Email Akun</span>
                                             <span class="employee-data-value">
                                                  {{ $employee->user?->email ?? '-' }}
                                             </span>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="employee-detail-card">
                              <div class="employee-detail-card-header">
                                   <span>
                                        <i class="bi bi-clock-history"></i>
                                   </span>

                                   <h4>Informasi Sistem</h4>
                              </div>

                              <div class="employee-detail-card-body">
                                   <div class="employee-data-grid">
                                        <div class="employee-data-item employee-data-item-full">
                                             <span class="employee-data-label">Dibuat</span>
                                             <span class="employee-data-value">
                                                  {{ $employee->created_at ? $employee->created_at->format('d M Y H:i') : '-' }}
                                             </span>
                                        </div>

                                        <div class="employee-data-item employee-data-item-full">
                                             <span class="employee-data-label">Diperbarui</span>
                                             <span class="employee-data-value">
                                                  {{ $employee->updated_at ? $employee->updated_at->format('d M Y H:i') : '-' }}
                                             </span>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         @if ($canManageEmployees && Route::has('super-admin.employees.destroy'))
                              <div class="employee-detail-card">
                                   <div class="employee-detail-card-header">
                                        <span>
                                             <i class="bi bi-exclamation-triangle-fill"></i>
                                        </span>

                                        <h4>Penghapusan Data</h4>
                                   </div>

                                   <div class="employee-detail-card-body">
                                        <form method="POST"
                                             action="{{ route('super-admin.employees.destroy', $employee) }}"
                                             class="employee-delete-form"
                                             onsubmit="return confirm('Yakin ingin menghapus employee ini?')">
                                             @csrf
                                             @method('DELETE')

                                             <button type="submit" class="employee-danger-btn">
                                                  <i class="bi bi-trash3-fill"></i>
                                                  Hapus Employee
                                             </button>
                                        </form>
                                   </div>
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
