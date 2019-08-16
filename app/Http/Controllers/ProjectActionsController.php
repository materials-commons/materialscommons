<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectActionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('app.projects.processes.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Action  $process
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Action $process)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Action  $process
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $process)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Action  $process
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Action $process)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Action  $process
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $process)
    {
        //
    }
}
