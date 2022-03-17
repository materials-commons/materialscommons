<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Actions\Notifications\CreateNotificationAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class MarkDatasetForNotificationsWebController extends Controller
{
    public function __invoke(Request $request, Dataset $dataset, CreateNotificationAction $createNotificationAction)
    {
        $user = auth()->user();
        $createNotificationAction->addNotificationForUser($user, $dataset);
        $route = route('public.datasets.notifications.unmark-for-notification', [$dataset]);
        return <<<EOF
<a class="action-link ml-3"
                       href="#"
                       id="notification"
                       data-toggle="tooltip"
                       title="Stop notifications on dataset"
                       hx-get="{$route}"
                       hx-target="#notification"
                       hx-swap="outerHTML">
                        <i class='fa-fw fas fa-bell yellow-4'></i>
                    </a>

EOF;
    }
}
