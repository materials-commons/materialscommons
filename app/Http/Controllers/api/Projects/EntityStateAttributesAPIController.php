<?php

namespace App\Http\Controllers\api\Projects;

use App\EntityState;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EntityStateAttributesAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, EntityState $entityState)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, EntityState $entityState)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, EntityState $entityState)
    {
        //
    }
}
