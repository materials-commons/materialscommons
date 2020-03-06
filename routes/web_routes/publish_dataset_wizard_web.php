<?php

use App\Http\Controllers\Web\PublishWizard\ChooseCreateOrSelectProjectStepWebController;
use App\Http\Controllers\Web\PublishWizard\CreateProjectStepWebController;
use App\Http\Controllers\Web\PublishWizard\CreateWorkflowStepWebController;
use App\Http\Controllers\Web\PublishWizard\SelectProjectStepWebController;
use App\Http\Controllers\Web\PublishWizard\StoreProjectStepWebController;
use Illuminate\Support\Facades\Route;

Route::get('/publish/wizard/select_project', SelectProjectStepWebController::class)
     ->name('publish.wizard.select_project');

Route::post('/publish/wizard/store_project', StoreProjectStepWebController::class)
     ->name('publish.wizard.store_project');

Route::get('/publish/wizard/{project}/create_workflow', CreateWorkflowStepWebController::class)
     ->name('publish.wizard.create_workflow');

Route::get('/publish/wizard/choose_create_or_select_project', ChooseCreateOrSelectProjectStepWebController::class)
     ->name('publish.wizard.choose_create_or_select_project');

Route::get('/publish/wizard/create_project', CreateProjectStepWebController::class)
     ->name('publish.wizard.create_project');

