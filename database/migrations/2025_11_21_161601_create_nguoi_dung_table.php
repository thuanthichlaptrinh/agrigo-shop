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
        Schema::create('NguoiDung', function (Blueprint $table) {
            $table->id('ID');
            $table->string('TenNguoiDung', 255);
            $table->string('Email', 255)->unique();
            $table->string('SDT', 15)->nullable();
            $table->string('MatKhau', 255);
            $table->string('DiaChi', 500)->nullable();
            $table->date('NgaySinh')->nullable();
            $table->enum('GioiTinh', ['Nam', 'Nữ', 'Khác'])->nullable();
            $table->string('HinhAnh', 1000)->nullable();
            $table->tinyInteger('TrangThai')->default(1);
            $table->foreignId('IDVaiTro')->constrained('VaiTro', 'ID');
            $table->timestamp('NgayTao')->useCurrent();
            $table->timestamp('NgayCapNhat')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('Email');
            $table->index('TrangThai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('NguoiDung');
    }
};
