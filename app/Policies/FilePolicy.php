<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function view(User $user, File $file): bool
    {
        return $this->ownsProject($user, $file);
    }

    public function download(User $user, File $file): bool
    {
        return $this->ownsProject($user, $file);
    }

    public function move(User $user, File $file, File $toDir): bool
    {
        return $this->ownsProject($user, $file) && $this->ownsProject($user, $toDir);
    }

    public function rename(User $user, File $file): bool
    {
        return $this->ownsProject($user, $file);
    }

    public function setActiveVersion(User $user, File $file): bool
    {
        return $this->ownsProject($user, $file);
    }

    private function ownsProject(User $user, File $file): bool
    {
        return (int)$file->project_id === (int)$user->current_project_id || (int)$file->owner_id === (int)$user->id;
    }
}
