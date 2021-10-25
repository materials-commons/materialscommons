<?php

namespace App\Http\Controllers\Web\Trash;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class RestoreProjectFromTrashWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $project->update(['deleted_at' => null]);
        return redirect(route('trash'));
    }
}
