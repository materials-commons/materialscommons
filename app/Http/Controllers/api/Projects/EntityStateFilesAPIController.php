<?php

namespace App\Http\Controllers\api\Projects;

use App\EntityState;
use App\File;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class EntityStateFilesAPIController extends Controller
{
    /**
     * List files for entity state.
     *
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     *
     * @return void
     */
    public function index(Project $project, EntityState $entityState)
    {
        $query = $entityState->files()->getQuery();

        return QueryBuilder::for($query)
                           ->allowedFilters('name')
                           ->jsonPaginate();
    }

    /**
     * Add a file to the entity state
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     *
     * @param $fileId
     *
     * @return \App\EntityState|null
     */
    public function store(Request $request, Project $project, EntityState $entityState, $fileId)
    {
        $entityState->attach($fileId);
        return $entityState->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     *
     * @param  \App\File  $file
     *
     * @return \App\File
     */
    public function show(Project $project, EntityState $entityState, File $file)
    {
        return $file;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\EntityState  $entityState
     *
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
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, EntityState $entityState)
    {
        //
    }
}
