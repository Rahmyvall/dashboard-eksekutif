<?php

declare (strict_types = 1);

use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\EmployeeActivityController;
use App\Http\Controllers\Api\PerformanceIndicatorController;
use App\Http\Controllers\Api\PerformancePeriodController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\RoleApiController;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\InvoiceApiController;

use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\LookupController;



use App\Http\Controllers\Api\WorkScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role API
|--------------------------------------------------------------------------
*/

Route::prefix('roles')
    ->name('api.roles.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GET ALL ROLE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                RoleApiController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE ROLE
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [
                RoleApiController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | DETAIL ROLE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{role}',
            [
                RoleApiController::class,
                'show',
            ]
        )
            ->whereNumber('role')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | UPDATE ROLE
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{role}',
            [
                RoleApiController::class,
                'update',
            ]
        )
            ->whereNumber('role')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | DELETE ROLE
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{role}',
            [
                RoleApiController::class,
                'destroy',
            ]
        )
            ->whereNumber('role')
            ->name('destroy');
    });

/* Service API */
Route::prefix('v1/services')->name('api.v1.services.')->group(function (): void {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::post('/', [ServiceController::class, 'store'])->name('store');
    Route::get('/trashed', [ServiceController::class, 'trashed'])->name('trashed');
    Route::patch('/{id}/restore', [ServiceController::class, 'restore'])->whereNumber('id')->name('restore');
    Route::delete('/{id}/force-delete', [ServiceController::class, 'forceDelete'])->whereNumber('id')->name('force-delete');
    Route::patch('/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->whereNumber('service')->name('toggle-status');
    Route::get('/{service}', [ServiceController::class, 'show'])->whereNumber('service')->name('show');
    Route::match(['put', 'patch'], '/{service}', [ServiceController::class, 'update'])->whereNumber('service')->name('update');
    Route::delete('/{service}', [ServiceController::class, 'destroy'])->whereNumber('service')->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Branch API
|--------------------------------------------------------------------------
*/

Route::prefix('branches')
    ->name('api.branches.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GET ALL BRANCH
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                BranchApiController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE BRANCH
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [
                BranchApiController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | RESTORE BRANCH
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{branch}/restore',
            [
                BranchApiController::class,
                'restore',
            ]
        )
            ->whereNumber('branch')
            ->name('restore');

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS BRANCH
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{branch}/status',
            [
                BranchApiController::class,
                'updateStatus',
            ]
        )
            ->whereNumber('branch')
            ->name('status');

        /*
        |--------------------------------------------------------------------------
        | DETAIL BRANCH
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{branch}',
            [
                BranchApiController::class,
                'show',
            ]
        )
            ->whereNumber('branch')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | UPDATE BRANCH
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{branch}',
            [
                BranchApiController::class,
                'update',
            ]
        )
            ->whereNumber('branch')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | DELETE BRANCH
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{branch}',
            [
                BranchApiController::class,
                'destroy',
            ]
        )
            ->whereNumber('branch')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Department API
|--------------------------------------------------------------------------
*/

Route::prefix('departments')
    ->name('api.departments.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GET ALL DEPARTMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                DepartmentApiController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE DEPARTMENT
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [
                DepartmentApiController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT STATISTICS
        |--------------------------------------------------------------------------
        |
        | Harus diletakkan sebelum route /{department}.
        |
        */

        Route::get(
            '/statistics',
            [
                DepartmentApiController::class,
                'statistics',
            ]
        )->name('statistics');

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT TRASH
        |--------------------------------------------------------------------------
        |
        | Menampilkan data department yang sudah terkena soft delete.
        |
        */

        Route::get(
            '/trash',
            [
                DepartmentApiController::class,
                'trash',
            ]
        )->name('trash');

        /*
        |--------------------------------------------------------------------------
        | RESTORE DEPARTMENT
        |--------------------------------------------------------------------------
        |
        | Parameter dikirim sebagai ID karena data yang terhapus tidak dapat
        | menggunakan implicit route model binding biasa.
        |
        */

        Route::patch(
            '/{department}/restore',
            [
                DepartmentApiController::class,
                'restore',
            ]
        )
            ->whereNumber('department')
            ->name('restore');

        /*
        |--------------------------------------------------------------------------
        | FORCE DELETE DEPARTMENT
        |--------------------------------------------------------------------------
        |
        | Menghapus department secara permanen dari database.
        |
        */

        Route::delete(
            '/{department}/force-delete',
            [
                DepartmentApiController::class,
                'forceDelete',
            ]
        )
            ->whereNumber('department')
            ->name('force-delete');

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS DEPARTMENT
        |--------------------------------------------------------------------------
        |
        | Body JSON:
        | {
        |     "status": "active"
        | }
        |
        */

        Route::patch(
            '/{department}/status',
            [
                DepartmentApiController::class,
                'updateStatus',
            ]
        )
            ->whereNumber('department')
            ->name('status');

        /*
        |--------------------------------------------------------------------------
        | DETAIL DEPARTMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{department}',
            [
                DepartmentApiController::class,
                'show',
            ]
        )
            ->whereNumber('department')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | UPDATE DEPARTMENT
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{department}',
            [
                DepartmentApiController::class,
                'update',
            ]
        )
            ->whereNumber('department')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PARTIAL UPDATE DEPARTMENT
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{department}',
            [
                DepartmentApiController::class,
                'update',
            ]
        )
            ->whereNumber('department')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | DELETE DEPARTMENT
        |--------------------------------------------------------------------------
        |
        | Menghapus department menggunakan soft delete.
        |
        */

        Route::delete(
            '/{department}',
            [
                DepartmentApiController::class,
                'destroy',
            ]
        )
            ->whereNumber('department')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Position API
|--------------------------------------------------------------------------
*/

Route::prefix('positions')
    ->name('api.positions.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GET ALL POSITION
        |--------------------------------------------------------------------------
        |
        | Query parameter:
        | - search
        | - status
        | - department_id
        | - level
        | - per_page
        |
        */

        Route::get(
            '/',
            [
                PositionController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE POSITION
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [
                PositionController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | POSITION TRASH
        |--------------------------------------------------------------------------
        |
        | Route statis harus diletakkan sebelum /{position}.
        |
        */

        Route::get(
            '/trash',
            [
                PositionController::class,
                'trash',
            ]
        )->name('trash');

        /*
        |--------------------------------------------------------------------------
        | RESTORE POSITION
        |--------------------------------------------------------------------------
        |
        | Menggunakan parameter ID karena data soft delete tidak dapat
        | menggunakan implicit route model binding biasa.
        |
        */

        Route::patch(
            '/{id}/restore',
            [
                PositionController::class,
                'restore',
            ]
        )
            ->whereNumber('id')
            ->name('restore');

        /*
        |--------------------------------------------------------------------------
        | FORCE DELETE POSITION
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{id}/force-delete',
            [
                PositionController::class,
                'forceDelete',
            ]
        )
            ->whereNumber('id')
            ->name('force-delete');

        /*
        |--------------------------------------------------------------------------
        | DETAIL POSITION
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{position}',
            [
                PositionController::class,
                'show',
            ]
        )
            ->whereNumber('position')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | UPDATE POSITION
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{position}',
            [
                PositionController::class,
                'update',
            ]
        )
            ->whereNumber('position')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PARTIAL UPDATE POSITION
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{position}',
            [
                PositionController::class,
                'update',
            ]
        )
            ->whereNumber('position')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | DELETE POSITION
        |--------------------------------------------------------------------------
        |
        | Menghapus position menggunakan soft delete.
        |
        */

        Route::delete(
            '/{position}',
            [
                PositionController::class,
                'destroy',
            ]
        )
            ->whereNumber('position')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Customer API
|--------------------------------------------------------------------------
|
| Database customers:
| - id
| - customer_code
| - customer_type
| - name
| - company_name
| - phone
| - email
| - address
| - tax_number
| - status
| - created_at
| - updated_at
| - deleted_at
|
*/

Route::prefix('customers')
    ->name('api.customers.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | GET ALL CUSTOMER
        |--------------------------------------------------------------------------
        |
        | Query parameter:
        | - search
        | - customer_type: individual | company
        | - status: active | inactive
        | - sort
        | - direction: asc | desc
        | - per_page
        |
        */

        Route::get(
            '/',
            [
                CustomerController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE CUSTOMER
        |--------------------------------------------------------------------------
        |
        | Body JSON:
        | {
        |     "customer_code": "CUST-0001",
        |     "customer_type": "individual",
        |     "name": "Nama Pelanggan",
        |     "company_name": null,
        |     "phone": "081234567890",
        |     "email": "customer@example.com",
        |     "address": "Alamat pelanggan",
        |     "tax_number": null,
        |     "status": "active"
        | }
        |
        */

        Route::post(
            '/',
            [
                CustomerController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER TRASH
        |--------------------------------------------------------------------------
        |
        | Route statis harus diletakkan sebelum /{customer} agar kata
        | "trash" tidak dianggap sebagai parameter customer.
        |
        */

        Route::get(
            '/trash',
            [
                CustomerController::class,
                'trash',
            ]
        )->name('trash');

        /*
        |--------------------------------------------------------------------------
        | RESTORE CUSTOMER
        |--------------------------------------------------------------------------
        |
        | Menggunakan parameter ID karena data soft delete tidak dapat
        | menggunakan implicit route model binding biasa.
        |
        */

        Route::patch(
            '/{id}/restore',
            [
                CustomerController::class,
                'restore',
            ]
        )
            ->whereNumber('id')
            ->name('restore');

        /*
        |--------------------------------------------------------------------------
        | FORCE DELETE CUSTOMER
        |--------------------------------------------------------------------------
        |
        | Menghapus customer secara permanen dari database.
        |
        */

        Route::delete(
            '/{id}/force-delete',
            [
                CustomerController::class,
                'forceDelete',
            ]
        )
            ->whereNumber('id')
            ->name('force-delete');

        /*
        |--------------------------------------------------------------------------
        | DETAIL CUSTOMER
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{customer}',
            [
                CustomerController::class,
                'show',
            ]
        )
            ->whereNumber('customer')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | UPDATE CUSTOMER
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{customer}',
            [
                CustomerController::class,
                'update',
            ]
        )
            ->whereNumber('customer')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PARTIAL UPDATE CUSTOMER
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{customer}',
            [
                CustomerController::class,
                'update',
            ]
        )
            ->whereNumber('customer')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | DELETE CUSTOMER
        |--------------------------------------------------------------------------
        |
        | Menghapus customer menggunakan soft delete.
        |
        */

        Route::delete(
            '/{customer}',
            [
                CustomerController::class,
                'destroy',
            ]
        )
            ->whereNumber('customer')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Work Schedule API
|--------------------------------------------------------------------------
|
| Database work_schedules:
| - id
| - name
| - start_time
| - end_time
| - late_tolerance_minutes
| - working_hours
| - status
| - created_at
| - updated_at
|
*/

Route::prefix('work-schedules')
    ->name('api.work-schedules.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | GET ALL WORK SCHEDULE
        |--------------------------------------------------------------------------
        |
        | Query parameter:
        | - search
        | - status: active | inactive
        | - sort_by
        | - sort_direction: asc | desc
        | - per_page
        |
        */

        Route::get(
            '/',
            [
                WorkScheduleController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE WORK SCHEDULE
        |--------------------------------------------------------------------------
        |
        | Body JSON:
        | {
        |     "name": "Jadwal Kerja Reguler",
        |     "start_time": "08:00",
        |     "end_time": "17:00",
        |     "late_tolerance_minutes": 15,
        |     "working_hours": 8,
        |     "status": "active"
        | }
        |
        */

        Route::post(
            '/',
            [
                WorkScheduleController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | DETAIL WORK SCHEDULE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{workSchedule}',
            [
                WorkScheduleController::class,
                'show',
            ]
        )
            ->whereNumber('workSchedule')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | UPDATE WORK SCHEDULE
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{workSchedule}',
            [
                WorkScheduleController::class,
                'update',
            ]
        )
            ->whereNumber('workSchedule')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PARTIAL UPDATE WORK SCHEDULE
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{workSchedule}',
            [
                WorkScheduleController::class,
                'update',
            ]
        )
            ->whereNumber('workSchedule')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | DELETE WORK SCHEDULE
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{workSchedule}',
            [
                WorkScheduleController::class,
                'destroy',
            ]
        )
            ->whereNumber('workSchedule')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Performance Period API
|--------------------------------------------------------------------------
|
| Database performance_periods:
| - id
| - name
| - start_date
| - end_date
| - period_type
| - status
| - created_at
| - updated_at
|
| Base URL:
| http://127.0.0.1:8000/api/v1/performance-periods
|
*/

Route::prefix('v1/performance-periods')
    ->name('api.v1.performance-periods.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | CURRENT PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        |
        | Mengambil periode berstatus active yang mencakup tanggal hari ini.
        | Route statis harus berada sebelum /{performancePeriod}.
        |
        */

        Route::get(
            '/current',
            [
                PerformancePeriodController::class,
                'current',
            ]
        )->name('current');

        /*
        |--------------------------------------------------------------------------
        | PERFORMANCE PERIOD SUMMARY
        |--------------------------------------------------------------------------
        |
        | Menghasilkan ringkasan:
        | - total
        | - draft
        | - active
        | - completed
        | - inactive
        | - current
        | - upcoming
        | - expired
        |
        */

        Route::get(
            '/summary',
            [
                PerformancePeriodController::class,
                'summary',
            ]
        )->name('summary');

        /*
        |--------------------------------------------------------------------------
        | GET ALL PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        |
        | Query parameter:
        | - search
        | - status
        | - period_type
        | - date
        | - sort
        | - direction
        | - per_page
        |
        */

        Route::get(
            '/',
            [
                PerformancePeriodController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        |
        | Body JSON:
        | {
        |     "name": "Penilaian Tahunan 2026",
        |     "start_date": "2026-01-01",
        |     "end_date": "2026-12-31",
        |     "period_type": "annual",
        |     "status": "active"
        | }
        |
        */

        Route::post(
            '/',
            [
                PerformancePeriodController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | DETAIL PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{performancePeriod}',
            [
                PerformancePeriodController::class,
                'show',
            ]
        )
            ->whereNumber('performancePeriod')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | FULL UPDATE PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{performancePeriod}',
            [
                PerformancePeriodController::class,
                'update',
            ]
        )
            ->whereNumber('performancePeriod')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PARTIAL UPDATE PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{performancePeriod}',
            [
                PerformancePeriodController::class,
                'update',
            ]
        )
            ->whereNumber('performancePeriod')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | DELETE PERFORMANCE PERIOD
        |--------------------------------------------------------------------------
        |
        | Tabel performance_periods tidak memiliki deleted_at,
        | sehingga data dihapus permanen.
        |
        */

        Route::delete(
            '/{performancePeriod}',
            [
                PerformancePeriodController::class,
                'destroy',
            ]
        )
            ->whereNumber('performancePeriod')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Performance Indicator API
|--------------------------------------------------------------------------
|
| Database performance_indicators:
| - id
| - code
| - name
| - description
| - unit
| - weight
| - target_direction
| - status
| - created_at
| - updated_at
|
| Base URL:
| http://127.0.0.1:8000/api/v1/performance-indicators
|
*/

Route::prefix('v1/performance-indicators')
    ->name('api.v1.performance-indicators.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | GET ALL PERFORMANCE INDICATOR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                PerformanceIndicatorController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREATE PERFORMANCE INDICATOR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [
                PerformanceIndicatorController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | DETAIL PERFORMANCE INDICATOR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{indicator}',
            [
                PerformanceIndicatorController::class,
                'show',
            ]
        )
            ->whereNumber('indicator')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | FULL UPDATE PERFORMANCE INDICATOR
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{indicator}',
            [
                PerformanceIndicatorController::class,
                'update',
            ]
        )
            ->whereNumber('indicator')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PARTIAL UPDATE PERFORMANCE INDICATOR
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{indicator}',
            [
                PerformanceIndicatorController::class,
                'update',
            ]
        )
            ->whereNumber('indicator')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | DELETE PERFORMANCE INDICATOR
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{indicator}',
            [
                PerformanceIndicatorController::class,
                'destroy',
            ]
        )
            ->whereNumber('indicator')
            ->name('destroy');
    });
/*
|--------------------------------------------------------------------------
| Service Category API
|--------------------------------------------------------------------------
|
| Database service_categories:
| - id
| - code
| - name
| - description
| - status
| - created_at
| - updated_at
| - deleted_at
|
| Base URL:
| /api/v1/service-categories
|
*/

Route::prefix('v1/service-categories')
    ->name('api.v1.service-categories.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                ServiceCategoryController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [
                ServiceCategoryController::class,
                'store',
            ]
        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | TRASHED
        |--------------------------------------------------------------------------
        |
        | HARUS sebelum /{serviceCategory}
        |
        */

        Route::get(
            '/trashed',
            [
                ServiceCategoryController::class,
                'trashed',
            ]
        )->name('trashed');

        /*
        |--------------------------------------------------------------------------
        | RESTORE
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{id}/restore',
            [
                ServiceCategoryController::class,
                'restore',
            ]
        )
            ->whereNumber('id')
            ->name('restore');

        /*
        |--------------------------------------------------------------------------
        | FORCE DELETE
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{id}/force-delete',
            [
                ServiceCategoryController::class,
                'forceDelete',
            ]
        )
            ->whereNumber('id')
            ->name('force-delete');

        /*
        |--------------------------------------------------------------------------
        | TOGGLE STATUS
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{serviceCategory}/toggle-status',
            [
                ServiceCategoryController::class,
                'toggleStatus',
            ]
        )
            ->whereNumber('serviceCategory')
            ->name('toggle-status');

        /*
        |--------------------------------------------------------------------------
        | SHOW
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{serviceCategory}',
            [
                ServiceCategoryController::class,
                'show',
            ]
        )
            ->whereNumber('serviceCategory')
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | PUT UPDATE
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/{serviceCategory}',
            [
                ServiceCategoryController::class,
                'update',
            ]
        )
            ->whereNumber('serviceCategory')
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | PATCH UPDATE
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{serviceCategory}',
            [
                ServiceCategoryController::class,
                'update',
            ]
        )
            ->whereNumber('serviceCategory')
            ->name('patch');

        /*
        |--------------------------------------------------------------------------
        | SOFT DELETE
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{serviceCategory}',
            [
                ServiceCategoryController::class,
                'destroy',
            ]
        )
            ->whereNumber('serviceCategory')
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Invoice API
|--------------------------------------------------------------------------
|
| Base URL : /api/v1/invoices
|
| Endpoints:
| GET    /api/v1/invoices                        → index  (list + filter)
| POST   /api/v1/invoices                        → store  (buat invoice)
| GET    /api/v1/invoices/{id}                   → show   (detail)
| PUT    /api/v1/invoices/{id}                   → update (ubah data)
| PATCH  /api/v1/invoices/{id}/payment-status    → ubah status bayar
| DELETE /api/v1/invoices/{id}                   → destroy
|
| Query filter index:
| - search          : invoice_number / order_number
| - payment_status  : unpaid | partial | paid
| - from_date       : YYYY-MM-DD
| - to_date         : YYYY-MM-DD
| - per_page        : 1-100 (default 15)
| - sort_by         : id | invoice_number | invoice_date | due_date | total_amount | payment_status | created_at
| - sort_direction  : asc | desc
|
*/

Route::prefix('v1/invoices')
    ->name('api.v1.invoices.')
    ->group(function (): void {

        Route::get('/', [InvoiceApiController::class, 'index'])->name('index');
        Route::post('/', [InvoiceApiController::class, 'store'])->name('store');

        Route::patch(
            '/{invoice}/payment-status',
            [InvoiceApiController::class, 'updatePaymentStatus']
        )->whereNumber('invoice')->name('payment-status');

        Route::get('/{invoice}', [InvoiceApiController::class, 'show'])
            ->whereNumber('invoice')->name('show');
        Route::put('/{invoice}', [InvoiceApiController::class, 'update'])
            ->whereNumber('invoice')->name('update');
        Route::patch('/{invoice}', [InvoiceApiController::class, 'update'])
            ->whereNumber('invoice')->name('patch');
        Route::delete('/{invoice}', [InvoiceApiController::class, 'destroy'])
            ->whereNumber('invoice')->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Payment API
|--------------------------------------------------------------------------
|
| Base URL : /api/v1/payments
|
| Endpoints:
| GET    /api/v1/payments                    → index   (list + filter)
| POST   /api/v1/payments                    → store   (catat pembayaran)
| GET    /api/v1/payments/{id}               → show    (detail)
| PUT    /api/v1/payments/{id}               → update  (ubah data)
| PATCH  /api/v1/payments/{id}/confirm       → konfirmasi pembayaran
| PATCH  /api/v1/payments/{id}/cancel        → batalkan pembayaran
| GET    /api/v1/payments/summary/{invoice}  → ringkasan per invoice
| DELETE /api/v1/payments/{id}               → destroy
|
| Query filter index:
| - search           : payment_number / reference_number / order_number
| - status           : pending | confirmed | cancelled | refunded
| - payment_method   : cash | transfer | qris | debit | credit | other
| - invoice_id       : integer
| - service_order_id : integer
| - from_date        : YYYY-MM-DD
| - to_date          : YYYY-MM-DD
| - per_page         : 1-100 (default 15)
| - sort_by          : id | payment_number | payment_date | amount | status | payment_method | created_at
| - sort_direction   : asc | desc
|
*/

Route::prefix('v1/payments')
    ->name('api.v1.payments.')
    ->group(function (): void {

        // Route statis harus diletakkan sebelum route dengan parameter
        Route::get('/summary/{invoiceId}', [PaymentApiController::class, 'summary'])
            ->whereNumber('invoiceId')->name('summary');

        Route::get('/', [PaymentApiController::class, 'index'])->name('index');
        Route::post('/', [PaymentApiController::class, 'store'])->name('store');

        Route::patch('/{payment}/confirm', [PaymentApiController::class, 'confirm'])
            ->whereNumber('payment')->name('confirm');
        Route::patch('/{payment}/cancel', [PaymentApiController::class, 'cancel'])
            ->whereNumber('payment')->name('cancel');

        Route::get('/{payment}', [PaymentApiController::class, 'show'])
            ->whereNumber('payment')->name('show');
        Route::put('/{payment}', [PaymentApiController::class, 'update'])
            ->whereNumber('payment')->name('update');
        Route::patch('/{payment}', [PaymentApiController::class, 'update'])
            ->whereNumber('payment')->name('patch');
        Route::delete('/{payment}', [PaymentApiController::class, 'destroy'])
            ->whereNumber('payment')->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Employee Activity API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/employee-activities')
    ->name('api.v1.employee-activities.')
    ->group(function (): void {
        Route::get('/', [EmployeeActivityController::class, 'index'])->name('index');
        Route::post('/', [EmployeeActivityController::class, 'store'])->name('store');

        Route::patch('/{employeeActivity}/verify', [EmployeeActivityController::class, 'verify'])
            ->whereNumber('employeeActivity')->name('verify');

        Route::patch('/{employeeActivity}/cancel-verification', [EmployeeActivityController::class, 'cancelVerification'])
            ->whereNumber('employeeActivity')->name('cancel-verification');

        Route::get('/{employeeActivity}', [EmployeeActivityController::class, 'show'])
            ->whereNumber('employeeActivity')->name('show');

        Route::put('/{employeeActivity}', [EmployeeActivityController::class, 'update'])
            ->whereNumber('employeeActivity')->name('update');

        Route::patch('/{employeeActivity}', [EmployeeActivityController::class, 'update'])
            ->whereNumber('employeeActivity')->name('patch');

        Route::delete('/{employeeActivity}', [EmployeeActivityController::class, 'destroy'])
            ->whereNumber('employeeActivity')->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Lookup API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/lookups')
    ->name('api.v1.lookups.')
    ->group(function (): void {
        Route::get('/employee-activities', [LookupController::class, 'employeeActivityFilters'])
            ->name('employee-activities');
        Route::get('/employees', [LookupController::class, 'employees'])
            ->name('employees');
        Route::get('/service-orders', [LookupController::class, 'serviceOrders'])
            ->name('service-orders');
        Route::get('/employee-activity-statuses', [LookupController::class, 'employeeActivityStatuses'])
            ->name('employee-activity-statuses');
    });
