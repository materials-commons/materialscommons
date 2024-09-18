<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class GetAttributeDetailsForQueryBuilder extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $attrType = $request->query('attrType');
        $attrName = $request->query('attrName');
        return view('app.projects.datahq.pages.qb-attr-details', [
            'attrType' => $attrType,
            'attrName' => $attrName,
            'project'  => $project,
        ]);
    }
}
