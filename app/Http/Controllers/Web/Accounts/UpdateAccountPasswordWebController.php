<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UpdateAccountPasswordRequest;
use Illuminate\Support\Facades\Hash;

class UpdateAccountPasswordWebController extends Controller
{
    public function __invoke(UpdateAccountPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        abort_unless(Hash::check($validated['password'], $user->password), 400, 'Invalid password');
        abort_unless($validated['new_password'] === $validated['new_password2'], 400, "Passwords don't match");
        $user->update(['password' => Hash::make($validated['new_password'])]);
        flash('Password updated')->success();
        return redirect(route('accounts.show'));
    }
}
