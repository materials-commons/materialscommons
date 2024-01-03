<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $sharekey
 * @property integer $shareable_id
 * @property string $shareable_type
 * @property mixed $expires_at
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 * @mixin Builder
 */
class Share extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'shareable_id' => 'integer',
        'expires_at'   => 'datetime',
    ];

    public function shareable()
    {
        return $this->morphTo();
    }
}
