<?php

use App\Http\Controllers\Web\DataHQ\GetAttributeDetailsForQueryBuilder;
use App\Http\Controllers\Web\DataHQ\IndexDataHQWebController;
use App\Http\Controllers\Web\DataHQ\SamplesHQ\DownloadDataForChartWebController;
use App\Http\Controllers\Web\DataHQ\SamplesHQ\GetDataForChartWebController;
use Illuminate\Support\Facades\Route;

// DataHQ (The only one we should need is IndexDataHQWebController)
Route::get('/projects/{project}/datahq', IndexDataHQWebController::class)
     ->name('projects.datahq.index');

Route::get('/projects/{project}/qb-attribute-details', GetAttributeDetailsForQueryBuilder::class)
     ->name('projects.datahq.qb-attribute-details');

// SamplesHQ (These will be removed)

Route::post('/projects/{project}/datahq/sampleshq/get-chart-data', GetDataForChartWebController::class)
     ->name('projects.datahq.sampleshq.get-chart-data');

Route::post('/projects/{project}/datahq/sampleshq/download-chart-data', DownloadDataForChartWebController::class)
     ->name('projects.datahq.sampleshq.download-chart-data');
