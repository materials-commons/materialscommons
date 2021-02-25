<?php

namespace App\View\Components\Projects\Globus\Monitor;

use App\Models\TransferRequest;
use Illuminate\View\Component;

class GlobusTransfer extends Component
{
    public ?TransferRequest $globusRequest;

    public function __construct($globusRequest)
    {
        $this->globusRequest = $globusRequest;
    }

    public function render()
    {
        return view('components.projects.globus.monitor.globus-transfer');
    }
}
