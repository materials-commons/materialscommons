<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string uuid
 * @property integer project_id
 * @property integer owner_id
 * @property string url
 * @property string title
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class Sheet extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'project_id' => 'integer',
        'owner_id'   => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
