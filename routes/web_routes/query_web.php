<?php

use App\Http\Controllers\Web\Query\ShowAttributesOverviewWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/attributes/show-overview', ShowAttributesOverviewWebController::class)
     ->name('projects.attributes.show-overview');
