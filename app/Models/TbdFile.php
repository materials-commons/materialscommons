<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $disk
 * @mixin Builder
 */
class TbdFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
