<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use function chmod;
use function shell_exec;

class GenerateThumbnailAction
{
    public function execute(File $file): void
    {
        umask(0);
        if ($file->isImage()) {
            $this->generateThumbnail($file);
        }
    }

    private function generateThumbnail(File $file): void
    {
        if (Storage::disk('mcfs')->exists($file->thumbnailPathPartial())) {
            return;
        }

        if (!Storage::disk('mcfs')->exists($file->realPathPartial())) {
            return;
        }

        $this->createThumbnailDir($file);

        $originalFilePath = Storage::disk('mcfs')->path($file->realPathPartial());
        $thumbnailFilePath = Storage::disk('mcfs')->path($file->thumbnailPathPartial());
        try {
            // Use ImageMagick to create a thumbnail with max dimensions of 200x200 pixels
            shell_exec("convert {$originalFilePath} -resize 200x200 {$thumbnailFilePath}");
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            echo "Thumbnail generation failed: {$msg}\n";
        }
        chmod($thumbnailFilePath, 0777);
    }

    private function createThumbnailDir(File $file): void
    {
        if (!Storage::disk('mcfs')->exists($file->pathDirPartial()."/.thumbnails")) {
            Storage::disk('mcfs')->makeDirectory($file->pathDirPartial()."/.thumbnails");
            chmod(Storage::disk('mcfs')->path($file->pathDirPartial()."/.thumbnails"), 0777);
        }
    }
}