<?php

namespace App\Http\Controllers\Web\Projects\Globus\NG2;

use App\Actions\Globus\CloseGlobusTransferAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusTransfer;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;
use function flash;
use function redirect;

class CloseOpenGlobus2TransfersWebController extends Controller
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
        flash("Marked globus transfers as finished.")->success();
        return redirect()->back();
    }
}
