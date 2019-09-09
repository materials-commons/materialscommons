<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    //
    protected $dates = [
        'published_at',
        'privately_published_at'
    ];

    public function workflows()
    {
        return $this->belongsToMany(Workflow::class, 'dataset2workflow', 'dataset_id', 'workflow_id');
    }
}
