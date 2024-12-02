<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function is_null;
use function now;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property mixed $active_at
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
        'active_at'     => 'datetime',
        'owner_id'      => 'integer',
        'project_id'    => 'integer',
        'experiment_id' => 'integer',
        'dataset_id'    => 'integer'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datahqViews()
    {
        return $this->hasMany(DatahqView::class, 'datahq_instance_id');
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

    public function isActive()
    {
        return !is_null($this->last_active);
    }

    public static function getOrCreateActiveDatahqInstanceForUser($user, $project, $experiment = null)
    {
        $q = self::query()
                 ->where('owner_id', $user->id)
                 ->where('project_id', $project->id);

        if (is_null($experiment)) {
            $q->whereNull('experiment_id');
        } else {
            $q->where('experiment_id', $experiment->id);
        }

        $instance = $q->whereNotNull('active_at')->first();
        if (!is_null($instance)) {
            return $instance;
        } else {
            $instance = DatahqInstance::create([
                'owner_id'      => $user->id,
                'project_id'    => $project->id,
                'experiment_id' => is_null($experiment) ? null : $experiment->id,
                'active_at'     => now()
            ]);

            DatahqView::create([
                'datahq_instance_id' => $instance->id,
                'owner_id'           => $user->id,
                'view_type'          => 'overview',
                'active_at'          => now(),
            ]);
        }

        $instance->load('datahqViews');

        return $instance;
    }

    public function currentDatahqView()
    {
        return $this->datahqViews->firstWhere('active_at', '!=', null);
    }

    public function getOrCreateDatahqView($viewType)
    {
        $view = $this->datahqViews->firstWhere('view_type', $viewType);
        if (!is_null($view)) {
            if (is_null($view->active_at)) {
                // Make other views null, and mark this view as active
                DatahqView::where('datahq_instance_id', $this->id)->update(['active_at' => null]);
                $view->active_at = now();
                $view->save();

                // Reload our instance and relations
                $this->refresh();
                $this->load('datahqViews');
            }
            return $view;
        }
        // No view of that type exists. First mark other views as inactive, then create
        // a new view of the given type and mark it as active.
        DatahqView::where('datahq_instance_id', $this->id)->update(['active_at' => null]);

        $view = DatahqView::create([
            'datahq_instance_id' => $this->id,
            'owner_id'           => $this->user_id,
            'view_type'          => $viewType,
            'active_at'          => now(),
        ]);

        // Reload our instance and relations
        $this->refresh();
        $this->load('datahqViews');
        return $view;
    }
}
