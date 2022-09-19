<?php

use App\Http\Controllers\Web\Accounts\ShowAccountWebController;
use App\Http\Controllers\Web\Accounts\UpdateAccountEmailWebController;
use App\Http\Controllers\Web\Accounts\UpdateAccountGlobusUserWebController;
use App\Http\Controllers\Web\Accounts\UpdateAccountPasswordWebController;
use App\Http\Controllers\Web\Accounts\UpdateAccountUserDetailsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/accounts/show', ShowAccountWebController::class)
     ->name('accounts.show');

Route::post('/accounts/update/details', UpdateAccountUserDetailsWebController::class)
     ->name('accounts.update.details');

Route::post('/accounts/update/globus', UpdateAccountGlobusUserWebController::class)
     ->name('accounts.update.globus');

Route::post('/accounts/update/password', UpdateAccountPasswordWebController::class)
     ->name('accounts.update.password');

Route::post('/accounts/update/email', UpdateAccountEmailWebController::class)
     ->name('accounts.update.email');
