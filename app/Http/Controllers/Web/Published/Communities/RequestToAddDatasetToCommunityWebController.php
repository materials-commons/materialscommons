<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function abort_unless;
use function redirect;

class RequestToAddDatasetToCommunityWebController extends Controller
{
    public function __invoke(Request $request, Community $community, Dataset $dataset)
    {
        abort_unless(Auth::check(), 404, "Guest accounts can't add datasets");
        abort_unless(auth()->id() == $dataset->owner_id, 404, "You are not the dataset owner");
        abort_unless((!is_null($dataset->published_at)), 404, "Dataset is not published");
        $community->datasetsWaitingForApproval()->sync($dataset);
        flash("Dataset '{$dataset->name}' sent to community organizer for review")->success();
        return redirect(route('public.communities.show', [$community]));
    }
}
