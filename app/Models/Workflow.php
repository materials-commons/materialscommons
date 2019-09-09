<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project2workflow', 'workflow_id', 'project_id');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2workflow', 'workflow_id', 'experiment_id');
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2workflow', 'workflow_id', 'dataset_id');
    }
}
