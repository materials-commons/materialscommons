<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer id
 * @property string uuid
 * @property string name
 * @property string description
 * @property string what
 * @property string when
 * @property integer script_id
 * @property integer project_id
 * @property integer owner_id
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class ScriptTrigger extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'script_id'  => 'integer',
        'project_id' => 'integer',
        'user_id'    => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function script(): BelongsTo
    {
        return $this->belongsTo(Script::class, 'script_id');
    }


}
