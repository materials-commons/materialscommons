<?php

use App\Http\Controllers\Web\Projects\AddUserToProjectWebController;
use App\Http\Controllers\Web\Projects\CreateProjectWebController;
use App\Http\Controllers\Web\Projects\DeleteProjectWebController;
use App\Http\Controllers\Web\Projects\EditProjectWebController;
use App\Http\Controllers\Web\Projects\IndexProjectsWebController;
use App\Http\Controllers\Web\Projects\RemoveUserFromProjectWebController;
use App\Http\Controllers\Web\Projects\ShowProjectWebController;
use App\Http\Controllers\Web\Projects\StoreProjectWebController;
use App\Http\Controllers\Web\Projects\UpdateProjectWebController;
use App\Http\Controllers\Web\Projects\Users\CreateProjectUsersWebController;
use App\Http\Controllers\Web\Projects\Users\IndexProjectUsersWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/create', CreateProjectWebController::class)->name('projects.create');
Route::post('/projects', StoreProjectWebController::class)->name('projects.store');

Route::get('/projects', IndexProjectsWebController::class)->name('projects.index');
Route::get('/projects/{project}', ShowProjectWebController::class)->name('projects.show');

Route::patch('/projects/{project}', UpdateProjectWebController::class)->name('projects.update');
Route::get('/projects/{project}/edit', EditProjectWebController::class)->name('projects.edit');

Route::delete('/projects/{project}', DeleteProjectWebController::class)->name('projects.destroy');

Route::delete('/projects/{project}/users/{user}/remove', RemoveUserFromProjectWebController::class)
     ->name('projects.users.remove');

Route::post('/projects/{project}/users/{user}', AddUserToProjectWebController::class)
     ->name('projects.users.add');

Route::get('/projects/{project}/users', IndexProjectUsersWebController::class)
     ->name('projects.users.index');

Route::get('/projects/{project}/users/create', CreateProjectUsersWebController::class)
     ->name('projects.users.create');
