<?php

namespace App\Http\Controllers\api;

use App\Project;
use App\Sample;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectSamplesAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Project  $project
     *
     * @return void
     */
    public function index(Project $project)
    {
        $query = $project->samples()->getQuery();

        return QueryBuilder::for($query)
                           ->allowedFilters('name')
                           ->jsonPaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $projectId Project that sample belongs to
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        request()->validate([
            'name'        => 'required',
            'description' => 'required',
        ]);

        $sample = Sample::create([
            'name'        => request('name'),
            'description' => request('description'),
            'owner_id'    => auth()->id(),
            'project_id'  => $projectId,
        ]);

        return $sample;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
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
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
