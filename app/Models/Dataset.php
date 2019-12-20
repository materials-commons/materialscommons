<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property mixed $communities
 * @property mixed $experiments
 * @property integer $owner_id
 * @property array $file_selection
 *
 * @mixin Builder
 */
class Dataset extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

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
}
