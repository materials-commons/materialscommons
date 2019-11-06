<?php

namespace App\Actions\Globus;

use App\Models\File;
use App\Models\GlobusUpload;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LoadGlobusUploadIntoProjectAction
{
    /** @var \App\Models\GlobusUpload */
    private $globusUpload;

    private $maxItemsToProcess;

    public function __construct(GlobusUpload $globusUpload, $maxItemsToProcess)
    {
        $this->globusUpload = $globusUpload;
        $this->maxItemsToProcess = $maxItemsToProcess;
    }

    public function __invoke()
    {
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->globusUpload->path),
            RecursiveIteratorIterator::SELF_FIRST);
        $fileCount = 0;

        // Start off with root dir and then adjust as needed
        $currentDir = File::where('project_id', $this->globusUpload->project->id)->where('name', '/')->first();
        foreach ($dirIterator as $path => $finfo) {
            if ($fileCount >= $this->maxItemsToProcess) {
                $this->globusUpload->update(['loading' => false]);

                return;
            }

            if (Str::endsWith($path, "/.") || Str::endsWith($path, "/..")) {
                continue;
            }

            if ($finfo->isDir()) {
                // assign current dir here
                $currentDir = $this->processDir($path, $currentDir);
            } else {
                $this->processFile($path, $finfo, $currentDir);
            }
        }

        $this->globusUpload->delete();
    }

    private function processDir($path, File $currentDir): File
    {
        $dirPath = Str::replaceFirst($this->globusUpload->path, "", $path);
        echo $dirPath;
        $dir = File::where('project_id', $this->globusUpload->project->id)->where('path', $dirPath)->first();
        if ($dir !== null) {
            return $dir;
        }

        return File::create([
            'name'         => basename($dirPath),
            'path'         => $dirPath,
            'mime_type'    => 'directory',
            'owner_id'     => $this->globusUpload->owner->id,
            'project_id'   => $this->globusUpload->project->id,
            'directory_id' => $currentDir->id,
        ]);
    }

    private function processFile($path, \SplFileInfo $finfo, File $currentDir)
    {
        $finfo->getSize();
        mime_content_type($path);
        $fileEntry = new File([
            'uuid'         => Uuid::uuid4()->toString(),
            'checksum'     => md5_file($path),
            'mime_type'    => mime_content_type($path),
            'size'         => $finfo->getSize(),
            'name'         => $finfo->getFilename(),
            'owner_id'     => $this->globusUpload->owner->id,
            'current'      => true,
            'description'  => "",
            'project_id'   => $this->globusUpload->project->id,
            'directory_id' => $currentDir->id,
        ]);

        $existing = File::where('directory_id', $currentDir->id)->where('name', $fileEntry->name)->get();
        $matchingFileChecksum = File::where('checksum', $fileEntry->checksum)->whereNull('uses_id')->first();

        if ( ! $matchingFileChecksum) {
            // Just save physical file and insert into database
            $this->moveFileIntoProject($path, $fileEntry->uuid);
        } else {
            // Matching file found, so point at it.
            $fileEntry->uses_uuid = $matchingFileChecksum->uuid;
            $fileEntry->uses_id = $matchingFileChecksum->id;
        }

        $fileEntry->save();

        if ($existing->isNotEmpty()) {
            // Existing files to mark as not current
            File::whereIn('id', $existing->pluck('id'))->update(['current' => false]);
        }

        return $fileEntry;
    }

    private function moveFileIntoProject($path, $uuid)
    {

    }
}