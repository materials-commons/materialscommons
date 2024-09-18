<?php

use App\Http\Controllers\Web\DataHQ\AddFilteredViewWebController;
use App\Http\Controllers\Web\DataHQ\ComputationsHQ\IndexComputationsHQWebController;
use App\Http\Controllers\Web\DataHQ\IndexDataHQWebController;
use App\Http\Controllers\Web\DataHQ\ProcessesHQ\IndexProcessesHQWebController;
use App\Http\Controllers\Web\DataHQ\SamplesHQ\IndexSamplesHQWebController;
use App\Http\Controllers\Web\DataHQ\SamplesHQ\ShowActivityAttributeFiltersSampleHQWebController;
use App\Http\Controllers\Web\DataHQ\SamplesHQ\ShowActivityFiltersSampleHQWebController;
use App\Http\Controllers\Web\DataHQ\SamplesHQ\ShowEntityAttributeFiltersSampleHQWebController;
use App\Http\Controllers\Web\DataHQ\SaveDataForWebController;
use App\Http\Controllers\Web\DataHQ\ShowEntityAttributesWebController;
use App\Http\Controllers\Web\DataHQ\ShowResultsWebController;
use Illuminate\Support\Facades\Route;

// DataHQ
Route::get('/projects/{project}/datahq', IndexDataHQWebController::class)
     ->name('projects.datahq.index');

Route::get('/projects/{project}/datahq/add-filtered-view', AddFilteredViewWebController::class)
     ->name('projects.datahq.add-filtered-view');

Route::post('/projects/{project}/save-data-for', SaveDataForWebController::class)
     ->name('projects.datahq.save-data-for');

// SamplesHQ

Route::get('/projects/{project}/datahq/sampleshq', IndexSamplesHQWebController::class)
     ->name('projects.datahq.sampleshq.index');

// ComputationsHQ

Route::get('/projects/{project}/datahq/computationshq', IndexComputationsHQWebController::class)
     ->name('projects.datahq.computationshq.index');

// ProcessesHQ

Route::get('/projects/{project}/datahq/processeshq', IndexProcessesHQWebController::class)
     ->name('projects.datahq.processeshq.index');