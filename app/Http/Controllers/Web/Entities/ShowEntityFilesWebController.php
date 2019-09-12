<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowEntityFilesWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $experimentId = $request->route('experiment');
        $experiment   = null;

        $entityId = $request->route('entity');
        $entity   = Entity::with('files')->where('id', $entityId)->first();

        if ($experimentId !== null) {
            $experiment = Experiment::find($experimentId);
        }

        return view('app.entities.show', compact('project', 'entity', 'experiment'));
    }
}
