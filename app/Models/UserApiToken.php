<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $token
 * @property integer $owner_id
 * @property mixed $created_at
 * @property mixed $updated_at
 * @mixin Builder
 */
class UserApiToken extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'owner_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function setTokenAttribute(string $token): void
    {
        $this->attributes['token'] = hash('sha256', $token);
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }
}
