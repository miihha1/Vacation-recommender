<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPortalVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && !$request->is('assets/*') && !$request->is('up')) {
            $visitorHash = hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));

            Visit::create([
                'visitor_hash' => $visitorHash,
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'visited_at' => now(),
            ]);
        }

        return $next($request);
    }
}
