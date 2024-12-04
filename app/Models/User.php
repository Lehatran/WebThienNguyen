<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Các thuộc tính được phép gán
    protected $fillable = [
        'name',
        'email',
        'birthday',
        'phoneNumber',
        'userName',
        'password',
        'role'
    ];

    // Các thuộc tính ẩn
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Lấy thông tin định danh để lưu trong JWT.
     * Ví dụ: ID của user
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Lấy các thông tin bổ sung để thêm vào JWT.
     * Trả về một mảng hoặc null.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
