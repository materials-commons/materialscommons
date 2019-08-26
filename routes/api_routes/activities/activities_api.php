<?php

use App\Http\Controllers\Api\Activities\CreateActivityApiController;
use App\Http\Controllers\Api\Activities\DeleteActivityApiController;
use App\Http\Controllers\Api\Activities\ShowActivityApiController;
use App\Http\Controllers\Api\Activities\UpdateActivityApiController;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine ActivitiesParams
 * @apiParam (URL Parameters) {String} api_token Mandatory API token
 * @apiParam (Body Parameters) {String} name Mandatory name of activity
 * @apiParam (Body Parameters) {Integer} project_id Mandatory id of project that Activity should belong to
 * @apiParam (Body Parameters) {String} [description] Optional description for activity
 */

/**
 * @api {post} /activities Create a new activity
 * @apiGroup Activities
 * @apiName CreateActivity
 * @apiDescription Creates a new activity in a project
 * @apiUse ActivitiesParams
 *
 * @apiUse AuthenticationError
 * @apiUse ValidationError
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Created
 * {
 *      "created_at": "2019-08-19 15:00:56",
 *       "id": 1,
 *       "name": "activity1",
 *       "owner_id": 1,
 *       "project_id": "1",
 *       "updated_at": "2019-08-19 15:00:56",
 *       "uuid": "2eab0d89-bc15-408e-b0fb-772c8bf216dd"
 * }
 *
 */
Route::post('/activities', CreateActivityApiController::class);

/**
 * @api {put} /activities/{activity_id} Update an existing activity
 * @apiGroup Activities
 * @apiName UpdateActivity
 * @apiDescription Updates attributes on an existing activity
 * @apiUse ActivitiesParams
 */
Route::put('/activities/{activity}', UpdateActivityApiController::class);

/**
 * @api {delete} /activities/{activity_id} Delete an existing activity
 * @apiGroup Activities
 * @apiName DeleteActivity
 * @apiDescription Delete an existing activity and all of its relationships,
 * does not delete the items the relationships point at.
 * @apiUse APITokenParam
 */
Route::delete('/activities/{activity}', DeleteActivityApiController::class);

/**
 * @api {get} /activities/{activity_id} Show an existing activity
 * @apiGroup Activities
 * @apiName ShowActivity
 * @apiDescription Show an existing activity
 * @apiUse APITokenParam
 */
Route::get('/activities/{activity}', ShowActivityApiController::class);
