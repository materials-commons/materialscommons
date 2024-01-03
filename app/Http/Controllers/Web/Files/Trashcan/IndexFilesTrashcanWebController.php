<?php

namespace App\Http\Controllers\Web\Files\Trashcan;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IndexFilesTrashcanWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $now = Carbon::now();
        return view('app.projects.trashcan.index', [
            'trash'         => File::getTrashForProject($project->id),
            'project'       => $project,
            'now'           => $now,
            'expiresInDays' => config('trash.expires_in_days'),
        ]);
    }
}
