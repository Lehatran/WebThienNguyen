<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Xác thực token từ header Authorization
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            // Nếu có lỗi khi xác thực token
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Thêm thông tin user vào request nếu xác thực thành công
        $request->attributes->add(['user' => $user]);

        return $next($request);
    }
}