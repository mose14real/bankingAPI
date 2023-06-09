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
                Route::get('check-accts', 'checkAccts');
                Route::post('check-acct', 'checkAcct');
                Route::post('create-all', 'createAll');
                Route::post('open-new-acct', 'openNewAcct');
                Route::patch('credit-acct', 'counterDeposit');
                Route::patch('debit-acct', 'counterWithdrawal');
                Route::post('close-acct', 'closeAcct');
            });
        });
    });

    // Customer Routes
    Route::group(['middleware' => ['customer']], function () {
        Route::controller(CustomersController::class)->group(function () {
            Route::prefix('customer')->group(function () {
                Route::patch('transfer-fund', 'transferFund');
                Route::post('check-balance', 'retrieveBalance');
                Route::get('check-all-acct/{id}', 'retrieveAllAcct');
                Route::post('check-transfer-history', 'transferHistory');
            });
        });
    });
});
