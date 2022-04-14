<?php

namespace App\Http\Queries\Published\Datasets;

use App\Models\File;
use Illuminate\Http\Request;

class IndexPublishedDatasetDirectoryQuery extends PublishedDatasetsDirectoryQueryBuilder
{
    public function __construct(?Request $request = null, $datasetId, $directoryId)
    {
        $builder = File::with(['owner', 'directory'])
            ->where('dataset_id', $datasetId)
            ->where('directory_id', $directoryId);
        parent::__construct($builder, $request);
    }
}