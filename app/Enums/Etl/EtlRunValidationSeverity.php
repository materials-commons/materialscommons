<?php

namespace App\Enums\Etl;

enum EtlRunValidationSeverity: string
{
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';

    public function label(): string
    {
        return match ($this) {
            self::Info => 'Info',
            self::Warning => 'Warning',
            self::Error => 'Error',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Info => 'text-bg-info',
            self::Warning => 'text-bg-warning',
            self::Error => 'text-bg-danger',
        };
    }

    public function isBlocking(): bool
    {
        return $this === self::Error;
    }
}
