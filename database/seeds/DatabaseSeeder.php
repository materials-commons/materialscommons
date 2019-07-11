<?php

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
        $user = factory(\App\User::class)->create([
            'name' => 'Epistimi Admin',
            'email' => 'admin@admin.org',
            'password' => bcrypt('admin')
        ]);


        factory(\App\Project::class, 3)->create([
            'owner_id' => $user->id,
        ]);

        $projects = \App\Project::all();

        $lab = factory(\App\Lab::class)->create([
            'name' => 'Default Lab',
            'owner_id' => $user->id,
            'default_lab' => true,
        ]);

        $lab->projects()->sync($projects);
        $user->labs()->sync($lab);
        $user->projects()->sync($projects);
    }
}