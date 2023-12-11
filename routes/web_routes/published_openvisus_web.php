<?php

use App\Http\Controllers\Web\Published\OpenVisus\IndexOpenVisusDatasetsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/openvisus/index', IndexOpenVisusDatasetsWebController::class)
     ->name('public.openvisus.index');