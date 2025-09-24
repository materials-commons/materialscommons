<?php

namespace App\Http\Controllers\Web\Projects\HealthReports;

use App\DTO\HealthReport;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowProjectHealthReportWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, string $date)
    {
        $healthReport = HealthReport::readFromStorage($date, $project);
        if (!$healthReport) {
            abort(404, "Health report not found for {$date}");
        }

        return view('app.projects.health-reports.show', [
            'project'      => $project,
            'healthReport' => $healthReport,
            'date'         => $date,
        ]);
    }
}
