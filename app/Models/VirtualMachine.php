<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 *
 * @mixin Builder
 */
class VirtualMachine extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    public function downloads()
    {
        return $this->morphMany(Download::class, 'downloadable');
    }
}
