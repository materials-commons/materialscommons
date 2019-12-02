<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UpdateAccountGlobusUserRequest;

class UpdateAccountGlobusUserWebController extends Controller
{
    public function __invoke(UpdateAccountGlobusUserRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $user->update(['globus_user' => $validated['globus_user']]);
        return redirect(route('accounts.show'));
    }
}
