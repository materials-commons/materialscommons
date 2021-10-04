<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property string funding
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
 * @property array ds_authors
 * @property mixed $published_at
 * @property mixed $publish_started_at
 * @property mixed $cleanup_started_at
 * @property string $doi
 * @property integer zipfile_size
 * @property boolean globus_path_exists
 *
 * @mixin Builder
 */
class Dataset extends Model implements Searchable
{
    use HasUUID;
    use HasTags;
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = [
        'published_at',
        'privately_published_at',
        'cleanup_started_at',
        'publish_started_at',
    ];

    protected $casts = [
        'ds_authors'         => 'array',
        'file_selection'     => 'array',
        'owner_id'           => 'integer',
        'project_id'         => 'integer',
        'files_count'        => 'integer',
        'activities_count'   => 'integer',
        'entities_count'     => 'integer',
        'experiments_count'  => 'integer',
        'comments_count'     => 'integer',
        'workflows_count'    => 'integer',
        'zipfile_size'       => 'integer',
        'globus_path_exists' => 'boolean',
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

    public function usedInProjects()
    {
        return $this->belongsToMany(Project::class, 'project2imported_dataset', 'dataset_id', 'project_id');
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

    public function papers()
    {
        return $this->morphedByMany(Paper::class, 'item', 'item2dataset');
    }

    public function entitiesFromTemplate()
    {
        return Entity::with('files.directory')->whereIn('id', function ($query) {
            $query->select('entity_id')
                  ->from('experiment2entity')
                  ->whereIn('experiment_id', function ($query) {
                      $query->select('experiment_id')
                            ->from('item2entity_selection')
                            ->where('item_id', $this->id)
                            ->where('item_type', Dataset::class);
                  });
        })->whereIn('name', function ($query) {
            $query->select('entity_name')
                  ->from('item2entity_selection')
                  ->where('item_id', $this->id)
                  ->where('item_type', Dataset::class)
                  ->whereIn('experiment_id', function ($query) {
                      $query->select('experiment_id')
                            ->from('item2entity_selection')
                            ->where('item_id', $this->id)
                            ->where('item_type', Dataset::class);
                  });
        })->orWhereIn('id', function ($query) {
            $query->select('entity_id')
                  ->from('item2entity_selection')
                  ->where('item_id', $this->id)
                  ->where('item_type', Dataset::class);
        })->get();
    }

    // Scopes

    public function scopeWithCounts($query)
    {
        return $query->withCount('files', 'entities', 'activities', 'experiments', 'comments', 'workflows',
            'communities');
    }

    // Attributes

    public function getTypeAttribute()
    {
        return "dataset";
    }

    public function getTotalFilesSizeAttribute()
    {
        return $this->files()->select('size')->sum('size');
    }

    //

    public function canEdit()
    {
        return $this->owner_id == auth()->user()->id || auth()->user()->is_admin;
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

    public function zipfileSize()
    {
        if (Storage::disk('mcfs')->exists($this->zipfilePathPartial())) {
            return Storage::disk('mcfs')->size($this->zipfilePathPartial());
        }

        return 0;
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
        $dsNameSlug = $this->zipfileName();
        return "zipfiles/{$this->uuid}/{$dsNameSlug}.zip";
    }

    public function zipfileName()
    {
        return Str::slug($this->name);
    }

    public function zipfileDirPartial()
    {
        return "zipfiles/{$this->uuid}";
    }

    public function privateGlobusPathPartial()
    {
        return "__datasets/{$this->uuid}";
    }

    public function isPublished()
    {
        return !is_null($this->published_at);
    }

    public function hasSelectedFiles()
    {
        if (is_null($this->file_selection)) {
            return false;
        }

        if (isset($this->file_selection['include_files']) && sizeof($this->file_selection["include_files"]) !== 0) {
            return true;
        }

        if (isset($this->file_selection['include_dirs']) && sizeof($this->file_selection["include_dirs"]) !== 0) {
            return true;
        }

        return false;
    }

    public function importDirectory()
    {
        $now = now()->toIso8601String();
        return Str::of("importedDataset-{$now}-{$this->name}")
                  ->slug()
                  ->limit(60)
                  ->rtrim('.')
                  ->rtrim('-')
                  ->__toString();
    }

    public function isInCommunity($communityId)
    {
        $this->communities->contains(function (Community $community) use ($communityId) {
            return $community->id == $communityId;
        });
    }

    public function hasFile($fileId)
    {
        return $this->files()->where('file_id', $fileId)->count() != 0;
    }
}
