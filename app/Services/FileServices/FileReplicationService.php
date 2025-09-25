<?php

namespace App\Services\FileServices;

use App\Models\File;
use Illuminate\Support\Carbon;

class FileReplicationService
{
    public function __construct(
        private FilePathResolver $paths,
        private FileStorageService $storage
    ) {}

    public function replicate(File $file): void
    {
        $real = $this->paths->realPathPartial($file);
        if (!$this->storage->exists('mcfs', $real)) {
            return; // source missing; nothing to do
        }

        $replicaPartial = $this->paths->partialReplicaPath($file);
        if (!$this->storage->exists('mcfs', $replicaPartial)) {
            // same disk scheme used by existing code
            $this->storage->copy('mcfs', $real, 'mcfs', $replicaPartial);
            // best effort permission set via native path
            @chmod($this->storage->path('mcfs', $replicaPartial), 0777);
        }
        $file->update(['replicated_at' => Carbon::now()]);
    }
}
