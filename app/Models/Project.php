<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 *
 * @mixin Builder
 */
class Project extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    protected $attributes = [];

    protected $casts = [
        'default_project' => 'boolean',
        'is_active'       => 'boolean',
        'owner_id'        => 'integer',
    ];

    public function setDescriptionAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['description'] = '';
        } else {
            $this->attributes['description'] = $value;
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project2user', 'project_id',
            'user_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab2project', 'project_id', 'lab_id');
    }

    public function workflows()
    {
        return $this->hasMany(Workflow::class, 'project_id');
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
        return $this->hasMany(Experiment::class, 'project_id')->orderBy('name');
    }

    public function entities()
    {
        return $this->hasMany(Entity::class, 'project_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'project_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'project_id');
    }
}
