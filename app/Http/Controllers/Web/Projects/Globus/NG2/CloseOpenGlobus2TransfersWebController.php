<?php

namespace App\Http\Controllers\Web\Projects\Globus\NG2;

use App\Actions\Globus\CloseGlobus2TransferAction;
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

        $closeGlobus2RequestAction = new CloseGlobus2TransferAction(GlobusApi::createGlobusApi());

        GlobusTransfer::with('transferRequest')
                      ->where('project_id', $project->id)
                      ->where('owner_id', $user->id)
                      ->where('state', 'open')
                      ->get()
                      ->each(function (GlobusTransfer $globusTransfer) use ($closeGlobus2RequestAction) {
                          $closeGlobus2RequestAction->execute($globusTransfer);
                      });
        flash("Marked globus transfers as finished.")->success();
        return redirect()->back();
    }
}
