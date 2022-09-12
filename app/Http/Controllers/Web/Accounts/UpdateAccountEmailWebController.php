<?php

namespace App\Http\Controllers\Web\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UpdateAccountEmailRequest;
use App\Models\User;

class UpdateAccountEmailWebController extends Controller
{
    public function __invoke(UpdateAccountEmailRequest $request)
    {
        $validated = $request->validated();
        $email = $validated['new_email'];
        $email2 = $validated['new_email2'];
        $user = auth()->user();

        if ($email != $email2) {
            flash("Unable to update email - Emails don't match")->error();
            return redirect(route('accounts.show'));
        }

        $userWithEmail = User::where('email', $email)->first();
        if (!is_null($userWithEmail)) {
            flash("Unable to update email - Email address '{$email}' is already in use")->error();
            return redirect(route('accounts.show'));
        }

        $user->update(['email' => $email]);
        flash("Email updated")->success();
        return redirect(route('accounts.show'));
    }
}
