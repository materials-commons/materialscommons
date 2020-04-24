<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ApiTokenRequest;
use App\Http\Resources\Users\UserApiTokenResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GetApiTokenApiController extends Controller
{
    public function __invoke(ApiTokenRequest $request)
    {
        $validated = $request->validated();
        $user = User::firstWhere('email', $validated['email']);
        abort_if(is_null($user), 400, "No such user");
        abort_unless(Hash::check($validated['password'], $user->password), 400, 'Invalid password');
        return new UserApiTokenResource($user);
    }
}
