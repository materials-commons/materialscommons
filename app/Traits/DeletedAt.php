<?php

namespace App\Traits;

trait DeletedAt
{
    public function expiresInDays(): int
    {
        $now = now()->startOfDay();
        $expiresInDays = config("trash.expires_in_days");
        return (int) $this->deleted_at->startOfDay()->addDays($expiresInDays)->diffInDays($now, true) + 1;
    }
}