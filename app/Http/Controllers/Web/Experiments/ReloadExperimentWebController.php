<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\ReloadExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function redirect;
use function route;
use const PHP_URL_HOST;

class ReloadExperimentWebController extends Controller
{
    public function __invoke(Request    $request, ReloadExperimentAction $reloadExperimentAction, Project $project,
                             Experiment $experiment)
    {
        $request->validate(['file_id' => 'nullable|integer', 'sheet_url' => 'nullable|url']);

        $fileId = $request->get('file_id');
        $sheetUrl = $this->cleanupGoogleSheetUrl($request->get('sheet_url'));

        if (is_null($fileId) && is_null($sheetUrl)) {
            // One of these must be set
            flash('Failed reloading, neither a URL or a File were supplied')->error();
            return redirect(route('projects.experiments.show', [$project, $experiment]));
        }

        if (!is_null($fileId) && !is_null($sheetUrl)) {
            // Both fileId and sheetUrl are set. Both can't be set.
            flash("Failed reloading, you specified both a file and a Google sheet. Choose one or the other.")->error();
            return redirect(route('projects.experiments.show', [$project, $experiment]));
        }

        if ($reloadExperimentAction->execute($project, $experiment, $fileId, $sheetUrl, auth()->id())) {
            flash("Reloading experiment {$experiment->name} in background.")->success();
        } else {
            flash("Failed reloading, no changes made to experiment.")->error();
        }

        return redirect(route('projects.experiments.show', [$project, $experiment]));
    }

    private function cleanupGoogleSheetUrl($url): ?string
    {
        if (Str::contains($url, "/edit?")) {
            // Remove /edit from the end of the url
            $path = dirname(parse_url($url, PHP_URL_PATH));

            $host = parse_url($url, PHP_URL_HOST);

            // $path starts with a slash (/), so we don't add one to separate host and path when constructing the url.
            return "https://{$host}{$path}";
        }

        return $url;
    }
}
