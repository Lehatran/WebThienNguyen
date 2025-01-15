<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:api', ['except' => ['login', 'showLoginForm', 'register', 'showRegisterForm','showChangePasswordForm']]);
}
   // Hiển thị form đăng ký
   public function showRegisterForm()
   {
       return view('register');
   }
  // Hiển thị form đăng nhập
  public function showLoginForm()
  {
      return view('login'); // Trả về view đăng nhập
  }


    // Xử lý đăng ký
    public function register(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'birthday' => 'required|date',
                'phoneNumber' => 'required|string|max:15|unique:users',
                'userName' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Tạo user mới
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'birthday' => $validatedData['birthday'],
                'phoneNumber' => $validatedData['phoneNumber'],
                'userName' => $validatedData['userName'],
                'password' => Hash::make($validatedData['password']),
                'role' => 0, // Giá trị mặc định
            ]);

            // Phản hồi tùy theo yêu cầu
            if ($request->expectsJson()) {
                // Nếu là API request
                return response()->json([
                    'message' => 'Tài khoản đã được đăng ký thành công',
                    'data' => $user,
                ], 201);
            }

            // Nếu là yêu cầu từ giao diện web
            return redirect()->route('register.form')->with('success', 'Tài khoản đã được đăng ký thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Lỗi xác thực
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Lỗi xác thực',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->route('register.form')
                             ->withErrors($e->validator)
                             ->withInput();
        } catch (\Exception $e) {
            // Lỗi khác
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại.',
                ], 500);
            }

            return redirect()->route('register.form')
                             ->with('error', 'Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại.')
                             ->withInput();
        }
    }




   // Xử lý đăng nhập
public function login(Request $request)
{
    try {
        // Lấy thông tin đăng nhập từ request
        $credentials = $request->only('email', 'password');

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {
            // Lấy user đã đăng nhập
            $user = Auth::user();

            // Tạo token JWT cho user
            $token = JWTAuth::fromUser($user);

            // Trả về token nếu là yêu cầu từ API
            if ($request->expectsJson()) {
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                ])->cookie('token', $token, 60, '/', '.localhost', false, true);  // Lưu vào cookie với domain chung;
            }

            // Nếu là yêu cầu từ web (trang web), sử dụng session hoặc redirect
            Auth::login($user);

            // Thêm thông báo thành công
            return redirect()->route('login.form')->with('success', 'Đăng nhập thành công!');
        } else {
            // Trả về lỗi nếu thông tin đăng nhập không chính xác
            return redirect()->route('login.form')->with('error', 'Thông tin đăng nhập không chính xác.');
        }
    } catch (\Exception $e) {
        // Trả về lỗi nếu có vấn đề xảy ra
        return redirect()->route('login.form')->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập.');
    }
}

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
    \Log::info('Logout request received', [
        'headers' => $request->headers->all(),
        'csrf_token' => $request->header('X-CSRF-TOKEN'),
    ]);

    if ($request->expectsJson() || $request->is('api/*')) {
        try {
            auth('api')->invalidate(true);
            auth('api')->logout();
            return response()->json(['message' => 'Successfully logged out from API'], 200);
        } catch (\Exception $e) {
            \Log::error('API logout error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Logout failed'], 500);
        }
    }
    \Log::info('CSRF Token:', ['token' => $request->header('X-CSRF-TOKEN')]);
    \Log::info('Session ID:', ['session_id' => $request->session()->getId()]);
    \Log::info('Session Content:', $request->session()->all());
    \Log::info('Authorization Header:', ['token' => $request->header('Authorization')]);

    // Xử lý logout cho web
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message' => 'Successfully logged out from web'], 200);
}



// public function logout(Request $request)
// {
//     if ($request->expectsJson() || $request->is('api/*')) {
//       //  auth('api')->logout();
//         auth('api')->invalidate(true); // Vô hiệu hóa token hiện tại
//         auth('api')->logout();
//         return response()->json(['message' => 'Successfully logged out']);
//     }

//     Auth::logout();
//     return redirect()->route('login')->with('message', 'Successfully logged out');
// }

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

}