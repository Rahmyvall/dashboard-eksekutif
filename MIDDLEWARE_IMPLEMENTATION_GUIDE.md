# IMPLEMENTASI MIDDLEWARE PERMISSION - PANDUAN LANGKAH DEMI LANGKAH

## Status Saat Ini

✅ **Sudah Selesai:**
- Branch routes: middleware sudah ditambahkan ke create, store, index, show, delete, restore, approve
- Position routes: index & show dengan middleware `check.menu.permission:positions.view`
- Dokumentasi lengkap: `ROUTE_MIDDLEWARE_MAPPING.md`

❌ **Masih Perlu Dilakukan:**
- Employee, Customer, Service, Invoice, Payment routes
- Work Schedule, Employee Activity, Attendance routes
- Perform ance Indicator dan Performance Period routes
- Users dan Roles routes

---

## Cara Tercepat: Gunakan Find & Replace di VS Code

### Step 1: Buka Find & Replace
**Keyboard:** `Ctrl + H`

### Step 2: Replace Pattern - Employee Routes

**Find:**
```
Route::get('/create', 'create')
                                ->name('create');

                            Route::post('/', 'store')
                                ->name('store');
                        });

                    /*
                    |----------------------------------------------------------
                    | Recycle BinÂ€"khususSuperAdmin

                    |----------------------------------------------------------
                    |
                    | Route statis wajib berada sebelum /{employee}.
                    |
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/trash', 'trash')
                                ->name('trash');

                            Route::post('/{id}/restore', 'restore')
                                ->whereNumber('id')
                                ->name('restore');

                            Route::delete('/{id}/force-delete', 'forceDelete')
                                ->whereNumber('id')
                                ->name('force-delete');
                        });

                    /*
                    |----------------------------------------------------------
                    | Edit, Update, dan Soft Delete
                    |----------------------------------------------------------
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/{employee}/edit', 'edit')
                                ->whereNumber('employee')
                                ->name('edit');

                            Route::match(
                                ['put', 'patch'],
                                '/{employee}',
                                'update'
                            )
                                ->whereNumber('employee')
                                ->name('update');

                            Route::delete('/{employee}', 'destroy')
                                ->whereNumber('employee')
                                ->name('destroy');
                        });
```

**Replace:**
```
Route::get('/create', 'create')
                                ->middleware('check.menu.permission:employees.create')
                                ->name('create');

                            Route::post('/', 'store')
                                ->middleware('check.menu.permission:employees.create')
                                ->name('store');
                        });

                    /*
                    |----------------------------------------------------------
                    | Recycle BinÂ€"khususSuperAdmin

                    |----------------------------------------------------------
                    |
                    | Route statis wajib berada sebelum /{employee}.
                    |
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/trash', 'trash')
                                ->middleware('check.menu.permission:employees.delete')
                                ->name('trash');

                            Route::post('/{id}/restore', 'restore')
                                ->middleware('check.menu.permission:employees.delete')
                                ->whereNumber('id')
                                ->name('restore');

                            Route::delete('/{id}/force-delete', 'forceDelete')
                                ->middleware('check.menu.permission:employees.delete')
                                ->whereNumber('id')
                                ->name('force-delete');
                        });

                    /*
                    |----------------------------------------------------------
                    | Edit, Update, dan Soft Delete
                    |----------------------------------------------------------
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/{employee}/edit', 'edit')
                                ->middleware('check.menu.permission:employees.edit')
                                ->whereNumber('employee')
                                ->name('edit');

                            Route::match(
                                ['put', 'patch'],
                                '/{employee}',
                                'update'
                            )
                                ->middleware('check.menu.permission:employees.edit')
                                ->whereNumber('employee')
                                ->name('update');

                            Route::delete('/{employee}', 'destroy')
                                ->middleware('check.menu.permission:employees.delete')
                                ->whereNumber('employee')
                                ->name('destroy');
                        });
```

---

### Step 3: Replace Pattern - Employee Index/Show

**Find:**
```
Route::get('/', 'index')
                            ->name('index');

                        /*
                         * Diletakkan paling bawah agar create dan trash tidak
                         * dianggap sebagai parameter {employee}.
                         */
                        Route::get('/{employee}', 'show')
                            ->whereNumber('employee')
                            ->name('show');
                    });

            /*
            |--------------------------------------------------------------
            | Alias Employment untuk menu sidebar
```

**Replace:**
```
Route::get('/', 'index')
                            ->middleware('check.menu.permission:employees.view')
                            ->name('index');

                        /*
                         * Diletakkan paling bawah agar create dan trash tidak
                         * dianggap sebagai parameter {employee}.
                         */
                        Route::get('/{employee}', 'show')
                            ->middleware('check.menu.permission:employees.view')
                            ->whereNumber('employee')
                            ->name('show');
                    });

            /*
            |--------------------------------------------------------------
            | Alias Employment untuk menu sidebar
```

---

### Step 4: Replace Pattern - Customer Routes

**Find:**
```
Route::get('/create', 'create')
                            ->name('create');

                        Route::post('/', 'store')
                            ->name('store');
                    });

                    /*
                    |----------------------------------------------------------
                    | Recycle BinÂ€"khususSuperAdmin

                    |----------------------------------------------------------
                    |
                    | Route statis diletakkan sebelum /{customer} agar kata
                    | "trash" tidak dianggap sebagai parameter model Customer.
                    |
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/trash', 'trash')
                                ->name('trash');

                            Route::post('/{id}/restore', 'restore')
                                ->whereNumber('id')
                                ->name('restore');

                            Route::delete('/{id}/force-delete', 'forceDelete')
                                ->whereNumber('id')
                                ->name('force-delete');
                        });

                    /*
                    |----------------------------------------------------------
                    | Edit, Update, dan Soft Delete
                    |----------------------------------------------------------
                    */

                    Route::middleware(
                        'role:super_admin'
                    )->group(function (): void {

                        Route::get('/{customer}/edit', 'edit')
                            ->whereNumber('customer')
                            ->name('edit');

                        Route::match(
                            ['put', 'patch'],
                            '/{customer}',
                            'update'
                        )
                            ->whereNumber('customer')
                            ->name('update');

                        Route::delete('/{customer}', 'destroy')
                            ->whereNumber('customer')
                            ->name('destroy');
                    });
```

**Replace:**
```
Route::get('/create', 'create')
                            ->middleware('check.menu.permission:customers.create')
                            ->name('create');

                        Route::post('/', 'store')
                            ->middleware('check.menu.permission:customers.create')
                            ->name('store');
                    });

                    /*
                    |----------------------------------------------------------
                    | Recycle BinÂ€"khususSuperAdmin

                    |----------------------------------------------------------
                    |
                    | Route statis diletakkan sebelum /{customer} agar kata
                    | "trash" tidak dianggap sebagai parameter model Customer.
                    |
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/trash', 'trash')
                                ->middleware('check.menu.permission:customers.delete')
                                ->name('trash');

                            Route::post('/{id}/restore', 'restore')
                                ->middleware('check.menu.permission:customers.delete')
                                ->whereNumber('id')
                                ->name('restore');

                            Route::delete('/{id}/force-delete', 'forceDelete')
                                ->middleware('check.menu.permission:customers.delete')
                                ->whereNumber('id')
                                ->name('force-delete');
                        });

                    /*
                    |----------------------------------------------------------
                    | Edit, Update, dan Soft Delete
                    |----------------------------------------------------------
                    */

                    Route::middleware(
                        'role:super_admin'
                    )->group(function (): void {

                        Route::get('/{customer}/edit', 'edit')
                            ->middleware('check.menu.permission:customers.edit')
                            ->whereNumber('customer')
                            ->name('edit');

                        Route::match(
                            ['put', 'patch'],
                            '/{customer}',
                            'update'
                        )
                            ->middleware('check.menu.permission:customers.edit')
                            ->whereNumber('customer')
                            ->name('update');

                        Route::delete('/{customer}', 'destroy')
                            ->middleware('check.menu.permission:customers.delete')
                            ->whereNumber('customer')
                            ->name('destroy');
                    });
```

---

### Step 5: Replace Pattern - Customer Index/Show

**Find:**
```
Route::get('/', 'index')
                            ->name('index');

                        /*
                         * Route show diletakkan paling bawah agar URL create
                         * dan trash tidak dianggap sebagai {customer}.
                         */
                        Route::get('/{customer}', 'show')
                            ->whereNumber('customer')
                            ->name('show');
                    });
        });

/*
    |--------------------------------------------------------------------------
    | Shared Service Finance
```

**Replace:**
```
Route::get('/', 'index')
                            ->middleware('check.menu.permission:customers.view')
                            ->name('index');

                        /*
                         * Route show diletakkan paling bawah agar URL create
                         * dan trash tidak dianggap sebagai {customer}.
                         */
                        Route::get('/{customer}', 'show')
                            ->middleware('check.menu.permission:customers.view')
                            ->whereNumber('customer')
                            ->name('show');
                    });
        });

/*
    |--------------------------------------------------------------------------
    | Shared Service Finance
```

---

### Step 6: Update Shared Invoice & Payment Routes (Lintas Role)

**Find:**
```
Route::prefix('invoices')
    ->name('invoices.')
    ->controller(InvoiceController::class)
    ->group(function (): void {
        Route::get('/', 'index')->name('index');
Route::get('/{invoice}', 'show')
    ->whereNumber('invoice')
    ->name('show');

Route::middleware('role:super_admin')->group(function (): void {

Route::get('/create', 'create')->name('create');
Route::post('/', 'store')->name('store');
Route::patch('/{invoice}/refresh-status', 'refreshPaymentStatus')
    ->whereNumber('invoice')
    ->name('refresh-status');
Route::get('/{invoice}/edit', 'edit')
    ->whereNumber('invoice')
    ->name('edit');
Route::match(['put', 'patch'], '/{invoice}', 'update')
    ->whereNumber('invoice')
    ->name('update');
Route::delete('/{invoice}', 'destroy')
    ->whereNumber('invoice')
    ->name('destroy');


});
```

**Replace:**
```
Route::prefix('invoices')
    ->name('invoices.')
    ->controller(InvoiceController::class)
    ->group(function (): void {
        Route::get('/', 'index')->middleware('check.menu.permission:invoices.view')->name('index');
Route::get('/{invoice}', 'show')
    ->middleware('check.menu.permission:invoices.view')
    ->whereNumber('invoice')
    ->name('show');

Route::middleware('role:super_admin')->group(function (): void {

Route::get('/create', 'create')->middleware('check.menu.permission:invoices.create')->name('create');
Route::post('/', 'store')->middleware('check.menu.permission:invoices.create')->name('store');
Route::patch('/{invoice}/refresh-status', 'refreshPaymentStatus')
    ->middleware('check.menu.permission:invoices.edit')
    ->whereNumber('invoice')
    ->name('refresh-status');
Route::get('/{invoice}/edit', 'edit')
    ->middleware('check.menu.permission:invoices.edit')
    ->whereNumber('invoice')
    ->name('edit');
Route::match(['put', 'patch'], '/{invoice}', 'update')
    ->middleware('check.menu.permission:invoices.edit')
    ->whereNumber('invoice')
    ->name('update');
Route::delete('/{invoice}', 'destroy')
    ->middleware('check.menu.permission:invoices.delete')
    ->whereNumber('invoice')
    ->name('destroy');


});
```

---

### Step 7: Update Payments (Lintas Role)

**Find:**
```
Route::prefix('payments')
    ->name('payments.')
    ->controller(PaymentController::class)
    ->group(function (): void {
        Route::get('/', 'index')->name('index');
Route::get('/{payment}/print', 'print')
    ->whereNumber('payment')
    ->name('print');
Route::get('/{payment}', 'show')
    ->whereNumber('payment')
    ->name('show');

Route::middleware('role:super_admin')->group(function (): void {

Route::get('/create', 'create')->name('create');
Route::post('/', 'store')->name('store');
Route::post('/{payment}/capture-proof', 'captureProof')
    ->whereNumber('payment')
    ->name('capture-proof');


Route::patch('/{payment}/status', 'updateStatus')
    ->whereNumber('payment')
    ->name('status');
Route::get('/{payment}/edit', 'edit')
    ->whereNumber('payment')
    ->name('edit');
Route::match(['put', 'patch'], '/{payment}', 'update')
    ->whereNumber('payment')
    ->name('update');
Route::delete('/{payment}', 'destroy')
    ->whereNumber('payment')
    ->name('destroy');


});
```

**Replace:**
```
Route::prefix('payments')
    ->name('payments.')
    ->controller(PaymentController::class)
    ->group(function (): void {
        Route::get('/', 'index')->middleware('check.menu.permission:payments.view')->name('index');
Route::get('/{payment}/print', 'print')
    ->middleware('check.menu.permission:payments.view')
    ->whereNumber('payment')
    ->name('print');
Route::get('/{payment}', 'show')
    ->middleware('check.menu.permission:payments.view')
    ->whereNumber('payment')
    ->name('show');

Route::middleware('role:super_admin')->group(function (): void {

Route::get('/create', 'create')->middleware('check.menu.permission:payments.create')->name('create');
Route::post('/', 'store')->middleware('check.menu.permission:payments.create')->name('store');
Route::post('/{payment}/capture-proof', 'captureProof')
    ->middleware('check.menu.permission:payments.edit')
    ->whereNumber('payment')
    ->name('capture-proof');


Route::patch('/{payment}/status', 'updateStatus')
    ->middleware('check.menu.permission:payments.edit')
    ->whereNumber('payment')
    ->name('status');
Route::get('/{payment}/edit', 'edit')
    ->middleware('check.menu.permission:payments.edit')
    ->whereNumber('payment')
    ->name('edit');
Route::match(['put', 'patch'], '/{payment}', 'update')
    ->middleware('check.menu.permission:payments.edit')
    ->whereNumber('payment')
    ->name('update');
Route::delete('/{payment}', 'destroy')
    ->middleware('check.menu.permission:payments.delete')
    ->whereNumber('payment')
    ->name('destroy');


});
```

---

## Untuk Routes Lainnya

Gunakan ROUTE_MIDDLEWARE_MAPPING.md sebagai referensi untuk:
- Service Categories
- Services
- Service Orders
- Expenses
- Performance Periods
- Performance Indicators
- Users & Roles
- Work Schedules
- Employee Activities
- Attendances
- Service Order Status Histories

Pola yang sama: tambahkan `.middleware('check.menu.permission:{permission_name}')` sebelum `.name(...)` pada setiap route.

---

## Quick Reference - Middleware Pattern

```php
// Template umum
Route::get('/', 'index')
    ->middleware('check.menu.permission:module.view')  // ADD THIS LINE
    ->name('index');

Route::get('/create', 'create')
    ->middleware('check.menu.permission:module.create')  // ADD THIS LINE
    ->name('create');

Route::post('/', 'store')
    ->middleware('check.menu.permission:module.create')  // ADD THIS LINE
    ->name('store');

Route::get('/{id}/edit', 'edit')
    ->middleware('check.menu.permission:module.edit')  // ADD THIS LINE
    ->whereNumber('id')
    ->name('edit');

Route::match(['put', 'patch'], '/{id}', 'update')
    ->middleware('check.menu.permission:module.edit')  // ADD THIS LINE
    ->whereNumber('id')
    ->name('update');

Route::delete('/{id}', 'destroy')
    ->middleware('check.menu.permission:module.delete')  // ADD THIS LINE
    ->whereNumber('id')
    ->name('destroy');
```

---

## Checklist Implementasi

- [ ] Branches (✅ Done)
- [ ] Departments
- [ ] Positions (✅ Done) 
- [ ] Employees
- [ ] Customers
- [ ] Service Categories
- [ ] Services
- [ ] Service Orders
- [ ] Invoices
- [ ] Payments
- [ ] Expenses
- [ ] Performance Periods
- [ ] Performance Indicators
- [ ] Users
- [ ] Roles
- [ ] Work Schedules
- [ ] Employee Activities
- [ ] Attendances
- [ ] Service Order Status Histories

---

## Testing After Implementation

```bash
# 1. Verify setup
php artisan verify:role-permissions

# 2. Test dengan user yang punya limited permissions
php artisan tinker
>>> $user = User::whereHas('roles', fn($q) => $q->where('name', 'Karyawan'))->first();
>>> $user->can('employees.view') // Should be false
>>> $user->can('employees.view_own') // Should be true

# 3. Test dalam browser - login dengan different roles
# Cek apakah routes protected dengan benar dan error 403 muncul jika tidak punya akses
```

---

✅ **Status: Siap untuk diimplementasikan dengan VS Code Find & Replace**

Semua pattern sudah disiapkan di atas. Tinggal copy-paste ke Find & Replace feature di VS Code!
