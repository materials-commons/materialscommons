<?php

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Lab;
use App\Models\Project;
use App\Models\User;
use App\Models\Workflow;
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

        $experiments = factory(Experiment::class, 20)->create([
            'owner_id'   => $user->id,
            'project_id' => $p->id,
        ]);
        $exp1 = $experiments[0];

        $workflow = factory(Workflow::class)->create(['owner_id' => $user->id]);
        foreach ($experiments as $exp) {
            $exp->workflows()->attach($workflow);
        }

        $datasets = factory(Dataset::class, 50)->create([
            'owner_id'   => $user->id,
            'project_id' => $p->id,
        ]);
        foreach ($datasets as $ds) {
            $ds->workflows()->attach($workflow);
        }

        $activity = factory(Activity::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $p->id,
        ]);

        $attribute = factory(Attribute::class)->create([
            'attributable_id'   => $activity->id,
            'attributable_type' => Activity::class,
        ]);

        factory(AttributeValue::class)->create([
            'attribute_id' => $attribute->id,
        ]);

        $exp1->activities()->attach($activity);

        $entity = factory(Entity::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $p->id,
        ]);

        $exp1->entities()->attach($entity);

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

        for ($x = 0; $x < 1; $x++) {
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
