<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property string uuid
 * @property string uses_uuid
 * @property string $name
 * @property string $description
 * @property string $mime_type
 * @property integer $project_id
 * @property integer $directory_id;
 *
 * @mixin Builder
 */
class File extends Model implements Searchable
{
    use HasUUID;

    protected $guarded = ['id'];

    protected $appends = ['selected'];

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
        return File::where('directory_id', $this->directory_id)->where('name', $this->name);
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
        return $this->belongsTo(File::class, 'directory_id');
    }

    public function toHumanBytes()
    {
        return $this->formatBytes($this->size);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    public function isDir()
    {
        if ($this->mime_type == 'directory') {
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
        $url = route('projects.files.show', [$this->project_id, $this]);
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

    public function realPathPartial()
    {
        $uuid = $this->getFileUuidToUse();
        $dirPath = $this->pathDirPartial();
        return "{$dirPath}/{$uuid}";
    }

    public function getFileUuidToUse()
    {
        $uuid = $this->uuid;
        if ($this->uses_uuid !== null) {
            $uuid = $this->uses_uuid;
        }

        return $uuid;
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

        return $this->pathDirPartial()."/.conversion/{$fileName}";
    }

    public function isConvertible()
    {
        if ($this->isConvertibleImage()) {
            return true;
        }

        return $this->isConvertibleOfficeDocument();
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
}
