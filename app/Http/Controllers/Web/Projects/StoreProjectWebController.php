<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class StoreProjectWebController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        request()->validate([
            'name'        => 'required',
            'description' => 'required',

            // 'default_project' => 'required|boolean',
        ]);

        $project = Project::create([
            'name'            => request('name'),
            'description'     => request('description'),

            // 'default_project' => request('default_project'),
            'default_project' => false,

            'is_active' => true,
            'owner_id'  => auth()->id(),
        ]);

        auth()->user()->projects()->attach($project);

        return redirect(route('projects.index'));
    }

}
