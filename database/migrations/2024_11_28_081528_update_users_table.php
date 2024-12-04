<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột 'birthday' nếu chưa tồn tại
            if (!Schema::hasColumn('users', 'birthday')) {
                $table->string('birthday', 191)->nullable();
            }

            // Thêm cột 'phoneNumber' nếu chưa tồn tại
            if (!Schema::hasColumn('users', 'phoneNumber')) {
                $table->string('phoneNumber', 191)->nullable();
            }

            // Thêm cột 'userName' nếu chưa tồn tại
            if (!Schema::hasColumn('users', 'userName')) {
                $table->string('userName', 191)->nullable();
            }

            // Thêm cột 'role' nếu chưa tồn tại
            if (!Schema::hasColumn('users', 'role')) {
                $table->boolean('role')->default(0);
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa các cột đã thêm khi rollback
            if (Schema::hasColumn('users', 'birthday')) {
                $table->dropColumn('birthday');
            }
            if (Schema::hasColumn('users', 'phoneNumber')) {
                $table->dropColumn('phoneNumber');
            }
            if (Schema::hasColumn('users', 'userName')) {
                $table->dropColumn('userName');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};