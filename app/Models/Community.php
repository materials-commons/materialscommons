<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property mixed $datasets
 *
 * @mixin Builder
 */
class Community extends Model
{
    use HasUUID;

    protected $guarded = ['id', 'uuid'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2community', 'community_id', 'dataset_id');
    }
}
