<?php

use App\Http\Controllers\Web\Admin\MCFS\CreateTransferRequestWebController;
use App\Http\Controllers\Web\Admin\MCFS\SearchMCFSLogWebController;
use App\Http\Controllers\Web\Admin\MCFS\SetMCFSLogLevelWebController;
use App\Http\Controllers\Web\Admin\MCFS\ShowMCFSLogWebController;
use App\Http\Controllers\Web\Admin\MCFS\ShowTransferRequestWebController;
use Illuminate\Support\Facades\Route;

Route::get('/mcfs/transfer-requests/{transferRequest}', ShowTransferRequestWebController::class)
     ->name('mcfs.transfer-requests.show');

Route::post('/mcfs/transfer-requests', CreateTransferRequestWebController::class)
     ->name('mcfs.transfer-requests.create');

Route::get('/mcfs/log', ShowMCFSLogWebController::class)
     ->name('mcfs.log.show');

Route::post('/mcfs/log/update', SetMCFSLogLevelWebController::class)
     ->name('mcfs.log.set-log-level');

Route::get('/mcfs/log/search', SearchMCFSLogWebController::class)
     ->name('mcfs.log.search');
