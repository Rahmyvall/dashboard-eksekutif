@php
     $currentUser = $user ?? auth()->user();
     $roleConfig = $roleConfig ?? [];
     $pageTitle = data_get($roleConfig, 'page_title', 'Dashboard');
     $roleLabel = $activeRoleLabel ?? data_get($roleConfig, 'role_label', 'Pengguna');
     $headline = data_get($roleConfig, 'headline', 'Dashboard');
     $description = data_get($roleConfig, 'description', '');
     $accent = data_get($roleConfig, 'accent', '#2563eb');
     $accentSecondary = data_get($roleConfig, 'accent_secondary', '#7c3aed');
     $statistics = data_get($roleConfig, 'statistics', []);
     $chart = data_get($roleConfig, 'chart', []);
     $priorities = data_get($roleConfig, 'priorities', []);
     $table = data_get($roleConfig, 'table', []);
     $activities = data_get($roleConfig, 'activities', []);
@endphp

<style>
     .role-dashboard {
          --rd-primary: {{ $accent }};
          --rd-secondary: {{ $accentSecondary }};
          --rd-surface: #ffffff;
          --rd-surface-soft: #f8fafc;
          --rd-text: #0f172a;
          --rd-muted: #64748b;
          --rd-border: #e2e8f0;
          --rd-success: #059669;
          --rd-warning: #d97706;
          --rd-danger: #dc2626;
          --rd-purple: #7c3aed;
          --rd-shadow: 0 18px 45px rgba(15, 23, 42, .08);
          color: var(--rd-text);
     }

     body.black-theme .role-dashboard {
          --rd-surface: #171a22;
          --rd-surface-soft: #20242e;
          --rd-text: #f8fafc;
          --rd-muted: #a8b2c1;
          --rd-border: #2b313d;
          --rd-shadow: 0 18px 45px rgba(0, 0, 0, .28);
     }

     .role-dashboard,
     .role-dashboard * {
          box-sizing: border-box;
     }

     .role-dashboard .rd-hero,
     .role-dashboard .rd-card,
     .role-dashboard .rd-kpi,
     .role-dashboard .rd-priority {
          transition: transform .25s ease, border-color .25s ease, background-color .25s ease, box-shadow .25s ease;
     }

     .role-dashboard .rd-shell {
          padding-bottom: 30px;
     }

     .role-dashboard .rd-hero {
          position: relative;
          isolation: isolate;
          overflow: hidden;
          margin-bottom: 22px;
          padding: 30px;
          border-radius: 26px;
          color: #fff;
          background:
               radial-gradient(circle at 85% 18%, rgba(255, 255, 255, .22), transparent 27%),
               radial-gradient(circle at 8% 105%, rgba(255, 255, 255, .13), transparent 32%),
               linear-gradient(135deg, var(--rd-primary), var(--rd-secondary));
          box-shadow: 0 22px 52px color-mix(in srgb, var(--rd-primary) 24%, transparent);
     }

     .role-dashboard .rd-hero::before,
     .role-dashboard .rd-hero::after {
          position: absolute;
          z-index: -1;
          content: "";
          border: 1px solid rgba(255, 255, 255, .16);
          border-radius: 50%;
     }

     .role-dashboard .rd-hero::before {
          top: -120px;
          right: -75px;
          width: 315px;
          height: 315px;
     }

     .role-dashboard .rd-hero::after {
          right: 145px;
          bottom: -185px;
          width: 380px;
          height: 380px;
     }

     .role-dashboard .rd-hero-grid {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 24px;
     }

     .role-dashboard .rd-eyebrow {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          margin-bottom: 13px;
          padding: 7px 12px;
          border: 1px solid rgba(255, 255, 255, .24);
          border-radius: 999px;
          font-size: 11px;
          font-weight: 800;
          letter-spacing: .04em;
          text-transform: uppercase;
          background: rgba(255, 255, 255, .10);
     }

     .role-dashboard .rd-eyebrow-dot {
          width: 8px;
          height: 8px;
          border-radius: 50%;
          background: #fff;
          box-shadow: 0 0 0 5px rgba(255, 255, 255, .15);
     }

     .role-dashboard .rd-hero h1 {
          margin: 0 0 9px;
          font-size: clamp(26px, 3vw, 40px);
          line-height: 1.12;
          color: #fff !important;
     }

     .role-dashboard .rd-hero p {
          max-width: 720px;
          margin: 0;
          font-size: 14px;
          line-height: 1.75;
          color: rgba(255, 255, 255, .80) !important;
     }

     .role-dashboard .rd-actions {
          display: flex;
          flex-wrap: wrap;
          justify-content: flex-end;
          gap: 10px;
          min-width: 260px;
     }

     .role-dashboard .rd-button {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 8px;
          min-height: 42px;
          padding: 10px 16px;
          border: 1px solid transparent;
          border-radius: 12px;
          font-size: 12px;
          font-weight: 800;
          cursor: pointer;
     }

     .role-dashboard .rd-button-light {
          color: #0f172a;
          background: #fff;
          box-shadow: 0 10px 24px rgba(15, 23, 42, .14);
     }

     .role-dashboard .rd-button-glass {
          border-color: rgba(255, 255, 255, .25);
          color: #fff;
          background: rgba(255, 255, 255, .10);
     }

     .role-dashboard .rd-kpi-grid {
          display: grid;
          grid-template-columns: repeat(4, minmax(0, 1fr));
          gap: 18px;
          margin-bottom: 22px;
     }

     .role-dashboard .rd-kpi {
          min-height: 166px;
          padding: 21px;
          border: 1px solid var(--rd-border);
          border-radius: 20px;
          background: var(--rd-surface);
          box-shadow: var(--rd-shadow);
     }

     .role-dashboard .rd-kpi:hover {
          transform: translateY(-3px);
          border-color: var(--rd-primary);
     }

     .role-dashboard .rd-kpi-icon,
     .role-dashboard .rd-item-icon,
     .role-dashboard .rd-activity-icon {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: 14px;
          color: var(--rd-primary);
          background: color-mix(in srgb, var(--rd-primary) 14%, transparent);
     }

     .role-dashboard .rd-kpi-icon {
          width: 48px;
          height: 48px;
     }

     .role-dashboard .rd-kpi-icon svg,
     .role-dashboard .rd-item-icon svg,
     .role-dashboard .rd-activity-icon svg {
          width: 20px;
          height: 20px;
     }

     .role-dashboard .rd-kpi-label {
          margin: 17px 0 6px;
          font-size: 12px;
          font-weight: 800;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-kpi-value {
          margin: 0;
          font-size: 28px;
          font-weight: 900;
          letter-spacing: -.03em;
          color: var(--rd-text);
     }

     .role-dashboard .rd-kpi-note {
          margin-top: 8px;
          font-size: 10px;
          font-weight: 700;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-main-grid {
          display: grid;
          grid-template-columns: minmax(0, 1.65fr) minmax(330px, .85fr);
          gap: 20px;
          margin-bottom: 20px;
     }

     .role-dashboard .rd-bottom-grid {
          display: grid;
          grid-template-columns: minmax(0, 1.2fr) minmax(0, .8fr);
          gap: 20px;
     }

     .role-dashboard .rd-card {
          overflow: hidden;
          border: 1px solid var(--rd-border);
          border-radius: 20px;
          background: var(--rd-surface);
          box-shadow: var(--rd-shadow);
     }

     .role-dashboard .rd-card-header {
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          gap: 16px;
          padding: 20px 22px;
          border-bottom: 1px solid var(--rd-border);
     }

     .role-dashboard .rd-card-title {
          margin: 0 0 5px;
          font-size: 15px;
          font-weight: 900;
          color: var(--rd-text);
     }

     .role-dashboard .rd-card-subtitle {
          margin: 0;
          font-size: 11px;
          line-height: 1.6;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-card-body {
          padding: 22px;
     }

     .role-dashboard .rd-status {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: 6px 9px;
          border-radius: 999px;
          font-size: 10px;
          font-weight: 800;
          color: var(--rd-success);
          background: color-mix(in srgb, var(--rd-success) 14%, transparent);
     }

     .role-dashboard .rd-status-dot {
          width: 6px;
          height: 6px;
          border-radius: 50%;
          background: currentColor;
     }

     .role-dashboard .rd-chart-legend {
          display: flex;
          flex-wrap: wrap;
          gap: 14px;
          margin-bottom: 20px;
     }

     .role-dashboard .rd-legend {
          display: inline-flex;
          align-items: center;
          gap: 7px;
          font-size: 10px;
          font-weight: 800;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-legend-dot {
          width: 9px;
          height: 9px;
          border-radius: 50%;
     }

     .role-dashboard .rd-chart {
          display: grid;
          gap: 16px;
     }

     .role-dashboard .rd-chart-row {
          display: grid;
          grid-template-columns: 45px minmax(0, 1fr);
          align-items: center;
          gap: 12px;
     }

     .role-dashboard .rd-chart-label {
          font-size: 10px;
          font-weight: 800;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-bars {
          display: grid;
          gap: 6px;
     }

     .role-dashboard .rd-track {
          position: relative;
          overflow: hidden;
          height: 13px;
          border-radius: 999px;
          background: var(--rd-surface-soft);
          border: 1px solid var(--rd-border);
     }

     .role-dashboard .rd-bar {
          height: 100%;
          min-width: 3px;
          border-radius: inherit;
     }

     .role-dashboard .rd-bar-primary {
          background: linear-gradient(90deg, var(--rd-primary), color-mix(in srgb, var(--rd-primary) 70%, #fff));
     }

     .role-dashboard .rd-bar-secondary {
          background: linear-gradient(90deg, var(--rd-secondary), color-mix(in srgb, var(--rd-secondary) 70%, #fff));
     }

     .role-dashboard .rd-priority-list {
          display: grid;
          gap: 12px;
     }

     .role-dashboard .rd-priority {
          display: flex;
          align-items: flex-start;
          gap: 13px;
          padding: 14px;
          border: 1px solid var(--rd-border);
          border-radius: 15px;
          background: var(--rd-surface-soft);
     }

     .role-dashboard .rd-priority:hover {
          transform: translateX(3px);
          border-color: var(--rd-primary);
     }

     .role-dashboard .rd-item-icon,
     .role-dashboard .rd-activity-icon {
          flex: 0 0 42px;
          width: 42px;
          height: 42px;
     }

     .role-dashboard .rd-item-content,
     .role-dashboard .rd-activity-content {
          flex: 1;
          min-width: 0;
     }

     .role-dashboard .rd-item-content h6,
     .role-dashboard .rd-activity-content h6 {
          margin: 1px 0 4px;
          font-size: 11px;
          font-weight: 900;
          color: var(--rd-text);
     }

     .role-dashboard .rd-item-content p,
     .role-dashboard .rd-activity-content p {
          margin: 0;
          font-size: 10px;
          line-height: 1.55;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-table-wrapper {
          overflow-x: auto;
     }

     .role-dashboard .rd-table {
          width: 100%;
          min-width: 640px;
          border-collapse: collapse;
     }

     .role-dashboard .rd-table th,
     .role-dashboard .rd-table td {
          padding: 14px 12px;
          border-bottom: 1px solid var(--rd-border);
          text-align: left;
          vertical-align: middle;
     }

     .role-dashboard .rd-table th {
          font-size: 10px;
          font-weight: 900;
          letter-spacing: .05em;
          text-transform: uppercase;
          color: var(--rd-muted);
     }

     .role-dashboard .rd-table td {
          font-size: 11px;
          color: var(--rd-text);
     }

     .role-dashboard .rd-table-status {
          display: inline-flex;
          padding: 6px 9px;
          border-radius: 999px;
          font-size: 9px;
          font-weight: 800;
          color: var(--rd-primary);
          background: color-mix(in srgb, var(--rd-primary) 14%, transparent);
     }

     .role-dashboard .rd-activity-list {
          display: grid;
          gap: 2px;
     }

     .role-dashboard .rd-activity {
          position: relative;
          display: flex;
          gap: 13px;
          padding: 12px 0;
     }

     .role-dashboard .rd-activity:not(:last-child)::after {
          position: absolute;
          top: 51px;
          bottom: -5px;
          left: 20px;
          width: 1px;
          content: "";
          background: var(--rd-border);
     }

     .role-dashboard .rd-activity-time {
          margin-top: 5px;
          font-size: 9px;
          font-weight: 800;
          color: var(--rd-muted);
     }

     @media (max-width: 1199px) {
          .role-dashboard .rd-kpi-grid {
               grid-template-columns: repeat(2, minmax(0, 1fr));
          }

          .role-dashboard .rd-main-grid,
          .role-dashboard .rd-bottom-grid {
               grid-template-columns: 1fr;
          }
     }

     @media (max-width: 767px) {
          .role-dashboard .rd-hero {
               padding: 24px;
               border-radius: 20px;
          }

          .role-dashboard .rd-hero-grid {
               align-items: flex-start;
               flex-direction: column;
          }

          .role-dashboard .rd-actions {
               justify-content: flex-start;
               min-width: 0;
          }

          .role-dashboard .rd-kpi-grid {
               grid-template-columns: 1fr;
          }

          .role-dashboard .rd-card-header {
               flex-direction: column;
          }
     }
</style>

<div class="role-dashboard">
     <div class="rd-shell">
          <section class="rd-hero">
               <div class="rd-hero-grid">
                    <div>
                         <div class="rd-eyebrow">
                              <span class="rd-eyebrow-dot"></span>
                              {{ $roleLabel }}
                         </div>

                         <h1>{{ $headline }}</h1>

                         <p>
                              Selamat datang, {{ $currentUser?->name ?? 'Pengguna' }}.
                              {{ $description }}
                         </p>
                    </div>

                    <div class="rd-actions">
                         <button type="button" class="rd-button rd-button-light" id="refreshRoleDashboard">
                              <i data-feather="refresh-cw"></i>
                              Segarkan
                         </button>

                         <button type="button" class="rd-button rd-button-glass" onclick="window.print()">
                              <i data-feather="printer"></i>
                              Cetak Ringkasan
                         </button>
                    </div>
               </div>
          </section>

          <section class="rd-kpi-grid">
               @foreach ($statistics as $stat)
                    <article class="rd-kpi">
                         <span class="rd-kpi-icon">
                              <i data-feather="{{ data_get($stat, 'icon', 'activity') }}"></i>
                         </span>

                         <p class="rd-kpi-label">
                              {{ data_get($stat, 'label', '-') }}
                         </p>

                         <h2 class="rd-kpi-value">
                              {{ data_get($stat, 'value', '0') }}
                         </h2>

                         <div class="rd-kpi-note">
                              {{ data_get($stat, 'note', '-') }}
                         </div>
                    </article>
               @endforeach
          </section>

          <section class="rd-main-grid">
               <article class="rd-card">
                    <header class="rd-card-header">
                         <div>
                              <h2 class="rd-card-title">
                                   {{ data_get($chart, 'title', 'Ringkasan Kinerja') }}
                              </h2>

                              <p class="rd-card-subtitle">
                                   {{ data_get($chart, 'subtitle', '') }}
                              </p>
                         </div>

                         <span class="rd-status">
                              <span class="rd-status-dot"></span>
                              Data terbaru
                         </span>
                    </header>

                    <div class="rd-card-body">
                         <div class="rd-chart-legend">
                              <span class="rd-legend">
                                   <span class="rd-legend-dot" style="background: var(--rd-primary);"></span>
                                   {{ data_get($chart, 'series_1_label', 'Target') }}
                              </span>

                              <span class="rd-legend">
                                   <span class="rd-legend-dot" style="background: var(--rd-secondary);"></span>
                                   {{ data_get($chart, 'series_2_label', 'Realisasi') }}
                              </span>
                         </div>

                         <div class="rd-chart">
                              @foreach (data_get($chart, 'rows', []) as $row)
                                   @php
                                        $firstValue = max(0, min(100, (int) data_get($row, 'series_1', 0)));
                                        $secondValue = max(0, min(100, (int) data_get($row, 'series_2', 0)));
                                   @endphp

                                   <div class="rd-chart-row">
                                        <span class="rd-chart-label">
                                             {{ data_get($row, 'label', '-') }}
                                        </span>

                                        <div class="rd-bars">
                                             <div class="rd-track"
                                                  title="{{ data_get($chart, 'series_1_label', 'Series 1') }}: {{ $firstValue }}">
                                                  <div class="rd-bar rd-bar-primary"
                                                       style="width: {{ $firstValue }}%;"></div>
                                             </div>

                                             <div class="rd-track"
                                                  title="{{ data_get($chart, 'series_2_label', 'Series 2') }}: {{ $secondValue }}">
                                                  <div class="rd-bar rd-bar-secondary"
                                                       style="width: {{ $secondValue }}%;"></div>
                                             </div>
                                        </div>
                                   </div>
                              @endforeach
                         </div>
                    </div>
               </article>

               <aside class="rd-card">
                    <header class="rd-card-header">
                         <div>
                              <h2 class="rd-card-title">
                                   {{ data_get($roleConfig, 'priority_title', 'Prioritas') }}
                              </h2>

                              <p class="rd-card-subtitle">
                                   Item yang perlu dipantau dan ditindaklanjuti.
                              </p>
                         </div>
                    </header>

                    <div class="rd-card-body">
                         <div class="rd-priority-list">
                              @forelse ($priorities as $item)
                                   <div class="rd-priority">
                                        <span class="rd-item-icon">
                                             <i data-feather="{{ data_get($item, 'icon', 'circle') }}"></i>
                                        </span>

                                        <div class="rd-item-content">
                                             <h6>{{ data_get($item, 'title', '-') }}</h6>
                                             <p>{{ data_get($item, 'description', '-') }}</p>
                                        </div>
                                   </div>
                              @empty
                                   <p class="rd-card-subtitle">
                                        Belum ada prioritas.
                                   </p>
                              @endforelse
                         </div>
                    </div>
               </aside>
          </section>

          <section class="rd-bottom-grid">
               <article class="rd-card">
                    <header class="rd-card-header">
                         <div>
                              <h2 class="rd-card-title">
                                   {{ data_get($table, 'title', 'Ringkasan Data') }}
                              </h2>

                              <p class="rd-card-subtitle">
                                   Data utama berdasarkan tanggung jawab role aktif.
                              </p>
                         </div>
                    </header>

                    <div class="rd-card-body">
                         <div class="rd-table-wrapper">
                              <table class="rd-table">
                                   <thead>
                                        <tr>
                                             @foreach (data_get($table, 'headers', []) as $header)
                                                  <th>{{ $header }}</th>
                                             @endforeach
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @forelse (data_get($table, 'rows', []) as $row)
                                             <tr>
                                                  <td>{{ data_get($row, 'col1', '-') }}</td>
                                                  <td>{{ data_get($row, 'col2', '-') }}</td>
                                                  <td>{{ data_get($row, 'col3', '-') }}</td>
                                                  <td>
                                                       <span class="rd-table-status">
                                                            {{ data_get($row, 'col4', '-') }}
                                                       </span>
                                                  </td>
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="4">
                                                       Data belum tersedia.
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </article>

               <article class="rd-card">
                    <header class="rd-card-header">
                         <div>
                              <h2 class="rd-card-title">
                                   {{ data_get($roleConfig, 'activity_title', 'Aktivitas Terbaru') }}
                              </h2>

                              <p class="rd-card-subtitle">
                                   Perubahan dan aktivitas terbaru.
                              </p>
                         </div>
                    </header>

                    <div class="rd-card-body">
                         <div class="rd-activity-list">
                              @forelse ($activities as $activity)
                                   <div class="rd-activity">
                                        <span class="rd-activity-icon">
                                             <i data-feather="{{ data_get($activity, 'icon', 'activity') }}"></i>
                                        </span>

                                        <div class="rd-activity-content">
                                             <h6>{{ data_get($activity, 'title', '-') }}</h6>
                                             <p>{{ data_get($activity, 'description', '-') }}</p>
                                             <div class="rd-activity-time">
                                                  {{ data_get($activity, 'time', '-') }}
                                             </div>
                                        </div>
                                   </div>
                              @empty
                                   <p class="rd-card-subtitle">
                                        Belum ada aktivitas terbaru.
                                   </p>
                              @endforelse
                         </div>
                    </div>
               </article>
          </section>
     </div>
</div>

@push('script')
     <script>
          document.addEventListener('DOMContentLoaded', function() {
               const refreshButton = document.getElementById(
                    'refreshRoleDashboard'
               );

               if (typeof feather !== 'undefined') {
                    feather.replace();
               }

               if (refreshButton) {
                    refreshButton.addEventListener('click', function() {
                         refreshButton.disabled = true;
                         refreshButton.innerHTML =
                              '<span class="spinner-border spinner-border-sm" role="status"></span> Menyegarkan...';

                         window.setTimeout(function() {
                              window.location.reload();
                         }, 450);
                    });
               }
          });
     </script>
@endpush
