<?php

namespace App\Http\Controllers\Web\Dashboard\Admin\MCFS;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\TransferRequest;
use App\Services\MCFSApiService;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;

class IndexMCFSWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        if (auth()->user()->is_admin) {
            $transferRequests = TransferRequest::with(['project', 'globusTransfer', 'owner'])
                                               ->withCount(['transferRequestFiles'])
                                               ->cursor();
        } else {
            $transferRequests = TransferRequest::with(['project', 'globusTransfer', 'owner'])
                                               ->withCount(['transferRequestFiles'])
                                               ->where('owner_id', auth()->id())
                                               ->get();
        }

        $transferRequestsStatus = MCFSApiService::getStatusAllTransferRequests();

        return view('app.dashboard.index', [
            'projectsCount'          => $this->getUserProjectsCount(auth()->id()),
            'deletedCount'           => Project::getDeletedTrashCountForUser(auth()->id()),
            'archivedCount'          => $this->getUserArchivedProjectsCount(auth()->id()),
            'publishedDatasetsCount' => Dataset::getPublishedDatasetsCountForUser(auth()->id()),
            'transferRequests'       => $transferRequests,
            'transferRequestsStatus' => $transferRequestsStatus,
        ]);
    }
}
