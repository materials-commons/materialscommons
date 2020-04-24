<?php

use App\Http\Controllers\Api\Entities\CreateEntityApiController;
use App\Http\Controllers\Api\Entities\DeleteEntityApiController;
use App\Http\Controllers\Api\Entities\IndexEntitiesApiController;
use App\Http\Controllers\Api\Entities\ShowEntityApiController;
use App\Http\Controllers\Api\Entities\UpdateEntityApiController;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine EntitiesParams
 * @apiParam (URL Parameters) {String} api_token Mandatory API token
 * @apiParam (Body Parameters) {String} name name of entity
 * @apiParam (Body Parameters) {String} [description] description of entity
 */

/**
 * @api {post} /entities Create a new entity
 * @apiGroup Entities
 * @apiName CreateEntity
 * @apiDescription Creates a new entity
 * @apiUse EntitiesParams
 */
Route::post('/entities', CreateEntityApiController::class);

/**
 * @api {put} /entities/{entity_id} Update an existing entity
 * @apiGroup Entities
 * @apiName UpdateEntity
 * @apiDescription Change attributes of an existing entity
 * @apiUse EntitiesParams
 */
Route::put('/entities/{entity}', UpdateEntityApiController::class);

/**
 * @api {delete} /entities/{entity_id} Delete an existing entity
 * @apiGroup Entities
 * @apiName DeleteEntity
 * @apiDescription Delete an existing entity and all its relationships
 * @apiUse APITokenParam
 */
Route::delete('/project/{project}/entitites/{entity}', DeleteEntityApiController::class);

/**
 * @api {get} /entities/{entity_id} Show an existing entity
 * @apiGroup Entities
 * @apiName ShowEntity
 * @apiDescription Show details of a entity user has access to
 * @apiUse APITokenParam
 */
Route::get('/projects/{project}/entities/{entity}', ShowEntityApiController::class);

Route::get('/projects/{project}/entities', IndexEntitiesApiController::class);
