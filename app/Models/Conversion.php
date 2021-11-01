<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer file_id
 * @property integer project_id
 * @property integer owner_id
 * @property mixed conversion_started_at
 * @mixin Builder
 */
class Conversion extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [];

    protected $dates = [
        'conversion_started_at',
    ];

    protected $casts = [
        'owner_id'   => 'integer',
        'file_id'    => 'integer',
        'project_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
