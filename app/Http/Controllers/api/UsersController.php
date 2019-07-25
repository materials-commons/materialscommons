<?php

namespace App\Http\Controllers\api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Freshbitsweb\Laratables\Laratables;

class UsersController extends Controller
{
    function getUsers()
    {
        return Laratables::recordsOf(User::class);
    }
}
