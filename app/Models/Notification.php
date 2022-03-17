<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $email
 * @property integer $owner_id
 *
 * @mixin Builder
 */
class Notification extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'      => 'integer',
        'notifyable_id' => 'integer'
    ];

    public function notifyable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
