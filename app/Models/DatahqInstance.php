<?php

namespace App\Models;

use App\Casts\ExplorerStateCast;
use App\Casts\OverviewStateCast;
use App\DTO\DataHQ\OverviewState;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function collect;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $current_explorer
 * @property string $current_context
 * @property mixed $overview_explorer_state
 * @property mixed $samples_explorer_state
 * @property mixed $processes_explorer_state
 * @property mixed $computations_explorer_state;
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
        'owner_id'                    => 'integer',
        'project_id'                  => 'integer',
        'experiment_id'               => 'integer',
        'dataset_id'                  => 'integer',
        'overview_state'              => OverviewStateCast::class,
        'samples_explorer_state'      => ExplorerStateCast::class,
        'computations_explorer_state' => ExplorerStateCast::class,
        'processes_explorer_state'    => ExplorerStateCast::class,
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

    public static function getOrCreateInstanceForProject($project, $user)
    {
        $instance = DatahqInstance::where('project_id', $project->id)
                                  ->where('owner_id', $user->id)
                                  ->first();
        if (is_null($instance)) {
            $instance = DatahqInstance::create([
                'project_id'       => $project->id,
                'owner_id'         => $user->id,
                'current_explorer' => 'overview'
            ]);
        }

        return $instance;
    }

    public static function getOrCreateInstanceForDataset($dataset, $user)
    {
        $instance = DatahqInstance::where('dataset_id', $dataset->id)
                                  ->where('owner_id', $user->id)
                                  ->first();
        if (is_null($instance)) {
            $instance = DatahqInstance::create([
                'dataset_id' => $dataset->id,
                'owner_id'   => $user->id,
            ]);
        }

        return $instance;
    }

    public static function getOrCreateInstanceForExperiment($experiment, $user)
    {
        $instance = DatahqInstance::where('experiment_id', $experiment->id)
                                  ->where('owner_id', $user->id)
                                  ->first();
        if (is_null($instance)) {
            $instance = DatahqInstance::create([
                'experiment_id' => $experiment->id,
                'owner_id'      => $user->id,
            ]);
        }

        return $instance;
    }

    private static function createDefaultOverviewState(
        ?Experiment $experiment = null,
        ?Dataset    $dataset = null
    ): OverviewState {
        if (is_null($experiment)) {
            $experimentsContext = collect();
        } else {
            $experimentsContext = collect(["e-{$experiment->id}" => 'samples']);
        }

        if (is_null($dataset)) {
            $datasetsContext = collect();
        } else {
            $datasetsContext = collect(["d-{$dataset->id}" => 'samples']);
        }

        return new OverviewState("samples", $experimentsContext, $datasetsContext);
    }
}
