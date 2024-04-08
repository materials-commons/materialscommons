<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string uuid
 * @property integer script_id
 * @property integer owner_id
 * @property integer project_id
 * @property mixed started_at
 * @property mixed finished_at
 * @property mixed aborted_at
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
        'aborted_at'  => 'datetime',
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
}
