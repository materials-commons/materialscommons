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
use JsonSerializable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer id
 * @property string uuid
 * @property string name
 * @property string path
 * @property string url
 * @property string description
 * @property string summary
 * @property integer size
 * @property string checksum
 * @property boolean current
 * @property string mime_type
 * @property string media_type_description
 * @property string disk
 * @property integer owner_id
 * @property integer project_id
 * @property boolean is_shortcut
 * @property integer directory_id;
 * @property string uses_uuid
 * @property integer uses_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed deleted_at
 * @property integer dataset_id
 * @property mixed replicated_at
 * @property integer unique_proj_dir
 * @property string upload_source
 * @property mixed file_missing_at
 * @property string file_missing_determined_by
 * @property string health
 * @property mixed last_health_check_at
 * @property mixed health_fixed_at
 * @property string health_fixed_by
 * @property mixed thumbnail_created_at
 * @property string thumbnail_status
 * @property mixed conversion_created_at
 * @property string conversion_status
 *
 * @mixin Builder
 *
 * Also see App\Observers\FileObserver
 */
class File extends Model implements Searchable, JsonSerializable
{
    use HasUUID;
    use FileType;
    use HasFactory;
    use DeletedAt;

    protected $guarded = ['id'];

    protected $appends = ['selected'];

    protected $casts = [
        'size'                  => 'integer',
        'current'               => 'boolean',
        'owner_id'              => 'integer',
        'project_id'            => 'integer',
        'dataset_id'            => 'integer',
        'is_shortcut'           => 'boolean',
        'directory_id'          => 'integer',
        'experiments_count'     => 'integer',
        'entities_count'        => 'integer',
        'activities_count'      => 'integer',
        'entity_states_count'   => 'integer',
        'deleted_at'            => 'datetime',
        'replicated_at'         => 'datetime',
        'file_missing_at'       => 'datetime',
        'last_health_check_at'  => 'datetime',
        'thumbnail_created_at'  => 'datetime',
        'conversion_created_at' => 'datetime',
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

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'uuid'         => $this->uuid,
            'name'         => $this->name,
            'description'  => $this->description,
            'mime_type'    => $this->mime_type,
            'size'         => $this->size,
            'current'      => $this->current,
            'path'         => $this->path,
            'project_id'   => $this->project_id,
            'directory_id' => $this->directory_id,
            'owner_id'     => $this->owner_id,
            'checksum'     => $this->checksum,
            'created_at'   => $this->created_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
            'deleted_at'   => $this->deleted_at?->toISOString(),
            'dataset_id'   => $this->dataset_id,
            'type'         => $this->getTypeAttribute(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $file = new self();

        // Fill the basic attributes
        $file->fill([
            'uuid'         => $data['uuid'] ?? null,
            'name'         => $data['name'] ?? null,
            'description'  => $data['description'] ?? null,
            'mime_type'    => $data['mime_type'] ?? null,
            'size'         => $data['size'] ?? null,
            'current'      => $data['current'] ?? null,
            'path'         => $data['path'] ?? null,
            'project_id'   => $data['project_id'] ?? null,
            'directory_id' => $data['directory_id'] ?? null,
            'dataset_id'   => $data['dataset_id'] ?? null,
            'owner_id'     => $data['owner_id'] ?? null,
            'checksum'     => $data['checksum'] ?? null,
        ]);

        // Handle timestamps if they exist
        if (isset($data['created_at'])) {
            $file->created_at = Carbon::parse($data['created_at']);
        }
        if (isset($data['updated_at'])) {
            $file->updated_at = Carbon::parse($data['updated_at']);
        }
        if (isset($data['deleted_at'])) {
            $file->deleted_at = Carbon::parse($data['deleted_at']);
        }

        // Set the ID if it exists (for existing records)
        if (isset($data['id'])) {
            $file->id = $data['id'];
            $file->exists = true; // Mark as existing record
        }

        return $file;
    }

//    public static function fromJson(string $value): self
//    {
//        $data = json_decode($value, true);
//
//        if (json_last_error() !== JSON_ERROR_NONE) {
//            throw new \InvalidArgumentException('Invalid JSON: '.json_last_error_msg());
//        }
//
//        return self::fromArray($data);
//    }

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
        if ($this->isDir()) {
            return false;
        }

        if ($this->mime_type == 'url') {
            return false;
        }

        return true;
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
