<?php

namespace App\Enums\Etl;

enum EtlRunProcessResultStatus: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Skipped = 'skipped';
    case Warning = 'warning';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Created => 'Created',
            self::Updated => 'Updated',
            self::Skipped => 'Skipped',
            self::Warning => 'Warnings',
            self::Failed => 'Failed',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Created => 'text-bg-success',
            self::Updated => 'text-bg-primary',
            self::Skipped => 'text-bg-secondary',
            self::Warning => 'text-bg-warning',
            self::Failed => 'text-bg-danger',
        };
    }
}
