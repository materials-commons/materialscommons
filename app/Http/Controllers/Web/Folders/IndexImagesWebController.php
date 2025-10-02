<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class IndexImagesWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, Project $project, File $folder)
    {
        $arg = $request->get('arg');
        $destProj = $this->getDestinationProjectId($project);
        $destDir = $this->getDestinationDirId();
        $images = File::with(['entities'])
                      ->where('directory_id', $folder->id)
                      ->active()
                      ->where(function ($query) {
                          $query->orWhere('mime_type', 'image/jpeg')
                                ->orWhere('mime_type', 'image/png')
                                ->orWhere('mime_type', 'image/tiff')
                                ->orWhere('mime_type', 'image/x-ms-bmp')
                                ->orWhere('mime_type', 'image/bmp');
                      })
                      ->cursor();

        return view('app.projects.folders.index-images', [
            'folder'   => $folder,
            'project'  => $project,
            'images'   => $images,
            'destProj' => $destProj,
            'destDir'  => $destDir,
            'arg'      => $arg,
        ]);
    }
}

/*
 *  "image/gif"      => true,
        "image/jpeg"     => true,
        "image/png"      => true,
        "image/tiff"     => true,
        "image/x-ms-bmp" => true,
        "image/bmp"      => true,
 */
