<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UpdateAccountGlobusUserRequest;
use App\Models\Project;

class UpdateGlobusAccountWebController extends Controller
{
    public function __invoke(UpdateAccountGlobusUserRequest $request, Project $project)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $user->update(['globus_user' => $validated['globus_user']]);
        flash("Globus user updated")->success();
        return redirect(route('projects.globus.downloads.create', [$project]));
    }
}
