<?php

namespace Tests\Factories;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;

class ExperimentFactory
{
    protected $experimentOwner;
    protected $project;
    protected $createEntity = false;
    protected $createActivity = false;

    public function ownedBy($owner)
    {
        $this->experimentOwner = $owner;
        return $this;
    }

    public function inProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function withEntity()
    {
        $this->createEntity = true;
        return $this;
    }

    public function withActivity()
    {
        $this->createActivity = true;
        return $this;
    }

    public function create()
    {
        $owner = $this->experimentOwner ?? factory(User::class)->create();
        $project = $this->project ?? ProjectFactory::create();
        if (!is_null($this->experimentOwner)) {
            $project->team->members()->syncWithoutDetaching($this->experimentOwner);
        }
        $experimentOwner = $this->experimentOwner ?? $project->owner;
        $experiment = factory(Experiment::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $experimentOwner->id,
        ]);

        if ($this->createEntity) {
            $this->createEntityForExperiment($experiment);
        }

        if ($this->createActivity) {
            $this->createActivityForExperiment($experiment);
        }

        return $experiment;
    }

    public function createEntityForExperiment(Experiment $experiment)
    {
        $entity = factory(Entity::class)->create([
            'project_id' => $experiment->project_id,
        ]);
        $es = factory(EntityState::class)->create([
            'entity_id' => $entity->id,
        ]);

        $attr = factory(Attribute::class)->create([
            'attributable_type' => EntityState::class,
            'attributable_id'   => $es->id,
        ]);

        factory(AttributeValue::class)->create([
            'attribute_id' => $attr->id,
        ]);

        $experiment->entities()->attach($entity);

        return $entity;
    }

    public function createActivityForExperiment(Experiment $experiment)
    {
        $activity = factory(Activity::class)->create([
            'project_id' => $experiment->project_id,
        ]);

        $attr = factory(Attribute::class)->create([
            'attributable_type' => Activity::class,
            'attributable_id'   => $activity->id,
        ]);

        factory(AttributeValue::class)->create([
            'attribute_id' => $attr->id,
        ]);

        $experiment->activities()->attach($activity);

        return $activity;
    }
}