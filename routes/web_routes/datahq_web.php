<?php

use App\Http\Controllers\Web\DataHQ\IndexDataHQWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/datahq', IndexDataHQWebController::class)
     ->name('projects.datahq.index');