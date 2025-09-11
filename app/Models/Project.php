<?php

namespace App\Models;

use App\Traits\DeletedAt;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
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
 * @property integer size
 * @property integer file_count
 * @property integer directory_count
 * @property array file_types
 * @property mixed deleted_at
 * @property mixed archived_at
 * @property mixed health_report_last_run_at
 * @property mixed upload_check_needed_at
 * @property mixed health_report_started_at
 * @property mixed last_accessed_at
 *
 * @mixin Builder
 */
class Project extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;
    use DeletedAt;

    protected $guarded = ['id'];

    protected $attributes = [];

    protected $casts = [
        'default_project'           => 'boolean',
        'is_active'                 => 'boolean',
        'owner_id'                  => 'integer',
        'entities_count'            => 'integer',
        'is_public'                 => 'boolean',
        'size'                      => 'integer',
        'file_count'                => 'integer',
        'directory_count'           => 'integer',
        'file_types'                => 'array',
        'deleted_at'                => 'datetime',
        'archived_at'               => 'datetime',
        'health_report_last_run_at' => 'datetime',
        'upload_check_needed_at'    => 'datetime',
        'health_report_started_at'  => 'datetime',
        'last_accessed_at'          => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project2user', 'project_id',
            'user_id');
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, "project_id");
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
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

    public function samples()
    {
        return $this->hasMany(Entity::class, 'project_id')->where('category', 'experimental');
    }

    public function computations()
    {
        return $this->hasMany(Entity::class, 'project_id')->where('category', 'computational');
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
        return $this->hasMany(Dataset::class, 'project_id')
                    ->whereNotNull('published_at');
    }

    public function unpublishedDatasets()
    {
        return $this->hasMany(Dataset::class, 'project_id')
                    ->whereNull('published_at');
    }

    public function onlyFiles()
    {
        return $this->hasMany(File::class, 'project_id')
                    ->where('mime_type', '<>', 'directory');
    }

    public function rootDir()
    {
        return $this->hasOne(File::class, 'project_id')
                    ->whereNull('dataset_id')
                    ->whereNull('deleted_at')
                    ->where('mime_type', 'directory')
                    ->where('path', '/');
    }

    // Attributes

    public function getTypeAttribute()
    {
        return "project";
    }

    public function getTotalFilesSizeAttribute()
    {
        return $this->files()->select('size')->sum('size');
    }

    //

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.show', [$this->id]);
        return new SearchResult($this, $this->name, $url);
    }

    public static function getDeletedTrashCountForUser($userId): int
    {
        return Project::where('owner_id', $userId)
                      ->where('deleted_at', '>', Carbon::now()->subDays(config('trash.expires_in_days')))
                      ->count();
    }

    public static function getDeletedForUser($userId)
    {
        return Project::with('owner', 'rootDir', 'team.members', 'team.admins')
                      ->withCount('entities')
                      ->where('owner_id', $userId)
                      ->where('deleted_at', '>', Carbon::now()->subDays(config('trash.expires_in_days')))
                      ->get();
    }

    // Project Directory
    public function projectFilesDir(): string
    {
        $dirGroup = intdiv($this->id, 10);
        return Storage::disk('mcfs')->path("projects/{$dirGroup}/{$this->id}");
    }
}
