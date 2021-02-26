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
 * @property integer transfer_request_id
 * @property string $state
 * @property string globus_acl_id
 * @property string globus_endpoint_id
 * @property string globus_identity_id
 * @property string globus_path
 * @property string globus_url
 * @property string last_globus_transfer_id_completed
 * @property string latest_globus_transfer_completed_date
 *
 * @mixin Builder
 */
class GlobusTransfer extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

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

    public function transferRequest()
    {
        return $this->hasOne(TransferRequest::class, "transfer_request_id");
    }
}
