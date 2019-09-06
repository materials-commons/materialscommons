<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use Freshbitsweb\Laratables\Laratables;

class ProjectsDatatableController extends Controller
{
    public function getProjectExperiments($projectId)
    {
        return Laratables::recordsOf(Experiment::class, function ($query) use ($projectId) {
            return $query->where('project_id', $projectId);
        });
    }


}