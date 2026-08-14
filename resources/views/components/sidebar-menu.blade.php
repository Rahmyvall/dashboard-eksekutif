@php
     use App\Services\MenuService;

     $menuService = app(MenuService::class);
     $menus = $menuService->getMenus();

     $routeActive = static function (string ...$patterns): bool {
         return request()->routeIs(...$patterns);
     };

     $menuUrl = static function (string $key, array $parameters = []): string {
         $routes = [
             'dashboard' => 'dashboard',
             'branches' => 'branches.index',
             'departments' => 'departments.index',
             'positions' => 'positions.index',
             'employees' => 'employees.index',
             'employment' => 'employment.index',
             'customers' => 'customers.index',
             'service_categories' => 'service-categories.index',
             'services' => 'services.index',
             'service_orders' => 'service-orders.index',
             'service_orders_create' => 'service-orders.create',
             'service_order_items' => 'service-order-items.index',
             'branch_approvals' => 'service-orders.approvals.index',
             'work_schedules' => 'work-schedules.index',
             'employee_schedules' => 'employee-schedules.index',
             'employee_activities' => 'employee-activities.index',
             'service_order_status' => 'service-order-status-histories.index',
             'employee_schedules_mine' => 'employee-schedules.mine',
             'employee_activities_mine' => 'employee-activities.mine',
             'attendances' => 'attendances.index',
             'attendances_mine' => 'attendances.mine',
             'check_in' => 'attendances.checkin',
             'check_out' => 'attendances.checkout',
             'leave_requests' => 'leave-requests.index',
             'leave_requests_mine' => 'leave-requests.mine',
             'expenses' => 'expenses.index',
             'invoices' => 'invoices.index',
             'payments' => 'payments.index',
             'customer_feedback' => 'customer-feedback.index',
             'customer_complaints' => 'customer-complaints.index',
             'customer_complaints_create' => 'customer-complaints.create',
             'performance_indicators' => 'performance-indicators.index',
             'performance_periods' => 'performance-periods.index',
             'performance_roles' => 'performance-roles.index',
             'employee_targets' => 'employee-targets.index',
             'employee_performance' => 'employee-performance.index',
             'performance_details' => 'performance-details.index',
             'employee_performance_mine' => 'employee-performance.mine',
             'performance_details_mine' => 'performance-details.mine',
             'reports_services' => 'reports.services',
             'reports_performance' => 'reports.performance',
             'reports_customers' => 'reports.customers',
             'reports_complaints' => 'reports.complaints',
             'reports_finance' => 'reports.finance',
             'users' => 'super-admin.users.index',
             'roles_permissions' => 'super-admin.roles.index',
             'audit_logs' => 'audit-logs.index',
         ];

         $route = $routes[$key] ?? '#';

         if ($route === '#') {
             return '#';
         }

         try {
             return route($route, $parameters);
         } catch (\Exception $e) {
             return '#';
         }
     };
@endphp

<div class="sidebar">
     {{-- ================================================================ --}}
     {{-- SIDEBAR HEADER --}}
     {{-- ================================================================ --}}
     <div class="sidebar-header">
          <div class="sidebar-brand">
               <a href="{{ route('dashboard') }}" class="sidebar-logo" aria-label="Buka dashboard">
                    <img src="{{ asset('backend/assets/img/logo.png') }}" alt="Logo Dashboard Monitoring"
                         class="sidebar-logo-image">
               </a>

               <small class="sidebar-logo-headline">
                    Kinerja Karyawan &amp; Kepuasan Pelanggan
               </small>

               @auth
                    <small class="sidebar-role-name">
                         {{ auth()->user()->getRoleNames()->first() ?? 'Pengguna' }}
                    </small>
               @endauth
          </div>
     </div>

     {{-- ================================================================ --}}
     {{-- SIDEBAR BODY --}}
     {{-- ================================================================ --}}
     <div id="dpSidebarBody" class="sidebar-body">
          <ul class="nav nav-sidebar">
               @foreach ($menus as $menu)
                    {{-- Menu Labels (Group Headers) --}}
                    @if ($menu['type'] === 'label')
                         <li class="nav-label">
                              <span class="content-label">{{ $menu['label'] }}</span>
                         </li>
                    @else
                         {{-- Regular Menu Items --}}
                         @php
                              $isOpen = false;
                              $isActive = false;

                              if (isset($menu['route'])) {
                                  $isActive = $routeActive(...(array) $menu['route']);
                              }

                              if (isset($menu['children'])) {
                                  $isOpen = collect($menu['children'])->some(function ($child) use ($routeActive) {
                                      return isset($child['route']) && $routeActive(...(array) $child['route']);
                                  });
                                  $isActive = $isOpen;
                              }
                         @endphp

                         <li class="nav-item {{ $isOpen ? 'show' : '' }}">
                              @if (isset($menu['children']) && !empty($menu['children']))
                                   <a href="javascript:void(0);"
                                        class="nav-link with-sub {{ $isActive ? 'active' : '' }}"
                                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                        aria-controls="submenu-{{ $menu['name'] }}">
                                        <i data-feather="{{ $menu['icon'] ?? 'folder' }}"></i>
                                        <span>{{ $menu['label'] }}</span>
                                   </a>

                                   <nav id="submenu-{{ $menu['name'] }}" class="nav nav-sub"
                                        aria-label="Menu {{ $menu['label'] }}">
                                        @foreach ($menu['children'] as $child)
                                             @php
                                                  $childActive = false;
                                                  if (isset($child['route'])) {
                                                      $childActive = $routeActive(...(array) $child['route']);
                                                  }
                                             @endphp

                                             <a href="{{ $menuUrl($child['url'] ?? '#') }}"
                                                  class="nav-sub-link {{ $childActive ? 'active' : '' }}">
                                                  {{ $child['label'] }}
                                             </a>
                                        @endforeach
                                   </nav>
                              @else
                                   <a href="{{ $menuUrl($menu['url'] ?? '#') }}"
                                        class="nav-link {{ $isActive ? 'active' : '' }}">
                                        <i data-feather="{{ $menu['icon'] ?? 'file' }}"></i>
                                        <span>{{ $menu['label'] }}</span>
                                   </a>
                              @endif
                         </li>
                    @endif
               @endforeach
          </ul>
     </div>

     {{-- ================================================================ --}}
     {{-- SIDEBAR FOOTER --}}
     {{-- ================================================================ --}}
     <div class="sidebar-footer">
          @auth
               <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                         <img src="{{ auth()->user()->avatar_url ?? asset('backend/assets/img/avatar.png') }}"
                              alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="sidebar-user-info">
                         <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                         <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
                    </div>
               </div>
          @endauth
     </div>
</div>
