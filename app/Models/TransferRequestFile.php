<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string uuid
 * @property integer file_id
 * @property integer project_id
 * @property integer owner_id
 * @property integer globus_entry_id
 * @property path string
 *
 * @mixin Builder
 */
class TransferRequestFile extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'        => 'integer',
        'project_id'      => 'integer',
        'globus_entry_id' => 'integer',
        'file_id'         => 'integer',
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
        return $this->belongsTo(TransferRequest::class, "transfer_request_id");
    }

    public function file()
    {
        return $this->belongsTo(File::class, "file_id");
    }

    public function directory()
    {
        return $this->belongsTo(File::class, 'directory_id');
    }
}
