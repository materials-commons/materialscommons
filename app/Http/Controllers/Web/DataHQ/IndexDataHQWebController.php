<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\DTO\DataHQ\Explorer;
use App\DTO\DataHQ\Subview;
use App\DTO\DataHQ\View;
use App\Http\Controllers\Controller;
use App\Models\DatahqInstance;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexDataHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $context = $request->input('context', 'project');
        $explorer = $request->input('explorer', 'overview');
        $view = $request->input('view', 'samples');
        $subview = $request->input('subview', '');
        $instance = DatahqInstance::getOrCreateInstanceForProject($project, auth()->user());
        if ($explorer === 'samples') {
            if (is_null($instance->samples_explorer_state)) {
                $instance->update([
                    'samples_explorer_state' => $this->createDefaultSamplesExplorerState(),
                    'current_explorer'       => 'samples',
                ]);
            }
        }
        return view('app.projects.datahq.index', [
            'project'    => $project,
            'experiment' => $instance->experiment,
            'context'    => $context,
            'explorer'   => $explorer,
            'view'       => $view,
            'subview'    => $subview,
            'instance'   => $instance,
        ]);
    }

    private function createDefaultSamplesExplorerState(): Explorer
    {
        return new Explorer("samples", "All Samples", collect([
            new View("All Samples", "", "", "Samples", collect([
                new Subview("Samples", "", null, null),
            ])),
        ]));
    }

    private function getDatahqInstance(Project $project, $context)
    {
        if ($context === 'project') {
            return DatahqInstance::getOrCreateInstanceForProject($project, auth()->user());
        }

        if (Str::startsWith($context, 'e-')) {
            $experimentId = Str::after($context, 'e-');
        }

        // Invalid context, so we will use project
        return DatahqInstance::getOrCreateInstanceForProject($project, auth()->user());
    }
}
