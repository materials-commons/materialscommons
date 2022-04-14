<?php

namespace App\Http\Queries\Published\Datasets;

use App\Models\File;
use Illuminate\Http\Request;

class SinglePublishedDatasetDirectoryQuery extends PublishedDatasetsDirectoryQueryBuilder
{
    public function __construct($datasetId, $directoryId, ?Request $request = null)
    {
        $query = File::with(['owner', 'directory'])
                     ->where('id', $directoryId)
                     ->where('dataset_id', $datasetId)
                     ->where('mime_type', 'directory');
        parent::__construct($query, $request);
    }
}