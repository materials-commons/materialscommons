<?php

namespace App\Http\Controllers\Web\Attributes;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\Attributes\AttributeDetails;
use Illuminate\Http\Request;

class ShowEntityAttributeDetailsByNameWebController extends Controller
{
    use AttributeDetails;

    public function __invoke(Request $request, Project $project, $name)
    {
        if ($request->input('modal') == "true") {
            return view('app.dialogs._show-attribute-details-dialog', [
                'project'  => $project,
                'name'     => $name,
                'entities' => $this->getAttributeEntityNames($project->id, $name),
                'details'  => $this->getSampleAttributeDetails($project->id, $name),
            ]);
        }

        return view('partials.attributes._show-details', [
            'project'  => $project,
            'name'     => $name,
            'entities' => $this->getAttributeEntityNames($project->id, $name),
            'details'  => $this->getSampleAttributeDetails($project->id, $name),
        ]);
    }
}
