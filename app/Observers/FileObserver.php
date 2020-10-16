<?php

namespace App\Observers;

use App\Models\File;
use App\Models\Project;
use App\Traits\FileType;
use Illuminate\Support\Facades\DB;

class FileObserver
{
    use FileType;

    /**
     * Handle the file "created" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function created(File $file)
    {
        DB::transaction(function () use ($file) {
            if ($this->fileShouldUpdateProject($file)) {
                // This is a file that will update the project.
                $project = Project::find($file->project_id);
                if (is_null($project)) {
                    return;
                }

                $this->addToCounts($project, $file);
            }
        });
    }

    /**
     * Handle the file "updated" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function updated(File $file)
    {
        DB::transaction(function () use ($file) {
            if ($file->mime_type === 'directory') {
                // Nothing to do for directories
                return;
            }

            if (is_null($file->project_id)) {
                // No project so ignore
                return;
            }

            // File was updated. Since this is an existing file what we care about is
            // if the file was changed from current to not current or vice versa because
            // this will affect the project size. If this wasn't the change then we
            // ignore this update.
            $oldCurrentValue = $file->getOriginal('current');
            if ($oldCurrentValue === $file->current) {
                // This wasn't changed so ignore update
                return;
            }

            // This is a file that will update the project.
            $project = Project::find($file->project_id);
            if (is_null($project)) {
                return;
            }

            // Now determine how to apply update. If $current is false, then we decrement size,
            // if it is true then we increment the size.
            if ($file->current) {
                // file was changed to current so increment
                $this->addToCounts($project, $file);
            } else {
                // File was changed to not current, so decrement the size
                $this->subtractFromCounts($project, $file);
            }
        });
    }

    /**
     * Handle the file "deleted" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function deleted(File $file)
    {
        DB::transaction(function () use ($file) {
            if ($this->fileShouldUpdateProject($file)) {
                // This is a file that will update the project.
                $project = Project::find($file->project_id);
                if (is_null($project)) {
                    return;
                }

                $this->subtractFromCounts($project, $file);
            }
        });
    }

    /**
     * Handle the file "restored" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function restored(File $file)
    {
        //
    }

    /**
     * Handle the file "force deleted" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function forceDeleted(File $file)
    {
        //
    }

    private function fileShouldUpdateProject(File $file)
    {
        if (is_null($file->project_id)) {
            // Not a part of a project so ignore
            return false;
        }

        if ($file->mime_type === 'directory') {
            // current flag is ignored for directories
            return true;
        }

        if (!$file->current) {
            // Nothing to do because this file isn't a part of the project size
            return false;
        }

        return true;
    }

    private function addToCounts(Project $project, File $file)
    {
        if ($file->mime_type === 'directory') {
            $project->directory_count++;
            $project->update(['directory_count' => $project->directory_count]);
        } else {
            $project->size = $project->size + $file->size;
            $project->file_count++;
            $fileTypes = $this->incrementFileType($project, $file);
            $project->update([
                'file_count' => $project->file_count,
                'size'       => $project->size,
                'file_types' => $fileTypes,
            ]);
        }
    }

    private function incrementFileType(Project $project, File $file)
    {
        $fileType = $this->mimeTypeToDescription($file->mime_type);
        $fileTypes = $project->file_types;
        if (!array_key_exists($fileType, $fileTypes)) {
            $fileTypes[$fileType] = 1;
        } else {
            $currentCount = $fileTypes[$fileType];
            $currentCount++;
            $fileTypes[$fileType] = $currentCount;
        }
        return $fileTypes;
    }

    private function subtractFromCounts(Project $project, File $file)
    {
        if ($file->mime_type === 'directory') {
            $project->directory_count--;
            $project->update(['directory_count' => $project->directory_count]);
        } else {
            $project->file_count--;
            $project->size = $project->size - $file->size;
            $fileTypes = $this->decrementFileType($project, $file);
            $project->update([
                'file_count' => $project->file_count,
                'size'       => $project->size,
                'file_types' => $fileTypes,
            ]);
        }
    }

    private function decrementFileType(Project $project, File $file)
    {
        $fileType = $this->mimeTypeToDescription($file->mime_type);
        $fileTypes = $project->file_types;
        if (array_key_exists($fileType, $fileTypes)) {
            $currentCount = $fileTypes[$fileType];
            $currentCount--;
            if ($currentCount == 0) {
                unset($fileTypes[$fileType]);
            } else {
                $fileTypes[$fileType] = $currentCount;
            }
        }
        return $fileTypes;
    }

}
