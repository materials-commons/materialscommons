<?php

namespace App\Models;

use App\Enums\Etl\EtlRunStepStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtlRunStep extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'etl_run_id'  => 'integer',
        'status'      => EtlRunStepStatus::class,
        'sort_order'  => 'integer',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function etlRun(): BelongsTo
    {
        return $this->belongsTo(EtlRun::class);
    }

    public function isActive(): bool
    {
        return $this->status === EtlRunStepStatus::Running;
    }

    public function isFinished(): bool
    {
        return $this->status?->isFinished() ?? false;
    }
}
