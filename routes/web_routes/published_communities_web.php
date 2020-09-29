<?php

use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunitiesWebController;
use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunityDatasetsWebController;
use App\Http\Controllers\Web\Published\Communities\ShowPublishedCommunityFilesWebController;
use App\Http\Controllers\Web\Published\Communities\ShowPublishedCommunityLinksWebController;
use App\Http\Controllers\Web\Published\Communities\ShowPublishedCommunityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/communities', IndexPublishedCommunitiesWebController::class)->name('communities.index');

Route::get('/communities/{community}/datasets', IndexPublishedCommunityDatasetsWebController::class)
     ->name('communities.datasets.index');

Route::get('/communities/{community}', ShowPublishedCommunityWebController::class)
     ->name('communities.show');

Route::get('/communities/{community}/files', ShowPublishedCommunityFilesWebController::class)
     ->name('communities.files.show');

Route::get('/communities/{community}/links', ShowPublishedCommunityLinksWebController::class)
     ->name('communities.links.show');

