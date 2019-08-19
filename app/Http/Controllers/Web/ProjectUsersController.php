<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

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
