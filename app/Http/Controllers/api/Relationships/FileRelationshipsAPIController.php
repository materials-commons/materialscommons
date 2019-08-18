<?php

namespace App\Http\Controllers\api\Relationships;

use App\Models\Activity;
use App\Models\EntityState;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileRelationshipsAPIController extends Controller
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
     * @param $projectId
     * @param  \App\File  $file
     * @param $entityStateId
     *
     * @return \App\File|null
     */
    public function addEntityState(Request $request, $projectId, File $file, $entityStateId)
    {
        $file->entityStates()->attach($entityStateId);
        return $file->fresh();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param $projectId
     * @param  \App\File  $file
     * @param $actionId
     *
     * @return \App\File|null
     */
    public function addAction(Request $request, $projectId, File $file, $actionId)
    {
        $file->activities()->attach($actionId);
        return $file->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\File  $file
     * @param  \App\EntityState  $entity_state
     *
     * @return void
     */
    public function deleteEntityState(Request $request, Project $project, File $file, EntityState $entity_state)
    {
        //
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\File  $file
     * @param  \App\Action  $action
     */
    public function deleteAction(Request $request, Project $project, File $file, Activity $action) {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, File $file)
    {
        //
    }
}
