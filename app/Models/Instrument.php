<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string uuid
 * @property string name
 * @property string location
 * @property string description
 * @property string notes
 * @property integer owner_id
 * @property integer globus_endpoint_id
 * @property mixed has_picture_at
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class Instrument extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'           => 'integer',
        'globus_endpoint_id' => 'integer',
        'has_picture_at'     => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function globusEndpoint()
    {
        return $this->belongsTo(GlobusEndpoint::class, 'globus_endpoint_id');
    }
}
