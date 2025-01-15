<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller

{
      // Lấy danh sách user (chỉ role = 0) và tìm kiếm theo tên hoặc email
      public function index(Request $request)
      {
         // Kiểm tra nếu người dùng hiện tại không phải admin (role != 1)
    if (auth()->user()->role != 1) {
        if ($request->wantsJson()) {
            // Trả về JSON thông báo lỗi nếu là API
            return response()->json(['error' => 'Unauthorized. Chỉ admin mới có thể truy cập.'], 403);
        }

        // Điều hướng đến trang khác hoặc trả về lỗi nếu là giao diện web
        return redirect()->back()->withErrors('Bạn k có quyền vào trang này.');
    }


          // Lấy từ khóa tìm kiếm từ query string
          $search = $request->input('search');

          // Lấy danh sách user có role = 0, tìm kiếm theo tên hoặc email
          $users = User::where('role', 0)
              ->when($search, function ($query, $search) {
                  return $query->where(function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
              })
              ->get();

          if ($request->wantsJson()) {
              // Trả về JSON cho API
              return response()->json(['data' => $users], 200);
          }

          // Trả về view cho web
          return view('admin.list-user', ['users' => $users, 'search' => $search]);
      }

    // Xem thông tin chi tiết user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['data' => $user], 200);
    }
}
