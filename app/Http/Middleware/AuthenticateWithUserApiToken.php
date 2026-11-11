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
     * X-API-Key: <token-name>
     *
     * X-API-Key is optional. If it is not supplied, authentication falls back to
     * lookup by token hash only.
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
        $tokenName = $request->header('X-API-Key') ?? 'default';

        $query = UserApiToken::query()
                             ->with('owner')
                             ->where('name', $tokenName)
                             ->where('token', $tokenHash);

        $apiToken = $query->first();

        abort_if(is_null($apiToken), 401, 'Invalid API token');
        abort_if(is_null($apiToken->owner), 401, 'Invalid API token owner');

        Auth::setUser($apiToken->owner);

        return $next($request);
    }
}
