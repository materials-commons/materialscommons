<?php

use App\Http\Controllers\Api\Communities\UpdateCommunityDatasetSelectionApiController;
use Illuminate\Support\Facades\Route;

Route::put('/communities/{community}/datasets/selection', UpdateCommunityDatasetSelectionApiController::class)
     ->name('api.communities.datasets.selection');