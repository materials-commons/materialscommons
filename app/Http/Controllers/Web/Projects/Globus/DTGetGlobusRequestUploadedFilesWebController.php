<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusRequestFile;
use Freshbitsweb\Laratables\Laratables;

class DTGetGlobusRequestUploadedFilesWebController extends Controller
{
    public function __invoke($projectId, $globusRequestId)
    {
        ray($projectId, $globusRequestId);
        $values = Laratables::recordsOf(GlobusRequestFile::class, function ($query) use ($globusRequestId) {
            return $query->with(['directory'])->where('globus_request_id', $globusRequestId);
        });

        ray($values);
        return $values;
    }
}
