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
 * @property integer $datahq_tab_id
 * @property string $name
 *
 * @mixin Builder
 */
class DatahqSubView extends Model
{
    use HasFactory;
    use HasUUID;

    protected $table = 'datahq_subviews';
    protected $guarded = ['id'];
    protected $casts = [
        'owner_id'      => 'integer',
        'datahq_tab_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datahqTab()
    {
        return $this->belongsTo(DatahqTab::class, 'datahq_tab_id');
    }

    public function datahqChart()
    {
        return $this->hasOne(DatahqChart::class, 'datahq_subview_id');
    }
}
