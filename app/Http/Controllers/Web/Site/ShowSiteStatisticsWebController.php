<?php

namespace App\Http\Controllers\Web\Site;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function app;
use function collect;
use function log;

class ShowSiteStatisticsWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('app.site.statistics', [
            'usersChart'         => $this->createUsersChart(),
            'projectsChart'      => $this->createProjectsChart(),
            'datasetsChart'      => $this->createDatasetsChart(),
            'entitiesChart'      => $this->createEntitiesChart(),
            'activitiesChart'    => $this->createActivitiesChart(),
            'attributesChart'    => $this->createAttributesChart(),
            'filesUploadedChart' => $this->createFilesUploadedChart(),
        ]);
    }

    private function createUsersChart()
    {
        return $this->createChart(Carbon::parse(User::min("created_at")),
            User::class,
            "UserRegistrationsChart",
            "User Registrations",
            'Monthly User Registrations');
    }

    private function createProjectsChart()
    {
        return $this->createChart(Carbon::parse(Project::min("created_at")),
            Project::class,
            "ProjectsCreatedChart",
            "Projects Created",
            'Monthly Projects Created');
    }

    private function createDatasetsChart()
    {
        return $this->createChart(Carbon::parse(Dataset::min("created_at")),
            Dataset::class,
            "DatasetsCreatedChart",
            "Datasets Created",
            "Monthly Datasets Created");
    }

    private function createFilesUploadedChart()
    {
        $start = Carbon::parse(File::min("created_at"));
        $end = Carbon::now();
        $selectRaw = "YEAR(created_at) as year, MONTH(created_at) as month, COUNT(id) as aggregate";
        $q = DB::table("files")
               ->selectRaw($selectRaw)
               ->where("created_at", ">=", $start)
               ->where("created_at", "<=", $end)
               ->groupByRaw("YEAR(created_at), MONTH(created_at)");
//        $selectRaw = "created_ym as ym, COUNT(id) as aggregate";
//        $q = DB::table('files')
//               ->selectRaw($selectRaw)
//               ->whereBetween('created_at', [$start, $end])
//               ->groupBy('created_ym');
        return $this->createChartFromQuery($start,
            $q,
            "FilesUploadedChart",
            "Files Uploaded",
            "Monthly Files Uploaded");
    }

    private function createEntitiesChart()
    {
        return $this->createChart(Carbon::parse(Entity::min("created_at")),
            Entity::class,
            "EntitiesCreatedChart",
            "Entities Created",
            "Monthly Entities Created");
    }

    private function createActivitiesChart()
    {
        return $this->createChart(Carbon::parse(Activity::min("created_at")),
            Activity::class,
            "ActivitiesCreatedChart",
            "Activities Created",
            "Monthly Activities Created");
    }

    private function createAttributesChart()
    {
        $start = Carbon::parse(File::min("created_at"));
        $end = Carbon::now();
        $selectRaw = "YEAR(created_at) as year, MONTH(created_at) as month, COUNT(id) as aggregate";
        $q = DB::table("attributes")
               ->selectRaw($selectRaw)
               ->where("created_at", ">=", $start)
               ->where("created_at", "<=", $end)
               ->groupByRaw("YEAR(created_at), MONTH(created_at)");
        return $this->createChartFromQuery($start,
            $q,
            "AttributesCreatedChart",
            "Attributes Created",
            "Monthly Attributes Created");
    }

    private function createChartFromQuery($start, $q, $name, $label, $text)
    {
        $accumulator = 0;
        $dataPerMonth = $q->get()
                          ->map(function ($data) use (&$accumulator) {
                              $accumulator = $data->aggregate + $accumulator;
                              return [
                                  "count" => $accumulator,
                                  "month" => Carbon::createFromDate(
                                      $data->year,
                                      $data->month
                                  )->lastOfMonth()->format("Y-m-d")
                              ];
                          });
        $counts = $dataPerMonth->pluck("count")->toArray();
        $labels = $dataPerMonth->pluck("month")->toArray();

        $chart = app()->chartjs
            ->name($name)
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label"           => $label,
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $counts,
                ]
            ])
            ->options([
                'scales'  => [
                    'x' => [
                        'type' => 'time',
                        'time' => [
                            'unit' => 'month'
                        ],
                        'min'  => $start->format("Y-m-d"),
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text'    => $text,
                    ]
                ],
            ]);

        return $chart;
    }

    private function createChart($start, $cls, $name, $label, $text)
    {
        $end = Carbon::now();
        $period = CarbonPeriod::create($start, "1 month", $end);

        $dataPerMonth = collect($period)->map(function ($date) use ($cls) {
            $endDate = $date->copy()->endOfMonth();

            return [
                "count" => $cls::where("created_at", "<=", $endDate)->count(),
                "month" => $endDate->format("Y-m-d")
            ];
        });

        $data = $dataPerMonth->pluck("count")->toArray();
        $labels = $dataPerMonth->pluck("month")->toArray();

        $route = route("dashboard.projects.show");

        $options = <<<OPTS
{"scales":{"x":{"type":"time","time":{"unit":"month"},"min":"2020-04-06"}},"plugins":{"title":{"display":true,"text":"{$text}"}}},
OPTS;
//   "onClick":(e) => {
//       console.log(e.chart);
//       let points = e.chart.getElementsAtEventForMode(e, 'nearest', {intersect: true}, true);
//       if (points.length) {
//           const firstPoint = points[0];
//           const label = e.chart.data.labels[firstPoint.index];
//           const value = e.chart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
//           console.log("label =", label);
//           console.log("value =", value);
//           window.location.href="{$route}"
//       }
//   }
//}
//OPTS;


        $chart = app()
            ->chartjs->name($name)
                     ->type("line")
                     ->size(["width" => 400, "height" => 200])
                     ->labels($labels)
                     ->datasets([
                         [
                             "label"           => $label,
                             "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                             "borderColor"     => "rgba(38, 185, 154, 0.7)",
                             "data"            => $data
                         ]
                     ])
            ->optionsRaw($options);
//                     ->options([
//                         'scales'  => [
//                             'x' => [
//                                 'type' => 'time',
//                                 'time' => [
//                                     'unit' => 'month'
//                                 ],
//                                 'min'  => $start->format("Y-m-d"),
//                             ]
//                         ],
//                         'plugins' => [
//                             'title' => [
//                                 'display' => true,
//                                 'text'    => $text,
//                             ]
//                         ]
//                     ]);

        return $chart;
    }
}
