<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexImagesWebController extends Controller
{
    public function __invoke(Project $project, File $folder)
    {
        $images = File::where('directory_id', $folder->id)
                      ->where(function ($query) {
                          $query->orWhere('mime_type', 'image/jpeg')
                                ->orWhere('mime_type', 'image/png')
                                ->orWhere('mime_type', 'image/tiff')
                                ->orWhere('mime_type', 'image/x-ms-bmp')
                                ->orWhere('mime_type', 'image/bmp');
                      })
                      ->cursor();
        return view('app.projects.folders.index-images', [
            'folder'  => $folder,
            'project' => $project,
            'images'  => $images,
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