<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project2user', 'project_id', 'user_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Labs::class, 'lab2project', 'project_id', 'lab_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
