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

Route::middleware(['auth'])->prefix('app')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::resource('/projects', 'ProjectsController');
    Route::name('projects.')->group(function() {
        Route::resource('/projects/{project}/experiments', 'ProjectExperimentsController');
    });
    Route::resource('/tasks', 'TasksController');

    Route::view('/teams', 'app.teams.index')->name('teams.index');
    Route::view('/files', 'app.files.index')->name('files.index');
    Route::view('/settings', 'app.settings.index')->name('settings.index');
    Route::view('/users', 'app.users.index')->name('users.index');

    //    Route::resource('/labs', 'LabsController');
    //    Route::resource('/files', 'FileController');
    //    Route::resource('/teams', 'TeamController');
    //    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    //    Route::get('/users', 'UsersController@index')->name('users.index');
});