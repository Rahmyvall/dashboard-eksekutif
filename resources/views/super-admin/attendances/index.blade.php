@extends('layouts.app')

@section('title', 'Dashboard Monitoring Produktivitas Karyawan - Kehadiran')

@section('content')
     @php
          $authUser = auth()->user();

          $hasRole = static function (string $role) use ($authUser): bool {
              return $authUser && method_exists($authUser, 'hasRole') && $authUser->hasRole($role);
          };

          $isSuperAdmin = $hasRole('super_admin');
          $isOperasional = $hasRole('admin_operasional');
          $isManager = $hasRole('manager_departemen');
          $isAuditor = $hasRole('auditor_internal');
          $isKaryawan = $hasRole('karyawan');

          $isMineView = request()->routeIs('attendances.mine', 'attendances.my');
          $canManageAttendances = $isSuperAdmin || $isOperasional || $isManager || $isAuditor;
          $canAccessOperationalLinks = $canManageAttendances;

          $routeHas = static fn(string $routeName): bool => \Illuminate\Support\Facades\Route::has($routeName);

          $filters = $filters ?? [];
          $search = trim((string) ($filters['search'] ?? request('search', '')));
          $status = trim((string) ($filters['status'] ?? request('status', '')));
          $employeeId = trim((string) ($filters['employee_id'] ?? request('employee_id', '')));
          $startDate = trim((string) ($filters['start_date'] ?? request('start_date', '')));
          $endDate = trim((string) ($filters['end_date'] ?? request('end_date', '')));
          $perPage = trim((string) ($filters['per_page'] ?? request('per_page', '10')));

          $normalizeStatus = static function (?string $value): string {
              return strtolower(trim((string) $value));
          };

          $hasActiveFilters =
              $search !== '' ||
              $status !== '' ||
              (!$isMineView && $employeeId !== '') ||
              $startDate !== '' ||
              $endDate !== '';

          $filteredTotal = method_exists($attendances, 'total') ? $attendances->total() : $attendances->count();
          $currentPageCount = $attendances->count();
          $currentCollection = method_exists($attendances, 'getCollection')
              ? $attendances->getCollection()
              : collect($attendances);

          $presentOnPage = $currentCollection
              ->filter(
                  fn($item): bool => $normalizeStatus((string) ($item->status ?? '')) ===
                      \App\Models\Attendance::STATUS_PRESENT,
              )
              ->count();
          $lateOnPage = $currentCollection
              ->filter(
                  fn($item): bool => $normalizeStatus((string) ($item->status ?? '')) ===
                      \App\Models\Attendance::STATUS_LATE,
              )
              ->count();
          $absentOnPage = $currentCollection
              ->filter(
                  fn($item): bool => $normalizeStatus((string) ($item->status ?? '')) ===
                      \App\Models\Attendance::STATUS_ABSENT,
              )
              ->count();
          $totalDurationMinutes = (int) $currentCollection->sum('work_duration_minutes');
          $averageDurationMinutes = $currentPageCount > 0 ? (int) round($totalDurationMinutes / $currentPageCount) : 0;

          $formatMinutes = static function (int $minutes): string {
              $minutes = max(0, $minutes);
              $hours = intdiv($minutes, 60);
              $remain = $minutes % 60;

              return sprintf('%02d:%02d', $hours, $remain);
          };

          $statusLabel = static function (?string $value) use ($normalizeStatus): string {
              return match ($normalizeStatus($value)) {
                  \App\Models\Attendance::STATUS_PRESENT => 'Hadir',
                  \App\Models\Attendance::STATUS_LATE => 'Terlambat',
                  \App\Models\Attendance::STATUS_ABSENT => 'Tidak Hadir',
                  \App\Models\Attendance::STATUS_SICK => 'Sakit',
                  \App\Models\Attendance::STATUS_LEAVE => 'Cuti',
                  \App\Models\Attendance::STATUS_HOLIDAY => 'Libur',
                  default => 'Tidak Diketahui',
              };
          };

          $statusClassName = static function (?string $value) use ($normalizeStatus): string {
              return match ($normalizeStatus($value)) {
                  \App\Models\Attendance::STATUS_PRESENT => 'is-present',
                  \App\Models\Attendance::STATUS_LATE => 'is-late',
                  \App\Models\Attendance::STATUS_ABSENT => 'is-absent',
                  \App\Models\Attendance::STATUS_SICK => 'is-sick',
                  \App\Models\Attendance::STATUS_LEAVE => 'is-leave',
                  \App\Models\Attendance::STATUS_HOLIDAY => 'is-holiday',
                  default => 'is-unknown',
              };
          };
     @endphp

     <style>
          :root {
               --attendance-primary: #4f46e5;
               --attendance-secondary: #06b6d4;
               --attendance-purple: #8b5cf6;
               --attendance-success: #10b981;
               --attendance-warning: #f59e0b;
               --attendance-danger: #ef4444;
               --attendance-text: #24324a;
               --attendance-muted: #718096;
               --attendance-border: #e5eaf2;
          }

          .attendance-page,
          .attendance-page * {
               box-sizing: border-box;
          }

          .attendance-page {
               position: relative;
               min-height: calc(100vh - 70px);
               padding: 30px 18px 46px;
               overflow: hidden;
               background:
                    radial-gradient(circle at 3% 4%, rgba(99, 102, 241, .18), transparent 24%),
                    radial-gradient(circle at 97% 9%, rgba(6, 182, 212, .18), transparent 25%),
                    radial-gradient(circle at 88% 94%, rgba(236, 72, 153, .12), transparent 22%),
                    linear-gradient(145deg, #fbfdff 0%, #f8f7ff 48%, #f1fbff 100%);
          }

          .attendance-container {
               position: relative;
               z-index: 1;
               max-width: 1600px;
               margin: 0 auto;
          }

          .attendance-hero {
               position: relative;
               padding: 34px;
               margin-bottom: 22px;
               overflow: hidden;
               color: #ffffff;
               border: 1px solid rgba(255, 255, 255, .68);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 87% 14%, rgba(255, 255, 255, .32), transparent 22%),
                    linear-gradient(120deg, #4f46e5 0%, #7c3aed 44%, #0891b2 100%);
               box-shadow: 0 24px 54px rgba(79, 70, 229, .22);
          }

          .attendance-hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .attendance-hero-heading {
               display: flex;
               min-width: 0;
               gap: 18px;
               align-items: center;
          }

          .attendance-hero-icon {
               display: inline-flex;
               width: 68px;
               height: 68px;
               color: var(--attendance-primary);
               align-items: center;
               justify-content: center;
               border-radius: 21px;
               background: rgba(255, 255, 255, .96);
               box-shadow: 0 14px 30px rgba(49, 46, 129, .18);
          }

          .attendance-hero-icon svg {
               width: 30px;
               height: 30px;
          }

          .attendance-hero h1 {
               margin: 0;
               font-size: clamp(1.75rem, 2.5vw, 2.45rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .attendance-hero p {
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .96rem;
               line-height: 1.7;
          }

          .attendance-hero-actions {
               display: flex;
               gap: 10px;
               flex-wrap: wrap;
               justify-content: flex-end;
          }

          .attendance-hero-button {
               display: inline-flex;
               min-height: 46px;
               padding: 11px 17px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
               border: 1px solid rgba(255, 255, 255, .82);
               background: rgba(255, 255, 255, .97);
          }

          .attendance-hero-button.is-soft {
               color: #fff;
               border-color: rgba(255, 255, 255, .38);
               background: rgba(255, 255, 255, .15);
          }

          .attendance-hero-button svg {
               width: 16px;
               height: 16px;
          }

          .attendance-alert {
               display: flex;
               gap: 12px;
               align-items: center;
               padding: 14px 16px;
               margin-bottom: 16px;
               border-radius: 14px;
               font-weight: 700;
          }

          .attendance-alert-success {
               color: #047857;
               border-left: 5px solid var(--attendance-success);
               background: linear-gradient(135deg, #ecfdf5, #d1fae5);
          }

          .attendance-alert-danger {
               color: #b91c1c;
               border-left: 5px solid var(--attendance-danger);
               background: linear-gradient(135deg, #fff1f2, #fee2e2);
          }

          .attendance-stats-row {
               margin-bottom: 22px;
          }

          .attendance-stat-card {
               min-height: 136px;
               padding: 20px;
               border-radius: 21px;
               border: 1px solid rgba(255, 255, 255, .96);
               box-shadow: 0 14px 32px rgba(51, 65, 85, .09);
          }

          .attendance-stat-total {
               color: #4338ca;
               background: linear-gradient(135deg, #eef2ff, #e0e7ff);
          }

          .attendance-stat-present {
               color: #047857;
               background: linear-gradient(135deg, #ecfdf5, #ccfbf1);
          }

          .attendance-stat-late {
               color: #a16207;
               background: linear-gradient(135deg, #fffbeb, #fef3c7);
          }

          .attendance-stat-duration {
               color: #0369a1;
               background: linear-gradient(135deg, #eff6ff, #cffafe);
          }

          .attendance-stat-title {
               margin-bottom: 8px;
               font-size: .72rem;
               font-weight: 850;
               letter-spacing: .08em;
               text-transform: uppercase;
               opacity: .78;
          }

          .attendance-stat-value {
               font-size: 2.1rem;
               font-weight: 850;
               line-height: 1;
               letter-spacing: -.04em;
          }

          .attendance-stat-caption {
               margin-top: 8px;
               font-size: .78rem;
               font-weight: 650;
               opacity: .72;
          }

          .attendance-filter-card,
          .attendance-table-card {
               padding: 22px;
               margin-bottom: 22px;
               border: 1px solid rgba(226, 232, 240, .92);
               border-radius: 22px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 15px 38px rgba(51, 65, 85, .075);
          }

          .attendance-filter-card {
               margin-top: 10px;
          }

          .attendance-filter-heading,
          .attendance-table-heading {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               margin-bottom: 16px;
          }

          .attendance-heading-title {
               display: flex;
               gap: 10px;
               align-items: center;
               color: var(--attendance-text);
               font-size: .95rem;
               font-weight: 830;
          }

          .attendance-heading-title i {
               display: inline-flex;
               width: 36px;
               height: 36px;
               align-items: center;
               justify-content: center;
               border-radius: 11px;
               color: var(--attendance-primary);
               background: #eef2ff;
          }

          .attendance-heading-title i svg {
               width: 16px;
               height: 16px;
          }

          .attendance-filter-control {
               min-height: 46px;
               border-radius: 13px;
               border: 1px solid #dbe3ef;
               font-size: .87rem;
          }

          .attendance-filter-actions {
               display: flex;
               gap: 10px;
               align-items: flex-end;
               height: 100%;
          }

          .attendance-button-primary,
          .attendance-button-reset {
               display: inline-flex;
               min-height: 46px;
               padding: 0 16px;
               gap: 8px;
               align-items: center;
               justify-content: center;
               font-size: .84rem;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
          }

          .attendance-button-primary {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #6366f1, #8b5cf6, #22d3ee);
          }

          .attendance-button-reset {
               color: #64748b;
               border: 1px solid #dbe3ef;
               background: #fff;
          }

          .attendance-active-filters {
               display: flex;
               flex-wrap: wrap;
               gap: 8px;
               padding-top: 14px;
               margin-top: 14px;
               border-top: 1px dashed #dbe3ef;
          }

          .attendance-filter-chip {
               display: inline-flex;
               min-height: 30px;
               padding: 5px 10px;
               gap: 6px;
               align-items: center;
               color: #5b21b6;
               font-size: .75rem;
               font-weight: 800;
               border: 1px solid #ddd6fe;
               border-radius: 999px;
               background: #f5f3ff;
          }

          .attendance-filter-chip svg {
               width: 13px;
               height: 13px;
          }

          .attendance-table {
               min-width: 1260px;
               margin-bottom: 0;
          }

          .attendance-table thead th {
               padding: 14px 12px;
               font-size: .69rem;
               font-weight: 840;
               text-transform: uppercase;
               color: #52627a;
               background: #fbfcff;
               border-bottom: 1px solid #e8edf4;
               white-space: nowrap;
          }

          .attendance-table tbody td {
               padding: 15px 12px;
               font-size: .83rem;
               color: #41506a;
               border-color: #eef2f7;
               vertical-align: middle;
          }

          .attendance-table tbody tr:hover {
               background: linear-gradient(90deg, #fbfdff, #faf8ff);
          }

          .attendance-status {
               display: inline-flex;
               min-width: 104px;
               padding: 7px 10px;
               justify-content: center;
               border-radius: 999px;
               font-size: .72rem;
               font-weight: 840;
          }

          .attendance-status.is-present {
               color: #047857;
               border: 1px solid #a7f3d0;
               background: #ecfdf5;
          }

          .attendance-status.is-late {
               color: #92400e;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .attendance-status.is-absent {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .attendance-status.is-sick {
               color: #1e3a8a;
               border: 1px solid #bfdbfe;
               background: #eff6ff;
          }

          .attendance-status.is-leave {
               color: #6d28d9;
               border: 1px solid #ddd6fe;
               background: #f5f3ff;
          }

          .attendance-status.is-holiday {
               color: #0f766e;
               border: 1px solid #99f6e4;
               background: #ecfeff;
          }

          .attendance-status.is-unknown {
               color: #475569;
               border: 1px solid #cbd5e1;
               background: #f8fafc;
          }

          .attendance-actions {
               display: flex;
               min-width: 130px;
               gap: 6px;
               justify-content: flex-end;
          }

          .attendance-action-button {
               display: inline-flex;
               width: 36px;
               height: 36px;
               align-items: center;
               justify-content: center;
               border-radius: 11px;
               text-decoration: none;
          }

          .attendance-action-button svg {
               width: 15px;
               height: 15px;
          }

          .attendance-action-show {
               color: #0369a1;
               border: 1px solid #bae6fd;
               background: #f0f9ff;
          }

          .attendance-action-edit {
               color: #a16207;
               border: 1px solid #fde68a;
               background: #fffbeb;
          }

          .attendance-action-delete {
               color: #be123c;
               border: 1px solid #fecdd3;
               background: #fff1f2;
          }

          .attendance-pagination-wrap {
               display: flex;
               flex-wrap: wrap;
               padding-top: 16px;
               margin-top: 16px;
               justify-content: space-between;
               align-items: center;
               gap: 14px;
               border-top: 1px solid #eef2f7;
          }

          .attendance-pagination-info {
               color: var(--attendance-muted);
               font-size: .78rem;
               font-weight: 650;
          }

          @media (max-width: 991.98px) {
               .attendance-hero-content {
                    flex-direction: column;
                    align-items: flex-start;
               }

               .attendance-hero-actions {
                    width: 100%;
               }
          }

          @media (max-width: 767.98px) {
               .attendance-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
               }
          }
     </style>

     <div class="attendance-page">
          <div class="attendance-container">
               <section class="attendance-hero">
                    <div class="attendance-hero-content">
                         <div class="attendance-hero-heading">
                              <span class="attendance-hero-icon"><i data-feather="calendar"></i></span>
                              <div>
                                   <h1>Dashboard Monitoring Produktivitas Karyawan</h1>
                                   <p>
                                        {{ $isMineView ? 'Pantau ringkasan kehadiran pribadi Anda secara terstruktur.' : 'Pantau kehadiran tim dari jam masuk, keterlambatan, lembur, dan status harian dalam satu dashboard modern.' }}
                                   </p>
                              </div>
                         </div>

                         <div class="attendance-hero-actions">
                              @if ($isKaryawan && $routeHas('attendances.mine'))
                                   <a href="{{ route('attendances.mine') }}" class="attendance-hero-button is-soft">
                                        <i data-feather="user-check"></i>
                                        <span>Kehadiran Saya</span>
                                   </a>
                              @endif

                              @if ($canAccessOperationalLinks && $routeHas('super-admin.employee-activities.index'))
                                   <a href="{{ route('super-admin.employee-activities.index') }}"
                                        class="attendance-hero-button is-soft">
                                        <i data-feather="activity"></i>
                                        <span>Aktivitas</span>
                                   </a>
                              @endif

                              @if ($canAccessOperationalLinks && $routeHas('super-admin.work-schedules.index'))
                                   <a href="{{ route('super-admin.work-schedules.index') }}"
                                        class="attendance-hero-button is-soft">
                                        <i data-feather="clock"></i>
                                        <span>Jadwal</span>
                                   </a>
                              @endif

                              @if ($canManageAttendances && $routeHas('super-admin.attendances.create'))
                                   <a href="{{ route('super-admin.attendances.create') }}" class="attendance-hero-button">
                                        <i data-feather="plus-circle"></i>
                                        <span>Tambah Kehadiran</span>
                                   </a>
                              @endif
                         </div>
                    </div>
               </section>

               @if (session('success'))
                    <div class="attendance-alert attendance-alert-success">
                         <i data-feather="check-circle"></i>
                         <span>{{ session('success') }}</span>
                    </div>
               @endif

               @if (session('error'))
                    <div class="attendance-alert attendance-alert-danger">
                         <i data-feather="alert-circle"></i>
                         <span>{{ session('error') }}</span>
                    </div>
               @endif

               <div class="row g-3 attendance-stats-row">
                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="attendance-stat-card attendance-stat-total">
                              <div class="attendance-stat-title">Total Data Kehadiran</div>
                              <div class="attendance-stat-value">{{ number_format((int) $filteredTotal) }}</div>
                              <div class="attendance-stat-caption">{{ number_format((int) $currentPageCount) }} data pada
                                   halaman ini</div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="attendance-stat-card attendance-stat-present">
                              <div class="attendance-stat-title">Status Hadir</div>
                              <div class="attendance-stat-value">{{ number_format((int) $presentOnPage) }}</div>
                              <div class="attendance-stat-caption">rekap hadir per halaman aktif</div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="attendance-stat-card attendance-stat-late">
                              <div class="attendance-stat-title">Status Terlambat</div>
                              <div class="attendance-stat-value">{{ number_format((int) $lateOnPage) }}</div>
                              <div class="attendance-stat-caption">{{ number_format((int) $absentOnPage) }} tidak hadir
                              </div>
                         </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                         <article class="attendance-stat-card attendance-stat-duration">
                              <div class="attendance-stat-title">Rata-rata Durasi</div>
                              <div class="attendance-stat-value">{{ $formatMinutes($averageDurationMinutes) }}</div>
                              <div class="attendance-stat-caption">total {{ $formatMinutes($totalDurationMinutes) }} pada
                                   halaman ini</div>
                         </article>
                    </div>
               </div>

               <section class="attendance-filter-card">
                    <div class="attendance-filter-heading">
                         <div class="attendance-heading-title">
                              <i data-feather="filter"></i>
                              <span>Filter Data Kehadiran</span>
                         </div>
                    </div>

                    <form method="GET"
                         action="{{ $isMineView ? route(request()->route()->getName()) : route('super-admin.attendances.index') }}">
                         <div class="row g-3">
                              <div class="col-12 col-xl-4">
                                   <label class="form-label">Pencarian</label>
                                   <input type="search" name="search" value="{{ $search }}"
                                        class="form-control attendance-filter-control"
                                        placeholder="Nama pegawai, NIK, atau catatan..." autocomplete="off">
                              </div>

                              @if (!$isMineView)
                                   <div class="col-12 col-md-6 col-xl-2">
                                        <label class="form-label">Pegawai</label>
                                        <select name="employee_id" class="form-select attendance-filter-control">
                                             <option value="">Semua Pegawai</option>
                                             @foreach ($employees as $employee)
                                                  <option value="{{ $employee->id }}" @selected($employeeId === (string) $employee->id)>
                                                       {{ $employee->full_name }}
                                                  </option>
                                             @endforeach
                                        </select>
                                   </div>
                              @endif

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label class="form-label">Status</label>
                                   <select name="status" class="form-select attendance-filter-control">
                                        <option value="">Semua Status</option>
                                        @foreach ($statuses as $statusOption)
                                             <option value="{{ $statusOption }}" @selected($status === (string) $statusOption)>
                                                  {{ $statusLabel($statusOption) }}
                                             </option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label class="form-label">Tanggal Mulai</label>
                                   <input type="date" name="start_date" value="{{ $startDate }}"
                                        class="form-control attendance-filter-control">
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label class="form-label">Tanggal Akhir</label>
                                   <input type="date" name="end_date" value="{{ $endDate }}"
                                        class="form-control attendance-filter-control">
                              </div>

                              <div class="col-12 col-md-6 col-xl-2">
                                   <label class="form-label">Baris / Halaman</label>
                                   <select name="per_page" class="form-select attendance-filter-control">
                                        @foreach (['10', '25', '50', '100'] as $size)
                                             <option value="{{ $size }}" @selected($perPage === $size)>
                                                  {{ $size }}</option>
                                        @endforeach
                                   </select>
                              </div>

                              <div class="col-12 col-xl-3">
                                   <div class="attendance-filter-actions">
                                        <button type="submit" class="attendance-button-primary">
                                             <i data-feather="search"></i>
                                             <span>Terapkan Filter</span>
                                        </button>

                                        <a href="{{ $isMineView ? route(request()->route()->getName()) : route('super-admin.attendances.index') }}"
                                             class="attendance-button-reset">
                                             <i data-feather="rotate-ccw"></i>
                                             <span>Reset</span>
                                        </a>
                                   </div>
                              </div>
                         </div>

                         @if ($hasActiveFilters)
                              <div class="attendance-active-filters">
                                   @if ($search !== '')
                                        <span class="attendance-filter-chip"><i data-feather="search"></i>
                                             {{ $search }}</span>
                                   @endif

                                   @if (!$isMineView && $employeeId !== '')
                                        @php
                                             $employeeName = optional($employees->firstWhere('id', (int) $employeeId))
                                                 ->full_name;
                                        @endphp
                                        <span class="attendance-filter-chip"><i data-feather="user"></i>
                                             {{ $employeeName ?: 'Pegawai dipilih' }}</span>
                                   @endif

                                   @if ($status !== '')
                                        <span class="attendance-filter-chip"><i data-feather="activity"></i>
                                             {{ $statusLabel($status) }}</span>
                                   @endif
                              </div>
                         @endif
                    </form>
               </section>

               <section class="attendance-table-card">
                    <div class="attendance-table-heading">
                         <div class="attendance-heading-title">
                              <i data-feather="list"></i>
                              <span>{{ $isMineView ? 'Riwayat Kehadiran Saya' : 'Daftar Kehadiran Pegawai' }}</span>
                         </div>

                         <span class="attendance-filter-chip">
                              <i data-feather="layers"></i>
                              {{ number_format((int) $currentPageCount) }} data
                         </span>
                    </div>

                    <div class="table-responsive">
                         <table class="table attendance-table align-middle">
                              <thead>
                                   <tr>
                                        <th style="width: 72px;">No.</th>
                                        <th>Pegawai</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Status</th>
                                        <th>Durasi</th>
                                        <th>Terlambat</th>
                                        <th>Lembur</th>
                                        <th>Catatan</th>
                                        @if ($canManageAttendances)
                                             <th class="text-end">Aksi</th>
                                        @endif
                                   </tr>
                              </thead>

                              <tbody>
                                   @forelse ($attendances as $attendance)
                                        @php
                                             $name = trim((string) ($attendance->employee?->full_name ?? '-'));
                                             $initials = \Illuminate\Support\Str::of($name)
                                                 ->explode(' ')
                                                 ->filter()
                                                 ->take(2)
                                                 ->map(
                                                     fn($word): string => \Illuminate\Support\Str::upper(
                                                         \Illuminate\Support\Str::substr((string) $word, 0, 1),
                                                     ),
                                                 )
                                                 ->implode('');

                                             $statusClass = $statusClassName($attendance->status);
                                        @endphp

                                        <tr>
                                             <td>
                                                  {{ method_exists($attendances, 'firstItem') ? ($attendances->firstItem() ?? 1) + $loop->index : $loop->iteration }}
                                             </td>

                                             <td>
                                                  <div class="d-flex align-items-center gap-2">
                                                       <span class="d-inline-flex align-items-center justify-content-center"
                                                            style="width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#4f46e5,#06b6d4);color:#fff;font-weight:800;">
                                                            {{ $initials !== '' ? $initials : 'AT' }}
                                                       </span>
                                                       <div>
                                                            <div style="font-weight:800;color:#1f2f46;">{{ $name }}
                                                            </div>
                                                            <small
                                                                 class="text-muted">{{ $attendance->employee?->employee_number ?? '-' }}</small>
                                                       </div>
                                                  </div>
                                             </td>

                                             <td>
                                                  <strong>{{ optional($attendance->attendance_date)->format('d M Y') ?? '-' }}</strong>
                                             </td>

                                             <td>
                                                  <div>Masuk:
                                                       {{ $attendance->check_in ? substr((string) $attendance->check_in, 0, 5) : '-' }}
                                                  </div>
                                                  <div>Pulang:
                                                       {{ $attendance->check_out ? substr((string) $attendance->check_out, 0, 5) : '-' }}
                                                  </div>
                                             </td>

                                             <td>
                                                  <span
                                                       class="attendance-status {{ $statusClass }}">{{ $statusLabel($attendance->status) }}</span>
                                             </td>

                                             <td>{{ $attendance->work_duration_formatted }}</td>
                                             <td>{{ (int) $attendance->late_minutes }} menit</td>
                                             <td>{{ (int) $attendance->overtime_minutes }} menit</td>
                                             <td>{{ \Illuminate\Support\Str::limit((string) ($attendance->notes ?? '-'), 50) }}
                                             </td>

                                             @if ($canManageAttendances)
                                                  <td>
                                                       <div class="attendance-actions">
                                                            @if ($routeHas('super-admin.attendances.show'))
                                                                 <a href="{{ route('super-admin.attendances.show', $attendance) }}"
                                                                      class="attendance-action-button attendance-action-show"
                                                                      title="Detail" aria-label="Detail">
                                                                      <i data-feather="eye"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($routeHas('super-admin.attendances.edit'))
                                                                 <a href="{{ route('super-admin.attendances.edit', $attendance) }}"
                                                                      class="attendance-action-button attendance-action-edit"
                                                                      title="Edit" aria-label="Edit">
                                                                      <i data-feather="edit-3"></i>
                                                                 </a>
                                                            @endif

                                                            @if ($routeHas('super-admin.attendances.destroy'))
                                                                 <form method="POST"
                                                                      action="{{ route('super-admin.attendances.destroy', $attendance) }}"
                                                                      class="d-inline"
                                                                      onsubmit="return confirm('Yakin ingin menghapus data kehadiran ini?');">
                                                                      @csrf
                                                                      @method('DELETE')

                                                                      <button type="submit"
                                                                           class="attendance-action-button attendance-action-delete"
                                                                           title="Hapus" aria-label="Hapus">
                                                                           <i data-feather="trash-2"></i>
                                                                      </button>
                                                                 </form>
                                                            @endif
                                                       </div>
                                                  </td>
                                             @endif
                                        </tr>
                                   @empty
                                        <tr>
                                             <td colspan="{{ $canManageAttendances ? '10' : '9' }}"
                                                  class="text-center py-5 text-muted">
                                                  Tidak ada data kehadiran untuk ditampilkan.
                                             </td>
                                        </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>

                    @if ($attendances->hasPages())
                         <div class="attendance-pagination-wrap">
                              <div class="attendance-pagination-info">
                                   Menampilkan {{ number_format($attendances->firstItem() ?? 0) }} -
                                   {{ number_format($attendances->lastItem() ?? 0) }} dari
                                   {{ number_format($attendances->total()) }} data
                              </div>

                              <div>{{ $attendances->onEachSide(1)->links() }}</div>
                         </div>
                    @elseif ($filteredTotal > 0)
                         <div class="attendance-pagination-wrap">
                              <div class="attendance-pagination-info">
                                   Menampilkan seluruh {{ number_format($filteredTotal) }} data pada halaman ini.
                              </div>
                         </div>
                    @endif
               </section>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
