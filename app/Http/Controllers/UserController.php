<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{

     // Xem thông tin cá nhân
     public function profile()
     {
         $user = Auth::user();
         return response()->json(['data' => $user], 200);
     }

     public function showChangePasswordForm()
     {
        $user = Auth::user();
        return view('change-password', compact('user'));
       //  return view('change-password'); // trả về view form đổi mật khẩu
     }


      // Hiển thị form chỉnh sửa hồ sơ
    public function showEditProfileForm()
    {
        $user = Auth::user();
        return view('edit-profile', compact('user')); // tạo view edit-profile
    }

    // Cập nhật thông tin cá nhân
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phoneNumber' => 'required|string|size:10', // Thêm ràng buộc required và size
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'phoneNumber.required' => 'Số điện thoại không được để trống.',
            'phoneNumber.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phoneNumber.size' => 'Số điện thoại phải có đúng 10 ký tự.',
        ]);

        // Cập nhật thông tin
        $user->update($validated);

        // Kiểm tra yêu cầu từ API hay giao diện web
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cập nhật thông tin thành công!', 'data' => $user], 200);
        }

        return redirect()->route('edit.profile.form')->with('success', 'Cập nhật thông tin thành công!');
    }





     public function changePassword(Request $request)
     {
         $user = Auth::user();

         // Validate mật khẩu
    $validated = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed|different:current_password',
    ], [
        'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
        'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại.',
    ]);

         // Kiểm tra mật khẩu hiện tại
         if (!Hash::check($validated['current_password'], $user->password)) {
             if ($request->wantsJson()) {
                 // Trả về JSON cho API
                 return response()->json(['message' => 'Mật khẩu hiện tại không đúng.'], 400);
             } else {
                 // Trả về lỗi cho giao diện web
                 return redirect()->route('change.password.form')->with('error', 'Mật khẩu hiện tại không đúng.');
             }
         }
        // Kiểm tra mật khẩu mới không trùng với mật khẩu cũ (bổ sung)
        if (Hash::check($validated['new_password'], $user->password)) {
            return redirect()->route('change.password.form')->with('error', 'Mật khẩu mới không được trùng với mật khẩu cũ.');
        }

         // Cập nhật mật khẩu mới
         $user->password = Hash::make($validated['new_password']);
         $user->save();

         if ($request->wantsJson()) {
             // Trả về JSON cho API
             return response()->json(['message' => 'Đổi mật khẩu thành công!'], 200);
         } else {
             return redirect()->route('change.password.form')->with('success', 'Đổi mật khẩu thành công!');
         }
     }

}