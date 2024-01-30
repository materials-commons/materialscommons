<?php

namespace App\View\Components\TransferRequests;

use App\Models\GlobusTransfer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowGlobusDetails extends Component
{

    public GlobusTransfer $gr;

    public function __construct(GlobusTransfer $globusTransfer)
    {
        $this->gr = $globusTransfer;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transfer-requests.show-globus-details');
    }
}
