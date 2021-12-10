<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\OpenGlobusTransferAction;
use App\Http\Controllers\Controller;
use App\Models\GlobusTransfer;
use App\Models\Project;
use Illuminate\Http\Request;

class RedirectToProjectGlobusSiteWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $globusTransfer = GlobusTransfer::where('project_id', $project->id)
                                        ->where('owner_id', auth()->id())
                                        ->where('state', 'open')
                                        ->first();
        if (is_null($globusTransfer)) {
            $user = auth()->user();
            if (!isset($user->globus_user)) {
                return redirect(route('projects.globus.uploads.edit_account', [$project]));
            }

            // There isn't an open request so create a new one
            $openGlobusRequestAction = new OpenGlobusTransferAction(GlobusApi::createGlobusApi());
            $globusTransfer = $openGlobusRequestAction->execute($project->id, $user);
        }

        return redirect()->away($globusTransfer->globus_url);
    }
}
