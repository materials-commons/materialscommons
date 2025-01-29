<?php

namespace App\Models;

use App\Casts\DataHQ\ExplorerCast;
use App\DTO\DataHQ\Explorer;
use App\DTO\DataHQ\Subview;
use App\DTO\DataHQ\View;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function collect;
use function is_null;
use function now;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property integer $project_id
 * @property integer $experiment_id
 * @property integer $dataset_id
 * @property mixed $current_at
 * @property string $current_explorer
 * @property mixed $overview_explorer_state
 * @property mixed $samples_explorer_state
 * @property mixed $processes_explorer_state
 * @property mixed $computations_explorer_state;
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
        'overview_explorer_state'     => ExplorerCast::class,
        'samples_explorer_state'      => ExplorerCast::class,
        'computations_explorer_state' => ExplorerCast::class,
        'processes_explorer_state'    => ExplorerCast::class,
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
        $instance = DatahqInstance::with(['experiment'])
                                  ->where('project_id', $project->id)
            ->whereNull('experiment_id')
                                  ->where('owner_id', $user->id)
                                  ->first();
        if (is_null($instance)) {
            $e = new Explorer("overview", "samples", collect());

            $instance = DatahqInstance::create([
                'project_id'              => $project->id,
                'owner_id'                => $user->id,
                'current_explorer'        => 'overview',
                'current_at'              => now(),
                'overview_explorer_state' => $e,
            ]);
        }

        return $instance;
    }

    public static function addDefaultSamplesExplorerState(DatahqInstance $instance)
    {
        $instance->update([
            'samples_explorer_state' => self::createDefaultSamplesExplorerState(),
            'current_explorer'       => 'samples',
        ]);
    }

    public static function createDefaultSamplesExplorerState(): Explorer
    {
        return new Explorer("samples", "All Samples", collect([
            new View("All Samples", "", "", "Samples", collect([
                new Subview("Samples", "", null, null),
            ])),
        ]));
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
        $instance = DatahqInstance::with(['experiment'])
                                  ->where('project_id', $experiment->project_id)
                                  ->where('experiment_id', $experiment->id)
                                  ->where('owner_id', $user->id)
                                  ->first();
        if (is_null($instance)) {
            $e = new Explorer("overview", "samples", collect());

            $instance = DatahqInstance::create([
                'project_id'              => $experiment->project->id,
                'experiment_id'           => $experiment->id,
                'owner_id'                => $user->id,
                'current_explorer'        => 'overview',
                'current_at'              => now(),
                'overview_explorer_state' => $e,
            ]);
        }

        return $instance;
    }
}
