<?php

namespace App\Http\Queries\Files;

use App\Models\File;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleFileQuery extends FilesQueryBuilder
{
    use GetRequestParameterId;

    /**
     * Build query for a single file, don't allow directories to be looked up.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function __construct(?Request $request = null)
    {
        $fileId = $this->getParameterId('file');
        $query = File::withCommon()
                     ->with(['owner'])
                     ->whereNull('deleted_at')
                     ->whereNull('dataset_id')
                     ->where('current', true)
                     ->where('id', $fileId)
                     ->where('mime_type', '<>', 'directory');
        parent::__construct($query, $request);
    }
}
