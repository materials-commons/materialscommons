<?php

namespace App\Http\Controllers\Api\Globus;

use App\Actions\Globus\CloseGlobusTransferAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusTransfer;
use App\Models\Project;

class CloseGlobusTransferForProjectApiController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        $closeGlobusRequestAction = new CloseGlobusTransferAction(GlobusApi::createGlobusApi());

        GlobusTransfer::where('project_id', $project->id)
                      ->where('owner_id', $user->id)
                      ->where('state', 'open')
                      ->get()
                      ->each(function (GlobusTransfer $globusTransfer) use ($closeGlobusRequestAction) {
                          $closeGlobusRequestAction->execute($globusTransfer);
                      });

        return response()->json('globus transfer closed', 204);
    }
}
