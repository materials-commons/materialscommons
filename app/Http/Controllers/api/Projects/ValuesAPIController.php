<?php

namespace App\Http\Controllers\api\Projects;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Project;
use Illuminate\Http\Request;

class ValuesAPIController extends Controller
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
     * @param  \App\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, AttributeValue $value)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, AttributeValue $value)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @param  \App\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, AttributeValue $value)
    {
        //
    }
}
