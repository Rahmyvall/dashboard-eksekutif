@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

@section('content')
     @php
          $filters = $filters ?? [];
          $isMineView = (bool) ($isMineView ?? false);
          $statuses = $statuses ?? [];
          $leaveTypes = $leaveTypes ?? collect();
          $employees = $employees ?? collect();
          $canCreate = \Illuminate\Support\Facades\Route::has('leave-requests.create');
          $canUseAdminActions = !$isMineView;

          $totalRows = method_exists($leaveRequests, 'total')
              ? (int) $leaveRequests->total()
              : (int) $leaveRequests->count();
          $onPageRows = (int) $leaveRequests->count();

          $pendingCount = collect($leaveRequests)
              ->filter(fn($item): bool => (string) ($item->status ?? '') === \App\Models\LeaveRequest::STATUS_PENDING)
              ->count();

          $approvedCount = collect($leaveRequests)
              ->filter(fn($item): bool => (string) ($item->status ?? '') === \App\Models\LeaveRequest::STATUS_APPROVED)
              ->count();

          $statusClass = static function (?string $value): string {
              return match (strtolower(trim((string) $value))) {
                  \App\Models\LeaveRequest::STATUS_PENDING => 'lr-pill is-pending',
                  \App\Models\LeaveRequest::STATUS_APPROVED => 'lr-pill is-approved',
                  \App\Models\LeaveRequest::STATUS_REJECTED => 'lr-pill is-rejected',
                  default => 'lr-pill is-default',
              };
          };
     @endphp

     <style>
          @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          :root {
               --lr-bg: #f4f8ff;
               --lr-panel: rgba(255, 255, 255, 0.94);
               --lr-border: rgba(148, 163, 184, 0.22);
               --lr-text: #15243d;
               --lr-muted: #64748b;
               --lr-primary: #0f766e;
               --lr-primary-2: #2563eb;
               --lr-primary-soft: #e0f2fe;
               --lr-success: #0f766e;
               --lr-warning: #d97706;
               --lr-danger: #dc2626;
               --lr-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
          }

          .lr-page,
          .lr-page * {
               box-sizing: border-box;
          }

          .lr-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 42px;
               color: var(--lr-text);
               font-family: 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 6% 8%, rgba(14, 165, 233, 0.12), transparent 22%),
                    radial-gradient(circle at 94% 12%, rgba(16, 185, 129, 0.12), transparent 24%),
                    linear-gradient(145deg, #f9fcff 0%, #f3f8ff 47%, #f0fdf9 100%);
          }

          .lr-wrap {
               max-width: 1600px;
               margin: 0 auto;
          }

          .lr-hero {
               position: relative;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, 0.72);
               border-radius: 30px;
               padding: 30px 30px 26px;
               margin-bottom: 18px;
               color: #fff;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, 0.25), transparent 20%),
                    radial-gradient(circle at 16% 100%, rgba(16, 185, 129, 0.18), transparent 28%),
                    linear-gradient(128deg, #0f766e 0%, #0369a1 54%, #2563eb 100%);
               box-shadow: 0 26px 60px rgba(3, 105, 161, 0.22);
          }

          .lr-hero::before {
               position: absolute;
               top: -80px;
               right: 10%;
               width: 240px;
               height: 240px;
               content: '';
               border: 1px solid rgba(255, 255, 255, 0.12);
               border-radius: 50%;
          }

          .lr-hero::after {
               position: absolute;
               right: -26px;
               bottom: -80px;
               width: 210px;
               height: 210px;
               content: '';
               border-radius: 42px;
               background: rgba(255, 255, 255, 0.08);
               transform: rotate(24deg);
          }

          .lr-hero-inner {
               position: relative;
               z-index: 1;
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 20px;
          }

          .lr-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Manrope', sans-serif;
               font-size: clamp(1.4rem, 2vw, 2rem);
               font-weight: 800;
               letter-spacing: -0.04em;
          }

          .lr-hero p {
               margin: 8px 0 0;
               max-width: 760px;
               font-size: 0.9rem;
               color: rgba(255, 255, 255, 0.9);
               line-height: 1.7;
          }

          .lr-btn-main {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 52px;
               height: 52px;
               border-radius: 16px;
               color: #0f172a;
               background: rgba(255, 255, 255, 0.95);
               border: 1px solid rgba(255, 255, 255, 0.5);
               box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
               text-decoration: none;
               font-size: 1.2rem;
               transition: transform 0.2s ease, box-shadow 0.2s ease;
          }

          .lr-btn-main:hover {
               transform: translateY(-1px);
               box-shadow: 0 16px 30px rgba(15, 23, 42, 0.18);
          }

          .lr-cards {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 14px;
               margin-bottom: 16px;
          }

          .lr-card {
               position: relative;
               overflow: hidden;
               padding: 18px 18px 16px;
               border: 1px solid var(--lr-border);
               border-radius: 20px;
               background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.94));
               box-shadow: var(--lr-shadow);
          }

          .lr-card::before {
               position: absolute;
               inset: 0 auto auto 0;
               width: 100%;
               height: 4px;
               content: '';
               background: linear-gradient(90deg, rgba(15, 118, 110, 0.95), rgba(37, 99, 235, 0.82));
          }

          .lr-card .k {
               margin-bottom: 8px;
               font-size: 0.72rem;
               font-weight: 800;
               letter-spacing: 0.11em;
               text-transform: uppercase;
               color: var(--lr-muted);
          }

          .lr-card .v {
               font-size: clamp(1.5rem, 2vw, 2rem);
               line-height: 1;
               font-weight: 800;
               color: var(--lr-text);
               letter-spacing: -0.05em;
          }

          .lr-card .s {
               margin-top: 8px;
               font-size: 0.78rem;
               color: var(--lr-muted);
          }

          .lr-panel {
               border: 1px solid var(--lr-border);
               border-radius: 22px;
               background: var(--lr-panel);
               box-shadow: var(--lr-shadow);
               padding: 18px;
               margin-bottom: 16px;
          }

          .lr-filter {
               position: relative;
               overflow: hidden;
          }

          .lr-filter::before {
               position: absolute;
               inset: 0 0 auto 0;
               height: 4px;
               content: '';
               background: linear-gradient(90deg, rgba(15, 118, 110, 0.8), rgba(37, 99, 235, 0.8));
          }

          .lr-filter .form-control,
          .lr-filter .form-select {
               min-height: 44px;
               border-radius: 12px;
               border: 1px solid rgba(148, 163, 184, 0.25);
               background: rgba(248, 250, 252, 0.82);
               font-size: 0.82rem;
               color: var(--lr-text);
               box-shadow: none;
          }

          .lr-filter .form-control:focus,
          .lr-filter .form-select:focus {
               border-color: rgba(37, 99, 235, 0.6);
               box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
          }

          .lr-filter .btn {
               min-height: 44px;
               border-radius: 12px;
               font-weight: 700;
               border: none;
               background: linear-gradient(135deg, #0f766e, #2563eb);
               color: #fff;
               box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
          }

          .lr-filter .btn:hover {
               filter: brightness(1.03);
          }

          .lr-table {
               margin-bottom: 0;
               border-collapse: separate;
               border-spacing: 0;
          }

          .lr-table thead th {
               padding: 14px 16px;
               font-size: 0.7rem;
               text-transform: uppercase;
               letter-spacing: 0.1em;
               color: var(--lr-muted);
               font-weight: 800;
               border-bottom: 1px solid rgba(226, 232, 240, 0.9);
               background: linear-gradient(180deg, rgba(248, 250, 252, 0.98), rgba(240, 249, 255, 0.96));
               white-space: nowrap;
          }

          .lr-table tbody td {
               padding: 16px;
               vertical-align: middle;
               border-color: rgba(226, 232, 240, 0.9);
               font-size: 0.85rem;
               color: #243449;
               background: rgba(255, 255, 255, 0.5);
          }

          .lr-table tbody tr {
               transition: background 0.2s ease, transform 0.2s ease;
          }

          .lr-table tbody tr:hover {
               background: rgba(239, 246, 255, 0.7);
          }

          .lr-pill {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 100px;
               padding: 6px 12px;
               border-radius: 999px;
               font-size: 0.72rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: 0.05em;
               border: 1px solid transparent;
          }

          .lr-pill.is-pending {
               color: #92400e;
               background: #fffbeb;
               border-color: #fcd34d;
          }

          .lr-pill.is-approved {
               color: #065f46;
               background: #ecfdf5;
               border-color: #86efac;
          }

          .lr-pill.is-rejected {
               color: #9f1239;
               background: #fff1f2;
               border-color: #fda4af;
          }

          .lr-pill.is-default {
               color: #334155;
               background: #f1f5f9;
               border-color: #cbd5e1;
          }

          .table-action-group {
               display: inline-flex;
               align-items: center;
               gap: 6px;
               flex-wrap: wrap;
          }

          .table-action-group .btn {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               width: 34px;
               height: 34px;
               padding: 0;
               border-radius: 10px;
               border: 1px solid transparent;
               transition: all 0.2s ease;
          }

          .table-action-group .btn:hover {
               transform: translateY(-1px);
          }

          .table-action-group .btn-outline-info {
               border-color: rgba(14, 165, 233, 0.2);
               background: rgba(14, 165, 233, 0.08);
               color: #0369a1;
          }

          .table-action-group .btn-outline-success {
               border-color: rgba(16, 185, 129, 0.2);
               background: rgba(16, 185, 129, 0.08);
               color: #047857;
          }

          .table-action-group .btn-outline-warning {
               border-color: rgba(245, 158, 11, 0.2);
               background: rgba(245, 158, 11, 0.08);
               color: #b45309;
          }

          @media (max-width: 1080px) {
               .lr-cards {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 768px) {
               .lr-page {
                    padding-left: 12px;
                    padding-right: 12px;
               }

               .lr-cards {
                    grid-template-columns: 1fr;
               }

               .lr-hero {
                    padding: 22px 18px;
               }

               .lr-hero-inner {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .lr-btn-main {
                    width: 48px;
                    height: 48px;
               }
          }
     </style>

     <div class="lr-page">
          <div class="lr-wrap">
               <section class="lr-hero">
                    <div class="lr-hero-inner">
                         <div>
                              <h1>{{ $isMineView ? 'Pengajuan Cuti Saya' : 'Pengajuan Cuti' }}</h1>
                              <p>Template modern Produktivitas Karyawan dan Transaksi Jasa untuk monitoring pengajuan cuti.
                              </p>
                         </div>

                         @if ($canCreate)
                              <a href="{{ route('leave-requests.create') }}" class="lr-btn-main" title="Tambah Pengajuan"
                                   aria-label="Tambah Pengajuan">
                                   <i class="bi bi-plus-circle"></i>
                              </a>
                         @endif
                    </div>
               </section>

               <section class="lr-cards">
                    <article class="lr-card">
                         <div class="k">Total Data</div>
                         <div class="v">{{ number_format($totalRows) }}</div>
                         <div class="s">Seluruh hasil sesuai filter</div>
                    </article>
                    <article class="lr-card">
                         <div class="k">Data Halaman</div>
                         <div class="v">{{ number_format($onPageRows) }}</div>
                         <div class="s">Data aktif di halaman ini</div>
                    </article>
                    <article class="lr-card">
                         <div class="k">Pending</div>
                         <div class="v">{{ number_format($pendingCount) }}</div>
                         <div class="s">Menunggu keputusan</div>
                    </article>
                    <article class="lr-card">
                         <div class="k">Disetujui</div>
                         <div class="v">{{ number_format($approvedCount) }}</div>
                         <div class="s">Approve pada halaman ini</div>
                    </article>
               </section>

               @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
               @endif

               @if ($errors->any())
                    <div class="alert alert-danger">
                         <ul class="mb-0">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <section class="lr-panel lr-filter">
                    <form method="GET">
                         <div class="row g-2 align-items-end">
                              <div class="col-md-3">
                                   <label class="form-label small text-muted mb-1">Pencarian</label>
                                   <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                        class="form-control" placeholder="Cari nama, jenis, atau alasan...">
                              </div>
                              <div class="col-md-2">
                                   <label class="form-label small text-muted mb-1">Status</label>
                                   <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        @foreach ($statuses as $status)
                                             <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                                  {{ ucfirst($status) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="col-md-2">
                                   <label class="form-label small text-muted mb-1">Jenis Cuti</label>
                                   <select name="leave_type" class="form-select">
                                        <option value="">Semua Jenis</option>
                                        @foreach ($leaveTypes as $type)
                                             <option value="{{ $type }}" @selected(($filters['leave_type'] ?? '') === $type)>
                                                  {{ ucfirst($type) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              @if (!$isMineView)
                                   <div class="col-md-2">
                                        <label class="form-label small text-muted mb-1">Pegawai</label>
                                        <select name="employee_id" class="form-select">
                                             <option value="">Semua Pegawai</option>
                                             @foreach ($employees as $employee)
                                                  <option value="{{ $employee->id }}" @selected((string) ($filters['employee_id'] ?? '') === (string) $employee->id)>
                                                       {{ $employee->full_name }}
                                                  </option>
                                             @endforeach
                                        </select>
                                   </div>
                              @endif
                              <div class="col-md-2">
                                   <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                                   <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                                        class="form-control">
                              </div>
                              <div class="col-md-1">
                                   <button type="submit" class="btn w-100" title="Filter" aria-label="Filter">
                                        <i class="bi bi-funnel"></i>
                                   </button>
                              </div>
                         </div>
                    </form>
               </section>

               <section class="lr-panel p-0">
                    <div class="table-responsive">
                         <table class="table lr-table table-striped mb-0">
                              <thead>
                                   <tr>
                                        <th>Pegawai</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse ($leaveRequests as $leaveRequest)
                                        <tr>
                                             <td>{{ $leaveRequest->employee?->full_name ?? '-' }}</td>
                                             <td>{{ ucfirst((string) $leaveRequest->leave_type) }}</td>
                                             <td>
                                                  {{ optional($leaveRequest->start_date)->format('d M Y') }}
                                                  -
                                                  {{ optional($leaveRequest->end_date)->format('d M Y') }}
                                             </td>
                                             <td>{{ $leaveRequest->total_days }}</td>
                                             <td><span
                                                       class="{{ $statusClass($leaveRequest->status) }}">{{ ucfirst((string) $leaveRequest->status) }}</span>
                                             </td>
                                             <td class="text-nowrap">
                                                  @if ($canUseAdminActions)
                                                       <div class="table-action-group">
                                                            <a href="{{ route('super-admin.leave-requests.show', $leaveRequest) }}"
                                                                 class="btn btn-sm btn-outline-info" title="Lihat Detail"
                                                                 aria-label="Lihat Detail">
                                                                 <i class="bi bi-eye"></i>
                                                            </a>
                                                            @if ($leaveRequest->status === \App\Models\LeaveRequest::STATUS_PENDING)
                                                                 <form action="{{ route('super-admin.leave-requests.approve', $leaveRequest) }}"
                                                                      method="POST" class="d-inline">
                                                                      @csrf
                                                                      @method('PATCH')
                                                                      <button type="submit"
                                                                           class="btn btn-sm btn-outline-success"
                                                                           title="Setujui" aria-label="Setujui">
                                                                           <i class="bi bi-check2-circle"></i>
                                                                      </button>
                                                                 </form>
                                                                 <form action="{{ route('super-admin.leave-requests.reject', $leaveRequest) }}"
                                                                      method="POST" class="d-inline">
                                                                      @csrf
                                                                      @method('PATCH')
                                                                      <button type="submit"
                                                                           class="btn btn-sm btn-outline-warning"
                                                                           title="Tolak" aria-label="Tolak">
                                                                           <i class="bi bi-x-circle"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  @else
                                                       <span class="text-muted">-</span>
                                                  @endif
                                             </td>
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengajuan
                                                  cuti.</td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>
               </section>

               <div class="mt-3">
                    {{ $leaveRequests->links() }}
               </div>
          </div>
     </div>
@endsection
