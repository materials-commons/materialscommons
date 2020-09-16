<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Project;

class CreateDatasetWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $project->load('owner', 'team.members', 'team.admins');
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();
        $authorsAndAffiliations = $this->getAuthorsAndAffiliations($project);
        return view('app.projects.datasets.create', compact('project', 'communities', 'experiments',
            'authorsAndAffiliations'));
    }

    private function getAuthorsAndAffiliations(Project $project)
    {
        $users = $project->team->members()->get()->merge($project->team->admins()->get())->sortBy('name');
        $usersAndAffiliations = $users->map(function ($user) {
            if (isset($user->affiliations)) {
                return "{$user->name} ({$user->affiliations})";
            }

            return $user->name;
        });
        return implode("; ", $usersAndAffiliations->toArray());
    }
}
