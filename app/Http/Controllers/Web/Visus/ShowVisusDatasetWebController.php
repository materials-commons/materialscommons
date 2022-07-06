<?php

namespace App\Http\Controllers\Web\Visus;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Services\OpenVisusApiService;
use Illuminate\Http\Request;

class ShowVisusDatasetWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $file)
    {
        return view('app.visus.visus', [
            'project'         => $project,
            'visusDatasetUrl' => OpenVisusApiService::visusDatasetUrl('2kbit1'),
        ]);
    }
}
