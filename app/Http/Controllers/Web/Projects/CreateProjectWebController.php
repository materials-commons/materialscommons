<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;

class CreateProjectWebController extends Controller
{
    public function __invoke()
    {
        return view('app.projects.create');
    }
}
