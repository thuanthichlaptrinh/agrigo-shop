<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Tạo 3 tài khoản: Quản trị viên, Quản lý sản phẩm, Quản lý đơn hàng
     */
    public function run(): void
    {
        // Lấy các vai trò
        $adminRole = VaiTro::where('TenVaiTro', VaiTro::ADMIN)->first();
        $productManagerRole = VaiTro::where('TenVaiTro', VaiTro::PRODUCT_MANAGER)->first();
        $orderManagerRole = VaiTro::where('TenVaiTro', VaiTro::ORDER_MANAGER)->first();

        // Kiểm tra nếu chưa có vai trò thì chạy VaiTroSeeder trước
        if (!$adminRole || !$productManagerRole || !$orderManagerRole) {
            $this->command->error('Chưa có vai trò! Vui lòng chạy VaiTroSeeder trước.');
            $this->call(VaiTroSeeder::class);
            
            // Load lại roles
            $adminRole = VaiTro::where('TenVaiTro', VaiTro::ADMIN)->first();
            $productManagerRole = VaiTro::where('TenVaiTro', VaiTro::PRODUCT_MANAGER)->first();
            $orderManagerRole = VaiTro::where('TenVaiTro', VaiTro::ORDER_MANAGER)->first();
        }

        $users = [
            [
                'TenNguoiDung' => 'Quản Trị Viên',
                'Email' => 'admin@organic.vn',
                'SDT' => '0901234567',
                'MatKhau' => Hash::make('admin123'),
                'DiaChi' => 'Hồ Chí Minh',
                'GioiTinh' => 'Nam',
                'TrangThai' => 1,
                'IDVaiTro' => $adminRole->ID
            ],
            [
                'TenNguoiDung' => 'Quản Lý Sản Phẩm',
                'Email' => 'product@organic.vn',
                'SDT' => '0902234567',
                'MatKhau' => Hash::make('product123'),
                'DiaChi' => 'Hồ Chí Minh',
                'GioiTinh' => 'Nam',
                'TrangThai' => 1,
                'IDVaiTro' => $productManagerRole->ID
            ],
            [
                'TenNguoiDung' => 'Quản Lý Đơn Hàng',
                'Email' => 'order@organic.vn',
                'SDT' => '0903234567',
                'MatKhau' => Hash::make('order123'),
                'DiaChi' => 'Hồ Chí Minh',
                'GioiTinh' => 'Nữ',
                'TrangThai' => 1,
                'IDVaiTro' => $orderManagerRole->ID
            ]
        ];

        foreach ($users as $userData) {
            NguoiDung::updateOrCreate(
                ['Email' => $userData['Email']],
                $userData
            );
        }

        $this->command->info('✅ Đã tạo 3 tài khoản quản lý:');
        $this->command->info('   1. Admin: admin@organic.vn / admin123');
        $this->command->info('   2. Quản lý sản phẩm: product@organic.vn / product123');
        $this->command->info('   3. Quản lý đơn hàng: order@organic.vn / order123');
    }
}
