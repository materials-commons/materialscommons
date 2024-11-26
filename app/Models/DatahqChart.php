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
 * @property integer $datahq_subview_id
 * @property string $name
 * @property string $description
 * @property string $attribute1
 * @property string $attribute1_type
 * @property string $attribute2
 * @property string $attribute2_type
 * @property string $xaxis_name
 * @property string $yaxis_name
 * @property string $chart_type
 * @property mixed $shared_at
 *
 * @mixin Builder
 */
class DatahqChart extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];
    protected $casts = [
        'owner_id'          => 'integer',
        'datahq_subview_id' => 'integer',
        'shared_at'         => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datahqSubview()
    {
        return $this->belongsTo(DatahqSubview::class, 'datahq_subview_id');
    }
}
