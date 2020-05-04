<?php

namespace App\Http\Middleware;

use App\Models\Community;
use Closure;
use Illuminate\Support\Facades\DB;

class LinkInCommunity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $communityId = $this->getParameterId('community');
        if ($communityId == '') {
            return $next($request);
        }

        $linkId = $this->getParameterId('link');
        if ($linkId == '') {
            return $next($request);
        }

        $count = DB::table('item2link')
                   ->where('item_id', $communityId)
                   ->where('item_type', Community::class)
                   ->where('link_id', $linkId)
                   ->count();
        abort_if($count == 0, 404, 'No such link');

        return $next($request);
    }
}
