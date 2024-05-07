<?php

namespace App\Http\Controllers\Web\Projects\Globus\NG2;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\OpenGlobus2TransferAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use function auth;
use function redirect;
use function route;

class StartGlobus2TransferWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.uploads.edit_account', [$project]));
        }

        // There isn't an open request so create a new one
        $openGlobusRequestAction = new OpenGlobus2TransferAction(GlobusApi::createGlobusApi());
        $globusTransfer = $openGlobusRequestAction->execute($project->id, $user);
        return redirect(route('projects.globus2.show-started', [$project, $globusTransfer]));
    }
}
