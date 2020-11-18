<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class SetAsActiveFileVersionWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        if ($file->current) {
            // Already active version nothing to do
            return redirect(route('projects.files.show', [$project, $file]));
        }

        $this->setAsActiveFile($file);
        return redirect(route('projects.files.show', [$project, $file]));
    }

    private function setAsActiveFile(File $file)
    {
        DB::transaction(function () use ($file) {
            // First mark all files matching name in the directory as not active
            File::where('directory_id', $file->directory_id)
                ->where('name', $file->name)
                ->update(['current' => false]);

            // Then mark the file passed in as active
            $file->update(['current' => true]);
        });
    }
}
