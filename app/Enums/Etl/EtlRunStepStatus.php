<?php

namespace App\Enums\Etl;

enum EtlRunStepStatus: string
{
    case Waiting = 'waiting';
    case Running = 'running';
    case Completed = 'completed';
    case CompletedWithWarnings = 'completed_with_warnings';
    case Failed = 'failed';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match ($this) {
            self::Waiting => 'Waiting',
            self::Running => 'Running',
            self::Completed => 'Completed',
            self::CompletedWithWarnings => 'Completed with warnings',
            self::Failed => 'Failed',
            self::Skipped => 'Skipped',
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::Waiting => 'fas fa-clock',
            self::Running => 'fas fa-spinner fa-spin',
            self::Completed,
            self::CompletedWithWarnings => 'fas fa-check',
            self::Failed => 'fas fa-times',
            self::Skipped => 'fas fa-minus',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::Waiting => 'secondary',
            self::Running => 'primary',
            self::Completed => 'success',
            self::CompletedWithWarnings => 'warning',
            self::Failed => 'danger',
            self::Skipped => 'secondary',
        };
    }

    public function isFinished(): bool
    {
        return in_array($this, [
            self::Completed,
            self::CompletedWithWarnings,
            self::Failed,
            self::Skipped,
        ], true);
    }
}
