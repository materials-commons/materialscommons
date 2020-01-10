<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property string $uuid
 * @property string name
 * @property string description
 * @property integer $project_id
 * @property mixed experiments
 *
 * @mixin Builder
 */
class Entity extends Model implements Searchable
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

    public function getTypeAttribute()
    {
        return "sample";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.entities.show', [$this->project_id, $this]);
        return new SearchResult($this, $this->name, $url);
    }
}
