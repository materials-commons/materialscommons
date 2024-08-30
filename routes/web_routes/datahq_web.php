<?php

use App\Http\Controllers\Web\DataHQ\IndexDataHQWebController;
use App\Http\Controllers\Web\DataHQ\ShowEntityAttributesWebController;
use App\Http\Controllers\Web\DataHQ\ShowResultsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/datahq', IndexDataHQWebController::class)
     ->name('projects.datahq.index');

Route::get('/projects/{project}/datahq/entities', ShowEntityAttributesWebController::class)
     ->name('projects.datahq.entities');

Route::get('/projects/{project}/datahq/results', ShowResultsWebController::class)
     ->name('projects.datahq.results');