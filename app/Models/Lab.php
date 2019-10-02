<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Lab extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'lab2user', 'lab_id', 'user_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'lab2project', 'lab_id', 'project_id');
    }
}
