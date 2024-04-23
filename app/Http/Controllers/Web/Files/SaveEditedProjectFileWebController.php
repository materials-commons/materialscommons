<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\SaveFile;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use function auth;

class SaveEditedProjectFileWebController extends Controller
{
    use SaveFile;

    public function __invoke(Request $request, Project $project, File $file)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $contents = $validated['content'];

        $existing = File::where('directory_id', $file->directory_id)
                        ->whereNull('dataset_id')
                        ->whereNull('deleted_at')
                        ->where('name', $file->name)
                        ->get();

        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5($contents),
            'mime_type'    => $file->mime_type,
            'size'         => strlen($contents),
            'name'         => $file->name,
            'owner_id'     => auth()->id(),
            'project_id'   => $project->id,
            'current'      => true,
            'description'  => "",
            'summary'      => "",
            'disk'         => 'mcfs',
            'directory_id' => $file->directory_id,
        ]);

        if ($existing->isNotEmpty()) {
            // Existing files to mark as not current
            File::whereIn('id', $existing->pluck('id'))->update(['current' => false]);
        }

        $fileEntry->save();

        $this->saveFileContents($fileEntry, $contents);

        return redirect(route('projects.files.show', [$project, $fileEntry]));
    }
}
