<?php

namespace App\Http\Controllers\Api\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\OpenGlobusTransferAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Globus\GlobusTransferResource;
use App\Models\Project;

class OpenGlobusTransferForProjectApiController extends Controller
{

    public function __invoke(Project $project)
    {
        $user = auth()->user();
        abort_unless(isset($user->globus_user), 403, "User hasn't configured a globus user");

        // There isn't an open request so create a new one
        $openGlobusRequestAction = new OpenGlobusTransferAction(GlobusApi::createGlobusApi());
        $globusTransfer = $openGlobusRequestAction->execute($project->id, $user);
        return new GlobusTransferResource($globusTransfer);
    }
}
