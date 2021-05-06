<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class DownloadPublishedDatasetFileApiController extends Controller
{
    public function __invoke(Dataset $dataset, File $file)
    {
        abort_if(is_null($dataset->published_at), 404);
        $count = DB::table('dataset2file')->where('dataset_id', $dataset->id)
            ->where('file_id', $file->id)
            ->count();
        abort_if($count == 0, 400, "No such file");
        return response()->download($file->mcfsPath(), $file->name);
    }
}
