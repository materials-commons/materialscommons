<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\OpenGlobusTransferAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TransferRequest;

class StartGlobusTransferWebController extends Controller
{

    public function __invoke(Project $project)
    {
        $user = auth()->user();
        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.uploads.edit_account', [$project]));
        }

        $globusRequest = TransferRequest::where('project_id', $project->id)
                                        ->where('owner_id', $user->id)
                                        ->where('state', 'new')
                                        ->first();
        if (!is_null($globusRequest)) {
            // User already has an open request so use it
            return view('app.projects.globus.show', compact('globusRequest', 'project'));
        }

        // There isn't an open request so create a new one
        $openGlobusRequestAction = new OpenGlobusTransferAction(GlobusApi::createGlobusApi());
        $globusRequest = $openGlobusRequestAction->execute($project->id, $user);
        return redirect(route('projects.globus.show-started', [$project, $globusRequest]));
    }
}
