<?php

namespace App\View\Components\Projects\Globus\Monitor;

use App\Models\TransferRequest;
use Illuminate\View\Component;

class ShowFileUploadStatus extends Component
{
    public TransferRequest $globusRequest;

    public function __construct($globusRequest)
    {
        $this->globusRequest = $globusRequest;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.projects.globus.monitor.show-file-upload-status');
    }
}
