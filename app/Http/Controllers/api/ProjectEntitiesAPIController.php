<?php

namespace App\Http\Controllers\api;

use App\EntityState;
use App\Project;
use App\Entity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectEntitiesAPIController extends Controller
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
        $query = $project->entities()->getQuery();

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
            'name'        => 'required|string',
            'description' => 'string',
        ]);

        $entity = Entity::create([
            'name'        => request('name'),
            'description' => request('description'),
            'owner_id'    => auth()->id(),
            'project_id'  => $projectId,
        ]);

        $entityState = new EntityState(['current' => true]);
        $entity->entityStates()->save($entityState);

        return $entity->fresh();
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
        return Entity::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $entityId)
    {
        $attrs = request()->validate([
            'name'        => 'string',
            'description' => 'string',
        ]);

        return tap(Entity::findOrFail($entityId))->update($attrs)->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity  $entity
     *
     * @return void
     * @throws \Exception
     */
    public function destroy(Entity $entity)
    {
        $entity->delete();
    }
}
