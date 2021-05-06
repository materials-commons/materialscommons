<?php

use App\Http\Controllers\Api\Projects\AddAdminToProjectApiController;
use App\Http\Controllers\Api\Projects\AddUserToProjectApiController;
use App\Http\Controllers\Api\Projects\CreateProjectApiController;
use App\Http\Controllers\Api\Projects\DeleteProjectApiController;
use App\Http\Controllers\Api\Projects\IndexProjectsApiController;
use App\Http\Controllers\Api\Projects\RemoveAdminFromProjectApiController;
use App\Http\Controllers\Api\Projects\RemoveUserFromProjectApiController;
use App\Http\Controllers\Api\Projects\ShowProjectApiController;
use App\Http\Controllers\Api\Projects\UpdateProjectApiController;
use App\Http\Middleware\UserCanAccessProject;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine ProjectsParams
 * @apiParam (URL Parameters) {String} api_token Mandatory API token
 * @apiParam (Body Parameters) {String} name name of project
 * @apiParam (Body Parameters) {String} [description] description of project
 */

Route::middleware(UserCanAccessProject::class)->group(function() {
    /**
     * @api {post} /projects Create a new project
     * @apiGroup Projects
     * @apiName CreateProject
     * @apiDescription Creates a new project
     * @apiUse ProjectsParams
     */
    Route::post('/projects', CreateProjectApiController::class);

    /**
     * @api {get} /projects Get list of projects for user
     * @apiGroup Projects
     * @apiName IndexProjects
     * @apiDescription Get list of all projects user owns or is member of
     * @apiUse APITokenParam
     */
    Route::get('/projects', IndexProjectsApiController::class);

    /**
     * @api {put} /projects/{project_id} Update an existing project
     * @apiGroup Projects
     * @apiName UpdateProject
     * @apiDescription Change attributes of an existing project
     * @apiUse ProjectsParams
     */
    Route::put('/projects/{project}', UpdateProjectApiController::class);

    /**
     * @api {delete} /projects/{project_id} Delete an existing project
     * @apiGroup Projects
     * @apiName DeleteProject
     * @apiDescription Delete an existing project and all its relationships
     * @apiUse APITokenParam
     */
    Route::delete('/projects/{project}', DeleteProjectApiController::class);

    /**
     * @api {get} /projects/{project_id} Show an existing project
     * @apiGroup Projects
     * @apiName ShowProject
     * @apiDescription Show details of a project user has access to
     * @apiUse APITokenParam
     */
    Route::get('/projects/{project}', ShowProjectApiController::class);

    Route::put('/projects/{project}/add-user/{user}', AddUserToProjectApiController::class);
    Route::put('/projects/{project}/remove-user/{user}', RemoveUserFromProjectApiController::class);

    Route::put('/projects/{project}/add-admin/{user}', AddAdminToProjectApiController::class);
    Route::put('/projects/{project}/remove-admin/{user}', RemoveAdminFromProjectApiController::class);
});
