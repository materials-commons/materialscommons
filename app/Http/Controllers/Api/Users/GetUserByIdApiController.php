<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class GetUserByIdApiController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        return new UserResource($user);
    }
}
