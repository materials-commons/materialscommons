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
        $owner = $this->experimentOwner ?? User::factory()->create();
        $project = $this->project ?? ProjectFactory::create();
        if (!is_null($this->experimentOwner)) {
            $project->team->members()->syncWithoutDetaching($this->experimentOwner);
        }
        $experimentOwner = $this->experimentOwner ?? $project->owner;
        $experiment = Experiment::factory()->create([
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
        $entity = Entity::factory()->create([
            'project_id' => $experiment->project_id,
        ]);
        $es = EntityState::factory()->create([
            'entity_id' => $entity->id,
        ]);

        $attr = Attribute::factory()->create([
            'attributable_type' => EntityState::class,
            'attributable_id'   => $es->id,
        ]);

        AttributeValue::factory()->create([
            'attribute_id' => $attr->id,
        ]);

        $experiment->entities()->attach($entity);

        return $entity;
    }

    public function createActivityForExperiment(Experiment $experiment)
    {
        $activity = Activity::factory()->create([
            'project_id' => $experiment->project_id,
        ]);

        $attr = Attribute::factory()->create([
            'attributable_type' => Activity::class,
            'attributable_id'   => $activity->id,
        ]);

        AttributeValue::factory()->create([
            'attribute_id' => $attr->id,
        ]);

        $experiment->activities()->attach($activity);

        return $activity;
    }
}
