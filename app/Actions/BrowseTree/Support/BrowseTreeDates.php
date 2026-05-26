<?php

namespace App\Actions\BrowseTree\Support;

class BrowseTreeDates
{
    public static function bucket($date): string
    {
        if ($date === null) {
            return 'this-year';
        }

        if ($date->isToday()) {
            return 'today';
        }

        if ($date->greaterThanOrEqualTo(now()->subDays(7))) {
            return 'last-7-days';
        }

        if ($date->greaterThanOrEqualTo(now()->subDays(30))) {
            return 'last-30-days';
        }

        return 'this-year';
    }

    public static function label($date): string
    {
        if ($date === null) {
            return 'Date unavailable';
        }

        return 'Updated '.$date->diffForHumans();
    }
}
