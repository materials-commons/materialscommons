<?php

namespace App\Http\Controllers\Web\Published\Datasets\Folders;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class GotoPublishedDatasetFolderByPathWebController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        $path = request()->input("path");
        $dir = File::where('dataset_id', $dataset->id)
                   ->where('path', $path)
                   ->where('mime_type', 'directory')
                   ->where('current', true)
                   ->whereNull('deleted_at')
                   ->first();
        return redirect(route('public.datasets.folders.show', [$dataset, $dir]));
    }
}
