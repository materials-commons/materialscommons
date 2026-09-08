<?php

namespace App\Enums\Etl;

enum EtlRunStatus: string
{
    case Queued = 'queued';
    case Reading = 'reading';
    case Parsing = 'parsing';
    case Validating = 'validating';
    case Processing = 'processing';
    case Finalizing = 'finalizing';
    case Completed = 'completed';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Queued => 'Queued',
            self::Reading => 'Reading spreadsheet',
            self::Parsing => 'Parsing worksheets',
            self::Validating => 'Validating',
            self::Processing => 'Processing',
            self::Finalizing => 'Finalizing',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Queued => 'text-bg-secondary',
            self::Reading,
            self::Parsing,
            self::Validating,
            self::Processing,
            self::Finalizing => 'text-bg-primary',
            self::Completed => 'text-bg-success',
            self::Failed => 'text-bg-danger',
            self::Cancelled => 'text-bg-warning',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::Queued,
            self::Reading,
            self::Parsing,
            self::Validating,
            self::Processing,
            self::Finalizing,
        ], true);
    }

    public function isFinished(): bool
    {
        return in_array($this, [
            self::Completed,
            self::Failed,
            self::Cancelled,
        ], true);
    }

    public static function activeValues(): array
    {
        return array_map(
            fn (self $status) => $status->value,
            [
                self::Queued,
                self::Reading,
                self::Parsing,
                self::Validating,
                self::Processing,
                self::Finalizing,
            ],
        );
    }
}
