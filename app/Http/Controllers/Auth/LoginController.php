<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\GetRequestParameterId;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use function view;

class LoginController extends Controller
{
    use GetRequestParameterId;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use GetRequestParameterId;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $routeToUse = route('login');
        if (request()->routeIs('login-for-upload')) {
            $routeToUse = route('login-for-upload');
        } elseif (request()->routeIs('login-for-dataset-zipfile-download')) {
            $datasetId = $this->getParameterId('dataset');
            $routeToUse = route('login-for-dataset-zipfile-download', [$datasetId]);
        }

        return view('auth.login', [
            'routeToUse' => $routeToUse,
        ]);
    }


    public function redirectTo()
    {
        $routeName = Route::getCurrentRoute()->getName();
        if ($routeName == 'login-for-upload') {
            return route('public.publish.wizard.choose_create_or_select_project');
        }

        if ($routeName == 'login-for-dataset-zipfile-download') {
            $datasetId = $this->getParameterId('dataset');
            return route('public.datasets.overview.show', [$datasetId]);
        }

        if ($routeName == 'login-for-dataset-globus-download') {
            $datasetId = $this->getParameterId('dataset');
            return route('public.datasets.overview.show', [$datasetId]);
        }

        return route('dashboard');
    }

    public function authenticated(Request $request, $user)
    {
        if (is_null($user->email_verified_at)) {
            auth()->logout();
            flash("You need to confirm your account. Please check your email.")->error();
            return back()->with('warning',
                'You need to confirm your account. We have sent you an activation code, please check your email.');
        }

        $user->clearOlderRecentlyAccessedProjects();

        return redirect()->intended($this->redirectPath());
    }
}
