<?php

namespace App\Http\Controllers\Web\Shares;

use App\Actions\Etl\GetFileByPathAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function response;

class DownloadSharedProjectByPathFileWebController extends Controller
{
    public function __invoke(Request $request, GetFileByPathAction $getFileByPathAction, Project $project)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $filePath = $request->input('path');

        $file = $getFileByPathAction->execute($project->id, $filePath);

        if (is_null($file)) {
            abort(401);
        }

        return response()->download($file->mcfsPath(), $file->name);
    }
}
