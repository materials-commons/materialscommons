<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string uuid
 * @property integer owner_id
 * @property integer project_id
 * @property integer transfer_request_id
 * @property string state
 *
 * @mixin Builder
 */
class MCTransfer extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = "mc_transfers";

    protected $casts = [
        'owner_id'            => 'integer',
        'project_id'          => 'integer',
        'transfer_request_id' => 'integer',
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
        return $this->belongsTo(TransferRequest::class, 'transfer_request_id');
    }
}
