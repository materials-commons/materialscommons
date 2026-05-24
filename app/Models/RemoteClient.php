<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasUUID;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $client_id
 * @property string $name
 * @property string $hostname
 * @property string $state
 * @property string $type
 * @property string $version
 * @property integer $owner_id
 * @property mixed $last_seen_at
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 * @mixin Builder
 */
class RemoteClient extends Model
{
    /** @use HasFactory<\Database\Factories\RemoteClientFactory> */
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'     => 'integer',
        'last_seen_at' => 'datetime',
    ];

    protected $table = 'remote_clients';

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function remoteClientTransfers()
    {
        return $this->hasMany(RemoteClientTransfer::class, 'remot_client_id');
    }

}
