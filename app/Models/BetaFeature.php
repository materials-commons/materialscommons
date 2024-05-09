<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer id
 * @property string uuid
 * @property string feature
 * @property mixed active_at
 * @property mixed created_at
 * @property mixed updated_at
 * @mixin Builder
 */
class BetaFeature extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'active_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'beta_feature2user', 'beta_feature_id', 'user_id');
    }
}
