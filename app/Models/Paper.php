<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property string $name
 * @property string $doi
 * @property string $url
 * @property string $authors_text
 * @property string $journal
 * @property string $publisher
 * @property mixed $published_at
 *
 * @mixin Builder
 */
class Paper extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    protected $dates = [
        'published_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datasets()
    {
        return $this->morphToMany(Dataset::class, 'item', 'item2dataset');
    }
}
