<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UHCSSample extends Model
{
    use HasFactory;

    protected $table = "uhcs_samples";

    protected $guarded = ['id'];

    protected $casts = [
        'anneal_time'        => 'float',
        'anneal_temperature' => 'float',
    ];
}
