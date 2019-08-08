<?php

namespace App\Http\Controllers\api;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;
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
                           ->allowedFilters('name', Filter::exact('project_id'))
                           ->withCount(['samples', 'processes', 'files'])
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
        request()->validate([
            'name'        => [
                'required',
                function ($attribute, $value, $fail) {
                    $count = Project::where('name', $value)
                                    ->where('owner_id', auth()->id())
                                    ->count();
                    if ($count != 0) {
                        $fail('User already has a project named '.$value);
                    }
                },
            ],
            'description' => 'required',

            // 'default_project' => 'required|boolean',
        ]);

        $project = Project::create([
            'name'            => request('name'),
            'description'     => request('description'),

            // 'default_project' => request('default_project'),
            'default_project' => false,

            'is_active' => true,
            'owner_id'  => auth()->id(),
        ]);

        auth()->user()->projects()->attach($project);

        return $project;
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
