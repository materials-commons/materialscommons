<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
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
        File::where('directory_id', $this->directory_id)->where('name', $this->name);
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
}
