<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $globus_acl_id
 * @property string $globus_endpoint_id
 * @property string $globus_identity_id
 * @property string $globus_path
 * @property string $globus_url
 * @property string $path
 * @property boolean $loading
 * @property boolean $uploading
 * @property integer $project_id
 *
 * @mixin Builder
 */
class GlobusUploadDownload extends Model
{
    use HasUUID;

    protected $table = "globus_uploads_downloads";

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
