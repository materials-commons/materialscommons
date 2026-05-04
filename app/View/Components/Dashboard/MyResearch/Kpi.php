<?php

namespace App\View\Components\Dashboard\MyResearch;

use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\View\View;

class Kpi extends Component
{
    public string $projectsCount;
    public string $datasetsCount;
    public string $viewsCount;
    public string $downloadsCount;
    public string $missingLicensesCount;
    public string $collaboratorsCount;

    public string $projectsSubtitle;
    public string $datasetsSubtitle;
    public string $viewsSubtitle;
    public string $downloadsSubtitle;
    public string $missingLicensesSubtitle;
    public string $collaboratorsSubtitle;

    private User $user;

    public function __construct()
    {
        $this->user = auth()->user();

        $projectIds = $this->projectIds();

        $activeProjectsCount = Project::whereIn('id', $projectIds)
                                      ->whereNull('deleted_at')
                                      ->whereNull('archived_at')
                                      ->count();

        $archivedProjectsCount = Project::whereIn('id', $projectIds)
                                        ->whereNull('deleted_at')
                                        ->whereNotNull('archived_at')
                                        ->count();

        $ownedDatasetsQuery = Dataset::where('owner_id', $this->user->id)
                                     ->whereDoesntHave('tags', function ($q) {
                                         $q->where('tags.id', config('visus.import_tag_id'));
                                     });

//        $publishedDatasetsCount = (clone $ownedDatasetsQuery)
//            ->whereNotNull('published_at')
//            ->whereDoesntHave('tags', function ($q) {
//                $q->where('tags.id', config('visus.import_tag_id'));
//            })
//            ->count();

        $draftDatasetsCount = (clone $ownedDatasetsQuery)
            ->whereNull('published_at')
            ->count();

        $publishedDatasets = (clone $ownedDatasetsQuery)
            ->whereNotNull('published_at')
            ->withCount(['views', 'downloads'])
            ->get();
        $publishedDatasetsCount = $publishedDatasets->count();

        $missingLicensesCount = (clone $ownedDatasetsQuery)
            ->where(function ($query) {
                $query->whereNull('license')
                      ->orWhere('license', '');
            })
            ->count();

        $collaboratorsCount = DB::table('project2user')
                                ->whereIn('project_id', $projectIds)
                                ->where('user_id', '<>', $this->user->id)
                                ->distinct('user_id')
                                ->count('user_id');

        $this->projectsCount = $this->formatNumber($activeProjectsCount + $archivedProjectsCount);
        $this->datasetsCount = $this->formatNumber($publishedDatasetsCount + $draftDatasetsCount);
        $this->viewsCount = $this->formatNumber((int) $publishedDatasets->sum('views_count'));
        $this->downloadsCount = $this->formatNumber((int) $publishedDatasets->sum('downloads_count'));
        $this->missingLicensesCount = $this->formatNumber($missingLicensesCount);
        $this->collaboratorsCount = $this->formatNumber($collaboratorsCount);

        $this->projectsSubtitle = "{$this->formatNumber($activeProjectsCount)} active / {$this->formatNumber($archivedProjectsCount)} archived";
        $this->datasetsSubtitle = "{$this->formatNumber($publishedDatasetsCount)} published / {$this->formatNumber($draftDatasetsCount)} draft";
        $this->viewsSubtitle = 'on published datasets';
        $this->downloadsSubtitle = 'on published datasets';
        $this->missingLicensesSubtitle = 'owned datasets';
        $this->collaboratorsSubtitle = 'across projects';
    }

    public function render(): View
    {
        return view('components.dashboard.my-research.kpi');
    }

    private function projectIds(): Collection
    {
        return $this->user
            ->projects()
            ->pluck('projects.id')
            ->unique()
            ->values();
    }

    private function formatNumber(int $value): string
    {
        return number_format($value);
    }
}
