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
        Schema::create('BaiViet', function (Blueprint $table) {
            $table->id('ID');
            $table->string('TieuDe');
            $table->string('Slug')->unique();
            $table->text('NoiDung');
            $table->text('MoTaNgan')->nullable();
            $table->string('HinhAnh')->nullable();
            $table->integer('IDNguoiDung');
            $table->integer('IDDanhMuc')->nullable();
            $table->boolean('TrangThai')->default(true); // 1: Published, 0: Draft
            $table->integer('LuotXem')->default(0);
            $table->timestamp('NgayTao')->useCurrent();
            $table->timestamp('NgayCapNhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('IDNguoiDung')->references('ID')->on('NguoiDung')->onDelete('cascade');
            $table->foreign('IDDanhMuc')->references('ID')->on('DanhMuc')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BaiViet');
    }
};
