<?php

namespace App\Http\Middleware;

use App\Models\Community;
use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Support\Facades\DB;

class FileInCommunity
{
    use GetRequestParameterId;

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

        $fileId = $this->getParameterId('file');
        if ($fileId == '') {
            return $next($request);
        }

        $count = DB::table('item2file')
                   ->where('item_id', $communityId)
                   ->where('item_type', Community::class)
                   ->where('file_id', $fileId)
                   ->count();
        abort_if($count == 0, 404, 'No such file');

        return $next($request);
    }
}
