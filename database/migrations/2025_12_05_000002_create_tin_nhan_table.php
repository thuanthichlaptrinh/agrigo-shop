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
        Schema::create('TinNhan', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('IDCuocHoiThoai');
            $table->integer('IDNguoiGui')->nullable()->unsigned(); // null for bot/system messages
            $table->enum('LoaiNguoiGui', ['NguoiDung', 'Admin', 'Bot', 'HeThong'])->default('NguoiDung');
            $table->text('NoiDung');
            $table->string('HinhAnh', 1000)->nullable();
            $table->boolean('DaXem')->default(false);
            $table->timestamp('ThoiGianGui')->useCurrent();
            $table->timestamps();

            $table->foreign('IDCuocHoiThoai')->references('ID')->on('CuocHoiThoai')->onDelete('cascade');
            // Không dùng foreign key cho IDNguoiGui do cấu trúc bảng nguoi_dung dùng INT
            
            $table->index('IDCuocHoiThoai');
            $table->index('IDNguoiGui');
            $table->index('LoaiNguoiGui');
            $table->index('DaXem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TinNhan');
    }
};
