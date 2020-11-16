<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string unit
 * @property array $val
 *
 * @mixin Builder
 */
class AttributeValue extends Model
{
    use HasUUID;
    use HasFactory;

    protected $table = 'attribute_values';

    protected $guarded = ['id'];
    protected $casts = [
        'val'          => 'array',
        'attribute_id' => 'integer',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
