<?php

use App\Http\Controllers\Web\Projects\ShowProjectWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}', ShowProjectWebController::class)->name('public.projects.show');

