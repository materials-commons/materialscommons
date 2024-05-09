<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property integer id
 * @property string uuid
 * @property string docker_container_id
 * @property integer script_id
 * @property integer owner_id
 * @property integer project_id
 * @property mixed started_at
 * @property mixed finished_at
 * @property mixed failed_at
 *
 * @mixin Builder
 */
class ScriptRun extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'script_id'   => 'integer',
        'owner_id'    => 'integer',
        'project_id'  => 'integer',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function script()
    {
        return $this->belongsTo(Script::class, 'script_id');
    }

    public function logPath(): string
    {
        return Storage::disk('mcfs')->path($this->partialLogPath());
    }

    public function deleteLog(): bool
    {
        return Storage::disk('mcfs')->delete($this->partialLogPath());
    }

    public function getLogContents(): ?string
    {
        return Storage::disk('mcfs')->get($this->partialLogPath());
    }

    public function partialLogPath(): string
    {
        return "__run_script_logs/{$this->uuid}.log";
    }
}
