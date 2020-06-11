<?php

use App\Http\Controllers\Api\Users\GetUserByEmailApiController;
use App\Http\Controllers\Api\Users\GetUserByIdApiController;
use App\Http\Controllers\Api\Users\ListUsersApiController;
use Illuminate\Support\Facades\Route;

Route::get('/users/by-email/{email}', GetUserByEmailApiController::class);
Route::get('/users', ListUsersApiController::class);
Route::get('/users/{user}', GetUserByIdApiController::class);


