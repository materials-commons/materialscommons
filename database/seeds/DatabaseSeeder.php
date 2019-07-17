<?php

use App\Experiment;
use App\Lab;
use App\Project;
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
            'name' => 'Epistimi Admin',
            'email' => 'admin@admin.org',
            'password' => bcrypt('admin')
        ]);


        factory(Project::class, 3)->create([
            'owner_id' => $user->id,
        ]);

        $projects = Project::all();

        $p = $projects[0];

        $lab = factory(Lab::class)->create([
            'name' => 'Default Lab',
            'owner_id' => $user->id,
            'default_lab' => true,
        ]);

        factory(Experiment::class, 3)->create([
            'owner_id' => $user->id,
            'project_id' => $p->id,
        ]);

        $lab->projects()->sync($projects);
        $user->labs()->sync($lab);
        $user->projects()->sync($projects);
    }
}