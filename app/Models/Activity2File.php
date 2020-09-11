<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * For batch updating
 * @property integer $id
 * @property integer $activity_id
 * @property integer $file_id
 * @property string direction
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class Activity2File extends Model
{
    protected $table = "activity2file";

}
