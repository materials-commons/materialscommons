<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getTypeAttribute($value)
    {
        return UserType::getKey((int)$value);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project2user', 'user_id', 'project_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab2user', 'user_id', 'lab_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team2user', 'user_id', 'team_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }
}
