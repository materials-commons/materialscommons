<?php

use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunitiesWebController;
use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunityDatasetsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/communities', IndexPublishedCommunitiesWebController::class)->name('communities.index');
Route::get('/communities/{community}/datasets', IndexPublishedCommunityDatasetsWebController::class)
     ->name('communities.datasets.index');

