<?php

namespace App\Http\Controllers;

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
