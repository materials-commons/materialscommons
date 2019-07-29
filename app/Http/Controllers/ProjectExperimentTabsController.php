<?php

namespace App\Http\Controllers;

use App\Experiment;
use App\Project;
use Illuminate\Http\Request;

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
    public function samples(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.samples.index', compact('project', 'experiment'));
    }

    /**
     * @param  Project  $project
     * @param  Experiment  $experiment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processes(Project $project, Experiment $experiment)
    {
        return view('app.projects.experiments.processes.index', compact('project', 'experiment'));
    }
}
