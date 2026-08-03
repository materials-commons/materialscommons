<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtlRunProcessResultEntity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'etl_run_process_result_id' => 'integer',
        'entity_id'                 => 'integer',
        'row_number'                => 'integer',
    ];

    public function processResult(): BelongsTo
    {
        return $this->belongsTo(EtlRunProcessResult::class, 'etl_run_process_result_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}
