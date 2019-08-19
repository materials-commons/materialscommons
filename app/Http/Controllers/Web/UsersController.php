<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    function getUsers()
    {
        return Laratables::recordsOf(User::class);
    }
}
