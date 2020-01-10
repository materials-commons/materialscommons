<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property string name
 * @property string description
 * @property integer $project_id
 * @property mixed experiments
 *
 * @mixin Builder
 */
class Activity extends Model implements Searchable
{
    use HasUUID;

    protected $guarded = ['id'];

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
