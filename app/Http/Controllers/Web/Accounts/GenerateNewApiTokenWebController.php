<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenerateNewApiTokenWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $token = Str::random(60);
        $user->update(['api_token' => $token]);
        return redirect(route('accounts.show'));
    }
}
