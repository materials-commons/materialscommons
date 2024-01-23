<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\DeleteProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;

class DeleteProjectWebController extends Controller
{
    public function __invoke(DeleteProjectAction $deleteProjectAction, Project $project)
    {
        if (!$this->checkIfCanDelete($project)) {
            return redirect(route('dashboard.projects.show'));
        }

        $deleteProjectAction($project);

        $user = auth()->user();
        $user->removeFromActiveProjects($project);
        $user->removeFromRecentlyAccessedProjects($project);

        return redirect(route('dashboard.projects.show'));
    }

    private function checkIfCanDelete(Project $project)
    {
        if (auth()->id() !== $project->owner_id) {
            flash("Not project owner")->error();
            return false;
        }

        if ($project->publishedDatasets()->count() !== 0) {
            flash("Cannot delete projects with published datasets")->error();
            return false;
        }

//        if ($project->file_count > 0) {
//            flash("You cannot delete a project that has files")->error();
//            return false;
//        }

        return true;
    }
}
