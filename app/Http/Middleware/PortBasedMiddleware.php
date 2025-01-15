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
    $port = $request->getPort();  // Lấy port của yêu cầu
    $path = $request->path();     // Lấy path (route) của yêu cầu

    \Log::info("Incoming request", [
        'port' => $port,
        'path' => $path,
    ]);

    // Kiểm tra lại giá trị port và route
\Log::info('Port: ' . $port);
\Log::info('Path: ' . $path);

    // Kiểm tra cổng và route tương ứng
    $validRoutes = [
        8000 => ['login', 'register','logout', 'auth/*', 'user/*', 'admin/*', 'api/auth/*', 'api/user/*', 'api/admin/*'], // Các route cho cổng 8000
        8001 => ['products/*', 'login'],             // Các route cho cổng 8001
        8002 => ['category/*'],             // Các route cho cổng 8002
        8003 => ['address/*'],              // Các route cho cổng 8003
    ];

    // Lặp qua cổng và các route hợp lệ
    foreach ($validRoutes as $validPort => $routes) {
        foreach ($routes as $route) {
            // Kiểm tra nếu cổng và route khớp
            if ($port == $validPort && $request->is($route)) {
                return $next($request); // Cho phép tiếp tục yêu cầu
            }
        }
    }

    // Trả về lỗi nếu không hợp lệ
    return response()->json(['error' => 'Invalid port for this route'], 400);
}
}

//             // Kiểm tra cổng và chỉ cho phép route ở cổng phù hợp
//             if ($port == 8000 && $request->is('api/auth/*')) {
//                 return $next($request); // Chỉ cho phép Auth trên cổng 8000
//             }

//             if ($port == 8001 && $request->is('api/products/*')) {
//                 return $next($request); // Chỉ cho phép Product trên cổng 8001
//             }

//             if ($port == 8002 && $request->is('api/category/*')) {
//                 return $next($request); // Chỉ cho phép Category trên cổng 8002
//             }

//             if ($port == 8003 && $request->is('api/address/*')) {
//                 return $next($request); // Chỉ cho phép Address trên cổng 8003
//             }

//             if ($port == 8004 && $request->is('api/admin/*')) {
//                 return $next($request); // Chỉ cho phép Auth trên cổng 8004
//             }
//             if ($port == 8005 && $request->is('api/user/*')) {
//                 return $next($request); // Chỉ cho phép Auth trên cổng 8005
//             }

//             return response()->json(['error' => 'Invalid port for this route'], 400);

//         }
//     }
//     }