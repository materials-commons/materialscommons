<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entityStates()
    {
        return $this->belongsToMany(EntityState::class, 'entity_state2file')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity2file')->withTimestamps();
    }
}
