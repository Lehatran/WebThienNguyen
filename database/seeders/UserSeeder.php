<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;  // Thêm dòng này để import User model
use Illuminate\Support\Facades\Hash; // Import để sử dụng Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'ha1@gmail.com',
            'password' => Hash::make('123'),
            'birthday'=>'2003-12-12',
            'phoneNumber'=>'0123456789',
            'username'=>'ha1',
            'role'=>1
        ]);
    }
}
