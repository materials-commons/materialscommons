<?php

namespace App\Http\Controllers\Web\Admin\MCFS;

use App\Http\Controllers\Controller;
use App\Models\GlobusTransfer;
use App\Models\MCTransfer;
use App\Models\TransferRequest;
use App\Models\User;
use Illuminate\Http\Request;

class CreateTransferRequestWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'project_id'     => 'integer|required',
            'email'          => 'email|required',
            'is_mc_transfer' => 'boolean|required'
        ]);

        $user = User::where('email', $validated['email'])->first();
        $projectId = $validated['project_id'];

        $isMCTransfer = $validated['is_mc_transfer'];

        $tr = TransferRequest::create([
            'project_id' => $projectId,
            'owner_id'   => $user->id,
            'state'      => 'open',
        ]);

        if ($isMCTransfer) {
            MCTransfer::create([
                'project_id'          => $projectId,
                'owner_id'            => $user->id,
                'state'               => 'open',
                'transfer_request_id' => $tr->id,
            ]);
        } else {
            GlobusTransfer::create([
                'project_id'          => $projectId,
                'owner_id'            => $user->id,
                'state'               => 'open',
                'transfer_request_id' => $tr->id,
            ]);
        }

        // For now don't set up the rest of Globus
        return redirect(route('dashboard.admin.mcfs.index'));
    }
}
