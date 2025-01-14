<?php

namespace App\Models;

use App\Casts\DataHQ\ExplorerCast;
use App\DTO\DataHQ\Explorer;
use App\DTO\DataHQ\View;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
                                  ->whereNotNull('current_at')
                                  ->where('owner_id', $user->id)
                                  ->first();
        if (is_null($instance)) {
            $e = new Explorer("overview", "samples", collect([
                new View("samples", "", "", "", collect([])),
                new View("computations", "", "", "", collect([])),
                new View("processes", "", "", "", collect([])),
                new View("sampleattrs", "", "", "", collect([])),
                new View("computationattrs", "", "", "", collect([])),
                new View("processattrs", "", "", "", collect([])),
            ]));

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
}
