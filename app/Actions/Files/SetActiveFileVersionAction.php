<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Services\FileServices\FileVersioningService;

class SetActiveFileVersionAction
{
    public function __construct(private ?FileVersioningService $service = null)
    {
        $this->service = $this->service ?: app(FileVersioningService::class);
    }

    public function __invoke(File $file): File
    {
        return $this->service->setActive($file);
    }
}
