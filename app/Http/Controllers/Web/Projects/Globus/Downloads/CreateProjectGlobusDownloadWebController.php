<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Http\Controllers\Controller;
use App\Jobs\Globus\CreateGlobusProjectDownloadDirsJob;
use App\Models\Project;
use function auth;
use function randomWords;
use function redirect;
use function route;

class CreateProjectGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.downloads.edit_account', [$project]));
        }

        $createGlobusDownloadForProjectAction = new CreateGlobusDownloadForProjectAction();
        $data['name'] = randomWords(3);
        $data['description'] = '';
        $globusDownload = $createGlobusDownloadForProjectAction($data, $project->id, auth()->user());
        CreateGlobusProjectDownloadDirsJob::dispatch($globusDownload, auth()->user())->onQueue('globus');
        return redirect(route('projects.globus.downloads.index', [$project]));
    }
}
