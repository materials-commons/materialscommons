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
use App\Support\CacheKeys;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_USERS_CHART,
            "UserRegistrationsChart",
            "User Registrations",
            'Monthly User Registrations'
        );
    }

    private function createProjectsChart()
    {
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_PROJECTS_CHART,
            "ProjectsCreatedChart",
            "Projects Created",
            'Monthly Projects Created'
        );
    }

    private function createDatasetsChart()
    {
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_DATASETS_CHART,
            "DatasetsCreatedChart",
            "Datasets Created",
            "Monthly Datasets Created"
        );
    }

    private function createFilesUploadedChart()
    {
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_FILES_UPLOADED_CHART,
            "FilesUploadedChart",
            "Files Uploaded",
            "Monthly Files Uploaded"
        );
    }

    private function createEntitiesChart()
    {
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_ENTITIES_CHART,
            "EntitiesCreatedChart",
            "Entities Created",
            "Monthly Entities Created"
        );
    }

    private function createActivitiesChart()
    {
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_ACTIVITIES_CHART,
            "ActivitiesCreatedChart",
            "Activities Created",
            "Monthly Activities Created"
        );
    }

    private function createAttributesChart()
    {
        return $this->createCachedChart(
            CacheKeys::SITE_STATISTICS_ATTRIBUTES_CHART,
            "AttributesCreatedChart",
            "Attributes Created",
            "Monthly Attributes Created"
        );
    }

    private function createCachedChart(string $cacheKey, string $name, string $label, string $text)
    {
        $chartData = Cache::get($cacheKey, [
            'start'  => Carbon::now()->startOfMonth()->format("Y-m-d"),
            'labels' => [],
            'counts' => [],
        ]);

        return $this->createChartFromCachedData(
            Carbon::parse($chartData['start']),
            $chartData['labels'],
            $chartData['counts'],
            $name,
            $label,
            $text
        );
    }

    private function createChartFromCachedData($start, array $labels, array $counts, $name, $label, $text)
    {
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

}
