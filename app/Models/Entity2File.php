<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * For batch updating
 * @property integer $id
 * @property integer $entity_id
 * @property integer $file_id
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class Entity2File extends Model
{
    protected $table = "entity2file";

}
