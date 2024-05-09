<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Files\ShowFileViewModel;
use Illuminate\Http\Request;
use App\Traits\FileView;

class EditProjectFileWebController extends Controller
{
    use FileView;

    public function __invoke(Request $request, Project $project, File $file)
    {
        $viewModel = (new ShowFileViewModel($file, $project));
        return view('app.files.edit', $viewModel);
    }
}
