<?php

namespace App\ViewModels\Export;

//use Illuminate\Database\Eloquent\Collection;
use App\Models\Activity;
use App\Models\Attribute;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\ViewModels\ViewModel;
use function blank;
use function collect;
use function dump;
use function is_null;

class ActivityExportViewModel extends ViewModel
{

    private $activityName;
    private Collection $activities;
    private $activityAttributes = [];
    private $entityAttributes = [];

    private $entities = [];

    private $entity2Activity = [];

    private int $longestEntityNameLen = 0;

    public function __construct($activities, $activityName)
    {
        $this->activityName = $activityName;
        $this->activities = $activities;
        $this->activityAttributes = $this->getActivityAttributes();
        $this->entityAttributes = $this->getEntityAttributes();
        $this->loadUniqueEntityNames();
        $this->loadEntity2Activity();
    }

    public function activities(): Collection
    {
        return $this->activities;
    }

    public function activityAttributes()
    {
        return $this->activityAttributes;
    }

    public function entityAttributes()
    {
        return $this->entityAttributes;
    }

    public function entities()
    {
        return $this->entities;
    }

    public function longestEntityNameLen(): int
    {
        return $this->longestEntityNameLen;
    }

    public function entity2Activity()
    {
        return $this->entity2Activity;
    }

    public function getActivityAttributeValueForEntity($entityName, $attrName)
    {
        $activity = $this->entity2Activity[$entityName];
        foreach ($activity->attributes as $attr) {
            if ($attr->name === $attrName) {
                return $attr->values[0]->val["value"];
            }
        }

        return "";
    }

    public function getEntityAttributeValue($entityName, $attrName)
    {
        $activity = $this->entity2Activity[$entityName];
        foreach ($activity->entityStates as $entityState) {
            if ($entityState->pivot->direction !== "out") {
                continue;
            }
            foreach ($entityState->attributes as $attr) {
                if ($attr->name === $attrName) {
                    $val = $attr->values[0]->val["value"];
                    return Str::replace("=", "", $val);
//                    return $attr->values[0]->val["value"];
                }
            }
        }

        return "";
    }

    private function getActivityAttributes()
    {
        $attrs = [];
        $this->activities->each(function ($activity) use (&$attrs) {
            $activity->attributes->each(function ($attribute) use (&$attrs) {
                if ($attribute->values->isNotEmpty()) {
                    $attrs[$attribute->name] = $attribute->values[0];
                } else {
                    $attrs[$attribute->name] = null;
                }
            });
        });
        return $attrs;
    }

    private function getEntityAttributes()
    {
        $attrs = [];
        $this->activities->filter(function ($activity) use (&$attrs) {
            return !is_null($activity->entityStates);
        })->each(function ($activity) use (&$attrs) {
            $activity->entityStates->filter(function ($entityState) use (&$attrs) {
                return $entityState->pivot->direction == "out";
            })->each(function ($entityState) use (&$attrs) {
                $entityState->attributes->each(function ($attribute) use (&$attrs) {
                    if ($attribute->values->isNotEmpty()) {
                        $attrs[$attribute->name] = $attribute->values[0];
                    } else {
                        $attrs[$attribute->name] = null;
                    }
                });
            });
        });

        return $attrs;
    }

    private function loadUniqueEntityNames()
    {
        $this->activities->filter(function ($activity) {
            return !is_null($activity->entityStates);
        })->each(function ($activity) {
            $activity->entityStates->filter(function ($entityState) {
                return $entityState->pivot->direction == "in";
            })->each(function ($entityState) {
                $len = strlen($entityState->entity->name);
                if ($this->longestEntityNameLen < $len) {
                    $this->longestEntityNameLen = $len;
                }
                $this->entities[$entityState->entity->name] = $entityState->entity->name;
            });
        });
    }

    private function loadEntity2Activity()
    {
        foreach ($this->activities as $activity) {
            foreach ($activity->entityStates as $entityState) {
                if (is_null($entityState->entity)) {
                    continue;
                }

                if (!blank($entityState->entity->name)) {
                    $this->entity2Activity[$entityState->entity->name] = $activity;
                    break;
                }
            }
        }
    }
}
