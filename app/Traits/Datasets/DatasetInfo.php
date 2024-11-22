<?php

namespace App\Traits\Datasets;

use Illuminate\Support\Facades\DB;

trait DatasetInfo
{
    private function getDatasetTotalFilesSize($datasetId)
    {
        return DB::table('dataset2file')
                 ->where('dataset2file.dataset_id', $datasetId)
                 ->join('files', 'files.id', '=', 'dataset2file.file_id')
                 ->where('files.mime_type', '<>', 'directory')
                 ->distinct()
                 ->select('files.size', 'file.id')
                 ->sum('files.size');
    }
}