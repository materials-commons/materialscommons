<?php

use App\Dataset;
use App\Experiment;
use App\File;
use App\Lab;
use App\Project;
use App\User;
use App\Directory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'name'     => 'MC Admin',
            'email'    => 'admin@admin.org',
            'password' => bcrypt('admin'),
        ]);


        factory(Project::class, 3)->create([
            'owner_id' => $user->id,
        ]);

        $projects = Project::all();

        $p = $projects[0];

        $lab = factory(Lab::class)->create([
            'name'        => 'Default Lab',
            'owner_id'    => $user->id,
            'default_lab' => true,
        ]);

        factory(Experiment::class, 20)->create([
            'owner_id'   => $user->id,
            'project_id' => $p->id,
        ]);

        factory(Dataset::class, 50)->create([
            'owner_id'   => $user->id,
            'project_id' => $p->id,
        ]);

        $root = factory(Directory::class)->create([
            'project_id' => $p->id,
            'name' => '/',
        ]);

        $d2 = factory(Directory::class)->create([
            'project_id' => $p->id,
            'name' => '/d1',
            'parent_id' => $root->id,
        ]);

        factory(File::class, 50)->create([
            'project_id' => $p->id,
            'directory_id' => $d2->id,
        ]);

        $lab->projects()->sync($projects);
        $user->labs()->sync($lab);
        $user->projects()->sync($projects);
    }
}
