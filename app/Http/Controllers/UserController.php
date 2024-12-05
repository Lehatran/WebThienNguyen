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

     // Cập nhật thông tin cá nhân
     public function update(Request $request)
     {
         $user = Auth::user();

         // Validate dữ liệu
         $validated = $request->validate([
             'name' => 'string|max:255',
             'email' => 'email|unique:users,email,' . $user->id,
             'birthday' => 'date|nullable',
             'phoneNumber' => 'string|max:10',
         ]);

         // Cập nhật thông tin
         $user->update($validated);

         return response()->json(['message' => 'Profile updated successfully', 'data' => $user], 200);
     }

     // Đổi mật khẩu
     public function changePassword(Request $request)
     {
         $user = Auth::user();

         // Validate mật khẩu
         $validated = $request->validate([
             'current_password' => 'required',
             'new_password' => 'required|min:8|confirmed',
         ]);

         // Kiểm tra mật khẩu hiện tại
         if (!Hash::check($validated['current_password'], $user->password)) {
             return response()->json(['message' => 'Current password is incorrect'], 400);
         }

         // Cập nhật mật khẩu mới
         $user->password = Hash::make($validated['new_password']);
         $user->save();

         return response()->json(['message' => 'Password changed successfully'], 200);
     }

}