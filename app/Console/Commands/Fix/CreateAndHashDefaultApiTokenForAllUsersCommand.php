<?php

namespace App\Console\Commands\Fix;

use App\Models\User;
use App\Models\UserApiToken;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('mc-fix:create-and-hash-default-api-token-for-all-users')]
#[Description('Command description')]
class CreateAndHashDefaultApiTokenForAllUsersCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::all()->each(function ($user) {
            $token = $user->api_token;
            if (blank($token)) {
                $token = Str::random(80);
            }
            $user->apiTokens()->create([
                'name' => 'default',
                'token' => hash('sha256', $token),
            ]);
            $user->update(['api_token' => '']);
        });
    }
}
