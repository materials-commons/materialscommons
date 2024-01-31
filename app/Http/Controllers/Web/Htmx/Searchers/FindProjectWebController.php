<?php

namespace App\Http\Controllers\Web\Htmx\Searchers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;

class FindProjectWebController extends Controller
{
    public function __invoke(Request $request)
    {
        // For now only allow admin users to access
        if (!auth()->user()->is_admin) {
            return '';
        }
        $projectName = $request->get('project_name');
        if (blank($projectName)) {
            return '';
        }

        $projects = Project::with('owner')
                           ->where('name', 'like', "%{$projectName}%")
                           ->get();
        return view('partials.htmx.searchers._find-project', [
            'projects' => $projects,
        ]);
    }
}
