<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $state
 * @property string $remote_path
 * @property string $transfer_type
 * @property integer $expected_size
 * @property string $expected_checksum
 * @property integer $remote_client_id
 * @property integer $project_id
 * @property integer $owner_id
 * @property integer $file_id
 * @property mixed $last_active_at
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 * @mixin Builder
 */
class RemoteClientTransfer extends Model
{
    /** @use HasFactory<\Database\Factories\RemoteClientTransferFactory> */
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'         => 'integer',
        'project_id'       => 'integer',
        'file_id'          => 'integer',
        'remote_client_id' => 'integer',
        'last_active_at'   => 'datetime',
    ];

    protected $table = 'remote_client_transfers';

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function remoteClient()
    {
        return $this->belongsTo(RemoteClient::class, 'remote_client_id');
    }
}
