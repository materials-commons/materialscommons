<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\DeleteProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;

class DeleteProjectWebController extends Controller
{
    /**
     * @param  \App\Actions\Projects\DeleteProjectAction  $deleteProjectAction
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function __invoke(DeleteProjectAction $deleteProjectAction, Project $project)
    {
        if (!$this->checkIfCanDelete($project)) {
            return redirect(route('dashboard.projects.show'));
        }

        $deleteProjectAction($project);

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

        if ($project->file_count > 0) {
            flash("You cannot delete a project that has files")->error();
            return false;
        }

        return true;
    }
}
