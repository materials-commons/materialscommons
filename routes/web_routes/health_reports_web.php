<?php

use App\Http\Controllers\Web\Projects\HealthReports\IndexProjectHealthReportsWebController;
use App\Http\Controllers\Web\Projects\HealthReports\ShowProjectHealthReportWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/health-reports/show/{date}', ShowProjectHealthReportWebController::class)
     ->name('projects.health-reports.show');
Route::get('/projects/{project}/health-reports', IndexProjectHealthReportsWebController::class)
     ->name('projects.health-reports.index');
