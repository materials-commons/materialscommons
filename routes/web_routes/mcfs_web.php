<?php

use App\Http\Controllers\Web\Admin\MCFS\ShowTransferRequestWebController;
use Illuminate\Support\Facades\Route;

Route::get('/mcfs/transfer-requests/{transferRequest}', ShowTransferRequestWebController::class)
     ->name('mcfs.transfer-requests.show');
