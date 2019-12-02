<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UpdateAccountUserDetailsRequest;

class UpdateAccountUserDetailsWebController extends Controller
{
    public function __invoke(UpdateAccountUserDetailsRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $user->update($validated);
        return redirect(route('accounts.show'));
    }
}
