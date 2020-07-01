<?php

namespace App\Http\Queries\Files;

use App\Http\Queries\Traits\GetFileQuery;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleFileQuery extends FilesQueryBuilder
{
    use GetRequestParameterId;
    use GetFileQuery;

    /**
     * Build query for a single file, don't allow directories to be looked up.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function __construct(?Request $request = null)
    {
        $fileId = $this->getParameterId('file');
        $query = $this->getBaseFileQuery()
                      ->where('id', $fileId)
                      ->where('mime_type', '!=', 'directory');
        parent::__construct($query, $request);
    }
}