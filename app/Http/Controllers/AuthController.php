<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct()
{

    $this->middleware('auth:api', ['except' => ['login', 'showLoginForm']]);
}

public function login(Request $request)
{
    try {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized',
                'credentials' => $credentials // Debug thông tin credentials
            ], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTrace()
        ], 500);
    }
}


// public function login(Request $request)
// {
//     $credentials = $request->only('email', 'password');

//     if ($request->expectsJson() || $request->is('api/*')) {
//         // // Xử lý đăng nhập cho API, xác thực người dùng
//         if (!$token = auth('api')->attempt($credentials)) {
//             return response()->json(['error' => 'Unauthorized', 'credentials' => $credentials], 401);
//         }

//         // Trả về token cùng với thời gian sống (expiration time)
//         return response()->json([
//             'access_token' => $token,
//             'token_type' => 'bearer',
//             'expires_in' => auth('api')->factory()->getTTL() * 60 // Giả định TTL được cấu hình là 60 phút
//         ]);
//     } else {
//         // Xử lý đăng nhập cho Web
//         if (Auth::attempt($credentials)) {
//             return redirect()->intended('them-san-pham'); // Chuyển hướng đến trang "thêm sản phẩm"
//         } else {
//             return redirect()->back()->withErrors(['email' => 'Thông tin đăng nhập không hợp lệ']);
//         }
//     }
// }

public function profile(Request $request)
{

    if ($request->expectsJson()) {

        if (!auth('api')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth('api')->user();
        return response()->json($user);
    }

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    return view('profile', compact('user'));
}


public function logout(Request $request)
{
    if ($request->expectsJson() || $request->is('api/*')) {
      //  auth('api')->logout();
        auth('api')->invalidate(true); // Vô hiệu hóa token hiện tại
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    Auth::logout();
    return redirect()->route('login')->with('message', 'Successfully logged out');
}

    public function refresh(Request $request)
     {

         // Kiểm tra xem có token không
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Kiểm tra xem token có hợp lệ không
    if (!auth('api')->setToken($token)->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Cấp phát token mới mà không cần vô hiệu hóa token cũ
    $newToken = auth('api')->refresh();

    return $this->respondWithToken($newToken);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // Sử dụng 'api' guard
        ]);
    }
    public function showLoginForm()
    {
        // Nếu yêu cầu không phải JSON, trả về view cho login
        if (!request()->expectsJson()) {
            return view('login'); // Đảm bảo view này tồn tại
        }
        return response()->json(['message' => 'Show login form']);
    }


}
