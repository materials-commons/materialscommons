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
        $slugifiedName = Str::limit(slugify($project->name), 15, "");

        // Somebody has a project name with one of more special characters, so make the name
        // "p" rather than ""
        if ($slugifiedName == "") {
            $slugifiedName = "p";
        }

        // If project name ends with a '-' remove it.
        if (Str::endsWith($slugifiedName, "-")) {
            $slugifiedName = substr($slugifiedName, 0, -1);
        }

        $this->addUniqueSlugToItem($project, $slugifiedName, $project->id);
    }

    private function addUniqueSlugToUser(User $user)
    {
        // Extract the username from the email.
        $username = Str::before($user->email, "@");

        $slugifiedName = Str::lower($username);
        $this->addUniqueSlugToItem($user, $slugifiedName, $user->id);
    }

    private function addUniqueSlugToItem($item, $slugifiedName, $id)
    {

        $slug = $slugifiedName;
        $idAdded = false;
        $count = 1;
        $slugToUse = $slug;
        while (true) {
            DB::beginTransaction();
            try {
                $item->update(['slug' => $slugToUse]);
                DB::commit();
                break;
            } catch (\Throwable $e) {
                DB::rollback();
                if (!$idAdded) {
                    // if not unique and the id hasn't been appended then append it.
                    $slugToUse = "{$slug}-{$id}";

                    // Make slug equal to the slug plus the id so that if we start having
                    // to append the count it will be off the slug with the id, and not
                    // just the slug.
                    $slug = $slugToUse;
                    $idAdded = true;
                } else {
                    // if we are here then the slug contains the id, and it's still not
                    // unique so begin appending the count until its unique.
                    $slugToUse = $slug."-{$count}";
                    $count++;
                }
            }
        }
    }
}