<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property integer $datahq_instance_id
 * @property string $view_type
 * @property mixed $active_at
 *
 * @mixin Builder
 */
class DatahqView extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];
    protected $casts = [
        'owner_id'           => 'integer',
        'datahq_instance_id' => 'integer',
        'active_at' => 'datetime'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datahqInstance()
    {
        return $this->belongsTo(DatahqInstance::class, 'datahq_instance_id');
    }

    public function datahqTabs()
    {
        return $this->hasMany(DatahqTab::class, 'datahq_view_id');
    }
}
