<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowMarkdownEditorWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('projects.markdown-editor', [
            'project' => $project,
        ]);
    }
}
