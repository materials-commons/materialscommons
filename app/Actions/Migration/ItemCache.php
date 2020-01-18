<?php

namespace App\Actions\Migration;

use App\Models\Project;
use App\Models\User;

class ItemCache
{
    public static $projects;
    public static $users;

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
}