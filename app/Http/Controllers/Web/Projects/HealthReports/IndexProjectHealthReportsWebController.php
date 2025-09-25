<?php

namespace App\Http\Controllers\Web\Projects\HealthReports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexProjectHealthReportsWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $files = Storage::disk('mcfs')->allFiles("__health-reports/{$project->id}");
        $reportDates = collect($files)->map(fn($file) => basename($file, '.json'));
        return view('app.projects.health-reports.index', compact('project', 'reportDates'));
    }
}
