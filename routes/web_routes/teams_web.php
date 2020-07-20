<?php

use App\Http\Controllers\Web\Teams\CreateTeamWebController;
use App\Http\Controllers\Web\Teams\DeleteTeamWebController;
use App\Http\Controllers\Web\Teams\DestroyTeamWebController;
use App\Http\Controllers\Web\Teams\EditTeamWebController;
use App\Http\Controllers\Web\Teams\IndexTeamsWebController;
use App\Http\Controllers\Web\Teams\ModifyTeamsUsersAndProjectsWebController;
use App\Http\Controllers\Web\Teams\ShowTeamWebController;
use App\Http\Controllers\Web\Teams\StoreTeamWebController;
use App\Http\Controllers\Web\Teams\UpdateTeamWebController;
use Illuminate\Support\Facades\Route;

Route::get('/teams', IndexTeamsWebController::class)
     ->name('teams.index');

Route::get('/teams/create', CreateTeamWebController::class)
     ->name('teams.create');

Route::post('/teams', StoreTeamWebController::class)
     ->name('teams.store');

Route::get('/teams/{team}', ShowTeamWebController::class)
     ->name('teams.show');
Route::get('/teams/{team}/members', ShowTeamWebController::class)
     ->name('teams.members.show');
Route::get('/teams/{team}/admins', ShowTeamWebController::class)
     ->name('teams.admins.show');


Route::get('/teams/{team}/edit', EditTeamWebController::class)
     ->name('teams.edit');
Route::put('/teams/{team}/update', UpdateTeamWebController::class)
     ->name('teams.update');

Route::get('/teams/{team}/modify-users-projects', ModifyTeamsUsersAndProjectsWebController::class)
     ->name('teams.modify-users-projects');

Route::get('/teams/{team}/delete', DeleteTeamWebController::class)
     ->name('teams.delete');
Route::delete('/teams/{team}/destroy', DestroyTeamWebController::class)
     ->name('teams.destroy');

Route::prefix('/projects/{project}')->group(function () {
});

