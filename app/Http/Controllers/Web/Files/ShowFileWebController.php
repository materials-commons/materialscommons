<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Files\ShowFileViewModel;

class ShowFileWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        $viewModel = new ShowFileViewModel($file, $project);
        return view('app.files.show', $viewModel);
    }
}
