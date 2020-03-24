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
use App\Http\Controllers\Web\Published\SearchPublishedDataWebController;
use App\Http\Controllers\Web\Welcome\AboutWebController;
use App\Http\Controllers\Web\Welcome\WelcomeWebController;
use App\Http\Controllers\Web2\HomeController;
use App\Http\Controllers\Web2\Projects\ProjectsDatatableController;
use App\Http\Controllers\Web2\Projects\Settings\ProjectSettingsController;
use App\Http\Controllers\Web2\Published\PublicDataController;
use App\Http\Controllers\Web2\Published\PublicDataNewController;
use App\Http\Controllers\Web2\Published\PublicDataProjectsController;
use App\Http\Controllers\Web2\TasksController;
use App\Http\Controllers\Web2\UsersController;
use App\Mail\AnnouncementMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', WelcomeWebController::class)->name('welcome');
Route::get('/about', AboutWebController::class)->name('about');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login-for-upload', [LoginController::class, 'showLoginForm'])->name('login-for-upload');
Route::post('login-for-upload', [LoginController::class, 'login'])->name('login-for-upload');

Route::get('register-for-upload', [RegisterController::class, 'showRegistrationForm'])->name('register-for-upload');
Route::post('register-for-upload', [RegisterController::class, 'register'])->name('register-for-upload');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//Route::get('')

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home2', function () {
    return view('home2');
});

//Route::get('/getUsers', 'UsersController@getUsers')->name('get_users');

//Route::view('/public', 'public.index')->name('public.index');

Route::get('preview-mc-email', function () {
    $user = App\Models\User::where('email', 'admin@admin.org')->first();
    Mail::bcc($user->email)->send(new AnnouncementMail($user));
    return new AnnouncementMail($user);
});

Route::get('/public', [PublicDataController::class, 'index'])->name('public.index');
Route::get('/getAllPublishedDatasets',
    [PublicDataController::class, 'getAllPublishedDatasets'])->name('get_all_published_datasets');

Route::prefix('public')->group(function () {
    Route::name('public.')->group(function () {
        Route::post('/search', SearchPublishedDataWebController::class)->name('search');
        Route::get('/new', [PublicDataNewController::class, 'index'])->name('new.index');
        Route::get('/projects', [PublicDataProjectsController::class, 'index'])->name('projects.index');
        Route::get('/datasets', [PublicDataController::class, 'index'])->name('datasets.index');
        //        Route::get('/datasets/{dataset}', [PublicDataDatasetsController::class, 'show'])->name('datasets.show');
//        Route::get('/tags', [PublicDataTagsController::class, 'index'])->name('tags.index');
//        Route::view('/community', 'public.community.index')->name('community.index');

        require base_path('routes/web_routes/published_datasets_web.php');
        require base_path('routes/web_routes/published_communities_web.php');
        require base_path('routes/web_routes/publish_dataset_wizard_web.php');
        require base_path('routes/web_routes/published_authors_web.php');
        require base_path('routes/web_routes/published_tags_web.php');
    });
});

//Route::get('/share/{file}', function() {
//
//})->name('share')->middleware('signed');

Route::middleware(['auth'])->prefix('app')->group(function () {
    require base_path('routes/web_routes/projects_web.php');
    require base_path('routes/web_routes/experiments_web.php');
    require base_path('routes/web_routes/entities_web.php');
    require base_path('routes/web_routes/activities_web.php');
    require base_path('routes/web_routes/workflows_web.php');
    require base_path('routes/web_routes/files_web.php');
    require base_path('routes/web_routes/folders_web.php');
    require base_path('routes/web_routes/datasets_web.php');
    require base_path('routes/web_routes/account_web.php');
    require base_path('routes/web_routes/communities_web.php');
    require base_path('routes/web_routes/dashboard_web.php');
    require base_path('routes/web_routes/users_web.php');

    Route::get('/getUsers', [UsersController::class, 'getUsers'])->name('get_users');

    Route::get('/projects/{project}/getProjectExperiments',
        [ProjectsDatatableController::class, 'getProjectExperiments'])->name('get_project_experiments');

    Route::name('projects.')->group(function () {


//        Route::resource('/projects/{project}/publish', ProjectDatasetsController::class);

//        Route::get('/projects/{project}/users', [ProjectUsersController::class, 'index'])->name('users.index');

        Route::get('/projects/{project}/settings', [ProjectSettingsController::class, 'index'])->name('settings.index');

        // Route::resource('/projects', ProjectsController::class);
        // Route::get('/projects/{project}/getRootFolder', [ProjectFoldersDatatableController::class, 'getRootFolder'])->name('get_root_folder');
        // Route::get('/projects/{project}/folder/{folder}/getFolder', [ProjectFoldersDatatableController::class, 'getFolder'])->name('get_folder');
        // Route::resource('/projects/{project}/actions', ProjectActionsController::class);
        // Route::Post('/projects/{project}/upload', [ProjectFileUploadController::class, 'store']);
        // Route::resource('/projects/{project}/folders', ProjectFoldersController::class);
        // Route::resource('/projects/{project}/files', ProjectFilesController::class);
    });

    Route::resource('/tasks', TasksController::class);
    Route::view('/settings', 'app.settings.index')->name('settings.index');
    Route::view('/users', 'app.users.index')->name('users.index');
});
