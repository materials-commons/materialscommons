<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()->get();
        return view('app.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        return view('app.projects.create');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',

            // 'default_project' => 'required|boolean',
        ]);

        $project = Project::create([
            'name' => request('name'),
            'description' => request('description'),

            // 'default_project' => request('default_project'),
            'default_project' => false,

            'is_active' => true,
            'owner_id' => auth()->id(),
        ]);

        auth()->user()->projects()->attach($project);

        return redirect(route('projects.index'));
    }

    public function show(Project $project)
    {
        return view('app.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('app.projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $attrs = request()->validate([
            'name' => 'string',
            'description' => 'string',
            'is_active' => 'boolean',
            'default_project' => 'boolean'
        ]);

        $project->update($attrs);
        return redirect(route('projects.index'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect(route('projects.index'));
    }
}
