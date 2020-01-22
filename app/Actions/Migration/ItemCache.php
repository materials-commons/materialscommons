<?php

namespace App\Actions\Migration;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\User;

class ItemCache
{
    public static $projects = [];
    public static $users = [];
    public static $activities = [];
    public static $entities = [];
    public static $entityStates = [];
    public static $experiments = [];

    public static function loadProjects()
    {
        Project::orderBy('id')->chunk(1000, function ($projects) {
            foreach ($projects as $project) {
                ItemCache::$projects[$project->uuid] = $project;
            }
        });
    }

    public static function findProject($uuid)
    {
        if (isset(ItemCache::$projects[$uuid])) {
            return ItemCache::$projects[$uuid];
        }

        return null;
    }

    public static function loadUsers()
    {
        User::orderBy('id')->chunk(1000, function ($users) {
            foreach ($users as $user) {
                ItemCache::$users[$user->email] = $user;
            }
        });
    }

    public static function findUser($email)
    {
        if (isset(ItemCache::$users[$email])) {
            return ItemCache::$users[$email];
        }

        return null;
    }

    public static function loadActivities()
    {
        Activity::orderBy('id')->chunk(1000, function ($activities) {
            foreach ($activities as $activity) {
                ItemCache::$activities[$activity->uuid] = $activity;
            }
        });
    }

    public static function findActivity($uuid)
    {
        if (isset(ItemCache::$activities[$uuid])) {
            return ItemCache::$activities[$uuid];
        }

        return null;
    }

    public static function loadEntities()
    {
        Entity::orderBy('id')->chunk(1000, function ($entities) {
            foreach ($entities as $entity) {
                ItemCache::$entities[$entity->uuid] = $entity;
            }
        });
    }

    public static function findEntity($uuid)
    {
        if (isset(ItemCache::$entities[$uuid])) {
            return ItemCache::$entities[$uuid];
        }

        return null;
    }

    public static function loadEntityStates()
    {
        EntityState::orderBy('id')->chunk(1000, function ($entityStates) {
            foreach ($entityStates as $entityState) {
                ItemCache::$entityStates[$entityState->uuid] = $entityState;
            }
        });
    }

    public static function findEntityState($uuid)
    {
        if (isset(ItemCache::$entityStates[$uuid])) {
            return ItemCache::$entityStates[$uuid];
        }

        return null;
    }

    public static function loadExperiments()
    {
        Experiment::orderBy('id')->chunk(1000, function ($experiments) {
            foreach ($experiments as $experiment) {
                ItemCache::$experiments[$experiment->uuid] = $experiment;
            }
        });
    }

    public static function findExperiment($uuid)
    {
        if (isset(ItemCache::$experiments[$uuid])) {
            return ItemCache::$experiments[$uuid];
        }

        return null;
    }

    public static function loadItemsFromMultiple($itemsList, $key, $getFunc)
    {
        $itemsToReturn = [];
        if (isset($itemsList[$key])) {
            $itemIds = $itemsList[$key];
            foreach ($itemIds as $itemId) {
                $item = $getFunc($itemId);
                if ($item != null) {
                    array_push($itemsToReturn, $item);
                }
            }
        }

        return $itemsToReturn;
    }
}