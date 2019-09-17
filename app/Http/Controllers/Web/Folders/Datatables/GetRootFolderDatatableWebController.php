<?php

namespace App\Http\Controllers\Web\Folders\Datatables;

use App\Http\Controllers\Controller;
use App\Models\File;
use Freshbitsweb\Laratables\Laratables;

class GetRootFolderDatatableWebController extends Controller
{
    public function __invoke($id)
    {
        $rootId = File::where('project_id', $id)->where('name', '/')->first()->id;
        return Laratables::recordsOf(File::class, function ($query) use ($id, $rootId) {
            return $query->where('project_id', $id)->where('directory_id', $rootId)->where('current', true);
        });
    }
}
