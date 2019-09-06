<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasUUID;

    protected $table = 'attribute_values';

    protected $guarded = ['id', 'uuid'];
    protected $casts = [
        'val' => 'array',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    //    public function setValAttribute($value)
    //    {
    //        $this->attributes['val'] = json_encode($value);
    //    }
    //
    //    public function getValAttribute($value)
    //    {
    //        error_log($value);
    //        $v = json_decode($value, true);
    //        error_log($v);
    //        return $v;
    //    }

}
