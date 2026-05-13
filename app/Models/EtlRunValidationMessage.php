<?php

namespace App\Models;

use App\Enums\Etl\EtlRunValidationSeverity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtlRunValidationMessage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'etl_run_id' => 'integer',
        'severity'   => EtlRunValidationSeverity::class,
        'row_number' => 'integer',
        'context'    => 'array',
    ];

    public function etlRun(): BelongsTo
    {
        return $this->belongsTo(EtlRun::class);
    }

    public function isWarning(): bool
    {
        return $this->severity === EtlRunValidationSeverity::Warning;
    }

    public function isError(): bool
    {
        return $this->severity === EtlRunValidationSeverity::Error;
    }
}
