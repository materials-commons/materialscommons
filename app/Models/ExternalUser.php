<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class ExternalUser extends Model
{
    use HasUUID;

    protected $table = "external_users";
    protected $guarded = ['id'];

    public function datasets()
    {
        return $this->morphToMany(Dataset::class, 'item', 'item2dataset');
    }
}
