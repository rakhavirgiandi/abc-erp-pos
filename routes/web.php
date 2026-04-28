<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\PointOfSalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('authorizes', [AuthController::class, 'authorizes']);
Route::get('login', [AuthController::class, 'index']);
Route::get('logout', [AuthController::class, 'logout']);
Route::group(['middleware' => ['auth.primary']], function () {
    Route::prefix('pos')->group(function () {
        Route::get('/cashier', [PointOfSalesController::class, 'cashier']);
        Route::get('/print-receipts/{number}', [PointOfSalesController::class, 'printReceipt']);
        Route::get('/settings', [PointOfSalesController::class, 'settings']);
        Route::post('/authorize', [PointOfSalesController::class, 'authorize']);
        Route::post('/logout', [PointOfSalesController::class, 'logout']);
    });
});
