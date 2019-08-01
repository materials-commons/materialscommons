<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home2', function () {
    return view('home2');
});

//Route::get('/getUsers', 'UsersController@getUsers')->name('get_users');

//Route::view('/public', 'public.index')->name('public.index');

Route::get('/public', 'PublicDataController@index')->name('public.index');
Route::get('/getAllPublishedDatasets', 'PublicDataController@getAllPublishedDatasets')->name('get_all_published_datasets');

Route::prefix('public')->group(function () {
    Route::name('public.')->group(function () {
        Route::get('/new', 'PublicDataNewController@index')->name('new.index');
        Route::get('/projects', 'PublicDataProjectsController@index')->name('projects.index');
        Route::get('/datasets', 'PublicDataDatasetsController@index')->name('datasets.index');
        Route::get('/datasets/{dataset}', 'PublicDataDatasetsController@show')->name('datasets.show');
        Route::get('/authors', 'PublicDataAuthorsController@index')->name('authors.index');
        Route::get('/tags', 'PublicDataTagsController@index')->name('tags.index');
        Route::view('/community', 'public.community.index')->name('community.index');
    });
});

Route::middleware(['auth'])->prefix('app')->group(function () {
    Route::get('/getUsers', 'UsersController@getUsers')->name('get_users');
//    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::resource('/projects', 'ProjectsController');
    Route::name('projects.')->group(function () {
        Route::resource('/projects/{project}/experiments', 'ProjectExperimentsController');

        Route::get('/projects/{project}/experiments/{experiment}/workflow', 'ProjectExperimentTabsController@workflow')->name('experiments.workflow.index');
        Route::get('/projects/{project}/experiments/{experiment}/samples', 'ProjectExperimentTabsController@samples')->name('experiments.samples.index');
        Route::get('/projects/{project}/experiments/{experiment}/processes', 'ProjectExperimentTabsController@processes')->name('experiments.processes.index');

        Route::resource('/projects/{project}/samples', 'ProjectSamplesController');

        Route::get('/projects/{project}/getRootFolder', 'ProjectFoldersDatatableController@getRootFolder')->name('get_root_folder');
        Route::get('/projects/{project}/folder/{folder}/getFolder', 'ProjectFoldersDatatableController@getFolder')->name('get_folder');

        Route::resource('/projects/{project}/processes', 'ProjectProcessesController');

        Route::resource('/projects/{project}/files', 'ProjectFilesController');

        Route::resource('/projects/{project}/folders', 'ProjectFoldersController');

        Route::Post('/projects/{project}/upload', 'ProjectFileUploadController@store');

        Route::get('/projects/{project}/users', 'ProjectUsersController@index')->name('users.index');

        Route::get('/projects/{project}/settings', 'ProjectSettingsController@index')->name('settings.index');
    });

    Route::resource('/tasks', 'TasksController');
    Route::view('/settings', 'app.settings.index')->name('settings.index');
    Route::view('/users', 'app.users.index')->name('users.index');

    //    Route::resource('/labs', 'LabsController');
    //    Route::resource('/files', 'FileController');
    //    Route::view('/teams', 'app.teams.index')->name('teams.index');
    //    Route::resource('/teams', 'TeamController');
    //    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    //    Route::get('/users', 'UsersController@index')->name('users.index');
});
