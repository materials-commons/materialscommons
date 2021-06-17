<?php

use App\Http\Controllers\Api\EntityStates\CreateEntityStateForEntityApiController;
use Illuminate\Support\Facades\Route;

Route::post('/projects/{project}/entities/{entity}/activities/{activity}/create-entity-state',
    CreateEntityStateForEntityApiController::class);

