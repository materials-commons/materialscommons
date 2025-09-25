<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

// DetectDeviceType detects the device type and sets the device type in the request. It does
// this by looking at the user agent.
class DetectDeviceType
{
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent');
        $deviceType = $this->determineDeviceType($userAgent);
        $request->merge(['deviceType' => $deviceType]);
        return $next($request);
    }

    private function determineDeviceType($userAgent): string
    {
        if (is_null($userAgent)) {
            return "desktop";
        }

        if (is_array($userAgent)) {
            return "desktop";
        }

        $userAgent = strtolower($userAgent);

        if (Str::contains($userAgent, ['iphone', 'android'])) {
            return "phone";
        }

        return "desktop";
    }
}
