<?php

use App\Http\Controllers\Web\Admin\MCFS\CreateTransferRequestWebController;
use App\Http\Controllers\Web\Admin\MCFS\ShowTransferRequestWebController;
use Illuminate\Support\Facades\Route;

Route::get('/mcfs/transfer-requests/{transferRequest}', ShowTransferRequestWebController::class)
     ->name('mcfs.transfer-requests.show');

Route::post('/mcfs/transfer-requests', CreateTransferRequestWebController::class)
     ->name('mcfs.transfer-requests.create');