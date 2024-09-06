<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use function ray;
use function session;

class ShowResultsWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        ray($request->session()->all());
        $dataFor = session("{$project->id}:de:data-for");
        ray("dataFor = {$dataFor}");
        $deState = session($dataFor);
        ray("deState =", $deState);
        $query = "s:'stress', s:'strain'";
        $chart = app()->chartjs
            ->name('StressStrain')
            ->type('line')
            ->size(["width" => 400, "height" => 200])
            ->labels(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'])
            ->datasets([
                [
                    "label"           => "Stress vs Strain",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => ["10", "20", "30", "40", "50", "60", "70", "80", "90", "100", "110", "120"]
                ]
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text'    => "Stress vs Strain",
                    ]
                ],
            ]);;
        return view('app.projects.datahq.index', [
            'experiments' => $experiments,
            'project'     => $project,
            'query'       => $query,
            'chart'       => $chart,
        ]);
    }
}
