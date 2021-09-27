<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property string uuid
 * @property string name
 * @property string description
 * @property integer $project_id
 * @property mixed experiments
 * @property \Illuminate\Support\Collection $files
 *
 * @mixin Builder
 */
class Activity extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'   => 'integer',
        'project_id' => 'integer',
        'copied_at'  => 'datetime',
    ];

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

    public function files()
    {
        return $this->belongsToMany(File::class, 'activity2file')
                    ->where('current', true)
                    ->withPivot('direction')
                    ->withTimestamps();
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2activity', 'activity_id', 'dataset_id');
    }

    public function entityStates()
    {
        return $this->belongsToMany(EntityState::class, 'activity2entity_state')
                    ->withPivot('direction')
                    ->withTimestamps();
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'activity2entity');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2activity', 'activity_id', 'experiment_id');
    }

    public function workflows()
    {
        return $this->morphToMany(Workflow::class, 'item', 'item2workflow');
    }

    public function etlruns()
    {
        return $this->morphToMany(EtlRun::class, 'item', 'item2activity');
    }

    public function getTypeAttribute()
    {
        return "process";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.activities.show', [$this->project_id, $this]);
        return new SearchResult($this, $this->name, $url);
    }
}
