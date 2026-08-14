<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NasabahMiddleware
{
    /**
     * Ensure the request is from an authenticated nasabah (customer_accounts guard).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('nasabah')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
