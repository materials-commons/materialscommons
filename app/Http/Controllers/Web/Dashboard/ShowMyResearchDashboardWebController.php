<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function auth;
use function is_null;
use function now;

class ShowMyResearchDashboardWebController extends Controller
{
    use UserProjects;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $projects = $this->getUserProjects(auth()->id());
        $activeProjects = $this->getActiveProjects(auth()->user(), $projects);
        $recentlyAccessedProjects = $this->getRecentlyAccessedProjects(auth()->user(), $projects);
        $archivedProjects = $this->getUserArchivedProjects(auth()->id());
        $deletedProjects = Project::getDeletedForUser(auth()->id());
        $datasets = $this->geUserDatasets(auth()->user(), $projects);
        $listedInDatasets = $this->getDatasetsUserIsListedIn(auth()->user(), $datasets);
        $communities = $this->getUserCommunities(auth()->user(), $datasets, $listedInDatasets);

        return view('app.dashboard.index', [
            'publishedDatasetsCount'   => $datasets->filter(fn($dataset) => $dataset->published_at !== null)->count(),
            'deletedCount'             => Project::getDeletedTrashCountForUser(auth()->id()),
            'projectsCount'            => $projects->count(),
            'archivedCount'            => $archivedProjects->count(),
            'projects'                 => $projects,
            'activeProjects'           => $activeProjects,
            'recentlyAccessedProjects' => $recentlyAccessedProjects,
            'archivedProjects'         => $archivedProjects,
            'deletedProjects'          => $deletedProjects,
            'datasets'                 => $datasets,
            'listedInDatasets'         => $listedInDatasets,
            'communities'              => $communities,
        ]);
    }

    private function geUserDatasets($user, $projects)
    {
        $projectIds = $projects->pluck('id');

        $ownedDatasets = Dataset::query()
                                ->with(['project', 'papers.owner'])
                                ->withCount(['views', 'downloads', 'comments'])
                                ->withCounts()
                                ->where(function ($query) use ($user, $projectIds) {
                                    $query->where('owner_id', $user->id)
                                          ->orWhereIn('project_id', $projectIds);
                                })
                                ->whereDoesntHave('tags', function ($q) {
                                    $q->where('tags.id', config('visus.import_tag_id'));
                                })
                                ->get();

//        $authoredDatasets = $user->datasets()
//                                 ->with(['project'])
//                                 ->withCount(['views', 'downloads', 'comments'])
//                                 ->withCounts()
//                                 ->get();

        return $ownedDatasets;
    }

    private function getUserCommunities(User $user, $datasets, $listedInDatasets)
    {
        $communityIdsFromDatasets = $this->getPublishedCommunityIdsForDatasets(
            $datasets->merge($listedInDatasets)->pluck('id')
        );

        return Community::query()
                        ->with([
                            'owner',
                            'links',
                            'files',
                            'publishedDatasets' => function ($query) {
                                $query->with(['owner', 'project', 'tags'])
                                      ->withCount(['views', 'downloads'])
                                      ->orderByDesc('published_at');
                            },
                        ])
                        ->where(function ($query) use ($user, $communityIdsFromDatasets) {
                            $query->where('owner_id', $user->id)
                                  ->when($communityIdsFromDatasets->isNotEmpty(),
                                      function ($query) use ($communityIdsFromDatasets) {
                                          $query->orWhereIn('id', $communityIdsFromDatasets->unique()->values());
                                      });
                        })
                        ->orderBy('name')
                        ->get();
    }

    private function getPublishedCommunityIdsForDatasets(Collection $datasetIds): Collection
    {
        $datasetIds = $datasetIds
            ->filter()
            ->unique()
            ->values();

        if ($datasetIds->isEmpty()) {
            return collect();
        }

        return DB::table('dataset2community')
                 ->join('communities', 'communities.id', '=', 'dataset2community.community_id')
                 ->whereIn('dataset2community.dataset_id', $datasetIds)
                 ->where('communities.public', true)
                 ->distinct()
                 ->pluck('dataset2community.community_id')
                 ->values();
    }

    private function getDatasetsUserIsListedIn(User $user, $datasets)
    {
        $existingDatasetIds = $datasets->pluck('id');

        return Dataset::query()
                      ->with(['owner', 'project', 'tags'])
                      ->withCount(['views', 'downloads'])
                      ->whereNotNull('published_at')
                      ->where('owner_id', '!=', $user->id)
                      ->when($existingDatasetIds->isNotEmpty(), function ($query) use ($existingDatasetIds) {
                          $query->whereNotIn('id', $existingDatasetIds);
                      })
                      ->whereRaw('ds_authors COLLATE utf8mb4_general_ci like ?', ['%"name":"'.$user->name.'"%'])
                      ->whereDoesntHave('tags', function ($q) {
                          $q->where('tags.id', config('visus.import_tag_id'));
                      })
                      ->orderByDesc('published_at')
                      ->get();
    }

    private function getActiveProjects(User $user, $projects)
    {
        if (!$user->hasActiveProjects()) {
            return collect();
        }

        return $projects->filter(function ($project) use ($user) {
            return $user->isActiveProject($project);
        })->sortByDesc(function ($project) use ($user) {
            $accessedOn = $user->projectRecentlyAccessedOn($project);

            if (is_null($accessedOn)) {
                return now()->getTimestamp();
            }

            return Carbon::parse($accessedOn)->getTimestamp();
        });
    }

    private function getRecentlyAccessedProjects(User $user, $projects)
    {
        if (!$user->hasRecentlyAccessedProjectsThatAreNotActive()) {
            return collect();
        }

        return $projects->filter(function ($project) use ($user) {
            if ($user->isActiveProject($project)) {
                return false;
            }

            if (is_null($user->projectRecentlyAccessedOn($project))) {
                return false;
            }

            return true;
        })->sortByDesc(function ($project) use ($user) {
            $accessedOn = $user->projectRecentlyAccessedOn($project);

            if (is_null($accessedOn)) {
                return now()->getTimestamp();
            }

            return Carbon::parse($accessedOn)->getTimestamp();
        });
    }
}
