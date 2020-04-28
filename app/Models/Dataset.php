<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Tags\HasTags;

/**
 * @property integer $id
 * @property string name
 * @property string uuid
 * @property string description
 * @property string summary
 * @property string license
 * @property mixed $tags
 * @property integer project_id
 * @property mixed $communities
 * @property mixed $experiments
 * @property integer $owner_id
 * @property mixed owner
 * @property array $file_selection
 * @property mixed entities
 * @property mixed activities
 * @property mixed workflows
 * @property string $globus_acl_id
 * @property string $globus_endpoint_id
 * @property string $globus_path
 * @property string $authors
 * @property mixed $published_at
 * @property string $doi
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

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
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

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function downloads()
    {
        return $this->morphMany(Download::class, 'downloadable');
    }

    public function getTypeAttribute()
    {
        return "dataset";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.datasets.show', [$this->project_id, $this]);
        if (Request::routeIs('public.*')) {
            $url = route('public.datasets.show', [$this]);
        }

        return new SearchResult($this, $this->name, $url);
    }

    public function zipfilePath()
    {
        return Storage::disk('mcfs')->path($this->zipfilePathPartial());
    }

    public function zipfileDir()
    {
        return Storage::disk('mcfs')->path($this->zipfileDirPartial());
    }

    public function publishedGlobusPath()
    {
        return Storage::disk('mcfs')->path($this->publishedGlobusPathPartial());
    }

    public function privateGlobusPath()
    {
        return Storage::disk('mcfs')->path($this->privateGlobusPathPartial());
    }

    public function publishedGlobusPathPartial()
    {
        return "__published_datasets/{$this->uuid}";
    }

    public function zipfilePathPartial()
    {
        $dsNameSlug = Str::slug($this->name);
        return "zipfiles/{$this->uuid}/{$dsNameSlug}.zip";
    }

    public function zipfileDirPartial()
    {
        return "zipfiles/{$this->uuid}";
    }

    public function privateGlobusPathPartial()
    {
        return "__datasets/{$this->uuid}";
    }
}
