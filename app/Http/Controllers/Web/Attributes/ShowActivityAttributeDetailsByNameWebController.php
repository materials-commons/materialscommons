<?php

namespace App\Http\Controllers\Web\Attributes;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowActivityAttributeDetailsByNameWebController extends Controller
{
    public function __invoke(Request $request, Project $project, $name)
    {
        return view('partials.attributes._show-details', [
            'project' => $project,
            'name'    => $name,
        ]);
    }
}
