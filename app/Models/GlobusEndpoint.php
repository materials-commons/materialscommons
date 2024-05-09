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
 * @property string notes
 * @property integer owner_id
 * @property string endpoint_id
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class GlobusEndpoint extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
