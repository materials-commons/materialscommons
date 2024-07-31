<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\CreateGlobusUploadAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use function auth;
use function randomWords;
use function redirect;
use function route;

class CreateProjectGlobusUploadWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.uploads.edit_account', [$project]));
        }

        $createGlobusUploadAction = new CreateGlobusUploadAction(GlobusApi::createGlobusApi());
        $data['name'] = randomWords(3);
        $data['description'] = '';
        $globusUpload = $createGlobusUploadAction($data, $project->id, auth()->user());

        return redirect(route('projects.globus.uploads.show', [$project, $globusUpload]));
    }
}
