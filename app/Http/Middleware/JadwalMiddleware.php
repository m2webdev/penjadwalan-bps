<?php

namespace App\Http\Middleware;

use App\Services\PenjadwalanService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JadwalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (env('AP_ENV') == 'local' || env('APP_ENV') == 'production') {
            $service = app(PenjadwalanService::class);
            $service->setNextSchedule();
        }
        return $next($request);
    }
}
