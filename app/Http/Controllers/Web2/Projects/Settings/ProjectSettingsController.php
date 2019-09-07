<?php

namespace App\Http\Controllers\Web2\Projects\Settings;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('app.projects.settings.index', ['project' => $project]);
    }
}
