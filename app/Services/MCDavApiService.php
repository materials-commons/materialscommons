<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class MCDavApiService
{
    public static function ResetUserState(User $user): bool
    {
        $resp = Http::Post("http://localhost:8556/api/reset-user-state", [
            "email" => $user->email,
        ]);

        return $resp->successful();
    }
}