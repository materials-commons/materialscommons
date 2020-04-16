<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class DownloadDatasetFileWebController extends Controller
{
    public function __invoke(Dataset $dataset, File $file)
    {
        $count = DB::table('dataset2file')->where('dataset_id', $dataset->id)
                   ->where('file_id', $file->id)
                   ->count();
        abort_if($count == 0, 400, "No such file");
        return response()->download($file->mcfsPath(), $file->name);
    }
}
