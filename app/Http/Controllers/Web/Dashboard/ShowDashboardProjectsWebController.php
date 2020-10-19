<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class ShowDashboardProjectsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $projects = auth()->user()->projects()->with(['owner'])->withCount('entities')->get();
        return view('app.dashboard.index', [
            'projects'               => $projects,
            'projectsCount'          => $projects->count(),
            'publishedDatasetsCount' => Dataset::whereNotNull('published_at')
                                               ->where('owner_id', auth()->id())
                                               ->count(),
        ]);
    }
}
