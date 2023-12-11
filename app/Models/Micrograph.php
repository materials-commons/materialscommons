<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micrograph extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'micron_bar'     => 'float',
        'micron_bar_px'  => 'integer',
        'uhcs_sample_id' => 'integer',
    ];

    public function uhcs_sample()
    {
        return $this->hasOne(UHCSSample::class, 'uhcs_sample_id');
    }
}
