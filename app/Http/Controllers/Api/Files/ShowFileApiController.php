<?php

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Http\Queries\Files\SingleFileQuery;
use App\Http\Resources\Files\FileResource;

class ShowFileApiController extends Controller
{
    /**
     * Show the file.
     *
     * @param  \App\Http\Queries\Files\SingleFileQuery  $query
     *
     * @return \App\Http\Resources\Files\FileResource
     */
    public function __invoke(SingleFileQuery $query)
    {
        $data = $query->get();
        abort_if($data->isEmpty(), 404, "File not found");
        return new FileResource($data[0]);
    }
}
