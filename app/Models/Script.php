<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer id
 * @property string uuid
 * @property string description
 * @property string queue
 * @property string container
 * @property integer script_file_id
 *
 * @mixin Builder
 */
class Script extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'script_file_id' => 'integer',
    ];

    public function scriptFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'script_file_id');
    }
}
