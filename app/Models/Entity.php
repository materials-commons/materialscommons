<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Tags\HasTags;

/**
 * @property integer $id
 * @property string $uuid
 * @property string name
 * @property integer owner_id
 * @property mixed owner
 * @property string description
 * @property integer $project_id
 * @property mixed experiments
 * @property mixed entityStates
 *
 * @mixin Builder
 */
class Entity extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;
    use HasTags;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'   => 'integer',
        'project_id' => 'integer',
        'copied_at'  => 'datetime',
    ];

    public static function activityNamesForEntities($entities)
    {
        $entityIds = $entities->pluck('id')->all();
        return DB::table('activity2entity')
                 ->whereIn('entity_id', $entityIds)
                 ->join('activities', 'activity2entity.activity_id', '=', 'activities.id')
                 ->where('activities.name', '<>', 'Create Samples')
                 ->select('activities.name')
                 ->distinct()
                 ->orderBy('activities.name')
                 ->get();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function entityStates()
    {
        return $this->hasMany(EntityState::class);
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2entity', 'entity_id', 'dataset_id');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2entity', 'entity_id', 'experiment_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'entity2file', 'entity_id', 'file_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity2entity', 'entity_id', 'activity_id');
    }

    public function workflows()
    {
        return $this->morphToMany(Workflow::class, 'item', 'item2workflow');
    }

    public function etlruns()
    {
        return $this->morphedByMany(EtlRun::class, 'item', 'item2entity');
    }

    public function getTypeAttribute()
    {
        return "sample";
    }

    public function getSearchResult(): SearchResult
    {
        if (is_null($this->dataset_id)) {
            $url = route('projects.entities.show', [$this->project_id, $this]);
        } else {
            $url = route('public.datasets.entities.show-spread', [$this->dataset_id, $this]);
        }
        return new SearchResult($this, $this->name, $url);
    }
}
