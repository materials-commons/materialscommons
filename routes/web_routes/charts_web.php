<?php

use App\Http\Controllers\Web\Projects\Charts\CreateChart;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/charts/create', CreateChart::class)
     ->name('projects.charts.create');
