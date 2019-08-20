<?php

use App\Http\Controllers\Api\Activities\CreateActivityApiController;
use App\Http\Controllers\Api\Activities\DeleteActivityApiController;
use App\Http\Controllers\Api\Activities\ShowActivityApiController;
use App\Http\Controllers\Api\Activities\UpdateActivityApiController;
use App\Http\Controllers\api\ProjectActionsAPIController;
use App\Http\Controllers\api\ProjectEntitiesAPIController;
use App\Http\Controllers\api\Projects\ActionAttributesAPIController;
use App\Http\Controllers\api\Projects\ActionEntityStatesAPIController;
use App\Http\Controllers\api\Projects\ActionFilesAPIController;
use App\Http\Controllers\Api\Projects\Activities\IndexProjectActivitiesApiController;
use App\Http\Controllers\api\Projects\AttributesAPIController;
use App\Http\Controllers\api\Projects\EntityStateAttributesAPIController;
use App\Http\Controllers\api\Projects\EntityStateFilesAPIController;
use App\Http\Controllers\api\Projects\ValuesAPIController;
use App\Http\Controllers\api\ProjectsAPIController;
use App\Http\Middleware\UserCanAccessProject;
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
    Route::apiResource('/projects', ProjectsAPIController::class)->middleware(UserCanAccessProject::class);
    Route::apiResource('/projects/{project}/entities', ProjectEntitiesAPIController::class);

    Route::apiResource('/projects/{project}/actions', ProjectActionsAPIController::class);
    Route::apiResource('/projects/{project}/actions/{action}/entity-state', ActionEntityStatesAPIController::class);
    Route::apiResource('/projects/{project}/actions/{action}/files', ActionFilesAPIController::class);
    Route::apiResource('/projects/{project}/actions/{action}/attributes', ActionAttributesAPIController::class);

    Route::apiResource('/projects/{project}/entity-state/{entity_state}/files', EntityStateFilesAPIController::class);
    Route::apiResource('/projects/{project}/entity-state/{entity_state}/attributes',
        EntityStateAttributesAPIController::class);

    Route::apiResource('/projects/{project}/attributes', AttributesAPIController::class);

    Route::apiResource('/projects/{project}/values', ValuesAPIController::class);

    /**
     * @apiDefine AuthenticationError
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 401 Unauthorized
     * {
     *      "message": "Unauthenticated."
     * }
     */

    /**
     * @apiDefine ValidationError
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 422 Unprocessable Entity
     * {
     *      "errors": {
     *          "project_id": [
     *              "The project id field is required."
     *          ]
     *      },
     *      "message": "The given data was invalid."
     * }
     */

    /**
     *
     */


    /**
     * @api {post} /activities Create a new activity
     * @apiName CreateActivity
     * @apiDescription Creates a new activity in a project
     * @apiParam (URL Parameters) {String} api_token Mandatory API token
     * @apiParam (Body Parameters) {String} name Mandatory name of activity
     * @apiParam (Body Parameters) {Integer} project_id Mandatory id of project that Activity should belong to
     * @apiParam (Body Parameters) {String} [description] Optional description for activity
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
     * @apiVersion 0.1.0
     * @apiName CreateActivity
     * @apiGroup Activities
     */
    Route::post('/activities', CreateActivityApiController::class);

    Route::put('/activities/{activity}', UpdateActivityApiController::class);
    Route::delete('/activities/{activity}', DeleteActivityApiController::class);
    Route::get('/activities/{activity}', ShowActivityApiController::class);
    Route::get('/projects/{project}/activities', IndexProjectActivitiesApiController::class);

    //    Route::post('/projects/{project}/relationships/files/{file}/add_action/{action}', 'api\Relationships\FileRelationshipsAPIController@addAction');
});

//Route::middleware('auth:api')->resource('/projects', 'api\ProjectsAPIController');

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
