<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntityState extends Model
{
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
        return $this->belongsToMany(File::class, 'entity_state2file');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action2entity_state');
    }
}
