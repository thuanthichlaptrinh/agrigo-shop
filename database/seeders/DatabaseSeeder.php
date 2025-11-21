<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chạy seeder tạo vai trò trước
        $this->call([
            VaiTroSeeder::class,
            AdminUserSeeder::class, // Tạo 3 tài khoản quản lý
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
