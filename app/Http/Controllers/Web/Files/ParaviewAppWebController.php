<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class ParaviewAppWebController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Project $project, File $file)
    {
        return view('app.files.paraview_iframe');
    }
}
