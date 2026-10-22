<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer id
 * @property string uuid
 * @property string name
 * @property string description
 * @property string globus_acl_id
 * @property string globus_endpoint_id
 * @property string globus_identity_id
 * @property string globus_path
 * @property string globus_url
 * @property array globus_accounts
 * @property integer project_id
 * @property integer owner_id
 * @property integer directory_id
 * @mixin Builder
 */
class GlobusDrop extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'globus_accounts' => 'array',
        'project_id'      => 'integer',
        'owner_id'        => 'integer',
        'directory_id'    => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function directory()
    {
        return $this->belongsTo(File::class, 'directory_id');
    }
}
