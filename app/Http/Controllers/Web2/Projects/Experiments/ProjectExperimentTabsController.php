<?php

namespace App\Http\Controllers\Web2\Projects\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class ProjectExperimentTabsController extends Controller
{
    /**
     * @param  Project  $project
     * @param  Experiment  $experiment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function workflow(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.workflow.index', compact('project', 'experiment'));
    }

    /**
     * @param  Project  $project
     * @param  Experiment  $experiment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function entities(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.samples.index', compact('project', 'experiment'));
    }

    /**
     * @param  Project  $project
     * @param  Experiment  $experiment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.processes.index', compact('project', 'experiment'));
    }
}
