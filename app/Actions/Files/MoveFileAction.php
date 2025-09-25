<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Models\User;
use App\Services\FileServices\FileMoveService;

class MoveFileAction
{
    public function __construct(private ?FileMoveService $service = null)
    {
        $this->service = $this->service ?: app(FileMoveService::class);
    }

    // Move a file to another directory. Can also move between projects. It assumes that all access checks
    // have already been done.
    public function __invoke(File $file, File $toDir, User $user)
    {
        return $this->service->move($file, $toDir, $user);
    }
}
