<?php

namespace App\Http\Middleware;

use Closure;

class UserCanAccessProject
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
        $count = auth()->user()->projects()->where('project_id', 1)->count();
        error_log($count);
        return $next($request);
    }
}
