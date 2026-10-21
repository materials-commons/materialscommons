<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexGlobusDropsWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $drops = $project->globusDrops()->get();
        return view('app.projects.globus.drops.index', compact('project', 'drops'));
    }
}
