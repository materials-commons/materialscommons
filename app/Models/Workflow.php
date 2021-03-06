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
 * @property string $name
 * @property integer $owner_id
 * @property integer $project_id
 * @property integer $file_id;
 * @property string $workflow
 * @property string $description
 * @property mixed experiments
 *
 * @mixin Builder
 */
class Workflow extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'project_id' => 'integer',
        'file_id'    => 'integer',
        'owner_id'   => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function experiments()
    {
        return $this->morphedByMany(Experiment::class, 'item', 'item2workflow');
    }

    public function datasets()
    {
        return $this->morphedByMany(Dataset::class, 'item', 'item2workflow');
    }

    public function activities()
    {
        return $this->morphedByMany(Activity::class, 'item', 'item2workflow');
    }

    public function entities()
    {
        return $this->morphedByMany(Entity::class, 'item', 'item2workflow');
    }

    public function getTypeAttribute()
    {
        return "workflow";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.workflows.show', [$this->project_id, $this]);
        return new SearchResult($this, $this->name, $url);
    }
}
