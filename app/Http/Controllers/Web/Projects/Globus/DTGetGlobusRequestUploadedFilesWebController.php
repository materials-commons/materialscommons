<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\TransferRequestFile;
use Freshbitsweb\Laratables\Laratables;

class DTGetGlobusRequestUploadedFilesWebController extends Controller
{
    public function __invoke($projectId, $transferRequestId)
    {
        $values = Laratables::recordsOf(TransferRequestFile::class, function ($query) use ($transferRequestId) {
            return $query->with(['directory'])->where('transfer_request_id', $transferRequestId);
        });

        ray($values);
        return $values;
    }
}
