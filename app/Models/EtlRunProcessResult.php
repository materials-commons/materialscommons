<?php

namespace App\Models;

use App\Enums\Etl\EtlRunProcessResultStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EtlRunProcessResult extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'etl_run_id'        => 'integer',
        'sample_count'      => 'integer',
        'input_count'       => 'integer',
        'output_count'      => 'integer',
        'activity_count'    => 'integer',
        'attribute_count'   => 'integer',
        'measurement_count' => 'integer',
        'file_count'        => 'integer',
        'warning_count'     => 'integer',
        'error_count'       => 'integer',
        'status'            => EtlRunProcessResultStatus::class,
        'started_at'        => 'datetime',
        'finished_at'       => 'datetime',
    ];

    public function etlRun(): BelongsTo
    {
        return $this->belongsTo(EtlRun::class);
    }

    public function entities(): HasMany
    {
        return $this->hasMany(EtlRunProcessResultEntity::class);
    }

    public function hasWarnings(): bool
    {
        return $this->warning_count > 0 || $this->status === EtlRunProcessResultStatus::Warning;
    }

    public function hasErrors(): bool
    {
        return $this->error_count > 0 || $this->status === EtlRunProcessResultStatus::Failed;
    }
}
