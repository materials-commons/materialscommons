<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowAccountWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        return view('app.account.show', compact('user'));
    }
}
