<?php

/**
 * Script untuk menambahkan permission middleware ke semua routes
 * Jalankan: php add-middleware-to-routes.php
 */

$file = __DIR__ . '/routes/web.php';
$content = file_get_contents($file);

// Mapping dari route name patterns ke permission middleware
$replacements = [
    // Employees routes
    "Route::get('/create', 'create')\n                                ->name('create');\n\n                            Route::post('/', 'store')\n                                ->name('store');\n                        });\n\n                    /*\n                    |----------------------------------------------------------\n                    | Recycle Binâ€"khususSuperAdmin\n\n                    |----------------------------------------------------------\n                    |\n                    | Route statis wajib berada sebelum /{employee}.\n                    |\n                    */\n\n                    Route::middleware('role:super_admin')\n                        ->group(function (): void {\n                            Route::get('/trash', 'trash')" 
        => "Route::get('/create', 'create')\n                                ->middleware('check.menu.permission:employees.create')\n                                ->name('create');\n\n                            Route::post('/', 'store')\n                                ->middleware('check.menu.permission:employees.create')\n                                ->name('store');\n                        });\n\n                    /*\n                    |----------------------------------------------------------\n                    | Recycle Binâ€"khususSuperAdmin\n\n                    |----------------------------------------------------------\n                    |\n                    | Route statis wajib berada sebelum /{employee}.\n                    |\n                    */\n\n                    Route::middleware('role:super_admin')\n                        ->group(function (): void {\n                            Route::get('/trash', 'trash')\n                                ->middleware('check.menu.permission:employees.delete')",
    
    // General pattern for View middleware
    "->name('index');\n\n                        /*\n                         * Diletakkan paling bawah agar create dan trash tidak\n                         * dianggap sebagai parameter {employee}.\n                         */\n                        Route::get('/{employee}', 'show')\n                            ->whereNumber('employee')\n                            ->name('show');\n                    });\n\n            /*\n            |--------------------------------------------------------------\n            | Alias Employment untuk menu sidebar" 
        => "->middleware('check.menu.permission:employees.view')\n                            ->name('index');\n\n                        /*\n                         * Diletakkan paling bawah agar create dan trash tidak\n                         * dianggap sebagai parameter {employee}.\n                         */\n                        Route::get('/{employee}', 'show')\n                            ->middleware('check.menu.permission:employees.view')\n                            ->whereNumber('employee')\n                            ->name('show');\n                    });\n\n            /*\n            |--------------------------------------------------------------\n            | Alias Employment untuk menu sidebar",
];

// Apply replacements
foreach ($replacements as $search => $replace) {
    if (strpos($content, $search) !== false) {
        $content = str_replace($search, $replace, $content);
        echo "✓ Updated: " . substr($search, 0, 50) . "...\n";
    } else {
        echo "✗ Not found: " . substr($search, 0, 50) . "...\n";
    }
}

file_put_contents($file, $content);
echo "\n✓ Routes updated successfully!\n";