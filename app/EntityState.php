<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntityState extends Model
{
    use Traits\HasUUID;

    protected $table = 'entity_states';
    protected $guarded = [];
    protected $casts = [
        'current' => 'boolean',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'entity_state2file')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action2entity_state')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
