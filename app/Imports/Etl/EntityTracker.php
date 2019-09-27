<?php

namespace App\Imports\Etl;

use App\Models\Entity;

class EntityTracker
{
    private $entities;

    public function __construct()
    {
        $this->entities = collect();
    }

    public function hasEntity($entityName)
    {
        return $this->entities->has($entityName);
    }

    public function addEntity(Entity $entity)
    {
        if (!$this->hasEntity($entity->name)) {
            $this->entities->put($entity->name, $entity);
        }
    }

    public function getEntity($entityName)
    {
        return $this->entities->get($entityName);
    }
}