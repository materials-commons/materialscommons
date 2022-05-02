<?php

namespace App\Traits\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function slugify;
use function substr;

trait AddProjectSlug
{
    // Adds a slug to the project. The slug must be unique so this method loops until its able to create a
    // unique slug for the project.
    private function addSlugToProject(Project $project)
    {
        $startOfUuid = substr($project->uuid, 0, 4);
        $slugifiedName = Str::limit(slugify($project->name), 15, "");

        if ($slugifiedName == "") {
            $slugifiedName = "p";
        }

        if (Str::endsWith($slugifiedName, "-")) {
            $slugifiedName = substr($slugifiedName, 0, -1);
        }

        $slug = $slugifiedName."-".$startOfUuid;

        $count = 1;
        $slugToUse = $slug;
        while(true) {
            DB::beginTransaction();
            try {
                $project->update(['slug' => $slugToUse]);
                DB::commit();
                break;
            } catch (\Throwable $e) {
                DB::rollback();
                $slugToUse = $slug . "-{$count}";
                $count++;
            }
        }
    }
}