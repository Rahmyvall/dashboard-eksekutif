# Dokumentasi Implementasi Role-Based Sidebar Access Control

## Ringkasan

Sistem ini mengimplementasikan kontrol akses berbasis role untuk sidebar menu dengan granular permission checking. Setiap menu item dapat dilihat hanya oleh user yang memiliki permission dan role yang sesuai.

## Arsitektur

### 1. Permission System
- **Database**: `permissions` table menyimpan semua permissions
- **Associations**: `role_has_permissions` table menghubungkan role dengan permissions
- **Format Permission**: `feature.action` (contoh: `employees.view`, `invoices.create`)

### 2. Role Definition
Sistem mendefinisikan 9 role dalam `Role` model:

```
1. super_admin (super_administrator)
2. executive (direktur_utama)
3. hr (hrd_manager)
4. manager_departemen
5. karyawan
6. admin_pelayanan
7. admin_operasional
8. finance (finance_staff)
9. auditor (auditor_internal)
```

### 3. Menu Service (`App\Services\MenuService`)

Service yang mengelola:
- Menu structure definition
- Permission filtering
- Role-based menu visibility
- Menu route resolution

**Penggunaan di Blade:**
```blade
@php
    $menuService = app(MenuService::class);
    $menus = $menuService->getMenus(); // Get accessible menus for current user
@endphp

@foreach($menus as $menu)
    {{-- Render menu item --}}
@endforeach
```

### 4. Helper Functions

```php
// Cek apakah user dapat mengakses menu tertentu
canAccessMenu('master_data')

// Get semua menu yang dapat diakses
getAccessibleMenus()

// Cek role
userHasRole('super_admin') // atau array
userHasRole(['super_admin', 'executive'])

// Cek permission
userCan('employees.view')
userCan(['employees.view', 'employees.create'])
```

## Implementasi

### Step 1: Run Permission Seeder

```bash
php artisan db:seed --class=RolePermissionSeeder
```

Seeder ini akan:
1. Membuat semua permissions yang dibutuhkan
2. Mengassign permissions ke setiap role sesuai definisi
3. Handle multiple role name aliases untuk backward compatibility

### Step 2: Update Routes dengan Middleware (Opsional)

Untuk melindungi routes dari akses langsung:

```php
Route::middleware(['auth', 'check.menu.permission:employees.view'])
    ->group(function () {
        Route::resource('employees', EmployeeController::class);
    });
```

Atau gunakan dalam controller:

```php
public function __construct()
{
    $this->middleware('check.menu.permission:employees.view');
}
```

### Step 3: Update Sidebar View

Replace sidebar rendering di `resources/views/layouts/sidebar.blade.php` dengan component:

```blade
<x-sidebar-menu />
```

Atau include component dalam layout:

```blade
@include('components.sidebar-menu')
```

## Permission Mapping

### Master Data
- `branches.view` - Lihat Cabang
- `departments.view/create/edit/delete` - Kelola Departemen
- `positions.view/create/edit/delete` - Kelola Jabatan
- `employees.view/create/edit/delete` - Kelola Karyawan
- `employees.view_own` - Lihat Profil Sendiri
- `customers.view/create/edit/delete` - Kelola Pelanggan
- `service_categories.view/create/edit/delete` - Kelola Kategori Layanan
- `services.view/create/edit/delete` - Kelola Layanan

### Service Orders
- `service_orders.view/create/edit/delete` - Kelola Pesanan
- `service_order_items.view/create/edit/delete` - Kelola Item Pesanan
- `branch_approvals.view/approve/reject` - Kelola Persetujuan

### HR & Attendance
- `attendances.view/create/edit/delete` - Kelola Kehadiran
- `attendances.view_own` - Lihat Kehadiran Sendiri
- `attendances.checkin/checkout` - Check In/Out
- `leave_requests.view/create/approve/reject` - Kelola Cuti

### Performance
- `performance_indicators.view/create/edit/delete` - Kelola KPI
- `employee_targets.view/create/edit/delete` - Kelola Target
- `employee_performance.view` - Lihat Hasil Kinerja
- `employee_performance.view_own` - Lihat Kinerja Sendiri
- `performance_details.view` - Lihat Detail Penilaian
- `performance_details.view_own` - Lihat Penilaian Sendiri

### Finance
- `invoices.view/create/edit/approve` - Kelola Invoice
- `payments.view/create/edit/approve` - Kelola Pembayaran
- `expenses.view/create/edit/approve` - Kelola Pengeluaran

### Customer Service
- `customer_feedback.view/create/edit/delete` - Kelola Feedback
- `customer_complaints.view/create/edit/delete` - Kelola Keluhan

### Reports
- `reports.view` - Lihat Laporan
- `reports.export` - Export Laporan
- `reports.services` - Laporan Layanan
- `reports.performance` - Laporan Kinerja
- `reports.customers` - Laporan Pelanggan
- `reports.finance` - Laporan Keuangan

### System
- `users.view/create/edit/delete` - Kelola User
- `roles.view/create/edit/delete` - Kelola Role
- `permissions.view/create/edit/delete` - Kelola Permission
- `audit_logs.view` - Lihat Audit Log
- `system_settings.view/edit` - Pengaturan Sistem

## Role-Based Access

### Super Administrator
- ✅ Full access to all permissions and menus

### Direktur Utama (Executive)
- ✅ View-only access (Read Only)
- ✅ Dashboard, KPI, Laporan
- ✅ Cannot Create, Edit, Delete, Approve

### HRD Manager
- ✅ Penuh untuk SDM: Karyawan, Departemen, Jabatan
- ✅ Absensi & Cuti: Create, Approve
- ✅ Performance: Create, Edit Indicators, Periods, Roles, Targets
- ✅ Jadwal Kerja
- ✅ View Reports (HR & Performance)

### Manager Departemen
- ✅ Kelola karyawan dalam departemen saja
- ✅ Monitoring Absensi & Cuti (Approve untuk timnya)
- ✅ View Performance & Target
- ✅ View Jadwal & Aktivitas
- ❌ Cannot Create Master Data

### Karyawan
- ✅ View Profil Sendiri
- ✅ Check In / Check Out
- ✅ Lihat & Ajukan Cuti
- ✅ Lihat Jadwal & Aktivitas Sendiri
- ✅ Lihat Kinerja & Penilaian Sendiri

### Admin Pelayanan
- ✅ Kelola Pelanggan
- ✅ Create & Edit Service Orders & Items
- ✅ Kelola Feedback & Complaints
- ✅ Create Invoice
- ✅ View Reports (Services & Customers)

### Admin Operasional
- ✅ View Service Orders
- ✅ Create & Edit Jadwal & Penugasan
- ✅ Kelola Aktivitas Karyawan
- ✅ View Performance
- ✅ View Reports (Services & Performance)

### Finance Staff
- ✅ View & Create Invoice
- ✅ Create & Approve Payment
- ✅ Create & Approve Expense
- ✅ View Reports (Finance)

### Auditor Internal
- ✅ View-only (Read Only)
- ✅ Access all data untuk audit
- ✅ View & Export Audit Logs
- ✅ Cannot Create, Edit, Delete

## Testing

### 1. Verify Permissions Loaded
```bash
php artisan tinker
>>> \App\Models\Role::with('permissions')->where('name', 'super_admin')->first()
```

### 2. Test Menu Visibility
```blade
@php
    $menus = getAccessibleMenus();
    dd($menus);
@endphp
```

### 3. Test Permission Check
```blade
@can('employees.view')
    <p>User dapat view employees</p>
@endcan

@if(userCan('employees.create'))
    <p>User dapat create employees</p>
@endif
```

### 4. Direct URL Access Protection
URL akan protected oleh middleware di route. User tanpa permission akan mendapat error 403.

## Maintenance

### Adding New Permission

1. Edit `RolePermissionSeeder::run()` - tambah ke `$permissions` array
2. Assign ke role yang tepat dalam seeder
3. Update `MenuService::MENU_STRUCTURE` jika perlu menu item baru
4. Re-run seeder:
   ```bash
   php artisan db:seed --class=RolePermissionSeeder
   ```

### Adding New Role

1. Tambah constant di `Role` model
2. Tambah aliases di `RoleMiddleware` (jika ada)
3. Tambah role logic di seeder
4. Update `MenuService::MENU_STRUCTURE` untuk define menus
5. Run seeder

### Updating Menu Structure

Edit `MenuService::MENU_STRUCTURE`:
- Ubah labels
- Tambah/hapus menu items
- Adjust role restrictions di children array

## Troubleshooting

### Menu tidak muncul untuk user dengan role yang sesuai?

1. Verify permissions dimuat: `php artisan tinker`
2. Check user memiliki role yang benar: `auth()->user()->getRoleNames()`
3. Check user memiliki permission: `auth()->user()->can('permission_name')`
4. Check MenuService definition - ensure permission dan role match

### Permission error saat access route?

1. Verify middleware registered di route
2. Check permission name di middleware parameter
3. Verify user memiliki permission: `auth()->user()->can('permission')`
4. Check spelling dan consistency

### Sidebar-menu component tidak render?

1. Verify component path: `resources/views/components/sidebar-menu.blade.php`
2. Check helpers loaded di AppServiceProvider
3. Verify MenuService syntax tanpa error
4. Check cache: `php artisan cache:clear && php artisan view:clear`

## Best Practices

1. **Permission Naming**: Gunakan format `feature.action` untuk consistency
2. **Role Aliases**: Update RoleMiddleware jika ada nama role baru
3. **Menu Labels**: Gunakan bahasa yang jelas dan konsisten
4. **Route Groups**: Kelompokkan routes berdasarkan role/permission
5. **Testing**: Buat tests untuk setiap role permission combination
6. **Documentation**: Keep this doc updated saat ada perubahan

## Future Enhancements

1. Permission UI Management - Admin bisa assign permissions tanpa seeder
2. Audit Trail - Log semua permission changes
3. Dynamic Menu Configuration - Store menu config di database
4. Role Templates - Quick role creation berdasarkan template
5. Permission Expiry - Temporary permissions dengan expiration date
