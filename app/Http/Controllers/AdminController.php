<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{// Lấy danh sách user
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
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
