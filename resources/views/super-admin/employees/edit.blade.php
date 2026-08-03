@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
     <style>
          .employee-edit-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 6% 7%, rgba(99, 102, 241, .18), transparent 24%),
                    radial-gradient(circle at 94% 10%, rgba(6, 182, 212, .16), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 48%, #f0fbff 100%);
          }

          .employee-edit-container {
               max-width: 1550px;
               margin: 0 auto;
          }

          .employee-edit-hero {
               display: flex;
               gap: 22px;
               align-items: center;
               justify-content: space-between;
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 26px;
               background:
                    radial-gradient(circle at 88% 18%, rgba(255, 255, 255, .28), transparent 25%),
                    linear-gradient(120deg, #6366f1, #8b5cf6 48%, #06b6d4);
               box-shadow: 0 22px 48px rgba(99, 102, 241, .22);
          }

          .employee-edit-hero-main {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .employee-edit-hero-icon {
               display: grid;
               flex: 0 0 62px;
               width: 62px;
               height: 62px;
               place-items: center;
               color: #4f46e5;
               font-size: 1.55rem;
               border-radius: 19px;
               background: rgba(255, 255, 255, .95);
          }

          .employee-edit-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 2.6vw, 2.35rem);
               font-weight: 850;
          }

          .employee-edit-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .9);
               font-size: .88rem;
          }

          .employee-edit-back {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               min-height: 46px;
               padding: 0 17px;
               color: #fff;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 14px;
               background: rgba(255, 255, 255, .16);
          }

          .employee-edit-back:hover {
               color: #fff;
               background: rgba(255, 255, 255, .25);
          }

          .employee-edit-card {
               overflow: hidden;
               border: 1px solid #e0e7ff;
               border-radius: 25px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 20px 52px rgba(51, 65, 85, .10);
          }

          .employee-edit-card-header {
               display: flex;
               gap: 14px;
               align-items: center;
               padding: 23px 26px;
               border-bottom: 1px solid #e0e7ff;
               background: linear-gradient(100deg, #eef2ff, #eff6ff, #ecfeff);
          }

          .employee-edit-card-header span {
               display: grid;
               width: 48px;
               height: 48px;
               place-items: center;
               color: #fff;
               border-radius: 15px;
               background: linear-gradient(135deg, #6366f1, #0ea5e9);
          }

          .employee-edit-card-header h4 {
               margin: 0;
               color: #1e293b;
               font-size: 1.05rem;
               font-weight: 850;
          }

          .employee-edit-card-header p {
               margin: 4px 0 0;
               color: #64748b;
               font-size: .8rem;
          }

          .employee-edit-card-body {
               padding: 28px;
          }

          .employee-edit-actions {
               display: flex;
               gap: 12px;
               align-items: center;
               justify-content: space-between;
               padding: 21px 27px;
               border-top: 1px solid #e0e7ff;
               background: linear-gradient(100deg, #fbfdff, #f5f3ff, #ecfeff);
          }

          .employee-edit-note {
               color: #64748b;
               font-size: .78rem;
          }

          .employee-edit-buttons {
               display: flex;
               gap: 11px;
          }

          .employee-edit-cancel,
          .employee-edit-save {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               min-height: 48px;
               padding: 0 21px;
               font-size: .86rem;
               font-weight: 850;
               text-decoration: none;
               border-radius: 14px;
          }

          .employee-edit-cancel {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #fff;
          }

          .employee-edit-save {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
               box-shadow: 0 12px 25px rgba(99, 102, 241, .25);
          }

          @media (max-width: 767.98px) {
               .employee-edit-page {
                    padding: 16px 12px 32px;
               }

               .employee-edit-hero,
               .employee-edit-actions {
                    align-items: stretch;
                    flex-direction: column;
               }

               .employee-edit-back,
               .employee-edit-cancel,
               .employee-edit-save {
                    justify-content: center;
                    width: 100%;
               }

               .employee-edit-card-body {
                    padding: 18px;
               }

               .employee-edit-buttons {
                    flex-direction: column-reverse;
                    width: 100%;
               }
          }
     </style>

     <div class="employee-edit-page">
          <div class="employee-edit-container">
               <div class="employee-edit-hero">
                    <div class="employee-edit-hero-main">
                         <div class="employee-edit-hero-icon">
                              <i class="bi bi-person-gear"></i>
                         </div>

                         <div>
                              <h1>Edit Employee</h1>
                              <p>
                                   {{ $employee->employee_number }}
                                   — {{ $employee->full_name }}
                              </p>
                         </div>
                    </div>

                    <a href="{{ route('super-admin.employees.index') }}" class="employee-edit-back">
                         <i class="bi bi-arrow-left"></i>
                         Kembali ke Daftar
                    </a>
               </div>

               @include('super-admin.employees._alerts')

               <form method="POST" action="{{ route('super-admin.employees.update', $employee) }}"
                    enctype="multipart/form-data" class="employee-edit-card">
                    @csrf
                    @method('PUT')

                    <div class="employee-edit-card-header">
                         <span>
                              <i class="bi bi-ui-checks-grid"></i>
                         </span>

                         <div>
                              <h4>Perbarui Informasi Employee</h4>
                              <p>
                                   Periksa kembali data sebelum menyimpan perubahan.
                              </p>
                         </div>
                    </div>

                    <div class="employee-edit-card-body">
                         @include('super-admin.employees._form', [
                             'employee' => $employee,
                             'departments' => $departments,
                             'positions' => $positions,
                             'users' => $users,
                         ])
                    </div>

                    <div class="employee-edit-actions">
                         <div class="employee-edit-note">
                              <i class="bi bi-shield-check me-1"></i>
                              Perubahan akan tercatat pada waktu pembaruan employee.
                         </div>

                         <div class="employee-edit-buttons">
                              <a href="{{ route('super-admin.employees.show', $employee) }}" class="employee-edit-cancel">
                                   <i class="bi bi-x-lg"></i>
                                   Batal
                              </a>

                              <button type="submit" class="employee-edit-save">
                                   <i class="bi bi-check2-circle"></i>
                                   Simpan Perubahan
                              </button>
                         </div>
                    </div>
               </form>
          </div>
     </div>
@endsection
