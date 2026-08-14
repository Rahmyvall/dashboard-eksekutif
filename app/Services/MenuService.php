<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    /**
     * Menu structure dengan permission mapping
     */
    public const MENU_STRUCTURE = [
        [
            'label' => 'Dashboard Utama',
            'type' => 'label',
        ],
        [
            'label' => 'Dashboard',
            'name' => 'dashboard',
            'url' => 'dashboard.view',
            'icon' => 'home',
            'route' => ['dashboard', 'dashboard.*', '*.dashboard'],
            'permission' => 'dashboard.view',
        ],

        // MASTER DATA
        [
            'label' => 'Master Data',
            'type' => 'label',
        ],
        [
            'label' => 'Master Data',
            'name' => 'master_data',
            'icon' => 'database',
            'permission' => 'master_data.view',
            'children' => [
                [
                    'label' => 'Data Cabang',
                    'url' => 'branches',
                    'route' => ['branches.*', 'super-admin.branches.*'],
                    'permission' => 'branches.view',
                    'roles' => ['super_admin', 'executive', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => 'Data Departemen',
                    'url' => 'departments',
                    'route' => ['departments.*', 'super-admin.departments.*'],
                    'permission' => 'departments.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'auditor'],
                ],
                [
                    'label' => 'Data Jabatan',
                    'url' => 'positions',
                    'route' => ['positions.*', 'super-admin.positions.*'],
                    'permission' => 'positions.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'auditor'],
                ],
                [
                    'label' => 'Data Karyawan',
                    'url' => 'employees',
                    'route' => ['employees.*', 'super-admin.employees.*'],
                    'permission' => 'employees.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => 'Data Employment',
                    'url' => 'employment',
                    'route' => ['employment.*', 'employments.*', 'super-admin.employment.*', 'super-admin.employments.*'],
                    'permission' => 'employees.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => 'Data Pelanggan',
                    'url' => 'customers',
                    'route' => ['customers.*', 'super-admin.customers.*'],
                    'permission' => 'customers.view',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'admin_operasional', 'finance', 'auditor'],
                ],
                [
                    'label' => 'Kategori Layanan',
                    'url' => 'service_categories',
                    'route' => ['service-categories.*', 'super-admin.service-categories.*'],
                    'permission' => 'service_categories.view',
                    'roles' => ['super_admin', 'admin_pelayanan', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => 'Data Layanan',
                    'url' => 'services',
                    'route' => ['services.*', 'super-admin.services.*'],
                    'permission' => 'services.view',
                    'roles' => ['super_admin', 'admin_pelayanan', 'admin_operasional', 'auditor'],
                ],
            ],
        ],

        // OPERASIONAL LAYANAN
        [
            'label' => 'Operasional Layanan',
            'type' => 'label',
        ],
        [
            'label' => 'Proses Layanan',
            'name' => 'service_process',
            'icon' => 'briefcase',
            'permission' => 'service_orders.view',
            'children' => [
                [
                    'label' => '1. Pesanan Layanan',
                    'url' => 'service_orders',
                    'route' => ['service-orders.*', 'super-admin.service-orders.*'],
                    'permission' => 'service_orders.view',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'admin_operasional', 'finance', 'auditor'],
                ],
                [
                    'label' => 'Tambah Pesanan',
                    'url' => 'service_orders_create',
                    'route' => ['service-orders.create', 'super-admin.service-orders.create'],
                    'permission' => 'service_orders.create',
                    'roles' => ['super_admin', 'admin_pelayanan', 'admin_operasional'],
                ],
                [
                    'label' => '2. Item Pesanan',
                    'url' => 'service_order_items',
                    'route' => ['service-order-items.*', 'super-admin.service-order-items.*'],
                    'permission' => 'service_order_items.view',
                    'roles' => ['super_admin', 'admin_pelayanan', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => '3. Persetujuan Cabang',
                    'url' => 'branch_approvals',
                    'route' => ['service-orders.approvals.*', 'branches.approve', 'branches.reject'],
                    'permission' => 'branch_approvals.view',
                    'roles' => ['super_admin', 'executive', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => '4. Jadwal Kerja',
                    'url' => 'work_schedules',
                    'route' => ['work-schedules.*', 'super-admin.work-schedules.*'],
                    'permission' => 'work_schedules.view',
                    'roles' => ['super_admin', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => '5. Penugasan Karyawan',
                    'url' => 'employee_schedules',
                    'route' => ['employee-schedules.*', 'super-admin.employee-schedules.*'],
                    'permission' => 'employee_schedules.view',
                    'roles' => ['super_admin', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => '6. Aktivitas Pekerjaan',
                    'url' => 'employee_activities',
                    'route' => ['employee-activities.*', 'super-admin.employee-activities.*'],
                    'permission' => 'employee_activities.view',
                    'roles' => ['super_admin', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => '7. Riwayat Status Pesanan',
                    'url' => 'service_order_status',
                    'route' => ['service-order-status-histories.*', 'super-admin.service-order-status-histories.*', 'super-admin.service_order_status_histories.*', 'service-orders.status-histories.*'],
                    'permission' => 'service_order_status.view',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'admin_operasional', 'auditor'],
                ],
                // For Karyawan only
                [
                    'label' => 'Jadwal Kerja Saya',
                    'url' => 'employee_schedules_mine',
                    'route' => ['employee-schedules.mine', 'employee-schedules.my', 'work-schedules.mine'],
                    'permission' => 'employee_schedules.view_own',
                    'roles' => ['karyawan'],
                ],
                [
                    'label' => 'Aktivitas Saya',
                    'url' => 'employee_activities_mine',
                    'route' => ['employee-activities.mine', 'employee-activities.my'],
                    'permission' => 'employee_activities.view_own',
                    'roles' => ['karyawan'],
                ],
            ],
        ],

        // SDM OPERASIONAL
        [
            'label' => 'SDM Operasional',
            'type' => 'label',
        ],
        [
            'label' => 'SDM Operasional',
            'name' => 'hr_operations',
            'icon' => 'users',
            'permission' => 'attendances.view',
            'children' => [
                [
                    'label' => 'Data Kehadiran',
                    'url' => 'attendances',
                    'route' => ['attendances.*', 'super-admin.attendances.*'],
                    'permission' => 'attendances.view',
                    'roles' => ['super_admin', 'hr', 'manager_departemen', 'auditor'],
                ],
                [
                    'label' => 'Kehadiran Saya',
                    'url' => 'attendances_mine',
                    'route' => ['attendances.*', 'super-admin.attendances.*'],
                    'permission' => 'attendances.view_own',
                    'roles' => ['karyawan'],
                ],
                [
                    'label' => 'Check In',
                    'url' => 'check_in',
                    'permission' => 'attendances.checkin',
                    'roles' => ['karyawan'],
                ],
                [
                    'label' => 'Check Out',
                    'url' => 'check_out',
                    'permission' => 'attendances.checkout',
                    'roles' => ['karyawan'],
                ],
                [
                    'label' => 'Pengajuan Cuti',
                    'url' => 'leave_requests',
                    'route' => ['leave-requests.*', 'super-admin.leave-requests.*'],
                    'permission' => 'leave_requests.view',
                    'roles' => ['super_admin', 'hr', 'manager_departemen', 'auditor'],
                ],
                [
                    'label' => 'Pengajuan Cuti Saya',
                    'url' => 'leave_requests_mine',
                    'route' => ['leave-requests.mine', 'leave-requests.my'],
                    'permission' => 'leave_requests.view_own',
                    'roles' => ['karyawan'],
                ],
            ],
        ],

        // KEUANGAN LAYANAN
        [
            'label' => 'Keuangan Layanan',
            'type' => 'label',
        ],
        [
            'label' => 'Keuangan Layanan',
            'name' => 'finance',
            'icon' => 'credit-card',
            'permission' => 'invoices.view',
            'children' => [
                [
                    'label' => '8. Pengeluaran',
                    'url' => 'expenses',
                    'route' => ['expenses.*', 'super-admin.expenses.*'],
                    'permission' => 'expenses.view',
                    'roles' => ['super_admin', 'admin_operasional', 'finance', 'auditor'],
                ],
                [
                    'label' => '9. Invoice',
                    'url' => 'invoices',
                    'route' => ['invoices.*', 'super-admin.invoices.*'],
                    'permission' => 'invoices.view',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'finance', 'auditor'],
                ],
                [
                    'label' => '10. Pembayaran',
                    'url' => 'payments',
                    'route' => ['payments.*', 'super-admin.payments.*'],
                    'permission' => 'payments.view',
                    'roles' => ['super_admin', 'executive', 'finance', 'auditor'],
                ],
            ],
        ],

        // PELAYANAN PELANGGAN
        [
            'label' => 'Pelayanan Pelanggan',
            'type' => 'label',
        ],
        [
            'label' => 'Pelayanan Pelanggan',
            'name' => 'customer_service',
            'icon' => 'message-circle',
            'permission' => 'customer_feedback.view',
            'children' => [
                [
                    'label' => '11. Feedback Pelanggan',
                    'url' => 'customer_feedback',
                    'route' => ['customer-feedback.*', 'super-admin.customer-feedback.*'],
                    'permission' => 'customer_feedback.view',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => '12. Keluhan Pelanggan',
                    'url' => 'customer_complaints',
                    'route' => ['customer-complaints.*', 'super-admin.customer-complaints.*', 'complaints.*'],
                    'permission' => 'customer_complaints.view',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => 'Tambah Keluhan',
                    'url' => 'customer_complaints_create',
                    'route' => ['customer-complaints.create', 'super-admin.customer-complaints.create', 'complaints.create'],
                    'permission' => 'customer_complaints.create',
                    'roles' => ['super_admin', 'admin_pelayanan'],
                ],
            ],
        ],

        // KINERJA KARYAWAN
        [
            'label' => 'Kinerja Karyawan',
            'type' => 'label',
        ],
        [
            'label' => 'Kinerja Karyawan',
            'name' => 'performance',
            'icon' => 'bar-chart-2',
            'permission' => 'employee_performance.view',
            'children' => [
                // Non-Karyawan
                [
                    'label' => 'Indikator Kinerja',
                    'url' => 'performance_indicators',
                    'route' => ['performance-indicators.*', 'super-admin.performance-indicators.*', 'kpi-indicators.*'],
                    'permission' => 'performance_indicators.view',
                    'roles' => ['super_admin', 'hr', 'auditor'],
                ],
                [
                    'label' => 'Periode Penilaian',
                    'url' => 'performance_periods',
                    'route' => ['performance-periods.*', 'super-admin.performance-periods.*'],
                    'permission' => 'performance_periods.view',
                    'roles' => ['super_admin', 'auditor'],
                ],
                [
                    'label' => 'Bobot Kinerja per Role',
                    'url' => 'performance_roles',
                    'route' => ['performance-roles.*', 'performance-role.*', 'super-admin.performance-roles.*'],
                    'permission' => 'performance_roles.view',
                    'roles' => ['super_admin', 'hr'],
                ],
                [
                    'label' => 'Target Karyawan',
                    'url' => 'employee_targets',
                    'route' => ['employee-targets.*', 'super-admin.employee-targets.*'],
                    'permission' => 'employee_targets.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'auditor'],
                ],
                [
                    'label' => 'Hasil Kinerja',
                    'url' => 'employee_performance',
                    'route' => ['employee-performance.*', 'employee-performances.*', 'employee-kpi-results.*'],
                    'permission' => 'employee_performance.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                [
                    'label' => 'Detail Penilaian',
                    'url' => 'performance_details',
                    'route' => ['performance-details.*', 'super-admin.performance-details.*', 'performance-evaluations.*'],
                    'permission' => 'performance_details.view',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'admin_operasional', 'auditor'],
                ],
                // Karyawan only
                [
                    'label' => 'Hasil Kinerja Saya',
                    'url' => 'employee_performance_mine',
                    'route' => ['employee-performance.mine', 'employee-performances.mine', 'employee-kpi-results.mine', 'employee-kpi-results.my'],
                    'permission' => 'employee_performance.view_own',
                    'roles' => ['karyawan'],
                ],
                [
                    'label' => 'Detail Penilaian Saya',
                    'url' => 'performance_details_mine',
                    'route' => ['performance-details.mine', 'performance-evaluations.mine', 'performance-evaluations.my'],
                    'permission' => 'performance_details.view_own',
                    'roles' => ['karyawan'],
                ],
            ],
        ],

        // LAPORAN
        [
            'label' => 'Laporan',
            'type' => 'label',
        ],
        [
            'label' => 'Laporan',
            'name' => 'reports',
            'icon' => 'file-text',
            'permission' => 'reports.view',
            'children' => [
                [
                    'label' => 'Laporan Layanan',
                    'url' => 'reports_services',
                    'route' => ['reports.services', 'reports.transactions'],
                    'permission' => 'reports.services',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'admin_operasional', 'finance', 'auditor'],
                ],
                [
                    'label' => 'Laporan Kinerja',
                    'url' => 'reports_performance',
                    'route' => ['reports.performance'],
                    'permission' => 'reports.performance',
                    'roles' => ['super_admin', 'executive', 'hr', 'manager_departemen', 'auditor'],
                ],
                [
                    'label' => 'Laporan Pelanggan',
                    'url' => 'reports_customers',
                    'route' => ['reports.customers', 'reports.satisfaction'],
                    'permission' => 'reports.customers',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'auditor'],
                ],
                [
                    'label' => 'Laporan Keluhan',
                    'url' => 'reports_complaints',
                    'route' => ['reports.complaints'],
                    'permission' => 'reports.complaints',
                    'roles' => ['super_admin', 'executive', 'admin_pelayanan', 'auditor'],
                ],
                [
                    'label' => 'Laporan Keuangan',
                    'url' => 'reports_finance',
                    'route' => ['reports.finance'],
                    'permission' => 'reports.finance',
                    'roles' => ['super_admin', 'executive', 'finance', 'auditor'],
                ],
            ],
        ],

        // SISTEM
        [
            'label' => 'Sistem',
            'type' => 'label',
        ],
        [
            'label' => 'Manajemen User',
            'name' => 'user_management',
            'icon' => 'lock',
            'permission' => 'users.view',
            'children' => [
                [
                    'label' => 'User Management',
                    'url' => 'users',
                    'route' => ['super-admin.users.*', 'users.*'],
                    'permission' => 'users.view',
                    'roles' => ['super_admin'],
                ],
                [
                    'label' => 'Role & Permission',
                    'url' => 'roles_permissions',
                    'route' => ['super-admin.roles.*', 'roles.*', 'permissions.*', 'super-admin.permissions.*'],
                    'permission' => 'roles.view',
                    'roles' => ['super_admin'],
                ],
                [
                    'label' => 'Audit Log',
                    'url' => 'audit_logs',
                    'route' => ['audit-logs.*', 'super-admin.audit-logs.*'],
                    'permission' => 'audit_logs.view',
                    'roles' => ['super_admin', 'auditor'],
                ],
            ],
        ],
    ];

    /**
     * Get menus untuk current user
     */
    public function getMenus(): Collection
    {
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        return collect(self::MENU_STRUCTURE)
            ->filter(function ($menu) use ($user) {
                if ($menu['type'] ?? null === 'label') {
                    return false;
                }

                // For grouped menus, visibility follows accessible children.
                if (isset($menu['children'])) {
                    $menu['children'] = collect($menu['children'])
                        ->filter(function ($child) use ($user) {
                            // Check permission
                            if (isset($child['permission']) && !$user->can($child['permission'])) {
                                return false;
                            }

                            // Check role restrictions
                            if (isset($child['roles'])) {
                                return $user->hasAnyRole($child['roles']);
                            }

                            return true;
                        })
                        ->values()
                        ->toArray();

                    // Hide parent if no children are visible
                    if (empty($menu['children'])) {
                        return false;
                    }

                    return true;
                }

                if (isset($menu['permission']) && !$user->can($menu['permission'])) {
                    return false;
                }

                if (isset($menu['roles']) && !$user->hasAnyRole($menu['roles'])) {
                    return false;
                }

                return true;
            })
            ->values();
    }

    /**
     * Check if user can access specific menu
     */
    public function canAccessMenu(string $menuName): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $menu = $this->findMenu($menuName);

        if (!$menu) {
            return false;
        }

        if (isset($menu['children'])) {
            foreach ($menu['children'] as $child) {
                if (isset($child['permission']) && !$user->can($child['permission'])) {
                    continue;
                }

                if (isset($child['roles']) && !$user->hasAnyRole($child['roles'])) {
                    continue;
                }

                return true;
            }

            return false;
        }

        if (isset($menu['permission']) && !$user->can($menu['permission'])) {
            return false;
        }

        if (isset($menu['roles']) && !$user->hasAnyRole($menu['roles'])) {
            return false;
        }

        return true;
    }

    /**
     * Find menu by name
     */
    public function findMenu(string $name): ?array
    {
        foreach (self::MENU_STRUCTURE as $menu) {
            if (($menu['name'] ?? null) === $name) {
                return $menu;
            }

            if (isset($menu['children'])) {
                foreach ($menu['children'] as $child) {
                    if (($child['name'] ?? null) === $name) {
                        return $child;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get menu labels (group headers)
     */
    public function getMenuLabels(): Collection
    {
        $menus = $this->getMenus();
        $labels = collect();

        foreach (self::MENU_STRUCTURE as $item) {
            if (($item['type'] ?? null) === 'label') {
                $labels->push($item);
            }

            // Check if related menus exist
            if (isset($item['children'])) {
                foreach ($menus as $menu) {
                    if (($menu['name'] ?? null) === ($item['name'] ?? null)) {
                        $labels->push($item);
                        break;
                    }
                }
            }
        }

        return $labels->unique('label');
    }
}
