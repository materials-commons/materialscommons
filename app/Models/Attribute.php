<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string name
 * @property string description
 *
 * @mixin Builder
 */
class Attribute extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['marked_important_at'];

    protected $casts = [
        'attributable_id' => 'integer',
        'best_value_id'   => 'integer',
    ];

    public function attributable()
    {
        return $this->morphTo();
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function bestValue()
    {
        return $this->hasOne(AttributeValue::class, 'attribute_id', 'best_value_id');
    }

    public function etlruns()
    {
        return $this->morphedByMany(EtlRun::class, 'item', 'item2attribute');
    }
}
