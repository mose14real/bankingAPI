<?php

use App\Http\Controllers\AcctOfficersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomersController;

// Public Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
});

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Account Officer Routes
    Route::group(['middleware' => ['acct.officer']], function () {
        Route::controller(AcctOfficersController::class)->group(function () {
            Route::prefix('acct.officer')->group(function () {
                Route::get('check-customers-acct', 'checkCustomersAcct');
                Route::get('check-customer-acct/{id}', 'checkCustomerAcct');
                Route::post('open-acct', 'openAcct');
                Route::post('fund-acct/{id}', 'fundAcct');
                Route::patch('upgrade-acct/{id}', 'upgradeAcct');
                Route::delete('close-accct/{id}', 'closeAcct');
            });
        });
    });

    // Customer Routes
    Route::group(['middleware' => ['customer']], function () {
        Route::controller(CustomersController::class)->group(function () {
            Route::prefix('customer')->group(function () {
                Route::patch('transfer-fund/{id}', 'transferFund');
                Route::get('check-balance/{id}', 'checkBalance');
                Route::get('check-transfer-history/{id}', 'checkTransferHistory');
            });
        });
    });
});
