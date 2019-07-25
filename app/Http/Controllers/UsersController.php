<?php

namespace App\Http\Controllers;

use App\User;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    function getUsers()
    {
        return Laratables::recordsOf(User::class);
    }
}
