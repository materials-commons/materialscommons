<?php /** @noinspection PhpIncludeInspection */

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

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Web2\HomeController;
use App\Http\Controllers\Web2\Projects\Activities\ProjectActionsController;
use App\Http\Controllers\Web2\Projects\Datasets\ProjectDatasetsController;
use App\Http\Controllers\Web2\Projects\Entities\ProjectEntitiesController;
use App\Http\Controllers\Web2\Projects\Experiments\ProjectExperimentTabsController;
use App\Http\Controllers\Web2\Projects\Folders\ProjectFilesController;
use App\Http\Controllers\Web2\Projects\Folders\ProjectFileUploadController;
use App\Http\Controllers\Web2\Projects\Folders\ProjectFoldersController;
use App\Http\Controllers\Web2\Projects\Folders\ProjectFoldersDatatableController;
use App\Http\Controllers\Web2\Projects\ProjectsDatatableController;
use App\Http\Controllers\Web2\Projects\Settings\ProjectSettingsController;
use App\Http\Controllers\Web2\Projects\Users\ProjectUsersController;
use App\Http\Controllers\Web2\Published\PublicDataAuthorsController;
use App\Http\Controllers\Web2\Published\PublicDataController;
use App\Http\Controllers\Web2\Published\PublicDataDatasetsController;
use App\Http\Controllers\Web2\Published\PublicDataNewController;
use App\Http\Controllers\Web2\Published\PublicDataProjectsController;
use App\Http\Controllers\Web2\Published\PublicDataTagsController;
use App\Http\Controllers\Web2\TasksController;
use App\Http\Controllers\Web2\UsersController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home2', function () {
    return view('home2');
});

//Route::get('/getUsers', 'UsersController@getUsers')->name('get_users');

//Route::view('/public', 'public.index')->name('public.index');

Route::get('/public', [PublicDataController::class, 'index'])->name('public.index');
Route::get('/getAllPublishedDatasets', [PublicDataController::class, 'getAllPublishedDatasets'])->name('get_all_published_datasets');

Route::prefix('public')->group(function () {
    Route::name('public.')->group(function () {
        Route::get('/new', [PublicDataNewController::class, 'index'])->name('new.index');
        Route::get('/projects', [PublicDataProjectsController::class, 'index'])->name('projects.index');
        Route::get('/datasets', [PublicDataDatasetsController::class, 'index'])->name('datasets.index');
        Route::get('/datasets/{dataset}', [PublicDataDatasetsController::class, 'show'])->name('datasets.show');
        Route::get('/authors', [PublicDataAuthorsController::class, 'index'])->name('authors.index');
        Route::get('/tags', [PublicDataTagsController::class, 'index'])->name('tags.index');
        Route::view('/community', 'public.community.index')->name('community.index');

        require base_path('routes/web_routes/published/datasets/datasets_web.php');
    });
});

Route::middleware(['auth'])->prefix('app')->group(function () {
    Route::get('/getUsers', [UsersController::class, 'getUsers'])->name('get_users');
    require base_path('routes/web_routes/projects/projects_web.php');
    require base_path('routes/web_routes/experiments/experiments_web.php');

    //    Route::resource('/projects', ProjectsController::class);
    Route::get('/projects/{project}/getProjectExperiments',
        [ProjectsDatatableController::class, 'getProjectExperiments'])->name('get_project_experiments');
    Route::name('projects.')->group(function () {
//        Route::resource('/projects/{project}/experiments', ProjectExperimentsController::class);

        Route::get('/projects/{project}/experiments/{experiment}/workflow', [ProjectExperimentTabsController::class, 'workflow'])->name('experiments.workflow.index');
        Route::get('/projects/{project}/experiments/{experiment}/entities', [ProjectExperimentTabsController::class, 'entities'])
             ->name('experiments.entities.index');
        Route::get('/projects/{project}/experiments/{experiment}/activities', [ProjectExperimentTabsController::class, 'activities'])
             ->name('experiments.activities.index');

        Route::resource('/projects/{project}/entities', ProjectEntitiesController::class);

        Route::get('/projects/{project}/getRootFolder', [ProjectFoldersDatatableController::class, 'getRootFolder'])->name('get_root_folder');
        Route::get('/projects/{project}/folder/{folder}/getFolder', [ProjectFoldersDatatableController::class, 'getFolder'])->name('get_folder');

        Route::resource('/projects/{project}/actions', ProjectActionsController::class);

        Route::resource('/projects/{project}/files', ProjectFilesController::class);

        Route::resource('/projects/{project}/publish', ProjectDatasetsController::class);

        Route::resource('/projects/{project}/folders', ProjectFoldersController::class);

        Route::Post('/projects/{project}/upload', [ProjectFileUploadController::class, 'store']);

        Route::get('/projects/{project}/users', [ProjectUsersController::class, 'index'])->name('users.index');

        Route::get('/projects/{project}/settings', [ProjectSettingsController::class, 'index'])->name('settings.index');
    });

    Route::resource('/tasks', TasksController::class);
    Route::view('/settings', 'app.settings.index')->name('settings.index');
    Route::view('/users', 'app.users.index')->name('users.index');

    //    Route::resource('/labs', 'LabsController');
    //    Route::resource('/files', 'FileController');
    //    Route::view('/teams', 'app.teams.index')->name('teams.index');
    //    Route::resource('/teams', 'TeamController');
    //    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    //    Route::get('/users', 'UsersController@index')->name('users.index');
});
