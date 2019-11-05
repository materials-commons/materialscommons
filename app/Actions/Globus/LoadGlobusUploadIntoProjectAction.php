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

        /** @var  \App\Models\File */
        $currentDir = null;
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
            }

            $this->processFileOrDir($path, $finfo, $currentDir);
        }

        $this->globusUpload->delete();
    }

    private function processFileOrDir($path, \SplFileInfo $finfo, File $currentDir)
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
    }
}