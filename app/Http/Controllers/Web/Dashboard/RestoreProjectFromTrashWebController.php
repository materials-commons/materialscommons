<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Actions\Projects\RestoreProjectFromTrashAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestoreProjectFromTrashWebController extends Controller
{
    public function __invoke(Request $request, RestoreProjectFromTrashAction $restoreProjectFromTrashAction, Project $project)
    {
        $restoreProjectFromTrashAction->execute($project);
        return redirect(route('dashboard.projects.trash.index'));
    }
}
