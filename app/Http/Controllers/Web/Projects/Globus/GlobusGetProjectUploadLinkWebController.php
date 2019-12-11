<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\Project;

class GlobusGetProjectUploadLinkWebController extends Controller
{
    public function __invoke(Project $project)
    {
//        $endpointId = env('MC_GLOBUS_ENDPOINT_ID');
//        $uuid = Uuid::uuid4()->toString();
//        $path = storage_path("app/__globus_uploads/{$uuid}");
//        $globusPath = "/__globus_uploads/{$uuid}";
//
//        if (!is_dir($path)) {
//            mkdir($path);
//        }
//
//        $globusApi = GlobusApi::createGlobusApi();
//        $user = auth()->user();
//
//
//        GlobusUrl::globusUploadUrl($endpointId, $globusPath);
    }
}
