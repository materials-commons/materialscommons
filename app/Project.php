<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project2user', 'project_id',
            'user_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Labs::class, 'lab2project', 'project_id',
            'lab_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function experiments()
    {
        return $this->hasMany(Experiment::class, 'project_id');
    }

    public function samples()
    {
        return $this->hasMany(Sample::class, 'project_id');
    }

    public function processes()
    {
        return $this->hasMany(Process::class, 'project_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'project_id');
    }
}
