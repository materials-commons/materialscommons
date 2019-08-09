<?php

use App\Dataset;
use App\Experiment;
use App\File;
use App\Lab;
use App\Project;
use App\Entity;
use App\User;
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
            'name'      => 'MC Admin',
            'email'     => 'admin@admin.org',
            'password'  => bcrypt('admin'),
            'api_token' => 'admintoken',

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

        $root = factory(File::class)->create([
            'project_id' => $p->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $d2 = factory(File::class)->create([
            'project_id'   => $p->id,
            'name'         => 'd1',
            'path'         => '/d1',
            'directory_id' => $root->id,
            'mime_type'    => 'directory',
            'owner_id'     => $user->id,
        ]);

        factory(Entity::class, 20)->create([
            'project_id' => $p->id,
            'owner_id'   => $user->id,
        ]);

        for ($x = 0; $x < 10; $x++) {
            factory(File::class, 15)->create([
                'project_id'   => $p->id,
                'directory_id' => $d2->id,
                'owner_id'     => $user->id,
            ]);
        }

        $lab->projects()->sync($projects);
        $user->labs()->sync($lab);
        $user->projects()->sync($projects);
    }
}
