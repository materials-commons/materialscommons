<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\GetFileContentsForDisplayAction;
use App\Http\Controllers\Controller;
use App\Jobs\Files\GenerateThumbnailJob;
use App\Models\File;
use App\Models\Project;
use App\Traits\FileType;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DisplayFileThumbnailWebController extends Controller
{
    use FileType;
    
    public function __invoke(GetFileContentsForDisplayAction $getFileContentsForDisplayAction, Project $project,
        File $file)
    {
        if (!$file->isImage()) {
            // If not an image, redirect to regular file display
            return redirect()->route('projects.files.display', [$project, $file]);
        }
        
        // Check if thumbnail exists
        $thumbnailPath = $file->thumbnailPathPartial();
        if (!Storage::disk('mcfs')->exists($thumbnailPath)) {
            // If thumbnail doesn't exist, generate it
            GenerateThumbnailJob::dispatch($file)->onQueue('globus');
            
            // Use the original file for now
            $f = $getFileContentsForDisplayAction->execute($file);
        } else {
            // Use the thumbnail
            $f = $getFileContentsForDisplayAction->execute($file, true);
        }
        
        abort_if(is_null($f), 404);
        $response = Response::make($f, 200);
        $response->header("Content-Type", "image/jpeg");

        return $response;
    }
}