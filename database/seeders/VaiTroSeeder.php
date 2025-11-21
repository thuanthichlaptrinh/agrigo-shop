<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VaiTro;

class VaiTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'TenVaiTro' => VaiTro::ADMIN,
                'MoTa' => 'Quản trị viên - Có toàn quyền quản lý hệ thống'
            ],
            [
                'TenVaiTro' => VaiTro::USER,
                'MoTa' => 'Người dùng - Khách hàng mua hàng'
            ],
            [
                'TenVaiTro' => VaiTro::PRODUCT_MANAGER,
                'MoTa' => 'Quản lý sản phẩm - Quản lý danh mục, sản phẩm, nhà cung cấp'
            ],
            [
                'TenVaiTro' => VaiTro::ORDER_MANAGER,
                'MoTa' => 'Quản lý đơn hàng - Xử lý đơn hàng, giao hàng'
            ]
        ];

        foreach ($roles as $role) {
            VaiTro::updateOrCreate(
                ['TenVaiTro' => $role['TenVaiTro']],
                ['MoTa' => $role['MoTa']]
            );
        }
    }
}
