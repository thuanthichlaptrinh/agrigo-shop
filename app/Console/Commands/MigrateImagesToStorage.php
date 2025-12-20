<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToStorage extends Command
{
    protected $signature = 'images:migrate 
                            {--dry-run : Chỉ hiển thị những gì sẽ được thực hiện, không thực sự di chuyển}
                            {--table= : Chỉ migrate một bảng cụ thể}';

    protected $description = 'Di chuyển hình ảnh từ public/uploads sang storage/app/public và cập nhật database';

    // Cấu hình các bảng và cột cần migrate
    protected array $tables = [
        'SanPham' => [
            'column' => 'HinhAnh',
            'old_prefix' => 'uploads/products/',
            'new_folder' => 'products',
        ],
        'HinhAnhSanPham' => [
            'column' => 'DuongDan',
            'old_prefix' => 'uploads/products/',
            'new_folder' => 'products',
        ],
        'LoaiSanPham' => [
            'column' => 'HinhAnh',
            'old_prefix' => 'uploads/categories/',
            'new_folder' => 'categories',
        ],
        'BaiViet' => [
            'column' => 'HinhAnh',
            'old_prefix' => 'uploads/articles/',
            'new_folder' => 'articles',
        ],
        'Banner' => [
            'column' => 'HinhAnh',
            'old_prefix' => 'uploads/banners/',
            'new_folder' => 'banners',
        ],
        'DanhGia' => [
            'column' => 'HinhAnh',
            'old_prefix' => 'uploads/reviews/',
            'new_folder' => 'reviews',
        ],
    ];

    protected int $movedCount = 0;
    protected int $updatedCount = 0;
    protected int $errorCount = 0;

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $specificTable = $this->option('table');

        if ($isDryRun) {
            $this->warn('🔍 CHẾ ĐỘ DRY-RUN: Không có thay đổi thực sự nào được thực hiện');
            $this->newLine();
        }

        // Đảm bảo symbolic link tồn tại
        if (!$isDryRun && !file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        $tablesToProcess = $specificTable 
            ? [$specificTable => $this->tables[$specificTable] ?? null]
            : $this->tables;

        foreach ($tablesToProcess as $table => $config) {
            if (!$config) {
                $this->error("Bảng '$table' không được cấu hình!");
                continue;
            }

            $this->migrateTable($table, $config, $isDryRun);
        }

        $this->newLine();
        $this->info('📊 KẾT QUẢ:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Files di chuyển', $this->movedCount],
                ['Records cập nhật', $this->updatedCount],
                ['Lỗi', $this->errorCount],
            ]
        );

        if (!$isDryRun && $this->movedCount > 0) {
            $this->newLine();
            $this->warn('⚠️  Nhớ xóa thư mục public/uploads sau khi kiểm tra mọi thứ hoạt động!');
        }

        return Command::SUCCESS;
    }

    protected function migrateTable(string $table, array $config, bool $isDryRun): void
    {
        $column = $config['column'];
        $oldPrefix = $config['old_prefix'];
        $newFolder = $config['new_folder'];

        $this->info("📁 Đang xử lý bảng: $table ($column)");

        // Tạo thư mục đích nếu chưa có
        if (!$isDryRun) {
            Storage::disk('public')->makeDirectory($newFolder);
        }

        // Lấy các records có đường dẫn cũ
        $records = DB::table($table)
            ->whereNotNull($column)
            ->where($column, 'like', $oldPrefix . '%')
            ->get(['ID', $column]);

        if ($records->isEmpty()) {
            $this->line("  ✓ Không có records cần migrate");
            return;
        }

        $this->line("  Tìm thấy {$records->count()} records cần migrate");

        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        foreach ($records as $record) {
            $oldPath = $record->$column;
            $filename = basename($oldPath);
            $newPath = "storage/$newFolder/$filename";

            $sourceFile = public_path($oldPath);
            $destFile = storage_path("app/public/$newFolder/$filename");

            if ($isDryRun) {
                $this->newLine();
                $this->line("  [DRY-RUN] ID={$record->ID}:");
                $this->line("    From: $oldPath");
                $this->line("    To:   $newPath");
                $bar->advance();
                continue;
            }

            try {
                // Di chuyển file nếu tồn tại
                if (File::exists($sourceFile)) {
                    if (!File::exists($destFile)) {
                        File::copy($sourceFile, $destFile);
                        $this->movedCount++;
                    }
                }

                // Cập nhật database
                DB::table($table)
                    ->where('ID', $record->ID)
                    ->update([$column => $newPath]);

                $this->updatedCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->newLine();
                $this->error("  Lỗi ID={$record->ID}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
