<?php

namespace App\Models;

use App\Enums\Etl\EtlRunLogLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtlRunLogEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'etl_run_id' => 'integer',
        'level'      => EtlRunLogLevel::class,
        'context'    => 'array',
    ];

    public function etlRun(): BelongsTo
    {
        return $this->belongsTo(EtlRun::class);
    }
}
