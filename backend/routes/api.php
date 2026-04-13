<?php

use App\Http\Controllers\Api\V1\AdminUserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json([
        'status' => 'ok',
        'service' => config('app.name'),
        'timestamp' => now()->toIso8601String(),
    ]));

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store'])->middleware('role:cashier,admin,superadmin');

        Route::get('/cashier/overview', [DashboardController::class, 'cashier'])
            ->middleware('role:cashier,admin,superadmin');

        Route::prefix('admin')
            ->middleware('role:admin,superadmin')
            ->group(function () {
                Route::get('/overview', [DashboardController::class, 'admin']);
                Route::post('/products', [ProductController::class, 'store']);
                Route::put('/products/{product}', [ProductController::class, 'update']);
            });

        Route::prefix('superadmin')
            ->middleware('role:superadmin')
            ->group(function () {
                Route::get('/overview', [DashboardController::class, 'superadmin']);
                Route::get('/stores', [StoreController::class, 'index']);
                Route::post('/stores', [StoreController::class, 'store']);
                Route::get('/stores/{store}', [StoreController::class, 'show']);
                Route::put('/stores/{store}', [StoreController::class, 'update']);

                Route::get('/admins', [AdminUserController::class, 'index']);
                Route::post('/admins', [AdminUserController::class, 'store']);
                Route::put('/admins/{user}', [AdminUserController::class, 'update']);
            });
    });
});
