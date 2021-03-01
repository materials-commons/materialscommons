<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\OpenGlobusTransferAction;
use App\Http\Controllers\Controller;
use App\Models\GlobusTransfer;
use App\Models\Project;

class StartGlobusTransferWebController extends Controller
{

    public function __invoke(Project $project)
    {
        $user = auth()->user();
        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.uploads.edit_account', [$project]));
        }

        $globusTransfer = GlobusTransfer::where('project_id', $project->id)
                                        ->where('owner_id', $user->id)
                                        ->where('state', 'open')
                                        ->first();
        if (!is_null($globusTransfer)) {
            // User already has an open request so use it
            return view('app.projects.globus.show', compact('globusTransfer', 'project'));
        }

        // There isn't an open request so create a new one
        $openGlobusRequestAction = new OpenGlobusTransferAction(GlobusApi::createGlobusApi());
        $transferRequest = $openGlobusRequestAction->execute($project->id, $user);
        return redirect(route('projects.globus.show-started', [$project, $transferRequest->globusTransfer]));
    }
}
