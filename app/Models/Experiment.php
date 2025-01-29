<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Repat\LaravelJobs\Job;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $job_id
 * @property integer $sheet_id
 * @property string name
 * @property integer owner_id
 * @property integer status
 * @property string description
 * @property string summary
 * @property mixed workflows
 * @property mixed etlRuns
 * @property mixed created_at
 * @property mixed updated_at
 * @property string loaded_file_path
 * @property mixed loading_started_at
 * @property mixed loading_finished_at
 *
 * @mixin Builder
 */
class Experiment extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'project_id'          => 'integer',
        'owner_id'            => 'integer',
        'job_id'   => 'integer',
        'sheet_id' => 'integer',
        'loading'             => 'boolean',
        'loading_started_at'  => 'datetime',
        'loading_finished_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function workflows()
    {
        return $this->morphToMany(Workflow::class, 'item', 'item2workflow');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function sheet()
    {
        return $this->belongsTo(Sheet::class, 'sheet_id');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'experiment2entity', 'experiment_id', 'entity_id');
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function experimental_entities()
    {
        return $this->belongsToMany(Entity::class, 'experiment2entity', 'experiment_id', 'entity_id')
                    ->where('category', 'experimental');
    }

    public function computational_entities()
    {
        return $this->belongsToMany(Entity::class, 'experiment2entity', 'experiment_id', 'entity_id')
                    ->where('category', 'computational');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'experiment2activity', 'experiment_id', 'activity_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'experiment2file', 'experiment_id', 'file_id');
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2experiment', 'experiment_id', 'dataset_id');
    }

    public function etlruns()
    {
        return $this->morphMany(EtlRun::class, 'etlable')->orderBy('created_at', 'desc');
    }

    public static function laratablesCustomAction($experiment)
    {
        return '';
    }

    public function getTypeAttribute()
    {
        return "experiment";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.experiments.show', [$this->project_id, $this]);

        return new SearchResult($this, $this->name, $url);
    }
}
