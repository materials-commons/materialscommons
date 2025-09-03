<?php

namespace App\Http\Controllers\Web2\Projects\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use Freshbitsweb\Laratables\Laratables;

class ProjectFoldersDatatableController extends Controller
{

    /**
     * @param $id Project id
     *
     * @return array
     */
    public function getRootFolder($id)
    {
        $rootId = File::where('project_id', $id)->where('name', '/')->first()->id;
        return Laratables::recordsOf(File::class, function ($query) use ($id, $rootId) {
            return $query->where('project_id', $id)
                         ->where('directory_id', $rootId)
                         ->active();
        });
    }

    /**
     * @param $projectId project Id that folder is in
     * @param $folderId folder Id to select files/directories from
     * @return array
     */
    public function getFolder($projectId, $folderId)
    {
        return Laratables::recordsOf(File::class, function ($query) use ($projectId, $folderId) {
            return $query->where('project_id', $projectId)
                         ->where('directory_id', $folderId)
                         ->active();
        });
    }
}
