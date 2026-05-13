<?php

namespace App\Enums\Etl;

enum EtlRunLogLevel: string
{
    case Debug = 'debug';
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';

    public function label(): string
    {
        return match ($this) {
            self::Debug => 'DEBUG',
            self::Info => 'INFO',
            self::Success => 'OK',
            self::Warning => 'WARN',
            self::Error => 'ERROR',
        };
    }

    public function cssClass(): string
    {
        return match ($this) {
            self::Debug => 'mc-log-debug',
            self::Info => 'mc-log-info',
            self::Success => 'mc-log-success',
            self::Warning => 'mc-log-warning',
            self::Error => 'mc-log-error',
        };
    }
}
