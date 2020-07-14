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
 * @property string $name
 * @property string $description
 * @property mixed workflows
 * @property mixed experiments
 * @property integer owner_id
 *
 * @mixin Builder
 */
class Project extends Model implements Searchable
{
    use HasUUID;

    protected $guarded = ['id'];

    protected $attributes = [];

    protected $casts = [
        'default_project' => 'boolean',
        'is_active'       => 'boolean',
        'owner_id'        => 'integer',
        'entities_count'  => 'integer',
        'is_public'       => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project2user', 'project_id',
            'user_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab2project', 'project_id', 'lab_id');
    }

    public function teams()
    {
        return $this->morphToMany(Team::class, 'item2team');
    }

    public function importedDatasets()
    {
        return $this->belongsToMany(Dataset::class, 'project2imported_dataset', 'project_id', 'dataset_id');
    }

    public function workflows()
    {
        return $this->hasMany(Workflow::class, 'project_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function experiments()
    {
        return $this->hasMany(Experiment::class, 'project_id')->orderBy('name');
    }

    public function entities()
    {
        return $this->hasMany(Entity::class, 'project_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'project_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'project_id');
    }

    public function datasets()
    {
        return $this->hasMany(Dataset::class, 'project_id');
    }

    public function publishedDatasets()
    {
        return $this->hasMany(Dataset::class, 'project_id')->whereNotNull('published_at');
    }

    public function rootDir()
    {
        return $this->hasOne(File::class, 'project_id')
                    ->where('path', '/');
    }

    public function getTypeAttribute()
    {
        return "project";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.show', [$this->id]);
        return new SearchResult($this, $this->name, $url);
    }
}
