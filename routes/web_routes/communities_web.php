<?php

use App\Http\Controllers\Web\Communities\CreateCommunityWebController;
use App\Http\Controllers\Web\Communities\DeleteCommunityWebController;
use App\Http\Controllers\Web\Communities\DestroyCommunityWebController;
use App\Http\Controllers\Web\Communities\EditCommunityWebController;
use App\Http\Controllers\Web\Communities\IndexCommunitiesWebController;
use App\Http\Controllers\Web\Communities\ShowCommunityRecommendedPracticesWebController;
use App\Http\Controllers\Web\Communities\ShowCommunityWebController;
use App\Http\Controllers\Web\Communities\StoreCommunityWebController;
use App\Http\Controllers\Web\Communities\UpdateCommunityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/communities', IndexCommunitiesWebController::class)
     ->name('communities.index');

Route::get('/communities/create', CreateCommunityWebController::class)
     ->name('communities.create');

Route::post('/communities', StoreCommunityWebController::class)
     ->name('communities.store');

Route::get('/communities/{community}', ShowCommunityWebController::class)
     ->name('communities.show');

Route::get('/communities/{community}/practices', ShowCommunityRecommendedPracticesWebController::class)
     ->name('communities.practices.show');

Route::get('/communities/{community}/edit', EditCommunityWebController::class)
     ->name('communities.edit');

Route::put('/communities/{community}', UpdateCommunityWebController::class)
     ->name('communities.update');

Route::get('/communities/{community}/delete', DeleteCommunityWebController::class)
     ->name('communities.delete');

Route::delete('/communities/{community}', DestroyCommunityWebController::class)
     ->name('communities.destroy');



