<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm loại 'jwt' vào ENUM Loai
        DB::statement("ALTER TABLE Token MODIFY COLUMN Loai ENUM('reset_password','verify_email','remember_me','jwt') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các token JWT trước khi rollback
        DB::table('Token')->where('Loai', 'jwt')->delete();
        
        // Rollback về ENUM cũ
        DB::statement("ALTER TABLE Token MODIFY COLUMN Loai ENUM('reset_password','verify_email','remember_me') NOT NULL");
    }
};
