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

          .lr-page,
          .lr-page * {
               box-sizing: border-box;
          }

          .lr-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 44px;
               color: #1f2a44;
               font-family: 'Manrope', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 7% 8%, rgba(14, 165, 233, .12), transparent 24%),
                    radial-gradient(circle at 93% 10%, rgba(16, 185, 129, .13), transparent 26%),
                    linear-gradient(155deg, #f7fcff 0%, #eff8ff 52%, #ecfbf7 100%);
          }

          .lr-wrap {
               max-width: 1620px;
               margin: 0 auto;
          }

          .lr-hero {
               position: relative;
               overflow: hidden;
               border: 1px solid rgba(255, 255, 255, .5);
               border-radius: 28px;
               padding: 28px 30px;
               margin-bottom: 18px;
               color: #fff;
               background:
                    radial-gradient(circle at 87% 14%, rgba(255, 255, 255, .22), transparent 22%),
                    linear-gradient(124deg, #0f766e 0%, #0369a1 58%, #2563eb 100%);
               box-shadow: 0 24px 54px rgba(3, 105, 161, .25);
          }

          .lr-hero-inner {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 16px;
          }

          .lr-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Manrope', sans-serif;
               font-size: clamp(1.2rem, 2.1vw, 1.8rem);
               font-weight: 700;
          }

          .lr-hero p {
               margin: 7px 0 0;
               font-size: .87rem;
               color: rgba(255, 255, 255, .92);
          }

          .lr-btn-main {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-height: 44px;
               padding: 0 18px;
               font-size: .84rem;
               font-weight: 800;
               border-radius: 13px;
               color: #0f3c66;
               border: 1px solid rgba(255, 255, 255, .8);
               background: #fff;
               text-decoration: none;
          }

          .lr-cards {
               display: grid;
               grid-template-columns: repeat(4, minmax(0, 1fr));
               gap: 12px;
               margin-bottom: 14px;
          }

          .lr-card {
               border: 1px solid #dbe6f2;
               border-radius: 18px;
               background: linear-gradient(160deg, #fff 0%, #f8fbff 100%);
               box-shadow: 0 10px 26px rgba(15, 23, 42, .08);
               padding: 16px;
          }

          .lr-card .k {
               font-size: .72rem;
               letter-spacing: .08em;
               text-transform: uppercase;
               color: #64748b;
               font-weight: 800;
               margin-bottom: 8px;
          }

          .lr-card .v {
               font-size: 1.7rem;
               line-height: 1;
               font-weight: 800;
               color: #0f172a;
          }

          .lr-card .s {
               margin-top: 6px;
               color: #64748b;
               font-size: .78rem;
          }

          .lr-panel {
               border: 1px solid #dbe6f2;
               border-radius: 20px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
               padding: 16px;
               margin-bottom: 14px;
          }

          .lr-filter .form-control,
          .lr-filter .form-select {
               min-height: 42px;
               border-radius: 11px;
               border: 1px solid #d5e2ef;
               font-size: .83rem;
          }

          .lr-filter .btn {
               min-height: 42px;
               border-radius: 11px;
               font-weight: 700;
          }

          .lr-table {
               margin-bottom: 0;
          }

          .lr-table thead th {
               font-size: .7rem;
               text-transform: uppercase;
               letter-spacing: .08em;
               color: #64748b;
               font-weight: 800;
               border-bottom: 1px solid #e8eef5;
               white-space: nowrap;
               background: #f8fbff;
          }

          .lr-table tbody td {
               vertical-align: middle;
               border-color: #eef3f8;
               font-size: .84rem;
               color: #243449;
          }

          .lr-pill {
               display: inline-flex;
               align-items: center;
               justify-content: center;
               min-width: 90px;
               padding: 5px 10px;
               border-radius: 999px;
               font-size: .72rem;
               font-weight: 800;
               text-transform: uppercase;
               letter-spacing: .04em;
               border: 1px solid transparent;
          }

          .lr-pill.is-pending {
               color: #92400e;
               background: #fffbeb;
               border-color: #fde68a;
          }

          .lr-pill.is-approved {
               color: #065f46;
               background: #ecfdf5;
               border-color: #a7f3d0;
          }

          .lr-pill.is-rejected {
               color: #9f1239;
               background: #fff1f2;
               border-color: #fecdd3;
          }

          .lr-pill.is-default {
               color: #334155;
               background: #f1f5f9;
               border-color: #cbd5e1;
          }

          @media (max-width: 1080px) {
               .lr-cards {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
               }
          }

          @media (max-width: 768px) {
               .lr-cards {
                    grid-template-columns: 1fr;
               }

               .lr-hero-inner {
                    flex-direction: column;
                    align-items: flex-start;
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
                         <div class="row g-2">
                              <div class="col-md-3">
                                   <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                        class="form-control" placeholder="Cari nama, jenis, atau alasan...">
                              </div>
                              <div class="col-md-2">
                                   <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        @foreach ($statuses as $status)
                                             <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                                  {{ ucfirst($status) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="col-md-2">
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
                                   <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                                        class="form-control">
                              </div>
                              <div class="col-md-1">
                                   <button type="submit" class="btn btn-outline-primary w-100" title="Filter"
                                        aria-label="Filter">
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
                                                                      class="btn btn-sm btn-outline-success" title="Setujui"
                                                                      aria-label="Setujui">
                                                                      <i class="bi bi-check2-circle"></i>
                                                                 </button>
                                                            </form>
                                                            <form action="{{ route('super-admin.leave-requests.reject', $leaveRequest) }}"
                                                                 method="POST" class="d-inline">
                                                                 @csrf
                                                                 @method('PATCH')
                                                                 <button type="submit"
                                                                      class="btn btn-sm btn-outline-warning" title="Tolak"
                                                                      aria-label="Tolak">
                                                                      <i class="bi bi-x-circle"></i>
                                                                 </button>
                                                            </form>
                                                       @endif
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
