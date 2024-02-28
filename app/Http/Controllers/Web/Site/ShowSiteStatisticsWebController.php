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
use function app;
use function collect;

class ShowSiteStatisticsWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('app.site.statistics', [
            'usersChart'             => $this->createUsersChart(),
            'projectsChart'          => $this->createProjectsChart(),
            'publishedDatasetsChart' => $this->createPublishedDatasetsChart(),
            'entitiesChart'          => $this->createEntitiesChart(),
            'activitiesChart'        => $this->createActivitiesChart(),
            'attributesChart'        => $this->createAttributesChart(),
            //            'filesUploadedChart'     => $this->createFilesUploadedChart(),
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

    private function createPublishedDatasetsChart()
    {
        return $this->createChart(Carbon::parse(Dataset::min("created_at")),
            Dataset::class,
            "PublishedDatasetsCreatedChart",
            "Datasets Published",
            "Monthly Datasets Published");
    }

    private function createFilesUploadedChart()
    {
        return $this->createChart(Carbon::parse(File::min("created_at")),
            File::class,
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
        return $this->createChart(Carbon::parse(Attribute::min("created_at")),
            Attribute::class,
            "AttributesCreatedChart",
            "Attributes Created",
            "Monthly Attributes Created");
    }

    private function createChart($start, $cls, $name, $label, $text)
    {
        $end = Carbon::now();
        $period = CarbonPeriod::create($start, "1 month", $end);

        $projectsPerMonth = collect($period)->map(function ($date) use ($cls) {
            $endDate = $date->copy()->endOfMonth();

            return [
                "count" => $cls::where("created_at", "<=", $endDate)->count(),
                "month" => $endDate->format("Y-m-d")
            ];
        });

        $data = $projectsPerMonth->pluck("count")->toArray();
        $labels = $projectsPerMonth->pluck("month")->toArray();

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
                         ]
                     ]);

        return $chart;
    }
}
