<?php

namespace App\Http\Controllers\api;

use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = auth()->user()->projects()->getQuery();

        return QueryBuilder::for($query)
                           ->allowedFilters('name', AllowedFilter::exact('project_id'))
                           ->withCount(['activities', 'entities', 'files'])
                           ->jsonPaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attrs = request()->validate([
            'name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $count = Project::where('name', $value)
                                    ->where('owner_id', auth()->id())
                                    ->count();
                    if ($count != 0) {
                        $fail('User already has a project named '.$value);
                    }
                },
            ],

            'description' => 'string',
            'is_active'   => 'boolean',
        ]);

        $attrs['owner_id'] = auth()->id();

        $project = Project::create($attrs);

        File::create([
            'project_id'             => $project->id,
            'name'                   => '/',
            'path'                   => '/',
            'mime_type'              => 'directory',
            'media_type_description' => 'directory',
            'owner_id'               => auth()->id(),
        ]);

        auth()->user()->projects()->attach($project);

        return $project->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param $projectId
     *
     * @return \App\Project
     */
    public function show($projectId)
    {
        return Project::withCount(['actions', 'entities', 'files'])
                      ->findOrFail($projectId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     *
     * @return bool
     */
    public function update(Request $request, $projectId)
    {
        $attrs = request()->validate([
            'name'        => 'string',
            'description' => 'string',
            'is_active'   => 'boolean',
        ]);

        return tap(Project::findOrFail($projectId))->update($attrs)->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     *
     * @return void
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $project->delete();
    }
}
