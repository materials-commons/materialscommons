<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UpdateAccountPasswordRequest;
use Illuminate\Support\Facades\Hash;
use function redirect;
use function route;

class UpdateAccountPasswordWebController extends Controller
{
    public function __invoke(UpdateAccountPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        if (!Hash::check($validated['password'], $user->password)) {
            flash("Invalid current password entered")->error();
            return redirect(route('accounts.show'));
        }

        if ($validated['new_password'] !== $validated['new_password2']) {
            flash("Passwords don't match")->error();
            return redirect(route('accounts.show'));
        }
        $user->update(['password' => Hash::make($validated['new_password'])]);
        flash('Password updated')->success();
        return redirect(route('accounts.show'));
    }
}
