<?php

use App\Http\Controllers\Web\PublishWizard\ChooseSimpleOrProjectBasedStepWebController;
use App\Http\Controllers\Web\PublishWizard\CreateDefaultProjectStepWebController;
use App\Http\Controllers\Web\PublishWizard\CreateWorkflowStepWebController;
use App\Http\Controllers\Web\PublishWizard\DatasetDetailsStepWebController;
use App\Http\Controllers\Web\PublishWizard\ReviewDatasetAndPublishStepWebController;
use App\Http\Controllers\Web\PublishWizard\SelectProjectStepWebController;
use App\Http\Controllers\Web\PublishWizard\UploadFilesStepWebController;
use Illuminate\Support\Facades\Route;

Route::get('/publish/wizard/select_project', SelectProjectStepWebController::class)
     ->name('publish.wizard.select_project');

Route::get('/publish/wizard/{project}/upload_files', UploadFilesStepWebController::class)
     ->name('publish.wizard.upload_files');

Route::get('/publish/wizard/{project}/dataset_details', DatasetDetailsStepWebController::class)
     ->name('publish.wizard.dataset_details');

Route::get('/publish/wizard/{project}/create_workflow', CreateWorkflowStepWebController::class)
     ->name('publish.wizard.create_workflow');

Route::get('/publish/wizard/choose_path', ChooseSimpleOrProjectBasedStepWebController::class)
     ->name('publish.wizard.choose_path');

Route::get('/publish/wizard/create_default_project', CreateDefaultProjectStepWebController::class)
     ->name('publish.wizard.create_default_project');

Route::get('/publish/wizard/{project}/review/{dataset}', ReviewDatasetAndPublishStepWebController::class)
     ->name('publish.wizard.review_dataset');

