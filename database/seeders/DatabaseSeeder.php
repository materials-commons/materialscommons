<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name'      => 'MC Admin',
            'email'     => 'admin@admin.org',
            'password'  => bcrypt('admin'),
            'api_token' => 'admintoken',

        ]);

        User::factory()->create([
            'name'      => 'Bob',
            'email'     => 'bob@mc.org',
            'password'  => bcrypt('bob'),
            'api_token' => 'bobtoken',

        ]);

        User::factory()->create([
            'name'      => 'Charlie',
            'email'     => 'charlie@mc.org',
            'password'  => bcrypt('charlie'),
            'api_token' => 'charlietoken',

        ]);
    }
}
