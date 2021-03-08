<?php

namespace App\View\Components\Projects\Globus\Monitor;

use App\Models\TransferRequest;
use App\Models\TransferRequestFile;
use Illuminate\View\Component;

class ShowConflictingGlobusFileUploads extends Component
{
    public TransferRequest $globusRequest;

    public function __construct($globusRequest)
    {
        $this->globusRequest = $globusRequest;
    }

    public function render()
    {
        $conflictingFiles = TransferRequestFile::where('project_id', $this->globusRequest->project_id)
                                               ->where('globus_request_id', '<>', $this->globusRequest->id)
                                               ->whereIn('directory_id', function ($q) {
                                                   $q->select('directory_id')->from('globus_request_files');
                                               });
        return view('components.projects.globus.monitor.show-conflicting-globus-file-uploads');
    }
}
