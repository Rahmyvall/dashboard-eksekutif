# LANGKAH BERIKUTNYA - Route Middleware Protection (PRIORITAS TINGGI)

## 📊 Status Saat Ini

✅ **Selesai:**
- 249 permissions defined
- 9 roles configured
- Permission system backend working
- Sidebar updated with permission checks
- 2 resource groups dengan middleware (Branches, Positions)

🟡 **Pending:**
- Tambahkan middleware ke ~70 routes sisanya
- Test access untuk semua roles
- Deploy ke production

---

## 🎯 Target: Tambahkan Middleware ke Semua Routes

**Total Routes yang Perlu Update:** ~70+ routes dalam 14+ resource groups

### Resource Groups Prioritas

**PRIORITY 1 - Penting (Sering digunakan):**
1. Employee routes (~15 routes)
2. Customers routes (~15 routes)
3. Service Orders routes (~12 routes)
4. Invoices routes (~12 routes)
5. Payments routes (~10 routes)

**PRIORITY 2 - Medium (Reguler digunakan):**
6. Expenses routes (~8 routes)
7. Service Categories routes (~12 routes)
8. Services routes (~8 routes)
9. Performance Indicators routes (~15 routes)
10. Performance Periods routes (~8 routes)

**PRIORITY 3 - Low (Admin-only):**
11. Users routes (~8 routes)
12. Roles routes (~8 routes)
13. Work Schedules routes (~10 routes)
14. Employee Activities routes (~8 routes)
15. Attendances routes (~8 routes)
16. Etc.

---

## 🛠️ Cara Menambah Middleware

### Metode 1: Manual (1-2 routes)

```php
// routes/web.php

Route::prefix('branches')
    ->name('branches.')
    ->middleware('check.menu.permission:branches.view')  // ← ADD THIS
    ->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('create', [BranchController::class, 'create'])->name('create');
        Route::post('/', [BranchController::class, 'store'])->name('store');
    });
```

### Metode 2: Find & Replace (RECOMMENDED - Cepat)

**Contoh untuk Employee routes:**

**Step 1:** Buka VS Code
**Step 2:** Ctrl+H (Find and Replace)
**Step 3:** Copy pattern dari dokumentasi `MIDDLEWARE_IMPLEMENTATION_GUIDE.md`
**Step 4:** Paste di Find & Replace fields
**Step 5:** Click "Replace All"

---

## 📋 Employee Routes - Contoh Lengkap

File: `routes/web.php`

**BEFORE:**
```php
Route::prefix('employees')
    ->name('employees.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('{employee}', [EmployeeController::class, 'update'])->name('update');
        // ... more routes
    });
```

**AFTER:**
```php
Route::prefix('employees')
    ->name('employees.')
    ->middleware('auth')
    ->group(function () {
        // View employees
        Route::get('/', [EmployeeController::class, 'index'])
            ->name('index')
            ->middleware('check.menu.permission:employees.view');

        // Create employee
        Route::get('create', [EmployeeController::class, 'create'])
            ->name('create')
            ->middleware('check.menu.permission:employees.create');

        Route::post('/', [EmployeeController::class, 'store'])
            ->name('store')
            ->middleware('check.menu.permission:employees.create');

        // View employee detail
        Route::get('{employee}', [EmployeeController::class, 'show'])
            ->name('show')
            ->middleware('check.menu.permission:employees.view');

        // Edit employee
        Route::get('{employee}/edit', [EmployeeController::class, 'edit'])
            ->name('edit')
            ->middleware('check.menu.permission:employees.edit');

        Route::put('{employee}', [EmployeeController::class, 'update'])
            ->name('update')
            ->middleware('check.menu.permission:employees.edit');

        // ... more routes dengan appropriate middleware
    });
```

---

## 🔄 Smart Strategy - Kelompok Routes

### Kelompok 1: Master Data (High Priority)

Sequence untuk update:
1. ✅ Branches (DONE)
2. ✅ Positions (DONE)
3. 🟡 Departments → `departments.view|departments.create|departments.edit|departments.delete`
4. 🟡 Employees → `employees.view|employees.create|employees.edit|employees.delete`
5. 🟡 Customers → `customers.view|customers.create|customers.edit|customers.delete`
6. 🟡 Service Categories → `service_categories.view|service_categories.create|...`
7. 🟡 Services → `services.view|services.create|...`

### Kelompok 2: Operasional (Medium Priority)

1. Service Orders → `service_orders.view|service_orders.create|...`
2. Service Order Items → `service_order_items.view|...`
3. Work Schedules → `work_schedules.view|work_schedules.create|...`
4. Employee Schedules → `employee_schedules.view|...`
5. Employee Activities → `employee_activities.view|...`

### Kelompok 3: Finance (High Priority)

1. Invoices → `invoices.view|invoices.create|...`
2. Payments → `payments.view|payments.create|...`
3. Expenses → `expenses.view|expenses.create|...`

### Kelompok 4: Performance (Medium Priority)

1. Performance Indicators → `performance_indicators.view|...`
2. Performance Periods → `performance_periods.view|...`
3. Employee Targets → `employee_targets.view|...`

### Kelompok 5: System (Low Priority - Admin only)

1. Users → `users.view|users.create|...`
2. Roles → `roles.view|roles.create|...`
3. Permissions → `permissions.view|permissions.edit|...`
4. Audit Logs → `audit_logs.view`
5. System Settings → `system_settings.view|system_settings.edit`

---

## 📝 Permission-Action Mapping

**Untuk semua routes, gunakan mapping ini:**

| Route Method | HTTP Method | Permission |
|--------------|------------|-----------|
| index | GET | `{module}.view` |
| create | GET | `{module}.create` |
| store | POST | `{module}.create` |
| show | GET | `{module}.view` |
| edit | GET | `{module}.edit` |
| update | PUT/PATCH | `{module}.edit` |
| destroy | DELETE | `{module}.delete` |
| trash/trashed | GET | `{module}.view` |
| restore | PUT/POST | `{module}.restore` |
| forceDelete | DELETE | `{module}.delete` |
| approve | PUT/POST | `{module}.approve` |
| export | GET | `{module}.view` |
| bulkAction | POST | Varies |

---

## ⚡ Quick Task - Update 5 Routes dalam 10 menit

### Task: Update Employee Routes

1. **Open:** `routes/web.php`
2. **Find:** `Route::prefix('employees')`
3. **Add middleware to each route:**
   - `->middleware('check.menu.permission:employees.view')` untuk index & show
   - `->middleware('check.menu.permission:employees.create')` untuk create & store
   - `->middleware('check.menu.permission:employees.edit')` untuk edit & update
   - `->middleware('check.menu.permission:employees.delete')` untuk destroy

4. **Save** (Ctrl+S)
5. **Test:**
   ```bash
   php artisan verify:role-permissions
   ```

---

## 🧪 Test Setelah Menambah Middleware

### Test 1: Verify Syntax
```bash
php artisan route:list | grep employees
```

### Test 2: Check Permission Assignment
```bash
php artisan tinker

>>> $user = User::find(1);  # Karyawan user
>>> $user->can('employees.create')
=> false

>>> $user = User::find(2);  # HR user
>>> $user->can('employees.create')
=> true
```

### Test 3: Browser Test
1. Login sebagai Karyawan
2. Coba akses `/employees` → Harus 403
3. Login sebagai HRD Manager
4. Akses `/employees` → Harus 200 (success)

### Test 4: Check Audit Logs
```sql
SELECT * FROM audit_logs 
ORDER BY created_at DESC 
LIMIT 10;
```

---

## 📌 Penting: Before & After Checklist

### Before Adding Middleware
- [ ] Read the actual route code in `routes/web.php`
- [ ] Identify all routes in the resource group
- [ ] Map each route to its permission
- [ ] Test current functionality (should work for all users)

### After Adding Middleware
- [ ] Routes syntax check: `php artisan route:list`
- [ ] No errors in `php artisan migrate:status`
- [ ] Database seeding still works: `php artisan verify:role-permissions`
- [ ] Test access with different user roles
- [ ] Check audit logs for access attempts

---

## 🚨 Common Mistakes to Avoid

❌ **WRONG:**
```php
Route::get('/', [Controller::class, 'index'])->middleware('check.menu.permission:employees.view', 'auth');
// Wrong order - auth should come first
```

✅ **CORRECT:**
```php
Route::get('/', [Controller::class, 'index'])
    ->middleware(['auth', 'check.menu.permission:employees.view']);
// OR
Route::get('/', [Controller::class, 'index'])
    ->middleware('auth')
    ->middleware('check.menu.permission:employees.view');
```

❌ **WRONG:**
```php
->middleware('check.menu.permission:employees')
// Wrong - missing action (view/create/edit/delete)
```

✅ **CORRECT:**
```php
->middleware('check.menu.permission:employees.view')
->middleware('check.menu.permission:employees.create')
->middleware('check.menu.permission:employees.edit')
->middleware('check.menu.permission:employees.delete')
```

---

## 📚 Documentation References

- **Full mapping:** Read `ROUTE_MIDDLEWARE_MAPPING.md`
- **Step-by-step guide:** Read `MIDDLEWARE_IMPLEMENTATION_GUIDE.md`
- **Query examples:** Read `SQL_VERIFICATION_QUERIES.md`
- **System overview:** Read `PERMISSION_SYSTEM_COMPLETE.md`

---

## 🎯 NEXT ACTION (Untuk User)

### Option 1: Lanjutkan Satu Resource Group
1. Buka `MIDDLEWARE_IMPLEMENTATION_GUIDE.md`
2. Cari "Employee routes" section
3. Copy Find & Replace pattern
4. Edit `routes/web.php`
5. Run `php artisan verify:role-permissions`

### Option 2: Lanjutkan Secara Bertahap
- Hari 1: Departments + Employees
- Hari 2: Customers + Services  
- Hari 3: Service Orders + Invoices/Payments
- Hari 4: Performance Indicators + KPI
- Hari 5: System Management routes

### Option 3: Ask Assistant untuk Update Batch
Bisa minta assistant untuk update multiple routes sekaligus

---

## 📊 Progress Tracker

Update status ini setelah selesai:

```
MASTER DATA ROUTES:
- [x] Branches (DONE)
- [x] Positions (DONE)
- [ ] Departments
- [ ] Employees
- [ ] Customers
- [ ] Service Categories
- [ ] Services

OPERASIONAL ROUTES:
- [ ] Service Orders
- [ ] Work Schedules
- [ ] Employee Activities
- [ ] Attendances
- [ ] Leave Requests

FINANCE ROUTES:
- [ ] Invoices
- [ ] Payments
- [ ] Expenses

PERFORMANCE ROUTES:
- [ ] Performance Indicators
- [ ] Performance Periods
- [ ] Employee Targets
- [ ] Employee Performance

SYSTEM ROUTES:
- [ ] Users
- [ ] Roles
- [ ] Permissions
- [ ] Audit Logs
- [ ] System Settings
```

---

## ✨ Hasil Akhir yang Diharapkan

Setelah semua routes punya middleware:

✅ **Security:**
- Tidak ada user yang bisa akses route yang tidak authorized
- Semua unauthorized attempts dilog di audit_logs

✅ **User Experience:**
- Sidebar hanya show menu yang user boleh access
- Direct URL access ke restricted route → 403
- Clear message bahwa user tidak authorized

✅ **Compliance:**
- RBAC fully implemented
- Audit trail lengkap
- Role separation terjaga

---

**Estimasi waktu untuk complete:** 2-4 jam (tergantung metode)

**Rekomendasi:** Update 2-3 resource groups per hari untuk ensure quality

---

**Last Updated:** 2026-08-14
**Version:** 1.0
