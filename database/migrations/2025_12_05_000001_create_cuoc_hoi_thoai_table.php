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
        Schema::create('CuocHoiThoai', function (Blueprint $table) {
            $table->id('ID');
            $table->integer('IDNguoiDung')->nullable()->unsigned();
            $table->string('SessionID', 100)->nullable(); // For guest users
            $table->string('TieuDe', 255)->nullable();
            $table->enum('TrangThai', ['Mo', 'Dong', 'Cho'])->default('Mo');
            $table->integer('IDAdmin')->nullable()->unsigned(); // Assigned admin
            $table->timestamp('LanHoatDongCuoi')->nullable();
            $table->timestamps();

            // Không dùng foreign key do cấu trúc bảng nguoi_dung dùng INT thay vì BIGINT
            $table->index('IDNguoiDung');
            $table->index('SessionID');
            $table->index('TrangThai');
            $table->index('IDAdmin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CuocHoiThoai');
    }
};
