<?php

namespace App\Http\Controllers\Web\Published\Datasets\Folders;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\Datasets\GetPublishedDatasetFolderFiles;
use App\ViewModels\Folders\ShowFolderViewModel;
use App\ViewModels\Published\Datasets\ShowPublishedDatasetFolderViewModel;
use Illuminate\Http\Request;

class ShowPublishedDatasetFolderWebController extends Controller
{
    use GetPublishedDatasetFolderFiles;

    public function __invoke(Request $request, $datasetId, $folderId)
    {
        $dataset = Dataset::with(['workflows', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        if ($folderId == -1) {
            $dir = File::where('dataset_id', $dataset->id)
                       ->where('path', '/')
                       ->first();
        } else {
            $dir = File::where('dataset_id', $dataset->id)
                       ->where('id', $folderId)
                       ->first();
        }
        $files = $this->getPublishedDatasetFolderFiles($dataset, $folderId);
        $viewModel = (new ShowPublishedDatasetFolderViewModel($dir, $files))->withDataset($dataset);
        return view('public.datasets.show', $viewModel);
    }
}
