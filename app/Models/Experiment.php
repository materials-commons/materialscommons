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
}
