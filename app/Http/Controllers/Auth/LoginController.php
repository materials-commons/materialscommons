<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
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


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        $routeName = Route::getCurrentRoute()->getName();
        if ($routeName == 'login-for-upload') {
            return route('public.publish.wizard.choose_create_or_select_project');
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
        return redirect()->intended($this->redirectPath());
    }
}
