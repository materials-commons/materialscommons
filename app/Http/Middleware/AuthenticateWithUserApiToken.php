<?php

namespace App\Http\Middleware;

use App\Models\UserApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function abort;
use function blank;

class AuthenticateWithUserApiToken
{
    /**
     * Authenticate requests using user_api_tokens.
     *
     * Expected headers:
     *
     * Authorization: Bearer <plain-text-token>
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $plainTextToken = $request->bearerToken();

        abort_if(blank($plainTextToken), 401, 'Missing API token');

        $tokenHash = UserApiToken::hashToken($plainTextToken);

        $query = UserApiToken::query()
                             ->with('owner')
                             ->where('token', $tokenHash);

        $apiToken = $query->first();

        abort_if(is_null($apiToken), 401, 'Invalid API token');
        abort_if(is_null($apiToken->owner), 401, 'Invalid API token owner');

        Auth::setUser($apiToken->owner);

        return $next($request);
    }
}
