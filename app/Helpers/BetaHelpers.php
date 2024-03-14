<?php

use App\Models\BetaFeature;

if (!function_exists("isInBeta")) {
    function isInBeta($feature): bool
    {
        $a = auth();
        if (is_null($a)) {
            return false;
        }

        $feature = BetaFeature::with(['users'])
                              ->where('feature', $feature)
                              ->whereNotNull('active_at')
                              ->whereHas('users', function ($query) use ($a) {
                                  $query->where('user_id', $a->id);
                              })
                              ->first();

        return !is_null($feature);
    }
}

