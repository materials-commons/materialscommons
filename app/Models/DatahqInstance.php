<?php

namespace App\Models;

use App\Casts\ExplorerViewStateCast;
use App\Casts\OverviewStateCast;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $current_view
 * @property string $current_context
 * @property mixed $overview_view
 * @property mixed $samples_view
 * @property mixed $processes_view
 * @property mixed $computations_view;
 * @property integer $owner_id
 * @property integer $project_id
 * @property integer $experiment_id
 * @property integer $dataset_id
 *
 * @mixin Builder
 */
class DatahqInstance extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];
    protected $casts = [
        'owner_id'          => 'integer',
        'project_id'        => 'integer',
        'experiment_id'     => 'integer',
        'dataset_id'        => 'integer',
        'overview_view'     => OverviewStateCast::class,
        'samples_view'      => ExplorerViewStateCast::class,
        'computations_view' => ExplorerViewStateCast::class,
        'processes_view'    => ExplorerViewStateCast::class,
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id');
    }

    public function experiment()
    {
        return $this->belongsTo(Experiment::class, 'experiment_id');
    }
}
