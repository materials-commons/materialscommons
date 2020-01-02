<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;

class IndexProjectsWebController extends Controller
{
    /**
     * List users projects
     */
    public function __invoke()
    {
        $projects = auth()->user()->projects()->get();

        return view('app.projects.index', compact('projects'));
    }
}
