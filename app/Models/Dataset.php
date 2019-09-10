<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    protected $dates = [
        'published_at',
        'privately_published_at',
    ];

    public function workflows()
    {
        return $this->belongsToMany(Workflow::class, 'dataset2workflow', 'dataset_id', 'workflow_id');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'dataset2entity', 'dataset_id', 'entity_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'dataset2activity', 'dataset_id', 'activity_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'dataset2file', 'dataset_id', 'file_id');
    }
}
