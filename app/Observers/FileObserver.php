<?php

namespace App\Observers;


use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class FileObserver
{
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

                $project->size = $project->size + $file->size;
                $project->save();
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
                // add file size to project
                $project->size = $project->size + $file->size;
            } else {
                // File was changed to not current, so decrement the size
                $project->size = $project->size - $file->size;
            }

            $project->save();
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

                $project->size = $project->size - $file->size;
                $project->save();
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
        if (!$file->current) {
            // Nothing to do because this file isn't a part of the project size
            return false;
        }

        if (is_null($file->project_id)) {
            // Not a part of a project so ignore
            return false;
        }

        return true;
    }

}
