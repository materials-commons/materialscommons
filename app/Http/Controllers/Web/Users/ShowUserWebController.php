<?php

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class ShowUserWebController extends Controller
{
    public function __invoke(User $user)
    {
        return view('app.users.show', compact('user'));
    }
}
