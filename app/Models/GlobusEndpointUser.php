<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string uuid
 * @property integer user_id
 * @property integer globus_endpoint_id
 * @property string globus_identity_id
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class GlobusEndpointUser extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'user_id'            => 'integer',
        'globus_endpoint_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function globusEndpoint()
    {
        return $this->belongsTo(GlobusEndpoint::class, 'globus_endpoint_id');
    }

    public function globusPaths()
    {
        $this->hasMany(GlobusEndpointUserPath::class, 'globus_endpoint_user_id');
    }
}
