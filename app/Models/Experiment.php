<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function workflows()
    {
        return $this->belongsToMany(Workflow::class, 'experiment2workflow', 'experiment_id', 'workflow_id');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'experiment2entity', 'experiment_id', 'entity_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'experiment2entity', 'experiment_id', 'activity_id');
    }
}
