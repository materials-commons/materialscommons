<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

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
