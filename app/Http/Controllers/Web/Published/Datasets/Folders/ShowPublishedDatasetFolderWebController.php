<?php

namespace App\Http\Controllers\Web\Published\Datasets\Folders;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\Datasets\GetPublishedDatasetFolderFiles;
use App\Traits\Projects\UserProjects;
use App\ViewModels\Folders\ShowFolderViewModel;
use App\ViewModels\Published\Datasets\ShowPublishedDatasetFolderViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function auth;
use function collect;

class ShowPublishedDatasetFolderWebController extends Controller
{
    use GetPublishedDatasetFolderFiles;
    use UserProjects;

    public function __invoke(Request $request, $datasetId, $folderId)
    {
        $dataset = Dataset::with(['workflows', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        if ($folderId == -1) {
            $dir = File::where('dataset_id', $dataset->id)
                       ->where('path', '/')
                       ->where('mime_type', 'directory')
                       ->first();
        } else {
            $dir = File::where('dataset_id', $dataset->id)
                       ->where('id', $folderId)
                       ->first();
        }
        $files = $this->getPublishedDatasetFolderFiles($dataset, $folderId);
        $readme = $files->first(function ($file) {
            return Str::lower($file->name) == "readme.md";
        });

        if (auth()->check()) {
            $userProjects = $this->getUserProjects(auth()->id());
        } else {
            $userProjects = collect();
        }

        $viewModel = (new ShowPublishedDatasetFolderViewModel($dir, $files))
            ->withReadme($readme)
            ->withUserProjects($userProjects)
            ->withDataset($dataset);
        return view('public.datasets.show', $viewModel);
    }
}
