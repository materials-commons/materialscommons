<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function communities()
    {
        return $this->morphedByMany(Community::class, 'item', 'item2link');
    }
}
