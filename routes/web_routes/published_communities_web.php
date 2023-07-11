<?php

use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunitiesWebController;
use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunityDatasetsWebController;
use App\Http\Controllers\Web\Published\Communities\SearchCommunityForDatasetsMatchingTagWebController;
use App\Http\Controllers\Web\Published\Communities\SearchCommunityForDatasetsWithAuthorWebController;
use App\Http\Controllers\Web\Published\Communities\ShowPublishedCommunityFilesWebController;
use App\Http\Controllers\Web\Published\Communities\ShowPublishedCommunityLinksWebController;
use App\Http\Controllers\Web\Published\Communities\ShowPublishedCommunityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/communities', IndexPublishedCommunitiesWebController::class)
     ->name('public.communities.index');

Route::get('/communities/{community}/datasets', IndexPublishedCommunityDatasetsWebController::class)
     ->name('public.communities.datasets.index');

Route::get('/communities/{community}', ShowPublishedCommunityWebController::class)
     ->name('public.communities.show');

Route::get('/communities/{community}/files', ShowPublishedCommunityFilesWebController::class)
     ->name('public.communities.files.show');

Route::get('/communities/{community}/links', ShowPublishedCommunityLinksWebController::class)
     ->name('public.communities.links.show');

Route::get('/communities/{community}/search/authors', SearchCommunityForDatasetsWithAuthorWebController::class)
     ->name('public.communities.search.authors');

Route::get('/communities/{community}/search/tag', SearchCommunityForDatasetsMatchingTagWebController::class)
     ->name('public.communities.search.tag');

