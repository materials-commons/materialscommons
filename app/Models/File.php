<?php

namespace App\Models;

use App\Services\FileServices\FileConversionService;
use App\Services\FileServices\FilePathService;
use App\Services\FileServices\FileReplicationService;
use App\Services\FileServices\FileStorageService;
use App\Services\FileServices\FileThumbnailService;
use App\Services\FileServices\FileVersioningService;
use App\Traits\DeletedAt;
use App\Traits\FileType;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property string uuid
 * @property string uses_uuid
 * @property string path
 * @property string url
 * @property string $name
 * @property string $description
 * @property string $mime_type
 * @property integer $project_id
 * @property integer $directory_id;
 * @property integer owner_id
 * @property integer size
 * @property boolean current
 * @property string checksum
 * @property string $disk
 *
 * @mixin Builder
 *
 * Also see App\Observers\FileObserver
 */
class File extends Model implements Searchable
{
    use HasUUID;
    use FileType;
    use HasFactory;
    use DeletedAt;

    protected $guarded = ['id'];

    protected $appends = ['selected'];

    protected $casts = [
        'size'                => 'integer',
        'current'             => 'boolean',
        'owner_id'            => 'integer',
        'project_id'          => 'integer',
        'is_shortcut'         => 'boolean',
        'directory_id'        => 'integer',
        'experiments_count'   => 'integer',
        'entities_count'      => 'integer',
        'activities_count'    => 'integer',
        'entity_states_count' => 'integer',
        'deleted_at'          => 'datetime',
    ];

    private $selected;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function entityStates()
    {
        return $this->belongsToMany(EntityState::class, 'entity_state2file')->withTimestamps();
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity2file')->withTimestamps();
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'entity2file');
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2file', 'file_id', 'dataset_id');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2file', 'file_id', 'experiment_id');
    }

    public function directory()
    {
        return $this->belongsTo(File::class, 'directory_id')->where('current', true);
    }

    public function communities()
    {
        return $this->morphedByMany(Community::class, 'item', 'item2file');
    }

    public function etlruns()
    {
        return $this->morphedByMany(EtlRun::class, 'item', 'item2file');
    }

    public function previousVersions()
    {
        return $this->hasMany(File::class, 'directory_id', 'directory_id')
                    ->where('name', $this->name)
                    ->where('id', '<>', $this->id)
                    ->whereNull('dataset_id')
                    ->whereNull('deleted_at')
                    ->orderBy('created_at');
    }

    // Scopes

    public function scopeWithCommon($query)
    {
        return $query->with('directory')
                     ->withCount(['entityStates', 'activities', 'entities', 'experiments', 'previousVersions']);
    }

    public function scopeCurrentProjectFiles($query, $projectId)
    {
        return $query->where('project_id', $projectId)
                     ->whereNull('dataset_id')
                     ->whereNull('deleted_at')
                     ->where('current', true);
    }

    public function scopeActiveFiles($query)
    {
        return $query->where('current', true)
                     ->whereNull('dataset_id')
                     ->whereNull('deleted_at');
    }

    public function scopeActive($query)
    {
        return $query->where('current', true)
                     ->whereNull('dataset_id')
                     ->whereNull('deleted_at');
    }

    public function scopeDirectories($query)
    {
        return $query->where('mime_type', 'directory');
    }

    public function scopeActiveProjectDirectories($query, $projectId)
    {
        return $query->activeFiles()
                     ->where('project_id', $projectId)
                     ->directories();
    }

    public function scopeFiles($query)
    {
        return $query->where('mime_type', '<>', 'directory');
    }

    public function currentVersion()
    {
        return File::where('directory_id', $this->directory_id)
                   ->where('name', $this->name)
                   ->active()
                   ->first();
    }

    /* FilePathService */
    public function fullPath(): ?string
    {
        return app(FilePathService::class)->getFullPath($this);
    }

    public function dirPath(): ?string
    {
        return app(FilePathService::class)->getDirPath($this);
    }

    public function getFilePath(): string
    {
        return app(FilePathService::class)->getFilePath($this);
    }

    public function mcfsPath()
    {
        return app(FilePathService::class)->getMcfsPath($this);
    }

    public function mcfsReplicaPath()
    {
        return app(FilePathService::class)->getMcfsReplicaPath($this);
    }

    public function realPathPartial()
    {
        return app(FilePathService::class)->getRealPathPartial($this);
    }

    public function pathDirPartial()
    {
        return app(FilePathService::class)->getPathDirPartial($this);
    }

    public function convertedPathPartial()
    {
        return app(FilePathService::class)->getConvertedPathPartial($this);
    }

    public function thumbnailPathPartial()
    {
        return app(FilePathService::class)->getThumbnailPathPartial($this);
    }

    public function projectPathDirPartial(): string
    {
        return app(FilePathService::class)->getProjectPathDirPartial($this);
    }

    public function partialReplicaPath()
    {
        return app(FilePathService::class)->getPartialReplicaPath($this);
    }

    public function getDirPathForFormatting(): string
    {
        return app(FilePathService::class)->getDirPathForFormatting($this);
    }

    public function getFileUuidToUse()
    {
        return app(FilePathService::class)->getFileUuidToUse($this);
    }

    /* FilePathService */

    /* fhere */

    // Utility methods

    public function toHumanBytes(): string
    {
        return formatBytes($this->size);
    }

    public function isDir()
    {
        if ($this->mime_type == 'directory') {
            return true;
        }

        return false;
    }

    public function isFile()
    {
        return !$this->isDir();
    }

    public function isRunnable(): bool
    {
        if (is_null($this->directory)) {
            $this->load('directory');
        }

        if ($this->directory->path != "/scripts") {
            return false;
        }

        if (Str::endsWith($this->name, ".py")) {
            return true;
        }

        return false;
    }

    public function getSelectedAttribute()
    {
        return $this->selected;
    }

    public function setSelectedAttribute($selected)
    {
        $this->selected = $selected;
    }

    public function getTypeAttribute()
    {
        if ($this->mime_type == "directory") {
            return "directory";
        }

        return "file";
    }

    public function getSearchResult(): SearchResult
    {
        if (is_null($this->dataset_id)) {
            if ($this->mime_type == 'directory') {
                $url = route('projects.folders.show', [$this->project_id, $this]);
            } else {
                $url = route('projects.files.show', [$this->project_id, $this]);
            }
        } else {
            if ($this->mime_type == 'directory') {
                $url = route('public.datasets.folders.show', [$this->dataset_id, $this]);
            } else {
                $url = route('public.datasets.files.show', [$this->dataset_id, $this]);
            }
        }
        return new SearchResult($this, $this->name, $url);
    }

    public static function laratablesCustomAction($file)
    {
        return '';
    }

    public function mcfsReplicate()
    {
        app(FileReplicationService::class)->replicate($this);
    }


    public function realFileExists()
    {
        $pathService = app(FilePathService::class);
        $storage = app(FileStorageService::class);
        return $storage->exists('mcfs', $pathService->getRealPathPartial($this));
    }

    public function getFileUsesIdToUse()
    {
        if (!is_null($this->uses_id)) {
            return $this->uses_id;
        }

        return $this->id;
    }

    public function thumbnailExists()
    {
        $pathService = app(FilePathService::class);
        $storage = app(FileStorageService::class);
        return $storage->exists('mcfs', $pathService->getThumbnailPathPartial($this));
    }

    public function isConvertible()
    {
        if ($this->isConvertibleImage()) {
            return true;
        }

        if ($this->isConvertibleOfficeDocument()) {
            return true;
        }

        return $this->isJupyterNotebook();
    }

    public function isImage()
    {
        return $this->fileType($this) === "image";
    }

    public function shouldBeConverted()
    {
        return app(FileConversionService::class)->shouldConvert($this);
    }

    public function shouldGenerateThumbnail()
    {
        return app(FileThumbnailService::class)->shouldGenerate($this);
    }

    public function isConvertibleImage()
    {
        switch ($this->mime_type) {
            case 'image/bmp':
            case 'image/x-ms-bmp':
            case 'image/tiff':
                return true;
            default:
                return false;
        }
    }

    public function isConvertibleOfficeDocument()
    {
        switch ($this->mime_type) {
            case "application/vnd.ms-powerpoint":
            case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
            case "application/msword":
            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                return true;
            default:
                return false;
        }
    }

    public function isJupyterNotebook()
    {
        if ($this->mime_type !== 'directory' && Str::endsWith($this->name, ".ipynb")) {
            return true;
        }

        return false;
    }

    public function setAsActiveFile()
    {
        app(FileVersioningService::class)->setActive($this);
    }

    public static function getDirectoryByPath($projectId, $path)
    {
        return File::where('project_id', $projectId)
                   ->where('path', $path)
                   ->directories()
                   ->active()
                   ->first();
    }

    public static function getAllDirectoriesByPath($projectId, $path)
    {
        return File::where('project_id', $projectId)
                   ->where('path', $path)
                   ->directories()
                   ->active()
                   ->get();
    }

    public static function getTrashForProject($projectId)
    {
        return File::with('directory')
                   ->where('project_id', $projectId)
                   ->where('current', true)
                   ->whereNull('dataset_id')
                   ->where('deleted_at', '>', Carbon::now()->subDays(config('trash.expires_in_days')))
                   ->get();
    }

    public static function getTrashCountForProject($projectId): int
    {
        return File::where('project_id', $projectId)
                   ->where('current', true)
                   ->whereNull('dataset_id')
                   ->where('deleted_at', '>', Carbon::now()->subDays(config('trash.expires_in_days')))
                   ->count();
    }
}
