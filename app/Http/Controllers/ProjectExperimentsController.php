<?php

namespace App\Http\Controllers;

use App\Enums\ExperimentStatus;
use App\Experiment;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectExperimentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Project  $project
     * @return void
     */
    public function index(Project $project)
    {
        return view('app.projects.experiments.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return view('app.projects.experiments.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Project  $project
     * @return void
     */
    public function store(Project $project)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Experiment::create([
            'name' => request('name'),
            'description' => request('description'),
            'project_id' => $project->id,
            'owner_id' => auth()->id(),
            'status' => ExperimentStatus::InProgress,
        ]);

        return redirect(route('projects.experiments.index', ['project' => $project->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  Project  $project
     * @param  Experiment  $experiment
     * @return void
     */
    public function show(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.show', compact('project', 'experiment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
