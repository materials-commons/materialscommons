<?php

use App\Http\Controllers\Web\Site\ShowSiteStatisticsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/site/statistics', ShowSiteStatisticsWebController::class)
     ->name('site.statistics');
