<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use Traits\HasUUID;

    public function users()
    {
        return $this->belongsToMany(User::class, 'lab2user', 'lab_id', 'user_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'lab2project', 'lab_id', 'project_id');
    }
}
