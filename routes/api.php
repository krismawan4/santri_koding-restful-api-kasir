<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/login', 'login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/auth/me', 'me');
            Route::post('/auth/logout', 'logout');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index');
            Route::get('/categories/{id}', 'show');
        });
        Route::controller(TableController::class)->group(function () {
            Route::get('/tables', 'index');
            Route::get('/tables/{id}', 'show');
        });
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/items', 'index');
            Route::get('/items/{id}', 'show');
        });
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/transactions', 'index');
            Route::get('/transactions/{id}', 'show');
        });
        Route::get('/dashboard',DashboardController::class);
        Route::get('reports/income-report', IncomeReportController::class);
    });

    Route::middleware(['auth:sanctum', 'role:kasir'])->group(function () {
        Route::controller(TransactionController::class)->group(function () {
            Route::post('/transactions', 'store');
            Route::put('/transactions/{id}', 'update');
            Route::delete('/transactions/{id}', 'destroy');
            Route::put('/transactions/{id}/status', 'updateStatus');
        });
    });

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::post('/categories', 'store');
            Route::put('/categories/{id}', 'update');
            Route::delete('/categories/{id}', 'destroy');
        });
        Route::controller(TableController::class)->group(function () {
            Route::post('/tables', 'store');
            Route::put('/tables/{id}', 'update');
            Route::delete('/tables/{id}', 'destroy');
        });
        Route::controller(ItemController::class)->group(function () {
            Route::post('/items', 'store');
            Route::put('/items/{id}', 'update');
            Route::delete('/items/{id}', 'destroy');
        });
    });

});
