<?php

namespace App\Http\Controllers\Web2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Freshbitsweb\Laratables\Laratables;

class UsersController extends Controller
{
    function getUsers()
    {
        return Laratables::recordsOf(User::class);
    }
}
