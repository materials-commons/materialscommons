<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string uuid
 * @property string name
 * @property string email
 * @property string affiliations
 *
 * @mixin Builder
 */
class Comment extends Model
{
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'       => 'integer',
        'commentable_id' => 'integer',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
