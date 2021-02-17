<?php

namespace App\View\Components\Projects\Globus\Monitor;

use App\Models\GlobusRequest;
use Illuminate\View\Component;

class ShowOtherActiveGlobusUploads extends Component
{
    public GlobusRequest $globusRequest;

    public function __construct($globusRequest)
    {
        $this->globusRequest = $globusRequest;
    }

    public function render()
    {
        $otherActive = GlobusRequest::with(['owner'])
                                    ->withCount(['globusRequestFiles'])
                                    ->where('project_id', $this->globusRequest->project_id)
                                    ->where('owner_id', '<>', auth()->id())
                                    ->get();
        return view('components.projects.globus.monitor.show-other-active-globus-uploads', [
            'otherActive' => $otherActive,
        ]);
    }
}
