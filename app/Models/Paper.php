<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property string name
 * @property string $reference
 * @property string $doi
 * @property string $url
 *
 * @mixin Builder
 */
class Paper extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datasets()
    {
        return $this->morphToMany(Dataset::class, 'item', 'item2dataset');
    }
}
