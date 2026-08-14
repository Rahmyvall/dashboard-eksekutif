# Route Middleware Mapping untuk Permission Checking

## Deskripsi
File ini mendokumentasikan middleware permission yang harus ditambahkan ke setiap route untuk melindungi akses berdasarkan role dan permission.

Gunakan middleware: `check.menu.permission:{permission_name}`

---

## Branches Routes

```php
// Index & Show
Route::get('/', ...) -> middleware('check.menu.permission:branches.view')
Route::get('/{branch}', ...) -> middleware('check.menu.permission:branches.view')

// Create & Store  
Route::get('/create', ...) -> middleware('check.menu.permission:branches.create')
Route::post('/', ...) -> middleware('check.menu.permission:branches.create')

// Edit & Update
Route::get('/{branch}/edit', ...) -> middleware('check.menu.permission:branches.edit')
Route::match(['put', 'patch'], '/{branch}', ...) -> middleware('check.menu.permission:branches.edit')

// Delete
Route::delete('/{branch}', ...) -> middleware('check.menu.permission:branches.delete')

// Trash & Restore
Route::get('/trash', ...) -> middleware('check.menu.permission:branches.delete')
Route::post('/{id}/restore', ...) -> middleware('check.menu.permission:branches.delete')
Route::delete('/{id}/force-delete', ...) -> middleware('check.menu.permission:branches.delete')

// Approve
Route::patch('/{branch}/approve', ...) -> middleware('check.menu.permission:branches.approve')
Route::patch('/{branch}/reject', ...) -> middleware('check.menu.permission:branches.approve')
```

---

## Departments Routes

```php
// Index & Show
Route::get('/', ...) -> middleware('check.menu.permission:departments.view')
Route::get('/{department}', ...) -> middleware('check.menu.permission:departments.view')

// Create & Store
Route::get('/create', ...) -> middleware('check.menu.permission:departments.create')
Route::post('/', ...) -> middleware('check.menu.permission:departments.create')

// Edit & Update
Route::get('/{department}/edit', ...) -> middleware('check.menu.permission:departments.edit')
Route::match(['put', 'patch'], '/{department}', ...) -> middleware('check.menu.permission:departments.edit')

// Delete
Route::delete('/{department}', ...) -> middleware('check.menu.permission:departments.delete')

// Trash & Restore
Route::get('/departments-trash', ...) -> middleware('check.menu.permission:departments.delete')
Route::post('/departments/{id}/restore', ...) -> middleware('check.menu.permission:departments.delete')
Route::delete('/departments/{id}/force-delete', ...) -> middleware('check.menu.permission:departments.delete')
```

---

## Positions Routes

```php
// Index & Show
Route::get('/', ...) -> middleware('check.menu.permission:positions.view')
Route::get('/{position}', ...) -> middleware('check.menu.permission:positions.view')

// Create & Store
Route::get('/create', ...) -> middleware('check.menu.permission:positions.create')
Route::post('/', ...) -> middleware('check.menu.permission:positions.create')

// Edit & Update
Route::get('/{position}/edit', ...) -> middleware('check.menu.permission:positions.edit')
Route::match(['put', 'patch'], '/{position}', ...) -> middleware('check.menu.permission:positions.edit')

// Delete
Route::delete('/{position}', ...) -> middleware('check.menu.permission:positions.delete')

// Trash & Restore
Route::get('/positions-trash', ...) -> middleware('check.menu.permission:positions.delete')
Route::post('/positions/{id}/restore', ...) -> middleware('check.menu.permission:positions.delete')
Route::delete('/positions/{id}/force-delete', ...) -> middleware('check.menu.permission:positions.delete')
```

---

## Employees Routes

```php
// Index & Show
Route::get('/', ...) -> middleware('check.menu.permission:employees.view')
Route::get('/{employee}', ...) -> middleware('check.menu.permission:employees.view')

// Create & Store
Route::get('/create', ...) -> middleware('check.menu.permission:employees.create')
Route::post('/', ...) -> middleware('check.menu.permission:employees.create')

// Edit & Update
Route::get('/{employee}/edit', ...) -> middleware('check.menu.permission:employees.edit')
Route::match(['put', 'patch'], '/{employee}', ...) -> middleware('check.menu.permission:employees.edit')

// Delete
Route::delete('/{employee}', ...) -> middleware('check.menu.permission:employees.delete')

// Trash & Restore
Route::get('/trash', ...) -> middleware('check.menu.permission:employees.delete')
Route::post('/{id}/restore', ...) -> middleware('check.menu.permission:employees.delete')
Route::delete('/{id}/force-delete', ...) -> middleware('check.menu.permission:employees.delete')

// Employment Alias
Route::get('/employment', ...) -> middleware('check.menu.permission:employees.view')
```

---

## Customers Routes

```php
// Index & Show
Route::get('/', ...) -> middleware('check.menu.permission:customers.view')
Route::get('/{customer}', ...) -> middleware('check.menu.permission:customers.view')

// Create & Store
Route::get('/create', ...) -> middleware('check.menu.permission:customers.create')
Route::post('/', ...) -> middleware('check.menu.permission:customers.create')

// Edit & Update
Route::get('/{customer}/edit', ...) -> middleware('check.menu.permission:customers.edit')
Route::match(['put', 'patch'], '/{customer}', ...) -> middleware('check.menu.permission:customers.edit')

// Delete
Route::delete('/{customer}', ...) -> middleware('check.menu.permission:customers.delete')

// Trash & Restore
Route::get('/trash', ...) -> middleware('check.menu.permission:customers.delete')
Route::post('/{id}/restore', ...) -> middleware('check.menu.permission:customers.delete')
Route::delete('/{id}/force-delete', ...) -> middleware('check.menu.permission:customers.delete')
```

---

## Service Categories Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:service_categories.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:service_categories.create')
Route::post('/', 'store') -> middleware('check.menu.permission:service_categories.create')

// Edit & Update
Route::get('/{serviceCategory}/edit', 'edit') -> middleware('check.menu.permission:service_categories.edit')
Route::match(['put', 'patch'], '/{serviceCategory}', 'update') -> middleware('check.menu.permission:service_categories.edit')

// Delete
Route::delete('/{serviceCategory}', 'destroy') -> middleware('check.menu.permission:service_categories.delete')

// Toggle Status
Route::patch('/{serviceCategory}/toggle-status', 'toggleStatus') -> middleware('check.menu.permission:service_categories.edit')

// Trash & Restore
Route::get('/trashed', 'trashed') -> middleware('check.menu.permission:service_categories.delete')
Route::patch('/{id}/restore', 'restore') -> middleware('check.menu.permission:service_categories.delete')
Route::delete('/{id}/force-delete', 'forceDelete') -> middleware('check.menu.permission:service_categories.delete')

// Show
Route::get('/{serviceCategory}', 'show') -> middleware('check.menu.permission:service_categories.view')
```

---

## Services Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:services.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:services.create')
Route::post('/', 'store') -> middleware('check.menu.permission:services.create')

// Edit & Update
Route::get('/{service}/edit', 'edit') -> middleware('check.menu.permission:services.edit')
Route::match(['put', 'patch'], '/{service}', 'update') -> middleware('check.menu.permission:services.edit')

// Delete
Route::delete('/{service}', 'destroy') -> middleware('check.menu.permission:services.delete')

// Toggle Status
Route::patch('/{service}/toggle-status', 'toggleStatus') -> middleware('check.menu.permission:services.edit')

// Show
Route::get('/{service}', 'show') -> middleware('check.menu.permission:services.view')
```

---

## Service Orders Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:service_orders.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:service_orders.create')
Route::post('/', 'store') -> middleware('check.menu.permission:service_orders.create')

// Edit & Update
Route::get('/{serviceOrder}/edit', 'edit') -> middleware('check.menu.permission:service_orders.edit')
Route::match(['put', 'patch'], '/{serviceOrder}', 'update') -> middleware('check.menu.permission:service_orders.edit')

// Delete
Route::delete('/{serviceOrder}', 'destroy') -> middleware('check.menu.permission:service_orders.delete')

// Update Status
Route::patch('/{serviceOrder}/status', 'updateStatus') -> middleware('check.menu.permission:service_orders.edit')

// Show
Route::get('/{serviceOrder}', 'show') -> middleware('check.menu.permission:service_orders.view')
```

---

## Invoices Routes (Shared)

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:invoices.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:invoices.create')
Route::post('/', 'store') -> middleware('check.menu.permission:invoices.create')

// Edit & Update
Route::get('/{invoice}/edit', 'edit') -> middleware('check.menu.permission:invoices.edit')
Route::match(['put', 'patch'], '/{invoice}', 'update') -> middleware('check.menu.permission:invoices.edit')

// Delete
Route::delete('/{invoice}', 'destroy') -> middleware('check.menu.permission:invoices.delete')

// Print & Refresh
Route::get('/{invoice}/print', 'print') -> middleware('check.menu.permission:invoices.view')
Route::patch('/{invoice}/refresh-status', 'refreshPaymentStatus') -> middleware('check.menu.permission:invoices.edit')

// Show
Route::get('/{invoice}', 'show') -> middleware('check.menu.permission:invoices.view')
```

---

## Payments Routes (Shared)

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:payments.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:payments.create')
Route::post('/', 'store') -> middleware('check.menu.permission:payments.create')

// Edit & Update
Route::get('/{payment}/edit', 'edit') -> middleware('check.menu.permission:payments.edit')
Route::match(['put', 'patch'], '/{payment}', 'update') -> middleware('check.menu.permission:payments.edit')

// Delete
Route::delete('/{payment}', 'destroy') -> middleware('check.menu.permission:payments.delete')

// Print & Capture
Route::get('/{payment}/print', 'print') -> middleware('check.menu.permission:payments.view')
Route::post('/{payment}/capture-proof', 'captureProof') -> middleware('check.menu.permission:payments.edit')

// Update Status
Route::patch('/{payment}/status', 'updateStatus') -> middleware('check.menu.permission:payments.edit')

// Show
Route::get('/{payment}', 'show') -> middleware('check.menu.permission:payments.view')
```

---

## Expenses Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:expenses.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:expenses.create')
Route::post('/', 'store') -> middleware('check.menu.permission:expenses.create')

// Edit & Update
Route::get('/{expense}/edit', 'edit') -> middleware('check.menu.permission:expenses.edit')
Route::match(['put', 'patch'], '/{expense}', 'update') -> middleware('check.menu.permission:expenses.edit')

// Delete
Route::delete('/{expense}', 'destroy') -> middleware('check.menu.permission:expenses.delete')

// Show
Route::get('/{expense}', 'show') -> middleware('check.menu.permission:expenses.view')
```

---

## Performance Periods Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:performance_periods.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:performance_periods.create')
Route::post('/', 'store') -> middleware('check.menu.permission:performance_periods.create')

// Edit & Update
Route::get('/{performancePeriod}/edit', 'edit') -> middleware('check.menu.permission:performance_periods.edit')
Route::match(['put', 'patch'], '/{performancePeriod}', 'update') -> middleware('check.menu.permission:performance_periods.edit')

// Delete
Route::delete('/{performancePeriod}', 'destroy') -> middleware('check.menu.permission:performance_periods.delete')

// Show
Route::get('/{performancePeriod}', 'show') -> middleware('check.menu.permission:performance_periods.view')
```

---

## Performance Indicators Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:performance_indicators.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:performance_indicators.create')
Route::post('/', 'store') -> middleware('check.menu.permission:performance_indicators.create')

// Edit & Update
Route::get('/{performanceIndicator}/edit', 'edit') -> middleware('check.menu.permission:performance_indicators.edit')
Route::match(['put', 'patch'], '/{performanceIndicator}', 'update') -> middleware('check.menu.permission:performance_indicators.edit')

// Delete
Route::delete('/{performanceIndicator}', 'destroy') -> middleware('check.menu.permission:performance_indicators.delete')

// Status Actions
Route::patch('/{performanceIndicator}/toggle-status', 'toggleStatus') -> middleware('check.menu.permission:performance_indicators.edit')
Route::patch('/{performanceIndicator}/activate', 'activate') -> middleware('check.menu.permission:performance_indicators.edit')
Route::patch('/{performanceIndicator}/deactivate', 'deactivate') -> middleware('check.menu.permission:performance_indicators.edit')

// Bulk Actions
Route::patch('/bulk-status', 'bulkStatus') -> middleware('check.menu.permission:performance_indicators.edit')
Route::delete('/bulk-destroy', 'bulkDestroy') -> middleware('check.menu.permission:performance_indicators.delete')

// Export
Route::get('/export/excel', 'exportExcel') -> middleware('check.menu.permission:performance_indicators.view')
Route::get('/export/pdf', 'exportPdf') -> middleware('check.menu.permission:performance_indicators.view')

// Show
Route::get('/{performanceIndicator}', 'show') -> middleware('check.menu.permission:performance_indicators.view')
```

---

## Users Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:users.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:users.create')
Route::post('/', 'store') -> middleware('check.menu.permission:users.create')

// Edit & Update
Route::get('/{user}/edit', 'edit') -> middleware('check.menu.permission:users.edit')
Route::match(['put', 'patch'], '/{user}', 'update') -> middleware('check.menu.permission:users.edit')

// Delete
Route::delete('/{user}', 'destroy') -> middleware('check.menu.permission:users.delete')

// Trash & Restore
Route::get('/users-trash', ...) -> middleware('check.menu.permission:users.delete')
Route::post('/users/{id}/restore', ...) -> middleware('check.menu.permission:users.delete')
Route::delete('/users/{id}/force-delete', ...) -> middleware('check.menu.permission:users.delete')

// Show
Route::get('/{user}', 'show') -> middleware('check.menu.permission:users.view')
```

---

## Roles Routes

```php
// Index
Route::get('/', 'index') -> middleware('check.menu.permission:roles.view')

// Create & Store
Route::get('/create', 'create') -> middleware('check.menu.permission:roles.create')
Route::post('/', 'store') -> middleware('check.menu.permission:roles.create')

// Edit & Update
Route::get('/{role}/edit', 'edit') -> middleware('check.menu.permission:roles.edit')
Route::match(['put', 'patch'], '/{role}', 'update') -> middleware('check.menu.permission:roles.edit')

// Delete
Route::delete('/{role}', 'destroy') -> middleware('check.menu.permission:roles.delete')

// Show
Route::get('/{role}', 'show') -> middleware('check.menu.permission:roles.view')
```

---

## Work Schedules Routes

```php
// Index
Route::get('/', [...]) -> middleware('check.menu.permission:work_schedules.view')

// Create & Store
Route::get('/create', [...]) -> middleware('check.menu.permission:work_schedules.create')
Route::post('/', [...]) -> middleware('check.menu.permission:work_schedules.create')

// Print
Route::get('/print', [...]) -> middleware('check.menu.permission:work_schedules.view')

// Edit & Update
Route::get('/{workSchedule}/edit', [...]) -> middleware('check.menu.permission:work_schedules.edit')
Route::match(['put', 'patch'], '/{workSchedule}', [...]) -> middleware('check.menu.permission:work_schedules.edit')

// Delete
Route::delete('/{workSchedule}', [...]) -> middleware('check.menu.permission:work_schedules.delete')

// Toggle Status
Route::patch('/{workSchedule}/toggle-status', [...]) -> middleware('check.menu.permission:work_schedules.edit')

// Show
Route::get('/{workSchedule}', [...]) -> middleware('check.menu.permission:work_schedules.view')
```

---

## Employee Activities Routes

```php
// Index
Route::get('/', [...]) -> middleware('check.menu.permission:employee_activities.view')

// Create & Store
Route::get('/create', [...]) -> middleware('check.menu.permission:employee_activities.create')
Route::post('/', [...]) -> middleware('check.menu.permission:employee_activities.create')

// Print
Route::get('/print', [...]) -> middleware('check.menu.permission:employee_activities.view')

// Edit & Update
Route::get('/{employeeActivity}/edit', [...]) -> middleware('check.menu.permission:employee_activities.edit')
Route::match(['put', 'patch'], '/{employeeActivity}', [...]) -> middleware('check.menu.permission:employee_activities.edit')

// Delete
Route::delete('/{employeeActivity}', [...]) -> middleware('check.menu.permission:employee_activities.delete')

// Verify
Route::patch('/{employeeActivity}/verify', [...]) -> middleware('check.menu.permission:employee_activities.edit')
Route::patch('/{employeeActivity}/cancel-verification', [...]) -> middleware('check.menu.permission:employee_activities.edit')

// Show
Route::get('/{employeeActivity}', [...]) -> middleware('check.menu.permission:employee_activities.view')
```

---

## Attendances Routes

```php
// Index
Route::get('/', [...]) -> middleware('check.menu.permission:attendances.view')

// Create & Store
Route::get('/create', [...]) -> middleware('check.menu.permission:attendances.create')
Route::post('/', [...]) -> middleware('check.menu.permission:attendances.create')

// Edit & Update
Route::get('/{attendance}/edit', [...]) -> middleware('check.menu.permission:attendances.edit')
Route::match(['put', 'patch'], '/{attendance}', [...]) -> middleware('check.menu.permission:attendances.edit')

// Delete
Route::delete('/{attendance}', [...]) -> middleware('check.menu.permission:attendances.delete')

// Show
Route::get('/{attendance}', [...]) -> middleware('check.menu.permission:attendances.view')
```

---

## Service Order Status Histories Routes

```php
// Index
Route::get('/', [...]) -> middleware('check.menu.permission:service_order_status_histories.view')

// Edit & Update
Route::get('/{serviceOrderStatusHistory}/edit', [...]) -> middleware('check.menu.permission:service_order_status_histories.edit')
Route::match(['put', 'patch'], '/{serviceOrderStatusHistory}', [...]) -> middleware('check.menu.permission:service_order_status_histories.edit')

// Delete
Route::delete('/{serviceOrderStatusHistory}', [...]) -> middleware('check.menu.permission:service_order_status_histories.delete')

// Show
Route::get('/{serviceOrderStatusHistory}', [...]) -> middleware('check.menu.permission:service_order_status_histories.view')
```

---

## Implementation Notes

1. **Permissions Sudah Didefinisikan**: Semua permission di atas sudah didefinisikan di `RolePermissionSeeder.php`

2. **Middleware Syntax**:
   ```php
   ->middleware('check.menu.permission:permission_name')
   ```

3. **Multiple Permissions** (OR logic):
   ```php
   ->middleware('check.menu.permission:employees.view|employees.view_own')
   ```

4. **Response**:
   - API: 403 JSON response
   - Web: abort(403) dengan error page

5. **Testing**:
   ```bash
   php artisan verify:role-permissions  // Verify setup
   ```

---

## Quick Implementation Steps

1. Copy mapping dari file ini
2. Update route di `routes/web.php` dengan middleware sesuai
3. Test setiap route dengan curl atau browser
4. Verify permissions dengan: `php artisan verify:role-permissions`

✅ **Ready for Implementation**
