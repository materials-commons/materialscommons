<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Tags\HasTags;

/**
 * @property integer $id
 * @property string name
 * @property string uuid
 * @property string description
 * @property integer project_id
 * @property mixed $communities
 * @property mixed $experiments
 * @property integer $owner_id
 * @property array $file_selection
 * @property mixed entities
 * @property mixed activities
 * @property mixed workflows
 *
 * @mixin Builder
 */
class Dataset extends Model implements Searchable
{
    use HasUUID;
    use HasTags;

    protected $guarded = ['id'];

    protected $dates = [
        'published_at',
        'privately_published_at',
    ];

    protected $casts = [
        'file_selection' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function workflows()
    {
        return $this->morphToMany(Workflow::class, 'item', 'item2workflow');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'dataset2entity', 'dataset_id', 'entity_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'dataset2activity', 'dataset_id', 'activity_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'dataset2file', 'dataset_id', 'file_id');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'dataset2experiment', 'dataset_id', 'experiment_id');
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'dataset2community', 'dataset_id', 'community_id');
    }

    public function publishedCommunities()
    {
        return $this->belongsToMany(Community::class, 'dataset2community', 'dataset_id', 'community_id')
                    ->where('public', true);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getTypeAttribute()
    {
        return "dataset";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.datasets.show', [$this->project_id, $this]);
        return new SearchResult($this, $this->name, $url);
    }
}
