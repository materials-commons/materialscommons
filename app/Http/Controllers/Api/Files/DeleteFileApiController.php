<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\DeleteFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;

class DeleteFileApiController extends Controller
{
    public function __invoke(DeleteFileAction $deleteFileAction, $projectId, $fileId)
    {
        $file = File::withCount(['entityStates', 'activities', 'entities'])
                    ->where('project_id', $projectId)
                    ->whereNull('deleted_at')
                    ->whereNull('dataset_id')
                    ->where('current', true)
                    ->where('id', $fileId)
                    ->first();
        $force = request()->input('force', false);

        // If no force flag then check if there are related objects and abort if true
        if (!$force) {
            abort_if($this->hasRelatedObjects($file), 400, "File has related objects");
        }

        $deleteFileAction($file);
    }

    private function hasRelatedObjects(File $file)
    {
        if ($file->entity_states_count > 0) {
            return true;
        }

        if ($file->activities_count > 0) {
            return true;
        }

        if ($file->entities_count > 0) {
            return true;
        }

        return false;
    }
}
