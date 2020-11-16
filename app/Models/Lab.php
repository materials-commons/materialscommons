<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Lab extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id', 'uuid'];

    protected $casts = [
        'default_lab' => 'boolean',
        'owner_id'    => 'integer',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'lab2user', 'lab_id', 'user_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'lab2project', 'lab_id', 'project_id');
    }
}
