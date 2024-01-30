<?php

namespace App\Http\Controllers\Web\Admin\MCFS;

use App\Http\Controllers\Controller;
use App\Models\TransferRequest;
use App\Models\TransferRequestFile;
use Illuminate\Http\Request;

class ShowTransferRequestWebController extends Controller
{
    public function __invoke(Request $request, TransferRequest $transferRequest)
    {
        $transferRequest->load(['project', 'globusTransfer', 'owner'])
                        ->loadCount(['transferRequestFiles']);
        $transferRequestFiles = TransferRequestFile::query()
                                                   ->where('transfer_request_id', $transferRequest->id)
                                                   ->cursor();
        return view('app.transfer-requests.show', [
            'transferRequest'      => $transferRequest,
            'transferRequestFiles' => $transferRequestFiles,
        ]);
    }
}
