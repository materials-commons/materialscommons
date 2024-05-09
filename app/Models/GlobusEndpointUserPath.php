<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string uuid
 * @property integer globus_endpoint_user_id
 * @property string globus_path
 * @property string globus_acl_id
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class GlobusEndpointUserPath extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'globu_endpoint_user_id' => 'integer',
    ];

    public function globusEndpointUser()
    {
        return $this->belongsTo(GlobusEndpointUser::class, 'globus_endpoint_user_id');
    }
}
