<?php

namespace App\Http\Controllers\Web\Projects\Datatables;

use App\Http\Controllers\Controller;
use App\Models\File;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use function now;

class GetRecentlyUploadedFilesWebController extends Controller
{
    public function __invoke(Request $request, $projectId)
    {
        return Laratables::recordsOf(File::class, function ($query) use ($projectId) {
            return $query->with(['directory', 'owner'])
                         ->where('project_id', $projectId)
                         ->active()
                         ->files()
                         ->where('created_at', '>', now()->subDays(7)->endOfDay());
        });
    }
}
