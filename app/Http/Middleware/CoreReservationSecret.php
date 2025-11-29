<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreReservationSecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // 1. Get the secret from the .env file via config
        $expectedCode = config('app.coreresv_secret');

        // 2. Get the code sent by the frontend header
        $actualCode = $request->header('X-CoreReservation-Code');

        // 3. Perform a secure comparison
        if (empty($actualCode) || !hash_equals($expectedCode, $actualCode)) {
            return response()->json([
                'message' => 'Invalid or missing core reservation Secret Code.',
            ], 401);
        }

        // If the code is correct, proceed to the next middleware or controller
        return $next($request);
    }
}
