<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class GetUserByApiTokenApiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $apiToken)
    {
        return new UserResource(User::firstWhere('api_token', $apiToken));
    }
}
