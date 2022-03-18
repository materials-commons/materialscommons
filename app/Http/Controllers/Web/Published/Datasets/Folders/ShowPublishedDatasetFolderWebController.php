<?php

namespace App\Http\Controllers\Web\Published\Datasets\Folders;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\Datasets\GetPublishedDatasetFolderFiles;
use App\ViewModels\Folders\ShowFolderViewModel;
use Illuminate\Http\Request;

class ShowPublishedDatasetFolderWebController extends Controller
{
    use GetPublishedDatasetFolderFiles;

    public function __invoke(Request $request, Dataset $dataset, $folderId)
    {
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
        $viewModel = (new ShowFolderViewModel($dir, $files))->withDataset($dataset);
        return view('public.datasets.show', $viewModel);
    }
}
