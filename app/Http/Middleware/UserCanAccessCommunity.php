<?php

namespace App\Http\Middleware;

use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Http\Request;

class UserCanAccessCommunity
{
    use GetRequestParameterId;

    /**
     * Make sure user has access to the community.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $communityId = $this->getParameterId('community');
        if ($communityId == '') {
            return $next($request);
        }

        $count = auth()->user()->communities()->where('id', $communityId)->count();
        abort_unless($count == 1, 404, 'No such community');

        return $next($request);
    }
}