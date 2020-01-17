<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;

class ShowDashboardWebController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $globusUploads = $user->globusUploads->filter(function (GlobusUploadDownload $globusEntry) {
            return $globusEntry->type == "upload";
        });

        return view('app.dashboard.index', compact('globusUploads', 'user'));
    }
}
