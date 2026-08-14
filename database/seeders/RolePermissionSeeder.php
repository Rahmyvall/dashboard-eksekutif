<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks untuk mencegah error (MySQL specific)
        $driver = \DB::getDriverName();
        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // Clear existing data
        try {
            \DB::table('permission_role')->truncate();
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        // ====================================================================
        // DEFINE PERMISSIONS
        // ====================================================================

        $permissions = [
            // Dashboard
            'dashboard.view' => 'Lihat Dashboard',
            'dashboard.executive.view' => 'Lihat Dashboard Eksekutif',

            // Master Data - Branches
            'branches.view' => 'Lihat Cabang',
            'branches.create' => 'Buat Cabang',
            'branches.edit' => 'Edit Cabang',
            'branches.delete' => 'Hapus Cabang',

            // Master Data - Departments
            'departments.view' => 'Lihat Departemen',
            'departments.create' => 'Buat Departemen',
            'departments.edit' => 'Edit Departemen',
            'departments.delete' => 'Hapus Departemen',

            // Master Data - Positions
            'positions.view' => 'Lihat Jabatan',
            'positions.create' => 'Buat Jabatan',
            'positions.edit' => 'Edit Jabatan',
            'positions.delete' => 'Hapus Jabatan',

            // Master Data - Employees
            'employees.view' => 'Lihat Karyawan',
            'employees.create' => 'Buat Karyawan',
            'employees.edit' => 'Edit Karyawan',
            'employees.delete' => 'Hapus Karyawan',
            'employees.view_own' => 'Lihat Profil Sendiri',
            'employees.edit_own' => 'Edit Profil Sendiri',

            // Master Data - Customers
            'customers.view' => 'Lihat Pelanggan',
            'customers.create' => 'Buat Pelanggan',
            'customers.edit' => 'Edit Pelanggan',
            'customers.delete' => 'Hapus Pelanggan',

            // Master Data - Service Categories
            'service_categories.view' => 'Lihat Kategori Layanan',
            'service_categories.create' => 'Buat Kategori Layanan',
            'service_categories.edit' => 'Edit Kategori Layanan',
            'service_categories.delete' => 'Hapus Kategori Layanan',

            // Master Data - Services
            'services.view' => 'Lihat Data Layanan',
            'services.create' => 'Buat Layanan',
            'services.edit' => 'Edit Layanan',
            'services.delete' => 'Hapus Layanan',

            // Service Orders
            'service_orders.view' => 'Lihat Pesanan Layanan',
            'service_orders.create' => 'Buat Pesanan Layanan',
            'service_orders.edit' => 'Edit Pesanan Layanan',
            'service_orders.delete' => 'Hapus Pesanan Layanan',
            'service_orders.approve' => 'Setujui Pesanan Layanan',

            // Service Order Items
            'service_order_items.view' => 'Lihat Item Pesanan',
            'service_order_items.create' => 'Buat Item Pesanan',
            'service_order_items.edit' => 'Edit Item Pesanan',
            'service_order_items.delete' => 'Hapus Item Pesanan',

            // Branch Approval
            'branch_approvals.view' => 'Lihat Persetujuan Cabang',
            'branch_approvals.approve' => 'Setujui Pesanan Cabang',
            'branch_approvals.reject' => 'Tolak Pesanan Cabang',

            // Work Schedules
            'work_schedules.view' => 'Lihat Jadwal Kerja',
            'work_schedules.create' => 'Buat Jadwal Kerja',
            'work_schedules.edit' => 'Edit Jadwal Kerja',
            'work_schedules.delete' => 'Hapus Jadwal Kerja',

            // Employee Schedules
            'employee_schedules.view' => 'Lihat Penugasan Karyawan',
            'employee_schedules.create' => 'Buat Penugasan Karyawan',
            'employee_schedules.edit' => 'Edit Penugasan Karyawan',
            'employee_schedules.delete' => 'Hapus Penugasan Karyawan',
            'employee_schedules.view_own' => 'Lihat Jadwal Kerja Saya',

            // Employee Activities
            'employee_activities.view' => 'Lihat Aktivitas Karyawan',
            'employee_activities.create' => 'Buat Aktivitas Karyawan',
            'employee_activities.edit' => 'Edit Aktivitas Karyawan',
            'employee_activities.delete' => 'Hapus Aktivitas Karyawan',
            'employee_activities.view_own' => 'Lihat Aktivitas Saya',

            // Service Order Status
            'service_order_status.view' => 'Lihat Riwayat Status Pesanan',
            'service_order_status.create' => 'Buat Status Pesanan',
            'service_order_status.edit' => 'Edit Status Pesanan',

            // Attendances
            'attendances.view' => 'Lihat Kehadiran',
            'attendances.create' => 'Buat Kehadiran',
            'attendances.edit' => 'Edit Kehadiran',
            'attendances.delete' => 'Hapus Kehadiran',
            'attendances.view_own' => 'Lihat Kehadiran Saya',
            'attendances.checkin' => 'Check In',
            'attendances.checkout' => 'Check Out',

            // Leave Requests
            'leave_requests.view' => 'Lihat Pengajuan Cuti',
            'leave_requests.create' => 'Buat Pengajuan Cuti',
            'leave_requests.edit' => 'Edit Pengajuan Cuti',
            'leave_requests.delete' => 'Hapus Pengajuan Cuti',
            'leave_requests.approve' => 'Setujui Pengajuan Cuti',
            'leave_requests.reject' => 'Tolak Pengajuan Cuti',
            'leave_requests.view_own' => 'Lihat Pengajuan Cuti Saya',

            // Performance Indicators
            'performance_indicators.view' => 'Lihat Indikator Kinerja',
            'performance_indicators.create' => 'Buat Indikator Kinerja',
            'performance_indicators.edit' => 'Edit Indikator Kinerja',
            'performance_indicators.delete' => 'Hapus Indikator Kinerja',

            // Performance Periods
            'performance_periods.view' => 'Lihat Periode Penilaian',
            'performance_periods.create' => 'Buat Periode Penilaian',
            'performance_periods.edit' => 'Edit Periode Penilaian',
            'performance_periods.delete' => 'Hapus Periode Penilaian',

            // Performance Roles
            'performance_roles.view' => 'Lihat Bobot Kinerja per Role',
            'performance_roles.create' => 'Buat Bobot Kinerja',
            'performance_roles.edit' => 'Edit Bobot Kinerja',
            'performance_roles.delete' => 'Hapus Bobot Kinerja',

            // Employee Targets
            'employee_targets.view' => 'Lihat Target Karyawan',
            'employee_targets.create' => 'Buat Target Karyawan',
            'employee_targets.edit' => 'Edit Target Karyawan',
            'employee_targets.delete' => 'Hapus Target Karyawan',

            // Employee Performance
            'employee_performance.view' => 'Lihat Hasil Kinerja',
            'employee_performance.create' => 'Buat Hasil Kinerja',
            'employee_performance.edit' => 'Edit Hasil Kinerja',
            'employee_performance.delete' => 'Hapus Hasil Kinerja',
            'employee_performance.view_own' => 'Lihat Hasil Kinerja Saya',

            // Performance Details
            'performance_details.view' => 'Lihat Detail Penilaian',
            'performance_details.create' => 'Buat Detail Penilaian',
            'performance_details.edit' => 'Edit Detail Penilaian',
            'performance_details.delete' => 'Hapus Detail Penilaian',
            'performance_details.view_own' => 'Lihat Detail Penilaian Saya',

            // Customer Feedback
            'customer_feedback.view' => 'Lihat Feedback Pelanggan',
            'customer_feedback.create' => 'Buat Feedback Pelanggan',
            'customer_feedback.edit' => 'Edit Feedback Pelanggan',
            'customer_feedback.delete' => 'Hapus Feedback Pelanggan',

            // Customer Complaints
            'customer_complaints.view' => 'Lihat Keluhan Pelanggan',
            'customer_complaints.create' => 'Buat Keluhan Pelanggan',
            'customer_complaints.edit' => 'Edit Keluhan Pelanggan',
            'customer_complaints.delete' => 'Hapus Keluhan Pelanggan',

            // Invoices
            'invoices.view' => 'Lihat Invoice',
            'invoices.create' => 'Buat Invoice',
            'invoices.edit' => 'Edit Invoice',
            'invoices.delete' => 'Hapus Invoice',
            'invoices.approve' => 'Setujui Invoice',

            // Payments
            'payments.view' => 'Lihat Pembayaran',
            'payments.create' => 'Buat Pembayaran',
            'payments.edit' => 'Edit Pembayaran',
            'payments.delete' => 'Hapus Pembayaran',
            'payments.approve' => 'Setujui Pembayaran',

            // Expenses
            'expenses.view' => 'Lihat Pengeluaran',
            'expenses.create' => 'Buat Pengeluaran',
            'expenses.edit' => 'Edit Pengeluaran',
            'expenses.delete' => 'Hapus Pengeluaran',
            'expenses.approve' => 'Setujui Pengeluaran',

            // Reports
            'reports.view' => 'Lihat Laporan',
            'reports.export' => 'Export Laporan',
            'reports.services' => 'Lihat Laporan Layanan',
            'reports.performance' => 'Lihat Laporan Kinerja',
            'reports.customers' => 'Lihat Laporan Pelanggan',
            'reports.complaints' => 'Lihat Laporan Keluhan',
            'reports.finance' => 'Lihat Laporan Keuangan',
            'reports.hr' => 'Lihat Laporan HR',

            // Audit Log
            'audit_logs.view' => 'Lihat Audit Log',
            'audit_logs.export' => 'Export Audit Log',

            // User Management
            'users.view' => 'Lihat User',
            'users.create' => 'Buat User',
            'users.edit' => 'Edit User',
            'users.delete' => 'Hapus User',

            // Roles & Permissions
            'roles.view' => 'Lihat Role',
            'roles.create' => 'Buat Role',
            'roles.edit' => 'Edit Role',
            'roles.delete' => 'Hapus Role',
            'permissions.view' => 'Lihat Permission',
            'permissions.create' => 'Buat Permission',
            'permissions.edit' => 'Edit Permission',
            'permissions.delete' => 'Hapus Permission',

            // System Settings
            'system_settings.view' => 'Lihat Pengaturan Sistem',
            'system_settings.edit' => 'Edit Pengaturan Sistem',
            'system_settings.backup' => 'Backup Database',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['display_name' => $displayName]
            );
        }

        // ====================================================================
        // ASSIGN PERMISSIONS TO ROLES
        // ====================================================================

        // 1. SUPER ADMINISTRATOR - Full Access
        $superAdmin = Role::whereIn('name', ['Super Administrator', 'super_admin', 'super_administrator'])->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::all()->pluck('id')->toArray());
        }

        // 2. DIREKTUR UTAMA - Read-Only Dashboard & Reports
        $direktur = Role::whereIn('name', ['Direktur Manager', 'Direktur Utama', 'executive', 'direktur_utama'])->first();
        if ($direktur) {
            $direkturPermissions = [
                // Dashboard
                'dashboard.view',
                'dashboard.executive.view',

                // Read-only access to various data
                'branches.view',
                'departments.view',
                'positions.view',
                'employees.view',
                'customers.view',
                'services.view',
                'service_categories.view',

                // Service monitoring
                'service_orders.view',
                'service_order_items.view',
                'service_order_status.view',
                'work_schedules.view',
                'employee_schedules.view',
                'employee_activities.view',

                // HR monitoring
                'attendances.view',
                'leave_requests.view',

                // Performance
                'performance_indicators.view',
                'performance_periods.view',
                'performance_roles.view',
                'employee_targets.view',
                'employee_performance.view',
                'performance_details.view',

                // Customer
                'customers.view',
                'customer_feedback.view',
                'customer_complaints.view',

                // Finance - Read Only
                'invoices.view',
                'payments.view',
                'expenses.view',

                // Reports
                'reports.view',
                'reports.export',
                'reports.services',
                'reports.performance',
                'reports.customers',
                'reports.complaints',
                'reports.finance',
                'reports.hr',
            ];

            $this->syncRolePermissions($direktur, $direkturPermissions);
        }

        // 3. HRD MANAGER - SDM Management
        $hrd = Role::whereIn('name', ['HRD Manager', 'hr_manager', 'hrd_manager', 'hr'])->first();
        if ($hrd) {
            $hrdPermissions = [
                'dashboard.view',

                // Master Data - SDM Related
                'departments.view',
                'departments.create',
                'departments.edit',
                'positions.view',
                'positions.create',
                'positions.edit',
                'employees.view',
                'employees.create',
                'employees.edit',
                'employees.delete',

                // Attendance & Leave
                'attendances.view',
                'attendances.create',
                'attendances.edit',
                'leave_requests.view',
                'leave_requests.create',
                'leave_requests.approve',
                'leave_requests.reject',

                // Performance
                'performance_indicators.view',
                'performance_indicators.create',
                'performance_indicators.edit',
                'performance_periods.view',
                'performance_periods.create',
                'performance_periods.edit',
                'performance_roles.view',
                'performance_roles.create',
                'performance_roles.edit',
                'employee_targets.view',
                'employee_targets.create',
                'employee_targets.edit',
                'employee_performance.view',
                'performance_details.view',

                // Work Schedules
                'work_schedules.view',
                'work_schedules.create',
                'work_schedules.edit',
                'employee_schedules.view',
                'employee_schedules.create',
                'employee_schedules.edit',

                // Reports
                'reports.view',
                'reports.export',
                'reports.hr',
                'reports.performance',

                // System - Limited
                'audit_logs.view',
            ];

            $this->syncRolePermissions($hrd, $hrdPermissions);
        }

        // 4. MANAGER DEPARTEMEN - Department Team Management
        $managerDept = Role::whereIn('name', ['Manager Departemen', 'manager_departemen'])->first();
        if ($managerDept) {
            $managerPermissions = [
                'dashboard.view',

                // View department members
                'branches.view',
                'employees.view',
                'employee_activities.view',
                'attendances.view',
                'leave_requests.view',
                'leave_requests.approve',
                'leave_requests.reject',

                // Performance - Team level
                'employee_targets.view',
                'employee_performance.view',
                'performance_details.view',

                // Reports
                'reports.view',
                'reports.export',
                'reports.hr',
                'reports.performance',
            ];

            $this->syncRolePermissions($managerDept, $managerPermissions);
        }

        // 5. KARYAWAN - Personal Access Only
        $karyawan = Role::whereIn('name', ['Karyawan', 'karyawan'])->first();
        if ($karyawan) {
            $karyawanPermissions = [
                'dashboard.view',

                // Own profile
                'employees.view_own',
                'employees.edit_own',

                // Own attendance
                'attendances.view_own',
                'attendances.checkin',
                'attendances.checkout',

                // Own leave requests
                'leave_requests.view_own',
                'leave_requests.create',

                // Own schedules
                'employee_schedules.view_own',
                'employee_activities.view_own',

                // Own performance
                'employee_performance.view_own',
                'performance_details.view_own',
            ];

            $this->syncRolePermissions($karyawan, $karyawanPermissions);
        }

        // 6. ADMIN PELAYANAN - Service Customer Management
        $adminPelayanan = Role::whereIn('name', ['Admin Pelayanan', 'admin_pelayanan'])->first();
        if ($adminPelayanan) {
            $adminPelayananPermissions = [
                'dashboard.view',

                // Master Data
                'customers.view',
                'customers.create',
                'customers.edit',
                'services.view',
                'service_categories.view',

                // Service Orders
                'service_orders.view',
                'service_orders.create',
                'service_orders.edit',
                'service_order_items.view',
                'service_order_items.create',
                'service_order_items.edit',

                // Customer Related
                'customer_feedback.view',
                'customer_feedback.create',
                'customer_complaints.view',
                'customer_complaints.create',

                // Invoices
                'invoices.view',
                'invoices.create',
                'invoices.edit',

                // Reports
                'reports.view',
                'reports.export',
                'reports.services',
                'reports.customers',
                'reports.complaints',
            ];

            $this->syncRolePermissions($adminPelayanan, $adminPelayananPermissions);
        }

        // 7. ADMIN OPERASIONAL - Field Operations Management
        $adminOperasional = Role::whereIn('name', ['Admin Operasional', 'admin_operasional'])->first();
        if ($adminOperasional) {
            $adminOperasionalPermissions = [
                'dashboard.view',

                // Master Data
                'employees.view',
                'customers.view',
                'services.view',
                'service_categories.view',

                // Service Orders
                'service_orders.view',
                'service_orders.create',
                'service_orders.edit',
                'service_order_items.view',
                'service_order_status.view',

                // Schedules & Assignments
                'work_schedules.view',
                'work_schedules.create',
                'work_schedules.edit',
                'employee_schedules.view',
                'employee_schedules.create',
                'employee_schedules.edit',

                // Employee Activities
                'employee_activities.view',
                'employee_activities.create',
                'employee_activities.edit',

                // Performance
                'employee_performance.view',

                // Reports
                'reports.view',
                'reports.export',
                'reports.services',
                'reports.performance',
            ];

            $this->syncRolePermissions($adminOperasional, $adminOperasionalPermissions);
        }

        // 8. FINANCE STAFF - Finance & Expense Management
        $financeStaff = Role::whereIn('name', ['Finance Staff', 'finance_staff', 'finance', 'keuangan'])->first();
        if ($financeStaff) {
            $financePermissions = [
                'dashboard.view',

                // Service Orders
                'service_orders.view',
                'service_order_items.view',

                // Invoices
                'invoices.view',
                'invoices.create',
                'invoices.edit',
                'invoices.approve',

                // Payments
                'payments.view',
                'payments.create',
                'payments.edit',
                'payments.approve',

                // Expenses
                'expenses.view',
                'expenses.create',
                'expenses.edit',
                'expenses.approve',

                // Reports
                'reports.view',
                'reports.export',
                'reports.services',
                'reports.finance',
            ];

            $this->syncRolePermissions($financeStaff, $financePermissions);
        }

        // 9. AUDITOR INTERNAL - Audit & Monitoring (Read-Only)
        $auditor = Role::whereIn('name', ['Auditor Internal', 'auditor_internal', 'auditor'])->first();
        if ($auditor) {
            $auditorPermissions = [
                'dashboard.view',

                // View all master data (read-only)
                'branches.view',
                'departments.view',
                'positions.view',
                'employees.view',
                'customers.view',
                'services.view',
                'service_categories.view',

                // Service monitoring
                'service_orders.view',
                'service_order_items.view',
                'service_order_status.view',
                'work_schedules.view',
                'employee_schedules.view',
                'employee_activities.view',

                // HR monitoring
                'attendances.view',
                'leave_requests.view',

                // Performance monitoring
                'performance_indicators.view',
                'employee_targets.view',
                'employee_performance.view',
                'performance_details.view',

                // Finance monitoring
                'invoices.view',
                'payments.view',
                'expenses.view',

                // Customer monitoring
                'customers.view',
                'customer_feedback.view',
                'customer_complaints.view',

                // Audit Log
                'audit_logs.view',
                'audit_logs.export',

                // Reports
                'reports.view',
                'reports.export',
                'reports.services',
                'reports.performance',
                'reports.customers',
                'reports.complaints',
                'reports.finance',
                'reports.hr',
            ];

            $this->syncRolePermissions($auditor, $auditorPermissions);
        }

        // Re-enable foreign key checks (MySQL specific)
        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        echo "Permissions dan role assignments berhasil dibuat!\n";
    }

    /**
     * Helper method to sync permissions by name
     */
    private function syncRolePermissions(Role $role, array $permissionNames): void
    {
        $permissionIds = Permission::whereIn('name', $permissionNames)
            ->pluck('id')
            ->toArray();

        $role->permissions()->sync($permissionIds);
    }
}
