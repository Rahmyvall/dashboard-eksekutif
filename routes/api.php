<?php

declare (strict_types = 1);

use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\RoleApiController;
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
