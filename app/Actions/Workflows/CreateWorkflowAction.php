<?php

namespace App\Actions\Workflows;

use App\Models\File;
use App\Models\Workflow;

class CreateWorkflowAction
{
    public function __invoke($data, $projectId, $userId, $experiment = null)
    {
        $workflow = new Workflow([
            'name' => $data['name'],
        ]);

        if (array_key_exists('description', $data)) {
            $workflow->description = $data['description'];
        }

        if (array_key_exists('summary', $data)) {
            $workflow->description = $data['summary'];
        }

        if (array_key_exists('workflow', $data)) {
            $workflow->workflow = $data['workflow'];
        } elseif (array_key_exists('file_id', $data)) {
            $fileId = $data['file_id'];
            $count = File::where('id', $fileId)->where('project_id', $projectId)->count();
            abort_if($count == 0, 404, 'No such file');
            $workflow->file_id = $data['file_id'];
        }
        $workflow->owner_id = $userId;
        $workflow->project_id = $projectId;
        $workflow->save();
        $workflow->refresh();
        if ($experiment != null) {
            $experiment->workflows()->attach($workflow);
        }

        return $workflow;
    }
}
