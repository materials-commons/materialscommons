<?php

use App\Http\Controllers\Web\DataExplorer\IndexDataExplorerWebController;
use App\Http\Controllers\Web\DataExplorer\ShowDataExplorerDataSourceWebController;
use App\Http\Controllers\Web\DataExplorer\ShowDataExplorerProcessAttributesWebController;
use App\Http\Controllers\Web\DataExplorer\ShowDataExplorerSampleAttributesWebController;
use App\Http\Controllers\Web\DataExplorer\ShowDataExplorerSampleDetailsWebController;
use App\Http\Controllers\Web\DataExplorer\ShowDataExplorerSampleFilesWebController;
use App\Http\Controllers\Web\DataExplorer\ShowDataExplorerSamplesTableWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/data-explorer', IndexDataExplorerWebController::class)
     ->name('projects.data-explorer.index');

Route::get('/projects/{project}/data-explorer/samples', ShowDataExplorerSamplesTableWebController::class)
     ->name('projects.data-explorer.samples');

Route::get('/projects/{project}/data-explorer/sample-details', ShowDataExplorerSampleDetailsWebController::class)
     ->name('projects.data-explorer.sample-details');

Route::get('/projects/{project}/data-explorer/sample-files', ShowDataExplorerSampleFilesWebController::class)
     ->name('projects.data-explorer.sample-files');

Route::get('/projects/{project}/data-explorer/sample-attributes', ShowDataExplorerSampleAttributesWebController::class)
     ->name('projects.data-explorer.sample-attributes');

Route::get('/projects/{project}/data-explorer/process-attributes',
    ShowDataExplorerProcessAttributesWebController::class)
     ->name('projects.data-explorer.process-attributes');

Route::get('/projects/{project}/data-explorer/data-source', ShowDataExplorerDataSourceWebController::class)
     ->name('projects.data-explorer.data-source');
