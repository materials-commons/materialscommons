<?php

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
        factory(User::class)->create([
            'name'      => 'MC Admin',
            'email'     => 'admin@admin.org',
            'password'  => bcrypt('admin'),
            'api_token' => 'admintoken',

        ]);

        factory(User::class)->create([
            'name'      => 'Bob',
            'email'     => 'bob@mc.org',
            'password'  => bcrypt('bob'),
            'api_token' => 'bobtoken',

        ]);

        factory(User::class)->create([
            'name'      => 'Charlie',
            'email'     => 'charlie@mc.org',
            'password'  => bcrypt('charlie'),
            'api_token' => 'charlietoken',

        ]);

//        factory(Project::class, 3)->create([
//            'owner_id' => $user->id,
//        ]);
//
//        $projects = Project::all();
//
//        $p = $projects[0];
//
//        $lab = factory(Lab::class)->create([
//            'name'        => 'Default Lab',
//            'owner_id'    => $user->id,
//            'default_lab' => true,
//        ]);
//
//        $experiments = factory(Experiment::class, 5)->create([
//            'owner_id'   => $user->id,
//            'project_id' => $p->id,
//        ]);
//
//        $workflow = factory(Workflow::class)->create([
//            'owner_id'   => $user->id,
//            'project_id' => $p->id,
//        ]);
//
//        $communities = factory(Community::class, 5)->create();
//
//        $datasets = factory(Dataset::class, 3)->create([
//            'owner_id'   => $user->id,
//            'project_id' => $p->id,
//        ]);
//
//        foreach ($datasets as $dataset) {
//            $comment = factory(Comment::class)->create();
//            $dataset->comments()->save($comment);
//            $comment = factory(Comment::class)->create();
//            $dataset->comments()->save($comment);
//        }
//
//        $communities[0]->datasets()->attach($datasets);
//
//        $activity = factory(Activity::class)->create([
//            'name'       => 'Heat Treatment',
//            'owner_id'   => $user->id,
//            'project_id' => $p->id,
//        ]);
//
//        $activity->workflows()->attach($workflow);
//
//        $attribute = factory(Attribute::class)->create([
//            'name'              => 'Treatment',
//            'attributable_id'   => $activity->id,
//            'attributable_type' => Activity::class,
//        ]);
//
//        factory(AttributeValue::class)->create([
//            'attribute_id' => $attribute->id,
//        ]);
//        factory(AttributeValue::class)->create([
//            'attribute_id' => $attribute->id,
//            'val'          => ['value' => ['temperature' => 4, 'time' => 5]],
//        ]);
//        factory(AttributeValue::class)->create([
//            'attribute_id' => $attribute->id,
//            'val'          => ['value' => ['temperature' => 5, 'time' => 6]],
//        ]);
//
//        $entity = factory(Entity::class)->create([
//            'name'       => 'Sample 1',
//            'owner_id'   => $user->id,
//            'project_id' => $p->id,
//        ]);
//
//        $entity->workflows()->attach($workflow);
//
//        // Attach to all datasets
//        foreach ($datasets as $ds) {
//            $ds->workflows()->attach($workflow);
//            $ds->entities()->attach($entity);
//            $ds->activities()->attach($activity);
//        }
//
//        $root = factory(File::class)->create([
//            'project_id' => $p->id,
//            'name'       => '/',
//            'path'       => '/',
//            'mime_type'  => 'directory',
//            'owner_id'   => $user->id,
//        ]);
//
//        factory(File::class)->create([
//            'project_id' => $projects[1]->id,
//            'name'       => '/',
//            'path'       => '/',
//            'mime_type'  => 'directory',
//            'owner_id'   => $user->id,
//        ]);
//
//        factory(File::class)->create([
//            'project_id' => $projects[2]->id,
//            'name'       => '/',
//            'path'       => '/',
//            'mime_type'  => 'directory',
//            'owner_id'   => $user->id,
//        ]);
//
//        $d2 = factory(File::class)->create([
//            'project_id'   => $p->id,
//            'name'         => 'd1',
//            'path'         => '/d1',
//            'directory_id' => $root->id,
//            'mime_type'    => 'directory',
//            'owner_id'     => $user->id,
//        ]);
//
//        factory(Entity::class, 2)->create([
//            'project_id' => $p->id,
//            'owner_id'   => $user->id,
//        ]);
//
//        $files = factory(File::class, 5)->create([
//            'project_id'   => $p->id,
//            'directory_id' => $d2->id,
//            'owner_id'     => $user->id,
//        ]);
//
//        $datasets[0]->files()->attach($files);
//
//        $entityState = factory(EntityState::class)->create([
//            'entity_id' => $entity->id,
//        ]);
//
//        // Attach to all experiments
//        foreach ($experiments as $exp) {
//            //            $exp->workflows()->attach($workflow);
//            $exp->workflows()->attach(factory(Workflow::class)->create(['owner_id' => $user->id]));
//            $exp->entities()->attach($entity);
//            $exp->activities()->attach($activity);
//            $exp->files()->attach($files[0]);
//        }
//
//        $entityState->files()->attach($files[0]);
//
//        $entity->files()->attach($files[0]);
//        $activity->entities()->attach($entity);
//        $activity->files()->attach($files[0]);
//
//        $lab->projects()->sync($projects);
//        $user->labs()->sync($lab);
//        $user->projects()->sync($projects);
    }
}
