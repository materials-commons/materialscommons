<?php

use App\Http\Controllers\Api\TourStateController;
use Illuminate\Support\Facades\Route;

Route::get('/tour-states', [TourStateController::class, 'index']);
Route::get('/tour-states/{tourName}', [TourStateController::class, 'show']);
Route::post('/tour-states', [TourStateController::class, 'store']);
Route::post('/tour-states/complete-step', [TourStateController::class, 'completeStep']);
Route::post('/tour-states/complete-tour', [TourStateController::class, 'completeTour']);
Route::post('/tour-states/reset', [TourStateController::class, 'reset']);
Route::post('/tour-states/reset-all', [TourStateController::class, 'resetAll']);