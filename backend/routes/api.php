<?php

use App\Http\Controllers\Api\V1\AdminUserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\StoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json([
        'status'    => 'ok',
        'service'   => config('app.name'),
        'timestamp' => now()->toIso8601String(),
    ]));

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Products — read available to all authenticated roles; write/deactivate to admin+
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);

        // Orders — all authenticated roles can read; cashier/admin/superadmin can create
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store'])->middleware('role:cashier,admin,superadmin');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->middleware('role:cashier,admin,superadmin');

        // Cashier dashboard
        Route::get('/cashier/overview', [DashboardController::class, 'cashier'])
            ->middleware('role:cashier,admin,superadmin');

        // Admin routes
        Route::prefix('admin')
            ->middleware('role:admin,superadmin')
            ->group(function () {
                Route::get('/overview', [DashboardController::class, 'admin']);

                Route::get('/reports/sales', [ReportController::class, 'sales']);
                Route::get('/reports/products', [ReportController::class, 'products']);

                Route::post('/products', [ProductController::class, 'store']);
                Route::put('/products/{product}', [ProductController::class, 'update']);
                Route::delete('/products/{product}', [ProductController::class, 'destroy']);
            });

        // Superadmin routes
        Route::prefix('superadmin')
            ->middleware('role:superadmin')
            ->group(function () {
                Route::get('/overview', [DashboardController::class, 'superadmin']);

                Route::get('/reports/stores', [ReportController::class, 'stores']);

                Route::get('/stores', [StoreController::class, 'index']);
                Route::post('/stores', [StoreController::class, 'store']);
                Route::get('/stores/{store}', [StoreController::class, 'show']);
                Route::put('/stores/{store}', [StoreController::class, 'update']);
                Route::delete('/stores/{store}', [StoreController::class, 'destroy']);

                Route::get('/admins', [AdminUserController::class, 'index']);
                Route::post('/admins', [AdminUserController::class, 'store']);
                Route::put('/admins/{user}', [AdminUserController::class, 'update']);
                Route::delete('/admins/{user}', [AdminUserController::class, 'destroy']);
            });
    });
});
