<?php

namespace App\Http\Controllers\Web\Folders\Datatables;

use App\Http\Controllers\Controller;
use App\Models\File;
use Freshbitsweb\Laratables\Laratables;

class GetFolderDatatableWebController extends Controller
{
    public function __invoke($projectId, $folderId)
    {
        return Laratables::recordsOf(File::class, function ($query) use ($projectId, $folderId) {
            return $query->where('project_id', $projectId)->where('directory_id', $folderId)->where('current', true);
        });
    }
}
