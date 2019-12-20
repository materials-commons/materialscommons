<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $affiliations
 * @property string $globus_user
 * @property string $api_token
 *
 * @mixin Builder
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'globus_user', 'description',
        'api_token', 'affiliations',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project2user', 'user_id', 'project_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab2user', 'user_id', 'lab_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    public function communities()
    {
        return $this->hasMany(Community::class, 'owner_id');
    }

    public function globusUploads()
    {
        return $this->hasMany(GlobusUpload::class, 'owner_id');
    }
}
