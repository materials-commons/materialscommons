<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\DatasetFileSelection;
use App\Actions\Etl\GetFileByPathAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class FileByPathInDatasetSelectionApiController extends Controller
{
    public function __invoke(Request $request, GetFileByPathAction $getFileByPathAction, Project $project,
        Dataset $dataset)
    {
        $filePath = $request->input("file_path");
        abort_if($filePath == "", 400, "file_path is required");
        $file = $getFileByPathAction($project->id, $filePath);
        abort_if(is_null($file), 400, "No such file");
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);
        return [
            'data' => [
                'in_dataset' => $datasetFileSelection->isIncludedFile($filePath),
            ],
        ];
    }
}
