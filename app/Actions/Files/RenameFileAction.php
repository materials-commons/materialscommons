<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Services\FileServices\FileRenameService;

class RenameFileAction
{
    public function __construct(private ?FileRenameService $service = null)
    {
        $this->service = $this->service ?: app(FileRenameService::class);
    }

    /**
     * Rename a file and all its previous version
     *
     * @param  \App\Models\File  $file
     * @param string $name
     * @return \App\Models\File|null
     */
    public function __invoke(File $file, $name, $url = null)
    {
        return $this->service->rename($file, (string)$name, $url);
    }
}
