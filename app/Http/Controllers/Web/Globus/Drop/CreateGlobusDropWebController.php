<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateGlobusDropWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.globus.drops.create', [
            'project' => $project,
        ]);
    }
}
