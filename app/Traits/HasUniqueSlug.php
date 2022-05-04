<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function slugify;
use function substr;

trait HasUniqueSlug
{
    // Adds a slug to the project. The slug must be unique so this method loops until its able to create a
    // unique slug for the project.
    private function addUniqueSlugToProject(Project $project)
    {
        $startOfUuid = substr($project->uuid, 0, 4);
        $slugifiedName = Str::limit(slugify($project->name), 15, "");

        if ($slugifiedName == "") {
            $slugifiedName = "p";
        }

        if (Str::endsWith($slugifiedName, "-")) {
            $slugifiedName = substr($slugifiedName, 0, -1);
        }

        $this->addUniqueSlugToItem($project, $slugifiedName, $startOfUuid);
    }

    private function addUniqueSlugToUser(User $user)
    {
        // Replace @ and . (period) with - (dash)
        $fixedEmail = Str::replace("@", "-", Str::replace(".", "-", $user->email));
        $slugifiedName = slugify($fixedEmail);
        $this->addUniqueSlugToItem($user, $slugifiedName, "");
    }

    private function addUniqueSlugToItem($item, $slugifiedName, $uuidPiece)
    {
        if ($uuidPiece == "") {
            $slug = $slugifiedName;
        } else {
            $slug = $slugifiedName."-".$uuidPiece;
        }

        $count = 1;
        $slugToUse = $slug;
        while(true) {
            DB::beginTransaction();
            try {
                $item->update(['slug' => $slugToUse]);
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