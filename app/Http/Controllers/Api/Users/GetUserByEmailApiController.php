<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class GetUserByEmailApiController extends Controller
{
    public function __invoke(Request $request, $email)
    {
        return new UserResource(User::firstWhere('email', $email));
    }
}
