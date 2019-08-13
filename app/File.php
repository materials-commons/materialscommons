<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Traits\HasUUID;

    protected $guarded = [];

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
    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action2file')->withTimestamps();
    }
}
