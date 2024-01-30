<?php

namespace App\View\Components\TransferRequests;

use App\Models\TransferRequest;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowFileActivity extends Component
{
    public $tr;

    public function __construct(TransferRequest $transferRequest)
    {
        $this->tr = $transferRequest;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transfer-requests.show-file-activity');
    }
}
