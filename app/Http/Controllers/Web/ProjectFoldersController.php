<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Directory;
use App\Models\File;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectFoldersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Project  $project
     * @return void
     */
    public function index(Project $project)
    {
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        return view('app.projects.folders.index', compact('directory', 'project'));
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
     * @param $projectId
     * @param $folderId
     * @return void
     */
    public function show($projectId, $folderId)
    {
//        error_log("I am here", $projectId, $folderId);
        $dir = File::where('project_id', $projectId)->where('id', $folderId)->first();
        $project = Project::find($projectId);
//        error_log(print_r($dir));
        return view('app.projects.folders.show', ['project' => $project, 'directory' => $dir]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function edit(Directory $directory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Directory $directory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Directory  $directory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Directory $directory)
    {
        //
    }
}
