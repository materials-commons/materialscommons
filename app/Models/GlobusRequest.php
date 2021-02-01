<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property integer $project_id
 * @property string $globus_acl_id
 * @property string $globus_endpoint_id
 * @property string $globus_identity_id
 * @property string $globus_path
 * @property string $globus_url
 *
 * @mixin Builder
 */
class GlobusRequest extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['last_active_at'];

    protected $casts = [
        'owner_id'   => 'integer',
        'project_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function globusRequestFiles()
    {
        return $this->hasMany(GlobusRequestFile::class, "globus_request_id");
    }
}
