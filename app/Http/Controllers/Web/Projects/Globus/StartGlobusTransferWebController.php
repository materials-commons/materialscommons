<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\OpenGlobusTransferAction;
use App\Http\Controllers\Controller;
use App\Models\Project;

class StartGlobusTransferWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.uploads.edit_account', [$project]));
        }

        // There isn't an open request so create a new one
        $openGlobusRequestAction = new OpenGlobusTransferAction(GlobusApi::createGlobusApi());
        $globusTransfer = $openGlobusRequestAction->execute($project->id, $user);
        return redirect(route('projects.globus.show-started', [$project, $globusTransfer]));
    }
}
