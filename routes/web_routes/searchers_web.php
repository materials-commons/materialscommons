<?php

use App\Http\Controllers\Web\Htmx\Searchers\FindProjectWebController;
use App\Http\Controllers\Web\Htmx\Searchers\FindUserWebController;
use Illuminate\Support\Facades\Route;

Route::get('/htmx/searchers/find-project', FindProjectWebController::class)
     ->name('htmx.searchers.find-project');

Route::get('/htmx/searchers/find-user', FindUserWebController::class)
     ->name('htmx.searchers.find-user');
