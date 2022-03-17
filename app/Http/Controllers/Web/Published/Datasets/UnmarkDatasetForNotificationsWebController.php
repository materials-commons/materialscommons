<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Actions\Notifications\DeleteNotificationAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class UnmarkDatasetForNotificationsWebController extends Controller
{
    public function __invoke(Request $request, Dataset $dataset, DeleteNotificationAction $deleteNotificationAction)
    {
        $user = auth()->user();
        $deleteNotificationAction->deleteNotificationForUser($user, $dataset);

        $route = route('public.datasets.notifications.mark-for-notification', [$dataset]);

        return <<<EOF
<a class="action-link ml-3"
                       href="#"
                       id="notification"
                       data-toggle="tooltip"
                       title="Get notified when dataset is updated"
                       hx-get="{$route}"
                       hx-target="#notification"
                       hx-swap="outerHTML">
                        <i class="fas fa-fw fa-bell-slash"></i>
                    </a>

EOF;
    }
}
