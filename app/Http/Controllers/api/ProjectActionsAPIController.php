<?php

namespace App\Http\Controllers\api;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectActionsAPIController extends Controller
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
        $query = $project->activities()->getQuery();

        return QueryBuilder::for($query)
                           ->allowedFilters('name')
                           ->jsonPaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param $projectId
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        request()->validate([
            'name'        => 'required|string',
            'description' => 'string',
        ]);

        $action = Activity::create([
            'name' => request('name'),
            'description' => request('description'),
            'owner_id' => auth()->id(),
            'project_id' => $projectId,
        ]);

        return $action->fresh();
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
        return Activity::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $actionId)
    {
        $attrs = request()->validate([
            'name'        => 'string',
            'description' => 'string',
        ]);

        return tap(Activity::findOrFail($actionId))->update($attrs)->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Action  $action
     *
     * @return void
     * @throws \Exception
     */
    public function destroy(Activity $action)
    {
        $action->delete();
    }
}
