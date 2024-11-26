<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property integer $datahq_instance_id
 * @property integer $project_id
 * @property integer $experiment_id
 * @property integer $dataset_id
 * @property string $view_type
 *
 * @mixin Builder
 */
class DatahqView extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];
    protected $casts = [
        'owner_id'           => 'integer',
        'datahq_instance_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datahqInstance()
    {
        return $this->belongsTo(DatahqInstance::class, 'datahq_instance_id');
    }

    public function datahqTabs()
    {
        return $this->hasMany(DatahqTab::class, 'datahq_view_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function experiment()
    {
        return $this->belongsTo(Experiment::class, 'experiment_id');
    }

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id');
    }
}
