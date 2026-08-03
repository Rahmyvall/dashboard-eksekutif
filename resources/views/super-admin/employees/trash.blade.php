@extends('layouts.app')

@section('title', 'Employee Terhapus')

@section('content')
     <style>
          .employee-trash-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 6% 7%, rgba(244, 63, 94, .13), transparent 24%),
                    radial-gradient(circle at 94% 10%, rgba(99, 102, 241, .16), transparent 25%),
                    linear-gradient(145deg, #fffdfd, #faf7ff 48%, #f4fbff);
          }

          .employee-trash-container {
               max-width: 1580px;
               margin: 0 auto;
          }

          .employee-trash-hero {
               display: flex;
               gap: 20px;
               align-items: center;
               justify-content: space-between;
               padding: 30px;
               margin-bottom: 22px;
               color: #fff;
               border-radius: 26px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .28), transparent 24%),
                    linear-gradient(120deg, #e11d48, #8b5cf6 52%, #6366f1);
               box-shadow: 0 22px 48px rgba(190, 24, 93, .18);
          }

          .employee-trash-title {
               display: flex;
               gap: 16px;
               align-items: center;
          }

          .employee-trash-icon {
               display: grid;
               flex: 0 0 60px;
               width: 60px;
               height: 60px;
               place-items: center;
               color: #be123c;
               font-size: 1.5rem;
               border-radius: 18px;
               background: rgba(255, 255, 255, .95);
          }

          .employee-trash-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 2.7vw, 2.35rem);
               font-weight: 850;
          }

          .employee-trash-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .9);
               font-size: .86rem;
          }

          .employee-trash-back {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               min-height: 46px;
               padding: 0 17px;
               color: #fff;
               font-size: .83rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .42);
               border-radius: 14px;
               background: rgba(255, 255, 255, .16);
          }

          .employee-trash-filter {
               padding: 19px;
               margin-bottom: 22px;
               border: 1px solid #e0e7ff;
               border-radius: 20px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 15px 35px rgba(51, 65, 85, .08);
          }

          .employee-trash-search {
               position: relative;
          }

          .employee-trash-search i {
               position: absolute;
               top: 50%;
               left: 15px;
               color: #8b5cf6;
               transform: translateY(-50%);
          }

          .employee-trash-search input {
               min-height: 47px;
               padding-left: 43px;
               border: 1px solid #dbe3ef;
               border-radius: 13px;
          }

          .employee-trash-filter-actions {
               display: flex;
               gap: 10px;
          }

          .employee-trash-filter-btn {
               display: inline-flex;
               gap: 8px;
               align-items: center;
               justify-content: center;
               min-height: 47px;
               padding: 0 18px;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
          }

          .employee-trash-filter-submit {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #e11d48, #8b5cf6);
          }

          .employee-trash-filter-reset {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #fff;
          }

          .employee-trash-card {
               overflow: hidden;
               border: 1px solid #e0e7ff;
               border-radius: 24px;
               background: rgba(255, 255, 255, .97);
               box-shadow: 0 18px 44px rgba(51, 65, 85, .09);
          }

          .employee-trash-card-header {
               display: flex;
               gap: 16px;
               align-items: center;
               justify-content: space-between;
               padding: 21px 23px;
               border-bottom: 1px solid #eef2ff;
               background: linear-gradient(100deg, #fff1f2, #f5f3ff, #eff6ff);
          }

          .employee-trash-card-header h4 {
               margin: 0;
               color: #1e293b;
               font-size: 1rem;
               font-weight: 850;
          }

          .employee-trash-count {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               padding: 7px 11px;
               color: #7c3aed;
               font-size: .74rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .employee-trash-card-body {
               padding: 10px 17px 20px;
          }

          .employee-trash-table {
               min-width: 1100px;
               margin-bottom: 0;
          }

          .employee-trash-table thead th {
               padding: 14px 12px;
               color: #52627a;
               font-size: .71rem;
               font-weight: 850;
               letter-spacing: .06em;
               white-space: nowrap;
               text-transform: uppercase;
               border: 0;
               background: #fafbff;
          }

          .employee-trash-table tbody td {
               padding: 16px 12px;
               color: #334155;
               vertical-align: middle;
               border-bottom: 1px solid #edf1f7;
          }

          .employee-trash-person {
               display: flex;
               gap: 11px;
               align-items: center;
               min-width: 240px;
          }

          .employee-trash-avatar {
               display: grid;
               flex: 0 0 44px;
               width: 44px;
               height: 44px;
               overflow: hidden;
               place-items: center;
               color: #fff;
               font-weight: 850;
               border-radius: 13px;
               background: linear-gradient(135deg, #e11d48, #8b5cf6);
          }

          .employee-trash-avatar img {
               width: 100%;
               height: 100%;
               object-fit: cover;
          }

          .employee-trash-name {
               display: block;
               color: #1e293b;
               font-size: .88rem;
               font-weight: 820;
          }

          .employee-trash-number {
               display: block;
               margin-top: 3px;
               color: #94a3b8;
               font-size: .72rem;
          }

          .employee-trash-action-group {
               display: inline-flex;
               gap: 8px;
          }

          .employee-trash-action {
               display: inline-flex;
               gap: 7px;
               align-items: center;
               min-height: 38px;
               padding: 0 12px;
               font-size: .76rem;
               font-weight: 800;
               border-radius: 11px;
          }

          .employee-trash-restore {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .employee-trash-force {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .employee-trash-empty {
               padding: 60px 20px !important;
               color: #64748b;
               text-align: center;
          }

          .employee-trash-pagination {
               display: flex;
               gap: 15px;
               align-items: center;
               justify-content: space-between;
               padding: 18px 4px 0;
          }

          @media (max-width: 767.98px) {
               .employee-trash-page {
                    padding: 16px 12px 32px;
               }

               .employee-trash-hero,
               .employee-trash-card-header,
               .employee-trash-pagination {
                    align-items: stretch;
                    flex-direction: column;
               }

               .employee-trash-back,
               .employee-trash-filter-btn {
                    width: 100%;
                    justify-content: center;
               }

               .employee-trash-filter-actions {
                    flex-direction: column;
               }
          }
     </style>

     @php
          $currentSearch = request('search', $search ?? '');
     @endphp

     <div class="employee-trash-page">
          <div class="employee-trash-container">
               <div class="employee-trash-hero">
                    <div class="employee-trash-title">
                         <div class="employee-trash-icon">
                              <i class="bi bi-trash3-fill"></i>
                         </div>

                         <div>
                              <h1>Employee Terhapus</h1>
                              <p>
                                   Pulihkan data atau hapus secara permanen dari sistem.
                              </p>
                         </div>
                    </div>

                    <a href="{{ route('super-admin.employees.index') }}" class="employee-trash-back">
                         <i class="bi bi-arrow-left"></i>
                         Kembali ke Daftar
                    </a>
               </div>

               @include('super-admin.employees._alerts')

               <div class="employee-trash-filter">
                    <form method="GET" action="{{ route('super-admin.employees.trash') }}">
                         <div class="row g-3 align-items-end">
                              <div class="col-lg-8">
                                   <label for="search" class="form-label fw-semibold small text-secondary">
                                        Cari Employee Terhapus
                                   </label>

                                   <div class="employee-trash-search">
                                        <i class="bi bi-search"></i>

                                        <input type="text" id="search" name="search" class="form-control"
                                             value="{{ $currentSearch }}"
                                             placeholder="Nomor employee, nama, email, atau telepon..." autocomplete="off">
                                   </div>
                              </div>

                              <div class="col-lg-4">
                                   <div class="employee-trash-filter-actions">
                                        <button type="submit"
                                             class="employee-trash-filter-btn employee-trash-filter-submit">
                                             <i class="bi bi-search"></i>
                                             Cari
                                        </button>

                                        <a href="{{ route('super-admin.employees.trash') }}"
                                             class="employee-trash-filter-btn employee-trash-filter-reset">
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                             Reset
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </form>
               </div>

               <div class="employee-trash-card">
                    <div class="employee-trash-card-header">
                         <h4>
                              <i class="bi bi-archive-fill me-2"></i>
                              Daftar Employee di Recycle Bin
                         </h4>

                         <span class="employee-trash-count">
                              <i class="bi bi-database-fill"></i>
                              {{ $employees->total() }} data
                         </span>
                    </div>

                    <div class="employee-trash-card-body">
                         <div class="table-responsive">
                              <table class="table employee-trash-table align-middle">
                                   <thead>
                                        <tr>
                                             <th width="5%">No</th>
                                             <th width="23%">Employee</th>
                                             <th width="18%">Departemen / Jabatan</th>
                                             <th width="19%">Kontak</th>
                                             <th width="15%">Dihapus Pada</th>
                                             <th width="20%" class="text-center">
                                                  Tindakan
                                             </th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse ($employees as $employee)
                                             <tr>
                                                  <td>
                                                       {{ $employees->firstItem() + $loop->index }}
                                                  </td>

                                                  <td>
                                                       <div class="employee-trash-person">
                                                            <div class="employee-trash-avatar">
                                                                 @if ($employee->photo_url)
                                                                      <img src="{{ $employee->photo_url }}"
                                                                           alt="Foto {{ $employee->full_name }}">
                                                                 @else
                                                                      {{ strtoupper(mb_substr($employee->full_name, 0, 1)) }}
                                                                 @endif
                                                            </div>

                                                            <div>
                                                                 <span class="employee-trash-name">
                                                                      {{ $employee->full_name }}
                                                                 </span>

                                                                 <span class="employee-trash-number">
                                                                      {{ $employee->employee_number }}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <strong>
                                                            {{ $employee->department?->name ?? '-' }}
                                                       </strong>

                                                       <div class="small text-secondary mt-1">
                                                            {{ $employee->position?->name ?? '-' }}
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <div>{{ $employee->email ?: '-' }}</div>
                                                       <div class="small text-secondary mt-1">
                                                            {{ $employee->phone ?: '-' }}
                                                       </div>
                                                  </td>

                                                  <td>
                                                       <strong>
                                                            {{ $employee->deleted_at ? $employee->deleted_at->format('d M Y') : '-' }}
                                                       </strong>

                                                       <div class="small text-secondary mt-1">
                                                            {{ $employee->deleted_at ? $employee->deleted_at->format('H:i') : '-' }}
                                                            WIB
                                                       </div>
                                                  </td>

                                                  <td class="text-center">
                                                       <div class="employee-trash-action-group">
                                                            @if (Route::has('super-admin.employees.restore'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.employees.restore', $employee->id) }}"
                                                                      onsubmit="return confirm('Pulihkan employee ini?')">
                                                                      @csrf

                                                                      <button type="submit"
                                                                           class="employee-trash-action employee-trash-restore">
                                                                           <i class="bi bi-arrow-counterclockwise"></i>
                                                                           Pulihkan
                                                                      </button>
                                                                 </form>
                                                            @endif

                                                            @if (Route::has('super-admin.employees.force-delete'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.employees.force-delete', $employee->id) }}"
                                                                      onsubmit="return confirm('Hapus permanen employee ini? Tindakan tidak dapat dibatalkan.')">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="employee-trash-action employee-trash-force">
                                                                           <i class="bi bi-x-octagon-fill"></i>
                                                                           Permanen
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="6" class="employee-trash-empty">
                                                       <i class="bi bi-inbox-fill fs-1 d-block mb-3"></i>

                                                       @if ($currentSearch !== '')
                                                            Tidak ada employee terhapus yang
                                                            sesuai pencarian.
                                                       @else
                                                            Recycle bin employee masih kosong.
                                                       @endif
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>

                         @if ($employees->hasPages())
                              <div class="employee-trash-pagination">
                                   <div class="small text-secondary">
                                        Menampilkan
                                        {{ $employees->firstItem() }}–{{ $employees->lastItem() }}
                                        dari {{ $employees->total() }} data
                                   </div>

                                   {{ $employees->links() }}
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </div>
@endsection
