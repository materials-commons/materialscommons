<?php

use App\Http\Controllers\Web\Dashboard\ShowDashboardWebController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', ShowDashboardWebController::class)
     ->name('dashboard');
