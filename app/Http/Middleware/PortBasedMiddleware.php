<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

class PortBasedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        {
            $port = $request->getPort();

            // Kiểm tra cổng và chỉ cho phép route ở cổng phù hợp
            if ($port == 8000 && $request->is('api/auth/*')) {
                return $next($request); // Chỉ cho phép Auth trên cổng 8000
            }

            if ($port == 8001 && $request->is('api/products/*')) {
                return $next($request); // Chỉ cho phép Product trên cổng 8001
            }

            if ($port == 8002 && $request->is('api/category/*')) {
                return $next($request); // Chỉ cho phép Category trên cổng 8002
            }

            if ($port == 8003 && $request->is('api/address/*')) {
                return $next($request); // Chỉ cho phép Address trên cổng 8003
            }

            if ($port == 8004 && $request->is('api/admin/*')) {
                return $next($request); // Chỉ cho phép Auth trên cổng 8004
            }
            if ($port == 8005 && $request->is('api/user/*')) {
                return $next($request); // Chỉ cho phép Auth trên cổng 8005
            }

            return response()->json(['error' => 'Invalid port for this route'], 400);
    
        }
    }
}
