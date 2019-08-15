<?php

use App\Http\Controllers\api\ProjectActionsAPIController;
use App\Http\Controllers\api\ProjectEntitiesAPIController;
use App\Http\Controllers\api\Projects\ActionAttributesAPIController;
use App\Http\Controllers\api\Projects\ActionEntityStatesAPIController;
use App\Http\Controllers\api\Projects\ActionFilesAPIController;
use App\Http\Controllers\api\Projects\AttributesAPIController;
use App\Http\Controllers\api\Projects\EntityStateAttributesAPIController;
use App\Http\Controllers\api\Projects\EntityStateFilesAPIController;
use App\Http\Controllers\api\Projects\ValuesAPIController;
use App\Http\Controllers\api\ProjectsAPIController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/projects', ProjectsAPIController::class);
    Route::apiResource('/projects/{project}/entities', ProjectEntitiesAPIController::class);

    Route::apiResource('/projects/{project}/actions', ProjectActionsAPIController::class);
    Route::apiResource('/projects/{project}/actions/{action}/entity-state', ActionEntityStatesAPIController::class);
    Route::apiResource('/projects/{project}/actions/{action}/files', ActionFilesAPIController::class);
    Route::apiResource('/projects/{project}/actions/{action}/attributes', ActionAttributesAPIController::class);

    Route::apiResource('/projects/{project}/entity-state/{entity_state}/files', EntityStateFilesAPIController::class);
    Route::apiResource('/projects/{project}/entity-state/{entity_state}/attributes', EntityStateAttributesAPIController::class);

    Route::apiResource('/projects/{project}/attributes', AttributesAPIController::class);

    Route::apiResource('/projects/{project}/values', ValuesAPIController::class);


    //    Route::post('/projects/{project}/relationships/files/{file}/add_action/{action}', 'api\Relationships\FileRelationshipsAPIController@addAction');
});

//Route::middleware('auth:api')->resource('/projects', 'api\ProjectsAPIController');

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
