<?php

namespace App\Http\Controllers\Web\Projects\Entities;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectEntitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Project  $project
     * @return void
     */
    public function index(Project $project)
    {
        return view('app.projects.samples.index', ['project' => $project]);
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
     * @param  \App\Entity  $sample
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Entity $sample)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity  $sample
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Entity $sample)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity  $sample
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entity $sample)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity  $sample
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entity $sample)
    {
        //
    }
}
