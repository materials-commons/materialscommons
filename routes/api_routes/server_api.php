<?php

use App\Http\Controllers\Api\Server\ShowServerInfoApiController;
use Illuminate\Support\Facades\Route;

Route::get('/server/info', ShowServerInfoApiController::class);

