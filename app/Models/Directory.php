<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Directory extends Model
{
    use HasUUID;
    //
    protected $guarded = ['id', 'uuid'];

}
