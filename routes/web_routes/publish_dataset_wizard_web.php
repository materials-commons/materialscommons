<?php

use App\Http\Controllers\Web\PublishWizard\CreateWorkflowStepWebController;
use App\Http\Controllers\Web\PublishWizard\DatasetDetailsStepWebController;
use App\Http\Controllers\Web\PublishWizard\SelectProjectStepWebController;
use App\Http\Controllers\Web\PublishWizard\UploadFilesStepWebController;
use Illuminate\Support\Facades\Route;

Route::get('/publish/wizard/select_project', SelectProjectStepWebController::class)
     ->name('publish.wizard.select_project');

Route::get('/publish/wizard/upload_files', UploadFilesStepWebController::class)
     ->name('publish.wizard.upload_files');

Route::get('/publish/wizard/dataset_details', DatasetDetailsStepWebController::class)
     ->name('publish.wizard.dataset_details');

Route::get('/publish/wizard/create_workflow', CreateWorkflowStepWebController::class)
     ->name('publish.wizard.create_workflow');

