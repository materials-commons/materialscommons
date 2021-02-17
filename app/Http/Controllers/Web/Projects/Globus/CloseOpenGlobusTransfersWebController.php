<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\CloseGlobusRequestAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusRequest;
use App\Models\Project;

class CloseOpenGlobusTransfersWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        $closeGlobusRequestAction = new CloseGlobusRequestAction(GlobusApi::createGlobusApi());

        GlobusRequest::where('project_id', $project->id)
                     ->where('owner_id', $user->id)
                     ->where('state', 'new')
                     ->get()
                     ->each(function (GlobusRequest $globusRequest) use ($closeGlobusRequestAction) {
                         $closeGlobusRequestAction->execute($globusRequest);
                     });
        flash("Marked globus transfers as finished.")->success();
        return redirect()->back();
    }
}
