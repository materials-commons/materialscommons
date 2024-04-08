<?php

namespace App\Models;

use App\Traits\FileType;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use function intdiv;

/**
 * @property integer $id
 * @property string uuid
 * @property string uses_uuid
 * @property string path
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
        'deleted_at' => 'datetime',
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

    public function previousVersions()
    {
        return $this->hasMany(File::class, 'directory_id', 'directory_id')
                    ->where('name', $this->name)
                    ->where('id', '<>', $this->id)
                    ->whereNull('dataset_id')
                    ->whereNull('deleted_at')
                    ->orderBy('created_at');
    }

    public function currentVersion()
    {
        return File::where('directory_id', $this->directory_id)
                   ->where('name', $this->name)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
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

    // Scopes

    public function scopeWithCommon($query)
    {
        return $query->with('directory')
                     ->withCount(['entityStates', 'activities', 'entities', 'experiments', 'previousVersions']);
    }

    public function fullPath()
    {
        if ($this->isDir()) {
            return $this->path;
        }

        if (!isset($this->directory)) {
            return "/".$this->name;
        }

        if ($this->directory->path == "/") {
            return "/".$this->name;
        }

        return $this->directory->path."/".$this->name;
    }

    public function dirPath()
    {
        if ($this->isDir()) {
            return $this->path;
        }

        return $this->directory->path;
    }

    // Utility methods

    public function toHumanBytes()
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

    public function mcfsPath()
    {
        return Storage::disk('mcfs')->path($this->realPathPartial());
    }

    public function projectPathDirPartial(): string
    {
        $dirGroup = intdiv($this->id, 10);
        return "projects/{$dirGroup}/{$this->project_id}";
    }

    public function mcfsReplicaPath()
    {
        return Storage::disk('mcfs_replica')->path($this->realPathPartial());
    }

    public function partialReplicaPath()
    {
        $realPartial = $this->realPathPartial();
        return "replica/{$realPartial}";
    }

    public function mcfsReplicate()
    {
        $mcfsPathPartial = $this->realPathPartial();
        if (!Storage::disk('mcfs')->exists($mcfsPathPartial)) {
            // The file we are copying should exist, if it doesn't just skip. This case
            // should not happen, but we should check for it.
            return;
        }

        if (!Storage::disk('mcfs')->exists($this->partialReplicaPath())) {
            @Storage::disk('mcfs')->copy($mcfsPathPartial, $this->partialReplicaPath());
            $replicaPath = Storage::disk('mcfs')->path($this->partialReplicaPath());
            @chmod($replicaPath, 0777);
        }

        // If we are here then either the copy was successful, or the copy had already been done. In either case
        // we set replicated_at to denote that the file has been replicated.
        $this->update(['replicated_at' => Carbon::now()]);
    }

    public function realPathPartial()
    {
        $uuid = $this->getFileUuidToUse();
        $dirPath = $this->pathDirPartial();
        return "{$dirPath}/{$uuid}";
    }

    public function realFileExists()
    {
        return Storage::disk('mcfs')->exists($this->realPathPartial());
    }

    public function getFileUuidToUse()
    {
        $uuid = $this->uuid;
        if (!blank($this->uses_uuid)) {
            $uuid = $this->uses_uuid;
        }

        return $uuid;
    }

    public function getFileUsesIdToUse()
    {
        if (!is_null($this->uses_id)) {
            return $this->uses_id;
        }

        return $this->id;
    }

    public function pathDirPartial()
    {
        $uuid = $this->getFileUuidToUse();
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }

    public function convertedPathPartial()
    {
        $fileName = $this->getFileUuidToUse();
        if ($this->isConvertibleImage()) {
            $fileName = $fileName.".jpg";
        }

        if ($this->isConvertibleOfficeDocument()) {
            $fileName = $fileName.".pdf";
        }

        if ($this->isJupyterNotebook()) {
            $fileName = $fileName.".html";
        }

        return $this->pathDirPartial()."/.conversion/{$fileName}";
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
        if (!$this->isConvertible()) {
            return false;
        }

        return !Storage::disk('mcfs')->exists($this->convertedPathPartial());
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

    public function getDirPathForFormatting(): string
    {
        if (is_null($this->path)) {
            return "";
        }

        if ($this->path === "/") {
            return "";
        }

        return $this->path;
    }

    public function getFilePath(): string
    {
        if (!isset($this->directory)) {
            return $this->name;
        }

        if ($this->directory->path == "/") {
            return $this->directory->path.$this->name;
        }

        return $this->directory->path."/".$this->name;
    }

    public function setAsActiveFile()
    {
        $file = $this;

        DB::transaction(function () use ($file) {
            // First mark all files matching name in the directory as not active
            File::where('directory_id', $file->directory_id)
                ->where('name', $file->name)
                ->whereNull('dataset_id')
                ->whereNull('deleted_at')
                ->update(['current' => false]);

            // Then mark the file passed in as active
            $file->update(['current' => true]);
        });
    }

    public static function getDirectoryByPath($projectId, $path)
    {
        return File::where('project_id', $projectId)
                   ->where('path', $path)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
    }

    public static function getAllDirectoriesByPath($projectId, $path)
    {
        return File::where('project_id', $projectId)
                   ->where('path', $path)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->get();
    }

    public static function getTrashForProject($projectId)
    {
        return File::with('directory')
                   ->where('project_id', $projectId)
                   ->where('deleted_at', '>', Carbon::now()->subDays(config('trash.expires_in_days')))
                   ->get();
    }

    public static function getTrashCountForProject($projectId): int
    {
        return File::where('project_id', $projectId)
                   ->where('deleted_at', '>', Carbon::now()->subDays(config('trash.expires_in_days')))
                   ->count();
    }
}
