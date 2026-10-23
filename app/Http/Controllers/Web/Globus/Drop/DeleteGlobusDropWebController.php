<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\GlobusDrop;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteGlobusDropWebController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, GlobusDrop $drop)
    {
        $globusApi = GlobusApi::createGlobusApi();

        if ($drop->project_id !== $project->id) {
            flash()->error("You do not have permission to delete this drop.");
            return redirect()->route('projects.globus.drops.index', [$project]);
        }

        try {
            $globusApi->deleteACLsOnPath($drop->globus_endpoint_id, $drop->globus_path);
        } catch (\Exception $e) {
            Log::error("Unable to delete all acls on globus upload path: ".$drop->globus_path);
        }

        try {
            Storage::disk('mcfs')->deleteDirectory("__globus_uploads/drops/{$drop->id}");
        } catch (\Exception $e) {
            Log::error("Unable to delete download dir __globus_uploads/{$drop->id}");
        }

        $drop->delete();
        flash()->success("Drop deleted successfully.");
        return redirect()->route('projects.globus.drops.index', [$project]);
    }
}
