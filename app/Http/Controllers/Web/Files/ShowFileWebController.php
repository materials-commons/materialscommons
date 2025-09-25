<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Files\FileHealth;
use App\ViewModels\Files\ShowFileViewModel;

class ShowFileWebController extends Controller
{
    use FileHealth;

    public function __invoke(Project $project, $fileId)
    {
        $file = File::withCount('entities', 'activities', 'experiments')
                    ->findOrFail($fileId);

        // Check if file is missing and set health to missing only if the file
        // isn't already marked as missing.
        if ($file->health !== 'missing') {
            if (!$file->realFileExists()) {
                $this->setFileHealthMissing($file, "view-file");
            }
        }
        $viewModel = (new ShowFileViewModel($file, $project))
            ->withPreviousVersions($file->previousVersions()->get());
        return view('app.files.show', $viewModel);
    }
}
