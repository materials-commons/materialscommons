<?php

use App\Http\Controllers\Web\Mql\StoreMQLQueryWebController;
use Illuminate\Support\Facades\Route;

Route::post('/projects/{project}/mql/store', StoreMQLQueryWebController::class)
     ->name('projects.mql.store');

