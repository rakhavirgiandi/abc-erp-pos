<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ChooseCompanyController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\Companies\v1\PointOfSalesController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login']);
Route::post('/sessions', [AuthController::class, 'session']);
Route::get('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/new-password/{code}', [AuthController::class, 'newPassword']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/delete-request', [AuthController::class, 'deleteRequest']);

Route::group(['middleware' => ['auth.primary']], function () {
    Route::get('/', [ChooseCompanyController::class, 'index']);
    Route::get('/choose-company', [ChooseCompanyController::class, 'index']);
    Route::get('/transaction/{id}', [TransactionController::class, 'detail']);
    Route::get('/subscription/{company_id}/edition', [SubscriptionController::class, 'edition']);
    Route::get('/subscription/{company_id}/edition/{edition_id}/period', [SubscriptionController::class, 'period']);
    Route::get('/subscription/{company_id}/edition/{edition_id}/period/{period_id}/payment', [SubscriptionController::class, 'paymentMethod']);
    Route::middleware(['setup.config'])->group(function () {
        Route::post('/open-database', [ChooseCompanyController::class, 'openDatabase']);
    });

    Route::group([
        'middleware' => ['companies']
    ], function () {
        Route::prefix('pos')->group(function () {
            Route::get('/cashier', [PointOfSalesController::class, 'cashier'])->name('pos');
            Route::get('/print-receipts/{number}', [PointOfSalesController::class, 'printReceipt'])->name('pos.print-receipts');
            Route::get('/settings', [PointOfSalesController::class, 'settings'])->name('pos.settings');

            Route::post('/authorize', [PointOfSalesController::class, 'authorize'])->name('pos.authorize');
            Route::post('/logout', [PointOfSalesController::class, 'logout'])->name('pos.logout');
        });
    });
});