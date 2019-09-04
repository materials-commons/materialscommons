<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('app.projects.users.index', ['project' => $project]);
    }
}
