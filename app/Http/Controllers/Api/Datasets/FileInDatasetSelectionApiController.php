<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\DatasetFileSelection;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class FileInDatasetSelectionApiController extends Controller
{
    public function __invoke(Request $request, Project $project, Dataset $dataset, $fileId)
    {
        $file = File::with('directory')->findOrFail($fileId);
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);
        return [
            'data' => [
                'in_dataset' => $datasetFileSelection->isIncludedFile("{$file->directory->path}/{$file->name}"),
            ],
        ];
    }
}
