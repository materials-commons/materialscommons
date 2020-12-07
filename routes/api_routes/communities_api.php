<?php

use App\Http\Controllers\Api\Communities\AddDatasetToCommunityApiController;
use App\Http\Controllers\Api\Communities\CreateCommunityApiController;
use App\Http\Controllers\Api\Communities\CreateLinkInCommunityApiController;
use App\Http\Controllers\Api\Communities\DeleteFileFromCommunityApiController;
use App\Http\Controllers\Api\Communities\DeleteLinkFromCommunityApiController;
use App\Http\Controllers\Api\Communities\IndexMyCommunitiesApiController;
use App\Http\Controllers\Api\Communities\IndexPublicCommunitiesApiController;
use App\Http\Controllers\Api\Communities\IndexTagsForCommunityApiController;
use App\Http\Controllers\Api\Communities\RemoveDatasetFromCommunityApiController;
use App\Http\Controllers\Api\Communities\ShowCommunityApiController;
use App\Http\Controllers\Api\Communities\UpdateCommunityDatasetSelectionApiController;
use App\Http\Controllers\Api\Communities\UploadFileToCommunityApiController;
use Illuminate\Support\Facades\Route;

Route::put('/communities/{community}/datasets/selection', UpdateCommunityDatasetSelectionApiController::class)
     ->name('api.communities.datasets.selection');

Route::post('/communities', CreateCommunityApiController::class);

Route::get('/communities/public', IndexPublicCommunitiesApiController::class);
Route::get('/communities', IndexMyCommunitiesApiController::class);

Route::get('/communities/{community}', ShowCommunityApiController::class);

Route::post('/communities/{community}/datasets/{dataset}/add', AddDatasetToCommunityApiController::class);
Route::delete('/communities/{community}/datasets/{dataset}', RemoveDatasetFromCommunityApiController::class);

Route::post('/communities/{community}/upload', UploadFileToCommunityApiController::class);
Route::delete('/communities/{community}/files/{file}', DeleteFileFromCommunityApiController::class);

Route::post("/communities/{community}/links", CreateLinkInCommunityApiController::class);
Route::delete("/communities/{community}/links/{link}", DeleteLinkFromCommunityApiController::class);

Route::get('/communities/{community}/tags', IndexTagsForCommunityApiController::class);